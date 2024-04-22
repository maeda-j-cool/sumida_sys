<?php
/**
 * ProjectName:スマートギフトサイト
 * Subsystem:ギフトカードを利用した商品交換モールサイト
 *
 * Copyright (c) TOPPAN SYSTEMSOLUTIONS LTD., all rights reserved.
 *
 * @package アドレス帳(Address)
 * 
 * $Id:$
 * 
 **/
 
/**
 * キーワード検索で商品を探す更新系Queryクラス
 *
 * PHP5.3.10
 *
 * @access  public
 * @author Yoshimi Kawai <y-kawai@tms.co.jp>
 * @created 2008/08/14
 * @version $Revision:$
 **/
class KeywordSagasuQueryUID extends DBConnectIUD {


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
    function Query($qrsarr,$qsqlno){
        //SQLを組み立てる変数
        $qsqlstr = '';

        switch ($qsqlno) {
            case '1':
                $qsqlstr  = "insert into ";
                $qsqlstr .= DB_SHOP_LIBRARY . "f27keyword ( ";
                
                $qsqlstr .= "f27srcdate, ";		// 検索日時
                $qsqlstr .= "f27keyword "; 		// 検索ワード
                
                $qsqlstr .=	") values ( ";
                
                $qsqlstr .= "'" . $qrsarr['f27srcdate'] . "',"; // 検索日時
                $qsqlstr .= "'" . $qrsarr['f27keyword'] . "')";	// 検索ワード
                
                break;
            default:
            
        }
//echo $qsqlstr;

        return $qsqlstr;
    }

}
?>
