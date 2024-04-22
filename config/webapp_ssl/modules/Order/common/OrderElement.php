<?php
/**
 * ProjectName : スマートギフトサイト
 * Subsystem   : ギフトカードを利用した商品交換モールサイト
 */
require_once(WT_ROOT_DIR . 'webapp_ssl/modules/Order/common/AbsOrderElement.php');

/**
 * 注文者クラス
 *
 * @author  Yuki Tanaka
 * @version Release:<1.0>
 */
class Chumonsha extends AbsOrderElement
{
    /**
     * セッションの名前
     * @var string
     */
    protected $_sessionName = 'chumonsha_info';

    // 各リクエスト、セッションキー
    const USE_FLG              = 'use_flg';              // ご利用用途フラグ
    const SEI_KANJI            = 'sei_kanji';            // 姓漢字
    const MEI_KANJI            = 'mei_kanji';            // 名漢字
    const SEI_KANA             = 'sei_kana';             // 姓カナ
    const MEI_KANA             = 'mei_kana';             // 名カナ
    const ZIP1                 = 'zip1';                 // 郵便番号１
    const ZIP2                 = 'zip2';                 // 郵便番号２
    const ADD1                 = 'add1';                 // 住所１
    const ADD2                 = 'add2';                 // 住所２
    const ADD3                 = 'add3';                 // 住所３
    const TEL_SHIGAI           = 'tel_shigai';           // 電話市外
    const TEL_SHINAI           = 'tel_shinai';           // 電話市内
    const TEL_KYOKUNAI         = 'tel_kyokunai';         // 電話局内
    const EMAIL_ADDRESS        = 'email_address';        // emailアドレス
    const EMAIL_ADDRESS_VERIFY = 'email_address_verify'; // emailアドレス確認用
    const PRIVACY_POLICY_FLG   = 'privacy_policy_flg';   // プライバシーポリシーフラグ
    const RINGBELL_INFO_FLG    = 'ringbell_info_flg';    // リンベルインフォフラグ
    const BIKO                 = 'biko';                 // 備考

    /**
     * 初期化用データ
     * @var array
     */
    protected $_initData = array(
        self::__IS_VALID           => false,
        self::USE_FLG              => '0',
        self::SEI_KANJI            => '',
        self::MEI_KANJI            => '',
        self::SEI_KANA             => '',
        self::MEI_KANA             => '',
        self::ZIP1                 => '',
        self::ZIP2                 => '',
        self::ADD1                 => '',
        self::ADD2                 => '',
        self::ADD3                 => '',
        self::TEL_SHIGAI           => '',
        self::TEL_SHINAI           => '',
        self::TEL_KYOKUNAI         => '',
        self::EMAIL_ADDRESS        => '',
        self::EMAIL_ADDRESS_VERIFY => '',
        self::PRIVACY_POLICY_FLG   => '0',
        self::RINGBELL_INFO_FLG    => '1',
        self::BIKO                 => '',
    );
}

/**
 * 送り主クラス
 *
 * @author  Yuki Tanaka
 * @version Release:<1.0>
 */
class Okurinushi extends AbsOrderElement
{
    /**
     * セッションの名前
     * @var string
     */
    protected $_sessionName = 'okurinushi_info';

    //各リクエスト、セッションキー
    const OKURINUSHI_FLG          = 'okurinushi_flg';          // 送り主フラグ
    const OKURINUSHI_SEI_KANJI    = 'okurinushi_sei_kanji';    // 送り主姓漢字
    const OKURINUSHI_MEI_KANJI    = 'okurinushi_mei_kanji';    // 送り主名漢字
    const OKURINUSHI_SEI_KANA     = 'okurinushi_sei_kana';     // 送り主姓カナ
    const OKURINUSHI_MEI_KANA     = 'okurinushi_mei_kana';     // 送り主名カナ
    const OKURINUSHI_ZIP1         = 'okurinushi_zip1';         // 送り主郵便番号１
    const OKURINUSHI_ZIP2         = 'okurinushi_zip2';         // 送り主郵便番号２
    const OKURINUSHI_ADD1         = 'okurinushi_add1';         // 送り主住所１
    const OKURINUSHI_ADD2         = 'okurinushi_add2';         // 送り主住所２
    const OKURINUSHI_ADD3         = 'okurinushi_add3';         // 送り主住所３
    const OKURINUSHI_TEL_SHIGAI   = 'okurinushi_tel_shigai';   // 送り主電話市外
    const OKURINUSHI_TEL_SHINAI   = 'okurinushi_tel_shinai';   // 送り主電話市内
    const OKURINUSHI_TEL_KYOKUNAI = 'okurinushi_tel_kyokunai'; // 送り主電話局内

    /**
     * 初期化用データ
     * @var array
     */
    protected $_initData = array(
        self::__IS_VALID              => false,
        self::OKURINUSHI_FLG          => '0',
        self::OKURINUSHI_SEI_KANJI    => '',
        self::OKURINUSHI_MEI_KANJI    => '',
        self::OKURINUSHI_SEI_KANA     => '',
        self::OKURINUSHI_MEI_KANA     => '',
        self::OKURINUSHI_ZIP1         => '',
        self::OKURINUSHI_ZIP2         => '',
        self::OKURINUSHI_ADD1         => '',
        self::OKURINUSHI_ADD2         => '',
        self::OKURINUSHI_ADD3         => '',
        self::OKURINUSHI_TEL_SHIGAI   => '',
        self::OKURINUSHI_TEL_SHINAI   => '',
        self::OKURINUSHI_TEL_KYOKUNAI => ''
    );

    /**
     * オブジェクトにデータをセット
     *
     * @param array $params
     *
     * @return void
     */
    public function setObjectData($params)
    {
        if (isset($params[self::OKURINUSHI_FLG]) && ($params[self::OKURINUSHI_FLG] == '0')) {
            $params = $this->_initData;
        }
        parent::setObjectData($params);
    }
}
