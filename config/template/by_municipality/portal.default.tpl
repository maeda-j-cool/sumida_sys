
    <article class="child-rearing child-rearing-login" id="support-portal">
        <div class="child-rearing__wrap citizen">
            <p class="citizen">{$settings.kenshu_name|escape}民の</p>
            <h2 class="child-sub-title">子育て支援ポータル</h2>
            <p class="child-text">{$settings.kenshu_name|escape}はお子さまの健やかな成長を願って、さまざまなかたちで皆さまの子育てを応援しています。</p>
            <div class="tabs login-tabs">
                <label class="tab_item tab_item_active" for="programming">支援情報</label><input id="guidance" type="radio"
                    name="tab_item"><label class="tab_item" for="guidance">相談窓口情報</label><input id="dummy" type="radio"
                    name="tab_item">
                <div class="tab_content" id="programming_content">

                {include file=$smarty.const.WT_HTML_DIR|cat:"/portal/default_childbirth.html"}

                </div>

                <div class="tab_content" id="guidance_content">

                {include file=$smarty.const.WT_HTML_DIR|cat:"/portal/default_guidance.html"}
                </div>
            </div>
        </div>
    </article>
