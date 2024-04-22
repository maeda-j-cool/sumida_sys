<?php
class PageNotFoundAction extends WtAction
{
    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $controller->redirect(WT_URL_BASE . 'systemerror/notfound.html');
        return VIEW_NONE;
    }

    /**
     * {@inheritdoc}
     */
    function getDefaultView($controller, $request, $user)
    {
        $controller->redirect(WT_URL_BASE . 'systemerror/notfound.html');
        return VIEW_NONE;
    }

    /**
     * {@inheritdoc}
     */
    function getRequestMethods()
    {
        return REQ_POST;
    }

    /**
     * {@inheritdoc}
     */
    function handleError($controller, $request, $user)
    {
        return $this->getDefaultView($controller, $request, $user);
    }

    /**
     * {@inheritdoc}
     */
    function isSecure($controller, $user)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    function registerValidators($validatorManager, $controller, $request, $user)
    {
    }

    /**
     * {@inheritdoc}
     */
    function validate($controller, $request, $user)
    {
    }
}
