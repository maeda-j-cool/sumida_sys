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
<div class="Wrapper login-bg">
{include file=$headerTemplate}
<main>
    <section class="login-mypege">
        <h2 class="l-section__title">マイページ</h2>
        <h3 class="l-section__title__welcome">ようこそ / {$user_name|escape}様</h3>
        <div class="mypage-box">
            <ul class="mypageNav">
                <li class="mypageNav-card">
                    <a href="{wt_action_url mod='OkiniiriIchiran' act='OkiniiriIchiran'}">
                        <figure>
                            <img src="/assets/img/mypage01.svg" alt="お気に入り商品一覧">
                        </figure>
                        <p class="mypageNav-card-txt">お気に入り商品一覧</p>
                    </a>
                </li>
                <li class="mypageNav-card">
                    <a href="{wt_action_url mod='Member' act='UpdateInput'}">
                        <figure>
                            <img src="/assets/img/mypage02.svg" alt="利用者登録情報/パスワードの変更">
                        </figure>
                        <p class="mypageNav-card-txt">利用者登録情報/<br>パスワードの変更</p>
                    </a>
                </li>
                <li class="mypageNav-card">
                    <a href="{wt_action_url mod='CardList' act='CardList'}">
                        <img src="/assets/img/mypage03.svg" alt="ID番号の追加・登録ID番号一覧">
                        <p class="mypageNav-card-txt">ID番号の追加・<br>登録ID番号一覧</p>
                    </a>
                </li>
                <li class="mypageNav-card">
                    <a href="{wt_action_url mod='ShukaJyokyo' act='ShukaJyokyo'}">
                        <img src="/assets/img/mypage04.svg" alt="交換履歴一覧">
                        <p class="mypageNav-card-txt">交換履歴一覧</p>
                    </a>
                </li>
            </ul>
        </div>
    </section>
{*
    <!-- <article class="login-related pc-none">
        <section class="login-related__box">
            <div class="item">
                <figure class="item__image"><img src="/assets/img/sp/product-list.png" alt=""></figure>
                <p class="item__text">商品一覧</p>
            </div>
            <div class="item">
                <figure class="item__image"><img src="/assets/img/sp/support-portal.png" alt=""></figure>
                <p class="item__text">子育て支援ポータル</p>
            </div>
            <div class="item">
                <figure class="item__image"><img src="/assets/img/sp/FAQ.png" alt=""></figure>
                <p class="item__text">よくあるご質問</p>
            </div>
            <div class="item">
                <figure class="item__image"><img src="/assets/img/sp/inquiry.png" alt=""></figure>
                <p class="item__text">お問い合わせ</p>
            </div>
        </section>
    </article> -->
*}
</main>
</div>
{include file=$footerTemplate}

