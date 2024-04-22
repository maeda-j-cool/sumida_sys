<?php
class ToiawaseKanryoView extends SgView
{
    /**
     * @var string
     */
    protected $_title = 'お問合せ内容完了';

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $this->setTemplate(dirname(__DIR__) . '/templates/ToiawaseKanryo.tpl');
        if ($request->getAttribute('is_virtual')) {
            $this->_renderer->setAttribute('wt__is_login', false);
            $this->_renderer->setAttribute('is_virtual', false);
        }
        return $this->_renderer;
    }
}