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
<form method="post" action="{wt_action_url mod="Toiawase" act="ToiawaseKakunin"}">
<main>
    <div class="c-inner">
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
                                <li class="here">
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
                                <img src="/assets/img/cart/or_2-sp.png" alt="確認">
                            </figure>
                        </div>
                        <div class="c-top__titlebox">
                            <h2 class="l-section__title">お問い合わせ内容確認</h2>
                            <p class="hedBottomTxt">
                                ご入力いただきましたお問い合わせ情報のご確認をお願いいたします。<br>
                                確認後に、「送信する」ボタンを押してください。
                            </p>
                        </div>
                        <article class="p-tableBox">
                            <div class="tableInfo tableInfo--confirm">
                                <h3 class="tableInfo__title">お客様情報</h3>
                                <table>
                                    <tbody>
                                    <tr>
                                        <th>お名前</th>
                                        <td data-stt-ignore="">
{$confirm_params[$const.I_SEI_KANJI]|escape}&nbsp;{$confirm_params[$const.I_MEI_KANJI]|escape}
（{$confirm_params[$const.I_SEI_KANA]|escape}&nbsp;{$confirm_params[$const.I_MEI_KANA]|escape}）様
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>メールアドレス</th>
                                        <td data-stt-ignore="">
{$confirm_params[$const.I_EMAIL]|escape}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>電話番号</th>
                                        <td data-stt-ignore="">
{$confirm_params[$const.I_TEL1]|escape}-{$confirm_params[$const.I_TEL2]|escape}-{$confirm_params[$const.I_TEL3]|escape}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tableInfo tableInfo--confirm">
                                <h3 class="tableInfo__title">お問い合わせ内容</h3>
                                <table>
                                    <tbody>
                                    <tr>
                                        <th>お問い合わせ項目</th>
                                        <td data-stt-ignore="">
{$option_inquiry_items[$confirm_params[$const.I_INQUIRY_ITEM]]|escape}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>お問い合わせ内容</th>
                                        <td data-stt-ignore="">
{$confirm_params[$const.I_INQUIRY_TEXT]|escape|nl2br}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </article>
                        <div class="tableInfos__btns pc">
                            <div class="cntFtrButtons">
                                <button type="submit" name="BTN_BACK" class="cntFtrBtn_prev btn_gray01 btn_hover01 btn--info--fix">修正する</button>
                                <button type="submit" name="BTN_SUBMIT" class="cntFtrBtn_next btn_gold01 btn_hover01 btn--info btn--info--comfirm">送信する</button>
                            </div>
                        </div>
                        <div class="tableInfos__btns tableInfos__btns--confirm sp">
                            <div class="cntFtrButtons">
                                <button type="submit" name="BTN_SUBMIT" class="cntFtrBtn_next btn_gold01 btn_hover01 btn--info btn--info--comfirm">送信する</button>
                                <button type="submit" name="BTN_BACK" class="cntFtrBtn_prev btn_gray01 btn_hover01 btn--info--fix">修正する</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
{include file=$smarty.const.WT_COMMON_TEMPLATE_DIR|cat:"post_token.tpl"}
</form>
</div>
{include file=$footerTemplate}
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
