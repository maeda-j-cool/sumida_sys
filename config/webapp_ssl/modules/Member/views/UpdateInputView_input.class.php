<?php
class UpdateInputView extends SgView
{
    /**
     * @var string
     */
    protected $_title = 'ご利用登録変更';

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $this->setTemplate(dirname(__DIR__) . '/templates/UpdateInput.tpl');
        return $this->_renderer;
    }
}
