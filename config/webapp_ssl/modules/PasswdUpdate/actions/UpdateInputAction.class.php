<?php
require_once(dirname(__DIR__) . '/querys/PasswdUpdateQueryIUD.class.php');

class UpdateInputAction extends SgAction
{
    /**
     * @var bool 認証が必要な画面かどうか
     */
    protected $_requireAuth = false;

    /**
     * @var bool アクションとモジュール名をセッションに保存するかどうか
     */
    protected $_saveCurrentAction = false;

    const I_PASSWORD1 = 'password';
    const I_PASSWORD2 = 'password_confirm';

    const MOD_ID = 'shop';
    const MOD_PG = 'S0005';

    /**
     * {@inheritdoc}
     */
    protected function _initPostParams($request)
    {
        $this->_postParams = [
            self::I_PASSWORD1 => 500,
            self::I_PASSWORD2 => 501,
        ];
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _initialize($controller, $request, $user)
    {
        parent::_initialize($controller, $request, $user);
        $gcInfo = $this->gcInfo;
        if ($user->getAttribute('is_virtual_login')
            || $user->isAuthenticated()
            || !($gcInfo instanceof SgGiftcardInfo)
        ) {
            $controller->redirect(WT_URL_BASE_SSL, true);
        }
        if (!$user->getModuleParam('__')) {
            $sessParams = $user->getAttribute('index_sess_params');
            if (!($sessParams['reissue'] ?? null)) {
                $controller->redirect(WT_URL_BASE, true);
            }
            $user->removeAttribute('index_sess_params');
            $user->setModuleParam('__', true);
        }
    }

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        if ($this->_isSubmit('BTN_SUBMIT')) {
            $newPassword = $request->getParameter(self::I_PASSWORD1);
            $db = new PasswdUpdateQueryIUD();
            $db->ConntTrans();
            try {
                $db->setRecordsetArray([
                    'ID' => static::MOD_ID,
                    'PG' => static::MOD_PG,
                    'DATE' => date(DB_TIMESTAMP_FORMAT_SYSTEM),
                    'PASSWORD' => rincrypt($newPassword),
                    'GCNO' => $this->gcInfo->maincardNo,
                    'KENGROUP' => $this->gcInfo->kenshuGroup,
                ]);
                $db->setSelectSql('update-m01');
                if (!$db->Execute()) {
                    throw new Exception(E_DB_EXECUTE_ERR);
                }
                $db->ConnCommit();
            } catch (Exception $e) {
                $db->ConnRollBack();
                $request->setError('_', E_SYSTEM_ERROR); // 予期せぬエラーが発生しました。恐れ入りますが、もう一度お手続きください
                return $this->handleError($controller, $request, $user);
            }
            // セッションクリア
            $user->clearAll();
            $user->setModuleParam('password_update_complete', true);
            $user->store(false);
            $controller->redirect($this->getActionUrl('PasswdUpdate', 'UpdateComplete'));
            return VIEW_NONE;
        }
        return $this->handleError($controller, $request, $user);
    }

    /**
     * {@inheritdoc}
     */
    function getDefaultView($controller, $request, $user)
    {
        // CSRF対策のトークンを取得する。
        $this->_setToken($request, $user);
        return VIEW_INPUT;
    }

    /**
     * {@inheritdoc}
     */
    function validate($controller, $request, $user)
    {
        if ($this->_isSubmit('BTN_SUBMIT')) {
            $this->_isValidToken($user); // CSRF対策のトークンチェック
            $appValidator = $this->_getValidator();
            $password1 = trim($request->getParameter(self::I_PASSWORD1));
            $password2 = trim($request->getParameter(self::I_PASSWORD2));
            if (!strlen($password1)) {
                $appValidator->setCustomError(self::I_PASSWORD1, '新しいパスワードを入力してください。');
            } elseif (!preg_match('/[A-Z]/', $password1)) {
                $appValidator->setCustomError(self::I_PASSWORD1, '新しいパスワードに英字（大文字）が含まれておりません。');
            } elseif (!preg_match('/[a-z]/', $password1)) {
                $appValidator->setCustomError(self::I_PASSWORD1, '新しいパスワードに英字（小文字）が含まれておりません。');
            } elseif (!preg_match('/[0-9]/', $password1)) {
                $appValidator->setCustomError(self::I_PASSWORD1, '新しいパスワードに数字が含まれておりません。');
            } else {
                $appValidator->ascii(self::I_PASSWORD1, '新しいパスワード', true, 8, 32);
            }
            if (!strlen($password2)) {
                $appValidator->setCustomError(self::I_PASSWORD2, '新しいパスワード（確認）を入力してください。');
            } elseif (strlen($password1) && ($password1 !== $password2)) {
                $appValidator->setCustomError(self::I_PASSWORD2, '新しいパスワードと新しいパスワード（確認）が一致しません。');
            }
            if (strlen($password1 . $password2)
                && !$appValidator->hasError(self::I_PASSWORD1)
                && !$appValidator->hasError(self::I_PASSWORD2)
            ) {
                // 同一パスワードチェック
                include_once(dirname(__DIR__, 2) . '/Member/querys/MemberQuerySel.class.php');
                $db = new MemberQuerySel();
                $db->setSelectSql('same-user-password-check');
                $db->setRecordsetArray([
                    'LOGINID'  => $request->getAttribute($this->gcInfo->email),
                    'PASSWORD' => rincrypt($password1),
                    'KENGROUP' => $this->targetKenshuGroup,
                ]);
                $rs = $db->Execute();
                if (!$rs) {
                    throw new Exception(E_DB_EXECUTE_ERR);
                }
                if ($rs->RecordCount()) {
                    $appValidator->setCustomError(self::I_PASSWORD1, '既に登録された情報になります。パスワードを変更してください。');
                }
            }
            $this->_validate($appValidator);
            $appValidator->setErrors();
        }
    }
}