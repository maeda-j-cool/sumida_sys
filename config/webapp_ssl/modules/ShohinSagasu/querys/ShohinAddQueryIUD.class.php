<?php
/**
 * ProjectName:スマートギフトサイト
 * Subsystem:ギフトカードを利用した商品交換モールサイト
 *
 * PHP versions 5.3.1
 *
 * @package webapp
 */

/**
 * 商品登録QueryIUDクラス
 *
 * @author  Katsushi Akagawa
 * @version Release:<1.0>
 */
class ShohinAddQueryIUD extends DBConnectIUD
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
    function Query($qrsarr, $qsqlno)
    {
        // SQLを組み立てる変数
        $qsqlstr = '';
        switch ($qsqlno) {
            case '1':
                $qsqlstr  = "insert into ";
                $qsqlstr .= DB_SHOP_LIBRARY."F42OKINI (f42delflg,f42insid,f42insprogram,f42insdate,f42updid,f42updprogram,f42upddate,f42okiniirino,f42webkaiinno,f42shohinno) values (";
                $qsqlstr .= "'" . $qrsarr['F42_DEL_FLG'] . "', ";
                $qsqlstr .= "'" . $qrsarr['F42_INS_ID'] . "', ";
                $qsqlstr .= "'" . $qrsarr['F42_INS_PROGRAM'] . "', ";
                $qsqlstr .= "'" . $qrsarr['F42_INS_DATE'] . "', ";
                $qsqlstr .= "'" . $qrsarr['F42_UPD_ID'] . "', ";
                $qsqlstr .= "'" . $qrsarr['F42_UPD_PROGRAM'] . "', ";
                $qsqlstr .= "'" . $qrsarr['F42_UPD_DATE'] . "', ";
                $qsqlstr .= (is_null($qrsarr['F42_OKINIIRI_NO']) || $qrsarr['F42_OKINIIRI_NO'] === 'null' ? $qrsarr['F42_OKINIIRI_NO'] : "'" . $qrsarr['F42_OKINIIRI_NO'] . "'") . ", ";
                $qsqlstr .= (is_null($qrsarr['F42_WEBKAIIN_NO']) || $qrsarr['F42_WEBKAIIN_NO'] === 'null' ? $qrsarr['F42_WEBKAIIN_NO'] : "'" . $qrsarr['F42_WEBKAIIN_NO'] . "'") . ", ";
                $qsqlstr .= (is_null($qrsarr['F42_SHOHIN_NO']) || $qrsarr['F42_SHOHIN_NO'] === 'null' ? $qrsarr['F42_SHOHIN_NO'] : "'" . $qrsarr['F42_SHOHIN_NO'] . "'");
                $qsqlstr .= ")";
                break;
            default:
                break;
        }
        return $qsqlstr;
    }
}
