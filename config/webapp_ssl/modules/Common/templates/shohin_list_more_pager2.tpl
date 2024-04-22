{if isset($wt__pager_info)}
<div class="pageNav04c mtb10">
<p class="pagenav_font floatL">{$wt__pager_info.total|number_format}件中： {if $wt__pager_info.total}{$wt__pager_info.start|number_format}{else}0{/if}～{$wt__pager_info.end|number_format}件を表示しています</p>
<ul class="pageNav">
{include file=$smarty.const.WT_COMMON_TEMPLATE_DIR|cat:"sliding_more_pager1.tpl" pager_function="get_list"}
</ul>
</div>
{/if}
