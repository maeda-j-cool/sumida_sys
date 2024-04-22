<?php
class UpdateCompleteAction extends SgAction
{
    /**
     * @var bool 認証が必要な画面かどうか
     */
    protected $_requireAuth = true;

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        return $this->getDefaultView($controller, $request, $user);
    }

    /**
     * {@inheritdoc}
     */
    function getDefaultView($controller, $request, $user)
    {
        if (!$user->hasAttribute('member_edit_complete')) {
            $controller->redirect(WT_URL_BASE_SSL);
            return VIEW_NONE;
        }
        $user->removeAttribute('member_edit_complete');
        return VIEW_INPUT;
    }
}
