<?php
class OrderHistoryAction extends SgAction
{
    /**
     * @var bool 認証が必要な画面かどうか
     */
    protected $_requireAuth = true;

    /**
     * @var bool アクションとモジュール名をセッションに保存するかどうか
     */
    protected $_saveCurrentAction = true;

    /**
     * {@inheritdoc}
     */
    protected function _initialize($controller, $request, $user)
    {
        // 仮想ログインのチェック
        if ($user->getAttribute('is_virtual_login')) {
            $controller->redirect(WT_URL_BASE_SSL, true);
        }
        parent::_initialize($controller, $request, $user);
    }

    /**
     * {@inheritdoc}
     */
    public function execute($controller, $request, $user)
    {
        $controller->redirect(WT_URL_BASE_SSL . 'systemerror/error.html');
        return VIEW_NONE;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultView($controller, $request, $user)
    {
        $giftCardNo  = $this->gcInfo->maincardNo;
        $giftCardPin = $this->gcInfo->maincardPin;
        $histories = $this->_getHistories($request, $giftCardNo, $giftCardPin);
        $request->setAttribute('histories', $histories);
        return VIEW_INPUT;
    }

    /**
     * 交換履歴情報の取得
     *
     * @param WtRequest $request WtRequestオブジェクト
     * @param string    $cardNo  ギフトカード番号
     * @param string    $pinNo   PIN番号
     * @return \stdClass 交換履歴情報
     * @see    TgcGiftcard::callHistoryApi
     */
    protected function _getHistories($request, $cardNo, $pinNo)
    {
        $histories = null;
        $orderNo = SgGiftcardInfo::getTgcSlipNo();
        try {
            $tgc = (new SgGiftcardClient())
                ->setLogFile(WT_TGC_LOG_PATH . 'TGC_' .date('Ymd') . '.log')
                ->initRequest()
                ->setCardNo($cardNo, $pinNo)
            ;
            // 履歴取得の対象とする取引区分:"1003" => 売上(減算)
            $histories = $tgc->getHistories($orderNo, 30, 1, ['1003']);
            if ($histories->responseInfo->errorCd !== '0') {
                // エラーの表示方法を変更
                $request->setError('error', implode("\n", [
                    'スマートギフトカードは現在ご利用できません。',
                    '正しい情報を入力しても本メッセージが表示される場合は、お問い合わせフォーム、またはフリーコール（' . $this->settings['call_center_phone'] . '）よりお問い合わせください。',
                    '※' . $this->settings['call_center_phone'],
                ]));
                $histories = null;
            } else {
                // 券種コードチェック
                if (!in_array($histories->cardInfo->designCd, WT_ENABLE_KENCD_LIST)) {
                    $request->setError('error', 'ご入力いただいたスマートギフトカードは本サイトではご利用いただくことができません。');
                    $histories = null;
                }
            }
        } catch (ServiceUnavailableException $e) {
            WtApp::getLogger()->warn($e->getMessage());
            $request->setError('error', '誠に申し訳ありません。只今メンテナンス中です。');
        }
        return $histories;
    }
}
