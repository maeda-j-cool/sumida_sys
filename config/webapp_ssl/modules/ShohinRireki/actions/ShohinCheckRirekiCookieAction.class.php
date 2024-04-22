<?php
require_once(WT_ROOT_DIR . 'util/Common/actions/ShohinCheckRirekiCookie.class.php');

class ShohinCheckRirekiCookieAction extends SgAction
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
     * @var string プログラムID
     */
    protected $_modPg = 'S0208';

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        return VIEW_NONE;
    }

    /**
     * {@inheritdoc}
     */
    function getDefaultView($controller, $request, $user)
    {
        $rireki = new ShohinCheckRirekiCookie($this);
        $arrShohin = $rireki->getDispArray(CHECK_SHOHIN_COOKIE_MAX, $this->getShohinKenshuGroup());
        if (count($arrShohin) < 1) {
            // 最近チェックした商品がない場合
            $request->setError('error', '最近見た商品ページにはまだ商品がございません。');
        }
        $arrShohin = array_reverse($arrShohin);
        $request->setAttribute('arrShohin', $arrShohin);
        return VIEW_INPUT;
    }
}