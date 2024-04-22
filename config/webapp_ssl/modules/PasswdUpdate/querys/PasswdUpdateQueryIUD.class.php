<?php
class PasswdUpdateQueryIUD extends DBConnectIUD
{
    /**
     * {@inheritdoc}
     */
    function Query($bindParams, $sqlNo)
    {
        switch ($sqlNo) {
            case 'update-m01':
                $updateParams = [
                    'M01UPID'   => "'{$bindParams['ID']}'",
                    'M01UPPGM'  => "'{$bindParams['PG']}'",
                    'M01UPDATE' => "'{$bindParams['DATE']}'",
                    'M01PASSWD' => "'{$bindParams['PASSWORD']}'",
                    'M01PDATE'  => "'{$bindParams['DATE']}'",
                ];
                $updateLines = [];
                foreach ($updateParams as $k => $v) {
                    $updateLines[] = $k . ' = ' . $v;
                }
                $sql = 'UPDATE '
                     .     DB_SHOP_LIBRARY . 'M01WKAIIN'
                     . ' SET '
                     .     implode(',', $updateLines)
                     . ' WHERE '
                     .     "M01GCNO = '{$bindParams['GCNO']}'"
                     . ' AND '
                     .     "M01KENGROUP = '{$bindParams['KENGROUP']}'"
                     . ' AND '
                     .     "M01KAINSTS <> '00'"
                ;
                break;

            default:
                break;
        }
        return $sql;
    }
}
