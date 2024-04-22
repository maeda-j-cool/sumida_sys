<?php
require_once(WT_ROOT_DIR . 'util/Common/actions/RecommendShohin.class.php');
require_once(WT_ROOT_DIR . 'util/Common/actions/NinkiShohinRanking.class.php');
require_once(WT_ROOT_DIR . 'util/Common/actions/NewInformation.class.php');
require_once(dirname(__DIR__) . '/querys/ToppageQuerySel.class.php');

class ToppageAction extends SgAction
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
    function execute($controller, $request, $user)
    {
        // キーワード検索時はカテゴリ検索結果画面に遷移する
        if ($this->_isSubmit('BTN_SEARCH')) {
            $keyword = urlencode($request->getParameter('keyword'));
            $controller->redirect($this->getActionUrl('SS', 'KS') . '?kysg=on&keyword=' . $keyword);
            return VIEW_NONE;
        }
        return $this->getDefaultView($controller, $request, $user);
    }

    /**
     * {@inheritdoc}
     */
    function getDefaultView($controller, $request, $user)
    {
        // 動画メッセージ情報取得
        $movieUrl = $this->_getMovieMessageUrl($this->gcInfo->maincardNo);
        $request->setAttribute('movieUrl', $movieUrl);

        // 新着お知らせ情報取得
        $newInfo = new NewInformation($this);
        // 新着お知らせエリア
        if (isSmartPhone()) {
            // スマホ
            $arrNewInfoDataArea = $newInfo->getNewInformationArray(NEW_INFO_KBN_SHOP_OSHIRASE_SP);
        } else {
            $arrNewInfoDataArea = $newInfo->getNewInformationArray(NEW_INFO_KBN_SHOP_OSHIRASE_PC);
        }
        $request->setAttribute('arrNewInfoDataArea', $arrNewInfoDataArea);
        // ギフトカードエリア
        if (isSmartPhone()) {
            $arrGiftCardDataArea = $newInfo->getNewInformationArray(NEW_INFO_KBN_SHOP_METENANCE_SP);
        } else {
            $arrGiftCardDataArea = $newInfo->getNewInformationArray(NEW_INFO_KBN_SHOP_METENANCE_PC);
        }
        $request->setAttribute('arrGiftCardDataArea', $arrGiftCardDataArea);
        // 新着お知らせ2エリア
        if (isSmartPhone()) {
            $arrNewInfoData2Area = $newInfo->getNewInformationArray(NEW_INFO_KBN_SHOP_OSHIRASE2_SP);
        } else {
            $arrNewInfoData2Area = $newInfo->getNewInformationArray(NEW_INFO_KBN_SHOP_OSHIRASE2_PC);
        }
        $request->setAttribute('arrNewInfoData2Area', $arrNewInfoData2Area);

        $dispPopUp = false;
        $firstLoginMessage = '';
        if (isset($this->settings['first_login_message']) && strlen($this->settings['first_login_message'])) {
            $firstLoginMessage = $this->settings['first_login_message'];
            if (!isset($_COOKIE[POPUP_COOKIE_NAME])) {
                setcookie(POPUP_COOKIE_NAME, true, time() + POPUP_COOKIE_EXP_SECOND, ini_get('session.cookie_path'));
                $dispPopUp = true;
            }
        }
        $request->setAttribute('show_popup', $dispPopUp);
        $request->setAttribute('first_login_message', $firstLoginMessage);

        // 人気商品ランキング情報取得
        $categoryNo = 84030;
        $shohinRankingArray = $this->getShohinRanking($this->getShohinKenshuGroup(), $categoryNo);
        $rankingList1 = array_slice($shohinRankingArray, 0, 3);
        $rankingList2 = array_slice($shohinRankingArray, 3, 4);
        $rankingList3 = array_slice($shohinRankingArray, 7, 4);
        $request->setAttribute('ranking_list1', $rankingList1);
        $request->setAttribute('ranking_list2', $rankingList2);
        $request->setAttribute('ranking_list3', $rankingList3);
        // 承認待ちのポイント有無判定
        if (!!$this->gcInfo->numberOfStatuses['W1']) {
            $request->setAttribute('header_banner_message', implode("\n", [
                'ご利用登録の審査中。各市町村での承認の完了目安（約1から2週間）',
                '完了後に登録いただいたメールにてお知らせします。',
            ]));
            $request->setAttribute('header_banner_link', $this->getActionUrl('CardList', 'CardList') . '#cardlist');
        }
        return VIEW_INPUT;
    }

    /**
     * 動画メッセージを取得する。
     *
     * @param string $giftcardNo
     * @return string 動画メッセージURL
     */
    private function _getMovieMessageUrl($giftcardNo)
    {
        $dbc = new ToppageQuerySel();
        $dbc->setSelectSql('1');
        $dbc->setRecordsetArray(['giftcard_no' => $giftcardNo]);
        $rs = $dbc->Execute();
        if (!$rs) {
            throw new WtDbException(E_DB_EXECUTE_ERR);
        }
        return (string)$rs->Fields('M22URL');
    }

    /**
     * 人気商品ランキング情報(総合)を取得する。
     *
     * @param string $kenshuGroup 券種グループ
     * @return array 商品情報リスト
     */
    function getShohinRanking($kenshuGroup, $categoryNo = CATEGNO_TOTAL)
    {
        // 人気商品ランキング情報を取得
        $shohinkRanking = new NinkiShohinRanking($this);
        $arrShohinRanking = $shohinkRanking->getNinkiShohinRankingArray($categoryNo, $kenshuGroup, 3 + 8);
        if (!is_array($arrShohinRanking) || count($arrShohinRanking) == 0) {
            return array();
        }
        return $arrShohinRanking;
        //$arrShohinRankingItem = [];
        //foreach ($arrShohinRanking as $shohinInfo) {
        //    // 商品オブジェクト生成
        //    $shohin = new NormalShohin();
        //    $shohin->setAll($shohinInfo);
        //    $arrShohinRankingItem[] = $shohin;
        //}
        //return $arrShohinRankingItem;
    }
}
