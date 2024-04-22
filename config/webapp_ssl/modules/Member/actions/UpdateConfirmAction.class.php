<?php
require_once(__DIR__ . '/RegisterConfirmAction.class.php');
require_once(dirname(__DIR__) . '/querys/MemberQuerySel.class.php');

class UpdateConfirmAction extends RegisterConfirmAction
{
    /**
     * @var bool 認証が必要な画面かどうか
     */
    protected $_requireAuth = true;

    const MODE = 'Update';

    const MOD_PG = 'S0620';

    protected function saveData($dbParams)
    {
        $db = new MemberQueryIUD();
        $db->ConntTrans();
        try {
            unset($dbParams['POINT']);
            $db->setRecordsetArray($dbParams);
            $db->setSelectSql('update-m01');
            if (!$db->Execute()) {
                throw new WtDbException(E_DB_EXECUTE_ERR);
            }
            $db->setSelectSql('update-f01');
            if (!$db->Execute()) {
                throw new WtDbException(E_DB_EXECUTE_ERR);
            }
            $db->setSelectSql('update-m11');
            if (!$db->Execute()) {
                throw new WtDbException(E_DB_EXECUTE_ERR);
            }
            $db->ConnCommit();
        } catch (Exception $e) {
            WtApp::getLogger()->error($e->getMessage());
            $db->ConnRollBack();
            return false;
        }
        return true;
    }
}
