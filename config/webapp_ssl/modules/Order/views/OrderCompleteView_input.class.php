<?php
/**
 * ProjectName : スマートギフトサイト
 * Subsystem   : ギフトカードを利用した商品交換モールサイト
 *
 * @package webapp_ssl
 */

/**
 * カード情報入力Viewクラス(S0101)
 *
 * @author  Yuki Tanaka
 * @version Release:<1.0>
 */
class OrderCompleteView extends SgView
{
    /**
     * @var string
     */
    protected $_title = 'お申込み完了';

    /**
     * {@inheritdoc}
     */
    public function execute($controller, $request, $user)
    {
        $this->setTemplate(CHUMON_TEMPLATE_DIR . 'OrderComplete.tpl');
        return $this->_renderer;
    }
}
