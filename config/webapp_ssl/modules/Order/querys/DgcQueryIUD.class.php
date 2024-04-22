<?php
class DgcQueryIUD extends DBConnectIUD
{
    /**
     * {@inheritdoc}
     */
    function Query($bindParams, $sqlNo)
    {
        $sql = '';
        switch ($sqlNo) {
            case 'get-usable-dgc-stock':
                $sql = 'SELECT '
                     .     '*'
                     . ' FROM '
                     .     DB_SHOP_LIBRARY . 'M02DGCSTK'
                     . ' WHERE '
                     .     "M02DSTKSHOHNNO = '{$bindParams['ITEM_NO']}'"
                     . ' AND '
                     .     "M02DSTKPUBLISHER = '{$bindParams['PUBLISHER']}'"
                     . ' AND '
                     .     'M02DSTKNO = ('
                     .         'SELECT '
                     .             'MIN(M02DSTKNO)'
                     .         ' FROM '
                     .             DB_SHOP_LIBRARY . 'M02DGCSTK'
                     .         ' WHERE '
                     .             "M02DSTKSHOHNNO = '{$bindParams['ITEM_NO']}'"
                     .         ' AND '
                     .             "M02DSTKPUBLISHER = '{$bindParams['PUBLISHER']}'"
                     .         ' AND '
                     .             "M02DSTKDELFLG = '0'"
                     .         ' AND '
                     .             'M02DSTKUSEDTIME IS NULL'
                     .     ')'
                     .     ' FOR UPDATE WITH RS'
                ;
                break;

            case 'update-dgc-stock':
                $sql = 'UPDATE ' . DB_SHOP_LIBRARY . 'M02DGCSTK'
                     . ' SET '
                     .     "M02DSTKUPID = '{$bindParams['ID']}',"
                     .     "M02DSTKUPPGM = '{$bindParams['PG']}',"
                     .     "M02DSTKUPDATE = '{$bindParams['DATE']}',"
                     .     "M02DSTKUSEDTIME = '{$bindParams['DATE']}',"
                     .     "M02DSTKWJUCNO = '{$bindParams['ORDER_NO']}'"
                     . ' WHERE '
                     .     "M02DSTKSHOHNNO = '{$bindParams['ITEM_NO']}'"
                     . ' AND '
                     .     "M02DSTKPUBLISHER = '{$bindParams['PUBLISHER']}'"
                     . ' AND '
                     .     "M02DSTKNO = '{$bindParams['DSTK_NO']}'"
                ;
                break;

            case 'rollback-dgc-stock':
                $sql = 'UPDATE ' . DB_SHOP_LIBRARY . 'M02DGCSTK'
                     . ' SET '
                     .     "M02DSTKUPID = '{$bindParams['ID']}',"
                     .     "M02DSTKUPPGM = '{$bindParams['PG']}',"
                     .     "M02DSTKUPDATE = '{$bindParams['DATE']}',"
                     .     "M02DSTKUSEDTIME = NULL,"
                     .     "M02DSTKWJUCNO = NULL"
                     . ' WHERE '
                     .     "M02DSTKSHOHNNO = '{$bindParams['ITEM_NO']}'"
                     . ' AND '
                     .     "M02DSTKPUBLISHER = '{$bindParams['PUBLISHER']}'"
                     . ' AND '
                     .     "M02DSTKNO = '{$bindParams['DSTK_NO']}'"
                ;
                break;

            default:
                break;
        }
        return $sql;
    }
}
