{*
{strip}
<div class="error_message_box"{if !isset($Errors) || empty($Errors)} style="display:none;"{/if}>
{foreach from=$Errors item=error}
    <p>{$error|escape|nl2br}</p>
{/foreach}
</div>
{/strip}
*}
{strip}
<div class="p-login__alert tableInfos-card-alert"{if !isset($Errors) || empty($Errors)} style="display:none;"{/if}>
    <div class="p-login__alert--mark"><img src="/assets/img/alert.png" alt=""></div>
    <ul class="p-login__alert--list">
{foreach from=$Errors item=error}
        <li>{$error|escape|nl2br}</li>
{/foreach}
    </ul>
    <div class="p-login__alert--damy"></div>
</div>
{/strip}
