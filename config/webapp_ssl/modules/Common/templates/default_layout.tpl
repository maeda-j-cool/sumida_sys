{if !isset($contentsTemplate)}{assign var="contentsTemplate" value=""}{/if}
{if !isset($currmod)}{assign var="currmod" value=$controller->getCurrentModule()}{/if}
{capture name="body"}{if $contentsTemplate}{include file="$contentsTemplate"}{/if}{/capture}
<!DOCTYPE html>
<html class="html" lang="ja">
<head>
<script>
window.dataLayer = window.dataLayer || [];
dataLayer.push({
    customer_area: "{$gtm_customer_area|escape}",
    customer_status: "{$gtm_customer_status|escape}"
})
{if isset($smarty.capture.gtm_layer) && $smarty.capture.gtm_layer ne ''}
{$smarty.capture.gtm_layer}
{/if}
</script>
{include file="gtm_head.tpl"}
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta property="og:image" content="/assets/img/ogp.jpg">

<link rel="icon" href="/assets/img/MacOSX/icon_32x32.png">
<link rel="icon" href="/assets/img/Windows8/icon-50.png">
<link rel="icon" href="/assets/img/WindowsXP/tmp/icon-32.ico">
<link rel="icon" href="/assets/img/favicon/tmp/icon-48.ico">
<link rel="apple-touch-icon" sizes="36x36" href="/assets/img/Android/drawable_ldpi/ic_launcher.png">
<link rel="apple-touch-icon" sizes="29x29" href="/assets/img/iOS/Icon-Small.png">
<link rel="apple-touch-icon" sizes="29x29" href="/assets/img/iOS7/Icon-Small.png">
<link rel="apple-touch-icon" sizes="62x62" href="/assets/img/WindowsPhone/for_application/ApplicationIcon.png">
<link rel="apple-touch-icon" sizes="99x99" href="/assets/img/WindowsPhone/for_marketplace/AppName-99.png">
<link rel="apple-touch-icon" sizes="60x60" href="/assets/img/apple-touch-icons/apple-touch-icon-60x60.png">
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link rel="stylesheet" href="/assets/css/reset.css">
<link href="/assets/font/Inter,Zen_Maru_Gothic/Zen_Maru_Gothic/ZenMaruGothic-Bold.ttf" rel="preconnect">
<link href="/assets/font/Inter,Zen_Maru_Gothic/Zen_Maru_Gothic/ZenMaruGothic-Black.ttf" rel="preconnect">
<link href="/assets/font/Inter,Zen_Maru_Gothic/Zen_Maru_Gothic/ZenMaruGothic-Light.ttf" rel="preconnect">
<link href="/assets/font/Inter,Zen_Maru_Gothic/Zen_Maru_Gothic/ZenMaruGothic-Medium.ttf" rel="preconnect">
<link href="/assets/font/Inter,Zen_Maru_Gothic/Zen_Maru_Gothic/ZenMaruGothic-Regular.ttf" rel="preconnect">
{if $wt__is_login || $is_virtual}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
<link rel="stylesheet" href="/assets/css/common.css">
<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/assets/css/top-login.css?20250116-001">
<link rel="stylesheet" href="/assets/css/common-2.css">
<link rel="stylesheet" href="/assets/css/contents.css">
<link rel="stylesheet" href="/assets/css/layout.css?20231006-001">
{else}
<link rel="stylesheet" href="/assets/css/common.css">
<link rel="stylesheet" href="/assets/css/common-2.css">
<link rel="stylesheet" href="/assets/css/layout.css?20231006-001">
<link rel="stylesheet" href="/assets/css/style.css">
{/if}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://d.shutto-translation.com/trans.js?id=1668"></script>
{if isset($smarty.capture.header) && $smarty.capture.header ne ''}
{$smarty.capture.header}
{/if} 
<title>{if $title}{$title|escape} | {/if}{$smarty.const.HTML_TITLE_SUFFIX|escape}</title>
</head>
<body id="body">
{include file="gtm_body.tpl"}

{$smarty.capture.body}

{if isset($smarty.capture.js_top) && $smarty.capture.js_top ne ''}
{$smarty.capture.js_top}
{/if}
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="/assets/js/swiper.js"></script>
<script src="/assets/js/common.js"></script>
<script src="/assets/js/text-size.js"></script>
{if isset($smarty.capture.js) && $smarty.capture.js ne ''}
{$smarty.capture.js}
{/if}
</body>
</html>
