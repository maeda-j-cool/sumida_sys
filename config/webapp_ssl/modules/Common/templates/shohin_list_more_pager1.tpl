{if isset($wt__pager_info)}
<div class="pageNav04c mb10">
{if isset($set_page_loading_id)}
<div id="{$set_page_loading_id}" style="display:none;text-align:left;color:#003366;position:absolute;">
  <img src="tssimages/loading.gif" border="0" /> ... 読み込み中 ...
</div>
{/if}
<p class="pagenav_font floatL">{$wt__pager_info.total|number_format}件中： {if $wt__pager_info.total}{$wt__pager_info.start|number_format}{else}0{/if}～{$wt__pager_info.end|number_format}件を表示しています</p>
<ul class="pageNav">
{include file=$smarty.const.WT_COMMON_TEMPLATE_DIR|cat:"sliding_more_pager1.tpl" pager_function="get_list"}
</ul>
</div>
{/if}
