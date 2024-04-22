    <!--====== WRAPPER IN ======-->
    <div id="wrapper">
        <!--====== CONTENTS IN ======-->
        <form method="post" name="OrderHistory" action="{wt_action_url mod="OrderHistory" act="OrderHistory"}" autocomplete="off">
        <div id="contents">
            <div id="section01" class="section topRadius">
                <h2>残ポイント照会・有効期限・交換履歴</h2>
                <p class="hedBottomTxt">リンベルスマートギフトカードの残ポイント・有効期限、<br>お申し込み済のお客さまは交換履歴をご確認いただけます。<br>
                リンベルスマートギフトカードの<br class="sp">ギフトカード番号とPIN番号を入力して<br>「確認する」ボタンを押してください。</p>

{include file=$smarty.const.WT_COMMON_TEMPLATE_DIR|cat:"default_error_message.tpl"}

                <div class="secInner">
                    <p class="txtAlignR fntB"><span class="requiredMark">*</span>必須入力項目</p>

                    <div class="secInnerClear">
                        <div class="secInnerLeft">
                            <div class="formGroup group01">
                                <label><p class="requiredMark">*</p>ギフトカード番号</label>
                                <input type="text" name="giftcard_no" class="long" value="" placeholder="ギフトカード番号（半角英数字）">
                            </div>
                        </div>
                        <div class="secInnerRight">
                            <div class="formGroup group02">
                                <label><p class="requiredMark">*</p>PIN番号</label>
                                <input type="password" name="pin_no" class="long" value="" placeholder="PIN番号（半角英数字）" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div id="confirm" class="section01_btn01 btn_gold01 btn_hover01"><a href="javascript:void(0);">確認する</a></div>
                <!--[/.secInner]--></div>


                <div class="section01_links01">
                    <p><span>ギフトカード番号・PIN番号</span><br class="sp"><a href="{$smarty.const.HYOUKI_URL}">表記について</a></p>
                </div>

{if !empty($histories)}
                <div id="section01-01" class="boundary">
                    <h2>残ポイント照会</h2>
                    <div class="secInner">
                        <table>
                            <tr cellpadding="3">
                                <td class="giftcard">
                                    <span class="section01-01_sttl01">ギフトカード番号</span><br class="pc">
                                    <span class="section01-01_result01">{$histories->cardInfo->cardNo|escape}</span>
                                </td>
                                <td class="period">
                                    <span class="section01-01_sttl01">有効期限</span><br class="pc">
                                    <span class="section01-01_result01">{$histories->cardInfo->expireDate|wt_date_format:'Y年m月d日(D)'}</span>
                                </td>
                                <td class="point">
                                    <span class="section01-01_sttl01">ポイント残高</span><br class="pc">
                                    <span class="section01-01_result01">{$histories->cardInfo->balance|number_format|escape}<div class="points">Points</div></span>
                                </td>
                                <td class="btn">
                                    <a href="{wt_action_url mod="ShukaJyokyo" act="ShukaJyokyo"}?ft=0" class="btn_gold01 btn_hover01">出荷状況照会</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                <!--[#section01-01]--></div>

                <div id="section01-02" class="boundary">
                    <h2>交換履歴</h2>
                    <div class="secInner">
{if !count($histories->cardHistory->tradeInfo)}
                        <p>ただいま照会可能な交換履歴はございません<br><br>WEBでのお申し込み以外のお客さまは、<br class="sp">この画面での反映に時間がかかる場合がございます。ご了承ください。</p>
{else}
                        <table>
{foreach from=$histories->cardHistory->tradeInfo item=row}
                            <tr cellpadding="3">
                                <td class="giftcard">
                                    <span class="section01-01_sttl01">ご利用日時</span><br class="pc">
                                    <span class="section01-01_result01">{$row->tradeDate|wt_date_format:'Y/m/d(D) H:i:s'}</span>
                                </td>
                                <td class="period">
                                    <span class="section01-01_sttl01">ご利用方法</span><br class="pc">
                                    {$row->shopName|escape|default:'&nbsp;'}
                                </td>
                                <td class="point">
                                    <span class="section01-01_sttl01">ご利用ポイント</span><br class="pc">
                                    <span class="section01-01_result01">{$row->usedPoint|number_format|escape}<div class="points">Points</div></span>
                                </td>
                            </tr>
{/foreach}
                        </table>
{/if}
                    </div>
                <!--[/#section01-02]--></div>
{/if}
            <!--[/#section01]--></div>

            <div class="cntFtrButtons">
{if !$wt__is_login || $is_virtual}
                <div id="go_login" class="cntFtrBtn_top btn_gold01 btn_hover01"><a href="{wt_action_url mod="Default" act="Login"}">ログインページへ</a></div>
{else}
                <div id="go_top" class="cntFtrBtn_top btn_gold01 btn_hover01"><a href="{wt_action_url}">TOPページへ</a></div>
{/if}
            <!--[/.cntFtrButtons]--></div>

        </div>
        <input type="hidden" name="BTN_SEARCH" value="1">
        </form>
        <!--====== CONTENTS OUT ======-->

    </div>
    <!--====== WRAPPER OUT ======-->
