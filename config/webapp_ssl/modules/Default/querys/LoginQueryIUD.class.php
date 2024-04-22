<?php
class LoginQueryIUD extends DBConnectIUD
{
    /**
     * {@inheritdoc}
     */
    function Query($bindParams, $sqlNo)
    {
        $sql = '';
        switch ($sqlNo) {
            //case 'update-m01':
            //    $updateParams = [
            //        'M01UPID'   => "'{$bindParams['ID']}'",
            //        'M01UPPGM'  => "'{$bindParams['PG']}'",
            //        'M01UPDATE' => "'{$bindParams['DATE']}'",
            //        'M01POINT'  => (int)$bindParams['POINT'],
            //    ];
            //    $updateLines = [];
            //    foreach ($updateParams as $k => $v) {
            //        $updateLines[] = $k . ' = ' . $v;
            //    }
            //    $sql = 'UPDATE '
            //         .     DB_SHOP_LIBRARY . 'M01WKAIIN'
            //         . ' SET '
            //         .     implode(',', $updateLines)
            //         . ' WHERE '
            //         .     "M01GCNO = '{$bindParams['GCNO']}'";
            //    break;

            case 'update-f00':
                $updateParams = [
                    'F00UPID'   => "'{$bindParams['ID']}'",
                    'F00UPPGM'  => "'{$bindParams['PG']}'",
                    'F00UPDATE' => "'{$bindParams['DATE']}'",
                    'F00POINT'  => (int)$bindParams['POINT'],
                    'F00TDATE'  => "'{$bindParams['TDATE']}'",
                ];
                $updateLines = [];
                foreach ($updateParams as $k => $v) {
                    $updateLines[] = $k . ' = ' . $v;
                }
                $sql = 'UPDATE '
                     .     DB_SHOP_LIBRARY . 'F00CARDS'
                     . ' SET '
                     .     implode(',', $updateLines)
                     . ' WHERE '
                     .     "F00GCNO = '{$bindParams['GCNO']}'";
                break;

            default:
                break;
        }
        return $sql;
    }
}
