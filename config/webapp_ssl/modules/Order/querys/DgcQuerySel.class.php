<?php
class DgcQuerySel extends DBConnectSel
{
    /**
     * {@inheritdoc}
     */
    function Query($bindParams, $sqlNo)
    {
        $sql = '';
        switch ($sqlNo) {
            case 'get-dgc-info':
                $sql = 'SELECT '
                     .     '*'
                     . ' FROM '
                     .     DB_SHOP_LIBRARY . 'M02DGC'
                     . ' WHERE '
                     .     "M02SHOHNNO = '{$bindParams['ITEM_NO']}'"
                     . ' AND '
                     .     "M02DGCDELFLG = '0'"
                ;
                break;
            case 'get-dgc-results':
                $sql = 'SELECT '
                     .     '*'
                     . ' FROM '
                     .     DB_SHOP_LIBRARY . 'F08DGC AS DGC'
                     .     ' INNER JOIN ' . DB_SHOP_LIBRARY . 'F08JUCHUME AS ME'
                     .         ' ON ME.F08WJUCNO = DGC.F08WJUCNO'
                     .         ' AND ME.F08HAISONO = DGC.F08HAISONO'
                     .         ' AND ME.F08RENBAN = DGC.F08RENBAN'
                     .         " AND ME.F08SHOTYPE = '1'"
                     .     ' LEFT JOIN ' . DB_SHOP_LIBRARY . 'M02DGCSTK'
                     .         ' ON M02DSTKSHOHNNO = ME.F08SHOHNNO'
                     .         ' AND M02DSTKPUBLISHER = DGC.F08DGPUBLISHER'
                     .         ' AND M02DSTKNO = DGC.F08SLIPNO'
                     . ' WHERE '
                     .     'DGC.F08WJUCNO IN (' . implode(',', $bindParams['ORDER_NO_LIST']) . ')'
                ;
                break;
            default:
                break;
        }
        return $sql;
    }
}
