<?php
/**
 * ProjectName : スマートギフトサイト
 * Subsystem   : ギフトカードを利用した商品交換モールサイト
 */
require_once(WT_ROOT_DIR . 'webapp_ssl/modules/Order/common/ShohinInformation.php');
require_once(WT_ROOT_DIR . 'webapp_ssl/modules/Order/common/OrderElement.php');
require_once(WT_ROOT_DIR . 'webapp_ssl/modules/Order/common/Otodokesaki.php');
require_once(WT_ROOT_DIR . 'webapp_ssl/modules/Order/common/Creditcard.php');
require_once(WT_ROOT_DIR . 'webapp_ssl/modules/Order/common/GiftService.php');
require_once(WT_ROOT_DIR . 'webapp_ssl/modules/Order/querys/OrderCommonQuerySel.class.php');
require_once(WT_ROOT_DIR . 'webapp_ssl/modules/Order/querys/OrderCommonQueryIUD.class.php');
require_once(WT_ROOT_DIR . 'util/Common/const/DbConst.class.php');

/**
 * 注文処理クラス
 *
 * @author Yuki Tanaka, Saito
 */
class OrderCommonClass
{
    /**
     * @var WtUser
     */
    protected $_user;

    /**
     * @var object[]
     */
    protected $_orderObjects = array(
        'Chumonsha'   => null, // 注文者
        'Okurinushi'  => null, // 送り主
        'Creditcard'  => null, // クレジットカード
        'GiftService' => null, // サービス(のし・包装紙・挨拶状)
    );

    /**
     * @var array
     */
    protected $giftcardResults = array(
        'success.use'      => array(),
        'failure.use'      => array(),
        'httperr.use'      => array(),
        'success.rollback' => array(),
        'httperr.rollback' => array(),
        'failure.rollback' => array(),
        'success.cancel'   => array(),
        'httperr.cancel'   => array(),
        'failure.cancel'   => array(),
    );

    /**
     * @var SgGiftcardInfo
     */
    protected $gcInfo;

    /**
     * @var array
     */
    protected $alertErrors = array();

    /**
     * 受注日
     * @var string
     */
    protected $_orderDate = '';

    /**
     * @var string[] 受注番号リスト
     */
    protected $_orderNoList = array();

    /**
     * インサート用のデータ
     * @var array
     */
    protected $_insertData = array();

    /**
     * @var array
     */
    protected $pointUseData = array();

    /**
     * コンストラクタ
     *
     * @param WtUser $user
     */
    public function __construct($user)
    {
        if (!($user instanceof WtUser)) {
            throw new Exception('コンストラクタの引数がWtUserではありません');
        }
        $this->gcInfo = $user->getGiftcardInfo();
        $this->_user = $user;
    }

    /**
     * オブジェクト取得
     *
     * @return AbsOrderElement
     */
    protected function getOrderObj($className)
    {
        if (!array_key_exists($className, $this->_orderObjects)) {
            throw new Exception("不正なクラス指定: {$className}");
        }
        if (!$this->_orderObjects[$className]
            || !($this->_orderObjects[$className] instanceof $className)
        ) {
            $this->_orderObjects[$className] = new $className;
        }
        $this->setSessionToData($this->_orderObjects[$className]);
        return $this->_orderObjects[$className];
    }

    /**
     * セッションのデータをオブジェクトのデータに変換
     *
     * @param AbsOrderElement $orderElementObj
     *
     * @return void
     */
    public function setSessionToData($orderElementObj)
    {
        $sessName = $orderElementObj->getSessionName();
        $sessData = $this->_user->getAttribute($sessName);
        if (!empty($sessData)) {
            foreach ($sessData as $k => $v) {
                $orderElementObj->setObjectDataFromKey($k, $v);
            }
        }
    }

    /**
     * 注文要素オブジェクトのデータを対応するセッションに格納する
     *
     * @param AbsOrderElement $orderElementObj 注文要素オブジェクト
     *
     * @return void
     */
    private function _setOrderElementDataToSession($orderElementObj)
    {
        $sessName = $orderElementObj->getSessionName();
        $sessData = $orderElementObj->getObjectData(true);
        $this->_user->setAttribute($sessName, $sessData);
        $this->_user->store();
    }

    //**************************************************************************
    // 買い物カゴ内の商品情報
    //**************************************************************************

    /**
     * 商品情報を保存するセッション名
     */
    const SESSNAME_SHOHIN_LIST = 'shohin_info_list';

    /**
     * 商品情報(買い物かご)の取得
     *
     * @return array
     */
    public function getShohinInfoList()
    {
        $shohinInfoList = $this->_user->getAttribute(self::SESSNAME_SHOHIN_LIST);
        return $this->optimizeShohinInfoList($shohinInfoList);
    }

    /**
     * 商品情報(買い物かご)の保存
     *
     * @return void
     */
    public function saveShohinInfoList($shohinInfoList)
    {
        $shohinInfoList = $this->optimizeShohinInfoList($shohinInfoList);
        $this->_user->setAttribute(self::SESSNAME_SHOHIN_LIST, $shohinInfoList);
        $this->_user->store();
    }

    /**
     * 商品情報(買い物かご)の正規化
     * ※未設定のパラメータがあれば初期値を設定する
     *
     * @param array $shohinInfoList
     *
     * @return array
     * <pre>
     * Array(
     *     商品番号 => Array(
     *         ShohinInformation::SHOHIN_NO                  => 商品番号,
     *         ShohinInformation::SHOHIN_CODE                => 商品コード,
     *         ShohinInformation::BRAND_NAME                 => ブランド名,
     *         ShohinInformation::SHOHIN_NAME                => 商品名,
     *         ShohinInformation::SHOHIN_NAME_KANA           => 商品名カナ,
     *         ShohinInformation::HAISO_KEITAI               => 配送形態,
     *         ShohinInformation::HAISO_MOTO_SHIKIBETSU_CODE => 配送元識別コード,
     *         ShohinInformation::KAKAKU_ZEINUKI             => 商品価格税抜き,
     *         ShohinInformation::KISETSU_SHOHIN_FLG         => 季節商品フラグ,
     *         ShohinInformation::HYOJUN_NOKI                => 標準納期,
     *         ShohinInformation::GENTEI_SURYO               => 限定数量,
     *         ShohinInformation::NOKORI_SURYO               => 残り数量,
     *         ShohinInformation::KANOU_SURYO                => 購入可能数量,
     *         ShohinInformation::KISETSU_DATE               => 季節配送期間開始日,
     *         ShohinInformation::HOSO_FLG                   => 包装フラグ,
     *         ShohinInformation::NOSHI_FLG                  => のしフラグ,
     *         ShohinInformation::GREETING_CARD_FLG          => 挨拶状フラグ,
     *         ShohinInformation::HAISOSITEI_NOLIMIT_FLG     => 配送指定制限除外フラグ,
     *         ShohinInformation::TOKUSHU_FLG                => 特殊商品フラグ,
     *         ShohinInformation::KIKAN_GENTEI_HAISO_KEITAI  => 期間限定配送携帯,
     *         ShohinInformation::KIKAN_GENTEI_FLG           => 期間限定フラグ,
     *         ShohinInformation::DISP_HAISO_KEITAI          => 表示配送形態,
     *         ShohinInformation::CANNOT_DELIVERY_DATE       => 配送指定不可日,
     *         ShohinInformation::CANNOT_DELIVERY_SENTENCE   => 配送指定不可文言,
     *         ShohinInformation::HANBAI_SDATE               => 販売期間開始日,
     *         ShohinInformation::HANBAI_EDATE               => 販売期間終了日,
     *         ShohinInformation::TAXFREE_FLG                => 消費税無料フラグ,
     *         ShohinInformation::OTODOKE_KANO_DATE          => お届け可能日,
     *         ShohinInformation::OTODOKE_KIBO_DATE          => お届け希望日 ※自宅用でのみ使用,
     *         ShohinInformation::KONYU_SURYO                => 購入数量     ※自宅用でのみ使用,
     *         ShohinInformation::CGWEBLIB_STOCKOUT_FLG      => CGWEBLIB.MISHOHNPの欠品状態(20180123追加)
     *     ),
     *     商品番号 => Array(
     *         複数商品が指定されている場合は要素が増加
     *     ),
     * )
     * </pre>
     */
    protected function optimizeShohinInfoList($shohinInfoList)
    {
        $shohinInfoListTemp = array();
        if (is_array($shohinInfoList) && !empty($shohinInfoList)) {
            foreach ($shohinInfoList as $shohinInfo) {
                if (!isset($shohinInfo[ShohinInformation::SHOHIN_NO])) {
                    throw new Exception('');
                }
                $shohinNo = $shohinInfo[ShohinInformation::SHOHIN_NO];
                $shohinObj = new ShohinInformation($shohinInfo);
                $shohinInfoListTemp[$shohinNo] = $shohinObj->getObjectData();
            }
        }
        return $shohinInfoListTemp;
    }

    //**************************************************************************
    // ギフトカード情報
    //**************************************************************************

    /**
     * ギフトカード残高の確認
     *
     * @return array $errorCodeArray エラーコード配列
     */
    public function checkIntegrityOfUsePoint()
    {
        $gcInfo = $this->gcInfo;
        $prevUsablePoint = $gcInfo->usablePoints;
        $currUsablePoint = $gcInfo->sync()->usablePoints; // API再同期
        $this->_user->setGiftcardInfo($gcInfo);
        $errorCodeArray = [];
        if ($prevUsablePoint !== $currUsablePoint) {
            // ギフトカード残高に変更があった（合計値が等しければ個別の内容変更は許容する）
            // $errorCodeArray[] = 'ギフトカード残高情報が一致しません';
            $errorCodeArray[] = 'サイト接続に関してのエラーが発生しました。お申し込み手続きの途中の方は改めて確認の上お進みください。';   // ZSI-169 複数回クリックのときの制御
        }
        return $errorCodeArray;
    }

    /**
     * ギフトカードの減算処理を行う
     *
     * @return bool
     */
    public function executeUsePoint()
    {
        $usePoint = $this->getTotalOrderPoint();
        $cardList = $this->gcInfo->getCardList();
        $pointUseList = [];
        foreach ($cardList as $gcDetail) {
            if (!$gcDetail->usable || !$gcDetail->point) {
                continue;
            }
            if ($usePoint <= $gcDetail->point) {
                if ($usePoint) {
                    $pointUseList[$gcDetail->cardNo] = $usePoint;
                }
                break;
            }
            $pointUseList[$gcDetail->cardNo] = $gcDetail->point;
            $usePoint -= $gcDetail->point;
        }
        $slipNoList = []; // DB保存用に伝票番号を一時保管
        WtApp::getLogger()->info('>>>> ギフトカード減算処理開始 >>>>');
        foreach ($pointUseList as $cardNo => $point) {
            $mt = microtime(true);
            $result = null;
            if ($this->gcInfo->changePoint($cardNo, -$point, $result)) {
                $this->giftcardResults['success.use'][$cardNo] = $result;
                $slipNoList[$cardNo] = $result->req['sc'];
                WtApp::getLogger()->info(implode(' ', [
                    'ポイント数の更新成功:',
                    sprintf('伝票番号=%s,', $result->req['sc']),
                    sprintf('カード番号=%s,', $cardNo),
                    '取引区分=減算(1003),',
                    sprintf('Δ=%s', -$point),
                    sprintf('[%.04fsec]', microtime(true) - $mt),
                ]));
                continue;
            }
            if ($result->res instanceof \ServerResponseException) {
                $this->giftcardResults['httperr.use'][$cardNo] = $result;
                // ServiceUnavailableException も含まれる
                $e = $result->res;
                WtApp::getLogger()->error(implode(' ', [
                    'ポイント数の更新失敗:',
                    sprintf('伝票番号=%s,', $result->req['sc'] ?? ''),
                    sprintf('カード番号=%s,', $cardNo),
                    '取引区分=減算(1003),',
                    sprintf('Δ=%s', -$point),
                    sprintf('(%s)', get_class($e)),
                    $e->getMessage(),
                    sprintf('[%.04fsec]', microtime(true) - $mt),
                ]));
                // 通信エラー >>> 障害取消
                $mt = microtime(true);
                $result = null;
                if ($this->gcInfo->rollback($result->req['sc'], $result)) {
                    $this->giftcardResults['success.rollback'][$cardNo] = $result;
                    WtApp::getLogger()->info(implode(' ', [
                        '障害取消(ポイント数の更新)成功:',
                        sprintf('伝票番号=%s,', $result->req['sc']),
                        sprintf('カード番号=%s', $cardNo),
                        sprintf('[%.04fsec]', microtime(true) - $mt),
                    ]));
                } else {
                    if ($result->res instanceof \ServerResponseException) {
                        $this->giftcardResults['httperr.rollback'][$cardNo] = $result;
                        $e = $result->res;
                        WtApp::getLogger()->error(implode(' ', [
                            '障害取消(ポイント数の更新)失敗:',
                            sprintf('伝票番号=%s,', $result->req['sc'] ?? ''),
                            sprintf('カード番号=%s,', $cardNo),
                            sprintf('(%s)', get_class($e)),
                            $e->getMessage(),
                            sprintf('[%.04fsec]', microtime(true) - $mt),
                        ]));
                    } else {
                        $this->giftcardResults['failure.rollback'][$cardNo] = $result;
                        WtApp::getLogger()->error(implode(' ', [
                            '障害取消(ポイント数の更新)失敗:',
                            sprintf('伝票番号=%s,', $result->req['sc']),
                            sprintf('カード番号=%s,', $cardNo),
                            sprintf('errorCd=%s,', $result->res->errorCd),
                            sprintf('subErrorCd=%s', $result->res->subErrorCd),
                            sprintf('[%.04fsec]', microtime(true) - $mt),
                        ]));
                    }
                }
            } else {
                $this->giftcardResults['failure.use'][$cardNo] = $result;
                WtApp::getLogger()->error(implode(' ', [
                    'ポイント数の更新失敗:',
                    sprintf('伝票番号=%s,', $result->req['sc'] ?? ''),
                    sprintf('カード番号=%s,', $cardNo),
                    '取引区分=減算(1003),',
                    sprintf('Δ=%s', -$point),
                    sprintf('errorCd=%s,', $result->res->errorCd),
                    sprintf('subErrorCd=%s', $result->res->subErrorCd),
                    sprintf('[%.04fsec]', microtime(true) - $mt),
                ]));
            }
            return false;
        }
        // F08に伝票番号を設定
        foreach ($this->_insertData as $orderNo => $temp) {
            if (!isset($temp['F08']) || empty($temp['F08'])) {
                continue;
            }
            foreach ($temp['F08'] as $index => $row) {
                if (($row['F08SHOTYPE'] != '5') || !$row['F08GCNO']) {
                    continue;
                }
                // 'F08SHOTYPE' == '5' : ギフトカード
                $cardNo = $row['F08GCNO'];
                if (!isset($slipNoList[$cardNo])) {
                    $this->executeCancelUsePoint();
                    return false;
                }
                $this->_insertData[$orderNo]['F08'][$index]['F08GCARDNO'] = $slipNoList[$cardNo];
            }
        }
        $this->pointUseData = $pointUseList;
        $this->_user->setGiftcardInfo($this->gcInfo->calc());
        return true;
    }

    /**
     * ギフトカードの取消処理を行う
     *
     * @return void
     */
    public function executeCancelUsePoint()
    {
        // 成功しているTGC処理のキャンセル
        foreach ($this->giftcardResults['success.use'] as $cardNo => $r) {
            $mt = microtime(true);
            $result = null;
            if ($this->gcInfo->cancel($r->req['cn'], $r->req['p'], $r->req['sc'], $result, (int)$r->req['up'])) {
                $this->giftcardResults['success.cancel'][$cardNo] = $result;
                WtApp::getLogger()->info(implode(' ', [
                    'キャンセル(ポイント数の更新)成功:',
                    sprintf('伝票番号=%s,', $result->req['sc']),
                    sprintf('カード番号=%s', $cardNo),
                    sprintf('[%.04fsec]', microtime(true) - $mt),
                ]));
            } elseif ($result->res instanceof \ServerResponseException) {
                $this->giftcardResults['httperr.cancel'][$cardNo] = $result;
                $e = $result->res;
                WtApp::getLogger()->error(implode(' ', [
                    'キャンセル(ポイント数の更新)失敗:',
                    sprintf('伝票番号=%s,', $result->req['sc'] ?? ''),
                    sprintf('カード番号=%s,', $cardNo),
                    sprintf('(%s)', get_class($e)),
                    $e->getMessage(),
                    sprintf('[%.04fsec]', microtime(true) - $mt),
                ]));
            } else {
                $this->giftcardResults['failure.cancel'][$cardNo] = $result;
                WtApp::getLogger()->error(implode(' ', [
                    'キャンセル(ポイント数の更新)失敗:',
                    sprintf('伝票番号=%s,', $result->req['sc']),
                    sprintf('カード番号=%s,', $cardNo),
                    sprintf('errorCd=%s,', $result->res->errorCd),
                    sprintf('subErrorCd=%s', $result->res->subErrorCd),
                    sprintf('[%.04fsec]', microtime(true) - $mt),
                ]));
            }
        }
    }

    //**************************************************************************
    // 注文者情報
    //**************************************************************************

    /**
     * 注文者情報オブジェクト取得
     *
     * @return Chumonsha
     */
    public function getChumonshaObj()
    {
        return $this->getOrderObj('Chumonsha');
    }

    /**
     * 注文者情報の取得
     *
     * @return array
     * <pre>
     * Array(
     *     Chumonsha::USE_FLG              => ご利用用途フラグ,
     *     Chumonsha::SEI_KANJI            => 姓漢字,
     *     Chumonsha::MEI_KANJI            => 名漢字,
     *     Chumonsha::SEI_KANA             => 姓カナ,
     *     Chumonsha::MEI_KANA             => 名カナ,
     *     Chumonsha::ZIP1                 => 郵便番号１,
     *     Chumonsha::ZIP2                 => 郵便番号２,
     *     Chumonsha::ADD1                 => 住所１,
     *     Chumonsha::ADD2                 => 住所２,
     *     Chumonsha::ADD3                 => 住所３,
     *     Chumonsha::TEL_SHIGAI           => 電話市外,
     *     Chumonsha::TEL_SHINAI           => 電話市内,
     *     Chumonsha::TEL_KYOKUNAI         => 電話局内,
     *     Chumonsha::EMAIL_ADDRESS        => emailアドレス,
     *     Chumonsha::EMAIL_ADDRESS_VERIFY => emailアドレス確認用,
     *     Chumonsha::PRIVACY_POLICY_FLG   => プライバシーポリシーフラグ,
     *     Chumonsha::RINGBELL_INFO_FLG    => リンベルインフォフラグ,
     *     Chumonsha::BIKO                 => 備考,
     * )
     * </pre>
     */
    public function getChumonshaInfo()
    {
        return $this->getChumonshaObj()->getObjectData();
    }

    /**
     * 注文者情報の保存
     *
     * @param array $chumonshaInfo
     * @param bool  $isValid
     *
     * @return void
     */
    public function saveChumonshaInfo($chumonshaInfo, $isValid = true)
    {
        $chumonshaObj = $this->getChumonshaObj();
        $chumonshaObj->setObjectData($chumonshaInfo);
        $chumonshaObj->setValid($isValid);
        $this->_setOrderElementDataToSession($chumonshaObj);
    }

    //**************************************************************************
    // 送り主情報
    //**************************************************************************

    /**
     * 送り主情報オブジェクト取得
     *
     * @return Okurinushi
     */
    public function getOkurinushiObj()
    {
        return $this->getOrderObj('Okurinushi');
    }

    /**
     * 送り主情報の取得
     *
     * @return array
     * <pre>
     * Array(
     *     Okurinushi::OKURINUSHI_FLG          => 送り主フラグ,
     *     Okurinushi::OKURINUSHI_SEI_KANJI    => 送り主姓漢字,
     *     Okurinushi::OKURINUSHI_MEI_KANJI    => 送り主名漢字,
     *     Okurinushi::OKURINUSHI_SEI_KANA     => 送り主姓カナ,
     *     Okurinushi::OKURINUSHI_MEI_KANA     => 送り主名カナ,
     *     Okurinushi::OKURINUSHI_ZIP1         => 送り主郵便番号１,
     *     Okurinushi::OKURINUSHI_ZIP2         => 送り主郵便番号２,
     *     Okurinushi::OKURINUSHI_ADD1         => 送り主住所１,
     *     Okurinushi::OKURINUSHI_ADD2         => 送り主住所２,
     *     Okurinushi::OKURINUSHI_ADD3         => 送り主住所３,
     *     Okurinushi::OKURINUSHI_TEL_SHIGAI   => 送り主電話市外,
     *     Okurinushi::OKURINUSHI_TEL_SHINAI   => 送り主電話市内,
     *     Okurinushi::OKURINUSHI_TEL_KYOKUNAI => 送り主電話局内,
     * )
     * </pre>
     */
    public function getOkurinushiInfo()
    {
        return $this->getOkurinushiObj()->getObjectData();
    }

    /**
     * 送り主情報の保存
     *
     * @param array $okurinushiInfo
     * @param bool  $isValid
     *
     * @return void
     */
    public function saveOkurinushiInfo($okurinushiInfo, $isValid = true)
    {
        $okurinushiObj = $this->getOkurinushiObj();
        $okurinushiObj->setObjectData($okurinushiInfo);
        $okurinushiObj->setValid($isValid);
        $this->_setOrderElementDataToSession($okurinushiObj);
    }

    //**************************************************************************
    // お届け先情報
    //**************************************************************************

    /**
     * お届け先情報を保存するセッション名
     */
    const SESSNAME_OTODOKESAKI_LIST = 'otodokesaki_info_list';

    /**
     * お届け先情報オブジェクトリストの取得
     *
     * @return Otodokesaki[]
     */
    public function getOtodokesakiObjList()
    {
        $otodokesakiList = $this->_user->getAttribute(self::SESSNAME_OTODOKESAKI_LIST);
        $otodokesakiObjList = array();
        if (is_array($otodokesakiList) && !empty($otodokesakiList)) {
            foreach ($otodokesakiList as $otodokesaki) {
                $otodokesakiObjList[] = new Otodokesaki($otodokesaki);
            }
        }
        return $otodokesakiObjList;
    }

    /**
     * お届け先情報オブジェクトの取得
     *
     * @param int $index
     *
     * @return Otodokesaki
     */
    public function getOtodokesakiObj($index)
    {
        $otodokeIndex = intval($index);
        $otodokesakiObjList = $this->getOtodokesakiObjList();
        if (!isset($otodokesakiObjList[$otodokeIndex])) {
            throw new Exception('otodokesaki-not-found index:' . $index);
        }
        return $otodokesakiObjList[$otodokeIndex];
    }

    /**
     * お届け先に設定された全ての商品を削除する
     *
     * @return array 削除した購入商品情報
     */
    public function initOtodokesakiShohin()
    {
        $prevOtodokeShohinList = array();
        $otodokesakiObjList = $this->getOtodokesakiObjList();
        foreach ($otodokesakiObjList as $index => $otodokesakiObj) {
            $prevOtodokeShohinList[$index] = $otodokesakiObj->delShohin();
            $otodokesakiList[$index] = $otodokesakiObj->getObjectData(true);
        }
        $this->_user->setAttribute(self::SESSNAME_OTODOKESAKI_LIST, $otodokesakiList);
        $this->_user->store();
        return $prevOtodokeShohinList;
    }

    /**
     * お届け先に商品情報を設定
     * ※購入数量が0の場合は削除する
     *
     * @param int                 $index
     * @param array|OtodokeShohin $shohinInfo
     * @param bool                $isValid
     *
     * @return void
     */
    public function setOtodokesakiShohin($index, $shohinInfo, $isValid = true)
    {
        $otodokeIndex = intval($index);
        $otodokesakiObjList = $this->getOtodokesakiObjList();
        if (!isset($otodokesakiObjList[$otodokeIndex])) {
            throw new Exception('otodokesaki-not-found index:' . $index);
        }
        $otodokesakiObj = $otodokesakiObjList[$otodokeIndex];
        $otodokesakiObj->addShohin($shohinInfo, $isValid);
        $otodokesakiObjList[$otodokeIndex] = $otodokesakiObj;
        $otodokesakiList = array();
        foreach ($otodokesakiObjList as $otodokesakiObj) {
            $otodokesakiList[] = $otodokesakiObj->getObjectData(true);
        }
        $this->_user->setAttribute(self::SESSNAME_OTODOKESAKI_LIST, $otodokesakiList);
        $this->_user->store();
    }

    /**
     * お届け先情報の取得
     *
     * @return array
     * <pre>
     * Array(
     *     Array(
     *         Otodokesaki::OTODOKESAKI_FLG          => お届け先フラグ,
     *         Otodokesaki::OTODOKESAKI_SEI_KANJI    => お届け先姓漢字,
     *         Otodokesaki::OTODOKESAKI_MEI_KANJI    => お届け先名漢字,
     *         Otodokesaki::OTODOKESAKI_SEI_KANA     => お届け先姓カナ,
     *         Otodokesaki::OTODOKESAKI_MEI_KANA     => お届け先名カナ,
     *         Otodokesaki::OTODOKESAKI_ZIP1         => お届け先郵便番号１,
     *         Otodokesaki::OTODOKESAKI_ZIP2         => お届け先郵便番号２,
     *         Otodokesaki::OTODOKESAKI_ADD1         => お届け先住所１,
     *         Otodokesaki::OTODOKESAKI_ADD2         => お届け先住所２,
     *         Otodokesaki::OTODOKESAKI_ADD3         => お届け先住所３,
     *         Otodokesaki::OTODOKESAKI_TEL_SHIGAI   => お届け先電話市外,
     *         Otodokesaki::OTODOKESAKI_TEL_SHINAI   => お届け先電話市内,
     *         Otodokesaki::OTODOKESAKI_TEL_KYOKUNAI => お届け先電話局内,
     *         Otodokesaki::SHOHIN_LIST => 
     *             Array(
     *                 OtodokeShohin::SHOHIN_NO       => 商品番号,
     *                 OtodokeShohin::KONYU_SURYO     => 購入数量,
     *                 OtodokeShohin::KAKAKU_ZEINUKI  => 商品価格税抜き,
     *                 OtodokeShohin::KAKAKU_TAX      => 消費税,
     *                 OtodokeShohin::HAISO_KIBO_DATE => 配送希望日,
     *             ),
     *             Array(
     *                 複数商品が指定されている場合は要素が増加
     *             ),
     *     ),
     *     Array(
     *         複数のお届け先が指定されている場合は要素が増加
     *     ),
     * )
     * </pre>
     */
    public function getOtodokesakiList($forSave = false)
    {
        $otodokesakiList = array();
        foreach ($this->getOtodokesakiObjList() as $otodokesakiObj) {
            $otodokesakiList[] = $otodokesakiObj->getObjectData($forSave);
        }
        return $otodokesakiList;
    }

    /**
     * お届け先情報の保存
     *
     * @param array $otodokesakiList
     * @param bool  $isValid
     *
     * @return void
     */
    public function saveOtodokesakiList($otodokesakiList, $isValid = true)
    {
        $otodokesakiListTemp = array();
        foreach ($otodokesakiList as $otodokesaki) {
            $otodokesakiObj = new Otodokesaki($otodokesaki);
            $otodokesakiObj->setValid($isValid);
            $otodokesakiListTemp[] = $otodokesakiObj->getObjectData(true);
        }
        $this->_user->setAttribute(self::SESSNAME_OTODOKESAKI_LIST, $otodokesakiListTemp);
        $this->_user->store();
    }

    /**
     * お届け先情報の追加
     *
     * @param array $otodokesakiInfo
     *
     * @return int 追加したお届け先のインデックス
     */
    public function addOtodokesaki($otodokesakiInfo, $isValid = true)
    {
        $otodokesakiList = $this->getOtodokesakiList(true);
        $otodokesakiObj = new Otodokesaki($otodokesakiInfo);
        $otodokesakiObj->setValid($isValid);
        $otodokeIndex = count($otodokesakiList);
        $otodokesakiList[] = $otodokesakiObj->getObjectData(true);
        $this->_user->setAttribute(self::SESSNAME_OTODOKESAKI_LIST, array_values($otodokesakiList));
        $this->_user->store();
        return $otodokeIndex;
    }

    /**
     * お届け先情報の変更
     *
     * @param int   $index
     * @param array $otodokesakiInfo
     *
     * @return void
     */
    public function updateOtodokesaki($index, $otodokesakiInfo, $isValid = true)
    {
        $otodokeIndex = intval($index);
        $otodokesakiList = $this->getOtodokesakiList(true);
        if (!isset($otodokesakiList[$otodokeIndex])) {
            throw new Exception('otodokesaki-not-found index:' . $index);
        }
        /////array_merge($otodokesakiList[$index], $otodokesakiInfo)
        $otodokesakiObj = new Otodokesaki($otodokesakiInfo);
        $otodokesakiObj->setValid($isValid);
        $shohinList = $otodokesakiList[$otodokeIndex][Otodokesaki::SHOHIN_LIST];
        $otodokesakiList[$otodokeIndex] = $otodokesakiObj->getObjectData(true);
        $otodokesakiList[$otodokeIndex][Otodokesaki::SHOHIN_LIST] = $shohinList;
        $this->_user->setAttribute(self::SESSNAME_OTODOKESAKI_LIST, array_values($otodokesakiList));
        $this->_user->store();
    }

    /**
     * お届け先情報の削除
     *
     * @param array $otodokesakiInfo
     *
     * @return void
     */
    public function deleteOtodokesaki($index)
    {
        $otodokeIndex = intval($index);
        $otodokesakiList = $this->getOtodokesakiList(true);
        if (!isset($otodokesakiList[$otodokeIndex])) {
            throw new Exception('otodokesaki-not-found index:' . $index);
        }
        unset($otodokesakiList[$otodokeIndex]);
        $this->_user->setAttribute(self::SESSNAME_OTODOKESAKI_LIST, array_values($otodokesakiList));
        $this->_user->store();
    }

    //**************************************************************************
    // クレジットカード情報
    //**************************************************************************

    /**
     * クレジットカード情報オブジェクト取得
     *
     * @return Creditcard
     */
    public function getCreditcardObj()
    {
        return $this->getOrderObj('Creditcard');
    }

    /**
     * クレジットカード情報の取得
     *
     * @return array
     * <pre>
     * Array(
     *     Creditcard::CARD_NAME          => カード名
     *     Creditcard::CARD_NO            => カード番号
     *     Creditcard::YUKO_MONTH         => 有効期限(月)
     *     Creditcard::YUKO_YEAR          => 有効期限(年)
     *     Creditcard::CREDITCARD_PRICE   => クレジットカード金額(税別)
     *     Creditcard::CREDITCARD_TAX     => クレジットカード消費税
     *     Creditcard::CREDITCARD_TOTAL   => クレジットカード合計(税込)
     *     Creditcard::CREDITCARD_USE_FLG => クレジットカード使用フラグ
     *     Creditcard::CARD_TOKEN         => クレジットカードトークン(非通過対応用)
     * )
     * </pre>
     */
    public function getCreditcardInfo()
    {
        return $this->getCreditcardObj()->getObjectData();
    }

    /**
     * クレジットカード情報の保存
     *
     * @param array $creditcardInfo
     * @param bool  $isValid
     *
     * @return void
     */
    public function saveCreditcardInfo($creditcardInfo, $isValid = true)
    {
        $creditcardObj = $this->getCreditcardObj();
        $creditcardObj->setObjectData($creditcardInfo);
        $creditcardObj->setValid($isValid);
        $this->_setOrderElementDataToSession($creditcardObj);
    }

    /**
     * セッションの中のクレジットカードの与信照会する
     *
     * @return array エラーコード
     * @throws \Exception
     */
    public function creditcardAuth()
    {
        $errorCodeArray = array();
        try {
            //（例）クレジットのプロキシを書き換える
            //    WtApp::$proxyUrl = '';
            //    WtApp::$proxyUserPassword = '';
            //（例）元に戻す
            //    WtApp::$proxyUrl = WT_PROXY_URL;
            //    WtApp::$proxyUserPassword = WT_PROXY_USERPASSWORD;
            include_once(WT_ROOT_DIR . 'util/payment/SgGmoMpClient.php');
            $gmoMp = new SgGmoMpClient();
            $gmoMp->setLogFile(sprintf('%sgmo/gmo_%s.log', WT_LOG_DIR, date('Ymd')));
            $creditcardInfo = $this->getCreditcardInfo();
            $seq = getSequeceNo('GMO');
            $nod = strval(20 - strlen(GMO_PAYMENT_PREFIX));
            // プレフィックスを付けて、全体で20桁にする
            $orderId = sprintf("%s%0{$nod}d", GMO_PAYMENT_PREFIX, $seq);
            $amount  = $creditcardInfo[Creditcard::CREDITCARD_PRICE];
            $tax     = $creditcardInfo[Creditcard::CREDITCARD_TAX];
            $token   = $creditcardInfo[Creditcard::CARD_TOKEN];
            $gmoResult = $gmoMp->doAuth($orderId, $amount, $tax, $token);
            if ($gmoResult->success) {
                // 成功
                $creditcardInfo[Creditcard::ORDER_ID] = $orderId;
                $this->saveCreditcardInfo($creditcardInfo);
                $this->saveCreditResult($gmoResult, $amount, $tax);
            } else {
                $errorCodeArray = $gmoResult->errors;
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return $errorCodeArray;
    }

    public function creditcardTds2Auth($accessId, $accessPass)
    {
        $errorCodeArray = [];
        try {
            include_once(WT_ROOT_DIR . 'util/payment/SgGmoMpClient.php');
            $gmoMp = new SgGmoMpClient();
            $gmoMp->setLogFile(sprintf('%sgmo/gmo_%s.log', WT_LOG_DIR, date('Ymd')));
            $gmoResult = $gmoMp->doAuthAfterTds2Auth($accessId, $accessPass);
            if ($gmoResult->success) {
                $creditcardInfo = $this->getCreditcardInfo();
                $amount = $creditcardInfo[Creditcard::CREDITCARD_PRICE];
                $tax    = $creditcardInfo[Creditcard::CREDITCARD_TAX];
                $gmoResult->output['AccessID']   = $accessId;
                $gmoResult->output['AccessPass'] = $accessPass;
                $this->saveCreditResult($gmoResult, $amount, $tax);
            } else {
                $errorCodeArray = $gmoResult->errors;
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return $errorCodeArray;
    }

    public function saveCreditResult($gmoResult, $amount, $tax)
    {
        $respParams = $gmoResult->output;
        $orderNo = end($this->_orderNoList);
        $authDate = date(DB_TIMESTAMP_FORMAT_SYSTEM, strtotime($respParams['TranDate']));
        $this->_insertData[$orderNo]['F87'] = [
            'F87DELFLG'     => '0',
            'F87INSID'      => MOD_SHOP_ID,
            'F87INSPGM'     => MOD_PGM_ID,
            'F87INSDATE'    => $this->_orderDate,
            'F87UPID'       => MOD_SHOP_ID,
            'F87UPPGM'      => MOD_PGM_ID,
            'F87UPDATE'     => $this->_orderDate,
            'F87WJUCNO'     => $orderNo,
            'F87ACCESSID'   => $respParams['AccessID'] ?? null,
            'F87ACCESSPASS' => $respParams['AccessPass'] ?? null,
            'F87PROCESSID'  => '1', // 1:仮売上 2:実売上 3:金額変更 4:取消 5:返品 6:月跨返品
            'F87TRANID'     => $respParams['TranID'] ?? null,
            'F87ORDERID'    => $respParams['OrderID'] ?? null,
            'F87FORWARD'    => $respParams['Forward'] ?? null,
            'F87APPROVE'    => $respParams['Approve'] ?? null,
            'F87AUTHDATE'   => $authDate, // 仮売上が成功した場合にTranDateをセット
            'F87SALESDATE'  => null,      // 実売上が成功した場合にTranDateをセット
            'F87ERR'        => empty($gmoResult->errors) ? '' : base64_encode(serialize($gmoResult->errors)),
            'F87YUKOFLG'    => '1',
        ];
        $ccPrice = $ccTax = 0;
        $lastOrderNo = null;
        foreach (array_keys($this->_insertData) as $orderNo) {
            $lastOrderNo = $orderNo;
            $this->_insertData[$orderNo]['F06']['F06CTRLNO'] = $respParams['OrderID']; // カード取引自体の管理番号
            $this->_insertData[$orderNo]['F06']['F06CCCTRLNO'] = '';     // カード情報の管理番号
            $this->_insertData[$orderNo]['F06']['F06CCURIAGEFLG'] = '0';
            $ccPrice += $this->_insertData[$orderNo]['F06']['F06CCKINGAKU'];
            $ccTax   += $this->_insertData[$orderNo]['F06']['F06CCTAX'];
            //$this->_insertData[$orderNo]['F06']['F06CCKINGAKU'] = $amount;
            //$this->_insertData[$orderNo]['F06']['F06CCTAX']     = $tax;
            //$this->_insertData[$orderNo]['F06']['F06CCKINGAKZ'] = $amount + $tax;
        }
        if ($ccPrice != $amount) {
            throw new Exception(sprintf('Error: F06CCKINGAKU, amount=%d, ccprice=%d', $amount, $ccPrice));
        }
        if ($ccTax != $tax) {
            // 端数があれば最後で補正
            $taxDiff = $tax - $ccTax;
            $this->_insertData[$lastOrderNo]['F06']['F06CCTAX']     -= $taxDiff;
            $this->_insertData[$lastOrderNo]['F06']['F06CCKINGAKZ'] -= $taxDiff;
        }
    }

    //**************************************************************************
    // ギフトサービス情報
    //**************************************************************************

    /**
     * ギフトサービス情報オブジェクト取得
     *
     * @return GiftService
     */
    public function getGiftServiceObj()
    {
        return $this->getOrderObj('GiftService');
    }

    /**
     * ギフトサービス情報の取得
     *
     * @return array
     * <pre>
     * Array(
     *     GiftService::HOSO_NO            => 包装番号
     *     GiftService::GREETINGCARD_NO    => 挨拶状番号
     *     GiftService::NOSHI_NO           => のし番号
     *     GiftService::NOSHI_SHURUI       => のし上
     *     GiftService::NOSHI_SONOTA_NAIYO => のしその他内容
     *     GiftService::NOSHI_NAME_RIGHT   => のし名前右
     *     GiftService::NOSHI_NAME_LEFT    => のし名前左
     *     GiftService::NOSHI_NAME_FLG     => のし名前フラグ
     * )
     * </pre>
     */
    public function getGiftServiceInfo()
    {
        return $this->getGiftServiceObj()->getObjectData();
    }

    /**
     * ギフトサービス情報の保存
     *
     * @param array $serviceInfo
     * @param bool  $isValid
     *
     * @return void
     */
    public function saveGiftServiceInfo($serviceInfo, $isValid = true)
    {
        $serviceObj = $this->getGiftServiceObj();
        $serviceObj->setObjectData($serviceInfo);
        $serviceObj->setValid($isValid);
        $this->_setOrderElementDataToSession($serviceObj);
    }

    //**************************************************************************
    //**************************************************************************
    //**************************************************************************

    /**
     * 商品購入代金の取得
     *
     * @return int
     */
    public function getTotalOrderPoint()
    {
        $totalPoint = 0;
        foreach ($this->getOtodokesakiList() as $otodokesaki) {
            if (!empty($otodokesaki[Otodokesaki::SHOHIN_LIST])) {
                foreach ($otodokesaki[Otodokesaki::SHOHIN_LIST] as $otodokeShohin) {
                    $point    = $otodokeShohin[OtodokeShohin::KAKAKU_ZEINUKI];
                    $quantity = $otodokeShohin[OtodokeShohin::KONYU_SURYO];
                    $totalPoint += ($point * $quantity);
                }
            }
        }
        return $totalPoint;
    }

    /**
     * セッション状態のチェック
     *
     * @return void
     * @throws Exception
     */
    public function isValidSession()
    {
        // 買い物かご(全アクション共有)
        $shohinInfoList = $this->getShohinInfoList();
        if (empty($shohinInfoList)) {
            // 買い物カゴに商品情報が存在しない
            throw new Exception('購入対象商品情報が見つかりません。');
        }
        if (!$this->getChumonshaObj()->isValid()) {
            throw new Exception('セッション情報に問題があります: 注文者情報');
        }
        if (!$this->getOkurinushiObj()->isValid()) {
            throw new Exception('セッション情報に問題があります: 送り主情報');
        }
        if (!$this->getGiftServiceObj()->isValid()) {
            throw new Exception('セッション情報に問題があります: ギフトサービス');
        }
        $otodokesakiObjList = $this->getOtodokesakiObjList();
        if (empty($otodokesakiObjList)) {
            throw new Exception('お届け先情報が設定されていません。');
        }
        foreach ($otodokesakiObjList as $otodokesakiObj) {
            if (!$otodokesakiObj->isValid()) {
                throw new Exception('セッション情報に問題があります: お届け先情報');
            }
        }
        foreach ($otodokesakiObjList as $otodokeIndex => $otodokesakiObj) {
            $otodokeShohinObjList = $otodokesakiObj->getOtodokeShohinObjList();
            if (empty($otodokeShohinObjList)) {
                throw new Exception(sprintf('%d番目のお届け先に商品が設定されていません。', $otodokeIndex + 1));
            }
            foreach ($otodokeShohinObjList as $otodokeShohinObj) {
                if (!$otodokeShohinObj->isValid()) {
                    throw new Exception(sprintf('%d番目のお届け先に設定された商品情報に問題があります。', $otodokeIndex + 1));
                }
            }
        }
    }

    /**
     * 注文関連セッションの削除(初期化)
     * ログインカードの情報以外を削除する
     *
     * @return void
     */
    public function removeOrderSession($ignoreList = array())
    {
        $sessNameList = array();
        foreach (array_keys($this->_orderObjects) as $objName) {
            if (!in_array($objName, $ignoreList)) {
                $sessNameList[] = $this->getOrderObj($objName)->getSessionName();
            }
        }
        $sessNameList[] = self::SESSNAME_OTODOKESAKI_LIST;
        $user = $this->_user;
        foreach ($sessNameList as $sessName) {
            $user->removeAttribute($sessName);
        }
    }

    /**
     * パラメータ検証用のハッシュ値を取得
     *
     * @param mixed $value
     *
     * @return string
     */
    public function buildFingerprint($value)
    {
        return sha1(serialize($value));
    }

    /**
     * 新しく生成した受注番号の取得
     *
     * @return string
     */
    public function generateNewOrderNo()
    {
        $orderNo = sprintf('%s%06d', PRE_JUCHUNO, getSequeceNo('F06JUCHU'));
        $this->_orderNoList[] = $orderNo;
        return $orderNo;
    }

    /**
     * 受注番号リストの取得
     *
     * @return string[]
     */
    public function getOrderNoList()
    {
        return $this->_orderNoList;
    }

    /**
     * 商品在庫を更新
     *
     * @param string $shohinNo
     * @param int    $nBuy
     *
     * @return bool
     * @throws Exception
     */
    public function updateShohinZaiko($shohinNo, $nBuy)
    {
        $db = new OrderCommonQueryIUD();
        $db->ConntTrans();
        try {
            $db->setSelectSql('zaiko-check');
            $db->setRecordsetArray(array('M02SHOHNNO' => $shohinNo));
            $rs = $db->Execute();
            if (!$rs) {
                throw new WtDbException(E_DB_EXECUTE_ERR);
            }
            if (intval($rs->Fields('M02GENTEI')) > 0) {
                if (intval($rs->Fields('M02NOKORI')) < $nBuy) {
                    // 在庫不足
                    $db->ConnRollBack();
                    return false;
                }
                $db->setSelectSql('zaiko-update');
                $db->setRecordsetArray(array(
                    'M02UPID'    => MOD_SHOP_ID, // wt.ini.php
                    'M02UPPGM'   => MOD_PGM_ID,  // config.php
                    'M02UPDATE'  => date(DB_TIMESTAMP_FORMAT_SYSTEM),
                    'M02SHOHNNO' => $shohinNo,
                    'NNN'        => $nBuy,
                ));
                if (!$db->Execute()) {
                    throw new WtDbException(E_DB_EXECUTE_ERR);
                }
            }
            $db->ConnCommit();
        } catch (Exception $e) {
            $db->ConnRollBack();
            throw $e;
        }
        return true;
    }

    /**
     * 商品の在庫戻し
     *
     * @param string $shohinNo
     * @param int    $nBuy 在庫を戻す数量
     *
     * @return bool 成功時にtrue
     */
    public function rollbackShohin($shohinNo, $nBuy)
    {
        try {
            $this->updateShohinZaiko($shohinNo, 0 - $nBuy);
        } catch (Exception $e) {
            $this->alertErrors[] = sprintf('商品の在庫戻し処理に失敗しました。商品番号:%s', strval($shohinNo));
            return false;
        }
        return true;
    }

    /**
     * マスターデータの取得
     *
     * @param string $masterKey
     * @param bool   $sessionDataOnly
     *
     * @return array
     */
    public function getMasterData($masterKey, $sessionDataOnly = false)
    {
        // AbstractOrderActionから移動＆改修
        $user = $this->_user;
        $masterData = $user->getAttribute('__order_m03_data__');
        if (!is_array($masterData)) {
            $masterData = array();
        }
        if (!isset($masterData[$masterKey])) {
            if ($sessionDataOnly) {
                return array();
            }
            $dbc = new OrderCommonQuerySel();
            $dbc->setSelectSql('1');
            $dbc->setRecordsetArray(array('masterKey' => $masterKey));
            $rs = $dbc->Execute();
            if ($rs->RecordCount() === 0) {
                throw new Exception('SQLERROR:レコードが取得できません');
            }
            $masterData[$masterKey] = array();
            while (!$rs->EOF) {
                $k  = trim($rs->Fields('M03KEY2'));
                $v1 = $rs->Fields('M03NAME');
                $v2 = $rs->Fields('M03CHARA1');
                $masterData[$masterKey][$k] = array($v1, $v2);
                $rs->MoveNext();
            }
            $user->setAttribute('__order_m03_data__', $masterData);
            $user->store();
        }
        return $masterData[$masterKey];
    }

    /**
     * 受注登録データの作成
     *
     * ※商品1個ごとに別受注にする・・・(基幹側との兼ね合いで)
     *
     * @return void
     */
    public function createInsertData()
    {
        $this->_orderDate = date('Y-m-d-H.i.s');
        $baseParams = $this->getInsertBaseParams($this->_orderDate);
        $paramsF06 = $baseParams['F06'];
        $paramsF17 = $baseParams['F17'];
        //WAKUWAKU-183 start
        //複数商品購入時も包装紙や、のしの情報は一つの為、こちらで一時データに保存しておき、商品毎の包装紙やのしの情報に合わせる
        $tmp_paramsF17 = array();
        //包装フラグ
        $tmp_paramsF17['F17HOSONO'] = $paramsF17['F17HOSONO'];
        //のし区分
        $tmp_paramsF17['F17NKBN'] = $paramsF17['F17NKBN'];
        $tmp_params['F17NUKBN'] = $paramsF17['F17NUKBN'];
        $tmp_params['F17NUHOKA'] = $paramsF17['F17NUHOKA'];
        $tmp_params['F17NSMEI'] = $paramsF17['F17NSMEI'];
        $tmp_params['F17NSMEIK'] = $paramsF17['F17NSMEIK'];
        //挨拶状フラグ
        $tmp_params['F17CKBN'] = $paramsF17['F17CKBN'];
        //WAKUWAKU-183 end
        $shohinInfoList = $this->getShohinInfoList();
        $this->_insertData = array();
        $giftcardList = $this->gcInfo->getCardList();
        foreach ($this->getOtodokesakiList() as $otodokesaki) {
            $paramsF07 = $baseParams['F07'];
            if ($otodokesaki[Otodokesaki::OTODOKESAKI_FLG] == '1') {
                $paramsF07['F07OKURKBN'] = '0';
                $paramsF07['F07SEI']     = $otodokesaki[Otodokesaki::OTODOKESAKI_SEI_KANJI];
                $paramsF07['F07MEI']     = $otodokesaki[Otodokesaki::OTODOKESAKI_MEI_KANJI];
                $paramsF07['F07SEIKANA'] = $otodokesaki[Otodokesaki::OTODOKESAKI_SEI_KANA];
                $paramsF07['F07MEIKANA'] = $otodokesaki[Otodokesaki::OTODOKESAKI_MEI_KANA];
                $paramsF07['F07ZIP1']    = $otodokesaki[Otodokesaki::OTODOKESAKI_ZIP1];
                $paramsF07['F07ZIP2']    = $otodokesaki[Otodokesaki::OTODOKESAKI_ZIP2];
                $paramsF07['F07ADD1']    = $otodokesaki[Otodokesaki::OTODOKESAKI_ADD1];
                $paramsF07['F07ADD2']    = $otodokesaki[Otodokesaki::OTODOKESAKI_ADD2];
                $paramsF07['F07ADD3']    = $otodokesaki[Otodokesaki::OTODOKESAKI_ADD3];
                $paramsF07['F07TEL11']   = $otodokesaki[Otodokesaki::OTODOKESAKI_TEL_SHIGAI];
                $paramsF07['F07TEL12']   = $otodokesaki[Otodokesaki::OTODOKESAKI_TEL_SHINAI];
                $paramsF07['F07TEL13']   = $otodokesaki[Otodokesaki::OTODOKESAKI_TEL_KYOKUNAI];
            } else {
                $paramsF07['F07OKURKBN'] = '1';
                $paramsF07['F07SEI']     = $paramsF06['F06SEI'];
                $paramsF07['F07MEI']     = $paramsF06['F06MEI'];
                $paramsF07['F07SEIKANA'] = $paramsF06['F06SEIKN'];
                $paramsF07['F07MEIKANA'] = $paramsF06['F06MEIKN'];
                $paramsF07['F07ZIP1']    = $paramsF06['F06ZIP1'];
                $paramsF07['F07ZIP2']    = $paramsF06['F06ZIP2'];
                $paramsF07['F07ADD1']    = $paramsF06['F06ADD1'];
                $paramsF07['F07ADD2']    = $paramsF06['F06ADD2'];
                $paramsF07['F07ADD3']    = $paramsF06['F06ADD3'];
                $paramsF07['F07TEL11']   = $paramsF06['F06TEL11'];
                $paramsF07['F07TEL12']   = $paramsF06['F06TEL12'];
                $paramsF07['F07TEL13']   = $paramsF06['F06TEL13'];
            }
            foreach ($otodokesaki[Otodokesaki::SHOHIN_LIST] as $otodokeShohin) {
                $shohinNo = $otodokeShohin[OtodokeShohin::SHOHIN_NO];
                $shohinInfo = $shohinInfoList[$shohinNo];
                $deliveryType = $shohinInfo[ShohinInformation::HAISO_KEITAI];
                if ($shohinInfo[ShohinInformation::KIKAN_GENTEI_FLG] === '1') {
                    $deliveryType = $shohinInfo[ShohinInformation::KIKAN_GENTEI_HAISO_KEITAI];
                }
                $calcTax = 0;
                if ($shohinInfo[ShohinInformation::TAXFREE_FLG] !== '1') {
                    // 課税商品
                    $calcTax = ceil($shohinInfo[ShohinInformation::KAKAKU_ZEINUKI] * getTaxRate());
                }
                $paramsF08 = $baseParams['F08'];
                $paramsF08['F08SHOHNNO']  = $shohinNo;
                $paramsF08['F08SHOHNCD']  = $shohinInfo[ShohinInformation::SHOHIN_CODE];
                $paramsF08['F08SNAME']    = $shohinInfo[ShohinInformation::SHOHIN_NAME];
                $paramsF08['F08SNAMEK']   = $shohinInfo[ShohinInformation::SHOHIN_NAME_KANA];
                $paramsF08['F08SHOTYPE']  = '1';
                if ($otodokeShohin[OtodokeShohin::HAISO_KIBO_DATE]) {
                    $t = strtotime($otodokeShohin[OtodokeShohin::HAISO_KIBO_DATE]);
                    $paramsF08['F08KIBOBI'] = date('Y-m-d-H.i.s', $t);
                }
                $paramsF08['F08HSKETAI']  = $deliveryType;
                $paramsF08['F08HAIMTCD']  = $shohinInfo[ShohinInformation::HAISO_MOTO_SHIKIBETSU_CODE];
                $paramsF08['F08VPOINT']   = $shohinInfo[ShohinInformation::KAKAKU_ZEINUKI];
                $paramsF08['F08SURYO']    = 1; // 商品1つごとに受注を作成するため1固定
                $paramsF08['F08USEPOINT'] = 0;
                $paramsF08['F08GCNO']     = '';
                $paramsF08['F08GCARDNO']  = '';
                $paramsF08['F08KAKAKU']   = $shohinInfo[ShohinInformation::KAKAKU_ZEINUKI];
                $paramsF08['F08TAX']      = $calcTax;
                $paramsF08['F08KAKAKUZ']  = $shohinInfo[ShohinInformation::KAKAKU_ZEINUKI] + $calcTax;
                for ($i = 0; $i < $otodokeShohin[OtodokeShohin::KONYU_SURYO]; $i++) {
                    $orderNo = $this->generateNewOrderNo();
                    $paramsF06['F06WJUCNO'] = $orderNo;
                    $paramsF07['F07WJUCNO'] = $orderNo;
                    $paramsF08['F08WJUCNO'] = $orderNo;
                    $paramsF17['F17WJUCNO'] = $orderNo;
                    //WAKUWAKU-183 start
                    $dbc = new OrderCommonQuerySel();
                    $dbc->setSelectSql('2');
                    $dbc->setRecordsetArray(array('shohinNo' => $paramsF08['F08SHOHNNO']));
                    $rs = $dbc->Execute();
                    if ($rs->RecordCount() === 0) {
                        throw new Exception(sprintf('商品情報レコードが取得できません: 商品番号=%s', $paramsF08['F08SHOHNNO']));
                    }
                    if($rs->Fields('M02HOSOFLG') != 1) {//包装フラグ
                        $paramsF17['F17HOSONO'] = NULL;
                    } else {
                        $paramsF17['F17HOSONO'] = $tmp_paramsF17['F17HOSONO'];
                    }
                    if($rs->Fields('M02NOSIKBN') != 1) {//のし区分
                        $paramsF17['F17NKBN'] = NULL;
                        $paramsF17['F17NUKBN'] = NULL;
                        $paramsF17['F17NUHOKA'] = NULL;
                        $paramsF17['F17NSMEI'] = NULL;
                        $paramsF17['F17NSMEIK'] = NULL;
                    } else {
                        $paramsF17['F17NKBN'] = $tmp_paramsF17['F17NKBN'];
                        $paramsF17['F17NUKBN'] = $tmp_params['F17NUKBN'];
                        $paramsF17['F17NUHOKA'] = $tmp_params['F17NUHOKA'];
                        $paramsF17['F17NSMEI'] = $tmp_params['F17NSMEI'];
                        $paramsF17['F17NSMEIK'] = $tmp_params['F17NSMEIK'];
                    }
                    if($rs->Fields('M02MCRDFLG') != 1) {//挨拶状フラグ
                        $paramsF17['F17CKBN'] = NULL;
                    } else {
                        $paramsF17['F17CKBN'] = $tmp_params['F17NSMEIK'];
                    }
                    //WAKUWAKU-183 end
                    $this->_insertData[$orderNo] = array(
                        'F06' => $paramsF06,
                        'F07' => $paramsF07,
                        'F08' => array($paramsF08),
                        'F17' => $paramsF17,
                    );
                    // ギフトカード明細の作成
                    $vPoint = $paramsF08['F08VPOINT'];
                    $paramsF08G = $paramsF08;
                    $paramsF08G['F08SHOHNNO'] = 0;
                    $paramsF08G['F08SHOHNCD'] = 'OP-003';
                    $paramsF08G['F08SNAME']   = 'ギフトカード';
                    $paramsF08G['F08SNAMEK']  = 'ぎふとかーど';
                    $paramsF08G['F08SHOTYPE'] = '5';
                    $paramsF08G['F08HSKETAI'] = '1';
                    $paramsF08G['F08HAIMTCD'] = '';
                    $paramsF08G['F08VPOINT']  = 0;
                    $paramsF08G['F08KAKAKU']  = 0;
                    $paramsF08G['F08TAX']     = 0;
                    $paramsF08G['F08KAKAKUZ'] = 0;
                    $paramsF08G['F08SURYO']   = 1;
                    foreach ($giftcardList as $cardNo => $gcDetail) {
                        if (!$vPoint) {
                            break;
                        }
                        if (!$gcDetail->usable || !$gcDetail->point) {
                            continue;
                        }
                        $remainPoint = $gcDetail->point;
                        $paramsF08G['F08GCNO'] = $gcDetail->cardNo;
                        if ($vPoint > $remainPoint) {
                            $paramsF08G['F08USEPOINT'] = $remainPoint;
                            $paramsF08G['F08KAKAKU']   = $remainPoint;
                            $paramsF08G['F08KAKAKUZ']  = $remainPoint;
                            $vPoint -= $remainPoint;
                            $remainPoint = 0;
                        } else {
                            $paramsF08G['F08USEPOINT'] = $vPoint;
                            $paramsF08G['F08KAKAKU']   = $vPoint;
                            $paramsF08G['F08KAKAKUZ']  = $vPoint;
                            $remainPoint -= $vPoint;
                            $vPoint = 0;
                        }
                        $giftcardList[$cardNo]->point = $remainPoint;
                        ++$paramsF08G['F08RENBAN'];
                        $this->_insertData[$orderNo]['F08'][] = $paramsF08G;
                    }
                    if (count($this->_insertData[$orderNo]['F08']) == 1) {
                        $gcDetail = reset($giftcardList);
                        $paramsF08G['F08GCNO'] = $gcDetail->cardNo;
                        ++$paramsF08G['F08RENBAN'];
                        $this->_insertData[$orderNo]['F08'][] = $paramsF08G;
                    }
                    $ccTax = 0;
                    $ccPrice = $vPoint;
                    if (($shohinInfo[ShohinInformation::TAXFREE_FLG] !== '1') && $ccPrice) {
                        // 20230925 クレカの消費税はかからないように変更（課題管理表：項番4） ※内税対応
                        $taxRate = getTaxRate(); // 0.1
                        $ccTax = (int)ceil($ccPrice / ($taxRate + 1.0) * $taxRate); // 消費税は、切り上げ計算(元仕様のまま)
                        $ccPrice -= $ccTax;
                    }
                    $this->_insertData[$orderNo]['F06']['F06CCKINGAKU'] = $ccPrice; // クレジットカード注文金額_税別
                    $this->_insertData[$orderNo]['F06']['F06CCTAX']     = $ccTax;   // クレジットカード注文金額_消費税額
                    $this->_insertData[$orderNo]['F06']['F06CCKINGAKZ'] = $ccPrice + $ccTax; // クレジットカード注文金額_税込
                    if ($shohinInfo[ShohinInformation::HYOJI_KEY2] == 'DGC') {
                        if (empty($shohinInfo[ShohinInformation::DGC_INFO])) {
                            throw new Exception(sprintf('デジタルギフトコード情報が見つかりません。商品番号%s', $paramsF08['F08SHOHNNO']));
                        }
                        $this->_insertData[$orderNo]['F08DGC'] = [];
                        foreach ($shohinInfo[ShohinInformation::DGC_INFO] as $row) {
                            $this->_insertData[$orderNo]['F08DGC'][] = array_merge([
                                'F08WJUCNO'      => $orderNo,
                                'F08HAISONO'     => $paramsF08['F08HAISONO'],
                                'F08RENBAN'      => $paramsF08['F08RENBAN'],
                                'F08DGPUBLISHER' => $row['PUBLISHER'],
                                'F08SLIPNO'      => null,
                                'F08DGORDERNO'   => null,
                                'F08AROUNDINFO'  => null,
                                'F08ISSUERCD'    => null,
                                'F08DESIGNCD'    => null,
                                'F08CARDNO'      => null,
                                'F08INQUIRYCD'   => null,
                                'F08PIN'         => null,
                                'F08CERTIFYCODE' => null,
                                'F08DGCD'        => null,
                                'F08BARCODEURL'  => null,
                                'F08EXCHANGEURL' => null,
                                'F08BALANCE'     => $row['DGC_POINT'],
                                'F08CAMPAIGN'    => null,
                                'F08EXPRIREDATE' => null,
                                'F08GETDATE'     => null,
                                // 以下はメール文面作成時の参照用
                                'F08SHOHNNO'     => $paramsF08['F08SHOHNNO'],
                                'F08SHOHNCD'     => trim($paramsF08['F08SHOHNCD']),
                                'F08SNAME'       => trim($paramsF08['F08SNAME']),
                                'M02DSTKNO'      => null,
                                'M02DSTKINFO1'   => null,
                                'M02DSTKINFO2'   => null,
                                'M02DSTKINFO3'   => null,
                                'M02DSTKINFO4'   => null,
                                'M02DSTKINFO5'   => null,
                                'M02DSTKIREMARK1' => null,
                                'M02DSTKIREMARK2' => null,
                                'M02DSTKIREMARK3' => null,
                                'M02DSTKIREMARK4' => null,
                                'M02DSTKIREMARK5' => null,
                            ], $row);
                        }
                    }
                }
            }
        }
    }

    /**
     * 受注登録データの作成
     *
     * @param string $orderDate
     *
     * @return array
     */
    protected function getInsertBaseParams($orderDate)
    {
        $chumonsha  = $this->getChumonshaInfo();
        $okurinushi = $this->getOkurinushiInfo();
        $creditInfo = $this->getCreditcardInfo();
        $dbParams = array();
        // F06受注(F06JUCHU)
        $channelCode = DbConst::F06CHANNEL_PC; // '11'
        $ringbellIp = '/\A(?:172|61\.200\.18)\./';
        if ((array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && preg_match($ringbellIp, $_SERVER['HTTP_X_FORWARDED_FOR']))
            || (array_key_exists('REMOTE_ADDR', $_SERVER) && preg_match($ringbellIp, $_SERVER['REMOTE_ADDR']))
        ) {
            // 代理注文(リンベル様からの注文)の場合、'21' にする
            $channelCode = DbConst::F06CHANNEL_DAIRI;
        } elseif (isSmartPhone()) {
            // キャリア判定、'13'がスマートフォン
            $channelCode = '13';
        }
        $dbParams['F06'] = array(
            'F06DELFLG'      => '0',                                      // 削除フラグ
            'F06INSID'       => MOD_SHOP_ID,                              // 登録者
            'F06INSPGM'      => MOD_PGM_ID,                               // 登録プログラム
            'F06INSDATE'     => $orderDate,                               // 登録日時
            'F06UPID'        => MOD_SHOP_ID,                              // 最終更新者
            'F06UPPGM'       => MOD_PGM_ID,                               // 最終更新プログラム
            'F06UPDATE'      => $orderDate,                               // 最終更新日時
            'F06WJUCNO'      => '',                                       // WEB受注番号
            'F06JOBNO'       => '',                                       // JOBNO
            'F06GCNO'        => $this->gcInfo->maincardNo,                // カード番号
            'F06JUCHUBI'     => $orderDate,                               // WEB受注日時
            'F06JUCHKBN'     => $creditInfo[Creditcard::CREDITCARD_USE_FLG], // 受注区分
            'F06ID'          => SHOP_ID,                                  // サイトＩＤ
            'F06CHANNEL'     => $channelCode,                             // 販売チャネルコード
            'F06SHORFLG'     => DbConst::F06SHORFLG_UNIMPORT,             // ホスト処理フラグ ('11': ホストの取込処理待ち(一般、ポイント交換))
            'F06CCURIAGEFLG' => '',                                       // クレジットカード売上済みフラグ
            'F06CTRLNO'      => '',                                       // BJP管理番号
            'F06CCCTRLNO'    => '',                                       // BJPカード情報管理番号
            'F06COPX'        => '0',                                      // (請求先)法人区分
            'F06CPNM'        => '',                                       // (請求先)会社名
            'F06CPKN'        => '',                                       // (請求先)会社名かな
            'F06CPN2'        => '',                                       // (請求先)部署名
            'F06CPN3'        => '',                                       // (請求先)役職名
            'F06SEI'         => $chumonsha[Chumonsha::SEI_KANJI],         // (請求先)姓漢字
            'F06MEI'         => $chumonsha[Chumonsha::MEI_KANJI],         // (請求先)名漢字
            'F06SEIKN'       => $chumonsha[Chumonsha::SEI_KANA],          // (請求先)姓ふりがな
            'F06MEIKN'       => $chumonsha[Chumonsha::MEI_KANA],          // (請求先)名ひらがな
            'F06ZIP1'        => $chumonsha[Chumonsha::ZIP1],              // (請求先)郵便番号１
            'F06ZIP2'        => $chumonsha[Chumonsha::ZIP2],              // (請求先)郵便番号２
            'F06ADD1'        => $chumonsha[Chumonsha::ADD1],              // (請求先)住所１
            'F06ADD2'        => $chumonsha[Chumonsha::ADD2],              // (請求先)住所２
            'F06ADD3'        => $chumonsha[Chumonsha::ADD3],              // (請求先)住所３
            'F06TEL11'       => $chumonsha[Chumonsha::TEL_SHIGAI],        // (請求先)電話番号１（市外局番）
            'F06TEL12'       => $chumonsha[Chumonsha::TEL_SHINAI],        // (請求先)電話番号２(市内局番)
            'F06TEL13'       => $chumonsha[Chumonsha::TEL_KYOKUNAI],      // (請求先)電話番号３(局内番号)
            'F06EMAILPC'     => $chumonsha[Chumonsha::EMAIL_ADDRESS],     // (請求先)E-mailアドレス
            'F06NEWSDMFLG'   => $chumonsha[Chumonsha::RINGBELL_INFO_FLG], // (請求先）メルマガ・DM希望フラグ
            'F06TUSNRAN'     => $chumonsha[Chumonsha::BIKO],              // 通信欄
            'F06NNUSFLG'     => '0',                                      // 荷主フラグ
            'F06NSEI'        => $chumonsha[Chumonsha::SEI_KANJI],         // (荷主)姓漢字
            'F06NMEI'        => $chumonsha[Chumonsha::MEI_KANJI],         // (荷主)名漢字
            'F06NSEIKN'      => $chumonsha[Chumonsha::SEI_KANA],          // (荷主)姓ふりがな
            'F06NMEIKN'      => $chumonsha[Chumonsha::MEI_KANA],          // (荷主)名ひらがな
            'F06NZIP1'       => $chumonsha[Chumonsha::ZIP1],              // (荷主)郵便番号１
            'F06NZIP2'       => $chumonsha[Chumonsha::ZIP2],              // (荷主)郵便番号２
            'F06NADD1'       => $chumonsha[Chumonsha::ADD1],              // (荷主)住所１
            'F06NADD2'       => $chumonsha[Chumonsha::ADD2],              // (荷主)住所２
            'F06NADD3'       => $chumonsha[Chumonsha::ADD3],              // (荷主)住所３
            'F06NTEL11'      => $chumonsha[Chumonsha::TEL_SHIGAI],        // (荷主)電話番号１（市外局番）
            'F06NTEL12'      => $chumonsha[Chumonsha::TEL_SHINAI],        // (荷主)電話番号２(市内局番)
            'F06NTEL13'      => $chumonsha[Chumonsha::TEL_KYOKUNAI],      // (荷主)電話番号３(局内番号)
            'F06CCKINGAKU'   => 0,                                        // クレジットカード注文金額_税別
            'F06CCTAX'       => 0,                                        // クレジットカード注文金額_消費税額
            'F06CCKINGAKZ'   => 0,                                        // クレジットカード注文金額_税込
            'F06USEFLG'      => $chumonsha[Chumonsha::USE_FLG],           // ご利用用途フラグ
            'F06MEIGI'       => '',                                       // カード名義
            'F06KENGROUP'    => $this->gcInfo->kenshuGroup,               // 券種グループ
            'F06TENPO'       => WT_DEFAULT_KENSHU_GROUP,
        );
        if ($okurinushi[Okurinushi::OKURINUSHI_FLG] == '1') {
            $dbParams['F06']['F06NNUSFLG'] = '1';
            $dbParams['F06']['F06NSEI']    = $okurinushi[Okurinushi::OKURINUSHI_SEI_KANJI];
            $dbParams['F06']['F06NMEI']    = $okurinushi[Okurinushi::OKURINUSHI_MEI_KANJI];
            $dbParams['F06']['F06NSEIKN']  = $okurinushi[Okurinushi::OKURINUSHI_SEI_KANA];
            $dbParams['F06']['F06NMEIKN']  = $okurinushi[Okurinushi::OKURINUSHI_MEI_KANA];
            $dbParams['F06']['F06NZIP1']   = $okurinushi[Okurinushi::OKURINUSHI_ZIP1];
            $dbParams['F06']['F06NZIP2']   = $okurinushi[Okurinushi::OKURINUSHI_ZIP2];
            $dbParams['F06']['F06NADD1']   = $okurinushi[Okurinushi::OKURINUSHI_ADD1];
            $dbParams['F06']['F06NADD2']   = $okurinushi[Okurinushi::OKURINUSHI_ADD2];
            $dbParams['F06']['F06NADD3']   = $okurinushi[Okurinushi::OKURINUSHI_ADD3];
            $dbParams['F06']['F06NTEL11']  = $okurinushi[Okurinushi::OKURINUSHI_TEL_SHIGAI];
            $dbParams['F06']['F06NTEL12']  = $okurinushi[Okurinushi::OKURINUSHI_TEL_SHINAI];
            $dbParams['F06']['F06NTEL13']  = $okurinushi[Okurinushi::OKURINUSHI_TEL_KYOKUNAI];
        }
        // F07受注配送先(F07JUCHUHS)
        $dbParams['F07'] = array(
            'F07DELFLG'  => '0',         // 削除フラグ
            'F07INSID'   => MOD_SHOP_ID, // 登録者
            'F07INSPGM'  => MOD_PGM_ID,  // 登録プログラム
            'F07INSDATE' => $orderDate,  // 登録日時
            'F07UPID'    => MOD_SHOP_ID, // 最終更新者
            'F07UPPGM'   => MOD_PGM_ID,  // 最終更新プログラム
            'F07UPDATE'  => $orderDate,  // 最終更新日時
            'F07ID'      => SHOP_ID,     // サイトＩＤ
            'F07WJUCNO'  => '',          // WEB受注番号
            'F07HAISONO' => 1,           // 配送先番号(1件ずつ別受注を作成するので1固定)
            'F07OKURKBN' => '',          // 送り先区分
            'F07COPX'    => '0',         // 法人区分
            'F07CPNM'    => '',          // 会社名
            'F07CPKN'    => '',          // 会社名かな
            'F07CPN2'    => '',          // 部署名
            'F07CPN3'    => '',          // 役職
            'F07SEI'     => '',          // 姓（漢字）
            'F07MEI'     => '',          // 名（漢字）
            'F07SEIKANA' => '',          // 姓ふりがな
            'F07MEIKANA' => '',          // 名ふりがな
            'F07ZIP1'    => '',          // 配送先郵便番号１
            'F07ZIP2'    => '',          // 配送先郵便番号２
            'F07ADD1'    => '',          // 住所１
            'F07ADD2'    => '',          // 住所２
            'F07ADD3'    => '',          // 住所３
            'F07TEL11'   => '',          // 配送先電話番号１
            'F07TEL12'   => '',          // 配送先電話番号２
            'F07TEL13'   => '',          // 配送先電話番号３
        );
        // F08受注明細(F08JUCHUME)
        $dbParams['F08'] = array(
            'F08DELFLG'   => '0',         // 削除フラグ
            'F08INSID'    => MOD_SHOP_ID, // 登録者
            'F08INSPGM'   => MOD_PGM_ID,  // 登録プログラム
            'F08INSDATE'  => $orderDate,  // 登録日時
            'F08UPID'     => MOD_SHOP_ID, // 最終更新者
            'F08UPPGM'    => MOD_PGM_ID,  // 最終更新プログラム
            'F08UPDATE'   => $orderDate,  // 最終更新日時
            'F08ID'       => SHOP_ID,     // サイトＩＤ
            'F08WJUCNO'   => '',          // WEB受注番号
            'F08HAISONO'  => 1,           // 配送先番号(1件ずつ別受注を作成するので1固定)
            'F08RENBAN'   => 1,           // 連番      (1件ずつ別受注を作成するので1固定)
            'F08SHOHNNO'  => null,        // 商品No.
            'F08SHOHNCD'  => '',          // 商品コード
            'F08SNAME'    => '',          // 商品名
            'F08SNAMEK'   => '',          // 商品名ふりがな
            'F08SHOTYPE'  => '',          // 商品タイプ
            'F08KIBOBI'   => null,        // 希望配送日
            'F08HSKETAI'  => '',          // 配送形態
            'F08HAIMTCD'  => '',          // 配送元識別コード
            'F08VPOINT'   => '',          // ポイント
            'F08SURYO'    => '',          // 数量
            'F08USEPOINT' => '',          // 販売合計価格_税込
            'F08GCNO'     => '',          // ギフトカード番号
            'F08GCARDNO'  => '',          // ギフトカード番号
            'F08KAKAKU'   => null,        // 税抜価格
            'F08TAX'      => null,        // 税額
            'F08KAKAKUZ'  => null,        // 税込価格
        );
        // F17
        $giftService = $this->getGiftServiceInfo();
        $dbParams['F17'] = array(
            'F17DELFLG'  => '0',                                           // 削除フラグ
            'F17INSID'   => MOD_SHOP_ID,                                   // 登録者
            'F17INSPGM'  => MOD_PGM_ID,                                    // 登録プログラム
            'F17INSDATE' => $orderDate,                                    // 登録日時
            'F17UPID'    => MOD_SHOP_ID,                                   // 最終更新者
            'F17UPPGM'   => MOD_PGM_ID,                                    // 最終更新プログラム
            'F17UPDATE'  => $orderDate,                                    // 最終更新日時
            'F17ID'      => SHOP_ID,                                       // サイトＩＤ
            'F17WJUCNO'  => '',                                            // WEB受注番号
            'F17HAISONO' => 1,                                             // 配送先番号(1件ずつ別受注を作成するので1固定)
            'F17RENBAN'  => 1,                                             // 連番      (1件ずつ別受注を作成するので1固定)
            'F17HOSONO'  => $giftService[GiftService::HOSO_NO],            // 包装紙No.
            'F17NKBN'    => $giftService[GiftService::NOSHI_NO],           // のし区分
            'F17NUKBN'   => $giftService[GiftService::NOSHI_SHURUI],       // のし上種類
            'F17NUHOKA'  => $giftService[GiftService::NOSHI_SONOTA_NAIYO], // のし上その他
            'F17NSMEI'   => $giftService[GiftService::NOSHI_NAME_RIGHT],   // のし下名漢字
            'F17NSMEIK'  => $giftService[GiftService::NOSHI_NAME_LEFT],    // のし下名かな
            'F17NBIKO'   => '',                                            // のし備考
            'F17CKBN'    => $giftService[GiftService::GREETINGCARD_NO],    // 挨拶状種類
            'F17CSURYO'  => 0,                                             // カード枚数(固定)
        );
        return $dbParams;
    }

    /**
     * データの登録
     *
     * @return array $errorCodeArray エラーコード
     */
    public function insertData()
    {
        $errorCodeArray = array();
        try{
            $dbc = new OrderCommonQueryIUD();
            // トランザクション開始
            $dbc->ConntTrans();
            foreach ($this->_insertData as $insertData) {
                // F06受注テーブルに登録
                $dbc->setSelectSql('insert-f06');
                $dbc->setRecordsetArray($insertData['F06']);
                if (!$dbc->Execute()) {
                    throw new WtDbException(E_DB_EXECUTE_ERR);
                }
                // F07配送テーブルに登録
                $dbc->setSelectSql('insert-f07');
                $dbc->setRecordsetArray($insertData['F07']);
                if (!$dbc->Execute()) {
                    throw new WtDbException(E_DB_EXECUTE_ERR);
                }
                // F08受注詳細テーブルに登録
                $dbc->setSelectSql('insert-f08');
                foreach ($insertData['F08'] as $insertF08) {
                    $dbc->setRecordsetArray($insertF08);
                    if (!$dbc->Execute()) {
                        throw new WtDbException(E_DB_EXECUTE_ERR);
                    }
                }
                if (isset($insertData['F08DGC'])) {
                    // F08デジタルギフトコード受注明細情報
                    $dbc->setSelectSql('insert-f08dgc');
                    foreach ($insertData['F08DGC'] as $insertF08dgc) {
                        $dbc->setRecordsetArray($insertF08dgc);
                        if (!$dbc->Execute()) {
                            throw new WtDbException(E_DB_EXECUTE_ERR);
                        }
                    }
                }
                // F17のしテーブルに登録
                $dbc->setSelectSql('insert-f17');
                $dbc->setRecordsetArray($insertData['F17']);
                if (!$dbc->Execute()) {
                    throw new WtDbException(E_DB_EXECUTE_ERR);
                }
                if (isset($insertData['F87'])) {
                    // GMOクレジットカード決済状況
                    $dbc->setSelectSql('insert-f87');
                    $dbc->setRecordsetArray($insertData['F87']);
                    if (!$dbc->Execute()) {
                        throw new WtDbException(E_DB_EXECUTE_ERR);
                    }
                }
            }
            foreach ($this->pointUseData as $gcNo => $point) {
                $dbc->setRecordsetArray([
                    'ID' => MOD_SHOP_ID,
                    'PG' => MOD_PGM_ID,
                    'DATE' => $this->_orderDate,
                    'GCNO' => $gcNo,
                    'POINT' => $point,
                ]);
                //// M01の残ポイントを更新
                //$dbc->setSelectSql('m01-use-point');
                //if (!$dbc->Execute()) {
                //    throw new WtDbException(E_DB_EXECUTE_ERR);
                //}
                // F00の残ポイントを更新
                $dbc->setSelectSql('f00-use-point');
                if (!$dbc->Execute()) {
                    throw new WtDbException(E_DB_EXECUTE_ERR);
                }
            }
            if (!$dbc->ConnCommit()) {
                WtApp::getLogger()->error('COMMIT ERROR:トランザクションの確定処理に失敗しました。');
                throw new WtDbException(E_DB_EXECUTE_ERR, 1);
            }
        } catch (Exception $e) {
            if ($e->getCode() != 1) {
                $dbc->ConnRollBack();
            }
            $errorCodeArray['errorMessage'] = 'お申し込み確定処理に失敗いたしました。';
        }
        return $errorCodeArray;
    }

    /**
     * 注文完了メール送信処理
     *
     * @return void なし
     */
    public function sendSuccessMail()
    {
        // セッションから券種グループを取得
        $kenshuGroup = $this->gcInfo->kenshuGroup;
        $mailTemplateFile = 'OrderComplete.tpl';

        // メール文面に注文データをセット
        // 買い物かご内の商品のサービス許可状況
        // ※1つでも買い物かご内に有効な商品がある場合は有効とする
        $shohinFlags = array(
            ShohinInformation::HOSO_FLG          => false,
            ShohinInformation::NOSHI_FLG         => false,
            ShohinInformation::GREETING_CARD_FLG => false,
            ShohinInformation::TOKUSHU_FLG       => false,
        );
        $shohinInfoList = $this->getShohinInfoList();
        foreach ($shohinInfoList as $shohinInfo) {
            foreach ($shohinFlags as $k => $v) {
                if (!$v && $shohinInfo[$k]) {
                    $shohinFlags[$k] = true;
                }
            }
        }
        $useGcNoList = [];
        foreach ($this->_insertData as $insertData) {
            foreach ($insertData['F08'] as $paramsF08) {
                if ($paramsF08['F08GCNO'] && !in_array($paramsF08['F08GCNO'], $useGcNoList)) {
                    $useGcNoList[] = $paramsF08['F08GCNO'];
                }
            }
        }
        $chumonsha = $this->getChumonshaInfo();
        $mailTo = $chumonsha[Chumonsha::EMAIL_ADDRESS];
        $renderer = WtApp::getMailRenderer($mailTemplateFile, $kenshuGroup);
        $renderer->setAttribute('shohin_flags',      $shohinFlags);
        $renderer->setAttribute('shohin_info_list',  $shohinInfoList);
        $renderer->setAttribute('chumonsha_info',    $chumonsha);
        $renderer->setAttribute('okurinushi_info',   $this->getOkurinushiInfo());
        $renderer->setAttribute('otodokesaki_list',  $this->getOtodokesakiList());
        $renderer->setAttribute('giftservice_info',  $this->getGiftServiceInfo());
        $renderer->setAttribute('hososhi_list',      $this->getMasterData('HOSO', true));
        $renderer->setAttribute('noshi_list',        $this->getMasterData('NOSI', true));
        $noshiDetailKey = 'NOSD';
        $renderer->setAttribute('noshi_detail_list', $this->getMasterData($noshiDetailKey, true));
        $renderer->setAttribute('greeting_list',     $this->getMasterData('GREE', true));
        $renderer->setAttribute('creditcard_info',   $this->getCreditcardInfo());
        //$renderer->setAttribute('login_user_id',     $this->gcInfo->maincardNo);
        $renderer->setAttribute('giftcards',     implode(',', $useGcNoList));
        $renderer->setAttribute('gc_use_point', array_sum($this->pointUseData));
        $renderer->setAttribute('total_order_point', $this->getTotalOrderPoint());
        $renderer->setAttribute('total_remain_point', $this->gcInfo->usablePoints);
        $renderer->setAttribute('order_no_list',     $this->_orderNoList);
        // メール本文生成
        $settings = WtApp::getConfig('settings');
        $mailSubject  = $settings['order_mail_subject'];
        $mailFromName = $settings['order_mail_from_name'];
        $mailFrom     = $settings['order_mail_from_address'];
        $replyTo      = $settings['order_mail_reply_to_address'];
        $errorsTo     = $settings['order_mail_errors_to_address'];
        $bccAddress   = $settings['order_mail_bcc'];
        $text = $renderer->fetchResult();
        // メール送信
        $mailLog = sprintf('%smail/log/%s/OrderComplete_%s.log', WT_ROOT_DIR, $kenshuGroup, date('Ymd'));
        $wtMailer = new WtMail();
        $wtMailer
            ->setFrom($mailFrom, $mailFromName)
            ->addTo($mailTo)
            ->setSubject($mailSubject)
            ->addBcc($bccAddress)
            ->addExtraHeader('Reply-To: '  . $replyTo)
            ->addExtraHeader('Errors-To: ' . $errorsTo)
            ->setBodyText($text)
            ->setLogFileName($mailLog)
            ->send();
    }

    public function sendDgcMail($dgcResults)
    {
        $kenshuGroup = $this->gcInfo->kenshuGroup;
        $mailTemplateFile = 'OrderDgcComplete.tpl';
        $chumonsha = $this->getChumonshaInfo();
        $mailTo = $chumonsha[Chumonsha::EMAIL_ADDRESS];
        $settings = WtApp::getConfig('settings');
        $mailSubject  = 'デジタルギフトコードのお知らせ';
        $mailFromName = $settings['order_mail_from_name'];
        $mailFrom     = $settings['order_mail_from_address'];
        $replyTo      = $settings['order_mail_reply_to_address'];
        $errorsTo     = $settings['order_mail_errors_to_address'];
        $bccAddress   = $settings['order_mail_bcc'];
        $mailLog = sprintf('%smail/log/%s/OrderDgcComplete_%s.log', WT_ROOT_DIR, $kenshuGroup, date('Ymd'));
        $wtMailer = new WtMail();
        $wtMailer
            ->setFrom($mailFrom, $mailFromName)
            ->addTo($mailTo)
            ->setSubject($mailSubject)
            ->addBcc($bccAddress)
            ->addExtraHeader('Reply-To: '  . $replyTo)
            ->addExtraHeader('Errors-To: ' . $errorsTo)
            ->setLogFileName($mailLog)
        ;
        foreach ($dgcResults as $orderNo => $dgcRows) {
            $renderer = WtApp::getMailRenderer($mailTemplateFile);
            // 管理画面からの再送信もあるので注文フロー特有のクラスや定数は使用しない
            $renderer->setAttribute('chumonsha_sei', $chumonsha[Chumonsha::SEI_KANJI]);
            $renderer->setAttribute('chumonsha_mei', $chumonsha[Chumonsha::MEI_KANJI]);
            // 20230623 注文ごとに1通のメール送信に変更
            $renderer->setAttribute('order_no', $orderNo);
            foreach ($dgcRows as $k => $dgcRow) {
                $dgcRows[$k]['DGC_INFO'] = getDgcViewContents($dgcRow);
            }
            $renderer->setAttribute('dgc_info_list', $dgcRows);
            $wtMailer
                ->setBodyText($renderer->fetchResult())
                ->send();
        }
    }

    /**
     * 管理者にメールを送信する条件判定
     *
     * @return bool
     */
    public function hasErrorsDuringRestoration()
    {
        $gcResults = $this->giftcardResults;
        //$this->getCreditcardObj()->getOrderId()
        return !empty($this->alertErrors)
            || !empty($gcResults['httperr.rollback']) // 障害取消時のHTTPエラー
            || !empty($gcResults['failure.rollback']) // 障害取消エラー
            || !empty($gcResults['httperr.cancel'])   // 取消時のHTTPエラー
            || !empty($gcResults['failure.cancel']);  // 取消エラー
    }

    /**
     * 管理者へのメールを送信
     *
     * @param array $errorCodeArray
     *
     * @return void
     */
    public function sendErrorAdminMail($errorCodeArray)
    {
        // メール文面に注文データをセット
        $renderer = WtApp::getMailRenderer(ORDER_FAILURE_ADMIN_MAIL_TEMPLATE);
        $creditOrderId = $this->getCreditcardObj()->getOrderId(); // クレジット決済処理が成功した場合に設定されている。
        $gcResults = $this->giftcardResults;
        $bodyLines = array();
        // 成功した箇所のメール作成処理
        if ($creditOrderId || !empty($gcResults['success.use'])) {
            $bodyLines[] = '下記処理が成功しています。';
        }
        if ($creditOrderId) {
            $bodyLines[] = '　クレジットカード与信処理（取り消し処理は行っておりません。）';
            $bodyLines[] = "　　成功したクレジットカード管理番号：「{$creditOrderId}」";
        }
        if (!empty($gcResults['success.use'])) {
            $bodyLines[] = '　ギフトカード減算処理';
            foreach ($gcResults['success.use'] as $gcNo => $gcParams) {
                $bodyLines[] = "　　成功したギフトカード番号：「{$gcNo}」";
                $bodyLines[] = "　　成功したギフトカード取引番号：「{$gcParams->req['sc']}」";
                $bodyLines[] = "　　成功したギフトカード減算ポイント：「{$gcParams->req['up']}」";
            }
        }
        if (!empty($gcResults['success.rollback'])) {
            $bodyLines[] = '　ギフトカード障害取り消し処理';
            foreach ($gcResults['success.rollback'] as $gcNo => $gcParams) {
                $bodyLines[] = "　　成功したギフトカード番号：「{$gcNo}」";
                $bodyLines[] = "　　成功したギフトカード取引番号：「{$gcParams->req['sc']}」";
                $bodyLines[] = "　　成功したギフトカード減算ポイント：「{$gcParams->req['up']}」";
            }
        }
        if (!empty($gcResults['success.cancel'])) {
            $bodyLines[] = '　ギフトカード取り消し処理';
            foreach ($gcResults['success.cancel'] as $gcNo => $gcParams) {
                $bodyLines[] = "　　成功したギフトカード番号：「{$gcNo}」";
                $bodyLines[] = "　　成功したギフトカード取引番号：「{$gcParams->req['sc']}」";
                $bodyLines[] = "　　成功したギフトカード減算ポイント：「{$gcParams->req['up']}」";
                if (isset($gcResults['success.use'][$gcNo])) {
                    unset($gcResults['success.use'][$gcNo]);
                }
            }
        }
        // 失敗した箇所のメール作成処理
        // トリガーとなったエラー内容
        if (!empty($errorCodeArray)) {
            $bodyLines[] = '';
            foreach ($errorCodeArray as $errorMessage) {
                $bodyLines[] = "トリガーエラー：「 {$errorMessage} 」";
            }
        }
        // ●減算エラー
        if (!empty($gcResults['httperr.use'])) {
            // HTTPレスポンスエラー
            $gc = array_shift($gcResults['httperr.use']);
            $bodyLines[] = '';
            $bodyLines[] = '下記ギフトカードの減算処理中にHTTPレスポンスエラーが発生しました';
            $bodyLines[] = "　ギフトカード番号：「{$gc->req['cn']}」";
            $bodyLines[] = "　取引番号：「{$gc->req['sc']}」";
            $bodyLines[] = "　使用ポイント：「{$gc->req['up']}」";
        }
        // ●障害取消エラー
        if (!empty($gcResults['httperr.rollback'])) {
            // HTTPレスポンスエラー
            $gc = array_shift($gcResults['httperr.rollback']);
            $bodyLines[] = '';
            $bodyLines[] = '下記ギフトカードの障害取り消し処理中にHTTPレスポンスエラーが発生しました';
            $bodyLines[] = "　ギフトカード番号：「{$gc->req['cn']}」";
            $bodyLines[] = "　取引番号：「{$gc->req['sc']}」";
        } elseif (!empty($gcResults['failure.rollback'])) {
            // APIエラー
            $gc = array_shift($gcResults['failure.rollback']);
            $bodyLines[] = '';
            $bodyLines[] = '下記ギフトカードの障害取り消しに失敗しています';
            $bodyLines[] = "　ギフトカード番号：「{$gc->req['cn']}」";
            $bodyLines[] = "　取引番号：「{$gc->req['sc']}」";
            $bodyLines[] = "　使用ポイント：「{$gc->req['up']}」";
            $bodyLines[] = "　エラー内容：「{$gc->res->errorCd}」";
        }
        // ●取消エラー
        if (!empty($gcResults['failure.cancel'])) {
            foreach ($gcResults['failure.cancel'] as $gc) {
                $bodyLines[] = '';
                $bodyLines[] = '下記ギフトカードの取り消しに失敗しています';
                $bodyLines[] = "　ギフトカード番号：「{$gc->req['cn']}」";
                $bodyLines[] = "　取引番号：「{$gc->req['sc']}」";
                $bodyLines[] = "　使用ポイント：「{$gc->req['up']}」";
                $bodyLines[] = "　エラー内容：「{$gc->res->errorCd}」";
            }
        }
        if (!empty($gcResults['httperr.cancel'])) {
            foreach ($gcResults['httperr.cancel'] as $gc) {
                $bodyLines[] = '';
                $bodyLines[] = '下記ギフトカードの取り消し処理中にHTTPレスポンスエラーが発生しました';
                $bodyLines[] = "　ギフトカード番号：「{$gc->req['cn']}」";
                $bodyLines[] = "　取引番号：「{$gc->req['sc']}」";
                $bodyLines[] = "　使用ポイント：「{$gc->req['up']}」";
            }
        }
        if (!empty($this->alertErrors)) {
            // 商品在庫戻し失敗など
            $bodyLines[] = '';
            foreach ($this->alertErrors as $errorMessage) {
                $bodyLines[] = $errorMessage;
            }
        }
        $renderer->setAttribute('mailBody', implode("\n", $bodyLines));
        $renderer->setAttribute('chumonsha_info', $this->getChumonshaInfo());
        $mailBody = $renderer->fetchResult();
        $settings = WtApp::getConfig('settings');
        $wtMailer = new WtMail();
        $wtMailer
            ->setFrom($settings['order_mail_from_address'])
            ->addTo($settings['order_mail_errors_to_address'])
            ->setSubject('【スマートギフト】申込確定処理が失敗しました')
            ->addExtraHeader('Reply-To: '  . $settings['order_mail_from_address'])
            ->addExtraHeader('Errors-To: ' . $settings['order_mail_errors_to_address'])
            ->setBodyText($mailBody)
            ->setLogFileName(sprintf(ORDER_FAILURE_ADMIN_MAIL_LOG, WT_ROOT_DIR, date('Ymd')))
            ->send();
    }

    public function sendDgcErrorAdminMail($cancelNgInfo)
    {
        $bodyLines = [
            '下記デジタルギフトコードの取り消しに失敗しています。（取消失敗または取消不可）',
            '個別に該当デジタルギフトコードを確認し、必要に応じて復元処理を行ってください。',
        ];
        foreach ($cancelNgInfo as $f08DgcRow) {
            $bodyLines[] = '';
            $bodyLines[] = sprintf('デジタルギフトコード種別：%s', $f08DgcRow['DGC_NAME']);
            $bodyLines[] = sprintf('　商品番号：%s', $f08DgcRow['F08SHOHNNO']);
            $bodyLines[] = sprintf('　デジタルギフトコードストックNo：%s', $f08DgcRow['DGC_STKNO']);
            $bodyLines[] = sprintf('　カード番号：%s', $f08DgcRow['F08CARDNO']);
            $bodyLines[] = sprintf('　PIN：%s', $f08DgcRow['F08PIN']);
            $bodyLines[] = sprintf('　有効期限：%s', $f08DgcRow['F08EXPRIREDATE']);
            $bodyLines[] = sprintf('　金額：%s', number_format($f08DgcRow['F08BALANCE']));
        }
        $renderer = WtApp::getMailRenderer(ORDER_FAILURE_ADMIN_MAIL_TEMPLATE);
        $renderer->setAttribute('mailBody', implode("\n", $bodyLines));
        $renderer->setAttribute('chumonsha_info', $this->getChumonshaInfo());
        $mailBody = $renderer->fetchResult();
        $settings = WtApp::getConfig('settings');
        $wtMailer = new WtMail();
        $wtMailer
            ->setFrom($settings['order_mail_from_address'])
            ->addTo($settings['order_mail_errors_to_address'])
            ->setSubject('【スマートギフト】申込確定処理が失敗しました')
            ->addExtraHeader('Reply-To: '  . $settings['order_mail_from_address'])
            ->addExtraHeader('Errors-To: ' . $settings['order_mail_errors_to_address'])
            ->setBodyText($mailBody)
            ->setLogFileName(sprintf(ORDER_FAILURE_ADMIN_MAIL_LOG, WT_ROOT_DIR, date('Ymd')))
            ->send();
    }

    public function getInsertData()
    {
        return $this->_insertData;
    }

    public function updateInsertData($orderNo, $key, $data)
    {
        if (!isset($this->_insertData[$orderNo][$key])) {
            throw new Exception();
        }
        $this->_insertData[$orderNo][$key] = $data;
    }

    public function setInsertData($key, $insertData, $force = false)
    {
        $orderNo = end($this->_orderNoList);
        if (!$force && isset($this->_insertData[$orderNo][$key])) {
            // 既存データを上書きしないようにエラーにする
            throw new Exception();
        }
        $this->_insertData[$orderNo][$key] = $insertData;
    }

    public function getGtmLayerTag($shopName, $shopStatus = null)
    {
        $totalPriceWithoutTax = $totalTax = 0;
        $shohinInfoList = $this->getShohinInfoList();
        $items = [];
        foreach ($this->_insertData as $insertData) {
            $f08 = array_shift($insertData['F08']);
            if ($f08['F08SHOTYPE'] !== '1') {
                continue;
            }
            $totalPriceWithoutTax += (int)$f08['F08KAKAKU'];
            $totalTax += (int)$f08['F08TAX'];
            $itemNo = $f08['F08SHOHNNO'];
            if (!isset($items[$itemNo])) {
                $brand = '';
                if (isset($shohinInfoList[$itemNo][ShohinInformation::BRAND_NAME])) {
                    $brand = $shohinInfoList[$itemNo][ShohinInformation::BRAND_NAME];
                }
                $items[$itemNo] = [
                    'item_id' => trim($f08['F08SHOHNCD']), // 商品コード
                    'item_name' => $f08['F08SNAME'],   // 商品名
                    'affiliation' => $shopStatus,      // 出産前後の状態を表示(妊娠中|出産後)
                    'price' => (int)$f08['F08KAKAKU'], // 税抜価格
                    'item_brand' => $brand,
                    'quantity' => 0,
                ];
            }
            $items[$itemNo]['quantity'] += (int)$f08['F08SURYO'];
        }
        $itemNoList = array_keys($items);
        $categoriesList = array_combine($itemNoList, array_pad([], count($itemNoList), []));
        $db = new OrderCommonQuerySel();
        $db->setSelectSql('get_item_category');
        $db->setRecordsetArray(['item_no_list' => $itemNoList]);
        $rs = $db->Execute();
        if ($rs && ($rs->RecordCount() > 0)) {
            while (!$rs->EOF) {
                $itemNo = (string)$rs->Fields('F03SHOHNNO');
                $category = trim($rs->Fields('M04CNAME'));
                if ($category) {
                    $categoriesList[$itemNo][] = $category;
                }
                $rs->MoveNext();
            }
        }
        foreach ($categoriesList as $itemNo => $categories) {
            if (!empty($categories)) {
                foreach ($categories as $index => $category) {
                    $k = 'item_category';
                    if ($index) {
                        $k .= (string)($index + 1);
                    }
                    $items[$itemNo][$k] = $category;
                }
            }
        }
        // 出力データをPHP配列で作ってJSON化してからキーのエンクロージャを除去してJSオブジェクトにする。
        // ※エンクロージャを除去する必要はないとも思うけど一応、、、
        $data = [
            'event' => 'purchase',
            'ecommerce' => [
                'transaction_id' => implode(',', array_keys($this->_insertData)),
                'affiliation' => $shopName,
                'value' => $totalPriceWithoutTax,
                'tax' => $totalTax,
                'shipping' => 0,
                'currency' => 'JPY',
                'items' => array_values($items),
            ],
        ];
        $json = json_encode($data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        // エンクロージャを除去するキー名の配列
        $unencloseKeys = [
            'event',
            'ecommerce',
            'transaction_id',
            'affiliation',
            'value',
            'tax',
            'shipping',
            'currency',
            'coupon',
            'items',
            'item_id',
            'item_name',
            'affiliation',
            'price',
            'item_brand',
            'item_category(?:|[\d]+)',
            'quantity',
        ];
        // エンクロージャの除去
        $jsObject = preg_replace('/"(' . implode('|', $unencloseKeys) . ')"/', '$1', $json);
        return implode("\n", [
            'dataLayer.push({ ecommerce: null });',
            'dataLayer.push(' . $jsObject . ');',
        ]) . "\n";
    }
}
