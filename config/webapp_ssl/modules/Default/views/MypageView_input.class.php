<?php
class MypageView extends SgView
{
    /**
     * @var string
     */
    protected $_title = 'マイページ';

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $this->setTemplate(dirname(__DIR__) . '/templates/Mypage.tpl');
        return $this->_renderer;
    }
}
