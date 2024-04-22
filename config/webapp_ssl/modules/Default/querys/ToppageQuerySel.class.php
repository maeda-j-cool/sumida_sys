<?php
class ToppageQuerySel extends DBConnectSel
{
    /**
     * {@inheritdoc}
     */
    function Query($qrsarr, $qsqlno)
    {
        $qsqlstr = '';
        switch ($qsqlno) {
            case '1':
                $qsqlstr  = " select ";
                $qsqlstr .= " M22URL ";
                $qsqlstr .= "from ";
                $qsqlstr .= DB_SHOP_LIBRARY." M22VIDEO ";
                $qsqlstr .= "where ";
                $qsqlstr .= " M22GCNO = '" . $qrsarr['giftcard_no'] . "'";
                $qsqlstr .= " and ";
                $qsqlstr .= " M22DELFLG = '0' ";
                $qsqlstr .= " and ";
                $qsqlstr .= " M22ID = '". SHOP_ID . "'";
                $qsqlstr .= " fetch first 1 rows only";
                break;
            default:
                break;
        }
        return $qsqlstr;
    }
}
