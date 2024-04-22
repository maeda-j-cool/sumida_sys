<?php
/**
 * ProjectName : スマートギフトサイト
 * Subsystem   : ギフトカードを利用した商品交換モールサイト
 *
 * @package webapp_ssl
 */

/**
 * 注文ベース
 *
 * @author  Yuki Tanaka
 * @version Release:<1.0>
 */
abstract class AbstractOrderAction extends SgAction
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
     * @var OrderCommonClass
     */
    protected $orderCommon;

    /**
     * {@inheritdoc}
     */
    function isSecure($controller, $user)
    {
        $user->setAttribute('preModule', $user->getAttribute('currmod'));
        $user->setAttribute('preAction', $user->getAttribute('curract'));
        $user->setAttribute('currmod', $controller->getCurrentModule());
        $user->setAttribute('curract', $controller->getCurrentAction());
        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function _initialize($controller, $request, $user)
    {
        parent::_initialize($controller, $request, $user);
        // ここが呼び出されるタイミングはログイン判定より前なのでログイン前提の処理にしてはいけない
        $this->orderCommon = new OrderCommonClass($user);
        if ($user->isAuthenticated()) {
            // ギフトカード残高がない場合は購入不可
            if ($this->gcInfo->usablePoints <= 0) {
                $this->_controller->redirect(WT_URL_BASE_SSL, true); // exit
            }
            // 仮想ギフトカードチェック
            if ($this->_user->getAttribute('is_virtual_login')) {
                // isSecureの判定でリダイレクトさせるためにログアウト処理を行う
                $this->_user->setAuthenticated(false);
                $this->_user->store();
            }
        }
    }

    protected function getPastOrderCaPoint($giftcardNo)
    {
        $caPoint = 0;
        include_once(WT_ROOT_DIR . 'webapp_ssl/modules/Order/querys/PastOrderQuerySel.class.php');
        $db = new PastOrderQuerySel();
        $db->setSelectSql('get-past-ca-point');
        $db->setRecordsetArray(['GCNO' => $giftcardNo]);
        $rs = $db->Execute();
        if ($rs && $rs->RecordCount()) {
            while (!$rs->EOF) {
                $n = (int)$rs->Fields('F08SURYO');
                $p = (int)$rs->Fields('F08VPOINT');
                $caPoint += ($n * $p);
                $rs->MoveNext();
            }
            $rs->Close();
        }
        WtApp::getLogger()->debug(sprintf('[%s] past-order-ca-point: %d', $giftcardNo, $caPoint));
        return $caPoint;
    }

    protected function isCreditcardEnable()
    {
        // 注文全体で消費税無料フラグが有効な商品合計額がギフトカードの合計残ポイントより多い場合はクレジットカード不可
        $taxFreePrice = 0;
        $shohinInfoList = $this->orderCommon->getShohinInfoList();
        foreach ($this->orderCommon->getOtodokesakiList() as $otodokesaki) {
            foreach ($otodokesaki[Otodokesaki::SHOHIN_LIST] as $os) {
                $shohinNo = $os[OtodokeShohin::SHOHIN_NO];
                if ($shohinInfoList[$shohinNo][ShohinInformation::TAXFREE_FLG] === '1') {
                    $taxFreePrice += ($os[OtodokeShohin::KAKAKU_ZEINUKI] * $os[OtodokeShohin::KONYU_SURYO]);
                }
            }
        }
        return ($taxFreePrice <= $this->gcInfo->usablePoints);
    }

    protected function prepareTds2ApiRedirect()
    {
        $user = $this->_user;
        // 3DSサーバーからのPOSTコールバックを直接受けるためにセッションクッキーのSamesite属性を一時的に変更
        // ※Laxとかだと外部からのPOSTにセッションが乗ってこないので、、、
        $user->setLoginCookie('None')->store(false);
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params([
            'lifetime' => $cookieParams['lifetime'],
            'path' => '/',
            'domain' => $cookieParams['domain'],
            'secure' => $cookieParams['secure'],
            'httponly' => $cookieParams['httponly'],
            'samesite' => 'None',
        ]);
        session_start(); // Samesite=Noneで再スタートさせる
    }
}
