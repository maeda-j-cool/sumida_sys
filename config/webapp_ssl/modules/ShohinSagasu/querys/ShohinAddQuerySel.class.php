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
 * 商品登録QuerySelクラス
 *
 * @author  Katsushi Akagawa
 * @version Release:<1.0>
 */
class ShohinAddQuerySel extends DBConnectSel
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
            // お気に入り情報取得
            case '1':
                $qsqlstr  = "select ";
                $qsqlstr .= "F42SHOHINNO ";
                $qsqlstr .= "from ";
                $qsqlstr .= DB_SHOP_LIBRARY;
                $qsqlstr .= "F42OKINI ";
                $qsqlstr .= "where ";
                $qsqlstr .= "F42WEBKAIINNO = '" . $qrsarr['F42_WEBKAIIN_NO']. "' ";
                $qsqlstr .= "and ";
                $qsqlstr .= "F42DELFLG = '0' ";
                $qsqlstr .= "order by F42INSDATE ";
                break;

            default:
                break;
        }
        return $qsqlstr;
    }
}
