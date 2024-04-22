<?php
class RegisterConfirmView extends SgView
{
    /**
     * @var string
     */
    protected $_title = 'ご利用登録確認';

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $this->setTemplate(dirname(__DIR__) . '/templates/RegisterConfirm.tpl');
        return $this->_renderer;
    }
}
