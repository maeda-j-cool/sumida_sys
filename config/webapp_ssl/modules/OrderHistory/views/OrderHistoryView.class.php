<?php
class OrderHistoryView extends SgView
{
    /**
     * {@inheritdoc}
     */
    public function execute($controller, $request, $user)
    {
        $this->setTemplate(dirname(__DIR__) . '/templates/OrderHistory.tpl');
        $renderer = $this->_renderer;
        $histories = $request->getAttribute('histories');
        if ($request->hasErrors() || empty($histories)) {
            $histories = null;
        }
        $renderer->setAttribute('histories', $histories);
        return $renderer;
    }
}

