<?php
// charset = UTF-8

// @No.1:
// ・パスパラメータ「/type/trial」を持つ場合、仮想ギフトカード情報（券種グループ「岐阜県（gifu）」）を生成し、機能「トップページ」へ遷移する。

if (!isset($_SERVER['SCRIPT_NAME'])) {
    die('');
}
define('WT_APP_PATH', rtrim(dirname($_SERVER['SCRIPT_NAME'], 3), '/'));
define('WT_VIRTUAL_LOGIN', true);
include(dirname(dirname(__DIR__)) . '/index.php');
