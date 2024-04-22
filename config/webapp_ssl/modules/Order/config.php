<?php
/**
 * ProjectName : スマートギフトサイト
 * Subsystem   : ギフトカードを利用した商品交換モールサイト
 *
 * PHP versions 5.4.17
 *
 * @package webapp_ssl
 */
require_once(WT_ROOT_DIR . 'webapp_ssl/modules/Order/actions/AbstractOrderAction.class.php');
require_once(WT_ROOT_DIR . 'webapp_ssl/modules/Order/common/OrderCommonClass.php');

//ギフトカード、ビリング伝票番号用のプレフィックス
define('TGC_PAYMENT_PREFIX', 'SG');
define('BJP_PAYMENT_PREFIX', SHOP_ID);
define('GMO_PAYMENT_PREFIX', 'GM');

//機能ID
define('MOD_PGM_ID', 'S0110');

//WEB受注番号の先頭
define('PRE_JUCHUNO', '7176');

//一年のunixTimestamp値
//365 * 24 * 60 * 60
define('UNIXTIMESTAMP_YEAR', 31536000);

/**
 * 注文失敗管理者用メール
 */
define('ORDER_FAILURE_ADMIN_MAIL_TEMPLATE', 'OrderFailureAdmin.tpl');
define('ORDER_FAILURE_ADMIN_MAIL_LOG', '%smail/log/OrderfailureAdmin_%s.log');

// Google Analytics タグ用アカウント
define('GAACCOUNT', 'UA-46040339-1');

/**
 * 消費税率を取得
 *
 * @return float $taxRate;
 */
function getTaxRate()
{
    $taxRate = 0;
    if (strtotime(date('Y-m-d')) < strtotime('2019-10-01')) {
        $taxRate = 0.08;
    } else {
        $taxRate = 0.1;
    }
    return $taxRate;
}
