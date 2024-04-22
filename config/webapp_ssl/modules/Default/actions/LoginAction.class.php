<?php
include_once(dirname(__DIR__) . '/querys/LoginQuerySel.class.php');
include_once(dirname(__DIR__) . '/querys/LoginQueryIUD.class.php');
include_once(dirname(__DIR__, 2) . '/Member/querys/MemberQuerySel.class.php');

class LoginAction extends SgAction
{
    /**
     * @var bool
     */
    protected $defaultOnly = true;

    const CODE_LIFETIME = 1200;

    const RESEND_DURATION = 10;

    /**
     * {@inheritdoc}
     */
    protected function _initialize($controller, $request, $user)
    {
        parent::_initialize($controller, $request, $user);
    }

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $result = ['errors' => []];
        $sessParams = $user->getAttribute('index_sess_params');
        if (!is_array($sessParams)) {
            $sessParams = $result = [];
        } else {
            $token = isset($sessParams['token']) ? $sessParams['token'] : null;
            if (!$token || ($request->getParameter('token') !== $token)) {
                $sessParams = $result = [];
            } else {
                $sessParams['token'] = $result['token'] = $this->getAjaxToken();
                $sessParams['auth'] = false;
            }
        }
        if (!empty($sessParams)) {
            try {
                switch ($request->getParameter('op')) {
                    case 'register':
                        $sessParams['mode'] = 'register';
                        $user->setAuthenticated(false);
                        $user->removeGiftcardInfo();
                        $registerId = $request->getParameter('register_id');
                        $registerPassword = $request->getParameter('register_password');
                        if (!strlen($registerId)) {
                            $result['errors'][] = 'ID番号を入力してください。';
                        }
                        if (!strlen($registerPassword)) {
                            $result['errors'][] = '配布パスワードを入力してください。';
                        }
                        if (empty($result['errors'])) {
                            $message = '';
                            $gcInfo = $this->register($registerId, $registerPassword, $message);
                            if ($gcInfo instanceof SgGiftcardInfo) {
                                $user->setGiftcardInfo($gcInfo);
                                //　券種コードが「妊婦向け」「出産向け」いずれかである場合、
                                //　初回登録メールアドレス⼊⼒モーダルを表示する。
                                //　※該当しない場合は ($gcInfo instanceof SgGiftcardInfo) がfalseを返す
                                //　 (SgGiftcardInfo->syncで判定)
                                $result['op'] = 'inputmail';
                            } else {
                                $result['errors'][] = ($message ?: '入力いただいた内容に誤りがあります。ご確認お願いします。');
                            }
                        }
                        break;

                    case 'login':
                        $sessParams['mode'] = 'login';
                        $user->setAuthenticated(false);
                        $user->removeGiftcardInfo();
                        $loginId = trim($request->getParameter('login_id'));
                        $loginPassword = trim($request->getParameter('login_password'));
                        if (!strlen($loginId)) {
                            $result['errors'][] = 'メールアドレスを入力してください。';
                        }
                        if (!strlen($loginPassword)) {
                            $result['errors'][] = 'ユーザーパスワードを入力してください。';
                        }
                        if (empty($result['errors'])) {
                            $gcInfo = $this->login($loginId, $loginPassword);
                            if ($gcInfo instanceof SgGiftcardInfo) {
                                // 中野区ではログイン時のワンタイムパスワード処理を行わない
                                $gcInfo->sync();
                                if (!$gcInfo->expiryYmd) {
                                    // カードに有効期限が未設定（利用不可）
                                    $result['errors'][] = '入力いただいた内容に誤りがあります。ご確認お願いします。';
                                } elseif (strtotime($gcInfo->expiryYmd) < strtotime('-1 year')) {
                                    // 申込完了後のログインは、有効期限日から1年間のみ可能
                                    $result['errors'][] = '有効期限が切れています。';
                                } else {
                                    $user->setGiftcardInfo($gcInfo);
                                    $this->updatePoints($gcInfo);
                                    $user->setAuthenticated(true, false);
                                    if ($gcInfo->usablePoints <= 0) {
                                        // 中野区・墨田区共通で、申込完了後のログインは交換履歴（出荷状況照会）に遷移するようにしてください。
                                        // ただし、ギフトカード側に残ポイントがある場合（＝何らかの理由でポイント返還を行った場合）は、
                                        // 通常のログインと同じ扱いとし、再度の商品申込みも可能としてください。
                                        // ※上記要望から残ポイントで判定
                                        $result['location'] = $this->getActionUrl('ShukaJyokyo', 'ShukaJyokyo', $gcInfo->kenshuGroup);
                                    } else {
                                        $result['location'] = $this->getActionUrl('', '', $gcInfo->kenshuGroup);
                                    }
                                }
                            } else {
                                $result['errors'][] = '入力いただいた内容に誤りがあります。ご確認お願いします。';
                            }
                        }
                        break;

                    case 'inputmail':
                        // @No.3: 初回登録メールアドレス⼊⼒
                        // ・入力されたメールアドレスに対して、ワンタイムパスワードを掲載したメールを送信する。
                        $appValidator = $this->_getValidator();
                        $appValidator
                            ->z2h('register_email')
                            ->emailRfc('register_email', 'メールアドレス', true);
                        if ($appValidator->hasError('register_email')) {
                            $result['errors'] = array_values($appValidator->getErrors());
                        } else {
                            $mail = $request->getParameter('register_email');
                            $code = $this->buildRandomCode();
                            $sessParams['mail'] = $mail;
                            $sessParams['code'] = $code;
                            $this->sendAuthCodeMail($mail, $code);
                            $sessParams['send'] = time();
                            $result['message'] = $this->sendAuthCodeMessage($mail);
                            if (!IS_PROD) {
                                // 本番環境以外ではコード値をダイアログ上に表示する
                                $result['message'] .= '[' . $code . ']';
                            }
                            $result['op'] = 'inputcode';
                        }
                        break;

                    case 'inputcode':
                        $gcInfo = $user->getGiftcardInfo();
                        if (!($gcInfo instanceof SgGiftcardInfo) || !isset($sessParams['mode'])) {
                            $sessParams = $result = [];
                        } else {
                            $code = isset($sessParams['code']) ? $sessParams['code'] : '';
                            $postCode = WtString::convertKana($request->getParameter('onetime_code'), 'akVs', 'UTF-8');
                            if (!strlen($code) || ($code != trim($postCode))) {
                                $result['errors'][] = 'ワンタイムパスワードが違います。';
                            } elseif (isset($sessParams['send'])) {
                                unset($sessParams['code']);
                                if (($sessParams['send'] + self::CODE_LIFETIME) < time()) {
                                    $result['errors'][] = 'ワンタイムパスワードの有効期限が切れています。';
                                } else {
                                    if ($sessParams['mode'] === 'register') {
                                        // メール認証済
                                        $sessParams['auth'] = true;
                                        $gcInfo->email = $sessParams['mail'];
                                        $user->setGiftcardInfo($gcInfo);
                                        $result['location'] = $this->getActionUrl('Member', 'RegisterInput', $gcInfo->kenshuGroup);
                                    } elseif ($sessParams['mode'] === 'reissue') {
                                        $sessParams['reissue'] = true;
                                        $gcInfo->email = $sessParams['mail'];
                                        $user->setGiftcardInfo($gcInfo);
                                        $result['location'] = $this->getActionUrl('PasswdUpdate', 'UpdateInput', $sessParams['kenshu_group']);
                                    }
                                }
                            }
                        }
                        break;

                    case 'resendmail':
                        $mail  = $sessParams['mail'] ?? null;
                        $tSend = $sessParams['send'] ?? null;
                        if (!$mail || !$tSend) {
                            $sessParams = $result = [];
                        } else {
                            if (($tSend + self::RESEND_DURATION) > time()) {
                                $result['errors'][] = sprintf('再送信は%d秒以上の間隔をあけてください。', self::RESEND_DURATION);
                            } else {
                                $code = $this->buildRandomCode();
                                $sessParams['code'] = $code;
                                $this->sendAuthCodeMail($mail, $code);
                                $sessParams['send'] = time();
                                $result['message'] = $this->sendAuthCodeMessage($mail);
                                if (!IS_PROD) {
                                    // 本番環境以外ではコード値をダイアログ上に表示する
                                    $result['message'] .= '[' . $code . ']';
                                }
                                $result['op'] = 'inputcode';
                            }
                        }
                        break;

                    case 'reissue':
                        $sessParams['mode'] = 'reissue';
                        $appValidator = $this->_getValidator();
                        $appValidator
                            ->z2h('reissue_mail')
                            ->mbtrim('reissue_mail')
                            ->emailRfc('reissue_mail', 'メールアドレス', true)
                            ->z2h('reissue_tel')
                            ->mbtrim('reissue_tel')
                            ->numeric('reissue_tel', '電話番号', true, 10, 11)
                            ->mbtrim('reissue_c_sei')
                            ->seiKanji('reissue_c_sei', 'お子さまのお名前（姓）', true)
                            ->mbtrim('reissue_c_mei')
                            ->meiKanji('reissue_c_mei', 'お子さまのお名前（名）', true)
                        ;
                        if ($appValidator->hasErrors()) {
                            $result['errors'] = array_values($appValidator->getErrors());
                        } else {
                            $db = new LoginQuerySel();
                            $db->setSelectSql('get-login-info');
                            $db->setRecordsetArray([
                                'LOGINID' => $request->getParameter('reissue_mail'),
                                'TEL1'    => $request->getParameter('reissue_tel'),
                                'CSEI'    => $request->getParameter('reissue_c_sei'),
                                'CMEI'    => $request->getParameter('reissue_c_mei'),
                            ]);
                            $rs = $db->Execute();
                            if (!$rs) {
                                throw new WtDbException(E_DB_EXECUTE_ERR);
                            }
                            if (!$rs->RecordCount()) {
                                $result['errors'][] = '入力いただいた内容に誤りがあります。ご確認お願いします。';
                            } else {
                                $cno = $rs->Fields('M01GCNO');
                                $pin = $rs->Fields('M01PIN');
                                $kenshuGroup = $rs->Fields('M00KENGROUP');
                                $gcInfo = new SgGiftcardInfo($kenshuGroup, $cno, $pin, $rs->Fields('M01KAINSTS'));
                                $gcInfo->email = $rs->Fields('M01EMAILPC');
                                $user->setGiftcardInfo($gcInfo);
                                $mail = $gcInfo->email;
                                $code = $this->buildRandomCode();
                                $sessParams['mail'] = $mail;
                                $sessParams['code'] = $code;
                                $this->sendAuthCodeMail($mail, $code);
                                $sessParams['send'] = time();
                                $result['message'] = $this->sendAuthCodeMessage($mail);
                                if (!IS_PROD) {
                                    // 本番環境以外ではコード値をダイアログ上に表示する
                                    $result['message'] .= '[' . $code . ']';
                                }
                                $sessParams['kenshu_group'] = $kenshuGroup;
                                $result['op'] = 'inputcode';
                            }
                        }
                        break;

                    default:
                        $sessParams = $result = [];
                        break;
                }
            } catch (\Exception $e) {
                WtApp::getLogger()->warn($e->getMessage());
                $result['errors'] = [E_SYSTEM_ERROR];
            }
        }
        if ($user->isAuthenticated()) {
            $user->removeAttribute('index_sess_params');
        } else {
            $user->setAttribute('index_sess_params', $sessParams);
        }
        echo json_encode($result);
        return VIEW_NONE;
    }

    /**
     * {@inheritdoc}
     */
    function getDefaultView($controller, $request, $user)
    {
        if ($user->isAuthenticated()) {
            $user->setAuthenticated(false)->clearAll();
        }
        if (WtApp::getConfig('kenshu_group') !== WT_DEFAULT_KENSHU_GROUP) {
            $controller->redirect(WT_URL_BASE);
            return VIEW_NONE;
        }
        // QRコード対応（利用者登録ポップアップ＋ID/PASSをセット）
        // ?id=1234567890-12345
        $qrGcno = $qrPin = null;
        if (preg_match('/\A([\d]+)\-([\d]+)\z/', $request->getParameter('id'), $matches)) {
            $qrGcno = $matches[1];
            $qrPin  = $matches[2];
            $request->setAttribute('init_action', 'r');
        } else {
            $act = $request->getParameter('act');
            $request->setAttribute('init_action', in_array($act,['l', 'r'], true) ? $act : null);
        }
        $request->setAttribute('qr_gcno', $qrGcno);
        $request->setAttribute('qr_pin',  $qrPin);
        // CSRF対策用のトークン発行
        $sessParams = ['token' => $this->getAjaxToken()];
        $user->setAttribute('index_sess_params', $sessParams);
        $user->removeGiftcardInfo();
        $kenshuList = array_merge(['' => '選択してください'], $this->getKenshuList());
        $request->setAttribute('kenshu_list', $kenshuList);
        $request->setAttribute('ajax_token', $sessParams['token']);
        return VIEW_INPUT;
    }

    /**
     * {@inheritdoc}
     */
    function isSecure($controller, $user)
    {
        return false;
    }

    private function getAjaxToken()
    {
        return md5(uniqid(mt_rand(), true));
    }

    private function buildRandomCode()
    {
        return sprintf('%06d', mt_rand(0, 999999));
    }

    private function sendAuthCodeMail($mailTo, $code)
    {
        $kenshuGroup = $this->targetKenshuGroup;
        $renderer = WtApp::getMailRenderer('SendAuthCode.tpl');
        $renderer->setAttribute('auth_code', $code);
        $renderer->setAttribute('limit_sec', self::CODE_LIFETIME);
        $authLimit = '';
        foreach ([
            '時間' => 3600,
            '分' => 60,
            '秒' => 1,
        ] as $suffix => $sec) {
            if (!(self::CODE_LIFETIME % $sec)) {
                $authLimit = sprintf('%d%s', intval(self::CODE_LIFETIME / $sec), $suffix);
                break;
            }
        }
        $renderer->setAttribute('auth_limit', $authLimit);
        $mailBody = $renderer->fetchResult();
        $settings = WtApp::getConfig('settings');
        $mailSubject  = $settings['otauth_mail_subject'];
        $mailFromName = $settings['otauth_mail_from_name'];
        $mailFrom     = $settings['otauth_mail_from_address'];
        $replyTo      = $settings['otauth_mail_reply_to_address'];
        $errorsTo     = $settings['otauth_mail_errors_to_address'];
        $bccAddress   = $settings['otauth_mail_bcc'];
        $mailer = new WtMail();
        $mailer
            ->setFrom($mailFrom, $mailFromName)
            ->addTo($mailTo)
            ->setSubject($mailSubject)
            ->addBcc($bccAddress)
            ->addExtraHeader('Reply-To: '  . $replyTo)
            ->addExtraHeader('Errors-To: ' . $errorsTo)
            ->setBodyText($mailBody)
            ->setLogFileName(sprintf('%smail/log/SendAuthCode_%s_%s.log', WT_ROOT_DIR, $kenshuGroup, date('Ymd')))
            ->send();
    }

    private function sendAuthCodeMessage($email)
    {
        $maskEmail = function($email) {
            $tempPart = explode('@', $email);
            if (count($tempPart) !== 2) {
                return '';
            }
            $masks = [];
            $n = strlen($tempPart[0]);
            if ($n > 1) {
                $masks[] = substr($tempPart[0], 0, 1);
                if ($n > 5) {
                    $masks[] = str_repeat('*', $n - 2);
                    $masks[] = substr($tempPart[0], -1);
                } else {
                    $masks[] = str_repeat('*', $n - 1);
                }
            } else {
                $masks[] = '*';
            }
            $masks[] = '@';
            $d = strrchr($tempPart[1], '.');
            $n = strlen($tempPart[1]) - strlen($d);
            if ($n > 1) {
                $masks[] = substr($tempPart[1], 0, 1);
                $masks[] = str_repeat('*', $n - 1);
            } else {
                $masks[] = '*';
            }
            $masks[] = $d;
            return implode('', $masks);
        };
        $masked = $maskEmail($email);
        return strlen($masked) ? sprintf('ワンタイムパスワードを %s に送信しました。', $masked) : '';
    }

    private function register($gcNo, $password, &$message)
    {
        // 初回登録時
        // @No.2: 初回登録ログイン
        // ・次の情報を満たすM01利用者情報マスタデータが存在する場合、TGC宛にギフトカード情報を照合する。
        //　　・「ギフトカードNo」「ギフトカードPIN」が入力値と⼀致する
        //　　・「券種グループ」が「｛岐⾩県市町村券種グループ｝のいずれか」である
        //　　・「ステータス」が「仮登録」
        //　　・「有効期限」内　※ 管理者による利用者情報アップロード時に「郵送から2年」の日付が設定されている想定
        //・「ギフトカードNo」「ギフトカードPIN」が入力値と⼀致するギフトカード情報の
        //　券種コードが「岐⾩県妊婦向け」「岐⾩県出産向け」いずれかである場合、
        //　初回登録メールアドレス⼊⼒モーダルを表示する。
        $db = new MemberQuerySel();
        $db->setSelectSql('get-register-info');
        $db->setRecordsetArray([
            'GCNO' => $gcNo,
            'PIN'  => $password,
        ]);
        $rs = $db->Execute();
        if (!$rs) {
            throw new WtDbException(E_DB_EXECUTE_ERR);
        }
        if (!$rs->RecordCount()) {
            return false;
        }
        if ($rs->Fields('F01GCNO') || ($rs->Fields('M01KAINSTS') !== '00')) {
            WtApp::getLogger()->debug(sprintf('登録済みユーザーのため新規登録不可: M01GCNO=%s', $gcNo));
            $message = implode("\n", [
                '既に登録済のID番号になります。',
                'ID番号を確認いただき再度お試しいただくか、お問合せください。',
            ]);
            return false;
        }
        $expiryYmd = $rs->Fields('M01TDATE');
        if ($expiryYmd) {
            $ymd = date('Ymd', strtotime($expiryYmd));
            if ((int)$ymd < (int)date('Ymd')) {
                $message = '該当のID番号は有効期限が過ぎておりますので、ご利用いただけません。';
                return false;
            }
            $expiryYmd = $ymd;
        }
        $kenshuGroup = $rs->Fields('M01KENGROUP');
        // 券種グループは設定ファイルの有無で正しいものかを判定する
        $settingFile = sprintf('%ssettings/%s.ini.php', WT_ROOT_DIR, $kenshuGroup);
        if (!is_file($settingFile)) {
            WtApp::getLogger()->error(sprintf('該当券種の設定ファイルが存在しないため登録不可: M01GCNO=%s,M01KENGROUP=%s', $gcNo, $kenshuGroup));
            return false;
        }
        $gcInfo = (new SgGiftcardInfo($kenshuGroup, $gcNo, $rs->Fields('M01PIN'), '01'))->sync();
        $gcInfo->expiryYmd = $expiryYmd;
        //$gcInfo->usablePoints = $rs->Fields('M01POINT');
        // 対象外の券種の場合にはfalseを返す
        return $gcInfo->getCardInfo()->invalid ? false : $gcInfo;
    }

    private function login($loginId, $password)
    {
        // ログイン
        // ※TGCにはアクセスしない（コード認証成功時にTGCアクセス）
        $db = new LoginQuerySel();
        $db->setSelectSql('get-login-info');
        $db->setRecordsetArray([
            'LOGINID' => $loginId,
            'PASSWORD' => rincrypt($password),
        ]);
        $rs = $db->Execute();
        if (!$rs) {
            throw new WtDbException(E_DB_EXECUTE_ERR);
        }
        if (!$rs->RecordCount()) {
            return false;
        }
        if ($rs->RecordCount() !== 1) {
            // 同じログインID/パスワードが異なるレコードに登録されている (エラー)
            throw new \Exception(sprintf('同じログインID/パスワードが異なるレコードに登録されています: ログインID=%s', $loginId));
        }
        $cno = $rs->Fields('M01GCNO');
        $pin = $rs->Fields('M01PIN');
        $kenshuGroup = $rs->Fields('M00KENGROUP');
        $gcInfo = new SgGiftcardInfo($kenshuGroup, $cno, $pin, $rs->Fields('M01KAINSTS'));
        $gcInfo->userName = $rs->Fields('M01NAME');
        $gcInfo->email = $rs->Fields('M01EMAILPC');
        return $gcInfo; // この時点ではAPIを呼び出さない
    }

    private function updatePoints(SgGiftcardInfo $gcInfo)
    {
        // 保有カード情報の洗替
        $db = new LoginQueryIUD();
        foreach ($gcInfo->getCardList() as $gcDetail) {
            if (!$gcDetail->usable) {
                continue;
            }
            $db->setRecordsetArray([
                'ID' => 'shop',
                'PG' => 'S0621',
                'DATE' => date('Y-m-d-H.i.s'),
                'GCNO' => $gcDetail->cardNo,
                'POINT' => $gcDetail->point,
                'TDATE' => date('Y-m-d', strtotime($gcDetail->expiryYmd)),
            ]);
            $db->setSelectSql('update-f00');
            if (!$db->Execute()) {
                throw new WtDbException(E_DB_EXECUTE_ERR);
            }
        }
    }

    private function getKenshuList()
    {
        include_once WT_ROOT_DIR . 'wt/WtFileCache.php';
        $cache = new WtFileCache(WT_CACHE_DIR, 3600 * 4);
        $cacheId = 'KenshuList';
        $kenshuList = $cache->get($cacheId);
        if (empty($kenshuList)) {
            $kenshuList = $sortList = [];
            foreach (glob(sprintf('%ssettings/*.ini.php', WT_ROOT_DIR)) as $fileName) {
                $kenshuGroup = preg_replace('|\A.*/settings/(.*)\.ini\.php\z|', '$1', $fileName);
                $setting = include($fileName);
                $index = $setting['index'] ?? 0;
                if (($index > 0) && ($index < 1000) && strlen($setting['kenshu_name'] ?? '')) {
                    $kenshuList[$kenshuGroup] = $setting['kenshu_name'];
                    $sortList[$kenshuGroup] = $index;
                }
            }
            array_multisort($sortList, SORT_ASC, $kenshuList);
            $cache->save($cacheId, $kenshuList);
        }
        return $kenshuList;
    }
}
