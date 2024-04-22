<?php
class ShukaJyokyoAction extends SgAction
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
     * @var string 出荷状況確認のギフトカードに紐づくサイトID
     */
    protected $_siteId = SHOP_ID;

    /**
     * {@inheritdoc}
     */
    protected function _initialize($controller, $request, $user)
    {
        if ($user->getAttribute('is_virtual_login')) {
            $controller->redirect(WT_URL_BASE_SSL, true);
        }
        parent::_initialize($controller, $request, $user);
    }

    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        if (!$user->isAuthenticated()) {
            $controller->redirect(WT_URL_BASE_SSL);
            return VIEW_NONE;
        }
        $controller->redirect(WT_URL_BASE_SSL . 'systemerror/error.html');
        return VIEW_NONE;
    }

    /**
     * {@inheritdoc}
     */
    function getDefaultView($controller, $request, $user)
    {
        $hassoInfoArray = $this->_getHassoInfo(array_keys($this->gcInfo->getCardList()));
        if (!$request->getAttribute('hassoKensu')) {
            $request->setError('_', implode("\n", [
               'ただいま照会可能な交換商品はございません。',
                '商品や交通状況により、この画面での反映に時間がかかる場合がございます。ご了承ください。',
            ]));
        }
        $request->setAttribute('hassoJyokyoInfo', $hassoInfoArray);
        return VIEW_INPUT;
    }

    /**
     * 申込番号情報を取得する
     *
     * @param array $giftCardNoList ギフトカード番号リスト
     * @return array 申込番号情報
     */
    private function _getMoushikomiNo($giftCardNoList)
    {
        $moushikomiNos = [];
        $dbc = new ShukaJyokyoQuerySel();
        $dbc->setSelectSql('1');
        $dbc->setRecordsetArray(['giftcardNoList' => $giftCardNoList]);
        $rs = $dbc->Execute();
        if (!$rs) {
            throw new WtDbException(E_DB_EXECUTE_ERR);
        }
        if ($rs->RecordCount() > 0) {
            while (!$rs->EOF) {
                $moushikomiNos[] = $rs->Fields('TINO'); // 申込番号
                $rs->MoveNext();
            }
        }
        $rs->Close();
        return $moushikomiNos;
    }

    /**
     * 出荷状況情報を取得する
     *
     * @param string $moushikomiNo 申込番号
     * @return array 出荷状況情報
     */
    private function _getFikaisypData($moushikomiNo)
    {
        $shukajyokyoInfo = array();
        $request = $this->_request;
        $wherearr = array();
        $wherearr['moushikomiNo'] = $moushikomiNo;
        $wherearr['siteId'] = $this->_siteId;
        $dbc = new ShukaJyokyoQuerySel();
        $dbc->setSelectSql('2');
        $dbc->setRecordsetArray($wherearr);
        $rs = $dbc->Execute();
        if (!$rs) {
            throw new WtDbException(E_DB_EXECUTE_ERR);
        }
        if ($rs->RecordCount() <= 0) {
            //データが0件の場合、申込情報から表示
            $dbc->setSelectSql('7');
            $dbc->setRecordsetArray($wherearr);
            $rs0 = $dbc->Execute();
            if (!$rs0){
                throw new WtDbException(E_DB_EXECUTE_ERR);
            }
            if ($rs0->RecordCount() > 0) {
                $shukajyokyoInfo['shohinCd']  = trim($rs0->Fields('F08SHOHNCD'));
                $shukajyokyoInfo['name2']     = trim($rs0->Fields('F08SNAME'));
                $shukajyokyoInfo['tname']     = trim($rs0->Fields('F07SEI')) . " " . trim($rs0->Fields('F07MEI'));
                $shukajyokyoInfo['m02mfuka']  = trim($rs0->Fields('M02MAILFUKAFLG'));
                $shukajyokyoInfo['f08vpoint'] = trim($rs0->Fields('F08VPOINT'));
                $shukajyokyoInfo['f08kibobi'] = trim($rs0->Fields('F08KIBOBI'));
                $shukajyokyoInfo['zipcode'] = implode('-', [
                    $rs0->Fields('F07ZIP1'),
                    $rs0->Fields('F07ZIP2'),
                ]);
                $shukajyokyoInfo['address'] = implode('', [
                    $rs0->Fields('F07ADD1'),
                    $rs0->Fields('F07ADD2'),
                    $rs0->Fields('F07ADD3'),
                ]);
            }
            $rs->Close();
            return $shukajyokyoInfo;
        }
        $shukajyokyoInfo['shohinCd'] = trim($rs->Fields('SHOCD1')); // FIKAISYP.SHOCD1
        $shukajyokyoInfo['stat']     = trim($rs->Fields('STAT'));   // FIKAISYP.STAT
        $shukajyokyoInfo['tdlvdt']   = trim($rs->Fields('TDLVDT')); // FIKAISYP.TDLVDT
        $shukajyokyoInfo['jdate']    = trim($rs->Fields('JDATE'));  // FIKAISYP.JDATE
        $shukajyokyoInfo['pdate']    = trim($rs->Fields('PDATE'));  // FIKAISYP.PDATE
        $shukajyokyoInfo['ptime']    = trim($rs->Fields('PTIME'));  // FIKAISYP.PTIME
        $shukajyokyoInfo['okind']    = trim($rs->Fields('OKIND'));  // FIKAISYP.OKIND
        $shukajyokyoInfo['bcd']      = trim($rs->Fields('BCD'));    // FIKAISYP.BCD
        $shukajyokyoInfo['tname']    = trim($rs->Fields('TNAME'));  // FIKAISYP.TNAME
        // FIKAISYPのSHOCD1から商品名を取得する
        $shukajyokyoInfo['name2']     = ''; // MISHOHNP.NAME2
        $shukajyokyoInfo['tokcd']     = ''; // MISHOHNP.TOKCD
        $shukajyokyoInfo['hng1']      = ''; // MISHOHNP.HNG1
        $shukajyokyoInfo['m02sname']  = ''; // M02SHOHIN.M02SNAME
        $shukajyokyoInfo['m02brand']  = ''; // M02SHOHIN.M02BRAND
        $shukajyokyoInfo['m02mfuka']  = ''; // M02SHOHIN.M02MAILFUKAFLG
        $shukajyokyoInfo['m02vpoint'] = '';
        $shukajyokyoInfo['zipcode'] = implode('', [
            trim($rs->Fields('TPOST')),
            trim($rs->Fields('TYBN2')),
        ]);
        $shukajyokyoInfo['address'] = trim($rs->Fields('TADDRS'));
        $shohinCd = trim(mb_convert_kana($shukajyokyoInfo['shohinCd'], 's'));
        if (strlen($shohinCd)) {
            $wherearr['shohinCd'] = $shohinCd;
            $dbc->setSelectSql('3');
            $dbc->setRecordsetArray($wherearr);
            $rs = $dbc->Execute();
            if (!$rs) {
                throw new WtDbException(E_DB_EXECUTE_ERR);
            }
            if ($rs->RecordCount() > 0) {
                $shukajyokyoInfo['name2'] = trim($rs->Fields('NAME2'));
                $shukajyokyoInfo['tokcd'] = trim($rs->Fields('TOKCD'));
                $shukajyokyoInfo['hng1']  = trim($rs->Fields('HNG1'));
            }
            if (!$request->hasErrors()) {
                $dbc->setSelectSql('4');
                $dbc->setRecordsetArray($wherearr);
                $rs = $dbc->Execute();
                if (!$rs) {
                    throw new WtDbException(E_DB_EXECUTE_ERR);
                }
                if ($rs->RecordCount() > 0) {
                    $shukajyokyoInfo['m02sname']  = trim($rs->Fields('M02SNAME'));
                    $shukajyokyoInfo['m02brand']  = trim($rs->Fields('M02BRAND'));
                    $shukajyokyoInfo['m02mfuka']  = trim($rs->Fields('M02MAILFUKAFLG'));
                    $shukajyokyoInfo['m02vpoint'] = trim($rs->Fields('M02VPOINT'));
                }
            }
        }
        return $shukajyokyoInfo;
    }

    /**
     * 受注日情報を取得する
     *
     * @param string $moushikomiNo 申込番号
     * @return string 受注日情報
     */
    private function _getJuchubi($moushikomiNo)
    {
        $dbc = new ShukaJyokyoQuerySel();
        $dbc->setSelectSql('5');
        $dbc->setRecordsetArray([
            'moushikomiNo' => $moushikomiNo,
            'siteId' => $this->_siteId,
        ]);
        $rs = $dbc->Execute();
        if (!$rs) {
            throw new WtDbException(E_DB_EXECUTE_ERR);
        }
        return (string)$rs->Fields('F06JUCHUBI');
    }

    /**
     * 発送業者と配達照会URLを取得する
     *
     * @param string $okind 送り状種別
     * @return array 発送業者と配達照会URL
     */
    private function _getHassoGyosha($okind)
    {
        $hassoGyousha = [];
        $dbc = new ShukaJyokyoQuerySel();
        $dbc->setSelectSql('6');
        $dbc->setRecordsetArray([
            'okind' => $okind,
            'siteId' => $this->_siteId,
        ]);
        $rs = $dbc->Execute();
        if (!$rs) {
            throw new WtDbException(E_DB_EXECUTE_ERR);
        }
        if ($rs->RecordCount() > 0) {
            $hassoGyousha['hassoGyosha'] = $rs->Fields('M21HAISOKNM');
            $hassoGyousha['shoukaiUrl']  = $rs->Fields('M21URL');
        }
        return $hassoGyousha;
    }

    /**
     * 発送情報の取得
     *
     * @param array $giftCardNoList ギフトカード番号リスト
     * @return array
     */
    private function _getHassoInfo($giftCardNoList)
    {
        $hassoInfoArray = [];
        // ギフトカード番号から申込番号取得
        $moushikomiNos = $this->_getMoushikomiNo($giftCardNoList);
        // 取得した申込番号から各表示項目のデータ取得
        $this->_request->setAttribute('hassoKensu', count($moushikomiNos));
        if (count($moushikomiNos) == 0) {
            return $hassoInfoArray;
        }
        // デジタルギフトカード確認
        $dbc = new ShukaJyokyoQuerySel();
        $dbc->setSelectSql('8');
        $dbc->setRecordsetArray(['F08WJUCNO' => $moushikomiNos]);
        $rs = $dbc->Execute();
        if (!$rs) {
            throw new WtDbException(E_DB_EXECUTE_ERR);
        }
        $dgcInfoList = [];
        if ($rs->RecordCount()) {
            $sortKeyPrefixes = getDgcAttributes('sort');
            while (!$rs->EOF) {
                $orderNo = $rs->Fields('F08WJUCNO');
                if (!isset($dgcInfoList[$orderNo])) {
                    $dgcInfoList[$orderNo] = [];
                }
                $publisher = $rs->Fields('F08DGPUBLISHER');
                if (isset($sortKeyPrefixes[$publisher])) {
                    $index = 0;
                    $sortKeyPrefix = $sortKeyPrefixes[$publisher];
                    do {
                        $sortKey = sprintf('%s%03d', $sortKeyPrefix, $index++);
                    } while (isset($dgcInfoList[$orderNo][$sortKey]));
                    $dgcInfoList[$orderNo][$sortKey] = getDgcViewContents($rs->GetRowAssoc());
                }
                ksort($dgcInfoList[$orderNo]);
                $rs->MoveNext();
            }
        }
        // 発送情報
        foreach ($moushikomiNos as $key => $moushikomiNo) {
            $dgcInfo = [];
            if (isset($dgcInfoList[$moushikomiNo])) {
                $dgcInfo = $dgcInfoList[$moushikomiNo];
            }
            $hassoInfoArray[$key]['dgcInfo'] = $dgcInfo;

            // 申込番号設定
            $hassoInfoArray[$key]['moushikomiNo'] = $moushikomiNo;
            // 申込番号に紐づくデータをCGWEBLIB.FIKAISYPから取得
            $fikaisypData = $this->_getFikaisypData($moushikomiNo);
            // 発送状況取得
            // 「発送準備中」を設定
            $hassoInfoArray[$key]['hassoJyokyo'] = 0;
            // 発送日取得
            $hassoInfoArray[$key]['hassobi'] = '';
            // STATが2の場合、または、STATが3かつ現在日付よりTDLVDTが2日前の場合、「発送済み」(hassoJyokyo = 1)とする
            if (isset($fikaisypData['stat']) && $fikaisypData['stat'] === '2') {
                if (strlen($fikaisypData['pdate'])) {
                    if (strlen($fikaisypData['ptime'])) {
                        $hassoInfoArray[$key]['hassobi'] = date('Y-m-d H.i', strtotime($fikaisypData['pdate'] . $fikaisypData['ptime'] . '00'));
                    } else {
                        $hassoInfoArray[$key]['hassobi'] = date('Y-m-d H.i', strtotime($fikaisypData['pdate'] . '000000'));
                    }
                }
                $hassoInfoArray[$key]['hassoJyokyo'] = 1;
            } else if (isset($fikaisypData['stat']) && $fikaisypData['stat'] === '3') {
                if (strlen($fikaisypData['tdlvdt'])) {
                    $hassoInfoArray[$key]['hassobi'] = date('Y-m-d H.i', strtotime($fikaisypData['tdlvdt'] . '- 2day'));
                    if (date('Y-m-d', strtotime('now')) >= date('Y-m-d', strtotime($fikaisypData['tdlvdt'] . '- 2day'))) {
                        $hassoInfoArray[$key]['hassoJyokyo'] = 1;
                    }
                }
            }
            // 発送商品名称取得 -- 商品コード
            $hassoInfoArray[$key]['shohinCd'] = '';
            if (isset($fikaisypData['shohinCd']) && strlen($fikaisypData['shohinCd'])) {
                $hassoInfoArray[$key]['shohinCd'] = $fikaisypData['shohinCd'];
            }
            // 発送商品名称取得 -- 商品名
            $hassoInfoArray[$key]['shohinName'] = '';
            if (isset($fikaisypData['m02sname']) && strcmp($fikaisypData['m02sname'], '') !== 0) {
                if (strlen($fikaisypData['m02brand'])) {
                    $fikaisypData['m02brand'] .= '&nbsp;&nbsp;';
                }
                $hassoInfoArray[$key]['shohinName'] = $fikaisypData['m02brand'] . $fikaisypData['m02sname'];
            } else {
                // CGWEBLIBの商品マスタから商品名取得
                if (isset($fikaisypData['name2']) && strlen($fikaisypData['name2'])) {
                    $hassoInfoArray[$key]['shohinName'] = $fikaisypData['name2'];
                }
            }
            // 発送商品名称取得 -- 商品名＋商品コード
            $hassoInfoArray[$key]['shohinNameShohinCd'] = '';
            if (strlen($hassoInfoArray[$key]['shohinCd'])) {
                if (strlen($hassoInfoArray[$key]['shohinName'])) {
                    $hassoInfoArray[$key]['shohinNameShohinCd'] = $hassoInfoArray[$key]['shohinName'] . '&nbsp;&nbsp;（' . $hassoInfoArray[$key]['shohinCd'] . '）';
                } else {
                    $hassoInfoArray[$key]['shohinNameShohinCd'] = $hassoInfoArray[$key]['shohinCd'];
                }
            } else {
                $hassoInfoArray[$key]['shohinNameShohinCd'] = $hassoInfoArray[$key]['shohinName'];
            }
            // ポイント取得
            $hassoInfoArray[$key]['vpoint'] = '';
            if (isset($fikaisypData['f08vpoint']) && strlen($fikaisypData['f08vpoint'])) {
                $hassoInfoArray[$key]['vpoint'] = $fikaisypData['f08vpoint'];
            } elseif (isset($fikaisypData['m02vpoint']) && strlen($fikaisypData['m02vpoint'])) {
                $hassoInfoArray[$key]['vpoint'] = $fikaisypData['m02vpoint'];
            }
            // 受付日取得
            $hassoInfoArray[$key]['juchubi'] = $this->_getJuchubi($moushikomiNo);
            if (strcmp($hassoInfoArray[$key]['juchubi'], '') === 0) {
                // 取得できなかった場合、FIKAISYP.JDATEを設定
                if (isset($fikaisypData['jdate']) && strlen($fikaisypData['jdate'])) {
                    $hassoInfoArray[$key]['juchubi'] = date('Y-m-d', strtotime($fikaisypData['jdate']));
                }
            }
            // お届け予定日取得
            $hassoInfoArray[$key]['otodokeYoteibi'] = '';
            $hassoInfoArray[$key]['otodokeYoteibiKbn'] = 0;
            $hassoInfoArray[$key]['mailfukaflg'] = '';
            if (isset($fikaisypData['m02mfuka']) && strlen($fikaisypData['m02mfuka'])) {
                $hassoInfoArray[$key]['mailfukaflg'] = $fikaisypData['m02mfuka'];
            }
            if (isset($fikaisypData['f08kibobi']) && strlen($fikaisypData['f08kibobi'])) {
                $hassoInfoArray[$key]['otodokeYoteibi'] = $fikaisypData['f08kibobi'];
            }
            // 発送業者取得
            $hassoInfoArray[$key]['hassoGyosha'] = '';
            $hassoInfoArray[$key]['shoukaiUrl'] = '';
            if (isset($fikaisypData['okind']) && strcmp($fikaisypData['okind'], '') !== 0) {
                $hassoGyoushaArray = $this->_getHassoGyosha($fikaisypData['okind']);
                $hassoInfoArray[$key]['hassoGyosha'] = $hassoGyoushaArray['hassoGyosha'];
                $hassoInfoArray[$key]['shoukaiUrl'] = $hassoGyoushaArray['shoukaiUrl'];
            }
            // 送り状番号取得
            $hassoInfoArray[$key]['okurijoNo'] = '';
            if (isset($fikaisypData['bcd']) && strlen($fikaisypData['bcd'])) {
                $hassoInfoArray[$key]['okurijoNo'] = trim($fikaisypData['bcd']);
            }
            // 氏名(漢字)取得
            $hassoInfoArray[$key]['shimei'] = '';
            if (isset($fikaisypData['tname']) && strlen($fikaisypData['tname'])) {
                $hassoInfoArray[$key]['shimei'] = $fikaisypData['tname'];
            }
            $hassoInfoArray[$key]['zipcode'] = $fikaisypData['zipcode'];
            $hassoInfoArray[$key]['address'] = $fikaisypData['address'];
        }
        return $hassoInfoArray;
    }
}