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
<form method="post" action="{wt_action_url mod='Member' act='RegisterInput'}">
<main class="main-other">
    <div class="c-inner">
        <section class="l-section l-section--re">
            <div class="l-section__wrap">
                <h2 class="l-section__title">利用者情報登録</h2>
            <!-- <div class="infosaddComfirm infotopComfirm">
                <label class="check_guide" for="allot">
                    <input type="checkbox" name="myCheckbox" class="custom-checkbox" id="allot">
                    <span class="checkmark"></span>
                    出産応援ギフト（妊婦1人につき5万円相当）の支給を希望します。
                </label>
             
            </div> -->
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

                    <div class="tableInfo">
                        <h3 class="tableInfo__title">1歳を迎えたお子さまの氏名・生年月日</h3>
                        <table>
                            <tr>
                                <th class="tableInfo_th">
                                    <span class="mandatory_">必須</span>
                                    <p>お名前</p>
                                    </th>
                                <td>{strip}
                                    <span class="mr15">姓</span>
                                    <input type="text" name="{$const.I_SEI_KANJI3}" value="{$wt__posts[$const.I_SEI_KANJI3]|escape}" class="mr30" autocomplete="family-name">
                                    <span class="mr15">名</span>
                                    <input type="text" name="{$const.I_MEI_KANJI3}" value="{$wt__posts[$const.I_MEI_KANJI3]|escape}" class="mr30" autocomplete="given-name">
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
                                    <input type="text" name="{$const.I_SEI_KANA3}" value="{$wt__posts[$const.I_SEI_KANA3]|escape}" class="mr30" autocomplete="family-name">
                                    <span class="mr15">めい</span>
                                    <input type="text" name="{$const.I_MEI_KANA3}" value="{$wt__posts[$const.I_MEI_KANA3]|escape}" class="mr30" autocomplete="given-name">
                                    <span>【全角】</span>
                                {/strip}</td>
                            </tr>
                            <tr>
                                <th class="tableInfo_th">
                                    <span class="mandatory_">必須</span>
                                    <p>生年月日</p>
                                </th>
                                <td>
                                    <span class="tableInfo__select">
{html_options
    name="{$const.I_BIRTHDAY3_Y}"
    options=$y_list3
    selected=$wt__posts[$const.I_BIRTHDAY3_Y]
    autocomplete="bd-year"
}
                                    </span>
                                    <span class="tableInfo__select">
{html_options
    name="{$const.I_BIRTHDAY3_M}"
    options=$m_list
    selected=$wt__posts[$const.I_BIRTHDAY3_M]
    autocomplete="bday-month"
}
                                    </span>
                                    <span class="tableInfo__select">
{html_options
    name="{$const.I_BIRTHDAY3_D}"
    options=$d_list
    selected=$wt__posts[$const.I_BIRTHDAY3_D]
    autocomplete="bday-day"
}
                                    </span>
                                </td>
                            </tr>
                            <tr class="u-tableInfo">
                                <th>お子さまの情報</th>
                                <td>{$settings.kenshu_name}</td>
                            </tr>
                        </table>
                        <div class="u-annotation">
                            <p>※第何子については、以下内容をご確認ください。疑義がある場合は必ず申請前にお問い合わせください。</p>
                            <p>・同じ世帯に住民登録があり、家計を同一にして養育している18歳未満のお子さまのうち、対象のお子さまが1歳の誕生日時点で第何子かで決まります。</p>
                            <p>（例：18歳、5歳の兄弟姉妹がいて、1歳のお子さまがいるご家庭の場合、5歳が第1子、1歳が第2子となります。）</p>
                            <p>・別の住所地で生活をしているお子さまは算定に入りません。</p>
                        </div>
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
                                    <p>都道府県</p>
                                    <span class="mandatory_">必須</span>
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
                    <div class="tableInfo tableInfo--address">

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
                                    <span class="mandatory_">必須</span>
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
{if $enquete_info}
                <h2 class="l-section__title l-section__title--cart l-section__title--cart--sp" data-stt-ignore>
                アンケートにお答えください
                </h2>
                    <div class="tableInfo" data-stt-ignore>
                        <table>
                            <tbody>
{foreach from=$enquete_info item=enquete key=eid}
{assign var="post_name" value="enquete{$eid}"}
{assign var="post_value" value=""}
{if isset($wt__posts[$post_name])}
{assign var="post_value" value=$wt__posts[$post_name]}
{/if}
                                <tr>
                                    <th>
                                        【{$enquete.M36SEQ|escape}】{$enquete.M36QUESTION}
                                        {if $enquete.M36REQUIRED == '1'}
                                            <br class="sp-none"><span class="mandatory_">必須</span>
                                        {else}
                                            <br class="sp-none"><span class="any_">任意</span>
                                        {/if}
                                    </th>
{if $enquete.M36OPTIONTYPE == '00'}{* 単一選択 *}
                                    <td class="question-group">
{foreach from=$enquete.M37 item=option key=oid}
                                        <div class="radio-item">
                                            <input type="radio" name="{$post_name|escape}" value="{$oid|escape}" id="{$post_name|escape}_{$oid|escape}"{if $post_value==$oid} checked{/if}>
                                            <label for="{$post_name|escape}_{$oid|escape}">{$option.M37TEXT|escape}</label>
{if $option.M37HASFREE == '1'}
{assign var="post_name_ex" value="{$post_name|escape}_{$oid|escape}"}
{if $post_value==$oid && isset($wt__posts[$post_name_ex])}
{assign var="post_value_ex" value="{$wt__posts[$post_name_ex]}"}
{else}
{assign var="post_value_ex" value=""}
{/if}
                                            <textarea type="text" name="{$post_name_ex|escape}" rows="1" style="min-height:initial;">{$post_value_ex|escape}</textarea>
{/if}
                                        </div>
{/foreach}
                                    </td>
{elseif $enquete.M36OPTIONTYPE == '10'}{* 複数選択 *}
                                    <td class="question-group-plu">
{foreach from=$enquete.M37 item=option key=oid}
                                        <div class="checkbox-item">
                                            <input type="checkbox" name="{$post_name|escape}[]" value="{$oid|escape}" id="{$post_name|escape}_{$oid|escape}"{if is_array($post_value) && in_array($oid, $post_value)} checked{/if}>
                                            <label for="{$post_name|escape}_{$oid|escape}">{$option.M37TEXT|escape}</label>
{if $option.M37HASFREE == '1'}
{assign var="post_name_ex" value="{$post_name|escape}_{$oid|escape}"}
{if is_array($post_value) && in_array($oid, $post_value) && isset($wt__posts[$post_name_ex])}
{assign var="post_value_ex" value="{$wt__posts[$post_name_ex]}"}
{else}
{assign var="post_value_ex" value=""}
{/if}
                                            <textarea type="text" name="{$post_name_ex|escape}" rows="1" style="min-height:initial;">{$post_value_ex|escape}</textarea>
{/if}
                                        </div>
{/foreach}
                                    </td>
{elseif $enquete.M36OPTIONTYPE == '20'}{* 自由入力 *}
                                    <td>
                                        <div class="item__textarea">
                                            <textarea name="{$post_name|escape}" rows="30" cols="92" data-dl-input-translation="true">{$post_value|escape}</textarea>
                                        </div>
                                    </td>
{/if}
                                </tr>
{/foreach}
                            </tbody>
                        </table>
                    </div>
{/if}

                    {* <div class="infosaddComfirm">
                        <label class="check_guide" for="no_add">
                            <input type="checkbox" name="myCheckbox" class="custom-checkbox" id="no_add">
                            <span class="checkmark"></span>
                            他の自治体で、出産・子育て支援交付金による出産応援ギフトの支給を受けていません。
                        </label>
                        <p class="infosComfirm__note">
                            ※出産応援ギフトの支給状況などについて、他の自治体に確認することがあります。
                        </p>
                    </div>
                    <div class="infosComfirm info-left">
                        <label class="check_guide" for="terms">
                            <input type="checkbox" name="myCheckbox" class="custom-checkbox" id="terms">
                            <span class="checkmark"></span>
                            利用規約承諾に同意する
                        </label>
                        <p class="infosComfirm__note">
                            ※利用規約承諾については、<a class="under-bar" href="/terms/" target="_blank">こちら</a>をご確認ください。
                        </p>
                    </div> *}
                    <div class="infosComfirm">
                    <p class="infosComfirm__note">
                        以下の事項をご確認のうえ、ご承諾いただきますようお願いいたします。
                    </p>
                    </div>
                    <div class="infosaddComfirm">
                        <label class="check_guide" for="consent_return">
                            <input type="checkbox" name="myCheckbox" class="custom-checkbox" id="consent_return">
                            <span class="checkmark"></span>
                            利用規約に違反された場合は、ポイントの取り消しを行います。
                        </label>
                    </div>
                    <div class="infosaddComfirm">
                        <label class="check_guide" for="consent_share">
                            <input type="checkbox" name="myCheckbox" class="custom-checkbox" id="consent_share">
                            <span class="checkmark"></span>
                            本事業の適切な実施のため、区及び関係機関に情報を確認、共有します。
                        </label>
                    </div>
                    <div class="infosaddComfirm">
                        <label class="check_guide" for="consent_contact">
                            <input type="checkbox" name="myCheckbox" class="custom-checkbox" id="consent_contact">
                            <span class="checkmark"></span>
                            このアンケートをもとに、墨田区健康推進課からご連絡させていただくこともあります。
                        </label>
                    </div>
                    <div class="infosaddComfirm">
                        <label class="check_guide" for="consent_data">
                            <input type="checkbox" name="myCheckbox" class="custom-checkbox" id="consent_data">
                            <span class="checkmark"></span>
                            個人情報の取り扱いに同意する
                        </label>
                        <p class="infosComfirm__note">
                            ※個人情報の取り扱いについては、<a class="under-bar" href="/terms/" target="_blank">こちら</a>をご確認ください。
                        </p>
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
<script>
{literal}
document.addEventListener("DOMContentLoaded", function() {
    const checkbox = document.getElementById("consent_return");
    const checkbox2 = document.getElementById("consent_share");
    const checkbox3 = document.getElementById("consent_contact");
    const checkbox4 = document.getElementById("consent_data");
    const submitButton = document.querySelector(".btn--info--comfirm");

    checkbox.addEventListener("change", function() {
        if (checkbox.checked && checkbox2.checked && checkbox3.checked && checkbox4.checked ) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    });

     checkbox2.addEventListener("change", function() {
        if (checkbox.checked && checkbox2.checked && checkbox3.checked && checkbox4.checked ) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    });

    checkbox3.addEventListener("change", function() {
        if (checkbox.checked && checkbox2.checked && checkbox3.checked && checkbox4.checked ) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    });

    checkbox4.addEventListener("change", function() {
        if (checkbox.checked && checkbox2.checked && checkbox3.checked && checkbox4.checked ) {
            submitButton.disabled = false;
        } else {
            submitButton.disabled = true;
        }
    });

    // 初期状態でボタンを無効化
    submitButton.disabled = true;
});
{/literal}
</script>
