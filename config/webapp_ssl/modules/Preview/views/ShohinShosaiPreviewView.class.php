<?php
require_once(dirname(__DIR__, 2) . '/ShohinShosai/views/ShohinShosaiView_input.class.php');

class ShohinShosaiPreviewView extends ShohinShosaiView
{
    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        if ($request->hasErrors()) {
            $request->initErrors();
        }
        $this->_renderer->setAttribute('wt__is_login', true);
        return parent::execute($controller, $request, $user);
    }
}
