<?php
class OkiniiriIchiranQuerySel extends DBConnectSel
{
    /**
     * {@inheritdoc}
     */
    function Query($bindParams, $sqlNo)
    {
        $this->_conn->SetFetchMode(ADODB_FETCH_ASSOC);
        $sql = '';
        switch ($sqlNo) {
            case '1':
                $sql  = 'SELECT '
                      .     'M02SHOHNCD,' // 商品コード
                      .     'M02SHOHNNO,' // 商品番号
                      .     'M02SNAME,'   // 商品名
                      .     'M02BRAND,'   // ブランド名
                      .     'M02VPOINT,'  // バリューポイント
                      .     'M02SDATE,'   // 販売開始日
                      .     'M02EDATE,'   // 販売終了日
                      .     'M02NOKORI,'  // 残り数
                      .     'M02KISEFLG,' // 季節商品フラグ
                      .     'M02NOKI'     // 標準納期
                      . ' FROM '
                      .     DB_SHOP_LIBRARY . 'F42OKINI'
                      .         ' INNER JOIN ' . DB_SHOP_LIBRARY . 'M02SHOHIN'
                      .             ' ON '
                      .                 'M02SHOHNNO = F42SHOHINNO'
                      .             ' AND '
                      .                 "M02DELFLG = '0'"
                      .             ' AND '
                      .                 "M02KKAIFLG = '1'"
                      . ' WHERE '
                      .     "F42GCNO = '{$bindParams['GCNO']}'"
                      . ' AND '
                      .     "F42ID = '{$bindParams['SHOP_ID']}'"
                      . ' ORDER BY '
                      .     'F42INSDATE DESC,'
                      .     'M02SHOHNNO ASC';
                break;

            default :
                break;
        }
        return $sql;
    }
}
