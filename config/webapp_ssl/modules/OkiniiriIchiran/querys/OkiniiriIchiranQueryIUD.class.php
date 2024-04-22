<?php
class OkiniiriIchiranQueryIUD extends DBConnectIUD
{
    /**
     * {@inheritdoc}
     */
    function Query($bindParams, $sqlNo)
    {
        $sql = '';
        switch ($sqlNo) {
            case '1':
                $wheres = [
                    "F42ID = '{$bindParams['ID']}'",
                    "F42GCNO = '{$bindParams['GCNO']}'",
                ];
                if ($bindParams['SNO']) {
                    $wheres[] = "F42SHOHINNO = '{$bindParams['SNO']}'";
                }
                $sql = 'DELETE'
                     . ' FROM '
                     .     DB_SHOP_LIBRARY . 'F42OKINI'
                     . ' WHERE '
                     .     implode(' AND ', $wheres)
                ;
                break;
        }
        return $sql;
    }
}