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
require_once(WT_ROOT_DIR . 'webapp_ssl/modules/SS/views/KSListView.class.php');
/**
 * キーワードから商品を探すviewクラス
 *
 * @author  Keisuke Yamamoto
 * @version Release:<1.0>
 */
class KSView extends KSListView
{
    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $this->setTemplate(WT_ROOT_DIR . 'webapp_ssl/modules/SS/templates/KeywordSagasu.tpl');
        $renderer = $this->_renderer;

        $keyword = $request->getParameter('keyword_real');
        $renderer->setAttribute('keyword', $keyword);
        $renderer->setAttribute('title', $keyword);

        $this->_setDispShohin($request, $renderer);

        $renderer->setAttribute('sortList', $request->getAttribute('sortList'));

        $renderer->setAttribute('encoded_list_params', $request->getAttribute('encoded_list_params'));
        // 結果件数
        $renderer->setAttribute('resultCount', $request->getAttribute('resultCount'));

        return $renderer;
    }
}
