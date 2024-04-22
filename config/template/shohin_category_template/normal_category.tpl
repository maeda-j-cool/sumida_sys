{if isset($show_list) && $show_list}{* #1 *}
{* ----- リスト表示用 ----------------------------------------------------------------------------*}
{include file=$smarty.const.SHOHIN_CATEGORY_TEMPLATE_DIR|cat:"cat_point_list.tpl"}
{else}{* #1 *}
{* ----- ページ表示用 ----------------------------------------------------------------------------*}
{capture name=header}
<link rel="stylesheet" href="/assets/css/dialog.css">
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
        <li class="last">カテゴリ検索結果</li>
    </ul>
</div>
<section class="l-section l-section--productArchive">
    <div class="l-section__wrap">
        <h2 class="login-product__title">カテゴリ検索結果</h2>

{include file=$smarty.const.WT_COMMON_TEMPLATE_DIR|cat:"default_error_message.tpl"}

    </div>
</section>
{else}{* #2 *}
<div id="sgBread">
    <ul>
        <li class="home"><a href="{$smarty.const.WT_URL_BASE_SSL}">ホーム</a></li>
{if !empty($panTree)}
        {$panTree}
{elseif !empty($title)}
        <li>{$title|escape}</li>
{/if}
    </ul>
</div>
<section class="l-section l-section--productArchive">
    <div class="l-section__wrap">
        <h2 class="login-product__title">{if !empty($categoryName)}{$categoryName|escape} {else}全て{/if}の商品一覧</h2>
        <div class="archiveLead">
            <p class="archiveLead__num">{$resultCount|number_format}件あります</p>
            <div class="archiveLead__anchers__acBox sp">
                <div class="archiveLead__anchers__acBox--2">
                    <p class="">
                        <span class="archiveLead__serchBtn" id="tabBtnSearch">絞り込み</span>
                    </p>
                </div>
                <div class="archiveLead__anchers__acBox--1">
                    <select name="sort" id="custom-select" class="archiveLead__anchers-select">

{section name=index loop=$sortList}

                                    <option value="{$sortList[index].link|escape}" {if $sortList[index].link == ""}selected=""{/if}>{$sortList[index].name|escape}</option>
{/section}
 </select>
                   
                </div>
            </div>
            <ul class="archiveLead__anchers_pc pc">
                <li class="archiveLead__serch">
                    <span class="archiveLead__serchBtn arrow">絞り込み</span>
                </li>
                <li class="now">
                    <div class="archiveLead__anchers__acBox--1">
                        
                    <select name="sort" id="custom-select" class="archiveLead__anchers-select">

{section name=index loop=$sortList}

                                    <option value="{$sortList[index].link|escape}" {if $sortList[index].link == ""}selected=""{/if}>{$sortList[index].name|escape}</option>
{/section}
 </select>
                        
                    </div>
                </li>
            </ul>
                                <div id="tabSearch">
                        <dl>
                        <div class="tabSearch_bg">
                            <dt>商品の絞り込み</dt>
                            <dd>
                                <ul>
{section name=index loop=$dirList}
                                    <li>{strip}
{if $dirList[index].link == ""}
                                        {$dirList[index].name|escape}
{else}
                                        <a href="{wt_action_url mod='SS' act='CS'}group/{$dirList[index].group|escape:"url"}/catid/{$dirList[index].catid|escape:"url"}/tabAllFlg/1" target="_self"  class="tabSearch-arrow right-arrow">
                                            {$dirList[index].name}
                                        </a>
{/if}
                                    {/strip}</li>
{/section}
                                </ul>
                            </dd>
                            <dt>ブランド別</dt>
                            <dd>
                                <ul class="clearfix">
{section name=index loop=$brandList}
                                    <li>{strip}
{if $brandList[index].link == ""}
                                        {$brandList[index].name|escape}
{else}
                                        <a href="{$brandList[index].link|escape}" target="_self" class="tabSearch-arrow right-arrow">
                                            {$brandList[index].name|escape}
                                        </a>
{/if}
                                    {/strip}</li>
{/section}
                                </ul>
                            </dd>
                            <dt>キーワード</dt>
                            <dd>
                            <div class="freeWord">
                            
                        <form method="get" action="{wt_action_url mod='SS' act='CS'}group/{$group|escape:"url"}/catid/{$catid|escape:"url"}/{if $brand}brand/{$brand|escape}/{/if}tabAllFlg/1" class="search">
                   
                            <input type="search" name="keyword" value="{$keyword|escape}" maxlength="50" class="search_input" placeholder="なにをお探しですか？" maxlength="50">
                            <input type="submit" value="" class="search-magnifier">
                            <input type="hidden" name="kysg" value="on">
                      
                    </form>
                        </div>
                               
                            </dd>
                        </div>
                        </dl>
                    </div>
        </div>
        <div id="tabSearch2" class="sp" style="display: none;">
            <ul>
                <li>
                    <dl>
                        <dt class="menuBtn ac-arrow">商品の絞り込み</dt>
                        <dd class="menuBox">
                            <ul class="menuSub">
                            {assign var="count" value=0} 
                         
                            {section name=index loop=$dirList}
                              {math equation="x % 2" x=$count assign="remainder"}
                              {if $remainder == 0}
                                <li class="menuSub-list">
                              {/if}
                              {strip}
                                {if $dirList[index].link == ""}
                                  <a class="active">{$dirList[index].name|escape}</a>
                                {else}
                                  <a href="{wt_action_url mod='SS' act='CS'}group/{$dirList[index].group|escape:"url"}/catid/{$dirList[index].catid|escape:"url"}/tabAllFlg/1" target="_self">
                                    {$dirList[index].name|escape}
                                  </a>
                                {/if}
                              {/strip}
                              {if $remainder == 1 or $smarty.section.index.last} 
                                </li>
                              {/if}
                              {math equation="x + 1" x=$count assign="count"} 
                            {/section}
                            </ul>
                        </dd>
                    </dl>
                </li>
                <li>
                    <dl>
                        <dt class="menuBtn ac-arrow">ブランド別</dt>
                        <dd class="menuBox">
                            <ul class="menuSub">
                            {assign var="count" value=0} 
{section name=index loop=$brandList}
    {math equation="x % 2" x=$count assign="remainder"}
    {if $remainder == 0}
      <li class="menuSub-list">
    {/if}
                               {strip}
{if $brandList[index].link == ""}
                                    <a class="active">{$brandList[index].name|escape}</a>
{else}
                                    <a href="{$brandList[index].link|escape}" target="_self">
                                        {$brandList[index].name|escape}
                                    </a>
{/if}
                                {/strip}
                                {if $remainder == 1 or $smarty.section.index.last} 
                                    </li>
                                  {/if}
                                  {math equation="x + 1" x=$count assign="count"} 
{/section}
                            </ul>
                        </dd>
                    </dl>
                </li>
            </ul>
            <div class="freeWord">
        <form method="get" action="{wt_action_url mod='SS' act='CS'}group/{$group|escape:"url"}/catid/{$catid|escape:"url"}/{if $brand}brand/{$brand|escape}/{/if}tabAllFlg/1" class="search">
                   
            <input type="search" name="keyword" value="{$keyword|escape}" maxlength="50" class="search_input" placeholder="なにをお探しですか？" maxlength="50">
            <input type="submit" value="" class="search-magnifier">
            <input type="hidden" name="kysg" value="on">
      
    </form>
            </div>
        </div>
        <div class="productLists" id="shohin_list">
{include file=$smarty.const.SHOHIN_CATEGORY_TEMPLATE_DIR|cat:"cat_point_list.tpl"}
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
var current_page_no = {/literal}{$cs_page_no}{literal};
var last_page_no = {/literal}{$wt__pager_info.last}{literal};
$(function() {
    $('#read_more').click(function() {
        $('#read_more').hide();
        $('#loading_page').fadeIn();
        current_page_no++;
        if (current_page_no <= last_page_no) {
            $.ajax({
                type: 'POST',
                url: '{/literal}{wt_action_url mod="SS" act="CSList"}{literal}',
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
    {/literal}{if $cs_shohin_no}{literal}
    setTimeout(function() {
        var p = $('#sno{/literal}{$cs_shohin_no|escape}{literal}').offset().top;
        $('html, body').animate({ scrollTop: p }, 'fast');
    }, 1000);
    {/literal}{/if}{literal}
});
$(window).bind("unload",function(){});
{/literal}
</script>
{/if}
<script src="/assets/js2/dialog.js"></script>
<script>
const urlAddFavorite = '{wt_action_url mod="ShohinShosai" act="ShohinShosai"}kind/okiniiri/shohin/';
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
        $(function () {
            $(".archiveLead__serchBtn").on('click', function (event) {
                event.stopPropagation();  // Stop event from bubbling up to document
                var $menu = null;
                if ($(this).attr('id') === 'tabBtnSearch') {
                  $menu = $('#tabSearch2');
                } else {
                  $menu = $('#tabSearch');
                }
                if ($menu.is(':visible')) {
                  $menu.slideUp('normal', function () {
                    $('.menuBox').hide();
                  });
                } else {
                  $menu.slideDown();
                }
              });
              
              $(document).on('click', function (event) {
                var $target = $(event.target);
                if (!$target.is('#tabSearch') && !$target.is('.archiveLead__serch') && !$target.closest('#tabSearch').length && !$target.closest('.archiveLead__serch').length) {
                  $('#tabSearch').slideUp();
                }
              });
   
        $('.menuBtn').on('click', function () {                     
                $(this).next('.menuBox').slideToggle();
        
        });

 
    $('#shohin_list').on('click', '.add-favorite', function() {
        const $button = $(this);
        const shohinNo = $button.attr('name');
        const shohinName = $button.attr('rel');
        $.ajax({
            type: 'GET',
            url: urlAddFavorite + shohinNo,
            cache: false,
            async: false
        }).done((data, textStatus, jqXHR) => {
            if (data === '') {
                showGrowl('<b>' + shohinName + '</b><br>をお気に入りに追加しました。', () => {});
                $button.unbind();
                $button.removeClass('add-favorite');
                $('img', $button).attr('src', '/assets/image/item/icon_favorite_on.png');
            } else if (data.match('error:') !== null) {
                showAlert(data.replace('error:', ''), 'OK', () => {});
            } else {
                showAlert('システムエラーが発生した為、処理を中断しました。', 'OK', () => {});
            }
        }).fail((jqXHR, textStatus, errorThrown) => {
            console.log([jqXHR.status, textStatus, errorThrown].join(' '));
            showAlert('通信エラーが発生した為、処理を中断しました。', 'OK', () => {});
        });
    });
});
{/literal}
</script>
{/if}{* #1 *}
