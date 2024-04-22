<?php
require_once __DIR__ . '/AbstractToiawaseAction.class.php';

class ToiawaseKakuninAction extends AbstractToiawaseAction
{
    /**
     * @var string プログラムID
     */
    protected $_modPg = 'S0404';

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        if ($this->_isSubmit('BTN_BACK')) {
            // 戻るボタンが押下されたら入力画面にリダイレクトさせる。
            $user->setModuleParam(self::SESSNAME_POSTS, $user->getActionParam(self::SESSNAME_POSTS));
            $controller->redirect($this->getActionUrl('Toiawase', 'Toiawase'));
            return VIEW_NONE;
        }
        $postParams = $user->getActionParam(self::SESSNAME_POSTS);
        if (empty($postParams)) {
            $controller->redirect($this->getActionUrl('Toiawase', 'Toiawase'));
            return VIEW_NONE;
        }
        if ($this->_isSubmit('BTN_SUBMIT')) {
            $now = date(DB_TIMESTAMP_FORMAT_SYSTEM);
            $toiawaseNo = getSequeceNo('F02TOIAWASE');
            $dbParams = [
                'F02DELFLG'  => '0',
                'F02INSID'   => MOD_SHOP_ID,
                'F02INSPGM'  => $this->_modPg,
                'F02INSDATE' => $now,
                'F02UPID'    => MOD_SHOP_ID,
                'F02UPPGM'   => $this->_modPg,
                'F02UPDATE'  => $now,
                'F02ID'      => SHOP_ID,
                'F02TOINO'   => $toiawaseNo,
                'F02TOIKEY1' => 'TOIH',
                'F02TOIKEY2' => $postParams[self::I_INQUIRY_ITEM],
                // 'F02GCNO'    => $postParams[self::I_GIFTCARD_NO] ?? '',
                'F02GCNO'    => (!$user->getAttribute('is_virtual_login') && $user->isAuthenticated()) ? $this->gcInfo->maincardNo : '',
                'F02SHOHNNO' => null,
                'F02WJUCNO'  => $postParams[self::I_ORDER_NO] ?? '',
                'F02TDATE'   => $now,
                'F02SEI'     => $postParams[self::I_SEI_KANJI],
                'F02MEI'     => $postParams[self::I_MEI_KANJI],
                'F02SEIKN'   => $postParams[self::I_SEI_KANA],
                'F02MEIKN'   => $postParams[self::I_MEI_KANA],
                'F02EMAILPC' => $postParams[self::I_EMAIL],
                'F02TNAIYO'  => $postParams[self::I_INQUIRY_TEXT],
                'F02TOISTS'  => null,
                'F02TOIDWL'  => '0',
                'F02TEL1'    => $postParams[self::I_TEL1],
                'F02TEL2'    => $postParams[self::I_TEL2],
                'F02TEL3'    => $postParams[self::I_TEL3],
                'F02TOIREPFLG' => '0',
                'F02LOGINFLG' => (!$user->getAttribute('is_virtual_login') && $user->isAuthenticated()) ? '1' : '0',
            ];
            try {
                $db = new ToiawaseQueryIUD();
                $db->setSelectSql('1');
                $db->setRecordsetArray($dbParams);
                $db->ConntTrans();
                $rs = $db->Execute();
                if (!$rs) {
                    throw new WtDbException(E_DB_EXECUTE_ERR);
                }
                $db->ConnCommit();
            } catch (\Exception $e) {
                //WtApp::getLogger()->error($e->getMessage());
                $db->ConnRollback();
                //$request->setError('_', implode("\n", [
                //    '予期せぬエラーが発生しました。恐れ入りますが、もう一度お手続きください。',
                //    '同じエラーが何度か発生する場合はお問い合わせください。'
                //]));
                //return $this->handleError($controller, $request, $user);
                throw $e;
            }
            $optionInquiryItems = $request->getAttribute('option_inquiry_items');
            $renderer = WtApp::getMailRenderer('ToiawaseKanryo.tpl');
            $renderer->setAttribute('name1', $postParams[self::I_SEI_KANJI]);
            $renderer->setAttribute('name2', $postParams[self::I_MEI_KANJI]);
            $renderer->setAttribute('no', $toiawaseNo);
            // $renderer->setAttribute('giftcard_no', $postParams[self::I_GIFTCARD_NO] ?? '');
            $renderer->setAttribute('giftcard_no', (!$user->getAttribute('is_virtual_login') && $user->isAuthenticated()) ? $this->gcInfo->maincardNo : '');
            $renderer->setAttribute('komoku', $optionInquiryItems[$postParams[self::I_INQUIRY_ITEM]] ?? '');
            $renderer->setAttribute('naiyo', $postParams[self::I_INQUIRY_TEXT]);
            $renderer->setAttribute('tel', implode('-', [
                $postParams[self::I_TEL1],
                $postParams[self::I_TEL2],
                $postParams[self::I_TEL3],
            ]));
            // メール本文生成
            $text = $renderer->fetchResult();
            $settings = $this->settings;
            $mailSubject  = $settings['inquiry_mail_subject'];
            $mailFromName = $settings['inquiry_mail_from_name'];
            $mailFrom     = $settings['inquiry_mail_from_address'];
            $replyTo      = $settings['inquiry_mail_reply_to_address'];
            $errorsTo     = $settings['inquiry_mail_errors_to_address'];
            $bccAddress   = $settings['inquiry_mail_bcc'];
            // メール送信(ユーザ)
            $mailLog = sprintf('%smail/log/ToiawaseKanryo_%s.log', WT_ROOT_DIR, date('Ymd'));
            (new WtMail())
                ->setFrom($mailFrom, $mailFromName)
                ->addTo($postParams[self::I_EMAIL])
                ->setSubject($mailSubject)
                ->addBcc($bccAddress)
                ->addExtraHeader('Reply-To: '  . $replyTo)
                ->addExtraHeader('Errors-To: ' . $errorsTo)
                ->setBodyText($text)
                ->setLogFileName($mailLog)
                ->send();
            $user->setActionParam(self::SESSNAME_POSTS, null);
            // お問い合わせ番号をリクエストにセット
            $user->setModuleParam('toiawase_no', $toiawaseNo);
            $user->store(false);
            $controller->redirect($this->getActionUrl('Toiawase', 'ToiawaseKanryo'));
            return VIEW_NONE;
        }
        return $this->handleError($controller, $request, $user);
    }

    /**
     * {@inheritdoc}
     */
    function getDefaultView($controller, $request, $user)
    {
        // 入力画面からの遷移の場合はモジュールセッションから入力値を取得できる。
        // ※取得と同時にモジュールセッション情報を削除する。
        $postParams = $user->getModuleParam(self::SESSNAME_POSTS);
        if (!$postParams) {
            // 取得できなかった場合はアクションセッションから入力値を取得する。
            // ※画面のリロード時など。
            $postParams = $user->getActionParam(self::SESSNAME_POSTS);
        }
        $user->setModuleParam(self::SESSNAME_POSTS, null);
        if (!$postParams) {
            // 送信値が取得できない場合は入力画面にリダイレクトさせる。
            // ブラウザへのURL直接入力など。
            $controller->redirect($this->getActionUrl('Toiawase', 'Toiawase'));
            return VIEW_NONE;
        }
        // 取得した入力値をアクションセッションに保存する。
        // ※画面のリロード時などにはこのセッションから情報を取り出す。
        $user->setActionParam(self::SESSNAME_POSTS, $postParams);
        // レンダラーに登録する値として入力値をセットする。
        $request->setAttribute('confirm_params', $postParams);
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
            // CSRF対策のトークンチェックを行う。
            $this->_isValidToken($user);
        }
    }
}
