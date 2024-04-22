<?php
class UpdateInputView extends SgView
{
    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $this->setTemplate(dirname(__DIR__) . '/templates/UpdateInput.tpl');
        return $this->_renderer;
    }
}
