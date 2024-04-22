<?php
class ShohinShosaiQueryIUD extends DBConnectIUD
{
    /**
     * {@inheritdoc}
     */
    function Query($qrsarr, $qsqlno)
    {
        $qsqlstr = '';
        switch ($qsqlno) {
            case '1':
                $qsqlstr  = "insert into ";
                $qsqlstr .= DB_COMM_LIBRARY."F42OKINI (f42delflg,f42insid,f42insprogram,f42insdate,f42updid,f42updprogram,f42upddate,f42id,f42okiniirino,f42gcno,f42shohinno) values (";
                $qsqlstr .= "'" . $qrsarr['F42_DEL_FLG'] . "', ";
                $qsqlstr .= "'" . $qrsarr['F42_INS_ID'] . "', ";
                $qsqlstr .= "'" . $qrsarr['F42_INS_PROGRAM'] . "', ";
                $qsqlstr .= "'" . $qrsarr['F42_INS_DATE'] . "', ";
                $qsqlstr .= "'" . $qrsarr['F42_UPD_ID'] . "', ";
                $qsqlstr .= "'" . $qrsarr['F42_UPD_PROGRAM'] . "', ";
                $qsqlstr .= "'" . $qrsarr['F42_UPD_DATE'] . "', ";
                $qsqlstr .= "'" . $qrsarr['F42_ID'] . "', ";
                $qsqlstr .= "'" . $qrsarr['F42_OKINIIRI_NO'] . "', ";
                $qsqlstr .= "'" . $qrsarr['F42_GC_NO'] . "', ";
                $qsqlstr .= "'" . $qrsarr['F42_SHOHIN_NO'] . "'";
                $qsqlstr .= ")";
                break;
            default:
                break;
        }
        return $qsqlstr;
    }
}
