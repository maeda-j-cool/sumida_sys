{capture name=gtm_layer}
{$gtm_layer_tag}{* JSを出力するのでエスケープしたらダメ *}
{/capture}
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
<div class="Wrapper">
{include file=$headerTemplate}
<main>
    <section class="l-section l-section--delete">
        <div class="l-section__wrap">
            <div id="wrapper">
                <div id="contents">
                    <div class="flow">
                        <picture>
                            <source media="(max-width: 768px)" srcset="/assets/img/cart/or_3-sp.png">
                            <source media="(min-width: 769px)" srcset="/assets/img/cart/or_3-pc.png">
                            <img src="/assets/img/cart/or_3-pc.png" alt="完了">
                        </picture>
                    </div>
                    <div class="c-top__titlebox">
                        <h2 class="l-section__title">お申し込みが完了しました</h2>
                    </div>
                    <div class="completeImg">
                        <img src="/assets/img/cart/complete_2.svg" alt="きつね">
                    </div>
                    <div class="tableInfos__btns tableInfos__btns--complete pc">
                        <div class="cntFtrButtons">
                            <div class="cntFtrBtn_top btn_gold01 btn_hover01 btn--info btn--info--top" style="text-align:center;">
                                <a href="{$smarty.const.WT_URL_BASE_SSL}">TOPへ</a>
                            </div>
                            <div class="cntFtrBtn_top btn_gold01 btn_hover01 btn--info btn--info--comfirm" style="text-align:center;">
                                <a href="{wt_action_url mod="Default" act="Mypage"}">マイページへ</a>
                            </div>
                        </div>
                    </div>
                    <div class="tableInfos__btns tableInfos__btns--confirm sp">
                        <div class="cntFtrButtons">
                            <div class="cntFtrBtn_next btn_gold01 btn_hover01 btn--info btn--info--comfirm">
                                <a href="{wt_action_url mod="Default" act="Mypage"}">マイページへ</a>
                            </div>
                            <div class="cntFtrBtn_prev btn_gray01 btn_hover01 btn--info--fix">
                                <a href="{$smarty.const.WT_URL_BASE_SSL}">TOPへ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
</div>
{include file=$footerTemplate}
