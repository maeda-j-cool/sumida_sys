<?php
class ToiawaseKanryoAction extends SgAction
{
    /**
     * @var bool 認証が必要な画面かどうか
     */
    protected $_requireAuth = false;

    /**
     * @var bool アクションとモジュール名をセッションに保存するかどうか
     */
    protected $_saveCurrentAction = true;

    /**
     * @var bool
     */
    protected $defaultOnly = true;

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        throw new Exception('');
    }

    /**
     * {@inheritdoc}
     */
    function getDefaultView($controller, $request, $user)
    {
        $toiawaseNo = $user->getModuleParam('toiawase_no');
        if (!$toiawaseNo) {
            $controller->redirect($this->getActionUrl('Toiawase', 'Toiawase'));
            return VIEW_NONE;
        }
        $request->setAttribute('toiawase_no', $toiawaseNo);
        return VIEW_INPUT;
    }
}