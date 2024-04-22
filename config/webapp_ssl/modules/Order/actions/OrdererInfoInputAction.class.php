<?php
/**
 * ProjectName : スマートギフトサイト
 * Subsystem   : ギフトカードを利用した商品交換モールサイト
 *
 * @package webapp_ssl
 */

require_once(dirname(__DIR__) . '/common/DgcTrait.php');

/**
 * 注文者情報入力Actionクラス
 *
 * @author  Yuki Tanaka
 * @version Release:<1.0>
 */
class OrdererInfoInputAction extends AbstractOrderAction
{
    use DgcTrait;

    /**
     * 入力パラメータ
     */
    const USE_FLG                 = Chumonsha::USE_FLG;                  // ご利用用途フラグ
    const SEI_KANJI               = Chumonsha::SEI_KANJI;                // 姓漢字
    const MEI_KANJI               = Chumonsha::MEI_KANJI;                // 名漢字
    const SEI_KANA                = Chumonsha::SEI_KANA;                 // 姓かな
    const MEI_KANA                = Chumonsha::MEI_KANA;                 // 名かな
    const ZIP1                    = Chumonsha::ZIP1;                     // 郵便番号１
    const ZIP2                    = Chumonsha::ZIP2;                     // 郵便番号２
    const ADD1                    = Chumonsha::ADD1;                     // 住所１
    const ADD2                    = Chumonsha::ADD2;                     // 住所２
    const ADD3                    = Chumonsha::ADD3;                     // 住所３
    const TEL_SHIGAI              = Chumonsha::TEL_SHIGAI;               // 電話市外
    const TEL_SHINAI              = Chumonsha::TEL_SHINAI;               // 電話市内
    const TEL_KYOKUNAI            = Chumonsha::TEL_KYOKUNAI;             // 電話局内
    const EMAIL_ADDRESS           = Chumonsha::EMAIL_ADDRESS;            // emailアドレス
    const EMAIL_ADDRESS_VERIFY    = Chumonsha::EMAIL_ADDRESS_VERIFY;     // emailアドレス確認用
    const PRIVACY_POLICY_FLG      = Chumonsha::PRIVACY_POLICY_FLG;       // プライバシーポリシーフラグ
    const RINGBELL_INFO_FLG       = Chumonsha::RINGBELL_INFO_FLG;        // リンベルインフォフラグ
    const BIKO                    = Chumonsha::BIKO;                     // 備考
    const OKURINUSHI_FLG          = Okurinushi::OKURINUSHI_FLG;          // 送り主フラグ
    const OKURINUSHI_SEI_KANJI    = Okurinushi::OKURINUSHI_SEI_KANJI;    // 送り主姓漢字
    const OKURINUSHI_MEI_KANJI    = Okurinushi::OKURINUSHI_MEI_KANJI;    // 送り主名漢字
    const OKURINUSHI_SEI_KANA     = Okurinushi::OKURINUSHI_SEI_KANA;     // 送り主姓かな
    const OKURINUSHI_MEI_KANA     = Okurinushi::OKURINUSHI_MEI_KANA;     // 送り主名かな
    const OKURINUSHI_ZIP1         = Okurinushi::OKURINUSHI_ZIP1;         // 送り主郵便番号１
    const OKURINUSHI_ZIP2         = Okurinushi::OKURINUSHI_ZIP2;         // 送り主郵便番号２
    const OKURINUSHI_ADD1         = Okurinushi::OKURINUSHI_ADD1;         // 送り主住所１
    const OKURINUSHI_ADD2         = Okurinushi::OKURINUSHI_ADD2;         // 送り主住所２
    const OKURINUSHI_ADD3         = Okurinushi::OKURINUSHI_ADD3;         // 送り主住所３
    const OKURINUSHI_TEL_SHIGAI   = Okurinushi::OKURINUSHI_TEL_SHIGAI;   // 送り主電話市外
    const OKURINUSHI_TEL_SHINAI   = Okurinushi::OKURINUSHI_TEL_SHINAI;   // 送り主電話市内
    const OKURINUSHI_TEL_KYOKUNAI = Okurinushi::OKURINUSHI_TEL_KYOKUNAI; // 送り主電話局内
    const HOSO_NO                 = GiftService::HOSO_NO;                // 包装番号
    const GREETINGCARD_NO         = GiftService::GREETINGCARD_NO;        // 挨拶状番号
    const NOSHI_NO                = GiftService::NOSHI_NO;               // のし番号
    const NOSHI_SHURUI            = GiftService::NOSHI_SHURUI;           // のし上
    const NOSHI_SONOTA_NAIYO      = GiftService::NOSHI_SONOTA_NAIYO;     // のしその他内容
    const NOSHI_NAME_RIGHT        = GiftService::NOSHI_NAME_RIGHT;       // のし名前右
    const NOSHI_NAME_LEFT         = GiftService::NOSHI_NAME_LEFT;        // のし名前左
    const NOSHI_NAME_FLG          = GiftService::NOSHI_NAME_FLG;         // のし名前フラグ

    protected $_postNames = [
        self::USE_FLG,
        self::SEI_KANJI,
        self::MEI_KANJI,
        self::SEI_KANA,
        self::MEI_KANA,
        self::ZIP1,
        self::ZIP2,
        self::ADD1,
        self::ADD2,
        self::ADD3,
        self::TEL_SHIGAI,
        self::TEL_SHINAI,
        self::TEL_KYOKUNAI,
      //self::EMAIL_ADDRESS,
      //self::EMAIL_ADDRESS_VERIFY,
        self::PRIVACY_POLICY_FLG,
        self::RINGBELL_INFO_FLG,
        self::BIKO,
        self::OKURINUSHI_FLG,
        self::OKURINUSHI_SEI_KANJI,
        self::OKURINUSHI_MEI_KANJI,
        self::OKURINUSHI_SEI_KANA,
        self::OKURINUSHI_MEI_KANA,
        self::OKURINUSHI_ZIP1,
        self::OKURINUSHI_ZIP2,
        self::OKURINUSHI_ADD1,
        self::OKURINUSHI_ADD2,
        self::OKURINUSHI_ADD3,
        self::OKURINUSHI_TEL_SHIGAI,
        self::OKURINUSHI_TEL_SHINAI,
        self::OKURINUSHI_TEL_KYOKUNAI,
        self::HOSO_NO,
        self::GREETINGCARD_NO,
        self::NOSHI_NO,
        self::NOSHI_SHURUI,
        self::NOSHI_SONOTA_NAIYO,
        self::NOSHI_NAME_RIGHT,
        self::NOSHI_NAME_LEFT,
        self::NOSHI_NAME_FLG,
    ];

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        if ($this->_isSubmit('BTN_BACK')) {
            $redirectUrl = WT_URL_BASE_SSL;
            $shohinInfoList = $this->orderCommon->getShohinInfoList();
            if (!empty($shohinInfoList)) {
                // カゴに商品がある場合は最後の商品の詳細ページに戻る
                $shohinInfo = array_pop($shohinInfoList);
                $redirectUrl = $this->getActionUrl('ShohinShosai', 'ShohinShosai') . 'shohin/' . $shohinInfo[ShohinInformation::SHOHIN_NO];
            }
            $controller->redirect($redirectUrl);
            return VIEW_NONE;
        }
        if ($this->_isSubmit('BTN_NEXT')) {
            $orderCommon = $this->orderCommon;
            $postParams = array();
            foreach ($this->_postNames as $name) {
                if ($name === self::EMAIL_ADDRESS) {
                    // メールアドレスは変更不可(名古屋と同仕様)
                    continue;
                }
                $postParams[$name] = $request->getParameter($name);
            }
            try {
                // 入力情報のセッション保存
                $orderCommon->saveChumonshaInfo($postParams);
                $orderCommon->saveOkurinushiInfo($postParams);
                $orderCommon->saveGiftServiceInfo($postParams);
                // USE_FLG == '0' : 自宅用
                $buyItems = $request->getAttribute('self_buy_items');
                $otodokesaki = new Otodokesaki();
                foreach ($buyItems as $shohinNo => $buyInfo) {
                    $otodokesaki->addShohin(array(
                        OtodokeShohin::SHOHIN_NO       => $shohinNo,
                        OtodokeShohin::KONYU_SURYO     => $buyInfo['quantity'],
                        OtodokeShohin::KAKAKU_ZEINUKI  => $buyInfo['price'],
                        OtodokeShohin::KAKAKU_TAX      => $buyInfo['tax'],
                        OtodokeShohin::HAISO_KIBO_DATE => $buyInfo['haisodate'],
                    ), true);
                }
                $orderCommon->saveOtodokesakiList(array($otodokesaki->getObjectData(true)), true);
                if ($orderCommon->getTotalOrderPoint() < $this->gcInfo->usablePoints) {
                    $user->setAttribute('__payment_ok__', false);
                    $request->setError('use-point-remain', '商品の合計ポイントがご利用可能ポイント以上になるようにしてください。'); // @TODO メッセージどうする？
                    return $this->handleError($controller, $request, $user);
                }
                if (!$this->settings['billable']) { // 課金不可の場合に上限チェック
                    // ギフトカード合計ポイントが支払金額に達しているかをチェック
                    if ($orderCommon->getTotalOrderPoint() > $this->gcInfo->usablePoints) {
                        $request->setError('use-point-over', '商品の合計ポイントがご利用可能ポイントを超過しています。');
                        $user->setAttribute('__payment_ok__', false);
                        return $this->handleError($controller, $request, $user);
                    }
                    $user->setAttribute('__payment_ok__', true);
                    $controller->redirect($this->getActionUrl('Order', 'OrderConfirm'));
                    return VIEW_NONE;
                }
                // 課金可能時
                if (!$this->isCreditcardEnable()) {
                    // 注文全体で消費税無料フラグが有効な商品がカードの合計残ポイントより多い場合はクレジットカード不可
                    // @TODO エラーメッセージ変更
                    $request->setError('use-point-over', '商品の合計ポイントがご利用可能ポイントを超過しています。');
                    $user->setAttribute('__payment_ok__', false);
                    return $this->handleError($controller, $request, $user);
                }
                //$user->setGiftcardInfo($this->gcInfo->sync());
                $redirectUrl = $this->getActionUrl('Order', 'CardInfoInput');
                if ($user->getAttribute('fix_flg')
                    && !$request->getAttribute('change_quantity')
                ) {
                    // 確認画面からの遷移で数量の変更などがない場合
                    $redirectUrl = $this->getActionUrl('Order', 'OrderConfirm');
                }
                $user->setAttribute('fix_flg', false);
                $controller->redirect($redirectUrl);
                return VIEW_NONE;
            } catch (Exception $e) {
                // システムエラーに遷移
                $controller->redirect(WT_URL_SYSERROR);
                return VIEW_NONE;
            }
        }
        $controller->redirect($this->getActionUrl('Order', 'OrdererInfoInput'));
        return VIEW_NONE;
    }

    /**
     * {@inheritdoc}
     */
    function getDefaultView($controller, $request, $user)
    {
        if (!$user->getAttribute('fix_flg')) {
            $user->setAttribute('__payment_ok__', false);
        }
        $shohinInfoList = $this->orderCommon->getShohinInfoList();
        // 商品情報配列 @see ShohinInformation.php
        // Array(
        //     商品番号 => Array(
        //         'shohin_no'                  => 商品番号               ShohinInformation::SHOHIN_NO
        //         'shohin_code'                => 商品コード             ShohinInformation::SHOHIN_CODE
        //         'brand_name'                 => ブランド名             ShohinInformation::BRAND_NAME
        //         'shohin_name'                => 商品名                 ShohinInformation::SHOHIN_NAME
        //         'shohin_name_kana'           => 商品名ふりがな         ShohinInformation::SHOHIN_NAME_KANA
        //         'haiso_keitai'               => 配送形態               ShohinInformation::HAISO_KEITAI
        //         'haiso_moto_shikibetsu_code' => 配送元識別コード       ShohinInformation::HAISO_MOTO_SHIKIBETSU_CODE
        //         'kakaku_zeinuki'             => バリューポイント       ShohinInformation::KAKAKU_ZEINUKI
        //         'kisetsu_shohin_flg'         => 季節商品フラグ         ShohinInformation::KISETSU_SHOHIN_FLG
        //         'hyojun_nouki'               => 標準納期               ShohinInformation::HYOJUN_NOKI
        //         'gentei_suryo'               => 限定数                 ShohinInformation::GENTEI_SURYO
        //         'nokori_suryo'               => 残り数                 ShohinInformation::NOKORI_SURYO
        //         'kanou_suryo'                => 購入可能数量           ShohinInformation::KANOU_SURYO
        //         'kisetsu_haisoudate'         => 季節商品配送日設定     ShohinInformation::KISETSU_DATE
        //         'houso_flg'                  => 包装フラグ             ShohinInformation::HOSO_FLG
        //         'noshi_flg'                  => のし形状区分           ShohinInformation::NOSHI_FLG
        //         'greeting_card_flg'          => メッセージカードフラグ ShohinInformation::GREETING_CARD_FLG
        //         'haisositei_nolimit_flg'     => 配送指定不可除外フラグ ShohinInformation::HAISOSITEI_NOLIMIT_FLG
        //         'tokushu_flg'                => 特殊商品フラグ         ShohinInformation::TOKUSHU_FLG
        //         'kikan_gentei_haiso_keitai'  => 期間限定配送携帯       ShohinInformation::KIKAN_GENTEI_HAISO_KEITAI
        //         'kikan_gentei_flg'           => 期間限定フラグ         ShohinInformation::KIKAN_GENTEI_FLG
        //         'disp_haiso_keitai'          => 表示配送形態           ShohinInformation::DISP_HAISO_KEITAI
        //         'cannot_delivery_date'       => 配送指定不可日         ShohinInformation::CANNOT_DELIVERY_DATE
        //         'cannot_delivery_sentence'   => 配送指定不可文言       ShohinInformation::CANNOT_DELIVERY_SENTENCE
        //         'hanbai_sdate'               => 販売期間開始日         ShohinInformation::HANBAI_SDATE
        //         'hanbai_edate'               => 販売期間終了日         ShohinInformation::HANBAI_EDATE
        //         'taxfree_flg'                => 非課税フラグ           ShohinInformation::TAXFREE_FLG
        //         'otodoke_kano_date'          => お届け可能日           ShohinInformation::OTODOKE_KANO_DATE
        //         'otodoke_kibo_date'          => お届け希望日           ShohinInformation::OTODOKE_KIBO_DATE
        //         'konyu_suryo'                => 商品の交換数           ShohinInformation::KONYU_SURYO ※「自宅用」の場合に一時利用
        //     ),
        // )
        // 追加する商品番号 (商品詳細ページからのリダイレクト時に付与)
        $shohinNo = $request->getParameter('add');
        // ※数字以外のパラメータがURLに付与されている場合は無視
        if (ctype_digit($shohinNo)) {
            // 商品追加 
            $orderNum = $request->getParameter('n');
            if (!ctype_digit($orderNum) || !intval($orderNum)) {
                $orderNum = 1;
            }
            try {
                $shohinCommonObj = new NormalShohin($shohinNo, false, $this->getShohinKenshuGroup());
                $shohinName = $shohinCommonObj->get('M02SNAME');
                // エラーがある場合は一時セッションに保存。
                if ($shohinCommonObj->isBeforeOnSale()   // 販売開始日よりも前
                    || $shohinCommonObj->isAfterOnSale() // 販売終了日よりも後
                    || !$shohinCommonObj->existsZaiko()  // 在庫切れ
                    || (($shohinCommonObj->get('M02KISEFLG') == '1') && ($shohinCommonObj->getKisetsuHaisouDate() === false))
                ) {
                    $user->setActionParam('CART_ERROR', sprintf('%sは現在購入できません。', $shohinName));
                } elseif (isset($shohinInfoList[$shohinNo])) {
                    $user->setActionParam('CART_ERROR', sprintf('%sは既に買い物かごに入っております。', $shohinName));
                } else {
                    $shohinObj = new ShohinInformation();
                    $shohinObj->setShohinDataFromDB($shohinCommonObj->getCart());
                    $shohinData = $shohinObj->getObjectData();
                    $isShohinAddable = true;
                    if (!$this->settings['billable']) { // 課金不可の場合に上限チェック
                        // 所有残ポイント
                        $giftcardPoint = $this->gcInfo->usablePoints;
                        // ギフトの場合は数量選択などは後続画面で行うので単価のチェックだけ行う。
                        // ※商品単価が残ポイントより多い場合はエラー
                        if ($shohinData[ShohinInformation::KAKAKU_ZEINUKI] > $giftcardPoint) {
                            $user->setActionParam('CART_ERROR', sprintf('%sはご利用可能ポイントでは購入できません。', $shohinName));
                            $isShohinAddable = false;
                        }
                    }
                    if ($isShohinAddable) {
                        if ($orderNum > $shohinData[ShohinInformation::KANOU_SURYO]) {
                            $user->setActionParam('CART_ERROR', sprintf('%sは1回の注文で%d個まで購入できます。数量を変更しました。', $shohinName, $shohinData[ShohinInformation::KANOU_SURYO]));
                            $orderNum = $shohinData[ShohinInformation::KANOU_SURYO];
                        }
                        $shohinData[ShohinInformation::KONYU_SURYO] = $orderNum;
                        $shohinData[ShohinInformation::OTODOKE_KIBO_DATE] = '';
                        //>>> デジタルギフトコード対応
                        if ($shohinData[ShohinInformation::HYOJI_KEY2] === 'DGC') {
                            $shohinData[ShohinInformation::DGC_INFO] = $this->getDgcInfo($shohinNo);
                        }
                        //<<<
                        // クレジット利用時の明細不具合解消のためにとりあえず非課税商品→課税商品になるように
                        //$shohinData[ShohinInformation::TAXFREE_FLG] == '1' >> 非課税商品
                        if (empty($shohinInfoList) || ($shohinData[ShohinInformation::TAXFREE_FLG] !== '1')) {
                            // 課税商品であればそのまま末尾に追加
                            $shohinInfoList[$shohinNo] = $shohinData;
                        } else {
                            // 非課税商品は非課税商品の末尾に追加（課税商品の前）
                            $tempInfoList = [];
                            foreach ($shohinInfoList as $k => $v) {
                                if ($shohinNo && ($v[ShohinInformation::TAXFREE_FLG] !== '1')) {
                                    $tempInfoList[$shohinNo] = $shohinData;
                                    $shohinNo = null;
                                }
                                $tempInfoList[$k] = $v;
                            }
                            if ($shohinNo) {
                                $tempInfoList[$shohinNo] = $shohinData;
                            }
                            $shohinInfoList = $tempInfoList;
                        }
                        $this->orderCommon->saveShohinInfoList($shohinInfoList);
                    }
                }
            } catch(Exception $e) {
                WtApp::getLogger()->error($e->getMessage());
            }
            // 商品番号を除去してリダイレクト。
            $controller->redirect($this->getActionUrl('Order', 'OrdererInfoInput'));
            return VIEW_NONE;
        }
        if (empty($shohinInfoList)) {
            // 買い物かご商品情報が存在しない場合
            $request->setError('CART_ERROR', '買い物かごに商品はありません。商品をお選びください。');
        }
        $message = $user->getActionParam('CART_ERROR');
        if ($message) {
            $request->setError('CART_ERROR', $message);
            $user->setActionParam('CART_ERROR', null);
        }
        $orderCommon = $this->orderCommon;
        $postParams = array_merge(
            array_combine($this->_postNames, array_pad([], count($this->_postNames), '')),
            $orderCommon->getChumonshaInfo(),
            $orderCommon->getOkurinushiInfo(),
            $orderCommon->getGiftServiceInfo(),
            [self::EMAIL_ADDRESS => $this->gcInfo->email],
        );
        $kenshuGroup = $this->gcInfo->kenshuGroup;
        // 自宅用の場合のお届け日や数量のパラメータは特例として買い物かごの商品情報に設定している
        $cartFingerprint = $orderCommon->buildFingerprint($shohinInfoList);
        if ($user->getAttribute('__init_order__') != $cartFingerprint) {
            if (!$user->getAttribute('__init_order__')) {
                include_once(WT_ROOT_DIR . 'webapp_ssl/modules/Member/querys/MemberQuerySel.class.php');
                $db = new MemberQuerySel();
                $db->setSelectSql('get-register-info');
                $db->setRecordsetArray([
                    'GCNO' => $this->gcInfo->maincardNo,
                ]);
                $rs = $db->Execute();
                if ($rs && $rs->RecordCount()) {
                    $postParams[self::SEI_KANJI] = $rs->Fields('F01SEI');
                    $postParams[self::MEI_KANJI] = $rs->Fields('F01MEI');
                    $postParams[self::SEI_KANA] = $rs->Fields('F01SEIKN');
                    $postParams[self::MEI_KANA] = $rs->Fields('F01MEIKN');
                    $postParams[self::ZIP1] = $rs->Fields('F01ZIP1');
                    $postParams[self::ZIP2] = $rs->Fields('F01ZIP2');
                    $postParams[self::ADD1] = $rs->Fields('F01ADD1');
                    $postParams[self::ADD2] = $rs->Fields('F01ADD2');
                    $postParams[self::ADD3] = $rs->Fields('F01ADD3');
                    $postParams[self::TEL_SHIGAI] = $rs->Fields('F01TEL11');
                    $postParams[self::TEL_SHINAI] = $rs->Fields('F01TEL12');
                    $postParams[self::TEL_KYOKUNAI] = $rs->Fields('F01TEL13');
                    $postParams[self::EMAIL_ADDRESS] = $rs->Fields('M01EMAILPC');
                    $orderCommon->saveChumonshaInfo($postParams,  false);
                    $this->gcInfo->email = $postParams[self::EMAIL_ADDRESS];
                    $user->setGiftcardInfo($this->gcInfo);
                }
            }
            // 買い物カゴ内容に変化があった場合に初期化
            try {
                $ignoreSessionList = array(
                    'Chumonsha',
                    'Okurinushi',
                    'GiftService',
                );
                $orderCommon->removeOrderSession($ignoreSessionList); // 注文系のセッションを削除
                // 最短お届け日が算出できなかった場合にエラーメッセージを表示
                // ※標準納期が存在する事が前提
                foreach ($shohinInfoList as $shohinNo => $shohinInfo) {
                    if ($shohinInfo[ShohinInformation::HYOJUN_NOKI] && !$shohinInfo[ShohinInformation::OTODOKE_KANO_DATE]) {
                        $message = implode('', array(
                            '申し訳ございません。',
                            sprintf('%sは現在申込み期間が終了しております。', $shohinInfo[ShohinInformation::SHOHIN_NAME]),
                            '恐れ入りますが再度商品の選び直しをお願いします。',
                        ));
                        $request->setError('CANT_DELIVERY_ERROR_' . $shohinNo, $message);
                    }
                }
                if (empty($shohinInfoList)) {
                    $user->removeAttribute('__init_order__');
                } else {
                    $user->setAttribute('__init_order__', $cartFingerprint);
                }
            } catch (Exception $e) {
                $orderCommon->removeOrderSession();     // 注文系のセッションを削除
                $controller->redirect(WT_URL_SYSERROR); // システムエラーに遷移
                return VIEW_NONE;
            }
        } elseif ($this->getRequestMethods() == $request->getMethod()) {
            // 入力値の送信時
            foreach ($this->_postNames as $name) {
                $postParams[$name] = $request->getParameter($name);
            }
            // 画面のリロード用に一応セッション保存(検証済みフラグをOFFにする)
            $orderCommon->saveChumonshaInfo($postParams,  false);
            $orderCommon->saveOkurinushiInfo($postParams, false);
            $orderCommon->saveGiftServiceInfo($postParams, false);
        }
        // 買い物かご内の商品のサービス許可状況
        // ※1つでも買い物かご内に有効な商品がある場合は有効とする
        $flags = array(
            ShohinInformation::HOSO_FLG          => false,
            ShohinInformation::NOSHI_FLG         => false,
            ShohinInformation::GREETING_CARD_FLG => false,
            ShohinInformation::TOKUSHU_FLG       => false,
        );
        // 個人購入用
        $shohinPoint = 0;
        foreach ($shohinInfoList as $shohinInfo) {
            foreach ($flags as $k => $v) {
                if (!$v && $shohinInfo[$k]) {
                    $flags[$k] = true;
                }
            }
            $shohinPoint += ($shohinInfo[ShohinInformation::KAKAKU_ZEINUKI] * $shohinInfo[ShohinInformation::KONYU_SURYO]);
        }
        $request->setAttribute('shohin_point', $shohinPoint);
        $request->setAttribute('hoso_flg',     $flags[ShohinInformation::HOSO_FLG]);
        $request->setAttribute('noshi_flg',    $flags[ShohinInformation::NOSHI_FLG]);
        $request->setAttribute('greeting_flg', $flags[ShohinInformation::GREETING_CARD_FLG]);
        $request->setAttribute('tokushu_flg',  $flags[ShohinInformation::TOKUSHU_FLG]);
        $request->setAttribute('shohin_info_list', $shohinInfoList);
        $request->setAttribute('hososhi_list', $flags[ShohinInformation::HOSO_FLG] ? $orderCommon->getMasterData('HOSO') : array());
        $request->setAttribute('greeting_list', $flags[ShohinInformation::GREETING_CARD_FLG] ? $orderCommon->getMasterData('GREE') : array());
        $noshiList = $noshiDetailList = array();
        if ($flags[ShohinInformation::NOSHI_FLG]) {
            $noshiList = $orderCommon->getMasterData('NOSI');
            $noshiDetailList = $orderCommon->getMasterData('NOSD');
        }
        $request->setAttribute('noshi_list', $noshiList);
        $request->setAttribute('noshi_detail_list', $noshiDetailList);
        $request->setAttribute('wt__posts', $postParams);
        $haisoLimitY = null;
        $haisoLimitM = null;
        $haisoLimitD = null;
        $haisoLimitExists = false;
        if (preg_match('/\A([\d]{4})-?([\d]{2})-?([\d]{2})\z/', $this->settings['delivery_date_deadline'], $matches)) {
            $haisoLimitY = strval(intval($matches[1]));
            $haisoLimitM = strval(intval($matches[2]));
            $haisoLimitD = strval(intval($matches[3]));
            $haisoLimitExists = true;
        }
        $request->setAttribute('haiso_exists', $haisoLimitExists);
        $request->setAttribute('haiso_limit_y', $haisoLimitY);
        $request->setAttribute('haiso_limit_m', $haisoLimitM);
        $request->setAttribute('haiso_limit_d', $haisoLimitD);
        $request->setAttribute('pref_list', WtUtil::getPrefOptions('都道府県選択'));
        return VIEW_INPUT;
    }

    /**
     * {@inheritdoc}
     */
    function validate($controller, $request, $user)
    {
        $shohinInfoList = $this->orderCommon->getShohinInfoList();
        if (empty($shohinInfoList)) {
            $controller->redirect($this->getActionUrl('', ''), true); // exit
        }
        $appValidator = $this->_getValidator();
        $requestParameters = $request->getParameters();
        $requestKeys = array_keys($requestParameters);
        // 商品削除 "BTN_DELETE_<商品番号>"が送信されているかチェック
        $deleteButtonNameTemp = preg_grep('/\ABTN_DELETE_[\d]+(?:|[._]x)\z/', $requestKeys);
        if (!empty($deleteButtonNameTemp)) {
            do {
                $buttonName = array_shift($deleteButtonNameTemp);
                if ($this->_isSubmit($buttonName) && preg_match('/\ABTN_DELETE_([\d]+)(?:|[._]x)\z/', $buttonName, $matches)) {
                    $shohinNo = $matches[1];
                    if (isset($shohinInfoList[$shohinNo])) {
                        unset($shohinInfoList[$shohinNo]);
                    }
                }
            } while (!empty($deleteButtonNameTemp));
            $this->orderCommon->saveShohinInfoList($shohinInfoList);
        } elseif ($this->_isSubmit('BTN_DELETE_ALL')) {
            // 商品を全て削除
            $this->orderCommon->saveShohinInfoList(array());
        } elseif ($this->_isSubmit('BTN_NEXT')) {
            // 注文者情報
            $request->setParameter(self::USE_FLG, '0'); // とりあえず自宅専用(ギフトとの分岐削除)
            //$appValidator->select(self::USE_FLG, '交換商品のご用途', true, array('0', '1')); // '0':自宅 '1':ギフト
            $appValidator->seiKanji(self::SEI_KANJI, 'お名前（姓）', true);
            $appValidator->meiKanji(self::MEI_KANJI, 'お名前（名）', true);
            $appValidator->seiHiragana(self::SEI_KANA, 'ふりがな（姓）', true);
            $appValidator->meiHiragana(self::MEI_KANA, 'ふりがな（名）', true);
            $appValidator->zip3(self::ZIP1, '郵便番号1', true);
            $appValidator->zip4(self::ZIP2, '郵便番号2', true);
            $appValidator->address1(self::ADD1, '都道府県', true);
            $appValidator->address2(self::ADD2, '市区町村 番地', true, true);
            $appValidator->address3(self::ADD3, '建物名 部屋番号', false, true);
            $appValidator->telNumber('telNumber', self::TEL_SHIGAI, self::TEL_SHINAI, self::TEL_KYOKUNAI, '電話番号', true);
            // メールアドレスは変更不可(名古屋と同仕様)
            //$appValidator->emailRfc(self::EMAIL_ADDRESS, 'メールアドレス', true);
            //$appValidator->emailRfc(self::EMAIL_ADDRESS_VERIFY, 'メールアドレス確認', true);
            //if (!$appValidator->hasError(self::EMAIL_ADDRESS) && !$appValidator->hasError(self::EMAIL_ADDRESS_VERIFY)) {
            //    if ($request->getParameter(self::EMAIL_ADDRESS) !== $request->getParameter(self::EMAIL_ADDRESS_VERIFY)) {
            //        $appValidator->setCustomError('emailverify', 'メールアドレスと、メールアドレス確認の値が異なります。');
            //    }
            //}
            $appValidator->select(self::PRIVACY_POLICY_FLG, '個人情報の取り扱い', true, ['0', '1']);
            $appValidator->select(self::RINGBELL_INFO_FLG, 'リンベルからのご案内', false, ['0', '1']);
            //--------------------------------------------------------------
            // ご自宅用
            //--------------------------------------------------------------
            $buyItems = array();
            $totalQuantity = 0;
            $isChangeQuantity = false;
            $haisoSelectList = array();
            foreach ($shohinInfoList as $shohinNo => $shohinInfo) {
                $buyItems[$shohinNo] = array(
                    'quantity'  => 0,
                    'price'     => $shohinInfo[ShohinInformation::KAKAKU_ZEINUKI],
                    'tax'       => 0, // 消費税はクレジットカード払いの場合のみ税率から計算
                    'haisodate' => null,
                    'is_ca'     => in_array($shohinInfo[ShohinInformation::HYOJI_KEY2], ['CA', 'DGC']),
                );
                $shohinName = $shohinInfo[ShohinInformation::SHOHIN_NAME];
                // 商品個数のチェック
                $postName = "quantity_{$shohinNo}";
                $appValidator->z2h($postName)->number(
                    $postName,
                    "{$shohinName}の購入数",
                    true,
                    1,
                    null, //$shohinInfo[ShohinInformation::KANOU_SURYO],
                    false
                );
                $quantity = intval($request->getParameter($postName));
                if (!$appValidator->hasError($postName)) {
                    if ($quantity > $shohinInfo[ShohinInformation::KANOU_SURYO]) {
                        $message = sprintf('%sは1回の注文で%d個まで購入できます。', $shohinName, $shohinInfo[ShohinInformation::KANOU_SURYO]);
                        $appValidator->setCustomError($postName, $message);
                    } else {
                        $buyItems[$shohinNo]['quantity'] = $quantity;
                        $totalQuantity += $quantity;
                    }
                }
                if ($shohinInfoList[$shohinNo][ShohinInformation::KONYU_SURYO] != $quantity) {
                    $isChangeQuantity = true;
                }
                $shohinInfoList[$shohinNo][ShohinInformation::KONYU_SURYO] = $quantity;
                // 配送希望日のチェック
                if (intval($shohinInfo[ShohinInformation::HYOJUN_NOKI])) {
                    $postNameY = "haiso_kibo_{$shohinNo}_year";
                    $postNameM = "haiso_kibo_{$shohinNo}_month";
                    $postNameD = "haiso_kibo_{$shohinNo}_day";
                    $required = ($shohinInfo[ShohinInformation::KISETSU_SHOHIN_FLG] == '1');
                    if (!$required) {
                        $postNameSel = "haiso_select_{$shohinNo}";
                        $haisoSelectList[$shohinNo] = $request->getParameter($postNameSel);
                        $required = ($haisoSelectList[$shohinNo] === '1');
                        if ($required) {
                            $shohinInfoList[$shohinNo][ShohinInformation::OTODOKE_KIBO_DATE] = implode('-', array(
                                $request->getParameter($postNameY),
                                $request->getParameter($postNameM),
                                $request->getParameter($postNameD)
                            ));
                        } else {
                            $request->setParameter($postNameY, '');
                            $request->setParameter($postNameM, '');
                            $request->setParameter($postNameD, '');
                        }
                    }
                    $appValidator->ymdSeparate("haiso_kibo_{$shohinNo}", $postNameY, $postNameM, $postNameD, "{$shohinName}のお届け日", $required);
                    if (!$appValidator->hasError($postNameY) && !$appValidator->hasError($postNameM) && !$appValidator->hasError($postNameD)) {
                        $ymd = implode('-', array(
                            $request->getParameter($postNameY),
                            $request->getParameter($postNameM),
                            $request->getParameter($postNameD)
                        ));
                        if (trim($ymd, '-') !== '') {
                            $haisoLimitYmd = $this->settings['delivery_date_deadline'];
                            // 中野区：配送日指定が可能な期間を注文日を0日目として、30日目までにする
                            $t30daysAfter = strtotime('+30 day');
                            if (!$haisoLimitYmd || (strtotime($haisoLimitYmd) > $t30daysAfter)) {
                                $haisoLimitYmd = date('Y-m-d', $t30daysAfter);
                            }
                            $shohinInfoList[$shohinNo][ShohinInformation::OTODOKE_KIBO_DATE] = $ymd;
                            $shohinObj = new ShohinInformation($shohinInfo);
                            if (!$shohinObj->isValidDeliveryDate($ymd, $haisoLimitYmd)) {
                                $message = sprintf('%sのお届け日に選択できない日付が指定されています。', $shohinName);
                                $appValidator->setCustomError($postNameY, $message);
                            } elseif (isset($buyItems[$shohinNo])) {
                                $buyItems[$shohinNo]['haisodate'] = $ymd;
                            }
                        } else {
                            $shohinInfoList[$shohinNo][ShohinInformation::OTODOKE_KIBO_DATE] = '';
                            $buyItems[$shohinNo]['haisodate'] = '';
                        }
                    }
                }
            }
            $request->setAttribute('haiso_select', $haisoSelectList);
            $request->setAttribute('change_quantity', $isChangeQuantity);
            if (!$appValidator->hasErrors()) {
                if (!$totalQuantity) {
                    $appValidator->setCustomError('shohin-not-selected', '購入する商品を指定してください。');
                } else {
                    $totalPoint = $caPoint = 0;
                    foreach ($buyItems as $item) {
                        $point = ($item['price'] * $item['quantity']);
                        $totalPoint += $point;
                        if ($item['is_ca']) {
                            $caPoint += $point;
                        }
                    }
                    $giftcardPoint = $this->gcInfo->usablePoints;
                    if (!$this->settings['billable'] && ($giftcardPoint < $totalPoint)) {
                        // 課金不可（カード追加やクレジットでの不足分支払不可）
                        $appValidator->setCustomError('use-point-over', '商品の合計ポイントがご利用可能ポイントを超過しています。');
                    }
                    if (!$appValidator->hasErrors()) {
                        // 有効なカード数を算出（承認済み）
                        // ※承認されたカード(status='01')のみ有効期限が切れるとstatus='99'に更新される
                        $approvedCount = 0;
                        foreach ($this->gcInfo->getCardList() as $gcDetail) {
                            if (in_array($gcDetail->status, ['01', '99'], true)) {
                                ++$approvedCount;
                            }
                        }
                        $caPointLimit = (int)$this->settings['ca_point_limit'] * $approvedCount;
                        if ($caPointLimit > 0) {
                            if (($caPoint > 0) && ($caPoint <= $caPointLimit)) {
                                $caPoint += $this->getPastOrderCaPoint($this->gcInfo->maincardNo);
                            }
                            if ($caPoint > $caPointLimit) {
                                $appValidator->setCustomError('ca-point-over', sprintf("金券のお申し込みは、１つのIDにつき%sポイントまでとなります。", number_format($this->settings['ca_point_limit'])));
                            } else {
                                $request->setAttribute('self_buy_items', $buyItems);
                            }
                        } else {
                            $request->setAttribute('self_buy_items', $buyItems);
                        }
                    }
                }
            }
            $noshiNo = $request->getParameter(self::NOSHI_NO);
            if (!$noshiNo || ($noshiNo === '00')) {
                $request->setParameter(self::NOSHI_SHURUI, '');
                $request->setParameter(self::NOSHI_SONOTA_NAIYO, '');
                $request->setParameter(self::NOSHI_NAME_RIGHT, '');
            } else {
                // のし種類などは固定値 @TODO 確認
                $request->setParameter(self::NOSHI_NO, '01');
                $request->setParameter(self::NOSHI_SHURUI, '05'); // 内祝（出産内祝い用）
                $request->setParameter(self::NOSHI_SONOTA_NAIYO, '');
                $appValidator->length(self::NOSHI_NAME_RIGHT, '熨斗（のし）赤ちゃんのお名前', false, null, 25);
                $appValidator->length(self::NOSHI_NAME_LEFT, '熨斗（のし）ふりがな', false, null, 25);
            }
            $this->orderCommon->saveShohinInfoList($shohinInfoList);
        }
        $appValidator->setErrors();
        // 入力情報のセッション保存
        $this->orderCommon->saveChumonshaInfo($request->getParameters());
    }
}
