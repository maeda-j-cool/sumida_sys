<footer id="footer">
    <div class="footer_bg">
    <article class="inquiry">
        <div class="inquiry__inner">
            <h2 class="inquiry__sub-title">{$settings.call_center_name|escape}係</h2>
            <div class="inquiry__box">
                <section class="inquiry-item1 sp-none">
                    <figure class="tel-icon"><img src="/assets/img/tel.png" alt="電話"></figure>
                    <p class="inquiry-item1__text">お電話でのお問い合わせ</p>
                    <p class="tel-text"><a href="tel:{$settings.call_center_phone|replace:'-':''|escape}">{$settings.call_center_phone|escape}</a></p>
                    <p class="reception-time">{$settings.call_center_business_hours|escape}</p>
                    <p class="annotation">※回線の種類により稀につながらない場合がございますことをご了承ください。</p>
                </section>
                <section class="inquiry-item1 sp-none">
                    <div class="inquiry-item2">
                        <figure class="mail-icon"><img src="/assets/img/mail.png" alt="メール"></figure>
                        <p class="inquiry-item2__text">メールでのお問い合わせ</p>
                        <div class="mail">
                            <a href="{wt_action_url app="webapp" mod="Toiawase" act="Toiawase"}" class="send">メールをおくる</a>
                        </div>
                        <p class="annotation">
                            ※日本語に慣れていない方はこちらのお問い合わせフォームからお問い合わせください<br>
                        </p>
                    </div>
                </section>
            </div>
            <div class="pc-none btn-sp">
                <div class="tel-btn-sp">
                    <a href="tel:{$settings.call_center_phone|replace:'-':''|escape}" class="btn-sp__tel">{$settings.call_center_phone|escape}</a>
                </div>
                <div class="mail-btn-sp">
                    <a href="{wt_action_url app="webapp" mod="Toiawase" act="Toiawase"}" class="btn-sp__mail">お問い合わせ</a>
                </div>
            </div>
        </div>
        <div class="mini-site-map">
            <ul class="site-box1">
                <li class="box1-item">
                    <h3 class="box1-sub-item"><a href="/#top-connection">墨田区の子育て支援情報</a></h3>
                    <!-- <h3 class="box2-sub-item"><a href="/#top-connection">関連情報</a></h3> -->
                    <!--<h3 class="special-sub-item"><a href="/#top-Special-feature">特集</a></h3>-->
                </li>
                <li class="box3-item">
                    <p class="box3-sub-item"><a href="/abouts/">当サイトについて</a></p>
                    <p class="box3-sub-item"><a href="/privacy/">プライバシーポリシー</a>
</p>
                    <p class="box3-sub-item"><a href="/tokusyoho/">特定商取引法に基づく表示</a></p>
                    <p class="box3-sub-item"><a href="/terms/">ご利用規約</a></p>
                    {if $wt__is_login and !$is_virtual}
                        <p class="box3-sub-item"><a href="{wt_action_url app="webapp" mod="Withdraw" act="Withdrawal"}">ポイント利用辞退申請</a></p>
                    {/if}
                </li>
            </ul>
            <ul class="site-box2">
                <li class="box2-item">
                    <p class="box2-text"><a href="/guide/faq/">よくあるご質問</a></p>
                    <p class="box2-text"><a href="{wt_action_url app="webapp" mod="Toiawase" act="Toiawase"}">お問い合わせ</a></p>
                    <p class="box2-text"><a href="/guide/">ご利用ガイド</a></p>
                    <!-- {if $wt__is_login and !$is_virtual}
                    <p class="box2-text"><a href="/support/">健康相談<br class="sp">サポートダイヤル</a></p>
                    {/if} -->
                </li>
            </ul>
        </div>
        <p id="copywriter">©2024 <br class="pc-none">すみだ区バースデーサポート<br class="pc-none"></p>
    </article>
    </div>
</footer>
