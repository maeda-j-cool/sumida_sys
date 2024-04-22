<?php
/**
 * ProjectName:スマートギフトサイト
 * Subsystem:ギフトカードを利用した商品交換モールサイト
 *
 * PHP versions 5.3.1
 *
 * @package webapp_ssl
 */

/**
 * ショップ情報参照系Queryクラス
 *
 * @author  Katsushi Akagawa
 * @version Release:<1.0>
 */
class ShopInfoQuerySel extends DBConnectSel
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
        //SQLを組み立てる変数
        $qsqlstr = '';
        //caseにSQLnoをいれて、$qsqlstrにSQL文が構成されるようにすること
        //$qrsarr['xxx']には呼び元画面名から渡されたパラメータが格納されている
        switch ($qsqlno) {
            case '1':
                $qsqlstr  = "select";

                $qsqlstr  .= " S01SLCCNT";
                $qsqlstr  .= ",S01SLKCNT";
                $qsqlstr  .= ",S01SHCNT";
                $qsqlstr  .= ",S01SMBNUM";
                $qsqlstr  .= ",S01SHSNUM";
                $qsqlstr  .= ",S01SLOCNT";
                $qsqlstr  .= ",S01OSNAME";
                $qsqlstr  .= ",S01OSNKBN";
                $qsqlstr  .= ",S01OCVFLG";
                $qsqlstr  .= ",S01OCNVFLG";
                $qsqlstr  .= ",S01OSVFLG";
                $qsqlstr  .= ",S01OSNVFLG";
                $qsqlstr  .= ",S01CATKBN";
                $qsqlstr  .= ",S01OMETKBN";
                $qsqlstr  .= ",S01OMETKEY";
                $qsqlstr  .= ",S01OMETDES";
                $qsqlstr  .= ",S01OTSPLIT";
                $qsqlstr  .= ",S01OHSPLIT";
                $qsqlstr  .= " from ";

                $qsqlstr  .= DB_COMM_LIBRARY . "s01shop ";
                $qsqlstr  .= " where s01delflg = '0' ";
                $qsqlstr  .= " and S01ID = '" . $qrsarr['s01id'] . "'";

                break;

            default:
                break;
        }
        return $qsqlstr;
    }
}
