<?php
/**
 * ProjectName : スマートギフトサイト
 * Subsystem   : ギフトカードを利用した商品交換モールサイト
 */
require_once(WT_ROOT_DIR . 'webapp_ssl/modules/Order/common/AbsOrderElement.php');

/**
 * 商品情報クラス
 *
 * @author  Yuki Tanaka
 * @version Release:<1.0>
 */
class ShohinInformation extends AbsOrderElement
{
    const SHOHIN_NO                  = 'shohin_no';                  // 商品番号
    const SHOHIN_CODE                = 'shohin_code';                // 商品コード
    const BRAND_NAME                 = 'brand_name';                 // ブランド名
    const SHOHIN_NAME                = 'shohin_name';                // 商品名
    const SHOHIN_NAME_KANA           = 'shohin_name_kana';           // 商品名カナ
    const HAISO_KEITAI               = 'haiso_keitai';               // 配送形態
    const HAISO_MOTO_SHIKIBETSU_CODE = 'haiso_moto_shikibetsu_code'; // 配送元識別コード
    const KAKAKU_ZEINUKI             = 'kakaku_zeinuki';             // 商品価格税抜き
    const KISETSU_SHOHIN_FLG         = 'kisetsu_shohin_flg';         // 季節商品フラグ
    const HYOJUN_NOKI                = 'hyojun_nouki';               // 標準納期
    const GENTEI_SURYO               = 'gentei_suryo';               // 限定数量
    const NOKORI_SURYO               = 'nokori_suryo';               // 残り数量
    const KANOU_SURYO                = 'kanou_suryo';                // 購入可能数量
    const KISETSU_DATE               = 'kisetsu_haisoudate';         // 季節配送期間開始日
    const HOSO_FLG                   = 'houso_flg';                  // 包装フラグ
    const NOSHI_FLG                  = 'noshi_flg';                  // のしフラグ
    const GREETING_CARD_FLG          = 'greeting_card_flg';          // 挨拶状フラグ
    const HAISOSITEI_NOLIMIT_FLG     = 'haisositei_nolimit_flg';     // 配送指定制限除外フラグ
    const TOKUSHU_FLG                = 'tokushu_flg';                // 特殊商品フラグ
    const KAIGAI_FLG                 = 'kaigai_flg';                 // 海外届け限定商品フラグ
    const KIKAN_GENTEI_HAISO_KEITAI  = 'kikan_gentei_haiso_keitai';  // 期間限定配送携帯
    const KIKAN_GENTEI_FLG           = 'kikan_gentei_flg';           // 期間限定フラグ
    const DISP_HAISO_KEITAI          = 'disp_haiso_keitai';          // 表示配送形態
    const CANNOT_DELIVERY_DATE       = 'cannot_delivery_date';       // 配送指定不可日
    const CANNOT_DELIVERY_SENTENCE   = 'cannot_delivery_sentence';   // 配送指定不可文言
    const HANBAI_SDATE               = 'hanbai_sdate';               // 販売期間開始日
    const HANBAI_EDATE               = 'hanbai_edate';               // 販売期間終了日
    const TAXFREE_FLG                = 'taxfree_flg';                // 消費税無料フラグ
    const OTODOKE_KANO_DATE          = 'otodoke_kano_date';          // お届け可能日
    const OTODOKE_KIBO_DATE          = 'otodoke_kibo_date';          // お届け希望日 ※自宅用でのみ使用
    const KONYU_SURYO                = 'konyu_suryo';                // 購入数量     ※自宅用でのみ使用
    const CGWEBLIB_STOCKOUT_FLG      = 'cgweblib_stockout_flg';      // CGWEBLIB.MISHOHNP の欠品状態
    const HYOJI_KEY1                 = 'hyoji_key1';
    const HYOJI_KEY2                 = 'hyoji_key2';
    const DGC_INFO                   = 'dgc_info';

    /**
     * 初期化用データ
     * @var array
     */
    protected $_initData = array(
        self::SHOHIN_NO                  => '',
        self::SHOHIN_CODE                => '',
        self::BRAND_NAME                 => '',
        self::SHOHIN_NAME                => '',
        self::SHOHIN_NAME_KANA           => '',
        self::HAISO_KEITAI               => '',
        self::HAISO_MOTO_SHIKIBETSU_CODE => '',
        self::KAKAKU_ZEINUKI             => '',
        self::KISETSU_SHOHIN_FLG         => '',
        self::HYOJUN_NOKI                => '',
        self::GENTEI_SURYO               => '',
        self::NOKORI_SURYO               => '',
        self::KANOU_SURYO                => '',
        self::KISETSU_DATE               => array(),
        self::HOSO_FLG                   => '',
        self::NOSHI_FLG                  => '',
        self::GREETING_CARD_FLG          => '',
        self::HAISOSITEI_NOLIMIT_FLG     => '',
        self::TOKUSHU_FLG                => '',
        self::KAIGAI_FLG                 => '',
        self::KIKAN_GENTEI_HAISO_KEITAI  => '',
        self::KIKAN_GENTEI_FLG           => '',
        self::DISP_HAISO_KEITAI          => '',
        self::CANNOT_DELIVERY_DATE       => array(),
        self::CANNOT_DELIVERY_SENTENCE   => '',
        self::HANBAI_SDATE               => '',
        self::HANBAI_EDATE               => '',
        self::TAXFREE_FLG                => '',
        self::OTODOKE_KANO_DATE          => null,
        self::OTODOKE_KIBO_DATE          => null, // ※自宅用でのみ使用
        self::KONYU_SURYO                => 1,    // ※自宅用でのみ使用
        self::CGWEBLIB_STOCKOUT_FLG      => true,
        self::HYOJI_KEY1                 => '',
        self::HYOJI_KEY2                 => '',
        self::DGC_INFO                   => [],
    );

    /**
     * セッションの名前を取得
     *
     * @throws Exception
     */
    public function getSessionName()
    {
        throw new Exception('');
    }

    /**
     * 共通関数から取得した商品データにセット
     *
     * @param array $shohinData 商品データ
     *
     * @return void
     */
    public function setShohinDataFromDB($shohinDataFromDb)
    {
        $shohinData = $this->_data;
        $ifsetor = function(&$v, $default) {
            return isset($v) ? $v : $default;
        };
        foreach ($this->_initData as $k => $v) {
            $v = $ifsetor($shohinData[$k], $v);
            $shohinData[$k] = $ifsetor($shohinDataFromDb[$k], $v);
        }
        if ($shohinData[self::KISETSU_SHOHIN_FLG] === '1') {
            if (empty($shohinData[self::KISETSU_DATE])) {
                // ※元メソッド ::setKisetsuData()
                $shohinData[self::KISETSU_SHOHIN_FLG] = '0';
                $shohinData[self::KISETSU_DATE] = array();
            } else {
                // 季節商品期間の整形を行う
                // ※元メソッド ::computeSeasonDate()
                $seasonDate = array();
                foreach ($shohinData[self::KISETSU_DATE] as $temp) {
                    $tStt = strtotime($temp['haisosdate']);
                    $tEnd = strtotime($temp['haisoedate']);
                    if (is_int($tStt) && is_int($tEnd)) {
                        $seasonDate[] = array(
                            'haisosdate' => date('Y-m-d', $tStt),
                            'haisoedate' => date('Y-m-d', $tEnd),
                        );
                    }
                }
                $shohinData[self::KISETSU_DATE] = $seasonDate;
            }
        }
        $isNew = empty($this->_data);
        $this->_data = $shohinData;
        if ($shohinData[self::HAISOSITEI_NOLIMIT_FLG] === '0') {
            $this->setCannotDeliveryData();
        }
        if (empty($this->_data)) {
            // 初回設定時のみ？？？
            if ($shohinData[self::KIKAN_GENTEI_FLG] === '1') {
                $this->setLimitedDeliveryWayData();
            }
        }
        // 最短お届け日の設定
        $this->setShortestDeliveryDate();
    }

    /**
     * 配送指定不可情報から、配送指定不可情報を取得し、$shohinDataに情報を格納する
     * ※旧メソッド ::getCannotDeliveryData($shohinNo) をコピー修正
     *
     * @return void
     */
    private function setCannotDeliveryData()
    {
        $shohinData = $this->_data;
        if ($shohinData[self::HAISOSITEI_NOLIMIT_FLG] === '1') {
            $shohinData[self::CANNOT_DELIVERY_DATE]     = array();
            $shohinData[self::CANNOT_DELIVERY_SENTENCE] = '';
        } else {
            $dbc = new OrderCommonQuerySel();
            $dbc->setSelectSql('4');
            $dbc->setRecordsetArray(array('shohinNo' => $shohinData[self::SHOHIN_NO]));
            $rs = $dbc->Execute();
            if ($rs->RecordCount() === 0) {
                $shohinData[self::HAISOSITEI_NOLIMIT_FLG] = '1';
            } else {
                $deliverySentence = $deliverySentenceShop = '';
                $cannotDeliveryDate = $cannotDeliveryDateShop = array();
                while (!$rs->EOF) {
                    // 商品ごとの配送不可適用日レコードが存在する場合、全体の配送不可適用日レコードは無視する
                    // SQLで、商品設定のレコードが先に来るようにソートされている
                    // 全体の配送不可適用日レコードの商品番号は999999999で登録されている
                    // 商品番号が999999999でないレコードがあった場合、商品番号が999999999のレコードは無視する
                    // SHOHIN_NO === 999999999
                    $fukaDate = $rs->Fields('F71FUKADATE');
                    if ($fukaDate) {
                        $t = strtotime($fukaDate);
                        if ($t) {
                            $fukaDate = date('Y-m-d', $t);
                            if ($rs->Fields('F70SHOHNNO') === SHOHIN_NO) {
                                $deliverySentenceShop = $rs->Fields('F70OSHIRASE');
                                $cannotDeliveryDateShop[] = $fukaDate;
                            } else {
                                $deliverySentence = $rs->Fields('F70OSHIRASE');
                                $cannotDeliveryDate[] = $fukaDate;
                            }
                        }
                    }
                    $rs->MoveNext();
                }
                // 商品の設定が存在する場合は、そちらを優先して値をセットする
                if (!empty($cannotDeliveryDate)) {
                    $shohinData[self::CANNOT_DELIVERY_DATE]     = $cannotDeliveryDate;
                    $shohinData[self::CANNOT_DELIVERY_SENTENCE] = $deliverySentence;
                } else if (!empty($cannotDeliveryDateShop)) {
                    $shohinData[self::CANNOT_DELIVERY_DATE]     = $cannotDeliveryDateShop;
                    $shohinData[self::CANNOT_DELIVERY_SENTENCE] = $deliverySentenceShop;
                }
            }
        }
        $this->_data = $shohinData;
    }

    /**
     * 期間限定配送情報から、配送コード、期間を取得し、$shohinDataに情報を格納する
     * ※旧メソッド ::getLimitedDeliveryWayData($shohinNo) をコピー修正
     *
     * @return void
     */
    private function setLimitedDeliveryWayData()
    {
        $shohinData = $this->_data;
        $dbc = new OrderCommonQuerySel();
        $dbc->setSelectSql('5');
        $dbc->setRecordsetArray(array(
            'shohinNo' => $shohinData[self::SHOHIN_NO],
            'today'    => date('Y-m-d-H.i.s'),
        ));
        $rs  = $dbc->Execute();
        if ($rs->RecordCount() === 0) {
            $shohinData[self::KIKAN_GENTEI_FLG] = '0';
        } else {
            while (!$rs->EOF) {
                $shohinData[self::KIKAN_GENTEI_HAISO_KEITAI] = $rs->Fields('F72HAISOKBN');
                $shohinData[self::DISP_HAISO_KEITAI]         = $rs->Fields('F72HAISOKBN');
                $rs->MoveNext();
            }
        }
        $this->_data = $shohinData;
    }

    /**
     * 最短お届け日を算出・設定する
     * ※2017/07/27 DesiredDeliveryDateCalc.phpより移植(DesiredDeliveryDateCalcは廃止)
     * ・商品の標準納期が設定されていない場合、設定不可
     * ・設定されている場合は、「本日 + 標準納期 」で計算
     * ・季節商品である場合は、季節商品期間でしかお届けできない
     * ・商品の配送指定不可除外フラグがOFFの場合、下記配送指定の制限を受ける
     * ・商品に対する配送指定適用日と、全体に対する配送指定適用日が重複する場合は
     *   商品に対する配送指定適用日を優先する。
     *
     *               適用日                   指定不可日
     *   商品 |-----**********-----------------@@@@@@@@-----------------|
     *   全体 |-----------**********-----------------@@@@@@@@-----------|
     *                    <--> ←この範囲に注文する場合は、商品の設定を優先
     *
     * @param ShohinInformation &$shohinObj
     *
     * @return void
     */
    private function setShortestDeliveryDate()
    {
        $shohinData = $this->_data;
        if (!$shohinData[self::HYOJUN_NOKI] || !ctype_digit(strval($shohinData[self::HYOJUN_NOKI]))) {
            // 標準納期がセットされていない場合、配送希望日は指定不可
            $shohinData[self::OTODOKE_KANO_DATE] = null;
        } else {
            // 最短お届け日
            $tShortestDeliveryDate = strtotime(sprintf('%s +%d day', date('Y-m-d'), $shohinData[self::HYOJUN_NOKI]));
            if ($shohinData[self::HAISOSITEI_NOLIMIT_FLG] !== '1') {
                $tTemp = $tShortestDeliveryDate;
                $tShortestDeliveryDate = null;
                do {
                    $shortestDeliveryDate = date('Y-m-d', $tTemp);
                    if (!in_array($shortestDeliveryDate, $shohinData[self::CANNOT_DELIVERY_DATE])) {
                        $tShortestDeliveryDate = $tTemp;
                        break;
                    }
                } while ($tTemp += 86400);
            }
            // 季節商品で、季節商品の期間に合致しない場合は、配送希望不可
            if (($shohinData[self::KISETSU_SHOHIN_FLG] === '1') && (!empty($shohinData[self::KISETSU_DATE]))) {
                $tTemp = $tShortestDeliveryDate;
                $tShortestDeliveryDate = null;
                foreach ($shohinData[self::KISETSU_DATE] as $kisetsuTemp) {
                    $tStt = strtotime($kisetsuTemp['haisosdate']);
                    $tEnd = strtotime($kisetsuTemp['haisoedate']);
                    if ($tTemp < $tStt) {
                        // 季節商品開始日が標準納期算出の日付よりも後の場合、季節商品開始日を最短お届け日に設定する
                        $tTemp = $tStt;
                    }
                    if (($tStt <= $tTemp) && ($tTemp <= $tEnd)) {
                        if ($shohinData[self::HAISOSITEI_NOLIMIT_FLG] === '1') {
                            // 配送指定不可除外フラグ
                            $tShortestDeliveryDate = $tTemp;
                            break;
                        }
                        while ($tTemp <= $tEnd) {
                            $shortestDeliveryDate = date('Y-m-d', $tTemp);
                            if (!in_array($shortestDeliveryDate, $shohinData[self::CANNOT_DELIVERY_DATE])) {
                                $tShortestDeliveryDate = $tTemp;
                                break 2;
                            }
                            $tTemp += 86400;
                        }
                    }
                }
            }
            if ($tShortestDeliveryDate && ($tShortestDeliveryDate < (time() + UNIXTIMESTAMP_YEAR))) {
                // 制約：一年以内
                $shohinData[self::OTODOKE_KANO_DATE] = date('Y-m-d', $tShortestDeliveryDate);
                $shohinData[self::OTODOKE_KIBO_DATE] = $shohinData[self::OTODOKE_KANO_DATE];
                //$shohinData[self::KIBO_HAISO_DISP_FLG] = '1';
                //if (($shohinData[self::HAISOSITEI_NOLIMIT_FLG] !== '1')
                //    $shohinData[self::CANNOT_DELIVERY_SENTENCE_DISP_FLG] = '1';
                //}
            }
        }
        $this->_data = $shohinData;
    }

    /**
     * お届け希望日の配送可能チェック
     *
     * @param string $ymd YYYY-MM-DD
     * @param null|string $limitYmd YYYY-MM-DD
     *
     * @return bool 配送可能な日付の場合にtrueを返す
     */
    public function isValidDeliveryDate($ymd, $limitYmd = null)
    {
        $shohinData = $this->_data;
        if (!$shohinData[self::OTODOKE_KANO_DATE]) {
            throw new Exception('配送希望日指定不可');
        }
        $t = strtotime($ymd);
        if (!$t) {
            throw new Exception('不正な日付: ' . $ymd);
        }
        if ($t < strtotime($shohinData[self::OTODOKE_KANO_DATE])) {
            // お届け可能日より前の日付が指定されている
            return false;
        }
        if ($limitYmd) {
            if ($t > strtotime($limitYmd)) {
                return false;
            }
        } else {
            if (intval(date('Ymd', $t)) > intval(date('Ymd', strtotime('+90 days')))) {
                // 希望日が1年後よりも先
                return false;
            }
        }
        $ymd = date('Y-m-d', $t);
        if ($shohinData[self::HAISOSITEI_NOLIMIT_FLG] !== '1') {
            if (in_array($ymd, $shohinData[self::CANNOT_DELIVERY_DATE])) {
                // 配送指定不可日
                return false;
            }
        }
        if (($shohinData[self::KISETSU_SHOHIN_FLG] === '1') && (!empty($shohinData[self::KISETSU_DATE]))) {
            foreach ($shohinData[self::KISETSU_DATE] as $kisetsuTemp) {
                $tStt = strtotime($kisetsuTemp['haisosdate']);
                $tEnd = strtotime($kisetsuTemp['haisoedate']);
                if (($tStt <= $t) && ($t <= $tEnd)) {
                    return true;
                }
            }
            return false;
        }
        return true;
    }
}
