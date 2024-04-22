<?php
require_once(WT_ROOT_DIR . 'util/Common/actions/RecommendShohin.class.php');
require_once(WT_ROOT_DIR . 'util/Common/actions/NinkiShohinRanking.class.php');
require_once(dirname(__DIR__, 2) . '/SS/actions/CSListAction.class.php');

class CSAction extends CSListAction
{
    /**
     * @var bool アクションとモジュール名をセッションに保存するかどうか
     */
    protected $_saveCurrentAction = true;

    /**
     * @var string プログラムID
     */
    protected $_modPg = 'S0202';

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        // パラメーターからのカテゴリ番号(親の番号が含む)
        $catId = trim($request->getParameter('catid'));
        //if (!strlen($catId)) {
        //    $catId = '0-1000'; // 全カテゴリ
        //    //$request->setError('error', E_WRONG_PARAM_ERR);
        //    //return VIEW_INPUT;
        //}
        // パラメーターからのカテゴリグループ番号を取得する
        $group = $request->getParameter('group');
        if (!$group) {
            $group = 'cat_shobun'; // 商品分類
            //$request->setError('error', E_WRONG_PARAM_ERR);
            //return VIEW_INPUT;
        }
        // カテゴリツリーを取得する
        $categoryTree = getCategoryTree($group);
        if (!$categoryTree) {
            $request->setError('error', CATEGORY_TREE_ERROR);
            return VIEW_INPUT;
        }
        // カテゴリ番号の存在チェック
        if ($catId && !$categoryTree->idExistsCheck($catId)) {
            $request->setError('categoryExistsError', implode("\n", [
                '該当カテゴリが存在しておりません。',
                'しばらく経って解消しない場合はお問い合わせください。',
            ]));
            return VIEW_INPUT;
        }
        // カテゴリ番号をセッションに格納する
        $user->setAttribute('catIdInSession', $catId);

        $currentCatNo = $catId;
        if (($pos = strrpos($catId, '-')) !== false) {
            $currentCatNo = substr($catId, $pos + 1);
        }

        $title        = ''; // ブラウザーのTitle
        //$metaKeywords = ''; // METAタグKeywords
        //$metaDesc     = ''; // METAタグDescription
        // カテゴリ名表示フラグ(0:非表示1:表示)
        $catNmVisible = getSeoCategoryDispFlag();
        // カテゴリ任意表示フラグ(0:非表示1:表示)
        //$catOptNmVisible = getSeoCategoryNiniDispFlag();
        // METAタグ区分(1:全て共通を使う2:個別優先で共通も使う3:個別設定のみを使う)
        //$metaKbn = getSeoMetaTagDispFlag();
        // TITLE区切り文字
        $titleSplit = getSeoTitleSplitWord();
        $dbc = new CategorySagasuQuerySel();
        $categoryName = '';
        $categorySetumei = '';
        $templateKey1 = 'TLC1';
        $templateKey2 = '000010';
        if ($currentCatNo) {
            // カテゴリ情報を取得する
            $queryParam = array('M04CATEGNO' => $currentCatNo);
            $dbc->setRecordsetArray($queryParam);
            $dbc->setSelectSql('1');
            $rs = $dbc->Execute($request);
            if (!$rs) {
                // DBエラー
                $request->setError('DBERROR', E_DB_EXECUTE_ERR);
                return VIEW_INPUT;
            }
            if ($rs->EOF) {
                $request->setError('categoryExistsError', implode("\n", [
                    '該当カテゴリが存在しておりません。',
                    'しばらく経って解消しない場合はお問い合わせください。',
                ]));
                $rs->close();
                return VIEW_INPUT;
            }
            $categoryName = $rs->Fields('M04CNAME');
            $categorySetumei = $rs->Fields('M04SETUMEI');
            $templateKey1 = $rs->fields('M04TMPLAT1');
            $templateKey2 = $rs->fields('M04TMPLAT2');
            $rs->close();
        }
        $request->setAttribute('categoryName', $categoryName);
        $request->setAttribute('categoryNo', $currentCatNo);
        $request->setAttribute('categorySetumei', $categorySetumei);
        //$categoryOptNm       = $rs->fields('M04NINNAME');
        //$categoryOptKbn      = $rs->fields('M04NINKBN');
        //$categoryMetaKeyword = $rs->fields('M04METKEY');
        //$categoryMetaDes     = $rs->fields('M04METDES');
        // METAタグKeywordsとMETAタグDescriptionとの設定
        //if ($metaKbn == '1') { // 全て共通を使う
        //    $metaKeywords = getSeoKyotsuMetaTagKeyword();
        //    $metaDesc     = getSeoKyotsuMetaTagDescription();
        //} else if ($metaKbn == '2') { // 個別優先で共通も使う
        //    if (trim($categoryMetaKeyword) != '') {
        //        $metaKeywords = $categoryMetaKeyword;
        //        $metaKeywords .= ',' . getSeoKyotsuMetaTagKeyword();
        //    } else {
        //        // マスタに設定されていない場合は 「{カテ名},{共通MetaKeywords}」
        //        $metaKeywords = $request->getAttribute('categoryName');
        //        $metaKeywords .= ',' . getSeoKyotsuMetaTagKeyword();
        //    }
        //    if (trim($categoryMetaDes) != '') {
        //        $metaDesc = $categoryMetaDes;
        //        $metaDesc .= getSeoKyotsuMetaTagDescription();
        //    } else {
        //        // マスタに設定されていない場合は 「{カテ名}のページです。{共通MetaDes}」
        //        $metaDesc = $request->getAttribute('categoryName');
        //        $metaDesc .= getSeoKyotsuMetaTagDescription();
        //    }
        //} else if ($metaKbn == '3') { // 個別設定のみを使う
        //    $metaKeywords = $categoryMetaKeyword;
        //    $metaDesc     = $categoryMetaDes;
        //}
        //$request->setAttribute('metaKeywords', $metaKeywords);
        //$request->setAttribute('metaDesc',     $metaDesc);
        // ブラウザーのタイトルの設定
        if ($catNmVisible == '1') {
            $title = $catId ? $categoryTree->getFullNameOfId($catId, $titleSplit, false) : '全ての商品';
        }
        //if ($catOptNmVisible == '1') {
        //    if ($categoryOptKbn == '0') {
        //        if ($title != '') {
        //            $title .= $titleSplit;
        //        }
        //        $title .= $categoryOptNm;
        //    } else if ($categoryOptKbn == '1') {
        //        if ($title != '') {
        //            $title = $titleSplit . $title;
        //        }
        //        $title = $categoryOptNm . $title;
        //    }
        //}
        $siteKbn = getSeoSiteNameDispFlag(); //サイト名表示区分(1:表示しない2:前に表示する3:後ろに表示する)
        $siteName = getSeoSiteName();
        if ($siteKbn == '2') {
            if ($title != '') {
                $title = $titleSplit . $title;
            }
            $title = $siteName . $title;
        } else if ($siteKbn == '3') {
            if ($title != '') {
                $title .= $titleSplit;
            }
            $title .= $siteName;
        }
        $request->setAttribute('title', $title);
        $actionUrl = $this->getActionUrl('SS', 'CS');
        $link = $actionUrl . 'group/' . urlencode($group);

        $stringOfPanTree = '';
        if ($catId) {
            $panTree = $categoryTree->getArrayOfPanTree($catId);
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
                        $stringOfPanTree .= '<li><a href="' . $link . '/catid/' . urlencode($panTree[$index]['id']) . '">' . $panTree[$index]['name'] . '</a></li>';
                    }
                }
            }
        } else {
            $stringOfPanTree .= '<li class="last"><a>全ての商品</a></li>';
        }
        $request->setAttribute('panTree', $stringOfPanTree);
        // パラメーターからのソートキーを取得する
        $sort = urldecode($request->getParameter('sort'));
        // パラメーターからのソートキーに対応した昇順降順を取得する
        $order = urldecode($request->getParameter('order'));
        // パラメーターからのブランド名を取得する
        $brand = urldecode($request->getParameter('brand'));
        // パラメーターからのキーワードで探すトリガーを取得する
        $kysg = urldecode($request->getParameter('kysg'));
        // パラメーターからのキーワード検索ワードを取得する
        $keyword = $request->getParameter('keyword');
        $request->setAttribute('keyword', $keyword);
        $keywordReal = $request->getParameter('keyword_real');
        $request->setAttribute('keyword_real', $keywordReal);

        // 【脆弱性診断から重大な警告を受けた箇所】ソートするカラム名にSQLインジェクション
        // M02VPOINT、M02INSDATEはホワイトリストになっているので、ソート対象が増えた場合に
        // 一緒にここのチェックも増やす必要がある。
        if ($order && $sort && $sort != 'M02VPOINT' && $sort != 'M02INSDATE') {
            $request->setError('sorterror', E_WRONG_PARAM_ERR);
            return VIEW_INPUT;
        }

        // カテゴリ一覧のテンプレートファイル名を取得する
        $templateFilePath = '';
        try {
            // DBから取得
            $codeMasterArray = CodeMaster::getCodeMaster($templateKey1, $templateKey2, null, null, true);
            $codeMaster = reset($codeMasterArray); // 先頭レコード1件
            $templateFilePath = SHOHIN_CATEGORY_TEMPLATE_DIR . '/' . $codeMaster['M03CHARA1'];
            $request->setAttribute('resultTemplate', $templateFilePath);
        } catch (WtException $e) { // コードマスタ情報が取得できなかった場合 or ファイルが存在しない場合
            WtApp::getLogger()->warn($e->getMessage());
        }

        if ($request->hasErrors()) {
            $request->setAttribute('arrShohin', array());
            $request->setAttribute('encoded_list_params', array());
        } else {
            // 商品一覧情報取得
            $arrShohin = $this->getArrShohin($request, $user, $dbc, $currentCatNo, $catId, $group, $sort, $order, $brand, $kysg, $keyword, $keywordReal);
            $request->setAttribute('arrShohin', $arrShohin);
            // リスト生成に必要なパラメータを配列化
            $listParams = array('catid'         => $catId,
                                'group'         => $group,
                                'sort'          => $sort,
                                'order'         => $order,
                                'brand'         => $brand,
                                'kysg'          => $kysg,
                                'keyword'       => $keyword,
                                'keyword_real'  => $keywordReal,
                                'curcatno'      => $currentCatNo,
                                'tplpath'       => $codeMaster['M03CHARA1'],
                                'catname'       => $request->getAttribute('categoryName'));
            // URL-safeにするため'+'は'-'、'/'は'_'に置き換える
            $encodedListParams = strtr(base64_encode(serialize($listParams)), array('+' => '-', '/' => '_'));
            $request->setAttribute('encoded_list_params', $encodedListParams);
            // お気に入り情報
            if (!$user->getAttribute('is_virtual_login')) {
                $okiniiriList = $this->getOkiniiriList($this->gcInfo->maincardNo);
                $request->setAttribute('okiniiri_list', $okiniiriList);
            }
        }
        // レコメンド商品一覧情報取得
        $remainPoint = $this->gcInfo->usablePoints;
        $kenshuGroup = $this->getShohinKenshuGroup();
        $recommend = new RecommendShohin($this);
        $arrShohinRecommend = $recommend->getRecommendShohinArray($remainPoint, $kenshuGroup, $currentCatNo);
        $arrShohinRecommendItem = array();
        foreach ($arrShohinRecommend as $key => $shohinInfo) {
            // 商品オブジェクト生成
            $shohin = new NormalShohin();
            $shohin->setAll($shohinInfo);
            $arrShohinRecommendItem[] = $shohin;
        }
        $request->setAttribute('arrShohinRecommend', $arrShohinRecommendItem);
        // ソートリスト情報取得
        $sortList = $this->getSortList($catId, $group, $sort, $order, $brand, $kysg, $keywordReal, $kenshuGroup);
        $request->setAttribute('sortList', $sortList);

        // ブランドリスト情報取得
        $brandList = $this->getBrandList($user, $dbc, $currentCatNo, $catId, $group, $sort, $order, $brand);
        $request->setAttribute('brandList', $brandList);

        // 人気商品ランキング情報取得
        //$shohinRankingArray = $this->getShohinRanking($currentCatNo, $kenshuGroup);
        //$request->setAttribute('shohinRankingArray', $shohinRankingArray); // 人気商品ランキング
        $tmpCatId = $catId;
        // WAKUWAKU204の名古屋と同様にする
        // ※スマートギフト本体と同様の挙動にしたい場合は以下4行のコメントアウトを外す
        //#if ($tmpCatId) {
        //#    $tmpCatIdAry = explode('-', $tmpCatId);
        //#    $tmpCatId = $tmpCatIdAry[0] . '-' . $tmpCatIdAry[1];
        //#}
        $currentKaiso = 0;
        $dirTree = $categoryTree->getArrayOfDirTree($tmpCatId);
        foreach ($dirTree as $item) {
            if ($item['id'] == $tmpCatId) {
                $currentKaiso = $item['kaiso'];
                break;
            }
        }
        $dirList = array(0 => Array('id' => $tmpCatId, 'name' => 'すべて', 'kaiso' => 1));
        foreach ($dirTree as $item) {
            if ($item['kaiso'] == $currentKaiso + 1) {
                $dirList[] = $item;
            }
        }
        $kaiso = 1;
        foreach ($dirList as $index => $item) {
            $dirList[$index]['link'] = $actionUrl;
            $dirList[$index]['link'] .= 'group/' . urlencode($group);
            $dirList[$index]['link'] .= '/catid/' . urlencode($item['id']);
            $dirList[$index]['link'] .= '/tabAllFlg/1';
            // 第二階層URLリンク JS化対応
            $dirList[$index]['group'] = urlencode($group);
            $dirList[$index]['catid'] = urlencode($item['id']);
            if ($item['id'] == $catId) {
                $dirList[$index]['link'] = '';
                $kaiso = $dirList[$index]['kaiso'];
            }
        }
        $request->setAttribute('kaiso', $kaiso);
        $request->setAttribute('dirList', $dirList);

        // 戻り先（自分自身）のURLをセッションにセット
        if (isset($_SERVER['REQUEST_URI'])) {
            $url = WT_URL_BASE_SSL . $_SERVER['REQUEST_URI'];
            $user->setAttribute('shohin_referer_url', $url);
        }
        return VIEW_INPUT;
    }

    /**
     * {@inheritdoc}
     */
    function getRequestMethods()
    {
        return REQ_GET;
    }

    /**
     * {@inheritdoc}
     */
    function handleError($controller, $request, $user)
    {
        return $this->execute($controller, $request, $user);
    }

    /**
     * {@inheritdoc}
     */
    function validate($controller, $request, $user)
    {
        $keyword = WtString::trim(urldecode($request->getParameter('keyword')));
        $request->setParameter('keyword', $keyword);
        $request->setParameter('keyword_real', $keyword);
        $appValidator = $this->_getValidator();
        $appValidator->h2z('keyword')->length('keyword', 'キーワード', false, null, 50, false);
        $this->_validate($appValidator);
        $appValidator->setErrors();
    }

    /**
     * ソートリンク取得
     *
     * 作成するソートリンクは以下の通り。
     * <pre>
     * ・低い順
     *   - 対象カラム: ポイント（M02VPOINT）
     *   - ソート順:   昇順（ASC）
     * ・高い順
     *   - 対象カラム: ポイント（M02VPOINT）
     *   - ソート順:   降順（DESC）
     * ・早い順
     *   - 対象カラム: 新着日（M02INSDATE）
     *   - ソート順:   降順（DESC）
     * </pre>
     *
     * @param string $catId   カテゴリーID
     * @param string $group   グループ名
     * @param string $sort    ソート対象の名称
     * @param string $order   ソート対象のソート順（DESC|ASC）
     * @param string $brand   ブランド絞り込み条件
     * @param string $kysg    キーワード検索トリガー
     * @param string $keyword キーワード検索ワード
     * @param string $kenshuGroup 券種グループ
     *
     * @return array ソートリンク
     */
    function getSortList($catId, $group, $sort, $order, $brand, $kysg, $keyword, $kenshuGroup)
    {
        $defaultSort = 'M02VPOINT';
        $defaultOrder = 'asc';
        if (isset($this->settings['default_item_sort']) && strlen($this->settings['default_item_sort'])) {
            switch ($this->settings['default_item_sort']) {
                case 'VPOINT_ASC':
                    break;
                case 'VPOINT_DESC':
                    $defaultOrder = 'desc';
                    break;
                case 'INSDATE_DESC':
                    $defaultSort = 'M02INSDATE';
                    $defaultOrder = 'desc';
                    break;
            }
        }
        $sortList = [
            ['name' => 'ポイントが低い順', 'sort' => 'M02VPOINT', 'order' => 'asc'],
            ['name' => 'ポイントが高い順', 'sort' => 'M02VPOINT', 'order' => 'desc'],
        ];
        if (($defaultSort === 'M02VPOINT') && ($defaultOrder === 'desc')) {
            $sortList = array_reverse($sortList);
        }
        $sortList[] = ['name' => '新着順', 'sort' => 'M02INSDATE', 'order' => 'desc'];
        if (!$sort && !$order) {
            $sort = $defaultSort;
            $order = $defaultOrder;
        }
        foreach ($sortList as $index => $item) {
            $sortList[$index]['link'] = $this->getActionUrl('SS', 'CS');
            $sortList[$index]['link'] .= 'group/' . urlencode($group);
            if ($catId) {
                $sortList[$index]['link'] .= '/catid/' . urlencode($catId);
            }
            $sortList[$index]['link'] .= '/sort/' . urlencode($sortList[$index]['sort']);
            $sortList[$index]['link'] .= '/order/' . urlencode($sortList[$index]['order']);
            // ブランドは引き継ぐ
            if ($brand) {
                $sortList[$index]['link'] .= '/brand/' . urlencode($brand);
            }
            $sortList[$index]['link'] .= '/tabAllFlg/1';
            if ($kysg) {
                $sortList[$index]['link'] .= '?kysg=' . urlencode($kysg) . '&keyword=' . urlencode($keyword);
            }
            // ソートが指定されたらリンクを非活性にする
            if ($sort && $order) {
                if ($sortList[$index]['order'] == $order && $sortList[$index]['sort'] == $sort) {
                    $sortList[$index]['link'] = '';
                }
            } else {
                $sortList[0]['link'] = '';
            }
        }
        return $sortList;
    }

    /**
     * ブランドリンク取得
     *
     * @param WtUser                 $user         WtUserオブジェクト
     * @param CategorySagasuQuerySel $dbc          CategorySagasuQuerySelオブジェクト
     * @param string                 $currentCatNo カテゴリ番号
     * @param string                 $catId        カテゴリ番号
     * @param string                 $group        グループ名
     * @param string                 $sort         ソート対象の名称
     * @param string                 $order        ソート対象のソート順（DESC|ASC）
     * @param string                 $brand        ブランド絞り込み条件
     *
     * @return array ブランドリンク
     */
    function getBrandList($user, $dbc, $currentCatNo, $catId, $group, $sort, $order, $brand)
    {
        // ブランド一覧をDBから取得する
        $queryParam = array(
            'F03CATEGNO' => $currentCatNo,
            'F22KENGROUP' => $this->getShohinKenshuGroup(),
        );
        $dbc->setRecordsetArray($queryParam);
        $dbc->setSelectSql('2');
        $rs = $dbc->Execute();
        if (!$rs) { // DBエラー
            throw new WtDbException(E_DB_EXECUTE_ERR);
        }

        // ブランド一覧の配列を作成する
        $brandList[0] = Array('name' => 'すべて');
        while (!$rs->EOF) {
            $brandList[] = Array('name' => trim($rs->fields('M02BRAND')));
            $rs->MoveNext();
        }
        $rs->close();

        // 画面表示用のブランドリンクを作成する
        foreach ($brandList as $index => $item) {
            $brandList[$index]['link'] = $this->getActionUrl('SS', 'CS');
            $brandList[$index]['link'] .= 'group/' . urlencode($group);
            if ($catId) {
                $brandList[$index]['link'] .= '/catid/' . urlencode($catId);
            }
            if ($index !== 0) {
                $brandList[$index]['link'] .= '/brand/' . urlencode($brandList[$index]['name']);
            }
            // ソートは引き継ぐ
            if ($sort && $order) {
                $brandList[$index]['link'] .= '/sort/' . urlencode($sort);
                $brandList[$index]['link'] .= '/order/' . urlencode($order);
            }
            $brandList[$index]['link'] .= '/tabAllFlg/1';
            // ブランドが指定されたらリンクを非活性にする（指定されていない時は「すべて」が非活性）
            if ($brand) {
                if ($brandList[$index]['name'] == $brand) {
                    $brandList[$index]['link'] = '';
                }
            } else {
                $brandList[0]['link'] = '';
            }
        }
        return $brandList;
    }

    /**
     * 人気商品ランキング情報を取得する。
     *
     * @param string $currentCatNo カテゴリ番号
     * @param string $kenshuGroup  券種グループ
     *
     * @return array 商品情報リスト
     */
    function getShohinRanking($currentCatNo, $kenshuGroup)
    {
        // 人気商品ランキング情報を取得
        $shohinkRanking = new NinkiShohinRanking($this);
        $arrShohinRanking = $shohinkRanking->getNinkiShohinRankingArray($currentCatNo, $kenshuGroup);
        if (!is_array($arrShohinRanking) || count($arrShohinRanking) == 0) {
            return array();
        }
        $arrShohinRankingItem = array();
        foreach ($arrShohinRanking as $key => $shohinInfo) {
            // 商品オブジェクト生成
            $shohin = new NormalShohin();
            $shohin->setAll($shohinInfo);
            $arrShohinRankingItem[] = $shohin;
        }

        return $arrShohinRankingItem;
    }
}
