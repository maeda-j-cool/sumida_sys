<?php
require_once(WT_ROOT_DIR . 'webapp_ssl/modules/Order/querys/EnqueteQuerySel.class.php');

class Enquete
{
    public static function getInfo($kenshuGroup, $giftcardNo)
    {
        $db = new EnqueteQuerySel();
        $db->setSelectSql('get-past-enquete');
        $db->setRecordsetArray([
            'KENGROUP' => $kenshuGroup,
            'GCNO' => $giftcardNo,
        ]);
        $rs = $db->Execute();
        if (!$rs) {
            throw new Exception(E_DB_EXECUTE_ERR);
        }
        $enqueteList = [];
        if (!$rs->RecordCount()) {
            $db->setSelectSql('get-all-enquete');
            $db->setRecordsetArray([
                'MKENGROUP' => WT_DEFAULT_KENSHU_GROUP,
                'KENGROUP'  => $kenshuGroup,
            ]);
            $rs = $db->Execute();
            if (!$rs) {
                throw new Exception(E_DB_EXECUTE_ERR);
            }
            while (!$rs->EOF) {
                $enqueteId = (string)$rs->Fields('M35ENQID');
                if (!isset($enqueteList[$enqueteId])) {
                    $enqueteList[$enqueteId] = [
                        'M35ENQID' => $enqueteId,
                        'M36QUESTION' => $rs->Fields('M36QUESTION'),
                        'M36OPTIONTYPE' => $rs->Fields('M36OPTIONTYPE'),
                        'M36INPUTTYPE' => $rs->Fields('M36INPUTTYPE'),
                        'M36OPTIONS' => $rs->Fields('M36OPTIONS'),
                        'M36REQUIRED' => $rs->Fields('M36REQUIRED'),
                        'M36SEQ' => $rs->Fields('M36SEQ'),
                        'M37' => [],
                    ];
                }
                $optionId = $rs->Fields('M37ENQOPID');
                if ($optionId) {
                    $enqueteList[$enqueteId]['M37'][$optionId] = [
                        'M37ENQOPID' => $optionId,
                        'M37TEXT' => $rs->Fields('M37TEXT'),
                        'M37HASFREE' => $rs->Fields('M37HASFREE'),
                    ];
                }
                $rs->MoveNext();
            }
        }
        return $enqueteList;
    }
}
