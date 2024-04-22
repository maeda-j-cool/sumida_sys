{if isset($wt__pager_info)}
{*---------------------------------------------*}
{* 設定値 (現在ページの前後に表示するページ数) *}
{assign var="slide_delta" value=2               }
{*---------------------------------------------*}
{* strip *}
{* --- #CALC --- *}
{assign var="slide_offset"    value=$slide_delta*2                   }
{assign var="slide_max_page"  value=$slide_offset+1                  }
{assign var="slide_index_min" value=$wt__pager_info.curr-$slide_delta}
{assign var="slide_index_max" value=$wt__pager_info.curr+$slide_delta}
{if $slide_index_min < 1}
    {assign var="slide_index_min" value=1}
    {assign var="slide_index_max" value=$slide_max_page}
{/if}
{if $wt__pager_info.last && ($wt__pager_info.last < $slide_index_max)}
    {assign var="slide_index_max" value=$wt__pager_info.last}
{else if !$wt__pager_info.next}
    {assign var="slide_index_max" value=$wt__pager_info.curr}
{/if}
{if $slide_index_min > ($slide_index_max - $slide_offset)}
    {if ($slide_index_max - $slide_offset) < 1}
        {assign var="slide_index_min" value=1}
    {else}
        {assign var="slide_index_min" value=$slide_index_max-$slide_offset}
    {/if}
{/if}
{if $slide_index_min > $slide_index_max}
    {assign var="slide_index_max" value=$slide_index_min}
{/if}
{* --- #LEFT --- *}
{if $wt__pager_info.prev}
  <a href="{$wt__pager_info.base_url}{$wt__pager_info.page_query}/1">[最初の{$wt__pager_info.rpp|number_format}件]</a>&nbsp;
  <li class="pre"><a href="javascript:void(0);" onclick="{$pager_function}({$wt__pager_info.prev});">前へ</a></li>
{/if}
{* --- #CENTER --- $slide_index_min ～ $slide_index_max *}
{section name=i start=$slide_index_min loop=$slide_index_max+1}
    {if $smarty.section.i.index == $wt__pager_info.curr}
        <li class="navi_visi"><a href="javascript:void(0);" onclick="{$pager_function}({$smarty.section.i.index});">{$smarty.section.i.index}</a></li>
    {else}
        <li><a href="javascript:void(0);" onclick="{$pager_function}({$smarty.section.i.index});">{$smarty.section.i.index}</a></li>
    {/if}
{/section}
{* --- #RIGHT --- *}
{if $wt__pager_info.next}
  <li class="next"><a href="javascript:void(0);" onclick="{$pager_function}({$wt__pager_info.next});">次へ</a></li>
  <a href="{$wt__pager_info.base_url}{$wt__pager_info.page_query}/{$wt__pager_info.last}">[最後の{$wt__pager_info.rem|number_format}件]</a>&nbsp;
{/if}
{* /strip *}
{/if}
