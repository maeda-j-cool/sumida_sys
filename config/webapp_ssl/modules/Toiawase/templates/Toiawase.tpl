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
<form method="post" id="contact_form" action="{wt_action_url mod="Toiawase" act="Toiawase"}" autocomplete="off">
<main>
    <div class="c-inner">
        <section class="l-section l-section--delete">
            <div class="l-section__wrap">
                <div class="c-flow">
                    <ul class="c-flow__container">
                        <li class="here">
                            <div class="circle">
                                <p>入力</p>
                            </div>
                        </li>
                        <li>
                            <div class="circle">
                                <p>確認</p>
                            </div>
                        </li>
                        <li>
                            <div class="circle">
                                <p>完了</p>
                            </div>
                        </li>
                    </ul>
                    <figure class="sp">
                        <img src="/assets/img/cart/or_1-sp.png" alt="入力">
                    </figure>
                </div>
                <div class="c-top__titlebox">
                    <h2 class="l-section__title">お問い合わせ</h2>
                    <p class="hedBottomTxt">
                        お問い合わせの前に「<a href="/guide/faq/" target="_blank"><u>よくある質問</u></a>」をご覧ください。
                        よくいただくご質問にお答えしています。<br class="pc">
                        よくある質問をご覧いただき解決されない場合は<br class="sp">お問い合わせになりたい項目を選択し、<br class="pc">お問い合わせ内容の入力をお願いいたします。<br>
                        入力が終わりましたら、「確認する」ボタンを押してください。
                    </p>
                </div>

{include file=$smarty.const.WT_COMMON_TEMPLATE_DIR|cat:"default_error_message.tpl"}

                <article class="p-tableBox">
                    <div class="tableInfos">
                        <div class="tableInfo">
                            <h3 class="tableInfo__title">氏名</h3>
                            <table>
                                <tbody>
                                <tr>
                                    <th>お名前<span class="mandatory_">必須</span></th>
                                    <td>{strip}
                                        <span class="mr15">姓</span>
                                        <input type="text" name="{$const.I_SEI_KANJI}" value="{$wt__posts[$const.I_SEI_KANJI]|escape}" class="mr30">
                                        <span class="mr15">名</span>
                                        <input type="text" name="{$const.I_MEI_KANJI}" value="{$wt__posts[$const.I_MEI_KANJI]|escape}" class="mr30">
                                        <span>【全角】</span>
                                    {/strip}</td>
                                </tr>
                                <tr>
                                    <th>お名前（ふりがな）<span class="mandatory_">必須</span></th>
                                    <td>{strip}
                                        <span class="mr15">せい</span>
                                        <input type="text" name="{$const.I_SEI_KANA}" value="{$wt__posts[$const.I_SEI_KANA]|escape}" class="mr30">
                                        <span class="mr15">めい</span>
                                        <input type="text" name="{$const.I_MEI_KANA}" value="{$wt__posts[$const.I_MEI_KANA]|escape}" class="mr30">
                                        <span>【全角】</span>
                                    {/strip}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tableInfo tableInfo--mail">
                        <h3 class="tableInfo__title">メールアドレス</h3>
                        <table>
                            <tbody>
                            <tr>
                                <th>メールアドレス<span class="mandatory_">必須</span></th>
                                <td>
                                    <div class="mailcheck">
                                        <input type="email" name="{$const.I_EMAIL}" value="{$wt__posts[$const.I_EMAIL]|escape}" class="long" placeholder="" maxlength="129">
                                    </div>
                                    <p class="tableInfo__note">
                                        ※「@」の直前にドットのあるメールアドレスや、連続したドットを含むメールアドレスはご利用いただけません。例） kyoto.@nakano-birthday.net、kyoto...kyoto@nakano-birthday.net など
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>メールアドレス(確認用)<span class="mandatory_">必須</span></th>
                                <td>
                                    <input type="email" name="{$const.I_EMAIL_CF}" value="{$wt__posts[$const.I_EMAIL_CF]|escape}" class="long" placeholder="" maxlength="129">
                                </td>
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
<input type="tel" name="{$const.I_TEL1}" value="{$wt__posts[$const.I_TEL1]|escape}" class="short" placeholder="" maxlength="6">
　-　
<input type="tel" name="{$const.I_TEL2}" value="{$wt__posts[$const.I_TEL2]|escape}" class="short" placeholder="" maxlength="5">
　-　
<input type="tel" name="{$const.I_TEL3}" value="{$wt__posts[$const.I_TEL3]|escape}" class="short" placeholder="" maxlength="5">
                                {/strip}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tableInfo">
                        <h3 class="tableInfo__title">お問い合わせ内容</h3>
                        <table>
                            <tbody>
{if !empty($option_inquiry_items)}
                            <tr>
                                <th>お問い合わせ項目を選択<span class="mandatory_">必須</span></th>
                                <td>
{html_options
    name="{$const.I_INQUIRY_ITEM}"
    options=$option_inquiry_items
    selected=$wt__posts[$const.I_INQUIRY_ITEM]
}
                                </td>
                            </tr>
{/if}
                            <tr>
                                <th>お問い合わせ内容<br>（350文字まで）<span class="mandatory_">必須</span></th>
                                <td>
<textarea name="{$const.I_INQUIRY_TEXT}" rows="12" cols="80" placeholder="" maxlength="350">{strip}
{$wt__posts[$const.I_INQUIRY_TEXT]|escape}
{/strip}</textarea>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </article>
                <div class="infosComfirm">
                    <label class="check_guide">
                        <input type="checkbox" name="{$const.I_PI_CONSENT}" value="1" class="custom-checkbox" placeholder="" {if $wt__posts[$const.I_PI_CONSENT] == '1'} checked="checked"{/if}>
                        <span class="checkmark"></span>
                        個人情報の取り扱いに同意する
                    </label>
                    <p class="infosComfirm__note">
                        ※個人情報の取り扱いについては、<a class="under-bar" href="/privacy/" target="_blank">こちら</a>をご確認ください。
                    </p>
                </div>
                <div class="tableInfos__btns pc">
                    <button type="submit" name="BTN_NEXT" class="btn--info btn--info--comfirm">送信内容を確認する</button>
                </div>
                <div class="tableInfos__btns sp">
                    <div id="contact_confirm" class="">
                        <button type="submit" name="BTN_NEXT" class="btn--info btn--info--comfirm">送信内容を確認する</button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
</form>
</div>
{include file=$footerTemplate}
<script>
    {literal}
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
            submitButton.disabled = !checkbox.checked;
        });
    });
    {/literal}
    </script>