<?php
class EnqueteQuerySel extends DBConnectSel
{
    /**
     * {@inheritdoc}
     */
    function Query($bindParams, $sqlNo)
    {
        $sql = '';
        switch ($sqlNo) {
            case 'get-past-enquete':
                $sql = 'SELECT '
                    .     'F25ENQANSID'
                    . ' FROM '
                    .     DB_SHOP_LIBRARY . 'F25ENQANS'
                    . ' WHERE '
                    .     "F25KENGROUP = '{$bindParams['KENGROUP']}'"
                    . ' AND '
                    .     "F25GCNO = '{$bindParams['GCNO']}'";
                break;

            case 'get-all-enquete':
                $sql = 'SELECT '
                     .     'M35ENQID,'
                     .     'M35KENGROUP,'
                     .     'M36QUESTION,'
                     .     'M36OPTIONTYPE,'
                     .     'M36INPUTTYPE,'
                     .     'M36OPTIONS,'
                     .     'M36REQUIRED,'
                     .     'M36SEQ,'
                     .     'M37ENQOPID,'
                     .     'M37TEXT,'
                     .     'M37HASFREE,'
                     .     'M37SEQ'
                     . ' FROM '
                     .     DB_SHOP_LIBRARY . 'M35ENQUETE'
                     .     ' INNER JOIN ' . DB_SHOP_LIBRARY . 'M36ENQQ'
                     .         ' ON '
                     .             'M36ENQID = M35ENQID'
                     .         ' AND '
                     .             "M36DELFLG = '0'"
                     .     ' LEFT JOIN ' . DB_SHOP_LIBRARY . 'M37ENQOPT'
                     .         ' ON '
                     .             'M37ENQID = M36ENQID'
                     .         ' AND '
                     .             "M37DELFLG = '0'"
                     . ' WHERE '
                     .     "M35DELFLG = '0'"
                     . ' AND '
                     .     "M35KENGROUP IN ('{$bindParams['MKENGROUP']}', '{$bindParams['KENGROUP']}')"
                     . ' ORDER BY '
                     .     'M36SEQ,M37SEQ';
                break;

            default:
                break;
        }
        return $sql;
    }
}
