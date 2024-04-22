{capture name=header}
<link rel="stylesheet" href="/assets/css/dialog.css">
<link rel="stylesheet" href="/assets/css/slick.css" />
<link rel="stylesheet" href="/assets/css/slick-theme.css" />
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
{capture name=js}
<script src="/assets/js2/dialog.js"></script>
<script src="/assets/js2/slick.min.js"></script>
<script>
const urlAddFavorite = '{wt_action_url mod="ShohinShosai" act="ShohinShosai"}kind/okiniiri/shohin/';
{literal}
$(function(){
    $('.add-favorite').click(function() {
        const $button = $(this);
        const shohinNo = $button.attr('name');
        const shohinName = $button.attr('rel');
        $.ajax({
            type: 'GET',
            url: urlAddFavorite + shohinNo,
            cache: false,
            async: false
        }).done((data, textStatus, jqXHR) => {
            if (data === '') {
                showGrowl('<b>' + shohinName + '</b><br>をお気に入りに追加しました。', () => {});
                $button.unbind();
                $button.removeClass('add-favorite');
                $button.attr('href', '{/literal}{wt_action_url mod="OkiniiriIchiran" act="OkiniiriIchiran"}{literal}');
                $('span', $button).html('お気に入り登録済');
                $('img', $button).attr('src', '/assets/img/icon_hart_on.png');
            } else if (data.match('error:') !== null) {
                showAlert(data.replace('error:', ''), 'OK', () => {});
            } else {
                showAlert('システムエラーが発生した為、処理を中断しました。', 'OK', () => {});
            }
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.log([jqXHR.status, textStatus, errorThrown].join(' '));
            showAlert('通信エラーが発生した為、処理を中断しました。', 'OK', () => {});
        });
        return false;
    });
    $('.cart-in').click(function() {
        let confirmText = '';
        if ($(this).hasClass('sake')) {
            if ($(this).hasClass('sake2')) {
                confirmText = '当サイトでは転売を目的とした交換は固くお断りしております。<br>転売を目的とした交換ではない場合は、<br>「はい」を押してお進みください。';
            } else if ($(this).hasClass('sake3')) {
                confirmText = '本商品は季節限定の商品です。<br>お届け期間終了間際のご注文は、商品のお届けが次の期間になる場合がございます。<br>ご了承いただけましたら「はい」を押してお進みください。';
            } else if ($(this).hasClass('sake4')) {
                confirmText = '本商品はいかなる理由があってもお申し込み後の返品・交換はできません。<br>ご了承いただけましたら「はい」を押してお進みください。';
            } else if ($(this).hasClass('sake5')) {
                if ($(this).hasClass('ca')) {
                    confirmText = [
                        '・金券のお申し込みは、1つのIDにつき{/literal}{$settings.ca_point_limit|number_format}{literal}ポイントまでとなります。',
                        '・この商品は、ポイント発行会社へ出荷に関連する個人情報の一部を連携いたします。また、アルコール類、たばこ、遊技場入場券などの一部の商品には利用できません。',
                        '',
                        'ご了承いただけましたら「はい」を押してお進みください。'
                    ].join('<br>');
                } else {
                    confirmText = 'この商品は、商品の出荷に伴う際にポイント発行会社へ出荷に関連する個人情報の一部を連携いたします。<br>また、アルコール類、たばこ、遊技場入場券などの一部の商品には利用できません。<br>ご了承いただけましたら「はい」を押してお進みください。';
                }
            } else {
                confirmText = '当サイトでは未成年の方への酒類の販売はいたしておりません。<br>20歳以上でしたら「はい」を押してお進みください。';
            }
        } else if ($(this).hasClass('ca')) {
            confirmText = '金券のお申し込みは、１つのIDにつき{/literal}{$settings.ca_point_limit|number_format}{literal}ポイントまでとなります。<br>ご了承のうえ、お進みください。';
        }
        const submitForm = () => {
            $('form#item-area-form').submit();
        };
        if (confirmText.length) {
            showConfirm('', confirmText, 'はい', 'いいえ', submitForm, () => {});
        } else {
            submitForm();
        }
        return false;
    })

    var slider = "#slider"; // スライダー
    var thumbnailItem = "#thumbnail-list .thumbnail-item"; // サムネイル画像アイテム

    // サムネイル画像アイテムに data-index でindex番号を付与
    $(thumbnailItem).each(function(){
        var index = $(thumbnailItem).index(this);
        $(this).attr("data-index",index);
    });

    // スライダー初期化後、カレントのサムネイル画像にクラス「thumbnail-current」を付ける
    // 「slickスライダー作成」の前にこの記述は書いてください。
    $(slider).on('init',function(slick){
        var index = $(".slide-item.slick-slide.slick-current").attr("data-slick-index");
        $(thumbnailItem+'[data-index="'+index+'"]').addClass("thumbnail-current");
    });

    //slickスライダー初期化
    $(slider).slick({
        autoplay: true,
        arrows: false,
        fade: true,
        infinite: false //これはつけましょう。
    });
    //サムネイル画像アイテムをクリックしたときにスライダー切り替え
    $(thumbnailItem).on('click',function(){
        var index = $(this).attr("data-index");
        $(slider).slick("slickGoTo",index,false);
    });

    //サムネイル画像のカレントを切り替え
    $(slider).on('beforeChange',function(event,slick, currentSlide,nextSlide){
        $(thumbnailItem).each(function(){
            $(this).removeClass("thumbnail-current");
        });
        $(thumbnailItem+'[data-index="'+nextSlide+'"]').addClass("thumbnail-current");
    });
});
{/literal}
</script>
<script>
{literal}
$(function () {
    $('#shareCopy').click(function () {
        // フラッシュメッセージ表示
        $('.success-msg').fadeIn("slow", function () {
            $(this).delay(2000).fadeOut("slow");
        });
    });
});
{/literal}
</script>
{/capture}
<div class="Wrapper">
{include file=$headerTemplate}
<main>
    <div class="c-inner">
        <div id="sgBread">
            <ul>
                <li class="home"><a href="{$smarty.const.WT_URL_BASE_SSL}">ホーム</a></li>
{if !empty($panTree)}
                {$panTree}
{else}
                <li class="last">{$shohinname|escape}</li>
{/if}
            </ul>
        </div>
        <section class="l-section l-section--qn">
            <div class="l-section__itemDetail">
                <div class="p-item__inner">
                    <div class="p-item__main">
                        <div class="p-item__img">
                            <div id="wrap">
                                <ul id="slider">
{tss_html_image_shohin_slider
    alt="{$shohinname|escape}"
    shohin_code={$shohncd}
    prefix='<li class="slide-item">'
    suffix='</li>'
}
                                </ul>
                                <div class="thumbnail-list_wrap pc">
                                    <ul id="thumbnail-list">
{tss_html_image_shohin_slider
    alt="{$shohinname|escape}"
    shohin_code={$shohncd}
    prefix='<li class="thumbnail-item">'
    suffix='</li>'
}
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="p-item__content">
                            <form method="post" action="{wt_action_url mod="ShohinShosai" act="ShohinShosai"}" id="item-area-form">
                                <input type="hidden" name="shohin" value="{$shohin_no|escape}">
{* @TODO 確認: $shohinexp1? $shohinexp3? *}
{* $shohinexp1, $shohinexp3 にはHTMLタグも含まれるためエスケープしない *}
{if !empty($brandexp)}
                                <h2 class="p-item__description_title">
                                    {$brandexp|nl2br}
                                </h2>
{/if}
{if !empty($shohinexp1)}
                                <p class="p-item__description">
                                    {$shohinexp1|nl2br}
                                </p>
{/if}
{* $catchcopy|escape *}
{if $brandname}
                                <p class="p-item__sub" data-stt-ignore="">{$brandname|escape}</p>
{/if}
                                <h1 class="p-item__title" data-stt-ignore="">{$shohinname|escape}</h1>
                                <div class="p-item__pointwrap">
                                    <p class="p-item__point">{$vpoint|number_format}ポイント</p>
                                    <p class="p-item__favorite">
{if !$is_virtual}
{if $okiniiri_flg == '0'}
                                        <a rel="{$shohinname|escape}" name="{$shohin_no|escape}" href="javascript:void(0);" class="okiniiri add-favorite">
                                            <span>お気に入り登録</span><img src="/assets/img/icon_hart.png" alt="お気に入り登録">
                                        </a>
{else}
                                        <a rel="{$shohinname|escape}" name="{$shohin_no}" href="{wt_action_url mod="OkiniiriIchiran" act="OkiniiriIchiran"}" class="okiniiri">
                                            <span>お気に入り登録済</span><img src="/assets/img/icon_hart_on.png" alt="お気に入り登録済">
                                        </a>
{/if}
{/if}
                                    </p>
                                </div>
                                <p class="p-item__catalog"><span>申込番号：</span>{$shohncd|escape}</p>
                                <dl class="p-item__dtl-dl">
{if $nosikbn != '0'}
                                    <dt><img src="/assets/image/item/icn04.png" alt="のし対応"></dt>
                                    <dd>この商品は、のしをご指定いただけます。</dd>
{/if}
{if $komugi == 1 || $tamago == 1 || $soba == 1 || $nyu == 1 || $rakkasei == 1 || $ebi == 1 || $kani == 1}
                                    <dt><img src="/assets/image/item/icn05.png" alt="アレルギー"></dt>
                                    <dd>
                                        <ul>
{if $komugi == 1}
                                            <li><img src="/assets/image/item/icon_allergy_01.png" widht="28" height="28"></li>
{/if}
{if $tamago == 1}
                                            <li><img src="/assets/image/item/icon_allergy_02.png" widht="28" height="28"></li>
{/if}
{if $soba == 1}
                                            <li><img src="/assets/image/item/icon_allergy_03.png" widht="28" height="28"></li>
{/if}
{if $nyu == 1}
                                            <li><img src="/assets/image/item/icon_allergy_04.png" widht="28" height="28"></li>
{/if}
{if $rakkasei == 1}
                                            <li><img src="/assets/image/item/icon_allergy_05.png" widht="28" height="28"></li>
{/if}
{if $ebi == 1}
                                            <li><img src="/assets/image/item/icon_allergy_06.png" widht="28" height="28"></li>
{/if}
{if $kani == 1}
                                            <li><img src="/assets/image/item/icon_allergy_07.png" widht="28" height="28"></li>
{/if}
                                        </ul>
                                    </dd>
{/if}
                                </dl>
{if !$is_virtual && $remain_point > 0}
{if $buttonflg == "0"}
<p class="p-item__cartbotton cart-in is-soldout">{$buttonmsg|default:"販売期間前"}</p>
{elseif $buttonflg == "1" || $buttonflg == "2"}{* "2":季節商品 *}
<p class="p-item__cartbotton cart-in is-soldout">{$buttonmsg|default:"販売期間が終了しました"}</p>
{elseif $buttonflg == "3"}
<p class="p-item__cartbotton cart-in is-soldout">{$buttonmsg|default:"只今在庫を切らしております"}</p>
{elseif $already_in_cart}
<p class="p-item__cartbotton cart-in is-soldout">既にカートに入っています</p>
{else}
<button type="submit" class="p-item__cartbotton cart-in{if $sake_flg != 0} sake sake{$sake_flg}{/if}{if in_array($hk2, ['CA', 'DGC'])} ca{/if}">カートにいれる</button>
{/if}
{/if}
                            </form>
                        </div>
                    </div>
                    <table cellpadding="0" cellspacing="0" class="p-item__table">
                        <tbody>
{foreach from=$shohin_naiyo_setsumei item=shohinNaiyo}
                            <tr>
                                <td class="cl01">{$shohinNaiyo.M03NAME}</td>
                                <td class="cl02">{$shohinNaiyo.M02HKOMOKU|nl2br}</td>
                            </tr>
{/foreach}
                        </tbody>
                    </table>
{if count($other_messages)}
    {foreach from=$other_messages item=message}
                    <p class="p-item__dtl-cap">{$message|trim}</p>
    {/foreach}
{/if}
{if $hk2 !== 'DGC'}{* >>>!DGC *}
                    <dl class="p-item__desc clerfix">
{if (!empty($hkanouDayArray.haisosdate) && !empty($hkanouDayArray.haisoedate)) || ($haiso_fuka_flg == "0" && !empty($hFukaDispMsg))}
                        <dt>お届け可能日：</dt>
                        <dd class="l2">{strip}
    {if (!empty($hkanouDayArray.haisosdate) && !empty($hkanouDayArray.haisoedate))}
                            この商品は、{$hkanouDayArray.haisosdate|date_format:"%Y年%-m月%-d日"}～{$hkanouDayArray.haisoedate|date_format:"%Y年%-m月%-d日"}までお届け可能です。それ以外の期間は、ご注文をお受けする事ができません。
    {/if}
    {if $haiso_fuka_flg == "0" && !empty($hFukaDispMsg)}
                            {$hFukaDispMsg}
    {/if}
                        {/strip}</dd>
{/if}
{if $hKeitaiArray.kikangenteiFlg == '1'}{* A *}
{if !empty($hKeitaiArray.F72HAISOKBN) && !empty({$hKeitaiArray.F72SDATE}) && !empty({$hKeitaiArray.F72EDATE})}
                        <dt>配送便について：</dt>
{if $hKeitaiArray.F72HAISOKBN|trim == "1"}{* B *}
                        <dd>{strip}
                            この商品は、{$hKeitaiArray.F72SDATE|date_format:"%-m月%-d日"}～{$hKeitaiArray.F72EDATE|date_format:"%-m月%-d日"}のみ常温便にてお届けします。<br>
    {if !empty($delivertype)}
        {if $delivertype|trim == "2"}
                            その他期間は、冷蔵便でお届けします。
        {elseif $delivertype|trim == "3"}
                            その他期間は、冷凍便でお届けします。
        {/if}
    {/if}
                        {/strip}</dd>
{elseif $hKeitaiArray.F72HAISOKBN|trim == "2"}{* B *}
                        <dd>{strip}
                            この商品は、{$hKeitaiArray.F72SDATE|date_format:"%-m月%-d日"}～{$hKeitaiArray.F72EDATE|date_format:"%-m月%-d日"}のみ冷蔵便にてお届けします。<br>
    {if !empty($delivertype)}
        {if $delivertype|trim == "1"}
                            その他期間は、常温便でお届けします。
        {elseif $delivertype|trim == "3"}
                            その他期間は、冷凍便でお届けします。
        {/if}
    {/if}
                        {/strip}</dd>
{elseif $hKeitaiArray.F72HAISOKBN|trim == "3"}{* B *}
                        <dd>{strip}
                            この商品は、{$hKeitaiArray.F72SDATE|date_format:"%-m月%-d日"}～{$hKeitaiArray.F72EDATE|date_format:"%-m月%-d日"}のみ冷凍便にてお届けします。<br>
    {if !empty($delivertype)}
        {if $delivertype|trim == "1"}
                            その他期間は、常温便でお届けします。
        {elseif $delivertype|trim == "2"}
                            その他期間は、冷蔵便でお届けします。
        {/if}
    {/if}
                        {/strip}</dd>
{/if}{* B *}
{/if}
{else}{* A *}
{if !empty($delivertype)}{* C *}
                        <dt>配送便について：</dt>
{if $delivertype|trim == "1"}
                        <dd>この商品は、常温便でお届けします。</dd>
{elseif $delivertype|trim == "2"}
                        <dd>この商品は、冷蔵便でお届けします。</dd>
{elseif $delivertype|trim == "3"}
                        <dd>この商品は、冷凍便でお届けします。</dd>
{/if}
{/if}{* C *}
{/if}{* A *}
                    </dl>
{/if}{* <<<!DGC *}
                </div>
            </div>
            <div class="l-section__wrap">
{* >>> おすすめ商品 >>> *}
{if isset($recommendShohinArray) && is_array($recommendShohinArray) && !empty($recommendShohinArray)}
                <div class="c-item__group">
                    <h2 class="l-section__title mb0">あなたへのおすすめ</h2>
                    <div class="productLists">
{foreach from=$recommendShohinArray item=shohin}
                        <div class="productList">
                            <div class="productList__img">
                                <a href="{$shohin.linkToShosai}">
                                    {tms_html_image alt="{$shohin.displayNm|escape}" shohin_code={$shohin.code} image_type="4"}
                                </a>
                            </div>
                            <div class="productList__content" data-stt-ignore="">
                                <div class="productList__content_head">
                                    <p class="productList__point">{$shohin.priceAndTax|number_format}POINTS</p>
{*
                                    <p class="productList__choice"><img src="/assets/img/gihu_choice.svg" alt="岐阜市チョイス"></p>
*}
                                </div>
                                <a href="{$shohin.linkToShosai}">
                                    <p class="productList__title">{$shohin.displayNm|escape}</p>
{if $shohin.brandNm}
                                    <p class="productList__cat">{$shohin.brandNm|escape}</p>
{/if}
                                </a>
                            </div>
                        </div>
{/foreach}
                    </div>
                </div>
{/if}
{* <<< おすすめ商品 <<< *}

{* >>> 最近チェックした商品 >>> *}
{if isset($checkRirekiShohinArray) && is_array($checkRirekiShohinArray) && !empty($checkRirekiShohinArray)}
                <div class="c-item__group">
                    <h2 class="l-section__title mb0">最近チェックした商品</h2>
                    <div class="productLists">
{foreach from=$checkRirekiShohinArray item=shohin}
                        <div class="productList">
                            <div class="productList__img">
                                <a href="{$shohin.linkToShosai}">
                                    {tms_html_image alt="{$shohin.displayNm|escape}" shohin_code={$shohin.code} image_type="4"}
                                </a>
                            </div>
                            <div class="productList__content" data-stt-ignore="">
                                <div class="productList__content_head">
                                    <p class="productList__point">{$shohin.priceAndTax|number_format}POINTS</p>
{*
                                    <p class="productList__choice"><img src="/assets/img/gihu_choice.svg" alt="岐阜市チョイス"></p>
*}
                                </div>
                                <a href="{$shohin.linkToShosai}">
                                    <p class="productList__title">{$shohin.displayNm|escape}</p>
{if $shohin.brandNm}
                                    <p class="productList__cat">{$shohin.brandNm|escape}</p>
{/if}
                                </a>
                            </div>
                        </div>
{/foreach}
                    </div>
                </div>
{/if}
{* <<< 最近チェックした商品 <<< *}
            </div>
        </section>
    </div>
</main>
</div>
{include file=$footerTemplate}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
$(function() {
    $('#moveSrc_').appendTo('.p-item__inner')
});
</script>