<?php
/**
 * ProjectName:スマートギフトサイト
 * Subsystem:ギフトカードを利用した商品交換モールサイト
 *
 * PHP versions 5.3.1
 */

// リクエストオブジェクトに余計なパース処理をさせないようにする(無理矢理;)
$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'] = null;
$request = new WtRequest();
$user = new WtUser();
$user->load(); // 参照するだけなのでstore()は気にしないでOK

$hyojunNouki = 0;
$kisetsuDateList = null;

$curYear  = intval($request->getParameter('cy'));
$curMonth = intval($request->getParameter('cm'));
// 買い物かごセッション情報取得
$shohin = $user->getAttribute('shohin_info');
$hyojunNouki = intval($shohin['hyojun_nouki']) - 1;
if ($shohin['kisetsu_shohin_flg'] == '1') {
    $kisetsuDateList = $shohin['kisetsu_haisoudate'];
}

$tSaitanNouki = strtotime('+' . strval($hyojunNouki) . ' day');
// 指定されたカレンダー年月(YYYYMM)
$yyyymmCur = intval(sprintf('%04d%02d', $curYear, $curMonth));
// 最短納期 (カレンダー表示可能年月：MIN)
$yyyymmMin = intval(date('Ym', $tSaitanNouki));
// 当月の1年後 (カレンダー表示可能年月：MAX)
$yyyymmMax = intval(date('Ym', strtotime('+1 year')));
if (($yyyymmCur < $yyyymmMin) || ($yyyymmCur > $yyyymmMax)) {
    // 指定されたカレンダー年月が表示可能範囲外の場合は最短納期の年月に変更する
    $curYear  = intval(substr(strval($yyyymmMin), 0, 4));
    $curMonth = intval(substr(strval($yyyymmMin), 4, 2));
    $yyyymmCur = $yyyymmMin;
}
// リンク有無をここで判定しておく
$hasPrevLink = ($yyyymmCur != $yyyymmMin);
$hasNextLink = ($yyyymmCur != $yyyymmMax);

// 指定月と翌月のカレンダー配列を取得
$y1 = $curYear;
$m1 = $curMonth;
$y2 = $curYear;
$m2 = $curMonth + 1;
if ($m2 > 12) {
    ++$y2;
    $m2 = 1;
}
$calData1 = createCalendarArray($y1, $m1);
$calData2 = createCalendarArray($y2, $m2);
// 配送可能日のリストを作成
// ※キーにYYYY/MM/DD、値は配送可能日の場合にtrue、不可の場合はfalse、を設定
// 季節配送設定がある場合には全部false
$haisoDateList  = array();
$hasKisetsuDate = is_array($kisetsuDateList);
$loop1 = intval(date('t', mktime(0, 0, 0, $m1, 1, $y1)));
$loop2 = intval(date('t', mktime(0, 0, 0, $m2, 1, $y2)));
$loop  = max($loop1, $loop2); // 最大3件の無駄なデータ(月末日の差分)が作成されるが気にしない！
for ($i = 1; $i <= $loop; $i++) {
    // 季節配送設定がある場合には全てfalseを設定する
    // ※後続のロジックで配送可能な日だけtrueに上書きする
    $haisoDateList[sprintf('%04d-%02d-%02d', $y1, $m1, $i)] = !$hasKisetsuDate;
    $haisoDateList[sprintf('%04d-%02d-%02d', $y2, $m2, $i)] = !$hasKisetsuDate;
}
$yyyymm1 = intval(sprintf('%04d%02d', $y1, $m1));
$yyyymm2 = intval(sprintf('%04d%02d', $y2, $m2));
if ($hasKisetsuDate) {
    foreach ($kisetsuDateList as $info) {
        $t1 = strtotime($info['haisosdate']); // 配送可能日(From)
        $t2 = strtotime($info['haisoedate']); // 配送可能日(To)
        if ((intval(date('Ym', $t1)) <= $yyyymm2) && (intval(date('Ym', $t2)) >= $yyyymm1)) {
            for ($t = $t1; $t <= $t2; $t += 86400) {
                $k = date('Y-m-d', $t);
                if (isset($haisoDateList[$k])) {
                    $haisoDateList[$k] = true;
                }
            }
        }
    }
}

// お届け停止日
if ($shohin['haisositei_nolimit_flg'] == '0') { // お届け日制御の除外商品ではない場合のみ制御する
    foreach ($shohin['cannot_delivery_date'] as $stopDay) {
        $k = date('Y-m-d', strtotime($stopDay));
        if (isset($haisoDateList[$k])) {
            $haisoDateList[$k] = false;
        }
    }
}
// 最短納期以前の日付を配送不可に設定(最後に行う)
foreach (array_keys($haisoDateList) as $ymd) {
    if (strtotime($ymd) < $tSaitanNouki) {
        $haisoDateList[$ymd] = false;
    }
}
// 出力用HTMLの生成
$html1 = createCalendarHtml($calData1, $y1, $m1, $haisoDateList, 'cal-pack1');

$tPrev = mktime(0, 0, 0, $m1 - 1, 1, $y1);
$tNext = mktime(0, 0, 0, $m1 + 1, 1, $y1);
// ナビゲーションリンク
$navHtml = '<ul class="cal-nav">';
if ($hasPrevLink) {
    $onClick = sprintf(' onClick="calendar.init(%s, %s, 1)"',
                       date('Y', $tPrev), date('n', $tPrev));
    $navHtml .= '<li class="cal-prev"><p' . $onClick. '>前月へ</p></li>';
}
if ($hasNextLink) {
    $onClick = sprintf(' onClick="calendar.init(%s, %s, 1)"',
                       date('Y', $tNext), date('n', $tNext));
    $navHtml .= '<li class="cal-next"><p' . $onClick. '>次月へ</p></li>';
}
$navHtml .= '</ul>';
$html = '<div class="calendarWidget" style="background-color:#fff;">' . $html1 . $navHtml . '</div>';

echo $html;
// End of Script



/**
 * 出力用HTMLの生成
 *
 */
function createCalendarHtml($calData, $y, $m, $haisoDateList, $divClassName)
{
    $html = '<div class="' . $divClassName . '">'
          .     '<div class="cal-heading">' . sprintf('%d年%02d月', $y, $m) . '</div>'
          .     '<div class="cal-table">'
          .         '<table cellpadding="0" cellspacing="0" border="0">'
          .             '<tbody>'
          .                 '<!--<tr>'
          .                     '<th>月</th>'
          .                     '<th>火</th>'
          .                     '<th>水</th>'
          .                     '<th>木</th>'
          .                     '<th>金</th>'
          .                     '<th class="cal-d1">土</th>'
          .                     '<th class="cal-d2 last-child">日</th>'
          .                 '</tr>-->';
    foreach ($calData as $weekData) {
        $html .= '<tr>';
        foreach ($weekData as $dow => $day) {
            $k = sprintf('%04d-%02d-%02d', $y, $m, intval($day));
            $tdClass = '';
            $onClickFunction = '';
            if (!isset($haisoDateList[$k]) || !$haisoDateList[$k]) {
                $tdClass = ' class="stopday"';
                $spClass = ' class="cal-err"';
            } else {
                $onClickFunction = ' onClick="' . sprintf('calendar.setDate(%s, %s, %s)', $y, $m, $day) . '"';
                $spClass = ' class="haiso_date"';
                //#if (祝日) {
                //#    $tdClass = ' class="holiday"';
                //#}
                if ($dow == 5) {
                    $tdClass = ' class="cal-d1"';
                } else if ($dow == 6) {
                    $tdClass = ' class="cal-d2 last-child"';
                }
            }
            $tdData = '<td' . $tdClass . '>'
                    .     '<span' . $spClass . $onClickFunction . '>' . strval($day) . '</span>'
                    . '</td>';
            $html .= $tdData;
        }
        $html .= '</tr>';
    }
    $html .= '</tbody></table></div></div>';
    return $html;
}

/**
 * カレンダー情報配列の生成
 *
 */
function createCalendarArray($calYear, $calMonth) {
    // 対象となる月の初日
    $t = mktime(0, 0, 0, intval($calMonth), 1, intval($calYear));
    // 対象となる月の日数
    $lastDay = intval(date('t', $t));
    // 1(月曜日)から7(日曜日)で取得した曜日を0から6にする
    $dow = intval(date('N', $t)) - 1;
    $week = $weeks = array();
    for ($d = 1; $d <= $lastDay; $d++, $dow++) {
        $dow %= 7;
        if (($d == 1) && ($dow > 0)) {
            // 1日が月曜日以外の場合はブランク挿入
            $week = array_pad(array(), $dow, '');
        }
        $week[$dow] = $d;
        if ($dow == 6) {
            $weeks[] = $week;
            $week = array();
        }
    }
    if (count($week)) {
        // 未保存の週データがある場合は後方にブランクを挿入して保存
        $weeks[] = array_pad($week, 7, '');
    }
    return $weeks;
}
