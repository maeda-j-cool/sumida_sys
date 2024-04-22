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
<form method="post" action="{wt_action_url mod="PasswdUpdate" act="UpdateInput"}" autocomplete="off">
<main id="main-pass">
    <div class="c-inner">
        <section class="l-section l-section--reset">
            <div class="l-section__wrap">
                <div class="c-top__titlebox">
                    <h2 class="l-section__title">パスワードの変更</h2>
                </div>

{include file=$smarty.const.WT_COMMON_TEMPLATE_DIR|cat:"default_error_message.tpl"}

                <div class="tableInfos tableInfos-re">
                    <div class="tableInfo tableInfo--password">
                        <table>
                            <tr>
                                <th>新しいパスワード</th>
                                <td>
                                    <input type="password" name="{$const.I_PASSWORD1}" value="" class="tableInfo__bigInput">
                                    <p class="tableInfo__note">
                                        大文字、小文字、数字を組み合わせて、8桁以上32桁以下で設定してください
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>新しいパスワード（確認）</th>
                                <td>
                                    <input type="password" name="{$const.I_PASSWORD2}" value="" class="tableInfo__bigInput">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="tableInfos__btns">
                        <button type="submit" name="BTN_SUBMIT" class="btn--info btn--info--comfirm">送信する</button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
{include file=$smarty.const.WT_COMMON_TEMPLATE_DIR|cat:"post_token.tpl"}
</form>
</div>
<script>
let submited = false;
$('[name="BTN_SUBMIT"]').click(() => {
    if (!submited) {
        submited = true;
        return true;
    }
    return false;
});
</script>
{include file=$footerTemplate}
