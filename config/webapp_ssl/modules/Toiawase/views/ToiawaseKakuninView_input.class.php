<?php
class ToiawaseKakuninView extends SgView
{
    /**
     * @var string
     */
    protected $_title = 'お問合せ内容確認';

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $this->setTemplate(dirname(__DIR__) . '/templates/ToiawaseKakunin.tpl');
        if ($request->getAttribute('is_virtual')) {
            $this->_renderer->setAttribute('wt__is_login', false);
            $this->_renderer->setAttribute('is_virtual', false);
        }
        return $this->_renderer;
    }
}