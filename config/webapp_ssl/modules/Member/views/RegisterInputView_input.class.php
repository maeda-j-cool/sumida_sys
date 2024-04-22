<?php
class RegisterInputView extends SgView
{
    /**
     * @var string
     */
    protected $_title = 'ご利用登録';

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $this->setTemplate(dirname(__DIR__) . '/templates/RegisterInput.tpl');
        return $this->_renderer;
    }
}
