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
<div class="Wrapper">
{include file=$headerTemplate}
<form method="post" name="OrdererInfoInput" action="{wt_action_url mod='Order' act='OrdererInfoInput'}" autocomplete="off">
<main>
    <div class="c-inner">
        <section class="l-section l-section--qn">
            <div class="l-section__wrap">
                <div class="flow">
                    <picture>
                        <source media="(max-width: 768px)" srcset="/assets/img/cart/or_1-sp.png">
                        <source media="(min-width: 769px)" srcset="/assets/img/cart/or_1-pc.png">
                        <img src="/assets/img/cart/or_1-pc.png" alt="">
                    </picture>
                </div>

{include file=$smarty.const.WT_COMMON_TEMPLATE_DIR|cat:"default_error_message.tpl"}

{if !empty($shohin_info_list)}
                <div class="c-top__titlebox">
                    <h2 class="l-section__title">カートに入っている商品</h2>
                </div>
                <div class="l-section__goods inCarts mt50">
{foreach from=$shohin_info_list item=shohin_info key=shohin_no name=si}
{assign var="shohin_link" value="{wt_action_url mod="ShohinShosai" act="ShohinShosai"}shohin/{$shohin_info[ShohinInformation::SHOHIN_NO]}"}
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
                                <p class="inCart__name" data-stt-ignore><a href="{$shohin_link}">{$shohin_info[ShohinInformation::SHOHIN_NAME]|escape}</a></p>
                                <p class="inCart__point"><span class="vpoint">{$shohin_info[ShohinInformation::KAKAKU_ZEINUKI]|escape|number_format}</span><span class="inCart__pointS">POINTS</span></p>
                                <div class="inCart__amount pulldown01">
                                    <div class="button--count"><input type="button" class="spinner_down"></div>
                                    <input type="text" class="number input-quantity" name="quantity_{$shohin_no|escape}" value="{$shohin_info[ShohinInformation::KONYU_SURYO]|escape}" tabindex="1" style="background-color: rgb(255, 255, 255);">
                                    <div class="button--count right"><input type="button" class="spinner_up"></div>
                                    <button type="submit" name="BTN_DELETE_{$shohin_no|escape}" class="cancel_btn" >取消</button>
                                </div>
                            </div>
                        </div>
{if $shohin_info[ShohinInformation::OTODOKE_KANO_DATE]}
{if $shohin_info[ShohinInformation::OTODOKE_KIBO_DATE]}
{assign var=temp value="-"|explode:$shohin_info[ShohinInformation::OTODOKE_KIBO_DATE]}
{else}
{assign var=temp value="-"|explode:$shohin_info[ShohinInformation::OTODOKE_KANO_DATE]}
{/if}
{assign var=vy value=$temp[0]}
{assign var=vm value=$temp[1]}
{assign var=vd value=$temp[2]}
                        <div class="inCart__preferredDate preferredDate">
                            <p class="preferredDate__title">【お届け希望日】</p>
{assign var=hv value="1"}
{if $shohin_info[ShohinInformation::KISETSU_SHOHIN_FLG] != '1'}
{if (!isset($haiso_select[$shohin_no]) || $haiso_select[$shohin_no] != '1') && !strlen($shohin_info[ShohinInformation::OTODOKE_KIBO_DATE])}
{assign var=hv value="0"}
{/if}
                            <div class="preferredDate__radios">{strip}
                                <input type="radio" name="haiso_select_{$shohin_no|escape}" value="0" class="haiso_sel" id="none_{$shohin_no|escape}"{if $hv == '0'} checked{/if}>
                                <label for="none_{$shohin_no|escape}">
                                    指定なし　<span class="preferredDate__radiosNote">※14日前後でお届けいたします。</span>
                                </label>
                                <br>
                                <input type="radio" name="haiso_select_{$shohin_no|escape}" value="1" class="haiso_sel" id="yes_{$shohin_no|escape}"{if $hv == '1'} checked{/if}>
                                <label for="yes_{$shohin_no|escape}">
                                    指定あり　<span class="preferredDate__radiosNote">※14日目以降でのお届け指定が可能です。</span>
                                </label>
                            {/strip}</div>
{/if}
                            <div class="preferredDate__date haiso-select"{if $hv == '0'} style="display:none;"{/if}>
                                <input type="tel" name="haiso_kibo_{$shohin_no|escape}_year" value="{$vy}" class="hd-input year">
                                年
                                <input type="tel" name="haiso_kibo_{$shohin_no|escape}_month" value="{$vm}" class="hd-input month">
                                月
                                <input type="tel" name="haiso_kibo_{$shohin_no|escape}_day" value="{$vd}" class="hd-input date">
                                日
                                <input type="tel" class="datepicker hd-input" id="dp_{$shohin_no|escape}">
                            </div>
                            <div class="preferredDate__note haiso-select"{if $hv == '0'} style="display:none;"{/if}>
                                年末年始・お盆・希望商品の在庫状況などによりお選びいただけない日付もございます。ご了承ください。
                            </div>
                        </div>
{/if}
                    </div>
{/foreach}
                </div>
{**}
                <div class="point-change">
                    <p class="text">商品と交換できるポイントがあと<br class="sp"><span class="remain-points"></span>ポイント余っています。<br>
                        お申し込みは1回限りとなります。付与されたポイントを使い切るようにお申し込みください。</p>
                  <!--   <ul class="point-box-cart">
                        <li class="login-point__point login-point__cart">
                            <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-110050/tabAllFlg/1" class="text">5,000pt</a>
                        </li>
                        <li class="login-point__point login-point__cart">
                            <a href="{wt_action_url mod="SS" act="CS"}group/cat_point/catid/0-110080/tabAllFlg/1" class="text">10,000pt</a>
                        </li>
                    </ul> -->
                </div>
{**}
                <h2 class="l-section__title l-section__title--cart l-section__title--cart--sp">お届け先情報</h2>
                <div class="tableInfos">
                    <div class="tableInfo">
                        <h3 class="tableInfo__title">氏名</h3>
                        <table>
                            <tbody>
                            <tr>
                                <th>お名前（漢字）<span class="mandatory_">必須</span></th>
                                <td>
                                    <span class="mr15">姓</span>
                                    <input type="text" name="{$const.SEI_KANJI|escape}" value="{$wt__posts[$const.SEI_KANJI]|escape}" class="mr30">
                                    <span class="mr15">名</span>
                                    <input type="text" name="{$const.MEI_KANJI|escape}" value="{$wt__posts[$const.MEI_KANJI]|escape}" class="mr30">
                                    <span>【全角】</span>
                                </td>
                            </tr>
                            <tr>
                                <th>お名前（ふりがな）<span class="mandatory_">必須</span></th>
                                <td>
                                    <span class="mr15">姓</span>
                                    <input type="text" name="{$const.SEI_KANA|escape}" value="{$wt__posts[$const.SEI_KANA]|escape}" class="mr30">
                                    <span class="mr15">名</span>
                                    <input type="text" name="{$const.MEI_KANA|escape}" value="{$wt__posts[$const.MEI_KANA]|escape}" class="mr30">
                                    <span>【全角】</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tableInfo tableInfo--mail">
                        <h3 class="tableInfo__title">メールアドレス</h3>
                        <table>
                            <tbody>
                            <tr>
                                <th>メールアドレス</th>
                                <td>
                                    <div class="mailcheck">
                                        {$wt__posts[$const.EMAIL_ADDRESS]|escape}
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="tableInfo tableInfo--address">
                        <h3 class="tableInfo__title">住所</h3>
                        <table>
                            <tbody>
                            <tr>
                                <th>郵便番号<span class="mandatory_">必須</span></th>
                                <td>{strip}
                                    <input type="number" name="{$const.ZIP1|escape}" value="{$wt__posts[$const.ZIP1]|escape}" size="4" maxlength="3" onKeyUp="AjaxZip3.zip2addr('{$const.ZIP1|escape}','{$const.ZIP2|escape}','{$const.ADD1|escape}','{$const.ADD2|escape}');" pattern="\d{3}">
                                    &nbsp;－&nbsp;
                                    <input type="number" name="{$const.ZIP2|escape}" value="{$wt__posts[$const.ZIP2]|escape}" size="5" maxlength="4" onKeyUp="AjaxZip3.zip2addr('{$const.ZIP1|escape}','{$const.ZIP2|escape}','{$const.ADD1|escape}','{$const.ADD2|escape}');" pattern="\d{4}">
                                {/strip}</td>
                            </tr>
                            <tr>
                                <th>都道府県<span class="mandatory_">必須</span></th>
                                <td>
                                    <div class="tableInfo__select">
{html_options
    name="{$const.ADD1}"
    options=$pref_list
    selected=$wt__posts[$const.ADD1]
}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>市区町村 番地<span class="mandatory_">必須</span></th>
                                <td><input class="tableInfo__bigInput" type="text" name="{$const.ADD2|escape}" value="{$wt__posts[$const.ADD2]|escape}"></td>
                            </tr>
                            <tr>
                                <th>建物名 部屋番号</th>
                                <td><input class="tableInfo__bigInput" type="text" name="{$const.ADD3|escape}" value="{$wt__posts[$const.ADD3]|escape}"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tableInfo tableInfo--tell">
                        <h3 class="tableInfo__title">電話番号</h3>
                        <table>
                            <tbody>
                            <tr>
                                <th>電話番号<span class="mandatory_">必須</span></th>
                                <td>{strip}
                                    <input type="tel" name="{$const.TEL_SHIGAI|escape}" value="{$wt__posts[$const.TEL_SHIGAI]|escape}">
                                    　―　
                                    <input type="tel" name="{$const.TEL_SHINAI|escape}" value="{$wt__posts[$const.TEL_SHINAI]|escape}">
                                    　―　
                                    <input type="tel" name="{$const.TEL_KYOKUNAI|escape}" value="{$wt__posts[$const.TEL_KYOKUNAI]|escape}">
                                {/strip}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    {if $tokushu_flg}
                    <div class="tableInfo">
                        <h3 class="tableInfo__title">名入れ情報</h3>
                        <table>
                            <tbody>
                            <tr class="tableInfo__tr">
                                <th>名入れ情報</th>
                                <td><textarea class="tableInfo__bigInput" name="{$const.BIKO|escape}">{$wt__posts[$const.BIKO]|escape}</textarea></td>
                            </tr>
                            </tbody>
                        </table>
                        <p>※必要情報は商品詳細ページをご確認ください。</p>
                    </div>
                    {/if}
{if $noshi_flg}
                    <div class="p-sub__box">
                        <h2 class="tableInfo__title tableInfo__title_center">熨斗(のし)</h2>
                        <p class="hedBottomTxt">
                            出産祝いのお返しや、赤ちゃんのお披露目に一般的な「内祝（出産内祝い）」の のしをご用意しています。<br>
                            ご希望によりお子様のお名前をお入れします。漢字のお名前にはふりがなをお入れします。<br>
                            ※双子など、連名をご希望の場合はお名前を併記してください。
                        </p>
                    </div>
                    <div class="p-sub__box tableInfo">
                        <div class="btn_radio_noshi">
                            <label class="select_noshi">
                                <div class="select_noshi_radio_wrap">
                                    <input type="radio" name="{$const.NOSHI_NO|escape}" value="01" class="noshi-select"{if $wt__posts[$const.NOSHI_NO] == '01'} checked{/if}>
                                    <span class="mwform-radio-field-text">内のし</span>
                                </div>
                                <img src="/assets/img/noshi.png" alt="内のし">
                            </label>

                            <label class="select_noshi">
                                <div class="select_noshi_radio_wrap">
                                    <input type="radio" name="{$const.NOSHI_NO|escape}" value="00" class="noshi-select"{if $wt__posts[$const.NOSHI_NO] == '00'} checked{/if}>
                                    <span>のしなし</span>
                                </div>
                                <img src="/assets/img/noshi_none.png" alt="のしなし">
                            </label>
                        </div>
                        <p class="noshi__text">
                            ※熨斗をお付けできるのは「のし」指定可能商品のみとなります。こちらで指定をいただいても熨斗指定ができない商品にはお付けできませんのでご注意ください。
                        </p>
                        <div class="uchinoshiBox"{if $wt__posts[$const.NOSHI_NO] != '01'} style="display:none;"{/if}>
                            <div class="uchinoshiBox__img"><img src="/assets/img/uchinoshiex.png" alt=""></div>
                            <table class="uchinoshiBox__table">
                                <tbody>
                                <tr>
                                    <th>赤ちゃんのお名前</th>
                                    <td><input type="text" name="{$const.NOSHI_NAME_RIGHT|escape}" value="{$wt__posts[$const.NOSHI_NAME_RIGHT]|escape}" class="tableInfo__bigInput"></td>
                                </tr>
                                <tr>
                                    <th>ふりがな</th>
                                    <td><input type="text" name="{$const.NOSHI_NAME_LEFT|escape}" value="{$wt__posts[$const.NOSHI_NAME_LEFT]|escape}" class="tableInfo__bigInput"></td>
                                </tr>
                                </tbody></table>
                            <p class="uchinoshiBox__note">※双子など、連名をご希望の場合は、カンマ区切りでお名前を併記してください。</p>
                        </div>
                    </div>
{/if}
                    <div class="infosComfirm">
                        <label class="check_guide">
                            <input type="checkbox" name="{$const.PRIVACY_POLICY_FLG|escape}" value="1" class="custom-checkbox">
                            <span class="checkmark"></span>
                            個人情報の取り扱いに同意する
                        </label>
                        <p class="infosComfirm__note">
                            ※個人情報の取り扱いについては、<a class="under-bar" href="/privacy/" target="_blank">こちら</a>をご確認ください。
                        </p>
                    </div>
                    <div class="tableInfos__btns tableInfos__btns--confirm pc">
                        <div class="cntFtrButtons">
                            <button type="submit" name="BTN_BACK" class="cntFtrBtn_prev btn_gray01 btn_hover01 btn--info--fix">戻る</button>
                            <button type="submit" name="BTN_NEXT" class="cntFtrBtn_next btn_gold01 btn_hover01 btn--info btn--info--comfirm">次へ</button>
                        </div>
                    </div>
                    <div class="tableInfos__btns tableInfos__btns--confirm sp">
                        <div class="cntFtrButtons">
                            <button type="submit" name="BTN_NEXT" class="cntFtrBtn_next btn_gold01 btn_hover01 btn--info btn--info--comfirm">次へ</button>
                            <button type="submit" name="BTN_BACK" class="cntFtrBtn_prev btn_gray01 btn_hover01 btn--info--fix">戻る</button>
                        </div>
                    </div>
                </div>
{/if}
            </div>
        </section>
    </div>
</main>
</form>
</div>
{include file=$footerTemplate}
{if !empty($shohin_info_list)}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
{literal}
var totalPoints = 0;
var remainPoints = {/literal}{if $remain_point}{$remain_point}{else}0{/if}{literal};
var numberFormat = function(number) {
    let s = String(number).replace(/,/g, '');
    while (s != (s = s.replace(/^(-?\d+)(\d{3})/, '$1,$2')));
    return s;
};
var updateTotalPoints = function() {
    totalPoints = 0;
    $('.inCart__product').each(function () {
        var n = Number($('.input-quantity', $(this)).val());
        if (n) {
            totalPoints += (Number($('.vpoint', $(this)).text().replace(/,/, '')) * n);
        }
    });
    if (remainPoints > totalPoints) {
        $('.remain-points').text(numberFormat(remainPoints - totalPoints));
        $('.point-change').show();
    } else {
        $('.point-change').hide();
        $('.remain-points').text('0');
    }
    return remainPoints - totalPoints;
};
var bindDatePicker = function(selector, hdMin, hdMax) {
    $(selector).datepicker({
        dateFormat: "yy/mm/dd",
        minDate: hdMin,
        maxDate: hdMax,
        dayNamesMin: ['日', '月', '火', '水', '木', '金', '土'],
        showOn: "button",
        buttonImageOnly: true,
        buttonImage: "/assets/img/calendar.png",
        beforeShow: function(input, inst) {
            //開く前に日付を上書き
            var year = $(this).parent().find(".year").val();
            var month = $(this).parent().find(".month").val();
            var date = $(this).parent().find(".date").val();
            $(this).datepicker("setDate", year + "/" + month + "/" + date)
        },
        onSelect: function(dateText, inst) {
            //カレンダー確定時にフォームに反映
            var dates = dateText.split('/');
            $(this).parent().find(".year").val(dates[0]);
            $(this).parent().find(".month").val(dates[1]);
            $(this).parent().find(".date").val(dates[2]);
        }
    });
};

$(function() {
    updateTotalPoints();
    $('.input-quantity').change(updateTotalPoints);

{/literal}
{if !empty($shohin_info_list)}
{foreach from=$shohin_info_list item=shohin_info key=shohin_no name=si}
{if $shohin_info[ShohinInformation::OTODOKE_KANO_DATE]}
    bindDatePicker('#dp_{$shohin_no|escape}', '+{if $shohin_info[ShohinInformation::HYOJUN_NOKI]}{$shohin_info[ShohinInformation::HYOJUN_NOKI]}{else}14{/if}d', {if $haiso_exists}new Date({$haiso_limit_y|escape}, {$haiso_limit_m|escape} - 1, {$haiso_limit_d|escape}){else}'+30d'{/if});
{/if}
{/foreach}
{/if}
{literal}

    var step = 1;
    var min = 0;
    var max = 100;
    $('.spinner_up').click(function() {
        var $div = $(this).parents('.pulldown01');
        var sn = $('.input-quantity', $div).val();
        if (!sn.length) {
            sn = 0;
        }
        var number = parseInt(sn) + step;
        if (number > max) {
            number = max;
        }
        $('.input-quantity', $div).val(number);
        updateTotalPoints();
    });
    $('.spinner_down').click(function() {
        var $div = $(this).parents('.pulldown01');
        var sn = $('.input-quantity', $div).val();
        if (!sn.length) {
            sn = 0;
        }
        var number = parseInt(sn) - step;
        if (number < min) {
            number = min;
        }
        $('.input-quantity', $div).val(number);
        updateTotalPoints();
    });

    $('input').keydown(function(e) {
        if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
            return false;
        }
        return true;
    });

    $(document).ready(function() {
        // 初期状態の確認
        checkUchinoshiBox();
    
        // ラジオボタンの変更を監視
        $('.noshi-select').on('change', function() {
            checkUchinoshiBox();
        });
    });
    
    // `.uchinoshiBox` の表示状態を確認・切り替える関数
    function checkUchinoshiBox() {
        if ($('.noshi-select:checked').val() == '01') {
            $('.uchinoshiBox').slideDown();
        } else {
            $('.uchinoshiBox').slideUp();
        }
    }

    $('.inCarts').on('click', '.haiso_sel', function() {
        $block = $(this).parents('.preferredDate');
        if ($(this).val() === '1') {
            $('.haiso-select', $block).slideDown();
            $('.hd-input', $block).prop('disabled', false);
        } else {
            $('.haiso-select', $block).slideUp();
            $('.hd-input', $block).prop('disabled', true);
        }
    });
});

document.addEventListener("DOMContentLoaded", function() {
        const checkbox = document.querySelector(".custom-checkbox");
        const submitButtons = document.querySelectorAll(".btn--info--comfirm");
        
        checkbox.addEventListener("change", function() {
            submitButtons.forEach(function(submitButton) {
                submitButton.disabled = !checkbox.checked;
            });
        });
        
        // Disable all buttons initially
        submitButtons.forEach(function(submitButton) {
            submitButton.disabled = true;
        });
});
{/literal}
</script>
{/if}
