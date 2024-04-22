<?php
class UpdateCompleteView extends SgView
{
    /**
     * @var string
     */
    protected $_title = 'ご利用登録変更完了';

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $this->setTemplate(dirname(__DIR__) . '/templates/UpdateComplete.tpl');
        return $this->_renderer;
    }
}
