<?php
/**
 * ProjectName : スマートギフトサイト
 * Subsystem   : ギフトカードを利用した商品交換モールサイト
 */

/**
 * 注文構成要素基底クラス
 *
 * @author  Yuki Tanaka, Saito
 * @version Release:<1.0>
 */
abstract class AbsOrderElement
{
    /**
     * 設定キー: データが検証済みかどうか
     */
    const __IS_VALID = '__is_valid';

    /**
     * @var string セッションの名前
     */
    protected $_sessionName = '';

    /**
     * @var array 初期化用データ
     */
    protected $_initData = array();

    /**
     * @var array データ保持用配列
     */
    protected $_data = array();

    /**
     * @var bool
     */
    protected $_ignoreKeyCheck = false;

    /**
     * コンストラクタ
     *
     * @param array $params
     */
    public function __construct($params = null)
    {
        $this->_data = $this->_initData;
        $this->setValid(false);
        $this->setObjectData($params);
    }

    /**
     * 検証済みフラグの設定
     *
     * @param bool $isValid 検証済みの場合にtrue
     *
     * @return void
     */
    public function setValid($isValid = true)
    {
        if (isset($this->_data[self::__IS_VALID])) {
            $this->_data[self::__IS_VALID] = $isValid;
        }
    }

    /**
     * 検証済みフラグの取得
     *
     * @return bool 検証済みの場合にtrue
     */
    public function isValid()
    {
        if (isset($this->_data[self::__IS_VALID])) {
            return $this->_data[self::__IS_VALID];
        }
        throw new Exception('');
    }

    /**
     * セッションの名前を取得
     *
     * @return string
     */
    public function getSessionName()
    {
        return $this->_sessionName;
    }

    /**
     * 初期化データを取得
     *
     * @return array
     */
    public function getInitData()
    {
        return $this->_initData;
    }

    /**
     * オブジェクトのデータを取得
     *
     * @return array
     */
    public function getObjectData($forSave = false)
    {
        $data = $this->_data;
        if (!$forSave && isset($data[self::__IS_VALID])) {
            unset($data[self::__IS_VALID]);
        }
        return $data;
    }

    /**
     * オブジェクトにデータをセット
     *
     * @param array $params
     *
     * @return void
     */
    public function setObjectData($params)
    {
        if (is_array($params) && !empty($params)) {
            foreach ($params as $k => $v) {
                $this->setObjectDataFromKey($k, $v);
            }
        }
    }

    /**
     * オブジェクトのデータを取得（キー指定）
     *
     * @param string $k
     *
     * @return mixed
     */
    public function getObjectDataFromKey($k)
    {
        if (!array_key_exists($k, $this->_data)) {
            throw new Exception("不明なキー: {$k}");
        }
        return $this->_data[$k];
    }

    /**
     * オブジェクトのデータをセット（キー指定）
     *
     * @param string $k オブジェクトにセットするキー
     * @param mixed  $v オブジェクトにセットする値
     *
     * @return void
     */
    public function setObjectDataFromKey($k, $v)
    {
        if (array_key_exists($k, $this->_data) || $this->_ignoreKeyCheck) {
            // 無関係なデータは無視
            $this->_data[$k] = $v;
        }
    }
}
