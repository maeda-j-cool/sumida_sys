<?php
class ShukaJyokyoQuerySel extends DBConnectSel
{
    /**
     * {@inheritdoc}
     */
    function Query($qrsarr, $qsqlno)
    {
        $qsqlstr = '';
        switch ($qsqlno) {
            // 申込番号取得
            case '1':
                $cardNoTemp = [];
                foreach ($qrsarr['giftcardNoList'] as $cardNo) {
                    $cardNoTemp[] = "'{$cardNo}'";
                }
                $qsqlstr  = "SELECT ";
                $qsqlstr .= " DISTINCT TINO ";
                $qsqlstr .= " FROM ";
                $qsqlstr .= " ".DB_COMM_LIBRARY."TIGCVIEW ";
                $qsqlstr .= " WHERE ";
                $qsqlstr .= " GCNO IN (" . implode(',', $cardNoTemp) . ")";
                $qsqlstr .= " ORDER BY ";
                $qsqlstr .= " TINO DESC ";
                break;
            // 発送状況(FIKAISYP)情報取得
            case '2':
                $qsqlstr .= "SELECT ";
                $qsqlstr .= "SHOCD1, STAT, TDLVDT, JDATE, PDATE, PTIME, OKIND, BCD, TNAME, TPOST, TYBN2, TADDRS";
                $qsqlstr .= " FROM ";
                $qsqlstr .= DB_COMM_LIBRARY_CGWEBLIB."FIKAISYP ";
                $qsqlstr .= " WHERE TINO = '" . $qrsarr['moushikomiNo'] . "'";
                break;
            // 発送状況(MISHOHNP)情報取得
            case '3':
                $qsqlstr .= " SELECT NAME2, TOKCD, HNG1, SHOCD FROM ";
                $qsqlstr .= DB_COMM_LIBRARY_CGWEBLIB."MISHOHNP ";
                $qsqlstr .= " WHERE ";
                $qsqlstr .= " SHOCD = '" . $qrsarr['shohinCd'] . "'";
                break;
            // 発送状況(M02SHOHIN)情報取得
            case '4':
                $qsqlstr .= " SELECT M02SNAME, M02SHOHNCD, M02BRAND, M02MAILFUKAFLG FROM ";
                $qsqlstr .= DB_COMM_LIBRARY."M02SHOHIN ";
                $qsqlstr .= " WHERE ";
                $qsqlstr .= " M02DELFLG = '0' and M02KKAIFLG = '1' and M02ID = '" . $qrsarr['siteId'] . "'";
                $qsqlstr .= " and M02SHOHNCD = '" . $qrsarr['shohinCd'] . "'";
                break;
            // 受付日取得
            case '5':
                $qsqlstr  = "SELECT ";
                $qsqlstr .= " F06JUCHUBI ";
                $qsqlstr .= " FROM ";
                $qsqlstr .= " ".DB_COMM_LIBRARY."F06JUCHU ";
                $qsqlstr .= " WHERE ";
                $qsqlstr .= " F06ID = '" . $qrsarr['siteId'] . "' ";
                $qsqlstr .= " and ";
                $qsqlstr .= " F06WJUCNO = '" . $qrsarr['moushikomiNo'] . "' ";
                $qsqlstr .= " and ";
                $qsqlstr .= " F06DELFLG = '0'";
                break;
            // 配送業者名取得
            case '6':
                $qsqlstr  = "SELECT ";
                $qsqlstr .= " M21HAISOKNM ";
                $qsqlstr .= " , M21URL ";
                $qsqlstr .= " FROM ";
                $qsqlstr .= DB_COMM_LIBRARY . "M21HAISO ";
                $qsqlstr .= " WHERE ";
                $qsqlstr .= " M21DELFLG = '0'";
                $qsqlstr .= " and ";
                $qsqlstr .= " M21HAISOCD = '" . $qrsarr['okind'] . "'";
                break;
            // 発送状況(F07JUCHUHS,F08JUCHUMS)情報取得
            case '7':
                $qsqlstr .= "SELECT ";
                $qsqlstr .= "F07WJUCNO, F08SHOHNCD, F08SNAME, F08VPOINT, F07SEI, F07MEI, M02MAILFUKAFLG, F08KIBOBI";
                $qsqlstr .= ", F07ZIP1, F07ZIP2, F07ADD1, F07ADD2, F07ADD3";
                $qsqlstr .= " FROM ";
                $qsqlstr .= DB_COMM_LIBRARY."F07JUCHUHS ";
                $qsqlstr .= " INNER JOIN ".DB_COMM_LIBRARY."F08JUCHUME ";
                $qsqlstr .= " ON ".DB_COMM_LIBRARY."F07JUCHUHS.F07WJUCNO = ".DB_COMM_LIBRARY."F08JUCHUME.F08WJUCNO ";
                $qsqlstr .= " INNER JOIN ".DB_COMM_LIBRARY."M02SHOHIN  ";
                $qsqlstr .= " ON ".DB_COMM_LIBRARY."M02SHOHIN.M02SHOHNCD = ".DB_COMM_LIBRARY."F08JUCHUME.F08SHOHNCD ";
                $qsqlstr .= " WHERE F07WJUCNO = '" . $qrsarr['moushikomiNo'] . "'";
                $qsqlstr .= " and ";
                $qsqlstr .= " F08SHOTYPE = '1'"; //商品タイプを商品に絞る
                break;

            case '8':
                $qsqlstr  = "SELECT ";
                $qsqlstr .= "*";
                $qsqlstr .= " FROM ";
                $qsqlstr .= DB_COMM_LIBRARY . "F08DGC AS DGC";
                $qsqlstr .= " INNER JOIN " . DB_COMM_LIBRARY . "F08JUCHUME AS ME";
                $qsqlstr .= " ON ME.F08WJUCNO = DGC.F08WJUCNO";
                $qsqlstr .= " AND ME.F08HAISONO = DGC.F08HAISONO";
                $qsqlstr .= " AND ME.F08RENBAN = DGC.F08RENBAN";
                $qsqlstr .= " AND ME.F08SHOTYPE = '1'";
                $qsqlstr .= " LEFT JOIN " . DB_COMM_LIBRARY . "M02DGCSTK";
                $qsqlstr .= " ON M02DSTKSHOHNNO = ME.F08SHOHNNO";
                $qsqlstr .= " AND M02DSTKPUBLISHER = DGC.F08DGPUBLISHER";
                $qsqlstr .= " AND M02DSTKNO = DGC.F08SLIPNO";
                $qsqlstr .= " WHERE ";
                $qsqlstr .= " DGC.F08WJUCNO IN (" . implode(',', $qrsarr['F08WJUCNO']) . ")";
                break;

            default:
                break;
        }
        return $qsqlstr;
    }
}
