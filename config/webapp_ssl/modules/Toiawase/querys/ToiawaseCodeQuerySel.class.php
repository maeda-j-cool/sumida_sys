<?php
class ToiawaseCodeQuerySel extends DBConnectSel
{
    /**
     * {@inheritdoc}
     */
    function Query($qrsarr, $qsqlno)
    {
        $qsqlstr = '';
        switch ($qsqlno) {
            case '1':
                $qsqlstr  = "select";
                $qsqlstr  .= " M03KEY2 , M03NAME ";
                $qsqlstr  .= "from ";
                $qsqlstr  .= DB_COMM_LIBRARY;
                $qsqlstr  .= "M03CODE ";
                $qsqlstr  .= "where ";
                $qsqlstr  .= " M03KEY1 = 'TOIH' ";
                $qsqlstr  .= "and ";
                $qsqlstr  .= "M03DELFLG = '0' ";
                $qsqlstr  .= "and ";
                $qsqlstr  .= "M03KEY2 in('000001','000002','000005','000008','999999') ";
                $qsqlstr  .= "ORDER BY M03SEQ ";
                break;
            case '2':
                $qsqlstr  = "select M03NAME ";
                $qsqlstr  .= "from ";
                $qsqlstr  .= DB_COMM_LIBRARY;
                $qsqlstr  .= "M03CODE ";
                $qsqlstr  .= "where M03KEY1 = 'TOIH' ";
                $qsqlstr  .= "and M03KEY2 = '";
                $qsqlstr  .= $qrsarr['M03KEY2'];
                $qsqlstr  .= "' and M03DELFLG = '0' ";
                break;
            default:
                break;
        }
        return $qsqlstr;
    }
}
