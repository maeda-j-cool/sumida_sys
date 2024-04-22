<?php
class OkiniiriIchiranAction extends SgAction
{
    /**
     * @var bool 認証が必要な画面かどうか
     */
    protected $_requireAuth = true;

    /**
     * @var bool アクションとモジュール名をセッションに保存するかどうか
     */
    protected $_saveCurrentAction = false;

    /**
     * {@inheritdoc}
     */
    protected function _initialize($controller, $request, $user)
    {
        // 仮想ログインは参照不可 → トップに飛ばす
        if ($user->getAttribute('is_virtual_login')) {
            $controller->redirect(WT_URL_BASE_SSL, true);
        }
        parent::_initialize($controller, $request, $user);
    }

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $shohinNo = $request->getParameter('shohinNo');
        // チェックしたお気に入りもしくは、全てのお気に入りを削除
        if (($shohinNo === 'all') || ctype_digit($shohinNo)) {
            if ($shohinNo === 'all') {
                // 対象が存在しない場合に削除SQLがエラー終了するので、、、(原因不明)
                $dbR = new OkiniiriIchiranQuerySel();
                $dbR->setRecordsetArray([
                    'GCNO' => $this->gcInfo->maincardNo,
                    'SHOP_ID' => SHOP_ID,
                ]);
                $dbR->setSelectSql('1');
                $rs = $dbR->Execute();
                if (!$rs) {
                    throw new WtDbException(E_DB_EXECUTE_ERR);
                }
                if (!$rs->RecordCount()) {
                    return $this->getDefaultView($controller, $request, $user);
                }
            }
            $db = new OkiniiriIchiranQueryIUD();
            $db->setSelectSql('1');
            $db->setRecordsetArray([
                'ID'   => SHOP_ID,
                'SNO'  => ($shohinNo === 'all') ? null : $shohinNo,
                'GCNO' => $this->gcInfo->maincardNo,
            ]);
            $db->ConntTrans();
            $rs = $db->Execute();
            if (!$rs) {
                $db->ConnRollback();
                throw new WtDbException(E_DB_EXECUTE_ERR);
            }
            $db->ConnCommit();
        }
        $controller->redirect($this->getActionUrl('OkiniiriIchiran', 'OkiniiriIchiran'));
        return VIEW_NONE;
    }

    /**
     * {@inheritdoc}
     */
    function getDefaultView($controller, $request, $user)
    {
        // お気に入りリスト取得
        $db = new OkiniiriIchiranQuerySel();
        $db->setRecordsetArray([
            'GCNO' => $this->gcInfo->maincardNo,
            'SHOP_ID' => SHOP_ID,
        ]);
        $db->setSelectSql('1');
        $rs = $db->Execute();
        if (!$rs) {
            throw new WtDbException(E_DB_EXECUTE_ERR);
        }
        $searchResults = $rs->GetArray();
        if (!is_array($searchResults) || empty($searchResults)) {
            $request->setError(null, 'お気に入りページにはまだ登録商品がございません。');
        }
        $request->setAttribute('search_results', $searchResults);
        return VIEW_INPUT;
    }
}
