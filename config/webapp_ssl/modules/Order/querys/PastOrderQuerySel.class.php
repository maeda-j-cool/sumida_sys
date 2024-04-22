<?php
class PastOrderQuerySel extends DBConnectSel
{
    /**
     * {@inheritdoc}
     */
    function Query($bindParams, $sqlNo)
    {
        $sql = '';
        switch ($sqlNo) {
            case 'get-past-ca-point':
                $sql = 'SELECT '
                     .     'F08SURYO,'
                     .     'F08VPOINT'
                     . ' FROM '
                     .     DB_COMM_LIBRARY . 'F06JUCHU'
                     .     ' INNER JOIN ' . DB_COMM_LIBRARY . 'F08JUCHUME'
                     .         ' ON F08WJUCNO = F06WJUCNO'
                     .     ' INNER JOIN ' . DB_COMM_LIBRARY . 'M02SHOHIN'
                     .         ' ON F08SHOHNNO = M02SHOHNNO'
                     .         " AND M02HYOJIKEY2 IN ('CA', 'DGC')"
                     . ' WHERE '
                     .     "F06DELFLG = '0'"
                     . ' AND '
                     .     "F08DELFLG = '0'"
                     . ' AND '
                     .     "F06GCNO = '{$bindParams['GCNO']}'";
                break;

            default:
                break;
        }
        return $sql;
    }
}
