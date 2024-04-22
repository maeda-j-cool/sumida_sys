<?php
/**
 * ProjectName : スマートギフトサイト
 * Subsystem   : ギフトカードを利用した商品交換モールサイト
 */
require_once(WT_ROOT_DIR . 'webapp_ssl/modules/Order/common/AbsOrderElement.php');

/**
 * お届け先クラス
 *
 * @author  Yuki Tanaka
 * @version Release:<1.0>
 */
class Otodokesaki extends AbsOrderElement
{
    // 各リクエスト、セッションキー
    const OTODOKESAKI_FLG          = 'otodokesaki_flg';          // お届け先フラグ
    const OTODOKESAKI_SEI_KANJI    = 'otodokesaki_sei_kanji';    // お届け先姓漢字
    const OTODOKESAKI_MEI_KANJI    = 'otodokesaki_mei_kanji';    // お届け先名漢字
    const OTODOKESAKI_SEI_KANA     = 'otodokesaki_sei_kana';     // お届け先姓カナ
    const OTODOKESAKI_MEI_KANA     = 'otodokesaki_mei_kana';     // お届け先名カナ
    const OTODOKESAKI_ZIP1         = 'otodokesaki_zip1';         // お届け先郵便番号１
    const OTODOKESAKI_ZIP2         = 'otodokesaki_zip2';         // お届け先郵便番号２
    const OTODOKESAKI_ADD1         = 'otodokesaki_add1';         // お届け先住所１
    const OTODOKESAKI_ADD2         = 'otodokesaki_add2';         // お届け先住所２
    const OTODOKESAKI_ADD3         = 'otodokesaki_add3';         // お届け先住所３
    const OTODOKESAKI_TEL_SHIGAI   = 'otodokesaki_tel_shigai';   // お届け先電話市外
    const OTODOKESAKI_TEL_SHINAI   = 'otodokesaki_tel_shinai';   // お届け先電話市内
    const OTODOKESAKI_TEL_KYOKUNAI = 'otodokesaki_tel_kyokunai'; // お届け先電話局内
    const SHOHIN_LIST              = 'otodoke_shohin_list';      // お届け商品情報

    /**
     * 初期化用データ
     * @var array
     */
    protected $_initData = array(
        self::__IS_VALID               => false,
        self::OTODOKESAKI_FLG          => '0',
        self::OTODOKESAKI_SEI_KANJI    => '',
        self::OTODOKESAKI_MEI_KANJI    => '',
        self::OTODOKESAKI_SEI_KANA     => '',
        self::OTODOKESAKI_MEI_KANA     => '',
        self::OTODOKESAKI_ZIP1         => '',
        self::OTODOKESAKI_ZIP2         => '',
        self::OTODOKESAKI_ADD1         => '',
        self::OTODOKESAKI_ADD2         => '',
        self::OTODOKESAKI_ADD3         => '',
        self::OTODOKESAKI_TEL_SHIGAI   => '',
        self::OTODOKESAKI_TEL_SHINAI   => '',
        self::OTODOKESAKI_TEL_KYOKUNAI => '',
        self::SHOHIN_LIST              => array(),
    );

    /**
     * セッションの名前を取得
     *
     * @throws Exception
     */
    public function getSessionName()
    {
        // オブジェクト単体でのセッション保存を禁止
        throw new Exception('');
    }

    /**
     * お届け商品の追加
     *
     * @param array|OtodokeShohin $otodokeShohin
     *
     * @return int 追加した商品のインデックス
     */
    public function addShohin($otodokeShohin, $isValid = true)
    {
        if (is_array($otodokeShohin)) {
            $otodokeShohin = new OtodokeShohin($otodokeShohin);
        }
        if (!($otodokeShohin instanceof OtodokeShohin)) {
            throw new Exception('');
        }
        $otodokeShohin->setValid($isValid);
        $shohinIndex = count($this->_data[self::SHOHIN_LIST]);
        $this->_data[self::SHOHIN_LIST][$shohinIndex] = $otodokeShohin;
        return $shohinIndex;
    }

    /**
     * お届け商品の削除
     *
     * @param int $shohinIndex
     *
     * @return array 削除した商品情報配列
     */
    public function delShohin($shohinIndex = null)
    {
        $deleteShohinList = array();
        if (null === $shohinIndex) {
            foreach ($this->_data[self::SHOHIN_LIST] as $shohinIndex => $otodokeShohin) {
                $deleteShohinList[$shohinIndex] = $otodokeShohin->getObjectData();
            }
            $this->_data[self::SHOHIN_LIST] = array();
        } elseif (isset($this->_data[self::SHOHIN_LIST][$shohinIndex])) {
            $deleteShohinList[$shohinIndex] = $this->_data[self::SHOHIN_LIST][$shohinIndex]->getObjectData();
            unset($this->_data[self::SHOHIN_LIST][$shohinIndex]);
        }
        return $deleteShohinList;
    }

    /**
     * お届け商品オブジェクトの取得
     *
     * @return $this
     */
    public function getOtodokeShohinObjList()
    {
        return $this->_data[self::SHOHIN_LIST];
    }

    /**
     * オブジェクトのデータを取得
     *
     * @return array
     */
    public function getObjectData($forSave = false)
    {
        $otodokeShohinList = array();
        foreach ($this->_data[self::SHOHIN_LIST] as $shohinIndex => $otodokeShohin) {
            $otodokeShohinList[$shohinIndex] = $otodokeShohin->getObjectData($forSave);
        }
        $data = $this->_data;
        $data[self::SHOHIN_LIST] = $otodokeShohinList;
        if (!$forSave) {
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
        if (isset($params[self::OTODOKESAKI_FLG]) && ($params[self::OTODOKESAKI_FLG] == '0')) {
            $params[self::OTODOKESAKI_SEI_KANJI]    = '';
            $params[self::OTODOKESAKI_MEI_KANJI]    = '';
            $params[self::OTODOKESAKI_SEI_KANA]     = '';
            $params[self::OTODOKESAKI_MEI_KANA]     = '';
            $params[self::OTODOKESAKI_ZIP1]         = '';
            $params[self::OTODOKESAKI_ZIP2]         = '';
            $params[self::OTODOKESAKI_ADD1]         = '';
            $params[self::OTODOKESAKI_ADD2]         = '';
            $params[self::OTODOKESAKI_ADD3]         = '';
            $params[self::OTODOKESAKI_TEL_SHIGAI]   = '';
            $params[self::OTODOKESAKI_TEL_SHINAI]   = '';
            $params[self::OTODOKESAKI_TEL_KYOKUNAI] = '';
        }
        if (isset($params[self::SHOHIN_LIST]) && !empty($params[self::SHOHIN_LIST])) {
            $otodokeShohinList = array();
            foreach ($params[self::SHOHIN_LIST] as $shohinIndex => $otodokeShohinParams) {
                $otodokeShohinList[$shohinIndex] = new OtodokeShohin($otodokeShohinParams);
            }
            $params[self::SHOHIN_LIST] = $otodokeShohinList;
        }
        parent::setObjectData($params);
    }
}

/**
 * お届け先商品クラス
 *
 * @author  Saito
 * @version Release:<1.0>
 */
class OtodokeShohin extends AbsOrderElement
{
    const SHOHIN_NO       = 'shohin_no';
    const KONYU_SURYO     = 'konyu_suryo';
    const KAKAKU_ZEINUKI  = 'kakaku_zeinuki';
    const KAKAKU_TAX      = 'kakaku_tax';
    const HAISO_KIBO_DATE = 'haiso_kibo_date';

    /**
     * 初期化用データ
     * @var array
     */
    protected $_initData = array(
        self::__IS_VALID      => false,
        self::SHOHIN_NO       => null,
        self::KONYU_SURYO     => null,
        self::KAKAKU_ZEINUKI  => null,
        self::KAKAKU_TAX      => null,
        self::HAISO_KIBO_DATE => null,
    );
}
