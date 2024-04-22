{assign var=index value=0}
{section loop=$shohinCount name=ss}
<div class="productList" id="sno{$arrShohin[$index].no|escape}">
    <div class="productList__img">
        <a href="{$arrShohin[$index].linkToShosai|escape}{if $group}/group/{$group|escape:"url"}{/if}{if $catid}/catid/{$catid|escape:"url"}{/if}" class="productList__relative">
{tms_html_image alt="{$arrShohin[$index].displayNm|escape}" shohin_code=$arrShohin[$index].code image_type="4"}
        </a>
    </div>
    <div class="productList__content" data-stt-ignore>
        <a href="{$arrShohin[$index].linkToShosai|escape}{if $group}/group/{$group|escape:"url"}{/if}{if $catid}/catid/{$catid|escape:"url"}{/if}" class="productList__relative">
            <p class="productList__title">{$arrShohin[$index].displayNm|escape}</p>
            <p class="productList__point">{$arrShohin[$index].priceAndTax|number_format}point</p>
{if $arrShohin[$index].brandName}
            <p class="productList__cat">{$arrShohin[$index].brandName|escape}</p>
{/if}
        </a>
{if $arrShohin[$index].groupShohinFlg == true}
{foreach from=$arrShohin[$index].groupShohin item=groupShohin name=group}
        <a href="{$groupShohin.linkToShosai|escape}{if $group}/group/{$group|escape:"url"}{/if}{if $catid}/catid/{$catid|escape:"url"}{/if}" class="productList__relative">
            <p class="productList__title">{$groupShohin.M02SNAME|escape}</p>
            <p class="productList__point">{$groupShohin.M02VPOINT|number_format}point</p>
        </a>
{/foreach}
{/if}
{if isset($okiniiri_list[$arrShohin[$index]['no']])}
        <a href="javascript:void(0);" class="productList__favorite">
            <img src="/assets/image/item/icon_favorite_on.png" alt="お気に入り登録">
        </a>
{else}
        <a href="javascript:void(0);" class="productList__favorite add-favorite" rel="{$arrShohin[$index].displayNm|escape}" name="{$arrShohin[$index]['no']|escape}">
            <img src="/assets/image/item/icon_favorite.png" alt="お気に入り解除">
        </a>
{/if}
    </div>
</div>
{assign var=index value=$index+1}
{/section}
