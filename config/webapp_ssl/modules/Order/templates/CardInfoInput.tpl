{capture name=header}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">
<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
<script>
{literal}
(function(d) {
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
<form method="post" name="CardInfoInput" action="{wt_action_url mod='Order' act='CardInfoInput'}">
<main>
    <div class="c-inner">
        <section class="l-section l-section--qn">
            <div class="l-section__wrap">
                <!--====== WRAPPER IN ======-->
                <div id="wrapper">
                    <!--====== CONTENTS IN ======-->
                    <div id="contents">
                        <div class="c-flow">
                            <ul class="c-flow__container">
                                <li class="here">
                                    <div class="circle">
                                        <p>入力</p>
                                    </div>
                                </li>
                                <li>
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
                                <img src="/assets/img/cart/or_1-sp.png" alt="入力">
                            </figure>
                        </div>
                        <div class="c-top__titlebox">
                            <h2 class="l-section__title l-section__title--cart l-section__title--cart--sp l-section__title--cart-pading2">
                                お支払い
                            </h2>
                        </div>
                        <div class="c-top__titlebox">
                            <h2 class="l-section__title">カートに入っている商品</h2>
                        </div>
                        <div class="l-section__goods inCarts mt50">
{foreach from=$otodokesaki_list item=otodokesaki key=otodoke_index}
{foreach from=$otodokesaki[Otodokesaki::SHOHIN_LIST] item=buy_info name=ots}
{assign var="shohin_no" value=$buy_info[OtodokeShohin::SHOHIN_NO]}
{assign var="shohin_info" value=$shohin_info_list[$shohin_no]}
                            <div class="inCart">
                                <div class="inCart__product">
                                    <div class="inCart__img">
{tms_html_image alt="商品画像" width="non" height="150" shohin_code={$shohin_info[ShohinInformation::SHOHIN_CODE]} image_type="4"}
                                    </div>
                                    <div class="inCart__desc">
                                        <p class="inCart__proNum">申込番号：{$shohin_info[ShohinInformation::SHOHIN_CODE]|escape}</p>
{if $shohin_info[ShohinInformation::BRAND_NAME]}
                                        <p class="inCart__cat">{$shohin_info[ShohinInformation::BRAND_NAME]|escape}</p>
{/if}
                                        <p class="inCart__name" data-stt-ignore>{$shohin_info[ShohinInformation::SHOHIN_NAME]|escape}</p>
                                        <div class="inCart__amount pulldown01">
                                            <p>商品個数：{$buy_info[OtodokeShohin::KONYU_SURYO]|number_format|escape}個</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="inCart__preferredDate preferredDate">
                                    <div class=" preferredDate-inner">
                                        <p class="preferredDate__title">【交換ポイント】</p>
                                        <p class="inCart-text-size">{$buy_info[OtodokeShohin::KAKAKU_ZEINUKI]|number_format|escape}<span class="small-text-size-after">POINTS</span></p>
                                    </div>
                                    <div>
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

                        <article class="p-tableBox">
                            <div class="tableInfo tableInfo--confirm">
                                <table>
                                    <tbody>
                                    <tr>
                                        <th>合計交換ポイント</th>
                                        <td>
                                            <p class="inCart-text-size">{$shohin_point|number_format|escape}<span class="small-text-size-after">POINTS</span></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>ご利用可能ポイント</th>
                                        <td>
                                            <p class="inCart-text-size">{$remain_point|number_format|escape}<span class="small-text-size-after">POINTS</span></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>不足ポイント</th>
                                        <td>
                                            <p class="inCart-text-size" style="color: #FF0000;">{if $remain_point >= $shohin_point}0{else}{($remain_point - $shohin_point)|number_format}{/if}<span class="small-text-size-after">POINTS</span></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>支払額</th>
                                        <td>
                                            <p class="inCart-text-size">{$creditcard_info[Creditcard::CREDITCARD_TOTAL]|number_format}<span class="small-text-size-after">円</span><span class="small-text-size-before">(税込：課税区分10％)</span></p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </article>
                      </div>
                      <div class="tableInfos__btns tableInfos__btns--confirm pc">
                          <div class="cntFtrButtons">
                              <button type="submit" name="BTN_BACK" class="cntFtrBtn_prev btn_gray01 btn_hover01 btn--info--fix">戻る</button>
                              <button type="submit" name="BTN_NEXT" class="cntFtrBtn_next btn_gold01 btn_hover01 btn--info btn--info--comfirm do-gmo-payment">次へ</button>
                          </div>
                      </div>
                      <div class="tableInfos__btns tableInfos__btns--confirm sp">
                          <div class="cntFtrButtons">
                              <button type="submit" name="BTN_NEXT" class="cntFtrBtn_next btn_gold01 btn_hover01 btn--info btn--info--comfirm do-gmo-payment">次へ</button>
                              <button type="submit" name="BTN_BACK" class="cntFtrBtn_prev btn_gray01 btn_hover01 btn--info--fix">戻る</button>
                          </div>
                      </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
</form>
<script src="{$gmo_token_js|escape}"></script>
<script type="text/javascript">
const gmoShopId = '{$gmo_shop_id|escape}';
{literal}
const zen2han = function(s) {
    if (s.length) {
        const c = '\\uFF21-\\uFF3A\\uFF41-\\uFF5A\\uFF10-\\uFF19';
        return s.replace(new RegExp('[' + c + ']', 'g'), (s) => {
            return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
        });
    }
    return s;
};
const validatePaymentParams = (holderName, cardNo, expYear, expMonth, securityCode) => {
    let errors = [];
    if (!holderName.length) {
        errors.push('カード名義を入力してください。');
    } else if (!holderName.match(/^[a-zA-Z ]{1,50}$/)) {
        errors.push('カード名義は50文字以内の半角英字で入力してください。');
    }
    if (!cardNo.length) {
        errors.push('カード番号を入力してください。');
    } else if (!cardNo.match(/^[0-9]{14,16}$/)) {
          errors.push('カード番号は14～16桁の数字で入力してください。');
    }
    if (!expYear.length) {
        errors.push('カード有効期限_年を選択してください。');
    }
    if (!expMonth.length) {
        errors.push('カード有効期限_月を選択してください。');
    }
    if (expYear.length && expMonth.length) {
        const date = new Date();
        const ym = parseInt(date.getFullYear() + ('0' + (date.getMonth() + 1)).slice(-2));
        if (parseInt(expYear + expMonth) < ym) {
            errors.push('カード有効期限は期限切れです。 ');
        }
    }
    if (!securityCode.length) {
        errors.push('セキュリティコードを入力してください。');
    } else if (!securityCode.match(/^[0-9]{3,4}$/)) {
        errors.push('セキュリティコードは3～4桁の数字で入力してください。');
    }
    return errors;
}
const GmoPaymentCallback = (response) => {
    //console.log('GMO-callback', response);
    if (response.resultCode !== '000') {
        const errors = {
            '100': 'カード番号を入力してください。',
            '101': 'カード番号は10～16桁の数字で入力してください。',
            '102': 'カード番号は10～16桁の数字で入力してください。',
            '110': 'カード有効期限を選択してください。',
            '111': 'カード有効期限を選択してください。',
            '112': 'カード有効期限を選択してください。',
            '113': 'カード有効期限を選択してください。',
            '121': 'セキュリティコードは3～4桁の数字で入力してください。',
            '122': 'セキュリティコードは3～4桁の数字で入力してください。',
            '131': 'カード名義は50文字以内の半角英数字で入力してください。',
            '132': 'カード名義は50文字以内の半角英数字で入力してください。'
        };
        let message = '';
        if (typeof(errors[response.resultCode]) != 'undefined') {
            message = errors[response.resultCode] + ' [error' + response.resultCode + ']';
        } else {
            message = '購入処理中にエラーが発生しました: ' + response.resultCode;
        }
        const $error = $('div.p-login__alert');
        $('ul.p-login__alert--list', $error).html('<li>' + message + '</li>');
        $error.show();
        $(window).scrollTop($error.offset().top);
    } else {
        $('#gmo_token').val(response.tokenObject.token);
        const $form = $('form[name="CardInfoInput"]');
        $('<input>').attr({
            type : 'hidden',
            name : 'BTN_NEXT',
            value: true
        }).appendTo($form);
        $form.submit();
    }
};
$(function() {
    $('.do-gmo-payment').click(function() {
        const h2zList = ['#holder_name', '#card_number', '#security_code'];
        for (let i = 0; i < h2zList.length; i++) {
            const $input = $(h2zList[i]);
            $input.val(zen2han($input.val()).trim());
        }
        const holderName = $('#holder_name').val();
        const cardNumber = $('#card_number').val();
        const securityCode = $('#security_code').val();
        const expirationYear = $('#expiration_year  option:selected').val();
        const expirationMonth = $('#expiration_month option:selected').val();
        const errors = validatePaymentParams(holderName, cardNumber, expirationYear, expirationMonth, securityCode);
        if (errors.length) {
            const $error = $('div.p-login__alert');
            $('ul.p-login__alert--list', $error).html('<li>' + errors.join('</li><li>') + '</li>');
            $error.show();
            $(window).scrollTop($error.offset().top);
            return false;
        }
        const gmoParams = {
            cardno: cardNumber,
            expire: expirationYear + expirationMonth,
            securitycode: securityCode,
            holdername: holderName
        };
        Multipayment.init(gmoShopId);
        Multipayment.getToken(gmoParams, GmoPaymentCallback);
        return false;
    });
});
{/literal}
</script>
