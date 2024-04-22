<?php
class ShohinShosaiView extends SgView
{
    /**
     * {@inheritdoc}
     */
    function execute($controller, $request, $user)
    {
        $renderer = $this->_renderer;
        if ($request->getAttribute('kind') === 'okiniiri') {
            $renderer->setTemplate(dirname(__DIR__) . '/templates/ShohinAdd.tpl');
            $errors = $request->getErrors();
            $renderer->setAttribute('error', array_shift($errors));
            return $renderer;
        }
        $this->setTemplate(SHOHIN_TEMPLATE_DIR . $request->getAttribute('templateFileName'));
        $renderer->setAttribute('panTree', $request->getAttribute('panTree'));
        if ($request->hasErrors()) {
            return $renderer;
        }
        // ロジックよりパラメータを受け取る
        $shohin = $request->getAttribute('shohin'); // 商品情報
        $caregoryNo = $request->getAttribute('caregoryNo'); // カテゴリ番号
        $seoarr = $request->getAttribute('seoarr'); // SEO情報
        $recommendShohinArray = $request->getAttribute('recommendShohinArray'); // レコメンド商品
        $checkRirekiShohinArray = $request->getAttribute('checkRirekiShohinArray'); // 最近チェックした商品
        $shohinGroupArray = $request->getAttribute('shohinGroupArray'); // 関連商品
        $dispZaikoMessage = $request->getAttribute('dispZaikoMessage'); // 商品在庫状況表示文言
        $shohinNaiyoArray = $request->getAttribute('shohinNaiyoArray'); // 商品内容説明

        $v_shohinarr = $shohin->getAll();

        //--------------------------------
        // 「この商品に交換する」ボタンの表示
        //--------------------------------
        $v_buttonflg = '';        // 0:非表示＋メッセージ、1:表示
        $v_buttonmsg = '';         // メッセージ

            // 在庫チェック
        if (!$shohin->existsZaiko()) {
            $v_buttonflg = '3';
            $v_buttonmsg = '只今在庫を切らしております';
        }

        // CGWEBLIB.MISHOHNP の商品情報から申込可能かチェック
        if ($request->getAttribute('cgweblibApplyFlg') == false) {
            $v_buttonflg = '3';
            $v_buttonmsg = '只今在庫を切らしております';
        }

        // お届け日チェック（季節商品で、お届けが可能でないものは、メッセージを表示）
        //$kisetsuHaisouDate = $shohin->getKisetsuHaisouDate(); // 季節商品配送日設定
        $kisetsuHaisouDate = $request->getAttribute('hkanouDayArray');
        if ($v_shohinarr['M02KISEFLG'] == '1' && $kisetsuHaisouDate === false) {
            $v_buttonflg = '2';
            $v_buttonmsg = '季節商品のためお届けできません';
        }

        // 販売期間判別
        if ($shohin->isBeforeOnSale()) {
            //販売開始日よりも前
            $v_buttonflg = '0';
            $v_buttonmsg = $v_shohinarr['M02SCOMNT'];
        } else if ($shohin->isAfterOnSale()) {
            //販売終了日よりも後
            $v_buttonflg = '1';
            $v_buttonmsg = $v_shohinarr['M02ECOMNT'];
        }
        $renderer->setAttribute('title', trim(implode(' ', [
            $v_shohinarr['M02BRAND'],
            $v_shohinarr['M02SNAME'],
        ])));
        //--------------------------------
        // レンダラー設定
        //--------------------------------
        $renderer->setAttribute('shohin', $v_shohinarr['M02SHOHNNO']);     // 商品番号
        $renderer->setAttribute('categoryno', $caregoryNo);                //カテゴリ番号
        $renderer->setAttribute('fusokuPoint', $v_shohinarr['M02VPOINT'] - $request->getAttribute('remainPoint')); // 不足ポイント
        $renderer->setAttribute('shohin_no', $v_shohinarr['M02SHOHNNO']);               // 商品番号
        $renderer->setAttribute('shohinname', $v_shohinarr['M02SNAME']);                // 商品名
        $renderer->setAttribute('shohncd', trim($v_shohinarr['M02SHOHNCD']));           // 商品コード
        $renderer->setAttribute('brandname', $v_shohinarr['M02BRAND']);                 // ブランド名
        $renderer->setAttribute('mainjpg', trim($v_shohinarr['M02SHOHNCD']));           // （商品メイン画像）
        $renderer->setAttribute('delivertype', $v_shohinarr['M02HSKEITA']);             // 配送形態アイコン
        $renderer->setAttribute('haimtcd', $v_shohinarr['M02HAIMTCD']);                 // 配送元識別コード
        $renderer->setAttribute('hosoflg', $v_shohinarr['M02HOSOFLG']);                 // 包装フラグ
        $renderer->setAttribute('nosikbn', $v_shohinarr['M02NOSIKBN']);                 // のし区分
        $renderer->setAttribute('catchcopy', $v_shohinarr['M02CATCH']);                 // キャッチコピー
        $renderer->setAttribute('shohinexp1', $v_shohinarr['M02SETSU1']);               // 商品説明1:タグ表示OK
        $renderer->setAttribute('shohinexp3', $v_shohinarr['M02SETSU3']);               // 商品説明3:タグ表示OK
        $renderer->setAttribute('gentei', $v_shohinarr['M02GENTEI']);                   // 限定数
        $renderer->setAttribute('nokori', $v_shohinarr['M02NOKORI']);                   // 残り数
        $renderer->setAttribute('zaiko_nasi', $v_shohinarr['M02ZNASHI']);               // 在庫なし条件値
        $renderer->setAttribute('zaiko_mongon', $dispZaikoMessage);                     // 在庫文言表示
        $renderer->setAttribute('sake_flg', trim($v_shohinarr['M02LIQRFLG']));          // 酒類フラグ
        $renderer->setAttribute('naire_kubun', $v_shohinarr['M02NAIRKBN']);             // 名入れ区分
        $renderer->setAttribute('message_card_flg', $v_shohinarr['M02MCRDFLG']);        // メッセージカードフラグ
        $renderer->setAttribute('bag_flg', $v_shohinarr['M02BAGKBN']);                  // 手提げフラグ
        $renderer->setAttribute('quick_flg', $v_shohinarr['M02QICKFLG']);               // お急ぎ便フラグ
        $renderer->setAttribute('brandexp', $v_shohinarr['M02BSETSU']);                 // ブランド説明
        $renderer->setAttribute('vpoint', $v_shohinarr['M02VPOINT']);                   // バリューポイント
        $renderer->setAttribute('shohin_naiyo_setsumei', $shohinNaiyoArray);            // 商品内容説明
        $renderer->setAttribute('rinbel_original_img', $v_shohinarr['M02RIONLG']);      // イメージ画像1
        $renderer->setAttribute('suryogentei_img', $v_shohinarr['M02SURYOGENTEIG']);    // イメージ画像2
        $renderer->setAttribute('markg1_img_flg', $v_shohinarr['M02MARKG1']);           // イメージ画像3
        $renderer->setAttribute('markg2_img_flg', $v_shohinarr['M02MARKG2']);           // イメージ画像4
        $renderer->setAttribute('markg3_img_flg', $v_shohinarr['M02MARKG3']);           // イメージ画像5
        $renderer->setAttribute('markg4_img_flg', $v_shohinarr['M02MARKG4']);           // イメージ画像6
        $renderer->setAttribute('markg5_img_flg', $v_shohinarr['M02MARKG5']);           // イメージ画像7
        $renderer->setAttribute('markg6_img_flg', $v_shohinarr['M02MARKG6']);           // イメージ画像8
        $renderer->setAttribute('markg7_img_flg', $v_shohinarr['M02MARKG7']);           // イメージ画像9
        $renderer->setAttribute('markg8_img_flg', $v_shohinarr['M02MARKG8']);           // イメージ画像10
        $renderer->setAttribute('komugi', $v_shohinarr['M02KOMUGI']);                   // 小麦フラグ
        $renderer->setAttribute('soba', $v_shohinarr['M02SOBA']);                       // そばフラグ
        $renderer->setAttribute('tamago', $v_shohinarr['M02TAMAGO']);                   // 卵フラグ
        $renderer->setAttribute('nyu', $v_shohinarr['M02NYU']);                         // 乳フラグ
        $renderer->setAttribute('rakkasei', $v_shohinarr['M02RAKKASEI']);               // 落花生フラグ
        $renderer->setAttribute('ebi', $v_shohinarr['M02EBI']);                         // えびフラグ
        $renderer->setAttribute('kani', $v_shohinarr['M02KANI']);                       // かにフラグ
        $renderer->setAttribute('brand_copy', $v_shohinarr['M02BCOPE']);                // ブランドコピー
        $renderer->setAttribute('haiso_fuka_flg', $v_shohinarr['M02HAISOFJFLG']);       // 配送指定不可除外フラグ
        $renderer->setAttribute('hkanouDayArray', $kisetsuHaisouDate);                  // 季節商品配送日設定
        $renderer->setAttribute('hKeitaiArray', $request->getAttribute('hKeitaiArray'));// 配送形態
        $renderer->setAttribute('tax_free_flg', $v_shohinarr['M02TAXFREEFLG']);
        $renderer->setAttribute('hk1', trim($v_shohinarr['M02HYOJIKEY1']));
        $renderer->setAttribute('hk2', trim($v_shohinarr['M02HYOJIKEY2']));
        $otherMessage = $request->getAttribute('other_message');
        if (preg_match("~<br[ /]*>~mi", $otherMessage)) {
            $otherMessages = preg_split("~<br[ /]*>~", str_replace(array("\r\n", "\r", "\n"), '', $otherMessage));
        } else {
            $otherMessages = explode("\n", $otherMessage);
        }
        $renderer->setAttribute('other_messages', $otherMessages);
        $renderer->setAttribute('invcard_message', $request->getAttribute('invcard_message'));

        $renderer->setAttribute('is_otameshi', $user->getAttribute('is_virtual_login'));

        //「この商品に交換する」ボタン表示フラグ（0:非表示＋メッセージ、1:表示）
        $renderer->setAttribute('buttonflg', $v_buttonflg);
        $renderer->setAttribute('buttonmsg', $v_buttonmsg); //メッセージ

        // SEO情報
        $renderer->setAttribute('title', $seoarr['title']);              // タイトル
        $renderer->setAttribute('h1', $seoarr['h1']);                    // H1
        $renderer->setAttribute('metaKeywords', $seoarr['metakeyword']); // メタキーワード
        $renderer->setAttribute('metaDesc', $seoarr['metadesc']);        // メタデスクリプション

        // レコメンド商品
        $recommendDispArray = array();
        foreach ($recommendShohinArray as $shohin) {
            $recommendDispArray[] = array(
                'no'           => $shohin->get('M02SHOHNNO'),       // 商品番号
                'code'         => trim($shohin->get('M02SHOHNCD')), // 商品コード
                'priceAndTax'  => $shohin->get('M02VPOINT'),        // ポイント
                'displayNm'    => $shohin->getSeoName(),            // 商品名（SEO）
                'brandNm'      => $shohin->get('M02BRAND'),         // ブランド名
                'linkToShosai' => $shohin->get('linkToShosai'),     // 商品詳細画面へのリンクURL
            );
        }
        $renderer->setAttribute('recommendShohinArray', $recommendDispArray);

        $checkRirekiDispArray = array();
        foreach ($checkRirekiShohinArray as $shohin) {
            $checkRirekiDispArray[] = array(
                'no'           => $shohin->get('M02SHOHNNO'),       // 商品番号
                'code'         => trim($shohin->get('M02SHOHNCD')), // 商品コード
                'priceAndTax'  => $shohin->get('M02VPOINT'),        // ポイント
                'displayNm'    => $shohin->getSeoName(),            // 商品名（SEO）
                'brandNm'      => $shohin->get('M02BRAND'),         // ブランド名
                'linkToShosai' => $shohin->get('linkToShosai'),     // 商品詳細画面へのリンクURL
            );
        }
        $renderer->setAttribute('checkRirekiShohinArray', $checkRirekiDispArray);

        // 関連商品
        $shohinGroupDispArray = array();
        foreach ($shohinGroupArray as $shohin) {
            $shohinGroupDispArray[] = array(
                'no'           => $shohin->get('M02SHOHNNO'),       // 商品番号
                'code'         => trim($shohin->get('M02SHOHNCD')), // 商品コード
                'priceAndTax'  => $shohin->get('M02VPOINT'),        // ポイント
                'displayNm'    => $shohin->getSeoName(),            // 商品名（SEO）
                'brandNm'      => $shohin->get('M02BRAND'),         // ブランド名
                'linkToShosai' => $shohin->get('linkToShosai'),     // 商品詳細画面へのリンクURL
            );
        }
        $renderer->setAttribute('shohinGroupArray', $shohinGroupDispArray);

		//#1641 start
		$preview_flg = 0;
		if($request->hasParameter('__preview__')){
			$preview_flg = 1;
			$j_gentei = $v_shohinarr['M02GENTEI'];
			$j_nokori = $v_shohinarr['M02NOKORI'];
			$j_liqrflg = $v_shohinarr['M02LIQRFLG'];
			$j_sdate = $v_shohinarr['M02SDATE'];
			$j_edate = $v_shohinarr['M02EDATE'];
			$j_taxfreeflg = $v_shohinarr['M02TAXFREEFLG'];
			$j_noki = $v_shohinarr['M02NOKI'];
			$j_kiseflg = $v_shohinarr['M02KISEFLG'];
			$j_kikanghkflg = $v_shohinarr['M02KIKANGHKFLG'];
			$j_haisofjflg = $v_shohinarr['M02HAISOFJFLG'];
			$j_mailfukaflg = $v_shohinarr['M02MAILFUKAFLG'];
			$dbc = new ShohinShosaiQuerySel();

			//限定数・残り数
			$j_zaiko_view = '';
			if($j_gentei < 1 && $j_nokori < 1) {
				$j_zaiko_view = '在庫設定なし';
			}else{
				$j_zaiko_view = '限定数：'.$j_gentei.'個／残り数：'.$j_nokori.'個';
			}

			//アラート区分
			$j_liqrflg_view = '';
			if($j_liqrflg == 1) {
				$j_liqrflg_view = '酒類';
			}elseif($j_liqrflg == 2) {
				$j_liqrflg_view = '転売不可';
			}elseif($j_liqrflg == 3) {
				$j_liqrflg_view = '季節商品';
			}else{
				$j_liqrflg_view = 'なし';
			}
			
			//販売期間
			$j_sdate_view = '';
			$j_edate_view = '';
			if($j_sdate) {
				$j_sdates = explode('.',$j_sdate);
				list($syear,$smonth,$sday,$shour,$sminute,$ssecond) = preg_split('/[-: ]/',$j_sdates[0]);
				$j_sdate_view = $syear.'年'.$smonth.'月'.$sday.'日 '.$shour.':'.$sminute.':'.$ssecond;
			}
			if($j_edate) {
				$j_edates = explode('.',$j_edate);
				list($eyear,$emonth,$eday,$ehour,$eminute,$esecond) = preg_split('/[-: ]/',$j_edates[0]);
				$j_edate_view = $eyear.'年'.$emonth.'月'.$eday.'日 '.$ehour.':'.$eminute.':'.$esecond;
			}
			
			//非課税
			$j_taxfreeflg_view = '';
			if($j_taxfreeflg == 1) {
				$j_taxfreeflg_view = '非課税商品';
			} else {
				$j_taxfreeflg_view = '課税商品';
			}
			
			//標準納期
			$j_noki_view = '';
			if($j_noki) {
				$j_noki_view = 'お申し込みから'.$j_noki.'日';
			} else {
				$j_noki_view = 'お届け日指定不可';
			}
			
			//季節商品区分
			$j_kiseflg_view = '';
			if($j_kiseflg == 1) {
				$dbc->setSelectSql('11');
				$dbc->setRecordsetArray(array('F04SHOHNNO' => $v_shohinarr['M02SHOHNNO']));
				$rs = $dbc->Execute();
				// DB取得エラー
				if (!$rs) {
                    throw new Exception(E_DB_EXECUTE_ERR);
				}
                $i = 0;
                while (!$rs->EOF) {
                    $j_kiseflg_view[$i]['F04RENBAN'] = $rs->fields('F04RENBAN');
                    if($rs->fields('F04SDATE')) {
                        $f04_sdate = explode('.',$rs->fields('F04SDATE'));
                        list($syear,$smonth,$sday,$shour,$sminute,$ssecond) = preg_split('/[-: ]/',$f04_sdate[0]);
                        $j_kiseflg_view[$i]['F04SDATE'] = $syear.'年'.$smonth.'月'.$sday.'日 '.$shour.':'.$sminute.':'.$ssecond;
                    }
                    if($rs->fields('F04EDATE')) {
                        $f04_edate = explode('.',$rs->fields('F04EDATE'));
                        list($eyear,$emonth,$eday,$ehour,$eminute,$esecond) = preg_split('/[-: ]/',$f04_edate[0]);
                        $j_kiseflg_view[$i]['F04EDATE'] = $eyear.'年'.$emonth.'月'.$eday.'日 '.$ehour.':'.$eminute.':'.$esecond;
                    }

                    $rs->MoveNext();
                    $i++;
                }
				$rs->Close();
			} else {
				$j_kiseflg_view = '季節商品ではない';
			}
			
			//期間限定配送形態区分
			$j_kikanghkflg_view = '';
			if($j_kikanghkflg == 1) {
				$dbc->setSelectSql('12');
				$dbc->setRecordsetArray(array('F72SHOHNNO' => $v_shohinarr['M02SHOHNNO']));
				$rs = $dbc->Execute();
				if (!$rs) {
                    throw new Exception(E_DB_EXECUTE_ERR);
				}
                $i = 0;
                while (!$rs->EOF) {
                    $j_kikanghkflg_view[$i]['F72RENBAN'] = $rs->fields('F72RENBAN');
                    if($rs->fields('F72HAISOKBN') == 1) {
                        $j_kikanghkflg_view[$i]['F72HAISOKBN'] = '常温';
                    } elseif($rs->fields('F72HAISOKBN') == 2) {
                        $j_kikanghkflg_view[$i]['F72HAISOKBN'] = '冷蔵';
                    } elseif($rs->fields('F72HAISOKBN') == 3) {
                        $j_kikanghkflg_view[$i]['F72HAISOKBN'] = '冷凍';
                    } else {
                        $j_kikanghkflg_view[$i]['F72HAISOKBN'] = '';
                    }
                    if($rs->fields('F72SDATE')) {
                        $f72_sdate = explode('.',$rs->fields('F72SDATE'));
                        list($syear,$smonth,$sday,$shour,$sminute,$ssecond) = preg_split('/[-: ]/',$f72_sdate[0]);
                        $j_kikanghkflg_view[$i]['F72SDATE'] = $syear.'年'.$smonth.'月'.$sday.'日 '.$shour.':'.$sminute.':'.$ssecond;
                    }
                    if($rs->fields('F72EDATE')) {
                        $f72_edate = explode('.',$rs->fields('F72EDATE'));
                        list($eyear,$emonth,$eday,$ehour,$eminute,$esecond) = preg_split('/[-: ]/',$f72_edate[0]);
                        $j_kikanghkflg_view[$i]['F72EDATE'] = $eyear.'年'.$emonth.'月'.$eday.'日 '.$ehour.':'.$eminute.':'.$esecond;
                    }

                    $rs->MoveNext();
                    $i++;
				}
				$rs->Close();
			} else {
				$j_kikanghkflg_view = '期間限定配送ではない';
			}
			
			//配送指定不可除外区分
			$j_haisofjflg_view = '';
			if($j_haisofjflg == 1) {
				$j_haisofjflg_view = '配送指定不可日を除外する';
			} else {
				$j_haisofjflg_view = '配送指定不可日を除外しない';
			}
			
			//出荷報告メール送信不可区分
			$j_mailfukaflg_view = '';
			if($j_mailfukaflg == 1) {
				$j_mailfukaflg_view = '出荷報告メールを送信しない';
			} else {
				$j_mailfukaflg_view = '出荷報告メールを送信する';
			}
			
			$renderer->setAttribute('preview_flg', $preview_flg);
			$renderer->setAttribute('j_zaiko_view', $j_zaiko_view);
			$renderer->setAttribute('j_liqrflg_view', $j_liqrflg_view);
			$renderer->setAttribute('j_sdate_view', $j_sdate_view);
			$renderer->setAttribute('j_edate_view', $j_edate_view);
			$renderer->setAttribute('j_taxfreeflg_view', $j_taxfreeflg_view);
			$renderer->setAttribute('j_noki_view', $j_noki_view);
			$renderer->setAttribute('j_kiseflg_view', $j_kiseflg_view);
			$renderer->setAttribute('j_kikanghkflg_view', $j_kikanghkflg_view);
			$renderer->setAttribute('j_haisofjflg_view', $j_haisofjflg_view);
			$renderer->setAttribute('j_mailfukaflg_view', $j_mailfukaflg_view);
		}
		//#1641 end

        return $renderer;
    }

}
