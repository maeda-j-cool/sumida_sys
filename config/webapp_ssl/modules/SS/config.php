<?php
/**
 * ProjectName : スマートギフト交換サイト
 * Subsystem   : 通販Webシステム
 *
 * @package webapp_ssl
 */


define('CS_PAGE_KEY', '__cspk__');
define('KS_PAGE_KEY', '__kspk__');

/**
 * セッションマッピング情報 (モジュール単位)
 * ※キー'cur'で指定されたモジュールはキー'use'で指定されたモジュールと同一とみなす
 *   モジュール内でのみ保持されるセッション情報を複数の指定されたモジュール内で保持させるための設定
 */
WtApp::setConfig(
    'MOD_SESSION_MAP',
    array(
        array(
            'cur' => array('webapp_ssl', 'SS'),
            'use' => array('webapp_ssl', 'ShohinShosai')
        ),
    )
);
