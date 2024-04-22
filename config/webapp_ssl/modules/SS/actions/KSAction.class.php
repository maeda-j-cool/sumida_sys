<?php
require_once(__DIR__ . '/KSListAction.class.php');

class KSAction extends KSListAction
{
    /**
     * @var string プログラムID
     */
    protected $_modPg = 'S0202';

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        // パラメーターからのキーワード
        $kysg = $request->getParameter('kysg');
        $keyword = $request->getParameter('keyword');
        $keywordReal = $request->getParameter('keyword_real');
        // パン屑ツリー
        $panTree = '';
        if ($request->hasParameter('group') && $request->hasParameter('catid')) {
            $group = $request->getParameter('group');
            $catId = $request->getParameter('catid');
            $panTree = $this->getPanTree($request, $group, $catId);
            if ($panTree === false) {
                $panTree = '';
            }
        }
        $request->setAttribute('panTree', $panTree); // パン屑ツリー

        // ソート順取得
        $order = urldecode($request->getParameter('order'));
        $sort = urldecode($request->getParameter('sort'));

        // 【脆弱性診断から重大な警告を受けた箇所】ソートするカラム名にSQLインジェクション
        // M02VPOINT、M02INSDATEはホワイトリストになっているので、ソート対象が増えた場合に
        // 一緒にここのチェックも増やす必要がある。
        if ($order && $sort && $sort != 'M02VPOINT' && $sort != 'M02INSDATE') {
            $request->setError('sorterror', E_WRONG_PARAM_ERR);
            return VIEW_INPUT;
        }

        $sortList = $this->getSortList($order, $sort);
        $request->setAttribute('sortList', $sortList);

        $kysg = urldecode($kysg);
        //$keyword = urldecode($keyword);

        // 商品情報取得
        $dbc = new KeywordSagasuQuerySel();
        $arrShohin = $this->getArrShohin($request, $user, $dbc, $kysg, $keyword, $keywordReal, $order, $sort);
        $request->setAttribute('arrShohin', $arrShohin);
        // リスト生成に必要なパラメータを配列化
        $listParams = array('sort'          => $sort,
                            'order'         => $order,
                            'kysg'          => $kysg,
                            'keyword_real'  => $keywordReal,
                            'keyword'       => $keyword,);
        // URL-safeにするため'+'は'-'、'/'は'_'に置き換える
        $encodedListParams = strtr(base64_encode(serialize($listParams)), array('+' => '-', '/' => '_'));
        $request->setAttribute('encoded_list_params', $encodedListParams);

        // 戻り先（自分自身）のURLをセッションにセット
        if (isset($_SERVER['REQUEST_URI'])) {
            $url = 'http://' . WT_HOSTNAME . $_SERVER['REQUEST_URI'];
            $user->setAttribute('shohin_referer_url', $url);
        }
        return VIEW_INPUT;
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
        $this->_setPagerInfo(array(), $request, null, GET_PARAM_PAGE);
        return VIEW_INPUT;
    }

    /**
     * リクエスト判別
     *
     * @return integer REQ_POST|REQ_GET
     */
    function getRequestMethods()
    {
        return REQ_POST|REQ_GET;
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
        $user->setAttribute('currmod', $controller->getCurrentModule());
        $user->setAttribute('curract', $controller->getCurrentAction());
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
        $keyword = WtString::trim(urldecode($request->getParameter('keyword')));
        $request->setParameter('keyword', $keyword);
        $request->setParameter('keyword_real', $keyword);

        $appValidator = $this->_getValidator();

        $appValidator->h2z('keyword')->length('keyword', 'キーワード', true, null, 50, false);

        // バリデータ処理実行
        $this->_validate($appValidator);

        // エラー登録
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
     * @param string $order ソート対象のソート順（DESC|ASC）
     * @param string $sort  ソート対象の名称
     * @param string $kenshuGroup  券種グループ
     *
     * @return array ソートリンク
     */
    function getSortList($order, $sort)
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
            // 現在のソート順を判定
            $sortList[$index]['select'] = 'on';
            // ソートが指定されたらリンクを非活性にする
            if ($sort && $order) {
                if ($item['order'] == $order && $sortList[$index]['sort'] == $sort) {
                    $sortList[$index]['select'] = '';
                }
            } else {
                $sortList[0]['select'] = '';
            }
        }
        return $sortList;
    }

    /**
     * パン屑ナビゲーションの取得
     *
     * @param WtRequest $request  WtRequestオブジェクト
     * @param string    $prmGroup グループID
     * @param string    $prmCatid カテゴリID
     *
     * @return string パン屑ナビゲーション（HTMLタグ出力文字列）
     */
    function getPanTree($request, $prmGroup, $prmCatid)
    {
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

        $link = $this->getActionUrl('SS', 'KS');
        $link .= "group/" . urlencode($group);
        $panTree = $categoryTree->getArrayOfPanTree($catId);
        $stringOfPanTree = "";
        $cntOfPanTree = count($panTree);
        for ($index = 0; $index < $cntOfPanTree; $index++) {
            if ($panTree[$index]["id"] == "0") {
                $stringOfPanTree .= "<a href=\"" . WT_URL_BASE_SSL . "\">";
                $stringOfPanTree .= $panTree[$index]["name"];
                $stringOfPanTree .= "</a>";
            } else {
                $stringOfPanTree .= "<a href=\"";
                $stringOfPanTree .= $link;
                $stringOfPanTree .= "/catid/" . urlencode($panTree[$index]["id"]);
                $stringOfPanTree .= "\">";
                $stringOfPanTree .= $panTree[$index]["name"];
                $stringOfPanTree .= "</a>";
            }
        }

        return $stringOfPanTree;
    }

}
