<?php
class UpdateCompleteAction extends SgAction
{
    /**
     * @var bool 認証が必要な画面かどうか
     */
    protected $_requireAuth = false;

    /**
     * @var bool アクションとモジュール名をセッションに保存するかどうか
     */
    protected $_saveCurrentAction = false;

    /**
     * {@inheritdoc}
     */
    function getDefaultView($controller, $request, $user)
    {
        if (!$user->getModuleParam('password_update_complete', true)) {
            $controller->redirect(WT_URL_BASE);
            return VIEW_NONE;
        }
        return VIEW_INPUT;
    }
}