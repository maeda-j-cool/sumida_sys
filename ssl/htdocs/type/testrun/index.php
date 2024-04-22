<?php
// charset = UTF-8
$kenshuIndex = null;
$kenshuGroup = $_POST['kg'] ?? '';
if (strlen($kenshuGroup)) {
    $kenshuIndex = sprintf('%s/%s/index.php', dirname(__DIR__, 2), $kenshuGroup);
}
$cardType = $_POST['ct'] ?? '';
if ($kenshuIndex && is_file($kenshuIndex) && $cardType) {
    define('WT_TESTRUN', true);
    define('WT_TESTRUN_DESIGN_CD', $cardType);
    define('WT_APP_PATH', '/' . $kenshuGroup);
    include($kenshuIndex);
} else {
    include_once dirname(__DIR__, 4) . '/config/wt/WtApp.php';
    // LoginAction::getKenshuListのコピー
    // ※共通化しようとも思ったけど既存ソースをできるだけ触りたくないので、、、
    //>>>
    include_once WT_ROOT_DIR . 'wt/WtFileCache.php';
    $cache = new WtFileCache(WT_CACHE_DIR, 3600 * 4);
    $cacheId = 'KenshuList';
    $kenshuList = $cache->get($cacheId);
    if (empty($kenshuList)) {
        $kenshuList = $sortList = [];
        foreach (glob(sprintf('%ssettings/*.ini.php', WT_ROOT_DIR)) as $fileName) {
            $kenshuGroup = preg_replace('|\A.*/settings/(.*)\.ini\.php\z|', '$1', $fileName);
            $setting = include($fileName);
            $index = $setting['index'] ?? 0;
            if (($index > 0) && ($index < 1000) && strlen($setting['kenshu_name'] ?? '')) {
                $kenshuList[$kenshuGroup] = $setting['kenshu_name'];
                $sortList[$kenshuGroup] = $setting['index'];
            }
        }
        array_multisort($sortList, SORT_ASC, $kenshuList);
        $cache->save($cacheId, $kenshuList);
    }
    //<<<
    $cardTypes = [
        WT_KENCD_PREGNANCY  => '妊娠：出産応援ギフト',
        WT_KENCD_CHILDBIRTH => '出産：子育て応援ギフト',
    ];
    echo '<!DOCTYPE html>';
    echo '<html class="html" lang="jp">';
    echo '<head>';
    echo '<title>テストラン（test run）</title>';
    echo '<link rel="stylesheet" href="bootstrap.css" type="text/css">';
    echo '<link rel="stylesheet" href="bootstrap-theme.css" type="text/css">';
    echo '<link rel="stylesheet" href="style.css" type="text/css">';
    echo '</head>';
    echo '<body>';
    echo '<div class="container text-center" style="padding:64px;">';
    echo '<h4 style="margin-bottom:8px;color:#333399;">中野区出産・子育て応援ギフト「中野区ファーストバースデーサポート」</h4>';
    echo '<form method="post">';
    echo '<div class="form-group form-group-md">';
    echo '<div class="col-md-offset-4 col-md-4" style="padding:8px;">';
    echo '<select name="kg" class="form-control" >';
    foreach ($kenshuList as $v => $name) {
        echo '<option value="' . $v . '">' . $name . '</option>';
    }
    echo '</select>';
    echo '</div>';
    echo '</div>';
    echo '<div class="form-group form-group-md">';
    echo '<div class="col-md-offset-4 col-md-4" style="padding:8px;">';
    echo '<select name="ct" class="form-control" >';
    foreach ($cardTypes as $v => $name) {
        echo '<option value="' . $v . '">' . $name . '</option>';
    }
    echo '</select>';
    echo '</div>';
    echo '</div>';
    echo '<div class="form-group form-group-md">';
    echo '<div class="col-md-offset-4 col-md-4" style="padding:16px;">';
    echo '<input type="submit" value="仮想ログイン" class="btn btn-lg btn-primary">';
    echo '</div>';
    echo '</div>';
    echo '</form>';
    echo '</div>';
    echo '</body>';
    echo '</html>';
}
