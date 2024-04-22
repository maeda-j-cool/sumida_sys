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
class OrderConfirmView extends SgView
{
    /**
     * @var string
     */
    protected $_title = 'お申込み確認';

    /**
     * {@inheritdoc}
     */
    public function execute($controller, $request, $user)
    {
        $this->setTemplate(CHUMON_TEMPLATE_DIR . 'OrderConfirm.tpl');
        return $this->_renderer;
    }
}
