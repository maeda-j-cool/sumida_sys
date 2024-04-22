<?php
class LoginView extends SgView
{
    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $this->setTemplate(dirname(__DIR__) . '/templates/Login.tpl', false);
        $renderer = $this->_renderer;
        return $renderer;
    }
}
