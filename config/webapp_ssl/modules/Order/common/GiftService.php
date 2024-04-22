<?php
/**
 * ProjectName : スマートギフト
 * Subsystem   : 通販webシステム
 */
require_once(WT_ROOT_DIR . 'webapp_ssl/modules/Order/common/AbsOrderElement.php');

/**
 * ギフトサービスクラス
 *
 * @author  Yuki Tanaka, Saito
 * @version Release:<1.0>
 */
class GiftService extends AbsOrderElement
{
    /**
     * @var string セッション名
     */
    protected $_sessionName = 'giftservice_info';

    // 各リクエスト、セッションキー
    const HOSO_NO            = 'hoso_no';            // 包装番号
    const GREETINGCARD_NO    = 'greetingcard_no';    // 挨拶状番号
    const NOSHI_NO           = 'noshi_no';           // のし番号
    const NOSHI_SHURUI       = 'noshi_shurui';       // のし上
    const NOSHI_SONOTA_NAIYO = 'noshi_sonota_naiyo'; // のしその他内容
    const NOSHI_NAME_RIGHT   = 'noshi_name_right';   // のし名前右
    const NOSHI_NAME_LEFT    = 'noshi_name_left';    // のし名前左
    const NOSHI_NAME_FLG     = 'noshi_name_flg';     // のし名前フラグ

    /**
     * @var array 初期化用データ
     */
    protected $_initData = array(
        self::__IS_VALID         => false,
        self::HOSO_NO            => 0,    // F17HOSONO smallint
        self::GREETINGCARD_NO    => '00', // 未使用
        self::NOSHI_NO           => '00', // F17NKBN   char(2)
        self::NOSHI_SHURUI       => '00', // F17NUKBN  char(2)
        self::NOSHI_SONOTA_NAIYO => '',   // F17NUHOKA varchar(50)
        self::NOSHI_NAME_RIGHT   => '',   // F17NSMEI  varchar(50)
        self::NOSHI_NAME_LEFT    => '',   // F17NSMEIK varchar(50)
        self::NOSHI_NAME_FLG     => '0',  // 
    );
}
