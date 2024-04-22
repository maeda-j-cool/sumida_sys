<?php
/**
 * ProjectName:スマートギフトサイト
 * Subsystem:ギフトカードを利用した商品交換モールサイト
 *
 * Copyright (c) TOPPAN SYSTEMSOLUTIONS LTD., all rights reserved.
 *
 * @package 共通(Common)
 *
 * charset = UTF-8 (without BOM)
 *
 */

/**
 * 採番テーブル更新系Queryクラス
 *
 * PHP5.3.10
 *
 * @access  public
 * @author  renewal <webtailor_tss@toppan.co.jp>
 * @created 2012/10/01
 *
 * 更新履歴：
 */
class SaibanQueryUID extends DBConnectIUD
{
    /**
     * SQL構築処理
     *
     * @param array  $qrsarr クエリーパラメータ
     * @param string $qsqlno SQL識別番号
     *
     * @return string クエリーストリング
     */
    function Query($qrsarr, $qsqlno)
    {
        $qsqlstr = '';
        switch ($qsqlno) {
            case '1':
                $qsqlstr = 'SELECT NEXTVAL FOR ' . $qrsarr['SEQ_NAME'] . ' AS SEQ FROM SYSIBM.SYSDUMMY1';
                break;

            default:
                break;
        }
        return $qsqlstr;
    }
}
