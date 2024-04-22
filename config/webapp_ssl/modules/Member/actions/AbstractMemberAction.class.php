<?php
require_once(dirname(__DIR__, 2) . '/Order/config.php');

abstract class AbstractMemberAction extends SgAction
{
    /**
     * @var bool 認証が必要な画面かどうか
     */
    protected $_requireAuth = false;

    /**
     * @var bool アクションとモジュール名をセッションに保存するかどうか
     */
    protected $_saveCurrentAction = false;

    // 保護者（1人目）※必須
    const I_SEI_KANJI1  = 'sei_kanji1';
    const I_MEI_KANJI1  = 'mei_kanji1';
    const I_SEI_KANA1   = 'sei_kana1';
    const I_MEI_KANA1   = 'mei_kana1';
    const I_RELATION1   = 'rel1';

    // 保護者（2人目）※任意
    const I_SEI_KANJI2  = 'sei_kanji2';
    const I_MEI_KANJI2  = 'mei_kanji2';
    const I_SEI_KANA2   = 'sei_kana2';
    const I_MEI_KANA2   = 'mei_kana2';
    const I_RELATION2   = 'rel2';

    // お子さまの氏名・生年月日
    const I_SEI_KANJI3  = 'sei_kanji3';
    const I_MEI_KANJI3  = 'mei_kanji3';
    const I_SEI_KANA3   = 'sei_kana3';
    const I_MEI_KANA3   = 'mei_kana3';
    const I_BIRTHDAY3   = 'birthday3';
    const I_BIRTHDAY3_Y = 'birthday3_y';
    const I_BIRTHDAY3_M = 'birthday3_m';
    const I_BIRTHDAY3_D = 'birthday3_d';
    // 住所
    const I_ZIPCODE_1   = 'zipcode1';
    const I_ZIPCODE_2   = 'zipcode2';
    const I_ADDRESS_1   = 'address1';
    const I_ADDRESS_2   = 'address2';
    const I_ADDRESS_3   = 'address3';
    const I_TEL1        = 'tel1';
    const I_TEL1_1      = 'tel1_1';
    const I_TEL1_2      = 'tel1_2';
    const I_TEL1_3      = 'tel1_3';
    const I_TEL2        = 'tel2';
    const I_TEL2_1      = 'tel2_1';
    const I_TEL2_2      = 'tel2_2';
    const I_TEL2_3      = 'tel2_3';
    const I_PASSWORD1   = 'password';
    const I_PASSWORD2   = 'password_confirm';
    const S_EMAIL = 's_email';

    const I_F25PARAMS = 'f25_params';
    const I_F26PARAMS = 'f26_params';

    const SESSNAME_POSTS = 'sess_posts';

    /**
     * {@inheritdoc}
     */
    protected function _initPostParams($request)
    {
        // アンケートの送信名が不特定なのでバリデーションの優先順位は指定しない
        // ※指定すると指定された送信値以外のパラメータが無視されてしまうので
        $this->_postParams = [];
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _initialize($controller, $request, $user)
    {
        parent::_initialize($controller, $request, $user);
        $gcInfo = $this->gcInfo;
        if ($user->getAttribute('is_virtual_login') || !($gcInfo instanceof SgGiftcardInfo)) {
            $controller->redirect(WT_URL_BASE_SSL, true);
        }
        if (static::MODE === 'Register') {
            if ($user->isAuthenticated()) {
                $controller->redirect(WT_URL_BASE_SSL, true);
            }
            if (!$user->getModuleParam('__')) {
                $sessParams = $user->getAttribute('index_sess_params');
                if (!($sessParams['auth'] ?? null)) {
                    $controller->redirect(WT_URL_BASE, true);
                }
                $user->removeAttribute('index_sess_params');
                $user->setModuleParam('__', true);
            }
            include_once(WT_ROOT_DIR . 'webapp_ssl/modules/Order/common/Enquete.php');
            $request->setAttribute('enquete_info', Enquete::getInfo($gcInfo->kenshuGroup, $gcInfo->maincardNo));
        }
        $request->setAttribute(self::S_EMAIL, $gcInfo->email);
        $request->setAttribute('pref_list', WtUtil::getPrefOptions('都道府県選択'));
        $yList = ['' => '年'];
        for ($i = 0, $y = intval(date('Y')); $i < 100; $i++, $y--) {
            $yList[$y] = sprintf('%d年', $y);
        }
        $mList = ['' => '月'];
        for ($m = 1; $m <= 12; $m++) {
            $mList[sprintf('%02d', $m)] = sprintf('%d月', $m);
        }
        $dList = ['' => '日'];
        for ($d = 1; $d <= 31; $d++) {
            $dList[sprintf('%02d', $d)] = sprintf('%d日', $d);
        }
        $request->setAttribute('y_list', $yList);
        $request->setAttribute('m_list', $mList);
        $request->setAttribute('d_list', $dList);
        $yList = ['' => '年'];
        for ($i = 0, $y = intval(date('Y')); $i < 3; $i++, $y--) {
            $yList[$y] = sprintf('%d年', $y);
        }
        $request->setAttribute('y_list3', $yList);
        $relList = ['' => ''];
        foreach (CodeMaster::getCodeMaster('RELA', null, null, null, true) as $row) {
            $relList[$row['M03KEY2']] = $row['M03NAME'];
        }
        $request->setAttribute('rel_list', $relList);
    }
}
