<?php
class UpdateCompleteView extends SgView
{
    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $this->setTemplate(dirname(__DIR__) . '/templates/UpdateComplete.tpl');
        return $this->_renderer;
    }
}
