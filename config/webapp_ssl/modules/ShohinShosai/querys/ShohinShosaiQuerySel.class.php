<?php
class ShohinShosaiQuerySel extends DBConnectSel
{
    /**
     * {@inheritdoc}
     */
    function Query($qrsarr, $qsqlno)
    {
        $qsqlstr = '';
        switch ($qsqlno) {
            // SEO対策カテゴリ情報取得
            case '1':
                $qsqlstr = "select ";
                $qsqlstr .= " M04CNAME ";
                $qsqlstr .= "from ";
                $qsqlstr .= " ".DB_COMM_LIBRARY."M04CATEG ";
                $qsqlstr .= "where ";
                $qsqlstr .= " M04DELFLG = '0' ";
                $qsqlstr .= "and ";
                $qsqlstr .= " M04KKAIFLG = '1' ";
                $qsqlstr .= "and ";
                $qsqlstr .= " M04CATEGNO = '" . $qrsarr['cateno'] . "'";
                $qsqlstr .= "and ";
                $qsqlstr .= " M04ID = '" . SHOP_ID . "'";
                break;
            // お気に入り商品チェック
            case '2':
                $qsqlstr = "select ";
                $qsqlstr .= " count(*) ";
                $qsqlstr .= "from ";
                $qsqlstr .= " ".DB_COMM_LIBRARY."F42OKINI ";
                $qsqlstr .= "where ";
                $qsqlstr .= " F42DELFLG = '0' ";
                $qsqlstr .= "and ";
                $qsqlstr .= " F42SHOHINNO = '" . $qrsarr['shohin'] . "' ";
                $qsqlstr .= "and ";
                $qsqlstr .= " F42ID = '" . SHOP_ID . "'";
                $qsqlstr .= "and ";
                $qsqlstr .= " F42GCNO = '" . $qrsarr['giftcard_no'] . "'";
                break;
            // カテゴリ番号を取得
            case '3':
                $qsqlstr  = "SELECT ";
                $qsqlstr .= "M04CATEGNO ";
                $qsqlstr .= "FROM ";
                $qsqlstr .= " ".DB_COMM_LIBRARY."M04CATEG ";
                $qsqlstr .= "WHERE ";
                $qsqlstr .= "M04CATEGNO IN ";
                $qsqlstr .= "( ";
                $qsqlstr .= "SELECT ";
                $qsqlstr .= "F03CATEGNO ";
                $qsqlstr .= "FROM ";
                $qsqlstr .= " ".DB_COMM_LIBRARY."F03SHOCAT ";
                $qsqlstr .= "WHERE ";
                $qsqlstr .= "F03SHOHNNO = '" . $qrsarr['shohin'] . "'";
                $qsqlstr .= ") ";
                $qsqlstr .= "AND ";
                $qsqlstr .= "M04GROUP2 = '000002' ";
                $qsqlstr .= "AND ";
                $qsqlstr .= "M04KAISO = 1 ";
                $qsqlstr .= "AND ";
                $qsqlstr .= "M04ID = '" . SHOP_ID . "'";
                break;
            // 関連商品情報を取得
            case '4':
                $qsqlstr  = "select ";
                $qsqlstr .= "M02SNAME ";
                $qsqlstr .= ", M02BRAND ";
                $qsqlstr .= ", M02SHOHNNO ";
                $qsqlstr .= ", M02SHOHNCD";
                $qsqlstr .= ", M02VPOINT ";
                $qsqlstr .= "from ";
                $qsqlstr .= DB_COMM_LIBRARY . "M02SHOHIN as sh";
                $qsqlstr .= " inner join ";
                $qsqlstr .= DB_COMM_LIBRARY . "F22SHOKEN as ken";
                $qsqlstr .= " on sh.M02SHOHNNO = ken.F22SHOHNNO";
                $qsqlstr .= " and";
                $qsqlstr .= " sh.M02ID = ken.F22ID ";
                $qsqlstr .= "where ";
                $qsqlstr .= "M02ID = '" . SHOP_ID . "'";
                $qsqlstr .= "and ";
                $qsqlstr .= "M02DELFLG = '0'";
                $qsqlstr .= "and ";
                $qsqlstr .= "M02KKAIFLG = '1'";
                $qsqlstr .= "and ";
                $qsqlstr .= "M02SGROUP = '" . $qrsarr['groupNo'] . "'";
                $qsqlstr .= "and ";
                $qsqlstr .= "M02SHOHNNO != '" . $qrsarr['shohinNo'] . "'";
                $qsqlstr .= " and";
                $qsqlstr .= " F22DELFLG = '0'";
                $qsqlstr .= " and";
                $qsqlstr .= " F22KENGROUP = '" . $qrsarr['F22KENGROUP'] . "'";
                $qsqlstr .= " order by M02SGROUPSORT ASC, M02SHOHNNO ASC";
                $qsqlstr .= " fetch first 20 rows only";
                break;
            // 配送指定不可日期間表示文言を取得
            case '5':
                $qsqlstr  = "select ";
                $qsqlstr .= "F70OSHIRASE ";
                $qsqlstr .= "from ";
                $qsqlstr .= DB_COMM_LIBRARY . "F70HFUKA ";
                $qsqlstr .= "where ";
                $qsqlstr .= "F70ID = '" . SHOP_ID . "'";
                $qsqlstr .= " and ";
                $qsqlstr .= "F70DELFLG = '0'";
                $qsqlstr .= " and ";
                $qsqlstr .= "F70KKAIFLG = '1'";
                $qsqlstr .= " and ";
                $qsqlstr .= "F70SHOHNNO = '" . $qrsarr['shohinNo'] . "'";
                $qsqlstr .= " and ";
                $qsqlstr .= "days(F70EDATE) >= days(current date)";
                $qsqlstr .= " and ";
                $qsqlstr .= "days(F70SDATE) <= days(current date)";
                $qsqlstr .= " and ";
                $qsqlstr .= "F70ZENFLG = '" . $qrsarr['zenFlg'] . "'";
                $qsqlstr .= " order by F70ZENFLG ASC, F70RENBAN DESC";
                $qsqlstr .= " fetch first 1 rows only";
                break;
            // 配送形態を取得
            case '6':
                $qsqlstr  = " select ";
                $qsqlstr .= " F72SDATE ";
                $qsqlstr .= " ,F72EDATE ";
                $qsqlstr .= " ,F72HAISOKBN ";
                $qsqlstr .= " from ";
                $qsqlstr .= DB_COMM_LIBRARY;
                $qsqlstr .= "F72GHAISO ";
                $qsqlstr .= " where ";
                $qsqlstr .= "F72ID = '" . SHOP_ID . "'";
                $qsqlstr .= " and ";
                $qsqlstr .= " F72DELFLG = '0' ";
                $qsqlstr .= " and ";
                $qsqlstr .= " F72KKAIFLG = '1' ";
                $qsqlstr .= " and ";
                $qsqlstr .= " F72SHOHNNO = " . $qrsarr['shohinNo'];
                $qsqlstr .= " and ";
                $qsqlstr .= "days(F72EDATE) >= days(current date)";
                $qsqlstr .= " order by F72SDATE";
                $qsqlstr .= " fetch first 1 rows only";
                break;
            // 商品詳細の表示項目を取得
            case '7':
                $qsqlstr  = " select ";
                $qsqlstr .= " M03NAME ";
                $qsqlstr .= " ,M03SEQ ";
                $qsqlstr .= " from ";
                $qsqlstr .= DB_COMM_LIBRARY;
                $qsqlstr .= "M03CODE ";
                $qsqlstr .= " where ";
                $qsqlstr .= " M03DELFLG = '0' ";
                $qsqlstr .= " and ";
                $qsqlstr .= " M03KKAIFLG = '1' ";
                $qsqlstr .= " and ";
                $qsqlstr .= " M03KEY1 = '" . $qrsarr['key1'] . "'";
                $qsqlstr .= " and ";
                $qsqlstr .= " M03KEY2 like '" . trim($qrsarr['key2']) . "%'";
                $qsqlstr .= " and ";
                $qsqlstr .= " M03NAME is not null";
                $qsqlstr .= " and ";
                $qsqlstr .= " M03NAME != ''";
                $qsqlstr .= " order by M03SEQ ASC";
                break;
            // お気に入り情報取得
            case '8':
                $qsqlstr  = "select ";
                $qsqlstr .= "F42SHOHINNO ";
                $qsqlstr .= "from ";
                $qsqlstr .= DB_COMM_LIBRARY;
                $qsqlstr .= "F42OKINI ";
                $qsqlstr .= "where ";
                $qsqlstr .= "F42GCNO = '" . $qrsarr['F42_GC_NO']. "' ";
                $qsqlstr .= "and ";
                $qsqlstr .= "F42DELFLG = '0' ";
                $qsqlstr .= "and ";
                $qsqlstr .= "F42ID = '" . SHOP_ID . "' ";
                $qsqlstr .= "order by F42INSDATE ";
                break;

            case '9': // メガパンくず1
                $qsqlstr = 'SELECT '
                         .     'F41CATEGNO'
                         . ' FROM '
                         .     DB_COMM_LIBRARY . 'F41BREAD'
                         . ' WHERE '
                         .     "F41SHOHNNO = '" . $qrsarr['F41SHOHNNO'] . "'"
                         . ' AND '
                         .     "F41DELFLG = '0'"
                         . ' AND '
                         .     "F41KKAIFLG = '1'";
                break;

            case '10': // メガパンくず2
                $qsqlstr = 'SELECT '
                         .     'M04CATEGNO,'
                         .     'M04CNAME,'
                         .     'M03NRYAKU'
                         . ' FROM '
                         .     DB_COMM_LIBRARY . 'M04CATEG'
                         .         ' INNER JOIN ' . DB_COMM_LIBRARY . 'M03CODE'
                         .             ' ON '
                         .                 "M03DELFLG = '0'"
                         .             ' AND '
                         .                 'M03KEY1 = M04GROUP1'
                         .             ' AND '
                         .                 'M03KEY2 = M04GROUP2'
                         . ' WHERE '
                         .     "M04CATEGNO IN ('" . implode("', '", $qrsarr['CATNO_LIST']) . "')"
                         . ' AND '
                         .     "M04DELFLG = '0'"
                         . ' AND '
                         .     "M04KKAIFLG = '1'";
                break;

            //#1641 start
            case '11'://季節商品配送日設定取得（現在の日付に納期を足した日付が含まれる配送可能日を取得するのは_getKisetsuHaisoDateQuery）
                $qsqlstr = 'SELECT '
                         .     'F04RENBAN,'
                         .     'F04SDATE,'
                         .     'F04EDATE'
                         . ' FROM '
                         .     DB_COMM_LIBRARY . 'F04KISETU'
                         . ' WHERE '
                         .     "F04SHOHNNO = '" . $qrsarr['F04SHOHNNO'] . "'"
                         . ' AND '
                         .     "F04DELFLG = '0'"
                         . ' AND '
                         .     "F04KKAIFLG = '1'";
                break;

            case '12'://期間限定配送年月日設定取得（現在の日付に納期を足した日付が含まれる/期間限定配送年月日取得するのはcase '6':）
                $qsqlstr = 'SELECT '
                         .     'F72RENBAN,'
                         .     'F72SDATE,'
                         .     'F72EDATE,'
                         .     'F72HAISOKBN'
                         . ' FROM '
                         .     DB_COMM_LIBRARY . 'F72GHAISO'
                         . ' WHERE '
                         .     "F72SHOHNNO = '" . $qrsarr['F72SHOHNNO'] . "'"
                         . ' AND '
                         .     "F72DELFLG = '0'"
                         . ' AND '
                         .     "F72KKAIFLG = '1'";
                break;
            //#1641 end

            default:
                break;
        }
        return $qsqlstr;
    }
}
