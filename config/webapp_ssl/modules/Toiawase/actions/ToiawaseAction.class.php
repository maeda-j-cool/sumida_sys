<?php
require_once __DIR__ . '/AbstractToiawaseAction.class.php';

class ToiawaseAction extends AbstractToiawaseAction
{
    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        if ($this->_isSubmit('BTN_NEXT')) {
            $postParams = $user->getActionParam(self::SESSNAME_POSTS);
            $user->setModuleParam(self::SESSNAME_POSTS, $postParams);
            // 確認画面へリダイレクトする。
            $controller->redirect($this->getActionUrl('Toiawase', 'ToiawaseKakunin'));
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
            $isVirtual = $user->getAttribute('is_virtual_login');
            if (empty($postParams) && !$isVirtual && $user->isAuthenticated()) {
                $postParams = [];
                // ログイン済＋利用者情報登録ありの場合にはユーザー情報を初期値設定
                include_once(dirname(__DIR__, 2) . '/Member/querys/MemberQuerySel.class.php');
                $db = new MemberQuerySel();
                $db->setSelectSql('get-register-info');
                $db->setRecordsetArray(['GCNO' => $this->gcInfo->maincardNo]);
                $rs = $db->Execute();
                if ($rs && $rs->RecordCount()) {
                    $postParams[self::I_SEI_KANJI] = $rs->Fields('F01SEI');
                    $postParams[self::I_MEI_KANJI] = $rs->Fields('F01MEI');
                    $postParams[self::I_SEI_KANA]  = $rs->Fields('F01SEIKN');
                    $postParams[self::I_MEI_KANA]  = $rs->Fields('F01MEIKN');
                    $postParams[self::I_EMAIL]     = $rs->Fields('M01EMAILPC');
                    $postParams[self::I_TEL1]      = $rs->Fields('F01TEL11');
                    $postParams[self::I_TEL2]      = $rs->Fields('F01TEL12');
                    $postParams[self::I_TEL3]      = $rs->Fields('F01TEL13');
                  //$postParams[self::I_GIFTCARD_NO] = $this->gcInfo->maincardNo;
                }
            }
            $user->setActionParam(self::SESSNAME_POSTS, $postParams);
        }
        // 入力値をリクエストオブジェクトに登録する。(画面表示用)
        if (is_array($postParams)) {
            foreach ($postParams as $postName => $postValue) {
                $request->setParameter($postName, $postValue);
            }
        }
        // 送信フラグを強制設定してリクエストオブジェクトから入力値を取得して表示するようにする。
        $request->setAttribute('is_execute', true);
        return VIEW_INPUT;
    }

    /**
     * {@inheritdoc}
     */
    function validate($controller, $request, $user)
    {
        if ($this->_isSubmit('BTN_NEXT')) {
            $appValidator = $this->_getValidator();
            $appValidator
                ->seiKanji(self::I_SEI_KANJI, '姓（漢字）', true)
                ->meiKanji(self::I_MEI_KANJI, '名（漢字）', true)
                ->seiHiragana(self::I_SEI_KANA, '姓（かな）', true)
                ->meiHiragana(self::I_MEI_KANA, '名（かな）', true)
                ->emailRfc(self::I_EMAIL, 'メールアドレス', true)
                ->emailRfc(self::I_EMAIL_CF, '確認用メールアドレス', true)
            ;
            if (!$appValidator->hasError(self::I_EMAIL)
                && !$appValidator->hasError(self::I_EMAIL_CF)
                && (trim($request->getParameter(self::I_EMAIL)) !== trim($request->getParameter(self::I_EMAIL_CF)))
            ) {
                $appValidator->setCustomError(self::I_EMAIL_CF, '確認用メールアドレスがメールアドレスと異なります。');
            }
            $appValidator->telNumber(self::I_TEL, self::I_TEL1, self::I_TEL2, self::I_TEL3, '電話番号', true);
            $optionInquiryItems = $request->getAttribute('option_inquiry_items');
            if (!empty($optionInquiryItems)) {
                $appValidator->select(self::I_INQUIRY_ITEM, 'お問い合わせ項目', true, array_keys($optionInquiryItems));
            }
            $appValidator
                //->alphaNumeric(self::I_ORDER_NO, 'お申し込み番号', false, 1, 10)
                ->select(self::I_PI_CONSENT, '個人情報取り扱い規定に同意する', true, ['1'])
            ;
            $appValidator
                ->h2z(self::I_INQUIRY_TEXT)
                ->length(self::I_INQUIRY_TEXT, 'お問い合わせ内容', true, null, 350);
            $this->_validate($appValidator);
            $appValidator->setErrors();
            // 画面のリロード時にも入力値を保持するために送信値を一時保存
            // ※バリデータで文字列変換が行われている場合があるためバリーデート処理の後で行う。
            $postParams = $user->getActionParam(self::SESSNAME_POSTS);
            if (!is_array($postParams)) {
                $postParams = [];
            }
            foreach (array_keys($this->_postParams) as $k) {
                $postParams[$k] = $request->getParameter($k);
            }
            $user->setActionParam(self::SESSNAME_POSTS, $postParams);
        }
    }
}
