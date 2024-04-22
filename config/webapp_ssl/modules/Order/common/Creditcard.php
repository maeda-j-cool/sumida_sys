<?php
/**
 * ProjectName : スマートギフトサイト
 * Subsystem   : ギフトカードを利用した商品交換モールサイト
 */
require_once(WT_ROOT_DIR . 'webapp_ssl/modules/Order/common/AbsOrderElement.php');

/**
 * クレジットカードクラス
 *
 * @author  Yuki Tanaka
 * @version Release:<1.0>
 */
class Creditcard extends AbsOrderElement
{
    /**
     * @var string セッション名
     */
    protected $_sessionName = 'creditcard_info';

    // 各リクエスト、セッションキー
    const CREDITCARD_PRICE   = 'creditcard_price';   // クレジットカード金額
    const CREDITCARD_TAX     = 'creditcard_tax';     // クレジットカード税金
    const CREDITCARD_TOTAL   = 'creditcard_total';   // クレジットカード合計
    const CREDITCARD_USE_FLG = 'creditcard_use_flg'; // クレジットカード使用フラグ
    const CARD_TOKEN         = 'card_token';         // クレジットカードトークン(非通過対応用)
    const ORDER_ID           = 'credit_order_id';

    /**
     * @var array 初期化用データ
     */
    protected $_initData = array(
        self::__IS_VALID         => false,
        self::CREDITCARD_PRICE   => 0,
        self::CREDITCARD_TAX     => 0,
        self::CREDITCARD_TOTAL   => 0,
        self::CREDITCARD_USE_FLG => '1', // クレジットカードを使う場合に'2' ※詳細不明だがそのままF06JUCHKBNに保存してるので変更しない
        self::CARD_TOKEN         => '',
        self::ORDER_ID           => '',
    );

    /**
     * 現在指定されているギフトカード情報の設定/取得
     * ※セッター/ゲッターはマジックメソッドで処理する
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        $getters = array(
            'getCreditcardTotal' => self::CREDITCARD_TOTAL,
            'getCreditcardPrice' => self::CREDITCARD_PRICE,
            'getCreditcardTax'   => self::CREDITCARD_TAX,
            'getCreditcardToken' => self::CARD_TOKEN,
            'getCreditUseFlg'    => self::CREDITCARD_USE_FLG,
            'getCardToken'       => self::CARD_TOKEN,
            'getOrderId'         => self::ORDER_ID,
        );
        if (isset($getters[$method])) {
            if (!empty($arguments)) {
                throw new Exception('Bad arguments.' . "\n" . print_r($arguments, true));
            }
            return $this->_data[$getters[$method]];
        }
        $setters = array(
            'setCreditcardTotal' => self::CREDITCARD_TOTAL,
            'setCreditcardPrice' => self::CREDITCARD_PRICE,
            'setCreditcardTax'   => self::CREDITCARD_TAX,
            'setCreditcardToken' => self::CARD_TOKEN,
            'setCreditUseFlg'    => self::CREDITCARD_USE_FLG,
            'setCardToken'       => self::CARD_TOKEN,
            'setOrderId'         => self::ORDER_ID,
        );
        if (isset($setters[$method])) {
            if (count($arguments) != 1) {
                throw new Exception('Bad arguments.' . "\n" . print_r($arguments, true));
            }
            $this->_data[$setters[$method]] = array_shift($arguments);
            return $this;
        }
        throw new Exception(sprintf('Call to undefined method %s::%s()', __CLASS__, $method));
    }
}
