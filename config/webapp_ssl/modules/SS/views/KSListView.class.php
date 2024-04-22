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
 * キーワードから商品を探すviewクラス
 *
 * @author  Keisuke Yamamoto
 * @version Release:<1.0>
 */
class KSListView extends SgView
{
    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $this->setTemplate(WT_ROOT_DIR . 'webapp_ssl/modules/SS/templates/KeywordSagasu.tpl', false);
        $renderer = $this->_renderer;
        $renderer->setAttribute('keyword', $request->getParameter('keyword_real'));

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
        $shohins = $request->getAttribute('arrShohin');
        $arrShohin = array();
        // 検索結果が0件の場合は、メッセージを表示する
        if (!empty($shohins)) {
            //--------------------------------
            // 商品一覧表示情報作成
            //--------------------------------
            foreach ($shohins as $shohin) {
                $arrGroupShohin = $shohin->getAll();
                $groupShohinFlg = false;
                $groupShohin = array();
                if (isset($arrGroupShohin['groupShohinFlg']) && $arrGroupShohin['groupShohinFlg']) {
                    $groupShohinFlg = $arrGroupShohin['groupShohinFlg'];
                    $groupShohin = $arrGroupShohin['groupShohin'];
                }
                $arrShohin[] = array(
                    'no'             => $shohin->get('M02SHOHNNO'),       // 商品番号
                    'code'           => trim($shohin->get('M02SHOHNCD')), // 商品コード
                    'priceAndTax'    => $shohin->get('M02VPOINT'),        // ポイント
                    'displayNm'      => $shohin->get('M02SNAME'),         // 商品名
                    'brandName'      => $shohin->get('M02BRAND'),         // ブランド名
                    'linkToShosai'   => $shohin->get('linkToShosai'),     // 商品詳細画面へのリンクURL
                    'groupShohinFlg' => $groupShohinFlg,                  // 関連商品判定フラグ
                    'groupShohin'    => $groupShohin,                     // 関連商品
                );
            }
        }
        $shohinCount = count($arrShohin);
        $renderer->setAttribute('shohinCount', $shohinCount);
        $renderer->setAttribute('arrShohin', $arrShohin);
    }
}
