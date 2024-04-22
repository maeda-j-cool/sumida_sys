<?php
class RegisterCompleteAction extends SgAction
{
    /**
     * @var bool 認証が必要な画面かどうか
     */
    protected $_requireAuth = false;

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
        if ($user->getAttribute('member_edit_complete')) {
            $user->removeAttribute('member_edit_complete');
            // ログイン済にする
            $user->setAuthenticated(true);
            $user->setAttribute('is_virtual_login', false);
        }
        $controller->redirect(WT_URL_BASE_SSL);
        return VIEW_NONE;
    }
}
