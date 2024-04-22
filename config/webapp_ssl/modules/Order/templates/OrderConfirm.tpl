{capture name=header}
<script>
{literal}
(function (d) {
    var config = {
        kitId: 'btv7ivp',
        scriptTimeout: 3000,
        async: true
    },
    h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
})(document);
{/literal}
</script>
{/capture}
<div class="Wrapper login-bg">
{include file=$headerTemplate}
<form method="post" name="OrdererConfirm" action="{wt_action_url mod='Order' act='OrderConfirm'}" autocomplete="off">
<main>
    <div class="c-inner">
        <section class="l-section l-section--qn">
            <div class="l-section__wrap">
                <div id="wrapper">
                    <div id="contents">
                        <div class="c-flow">
                            <ul class="c-flow__container">
                                <li class="done">
                                    <div class="circle">
                                        <p>入力</p>
                                    </div>
                                </li>
                                <li class="here">
                                    <div class="circle">
                                        <p>確認</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="circle">
                                        <p>完了</p>
                                    </div>
                                </li>
                            </ul>
                            <figure class="sp">
                                <img src="/assets/img/cart/or_2-sp.png" alt="確認">
                            </figure>
                        </div>

{include file=$smarty.const.WT_COMMON_TEMPLATE_DIR|cat:"default_error_message.tpl"}

                        <div class="c-top__titlebox">
                            <h2 class="l-section__title">お申し込み内容ご確認</h2>
                        </div>
                        <article class="p-tableBox">
                            <div class="tableInfo tableInfo--confirm">
                                <h3 class="tableInfo__title">お届け先</h3>
                                <table>
                                    <tbody>
                                        <tr>
                                            <th>お名前</th>
                                            <td data-stt-ignore="">
                                                {$chumonsha_info[Chumonsha::SEI_KANJI]|escape} {$chumonsha_info[Chumonsha::MEI_KANJI]|escape}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>郵便番号</th>
                                            <td>
                                                {$chumonsha_info[Chumonsha::ZIP1]|escape}-{$chumonsha_info[Chumonsha::ZIP2]|escape}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>住所</th>
                                            <td data-stt-ignore="">{strip}
                                                {$chumonsha_info[Chumonsha::ADD1]|escape}
                                                {$chumonsha_info[Chumonsha::ADD2]|escape}
                                                {$chumonsha_info[Chumonsha::ADD3]|escape}
                                            {/strip}</td>
                                        </tr>
                                        <tr>
                                            <th>電話番号</th>
                                            <td data-stt-ignore="">{strip}
                                                {$chumonsha_info[Chumonsha::TEL_SHIGAI]|escape}
                                                -
                                                {$chumonsha_info[Chumonsha::TEL_SHINAI]|escape}
                                                -
                                                {$chumonsha_info[Chumonsha::TEL_KYOKUNAI]|escape}
                                            {/strip}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </article>
{if $noshi_flg}
                        <article class="p-tableBox">
                            <div class="tableInfo tableInfo--confirm">
                                <h3 class="tableInfo__title">熨斗オプション：{if $giftservice_info[GiftService::NOSHI_NO] == '00'}なし{else}内のし{/if}</h3>
{if $giftservice_info[GiftService::NOSHI_NO] != '00'}
                                <table>
                                    <tbody>
                                        <tr>
                                            <th>赤ちゃんの名前</th>
                                            <td>{$giftservice_info[GiftService::NOSHI_NAME_RIGHT]|default:"名入れ無し"|escape}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>ふりがな</th>
                                            <td>{$giftservice_info[GiftService::NOSHI_NAME_LEFT]|default:"名入れ無し"|escape}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
{/if}
                            </div>
                        </article>
{/if}
                        <div class="l-section__goods inCarts">
{foreach from=$otodokesaki_list item=otodokesaki}
{foreach from=$otodokesaki[Otodokesaki::SHOHIN_LIST] item=buy_info}
{assign var="shohin_no" value=$buy_info[OtodokeShohin::SHOHIN_NO]}
{assign var="shohin_info" value=$shohin_info_list[$shohin_no]}
                            <div class="inCart">
                                <div class="inCart__product">
                                    <div class="inCart__img">
{tms_html_image alt="商品画像" width="non" height="150" shohin_code={$shohin_info[ShohinInformation::SHOHIN_CODE]} image_type="4"}
                                    </div>
                                    <div class="inCart__desc">
                                        <p class="inCart__proNum">
                                            申込番号：{$shohin_info[ShohinInformation::SHOHIN_CODE]|escape}</p>
{if $shohin_info[ShohinInformation::BRAND_NAME]}
                                        <p class="inCart__cat" data-stt-ignore>{$shohin_info[ShohinInformation::BRAND_NAME]|escape}</p>
{/if}
                                        <p class="inCart__name" data-stt-ignore>{$shohin_info[ShohinInformation::SHOHIN_NAME]|escape}</p>
                                        <div class="inCart__amount pulldown01">
                                            <p>商品個数：{$buy_info[OtodokeShohin::KONYU_SURYO]|number_format|escape}個</p>
                                            <button type="submit" name="BTN_QCHG" class="cancel_btn">変更</button>
                                        </div>

                                            <p class="inCart__point">{$buy_info[OtodokeShohin::KAKAKU_ZEINUKI]|number_format|escape}<span class="inCart__pointS">POINTS</span></p>

                                    </div>
                                </div>
                                <div class="inCart__preferredDate preferredDate">
                                    <div class=" preferredDate-inner">
                                        <p class="preferredDate__title">【お届け希望日】</p>
                                        <p>
{if $buy_info[OtodokeShohin::HAISO_KIBO_DATE]}
                                            {$buy_info[OtodokeShohin::HAISO_KIBO_DATE]|wt_date_format:'Y年m月d日'}
{else}
                                            指定なし
{/if}
                                        </p>
                                    </div>
                                </div>
                            </div>
{/foreach}
{/foreach}
                        </div>

                        <div class="c-top__titlebox">
                            <h2 class="l-section__title">交換ポイント</h2>
                        </div>

                        <article class="p-tableBox">
                            <div class="tableInfo tableInfo--confirm">
                                <h3 class="tableInfo__title">交換ポイント</h3>
                                <table>
                                    <tbody>
                                        <tr>
                                            <th>合計交換ポイント</th>
                                            <td data-stt-ignore="">
                                                {$shohin_point|number_format|escape} Points
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>ご利用可能ポイント</th>
                                            <td>
                                                {$remain_point|number_format|escape} Points
                                            </td>
                                        </tr>
{if $creditcard_info}
                                        <tr>
                                            <th>クレジットカード合計</th>
                                            <td data-stt-ignore="">{$creditcard_info[Creditcard::CREDITCARD_TOTAL]|number_format|escape} Points</td>
                                        </tr>
{/if}
                                    </tbody>
                                </table>
                            </div>
                        </article>

{if $creditcard_info}
                        <div class="c-top__titlebox">
                            <h2 class="l-section__title">チャージポイント</h2>
                        </div>

                        <article class="p-tableBox">
                            <div class="tableInfo tableInfo--confirm">
                                <h3 class="tableInfo__title">クレジットカード</h3>
                                <table>
                                    <tbody>
                                        <tr>
                                            <th>クレジットカード合計</th>
                                            <td data-stt-ignore="">
                                                {$creditcard_info[Creditcard::CREDITCARD_TOTAL]|number_format|escape} Points
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>支払額・税込</th>
                                            <td>
                                                {$creditcard_info[Creditcard::CREDITCARD_TOTAL]|number_format|escape} 円
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </article>
{/if}

                        <div class="l-section__goods inCarts total-points">
                            <div class="inCart">
                                <p class="inCart-text-size"><span
                                        class="small-text-size-before">合計</span>{$shohin_point|number_format|escape}<span
                                        class="small-text-size-after">POINTS</span></p>
                            </div>
                        </div>
                        <div class="infosComfirm">
                            <p class="infosComfirm__note">
                                ※特定商取引に関する法律に基づく表記は<a class="under-bar" href="/tokusyoho/" target="_blank" rel="noopener">こちら</a>
                            </p>
                        </div>
                        <div class="tableInfos__btns tableInfos__btns--confirm pc">
                            <div class="cntFtrButtons">
                                <button type="submit" name="BTN_BACK" class="cntFtrBtn_prev btn_gray01 btn_hover01 btn--info--fix">戻る</button>
                                <button type="submit" name="BTN_NEXT" class="cntFtrBtn_next btn_gold01 btn_hover01 btn--info btn--info--comfirm">この内容で申し込む</button>
                            </div>
                        </div>
                        <div class="tableInfos__btns tableInfos__btns--confirm sp">
                            <div class="cntFtrButtons">
                                <button type="submit" name="BTN_NEXT" class="cntFtrBtn_next btn_gold01 btn_hover01 btn--info btn--info--comfirm">この内容で申し込む</button>
                                <button type="submit" name="BTN_BACK" class="cntFtrBtn_prev btn_gray01 btn_hover01 btn--info--fix">戻る</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
<input type="hidden" name="{$const.POST_TOKEN_NAME}" value="{$wt__post_token}">
</form>
<script>
let submited = false;
$('[name="BTN_NEXT"]').click(() => {
    if (!submited) {
        submited = true;
        return true;
    }
    return false;
});
</script>