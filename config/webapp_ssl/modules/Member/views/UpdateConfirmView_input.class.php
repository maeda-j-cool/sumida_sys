<?php
class UpdateConfirmView extends SgView
{
    /**
     * @var string
     */
    protected $_title = 'ご利用登録変更確認';

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $this->setTemplate(dirname(__DIR__) . '/templates/UpdateConfirm.tpl');
        return $this->_renderer;
    }
}
