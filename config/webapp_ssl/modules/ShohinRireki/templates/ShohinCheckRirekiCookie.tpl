{capture name=header}
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
<main>
    <div id="sgBread">
        <ul>
            <li class="home"><a href="{$smarty.const.WT_URL_BASE_SSL}">ホーム</a></li>
            <li class="last"><u>最近見た商品</u></li>
        </ul>
    </div>
    <section class="l-section l-section--productArchive">
        <div class="l-section__wrap">
            <h2 class="login-product__title">最近見た商品</h2>

{include file=$smarty.const.WT_COMMON_TEMPLATE_DIR|cat:"default_error_message.tpl"}
{if empty($Errors)}

            <div class="productLists">
{foreach from=$arrShohin item=shohin name="as"}
                <div class="productList">
                    <div class="productList__img">
                        <a href="{$shohin.linkToShosai}">
{tms_html_image alt="{$shohin.M02SNAME|escape}" shohin_code={$shohin.M02SHOHNCD} image_type="4"}
                        </a>
                    </div>
                    <div class="productList__content" data-stt-ignore="">
                        <a href="{$shohin.linkToShosai}">
                            <p class="productList__title"><u>{$shohin.M02SNAME}</u></p>
                            <p class="productList__point">{$shohin.M02VPOINT|number_format}point</p>
{if $shohin.M02BRAND}
                            <p class="productList__cat">{$shohin.M02BRAND|escape}</p>
{/if}
                        </a>
                    </div>
                </div>
{/foreach}
            </div>
{/if}
        </div>
    </section>
</main>
</div>
{include file=$footerTemplate}
