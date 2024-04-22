<?php
/**
 * ProjectName : スマートギフトサイト
 * Subsystem   : ギフトカードを利用した商品交換モールサイト
 *
 * @package webapp_ssl
 */

require_once(dirname(__DIR__) . '/common/DgcTrait.php');

/**
 * カード情報入力Actionクラス
 *
 * @author  Yuki Tanaka
 * @version Release:<1.0>
 */
class OrderConfirmAction extends AbstractOrderAction
{
    use DgcTrait;

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $orderCommon = $this->orderCommon;
        // セッション保存値の検証
        try {
            $orderCommon->isValidSession();
        } catch (Exception $e) {
            WtApp::getLogger()->warn($e->getMessage());
            // 注文系のセッションを削除
            $orderCommon->removeOrderSession();
            //システムエラーに遷移
            $controller->redirect(WT_URL_SYSERROR);
            return VIEW_NONE;
        }
        if ($this->_isSubmit('BTN_BACK')) {
            $redirectUrl = $this->getActionUrl('Order', 'CardInfoInput');
            $creditcardInfo = $orderCommon->getCreditcardInfo();
            if ($creditcardInfo[Creditcard::CREDITCARD_USE_FLG] !== '2') {
                $redirectUrl = $this->getActionUrl('Order', 'OrdererInfoInput');
            }
            $user->setAttribute('fix_flg', true);
            $controller->redirect($redirectUrl);
            return VIEW_NONE;
        }
        if ($this->_isSubmit('BTN_QCHG')) {
            $user->setAttribute('fix_flg', true);
            $controller->redirect($this->getActionUrl('Order', 'OrdererInfoInput'));
            return VIEW_NONE;
        }
        if ($this->_isSubmit('BTN_NEXT')) {
            // クレジットカード、ギフトカード取り消し、商品在庫戻しにおけるエラー内容を格納
            $errorCodeArray = array();
            $orderItems = array();
            $doCredit   = false;
            try {
                try {
                    // ギフトカード残高照会
                    // ・ギフトカード残高をAPIから再取得してセッション値と比較する
                    $errorCodeArray = $orderCommon->checkIntegrityOfUsePoint();
                    // 変更されていた場合にヘッダなどの表示に反映させる必要があるので再設定しておく
                    // ※リダイレクトしないエラー表示時用
                    $request->setAttribute('user_name', $this->gcInfo->userName);
                    $request->setAttribute('expiry_ymd', $this->gcInfo->expiryYmd);
                    $request->setAttribute('remain_point', $this->gcInfo->usablePoints);
                    if (!empty($errorCodeArray)) {
                        throw new Exception('[FAILURE] OrderCommonClass::checkIntegrityOfUsePoint');
                    }
                    // 商品有効チェック
                    $shohinInfoList = $orderCommon->getShohinInfoList();
                    $shohinObjListTemp = array();
                    $errorCodeArray = array();
                    $orderItemsTemp = array();
                    $otodokesakiList = $orderCommon->getOtodokesakiList();
                    // DB上の最新データに更新する対象項目
                    $shohinUpdateKeys = array(
                      //ShohinInformation::SHOHIN_NO,                  // 商品番号
                      //ShohinInformation::SHOHIN_CODE,                // 商品コード
                      //ShohinInformation::BRAND_NAME,                 // ブランド名
                      //ShohinInformation::SHOHIN_NAME,                // 商品名
                      //ShohinInformation::SHOHIN_NAME_KANA,           // 商品名カナ
                      //ShohinInformation::HAISO_KEITAI,               // 配送形態
                      //ShohinInformation::HAISO_MOTO_SHIKIBETSU_CODE, // 配送元識別コード
                      //ShohinInformation::KAKAKU_ZEINUKI,             // 商品価格税抜き
                        ShohinInformation::KISETSU_SHOHIN_FLG,         // 季節商品フラグ
                        ShohinInformation::HYOJUN_NOKI,                // 標準納期
                        ShohinInformation::GENTEI_SURYO,               // 限定数量
                        ShohinInformation::NOKORI_SURYO,               // 残り数量
                        ShohinInformation::KANOU_SURYO,                // 購入可能数量
                        ShohinInformation::KISETSU_DATE,               // 季節配送期間開始日
                        ShohinInformation::HOSO_FLG,                   // 包装フラグ
                        ShohinInformation::NOSHI_FLG,                  // のしフラグ
                        ShohinInformation::GREETING_CARD_FLG,          // 挨拶状フラグ
                        ShohinInformation::HAISOSITEI_NOLIMIT_FLG,     // 配送指定制限除外フラグ
                        ShohinInformation::TOKUSHU_FLG,                // 特殊商品フラグ
                        ShohinInformation::KIKAN_GENTEI_HAISO_KEITAI,  // 期間限定配送携帯
                        ShohinInformation::KIKAN_GENTEI_FLG,           // 期間限定フラグ
                        ShohinInformation::DISP_HAISO_KEITAI,          // 表示配送形態
                        ShohinInformation::CANNOT_DELIVERY_DATE,       // 配送指定不可日
                        ShohinInformation::CANNOT_DELIVERY_SENTENCE,   // 配送指定不可文言
                        ShohinInformation::HANBAI_SDATE,               // 販売期間開始日
                        ShohinInformation::HANBAI_EDATE,               // 販売期間終了日
                        ShohinInformation::TAXFREE_FLG,                // 消費税無料フラグ
                      //ShohinInformation::OTODOKE_KANO_DATE,          // お届け可能日
                      //ShohinInformation::OTODOKE_KIBO_DATE,          // お届け希望日 ※自宅用でのみ使用
                      //ShohinInformation::KONYU_SURYO,                // 購入数量     ※自宅用でのみ使用
                        ShohinInformation::CGWEBLIB_STOCKOUT_FLG,      // CGWEBLIB.MISHOHNP の欠品状態
                    );
                    foreach ($otodokesakiList as $otodokeIndex => $otodokesaki) {
                        $otodokeName = sprintf('%s %s', $otodokesaki[Otodokesaki::OTODOKESAKI_SEI_KANJI], $otodokesaki[Otodokesaki::OTODOKESAKI_MEI_KANJI]);
                        foreach ($otodokesaki[Otodokesaki::SHOHIN_LIST] as $otodokeShohin) {
                            $shohinNo = $otodokeShohin[OtodokeShohin::SHOHIN_NO];
                            if (!isset($orderItems[$shohinNo])) {
                                $orderItemsTemp[$shohinNo] = 0;
                            }
                            $orderItemsTemp[$shohinNo] += $otodokeShohin[OtodokeShohin::KONYU_SURYO];
                            if (!isset($shohinObjListTemp[$shohinNo])) {
                                $shohinName = $shohinInfoList[$shohinNo][ShohinInformation::SHOHIN_NAME];
                                // 商品情報をDBから再取得する
                                $shohinCommonObj = new NormalShohin($shohinNo, false, $this->getShohinKenshuGroup());
                                $shohinObj = new ShohinInformation();
                                $shohinObj->setShohinDataFromDB($shohinCommonObj->getCart()); // [メモ]ここで最短お届け日なども設定される
                                $shohinObjListTemp[$shohinNo] = $shohinObj;
                                foreach ($shohinUpdateKeys as $k) {
                                    $shohinInfoList[$shohinNo][$k] = $shohinObj->getObjectDataFromKey($k);
                                }
                                // 欠品商品チェック
                                if ($shohinObj->getObjectDataFromKey(ShohinInformation::CGWEBLIB_STOCKOUT_FLG) == false) {
                                    $errorCodeArray[] = sprintf('%sは在庫がありません。恐れ入りますが、他の商品をお選び直しください。', $shohinName);
                                }
                            }
                            // 配送日チェック
                            if ($otodokeShohin[OtodokeShohin::HAISO_KIBO_DATE]) {
                                $haisoLimitYmd = $this->settings['delivery_date_deadline'];
                                $shohinObj = $shohinObjListTemp[$shohinNo];
                                if (!$shohinObj->isValidDeliveryDate($otodokeShohin[OtodokeShohin::HAISO_KIBO_DATE], $haisoLimitYmd)) {
                                    $errorCodeArray[] = sprintf('%s様にお届けする%sのお届け日に選択できない日付が指定されています。', $otodokeName, $shohinName);
                                }
                            }
                        }
                    }
                    if (!empty($errorCodeArray)) {
                        throw new Exception('[FAILURE] 商品有効チェック');
                    }
                    // 有効なカード数を算出（承認済み）
                    // ※承認されたカード(status='01')のみ有効期限が切れるとstatus='99'に更新される
                    $approvedCount = 0;
                    foreach ($this->gcInfo->getCardList() as $gcDetail) {
                        if (in_array($gcDetail->status, ['01', '99'], true)) {
                            ++$approvedCount;
                        }
                    }
                    $caPointLimit = (int)$this->settings['ca_point_limit'] * $approvedCount;
                    $caPoint = 0;
                    foreach ($shohinInfoList as $shohinNo => $shohinInfo) {
                        if (in_array($shohinInfo[ShohinInformation::HYOJI_KEY2], ['CA', 'DGC'])) {
                            $caPoint += ($shohinInfo[ShohinInformation::KAKAKU_ZEINUKI] * $orderItemsTemp[$shohinNo]);
                        }
                    }
                    if (($caPoint > 0) && ($caPoint <= $caPointLimit)) {
                        $caPoint += $this->getPastOrderCaPoint($this->gcInfo->maincardNo);
                    }
                    if ($caPoint > $caPointLimit) {
                        $errorCodeArray[] = sprintf("金券のお申し込みは、１つのIDにつき%sポイントまでとなります。", number_format($this->settings['ca_point_limit']));
                        throw new Exception('[FAILURE] 金券商品購入エラー');
                    }
                    $creditcardInfo = $orderCommon->getCreditcardInfo();
                    if (($creditcardInfo[Creditcard::CREDITCARD_USE_FLG] !== '2')
                        && ($orderCommon->getTotalOrderPoint() > $this->gcInfo->usablePoints)
                    ) {
                        $errorCodeArray[] = 'お支払ポイントが、商品合計ポイントに達しておりません。';
                        throw new Exception();
                    }
                    // 受注登録データ作成
                    $orderCommon->createInsertData();
                    if (($creditcardInfo[Creditcard::CREDITCARD_USE_FLG] === '2') && GMOMP_USE_TDS2) {
                        // 3Dセキュア2.0 （とりあえず時間がないので無理矢理、、、）
                        if (!$request->getAttribute('gmo_tds2_ready')) {
                            include_once(WT_ROOT_DIR . 'util/payment/SgGmoMpClient.php');
                            $gmoMp = new SgGmoMpClient();
                            $gmoMp->setLogFile(sprintf('%sgmo/gmo_%s.log', WT_LOG_DIR, date('Ymd')));
                            $gmoMp->setTds2Params(
                                GMOMP_TDS2_TENANT,
                                GMOMP_TDS2_TYPE,
                                GMOMP_TDS2_REQUIRED,
                                $this->getActionUrl('Order', 'Tds2Callback')
                            );
                            $seq = getSequeceNo('GMO');
                            $nod = strval(20 - strlen(GMO_PAYMENT_PREFIX));
                            // プレフィックスを付けて、全体で20桁にする
                            $orderId = sprintf("%s%0{$nod}d", GMO_PAYMENT_PREFIX, $seq);
                            $amount  = $creditcardInfo[Creditcard::CREDITCARD_PRICE];
                            $tax     = $creditcardInfo[Creditcard::CREDITCARD_TAX];
                            $token   = $creditcardInfo[Creditcard::CARD_TOKEN];
                            $gmoResult = $gmoMp->doAuth($orderId, $amount, $tax, $token);
                            if (!empty($gmoResult->errors)) {
                                $errorCodeArray = $gmoResult->errors;
                                throw new Exception('[FAILURE] 3D-Secure-Auth');
                            }
                            $response = $gmoResult->output;
                            if ($response['ACS'] === '2') {
                                $redirectUrl = $response['RedirectUrl'];
                                if (!$redirectUrl || !$gmoMp->isValidTds2RedirectUrl($redirectUrl)) {
                                    $user->setAuthenticated(false);
                                    $user->clearAll();
                                    $controller->redirect(WT_URL_SYSERROR);
                                    return VIEW_NONE;
                                }
                                unset($response['ACS']);
                                if (!empty($response) && (strpos($redirectUrl, '?') !== false)) {
                                    // リダイレクトURLを組み直し（ひどい仕様、、、URLエンコードしてくれればいいのに）
                                    // ※１：値に"&"や"="が含まれます。idPass版をご利用の場合は文字列のパースにご注意ください。
                                    // ※２：レスポンスパラメータの順序は固定ですのでご注意ください。
                                    $redirectUrl .= '&' . http_build_query($response, '', '&', PHP_QUERY_RFC3986);
                                }
                                $creditcardInfo[Creditcard::ORDER_ID] = $orderId;
                                $orderCommon->saveCreditcardInfo($creditcardInfo);
                                $user->setAttribute('gmo_tds2_params', [
                                    'tds2_ready' => false,
                                    'access_id' => $gmoResult->output['AccessID'],
                                    'access_pass' => $gmoResult->output['AccessPass'],
                                ]);
                                $this->prepareTds2ApiRedirect();
                                $controller->redirect($redirectUrl);
                                return VIEW_NONE;
                            }
                            if ($response['ACS'] !== '0') {
                                WtApp::getLogger()->error('GMO-ERROR: ' . print_r($response, true));
                                $user->setAuthenticated(false);
                                $user->clearAll();
                                $controller->redirect(WT_URL_SYSERROR);
                                return VIEW_NONE;
                            }
                            // 通常オーソリ
                            // ※GMOMP_TDS2_TYPE=3の場合には3DS2.0未対応カードはここで通常オーソリ確定となる、、、
                            $creditcardInfo[Creditcard::ORDER_ID] = $orderId;
                            $orderCommon->saveCreditcardInfo($creditcardInfo);
                            $orderCommon->saveCreditResult($gmoResult, $amount, $tax);
                            $doCredit = true;
                        }
                    }
                    // 在庫減算
                    foreach ($orderItemsTemp as $shohinNo => $nBuy) {
                        if (!$orderCommon->updateShohinZaiko($shohinNo, $nBuy)) {
                            $shohinName = $shohinInfoList[$shohinNo][ShohinInformation::SHOHIN_NAME];
                            $errorCodeArray[] = sprintf('恐れ入りますが、ご指定の%sは在庫が確保できませんでした。商品、または注文数を変更してください。', $shohinName);
                            throw new Exception();
                        }
                        $orderItems[$shohinNo] = $nBuy;
                    }
                    if ($creditcardInfo[Creditcard::CREDITCARD_USE_FLG] === '2') {
                        if (!$doCredit) {
                            if (GMOMP_USE_TDS2) {
                                // 3Dセキュア2.0
                                $tds2Params = $user->getAttribute('gmo_tds2_params');
                                $errorCodeArray = $orderCommon->creditcardTds2Auth($tds2Params['access_id'], $tds2Params['access_pass']);
                                $user->removeAttribute('gmo_tds2_params');
                                if (!empty($errorCodeArray)) {
                                    throw new Exception('[FAILURE] OrderCommonClass::creditcardTds2Auth');
                                }
                            } else {
                                // クレジットカード与信処理
                                $errorCodeArray = $orderCommon->creditcardAuth();
                                if (!empty($errorCodeArray)) {
                                    throw new Exception('[FAILURE] OrderCommonClass::creditcardAuth');
                                }
                            }
                            $doCredit = true;
                        }
                    }
                    // ギフトカード減算
                    // ※F08への保存値設定があるので登録前に実行する必要がある
                    if (!$orderCommon->executeUsePoint()) {
                        throw new Exception('[FAILURE] OrderCommonClass::executeUsePoint');
                    }

                    // デジタルギフトカード発行処理
                    $insertData = $orderCommon->getInsertData();
                    foreach ($insertData as $orderNo => $data) {
                        if (!isset($data['F08DGC'])) {
                            continue;
                        }
                        $dgcResults[$orderNo] = [];
                        try {
                            foreach ($data['F08DGC'] as $f08DgcRow) {
                                // とりあえずストック型ギフトコードのみ
                                if ($f08DgcRow['DGC_TYPE'] !== 'stock') {
                                    throw new \Exception();
                                }
                                $f08DgcTemp = $this->execDgcStock($f08DgcRow);
                                if ($f08DgcTemp === false) {
                                    $shohinName = $shohinInfoList[$f08DgcRow['F08SHOHNNO']][ShohinInformation::SHOHIN_NAME];
                                    $errorCodeArray[] = sprintf('恐れ入りますが、ご指定の%sは在庫が確保できませんでした。商品、または注文数を変更してください。', $shohinName);
                                    throw new \Exception();
                                }
                                $dgcResults[$orderNo][] = $f08DgcTemp;
                            }
                        } catch (\Exception $e) {
                            $message = $e->getMessage();
                            if ($message) {
                                WtApp::getLogger()->info($message);
                            }
                            throw new Exception('DGC-ERROR');
                        }
                        $orderCommon->updateInsertData($orderNo, 'F08DGC', $dgcResults[$orderNo]);
                    }

                    // $orderCommon->executeUsePoint() で更新された値を再取得
                    $this->gcInfo = $user->getGiftcardInfo();
                    // データベースにデータ保存
                    $errorCodeArray = $orderCommon->insertData();
                    if (!empty($errorCodeArray)) {
                        throw new Exception('[FAILURE] OrderCommonClass::insertData');
                    }
                    $orderNoList = $orderCommon->getOrderNoList();
                    $user->setAttribute('order_no_list', $orderNoList);
                    $user->setModuleParam('gtm_layer_tag', $orderCommon->getGtmLayerTag(
                        $request->getAttribute('gtm_customer_area'),
                        $request->getAttribute('gtm_customer_status')
                    ));
                    // 正常終了メール
                    try {
                        $orderCommon->sendSuccessMail();
                        if (!empty($dgcResults)) {
                            $orderCommon->sendDgcMail($dgcResults);
                        }
                    } catch (Exception $mailException) {
                        // ⑤正常メール失敗時の処理
                        WtApp::getLogger()->error($mailException->getMessage());
                        WtApp::getLogger()->error('交換申し込み完了メール送信に失敗しました。交換申し込み番号：' . implode(', ', $orderNoList));
                    }
                    $user->setGiftcardInfo($this->gcInfo->calc());
                    // 注文系のセッションを削除
                    $orderCommon->removeOrderSession();
                    $user->removeAttribute(OrderCommonClass::SESSNAME_SHOHIN_LIST);
                    $user->removeAttribute('__payment_ok__');
                    $user->removeAttribute('__init_order__');
                    $user->store(false);
                    $controller->redirect($this->getActionUrl('Order', 'OrderComplete'));
                    return VIEW_NONE;
                } catch (Exception $e) {
                    WtApp::getLogger()->warn($e->getMessage());
                    // ギフトカードのキャンセル処理
                    $orderCommon->executeCancelUsePoint();
                    $user->setGiftcardInfo($this->gcInfo->sync()); // ギフトカード情報を再同期
                    if ($doCredit) {
                        // クレジット与信している場合は、管理者にメールを送信
                        $orderCommon->sendErrorAdminMail($errorCodeArray);
                    }
                    if (!empty($orderItems)) {
                        // ③商品在庫戻し
                        foreach ($orderItems as $shohinNo => $nBuy) {
                            $orderCommon->rollbackShohin($shohinNo, $nBuy);
                        }
                    }
                    // デジタルギフトカード
                    if (!empty($dgcResults)) {
                        $cancelNgInfo = [];
                        foreach ($dgcResults as $rows) {
                            foreach ($rows as $f08DgcRow) {
                                try {
                                    $this->rollbackDgcStock(
                                        $f08DgcRow['F08SHOHNNO'],
                                        $f08DgcRow['F08DGPUBLISHER'],
                                        $f08DgcRow['F08SLIPNO'] // === M02DGCSTK.M02DSTKNO
                                    );
                                } catch (\Exception $e) {
                                    $cancelNgInfo[] = $f08DgcRow;
                                }
                            }
                        }
                    }
                    if (!empty($cancelNgInfo)) {
                        $orderCommon->sendDgcErrorAdminMail($cancelNgInfo);
                        //$user->clearAll();
                    }
                    if ($orderCommon->hasErrorsDuringRestoration()) {
                        throw new Exception(); // システムエラー
                    }
                    // ④バリデーションエラー画面表示処理
                    if (!empty($errorCodeArray)) {
                        $request->setErrors($errorCodeArray);
                    } else {
                        $request->setError('exceptionMessage', 'お申し込み確定処理に失敗しました。');
                    }
                    $user->store();
                }
            } catch (Exception $e) {
                // システムエラー
                $orderCommon->sendErrorAdminMail($errorCodeArray); // 管理者にメールを送信
                // 注文系のセッションを削除
                //$orderCommon->removeOrderSession();
                //$user->removeAttribute('__init_order__');
                // セッションクリア
                $user->setAuthenticated(false);
                $user->clearAll();
                $controller->redirect(WT_URL_SYSERROR);
                return VIEW_NONE;
            }
        }
        return $this->getDefaultView($controller, $request, $user);
    }

    /**
     * {@inheritdoc}
     */
    function getDefaultView($controller, $request, $user)
    {
        if (!$user->getAttribute('__payment_ok__')) {
            $controller->redirect($this->getActionUrl('Order', 'CardInfoInput'));
            return VIEW_NONE;
        }
        if ($user->hasAttribute('gmo_tds2_params')) {
            if (!$request->hasErrors()) {
                $tds2Params = $user->getAttribute('gmo_tds2_params');
                if ($tds2Params['tds2_ready']) {
                    $tds2Params['tds2_ready'] = false;
                    $user->setAttribute('gmo_tds2_params', $tds2Params);
                    $_POST['BTN_NEXT'] = true;
                    $request->setAttribute('gmo_tds2_ready', true);
                    return $this->execute($controller, $request, $user);
                }
            }
            $user->removeAttribute('gmo_tds2_params');
        }
        $tds2Error = $user->getModuleParam('tds2_error', true);
        if ($tds2Error) {
            $request->setError('_', $tds2Error);
        }
        $orderCommon = $this->orderCommon;
        // セッション保存値の検証
        try {
            $orderCommon->isValidSession();
        } catch (Exception $e) {
            WtApp::getLogger()->warn($e->getMessage());
            $controller->redirect($this->getActionUrl('Order', 'CardInfoInput'));
            return VIEW_NONE;
        }
        // 買い物かご内の商品のサービス許可状況
        // ※1つでも買い物かご内に有効な商品がある場合は有効とする
        $flags = [
            ShohinInformation::HOSO_FLG          => false,
            ShohinInformation::NOSHI_FLG         => false,
            ShohinInformation::GREETING_CARD_FLG => false,
            ShohinInformation::TOKUSHU_FLG       => false,
        ];
        $shohinInfoList = $orderCommon->getShohinInfoList();
        foreach ($shohinInfoList as $shohinInfo) {
            foreach ($flags as $k => $v) {
                if (!$v && $shohinInfo[$k]) {
                    $flags[$k] = true;
                }
            }
        }
        $hosoFlg     = $flags[ShohinInformation::HOSO_FLG];
        $noshiFlg    = $flags[ShohinInformation::NOSHI_FLG];
        $greetingFlg = $flags[ShohinInformation::GREETING_CARD_FLG];
        $tokushuFlg  = $flags[ShohinInformation::TOKUSHU_FLG];
        $request->setAttribute('hoso_flg', $hosoFlg);
        $request->setAttribute('noshi_flg', $noshiFlg);
        $request->setAttribute('greeting_flg', $greetingFlg);
        $request->setAttribute('tokushu_flg', $tokushuFlg);
        $request->setAttribute('shohin_info_list', $shohinInfoList);
        $request->setAttribute('otodokesaki_list', $orderCommon->getOtodokesakiList());
        // クレジットカード
        $creditcardInfo = $orderCommon->getCreditcardInfo();
        if ($creditcardInfo[Creditcard::CREDITCARD_USE_FLG] != '2') { // '1':クレジットカード未使用 '2':クレジットカード使用
            $creditcardInfo = null;
        }
        $request->setAttribute('creditcard_info', $creditcardInfo);
        $request->setAttribute('shohin_point', $orderCommon->getTotalOrderPoint());
        $request->setAttribute('chumonsha_info', $orderCommon->getChumonshaInfo());
        $request->setAttribute('okurinushi_info', $orderCommon->getOkurinushiInfo());
        $request->setAttribute('giftservice_info', $orderCommon->getGiftServiceInfo());
        $request->setAttribute('hososhi_list', $hosoFlg ? $orderCommon->getMasterData('HOSO') : array());
        $noshiList = $noshiDetailList = array();
        if ($noshiFlg) {
            $noshiList = $orderCommon->getMasterData('NOSI');
            $noshiDetailList = $orderCommon->getMasterData('NOSD');
        }
        $request->setAttribute('noshi_list', $noshiList);
        $request->setAttribute('noshi_detail_list', $noshiDetailList);
        $request->setAttribute('greeting_list', $greetingFlg ? $orderCommon->getMasterData('GREE') : array());
        // CSRF対策のトークンを取得する。
        $this->_setToken($request, $user);
        return VIEW_INPUT;
    }

    /**
     * {@inheritdoc}
     */
    function validate($controller, $request, $user)
    {
        if ($this->_isSubmit('BTN_NEXT')) {
            if ($this->_user->getAttribute('is_testrun')) {
                $request->setError('_', '仮想ギフトカード利用時には注文を確定させることはできません。');
                return;
            }
            // CSRF対策のトークンチェックを行う。
            if (!$this->_isValidToken($user)) {
                return;
            }
        }
    }

    protected function execDgcStock($f08DgcRow)
    {
        $this->modId = MOD_SHOP_ID;
        $this->modPg = MOD_PGM_ID;
        $rs = $this->getDgcStock($f08DgcRow['F08SHOHNNO'], $f08DgcRow['PUBLISHER'], $f08DgcRow['F08WJUCNO']);
        if ($rs === false) {
            // 在庫確保失敗
            return false;
        }
        $f08DgcRow['F08SLIPNO']      = $rs->Fields('M02DSTKNO'); // varchar(20)
        $f08DgcRow['F08DGORDERNO']   = 0;  // bigint
        $f08DgcRow['F08AROUNDINFO']  = ''; // varchar(34)
        $f08DgcRow['F08ISSUERCD']    = 'stock'; // char(7)
        $f08DgcRow['F08DESIGNCD']    = ''; // char(4)
        $f08DgcRow['F08CARDNO']      = ''; // varchar(32)
        $f08DgcRow['F08INQUIRYCD']   = ''; // char(16)
        $f08DgcRow['F08PIN']         = ''; // varchar(128)
        $f08DgcRow['F08CERTIFYCODE'] = ''; // char(4)
        $f08DgcRow['F08DGCD']        = ''; // varchar(256)
        $f08DgcRow['F08BARCODEURL']  = ''; // varchar(256)
        $f08DgcRow['F08EXCHANGEURL'] = ''; // varchar(256)
        $f08DgcRow['F08BALANCE']     = $f08DgcRow['DGC_POINT']; // integer
        $f08DgcRow['F08CAMPAIGN']    = null; // integer
        $f08DgcRow['F08EXPRIREDATE'] = $rs->Fields('M02DSTKTDATE');
        $f08DgcRow['F08GETDATE']     = date(DB_TIMESTAMP_FORMAT_SYSTEM);
        foreach ([
            'M02DSTKNO',
            'M02DSTKINFO1',
            'M02DSTKINFO2',
            'M02DSTKINFO3',
            'M02DSTKINFO4',
            'M02DSTKINFO5',
            'M02DSTKIREMARK1',
            'M02DSTKIREMARK2',
            'M02DSTKIREMARK3',
            'M02DSTKIREMARK4',
            'M02DSTKIREMARK5',
        ] as $k) {
            $f08DgcRow[$k] = $rs->Fields($k);
        }
        return $f08DgcRow;
    }
}
