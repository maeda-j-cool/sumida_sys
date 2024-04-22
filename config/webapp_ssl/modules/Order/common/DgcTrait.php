<?php
require_once(dirname(__DIR__) . '/querys/DgcQuerySel.class.php');
require_once(dirname(__DIR__) . '/querys/DgcQueryIUD.class.php');

trait DgcTrait
{
    /**
     * @var string
     */
    protected $modId = '';

    /**
     * @var string
     */
    protected $modPg = '';

    /**
     * @param string $publisher
     * @return array [
     *      'type' => api|stock
     *      'name' => デジタルギフトコード名称
     *      'sort' => 商品が複数コードで構成される場合の表示ソート用
     * ]
     * @throws \Exception
     */
    public function getDgcSetting($publisher)
    {
        $dgcSettings = getDgcSettings();
        if (!isset($dgcSettings[$publisher])) {
            throw new \Exception(sprintf('DGC-ERROR: 不明なデジタルギフトコード発行元 [%s]', $publisher));
        }
        $dgcSetting = $dgcSettings[$publisher];
        $temp = array_intersect(array_keys($dgcSetting), ['type', 'name', 'sort', 'info']);
        if (count($temp) !== count($dgcSetting) || !in_array($dgcSetting['type'], ['api', 'stock'])) {
            throw new \Exception(sprintf('DGC-ERROR: デジタルギフトコード設定に問題があります。[%s]', $publisher));
        }
        return $dgcSetting;
    }

    /**
     * @param string $itemNo
     * @return array [
     *      [
     *          'ITEM_NO'   => 商品番号
     *          'PUBLISHER' => デジタルギフトコード発行元
     *          'DGC_POINT' => デジタルギフトコード金額
     *          'DGC_NAME'  => デジタルギフトコード名称
     *          'DGC_TYPE'  => デジタルギフトコード種別（api|stock）
     *      ],
     *      [
     *          'ITEM_NO'   => 商品番号
     *          ...
     *      ],
     * ]
     * @throws \Exception
     */
    public function getDgcInfo($itemNo)
    {
        $db = new DgcQuerySel();
        $db->setSelectSql('get-dgc-info');
        $db->setRecordsetArray(['ITEM_NO' => $itemNo]);
        $rs = $db->Execute();
        if (!$rs) {
            throw new WtDbException(E_DB_EXECUTE_ERR);
        }
        $dgcInfo = [];
        if (!$rs->RecordCount()) {
            throw new \Exception(sprintf('DGC-ERROR: デジタルギフトコード情報が見つかりません。商品番号[%s]', $itemNo));
        }
        while (!$rs->EOF) {
            $publisher = $rs->Fields('M02DGCPUBLISHER');
            $dgcSetting = $this->getDgcSetting($publisher);
            $index = 0;
            do {
                $sortKey = sprintf('%s%03d', $dgcSetting['sort'], $index++);
            } while (isset($dgcInfo[$sortKey]));
            $dgcInfo[$sortKey] = [
                'ITEM_NO'   => $itemNo,
                'PUBLISHER' => $publisher,
                'DGC_POINT' => $rs->Fields('M02DGCPOINT'),
                'DGC_NAME'  => $dgcSetting['name'],
                'DGC_TYPE'  => $dgcSetting['type'],
            ];
            $rs->MoveNext();
        }
        ksort($dgcInfo);
        return $dgcInfo;
    }

    public function getDgcStock($itemNo, $publisher, $orderNo = '')
    {
        $dgcSetting = $this->getDgcSetting($publisher);
        if ($dgcSetting['type'] !== 'stock') {
            throw new \Exception(sprintf('DGC-ERROR: デジタルギフトコード種別相違(!=="stock") [%s]', $publisher));
        }
        $db = new DgcQueryIUD();
        $db->ConntTrans();
        try {
            $db->setSelectSql('get-usable-dgc-stock');
            $db->setRecordsetArray(['ITEM_NO' => $itemNo, 'PUBLISHER' => $publisher]);
            $rs = $db->Execute();
            if (!$rs) {
                throw new WtDbException(E_DB_EXECUTE_ERR);
            }
            if (!$rs->RecordCount()) {
                // 在庫なし
                $db->ConnRollBack();
                return false;
            }
            $dstkNo = $rs->Fields('M02DSTKNO');
            $db->setSelectSql('update-dgc-stock');
            $db->setRecordsetArray([
                'ID'   => $this->modId,
                'PG'   => $this->modPg,
                'DATE' => date(DB_TIMESTAMP_FORMAT_SYSTEM),
                'ORDER_NO' => $orderNo,
                'ITEM_NO'   => $itemNo,
                'PUBLISHER' => $publisher,
                'DSTK_NO'   => $dstkNo,
            ]);
            if (!$db->Execute()) {
                throw new WtDbException(E_DB_EXECUTE_ERR);
            }
            if (!$db->ConnCommit()) {
                WtApp::getLogger()->error('COMMIT ERROR:トランザクションの確定処理に失敗しました。');
                throw new WtDbException(E_DB_EXECUTE_ERR, 1);
            }
        } catch (\Exception $e) {
            if ($e->getCode() != 1) {
                $db->ConnRollBack();
            }
            throw $e;
        }
        return $rs;
    }

    public function rollbackDgcStock($itemNo, $publisher, $dstkNo)
    {
        $dgcSetting = $this->getDgcSetting($publisher);
        if ($dgcSetting['type'] !== 'stock') {
            throw new \Exception(sprintf('DGC-ERROR: デジタルギフトコード種別相違(!=="stock") [%s]', $publisher));
        }
        $db = new DgcQueryIUD();
        $db->ConntTrans();
        try {
            $db->setSelectSql('rollback-dgc-stock');
            $db->setRecordsetArray([
                'ID'   => $this->modId,
                'PG'   => $this->modPg,
                'DATE' => date(DB_TIMESTAMP_FORMAT_SYSTEM),
                'ITEM_NO'   => $itemNo,
                'PUBLISHER' => $publisher,
                'DSTK_NO'   => $dstkNo,
            ]);
            if (!$db->Execute()) {
                throw new WtDbException(E_DB_EXECUTE_ERR);
            }
            if (!$db->ConnCommit()) {
                WtApp::getLogger()->error('COMMIT ERROR:トランザクションの確定処理に失敗しました。');
                throw new WtDbException(E_DB_EXECUTE_ERR, 1);
            }
        } catch (\Exception $e) {
            if ($e->getCode() != 1) {
                $db->ConnRollBack();
            }
            throw $e;
        }
    }
}