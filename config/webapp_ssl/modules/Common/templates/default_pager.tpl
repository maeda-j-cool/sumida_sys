{if isset($wt__pager_info)}
<p class="pagenav_font floatL">{$wt__pager_info.total|number_format}件中：{$wt__pager_info.start|number_format}～{$wt__pager_info.end|number_format}件を表示しています</p>
<ul class="pageNav">
    {include file=$smarty.const.WT_COMMON_TEMPLATE_DIR|cat:"sliding_pager1.tpl"}
</ul>
{/if}