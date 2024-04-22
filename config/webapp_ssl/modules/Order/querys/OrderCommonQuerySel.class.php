<?php
/**
 * ProjectName:ギフトカード
 * Subsystem:通販Webシステム
 *
 * PHP versions 5.4.17
 *
 * @package webapp_ssl
 */

/**
 * 注文QuerySelクラス
 *
 * @author  Yuki Tanaka
 * @version Release:<1.0>
 */
class OrderCommonQuerySel extends DBConnectSel
{
    /**
     * SQL構築処理
     *
     * Executeメソッドから呼び出されて、selsqlnoに対応するクエリーをストリングとして返す。
     *
     * @param array  $qrsarr クエリーパラメータ
     * @param string $qsqlno SQL識別番号
     *
     * @return string クエリーストリング
     */
    public function Query($qrsarr, $qsqlno)
    {
        //SQLを組み立てる変数
        $qsqlstr = '';
        switch ($qsqlno) {
            case '1':
                $qsqlstr  = " select ";
                $qsqlstr .= "   M03KEY2,";
                $qsqlstr .= "   M03NAME,";
                $qsqlstr .= "   M03CHARA1";
                $qsqlstr .= " from ";
                $qsqlstr .= DB_COMM_LIBRARY . "M03CODE ";
                $qsqlstr .= " where ";
                $qsqlstr .= "   M03KEY1 = '" . $qrsarr['masterKey'] . "'";
                $qsqlstr .= " and ";
                $qsqlstr .= "   M03DELFLG = '0'";
                $qsqlstr .= " order by ";
                $qsqlstr .= "   M03SEQ ";
                break;
            case '2':
                $qsqlstr  = " select ";
                $qsqlstr .= "   M02SHOHNNO , ";                  //商品番号
                $qsqlstr .= "   M02SHOHNCD , ";                  //商品コード
                $qsqlstr .= "   M02SNAME , ";                    //商品名
                $qsqlstr .= "   M02SNAMEK , ";                   //商品名カナ
                $qsqlstr .= "   M02BRAND , ";                    //
                $qsqlstr .= "   M02HSKEITA , ";                  //配送形態
                $qsqlstr .= "   M02HAIMTCD , ";                  //配送元識別コード
                $qsqlstr .= "   M02VPOINT , ";                   //商品ポイント
                $qsqlstr .= "   M02KISEFLG , ";                  //季節商品フラグ
                $qsqlstr .= "   M02NOKI , ";                     //標準納期
                $qsqlstr .= "   M02GENTEI , ";                   //限定数
                $qsqlstr .= "   M02NOKORI , ";                   //残り数
                $qsqlstr .= "   M02KANOSU , ";                   //購入可能数
                $qsqlstr .= "   M02SDATE , ";                    //販売開始日
                $qsqlstr .= "   M02EDATE , ";                    //販売終了日
                $qsqlstr .= "   M02HOSOFLG , ";                  //包装フラグ
                $qsqlstr .= "   M02NOSIKBN , ";                  //のし区分
                $qsqlstr .= "   M02HAISOFJFLG , ";               //配送不可指定除外フラグ
                $qsqlstr .= "   M02TOKUSHUFLG , ";               //特殊商品フラグ
                $qsqlstr .= "   M02KIKANGHKFLG , ";              //期間限定配送携帯フラグ
                $qsqlstr .= "   M02MCRDFLG ,";                   //挨拶状フラグ
                $qsqlstr .= "   M02TAXFREEFLG ";                 //消費税なしフラグ
                $qsqlstr .= " from ";
                $qsqlstr .= DB_COMM_LIBRARY . "M02SHOHIN ";
                $qsqlstr .= " where ";
                $qsqlstr .= "    M02SHOHNNO = " . $qrsarr['shohinNo'];
                $qsqlstr .= "   AND M02DELFLG = '0' ";
                $qsqlstr .= "   AND M02KKAIFLG = '1' ";
                $qsqlstr .= " order by ";
                $qsqlstr .= "   M02SHOHNNO ";
                break;
            case '3':
                $qsqlstr  = " select ";
                $qsqlstr .= "   F04SDATE , ";                    //季節商品配送可能開始日
                $qsqlstr .= "   F04EDATE  ";                     //季節商品配送終了開始日
                $qsqlstr .= " from ";
                $qsqlstr .= DB_COMM_LIBRARY . "F04KISETU ";
                $qsqlstr .= " where ";
                $qsqlstr .= "   F04SHOHNNO = " . $qrsarr['shohinNo'];
                $qsqlstr .= "   AND F04DELFLG = '0' ";
                $qsqlstr .= "   AND F04KKAIFLG = '1' ";
                $qsqlstr .= "   AND F04EDATE > '1' ";
                $qsqlstr .= " order by ";
                $qsqlstr .= "   F04SDATE ASC ";
                break;

            case '4':
                $qsqlstr  = " select ";
                $qsqlstr .= "   F70SHOHNNO , ";                  //商品番号
                $qsqlstr .= "   F70SDATE , ";                    //配送指定不可日適用開始日
                $qsqlstr .= "   F70EDATE , ";                    //配送指定不可日適用終了日
                $qsqlstr .= "   F70OSHIRASE , ";                 //お知らせ文言
                $qsqlstr .= "   F70RENBAN , ";                   //配送指定不可連番
                $qsqlstr .= "   F71TEKIYONO , ";                 //配送指定適用番号
                $qsqlstr .= "   F71FUKADATE  ";                  //配送指定不可日
                $qsqlstr .= " from ";
                $qsqlstr .= DB_COMM_LIBRARY . "F70HFUKA LEFT JOIN ";
                $qsqlstr .= DB_COMM_LIBRARY . "F71HFUKABI ON F70SHOHNNO = F71SHOHNNO ";
                $qsqlstr .= " where ";
                $qsqlstr .= "   (F70SHOHNNO = " . $qrsarr['shohinNo'];
                $qsqlstr .= "   OR  F70SHOHNNO = " . SHOHIN_NO . " ) ";
                $qsqlstr .= "   AND F70EDATE >= CURRENT_TIMESTAMP ";
                $qsqlstr .= "   AND F70SDATE <= CURRENT_TIMESTAMP ";
                $qsqlstr .= "   AND F70DELFLG = '0' ";
                $qsqlstr .= "   AND F70KKAIFLG = '1' ";
                $qsqlstr .= "   AND F71DELFLG = '0' ";
                $qsqlstr .= "   AND F71KKAIFLG = '1' ";
                $qsqlstr .= "   AND F71FUKADATE > CURRENT_TIMESTAMP ";
                $qsqlstr .= "   AND F70RENBAN = F71TEKIYONO ";
                $qsqlstr .= " order by ";
                $qsqlstr .= "   F70SHOHNNO DESC,F71FUKADATE ";
                break;

            case '5':
                $qsqlstr  = " select ";
                $qsqlstr .= "   F72HAISOKBN  ";                  //期間限定配送区分
                $qsqlstr .= " from ";
                $qsqlstr .= DB_COMM_LIBRARY . "F72GHAISO ";
                $qsqlstr .= " where ";
                $qsqlstr .= "   F72SHOHNNO = " . $qrsarr['shohinNo'];
                $qsqlstr .= "   AND F72DELFLG = '0' ";
                $qsqlstr .= "   AND F72KKAIFLG = '1' ";
                $qsqlstr .= "   AND F72SDATE <= '" . $qrsarr['today'] . "'";
                $qsqlstr .= "   AND F72EDATE >= '" . $qrsarr['today'] . "'";
                $qsqlstr .= " order by ";
                $qsqlstr .= "   F72RENBAN DESC ";
                break;

            case '8':
                $qsqlstr = 'SELECT NEXTVAL FOR ' . WT_DB_TBL_PREFIX . $qrsarr['SEQ_NAME'] . ' AS SEQ FROM SYSIBM/SYSDUMMY1';
                break;

            case '9':
                $qsqlstr  = " select ";
                $qsqlstr .= "   M20ID  ";                  //サイトコード
                $qsqlstr .= " from ";
                $qsqlstr .= DB_COMM_LIBRARY . "M20KENSHU ";
                $qsqlstr .= " where ";
                $qsqlstr .= "   M20KENCD = '" . $qrsarr['ticketNo'] . "'";
                $qsqlstr .= "   AND M20DELFLG = '0' ";
                break;

            case '10':
                $qsqlstr  = " select ";
                $qsqlstr .= "   F21KENGROUP  ";                  // 券種グループ
                $qsqlstr .= " from ";
                $qsqlstr .= DB_COMM_LIBRARY . "F21KENGRP ";
                $qsqlstr .= " where ";
                $qsqlstr .= "   F21KENCD = '" . $qrsarr['ticketNo'] . "'";
                $qsqlstr .= "   AND F21DELFLG = '0' ";
                break;

            case 'get_item_category':
                $qsqlstr = ' select '
                         .     'F03SHOHNNO,'
                         .     'M04CNAME'
                         . ' from '
                         .     DB_COMM_LIBRARY . 'F03SHOCAT'
                         .         ' inner join ' . DB_COMM_LIBRARY . 'M04CATEG'
                         .             ' on M04CATEGNO = F03CATEGNO'
                         . ' where '
                         .     "M04DELFLG = '0'"
                         . ' and '
                         .     "F03DELFLG = '0'"
                         . ' and '
                         .     'F03SHOHNNO IN (' . implode(',', $qrsarr['item_no_list']) . ')'
                         . ' order by '
                         .     'M04SORT,'
                         .     'M04KAISO,'
                         .     'F03SORT';
                break;

            default:
                break;
        }
        return $qsqlstr;
    }
}
