<?php
class LoginQuerySel extends DBConnectSel
{
    /**
     * {@inheritdoc}
     */
    function Query($bindParams, $sqlNo)
    {
        $sql = '';
        $defaultKenshuGroup = WT_DEFAULT_KENSHU_GROUP;
        switch ($sqlNo) {
            case 'get-login-info':
                $joins = [];
                $wheres = [
                    "M00DELFLG = '0'",
                    "M00MKENGROUP = '{$defaultKenshuGroup}'",
                    // 確認：ステータス「≠仮登録（00）」の件数が1件以上 >> ログインカードの判定だけでOK
                    "M01KAINSTS <> '00'",
                ];
                if (isset($bindParams['LOGINID'])) {
                    $wheres[] = "M00LOGINID = '{$bindParams['LOGINID']}'";
                }
                if (isset($bindParams['PASSWORD'])) {
                    $wheres[] = "M01PASSWD = '{$bindParams['PASSWORD']}'";
                }
                if (isset($bindParams['KENGROUP'])) {
                    $wheres[] = "M00KENGROUP = '{$bindParams['KENGROUP']}'";
                    $wheres[] = "M01KENGROUP = '{$bindParams['KENGROUP']}'";
                }
                if (isset($bindParams['TEL1']) || isset($bindParams['TEL2'])) {
                    $orTemp = [];
                    if (isset($bindParams['TEL1'])) {
                        $orTemp[] = "F01TEL11||F01TEL12||F01TEL13 = '{$bindParams['TEL1']}'";
                        $orTemp[] = "F01TEL21||F01TEL22||F01TEL23 = '{$bindParams['TEL1']}'";
                    }
                    if (isset($bindParams['TEL2'])) {
                        $orTemp[] = "F01TEL11||F01TEL12||F01TEL13 = '{$bindParams['TEL2']}'";
                        $orTemp[] = "F01TEL21||F01TEL22||F01TEL23 = '{$bindParams['TEL2']}'";
                    }
                    $wheres[] = '(' . implode(' OR ', $orTemp) . ')';
                    $joins[] = ' '
                        . 'INNER JOIN '
                        .     DB_SHOP_LIBRARY . 'F01JUSHO'
                        .     ' ON '
                        .         'F01GCNO = M00USERID'
                        .     ' AND '
                        .         "F01DELFLG = '0'";
                }
                if (isset($bindParams['CSEI']) && isset($bindParams['CMEI'])) {
                    $wheres[] = "M11SEI03 = '{$bindParams['CSEI']}'";
                    $wheres[] = "M11MEI03 = '{$bindParams['CMEI']}'";
                    $joins[] = ' '
                        . 'INNER JOIN '
                        .     DB_SHOP_LIBRARY . 'M11WKAIIN'
                        .     ' ON '
                        .         'M11GCNO = M01GCNO'
                        .     ' AND '
                        .         "M11DELFLG = '0'";
                }
                $sql = 'SELECT '
                     .     '*'
                     . ' FROM '
                     .     DB_SHOP_LIBRARY . 'M00USER'
                     .     ' INNER JOIN '
                     .         DB_SHOP_LIBRARY . 'M01WKAIIN'
                     .         ' ON '
                     .             'M01GCNO = M00USERID'
                     .         ' AND '
                     .             "M01DELFLG = '0'"
                     .     implode(' ', $joins)
                     . ' WHERE '
                     .     implode(' AND ', $wheres)
                ;
                break;

            case 'get-register-info':
                // ID重複判定用
                $sql = 'SELECT '
                     .     '*'
                     . ' FROM '
                     .     DB_SHOP_LIBRARY . 'M00USER'
                     . ' WHERE '
                     .     "M00DELFLG = '0'"
                     . ' AND '
                     .     "M00MKENGROUP = '{$defaultKenshuGroup}'"
                     . ' AND '
                     .     "M00LOGINID = '{$bindParams['LOGINID']}'"
                     . ' AND '
                     .     "M00KENGROUP = '{$bindParams['KENGROUP']}'"
                ;
                break;

            case 'get-possession-cards':
                $sql = 'SELECT '
                     .     '*'
                     . ' FROM '
                     .     DB_SHOP_LIBRARY . 'M00USER'
                     .     ' INNER JOIN ' . DB_SHOP_LIBRARY . 'F00CARDS'
                     .         ' ON '
                     .             'F00USERID = M00USERID'
                     .     ' INNER JOIN ' . DB_SHOP_LIBRARY . 'M01WKAIIN'
                     .         ' ON '
                     .             'M01GCNO = F00GCNO'
                   //.         ' AND '
                   //.             "M01KAINSTS = '01'"
                     . ' WHERE '
                     .     "M00DELFLG = '0'"
                     . ' AND '
                     .     "M00MKENGROUP = '{$defaultKenshuGroup}'"
                     . ' AND '
                     .     "M00USERID = '{$bindParams['GCNO']}'"
                ;
                break;

            case 'get-kaiin-status': // 注文フロー内で使用 AbstractOrderAction::setGiftcardInfo
                $sql = 'SELECT '
                     .     'M01KAINSTS'
                     . ' FROM '
                     .     DB_SHOP_LIBRARY . 'M01WKAIIN'
                     . ' WHERE '
                     .     "M01GCNO = '{$bindParams['GCNO']}'"
                     . ' AND '
                     .     "M01DELFLG = '0'"
                ;
                break;

            case 'valid-order-count':
                $sql = 'SELECT '
                     .     'COUNT(*) AS CNT'
                     . ' FROM '
                     .     DB_SHOP_LIBRARY . 'F06JUCHU'
                     . ' WHERE '
                     .     "F06GCNO = '{$bindParams['GCNO']}'"
                     . ' AND '
                     .     "F06KENGROUP = '{$bindParams['KENGROUP']}'"
                     . ' AND '
                     .     "F06DELFLG = '0'"
                     . ' AND '
                     .     "F06SHORFLG <> '90'"; // キャンセル以外
                break;
            default:
                break;
        }
        return $sql;
    }
}
