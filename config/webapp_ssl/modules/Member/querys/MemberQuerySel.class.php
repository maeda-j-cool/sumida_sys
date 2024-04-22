<?php
class MemberQuerySel extends DBConnectSel
{
    /**
     * {@inheritdoc}
     */
    function Query($bindParams, $sqlNo)
    {
        $sql = '';
        switch ($sqlNo) {
            case 'get-userinfo':
                $wheres = ["M01DELFLG = '0'"];
                if (isset($bindParams['GCNO'])) {
                    $wheres[] = "M01GCNO='{$bindParams['GCNO']}'";
                }
                if (isset($bindParams['EMAIL'])) {
                    $wheres[] = "M01EMAILPC='{$bindParams['EMAIL']}'";
                }
                if (isset($bindParams['PIN'])) {
                    $wheres[] = "M01PIN='{$bindParams['PIN']}'";
                }
                if (isset($bindParams['KENGROUP'])) {
                    $wheres[] = "M01KENGROUP='{$bindParams['KENGROUP']}'";
                }
                $sql = 'SELECT '
                     .     '*'
                     . ' FROM '
                     .     DB_SHOP_LIBRARY . 'M01WKAIIN'
                     .     ' INNER JOIN '
                     .         DB_SHOP_LIBRARY . 'M11WKAIIN'
                     .         ' ON '
                     .             'M11GCNO = M01GCNO'
                     . '        AND '
                     .             "M11DELFLG = '0'"
                     .     ' INNER JOIN '
                     .         DB_SHOP_LIBRARY . 'F01JUSHO'
                     .         ' ON '
                     .             'M01GCNO = F01GCNO'
                     .         ' AND '
                     .             "F01DELFLG = '0'"
                     .         ' AND '
                     .             "F01HONFLG = '1'"
                     . ' WHERE '
                     .     implode(' AND ', $wheres);
                break;

            case 'get-register-info':
                $honFlg = '1';
                if (isset($bindParams['SHSS']) && $bindParams['SHSS']) {
                    $honFlg = '0';
                }
                $wheres = ["M01DELFLG = '0'"];
                if (isset($bindParams['GCNO'])) {
                    $wheres[] = "M01GCNO='{$bindParams['GCNO']}'";
                }
                if (isset($bindParams['EMAIL'])) {
                    $wheres[] = "M01EMAILPC='{$bindParams['EMAIL']}'";
                }
                if (isset($bindParams['PIN'])) {
                    $wheres[] = "M01PIN='{$bindParams['PIN']}'";
                }
                if (isset($bindParams['PASSWORD'])) {
                    $wheres[] = "M01PASSWD='{$bindParams['PASSWORD']}'";
                }
                if (isset($bindParams['KENGROUP'])) {
                    $wheres[] = "M01KENGROUP='{$bindParams['KENGROUP']}'";
                }
                $sql = 'SELECT '
                     .     '*'
                     . ' FROM '
                     .     DB_SHOP_LIBRARY . 'M01WKAIIN'
                     .     ' LEFT JOIN '
                     .         DB_SHOP_LIBRARY . 'F01JUSHO'
                     .         ' ON '
                     .             'F01GCNO = M01GCNO'
                     .         ' AND '
                     .             "F01HONFLG = '{$honFlg}'"
                     .         ' AND '
                     .             "F01DELFLG = '0'"
                     . ' WHERE '
                     .     implode(' AND ', $wheres);
                break;

            case 'same-user-password-check':
                $defaultKenshuGroup = WT_DEFAULT_KENSHU_GROUP;
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
                     . ' WHERE '
                     .     "M00MKENGROUP = '{$defaultKenshuGroup}'"
                     . ' AND '
                     .     "M00LOGINID = '{$bindParams['LOGINID']}'"
                     . ' AND '
                     .     "M01PASSWD = '{$bindParams['PASSWORD']}'"
                   //. ' AND '
                   //.     "M00KENGROUP <> '{$bindParams['KENGROUP']}'"
                ;
                break;

            default:
                break;
        }
        return $sql;
    }
}
