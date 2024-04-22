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
 * キーワード検索で商品を探すQueryクラス
 *
 * @author  Keisuke Yamamoto
 * @version Release:<1.0>
 */
class KeywordSagasuQuerySel extends DBConnectSel
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
                // 商品情報の取得
                $defaultKenshuGroup = WT_DEFAULT_KENSHU_GROUP;
                if ($qrsarr['F22KENGROUP'] === $defaultKenshuGroup) {
                    $kenshuGroupWhere = " F22KENGROUP = '{$qrsarr['F22KENGROUP']}'";
                } else {
                    $kenshuGroupWhere = " F22KENGROUP IN ('{$qrsarr['F22KENGROUP']}', '{$defaultKenshuGroup}')";
                }
                //#1602 start
                $qrsarr['keywordHankaku'] = $qrsarr['keywordReal'];
                $qrsarr['keywordHankaku'] = mb_convert_kana($qrsarr['keywordHankaku'], 'aks', WT_SRC_CODE_CONV);
                //#1602 end
                $qsqlstr  = " select shohin.M02SHOHNNO"; // 商品番号
                $qsqlstr .= " , shohin.M02VPOINT"; // ポイント
                $qsqlstr .= " , shohin.M02SGROUP"; // 関連商品グルーピング
                $qsqlstr .= " , shohin.M02SGROUPSORT"; // 関連商品表示順
                $qsqlstr .= " , shohin.M02SHOHNCD"; // 商品コード
                $qsqlstr .= " , shohin.M02SNAME";   // 商品名
                $qsqlstr .= " , shohin.M02BRAND";   // ブランド名
                $qsqlstr .= " from (";
                $qsqlstr .= " select M02SHOHNNO as s1, M02SGROUP as s2, M02SGROUPSORT as s3";
                $qsqlstr .= " from ";
                $qsqlstr .= DB_COMM_LIBRARY;
                $qsqlstr .= "M02SHOHIN as sh";
                $qsqlstr .= " inner join ";
                $qsqlstr .= DB_COMM_LIBRARY;
                $qsqlstr .= "F22SHOKEN as ken";
                $qsqlstr .= " on sh.M02SHOHNNO = ken.F22SHOHNNO";
                $qsqlstr .= " and";
                $qsqlstr .= " sh.M02ID = ken.F22ID";
                $qsqlstr .= " where";
                $qsqlstr .= " M02ID = '" . SHOP_ID . "'";
                $qsqlstr .= " and ";
                $qsqlstr .= " (M02SGROUP is null";
                $qsqlstr .= " or";
                $qsqlstr .= " M02SGROUP = '')";
                $qsqlstr .= " and ";
                $qsqlstr .= " M02DELFLG = '0' ";
                $qsqlstr .= " and ";
                $qsqlstr .= " M02KKAIFLG = '1'";
                $qsqlstr .= " and ";
                $qsqlstr .= " M02HBKEITA = '0'";
                $qsqlstr .= " and ";
                $qsqlstr .= " ( ";
                $qsqlstr .= "(M02BRAND   like '%" . $qrsarr['keyword'] . "%' or M02BRAND   like '%" . $qrsarr['keywordReal'] . "%' or M02BRAND   like '%" . $qrsarr['keywordHankaku'] . "%')";
                $qsqlstr .= " or ";
                $qsqlstr .= "(M02SNAME   like '%" . $qrsarr['keyword'] . "%' or M02SNAME   like '%" . $qrsarr['keywordReal'] . "%' or M02SNAME   like '%" . $qrsarr['keywordHankaku'] . "%')";
                $qsqlstr .= " or ";
                $qsqlstr .= "(M02SNAMEK  like '%" . $qrsarr['keyword'] . "%' or M02SNAMEK  like '%" . $qrsarr['keywordReal'] . "%' or M02SNAMEK   like '%" . $qrsarr['keywordHankaku'] . "%')";
                $qsqlstr .= " or ";
                $qsqlstr .= "(M02CATCH   like '%" . $qrsarr['keyword'] . "%' or M02CATCH   like '%" . $qrsarr['keywordReal'] . "%' or M02CATCH   like '%" . $qrsarr['keywordHankaku'] . "%')";
                $qsqlstr .= " or ";
                $qsqlstr .= "(M02SETSU1  like '%" . $qrsarr['keyword'] . "%' or M02SETSU1  like '%" . $qrsarr['keywordReal'] . "%' or M02SETSU1   like '%" . $qrsarr['keywordHankaku'] . "%')";
                $qsqlstr .= " or ";
                $qsqlstr .= "(M02KEYWORD like '%" . $qrsarr['keyword'] . "%' or M02KEYWORD like '%" . $qrsarr['keywordReal'] . "%' or M02KEYWORD   like '%" . $qrsarr['keywordHankaku'] . "%')";
                $qsqlstr .= " ) ";
                $qsqlstr .= " and";
                $qsqlstr .= " F22DELFLG = '0'";
                $qsqlstr .= " and";
                $qsqlstr .= $kenshuGroupWhere; //$qsqlstr .= " F22KENGROUP = '" . $qrsarr['F22KENGROUP'] . "'";
                $qsqlstr .= " union all ";
                $qsqlstr .= " select min(shohinNo.M02SHOHNNO) as s1, shohinNo.M02SGROUP as s2, shohinNo.M02SGROUPSORT as s3";
                $qsqlstr .= " from (";
                $qsqlstr .= " select M02SGROUP, min(M02SGROUPSORT) as M02SGROUPSORT";
                $qsqlstr .= " from ";
                $qsqlstr .= DB_COMM_LIBRARY;
                $qsqlstr .= "M02SHOHIN as sh";
                $qsqlstr .= " inner join ";
                $qsqlstr .= DB_COMM_LIBRARY;
                $qsqlstr .= "F22SHOKEN as ken";
                $qsqlstr .= " on sh.M02SHOHNNO = ken.F22SHOHNNO";
                $qsqlstr .= " and";
                $qsqlstr .= " sh.M02ID = ken.F22ID";
                $qsqlstr .= " where";
                $qsqlstr .= " M02ID = '" . SHOP_ID . "'";
                $qsqlstr .= " and ";
                $qsqlstr .= " (M02SGROUP is not null";
                $qsqlstr .= " and";
                $qsqlstr .= " M02SGROUP != '')";
                $qsqlstr .= " and ";
                $qsqlstr .= " M02DELFLG = '0' ";
                $qsqlstr .= " and ";
                $qsqlstr .= " M02KKAIFLG = '1'";
                $qsqlstr .= " and ";
                $qsqlstr .= " M02HBKEITA = '0'";
                $qsqlstr .= " and ";
                $qsqlstr .= " ( ";
                $qsqlstr .= "(M02BRAND   like '%" . $qrsarr['keyword'] . "%' or M02BRAND   like '%" . $qrsarr['keywordReal'] . "%' or M02BRAND   like '%" . $qrsarr['keywordHankaku'] . "%')";
                $qsqlstr .= " or ";
                $qsqlstr .= "(M02SNAME   like '%" . $qrsarr['keyword'] . "%' or M02SNAME   like '%" . $qrsarr['keywordReal'] . "%' or M02SNAME   like '%" . $qrsarr['keywordHankaku'] . "%')";
                $qsqlstr .= " or ";
                $qsqlstr .= "(M02SNAMEK  like '%" . $qrsarr['keyword'] . "%' or M02SNAMEK  like '%" . $qrsarr['keywordReal'] . "%' or M02SNAMEK   like '%" . $qrsarr['keywordHankaku'] . "%')";
                $qsqlstr .= " or ";
                $qsqlstr .= "(M02CATCH   like '%" . $qrsarr['keyword'] . "%' or M02CATCH   like '%" . $qrsarr['keywordReal'] . "%' or M02CATCH   like '%" . $qrsarr['keywordHankaku'] . "%')";
                $qsqlstr .= " or ";
                $qsqlstr .= "(M02SETSU1  like '%" . $qrsarr['keyword'] . "%' or M02SETSU1  like '%" . $qrsarr['keywordReal'] . "%' or M02SETSU1   like '%" . $qrsarr['keywordHankaku'] . "%')";
                $qsqlstr .= " or ";
                $qsqlstr .= "(M02KEYWORD like '%" . $qrsarr['keyword'] . "%' or M02KEYWORD like '%" . $qrsarr['keywordReal'] . "%' or M02KEYWORD   like '%" . $qrsarr['keywordHankaku'] . "%')";
                $qsqlstr .= " ) ";
                $qsqlstr .= " and";
                $qsqlstr .= " F22DELFLG = '0'";
                $qsqlstr .= " and";
                $qsqlstr .= $kenshuGroupWhere; //$qsqlstr .= " F22KENGROUP = '" . $qrsarr['F22KENGROUP'] . "'";
                $qsqlstr .= " group by M02SGROUP";
                $qsqlstr .= " ) as sort";
                $qsqlstr .= " left join ";
                $qsqlstr .= DB_COMM_LIBRARY;
                $qsqlstr .= "M02SHOHIN as shohinNo";
                $qsqlstr .= " on sort.M02SGROUP = shohinNo.M02SGROUP";
                $qsqlstr .= " and";
                $qsqlstr .= " sort.M02SGROUPSORT = shohinNo.M02SGROUPSORT";
                $qsqlstr .= " group by shohinNo.M02SGROUP, shohinNo.M02SGROUPSORT";
                $qsqlstr .= " ) as shiborikomi";
                $qsqlstr .= " left join ";
                $qsqlstr .= DB_COMM_LIBRARY;
                $qsqlstr .= "M02SHOHIN as shohin";
                $qsqlstr .= " on shiborikomi.s1 = shohin.M02SHOHNNO";
                $qsqlstr .= " where";
                $qsqlstr .= " shohin.M02ID = '" . SHOP_ID . "'";
                if ($qrsarr['sort'] && $qrsarr['order']) {
                    $qsqlstr .= " order by shohin." . $qrsarr['sort'] . (strtolower(trim($qrsarr['order'])) == 'desc' ? ' desc' : ' asc') . " , shohin.M02SHOHNNO asc";
                } else {
                    $qsqlstr .= " order by shohin.M02VPOINT asc, shohin.M02SHOHNNO asc";
                }
                break;
            case '2';
                $qsqlstr  = " select M02SHOHNNO"; // 商品番号
                $qsqlstr .= " , M02VPOINT"; // ポイント
                $qsqlstr .= " , M02SGROUP"; // 関連商品グルーピング
                $qsqlstr .= " , M02SGROUPSORT"; //関連商品表示順
                $qsqlstr .= " , M02SHOHNCD"; // 商品コード
                $qsqlstr .= " , M02SNAME";   // 商品名
                $qsqlstr .= " , M02BRAND";   // ブランド名
                $qsqlstr .= " from ";
                $qsqlstr .= DB_COMM_LIBRARY;
                $qsqlstr .= "M02SHOHIN as sh";
                $qsqlstr .= " inner join ";
                $qsqlstr .= DB_COMM_LIBRARY;
                $qsqlstr .= "F22SHOKEN as ken";
                $qsqlstr .= " on sh.M02SHOHNNO = ken.F22SHOHNNO";
                $qsqlstr .= " and";
                $qsqlstr .= " sh.M02ID = ken.F22ID";
                $qsqlstr .= " where";
                $qsqlstr .= " M02ID = '" . SHOP_ID . "'";
                $qsqlstr .= " and ";
                $qsqlstr .= " M02SGROUP IN ('" . implode("','", $qrsarr['groupNos']) . "')";
                $qsqlstr .= " and ";
                $qsqlstr .= " M02SHOHNNO NOT IN ('" . implode("','", $qrsarr['shohinNos']) . "')";
                $qsqlstr .= " and ";
                $qsqlstr .= " M02DELFLG = '0' ";
                $qsqlstr .= " and ";
                $qsqlstr .= " M02KKAIFLG = '1'";
                $qsqlstr .= " and ";
                $qsqlstr .= " M02HBKEITA = '0' ";
                $qsqlstr .= " and";
                $qsqlstr .= " F22DELFLG = '0'";
                $qsqlstr .= " and";
                $defaultKenshuGroup = WT_DEFAULT_KENSHU_GROUP;
                if ($qrsarr['F22KENGROUP'] === $defaultKenshuGroup) {
                    $qsqlstr .= " F22KENGROUP = '{$qrsarr['F22KENGROUP']}'";
                } else {
                    $qsqlstr .= " F22KENGROUP IN ('{$qrsarr['F22KENGROUP']}', '{$defaultKenshuGroup}')";
                }
                $qsqlstr .= " order by M02SGROUP asc, M02SGROUPSORT asc, M02SHOHNNO asc";
                break;
            default:
                break;
        }
        return $qsqlstr;
    }
}
