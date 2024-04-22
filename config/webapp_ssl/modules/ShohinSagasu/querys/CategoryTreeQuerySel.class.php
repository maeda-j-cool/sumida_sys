<?php
/**
 * ProjectName:スマートギフトサイト
 * Subsystem:ギフトカードを利用した商品交換モールサイト
 *
 * Copyright (c) TOPPAN SYSTEMSOLUTIONS LTD., all rights reserved.
 *
 * @package 商品探す(ShohinSagasu)
 *
 * $Id:$
 *
 **/

/**
 * カテゴリツリーを表示するQueryクラス
 *
 * PHP5.3.10
 *
 * @access  public
 * @author TMSSHA-周定乾 <zhou@tms.co.jp>
 * @created 2005/09/28
 * @version $Revision: 1.1 $
 **/
class CategoryTreeQuerySel extends DBConnectSel {

    /**
     *
     * SQL構築処理
     *
     * Executeメソッドから呼び出されて、selsqlnoに対応するクエリーをストリングとして返す。
     *
     * @param qrsarr：クエリーパラメータ
     * @param qsqlno：SQL識別番号
     * @return qsqlstr：クエリーストリング
     *
     */
    function Query($qrsarr, $qsqlno)
    {
        //SQLを組み立てる変数
        $qsqlstr = '';
        //caseにSQLnoをいれて、$qsqlstrにSQL文が構成されるようにすること
        //$qrsarr['xxx']には呼び元画面名から渡されたパラメータが格納されている
        switch ($qsqlno) {
        case '1':
            //カテゴリ情報の取得
            $qsqlstr  = " select ";
            $qsqlstr .= " M04CATEGNO";	//カテゴリ番号
            $qsqlstr .= ",M04CNAME";	//カテゴリ名
            $qsqlstr .= ",M04UPCNO";	//上位カテゴリ番号
            $qsqlstr .= ",M04KAISO";	//カテゴリ階層
            $qsqlstr .= ",M04GROUP1";	//カテゴリグループKEY1
            $qsqlstr .= ",M04GROUP2";	//カテゴリグループKEY2
            $qsqlstr .= " from ";
            $qsqlstr .= DB_COMM_LIBRARY;
            $qsqlstr .= "M04CATEG";		//商品カテゴリマスタ
            $qsqlstr .= " where M04DELFLG = '0'";
            $qsqlstr .= " and ";
            $qsqlstr .= " M04KKAIFLG = '1'";
            $qsqlstr .= " and ";
            $qsqlstr .= " M04KAISO in(1,2,3)";
            $qsqlstr .= " order by M04KAISO,M04SORT";
            break;
        case '2':
            //コードマスタ情報の取得
            $qsqlstr  = " select ";
            $qsqlstr .= " M03KEY1";	//
            $qsqlstr .= ",M03KEY2";	//
            $qsqlstr .= " from ";
            $qsqlstr .= DB_COMM_LIBRARY . "M03CODE";	//コードマスタ
            $qsqlstr .= " where M03DELFLG = '0'";
            $qsqlstr .= " and ";
            $qsqlstr .= " M03KKAIFLG = '1'";
            $qsqlstr .= " and ";
            $qsqlstr .= " M03CHARA1 = '" . SHOP_ID . "'";
            $qsqlstr .= " and ";
            $qsqlstr .= " M03NRYAKU = '" . $qrsarr["M03NRYAKU"] . "'";
            break;
        case '3':
            //カテゴリ情報の取得
            $qsqlstr  = " select ";
            $qsqlstr .= " M04CATEGNO";	//カテゴリ番号
            $qsqlstr .= ",M04CNAME";	//カテゴリ名
            $qsqlstr .= ",M04UPCNO";	//上位カテゴリ番号
            $qsqlstr .= ",M04KAISO";	//カテゴリ階層
            $qsqlstr .= ",M04GROUP1";	//カテゴリグループKEY1
            $qsqlstr .= ",M04GROUP2";	//カテゴリグループKEY2
            $qsqlstr .= " from ";
            $qsqlstr .= DB_COMM_LIBRARY;
            $qsqlstr .= "M04CATEG";		//商品カテゴリマスタ
            $qsqlstr .= " where M04DELFLG = '0'";
            $qsqlstr .= " and ";
            $qsqlstr .= " M04KKAIFLG = '1'";
            $qsqlstr .= " and ";
            $qsqlstr .= " M04CATEGNO = '" . $qrsarr["M04CATEGNO"] . "'";
            break;
        case '4':
            //コードマスタ情報の取得
            $qsqlstr  = " select ";
            $qsqlstr .= " M03NRYAKU";
            $qsqlstr .= " from ";
            $qsqlstr .= DB_COMM_LIBRARY . "M03CODE";	//コードマスタ
            $qsqlstr .= " where M03DELFLG = '0'";
            $qsqlstr .= " and ";
            $qsqlstr .= " M03KKAIFLG = '1'";
            $qsqlstr .= " and ";
            $qsqlstr .= " M03CHARA1 = '" . SHOP_ID . "'";
            $qsqlstr .= " and ";
            $qsqlstr .= " M03KEY1 = '" . $qrsarr["M03KEY1"] . "'";
            $qsqlstr .= " and ";
            $qsqlstr .= " M03KEY2 = '" . $qrsarr["M03KEY2"] . "'";
            break;
        default:
        }
        return $qsqlstr;
    }
}
?>
