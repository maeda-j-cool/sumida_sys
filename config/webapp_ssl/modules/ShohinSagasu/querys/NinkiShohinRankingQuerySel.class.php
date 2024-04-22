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
 * 人気商品ランキング一覧を表示するQueryクラス
 *
 * PHP5.3.10
 *
 * @access  public
 * @author TMSSHA-周定乾 <zhou@tms.co.jp>
 * @created 2005/09/28
 * @version $Revision: 1.1 $
 **/
class NinkiShohinRankingQuerySel extends DBConnectSel {

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
            //人気商品情報の取得
            $qsqlstr  = " select ";
            $qsqlstr .= " F12JNAME";	//順位名
            $qsqlstr .= ",M02SHOHNNO";	//商品番号
            $qsqlstr .= ",M02SHOHNCD";	//商品コード
            $qsqlstr .= ",M02SNAME";	//商品名
            $qsqlstr .= ",M02BRAND";	//ブランド名
            $qsqlstr .= ",M02NINNAME";	//商品任意
            $qsqlstr .= ",M02NINKBN";	//商品任意表示区分(0:商品名の後ろに表示　1:商品名の前に表示)
            $qsqlstr .= ",M02KAKAKU ";	//標準価格
            $qsqlstr .= ",M02TAX";		//消費税額
            $qsqlstr .= " from ";
            $qsqlstr .= DB_SHOP_LIBRARY;
            $qsqlstr .= "F12NINKISH,";	//人気商品ランキング設定
            $qsqlstr .= DB_COMM_LIBRARY;
            $qsqlstr .= "M02SHOHIN";	//商品マスタ
            $qsqlstr .= " where ";
            $qsqlstr .= " F12DELFLG = '0' ";
            $qsqlstr .= " and ";
            $qsqlstr .= " F12SHOHNCD = M02SHOHNCD";
            $qsqlstr .= " and ";
            $qsqlstr .= " M02DELFLG = '0'";
            $qsqlstr .= " and ";
            $qsqlstr .= " M02KKAIFLG = '1'";
            $qsqlstr .= " and ";
            $qsqlstr .= " (M02USESITE = '00' or M02USESITE = '" . SHOP_ID . "')";			
            $qsqlstr .= " order by F12SORT";
            break;
        case '2':
            //人気商品情報の取得
            $qsqlstr  = " select ";
            $qsqlstr .= " M02SHOHNNO";	//商品番号
            $qsqlstr .= ",M02SNAME";	//商品名
            $qsqlstr .= ",M02BRAND";	//ブランド名
            $qsqlstr .= ",M02NINNAME";	//商品任意
            $qsqlstr .= ",M02NINKBN";	//商品任意表示区分(0:商品名の後ろに表示　1:商品名の前に表示)
            $qsqlstr .= ",M02KAKAKU ";	//標準価格
            $qsqlstr .= ",M02TAX";		//消費税額
            $qsqlstr .= " from ";
            $qsqlstr .= DB_COMM_LIBRARY;
            $qsqlstr .= "M02SHOHIN";	//商品マスタ
            $qsqlstr .= " where ";
            $qsqlstr .= " M02SHOHNCD = '" . $qrsarr['M02SHOHNCD'] . "'";
            $qsqlstr .= " and ";
            $qsqlstr .= " M02DELFLG = '0'";
            $qsqlstr .= " and ";
            $qsqlstr .= " M02KKAIFLG = '1'";
            $qsqlstr .= " and ";
            $qsqlstr .= " (M02USESITE = '00' or M02USESITE = '" . SHOP_ID . "')";			
            break;
        default:
        }
        return $qsqlstr;
    }
}
?>
