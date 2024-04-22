<?php
class ToiawaseQueryIUD extends DBConnectIUD
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
                $qsqlstr .= DB_SHOP_LIBRARY;
                $qsqlstr .= "F02TOIAWAS ( ";
                $qsqlstr .= "F02DELFLG,";
                $qsqlstr .= "F02INSID,";
                $qsqlstr .= "F02INSPGM,";
                $qsqlstr .= "F02INSDATE,";
                $qsqlstr .= "F02UPID,";
                $qsqlstr .= "F02UPPGM,";
                $qsqlstr .= "F02UPDATE,";
                $qsqlstr .= "F02ID,";
                $qsqlstr .= "F02TOINO,";
                $qsqlstr .= "F02TOIKEY1,";
                $qsqlstr .= "F02TOIKEY2,";
                $qsqlstr .= "F02GCNO,";
                $qsqlstr .= "F02SHOHNNO,";
                $qsqlstr .= "F02WJUCNO,";
                $qsqlstr .= "F02TDATE,";
                $qsqlstr .= "F02SEI,";
                $qsqlstr .= "F02MEI,";
                $qsqlstr .= "F02SEIKN,";
                $qsqlstr .= "F02MEIKN,";
                $qsqlstr .= "F02EMAILPC,";
                $qsqlstr .= "F02TNAIYO,";
                $qsqlstr .= "F02TOISTS,";
                $qsqlstr .= "F02TOIDWL,";
                $qsqlstr .= "F02TEL1, ";
                $qsqlstr .= "F02TEL2, ";
                $qsqlstr .= "F02TEL3, ";
                $qsqlstr .= "F02TOIREPFLG,";
                $qsqlstr .= "F02LOGINFLG )";
                $qsqlstr .=  "values ( '";
                $qsqlstr .= $qrsarr['F02DELFLG'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02INSID'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02INSPGM'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02INSDATE'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02UPID'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02UPPGM'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02UPDATE'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02ID'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02TOINO'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02TOIKEY1'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02TOIKEY2'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02GCNO'];
                $qsqlstr .= "', ";
                $qsqlstr .= "null";//$qrsarr['F02SHOHNNO']
                $qsqlstr .= " , ";
                if (is_null($qrsarr['F02WJUCNO']) || $qrsarr['F02WJUCNO'] == '') {
                    $qsqlstr .= "null";
                } else {
                    $qsqlstr .= "'" . $qrsarr['F02WJUCNO'] . "'";
                }
                $qsqlstr .= " , '";
                $qsqlstr .= $qrsarr['F02TDATE'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02SEI'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02MEI'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02SEIKN'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02MEIKN'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02EMAILPC'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02TNAIYO'];
                $qsqlstr .= "', ";
                $qsqlstr .= "null";//$qrsarr['F02TOISTS']
                $qsqlstr .= " , '";
                $qsqlstr .= $qrsarr['F02TOIDWL'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02TEL1'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02TEL2'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02TEL3'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02TOIREPFLG'];
                $qsqlstr .= "', '";
                $qsqlstr .= $qrsarr['F02LOGINFLG'];
                $qsqlstr .= "')";
                break;
            default:
                break;
        }
        return $qsqlstr;
    }
}