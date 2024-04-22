<?php
/**
 * ProjectName:スマートギフトサイト
 * Subsystem:ギフトカードを利用した商品交換モールサイト
 *
 * PHP versions 5.3.1
 *
 * @package webapp_ssl
 *
 */

/**
 * キーワード検索
 *
 * @author  Keisuke Yamamoto
 * @version Release:<1.0>
 */
class KSListAction extends SgAction
{
    /**
     * @var string プログラムID
     */
    protected $_modPg = 'S0202';

    /**
     * POSTリクエストの処理
     *
     * @param WtController $controller WtControllerオブジェクト
     * @param WtRequest    $request    WtRequestオブジェクト
     * @param WtUser       $user       WtUserオブジェクト
     *
     * @return string ビュー名称
     */
    function execute($controller, $request, $user)
    {
        $encodedListParams = base64_decode(strtr($request->getParameter('elp'), array('-' => '+', '_' => '/')));
        if ($encodedListParams) {
            $listParams = unserialize($encodedListParams);
            if (is_array($listParams)) {
                $keyList = array(
                    'sort',
                    'order',
                    'kysg',
                    'keyword_real',
                    'keyword'
                );
                foreach ($keyList as $k) {
                    if (!isset($listParams[$k])) {
                        return VIEW_NONE;
                    }
                }

                // 【脆弱性診断から重大な警告を受けた箇所】ソートするカラム名にSQLインジェクション
                // M02VPOINT、M02INSDATEはホワイトリストになっているので、ソート対象が増えた場合に
                // 一緒にここのチェックも増やす必要がある。
                if ($listParams['order'] && $listParams['sort'] && $listParams['sort'] != 'M02VPOINT' && $listParams['sort'] != 'M02INSDATE') {
                    return VIEW_NONE;
                }
                $dbc = new KeywordSagasuQuerySel();
                // 商品一覧情報取得
                $arrShohin = $this->getArrShohin(
                    $request,
                    $user,
                    $dbc,
                    $listParams['kysg'],
                    $listParams['keyword'],
                    $listParams['keyword_real'],
                    $listParams['order'],
                    $listParams['sort']
                );
                $request->setAttribute('search_conds', $listParams);
                $request->setAttribute('arrShohin', $arrShohin);
                $request->setAttribute('show_list', true);
                return VIEW_INPUT;
            }
        }
        return VIEW_NONE;
    }

    /**
     * GETリクエストの処理
     *
     * @param WtController $controller WtControllerオブジェクト
     * @param WtRequest    $request    WtRequestオブジェクト
     * @param WtUser       $user       WtUserオブジェクト
     *
     * @return string ビュー名称
     */
    function getDefaultView($controller, $request, $user)
    {
        return VIEW_NONE;
    }

    /**
     * リクエスト判別
     *
     * @return integer REQ_POST|REQ_GET
     */
    function getRequestMethods()
    {
        return REQ_POST;
    }

    /**
     * エラーハンドリング処理
     *
     * @param WtController $controller WtControllerオブジェクト
     * @param WtRequest    $request    WtRequestオブジェクト
     * @param WtUser       $user       WtUserオブジェクト
     *
     * @return $this->getDefaultView($controller, $request, $user)
     */
    function handleError($controller, $request, $user)
    {
        return $this->getDefaultView($controller, $request, $user);
    }

    /**
     * ログイン認証を行うかの指定
     *
     * @param WtController $controller WtControllerオブジェクト
     * @param WtUser       $user       WtUserオブジェクト
     *
     * @return bool ログインチェックを行う場合はtrue。行わない場合はfalse。
     */
    function isSecure($controller, $user)
    {
        return true;
    }

    /**
     * バリデータ登録処理
     *
     * @param WtValidatorManager $validatorManager WtValidatorManagerオブジェクト
     * @param WtController       $controller       WtControllerオブジェクト
     * @param WtRequest          $request          WtRequestオブジェクト
     * @param WtUser             $user             WtUserオブジェクト
     *
     * @return void なし
     */
    function registerValidators($validatorManager, $controller, $request, $user)
    {
    }

    /**
     * パラメータ検証処理
     *
     * @param WtController $controller WtControllerオブジェクト
     * @param WtRequest    $request    WtRequestオブジェクト
     * @param WtUser       $user       WtUserオブジェクト
     *
     * @return void なし
     */
    function validate($controller, $request, $user)
    {
    }

    /**
     * 商品一覧情報取得
     *
     * キーワードを元に、商品一覧を取得する。
     *
     * <pre>
     * ■取得条件は以下。
     * ・商品に紐づくカテゴリが存在する
     * ・商品が削除されていない
     * ・商品が有効になっている
     * ・サイトIDが40
     * ■取得対象は以下。
     *   ▽商品の表示に使用==============================================
     *   ・商品番号
     *   ・商品コード
     *   ・商品名
     *   ・ブランド名
     *   ・関連商品グルーピング
     *   ・関連商品表示順
     *   ・ポイント
     * ■ソート条件は以下。
     *   ▽ポイントソートリンクが押下された場合==============================
     *   ・第1ソート
     *       対象: パラメータ値（商品のポイント）
     *       順序: パラメータ値（昇順または降順）
     *   ▽新着日ソートリンクが押下された場合==============================
     *   ・第1ソート
     *       対象: パラメータ値(商品の登録日時)
     *       順序: パラメータ(昇順または降順)
     *   ▽それ以外======================================================
     *   ・第2ソート
     *       対象: 商品番号の順番
     *       順序: 昇順
     * </pre>
     *
     * @param WtRequest             $request     WtRequestオブジェクト
     * @param WtUser                $user        WtUserオブジェクト
     * @param KeywordSagasuQuerySel $dbc         KeywordSagasuQuerySelオブジェクト
     * @param string                $kysg        キーワード検索トリガー
     * @param string                $keyword     キーワード
     * @param string                $keywordReal キーワード
     * @param string                $order       ソート順(ポイントの低い、高いでソート)
     * @param string                $sort        ソート対象の名称
     *
     * @return array 商品一覧情報
     */
    function getArrShohin($request, $user, $dbc, $kysg, $keyword, $keywordReal, $order, $sort)
    {
        if (!$sort
            && isset($this->settings['default_item_sort'])
            && strlen($this->settings['default_item_sort'])
        ) {
            switch ($this->settings['default_item_sort']) {
                case 'VPOINT_ASC':
                    $sort = 'M02VPOINT';
                    $order = 'asc';
                    break;
                case 'VPOINT_DESC':
                    $sort = 'M02VPOINT';
                    $order = 'desc';
                    break;
                case 'INSDATE_DESC':
                    $sort = 'M02INSDATE';
                    $order = 'desc';
                    break;
            }
        }

        // WEB会員番号取得
        $webkaiin_no = SHOP_ID;

        // 1ページあたりの表示件数
        $itemPerPage = getShopKeywordSearchPagePerCnt();

        // 表示ページ番号を取得 (未設定の場合は先頭ページ)
        $pageNo = $request->getParameter(GET_PARAM_PAGE);
        $pageNo = is_numeric($pageNo) ? intval($pageNo) : 1;

        // >>> 20150107 ブラウザバック復元対応 >>>
        $itemPerPageOrig = $itemPerPage;
        $reqPageNo = $pageNo;
        $argsHash = sha1(implode(' ', array($kysg, $keyword, $keywordReal, $order, $sort)));
        $ksShohinNo = '';
        if (get_class($this) == 'KSAction') {
            $ksPageKey = $user->getModuleParam(KS_PAGE_KEY);
            $csPageKey = $user->getModuleParam(CS_PAGE_KEY);
            if ($csPageKey) {
                $user->setModuleParam(CS_PAGE_KEY, null);
            }
            if ($ksPageKey) {
                list($ksPageNo, $ksHash) = explode('@', $ksPageKey, 2);
                if (is_numeric($ksPageNo) && ($ksHash == $argsHash)) {
                    $reqPageNo = 1;
                    $pageNo = intval($ksPageNo);
                    $itemPerPage *= $pageNo;
                    $ksShohinNo = $user->getModuleParam('sno');
                } else {
                    $user->setModuleParam(KS_PAGE_KEY, null);
                }
            }
        }
        $user->setModuleParam(KS_PAGE_KEY, $pageNo . '@' . $argsHash);
        $request->setAttribute('ks_page_no', $pageNo);
        $request->setAttribute('ks_shohin_no', $ksShohinNo);
        $request->setAttribute('item_per_page', $itemPerPage);
        // <<< 20150107 ブラウザバック復元対応 <<<

        // キーワード検索実行
        $qrsarr = array(
            'kysg'        => $kysg,
            'keyword'     => $keyword,
            'keywordReal' => $keywordReal,
            'order'       => $order,
            'sort'        => $sort,
            'F22KENGROUP' => $this->getShohinKenshuGroup(),
        );
        $dbc->setRecordsetArray($qrsarr);
        $dbc->setSelectSql('1');
        $rs = $dbc->PageExecute($itemPerPage, $reqPageNo); // 20150107 ブラウザバック復元対応
        if (!$rs) { // DBエラー
            $rs->close();
            throw new WtDbException(E_DB_EXECUTE_ERR);
        } else if ($rs->RecordCount() === 0) {
            $request->setAttribute('resultCount', $rs->MaxRecordCount());
            // ページャー情報の設定
            $baseUrl = preg_replace('/[?#].*$/', '', $_SERVER['REQUEST_URI']);
            $baseUrl = rtrim(preg_replace("#(?:/keyword/[^/]+)(/|$)#", '$1', $baseUrl), '/') . '/';
            $baseUrl .= 'keyword/' . urlencode($keywordReal) . '/';
            $this->_setPagerInfo($rs, $request, $baseUrl, GET_PARAM_PAGE);
            // 空の配列を返却
            $rs->close();
            $request->setAttribute('item_per_page', 0);
            $request->setAttribute('resultCount', 0);
            return array();
        }
        $resultCount = $rs->MaxRecordCount();
        if ($resultCount < $itemPerPage) {
            $request->setAttribute('item_per_page', $resultCount);
        }
        $request->setAttribute('resultCount', $resultCount);

        // ページャー情報の設定
        $baseUrl = preg_replace('/[?#].*$/', '', $_SERVER['REQUEST_URI']);
        $baseUrl = rtrim(preg_replace("#(?:/keyword/[^/]+)(/|$)#", '$1', $baseUrl), '/') . '/';
        $baseUrl .= 'keyword/' . urlencode($keywordReal) . '/';
        $this->_setPagerInfo($rs, $request, $baseUrl, GET_PARAM_PAGE);

        // >>> 20150107 ブラウザバック復元対応 >>>
        if ((get_class($this) == 'KSAction') && ($pageNo > 1)) {
            $pagerInfo = $request->getAttribute('wt__pager_info');
            $pagerInfo['last'] = intval(ceil($pagerInfo['total'] / ($itemPerPage / $pageNo)));
            $request->setAttribute('wt__pager_info', $pagerInfo);
        }
        // <<< 20150107 ブラウザバック復元対応 <<<

        $arrShohin = array();
        $groupNos = array();
        $shohinNos = array();
        $shohinItemArray = array();
        // 代表商品、代表商品の商品番号と関連商品のグループ番号を取得
        while ($shohinInfo = $rs->FetchRow()) {
            // 代表商品オブジェクト生成
            $shohinInfo["linkToShosai"] = $this->getActionUrl('ShohinShosai', 'ShohinShosai') . "shohin/" . urlencode($shohinInfo["M02SHOHNNO"]);
            $shohinItemArray[] = $shohinInfo;
            // 関連商品グループ番号が存在する場合、配列に格納
            if (!is_null($shohinInfo['M02SGROUP']) && strcmp($shohinInfo['M02SGROUP'], '') !== 0) {
                $groupNos[] = $shohinInfo['M02SGROUP'];
            }
            // 代表商品の商品番号を格納
            $shohinNos[] = $shohinInfo['M02SHOHNNO'];
        }

        $qrsarr['groupNos'] = $groupNos;
        $qrsarr['shohinNos'] = $shohinNos;
        // 代表商品に紐づく関連商品がある場合、その関連商品を取得(ただし、代表商品は除く)
        $dbc->setRecordsetArray($qrsarr);
        $dbc->setSelectSql('2');
        $rs = $dbc->Execute();

        if (!$rs) { // DBエラー
            $rs->close();
            throw new WtDbException(E_DB_EXECUTE_ERR);
        }
        $arrGroupShohin = array();
        // 関連商品グループ番号ごとに配列に格納
        while ($groupShohinInfo = $rs->FetchRow()) {
            $groupShohinInfo["linkToShosai"] = $this->getActionUrl('ShohinShosai', 'ShohinShosai') . "shohin/" . urlencode($groupShohinInfo["M02SHOHNNO"]);
            $arrGroupShohin[$groupShohinInfo['M02SGROUP']][] = $groupShohinInfo;
        }
        // 取得した関連商品をその関連商品に紐づく代表商品の配列に組み入れる
        foreach ($shohinItemArray as $key => $shohinItemList) {
            // 関連商品グループ番号が取得できたかチェック
            if (!is_null($shohinItemList['M02SGROUP']) && strcmp($shohinItemList['M02SGROUP'], '') !== 0 ) {
                // 代表商品の関連商品グループ番号が取得した関連商品のグループ番号に存在するかどうかチェック
                if (isset($arrGroupShohin[$shohinItemList['M02SGROUP']])) {
                    // 関連商品グループ番号が存在する情報を設定
                    $shohinItemList['groupShohinFlg'] = true;
                    $shohinItemList['groupShohin'] = $arrGroupShohin[$shohinItemList['M02SGROUP']];
                } else {
                    // 関連商品グループ番号が存在しない情報を設定
                    $shohinItemList['groupShohinFlg'] = false;
                }
            }
            $shohin = new NormalShohin();
            $shohin->setAll($shohinItemList);
            $arrShohin[] = $shohin;
        }
        $rs->close();
        return $arrShohin;
    }
}
