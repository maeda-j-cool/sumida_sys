<?php
// charset = UTF-8
require_once(dirname(__FILE__, 3) . '/config/wt/WtApp.php');
if (!WtApp::getConfig('settings') || !WtApp::getConfig('kenshu_group')) {
    try {
        $settings = WtApp::getSettings();
    } catch (\Exception $e) {
        error_log($e->getMessage());
        header('HTTP', true, 500);
        die('');
    }
    WtApp::setConfig('settings', $settings);
    WtApp::setConfig('kenshu_group', WT_DEFAULT_KENSHU_GROUP);
}
// type/trialからの遷移時(仮想ログイン)などにWT_VIRTUAL_LOGIN=trueに定義されて呼び出される
if (!defined('WT_VIRTUAL_LOGIN')) {
    define('WT_VIRTUAL_LOGIN', false);
}
// セッションクッキーにSameSite属性を付与する(Lax)
$cookieParams = session_get_cookie_params();
session_set_cookie_params([
    'lifetime' => $cookieParams['lifetime'],
    'path' => '/',
    'domain' => $cookieParams['domain'],
    'secure' => $cookieParams['secure'],
    'httponly' => $cookieParams['httponly'],
    'samesite' => 'Lax',
]);
WtApp::setConfig('enable_virtual_login', WT_VIRTUAL_LOGIN);
WtApp::run('webapp_ssl');
