<?php
// charset = UTF-8
if (!defined('WT_APP_PATH')) {
    if (!isset($_SERVER['SCRIPT_NAME'])) {
        error_log('_SERVER["SCRIPT_NAME"] not found.');
        die('');
    }
    define('WT_APP_PATH', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
}
$kenshuGroup = trim(WT_APP_PATH, '/');
require_once(dirname(__FILE__, 4) . '/config/wt/WtApp.php');
try {
    $settings = WtApp::getSettings($kenshuGroup);
} catch (\Exception $e) {
    error_log($e->getMessage());
    header('HTTP', true, 500);
    die('');
}
WtApp::setConfig('settings', $settings);
WtApp::setConfig('kenshu_group', $kenshuGroup);
include(dirname(__DIR__) . '/index.php');
