<?php
/**
 * 静的ページ設定
 */
define('BASE_TYPE', 'webapp_ssl');
require_once dirname(__DIR__, 2) . '/config/wt/WtApp.php';
WtApp::init();
$settings = include(dirname(__DIR__, 2) . "/config/settings/default.ini.php");

define('INCLUDE_PATH_HEADER', __DIR__ . '/static_include/header.html');
define('INCLUDE_PATH_FOOTER', __DIR__ . '/static_include/footer.html');
define('INCLUDE_PATH_GTM_HEAD', __DIR__ . '/static_include/gtm_head.html');
define('INCLUDE_PATH_GTM_BODY', __DIR__ . '/static_include/gtm_body.html');

$username = $remainPoint = $expiryDate = '';
$isLogin = $isVirtualLogin = false;

$kenshuGroup = WT_DEFAULT_KENSHU_GROUP;
$user = new WtUser();
$user->load();
if (!$user->isSessionError() && !$user->isSessionTimeout()) {
    $isLogin = $user->isAuthenticated();
    if ($isLogin) {
        /** @var SgGiftcardInfo $gcInfo */
        $gcInfo = $user->getGiftcardInfo();
        $username = WtString::escape($gcInfo->userName);
        $remainPoint = $gcInfo->usablePoints;
        $yyyymmdd = $gcInfo->expiryYmd;
        if (preg_match('/^[\d]{8}$/', $yyyymmdd)) {
            $dt = new WtDateTime($yyyymmdd);
            $format = 'Y年m月d日(D)';
            $expiryDate = WtString::escape($dt->formatJp($format));
        }
        $kenshuGroup = $gcInfo->kenshuGroup;
        $isVirtualLogin = $user->getAttribute('is_virtual_login');
        if ($kenshuGroup !== WT_DEFAULT_KENSHU_GROUP) {
            $settings = WtApp::getSettings($kenshuGroup);
        }
    }
}
define('SITE_TITLE', $settings['site_name']);
$controller = new StaticController(WtController::getInstance(), $kenshuGroup);
class StaticController
{
    private $controller;
    private $kenshuGroup;

    public function __construct($controller, $kenshuGroup)
    {
        $this->controller = $controller;
        $this->kenshuGroup = $kenshuGroup;
    }

    public function getActionUrl($appName, $modName, $actName, $absolute = true, $kenshuGroup = null)
    {
        return $this->controller->getActionUrl($appName, $modName, $actName, $absolute, $kenshuGroup ?? $this->kenshuGroup);
    }

    //public function __call($method, $arguments)
    //{
    //    if (!method_exists($this->controller, $method)) {
    //        throw new \Exception();
    //    }
    //    return call_user_func_array([$method, $method], $arguments);
    //}
}
