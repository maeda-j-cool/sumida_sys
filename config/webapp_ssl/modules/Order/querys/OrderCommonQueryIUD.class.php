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
 * 注文QueryIUDクラス
 *
 * @author  Yuki Tanaka
 * @version Release:<1.0>
 */
class OrderCommonQueryIUD extends DBConnectIUD
{
    /**
     * カラム種別
     */
    const TYPE_STR  = 1;
    const TYPE_INT  = 2;
    const TYPE_DATE = 3;

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
            case 'insert-f06':
                $columns = array(
                    'F06DELFLG'      => self::TYPE_STR,
                    'F06INSID'       => self::TYPE_STR,
                    'F06INSPGM'      => self::TYPE_STR,
                    'F06INSDATE'     => self::TYPE_DATE,
                    'F06UPID'        => self::TYPE_STR,
                    'F06UPPGM'       => self::TYPE_STR,
                    'F06UPDATE'      => self::TYPE_DATE,
                    'F06WJUCNO'      => self::TYPE_INT,
                    'F06JOBNO'       => self::TYPE_STR,
                    'F06GCNO'        => self::TYPE_STR,
                    'F06JUCHUBI'     => self::TYPE_DATE,
                    'F06JUCHKBN'     => self::TYPE_STR,
                    'F06ID'          => self::TYPE_STR,
                    'F06CHANNEL'     => self::TYPE_STR,
                    'F06SHORFLG'     => self::TYPE_STR,
                    'F06CCURIAGEFLG' => self::TYPE_STR,
                    'F06CTRLNO'      => self::TYPE_STR,
                    'F06CCCTRLNO'    => self::TYPE_STR,
                    'F06COPX'        => self::TYPE_STR,
                    'F06CPNM'        => self::TYPE_STR,
                    'F06CPKN'        => self::TYPE_STR,
                    'F06CPN2'        => self::TYPE_STR,
                    'F06CPN3'        => self::TYPE_STR,
                    'F06SEI'         => self::TYPE_STR,
                    'F06MEI'         => self::TYPE_STR,
                    'F06SEIKN'       => self::TYPE_STR,
                    'F06MEIKN'       => self::TYPE_STR,
                    'F06ZIP1'        => self::TYPE_STR,
                    'F06ZIP2'        => self::TYPE_STR,
                    'F06ADD1'        => self::TYPE_STR,
                    'F06ADD2'        => self::TYPE_STR,
                    'F06ADD3'        => self::TYPE_STR,
                    'F06TEL11'       => self::TYPE_STR,
                    'F06TEL12'       => self::TYPE_STR,
                    'F06TEL13'       => self::TYPE_STR,
                    'F06EMAILPC'     => self::TYPE_STR,
                    'F06NEWSDMFLG'   => self::TYPE_STR,
                    'F06TUSNRAN'     => self::TYPE_STR,
                    'F06NNUSFLG'     => self::TYPE_STR,
                    'F06NSEI'        => self::TYPE_STR,
                    'F06NMEI'        => self::TYPE_STR,
                    'F06NSEIKN'      => self::TYPE_STR,
                    'F06NMEIKN'      => self::TYPE_STR,
                    'F06NZIP1'       => self::TYPE_STR,
                    'F06NZIP2'       => self::TYPE_STR,
                    'F06NADD1'       => self::TYPE_STR,
                    'F06NADD2'       => self::TYPE_STR,
                    'F06NADD3'       => self::TYPE_STR,
                    'F06NTEL11'      => self::TYPE_STR,
                    'F06NTEL12'      => self::TYPE_STR,
                    'F06NTEL13'      => self::TYPE_STR,
                    'F06CCKINGAKU'   => self::TYPE_INT,
                    'F06CCTAX'       => self::TYPE_INT,
                    'F06CCKINGAKZ'   => self::TYPE_INT,
                    'F06USEFLG'      => self::TYPE_STR,
                    'F06MEIGI'       => self::TYPE_STR,
                    'F06KENGROUP'    => self::TYPE_STR,
                    'F06TENPO'       => self::TYPE_STR,
                );
                $insertParams = $this->makeInsertParams($columns, $qrsarr);
                $qsqlstr = 'INSERT INTO ' . DB_COMM_LIBRARY . 'F06JUCHU'
                         . ' (' . implode(',', array_keys($insertParams)) . ')'
                         . ' VALUES'
                         . ' (' . implode(',', array_values($insertParams)) . ')';
                break;

            case 'insert-f07':
                $columns = array(
                    'F07DELFLG'  => self::TYPE_STR,
                    'F07INSID'   => self::TYPE_STR,
                    'F07INSPGM'  => self::TYPE_STR,
                    'F07INSDATE' => self::TYPE_DATE,
                    'F07UPID'    => self::TYPE_STR,
                    'F07UPPGM'   => self::TYPE_STR,
                    'F07UPDATE'  => self::TYPE_DATE,
                    'F07ID'      => self::TYPE_STR,
                    'F07WJUCNO'  => self::TYPE_INT,
                    'F07HAISONO' => self::TYPE_INT,
                    'F07OKURKBN' => self::TYPE_STR,
                    'F07COPX'    => self::TYPE_STR,
                    'F07CPNM'    => self::TYPE_STR,
                    'F07CPKN'    => self::TYPE_STR,
                    'F07CPN2'    => self::TYPE_STR,
                    'F07CPN3'    => self::TYPE_STR,
                    'F07SEI'     => self::TYPE_STR,
                    'F07MEI'     => self::TYPE_STR,
                    'F07SEIKANA' => self::TYPE_STR,
                    'F07MEIKANA' => self::TYPE_STR,
                    'F07ZIP1'    => self::TYPE_STR,
                    'F07ZIP2'    => self::TYPE_STR,
                    'F07ADD1'    => self::TYPE_STR,
                    'F07ADD2'    => self::TYPE_STR,
                    'F07ADD3'    => self::TYPE_STR,
                    'F07TEL11'   => self::TYPE_STR,
                    'F07TEL12'   => self::TYPE_STR,
                    'F07TEL13'   => self::TYPE_STR,
                );
                $insertParams = $this->makeInsertParams($columns, $qrsarr);
                $qsqlstr = 'INSERT INTO ' . DB_COMM_LIBRARY . 'F07JUCHUHS'
                         . ' (' . implode(',', array_keys($insertParams)) . ')'
                         . ' VALUES'
                         . ' (' . implode(',', array_values($insertParams)) . ')';
                break;

            case 'insert-f08':
                $columns = array(
                    'F08DELFLG'   => self::TYPE_STR,
                    'F08INSID'    => self::TYPE_STR,
                    'F08INSPGM'   => self::TYPE_STR,
                    'F08INSDATE'  => self::TYPE_DATE,
                    'F08UPID'     => self::TYPE_STR,
                    'F08UPPGM'    => self::TYPE_STR,
                    'F08UPDATE'   => self::TYPE_DATE,
                    'F08ID'       => self::TYPE_STR,
                    'F08WJUCNO'   => self::TYPE_INT,
                    'F08HAISONO'  => self::TYPE_INT,
                    'F08RENBAN'   => self::TYPE_INT,
                    'F08SHOHNNO'  => self::TYPE_INT,
                    'F08SHOHNCD'  => self::TYPE_STR,
                    'F08SNAME'    => self::TYPE_STR,
                    'F08SNAMEK'   => self::TYPE_STR,
                    'F08SHOTYPE'  => self::TYPE_STR,
                    'F08KIBOBI'   => self::TYPE_DATE,
                    'F08HSKETAI'  => self::TYPE_STR,
                    'F08HAIMTCD'  => self::TYPE_STR,
                    'F08VPOINT'   => self::TYPE_INT,
                    'F08SURYO'    => self::TYPE_INT,
                    'F08USEPOINT' => self::TYPE_INT,
                    'F08GCNO'     => self::TYPE_STR,
                    'F08GCARDNO'  => self::TYPE_STR,
                    'F08KAKAKU'   => self::TYPE_INT,
                    'F08TAX'      => self::TYPE_INT,
                    'F08KAKAKUZ'  => self::TYPE_INT,
                );
                $insertParams = $this->makeInsertParams($columns, $qrsarr);
                $qsqlstr = 'INSERT INTO ' . DB_COMM_LIBRARY . 'F08JUCHUME'
                         . ' (' . implode(',', array_keys($insertParams)) . ')'
                         . ' VALUES'
                         . ' (' . implode(',', array_values($insertParams)) . ')';
                break;

            case 'insert-f08dgc':
                $columns = array(
                    'F08WJUCNO'      => self::TYPE_INT,
                    'F08HAISONO'     => self::TYPE_INT,
                    'F08RENBAN'      => self::TYPE_INT,
                    'F08DGPUBLISHER' => self::TYPE_STR,
                    'F08SLIPNO'      => self::TYPE_STR,
                    'F08DGORDERNO'   => self::TYPE_STR,
                    'F08AROUNDINFO'  => self::TYPE_STR,
                    'F08ISSUERCD'    => self::TYPE_STR,
                    'F08DESIGNCD'    => self::TYPE_STR,
                    'F08CARDNO'      => self::TYPE_STR,
                    'F08INQUIRYCD'   => self::TYPE_STR,
                    'F08PIN'         => self::TYPE_STR,
                    'F08CERTIFYCODE' => self::TYPE_STR,
                    'F08DGCD'        => self::TYPE_STR,
                    'F08BARCODEURL'  => self::TYPE_STR,
                    'F08EXCHANGEURL' => self::TYPE_STR,
                    'F08BALANCE'     => self::TYPE_INT,
                    'F08CAMPAIGN'    => self::TYPE_INT,
                    'F08EXPRIREDATE' => self::TYPE_DATE,
                    'F08GETDATE'     => self::TYPE_DATE,
                );
                $insertParams = $this->makeInsertParams($columns, $qrsarr);
                $qsqlstr = 'INSERT' . ' INTO ' . DB_COMM_LIBRARY . 'F08DGC'
                    . ' (' . implode(',', array_keys($insertParams)) . ')'
                    . ' VALUES'
                    . ' (' . implode(',', array_values($insertParams)) . ')';
                break;

            case 'insert-f17':
                $columns = array(
                    'F17DELFLG'  => self::TYPE_STR,
                    'F17INSID'   => self::TYPE_STR,
                    'F17INSPGM'  => self::TYPE_STR,
                    'F17INSDATE' => self::TYPE_DATE,
                    'F17UPID'    => self::TYPE_STR,
                    'F17UPPGM'   => self::TYPE_STR,
                    'F17UPDATE'  => self::TYPE_DATE,
                    'F17ID'      => self::TYPE_STR,
                    'F17WJUCNO'  => self::TYPE_INT,
                    'F17HAISONO' => self::TYPE_INT,
                    'F17RENBAN'  => self::TYPE_INT,
                    'F17HOSONO'  => self::TYPE_INT,
                    'F17NKBN'    => self::TYPE_STR,
                    'F17NUKBN'   => self::TYPE_STR,
                    'F17NUHOKA'  => self::TYPE_STR,
                    'F17NSMEI'   => self::TYPE_STR,
                    'F17NSMEIK'  => self::TYPE_STR,
                    'F17NBIKO'   => self::TYPE_STR,
                    'F17CKBN'    => self::TYPE_STR,
                    'F17CSURYO'  => self::TYPE_INT,
                );
                $insertParams = $this->makeInsertParams($columns, $qrsarr);
                $qsqlstr = 'INSERT INTO ' . DB_COMM_LIBRARY . 'F17JUNOSHI'
                         . ' (' . implode(',', array_keys($insertParams)) . ')'
                         . ' VALUES'
                         . ' (' . implode(',', array_values($insertParams)) . ')';
                break;

            case 'insert-f87':
                // GMOクレジットカード決済状況：追加
                $columns = array(
                    'F87DELFLG'     => self::TYPE_STR,
                    'F87INSID'      => self::TYPE_STR,
                    'F87INSPGM'     => self::TYPE_STR,
                    'F87INSDATE'    => self::TYPE_DATE,
                    'F87UPID'       => self::TYPE_STR,
                    'F87UPPGM'      => self::TYPE_STR,
                    'F87UPDATE'     => self::TYPE_DATE,
                    'F87WJUCNO'     => self::TYPE_INT,
                    'F87ACCESSID'   => self::TYPE_STR,
                    'F87ACCESSPASS' => self::TYPE_STR,
                    'F87PROCESSID'  => self::TYPE_STR,
                    'F87TRANID'     => self::TYPE_STR,
                    'F87ORDERID'    => self::TYPE_STR,
                    'F87FORWARD'    => self::TYPE_STR,
                    'F87APPROVE'    => self::TYPE_STR,
                    'F87AUTHDATE'   => self::TYPE_DATE,
                    'F87SALESDATE'  => self::TYPE_DATE,
                    'F87ERR'        => self::TYPE_STR,
                    'F87YUKOFLG'    => self::TYPE_STR,
                );
                $insertParams = $this->makeInsertParams($columns, $qrsarr);
                if ($insertParams['F87AUTHDATE'] == 'null') {
                    $insertParams['F87AUTHDATE'] = 'CAST(null AS timestamp)'; // for DB2
                }
                if ($insertParams['F87SALESDATE'] == 'null') {
                    $insertParams['F87SALESDATE'] = 'CAST(null AS timestamp)'; // for DB2
                }
                $insertParams['F87RENBAN'] = 'CASE WHEN MAX(F87RENBAN) IS NULL THEN 1 ELSE MAX(F87RENBAN)+1 END AS RENBAN';
                $qsqlstr = 'INSERT INTO '
                         .     DB_SHOP_LIBRARY . 'F87GMOCRED'
                         .     '(' . implode(',', array_keys($insertParams)) . ')'
                         . ' SELECT '
                         .     implode(',', array_values($insertParams))
                         . ' FROM '
                         .     DB_SHOP_LIBRARY . 'F87GMOCRED'
                         . ' WHERE '
                         .     "F87WJUCNO = '{$qrsarr['F87WJUCNO']}'";
                break;

            case 'zaiko-check':
                $qsqlstr = 'SELECT '
                         .     'M02GENTEI,'
                         .     'M02NOKORI'
                         . ' FROM '
                         .     DB_COMM_LIBRARY . 'M02SHOHIN'
                         . ' WHERE '
                         .     "M02SHOHNNO = {$qrsarr['M02SHOHNNO']}"
                         . ' FOR UPDATE';
                break;

            case 'zaiko-update':
                $op = '-';
                if ($qrsarr['NNN'] < 0) {
                    $qrsarr['NNN'] = abs($qrsarr['NNN']);
                    $op = '+';
                }
                $qsqlstr = 'UPDATE ' . DB_COMM_LIBRARY . 'M02SHOHIN'
                         . ' SET '
                         .     "M02UPID = '{$qrsarr['M02UPID']}',"
                         .     "M02UPPGM = '{$qrsarr['M02UPPGM']}',"
                         .     "M02UPDATE = '{$qrsarr['M02UPDATE']}',"
                         .     "M02NOKORI = M02NOKORI {$op} {$qrsarr['NNN']}"
                         . ' WHERE '
                         .     "M02SHOHNNO = {$qrsarr['M02SHOHNNO']}";
                break;

            //case 'm01-use-point':
            //    $qsqlstr = 'UPDATE ' . DB_COMM_LIBRARY . 'M01WKAIIN'
            //        . ' SET '
            //        .     "M01UPID = '{$qrsarr['ID']}',"
            //        .     "M01UPPGM = '{$qrsarr['PG']}',"
            //        .     "M01UPDATE = '{$qrsarr['DATE']}',"
            //        .     'M01POINT = M01POINT - ' . (int)$qrsarr['POINT']
            //        . ' WHERE '
            //        .     "M01GCNO = '{$qrsarr['GCNO']}'";
            //    break;

            case 'f00-use-point':
                $qsqlstr = 'UPDATE ' . DB_COMM_LIBRARY . 'F00CARDS'
                    . ' SET '
                    .     "F00UPID = '{$qrsarr['ID']}',"
                    .     "F00UPPGM = '{$qrsarr['PG']}',"
                    .     "F00UPDATE = '{$qrsarr['DATE']}',"
                    .     'F00POINT = F00POINT - ' . (int)$qrsarr['POINT']
                    . ' WHERE '
                    .     "F00GCNO = '{$qrsarr['GCNO']}'";
                break;

            default:
                break;
        }
        return $qsqlstr;
    }

    /**
     * 挿入行データのクォート処理
     *
     * @param array $columnsInfo
     * @param array $qrsarr
     *
     * @return array
     */
    protected function makeInsertParams($columnsInfo, $qrsarr)
    {
        $insertParams = array();
        foreach ($columnsInfo as $col => $type) {
            $v = strval($qrsarr[$col]);
            if (($type == self::TYPE_INT) || ($type == self::TYPE_DATE)) {
                if (!strlen($v)) {
                    $v = 'null';
                } elseif ($type == self::TYPE_DATE) {
                    $v = "'{$v}'";
                }
            } else {
                $v = "'{$v}'";
            }
            $insertParams[$col] = $v;
        }
        return $insertParams;
    }
}
