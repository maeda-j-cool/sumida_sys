<?php
/**
 * ProjectName : スマートギフトサイト
 * Subsystem   : ギフトカードを利用した商品交換モールサイト
 *
 * @package webapp_ssl
 */

/**
 * 注文完了入力Actionクラス
 *
 * @author  Yuki Tanaka
 * @version Release:<1.0>
 */
class OrderCompleteAction extends SgAction
{
    /**
     * @var bool 認証が必要な画面かどうか
     */
    protected $_requireAuth = true;

    /**
     * {@inheritdoc}
     */
    function getDefaultView($controller, $request, $user)
    {
        $request->setAttribute('order_no_list', $user->getAttribute('order_no_list'));
        $request->setAttribute('gtm_layer_tag', $user->getModuleParam('gtm_layer_tag'));
        if ($user->hasAttribute('__init_order__')) {
            // 確認画面のリダイレクト時にセッションが保存されない不具合の暫定対応
            // ※3Dセキュアからのリダイレクトで注文完了した場合にセッションが保存されない不具合が発生
            // ※2023/10/09時点では原因が分からず、、、というわけでここで暫定対応
            (new OrderCommonClass($user))->removeOrderSession();
            $user->removeAttribute(OrderCommonClass::SESSNAME_SHOHIN_LIST);
            $user->removeAttribute('__payment_ok__');
            $user->removeAttribute('__init_order__');
            $user->removeAttribute('gmo_tds2_params');
            $user->removeAttribute('f25_params');
            $user->removeAttribute('f26_params');
            $gcInfo = $this->gcInfo->sync();
            $remainPoint = $gcInfo->usablePoints;
            $expiryYmd = $remainPoint ? $gcInfo->expiryYmd : null;
            $request->setAttribute('expiry_ymd', $expiryYmd);
            $request->setAttribute('remain_point', $remainPoint);
            $user->setGiftcardInfo($gcInfo);
            $user->store(false);
        }
        // メール送信失敗した場合のメッセージを表示
        if ($request->hasParameter('failureMail')) {
            $request->setError('CANT_SEND_MAIL_ERROR', implode("\n", [
                'お申し込み確認メール送信に失敗しました。',
                sprintf('お申し込み情報がメールで必要な場合は、お問い合わせフォーム、またはフリーコール（%s）よりお問い合わせください。', $this->settings['call_center_phone']),
            ]));
        }
        return VIEW_INPUT;
    }
}
