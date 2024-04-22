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
<div class="Wrapper before-login-Wrapper">
{include file=$headerTemplate}
<main id="main-pass">
<section class="l-section l-section--delete">
    <div class="l-section__wrap">
        <!--====== WRAPPER IN ======-->
        <div id="wrapper">
            <!--====== CONTENTS IN ======-->
            <div id="contents">
                <div class="c-top__titlebox">
                    <h2 class="l-section__title">パスワードを変更しました</h2>
                </div>
                <div class="completeImg">
                    <img src="/assets/img/main-character2.png" alt="mv">
                </div>
                <div class="tableInfos__btns tableInfos__btns--complete pc">
                    <div class="cntFtrButtons">
                        <div class="cntFtrBtn_top btn_gold01 btn_hover01 btn--info btn--info--top" style="text-align:center;"><a href="{$smarty.const.WT_URL_BASE}">TOPへ</a></div>
                        <div class="cntFtrBtn_top btn_gold01 btn_hover01 btn--info btn--info--comfirm" style="text-align:center;"><a href="{$smarty.const.WT_URL_BASE}?act=l">ログインする</a></div>
                    </div>
                </div>
                <div class="tableInfos__btns tableInfos__btns--confirm sp">
                    <div class="cntFtrButtons">
                        <div class="cntFtrBtn_next btn_gold01 btn_hover01 btn--info btn--info--comfirm"><a href="{$smarty.const.WT_URL_BASE}?act=l">ログインする</a></div>
                        <div class="cntFtrBtn_prev btn_gray01 btn_hover01 btn--info--fix"><a href="{$smarty.const.WT_URL_BASE}">TOPへ</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</main>
</div>
{include file=$footerTemplate}
