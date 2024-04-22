{capture name=header}
<link rel="stylesheet" href="/assets/css/dialog.css">
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
<script type="text/javascript">
{literal}
$(function() {
    $('.delete-item').click(function() {
        const shohinNo = $(this).attr('data-sno');
        if (shohinNo !== undefined && shohinNo.length) {
            let text = 'この商品をお気に入りリストから<br>削除してもよろしいでしょうか？';
            if (shohinNo === 'all') {
                text = '全ての商品をお気に入りリストから<br>削除してもよろしいでしょうか？';
            }
            const submitForm = () => {
                $('<form>', {
                    action: '{/literal}{wt_action_url mod="OkiniiriIchiran" act="OkiniiriIchiran"}{literal}',
                    method: 'post'
                }).append($('<input>', {
                    type: 'hidden',
                    name: 'shohinNo',
                    value: shohinNo
                })).appendTo(document.body).submit();
            };
            showConfirm('', text, 'はい', 'いいえ', submitForm, () => {});
        }
    });
});
{/literal}
</script>
{/capture}
<div class="Wrapper">
{include file=$headerTemplate}
<main>
    <div id="sgBread">
        <ul>
            <li class="home"><a href="{$smarty.const.WT_URL_BASE_SSL}">ホーム</a></li>
            <li class="last"><u>お気に入り一覧</u></li>
        </ul>
    </div>
    <section class="l-section l-section--productArchive">
        <div class="l-section__wrap">
            <h2 class="login-product__title">お気に入り一覧</h2>

{include file=$smarty.const.WT_COMMON_TEMPLATE_DIR|cat:"default_error_message.tpl"}

            <div class="archiveLead">
                <p class="archiveLead__num">{$search_results|count}件あります</p>
                <a href="javascript:void(0);" class="delete delete-item" style="color:#fff" data-sno="all">全件削除</a>
            </div>
            <div class="productLists">
{foreach from=$search_results item=row name=rowNo}
                <div class="productList">
                    <div class="productList__img">
                        <a href="{wt_action_url mod='ShohinShosai' act='ShohinShosai'}{$smarty.const.GET_PARAM_SHOHIN_NO}/{$row.M02SHOHNNO|escape}">
{tms_html_image alt="{$row.M02SNAME|escape}" shohin_code={$row.M02SHOHNCD} image_type="4"}
                        </a>
                    </div>
                    <div class="productList__content" data-stt-ignore="">
                        <a href="{wt_action_url mod='ShohinShosai' act='ShohinShosai'}{$smarty.const.GET_PARAM_SHOHIN_NO}/{$row.M02SHOHNNO|escape}">
                            <p class="productList__title"><u>{$row.M02SNAME|escape}</u></p>
                            <p class="productList__point">{$row.M02VPOINT|number_format|escape}point</p>
{if $row.M02BRAND}
                            <p class="productList__cat">{$row.M02BRAND|escape}</p>
{/if}
                        </a>
                        <a href="javascript:void(0);" class="delete-item productList__favorite" data-sno="{$row.M02SHOHNNO|escape}"><img src="/images/icon_delete.png" alt=""></a>
                    </div>
                </div>
{/foreach}
            </div>
        </div>
    </section>
</main>
</div>
{include file=$footerTemplate}
