<?php
require_once(__DIR__ . '/AbstractMemberAction.class.php');
require_once(dirname(__DIR__) . '/querys/MemberQueryIUD.class.php');
require_once dirname(__DIR__, 2) . '/TgcUpdateTrait.php';
require_once dirname(__DIR__, 2) . '/AdminMailTrait.php';

class RegisterConfirmAction extends AbstractMemberAction
{
    use TgcUpdateTrait;
    use AdminMailTrait;

    const MODE = 'Register';

    const MOD_ID = 'shop';
    const MOD_PG = 'S0610';

    /**
     * {@inheritdoc}
     */
    public function execute($controller, $request, $user)
    {
        if ($this->_isSubmit('BTN_BACK')) {
            // 戻るボタンが押下されたら入力画面にリダイレクトさせる。
            $user->setModuleParam(self::SESSNAME_POSTS, $user->getActionParam(self::SESSNAME_POSTS));
            $controller->redirect($this->getActionUrl('Member', static::MODE . 'Input'));
            return VIEW_NONE;
        }
        $postParams = $user->getActionParam(self::SESSNAME_POSTS);
        if (empty($postParams)) {
            $controller->redirect($this->getActionUrl('Member', static::MODE . 'Input'));
            return VIEW_NONE;
        }
        if ($this->_isSubmit('BTN_SUBMIT')) {
            $t = time();
            $gcInfo = $this->gcInfo;
            $dbParams = [
                'ID'       => static::MOD_ID,
                'PG'       => static::MOD_PG,
                'DATE'     => date(DB_TIMESTAMP_FORMAT_SYSTEM, $t),
                'USERID'   => $gcInfo->maincardNo,
                'GCNO'     => $gcInfo->maincardNo,
                'PIN'      => $gcInfo->maincardPin,
                'KENCD'    => $gcInfo->kenshuCode,
                'KENGROUP' => $gcInfo->kenshuGroup,
                'ZIP1'     => $postParams[self::I_ZIPCODE_1],
                'ZIP2'     => $postParams[self::I_ZIPCODE_2],
                'ADD1'     => $postParams[self::I_ADDRESS_1],
                'ADD2'     => $postParams[self::I_ADDRESS_2],
                'ADD3'     => $postParams[self::I_ADDRESS_3],
                'TEL11'    => $postParams[self::I_TEL1_1],
                'TEL12'    => $postParams[self::I_TEL1_2],
                'TEL13'    => $postParams[self::I_TEL1_3],
                'TEL21'    => $postParams[self::I_TEL2_1],
                'TEL22'    => $postParams[self::I_TEL2_2],
                'TEL23'    => $postParams[self::I_TEL2_3],
                'SEI01'    => $postParams[self::I_SEI_KANJI1],
                'MEI01'    => $postParams[self::I_MEI_KANJI1],
                'SEIK01'   => $postParams[self::I_SEI_KANA1],
                'MEIK01'   => $postParams[self::I_MEI_KANA1],
                'REL01'    => $postParams[self::I_RELATION1],
                'BIRTH01'  => null,
                'SEI02'    => $postParams[self::I_SEI_KANJI2],
                'MEI02'    => $postParams[self::I_MEI_KANJI2],
                'SEIK02'   => $postParams[self::I_SEI_KANA2],
                'MEIK02'   => $postParams[self::I_MEI_KANA2],
                'REL02'    => $postParams[self::I_RELATION2],
                'BIRTH02'  => null,
                'NAME'     => $postParams[self::I_SEI_KANJI1] . ' ' . $postParams[self::I_MEI_KANJI1],
                'PASSWORD' => '',
                'NEWSFLG'  => '0', // 中野区はイベントメールなどがないので0固定
            ];
            if ($postParams[self::I_PASSWORD1]) {
                $dbParams['PASSWORD'] = rincrypt($postParams[self::I_PASSWORD1]);
            }
            if (static::MODE === 'Register') {
                $dbParams['STATUS'] = '01';
                $dbParams['EMAIL'] = $postParams[self::S_EMAIL];
                $dbParams['SEI03']  = $postParams[self::I_SEI_KANJI3];
                $dbParams['MEI03']  = $postParams[self::I_MEI_KANJI3];
                $dbParams['SEIK03'] = $postParams[self::I_SEI_KANA3];
                $dbParams['MEIK03'] = $postParams[self::I_MEI_KANA3];
                $dbParams['REL03']  = $postParams[self::I_SEI_KANJI3] ? 'OTHER' : null;
                $dbParams['BIRTH03'] = $postParams[self::I_BIRTHDAY3];
                $dbParams['F25PARAMS'] = $postParams[self::I_F25PARAMS];
                $dbParams['F26PARAMS'] = $postParams[self::I_F26PARAMS];
            }
            if (!$this->saveData($dbParams)) {
                // DBエラー or TGCエラー
                $request->setError('_', E_SYSTEM_ERROR); // 予期せぬエラーが発生しました。恐れ入りますが、もう一度お手続きください
                return $this->handleError($controller, $request, $user);
            }
            $this->gcInfo->userName = $dbParams['NAME'];
            $user->setGiftcardInfo($this->gcInfo->calc());
            $user->setAttribute('member_edit_complete', true);
            $user->setActionParam(self::SESSNAME_POSTS, null);
            $controller->redirect($this->getActionUrl('Member', static::MODE . 'Complete'));
            return VIEW_NONE;
        }
        return $this->handleError($controller, $request, $user);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultView($controller, $request, $user)
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
            $controller->redirect($this->getActionUrl('Member', static::MODE . 'Input'));
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
    public function validate($controller, $request, $user)
    {
        if ($this->_isSubmit('BTN_SUBMIT')) {
            // CSRF対策のトークンチェックを行う。
            $this->_isValidToken($user);
        }
    }

    protected function saveData($dbParams)
    {
        $db = new MemberQueryIUD();
        $db->ConntTrans();
        try {
            $f25Temp = $dbParams['F25PARAMS'];
            $f26Temp = $dbParams['F26PARAMS'];
            unset($dbParams['F25PARAMS']);
            unset($dbParams['F26PARAMS']);
            $db->setRecordsetArray($dbParams);
            $db->setSelectSql('insert-m00');
            if (!$db->Execute()) {
                throw new WtDbException(E_DB_EXECUTE_ERR);
            }
            $db->setSelectSql('update-m01');
            if (!$db->Execute()) {
                throw new WtDbException(E_DB_EXECUTE_ERR);
            }
            $dbParams['POINT'] = $this->gcInfo->usablePoints;
            $dbParams['TDATE'] = date('Y-m-d', strtotime($this->gcInfo->expiryYmd));
            $db->setRecordsetArray($dbParams);
            $db->setSelectSql('insert-f00');
            if (!$db->Execute()) {
                throw new WtDbException(E_DB_EXECUTE_ERR);
            }
            $db->setSelectSql('insert-f01');
            if (!$db->Execute()) {
                throw new WtDbException(E_DB_EXECUTE_ERR);
            }
            $db->setSelectSql('insert-m11');
            if (!$db->Execute()) {
                throw new WtDbException(E_DB_EXECUTE_ERR);
            }
            $db->setSelectSql('insert-f25');
            $enqAnsId = getSequeceNo('F25ENQANS');
            $f25Params = $f25Temp;
            $f25Params['F25ENQANSID'] = $enqAnsId;
            $f25Params['ID'] = $dbParams['ID'];
            $f25Params['PG'] = $dbParams['PG'];
            $f25Params['DATE'] = $dbParams['DATE'];
            // 緊急度判定ワードの確認
            $enqueteInfo = $this->_request->getAttribute('enquete_info');
            if ($this->hasEnqueteAlert($enqueteInfo, $f26Temp)) {
                $f25Params['F25WJUCNO'] = 'ALERT';
            }
            $db->setRecordsetArray($f25Params);
            if (!$db->Execute()) {
                throw new WtDbException(E_DB_EXECUTE_ERR);
            }
            $db->setSelectSql('insert-f26');
            foreach ($f26Temp as $f26Params) {
                $f26Params['F26ENQANSID'] = $enqAnsId;
                $f26Params['ID'] = $dbParams['ID'];
                $f26Params['PG'] = $dbParams['PG'];
                $f26Params['DATE'] = $dbParams['DATE'];
                $db->setRecordsetArray($f26Params);
                if (!$db->Execute()) {
                    throw new WtDbException(E_DB_EXECUTE_ERR);
                }
            }
            // 中野区では登録時のギフトカード更新などを行わない
            if (!$db->ConnCommit()) {
                WtApp::getLogger()->error('COMMIT ERROR:トランザクションの確定処理に失敗しました。');
                throw new WtDbException(E_DB_EXECUTE_ERR, 1);
            }
            if ($f25Params['F25WJUCNO'] === 'ALERT') {
                $this->sendUrgencyJudgmentMail($dbParams['GCNO'], $dbParams['DATE'], $dbParams['NAME']);
            }
        } catch (Exception $e) {
            WtApp::getLogger()->error($e->getMessage());
            if ($e->getCode() != 1) {
                $db->ConnRollBack();
            }
            return false;
        }
        return true;
    }

    private function hasEnqueteAlert($enqueteInfo, $f26Params)
    {
        include_once(dirname(WT_ROOT_DIR) . '/CommonDefinition.php');
        $cache = new WtFileCache(WT_ENQUETE_URGENCY_CACHE_DIR);
        $enqueteUrgencyData = $cache->get(WT_ENQUETE_URGENCY_CACHE_ID);
        if (empty($enqueteUrgencyData)) {
            $command = implode(' ', [PHPEXEC, WT_ENQUETE_URGENCY_CACHE_BATCH]);
            if (shell_exec($command) !== false) {
                $enqueteUrgencyData = $cache->get(WT_ENQUETE_URGENCY_CACHE_ID);
            }
        }
        $temp = array_intersect(array_keys($enqueteUrgencyData), ['select', 'input', 'urgency_words']);
        if (count($temp) !== count($enqueteUrgencyData)) {
            WtApp::getLogger()->warn('make_enquete_urgency_cache failure: ' . print_r($enqueteUrgencyData, true));
            return false;
        }
        $enqueteAnswers = [];
        foreach ($f26Params as $params) {
            $eid = $params['F26ENQID'];
            $enquete = $enqueteInfo[$eid] ?? [];
            $seq = (string)$enquete['M36SEQ'];
            $oid = (string)$params['F26ENQOPID'];
            $k = sprintf('%s-%s', $seq, $oid);
            $enqueteAnswers[$k] = (string)$params['F26ENQOPFREE'];
        }
        $hasUrgency = false;
        $ngSelectList = array_intersect(array_keys($enqueteAnswers), $enqueteUrgencyData['select']);
        if (!empty($ngSelectList)) {
            WtApp::getLogger()->info(sprintf('[緊急度判定] 選択値：%s', implode(',', $ngSelectList)));
            $hasUrgency = true;
        }
        $urgencyWords = $enqueteUrgencyData['urgency_words'];
        foreach ($enqueteUrgencyData['input'] as $k) {
            $text = $enqueteAnswers[$k] ?? '';
            if (strlen($text)) {
                $matches = WtString::extractFuzzyKeywords($urgencyWords, $text, true);
                if (!empty($matches)) {
                    WtApp::getLogger()->info(sprintf('[緊急度判定] 入力値：%s=%s', $k, implode('|', $matches)));
                    $hasUrgency = true;
                }
            }
        }
        return $hasUrgency;
    }

    private function sendUrgencyJudgmentMail($id, $date, $name)
    {
        $renderer = WtApp::getMailRenderer('UrgencyJudgmentAdmin.tpl');
        $renderer->setAttribute('id', $id);
        $d = DateTime::createFromFormat(DB_TIMESTAMP_FORMAT_SYSTEM, $date);
        $renderer->setAttribute('date', $d->format('Y/m/d H:i:s'));
        $renderer->setAttribute('name', $name);
        $mailBody = $renderer->fetchResult();
        $logFile = sprintf('%smail/log/UrgencyJudgment_%s.log', WT_ROOT_DIR, date('Ymd'));
        $settings = WtApp::getConfig('settings');
        $wtMailer = new WtMail();
        $wtMailer
            ->setFrom(trim($settings['enquete_urgency_mail_from_address']))
            ->addTo(trim($settings['enquete_urgency_mail_to_address']))
            ->setSubject(trim($settings['enquete_urgency_mail_subject']))
            ->addExtraHeader('Reply-To: '  . trim($settings['enquete_urgency_mail_reply_to_address']))
            ->addExtraHeader('Errors-To: ' . trim($settings['enquete_urgency_mail_errors_to_address']))
            ->setBodyText($mailBody)
            ->setLogFileName($logFile);
        foreach(explode(',', $settings['enquete_urgency_mail_cc_addresses']) as $mailCc) {
            $mailCc = trim($mailCc);
            if (trim($mailCc)) {
                $wtMailer->addCc($mailCc);
            }
        }
        $wtMailer->send();
    }
}
