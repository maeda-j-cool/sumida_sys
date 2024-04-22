<?php
/**
 * ProjectName : スマートギフトサイト
 * Subsystem   : ギフトカードを利用した商品交換モールサイト
 *
 * @package webapp_ssl
 */

/**
 * 注文者情報入力Viewクラス(S0101)
 *
 * @author  Yuki Tanaka
 * @version Release:<1.0>
 */
class OrdererInfoInputView extends SgView
{
    /**
     * @var string
     */
    protected $_title = 'カート';

    /**
     * {@inheritdoc}
     */
    public function execute($controller, $request, $user)
    {
        $this->setTemplate(CHUMON_TEMPLATE_DIR . 'OrdererInfoInput.tpl');
        return $this->_renderer;
    }
}
