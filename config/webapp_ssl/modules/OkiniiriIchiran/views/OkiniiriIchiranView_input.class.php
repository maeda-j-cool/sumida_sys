<?php
class OkiniiriIchiranView extends SgView
{
    /**
     * @var string
     */
    protected $_title = 'お気に入り商品';

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $this->setTemplate(dirname(__DIR__) . '/templates/OkiniiriIchiran.tpl');
        return $this->_renderer;
    }
}
