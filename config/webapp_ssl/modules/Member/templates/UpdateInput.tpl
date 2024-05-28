{capture name=header}
<link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
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
<form method="post" action="{wt_action_url mod='Member' act='UpdateInput'}">
<main class="main-other">
    <div class="c-inner">
        <section class="l-section l-section--re">
            <div class="l-section__wrap">
                <h2 class="l-section__title">利用者登録情報/パスワードの変更</h2>
                <div class="l-section--re__sub">
                    <p class="l-section--re__subText">お客様の情報をご入力ください。<br>ご入力いただいた情報は、お申し込み時に反映されます。<br>
                        ※お申し込み時の反映は1人目の保護者の情報を反映します。この情報はお申し込み時変更が可能です。</p>
                </div>

{include file=$smarty.const.WT_COMMON_TEMPLATE_DIR|cat:"default_error_message.tpl"}

                <div class="tableInfos tableInfos-re">
                    <div class="tableInfo">
                        <h3 class="tableInfo__title">保護者の氏名・続柄</h3>
                        <p class="st_person">（1人目）</p>
                        <table>
                            <tr>
                                <th class="tableInfo_th">
                                    <span class="mandatory_">必須</span>
                                    <p>お名前</p>
                                </th>
                                <td>{strip}
                                    <span class="mr15">姓</span>
                                    <input type="text" name="{$const.I_SEI_KANJI1}" value="{$wt__posts[$const.I_SEI_KANJI1]|escape}" class="mr30" autocomplete="family-name">
                                    <span class="mr15">名</span>
                                    <input type="text" name="{$const.I_MEI_KANJI1}" value="{$wt__posts[$const.I_MEI_KANJI1]|escape}" class="mr30" autocomplete="given-name">
                                    <span>【全角】</span>
                                {/strip}</td>
                            </tr>
                            <tr>
                                <th class="tableInfo_th">
                                    <span class="mandatory_">必須</span>
                                    <p>お名前（ふりがな）</p>
                                </th>
                                <td>{strip}
                                    <span class="mr15">せい</span>
                                    <input type="text" name="{$const.I_SEI_KANA1}" value="{$wt__posts[$const.I_SEI_KANA1]|escape}" class="mr30" autocomplete="family-name">
                                    <span class="mr15">めい</span>
                                    <input type="text" name="{$const.I_MEI_KANA1}" value="{$wt__posts[$const.I_MEI_KANA1]|escape}" class="mr30" autocomplete="given-name">
                                    <span>【全角】</span>
                                {/strip}</td>
                            </tr>
                            <tr>
                                <th class="tableInfo_th">
                                    <span class="mandatory_">必須</span>
                                    <p>続柄</p>
                                </th>
                                <td>
                                    <span class="tableInfo__select">
{html_options
    name="{$const.I_RELATION1}"
    options=$rel_list
    selected=$wt__posts[$const.I_RELATION1]
}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="tableInfo">
                        <p class="st_person">（2人目）</p>
                        <table>
                            <tr>
                                <th class="tableInfo_th">
                                    <span class="any_">任意</span>
                                    <p>お名前</p>
                                </th>
                                <td>{strip}
                                    <span class="mr15">姓</span>
                                    <input type="text" name="{$const.I_SEI_KANJI2}" value="{$wt__posts[$const.I_SEI_KANJI2]|escape}" class="mr30" autocomplete="family-name">
                                    <span class="mr15">名</span>
                                    <input type="text" name="{$const.I_MEI_KANJI2}" value="{$wt__posts[$const.I_MEI_KANJI2]|escape}" class="mr30" autocomplete="given-name">
                                    <span>【全角】</span>
                                {/strip}</td>
                            </tr>
                            <tr>
                                <th class="tableInfo_th">
                                    <span class="any_">任意</span>
                                    <p>お名前（ふりがな）</p>
                                </th>
                                <td>{strip}
                                    <span class="mr15">せい</span>
                                    <input type="text" name="{$const.I_SEI_KANA2}" value="{$wt__posts[$const.I_SEI_KANA2]|escape}" class="mr30" autocomplete="family-name">
                                    <span class="mr15">めい</span>
                                    <input type="text" name="{$const.I_MEI_KANA2}" value="{$wt__posts[$const.I_MEI_KANA2]|escape}" class="mr30" autocomplete="given-name">
                                    <span>【全角】</span>
                                {/strip}</td>
                            </tr>
                            <tr>
                                <th class="tableInfo_th">
                                    <span class="any_">任意</span>
                                    <p>続柄</p>
                                </th>
                                <td>
                                    <span class="tableInfo__select">
{html_options
    name="{$const.I_RELATION2}"
    options=$rel_list
    selected=$wt__posts[$const.I_RELATION2]
}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="tableInfo tableInfo--address">
                        <h3 class="tableInfo__title">住所</h3>
                        <table>
                            <tr>
                                <th class="tableInfo_th">
                                    <span class="mandatory_">必須</span>
                                    <p>郵便番号</p>
                                </th>
                                <td>{strip}
<input type="number" name="{$const.I_ZIPCODE_1|escape}" value="{$wt__posts[$const.I_ZIPCODE_1]|escape}" size="4" maxlength="3" onKeyUp="AjaxZip3.zip2addr('{$const.I_ZIPCODE_1|escape}','{$const.I_ZIPCODE_2|escape}','{$const.I_ADDRESS_1|escape}','{$const.I_ADDRESS_2|escape}');" autocomplete="postal-code" pattern="\d{3}">
&nbsp;－&nbsp;
<input type="number" name="{$const.I_ZIPCODE_2|escape}" value="{$wt__posts[$const.I_ZIPCODE_2]|escape}" size="5" maxlength="4" onKeyUp="AjaxZip3.zip2addr('{$const.I_ZIPCODE_1|escape}','{$const.I_ZIPCODE_2|escape}','{$const.I_ADDRESS_1|escape}','{$const.I_ADDRESS_2|escape}');" autocomplete="postal-code" pattern="\d{4}">
                                {/strip}</td>
                            </tr>
                            <tr>
                                <th class="tableInfo_th">
                                    <span class="mandatory_">必須</span>
                                    <p>都道府県</p>
                                </th>
                                <td>
                                    <div class="tableInfo__select">
{html_options
    name="{$const.I_ADDRESS_1}"
    options=$pref_list
    selected=$wt__posts[$const.I_ADDRESS_1]
    autocomplete="address-level1"
}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="tableInfo_th">
                                    <span class="mandatory_">必須</span>
                                    <p>市区町村 番地</p>
                                </th>
                                <td>
                                    <input type="text" name="{$const.I_ADDRESS_2|escape}" value="{$wt__posts[$const.I_ADDRESS_2]|escape}" class="tableInfo__bigInput" autocomplete="address-level2">
                                </td>
                            </tr>
                            <tr>
                                <th class="tableInfo_th">
                                    <span class="any_">任意</span>
                                    <p>建物名 部屋番号</p>
                                </th>
                                <td>
                                    <input type="text" name="{$const.I_ADDRESS_3|escape}" value="{$wt__posts[$const.I_ADDRESS_3]|escape}" class="tableInfo__bigInput" autocomplete="address-level4">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="tableInfo tableInfo--tell">
                        <h3 class="tableInfo__title">電話番号</h3>
                        <table>
                            <tr>
                                <th class="tableInfo_th">
                                    <span class="mandatory_">必須</span>
                                    <p>電話番号1</p>
                                </th>
                                <td>{strip}
<input type="tel" name="{$const.I_TEL1_1|escape}" value="{$wt__posts[$const.I_TEL1_1]|escape}" class="short" placeholder="" maxlength="6" autocomplete="tel">
　-　
<input type="tel" name="{$const.I_TEL1_2|escape}" value="{$wt__posts[$const.I_TEL1_2]|escape}" class="short" placeholder="" maxlength="5" autocomplete="tel">
　-　
<input type="tel" name="{$const.I_TEL1_3|escape}" value="{$wt__posts[$const.I_TEL1_3]|escape}" class="short" placeholder="" maxlength="5" autocomplete="tel">
                                {/strip}</td>
                            </tr>
                            <tr>
                                <th class="tableInfo_th">
                                    <span class="any_">任意</span>
                                    <p>電話番号2</p>
                                </th>
                                <td>{strip}
<input type="tel" name="{$const.I_TEL2_1|escape}" value="{$wt__posts[$const.I_TEL2_1]|escape}" class="short" placeholder="" maxlength="6" autocomplete="tel">
　-　
<input type="tel" name="{$const.I_TEL2_2|escape}" value="{$wt__posts[$const.I_TEL2_2]|escape}" class="short" placeholder="" maxlength="5" autocomplete="tel">
　-　
<input type="tel" name="{$const.I_TEL2_3|escape}" value="{$wt__posts[$const.I_TEL2_3]|escape}" class="short" placeholder="" maxlength="5" autocomplete="tel">
                                {/strip}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="tableInfo tableInfo--mail">
                        <h3 class="tableInfo__title">メールアドレス</h3>
                        <table>
                            <tr>
                                <th class="tableInfo_th">
                                    <p>メールアドレス</p>
                                    </th>
                                <td>
                                    {$wt__posts[$const.S_EMAIL]|escape}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="tableInfo tableInfo--password">
                        <h3 class="tableInfo__title">ユーザーパスワード</h3>
                        <table>
                            <tr>
                                <th class="tableInfo_th">
                                    <span class="any_">任意</span>
                                    <p>新しいパスワード</p>
                                </th>
                                <td>
<input type="password" name="{$const.I_PASSWORD1|escape}" value="" id="password1" class="tableInfo__bigInput" autocomplete="new-password">
<span id="buttonEye" class="fa fa-eye" onclick="togglePasswordVisibility('password1', 'buttonEye')"></span>
                                    <p class="tableInfo__note">
                                        半角の英字(大文字・小文字)を含む、8文字以上の文字列で入力してください
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th class="tableInfo_th">
                                    <p>新しいパスワード（確認）</p>
                                </th>
                                <td>
<input type="password" name="{$const.I_PASSWORD2|escape}" value="" id="password2" class="tableInfo__bigInput" autocomplete="new-password">
<span id="buttonEye1" class="fa fa-eye" onclick="togglePasswordVisibility('password2', 'buttonEye1')"></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="tableInfos__btns">
                        <button type="submit" name="BTN_NEXT" class="btn--info btn--info--comfirm">確認画面へ</button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
</form>
</div>
{include file=$footerTemplate}
<script src="/assets/js2/form.js"></script>
