{if isset($show_list) && $show_list}{* #1 *}
{* ----- リスト表示用 ----------------------------------------------------------------------------*}
{include file=$smarty.const.SHOHIN_KEYWORD_TEMPLATE_DIR|cat:"keyword_list.tpl"}
{else}{* #1 *}
{* ----- ページ表示用 ----------------------------------------------------------------------------*}
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
{if !empty($Errors)}{* #2 *}
<div id="sgBread">
    <ul>
        <li class="home"><a href="{$smarty.const.WT_URL_BASE_SSL}">ホーム</a></li>
        <li class="last">キーワード検索結果</li>
    </ul>
</div>
<section class="l-section l-section--productArchive">
    <div class="l-section__wrap">
        <h2 class="login-product__title">キーワード検索結果</h2>

{include file=$smarty.const.WT_COMMON_TEMPLATE_DIR|cat:"default_error_message.tpl"}

    </div>
</section>
{else}{* #2 *}
<div id="sgBread">
    <ul>
        <li class="home"><a href="{$smarty.const.WT_URL_BASE_SSL}">ホーム</a></li>
        <li class="last">キーワード検索結果</li>
    </ul>
</div>
<section class="l-section l-section--productArchive">
    <div class="l-section__wrap">
        <h2 class="login-product__title">{if !empty($keyword)}{$keyword|escape} の{/if}商品一覧</h2>
        <div class="archiveLead">
            <p class="archiveLead__num">{$resultCount|number_format}件あります</p>
            <div class="archiveLead__anchers__acBox sp">
                <div class="archiveLead__anchers__acBox--1">
                    <select name="sort" class="archiveLead__anchers-select">
{section name=index loop=$sortList}
{assign var="kw_link" value="{wt_action_url mod="SS" act="KS"}keyword/{$keyword|escape:"url"}/sort/{$sortList[index].sort|escape:"url"}/order/{$sortList[index].order|escape:"url"}"}
                        <option value="{$kw_link}"{if $sortList[index].select == ""} selected=""{/if}>{$sortList[index].name|escape}</option>
{/section}
                    </select>
                </div>
            </div>
            <ul class="archiveLead__anchers_pc pc">
                <li class="now">
                    <div class="archiveLead__anchers__acBox--1">
                        <select name="sort" class="archiveLead__anchers-select">
{section name=index loop=$sortList}
{assign var="kw_link" value="{wt_action_url mod="SS" act="KS"}keyword/{$keyword|escape:"url"}/sort/{$sortList[index].sort|escape:"url"}/order/{$sortList[index].order|escape:"url"}"}
                            <option value="{$kw_link}"{if $sortList[index].select == ""} selected=""{/if}>{$sortList[index].name|escape}</option>
{/section}
                        </select>
                    </div>
                </li>
            </ul>
        </div>
        <div class="productLists" id="shohin_list">
{include file=$smarty.const.SHOHIN_KEYWORD_TEMPLATE_DIR|cat:"keyword_list.tpl"}
        </div>
        <div class="more more--productArchive c-more">
{if $wt__pager_info.last > 1}
            <a href="javascript:void(0);" id="read_more">もっとみる</a>
            <img src="/images/loading_80_80.gif" id="loading_page" style="display:none;" alt="" width="80" height="80">
{/if}
        </div>
    </div>
</section>
{/if}{* #2 *}
</main>
</div>
{include file=$footerTemplate}
{if $wt__pager_info.last > 1}
<script type="text/javascript">
{literal}
var current_page_no = {/literal}{$ks_page_no}{literal};
var last_page_no = {/literal}{$wt__pager_info.last}{literal};
$(function() {
    $('#read_more').click(function() {
        $('#read_more').hide();
        $('#loading_page').fadeIn();
        current_page_no++;
        if (current_page_no <= last_page_no) {
            $.ajax({
                type: 'POST',
                url: '{/literal}{wt_action_url mod="SS" act="KSList"}{literal}',
                data: { 'elp' : '{/literal}{$encoded_list_params}{literal}', '{/literal}{$smarty.const.GET_PARAM_PAGE}{literal}': current_page_no },
                success: function(html) {
                    $('#loading_page').hide();
                    $('#shohin_list').append(html);
                    if (current_page_no < last_page_no) {
                        $('#read_more').fadeIn();
                    }
                },
                dataType: 'html',
                cache: false,
                error: function(xhr, status, thrown) {
                    $('#loading_page').hide();
                    alert(status);
                }
            });
        }
    });
    {/literal}{if $ks_shohin_no}{literal}
    setTimeout(function() {
        var p = $('#sno{/literal}{$ks_shohin_no|escape}{literal}').offset().top;
        $('html, body').animate({ scrollTop: p }, 'fast');
    }, 1000);
    {/literal}{/if}{literal}
});
$(window).bind("unload",function(){});
{/literal}
</script>
{/if}
<script>
{literal}
document.addEventListener("DOMContentLoaded", function() {
    // クラス名で要素を取得
    const selectElements = document.querySelectorAll(".archiveLead__anchers-select");
    // 各<select>要素にイベントリスナーを追加
    selectElements.forEach((selectElement) => {
        selectElement.addEventListener("change", function(event) {
            // 選択された<option>要素のvalueを取得
            const selectedValue = this.value;
            // リダイレクト（valueがURLであれば）
            if (selectedValue && selectedValue !== '#') {
                window.location.href = selectedValue;
            }
        });
    });
});
$(function() {
    $('.is-ac').on('click', function () {
        $(this).next().slideToggle();
    });
});
{/literal}
</script>
{/if}{* #1 *}
