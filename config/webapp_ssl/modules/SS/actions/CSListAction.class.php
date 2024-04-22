<?php
class CSListAction extends SgAction
{
    /**
     * @var bool 認証が必要な画面かどうか
     */
    protected $_requireAuth = true;

    /**
     * @var bool アクションとモジュール名をセッションに保存するかどうか
     */
    protected $_saveCurrentAction = false;

    /**
     * @var string プログラムID
     */
    protected $_modPg = 'S0202';

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $encodedListParams = base64_decode(strtr($request->getParameter('elp'), array('-' => '+', '_' => '/')));
        if ($encodedListParams) {
            $listParams = unserialize($encodedListParams);
            if (is_array($listParams)) {
                $keyList = array(
                    'catid',
                    'group',
                    'sort',
                    'order',
                    'brand',
                    'kysg',
                    'keyword',
                    'keyword_real',
                    'curcatno',
                    'tplpath',
                    'catname'
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
                $path =  SHOHIN_CATEGORY_TEMPLATE_DIR . '/' .  $listParams['tplpath'];
                // パスチェック：券種グループごとのテンプレートが存在する場合、パスチェックをされると毎回異常終了なので削除
                // if (!is_file($path) || strpos($listParams['tplpath'], '..') !== false) {
                //    return VIEW_NONE;
                // }
                $request->setAttribute('categoryName', $listParams['catname']);
                $request->setAttribute('resultTemplate', $path);
                $dbc = new CategorySagasuQuerySel();
                // 商品一覧情報取得
                $arrShohin = $this->getArrShohin(
                    $request,
                    $user,
                    $dbc,
                    $listParams['curcatno'],
                    $listParams['catid'],
                    $listParams['group'],
                    $listParams['sort'],
                    $listParams['order'],
                    $listParams['brand'],
                    $listParams['kysg'],
                    $listParams['keyword'],
                    $listParams['keyword_real']
                );

                // お気に入り情報
                if (!$user->getAttribute('is_virtual_login')) {
                    $okiniiriList = $this->getOkiniiriList($this->gcInfo->maincardNo);
                    $request->setAttribute('okiniiri_list', $okiniiriList);
                }

                $request->setAttribute('search_conds', $listParams);
                $request->setAttribute('arrShohin', $arrShohin);
                $request->setAttribute('show_list', true);
                return VIEW_INPUT;
            }
        }
        return VIEW_NONE;
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

    /**
     * 商品一覧情報取得
     *
     * 選択したカテゴリおよびポイントのカテゴリ番号を元に、商品一覧を取得する。
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
     * @param WtRequest              $request      WtRequestオブジェクト
     * @param WtUser                 $user         WtUserオブジェクト
     * @param CategorySagasuQuerySel $dbc          CategorySagasuQuerySelオブジェクト
     * @param string                 $currentCatNo カテゴリ番号
     * @param string                 $catId        カテゴリ番号
     * @param string                 $group        グループ名
     * @param string                 $sort         ソート対象の名称
     * @param string                 $order        ソート対象のソート順（DESC|ASC）
     * @param string                 $brand        ブランド絞り込み条件
     * @param string                 $kysg         キーワード検索トリガー
     * @param string                 $keyword      キーワード検索ワード
     * @param string                 $keywordReal  キーワード検索ワード
     *
     * @return array 商品一覧情報
     */
    function getArrShohin($request, $user, $dbc, $currentCatNo, $catId, $group, $sort, $order, $brand, $kysg, $keyword, $keywordReal)
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
        $itemPerPage = getShopCategoryPagePerCnt();

        // 表示ページ番号を取得 (未設定の場合は先頭ページ)
        $pageNo = $request->getParameter(GET_PARAM_PAGE);
        $pageNo = is_numeric($pageNo) ? intval($pageNo) : 1;

        // >>> 20150107 ブラウザバック復元対応 >>>
        $reqPageNo = $pageNo;
        $argsHash = sha1(implode(' ', array($currentCatNo, $catId, $group, $sort, $order, $brand, $kysg, $keyword, $keywordReal)));
        $csShohinNo = '';
        if (get_class($this) == 'CSAction') {
            $csPageKey = $user->getModuleParam(CS_PAGE_KEY);
            $ksPageKey = $user->getModuleParam(KS_PAGE_KEY);
            if ($ksPageKey) {
                $user->setModuleParam(KS_PAGE_KEY, null);
            }
            if ($csPageKey) {
                list($csPageNo, $csHash) = explode('@', $csPageKey, 2);
                if (is_numeric($csPageNo) && ($csHash == $argsHash)) {
                    $reqPageNo = 1;
                    $pageNo = intval($csPageNo);
                    $itemPerPage *= $pageNo;
                    $csShohinNo = $user->getModuleParam('sno');
                } else {
                    $user->setModuleParam(CS_PAGE_KEY, null);
                }
            }
        }
        $user->setModuleParam(CS_PAGE_KEY, $pageNo . '@' . $argsHash);
        $request->setAttribute('cs_page_no', $pageNo);
        $request->setAttribute('cs_shohin_no', $csShohinNo);
        $request->setAttribute('item_per_page', $itemPerPage);
        // <<< 20150107 ブラウザバック復元対応 <<<

        // カテゴリ検索実行
        $qrsarr = array(
            'F03CATEGNO'   => $currentCatNo,
            'date'         => date(DB_TIMESTAMP_FORMAT_SYSTEM),
            'sort'         => $sort,
            'order'        => $order,
            'brand'        => $brand,
            'webkaiin_no'  => $webkaiin_no,
            'kysg'         => $kysg,
            'keyword'      => $keyword,
            'keywordReal'  => $keywordReal,
            'F22KENGROUP'  => $this->getShohinKenshuGroup(),
        );
        $dbc->setRecordsetArray($qrsarr);
        $dbc->setSelectSql('3');
        $rs = $dbc->PageExecute($itemPerPage, $reqPageNo); // 20150107 ブラウザバック復元対応
        if (!$rs) { // DBエラー
            $rs->close();
            throw new WtDbException(E_DB_EXECUTE_ERR);
        } else if ($rs->RecordCount() === 0) {
            $request->setAttribute('resultCount', $rs->MaxRecordCount());
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
        $this->_setPagerInfo($rs, $request, null, GET_PARAM_PAGE);

        // >>> 20150107 ブラウザバック復元対応 >>>
        if ((get_class($this) == 'CSAction') && ($pageNo > 1)) {
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
            // 商品オブジェクト生成
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
        $dbc->setSelectSql('4');
        $rs = $dbc->Execute();

        if (!$rs) { // DBエラー
            $rs->close();
            throw new WtDbException(E_DB_EXECUTE_ERR);
        }
        $arrGroupShohin = array();
        // 関連商品グループ番号ごとに配列に格納
        while ($groupShohinInfo = $rs->FetchRow()) {
            $groupShohinInfo['linkToShosai'] = $this->getActionUrl('ShohinShosai', 'ShohinShosai') . "shohin/" . urlencode($groupShohinInfo["M02SHOHNNO"]);
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
