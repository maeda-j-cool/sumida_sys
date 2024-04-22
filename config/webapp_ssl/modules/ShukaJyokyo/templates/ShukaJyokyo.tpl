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
<main>
    <div class="c-inner">
        <section class="l-section l-section--change">
            <div class="l-section__wrap">
                <div id="wrapper">
                    <div id="contents">
                        <h2 class="l-section__title">交換履歴・出荷状況照会</h2>

{include file=$smarty.const.WT_COMMON_TEMPLATE_DIR|cat:"default_error_message.tpl"}

{if empty($Errors)}
                        <div class="tableInfo tableInfo--change">
                            <h3 class="tableInfo__title--sub">全{$hassoKensu|escape}件の交換履歴</h3>
{foreach from=$hassoJyokyoInfo item=hassoJyokyoData name=loopHassoJyokyo}
                            <h3 class="tableInfo__title_b"{if !$smarty.foreach.loopHassoJyokyo.first} style="margin-top:16px;"{/if}>
                                {$smarty.foreach.loopHassoJyokyo.iteration}件目
{if !empty($hassoJyokyoData.dgcInfo)}
                                　送付済
{elseif $hassoJyokyoData.mailfukaflg!=1}
                                　{if $hassoJyokyoData.hassoJyokyo == 0}発送準備中{else}発送済{/if}
{/if}
                            </h3>
                            <table>
                                <tbody>
                                <tr>
                                    <th>受付番号</th>
                                    <td data-stt-ignore="">
                                        {$hassoJyokyoData.moushikomiNo|escape}
                                    </td>
                                </tr>
                                <tr>
                                    <th>商品</th>
                                    <td data-stt-ignore="">
                                        {$hassoJyokyoData.shohinName}（{$hassoJyokyoData.shohinCd|escape}）
                                    </td>
                                </tr>
                                <tr>
                                    <th>申込受付日</th>
                                    <td data-stt-ignore="">
                                        {$hassoJyokyoData.juchubi|wt_date_format:"Y年m月d日(D)"}
                                    </td>
                                </tr>
                                <tr>
                                    <th>お届け予定日</th>
                                    <td data-stt-ignore="">
{if !empty($hassoJyokyoData.dgcInfo)}
                                        {$hassoJyokyoData.juchubi|wt_date_format:"Y年m月d日(D)"}にメールにて送付済
{elseif $hassoJyokyoData.mailfukaflg == 1}
                                        お届け日のご案内ができない商品です
{elseif $hassoJyokyoData.otodokeYoteibi}
                                        {$hassoJyokyoData.otodokeYoteibi|wt_date_format:"Y年m月d日(D)"}
{else}
                                        一部の商品を除き、お申込日から約14日でお届けいたします
{/if}
                                    </td>
                                </tr>
{if $hassoJyokyoData.hassoJyokyo != 0}
{if $hassoJyokyoData.mailfukaflg == 1}
{if $hassoJyokyoData.hassoGyosha}
                                <tr>
                                    <th>配送業者</th>
                                    <td>{$hassoJyokyoData.hassoGyosha}</td>
                                </tr>
{/if}
{if $hassoJyokyoData.okurijoNo}
                                <tr>
                                    <th>配送伝票番号</th>
{if $hassoJyokyoData.shoukaiUrl}
                                    <td><a href="{$hassoJyokyoData.shoukaiUrl}{$hassoJyokyoData.okurijoNo}" target="_blank">{$hassoJyokyoData.okurijoNo}</a></td>
{else}
                                    <td>{$hassoJyokyoData.okurijoNo}</td>
{/if}
{/if}
{else}
{if $hassoJyokyoData.hassoGyosha}
                                <tr>
                                    <th>配送業者</th>
                                    <td>{$hassoJyokyoData.hassoGyosha}</td>
                                </tr>
{/if}
{if $hassoJyokyoData.okurijoNo}
                                <tr>
                                    <th>配送伝票番号</th>
{if $hassoJyokyoData.shoukaiUrl}
                                    <td><a href="{$hassoJyokyoData.shoukaiUrl}{$hassoJyokyoData.okurijoNo}" target="_blank">{$hassoJyokyoData.okurijoNo}</a></td>
{else}
                                    <td>{$hassoJyokyoData.okurijoNo}</td>
{/if}
                                </tr>
{/if}
{/if}
{/if}
{if empty($hassoJyokyoData.dgcInfo)}
                                <tr>
                                    <th>お届け先</th>
                                    <td data-stt-ignore="">
                                        〒{$hassoJyokyoData.zipcode|escape}<br>
                                        {$hassoJyokyoData.address|escape}<br>
{if $hassoJyokyoData.shimei}
                                        {$hassoJyokyoData.shimei|escape}様
{/if}
                                    </td>
                                </tr>
{else}
                                <tr>
                                    <th>デジタルギフトコード</th>
                                    <td data-stt-ignore="">
                                        <dl>
                                            <dd>
{foreach from=$hassoJyokyoData.dgcInfo item=row}
                                                <dl>
                                                    <dt>{$row.name|escape}</dt>
                                                    <dd>
{foreach from=$row.info item=info}
                                                        <dl class="dgc-block" style="padding:10px">
                                                            <dt>{$info.title|escape}</dt>
{if $info.clip}
                                                            <dd style="padding-bottom:4px;">
{if $info.link}
                                                                <a href="{$info.value|escape}" target="_blank"><span class="copy-target">{$info.value|escape}</span></a>
{else}
                                                                <span class="copy-target">{$info.value|escape}</span>
{/if}
                                                                </span>
                                                            </dd>
                                                            <dd style="padding-top:0;"><button>クリップボードにコピー</button></dd>
{else}
                                                            <dd>
{if $info.link}
                                                                <a href="{$info.value|escape}" target="_blank">{$info.value|escape}</a>
{else}
                                                                {$info.value|escape}
{/if}
                                                            </dd>
{/if}
                                                        </dl>
{/foreach}
                                                    </dd>
                                                </dl>
{/foreach}
                                            </dd>
                                        </dl>
                                    </td>
                                </tr>
{/if}
                                </tbody>
                            </table>
{/foreach}
                        </div>
{/if}
                        <div class="more--productArchive c-more mt60">
                            <a href="{wt_action_url mod="" act=""}" id="">TOPページヘ</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
</div>
{include file=$footerTemplate}
{literal}
<script type="text/javascript">
$(function() {
    $('.dgc-block button').click(function() {
        var text = $('.copy-target', $(this).parents('.dgc-block')).text();
        if (navigator.clipboard === undefined) {
            window.clipboardData.setData("Text", text);
        } else {
            navigator.clipboard.writeText(text);
        }
    });
});
</script>
{/literal}
