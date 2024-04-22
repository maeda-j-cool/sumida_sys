<?php
require_once(WT_ROOT_DIR . 'util/Common/actions/RecommendShohin.class.php');
require_once(WT_ROOT_DIR . 'util/Common/actions/ShohinCheckRirekiCookie.class.php');
require_once(WT_ROOT_DIR . 'util/Common/actions/NinkiShohinRanking.class.php');

define("OKINIIRI_LIMIT", 99); // お気に入り登録最大数

class ShohinShosaiAction extends SgAction
{
    /**
     * @var bool 認証が必要な画面かどうか
     */
    protected $_requireAuth = true;

    /**
     * @var bool アクションとモジュール名をセッションに保存するかどうか
     */
    protected $_saveCurrentAction = true;

    /**
     * @var string プログラムID
     */
    protected $_modPg = 'S0207';

    /**
     * @var string デフォルト商品テンプレート名
     */
    const DEFAULT_TEMPLATE_FILE = 'normal_shohin.tpl';

    /**
     * @var string 配送指定不可日期間表示文言商品個別設定
     */
    const SHOHHIN_KOBETU = '0';

    /**
     * @var string 配送指定不可日期間表示文言商品全体設定
     */
    const SHOHHIN_ZENTAI = 1;

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $shohinNo = $request->getParameter('shohin');
        if (!$shohinNo || !ctype_digit($shohinNo)) {
            return $this->handleError($controller, $request, $user);
        }
        $orderNum = $request->getParameter('order_num');
        if (strlen($orderNum)) {
            if (!ctype_digit($orderNum) || !intval($orderNum)) {
                $orderNum = '';
            }
        }
        $controller->redirect($this->getActionUrl('Order', 'OrdererInfoInput') . 'add/' . $shohinNo . '/n/' . $orderNum);
        return VIEW_NONE;
    }

    /**
     * {@inheritdoc}
     */
    function getDefaultView($controller, $request, $user)
    {
        $shohinNo = $request->getParameter('shohin');
        $group = $request->getParameter('group');
        $catId = $request->getParameter('catid');
        $kind = $request->getParameter('kind');
        $giftCardNo = $this->gcInfo->maincardNo;
        $kenshuGroup = $this->getShohinKenshuGroup();
        $request->setAttribute('is_buyable', !!$this->gcInfo->usablePoints);
        // 種別（okiniiri = お気に入りに追加）
        if ($kind === 'okiniiri') {
            // お気に入りに商品番号を登録する
            if ($user->getAttribute('is_virtual_login')) {
                $request->setError('error', 'ログインをしないでご利用される場合はお気に入り登録できません');
            } else {
                $this->_insertOkiniiriShohin($request, $user, $shohinNo, $giftCardNo);
            }
            $request->setAttribute('kind', $kind);
            return VIEW_INPUT;
        }
        try {
            //--------------------------------
            // 商品情報取得
            // 商品が存在しない場合、エラーメッセージを表示
            //--------------------------------
            if (!$shohinNo || !ctype_digit($shohinNo)) {
                throw new Exception();
            }
            $shohinInfo = $this->_getShohin($shohinNo, $request, $kenshuGroup);
        } catch(Exception $e) {
            $request->setError('DBERROR', '該当する商品が登録されておりません。');
            $request->setAttribute('templateFileName', self::DEFAULT_TEMPLATE_FILE); // テンプレートファイル名
            return VIEW_INPUT;
        }
        include_once(dirname(__DIR__, 2) . '/Order/common/OrderCommonClass.php');
        $orderCommon = new OrderCommonClass($user);
        $shohinInfoList = $orderCommon->getShohinInfoList();
        $request->setAttribute('already_in_cart', isset($shohinInfoList[$shohinNo]));
        $user->setModuleParam('sno', $shohinNo); // 20150107 ブラウザバック復元対応
        // 配送指定不可日表示文言設定
        $hFukaDispMsg = $this->_getNotDeliveryDayDispMessage($shohinInfo);
        // 配送可能日設定(季節商品時)
        $temp = $shohinInfo->getKisetsuHaisouDate();
        if (is_array($temp)) {
            $hKanouDayArray = array_shift($temp);
        } else {
            $hKanouDayArray = $temp;
        }
        // 配送形態設定
        $hKeitaiArray = $this->_getHaisoKeitai($shohinInfo);
        // 期間限定商品なのかどうか判別するための設定。
        if (!is_null($shohinInfo->get('M02KIKANGHKFLG')) && count($hKeitaiArray) > 0) {
            $hKeitaiArray['kikangenteiFlg'] = $shohinInfo->get('M02KIKANGHKFLG');
        } else {
            $hKeitaiArray['kikangenteiFlg'] = '0';
        }
        // CGWEBLIB.MISHOHNP の商品情報から申込可能かチェック
        $cgweblibApplyFlg = true;
        if ($shohinInfo->isApplyCgweblibMishohnp(trim($shohinInfo->get('M02SHOHNCD'))) == false) {
            $cgweblibApplyFlg = false;
        }
        // パン屑ツリー
        $panTree = (string)$this->getPanTree($request, $group, $catId, $shohinInfo);
        // カテゴリ番号
        $categoryNo = '';
        if (strlen($catId)) {
            $catIdExplode = explode('-', $catId); // 0-1000 → 0, 1000
            $categoryNoTmp = end($catIdExplode); // 最後のカテゴリ
            if ($categoryNoTmp != '' && is_numeric($categoryNoTmp)) {
                $categoryNo = $categoryNoTmp;
            }
        }
        // SEO情報
        $seoarr = $this->getSeo($request, $shohinInfo, $categoryNo);
        //レコード取得チェック
        if (!$seoarr) {
            WtApp::getLogger()->debug('SEO情報が取得できませんでした。' . __FILE__ . ':' . __LINE__);
            $controller->redirect(WT_URL_BASE_SSL);
            return VIEW_NONE;
        }
        // テンプレートファイル名
        $templateFileName = '';
        try {
            $codeMasterArray = CodeMaster::getCodeMaster($shohinInfo->get('M02TMPLAT1'), $shohinInfo->get('M02TMPLAT2'), null, null, true);
            $codeMaster = reset($codeMasterArray); // 先頭レコード1件
            $templateFileName = $codeMaster['M03CHARA1'];
        } catch (WtException $e) {
            // コードマスタ情報が取得できなかった場合
            $templateFileName = self::DEFAULT_TEMPLATE_FILE;
        }
        // お気に入り登録チェック
        $okiniiri_flg = $this->_chkOkiniiri($request, $shohinNo, $giftCardNo);
        // レコメンド商品取得
        $shohinRecommendArray = $this->getRecommendShohin($this->gcInfo->usablePoints, $kenshuGroup, $categoryNo, $shohinNo);
        // チェックした商品の履歴を取得 及び、クッキー保存処理
        $shohinCheckRirekiArray = $this->getShohinCheckRireki($shohinNo, $kenshuGroup);
        // 商品詳細の商品番号に紐づく関連商品を取得
        $shohinGroupArray = array();
        if (!is_null($shohinInfo->get('M02SGROUP')) && strcmp($shohinInfo->get('M02SGROUP'), '') !== 0) {
            $shohinGroupArray = $this->getShohinGroup($shohinInfo->get('M02SHOHNNO'), $shohinInfo->get('M02SGROUP'), $kenshuGroup);
        }
        // 商品在庫状況表示文言設定
        $dispZaikoMessage = $shohinInfo->setZaikoMessage();
        // 商品内容説明設定
        $shohinNaiyoArray = $this->_setShohinNaiyoSetsumei($shohinInfo);
        // 人気商品ランキング(自動集計)に登録
        if ($giftCardNo !== VIRTUALITY_LOGIN_GIFTCARD_NO) {
            // 仮想ギフトカード以外を集計（商品プレビュー時も仮想ギフトカードが設定されている）
            $ninkiShohin = new NinkiShohinRanking($this);
            $ninkiShohin->setNinkiShohinRanking($user, $shohinNo, $categoryNo, $this->_modPg);
        }
        $okiniiriList = $this->getOkiniiriList($giftCardNo);
        $request->setAttribute('okiniiri_list', $okiniiriList);
        $request->setAttribute('shohin', $shohinInfo); // 商品情報
        $request->setAttribute('hFukaDispMsg', $hFukaDispMsg); // 配送指定不可日表示文言
        $request->setAttribute('hkanouDayArray', $hKanouDayArray); // 配送可能日
        $request->setAttribute('hKeitaiArray', $hKeitaiArray); // 配送形態
        $request->setAttribute('panTree', $panTree); // パン屑ツリー
        $request->setAttribute('caregoryNo', $categoryNo); // カテゴリ番号
        $request->setAttribute('seoarr', $seoarr); // SEO情報
        $request->setAttribute('templateFileName', $templateFileName); // テンプレートファイル名
        $request->setAttribute('okiniiri_flg', $okiniiri_flg); // お気に入り
        $request->setAttribute('recommendShohinArray', $shohinRecommendArray); // レコメンド商品
        $request->setAttribute('checkRirekiShohinArray', $shohinCheckRirekiArray); // チェックした商品の履歴
        $request->setAttribute('shohinGroupArray', $shohinGroupArray); // 関連商品
        $request->setAttribute('dispZaikoMessage', $dispZaikoMessage); // 商品在庫状況表示文言
        $request->setAttribute('shohinNaiyoArray', $shohinNaiyoArray); // 商品内容説明
        $request->setAttribute('cgweblibApplyFlg', $cgweblibApplyFlg); // CGWEBLIB.MISHOHNP の商品情報から判定した申込可能フラグ
        return VIEW_INPUT;
    }

    /**
     * 商品情報を取得する
     *
     * @param integer   $shohinNo    商品No
     * @param WtRequest $request     WtRequestオブジェクト（継承先のPreview機能で使用）
     * @param string    $kenshuGroup 券種グループ
     * @return NormalShohin 商品情報
     */
    protected function _getShohin($shohinNo, $request, $kenshuGroup)
    {
        return new NormalShohin($shohinNo, true, $kenshuGroup);
    }

    /**
     * SEO情報:商品カテゴリマスタの取得
     *
     * @param WtRequest $request WtRequestオブジェクト
     * @param string    $cateno  カテゴリ番号
     * @return array|false 商品カテゴリマスタ（データ取得失敗時はfalse）
     */
    function getSEOCate($request, $cateno)
    {
        $catearr = array();
        $wherearr = array();
        $wherearr['cateno'] = $cateno;
        $dbc = new ShohinShosaiQuerySel();
        $dbc->setSelectSql('1');
        $dbc->setRecordsetArray($wherearr);
        $rs = $dbc->Execute();
        if (!$rs) {
            $request->setError('DBERROR', E_DB_EXECUTE_ERR);
            return false;
        }
        if ($rs->RecordCount() <= 0) {
            //データが0件の場合
            return false;
        }
        //正常にデータが取得できた場合
        $catearr['cname'] = $rs->fields('M04CNAME');
        return $catearr;
    }

    /**
     * SEO情報の取得
     *
     * @param WtRequest    $request    WtRequestオブジェクト
     * @param NormalShohin $shohin     商品情報
     * @param string       $categoryno カテゴリ番号
     * @return array SEO情報（データ取得失敗時はfalse）
     */
    function getSeo($request, $shohin, $categoryno)
    {
        $seoarr = array();//viewに渡すための配列
        $seoshoparr = array();//ショップ情報格納
        $seoshohinarr = array();//商品情報格納
        $seocategoryarr = array();//カテゴリ情報の格納

        //ショップ情報の取得
        $seoshoparr['SeoSiteName'] = getSeoSiteName();
        $seoshoparr['SeoSiteNameDispFlag'] = getSeoSiteNameDispFlag();
        $seoshoparr['SeoShohinNameDispFlag'] = getSeoShohinNameDispFlag();
        $seoshoparr['SeoShohinNameNiniDispFlag'] = getSeoShohinNameNiniDispFlag();
        $seoshoparr['SeoCategoryDispKubun'] = getSeoCategoryDispKubun();
        $seoshoparr['SeoMetaTagDispFlag'] = getSeoMetaTagDispFlag();
        $seoshoparr['SeoKyotsuMetaTagKeyword'] = getSeoKyotsuMetaTagKeyword();
        $seoshoparr['SeoKyotsuMetaTagDescription'] = getSeoKyotsuMetaTagDescription();
        $seoshoparr['SeoTitleSplitWord'] = getSeoTitleSplitWord();
        $seoshoparr['SeoH1SplitWord'] = getSeoH1SplitWord();

        //商品情報の取得
        $seoshohinarr['ShohinShohinName'] = $shohin->get('M02SNAME');

        //カテゴリ情報の取得
        if ($categoryno != "") {
            $seocategoryarr = $this->getSEOCate($request, $categoryno);
        } else {
            $seocategoryarr['cname'] = "";
        }
        //レコード取得チェック
        if (!$seocategoryarr) {
            $seocategoryarr['cname'] = "";
        }

        // 商品名
        $seoarr['title'] = $shohin->getSeoName();

        //カテゴリ名
        if ($seocategoryarr['cname'] != "") {
            //カテゴリ名がある場合
            if ($seoshoparr['SeoCategoryDispKubun'] == "2") {
                if ($seoarr['title'] == "") {
                    $seoarr['title'] = $seocategoryarr['cname'];
                } else {
                    $seoarr['title'] = $seocategoryarr['cname']
                                    .$seoshoparr['SeoTitleSplitWord']
                                    .$seoarr['title'];
                }
            } else if ($seoshoparr['SeoCategoryDispKubun'] == "3") {
                if ($seoarr['title'] == "") {
                    $seoarr['title'] = $seocategoryarr['cname'];
                } else {
                    $seoarr['title'] = $seoarr['title']
                                    .$seoshoparr['SeoTitleSplitWord']
                                    .$seocategoryarr['cname'];
                }
            }
        }
        //サイト名
        if ($seoshoparr['SeoSiteName'] != "") {
            if ($seoshoparr['SeoSiteNameDispFlag'] == "2") {
                if ($seoarr['title'] == "") {
                    $seoarr['title'] = $seoshoparr['SeoSiteName'];
                } else {
                    $seoarr['title'] = $seoshoparr['SeoSiteName']
                                    .$seoshoparr['SeoTitleSplitWord']
                                    .$seoarr['title'];
                }
            } else if ($seoshoparr['SeoSiteNameDispFlag'] == "3") {
                if ($seoarr['title'] == "") {
                    $seoarr['title'] = $seoshoparr['SeoSiteName'];
                } else {
                    $seoarr['title'] = $seoarr['title']
                                    .$seoshoparr['SeoTitleSplitWord']
                                    .$seoshoparr['SeoSiteName'];
                }
            }
        }

        $seoarr['h1'] = "";//見出し
        //商品名
        if ($seoshoparr['SeoShohinNameDispFlag'] == "1") {
            $seoarr['h1'] = $seoshohinarr['ShohinShohinName'];
        }
        //商品任意
        if ($seoshoparr['SeoShohinNameNiniDispFlag'] == "1") {
            if ($seoarr['h1'] == "") {
                $seoarr['h1'] = $seoshohinarr['ShohinShohinName'];
            }
        }
        //カテゴリ名
        if ($seocategoryarr['cname'] != "") {
            //カテゴリ名がある場合
            if ($seoshoparr['SeoCategoryDispKubun'] == "2") {
                if ($seoarr['h1'] == "") {
                    $seoarr['h1'] = $seocategoryarr['cname'];
                } else {
                    $seoarr['h1'] = $seocategoryarr['cname']
                                    .$seoshoparr['SeoH1SplitWord']
                                    .$seoarr['h1'];
                }
            } else if ($seoshoparr['SeoCategoryDispKubun'] == "3") {
                if ($seoarr['h1'] == "") {
                    $seoarr['h1'] = $seocategoryarr['cname'];
                } else {
                    $seoarr['h1'] = $seoarr['h1']
                                    .$seoshoparr['SeoH1SplitWord']
                                    .$seocategoryarr['cname'];
                }
            }
        }

        $seoarr['metakeyword'] = "";//メタキーワード
        if ($seoshoparr['SeoMetaTagDispFlag'] == "1") {
            $seoarr['metakeyword'] = $seoshoparr['SeoKyotsuMetaTagKeyword'];
        } else if ($seoshoparr['SeoMetaTagDispFlag'] == "2") {
            // マスタに設定されていない場合は 「{商品名},{共通MetaKeywords}」
            $seoarr['metakeyword'] = $seoshohinarr['ShohinShohinName'];
            $seoarr['metakeyword'] .= ',' . $seoshoparr['SeoKyotsuMetaTagKeyword'];
        }

        $seoarr['metadesc'] = "";//メタデスクリプション
        if ($seoshoparr['SeoMetaTagDispFlag'] == "1") {
            $seoarr['metadesc'] = $seoshoparr['SeoKyotsuMetaTagDescription'];
        } else if ($seoshoparr['SeoMetaTagDispFlag'] == "2") {
            // マスタに設定されていない場合は 「{商品名}のページです。{共通MetaDes}」
            $seoarr['metadesc'] = $seoshohinarr['ShohinShohinName'];
            $seoarr['metadesc'] .= $seoshoparr['SeoKyotsuMetaTagDescription'];
        }

        return $seoarr;
    }

    /**
     * 商品がお気に入りに登録されているかチェック
     *
     * @param WtRequest $request     WtRequestオブジェクト
     * @param integer   $shohinNo    商品番号
     * @param string    $giftCardNo  ギフトカード番号
     *
     * @return string お気に入りに入っている場合は1、入っていない場合は0
     */
    function _chkOkiniiri($request, $shohinNo, $giftCardNo)
    {
        $chk_flg = '0';

        //DB接続
        $dbc = new ShohinShosaiQuerySel();

        //SQLの条件指定
        $wherearr = array();
        $wherearr['shohin'] = $shohinNo;
        $wherearr['giftcard_no'] = $giftCardNo;

        $dbc->setSelectSql('2');
        $dbc->setRecordsetArray($wherearr);
        $rs = $dbc->Execute();
        if (!$rs) {
            $request->setError('DBERROR', E_DB_EXECUTE_ERR);
            return '0';
        }
        if ($rs->fields['0'] >= 1) {
            //データが1件以上の場合
            $chk_flg = '1';
        }

        return $chk_flg;
    }

    /**
     * レコメンド商品を取得する。
     *
     * @param string $point       ポイント
     * @param string $kenshuGroup 券種グループ
     * @param string $categoNo    カテゴリ番号
     * @param string $shohinNo    商品番号
     *
     * @return array レコメンド商品
     */
    function getRecommendShohin($point, $kenshuGroup, $categoNo, $shohinNo)
    {
        $recommend = new RecommendShohin($this);
        $arrShohinRecommend = $recommend->getRecommendShohinArray($point, $kenshuGroup, $categoNo, $shohinNo);
        $arrShohinRecommendItem = array();
        foreach ($arrShohinRecommend as $key => $shohinInfo) {
            // 商品オブジェクト生成
            $shohin = new NormalShohin();
            $shohin->setAll($shohinInfo);
            $arrShohinRecommendItem[] = $shohin;
        }
        return $arrShohinRecommendItem;
    }

    /**
     * チェックした商品の履歴用商品情報を作成する。
     * ※クッキー保存処理も同時に行う
     *
     * @param string $shohinNo    商品番号
     * @param string $kenshuGroup 券種グループ
     *
     * @return array 商品情報リスト
     */
    function getShohinCheckRireki($shohinNo, $kenshuGroup)
    {
        // 最近チェックした商品の履歴を取得
        $checkRireki = new ShohinCheckRirekiCookie($this);
        $arrShohinCheckRireki = $checkRireki->getDispArray(CHECK_SHOHIN_COOKIE_MAX, $kenshuGroup);
        $arrShohinCheckRirekiItem = array();
        foreach ($arrShohinCheckRireki as $key => $shohinInfo) {
            // 商品オブジェクト生成
            $shohin = new NormalShohin();
            $shohin->setAll($shohinInfo);
            $arrShohinCheckRirekiItem[] = $shohin;
        }

        // 最近チェックした商品の履歴に現在表示商品を追加
        if (!$checkRireki->exists($shohinNo)) {
            $checkRireki->add($shohinNo);
        }

        return $arrShohinCheckRirekiItem;
    }

    /**
     * 商品詳細の商品に紐づく関連商品情報を作成する。
     *
     * @param integer $shohinNo    商品番号
     * @param string  $groupNo     関連商品グループ番号
     * @param string  $kenshuGroup 券種グループ
     *
     * @return array 関連商品情報リスト
     */
    function getShohinGroup($shohinNo, $groupNo, $kenshuGroup)
    {
        //DB接続
        $dbc = new ShohinShosaiQuerySel();

        //SQLの条件指定
        $wherearr = array();
        $wherearr['shohinNo'] = $shohinNo;
        $wherearr['groupNo'] = $groupNo;
        $wherearr['F22KENGROUP'] = $kenshuGroup;

        $dbc->setSelectSql('4'); //任意のSQL_NOを指定する
        $dbc->setRecordsetArray($wherearr);
        $rs = $dbc->Execute();

        if (!$rs) {
            $this->_request->setError('DBERROR', E_DB_EXECUTE_ERR);
            $rs->close();
            return array();
        }
        if ($rs->RecordCount() === 0) {
            $rs->close();
            return array();
        }

        $arrShohinGroup = array();
        while (!$rs->EOF) {
            $shohin = array();
            $shohin['M02SHOHNNO']   = $rs->Fields('M02SHOHNNO');       // 商品番号
            $shohin["M02BRAND"]     = $rs->Fields("M02BRAND");         // ブランド名
            $shohin['M02SHOHNCD']   = trim($rs->Fields('M02SHOHNCD')); // 商品コード
            $shohin['M02SNAME']     = $rs->Fields('M02SNAME');         // 商品名
            $shohin['M02VPOINT']    = $rs->Fields('M02VPOINT');        // ポイント
            $shohin['linkToShosai'] = $this->getActionUrl('ShohinShosai', 'ShohinShosai') . "shohin/" . urlencode($rs->fields('M02SHOHNNO')); // 商品詳細画面へのリンクURL
            array_push($arrShohinGroup, $shohin);
            $rs->MoveNext();
        }
        $rs->close();

        $arrShohinGroupItem = array();
        foreach ($arrShohinGroup as $key => $shohinInfo) {
            // 商品オブジェクト生成
            $shohin = new NormalShohin();
            $shohin->setAll($shohinInfo);
            $arrShohinGroupItem[] = $shohin;
        }
        return $arrShohinGroupItem;
    }

    /**
     * パン屑ナビゲーションの取得
     *
     * @param WtRequest $request    WtRequestオブジェクト
     * @param string    $prmGroup   グループID
     * @param string    $prmCatid   カテゴリID
     * @param object    $shohinInfo 商品情報
     *
     * @return string パン屑ナビゲーション（HTMLタグ出力文字列）
     */
    function getPanTree($request, $prmGroup, $prmCatid, $shohinInfo)
    {
        $shohinNo = $shohinInfo->get('M02SHOHNNO');
        $pankuzuList = $this->getPankuzuList($shohinNo);
        if (is_array($pankuzuList) && count($pankuzuList)) {
            // 20150119 今までのものに合わせて無理矢理HTMLを生成
            $pankuzuLink = '';
            $shohinName = $shohinInfo->get('M02SNAME');
            if (isSmartPhone()) {
                $shohinName = '<a>' . $shohinName . '</a>';
            }
            $catLink = $this->getActionUrl('SS', 'CS');
            foreach ($pankuzuList as $pankuzuInfo) {
                if (strlen($pankuzuLink)) {
                    $pankuzuLink .= '</ul><ul>';
                    $pankuzuLink .= '<li class="home"><a href="' . WT_URL_BASE_SSL . '">home</a></li>';
                }
                foreach ($pankuzuInfo as $catId => $temp) {
                    list($catName, $group) = explode("\t", $temp);
                    $pankuzuLink .= '<li><a href="' . $catLink . 'group/' . $group . '/catid/0-' . $catId . '">' . $catName . '</a></li>';
                }
                $pankuzuLink .= '<li class="last">' . $shohinName . '</li>';
            }
            return $pankuzuLink;
        }
        if (!$prmGroup && !$prmCatid) {
            return '';
        }
        //パラメーターからのカテゴリグループ番号を取得する
        $group = $prmGroup;
        if (!$group) {
            $request->setError("pantreeerror", E_WRONG_PARAM_ERR);
            return false;
        }
        //カテゴリツリーを取得する
        if (!$categoryTree = getCategoryTree($group)) {
            $request->setError("pantreeerror", CATEGORY_TREE_ERROR);
            return false;
        }
        //パラメーターからのカテゴリ番号(親の番号が含む)
        if (strlen($catId = $prmCatid) == 0) {
            $catId = "0";
        }
        //カテゴリ番号の存在チェック
        if (!$categoryTree->idExistsCheck($catId)) {
            $request->setAttribute("pantreeerror", CATEGORY_TREE_ERROR);
            return false;
        }

        $link = $this->getActionUrl('SS', 'CS');
        $link .= "group/" . urlencode($group);
        $panTree = $categoryTree->getArrayOfPanTree($catId);
        $panTree[] = array('id' => 'shohinName', 'name' => $shohinInfo->get('M02SNAME'));
        $stringOfPanTree = "";
        $cntOfPanTree = count($panTree);
        for ($index = 0; $index < $cntOfPanTree; $index++) {
            if (strcmp($panTree[$index]['id'], '0') !== 0) {
                if ($index + 1 == $cntOfPanTree) {
                    if (isSmartPhone()) {
                        $stringOfPanTree .= '<li class="last"><a>' . $panTree[$index]['name'] . '</a></li>';
                    } else {
                        $stringOfPanTree .= '<li class="last">' . $panTree[$index]['name'] . '</li>';
                    }
                } else {
                    $stringOfPanTree .= "<li><a href=" . $link . "/catid/" . urlencode($panTree[$index]["id"]) . ">" . $panTree[$index]["name"] . "</a></li>";
                }
            }
        }

        return $stringOfPanTree;
    }

    function getPankuzuList($shohinNo)
    {
        $pankuzuList = array();
        $dbc = new ShohinShosaiQuerySel();
        $dbc->setSelectSql('9');
        $dbc->setRecordsetArray(array('F41SHOHNNO' => $shohinNo));
        $rs = $dbc->Execute();
        if (!$rs) {
            $this->_request->setError('DBERROR', E_DB_EXECUTE_ERR);
            return false;
        }
        $catTemp = trim($rs->Fields('F41CATEGNO'), " \t\n\r\0\x0B,");
        if (strlen($catTemp)) {
            $catNoList = array();
            $catIdList = explode(',', $catTemp);
            foreach ($catIdList as $catId) {
                foreach (explode('-', $catId) as $catNo) {
                    $catNoList[] = $catNo;
                }
            }
            $dbc->setSelectSql('10');
            $dbc->setRecordsetArray(array('CATNO_LIST' => $catNoList));
            $rs = $dbc->Execute();
            if (!$rs) {
                $this->_request->setError('DBERROR', E_DB_EXECUTE_ERR);
                return false;
            }
            $catNameList = array();
            while (!$rs->EOF) {
                $catNo   = $rs->Fields('M04CATEGNO');
                $catName = $rs->Fields('M04CNAME');
                $group   = $rs->Fields('M03NRYAKU');
                $catNameList[$catNo] = $catName . "\t" . $group;
                $rs->MoveNext();
            }
            foreach ($catIdList as $catIdTemp) {
                $catId = '';
                $pankuzu = array();
                foreach (explode('-', $catIdTemp) as $catNo) {
                    if ($catId) {
                        $catId .= '-';
                    }
                    $catId .= $catNo;
                    $pankuzu[$catId] = $catNameList[$catNo];
                }
                $pankuzuList[] = $pankuzu;
            }
        }
        return $pankuzuList;
    }

    /**
     * 配送指定不可日表示文言設定
     *
     * @param NormalShohin $shohinInfo 商品情報
     *
     * @return string 配送指定不可日表示文言
     */
    private function _getNotDeliveryDayDispMessage($shohinInfo)
    {
        $oshirase = '';
        // 配送指定除外フラグが除外(1)の場合、そのまま返却
        if (strcmp($shohinInfo->get('M02HAISOFJFLG'), '1') === 0) {
            return $oshirase;
        }

        // 配送指定不可日期間表示文言取得
        //DB接続
        $dbc = new ShohinShosaiQuerySel();

        //SQLの条件指定
        $wherearr = array();
        $wherearr['shohinNo'] = $shohinInfo->get('M02SHOHNNO');
        $wherearr['zenFlg'] = self::SHOHHIN_KOBETU;

        $dbc->setSelectSql('5'); //任意のSQL_NOを指定する
        $dbc->setRecordsetArray($wherearr);
        $rs = $dbc->Execute();

        if (!$rs) {
            $this->_request->setError('DBERROR', E_DB_EXECUTE_ERR);
            return $oshirase;
        }
        if ($rs->RecordCount() === 0) {
            // 商品個別設定がないので、全体設定を取得する
            $wherearr['shohinNo'] = SHOHIN_NO;
            $wherearr['zenFlg'] = self::SHOHHIN_ZENTAI;
            $dbc->setSelectSql('5'); //任意のSQL_NOを指定する
            $dbc->setRecordsetArray($wherearr);
            $rs = $dbc->Execute();
        }
        while (!$rs->EOF) {
            // 配送指定不可日表示文言設定
            $oshirase = $rs->fields('F70OSHIRASE');
            $rs->MoveNext();
        }
        return $oshirase;
    }

    /**
     * 配送形態設定
     *
     * @param NormalShohin $shohinInfo 商品情報
     *
     * @return array 配送形態
     */
    private function _getHaisoKeitai($shohinInfo)
    {
        $hKeitaiArray = array();

        // 配送形態取得
        // DB接続
        $dbc = new ShohinShosaiQuerySel();

        //SQLの条件指定
        $wherearr = array();
        $wherearr['shohinNo'] = $shohinInfo->get('M02SHOHNNO');

        $dbc->setSelectSql('6'); //任意のSQL_NOを指定する
        $dbc->setRecordsetArray($wherearr);
        $rs = $dbc->Execute();
        if (!$rs) {
            $this->_request->setError('DBERROR', E_DB_EXECUTE_ERR);
            return $hKeitaiArray;
        }
        if ($rs->RecordCount() === 0) {
            return $hKeitaiArray;
        }
        while (!$rs->EOF) {
            $hKeitaiArray = array(
                'F72HAISOKBN' => $rs->fields('F72HAISOKBN'),
                'F72SDATE'    => $rs->fields('F72SDATE'),
                'F72EDATE'    => $rs->fields('F72EDATE'),
            );
            $rs->MoveNext();
        }
        return $hKeitaiArray;
    }

    /**
     * 商品内容説明設定
     *
     * @param NormalShohin $shohinInfo 商品情報
     * @return array 商品内容説明
     */
    private function _setShohinNaiyoSetsumei($shohinInfo)
    {
        $shohinNaiyoArray = array();

        // 商品内容説明取得
        // DB接続
        $dbc = new ShohinShosaiQuerySel();

        //SQLの条件指定
        $wherearr = array();
        $wherearr['key1'] = $shohinInfo->get('M02HYOJIKEY1');
        $wherearr['key2'] = $shohinInfo->get('M02HYOJIKEY2');

        $dbc->setSelectSql('7'); //任意のSQL_NOを指定する
        $dbc->setRecordsetArray($wherearr);
        $rs = $dbc->Execute();

        if (!$rs) {
            $this->_request->setError('DBERROR', E_DB_EXECUTE_ERR);
            $rs->close();
            return $shohinNaiyoArray;
        }
        if ($rs->RecordCount() === 0) {
            $rs->close();
            return $shohinNaiyoArray;
        }

        $otherTitle = $otherMessage = '';
        $invCardTitle = $invCardMessage = '';
        while (!$rs->EOF) {
            // コードマスタのシーケンス値(M03SEQ)から取得する表示項目(M02HKOMOKUx)を設定
            $seq = strval($rs->fields('M03SEQ'));
            if (!is_null($shohinInfo->get('M02HKOMOKU'. $seq)) && strcmp($shohinInfo->get('M02HKOMOKU'. $seq), '') !== 0) {
                $hyojiKomoku = $shohinInfo->get('M02HKOMOKU'. $seq);
                // 表示項目の中で、輸入商品表示用納期を設定
                if ($seq == '13') {
                    $hyojiKomoku = sprintf('この商品は輸入商品のため、お届けに約%s日程度かかります。', $shohinInfo->get('M02HKOMOKU'. $rs->fields('M03SEQ')));
                }
                if ($seq == '15') {
                    $otherTitle = $rs->fields('M03NAME');
                    $otherMessage = $hyojiKomoku;
                } else if ($seq == '8') {
                    $invCardTitle = $rs->fields('M03NAME');
                    $invCardMessage = $hyojiKomoku;
                } else {
                    $shohinNaiyoArray[] = array(
                        'M03NAME' => $rs->fields('M03NAME'),
                        'M02HKOMOKU' => $hyojiKomoku,
                    );
                }
            }
            $rs->MoveNext();
        }
        $this->_request->setAttribute('other_title', $otherTitle);
        $this->_request->setAttribute('other_message', $otherMessage);
        $this->_request->setAttribute('invcard_title', $invCardTitle);
        $this->_request->setAttribute('invcard_message', $invCardMessage);

        $rs->close();
        return $shohinNaiyoArray;
    }

    /**
     * 商品番号をお気に入りに登録
     *
     * @param WtRequest $request    WtRequestオブジェクト
     * @param WtUser    $user       WtUserオブジェクト
     * @param string    $shohinNo   商品番号
     * @param string    $giftCardNo ギフトカード番号
     *
     * @return bool 登録成功した場合はtrue。登録失敗した場合はfalse。
     */
    protected function _insertOkiniiriShohin($request, $user, $shohinNo, $giftCardNo)
    {
        //現在時刻-DBtimestamp型更新時対応
        $nowdate = date(DB_TIMESTAMP_FORMAT_SYSTEM);

        $wherearr = array();
        $wherearr['F42_GC_NO'] = $giftCardNo;

        // DB接続
        $dbcSel = new ShohinShosaiQuerySel();
        $dbcSel->setSelectSql('8');
        $dbcSel->setRecordsetArray($wherearr);
        $rs = $dbcSel->Execute();

        // DB取得エラー
        if (!$rs) {
            //DBエラーの処理
            $request->setError('error', E_DB_EXECUTE_ERR);
            return false;
        }

        // 登録可能件数チェック
        if ($rs->RecordCount() >= OKINIIRI_LIMIT) {
            $request->setError('DBERROR', sprintf('お気に入りページへの登録は%d件までです。商品を追加登録する場合は不要な商品を削除してください。', OKINIIRI_LIMIT));
            return false;
        }

        // 重複チェック
        while (!$rs->EOF) {
            if ($rs->fields('F42SHOHINNO') == $shohinNo) {
                $request->setError('DBERROR', '選択された商品は既にお気に入りに登録されています。');
                return false;
            }
            $rs->MoveNext();
        }

        // 重複していない場合のみお気に入りに登録
        // 採番を取得
        $saiBanNo = getSequeceNo('F42OKINI');
        if ($saiBanNo == '') {
            //DBエラーの処理
            $request->setError('DBERROR', E_DB_EXECUTE_ERR);
            return false;
        }

        $dbAddarr = array();
        $dbAddarr['F42_DEL_FLG'] = '0';
        $dbAddarr['F42_INS_ID'] = MOD_SHOP_ID;
        $dbAddarr['F42_INS_PROGRAM'] = $this->_modPg;
        $dbAddarr['F42_INS_DATE'] = $nowdate;
        $dbAddarr['F42_UPD_ID'] = MOD_SHOP_ID;
        $dbAddarr['F42_UPD_PROGRAM'] = $this->_modPg;
        $dbAddarr['F42_UPD_DATE'] = $nowdate;
        $dbAddarr['F42_ID'] = SHOP_ID;
        $dbAddarr['F42_OKINIIRI_NO'] = $saiBanNo;
        $dbAddarr['F42_GC_NO'] = $giftCardNo;
        $dbAddarr['F42_SHOHIN_NO'] = $shohinNo;

        // DB接続
        $dbc = new ShohinShosaiQueryIUD();
        $dbc->ConntTrans();
        $dbc->setSelectSql('1');
        $dbc->setRecordsetArray($dbAddarr);
        $rs = $dbc->Execute();
        if (!$rs) {
            $dbc->ConnRollback();
            $request->setError('DBERROR', E_DB_EXECUTE_ERR);
            return false;
        } else {
            $dbc->ConnCommit();
        }
        return true;
    }


    protected function getOkiniiriList($giftcardNo)
    {
        $db = new CategorySagasuQuerySel();
        $db->setRecordsetArray(['giftcard_no' => $giftcardNo]);
        $db->setSelectSql('5');
        $rs = $db->Execute();
        if (!$rs) { // DBエラー
            $rs->close();
            throw new WtDbException(E_DB_EXECUTE_ERR);
        }
        $okiniiriList = [];
        while (!$rs->EOF) {
            $itemNo = (string)$rs->Fields('F42SHOHINNO');
            $okiniiriList[$itemNo] = 1;
            $rs->MoveNext();
        }
        $rs->close();
        return $okiniiriList;
    }
}