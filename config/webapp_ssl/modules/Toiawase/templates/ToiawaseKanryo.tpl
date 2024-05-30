{capture name=header}
<script xmlns="http://www.w3.org/1999/html">
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
                    <div class="c-flow">
                        <ul class="c-flow__container">
                            <li class="done">
                                <div class="circle">
                                    <p>入力</p>
                                </div>
                            </li>
                            <li class="done">
                                <div class="circle">
                                    <p>確認</p>
                                </div>
                            </li>
                            <li class="here">
                                <div class="circle">
                                    <p>完了</p>
                                </div>
                            </li>
                        </ul>
                        <figure class="sp">
                            <img src="/assets/img/cart/or_3-sp.png" alt="完了">
                        </figure>
                    </div>
                    <div class="c-top__titlebox">
                        <h2 class="l-section__title">送信しました</h2>
                    </div>
{* 問い合わせ番号 => $toiawase_no *}
                    <div class="tableInfos__btns tableInfos__btns--complete pc">
                        <div class="cntFtrButtons">
                            <div class="cntFtrBtn_top btn_gold01 btn_hover01 btn--info btn--info--top" style="text-align:center;">
                                <a href="{wt_action_url mod="" act=""}">TOPへ</a>
                            </div>
{if $wt__is_login && !$is_virtual}
                            <div class="cntFtrBtn_top btn_gold01 btn_hover01 btn--info btn--info--comfirm" style="text-align:center;">
                                <a href="{wt_action_url mod="Default" act="Mypage"}">マイページへ</a>
                            </div>
{/if}
                        </div>
                    </div>
                    <div class="tableInfos__btns tableInfos__btns--confirm sp">
                        <div class="cntFtrButtons">
{if $wt__is_login && !$is_virtual}
                            <div class="cntFtrBtn_next btn_gold01 btn_hover01 btn--info btn--info--comfirm">
                                <a href="{wt_action_url mod="Default" act="Mypage"}">マイページへ</a>
                            </div>
{/if}
                            <div class="cntFtrBtn_prev btn_gray01 btn_hover01 btn--info--fix">
                                <a href="{wt_action_url mod="" act=""}">TOPへ</a>
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
