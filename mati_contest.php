<?php
// このファイルは mati_contest.cgi (Perl) から自動変換されたものです。
// 町CGI  Copyright (c) 2003-2004 brassiere  http://brassiere.jp/

require_once __DIR__ . '/lib/bootstrap.php';
$this_script = 'mati_contest.php';
require_once __DIR__ . '/town_ini.php';
require_once __DIR__ . '/town_lib.php';
decode();
// メンテチェック
if ((((n($mente_flag) == 1) && ($in['admin_pass'] == '')) && ($in['mode'] != ''))) {
    error((string)($mente_message));
}
$seigenyou_now_time = pl_time();
// 制限時間チェック
$ato_nanbyou = (n($koudou_seigen) - ((n($seigenyou_now_time) - n($access_byou))));
if (((n($seigenyou_now_time) - n($access_byou)) < n($koudou_seigen))) {
    error("まだ行動できません。あと{$ato_nanbyou}秒お待ちください。");
}
// 条件分岐
if (($in['mode'] == 'matikon')) {
    matikon();
} elseif (($in['mode'] == 'mati_kouken')) {
    mati_kouken();
} else {
    error('「戻る」ボタンで街に戻ってください');
}
exit;
// ############以下サブルーチン
// ##街の貢献処理
// 日付変更処理（昨日時点の数値を移動）
// 住んでいる街のチェックサブルーチン
// 最終ランキング処理
// 優勝の住人への賞金配布

function bybunkaKeys(...$_args)
{
    global $a, $b, $bunkaKeys;
    return (n($bunkaKeys[n($b)]) <=> n($bunkaKeys[n($a)]));
}

function bysportsKeys(...$_args)
{
    global $a, $b, $sportsKeys;
    return (n($sportsKeys[n($b)]) <=> n($sportsKeys[n($a)]));
}

function byninjouKeys(...$_args)
{
    global $a, $b, $ninjouKeys;
    return (n($ninjouKeys[n($b)]) <=> n($ninjouKeys[n($a)]));
}

function byyuuhukuKeys(...$_args)
{
    global $a, $b, $yuuhukuKeys;
    return (n($yuuhukuKeys[n($b)]) <=> n($yuuhukuKeys[n($a)]));
}

function bybunka_zKeys(...$_args)
{
    global $a, $b, $bunka_zKeys;
    return (n($bunka_zKeys[n($b)]) <=> n($bunka_zKeys[n($a)]));
}

function bysports_zKeys(...$_args)
{
    global $a, $b, $sports_zKeys;
    return (n($sports_zKeys[n($b)]) <=> n($sports_zKeys[n($a)]));
}

function byninjou_zKeys(...$_args)
{
    global $a, $b, $ninjou_zKeys;
    return (n($ninjou_zKeys[n($b)]) <=> n($ninjou_zKeys[n($a)]));
}

function byyuuhuku_zKeys(...$_args)
{
    global $a, $b, $yuuhuku_zKeys;
    return (n($yuuhuku_zKeys[n($b)]) <=> n($yuuhuku_zKeys[n($a)]));
}

function bykourouKeys(...$_args)
{
    global $a, $b, $kourouKeys;
    return (n($kourouKeys[n($b)]) <=> n($kourouKeys[n($a)]));
}

function bykourou_zKeys(...$_args)
{
    global $a, $b, $kourou_zKeys;
    return (n($kourou_zKeys[n($b)]) <=> n($kourou_zKeys[n($a)]));
}

function matikon(...$_args)
{
    global $_, $bunkaKeys, $bunka_key, $bunka_rank, $bunka_zKeys, $bunka_z_key, $bunka_z_rank, $dainankai_con, $data,
        $date_sec, $i, $img_dir, $in, $k_yobi2, $k_yobi3, $k_yobi4, $k_yobi5, $kizon_mati, $korourank_alldata,
        $kourouKeys, $kourou_alldata, $kourou_bunka, $kourou_bunka_z, $kourou_key, $kourou_name, $kourou_ninjou,
        $kourou_ninjou_z, $kourou_num, $kourou_rank, $kourou_sports, $kourou_sports_z, $kourou_total, $kourou_yobi1,
        $kourou_yobi2, $kourou_yobi3, $kourou_yobi4, $kourou_yobi5, $kourou_yuuhuku, $kourou_yuuhuku_z,
        $kourou_zKeys, $kourou_z_key, $kourou_z_rank, $kourousya_logfile, $mat_bunka, $mat_bunka_y, $mat_bunka_z,
        $mat_ninjou, $mat_ninjou_y, $mat_ninjou_z, $mat_num, $mat_sports, $mat_sports_y, $mat_sports_z, $mat_yobi1,
        $mat_yuuhuku, $mat_yuuhuku_y, $mat_yuuhuku_z, $mati_alldata, $mati_con_nissuu, $mati_con_syoukin,
        $maticon_logfile, $matikon_settei, $matirank_alldata, $name, $nanniti_hyouki, $nannitime_con,
        $nannitime_hozon, $new_matikon_data, $ninjouKeys, $ninjou_key, $ninjou_rank, $ninjou_zKeys, $ninjou_z_key,
        $ninjou_z_rank, $rank_hiduke, $return_my_town, $saisyuu_flag, $sportsKeys, $sports_key, $sports_rank,
        $sports_zKeys, $sports_z_key, $sports_z_rank, $start_con_time, $this_script, $title, $town_hairetu,
        $tuika_flag, $yuuhukuKeys, $yuuhuku_key, $yuuhuku_rank, $yuuhuku_zKeys, $yuuhuku_z_key, $yuuhuku_z_rank,
        $zenkai_taikai;
    if (!(pl_open('MA', (string)($maticon_logfile)))) { error("{$maticon_logfile}が開けません"); }
    $matikon_settei = pl_readline('MA');
    $mati_alldata = pl_readlines('MA');
    pl_close('MA');
    time_get(...$_args);
    // 開始時のログ初期化
    if (($matikon_settei == '')) {
        lock(...$_args);
        $i = 0;
        $new_matikon_data = [];
        $__s1 = $_ ?? null;
        foreach ($town_hairetu ?? [] as $_) {
            $mati_kon_temp = "{$i}<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>\n";
            $new_matikon_data[] = $mati_kon_temp;
            $i++;
        }
        $_ = $__s1;
        $mati_alldata = $new_matikon_data;
        $matikon_settei = "{$date_sec}<>1<>1<>\n";
        pl_unshift($new_matikon_data, $matikon_settei);
        if (!(pl_open('OUT', ">{$maticon_logfile}"))) { error("Open Error : {$maticon_logfile}"); }
        pl_print('OUT', pl_join('', $new_matikon_data));
        pl_close('OUT');
        unlock(...$_args);
    }
    // 新しく街を追加した場合、街データに追加
    $i = 0;
    $tuika_flag = 0;
    $__s2 = $_ ?? null;
    foreach ($town_hairetu ?? [] as $_) {
        $new_mati_hueta = 0;
        foreach ($mati_alldata ?? [] as $kizon_mati) {
            $mat_num = pl_idx(pl_split('/<>/u', $kizon_mati), 0);
            if (((string)$mat_num === (string)$i)) {
                $new_mati_hueta = 1;
            }
        }
        if ((n($new_mati_hueta) == 0)) {
            // 街データに無い街番号（街配列が見つかった場合）
            $mati_kon_temp = "{$i}<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>\n";
            $mati_alldata[] = $mati_kon_temp;
            $tuika_flag = 1;
        }
        $i++;
    }
    $_ = $__s2;
    if ((n($tuika_flag) == 1)) {
        lock(...$_args);
        pl_unshift($mati_alldata, $matikon_settei);
        if (!(pl_open('OUT', ">{$maticon_logfile}"))) { error("Open Error : {$maticon_logfile}"); }
        pl_print('OUT', pl_join('', $mati_alldata));
        pl_close('OUT');
        unlock(...$_args);
        pl_shift($mati_alldata);
    }
    // 設定行読み込み、何大会何日目設定
    $saisyuu_flag = 0;
    [$start_con_time, $nannitime_hozon, $dainankai_con, $rank_hiduke] = pl_pad(pl_split('/<>/u', $matikon_settei), 4);
    $nannitime_con = (pl_int((((n($date_sec) - n($start_con_time))) / (((60 * 60) * 24)))) + 1);
    if ((((string)$nannitime_con !== (string)(string)($nannitime_hozon)) && (n($nannitime_con) <= n($mati_con_nissuu)))) {
        matikon_changeDay(...$_args);
    }
    if ((n($nannitime_con) > n($mati_con_nissuu))) {
        matikon_saisyuu(...$_args);
    }
    if ((n($nannitime_con) == n($mati_con_nissuu))) {
        $nanniti_hyouki = '最終日';
    } else {
        $nanniti_hyouki = "{$nannitime_con}日目";
    }
    // ランキングソート
    $__s3 = $_ ?? null;
    foreach ($mati_alldata ?? [] as $_) {
        // ($mat_num,$mat_bunka,$mat_sports,$mat_ninjou,$mat_yuuhuku,$mat_bunka_y,$mat_sports_y,$mat_ninjou_y,$mat_yuuhuku_y,$mat_bunka_z,$mat_sports_z,$mat_ninjou_z,$mat_yuuhuku_z,$mat_yobi1,$mat_yobi2,$mat_yobi3,$mat_yobi4,$mat_yobi5)= split(/<>/);
        $data = $_;
        $bunka_key = pl_idx(pl_split('/<>/u', $data), 5);
        $sports_key = pl_idx(pl_split('/<>/u', $data), 6);
        $ninjou_key = pl_idx(pl_split('/<>/u', $data), 7);
        $yuuhuku_key = pl_idx(pl_split('/<>/u', $data), 8);
        $bunka_z_key = pl_idx(pl_split('/<>/u', $data), 9);
        $sports_z_key = pl_idx(pl_split('/<>/u', $data), 10);
        $ninjou_z_key = pl_idx(pl_split('/<>/u', $data), 11);
        $yuuhuku_z_key = pl_idx(pl_split('/<>/u', $data), 12);
        $matirank_alldata[] = $data;
        $bunkaKeys[] = $bunka_key;
        $sportsKeys[] = $sports_key;
        $ninjouKeys[] = $ninjou_key;
        $yuuhukuKeys[] = $yuuhuku_key;
        $bunka_zKeys[] = $bunka_z_key;
        $sports_zKeys[] = $sports_z_key;
        $ninjou_zKeys[] = $ninjou_z_key;
        $yuuhuku_zKeys[] = $yuuhuku_z_key;
    }
    $_ = $__s3;
    $bunka_rank = pl_slice($matirank_alldata, pl_sort('bybunkaKeys', pl_range(0, (pl_count($matirank_alldata) - 1))));
    $sports_rank = pl_slice($matirank_alldata, pl_sort('bysportsKeys', pl_range(0, (pl_count($matirank_alldata) - 1))));
    $ninjou_rank = pl_slice($matirank_alldata, pl_sort('byninjouKeys', pl_range(0, (pl_count($matirank_alldata) - 1))));
    $yuuhuku_rank = pl_slice($matirank_alldata, pl_sort('byyuuhukuKeys', pl_range(0, (pl_count($matirank_alldata) - 1))));
    $bunka_z_rank = pl_slice($matirank_alldata, pl_sort('bybunka_zKeys', pl_range(0, (pl_count($matirank_alldata) - 1))));
    $sports_z_rank = pl_slice($matirank_alldata, pl_sort('bysports_zKeys', pl_range(0, (pl_count($matirank_alldata) - 1))));
    $ninjou_z_rank = pl_slice($matirank_alldata, pl_sort('byninjou_zKeys', pl_range(0, (pl_count($matirank_alldata) - 1))));
    $yuuhuku_z_rank = pl_slice($matirank_alldata, pl_sort('byyuuhuku_zKeys', pl_range(0, (pl_count($matirank_alldata) - 1))));
    t_header('gym_style');
    echo <<<EOM
    	<table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr>
    	<td bgcolor="#ffffff">{$title}が運営する「街コンテスト」です。自分の持っているパラーメータ値やお金を自分の住んでいる街のために使うことで、「文化度」「スポーツ振興度」「人気度」「裕福度」の各街パラメーターが上がります。<br>前日までの街パラメーターの多さによって翌日、順位が確定されます。<br>
    	{$mati_con_nissuu}日間が過ぎた時点で最終的な順位が決定し、それぞれのランキングごとに優勝賞金が街に与えられます（４つのランキングすべてで優勝すれば下記賞金×４となります）。それを住民で山分けした額が普通口座に振り込まれます。<br>街の名誉を賭けて頑張りましょう。<br>
    	※各ランキングの優勝賞金：{$mati_con_syoukin}万円<br>
    	※自分の家を持っていない方はこのコンテストに参加することはできません。パラメーターをあげられる間隔は１時間です。</td>
    	<td  bgcolor="#333333" align="center" width="35%"><img src="{$img_dir}/matikon_tytle.gif"><br>
    	<div style="color:ffffff; font-size:14px;">第{$dainankai_con}回大会</div>
    	<div style="color:ffff66; font-size:13px;">{$nanniti_hyouki}</div>
    	</td>
    	</tr></table><br>
    
    	<table width="90%" border="0" cellspacing="0" cellpadding="8" align="center" class="yosumi">
    	<tr><td colspan="4">
    	<div class="tyuu">現在のランキング（{$rank_hiduke}集計）</div>
    	</td></tr>
    	<tr align="center"><td>
    	<table border="0" cellspacing="1" cellpadding="3" ><tr><td colspan="3">■文化度</td></tr>
    	<tr class="jouge" bgcolor="#ffff66" align="center"><td></td><td>街　名</td><td>ポイント</td></tr>
    EOM;
    $i = 1;
    $__s4 = $_ ?? null;
    foreach ($bunka_rank ?? [] as $_) {
        [$mat_num, $mat_bunka, $mat_sports, $mat_ninjou, $mat_yuuhuku, $mat_bunka_y, $mat_sports_y, $mat_ninjou_y, $mat_yuuhuku_y, $mat_bunka_z, $mat_sports_z, $mat_ninjou_z, $mat_yuuhuku_z, $mat_yobi1, $k_yobi2, $k_yobi3, $k_yobi4, $k_yobi5] = pl_pad(pl_split('/<>/u', $_), 18);
        echo "<tr><td>{$i}</td><td>{$town_hairetu[n($mat_num)]}</td><td align=\"right\">{$mat_bunka_y}</td></tr>";
        $i++;
    }
    $_ = $__s4;
    echo <<<EOM
    	</table>
    	</td><td>
    	<table border="0" cellspacing="1" cellpadding="3" ><tr><td colspan="3">■スポーツ振興度</td></tr>
    	<tr class="jouge"  bgcolor="#ffff66" align="center"><td></td><td>街　名</td><td>ポイント</td></tr>
    EOM;
    $i = 1;
    $__s5 = $_ ?? null;
    foreach ($sports_rank ?? [] as $_) {
        [$mat_num, $mat_bunka, $mat_sports, $mat_ninjou, $mat_yuuhuku, $mat_bunka_y, $mat_sports_y, $mat_ninjou_y, $mat_yuuhuku_y, $mat_bunka_z, $mat_sports_z, $mat_ninjou_z, $mat_yuuhuku_z, $mat_yobi1, $k_yobi2, $k_yobi3, $k_yobi4, $k_yobi5] = pl_pad(pl_split('/<>/u', $_), 18);
        echo "<tr><td>{$i}</td><td>{$town_hairetu[n($mat_num)]}</td><td align=\"right\">{$mat_sports_y}</td></tr>";
        $i++;
    }
    $_ = $__s5;
    echo <<<EOM
    	</table>
    	</td><td>
    	<table border="0" cellspacing="1" cellpadding="3" ><tr><td colspan="3">■人気度</td></tr>
    	<tr class="jouge" bgcolor="#ffff66" align="center"><td></td><td>街　名</td><td>ポイント</td></tr>
    EOM;
    $i = 1;
    $__s6 = $_ ?? null;
    foreach ($ninjou_rank ?? [] as $_) {
        [$mat_num, $mat_bunka, $mat_sports, $mat_ninjou, $mat_yuuhuku, $mat_bunka_y, $mat_sports_y, $mat_ninjou_y, $mat_yuuhuku_y, $mat_bunka_z, $mat_sports_z, $mat_ninjou_z, $mat_yuuhuku_z, $mat_yobi1, $k_yobi2, $k_yobi3, $k_yobi4, $k_yobi5] = pl_pad(pl_split('/<>/u', $_), 18);
        echo "<tr><td>{$i}</td><td>{$town_hairetu[n($mat_num)]}</td><td align=\"right\">{$mat_ninjou_y}</td></tr>";
        $i++;
    }
    $_ = $__s6;
    echo <<<EOM
    	</table>
    	</td><td>
    	<table border="0" cellspacing="1" cellpadding="3" ><tr><td colspan="3">■裕福度</td></tr>
    	<tr class="jouge" bgcolor="#ffff66" align="center"><td></td><td>街　名</td><td>寄付金額</td></tr>
    EOM;
    $i = 1;
    $__s7 = $_ ?? null;
    foreach ($yuuhuku_rank ?? [] as $_) {
        [$mat_num, $mat_bunka, $mat_sports, $mat_ninjou, $mat_yuuhuku, $mat_bunka_y, $mat_sports_y, $mat_ninjou_y, $mat_yuuhuku_y, $mat_bunka_z, $mat_sports_z, $mat_ninjou_z, $mat_yuuhuku_z, $mat_yobi1, $k_yobi2, $k_yobi3, $k_yobi4, $k_yobi5] = pl_pad(pl_split('/<>/u', $_), 18);
        echo "<tr><td>{$i}</td><td>{$town_hairetu[n($mat_num)]}</td><td align=\"right\">{$mat_yuuhuku_y}円</td></tr>";
        $i++;
    }
    $_ = $__s7;
    echo <<<EOM
    	</table>
    	</td></tr></table><br><br>
    EOM;
    // 功労者ランキングの出力
    echo <<<EOM
    	<table width="90%" border="0" cellspacing="1" cellpadding="3" align="center" class="yosumi">
    	<tr><td colspan="5">
    	<div class="tyuu">現在の功労者ランキングベスト5</div>
    	※「貢献度」は各貢献ポイントの合計です。寄付金は１万１ポイントで計算。
    	</td></tr>
    	<tr class="jouge" bgcolor="#ffcc99" align="center"><td></td><td>名前</td><td>貢献度</td><td>文化貢献</td><td>スポーツ貢献</td><td>人気貢献</td><td>寄付金</td></tr>
    EOM;
    if (!(pl_open('KOR', (string)($kourousya_logfile)))) { error("{$kourousya_logfile}が開けません"); }
    $kourou_alldata = pl_readlines('KOR');
    pl_close('KOR');
    $__s8 = $_ ?? null;
    foreach ($kourou_alldata ?? [] as $_) {
        $data = $_;
        $kourou_key = pl_idx(pl_split('/<>/u', $data), 2);
        $kourou_z_key = pl_idx(pl_split('/<>/u', $data), 12);
        $korourank_alldata[] = $data;
        $kourouKeys[] = $kourou_key;
        $kourou_zKeys[] = $kourou_z_key;
    }
    $_ = $__s8;
    $kourou_rank = pl_slice($korourank_alldata, pl_sort('bykourouKeys', pl_range(0, (pl_count($korourank_alldata) - 1))));
    $kourou_z_rank = pl_slice($korourank_alldata, pl_sort('bykourou_zKeys', pl_range(0, (pl_count($korourank_alldata) - 1))));
    $i = 1;
    $__s9 = $_ ?? null;
    foreach ($kourou_rank ?? [] as $_) {
        [$kourou_num, $kourou_name, $kourou_total, $kourou_bunka, $kourou_sports, $kourou_ninjou, $kourou_yuuhuku, $kourou_bunka_z, $kourou_sports_z, $kourou_ninjou_z, $kourou_yuuhuku_z, $kourou_yobi1, $kourou_yobi2, $kourou_yobi3, $kourou_yobi4, $kourou_yobi5] = pl_pad(pl_split('/<>/u', $_), 16);
        // kourou_yobi1 = 最終貢献時間　kourou_yobi2＝kourou_total_z（前回の功労ポイントトータル）kourou_yobi3 = 累積功労トータル
        echo "<tr align=\"right\"><td>{$i}位</td><td align=\"left\">{$kourou_name}</td><td>{$kourou_total}ポイント</td><td>{$kourou_bunka}</td><td>{$kourou_sports}</td><td>{$kourou_ninjou}</td><td>{$kourou_yuuhuku}円</td></tr>";
        if ((n($i) >= 5)) {
            break;
        }
        $i++;
    }
    $_ = $__s9;
    echo '</table><br><br>';
    my_town_check($name);
    // 家を持っている人用の出力
    if (($return_my_town != 'no_town')) {
        echo <<<EOM
        	<table width="90%" border="0" cellspacing="0" cellpadding="8" align="center" class="yosumi">
        	<tr><td colspan="2">
        	<div class="tyuu">自分の街（{$town_hairetu[n($return_my_town)]}）へ貢献する</div>
        	※１回の貢献では、パラメーター、あるいはお金、どちらかひとつだけをあげることができます。間隔は1時間です。
        	</td></tr>
        	<tr><td width="50%">
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="mati_kouken">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<input type="hidden" name="sunderu_mati" value="{$return_my_town}">
        	●パラメーターアップ<br>
        	<select name="nouryoku">
        	<option value="">----文化----</option>
        	<option value="kokugo">国語</option>
        	<option value="suugaku">数学</option>
        	<option value="rika">理科</option>
        	<option value="syakai">社会</option>
        	<option value="eigo">英語</option>
        	<option value="ongaku">音楽</option>
        	<option value="bijutu">美術</option>
        	<option value="">----スポーツ振興----</option>
        	<option value="tairyoku">体力</option>
        	<option value="kenkou">健康</option>
        	<option value="speed">スピード</option>
        	<option value="power">パワー</option>
        	<option value="wanryoku">腕力</option>
        	<option value="kyakuryoku">脚力</option>
        	<option value="">----人気----</option>
        	<option value="looks">ルックス</option>
        	<option value="love">LOVE</option>
        	<option value="unique">面白さ</option>
        	<option value="etti">エッチ</option>
        	</select>
        	<select name="suuti">
        	<option value="5">5ポイント</option>
        	<option value="10">10ポイント</option>
        	<option value="15">15ポイント</option>
        	<option value="20">20ポイント</option>
        	<option value="25">25ポイント</option>
        	<option value="30">30ポイント</option>
        	<option value="40">40ポイント</option>
        	<option value="50">50ポイント</option>
        	</select>
        	<input type="submit" value=" O K ">
        	</form>
        	</td><td>
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="mati_kouken">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<input type="hidden" name="sunderu_mati" value="{$return_my_town}">
        	●裕福度アップ<br>
        	<select name="okane">
        	<option value="">金額の選択</option>
        	<option value="1000">1000円</option>
        	<option value="5000">5000円</option>
        	<option value="10000">1万円</option>
        	<option value="30000">3万円</option>
        	<option value="50000">5万円</option>
        	<option value="100000">10万円</option>
        	<option value="200000">20万円</option>
        	<option value="300000">30万円</option>
        	<option value="400000">40万円</option>
        	<option value="500000">50万円</option>
        	</select>
        	<input type="submit" value=" O K ">
        	</form>
        	</td></tr></table>
        EOM;
        // 家がない人用の出力
    } else {
        echo "<div class=\"honbun2\" align=\"center\">{$name}さんは家を持っていないので街への貢献はできません。</div>";
    }
    // 前回最終ランキング
    $zenkai_taikai = (n($dainankai_con) - 1);
    if ((n($zenkai_taikai) != 0)) {
        echo <<<EOM
        	<br><br><table width="90%" border="0" cellspacing="0" cellpadding="8" align="center" class="yosumi">
        	<tr><td colspan="4">
        	<div class="tyuu">第{$zenkai_taikai}回大会最終順位</div>
        	</td></tr>
        	<tr align="center"><td>
        	<table border="0" cellspacing="1" cellpadding="3">
        
        EOM;
        $i = 1;
        $__s10 = $_ ?? null;
        foreach ($bunka_z_rank ?? [] as $_) {
            [$mat_num, $mat_bunka, $mat_sports, $mat_ninjou, $mat_yuuhuku, $mat_bunka_y, $mat_sports_y, $mat_ninjou_y, $mat_yuuhuku_y, $mat_bunka_z, $mat_sports_z, $mat_ninjou_z, $mat_yuuhuku_z, $mat_yobi1, $k_yobi2, $k_yobi3, $k_yobi4, $k_yobi5] = pl_pad(pl_split('/<>/u', $_), 18);
            if ((n($i) == 1)) {
                echo "<tr align=\"center\" bgcolor=\"#ffff66\" class=\"jouge\"><td colspan=\"3\">文化度<div class=\"tyuu\">優勝</div><div class=\"dai\">{$town_hairetu[n($mat_num)]}</div>（{$mat_bunka_z}ポイント）</td></tr>";
                if ((n($saisyuu_flag) == 1)) {
                    syoukin_haihu($mat_num, '（文化度）');
                }
            } else {
                echo "<tr><td>{$i}</td><td>{$town_hairetu[n($mat_num)]}</td><td align=\"right\">{$mat_bunka_z}</td></tr>";
            }
            $i++;
        }
        $_ = $__s10;
        echo <<<EOM
        	</table>
        	</td><td>
        	<table border="0" cellspacing="1" cellpadding="3" >
        EOM;
        $i = 1;
        $__s11 = $_ ?? null;
        foreach ($sports_z_rank ?? [] as $_) {
            [$mat_num, $mat_bunka, $mat_sports, $mat_ninjou, $mat_yuuhuku, $mat_bunka_y, $mat_sports_y, $mat_ninjou_y, $mat_yuuhuku_y, $mat_bunka_z, $mat_sports_z, $mat_ninjou_z, $mat_yuuhuku_z, $mat_yobi1, $k_yobi2, $k_yobi3, $k_yobi4, $k_yobi5] = pl_pad(pl_split('/<>/u', $_), 18);
            if ((n($i) == 1)) {
                echo "<tr align=\"center\" bgcolor=\"#ffff66\" class=\"jouge\"><td colspan=\"3\">スポーツ振興度<div class=\"tyuu\">優勝</div><div class=\"dai\">{$town_hairetu[n($mat_num)]}</div>（{$mat_sports_z}ポイント）</td></tr>";
                if ((n($saisyuu_flag) == 1)) {
                    syoukin_haihu($mat_num, '（スポーツ振興度）');
                }
            } else {
                echo "<tr><td>{$i}位</td><td>{$town_hairetu[n($mat_num)]}</td><td align=\"right\">{$mat_sports_z}</td></tr>";
            }
            $i++;
        }
        $_ = $__s11;
        echo <<<EOM
        	</table>
        	</td><td>
        	<table border="0" cellspacing="1" cellpadding="3" >
        EOM;
        $i = 1;
        $__s12 = $_ ?? null;
        foreach ($ninjou_z_rank ?? [] as $_) {
            [$mat_num, $mat_bunka, $mat_sports, $mat_ninjou, $mat_yuuhuku, $mat_bunka_y, $mat_sports_y, $mat_ninjou_y, $mat_yuuhuku_y, $mat_bunka_z, $mat_sports_z, $mat_ninjou_z, $mat_yuuhuku_z, $mat_yobi1, $k_yobi2, $k_yobi3, $k_yobi4, $k_yobi5] = pl_pad(pl_split('/<>/u', $_), 18);
            if ((n($i) == 1)) {
                echo "<tr align=\"center\" bgcolor=\"#ffff66\" class=\"jouge\"><td colspan=\"3\">人気度<div class=\"tyuu\">優勝</div><div class=\"dai\">{$town_hairetu[n($mat_num)]}</div>（{$mat_ninjou_z}ポイント）</td></tr>";
                if ((n($saisyuu_flag) == 1)) {
                    syoukin_haihu($mat_num, '（人気度）');
                }
            } else {
                echo "<tr><td>{$i}</td><td>{$town_hairetu[n($mat_num)]}</td><td align=\"right\">{$mat_ninjou_z}</td></tr>";
            }
            $i++;
        }
        $_ = $__s12;
        echo <<<EOM
        	</table>
        	</td><td>
        	<table border="0" cellspacing="1" cellpadding="3" >
        EOM;
        $i = 1;
        $__s13 = $_ ?? null;
        foreach ($yuuhuku_z_rank ?? [] as $_) {
            [$mat_num, $mat_bunka, $mat_sports, $mat_ninjou, $mat_yuuhuku, $mat_bunka_y, $mat_sports_y, $mat_ninjou_y, $mat_yuuhuku_y, $mat_bunka_z, $mat_sports_z, $mat_ninjou_z, $mat_yuuhuku_z, $mat_yobi1, $k_yobi2, $k_yobi3, $k_yobi4, $k_yobi5] = pl_pad(pl_split('/<>/u', $_), 18);
            if ((n($i) == 1)) {
                echo "<tr align=\"center\" bgcolor=\"#ffff66\" class=\"jouge\"><td colspan=\"3\">裕福度<div class=\"tyuu\">優勝</div><div class=\"dai\">{$town_hairetu[n($mat_num)]}</div>（{$mat_yuuhuku_z}円）</td></tr>";
                if ((n($saisyuu_flag) == 1)) {
                    syoukin_haihu($mat_num, '（裕福度）');
                }
            } else {
                echo "<tr><td>{$i}</td><td>{$town_hairetu[n($mat_num)]}</td><td align=\"right\">{$mat_yuuhuku_z}円</td></tr>";
            }
            $i++;
        }
        $_ = $__s13;
        echo <<<EOM
        	</table>
        	</td></tr></table><br><br>
        EOM;
        // 前回功労者ランキングの出力
        echo <<<EOM
        	<table width="90%" border="0" cellspacing="1" cellpadding="3" align="center"  class="yosumi">
        	<tr><td colspan="5">
        	<div class="tyuu">第{$zenkai_taikai}回大会功労者ランキングベスト10</div>
        	</td></tr>
        	<tr class="jouge" bgcolor="#ffcc99" align="center"><td></td><td>名前</td><td>貢献度</td><td>文化貢献</td><td>スポーツ貢献</td><td>人気貢献</td><td>寄付金</td></tr>
        EOM;
        $i = 1;
        $__s14 = $_ ?? null;
        foreach ($kourou_z_rank ?? [] as $_) {
            [$kourou_num, $kourou_name, $kourou_total, $kourou_bunka, $kourou_sports, $kourou_ninjou, $kourou_yuuhuku, $kourou_bunka_z, $kourou_sports_z, $kourou_ninjou_z, $kourou_yuuhuku_z, $kourou_yobi1, $kourou_yobi2, $kourou_yobi3, $kourou_yobi4, $kourou_yobi5] = pl_pad(pl_split('/<>/u', $_), 16);
            // kourou_yobi1 = 最終貢献時間　kourou_yobi2＝kourou_total_z（前回の功労ポイントトータル）kourou_yobi3 = 累積功労トータル
            echo "<tr align=\"right\"><td>{$i}位</td><td align=\"left\">{$kourou_name}</td><td>{$kourou_yobi2}ポイント</td><td>{$kourou_bunka_z}</td><td>{$kourou_sports_z}</td><td>{$kourou_ninjou_z}</td><td>{$kourou_yuuhuku_z}円</td></tr>";
            if ((n($i) >= 10)) {
                break;
            }
            $i++;
        }
        $_ = $__s14;
        echo '</table><br><br>';
    }
    // 前回大会が0でなかったらの閉じ
    hooter('login_view', '戻る');
    exit;
}

function mati_kouken(...$_args)
{
    global $_, $bijutu, $eigo, $etti, $in, $k_temp, $kenkou, $kokugo, $kourou_alldata, $kourou_bunka,
        $kourou_bunka_z, $kourou_member_flag, $kourou_name, $kourou_ninjou, $kourou_ninjou_z, $kourou_num,
        $kourou_sports, $kourou_sports_z, $kourou_temp, $kourou_total, $kourou_yobi1, $kourou_yobi2, $kourou_yobi3,
        $kourou_yobi4, $kourou_yobi5, $kourou_yuuhuku, $kourou_yuuhuku_z, $kourousya_logfile, $kyakuryoku, $looks,
        $love, $mat_bunka, $mat_bunka_y, $mat_bunka_z, $mat_ninjou, $mat_ninjou_y, $mat_ninjou_z, $mat_num,
        $mat_sports, $mat_sports_y, $mat_sports_z, $mat_yobi1, $mat_yobi2, $mat_yobi3, $mat_yobi4, $mat_yobi5,
        $mat_yuuhuku, $mat_yuuhuku_y, $mat_yuuhuku_z, $mati_alldata, $maticon_logfile, $matikon_settei, $message_win,
        $money, $my_log_file, $n_kourou_bunka, $n_kourou_ninjou, $n_kourou_sports, $n_kourou_total,
        $n_kourou_yuuhuku, $name, $new_kourou_alldata, $new_mati_alldata, $okane_kanzan, $ongaku, $power, $rika,
        $sinki_kourou_temp, $speed, $suugaku, $syakai, $tairyoku, $this_script, $unique, $wanryoku;
    if ((($in['nouryoku'] == '') && ($in['okane'] == ''))) {
        error('貢献する内容が選ばれていません');
    }
    // 功労者ファイルへの書き込み
    if (!(pl_open('KOR', (string)($kourousya_logfile)))) { error("{$kourousya_logfile}が開けません"); }
    $kourou_alldata = pl_readlines('KOR');
    pl_close('KOR');
    $kourou_member_flag = 0;
    $now_time = pl_time();
    $__s1 = $_ ?? null;
    foreach ($kourou_alldata ?? [] as $_) {
        [$kourou_num, $kourou_name, $kourou_total, $kourou_bunka, $kourou_sports, $kourou_ninjou, $kourou_yuuhuku, $kourou_bunka_z, $kourou_sports_z, $kourou_ninjou_z, $kourou_yuuhuku_z, $kourou_yobi1, $kourou_yobi2, $kourou_yobi3, $kourou_yobi4, $kourou_yobi5] = pl_pad(pl_split('/<>/u', $_), 16);
        if (((string)$name === (string)(string)($kourou_name))) {
            if (((n($now_time) - n($kourou_yobi1)) < (60 * 60))) {
                error('貢献できる間隔の１時間がまだ経過していません');
            }
            $kourou_member_flag = 1;
            $kourou_yobi1 = $now_time;
            // 最終貢献時間を記録
            if ($in['nouryoku']) {
                // パラメータアップの場合
                $kourou_total = n($kourou_total) + n($in['suuti']);
                if (((((((($in['nouryoku'] == 'kokugo') || ($in['nouryoku'] == 'suugaku')) || ($in['nouryoku'] == 'rika')) || ($in['nouryoku'] == 'syakai')) || ($in['nouryoku'] == 'eigo')) || ($in['nouryoku'] == 'ongaku')) || ($in['nouryoku'] == 'bijutu'))) {
                    // 文化貢献
                    $kourou_bunka = n($kourou_bunka) + n($in['suuti']);
                } elseif ((((((($in['nouryoku'] == 'tairyoku') || ($in['nouryoku'] == 'kenkou')) || ($in['nouryoku'] == 'speed')) || ($in['nouryoku'] == 'power')) || ($in['nouryoku'] == 'wanryoku')) || ($in['nouryoku'] == 'kyakuryoku'))) {
                    // スポーツ貢献
                    $kourou_sports = n($kourou_sports) + n($in['suuti']);
                } elseif ((((($in['nouryoku'] == 'looks') || ($in['nouryoku'] == 'love')) || ($in['nouryoku'] == 'unique')) || ($in['nouryoku'] == 'etti'))) {
                    // 人気貢献
                    $kourou_ninjou = n($kourou_ninjou) + n($in['suuti']);
                }
            } elseif ($in['okane']) {
                // 寄付金の場合
                if ((n($money) <= n($in['okane']))) {
                    error('お金が足りません');
                }
                $okane_kanzan = (n($in['okane']) / 10000);
                $kourou_total = n($kourou_total) + n($okane_kanzan);
                $kourou_yuuhuku = n($kourou_yuuhuku) + n($in['okane']);
            }
        }
        // 名前一致の場合の閉じ
        $kourou_temp = "{$kourou_num}<>{$kourou_name}<>{$kourou_total}<>{$kourou_bunka}<>{$kourou_sports}<>{$kourou_ninjou}<>{$kourou_yuuhuku}<>{$kourou_bunka_z}<>{$kourou_sports_z}<>{$kourou_ninjou_z}<>{$kourou_yuuhuku_z}<>{$kourou_yobi1}<>{$kourou_yobi2}<>{$kourou_yobi3}<>{$kourou_yobi4}<>{$kourou_yobi5}<>\n";
        $new_kourou_alldata[] = $kourou_temp;
    }
    $_ = $__s1;
    // foreachの閉じ
    // 新規功労者の場合
    if ((n($kourou_member_flag) == 0)) {
        $kourou_num++;
        if ($in['okane']) {
            $n_kourou_total = n($n_kourou_total) + (n($in['okane']) / 10000);
            $n_kourou_yuuhuku = n($n_kourou_yuuhuku) + n($in['okane']);
            $n_kourou_bunka = 0;
            $n_kourou_sports = 0;
            $n_kourou_ninjou = 0;
        } else {
            $n_kourou_total = n($n_kourou_total) + n($in['suuti']);
            if (((((((($in['nouryoku'] == 'kokugo') || ($in['nouryoku'] == 'suugaku')) || ($in['nouryoku'] == 'rika')) || ($in['nouryoku'] == 'syakai')) || ($in['nouryoku'] == 'eigo')) || ($in['nouryoku'] == 'ongaku')) || ($in['nouryoku'] == 'bijutu'))) {
                // 文化貢献
                $n_kourou_bunka = $in['suuti'];
                $n_kourou_sports = 0;
                $n_kourou_ninjou = 0;
                $n_kourou_yuuhuku = 0;
            } elseif ((((((($in['nouryoku'] == 'tairyoku') || ($in['nouryoku'] == 'kenkou')) || ($in['nouryoku'] == 'speed')) || ($in['nouryoku'] == 'power')) || ($in['nouryoku'] == 'wanryoku')) || ($in['nouryoku'] == 'kyakuryoku'))) {
                // スポーツ貢献
                $n_kourou_sports = $in['suuti'];
                $n_kourou_bunka = 0;
                $n_kourou_ninjou = 0;
                $n_kourou_yuuhuku = 0;
            } elseif ((((($in['nouryoku'] == 'looks') || ($in['nouryoku'] == 'love')) || ($in['nouryoku'] == 'unique')) || ($in['nouryoku'] == 'etti'))) {
                // 人気貢献
                $n_kourou_ninjou = $in['suuti'];
                $n_kourou_sports = 0;
                $n_kourou_bunka = 0;
                $n_kourou_yuuhuku = 0;
            }
        }
        $sinki_kourou_temp = "{$kourou_num}<>{$name}<>{$n_kourou_total}<>{$n_kourou_bunka}<>{$n_kourou_sports}<>{$n_kourou_ninjou}<>{$n_kourou_yuuhuku}<>0<>0<>0<>0<>{$now_time}<>0<>0<>0<>0<>\n";
        $new_kourou_alldata[] = $sinki_kourou_temp;
    }
    // 街ファイルへの書き込み＆個人パラメータを引く
    if (!(pl_open('MA', (string)($maticon_logfile)))) { error("{$maticon_logfile}が開けません"); }
    $matikon_settei = pl_readline('MA');
    $mati_alldata = pl_readlines('MA');
    pl_close('MA');
    $__s2 = $_ ?? null;
    foreach ($mati_alldata ?? [] as $_) {
        [$mat_num, $mat_bunka, $mat_sports, $mat_ninjou, $mat_yuuhuku, $mat_bunka_y, $mat_sports_y, $mat_ninjou_y, $mat_yuuhuku_y, $mat_bunka_z, $mat_sports_z, $mat_ninjou_z, $mat_yuuhuku_z, $mat_yobi1, $mat_yobi2, $mat_yobi3, $mat_yobi4, $mat_yobi5] = pl_pad(pl_split('/<>/u', $_), 18);
        // 自分の街だった場合
        if (((string)$in['sunderu_mati'] === (string)(string)($mat_num))) {
            // パラメータアップの場合
            if ($in['nouryoku']) {
                if (($in['nouryoku'] == 'kokugo')) {
                    $kokugo = n($kokugo) - n($in['suuti']);
                    $mat_bunka = n($mat_bunka) + n($in['suuti']);
                } elseif (($in['nouryoku'] == 'suugaku')) {
                    $suugaku = n($suugaku) - n($in['suuti']);
                    $mat_bunka = n($mat_bunka) + n($in['suuti']);
                } elseif (($in['nouryoku'] == 'rika')) {
                    $rika = n($rika) - n($in['suuti']);
                    $mat_bunka = n($mat_bunka) + n($in['suuti']);
                } elseif (($in['nouryoku'] == 'syakai')) {
                    $syakai = n($syakai) - n($in['suuti']);
                    $mat_bunka = n($mat_bunka) + n($in['suuti']);
                } elseif (($in['nouryoku'] == 'eigo')) {
                    $eigo = n($eigo) - n($in['suuti']);
                    $mat_bunka = n($mat_bunka) + n($in['suuti']);
                } elseif (($in['nouryoku'] == 'ongaku')) {
                    $ongaku = n($ongaku) - n($in['suuti']);
                    $mat_bunka = n($mat_bunka) + n($in['suuti']);
                } elseif (($in['nouryoku'] == 'bijutu')) {
                    $bijutu = n($bijutu) - n($in['suuti']);
                    $mat_bunka = n($mat_bunka) + n($in['suuti']);
                } elseif (($in['nouryoku'] == 'tairyoku')) {
                    $tairyoku = n($tairyoku) - n($in['suuti']);
                    $mat_sports = n($mat_sports) + n($in['suuti']);
                } elseif (($in['nouryoku'] == 'kenkou')) {
                    $kenkou = n($kenkou) - n($in['suuti']);
                    $mat_sports = n($mat_sports) + n($in['suuti']);
                } elseif (($in['nouryoku'] == 'speed')) {
                    $speed = n($speed) - n($in['suuti']);
                    $mat_sports = n($mat_sports) + n($in['suuti']);
                } elseif (($in['nouryoku'] == 'power')) {
                    $power = n($power) - n($in['suuti']);
                    $mat_sports = n($mat_sports) + n($in['suuti']);
                } elseif (($in['nouryoku'] == 'wanryoku')) {
                    $wanryoku = n($wanryoku) - n($in['suuti']);
                    $mat_sports = n($mat_sports) + n($in['suuti']);
                } elseif (($in['nouryoku'] == 'kyakuryoku')) {
                    $kyakuryoku = n($kyakuryoku) - n($in['suuti']);
                    $mat_sports = n($mat_sports) + n($in['suuti']);
                } elseif (($in['nouryoku'] == 'looks')) {
                    $looks = n($looks) - n($in['suuti']);
                    $mat_ninjou = n($mat_ninjou) + n($in['suuti']);
                } elseif (($in['nouryoku'] == 'love')) {
                    $love = n($love) - n($in['suuti']);
                    $mat_ninjou = n($mat_ninjou) + n($in['suuti']);
                } elseif (($in['nouryoku'] == 'unique')) {
                    $unique = n($unique) - n($in['suuti']);
                    $mat_ninjou = n($mat_ninjou) + n($in['suuti']);
                } elseif (($in['nouryoku'] == 'etti')) {
                    $etti = n($etti) - n($in['suuti']);
                    $mat_ninjou = n($mat_ninjou) + n($in['suuti']);
                }
                // 寄付金の場合
            } elseif ($in['okane']) {
                $money = n($money) - n($in['okane']);
                $mat_yuuhuku = n($mat_yuuhuku) + n($in['okane']);
            }
            // 裕福度アップの場合の閉じ
        }
        // 自分の街だった場合の閉じ
        $kouken_temp = "{$mat_num}<>{$mat_bunka}<>{$mat_sports}<>{$mat_ninjou}<>{$mat_yuuhuku}<>{$mat_bunka_y}<>{$mat_sports_y}<>{$mat_ninjou_y}<>{$mat_yuuhuku_y}<>{$mat_bunka_z}<>{$mat_sports_z}<>{$mat_ninjou_z}<>{$mat_yuuhuku_z}<>{$mat_yobi1}<>{$mat_yobi2}<>{$mat_yobi3}<>{$mat_yobi4}<>{$mat_yobi5}<>\n";
        $new_mati_alldata[] = $kouken_temp;
    }
    $_ = $__s2;
    // foreachの閉じ
    pl_unshift($new_mati_alldata, $matikon_settei);
    // ログ更新
    lock(...$_args);
    if (!(pl_open('KOO', ">{$kourousya_logfile}"))) { error("{$kourousya_logfile}に書き込めません"); }
    pl_print('KOO', pl_join('', $new_kourou_alldata));
    pl_close('KOO');
    if (!(pl_open('MK', ">{$maticon_logfile}"))) { error("{$maticon_logfile}に書き込めません"); }
    pl_print('MK', pl_join('', $new_mati_alldata));
    pl_close('MK');
    unlock(...$_args);
    temp_routin(...$_args);
    log_kousin($my_log_file, $k_temp);
    t_header('', 'sonomati');
    echo <<<EOM
    	<div align="center"><br><table  border="0"  cellspacing="5" cellpadding="0" width="300" style="{$message_win}"><tr><td>街への貢献をしました。このポイントは次回のランキング集計時に反映されます。</td></tr></table><br>
    
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="matikon">
    	<input type="hidden" name="iesettei_id" value="{$in['iesettei_id']}">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="hidden" name="admin_pass" value="{$in['admin_pass']}">
    	<input type="submit" value="戻る">
    	</form></div>
    
    	</body></html>
    EOM;
    exit;
}

function matikon_changeDay(...$_args)
{
    global $_, $changeDay_temp, $change_mati_alldata, $change_matikon_settei, $dainankai_con, $date2, $mati_alldata,
        $maticon_logfile, $nannitime_con, $nannitime_hozon, $rank_hiduke, $start_con_time;
    lock(...$_args);
    $__s1 = $_ ?? null;
    foreach ($mati_alldata ?? [] as $_) {
        [$mat_num, $mat_bunka, $mat_sports, $mat_ninjou, $mat_yuuhuku, $mat_bunka_y, $mat_sports_y, $mat_ninjou_y, $mat_yuuhuku_y, $mat_bunka_z, $mat_sports_z, $mat_ninjou_z, $mat_yuuhuku_z, $mat_yobi1, $mat_yobi2, $mat_yobi3, $mat_yobi4, $mat_yobi5] = pl_pad(pl_split('/<>/u', $_), 18);
        $mat_bunka_y = $mat_bunka;
        $mat_sports_y = $mat_sports;
        $mat_ninjou_y = $mat_ninjou;
        $mat_yuuhuku_y = $mat_yuuhuku;
        $changeDay_temp = "{$mat_num}<>{$mat_bunka}<>{$mat_sports}<>{$mat_ninjou}<>{$mat_yuuhuku}<>{$mat_bunka_y}<>{$mat_sports_y}<>{$mat_ninjou_y}<>{$mat_yuuhuku_y}<>{$mat_bunka_z}<>{$mat_sports_z}<>{$mat_ninjou_z}<>{$mat_yuuhuku_z}<>{$mat_yobi1}<>{$mat_yobi2}<>{$mat_yobi3}<>{$mat_yobi4}<>{$mat_yobi5}<>\n";
        $change_mati_alldata[] = $changeDay_temp;
    }
    $_ = $__s1;
    $mati_alldata = [];
    $mati_alldata = $change_mati_alldata;
    $nannitime_hozon = $nannitime_con;
    $rank_hiduke = (string)($date2);
    $change_matikon_settei = "{$start_con_time}<>{$nannitime_hozon}<>{$dainankai_con}<>{$rank_hiduke}<>\n";
    pl_unshift($change_mati_alldata, $change_matikon_settei);
    if (!(pl_open('OUT', ">{$maticon_logfile}"))) { error("Open Error : {$maticon_logfile}"); }
    pl_print('OUT', pl_join('', $change_mati_alldata));
    pl_close('OUT');
    unlock(...$_args);
}

function matikon_saisyuu(...$_args)
{
    global $_, $change_mati_alldata, $dainankai_con, $date2, $date_sec, $kourousya_logfile, $mati_alldata,
        $maticon_logfile, $nannitime_con, $nannitime_hozon, $rank_hiduke, $saisyuu_flag, $saisyuu_matikon_settei,
        $saisyuu_temp, $start_con_time;
    lock(...$_args);
    $change_mati_alldata = [];
    $__s1 = $_ ?? null;
    foreach ($mati_alldata ?? [] as $_) {
        [$mat_num, $mat_bunka, $mat_sports, $mat_ninjou, $mat_yuuhuku, $mat_bunka_y, $mat_sports_y, $mat_ninjou_y, $mat_yuuhuku_y, $mat_bunka_z, $mat_sports_z, $mat_ninjou_z, $mat_yuuhuku_z, $mat_yobi1, $mat_yobi2, $mat_yobi3, $mat_yobi4, $mat_yobi5] = pl_pad(pl_split('/<>/u', $_), 18);
        $mat_bunka_z = $mat_bunka;
        $mat_sports_z = $mat_sports;
        $mat_ninjou_z = $mat_ninjou;
        $mat_yuuhuku_z = $mat_yuuhuku;
        $mat_bunka_y = 0;
        $mat_sports_y = 0;
        $mat_ninjou_y = 0;
        $mat_yuuhuku_y = 0;
        $mat_bunka = 0;
        $mat_sports = 0;
        $mat_ninjou = 0;
        $mat_yuuhuku = 0;
        $saisyuu_temp = "{$mat_num}<>{$mat_bunka}<>{$mat_sports}<>{$mat_ninjou}<>{$mat_yuuhuku}<>{$mat_bunka_y}<>{$mat_sports_y}<>{$mat_ninjou_y}<>{$mat_yuuhuku_y}<>{$mat_bunka_z}<>{$mat_sports_z}<>{$mat_ninjou_z}<>{$mat_yuuhuku_z}<>{$mat_yobi1}<>{$mat_yobi2}<>{$mat_yobi3}<>{$mat_yobi4}<>{$mat_yobi5}<>\n";
        $change_mati_alldata[] = $saisyuu_temp;
    }
    $_ = $__s1;
    $mati_alldata = [];
    $mati_alldata = $change_mati_alldata;
    $nannitime_hozon = 1;
    $rank_hiduke = (string)($date2);
    $dainankai_con++;
    $start_con_time = $date_sec;
    $nannitime_con = 1;
    // 最初の表示用に１を入れる
    $saisyuu_matikon_settei = "{$start_con_time}<>{$nannitime_hozon}<>{$dainankai_con}<>{$rank_hiduke}<>\n";
    pl_unshift($change_mati_alldata, $saisyuu_matikon_settei);
    // 功労者データ更新
    if (!(pl_open('KOR', (string)($kourousya_logfile)))) { error("{$kourousya_logfile}が開けません"); }
    $kourou_alldata = pl_readlines('KOR');
    pl_close('KOR');
    $new_kourou_alldata = [];
    $__s2 = $_ ?? null;
    foreach ($kourou_alldata ?? [] as $_) {
        [$kourou_num, $kourou_name, $kourou_total, $kourou_bunka, $kourou_sports, $kourou_ninjou, $kourou_yuuhuku, $kourou_bunka_z, $kourou_sports_z, $kourou_ninjou_z, $kourou_yuuhuku_z, $kourou_yobi1, $kourou_yobi2, $kourou_yobi3, $kourou_yobi4, $kourou_yobi5] = pl_pad(pl_split('/<>/u', $_), 16);
        $kourou_yobi2 = $kourou_total;
        $kourou_bunka_z = $kourou_bunka;
        $kourou_sports_z = $kourou_sports;
        $kourou_ninjou_z = $kourou_ninjou;
        $kourou_yuuhuku_z = $kourou_yuuhuku;
        $kourou_yobi3 = n($kourou_yobi3) + n($kourou_total);
        $kourou_total = 0;
        $kourou_bunka = 0;
        $kourou_sports = 0;
        $kourou_ninjou = 0;
        $kourou_yuuhuku = 0;
        $kourou_temp = "{$kourou_num}<>{$kourou_name}<>{$kourou_total}<>{$kourou_bunka}<>{$kourou_sports}<>{$kourou_ninjou}<>{$kourou_yuuhuku}<>{$kourou_bunka_z}<>{$kourou_sports_z}<>{$kourou_ninjou_z}<>{$kourou_yuuhuku_z}<>{$kourou_yobi1}<>{$kourou_yobi2}<>{$kourou_yobi3}<>{$kourou_yobi4}<>{$kourou_yobi5}<>\n";
        $new_kourou_alldata[] = $kourou_temp;
    }
    $_ = $__s2;
    // foreachの閉じ
    // データ更新
    if (!(pl_open('OUT', ">{$maticon_logfile}"))) { error("Open Error : {$maticon_logfile}"); }
    pl_print('OUT', pl_join('', $change_mati_alldata));
    pl_close('OUT');
    $saisyuu_flag = 1;
    if (!(pl_open('KOO', ">{$kourousya_logfile}"))) { error("{$kourousya_logfile}に書き込めません"); }
    pl_print('KOO', pl_join('', $new_kourou_alldata));
    pl_close('KOO');
    unlock(...$_args);
}

function syoukin_haihu(...$_args)
{
    global $_, $aite_bank, $aite_k_temp, $aite_log_file, $haihusaki_dir, $kityou_naiyou, $mati_con_syoukin,
        $ori_ie_list, $syoukin_taisyousya, $syoukingaku, $yuusyou_taun;
    lock(...$_args);
    $yuusyou_taun = $_args[0];
    if (!(pl_open('IN', (string)($ori_ie_list)))) { error("Open Error : {$ori_ie_list}"); }
    $ori_ie_para = pl_readlines('IN');
    pl_close('IN');
    // 住人の数を計算
    $syoukin_taisyousya = 0;
    $__s1 = $_ ?? null;
    foreach ($ori_ie_para ?? [] as $_) {
        [$ori_k_id, $ori_ie_name, $ori_ie_setumei, $ori_ie_image, $ori_ie_syubetu, $ori_ie_kentikubi, $ori_ie_town, $ori_ie_tateziku, $ori_ie_yokoziku, $ori_ie_sentaku_point, $ori_ie_rank, $ori_ie_yobi7, $ori_ie_yobi8, $ori_ie_yobi9, $ori_ie_yobi10] = pl_pad(pl_split('/<>/u', $_), 15);
        if (((string)$ori_ie_town === (string)(string)($yuusyou_taun))) {
            $syoukin_taisyousya++;
        }
    }
    $_ = $__s1;
    $syoukingaku = pl_int(((n($mati_con_syoukin) * 10000) / n($syoukin_taisyousya)));
    $__s2 = $_ ?? null;
    foreach ($ori_ie_para ?? [] as $_) {
        [$ori_k_id, $ori_ie_name, $ori_ie_setumei, $ori_ie_image, $ori_ie_syubetu, $ori_ie_kentikubi, $ori_ie_town, $ori_ie_tateziku, $ori_ie_yokoziku, $ori_ie_sentaku_point, $ori_ie_rank, $ori_ie_yobi7, $ori_ie_yobi8, $ori_ie_yobi9, $ori_ie_yobi10] = pl_pad(pl_split('/<>/u', $_), 15);
        if (((string)$ori_ie_town === (string)(string)($yuusyou_taun))) {
            $haihusaki_dir = "./member/{$ori_k_id}/log.cgi";
            // ver.1.40
            if (pl_exists((string)($haihusaki_dir))) {
                // ver.1.40
                openAitelog($ori_k_id);
                $aite_bank = n($aite_bank) + n($syoukingaku);
                aite_temp_routin(...$_args);
                if (!(pl_open('OUT', ">{$aite_log_file}"))) { error("{$aite_log_file}に書き込めません"); }
                pl_print('OUT', $aite_k_temp);
                pl_close('OUT');
                $kityou_naiyou = '街コンテスト賞金' . (string)($_args[1]);
                aite_kityou_syori((string)($kityou_naiyou), '', $syoukingaku, $aite_bank, '普', $ori_k_id, 'lock_off');
            }
            // ver.1.40
        }
    }
    $_ = $__s2;
    unlock(...$_args);
}

