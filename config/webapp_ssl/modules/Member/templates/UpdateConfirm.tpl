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
<form method="post" action="{wt_action_url mod='Member' act='UpdateConfirm'}">
<main class="main-other">
    <div class="c-inner">
        <section class="l-section l-section--re">
            <div class="l-section__wrap">
                <h2 class="l-section__title">利用者登録情報/パスワードの変更</h2>

{include file=$smarty.const.WT_COMMON_TEMPLATE_DIR|cat:"default_error_message.tpl"}

                <div class="tableInfos tableInfos-re-fix">
                    <div class="tableInfo">
                        <h3 class="tableInfo__title">保護者の氏名・続柄</h3>
                        <p class="st_person">（1人目）</p>
                        <table>
                            <tr>
                                <th>お名前</th>
                                <td>{$confirm_params[$const.I_SEI_KANJI1]|escape} {$confirm_params[$const.I_MEI_KANJI1]|escape}</td>
                            </tr>
                            <tr>
                                <th>お名前（ふりがな）</th>
                                <td>{$confirm_params[$const.I_SEI_KANA1]|escape} {$confirm_params[$const.I_MEI_KANA1]|escape}</td>
                            </tr>
                            <tr>
                                <th>続柄</th>
                                <td>
                                    {$rel_list[$confirm_params[$const.I_RELATION1]]|escape}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="tableInfo">
                        <p class="st_person">（2人目）</p>
                        <table>
                            <tr>
                                <th>お名前</th>
                                <td>{$confirm_params[$const.I_SEI_KANJI2]|escape} {$confirm_params[$const.I_MEI_KANJI2]|escape}</td>
                            </tr>
                            <tr>
                                <th>お名前（ふりがな）</th>
                                <td>{$confirm_params[$const.I_SEI_KANA2]|escape} {$confirm_params[$const.I_MEI_KANA2]|escape}</td>
                            </tr>
                            <tr>
                                <th>続柄</th>
                                <td>
                                    {$rel_list[$confirm_params[$const.I_RELATION2]]|escape}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="tableInfo">
                        <h3 class="tableInfo__title">住所</h3>
                        <table>
                            <tr>
                                <th>郵便番号</th>
                                <td>〒{$confirm_params[$const.I_ZIPCODE_1]|escape}-{$confirm_params[$const.I_ZIPCODE_2]|escape}</td>
                            </tr>
                            <tr>
                                <th>都道府県</th>
                                <td>
                                    {$confirm_params[$const.I_ADDRESS_1]|escape}
                                </td>
                            </tr>
                            <tr>
                                <th>市区町村 番地</th>
                                <td>
                                    {$confirm_params[$const.I_ADDRESS_2]|escape}
                                </td>
                            </tr>
                            <tr>
                                <th>建物名 部屋番号</th>
                                <td>
                                    {$confirm_params[$const.I_ADDRESS_3]|escape|default:'&nbsp;'}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="tableInfo tableInfo--tell">
                        <h3 class="tableInfo__title">電話番号</h3>
                        <table>
                            <tr>
                                <th>電話番号1</th>
                                <td>{$confirm_params[$const.I_TEL1_1]|escape}-{$confirm_params[$const.I_TEL1_2]|escape}-{$confirm_params[$const.I_TEL1_3]|escape}</td>
                            </tr>
                            <tr>
                                <th>電話番号2</th>
                                <td>{strip}
{if strlen($confirm_params[$const.I_TEL2_1])}
{$confirm_params[$const.I_TEL2_1]|escape}-{$confirm_params[$const.I_TEL2_2]|escape}-{$confirm_params[$const.I_TEL2_3]|escape}
{else}
&nbsp;
{/if}
                                {/strip}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="tableInfo tableInfo--mail">
                        <h3 class="tableInfo__title">メールアドレス</h3>
                        <table>
                            <tr>
                                <th>メールアドレス</th>
                                <td>
                                    {$confirm_params[$const.S_EMAIL]|escape}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="tableInfo tableInfo--password">
                        <h3 class="tableInfo__title">ユーザーパスワード</h3>
                        <table>
                            <tr>
                                <th>新しいパスワード</th>
                                <td>{if $confirm_params[$const.I_PASSWORD1]}●●●●●●●●●{else}変更なし{/if}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="tableInfos__btns tableInfos__btns--confirm pc">
                        <div class="cntFtrButtons">
                            <button type="submit" name="BTN_BACK" class="cntFtrBtn_prev btn_gray01 btn_hover01 btn--info--fix">戻る</button>
                            <button type="submit" name="BTN_SUBMIT" class="cntFtrBtn_next btn_gold01 btn_hover01 btn--info btn--info--comfirm">更新する</button >
                        </div>
                    </div>
                    <div class="tableInfos__btns tableInfos__btns--confirm sp">
                        <div class="cntFtrButtons">
                            <button type="submit" name="BTN_SUBMIT" class="cntFtrBtn_next btn_gold01 btn_hover01 btn--info btn--info--comfirm">更新する</button >
                            <button type="submit" name="BTN_BACK" class="cntFtrBtn_prev btn_gray01 btn_hover01 btn--info--fix">戻る</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
<input type="hidden" name="{$const.POST_TOKEN_NAME}" value="{$wt__post_token}">
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
