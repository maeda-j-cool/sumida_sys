<?php
/**
 * ProjectName:スマートギフトサイト
 * Subsystem:ギフトカードを利用した商品交換モールサイト
 *
 * PHP versions 5.3.1
 *
 * @package webapp_ssl
 */
require_once(WT_ROOT_DIR . 'webapp_ssl/modules/SS/views/CSListView.class.php');
/**
 * カテゴリから商品を探すviewクラス
 *
 * @author  Keisuke Yamamoto
 * @version Release:<1.0>
 */
class CSView extends CSListView
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
        $this->setTemplate($template);
        $renderer = $this->_renderer;
        $renderer->setAttribute('keyword', $request->getParameter('keyword_real'));

        $catno = $request->getParameter('catid');
        $pos = strrpos($catno, '-');
        if ($pos !== false) {
            $catno = substr($catno, $pos + 1);
        }
        $renderer->setAttribute('catno', $catno);
        $renderer->setAttribute('group', $request->getParameter('group'));
        $renderer->setAttribute('catid', $request->getParameter('catid'));
        $renderer->setAttribute('brand', $request->getParameter('brand'));

        // 第一階層のカテゴリ番号を取得
        $headerCateNo = $request->getParameter('catid');
        $pos = strpos($headerCateNo, '-');
        if ($pos !== false) {
            $headerCateNo = substr($headerCateNo, $pos + 1);
        }
        $renderer->setAttribute('headerCateNo', $headerCateNo);

        if ($request->hasErrors()) {
            $renderer->setAttribute('wt__pager_info', array('last' => 0));
            $renderer->setAttribute('encoded_list_params', '');
            if (isSmartPhone()) {
                $renderer->setAttribute('panTree', '<li><a>該当データなし</a></li>');
            } else {
                $renderer->setAttribute('panTree', '<li>該当データなし</li>');
            }
            $renderer->setAttribute('title', '該当データなし');
            return $renderer;
        }
        $this->_setDispShohin($request, $renderer);

        //--------------------------------
        // 商品一覧表示情報作成
        //--------------------------------
        $categoryName = $request->getAttribute('categoryName');
        $renderer->setAttribute('title', $categoryName ?: '全ての商品');

        // 商品一覧データ（レコメンド）
        $arrShohinRecommend = $request->getAttribute("arrShohinRecommend");
        $arrDispShohinRecommend = array();
        foreach ($arrShohinRecommend as $shohin) {
            $arrDispShohinRecommend[] = array(
                'no'           => $shohin->get('M02SHOHNNO'),       // 商品番号
                'code'         => trim($shohin->get('M02SHOHNCD')), // 商品コード
                'priceAndTax'  => $shohin->get('M02VPOINT'),        // ポイント
                'displayNm'    => $shohin->get('M02SNAME'),         // 商品名
                'brandName'    => trim($shohin->get('M02BRAND')),   // ブランド名
                'linkToShosai' => $shohin->get('linkToShosai'),     // 商品詳細画面へのリンクURL
            );
        }
        $shohinCountRecommend = count($arrShohinRecommend);
        $renderer->setAttribute('shohinCountRecommend', $shohinCountRecommend);
        $renderer->setAttribute('arrShohinRecommend',   $arrDispShohinRecommend);
        $renderer->setAttribute('kaiso',                $request->getAttribute('kaiso'));

        // ソートリンク
        $renderer->setAttribute('sortList', $request->getAttribute('sortList'));

        // ブランドリンク
        $renderer->setAttribute('brandList', $request->getAttribute('brandList'));

        // 人気商品ランキング
        //$shohinRankingArray = $request->getAttribute('shohinRankingArray'); // 人気商品ランキング
        //$shohinRankingDispArray = array();
        //if (isset($shohinRankingArray) && is_array($shohinRankingArray)) {
        //    foreach ($shohinRankingArray as $shohin) {
        //        $shohinRankingDispArray[] = array(
        //            'no'           => $shohin->get('M02SHOHNNO'),       // 商品番号
        //            'code'         => trim($shohin->get('M02SHOHNCD')), // 商品コード
        //            'priceAndTax'  => $shohin->get('M02VPOINT'),        // ポイント
        //            'displayNm'    => $shohin->getSeoName(),            // 商品名（SEO）
        //            'brandName'    => trim($shohin->get('M02BRAND')),   // ブランド名
        //            'linkToShosai' => $shohin->get('linkToShosai'),     // 商品詳細画面へのリンクURL
        //        );
        //    }
        //}
        //$renderer->setAttribute('shohinRankingArray', $shohinRankingDispArray);

        // 結果件数
        $renderer->setAttribute('resultCount', $request->getAttribute('resultCount'));

        if ($request->hasParameter('tabAllFlg')) {
            $renderer->setAttribute('tabAllVisible', true);
        } else {
            $renderer->setAttribute('tabAllVisible', false);
        }

        return $renderer;
    }
}
