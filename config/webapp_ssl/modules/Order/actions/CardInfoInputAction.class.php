<?php
/**
 * ProjectName : スマートギフトサイト
 * Subsystem   : ギフトカードを利用した商品交換モールサイト
 *
 * @package webapp_ssl
 */

/**
 * カード情報入力Actionクラス
 *
 * @author  Yuki Tanaka
 * @version Release:<1.0>
 */
class CardInfoInputAction extends AbstractOrderAction
{
    /**
     * 入力パラメータ
     */
    const CARD_TOKEN = Creditcard::CARD_TOKEN; // クレジットカード非通過対応用トークン

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $orderCommon = $this->orderCommon;
        if ($this->_isSubmit('BTN_BACK')
            || !$this->settings['billable']
            || !$this->isCreditcardEnable()
        ) {
            $controller->redirect($this->getActionUrl('Order', 'OrdererInfoInput'));
            return VIEW_NONE;
        }
        if ($this->_isSubmit('BTN_NEXT')) {
            $creditcardInfo = $user->getActionParam('creditcard_info');
            // クレジットカード使用
            $creditcardInfo[Creditcard::CREDITCARD_USE_FLG] = '2';
            $creditcardInfo[Creditcard::CARD_TOKEN] = $request->getParameter(self::CARD_TOKEN);
            $orderCommon->saveCreditcardInfo($creditcardInfo);
            $user->setAttribute('__payment_ok__', true);
            $controller->redirect($this->getActionUrl('Order', 'OrderConfirm'));
            return VIEW_NONE;
        }
        return $this->handleError($controller, $request, $user);
    }

    /**
     * {@inheritdoc}
     */
    function getDefaultView($controller, $request, $user)
    {
        $user->setAttribute('__payment_ok__', false);
        $orderCommon = $this->orderCommon;
        try {
            $orderCommon->isValidSession();
        } catch (Exception $e) {
            WtApp::getLogger()->warn($e->getMessage());
            $controller->redirect($this->getActionUrl('Order', 'OrdererInfoInput'));
            return VIEW_NONE;
        }
        if (!$this->settings['billable'] || !$this->isCreditcardEnable()) {
            $controller->redirect($this->getActionUrl('Order', 'OrdererInfoInput'));
            return VIEW_NONE;
        }
        $creditcardInfo = $orderCommon->getCreditcardInfo();
        $totalOrderPoint = $orderCommon->getTotalOrderPoint();
        $giftcardPoint = $this->gcInfo->usablePoints;
        if ($totalOrderPoint <= $giftcardPoint) {
            // ギフトカード残ポイントで購入可能な場合にはそのまま確認画面へ
            $creditcardInfo[Creditcard::CREDITCARD_USE_FLG] = '1';
            $creditcardInfo[Creditcard::CREDITCARD_PRICE] = 0;
            $creditcardInfo[Creditcard::CREDITCARD_TAX]   = 0;
            $creditcardInfo[Creditcard::CREDITCARD_TOTAL] = 0;
            $creditcardInfo[Creditcard::CARD_TOKEN] = '';
            $orderCommon->saveCreditcardInfo($creditcardInfo);
            $user->setAttribute('__payment_ok__', true);
            $controller->redirect($this->getActionUrl('Order', 'OrderConfirm'));
            return VIEW_NONE;
        }
        $ccPrice = $totalOrderPoint - $giftcardPoint;
        // 20230919 クレカの消費税はかからないように変更（課題管理表：項番4）
        // 20230921 内税に。。。
        $taxRate = getTaxRate(); // 0.1
        $ccTax = (int)ceil($ccPrice / ($taxRate + 1.0) * $taxRate); // 消費税は、切り上げ計算(元仕様のまま)
        $ccPrice -= $ccTax;
        $creditcardInfo[Creditcard::CREDITCARD_PRICE] = $ccPrice;
        $creditcardInfo[Creditcard::CREDITCARD_TAX]   = $ccTax;
        $creditcardInfo[Creditcard::CREDITCARD_TOTAL] = $ccPrice + $ccTax;
        $user->setActionParam('creditcard_info', $creditcardInfo);
        $request->setAttribute('creditcard_info', $creditcardInfo);
        $request->setAttribute('shohin_point', $totalOrderPoint);
        $request->setAttribute('shohin_info_list', $orderCommon->getShohinInfoList());
        $request->setAttribute('otodokesaki_list', $orderCommon->getOtodokesakiList());
        $request->setAttribute('chumonsha_info', $orderCommon->getChumonshaInfo());
        $yList = ['' => '年を選択'];
        for ($i = 0, $y = intval(date('Y')); $i < 10; $i++, $y++) {
            $yList[$y] = sprintf('%04d', $y);
        }
        $mList = ['' => '月を選択'];
        for ($m = 1; $m <= 12; $m++) {
            $mList[sprintf('%02d', $m)] = sprintf('%02d', $m);
        }
        $request->setAttribute('y_list', $yList);
        $request->setAttribute('m_list', $mList);
        include_once(WT_ROOT_DIR . 'util/payment/SgGmoMpClient.php');
        $gmoMp = new SgGmoMpClient();
        $request->setAttribute('gmo_shop_id',  $gmoMp->getShopId());
        $request->setAttribute('gmo_token_js', $gmoMp->getTokenJsUrl());
        return VIEW_INPUT;
    }

    /**
     * {@inheritdoc}
     */
    function validate($controller, $request, $user)
    {
        $appValidator = $this->_getValidator();
        if ($this->_isSubmit('BTN_NEXT')) {
            $token = trim($request->getParameter(self::CARD_TOKEN)); // 非通過対応用トークン
            if (!$token) {
                // クレジット決済用トークンが存在しない場合のエラーメッセージ
                WtApp::getLogger()->info('クレジット決済用トークンが送信されませんでした。');
                // 予期せぬエラーが発生しました。恐れ入りますが、もう一度お手続きください
                $appValidator->setCustomError('card_token_not_found', E_SYSTEM_ERROR);
            }
        }
        $appValidator->setErrors();
    }
}
