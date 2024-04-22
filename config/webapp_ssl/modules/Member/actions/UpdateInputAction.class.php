<?php
require_once(__DIR__ . '/RegisterInputAction.class.php');
require_once(dirname(__DIR__) . '/querys/MemberQuerySel.class.php');

class UpdateInputAction extends RegisterInputAction
{
    /**
     * @var bool 認証が必要な画面かどうか
     */
    protected $_requireAuth = true;

    const MODE = 'Update';

    protected function getInitPostParams($request, $user)
    {
        $db = new MemberQuerySel();
        $db->setSelectSql('get-userinfo');
        $dbParams = [
            'GCNO' => $this->gcInfo->maincardNo,
          //'PIN' => $this->gcInfo->maincardPin,
            'KENGROUP' => $this->gcInfo->kenshuGroup,
        ];
        if ($this->_user->getAttribute('is_testrun')) {
            unset($dbParams['KENGROUP']);
        }
        $db->setRecordsetArray($dbParams);
        $rs = $db->Execute();
        if (!$rs) {
            throw new Exception(E_DB_EXECUTE_ERR);
        }
        if (!$rs->RecordCount()) {
            throw new Exception('ギフトカードに紐づくユーザー情報が見つかりません。');
        }
        // 初期値設定
        $postParams = [];
        foreach (array_keys($this->_postParams) as $k) {
            $postParams[$k] = '';
        }
        $postParams[self::I_SEI_KANJI1] = $rs->Fields('F01SEI');
        $postParams[self::I_MEI_KANJI1] = $rs->Fields('F01MEI');
        $postParams[self::I_SEI_KANA1]  = $rs->Fields('F01SEIKN');
        $postParams[self::I_MEI_KANA1]  = $rs->Fields('F01MEIKN');
        $postParams[self::I_RELATION1]  = $rs->Fields('M11REL01');
        $postParams[self::I_SEI_KANJI2]  = $rs->Fields('M11SEI02');
        $postParams[self::I_MEI_KANJI2]  = $rs->Fields('M11MEI02');
        $postParams[self::I_SEI_KANA2]   = $rs->Fields('M11SEIKN02');
        $postParams[self::I_MEI_KANA2]   = $rs->Fields('M11MEIKN02');
        $postParams[self::I_RELATION2]   = $rs->Fields('M11REL02');
        // お子様の情報は編集不可＋非表示
        //$postParams[self::I_SEI_KANJI3]  = $rs->Fields('M11SEI03');
        //$postParams[self::I_MEI_KANJI3]  = $rs->Fields('M11MEI03');
        //$birthday = $rs->Fields('M11BIRTH03');
        //if ($birthday) {
        //    $birthTemp = explode('-', date('Y-m-d', strtotime($birthday)));
        //    $postParams[self::I_BIRTHDAY3_Y] = array_shift($birthTemp);
        //    $postParams[self::I_BIRTHDAY3_M] = array_shift($birthTemp);
        //    $postParams[self::I_BIRTHDAY3_D] = array_shift($birthTemp);
        //}
        $postParams[self::I_ZIPCODE_1] = $rs->Fields('F01ZIP1');
        $postParams[self::I_ZIPCODE_2] = $rs->Fields('F01ZIP2');
        $postParams[self::I_ADDRESS_1] = $rs->Fields('F01ADD1');
        $postParams[self::I_ADDRESS_2] = $rs->Fields('F01ADD2');
        $postParams[self::I_ADDRESS_3] = $rs->Fields('F01ADD3');
        $postParams[self::I_TEL1_1]    = $rs->Fields('F01TEL11');
        $postParams[self::I_TEL1_2]    = $rs->Fields('F01TEL12');
        $postParams[self::I_TEL1_3]    = $rs->Fields('F01TEL13');
        $postParams[self::I_TEL2_1]    = $rs->Fields('F01TEL21');
        $postParams[self::I_TEL2_2]    = $rs->Fields('F01TEL22');
        $postParams[self::I_TEL2_3]    = $rs->Fields('F01TEL23');
        $postParams[self::S_EMAIL]     = $rs->Fields('M01EMAILPC');
        $postParams[self::I_PASSWORD1] = '';
        $postParams[self::I_PASSWORD2] = '';
        return $postParams;
    }
}
