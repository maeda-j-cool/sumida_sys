<?php
require_once(dirname(__DIR__, 2) . '/ShohinShosai/actions/ShohinShosaiAction.class.php');

class ShohinShosaiPreviewAction extends ShohinShosaiAction
{
    protected $gcInfoOrig;

    /**
     * {@inheritdoc}
     */
    function _initialize($controller, $request, $user)
    {
        if (!isset($_SERVER['PHP_AUTH_USER'])
            || !isset($_SERVER['PHP_AUTH_PW'])
            || ($_SERVER['PHP_AUTH_USER'] !== PREVIEW_BASIC_AUTH_USER)
            || ($_SERVER['PHP_AUTH_PW'] !== PREVIEW_BASIC_AUTH_PW)
        ) {
            header('WWW-Authenticate: Basic realm="preview"');
            header('HTTP/1.0 401 Unauthorized');
            exit();
        }
        if ($user->isAuthenticated()) {
            $this->gcInfoOrig = $user->getGiftcardInfo();
        } else {
            $user->setAuthenticated(true);
        }
        $gcInfo = new SgGiftcardInfo($request->getParameter('kgr'), VIRTUALITY_LOGIN_GIFTCARD_NO, '1234');
        $gcInfo->usablePoints = 999999;
        $gcInfo->userName = '管理プレビュー';
        $gcInfo->expiryYmd = date('Ymd');
        $user->setGiftcardInfo($gcInfo);
        parent::_initialize($controller, $request, $user);
    }

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        // 今回は編集ページからの入力中の情報表示はなし
        return $this->getDefaultView($controller, $request, $user);
    }

    /**
     * {@inheritdoc}
     */
    function getDefaultView($controller, $request, $user)
    {
        $request->setAttribute('gtm_customer_area', '');
        $request->setAttribute('gtm_customer_status', '');
        $returnViewParam = parent::getDefaultView($controller, $request, $user);
        if ($this->gcInfoOrig) {
            $user->setGiftcardInfo($this->gcInfoOrig);
        } else {
            $user->setAuthenticated(false);
        }
        return $returnViewParam;
    }

    /**
     * {@inheritdoc}
     */
    protected function _getShohin($shohinNo, $request, $kenshuGroup)
    {
        return new NormalShohin($shohinNo, false, $kenshuGroup);
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
    function getShohinCheckRireki($shohinNo, $kenshuGroup)
    {
        return [];
    }

    protected function _insertOkiniiriShohin($request, $user, $shohinNo, $giftCardNo)
    {
        return true;
    }

    protected function getShohinKenshuGroup()
    {
        return 'preview';
    }
}
