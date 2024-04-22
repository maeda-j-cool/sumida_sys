<?php
abstract class AbstractToiawaseAction extends SgAction
{
    /**
     * @var bool 認証が必要な画面かどうか
     */
    protected $_requireAuth = false;

    /**
     * @var bool アクションとモジュール名をセッションに保存するかどうか
     */
    protected $_saveCurrentAction = true;

    /**
     * @var bool
     */
    protected $defaultOnly = true;

    const I_SEI_KANJI = 'sei_kanji';
    const I_MEI_KANJI = 'mei_kanji';
    const I_SEI_KANA = 'sei_kana';
    const I_MEI_KANA = 'mei_kana';
    const I_EMAIL = 'email';
    const I_EMAIL_CF = 'email_confirm';
    const I_TEL  = 'tel';
    const I_TEL1 = 'tel1';
    const I_TEL2 = 'tel2';
    const I_TEL3 = 'tel3';
    const I_INQUIRY_ITEM = 'inquiry_item';
    const I_INQUIRY_TEXT = 'inquiry_text';
    const I_GIFTCARD_NO  = 'giftcard_no';
    const I_ORDER_NO     = 'order_no';
    const I_PI_CONSENT   = 'personal_information_consent'; // 個人情報に関する同意

    const SESSNAME_POSTS = 'sess_inquiry_posts';

    /**
     * {@inheritdoc}
     */
    protected function _initPostParams($request)
    {
        $this->_postParams = [
            self::I_SEI_KANJI => 110,
            self::I_MEI_KANJI => 111,
            self::I_SEI_KANA => 120,
            self::I_MEI_KANA => 121,
            self::I_EMAIL => 130,
            self::I_EMAIL_CF => 131,
            self::I_TEL  => 140,
            self::I_TEL1 => 141,
            self::I_TEL2 => 142,
            self::I_TEL3 => 143,
            self::I_INQUIRY_ITEM => 200,
            self::I_INQUIRY_TEXT => 201,
          //self::I_GIFTCARD_NO  => 210,
          //self::I_ORDER_NO     => 220,
            self::I_PI_CONSENT   => 300,
        ];
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _initialize($controller, $request, $user)
    {
        parent::_initialize($controller, $request, $user);
        if ($user->getAttribute('is_virtual_login')) {
            // 仮想ギフトカードの場合はヘッダの残ポイントなどの表示をしない(問い合わせのみ)
            $request->setAttribute('user_name', null);
            $request->setAttribute('expiry_ymd', null);
            $request->setAttribute('remain_point', null);
        }
        $optionInquiryItems = $user->getModuleParam('option_inquiry_items');
        if (empty($optionInquiryItems)) {
            $db = new ToiawaseCodeQuerySel();
            $db->setSelectSql('1');
            $db->setRecordsetArray(['kenshu_group' => $this->targetKenshuGroup]);
            $rs = $db->Execute();
            if (!$rs) {
                // エラー発生時には自動ログ出力されている @see DBConnect::Execute()
                throw new \Exception(E_DB_EXECUTE_ERR);
            }
            $optionInquiryItems = [];
            if ($rs->RecordCount()) {
                $optionInquiryItems[''] = 'お問い合わせ項目を選択';
                while (!$rs->EOF) {
                    $optionInquiryItems[$rs->Fields('M03KEY2')] = $rs->Fields('M03NAME');
                    $rs->MoveNext();
                }
            }
            $user->setModuleParam('option_inquiry_items', $optionInquiryItems);
        }
        $request->setAttribute('option_inquiry_items', $optionInquiryItems);
    }
}
