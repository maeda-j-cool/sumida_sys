<?php
require_once(__DIR__ . '/AbstractMemberAction.class.php');

class RegisterInputAction extends AbstractMemberAction
{
    const MODE = 'Register';

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        if ($this->_isSubmit('BTN_NEXT')) {
            // 入力値をアクションセッションから取得する。
            $postParams = $user->getActionParam(self::SESSNAME_POSTS);
            $mkDate = function($y, $m, $d) {
                $date = null;
                if ($y && $m && $d) {
                    return date('Y-m-d', mktime(0, 0, 0, $m, $d, $y));
                }
                return $date;
            };
            if (static::MODE === 'Register') {
                $postParams[self::I_BIRTHDAY3] = $mkDate(
                    (int)$postParams[self::I_BIRTHDAY3_Y],
                    (int)$postParams[self::I_BIRTHDAY3_M],
                    (int)$postParams[self::I_BIRTHDAY3_D]
                );
                $postParams[self::I_F25PARAMS] = $request->getAttribute(self::I_F25PARAMS);
                $postParams[self::I_F26PARAMS] = $request->getAttribute(self::I_F26PARAMS);
            }
            // 入力値を次画面へ引き渡すためにモジュールセッションに保存する。
            $user->setModuleParam(self::SESSNAME_POSTS, $postParams);
            // 確認画面へリダイレクトする。
            $controller->redirect($this->getActionUrl('Member', static::MODE . 'Confirm'));
            return VIEW_NONE;
        }
        return $this->getDefaultView($controller, $request, $user);
    }

    /**
     * {@inheritdoc}
     */
    function getDefaultView($controller, $request, $user)
    {
        // 画面上で1度でも送信ボタンが押下されていればアクションセッションから入力値を取得できる。
        $postParams = $user->getActionParam(self::SESSNAME_POSTS);
        if (empty($postParams)) {
            // 確認画面からの戻り遷移の場合はモジュールセッションから入力値を取得できる。
            // ※取得と同時にモジュールセッション情報を削除する。
            $postParams = $user->getModuleParam(self::SESSNAME_POSTS, true);
            if (empty($postParams)) {
                $postParams = $this->getInitPostParams($request, $user);
            }
            $user->setActionParam(self::SESSNAME_POSTS, $postParams);
        }
        // 入力値をリクエストオブジェクトに登録する。(画面表示用)
        if (is_array($postParams) && !empty($postParams)) {
            foreach ($postParams as $postName => $postValue) {
                $request->setParameter($postName, $postValue);
            }
            // 送信フラグを強制設定してリクエストオブジェクトから入力値を取得して表示するようにする。
            $request->setAttribute('is_execute', true);
        }
        return VIEW_INPUT;
    }

    /**
     * {@inheritdoc}
     */
    function validate($controller, $request, $user)
    {
        if ($this->_isSubmit('BTN_NEXT')) {
            $appValidator = $this->_getValidator();
            $relKeyList = array_keys($request->getAttribute('rel_list'));
            $appValidator->seiKanji(self::I_SEI_KANJI1, '保護者（1人目）のお名前（姓）', true);
            $appValidator->meiKanji(self::I_MEI_KANJI1, '保護者（1人目）のお名前（名）', true);
            $appValidator->seiHiragana(self::I_SEI_KANA1, '保護者（1人目）のふりがな（姓）', true);
            $appValidator->meiHiragana(self::I_MEI_KANA1, '保護者（1人目）のふりがな（名）', true);
            $appValidator->select(self::I_RELATION1, '保護者（1人目）の続柄', true, $relKeyList);
            $required = !!strlen(implode('', [
                trim($request->getParameter(self::I_SEI_KANJI2)),
                trim($request->getParameter(self::I_MEI_KANJI2)),
                trim($request->getParameter(self::I_SEI_KANA2)),
                trim($request->getParameter(self::I_MEI_KANA2)),
                trim($request->getParameter(self::I_RELATION2)),
            ]));
            $appValidator->seiKanji(self::I_SEI_KANJI2, '保護者（2人目）のお名前（姓）', $required);
            $appValidator->meiKanji(self::I_MEI_KANJI2, '保護者（2人目）のお名前（名）', $required);
            $appValidator->seiHiragana(self::I_SEI_KANA2, '保護者（2人目）のふりがな（姓）', $required);
            $appValidator->meiHiragana(self::I_MEI_KANA2, '保護者（2人目）のふりがな（名）', $required);
            $appValidator->select(self::I_RELATION2, '保護者（2人目）の続柄', $required, $relKeyList);
            if (static::MODE === 'Register') {
                $appValidator->seiKanji(self::I_SEI_KANJI3, 'お子さまのお名前（姓）', true);
                $appValidator->meiKanji(self::I_MEI_KANJI3, 'お子さまのお名前（名）', true);
                $appValidator->seiHiragana(self::I_SEI_KANA3, 'お子さまのふりがな（姓）', true);
                $appValidator->meiHiragana(self::I_MEI_KANA3, 'お子さまのふりがな（名）', true);
                $appValidator->ymdSeparate(
                    self::I_BIRTHDAY3,
                    self::I_BIRTHDAY3_Y,
                    self::I_BIRTHDAY3_M,
                    self::I_BIRTHDAY3_D,
                    'お子さまの生年月日',
                    true
                );
                if (!$appValidator->hasError(self::I_BIRTHDAY3_Y)
                    && !$appValidator->hasError(self::I_BIRTHDAY3_M)
                    && !$appValidator->hasError(self::I_BIRTHDAY3_D)
                ) {
                    $ymd = (int)sprintf('%04d%02d%02d',
                        (int)$request->getParameter(self::I_BIRTHDAY3_Y),
                        (int)$request->getParameter(self::I_BIRTHDAY3_M),
                        (int)$request->getParameter(self::I_BIRTHDAY3_D)
                    );
                    if ((int)date('Ymd') < $ymd) {
                        $appValidator->setCustomError(self::I_BIRTHDAY3_Y, 'お子さまの生年月日に未来の日付は指定できません。');
                    }
                }
            }
            $appValidator->zip3(self::I_ZIPCODE_1, '郵便番号1', true);
            $appValidator->zip4(self::I_ZIPCODE_2, '郵便番号2', true);
            $appValidator->address1(self::I_ADDRESS_1, '都道府県', true);
            $appValidator->address2(self::I_ADDRESS_2, '市区町村 番地', true, true);
            $appValidator->address3(self::I_ADDRESS_3, '建物名 部屋番号', false, true);
            $appValidator->telNumber(
                self::I_TEL1,
                self::I_TEL1_1,
                self::I_TEL1_2,
                self::I_TEL1_3,
                '電話番号1',
                true
            );
            $appValidator->telNumber(
                self::I_TEL2,
                self::I_TEL2_1,
                self::I_TEL2_2,
                self::I_TEL2_3,
                '電話番号2',
                false
            );
            if ((static::MODE === 'Register')
                && !$appValidator->hasError(self::I_SEI_KANJI3)
                && !$appValidator->hasError(self::I_MEI_KANJI3)
              //&& !$appValidator->hasError(self::I_TEL1)
              //&& !$appValidator->hasError(self::I_TEL1_1)
              //&& !$appValidator->hasError(self::I_TEL1_2)
              //&& !$appValidator->hasError(self::I_TEL1_3)
              //&& !$appValidator->hasError(self::I_TEL2)
              //&& !$appValidator->hasError(self::I_TEL2_1)
              //&& !$appValidator->hasError(self::I_TEL2_2)
              //&& !$appValidator->hasError(self::I_TEL2_3)
            ) {
                include_once(dirname(__DIR__, 2) . '/Default/querys/LoginQuerySel.class.php');
                //$tel1 = implode('', [
                //    $request->getParameter(self::I_TEL1_1),
                //    $request->getParameter(self::I_TEL1_2),
                //    $request->getParameter(self::I_TEL1_3),
                //]);
                //$tel2 = implode('', [
                //    $request->getParameter(self::I_TEL2_1),
                //    $request->getParameter(self::I_TEL2_2),
                //    $request->getParameter(self::I_TEL2_3),
                //]);
                $dbParams = [
                    'LOGINID' => $request->getAttribute(self::S_EMAIL),
                  //'TEL1'    => $tel1,
                    'CSEI'    => $request->getParameter(self::I_SEI_KANJI3),
                    'CMEI'    => $request->getParameter(self::I_MEI_KANJI3),
                ];
                //if (strlen($tel2)) {
                //    $dbParams['TEL2'] = $tel2;
                //}
                $db = new LoginQuerySel();
                $db->setSelectSql('get-login-info');
                $db->setRecordsetArray($dbParams);
                $rs = $db->Execute();
                if (!$rs) {
                    throw new WtDbException(E_DB_EXECUTE_ERR);
                }
                if ($rs->RecordCount()) {
                    $appValidator->setCustomError(self::I_SEI_KANJI3, sprintf('お子さまの名前は既にこのメールアドレスに登録されています：%s%s', $dbParams['CSEI'], $dbParams['CMEI']));
                }
            }
            $passwordRequired = false;
            $password1 = trim($request->getParameter(self::I_PASSWORD1));
            $password2 = trim($request->getParameter(self::I_PASSWORD2));
            if ((static::MODE === 'Register')
                || (strlen($password1) || strlen($password2))
            ) {
                $passwordRequired = true;
            }
            if (!strlen($password1)) {
                if ($passwordRequired) {
                    $appValidator->setCustomError(self::I_PASSWORD1, '新しいパスワードを入力してください。');
                }
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
                if ($passwordRequired) {
                    $appValidator->setCustomError(self::I_PASSWORD2, '新しいパスワード（確認）を入力してください。');
                }
            } elseif (strlen($password1) && ($password1 !== $password2)) {
                $appValidator->setCustomError(self::I_PASSWORD2, '新しいパスワードと新しいパスワード（確認）が一致しません。');
            }
            if (strlen($password1 . $password2)
                && !$appValidator->hasError(self::I_PASSWORD1)
                && !$appValidator->hasError(self::I_PASSWORD2)
            ) {
                // 同一パスワードチェック
                include_once(dirname(__DIR__) . '/querys/MemberQuerySel.class.php');
                $db = new MemberQuerySel();
                $db->setSelectSql('same-user-password-check');
                $db->setRecordsetArray([
                    'LOGINID'  => $request->getAttribute(self::S_EMAIL),
                    'PASSWORD' => rincrypt($password1),
                    'KENGROUP' => $this->targetKenshuGroup, // 除外検索用
                ]);
                $rs = $db->Execute();
                if (!$rs) {
                    throw new Exception(E_DB_EXECUTE_ERR);
                }
                if ($rs->RecordCount()) {
                    $appValidator->setCustomError(self::I_PASSWORD1, '既に登録された情報になります。パスワードを変更してください。');
                }
            }
            if (static::MODE === 'Register') {
                $enqueteInfo = $request->getAttribute('enquete_info');
                if ($enqueteInfo) {
                    $f25Params = [
                        'F25ENQANSID' => null,
                        'F25KENGROUP' => $this->gcInfo->kenshuGroup,
                        'F25WJUCNO' => null,
                        'F25GCNO' => $this->gcInfo->maincardNo,
                    ];
                    $f26Params = [];
                    $freeTextRequired = false;
                    $hasInputs = false;
                    foreach ($enqueteInfo as $eid => $enquete) {
                        $postName = sprintf('enquete%d', $eid);
                        $dispName = sprintf('アンケート%dの回答', intval($enquete['M36SEQ']));
                        $required = ($enquete['M36REQUIRED'] == '1');
                        if ($enquete['M36OPTIONTYPE'] == '20') {
                            // 自由入力
                            switch ($enquete['M36INPUTTYPE']) {
                                case 'Email':
                                    $appValidator->emailRfc($postName, $dispName, $required);
                                    break;
                                case 'MobilePhone':
                                    $appValidator->numeric($postName, $dispName, $required, 9, 11, false);
                                    break;
                                case 'Number':
                                    $appValidator->numeric($postName, $dispName, $required, null, 200);
                                    break;
                                default:
                                    $appValidator->length($postName, $dispName, $required, null, 200);
                                    break;
                            }
                            $v = $request->getParameter($postName);
                            if (!$hasInputs && strlen(WtString::trim($v))) {
                                $hasInputs = true;
                            }
                            $f26Params[] = [
                                'F26ENQANSID' => null,
                                'F26ENQID' => $eid,
                                'F26ENQOPID' => 0, // @TODO 確認（PKになっているので）
                                'F26ENQOPFREE' => $v,
                            ];
                        } else {
                            $appValidator->select($postName, $dispName, $required, array_keys($enquete['M37']));
                            $postValues = $request->getParameter($postName);
                            if ((is_array($postValues) && !empty($postValues)) || strlen((string)$postValues)) {
                                if (!is_array($postValues)) {
                                    $postValues = [$postValues];
                                }
                                $optionsMax = intval($enquete['M36OPTIONS']);
                                if ($optionsMax && (count($postValues) > $optionsMax)) {
                                    $appValidator->setCustomError($postName, sprintf('%sは%d項目以内で選択してください。', $dispName, $optionsMax));
                                } else {
                                    foreach ($enquete['M37'] as $oid => $m37) {
                                        if (in_array($oid, $postValues)) {
                                            $hasInputs = true;
                                            $f26Temp = [
                                                'F26ENQANSID' => null,
                                                'F26ENQID' => $eid,
                                                'F26ENQOPID' => $oid,
                                                'F26ENQOPFREE' => null,
                                            ];
                                            if ($m37['M37HASFREE'] == '1') {
                                                $postName = sprintf('enquete%d_%d', $eid, $oid);
                                                if ($enquete['M36SEQ'] == 1) {
                                                    // 墨田区専用（特殊）
                                                    $dispName = sprintf('アンケート%dの回答：%s', intval($enquete['M36SEQ']), $m37['M37TEXT']);
                                                    $postNameN = $postName . '_n';
                                                    $dispNameN = $dispName . 'の人数';
                                                    $postNameA1 = $postName . '_age1';
                                                    $postNameA2 = $postName . '_age2';
                                                    $postNameA3 = $postName . '_age3';
                                                    $dispNameAx = $dispName . 'の年齢';
                                                    $appValidator->number($postNameN, $dispNameN, true, 1, 99);
                                                    $appValidator->numeric($postNameA1, $dispNameAx, false, null, 2);
                                                    if (!$appValidator->hasError($postNameA1)) {
                                                        $appValidator->numeric($postNameA2, $dispNameAx, false, null, 2);
                                                        if (!$appValidator->hasError($postNameA2)) {
                                                            $appValidator->numeric($postNameA3, $dispNameAx, false, null, 2);
                                                        }
                                                    }
                                                    if (!$appValidator->hasError($postNameA1) && !$appValidator->hasError($postNameA2) && !$appValidator->hasError($postNameA3)) {
                                                        $ageList = [
                                                            trim($request->getParameter($postNameA1)),
                                                            trim($request->getParameter($postNameA2)),
                                                            trim($request->getParameter($postNameA3)),
                                                        ];
                                                        $ageTemp = [];
                                                        foreach ($ageList as $age) {
                                                            if (strlen($age)) {
                                                                $ageTemp[] = sprintf('%s歳', $age);
                                                            }
                                                        }
                                                        if (empty($ageTemp)) {
                                                            $appValidator->setCustomError($postName, sprintf('%sを入力してください。', $dispNameAx));
                                                        } else {
                                                            $f26Temp['F26ENQOPFREE'] = sprintf(
                                                                '■人数：%s名　■年齢：%s',
                                                                $request->getParameter($postNameN),
                                                                implode(', ', $ageTemp)
                                                            );
                                                        }
                                                    }
                                                } else {
                                                    $dispName = sprintf('アンケート%dの回答', intval($enquete['M36SEQ']));
                                                    $appValidator->length($postName, $dispName, $freeTextRequired, null, 200);
                                                    $f26Temp['F26ENQOPFREE'] = $request->getParameter($postName);
                                                }
                                            }
                                            $f26Params[] = $f26Temp;
                                        }
                                    }
                                }
                            } else {
                                foreach ($enquete['M37'] as $oid => $m37) {
                                    if ($m37['M37HASFREE'] == '1') {
                                        $postName = sprintf('enquete%d_%d', $eid, $oid);
                                        if (strlen(WtString::trim($request->getParameter($postName)))) {
                                            $seq = intval($enquete['M36SEQ']);
                                            $appValidator->setCustomError($postName, sprintf('アンケート%dの回答を入力する場合は「%s」を選択してください。', $seq, $m37['M37TEXT']));
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (!$appValidator->hasErrors() && $hasInputs) {
                        $request->setAttribute(self::I_F25PARAMS, $f25Params);
                        $request->setAttribute(self::I_F26PARAMS, $f26Params);
                    }
                }
            }
            $appValidator->setErrors();
            // 画面のリロード時にも入力値を保持するために送信値を一時保存
            // ※バリデータで文字列変換が行われている場合があるためバリーデート処理の後で行う。
            $postParams = $user->getActionParam(self::SESSNAME_POSTS);
            if (!is_array($postParams)) {
                $postParams = [];
            }
            $postParams = array_merge($postParams, $request->getParameters());
            $user->setActionParam(self::SESSNAME_POSTS, $postParams);
        }
    }

    protected function getInitPostParams($request, $user)
    {
        // 初期値設定
        $postParams = [
            self::S_EMAIL => $request->getAttribute(self::S_EMAIL),
            self::I_SEI_KANJI1  => '',
            self::I_MEI_KANJI1  => '',
            self::I_SEI_KANA1   => '',
            self::I_MEI_KANA1   => '',
            self::I_RELATION1   => '',
            self::I_SEI_KANJI2  => '',
            self::I_MEI_KANJI2  => '',
            self::I_SEI_KANA2   => '',
            self::I_MEI_KANA2   => '',
            self::I_RELATION2   => '',
            self::I_SEI_KANJI3  => '',
            self::I_MEI_KANJI3  => '',
            self::I_SEI_KANA3   => '',
            self::I_MEI_KANA3   => '',
            self::I_BIRTHDAY3   => '',
            self::I_BIRTHDAY3_Y => '',
            self::I_BIRTHDAY3_M => '',
            self::I_BIRTHDAY3_D => '',
            self::I_ZIPCODE_1   => '',
            self::I_ZIPCODE_2   => '',
            self::I_ADDRESS_1   => '',
            self::I_ADDRESS_2   => '',
            self::I_ADDRESS_3   => '',
            self::I_TEL1        => '',
            self::I_TEL1_1      => '',
            self::I_TEL1_2      => '',
            self::I_TEL1_3      => '',
            self::I_TEL2        => '',
            self::I_TEL2_1      => '',
            self::I_TEL2_2      => '',
            self::I_TEL2_3      => '',
            self::I_PASSWORD1   => '',
            self::I_PASSWORD2   => '',
        ];
        return $postParams;
    }
}
