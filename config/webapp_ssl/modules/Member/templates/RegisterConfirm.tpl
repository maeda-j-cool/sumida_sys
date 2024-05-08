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
<form method="post" action="{wt_action_url mod='Member' act='RegisterConfirm'}">
<main class="main-other">
    <div class="c-inner">
        <section class="l-section l-section--re">
            <div class="l-section__wrap">
                <h2 class="l-section__title">利用申請登録</h2>

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
                        <h3 class="tableInfo__title">お子さまの氏名・生年月日</h3>
                        <table>
                            <tr>
                                <th>お名前</th>
                                <td>{$confirm_params[$const.I_SEI_KANJI3]|escape} {$confirm_params[$const.I_MEI_KANJI3]|escape}</td>
                            </tr>
                            <tr>
                                <th>お名前（ふりがな）</th>
                                <td>{$confirm_params[$const.I_SEI_KANA3]|escape} {$confirm_params[$const.I_MEI_KANA3]|escape}</td>
                            </tr>
                            <tr>
                                <th>生年月日</th>
                                <td>{strip}
                                    {$confirm_params[$const.I_BIRTHDAY3_Y]|escape}年
                                    {$confirm_params[$const.I_BIRTHDAY3_M]|escape}月
                                    {$confirm_params[$const.I_BIRTHDAY3_D]|escape}日
                                {/strip}</td>
                            </tr>
                            <tr class="u-tableInfo">
                                <th>お子様は<br>第何子ですか</th>
                                <td>第１子</td>
                            </tr>
                        </table>
                        <div class="u-annotation">
                            <p>※第何子については、以下内容をご確認ください。疑義がある場合は必ず申請前にお問い合わせください。</p>
                            <p>・同じ世帯に住民登録があり、家計を同一にして養育している18歳未満のお子さまのうち、対象のお子さまが1歳の誕生日時点で第何子かで決まります。</p>
                            <p>（例：18歳、5歳の兄弟姉妹がいて、1歳のお子さまがいるご家庭の場合、5歳が第1子、1歳が第2子となります。）</p>
                            <p>・別の住所地で生活をしているお子さまは算定に入りません。</p>
                        </div>
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
                                <td>●●●●●●●●●</td>
                            </tr>
                        </table>
                    </div>

{if $enquete_info}
                    <div class="tableInfo">
                        <h3 class="tableInfo__title">アンケートの回答</h3>
                        <table>
                            <tbody>
{foreach from=$enquete_info item=enquete key=eid}
{assign var="post_name" value="enquete{$eid}"}
{assign var="post_value" value=""}
{if isset($confirm_params[$post_name])}
{assign var="post_value" value=$confirm_params[$post_name]}
{/if}
                                <tr>
                                    <th>【{$enquete.M36SEQ|escape}】{$enquete.M36QUESTION}</th>
                                    <td>{strip}
{if $enquete.M36OPTIONTYPE == '00'}{* 単一選択 *}
    {foreach from=$enquete.M37 item=option key=oid}
        {if $post_value==$oid}
            <div>{$option.M37TEXT|escape}</div>
            {if $option.M37HASFREE == '1'}
                {assign var="post_name_ex" value="{$post_name|escape}_{$oid|escape}"}
                {if isset($confirm_params[$post_name_ex]) && $confirm_params[$post_name_ex]}<div>{$confirm_params[$post_name_ex]|escape|nl2br}</div>{/if}
            {/if}
        {/if}
    {/foreach}
{elseif $enquete.M36OPTIONTYPE == '10'}{* 複数選択 *}
    {foreach from=$enquete.M37 item=option key=oid}
        {if is_array($post_value) && in_array($oid, $post_value)}
            <div>{$option.M37TEXT|escape}</div>
            {if $option.M37HASFREE == '1'}
                {if $enquete.M36SEQ|escape == '1'}
{* 墨田区専用：特殊>>> *}
{assign var="post_name_ex" value="{$post_name|escape}_{$oid|escape}"}
{assign var="post_name_ex_n" value="{$post_name|escape}_{$oid|escape}_n"}
{assign var="post_name_ex_age1" value="{$post_name|escape}_{$oid|escape}_age1"}
{assign var="post_name_ex_age2" value="{$post_name|escape}_{$oid|escape}_age2"}
{assign var="post_name_ex_age3" value="{$post_name|escape}_{$oid|escape}_age3"}
<span>（
■人数：
{$confirm_params[$post_name_ex_n]|escape}名
&emsp;
■年齢：
{if isset($confirm_params[$post_name_ex_age1]) && $confirm_params[$post_name_ex_age1]}
{$confirm_params[$post_name_ex_age1]|escape}歳
{/if}
{if isset($confirm_params[$post_name_ex_age2]) && $confirm_params[$post_name_ex_age2]}
{if isset($confirm_params[$post_name_ex_age1]) && $confirm_params[$post_name_ex_age1]}
&emsp;
{/if}
{$confirm_params[$post_name_ex_age2]|escape}歳
{/if}
{if isset($confirm_params[$post_name_ex_age3]) && $confirm_params[$post_name_ex_age3]}
{if (isset($confirm_params[$post_name_ex_age1]) && $confirm_params[$post_name_ex_age1]) || (isset($confirm_params[$post_name_ex_age2]) && $confirm_params[$post_name_ex_age2])}
&emsp;
{/if}
{$confirm_params[$post_name_ex_age3]|escape}歳
{/if}
）</span>
{* <<<墨田区専用：特殊 *}
                {else}
                    {assign var="post_name_ex" value="{$post_name|escape}_{$oid|escape}"}
                    {if isset($confirm_params[$post_name_ex])}<div>{$confirm_params[$post_name_ex]|escape|nl2br}</div>{/if}
                {/if}
            {/if}
        {/if}
    {/foreach}
{elseif $enquete.M36OPTIONTYPE == '20'}{* 自由入力 *}
    <div>{$post_value|escape|nl2br}</div>
{/if}
                                    {/strip}</td>
                                </tr>
{/foreach}
                            </tbody>
                        </table>
                    </div>
{/if}

                    <div class="tableInfos__btns tableInfos__btns--confirm pc">
                        <div class="cntFtrButtons">
                            <button type="submit" name="BTN_BACK" class="cntFtrBtn_prev btn_gray01 btn_hover01 btn--info--fix">戻る</button>
                            <button type="submit" name="BTN_SUBMIT" class="cntFtrBtn_next btn_gold01 btn_hover01 btn--info btn--info--comfirm">登録する</button >
                        </div>
                    </div>
                    <div class="tableInfos__btns tableInfos__btns--confirm sp">
                        <div class="cntFtrButtons">
                            <button type="submit" name="BTN_SUBMIT" class="cntFtrBtn_next btn_gold01 btn_hover01 btn--info btn--info--comfirm">登録する</button >
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
