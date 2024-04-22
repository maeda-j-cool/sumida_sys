<?php
require_once(WT_ROOT_DIR . 'util/payment/SgGmoMpClient.php');
require_once(__DIR__ . '/OrderConfirmAction.class.php');

class Tds2CallbackAction extends AbstractOrderAction
{
    /**
     * {@inheritdoc}
     */
    public function execute($controller, $request, $user)
    {
        if (!$user->getAttribute('__payment_ok__')) {
            $controller->redirect($this->getActionUrl('Order', 'CardInfoInput'));
            return VIEW_NONE;
        }
        $user->setLoginCookie('Lax'); // 一時的にSamesite=NoneにしていたCookieを元に戻す
        $tds2Params = $user->getAttribute('gmo_tds2_params');
        WtApp::getLogger()->info('3DS2-callback-session-params:' . "\n" . print_r($tds2Params, true));
        if (empty($tds2Params)
            || !isset($tds2Params['tds2_ready'])
            || !isset($tds2Params['access_id'])
            || !isset($tds2Params['access_pass'])
        ) {
            WtApp::getLogger()->info('3DS2-callback: invalid-session-params');
            $user->removeAttribute('gmo_tds2_params')->store(false);
            $controller->redirect($this->getActionUrl('Order', 'OrderConfirm'));
            return VIEW_NONE;
        }
        $accessId   = $tds2Params['access_id'];
        $accessPass = $tds2Params['access_pass'];
        $receiveParams = [
            // 取引ID
            'MD' => $request->getParameter('MD'),
            // リクエスター取引ID（未使用）: 半角英数32桁 固定
            'RequestorTransId' => $request->getParameter('requestorTransId'),
            // イベント
            // ・3DSMethodFinished
            //     3DS2.0初期化処理が完了しました。3DS2.0認証処理を実行してください。
            // ・3DSMethodSkipped
            //     3DS2.0初期化処理をスキップした。3DS2.0認証処理を実行してください。
            // ・AuthResultReady
            //     3DS2.0認証結果取得の準備が完了しました。
            'Event' => $request->getParameter('event'),
            // 3DS2.0認証パラメータ: 半角英数2000桁
            'Param' => $request->getParameter('param'),
        ];
        WtApp::getLogger()->info('3DS2-callback-receive-params:' . "\n" . print_r($receiveParams, true));
        $gmoMp = new SgGmoMpClient();
        $gmoMp->setLogFile(sprintf('%sgmo/gmo_%s.log', WT_LOG_DIR, date('Ymd')));
        switch ($receiveParams['Event']) {
            case '3DSMethodFinished':
            case '3DSMethodSkipped':
                break;
            case 'AuthResultReady':
                if (isset($tds2Params['tds2_challenge'])) {
                    // チャレンジからのコールバック時には認証結果を取得する必要がある
                    unset($tds2Params['tds2_challenge']);
                    $result = $gmoMp->doTds2Result($accessId, $accessPass);
                    WtApp::getLogger()->info('3DS2-challenge-result:' . "\n" . print_r($result, true));
                    if (!in_array($result['Tds2TransResult'], ['Y', 'A'], true)) {
                        // 'N': 未認証／口座未確認。取引拒否
                        // 'U': 認証／口座確認を実行できなかった
                        // 'R': 認証／口座確認が拒否された
                        // 取引中断
                        $user->setModuleParam('tds2_error', '3Dセキュア認証に失敗しました');
                        $user->removeAttribute('gmo_tds2_params')->store(false);
                        $controller->redirect($this->getActionUrl('Order', 'OrderConfirm'));
                        return VIEW_NONE;
                    }
                    // 'Y': 認証／口座確認に成功
                    // 'A': 処理の試行が実施された
                    // 認証後決済実行へ（成功）
                }
                $tds2Params['tds2_ready'] = true;
                $user->setAttribute('gmo_tds2_params', $tds2Params)->store(false);
                $controller->redirect($this->getActionUrl('Order', 'OrderConfirm'));
                return VIEW_NONE;
        }
        $result = $gmoMp->doTds2Auth($accessId, $accessPass, $receiveParams['Param']);
        WtApp::getLogger()->info('3DS2-auth-result:' . "\n" . print_r($result, true));
        if (in_array($result['Tds2TransResult'], ['Y', 'A'], true)) {
            // 'Y': 認証／口座確認に成功
            // 'A': 処理の試行が実施された
            // 認証後決済実行へ（成功）
            $tds2Params['tds2_ready'] = true;
            $user->setAttribute('gmo_tds2_params', $tds2Params)->store(false);
            $controller->redirect($this->getActionUrl('Order', 'OrderConfirm'));
            return VIEW_NONE;
        }
        if ($result['Tds2TransResult'] === 'C') {
            // 'C': 認証チャレンジが必要
            if (isset($result['ChallengeUrl'])
                && $gmoMp->isValidTds2RedirectUrl($result['ChallengeUrl'])
            ) {
                $tds2Params['tds2_challenge'] = true;
                $user->setAttribute('gmo_tds2_params', $tds2Params);
                $this->prepareTds2ApiRedirect();
                $controller->redirect($result['ChallengeUrl']);
                return VIEW_NONE;
            }
        }
        // 'N': 未認証／口座未確認。取引拒否
        // 'U': 認証／口座確認を実行できなかった
        // 'R': 認証／口座確認が拒否された
        // 取引中断
        $user->setModuleParam('tds2_error', '3Dセキュア認証に失敗しました');
        $user->removeAttribute('gmo_tds2_params')->store(false);
        $controller->redirect($this->getActionUrl('Order', 'OrderConfirm'));
        return VIEW_NONE;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultView($controller, $request, $user)
    {
        $controller->redirect($this->getActionUrl('Order', 'OrderConfirm'));
        return VIEW_NONE;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($controller, $request, $user)
    {
    }
}
