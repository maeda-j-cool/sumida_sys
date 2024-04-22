<?php
/**
 * ProjectName:スマートギフトサイト
 * Subsystem:ギフトカードを利用した商品交換モールサイト
 *
 * PHP versions 5.3.1
 *
 * @package webapp_ssl
 */

/**
 * カテゴリ検索 リスト表示部分
 *
 * @author  Keisuke Yamamoto
 * @version Release:<1.0>
 */
class CSListView extends SgView
{
    const DEFAULT_TEMPLATE_FILE = 'normal_category.tpl';

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $template = $request->getAttribute('resultTemplate');
        if (!$template) {
            $template = SHOHIN_CATEGORY_TEMPLATE_DIR . self::DEFAULT_TEMPLATE_FILE;
        }
        $this->setTemplate($template, false);
        $renderer = $this->_renderer;

        $this->_setDispShohin($request, $renderer);
        $renderer->setAttribute('show_list', true);
        // 結果件数
        $renderer->setAttribute('resultCount', $request->getAttribute('resultCount'));
        $searchConds = $request->getAttribute('search_conds');
        if (is_array($searchConds)) {
            foreach ($searchConds as $k => $v) {
                $renderer->setAttribute($k, $v);
            }
        }
        return $renderer;
    }

    /**
     * 商品リストの表示用パラメータ設定
     *
     * @param WtRequest  $request  WtRequestオブジェクト
     * @param WtRenderer $renderer WtRendererオブジェクト
     *
     * @return void
     */
    protected function _setDispShohin($request, $renderer)
    {
        // 検索結果がない場合は、メッセージを表示する
        $arrShohin = $request->getAttribute('arrShohin');
        if (empty($arrShohin)) {
            $renderer->setAttribute('wt__pager_info', array('last' => 0));
            $renderer->setAttribute('encoded_list_params', '');
            if (isSmartPhone()) {
                $renderer->setAttribute('panTree', '<li><a>該当データなし</a></li>');
            } else {
                $renderer->setAttribute('panTree', '<li>該当データなし</li>');
            }
            $renderer->setAttribute('title', '該当データなし');
        }
        //--------------------------------
        // 商品一覧表示情報作成
        //--------------------------------
        $categoryName = $request->getAttribute('categoryName');
        $arrDispShohin = array();
        foreach ($arrShohin as $shohin) {
            $arrGroupShohin = $shohin->getAll();
            $groupShohinFlg = false;
            $groupShohin = array();
            if (isset($arrGroupShohin['groupShohinFlg']) && $arrGroupShohin['groupShohinFlg']) {
                $groupShohinFlg = $arrGroupShohin['groupShohinFlg'];
                $groupShohin = $arrGroupShohin['groupShohin'];
            }
            $arrDispShohin[] = array(
                'no'             => $shohin->get('M02SHOHNNO'),       // 商品番号
                'code'           => trim($shohin->get('M02SHOHNCD')), // 商品コード
                'priceAndTax'    => $shohin->get('M02VPOINT'),        // ポイント
                'displayNm'      => $shohin->get('M02SNAME'),         // 商品名
                'brandName'      => trim($shohin->get('M02BRAND')),   // ブランド名
                'linkToShosai'   => $shohin->get('linkToShosai'),     // 商品詳細画面へのリンクURL
                'groupShohinFlg' => $groupShohinFlg,                  // 関連商品判定フラグ
                'groupShohin'    => $groupShohin,                     // 関連商品
            );
        }
        $shohinCount = count($arrShohin);
        $renderer->setAttribute('shohinCount', $shohinCount);
        $renderer->setAttribute('arrShohin',   $arrDispShohin);
    }
}
