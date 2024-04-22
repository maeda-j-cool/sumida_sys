<?php
class ToppageView extends SgView
{
    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $this->setTemplate(dirname(__DIR__) . '/templates/ShopTop.tpl');
        $renderer = $this->_renderer;
        //$shohinRankingArray = $request->getAttribute('shohinRankingArray');
        // 人気商品ランキング
        //$shohinRankingDispArray = array();
        //if (isset($shohinRankingArray) && is_array($shohinRankingArray)) {
        //    foreach ($shohinRankingArray as $shohin) {
        //        $shohinRankingDispArray[] = array(
        //            'no'           => $shohin->get('M02SHOHNNO'),       // 商品番号
        //            'code'         => trim($shohin->get('M02SHOHNCD')), // 商品コード
        //            'priceAndTax'  => $shohin->get('M02VPOINT'),        // ポイント
        //            'displayNm'    => $shohin->getSeoName(),            // 商品名（SEO）
        //            'linkToShosai' => $shohin->get('linkToShosai'),     // 商品詳細画面へのリンクURL
        //            'brandName'    => trim($shohin->get('M02BRAND')),   // ブランド名
        //        );
        //    }
        //}
        //$renderer->setAttribute('shohinRankingArray', $shohinRankingDispArray);
        return $renderer;
    }
}
