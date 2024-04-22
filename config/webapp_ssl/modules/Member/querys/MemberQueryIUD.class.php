<?php
class MemberQueryIUD extends DBConnectIUD
{
    /**
     * {@inheritdoc}
     */
    function Query($bindParams, $sqlNo)
    {
        $escape = function($v, $enclose = true, $nullable = true) {
            $v = (string)$v;
            if ($nullable && !strlen($v)) {
                return 'NULL';
            }
            return $enclose ? sprintf("'%s'", $v) : $v;
        };
        $sql = '';
        switch ($sqlNo) {
            case 'insert-m00':
                $insertParams = [
                    'M00DELFLG'    => "'0'",
                    'M00INSID'     => $escape($bindParams['ID']),
                    'M00INSPGM'    => $escape($bindParams['PG']),
                    'M00INSDATE'   => $escape($bindParams['DATE']),
                    'M00UPID'      => $escape($bindParams['ID']),
                    'M00UPPGM'     => $escape($bindParams['PG']),
                    'M00UPDATE'    => $escape($bindParams['DATE']),
                    'M00USERID'    => $escape($bindParams['USERID']),
                    'M00MKENGROUP' => $escape(WT_DEFAULT_KENSHU_GROUP),
                    'M00KENGROUP'  => $escape($bindParams['KENGROUP']),
                    'M00LOGINID'   => $escape($bindParams['EMAIL']),
                ];
                $sql = 'INSERT' . ' INTO ' . DB_SHOP_LIBRARY . 'M00USER'
                     . '(' . implode(',', array_keys($insertParams))   . ')'
                     . ' VALUES '
                     . '(' . implode(',', array_values($insertParams)) . ')';
                break;

            //case 'insert-m01':
            //    $insertParams = [
            //        'M01DELFLG'   => "'0'",
            //        'M01INSID'    => $escape($bindParams['ID']),
            //        'M01INSPGM'   => $escape($bindParams['PG']),
            //        'M01INSDATE'  => $escape($bindParams['DATE']),
            //        'M01UPID'     => $escape($bindParams['ID']),
            //        'M01UPPGM'    => $escape($bindParams['PG']),
            //        'M01UPDATE'   => $escape($bindParams['DATE']),
            //        'M01GCNO'     => $escape($bindParams['GCNO']),
            //        'M01PIN'      => $escape($bindParams['PIN']),
            //        'M01KENGROUP' => $escape($bindParams['KENGROUP']),
            //        'M01KAINSTS'  => "'01'", // 本登録済み
            //        'M01POINT'    => $escape(intval($bindParams['POINT']), false),
            //        'M01KAIINNO'  => "''",
            //        'M01NAME'     => "''",
            //        'M01PASSWD'   => "''",
            //        'M01TOKEN'    => "''",
            //        'M01EMAILPC'  => $escape($bindParams['EMAIL']),
            //        'M01SEX'      => "''",
            //        'M01BIRTH'    => $escape($bindParams['BIRTH01']),
            //        'M01NEWSFLG'  => "'0'",
            //        'M01DMFLG'    => "'0'",
            //        'M01PDATE'    => 'NULL',
            //        'M01TDATE'    => 'NULL',
            //    ];
            //    $sql = 'INSERT' . ' INTO ' . DB_SHOP_LIBRARY . 'M01WKAIIN'
            //         . '(' . implode(',', array_keys($insertParams))   . ')'
            //         . ' VALUES '
            //         . '(' . implode(',', array_values($insertParams)) . ')';
            //    break;

            case 'update-m01':
                $updateParams = [
                  //'M01DELFLG' => "'0'",
                    'M01UPID'   => $escape($bindParams['ID']),
                    'M01UPPGM'  => $escape($bindParams['PG']),
                    'M01UPDATE' => $escape($bindParams['DATE']),
                    'M01BIRTH'  => $escape($bindParams['BIRTH01']),
                ];
                if (isset($bindParams['NAME'])) {
                    $updateParams['M01NAME'] = $escape($bindParams['NAME']);
                }
                if (isset($bindParams['STATUS'])) {
                    $updateParams['M01KAINSTS'] = $escape($bindParams['STATUS']);
                }
                if (isset($bindParams['POINT'])) {
                    $updateParams['M01POINT'] = $escape($bindParams['POINT'], false);
                }
                if (isset($bindParams['EMAIL'])) {
                    $updateParams['M01EMAILPC'] = $escape($bindParams['EMAIL']);
                }
                if (isset($bindParams['TDATE'])) {
                    $updateParams['M01TDATE'] = $escape($bindParams['TDATE']);
                }
                if (isset($bindParams['KENCD'])) {
                    $updateParams['M01KENCD'] = $escape($bindParams['KENCD']);
                }
                if (isset($bindParams['PASSWORD']) && strlen($bindParams['PASSWORD'])) {
                    $updateParams['M01PASSWD'] = $escape($bindParams['PASSWORD']);
                    $updateParams['M01PDATE'] = $escape($bindParams['DATE']);
                }
                if (isset($bindParams['NEWSFLG'])) {
                    $updateParams['M01NEWSFLG'] = $escape($bindParams['NEWSFLG']);
                }
                $updateLines = [];
                foreach ($updateParams as $k => $v) {
                    $updateLines[] = $k . ' = ' . $v;
                }
                $sql = 'UPDATE '
                     .     DB_SHOP_LIBRARY . 'M01WKAIIN'
                     . ' SET '
                     .     implode(',', $updateLines)
                     . ' WHERE '
                     .     "M01GCNO = '{$bindParams['GCNO']}'";
                break;

            case 'insert-f00':
                $insertParams = [
                    'F00INSID'     => $escape($bindParams['ID']),
                    'F00INSPGM'    => $escape($bindParams['PG']),
                    'F00INSDATE'   => $escape($bindParams['DATE']),
                    'F00UPID'      => $escape($bindParams['ID']),
                    'F00UPPGM'     => $escape($bindParams['PG']),
                    'F00UPDATE'    => $escape($bindParams['DATE']),
                    'F00USERID'    => $escape($bindParams['USERID']),
                    'F00RENBAN'  => 'CASE WHEN MAX(F00RENBAN) IS NULL THEN 1 ELSE MAX(F00RENBAN)+1 END AS F00RENBAN',
                    'F00GCNO'    => $escape($bindParams['GCNO']),
                    'F00PIN'     => $escape($bindParams['PIN']),
                    'F00KENCD'   => $escape($bindParams['KENCD']),
                    'F00POINT'   => $escape($bindParams['POINT']),
                    'F00TDATE'   => $escape($bindParams['TDATE']),
                ];
                $sql = 'INSERT' . ' INTO ' . DB_SHOP_LIBRARY . 'F00CARDS'
                    . ' (' . implode(',', array_keys($insertParams))   . ')'
                    . ' SELECT ' . implode(',', array_values($insertParams))
                    . ' FROM ' . DB_SHOP_LIBRARY . 'F00CARDS'
                    . ' WHERE ' . ' F00USERID = ' . $insertParams['F00USERID'];
                break;

            case 'insert-f01':
                $insertParams = [
                    'F01DELFLG'  => "'0'",
                    'F01INSID'   => $escape($bindParams['ID']),
                    'F01INSPGM'  => $escape($bindParams['PG']),
                    'F01INSDATE' => $escape($bindParams['DATE']),
                    'F01UPID'    => $escape($bindParams['ID']),
                    'F01UPPGM'   => $escape($bindParams['PG']),
                    'F01UPDATE'  => $escape($bindParams['DATE']),
                    'F01GCNO'    => $escape($bindParams['GCNO']),
                    'F01RENBAN'  => '1',
                    'F01SEI'     => $escape($bindParams['SEI01'], true, false),
                    'F01MEI'     => $escape($bindParams['MEI01'], true, false),
                    'F01SEIKN'   => $escape($bindParams['SEIK01'], true, false),
                    'F01MEIKN'   => $escape($bindParams['MEIK01'], true, false),
                    'F01HONFLG'  => "'1'",
                    'F01COPX'    => "'0'",
                    'F01CPNM'    => "''",
                    'F01CPKN'    => "''",
                    'F01CPN2'    => "''",
                    'F01CPN3'    => "''",
                    'F01ZIP1'    => $escape($bindParams['ZIP1'], true, false),
                    'F01ZIP2'    => $escape($bindParams['ZIP2'], true, false),
                    'F01ADD1'    => $escape($bindParams['ADD1'], true, false),
                    'F01ADD2'    => $escape($bindParams['ADD2'], true, false),
                    'F01ADD3'    => $escape($bindParams['ADD3'], true, false),
                    'F01TEL11'   => $escape($bindParams['TEL11'], true, false),
                    'F01TEL12'   => $escape($bindParams['TEL12'], true, false),
                    'F01TEL13'   => $escape($bindParams['TEL13'], true, false),
                    'F01TEL21'   => $escape($bindParams['TEL21'], true, false),
                    'F01TEL22'   => $escape($bindParams['TEL22'], true, false),
                    'F01TEL23'   => $escape($bindParams['TEL23'], true, false),
                ];
                $sql = 'INSERT' . ' INTO ' . DB_SHOP_LIBRARY . 'F01JUSHO'
                     . '(' . implode(',', array_keys($insertParams))   . ')'
                     . ' VALUES '
                     . '(' . implode(',', array_values($insertParams)) . ')';
                break;

            case 'update-f01':
                $updateParams = [
                    'F01UPID'   => $escape($bindParams['ID']),
                    'F01UPPGM'  => $escape($bindParams['PG']),
                    'F01UPDATE' => $escape($bindParams['DATE']),
                    'F01SEI'    => $escape($bindParams['SEI01'], true, false),
                    'F01MEI'    => $escape($bindParams['MEI01'], true, false),
                    'F01SEIKN'  => $escape($bindParams['SEIK01'], true, false),
                    'F01MEIKN'  => $escape($bindParams['MEIK01'], true, false),
                    'F01HONFLG' => "'1'",
                    'F01COPX'   => "'0'",
                    'F01ZIP1'   => $escape($bindParams['ZIP1'], true, false),
                    'F01ZIP2'   => $escape($bindParams['ZIP2'], true, false),
                    'F01ADD1'   => $escape($bindParams['ADD1'], true, false),
                    'F01ADD2'   => $escape($bindParams['ADD2'], true, false),
                    'F01ADD3'   => $escape($bindParams['ADD3'], true, false),
                    'F01TEL11'  => $escape($bindParams['TEL11'], true, false),
                    'F01TEL12'  => $escape($bindParams['TEL12'], true, false),
                    'F01TEL13'  => $escape($bindParams['TEL13'], true, false),
                    'F01TEL21'  => $escape($bindParams['TEL21'], true, false),
                    'F01TEL22'  => $escape($bindParams['TEL22'], true, false),
                    'F01TEL23'  => $escape($bindParams['TEL23'], true, false),
                ];
                $updateLines = [];
                foreach ($updateParams as $k => $v) {
                    $updateLines[] = $k . ' = ' . $v;
                }
                $sql = 'UPDATE '
                     .     DB_SHOP_LIBRARY . 'F01JUSHO'
                     . ' SET '
                     .     implode(',', $updateLines)
                     . ' WHERE '
                     .     "F01GCNO = '{$bindParams['GCNO']}'"
                     . ' AND '
                     .     'F01RENBAN = 1'; // "F01HONFLG = '1'";
                break;

            case 'insert-m11':
                $insertParams = [
                    'M11DELFLG'  => "'0'",
                    'M11INSID'   => $escape($bindParams['ID']),
                    'M11INSPGM'  => $escape($bindParams['PG']),
                    'M11INSDATE' => $escape($bindParams['DATE']),
                    'M11UPID'    => $escape($bindParams['ID']),
                    'M11UPPGM'   => $escape($bindParams['PG']),
                    'M11UPDATE'  => $escape($bindParams['DATE']),
                    'M11GCNO'    => $escape($bindParams['GCNO']),
                    'M11REL01'   => $escape($bindParams['REL01']),
                    'M11BIRTH02' => $escape($bindParams['BIRTH02']),
                    'M11SEI02'   => $escape($bindParams['SEI02'], true, false),
                    'M11MEI02'   => $escape($bindParams['MEI02'], true, false),
                    'M11SEIKN02' => $escape($bindParams['SEIK02'], true, false),
                    'M11MEIKN02' => $escape($bindParams['MEIK02'], true, false),
                    'M11REL02'   => $escape($bindParams['REL02']),
                    'M11SEI03'   => $escape($bindParams['SEI03'], true, false),
                    'M11MEI03'   => $escape($bindParams['MEI03'], true, false),
                    'M11SEIKN03' => $escape($bindParams['SEIK03'], true, false),
                    'M11MEIKN03' => $escape($bindParams['MEIK03'], true, false),
                    'M11BIRTH03' => $escape($bindParams['BIRTH03']),
                    'M11REL03'   => $escape($bindParams['REL03']),
                ];
                $sql = 'INSERT' . ' INTO ' . DB_SHOP_LIBRARY . 'M11WKAIIN'
                     . '(' . implode(',', array_keys($insertParams))   . ')'
                     . ' VALUES '
                     . '(' . implode(',', array_values($insertParams)) . ')';
                break;

            case 'update-m11':
                $updateParams = [
                    'M11UPID'    => $escape($bindParams['ID']),
                    'M11UPPGM'   => $escape($bindParams['PG']),
                    'M11UPDATE'  => $escape($bindParams['DATE']),
                    'M11REL01'   => $escape($bindParams['REL01']),
                    'M11SEI02'   => $escape($bindParams['SEI02'], true, false),
                    'M11MEI02'   => $escape($bindParams['MEI02'], true, false),
                    'M11SEIKN02' => $escape($bindParams['SEIK02'], true, false),
                    'M11MEIKN02' => $escape($bindParams['MEIK02'], true, false),
                    'M11REL02'   => $escape($bindParams['REL02']),
                    'M11BIRTH02' => $escape($bindParams['BIRTH02']),
                  // お子様の情報は編集不可＋非表示
                  //'M11SEI03'   => $escape($bindParams['SEI03'], true, false),
                  //'M11MEI03'   => $escape($bindParams['MEI03'], true, false),
                  //'M11SEIKN03' => $escape($bindParams['SEIK03'], true, false),
                  //'M11MEIKN03' => $escape($bindParams['MEIK03'], true, false),
                  //'M11BIRTH03' => $escape($bindParams['BIRTH03']),
                  //'M11REL03'   => $escape($bindParams['REL03']),
                ];
                $updateLines = [];
                foreach ($updateParams as $k => $v) {
                    $updateLines[] = $k . ' = ' . $v;
                }
                $sql = 'UPDATE '
                     .     DB_SHOP_LIBRARY . 'M11WKAIIN'
                     . ' SET '
                     .     implode(',', $updateLines)
                     . ' WHERE '
                     .     "M11GCNO = '{$bindParams['GCNO']}'";
                break;

            case 'insert-f25':
                $insertParams = [
                    'F25INSID'    => $escape($bindParams['ID']),
                    'F25INSPGM'   => $escape($bindParams['PG']),
                    'F25INSDATE'  => $escape($bindParams['DATE']),
                    'F25UPID'     => $escape($bindParams['ID']),
                    'F25UPPGM'    => $escape($bindParams['PG']),
                    'F25UPDATE'   => $escape($bindParams['DATE']),
                    'F25ENQANSID' => $escape($bindParams['F25ENQANSID']),
                    'F25KENGROUP' => $escape($bindParams['F25KENGROUP']),
                    'F25WJUCNO'   => $escape($bindParams['F25WJUCNO']),
                    'F25GCNO'     => $escape($bindParams['F25GCNO']),
                ];
                $sql = 'INSERT' . ' INTO ' . DB_COMM_LIBRARY . 'F25ENQANS'
                     . ' (' . implode(',', array_keys($insertParams)) . ')'
                     . ' VALUES'
                     . ' (' . implode(',', array_values($insertParams)) . ')';
                break;

            case 'insert-f26':
                $insertParams = [
                    'F26INSID'     => $escape($bindParams['ID']),
                    'F26INSPGM'    => $escape($bindParams['PG']),
                    'F26INSDATE'   => $escape($bindParams['DATE']),
                    'F26UPID'      => $escape($bindParams['ID']),
                    'F26UPPGM'     => $escape($bindParams['PG']),
                    'F26UPDATE'    => $escape($bindParams['DATE']),
                    'F26ENQANSID'  => $escape($bindParams['F26ENQANSID']),
                    'F26ENQID'     => $escape($bindParams['F26ENQID']),
                    'F26ENQOPID'   => $escape($bindParams['F26ENQOPID']),
                    'F26ENQOPFREE' => $escape($bindParams['F26ENQOPFREE']),
                ];
                $sql = 'INSERT' . ' INTO ' . DB_COMM_LIBRARY . 'F26ENQANSD'
                     . ' (' . implode(',', array_keys($insertParams)) . ')'
                     . ' VALUES'
                     . ' (' . implode(',', array_values($insertParams)) . ')';
                break;

            default:
                break;
        }
        return $sql;
    }
}
