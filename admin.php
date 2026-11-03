<?php
// このファイルは admin.cgi (Perl) から自動変換されたものです。
// 町CGI  Copyright (c) 2003-2004 brassiere  http://brassiere.jp/

require_once __DIR__ . '/lib/bootstrap.php';
$this_script = 'admin.php';
require_once __DIR__ . '/town_ini.php';
require_once __DIR__ . '/town_lib.php';
require_once __DIR__ . '/unit.php';
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
if (($in['mode'] == 'admin')) {
    admin();
} elseif (($in['mode'] == 'admin_bbs')) {
    admin_bbs();
} elseif (($in['mode'] == 'bbs1_settei_do')) {
    bbs1_settei_do();
} elseif (($in['mode'] == 'yakuba')) {
    yakuba();
} elseif (($in['mode'] == 'ad_orosi_kousin')) {
    ad_orosi_kousin();
} elseif (($in['mode'] == 'parts_taiou_hyou')) {
    parts_taiou_hyou();
} elseif (($in['mode'] == 'itiran')) {
    itiran();
} elseif (($in['mode'] == 'kensaku')) {
    kensaku();
} elseif (($in['mode'] == 'kozindata_henkou')) {
    kozindata_henkou();
} elseif (($in['mode'] == 'hukkatu')) {
    hukkatu();
} elseif (($in['mode'] == 'deletUsr')) {
    deletUsr();
} elseif (($in['mode'] == 'make_town')) {
    make_town();
} else {
    error('「戻る」ボタンで街に戻ってください');
}
exit;
// ############以下サブルーチン
// ##########以下サブルーチン
// #####街のレイアウト
// ####パーツ＆キーワード対応表
// ####管理者作成BBS
// ####参加者一覧
// ##個人データ変更
// データ復活
// 管理者権限による卸商品更新			#ver.1.2
// プロフィール削除サブルーチン		ver.1.3
// ###BBS1の設定
// ######ランキング表示
// サブルーチンの閉じ

function admin(...$_args)
{
    global $_, $admin_bbs_syurui, $admin_pass, $i, $in, $refe, $script, $setti1, $setti2, $setti3, $this_script,
        $town_hairetu;
    // パスワード入力画面
    if (($in['admin_pass'] == '')) {
        t_header(...$_args);
        echo <<<EOM
        	<form method="POST" action="{$this_script}">
        	<br><div align="center">
        	パスワードを入力してください。<br><br>
        	<input type="text" name="admin_pass" size="10"><input type="submit" value="OK"></div>
        	</form>
        EOM;
        exit;
    }
    if (((string)$in['admin_pass'] !== (string)$admin_pass)) {
        error('パスワードが違います');
    }
    $refe = $_SERVER['HTTP_REFERER'];
    if (((!pl_match('/' . pl_redelim($setti1) . '/u', $refe) && !pl_match('/' . pl_redelim($setti2) . '/u', $refe)) && !pl_match('/' . pl_redelim($setti3) . '/u', $refe))) {
        error("トップページより正しくお入りください。<br>{$refe}");
    }
    // 画面出力
    t_header(...$_args);
    echo <<<EOM
    	<div align="center" class="midasi">管理者メニュー</div><br>
    	<table width="500" border="0" cellspacing="0" cellpadding="10" align="center">
      <tr><td>
      <div class="tyuu">●街のレイアウト作成</div>
    EOM;
    $i = 0;
    $__s1 = $_ ?? null;
    foreach ($town_hairetu ?? [] as $_) {
        echo <<<EOM
        			<form method="POST" action="{$this_script}">
        			<input type="hidden" name="mode" value="make_town">
        			<input type="hidden" name="command" value="m_form">
        			<input type="hidden" name="admin_pass" value="{$in['admin_pass']}">
        			<input type="hidden" name="town_no" value="{$i}">
        			<input type="hidden" name="town_n" value="{$_}">
        			<input type="submit" value="『{$_}』のレイアウト作成">
        			</form>
        EOM;
        $i++;
    }
    $_ = $__s1;
    echo '<hr size="1">';
    echo '<div class="tyuu">●管理者作成BBSの設定</div>';
    $i = 0;
    $__s2 = $_ ?? null;
    foreach ($admin_bbs_syurui ?? [] as $_) {
        echo <<<EOM
        			<form method="POST" action="{$this_script}">
        			<input type="hidden" name="mode" value="admin_bbs">
        			<input type="hidden" name="bbs_num" value="{$i}">
        			<input type="hidden" name="admin_pass" value="{$in['admin_pass']}">
        			<input type="submit" value="{$_}の設定">
        			</form>
        EOM;
        $i++;
    }
    $_ = $__s2;
    echo <<<EOM
      		<hr size="1">
    		  	<div class="tyuu">●メンバー管理</div>
      			<form method="POST" action="{$this_script}">
    			<input type="hidden" name="mode" value="itiran">
    			<input type="hidden" name="sort_id" value="0">
    			<input type="hidden" name="admin_pass" value="{$in['admin_pass']}">
    			<input type="submit" value="登録者一覧（ランキング）">
    			</form>
      	<hr size="1">
    	  ※ver.1.2より個人データのバックアップデータはmemberディレクトリ内の「ID番号＋backup」ディレクトリに保存されています。またこのディレクトリは削除期限が過ぎてその人のデータが削除された後も残る仕様になっていますので、たまにbackupのみとなったディレクトリを削除するようにしてください。<br>
    	  ※個人データ以外の各種ログ（log_dirフォルダー内のファイル）は１日１回バックアップ処理が行われています。全てのログファイルは、「log_dir」フォルダー内の「backup_dir」フォルダーに頭に「backup_」がついたファイル名で保存されています。現状、復活に関するコマンドは用意しておりませんので、万が一ログが消失するようなことがあった場合、該当するファイルをこのバックアップファイルからコピーするなどしてお使いください。
    
      	<hr size="1">
    		  	<div class="tyuu">●卸商品の更新</div>
    			  ※このボタンを押すことで管理者が任意の時間に卸商品を更新することができます。
      			<form method="POST" action="{$this_script}">
    			<input type="hidden" name="mode" value="ad_orosi_kousin">
    			<input type="hidden" name="admin_pass" value="{$in['admin_pass']}">
    			<input type="submit" value="更新する">
    			</form>
      	<hr size="1">
    	</td></tr></table>
    	<div align="center"><a href="{$script}">[戻る]</a></div>
    	</body></html>
    EOM;
    exit;
}

function town_temp(...$_args)
{
    global $hanei, $keizai, $t_a0, $t_a1, $t_a10, $t_a11, $t_a12, $t_a13, $t_a14, $t_a15, $t_a16, $t_a2, $t_a3,
        $t_a4, $t_a5, $t_a6, $t_a7, $t_a8, $t_a9, $t_b0, $t_b1, $t_b10, $t_b11, $t_b12, $t_b13, $t_b14, $t_b15,
        $t_b16, $t_b2, $t_b3, $t_b4, $t_b5, $t_b6, $t_b7, $t_b8, $t_b9, $t_c0, $t_c1, $t_c10, $t_c11, $t_c12, $t_c13,
        $t_c14, $t_c15, $t_c16, $t_c2, $t_c3, $t_c4, $t_c5, $t_c6, $t_c7, $t_c8, $t_c9, $t_d0, $t_d1, $t_d10, $t_d11,
        $t_d12, $t_d13, $t_d14, $t_d15, $t_d16, $t_d2, $t_d3, $t_d4, $t_d5, $t_d6, $t_d7, $t_d8, $t_d9, $t_e0, $t_e1,
        $t_e10, $t_e11, $t_e12, $t_e13, $t_e14, $t_e15, $t_e16, $t_e2, $t_e3, $t_e4, $t_e5, $t_e6, $t_e7, $t_e8,
        $t_e9, $t_f0, $t_f1, $t_f10, $t_f11, $t_f12, $t_f13, $t_f14, $t_f15, $t_f16, $t_f2, $t_f3, $t_f4, $t_f5,
        $t_f6, $t_f7, $t_f8, $t_f9, $t_g0, $t_g1, $t_g10, $t_g11, $t_g12, $t_g13, $t_g14, $t_g15, $t_g16, $t_g2,
        $t_g3, $t_g4, $t_g5, $t_g6, $t_g7, $t_g8, $t_g9, $t_h0, $t_h1, $t_h10, $t_h11, $t_h12, $t_h13, $t_h14,
        $t_h15, $t_h16, $t_h2, $t_h3, $t_h4, $t_h5, $t_h6, $t_h7, $t_h8, $t_h9, $t_i0, $t_i1, $t_i10, $t_i11, $t_i12,
        $t_i13, $t_i14, $t_i15, $t_i16, $t_i2, $t_i3, $t_i4, $t_i5, $t_i6, $t_i7, $t_i8, $t_i9, $t_j0, $t_j1, $t_j10,
        $t_j11, $t_j12, $t_j13, $t_j14, $t_j15, $t_j16, $t_j2, $t_j3, $t_j4, $t_j5, $t_j6, $t_j7, $t_j8, $t_j9,
        $t_k0, $t_k1, $t_k10, $t_k11, $t_k12, $t_k13, $t_k14, $t_k15, $t_k16, $t_k2, $t_k3, $t_k4, $t_k5, $t_k6,
        $t_k7, $t_k8, $t_k9, $t_l0, $t_l1, $t_l10, $t_l11, $t_l12, $t_l13, $t_l14, $t_l15, $t_l16, $t_l2, $t_l3,
        $t_l4, $t_l5, $t_l6, $t_l7, $t_l8, $t_l9, $t_m0, $t_m1, $t_m10, $t_m11, $t_m12, $t_m13, $t_m14, $t_m15,
        $t_m16, $t_m2, $t_m3, $t_m4, $t_m5, $t_m6, $t_m7, $t_m8, $t_m9, $t_n0, $t_n1, $t_n10, $t_n11, $t_n12, $t_n13,
        $t_n14, $t_n15, $t_n16, $t_n2, $t_n3, $t_n4, $t_n5, $t_n6, $t_n7, $t_n8, $t_n9, $t_x0, $t_x1, $t_x10, $t_x11,
        $t_x12, $t_x13, $t_x14, $t_x15, $t_x16, $t_x2, $t_x3, $t_x4, $t_x5, $t_x6, $t_x7, $t_x8, $t_x9, $t_yobi2,
        $t_yobi3, $t_yobi4, $t_yobi5, $t_yobi6, $t_yobi7, $tika, $town_name, $town_temp, $zinkou;
    $town_temp = "{$town_name}<>{$zinkou}<>{$keizai}<>{$hanei}<>{$t_x0}<>{$t_x1}<>{$t_x2}<>{$t_x3}<>{$t_x4}<>{$t_x5}<>{$t_x6}<>{$t_x7}<>{$t_x8}<>{$t_x9}<>{$t_x10}<>{$t_x11}<>{$t_x12}<>{$t_x13}<>{$t_x14}<>{$t_x15}<>{$t_x16}<>{$t_a0}<>{$t_a1}<>{$t_a2}<>{$t_a3}<>{$t_a4}<>{$t_a5}<>{$t_a6}<>{$t_a7}<>{$t_a8}<>{$t_a9}<>{$t_a10}<>{$t_a11}<>{$t_a12}<>{$t_a13}<>{$t_a14}<>{$t_a15}<>{$t_a16}<>{$t_b0}<>{$t_b1}<>{$t_b2}<>{$t_b3}<>{$t_b4}<>{$t_b5}<>{$t_b6}<>{$t_b7}<>{$t_b8}<>{$t_b9}<>{$t_b10}<>{$t_b11}<>{$t_b12}<>{$t_b13}<>{$t_b14}<>{$t_b15}<>{$t_b16}<>{$t_c0}<>{$t_c1}<>{$t_c2}<>{$t_c3}<>{$t_c4}<>{$t_c5}<>{$t_c6}<>{$t_c7}<>{$t_c8}<>{$t_c9}<>{$t_c10}<>{$t_c11}<>{$t_c12}<>{$t_c13}<>{$t_c14}<>{$t_c15}<>{$t_c16}<>{$t_d0}<>{$t_d1}<>{$t_d2}<>{$t_d3}<>{$t_d4}<>{$t_d5}<>{$t_d6}<>{$t_d7}<>{$t_d8}<>{$t_d9}<>{$t_d10}<>{$t_d11}<>{$t_d12}<>{$t_d13}<>{$t_d14}<>{$t_d15}<>{$t_d16}<>{$t_e0}<>{$t_e1}<>{$t_e2}<>{$t_e3}<>{$t_e4}<>{$t_e5}<>{$t_e6}<>{$t_e7}<>{$t_e8}<>{$t_e9}<>{$t_e10}<>{$t_e11}<>{$t_e12}<>{$t_e13}<>{$t_e14}<>{$t_e15}<>{$t_e16}<>{$t_f0}<>{$t_f1}<>{$t_f2}<>{$t_f3}<>{$t_f4}<>{$t_f5}<>{$t_f6}<>{$t_f7}<>{$t_f8}<>{$t_f9}<>{$t_f10}<>{$t_f11}<>{$t_f12}<>{$t_f13}<>{$t_f14}<>{$t_f15}<>{$t_f16}<>{$t_g0}<>{$t_g1}<>{$t_g2}<>{$t_g3}<>{$t_g4}<>{$t_g5}<>{$t_g6}<>{$t_g7}<>{$t_g8}<>{$t_g9}<>{$t_g10}<>{$t_g11}<>{$t_g12}<>{$t_g13}<>{$t_g14}<>{$t_g15}<>{$t_g16}<>{$t_h0}<>{$t_h1}<>{$t_h2}<>{$t_h3}<>{$t_h4}<>{$t_h5}<>{$t_h6}<>{$t_h7}<>{$t_h8}<>{$t_h9}<>{$t_h10}<>{$t_h11}<>{$t_h12}<>{$t_h13}<>{$t_h14}<>{$t_h15}<>{$t_h16}<>{$t_i0}<>{$t_i1}<>{$t_i2}<>{$t_i3}<>{$t_i4}<>{$t_i5}<>{$t_i6}<>{$t_i7}<>{$t_i8}<>{$t_i9}<>{$t_i10}<>{$t_i11}<>{$t_i12}<>{$t_i13}<>{$t_i14}<>{$t_i15}<>{$t_i16}<>{$t_j0}<>{$t_j1}<>{$t_j2}<>{$t_j3}<>{$t_j4}<>{$t_j5}<>{$t_j6}<>{$t_j7}<>{$t_j8}<>{$t_j9}<>{$t_j10}<>{$t_j11}<>{$t_j12}<>{$t_j13}<>{$t_j14}<>{$t_j15}<>{$t_j16}<>{$t_k0}<>{$t_k1}<>{$t_k2}<>{$t_k3}<>{$t_k4}<>{$t_k5}<>{$t_k6}<>{$t_k7}<>{$t_k8}<>{$t_k9}<>{$t_k10}<>{$t_k11}<>{$t_k12}<>{$t_k13}<>{$t_k14}<>{$t_k15}<>{$t_k16}<>{$t_l0}<>{$t_l1}<>{$t_l2}<>{$t_l3}<>{$t_l4}<>{$t_l5}<>{$t_l6}<>{$t_l7}<>{$t_l8}<>{$t_l9}<>{$t_l10}<>{$t_l11}<>{$t_l12}<>{$t_l13}<>{$t_l14}<>{$t_l15}<>{$t_l16}<>{$t_m0}<>{$t_m1}<>{$t_m2}<>{$t_m3}<>{$t_m4}<>{$t_m5}<>{$t_m6}<>{$t_m7}<>{$t_m8}<>{$t_m9}<>{$t_m10}<>{$t_m11}<>{$t_m12}<>{$t_m13}<>{$t_m14}<>{$t_m15}<>{$t_m16}<>{$t_n0}<>{$t_n1}<>{$t_n2}<>{$t_n3}<>{$t_n4}<>{$t_n5}<>{$t_n6}<>{$t_n7}<>{$t_n8}<>{$t_n9}<>{$t_n10}<>{$t_n11}<>{$t_n12}<>{$t_n13}<>{$t_n14}<>{$t_n15}<>{$t_n16}<>{$tika}<>{$t_yobi2}<>{$t_yobi3}<>{$t_yobi4}<>{$t_yobi5}<>{$t_yobi6}<>{$t_yobi7}<>\n";
}

function make_town(...$_args)
{
    global $admin_pass, $in, $script, $town_data, $town_temp;
    if (((string)$in['admin_pass'] !== (string)$admin_pass)) {
        error('パスワードが違います');
    }
    t_header(2);
    if (($in['command'] == 'm_form')) {
        town_form(...$_args);
        exit;
    }
    if (($in['command'] == 'regi')) {
        $town_temp = "{$in['town_n']}<>{$in['zinkou']}<>{$in['keizai']}<>{$in['hanei']}<>{$in['t_x0']}<>{$in['t_x1']}<>{$in['t_x2']}<>{$in['t_x3']}<>{$in['t_x4']}<>{$in['t_x5']}<>{$in['t_x6']}<>{$in['t_x7']}<>{$in['t_x8']}<>{$in['t_x9']}<>{$in['t_x10']}<>{$in['t_x11']}<>{$in['t_x12']}<>{$in['t_x13']}<>{$in['t_x14']}<>{$in['t_x15']}<>{$in['t_x16']}<>{$in['t_a0']}<>{$in['t_a1']}<>{$in['t_a2']}<>{$in['t_a3']}<>{$in['t_a4']}<>{$in['t_a5']}<>{$in['t_a6']}<>{$in['t_a7']}<>{$in['t_a8']}<>{$in['t_a9']}<>{$in['t_a10']}<>{$in['t_a11']}<>{$in['t_a12']}<>{$in['t_a13']}<>{$in['t_a14']}<>{$in['t_a15']}<>{$in['t_a16']}<>{$in['t_b0']}<>{$in['t_b1']}<>{$in['t_b2']}<>{$in['t_b3']}<>{$in['t_b4']}<>{$in['t_b5']}<>{$in['t_b6']}<>{$in['t_b7']}<>{$in['t_b8']}<>{$in['t_b9']}<>{$in['t_b10']}<>{$in['t_b11']}<>{$in['t_b12']}<>{$in['t_b13']}<>{$in['t_b14']}<>{$in['t_b15']}<>{$in['t_b16']}<>{$in['t_c0']}<>{$in['t_c1']}<>{$in['t_c2']}<>{$in['t_c3']}<>{$in['t_c4']}<>{$in['t_c5']}<>{$in['t_c6']}<>{$in['t_c7']}<>{$in['t_c8']}<>{$in['t_c9']}<>{$in['t_c10']}<>{$in['t_c11']}<>{$in['t_c12']}<>{$in['t_c13']}<>{$in['t_c14']}<>{$in['t_c15']}<>{$in['t_c16']}<>{$in['t_d0']}<>{$in['t_d1']}<>{$in['t_d2']}<>{$in['t_d3']}<>{$in['t_d4']}<>{$in['t_d5']}<>{$in['t_d6']}<>{$in['t_d7']}<>{$in['t_d8']}<>{$in['t_d9']}<>{$in['t_d10']}<>{$in['t_d11']}<>{$in['t_d12']}<>{$in['t_d13']}<>{$in['t_d14']}<>{$in['t_d15']}<>{$in['t_d16']}<>{$in['t_e0']}<>{$in['t_e1']}<>{$in['t_e2']}<>{$in['t_e3']}<>{$in['t_e4']}<>{$in['t_e5']}<>{$in['t_e6']}<>{$in['t_e7']}<>{$in['t_e8']}<>{$in['t_e9']}<>{$in['t_e10']}<>{$in['t_e11']}<>{$in['t_e12']}<>{$in['t_e13']}<>{$in['t_e14']}<>{$in['t_e15']}<>{$in['t_e16']}<>{$in['t_f0']}<>{$in['t_f1']}<>{$in['t_f2']}<>{$in['t_f3']}<>{$in['t_f4']}<>{$in['t_f5']}<>{$in['t_f6']}<>{$in['t_f7']}<>{$in['t_f8']}<>{$in['t_f9']}<>{$in['t_f10']}<>{$in['t_f11']}<>{$in['t_f12']}<>{$in['t_f13']}<>{$in['t_f14']}<>{$in['t_f15']}<>{$in['t_f16']}<>{$in['t_g0']}<>{$in['t_g1']}<>{$in['t_g2']}<>{$in['t_g3']}<>{$in['t_g4']}<>{$in['t_g5']}<>{$in['t_g6']}<>{$in['t_g7']}<>{$in['t_g8']}<>{$in['t_g9']}<>{$in['t_g10']}<>{$in['t_g11']}<>{$in['t_g12']}<>{$in['t_g13']}<>{$in['t_g14']}<>{$in['t_g15']}<>{$in['t_g16']}<>{$in['t_h0']}<>{$in['t_h1']}<>{$in['t_h2']}<>{$in['t_h3']}<>{$in['t_h4']}<>{$in['t_h5']}<>{$in['t_h6']}<>{$in['t_h7']}<>{$in['t_h8']}<>{$in['t_h9']}<>{$in['t_h10']}<>{$in['t_h11']}<>{$in['t_h12']}<>{$in['t_h13']}<>{$in['t_h14']}<>{$in['t_h15']}<>{$in['t_h16']}<>{$in['t_i0']}<>{$in['t_i1']}<>{$in['t_i2']}<>{$in['t_i3']}<>{$in['t_i4']}<>{$in['t_i5']}<>{$in['t_i6']}<>{$in['t_i7']}<>{$in['t_i8']}<>{$in['t_i9']}<>{$in['t_i10']}<>{$in['t_i11']}<>{$in['t_i12']}<>{$in['t_i13']}<>{$in['t_i14']}<>{$in['t_i15']}<>{$in['t_i16']}<>{$in['t_j0']}<>{$in['t_j1']}<>{$in['t_j2']}<>{$in['t_j3']}<>{$in['t_j4']}<>{$in['t_j5']}<>{$in['t_j6']}<>{$in['t_j7']}<>{$in['t_j8']}<>{$in['t_j9']}<>{$in['t_j10']}<>{$in['t_j11']}<>{$in['t_j12']}<>{$in['t_j13']}<>{$in['t_j14']}<>{$in['t_j15']}<>{$in['t_j16']}<>{$in['t_k0']}<>{$in['t_k1']}<>{$in['t_k2']}<>{$in['t_k3']}<>{$in['t_k4']}<>{$in['t_k5']}<>{$in['t_k6']}<>{$in['t_k7']}<>{$in['t_k8']}<>{$in['t_k9']}<>{$in['t_k10']}<>{$in['t_k11']}<>{$in['t_k12']}<>{$in['t_k13']}<>{$in['t_k14']}<>{$in['t_k15']}<>{$in['t_k16']}<>{$in['t_l0']}<>{$in['t_l1']}<>{$in['t_l2']}<>{$in['t_l3']}<>{$in['t_l4']}<>{$in['t_l5']}<>{$in['t_l6']}<>{$in['t_l7']}<>{$in['t_l8']}<>{$in['t_l9']}<>{$in['t_l10']}<>{$in['t_l11']}<>{$in['t_l12']}<>{$in['t_l13']}<>{$in['t_l14']}<>{$in['t_l15']}<>{$in['t_l16']}<>{$in['t_m0']}<>{$in['t_m1']}<>{$in['t_m2']}<>{$in['t_m3']}<>{$in['t_m4']}<>{$in['t_m5']}<>{$in['t_m6']}<>{$in['t_m7']}<>{$in['t_m8']}<>{$in['t_m9']}<>{$in['t_m10']}<>{$in['t_m11']}<>{$in['t_m12']}<>{$in['t_m13']}<>{$in['t_m14']}<>{$in['t_m15']}<>{$in['t_m16']}<>{$in['t_n0']}<>{$in['t_n1']}<>{$in['t_n2']}<>{$in['t_n3']}<>{$in['t_n4']}<>{$in['t_n5']}<>{$in['t_n6']}<>{$in['t_n7']}<>{$in['t_n8']}<>{$in['t_n9']}<>{$in['t_n10']}<>{$in['t_n11']}<>{$in['t_n12']}<>{$in['t_n13']}<>{$in['t_n14']}<>{$in['t_n15']}<>{$in['t_n16']}<>{$in['tika']}<>{$in['t_yobi2']}<>{$in['t_yobi3']}<>{$in['t_yobi4']}<>{$in['t_yobi5']}<>{$in['t_yobi6']}<>{$in['t_yobi7']}<>\n";
        // ログを更新
        lock(...$_args);
        $town_data = './log_dir/townlog' . $in['town_no'] . '.cgi';
        if (!(pl_open('KOUT', ">{$town_data}"))) { error("Write Error : {$town_data}"); }
        pl_print('KOUT', $town_temp);
        pl_close('KOUT');
        unlock(...$_args);
        echo <<<EOM
        	<div align="center">{$in['town_n']}を作成しました。<br><br>
        	<a href="{$script}?town_no={$in['town_no']}" target="_blank">[街の確認]</a><br><br>
        	<!--TW_BACK--><br><br>
        EOM;
        hooter('admin', '戻る', 'admin.php');
        exit;
    }
}

function town_form(...$_args)
{
    global $admin_pass, $hanei, $i, $in, $keizai, $maketown_data, $name_seikei, $t_yobi1, $t_yobi2, $t_yobi3,
        $t_yobi4, $t_yobi5, $t_yobi6, $t_yobi7, $tateziku_kigou, $this_script, $town_data, $town_sprit_matrix,
        $yokoziku_bangou, $yokoziku_koumoku, $zinkou;
    get_unit(...$_args);
    simaitosi(...$_args);
    admin_parts(...$_args);
    if (((string)$in['admin_pass'] !== (string)$admin_pass)) {
        error('パスワードが違います');
    }
    $town_data = './log_dir/townlog' . $in['town_no'] . '.cgi';
    if (!(pl_exists($town_data))) {
        if (!(pl_open('INI', ">{$town_data}"))) { error("Write Error : {$town_data}"); }
        pl_close('INI');
    }
    if (!(pl_open('IN', (string)($town_data)))) { error("Open Error : {$town_data}"); }
    $maketown_data = pl_readline('IN');
    pl_close('IN');
    town_sprit($maketown_data);
    echo <<<EOM
    	<table border="0" cellspacing="0" cellpadding="10">
    	<tr><td valign="top" width="50%">
    	<div class="tyuu">{$in['town_n']}のレイアウト作成画面</div>
    	このフォームはその入力欄の位置がそのまま街のレイアウトに対応しています。<br>
    	右にある表を参考にして「記号」を入力欄に記述し、OKボタンを押すことで、その場所に記号に対応したパーツが建設されることになります。<br>
    	<span style="color:#ff6600">※設置後、数字のみが入力されていた場合、それは参加者の建築した家です（数字はその方のID）。この数字を変更してしまうと家がなくなってしまったり、街全体に不具合が出てきますので注意してください。</span><br>
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="make_town">
    	<input type="hidden" name="command" value="regi">
    	<input type="hidden" name="admin_pass" value="{$in['admin_pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="hidden" name="town_n" value="{$in['town_n']}">
    	<input type="hidden" name="zinkou" value="{$zinkou}">
    	<input type="hidden" name="keizai" value="{$keizai}">
    	<input type="hidden" name="hanei" value="{$hanei}">
    	<input type="hidden" name="t_yobi1" value="{$t_yobi1}">
    	<input type="hidden" name="t_yobi2" value="{$t_yobi2}">
    	<input type="hidden" name="t_yobi3" value="{$t_yobi3}">
    	<input type="hidden" name="t_yobi4" value="{$t_yobi4}">
    	<input type="hidden" name="t_yobi5" value="{$t_yobi5}">
    	<input type="hidden" name="t_yobi6" value="{$t_yobi6}">
    	<input type="hidden" name="t_yobi7" value="{$t_yobi7}">
    		<table border="0" cellspacing="0" cellpadding="0" width="400"><!--フォーム用の街スタート-->
            <tr valign="bottom"> <!--横軸の数字出力部分-->
             <td height="10" width="20"  align="center" class="sumi"> </td>
    EOM;
    // ヨコの番号部分出力
    foreach (pl_range(1, 16) as $yokoziku_koumoku) {
        echo "<td height=\"10\" width=\"32\" align=\"center\" class=\"migi\">{$yokoziku_koumoku}</td>\n";
    }
    echo '</tr>';
    // ヨコの番号(td)の数だけタテの記号(tr)を出力
    $i = 21;
    foreach (pl_range('a', 'l') as $tateziku_kigou) {
        echo "<tr valign=\"center\"><td height=\"32\" width=\"10\" align=\"center\" class=\"sita\">{$tateziku_kigou}</td>\n";
        foreach (pl_range(1, 16) as $yokoziku_bangou) {
            $name_seikei = 't_' . $tateziku_kigou . $yokoziku_bangou;
            echo "<td height=\"32\" width=\"32\"><input type=\"text\" size=\"4\" value=\"{$town_sprit_matrix[(n($yokoziku_bangou) + n($i))]}\" name=\"{$name_seikei}\" style=\"font-size:10px\"></td>\n";
        }
        echo "</tr>\n";
        $i = n($i) + 17;
    }
    echo <<<EOM
          </table>
    	<br><div align="center"><input type="submit" value=" O K ">
    	<!--TW_BACK-->
    	</div>
    	</form>
    	</td><td valign="top"><!--テーブル右部分-->
    	■パーツ＆記号対応表<br>
    	<FORM NAME="foMes5">
    <INPUT TYPE="text" SIZE="82" NAME="TeMes5" style="font-size:10px; color:#000000; background-color:#d6dbbf"></FORM>
    EOM;
    parts_taiou_hyou(...$_args);
    echo <<<EOM
    	</td></tr></table>
    	</body></html>
    EOM;
}

function byPartskeys(...$_args)
{
    global $a, $b;
    return pl_cmp($a, $b);
}

function parts_taiou_hyou(...$_args)
{
    global $_, $i, $parts_keys, $unit;
    echo <<<EOM
    	<table border="0" cellspacing="0" cellpadding="5" width="90%" bgcolor="#ffffcc">
    	<tr class="jouge" bgcolor="#ffff99" align="center"><td>記号</td><td>パーツ</td><td>記号</td><td>パーツ</td><td>記号</td><td>パーツ</td><td>記号</td><td>パーツ</td><td>記号</td><td>パーツ</td></tr><tr>
    EOM;
    $parts_keys = array_keys($unit ?? []);
    $parts_keys = pl_sort('byPartskeys', $parts_keys);
    $i = 1;
    $__s1 = $_ ?? null;
    foreach ($parts_keys ?? [] as $_) {
        if (($_ != '地')) {
            echo "<td align=\"right\" nowrap>{$_}</td>{$unit[$_]}";
            if (((n($i) % 5) == 0)) {
                echo '</tr><tr>';
            }
            $i++;
        }
    }
    $_ = $__s1;
    echo <<<EOM
    	</tr>
    	<tr><td colspan="8">
    	</td></tr></table>
    EOM;
    exit;
}

function admin_bbs(...$_args)
{
    global $admin_pass, $in;
    if (((string)$in['admin_pass'] !== (string)$admin_pass)) {
        error('パスワードが違います');
    }
    t_header(...$_args);
    bbs1_settei(...$_args);
    hooter('admin', '戻る', 'admin.php');
    exit;
}

function gyakujun(...$_args)
{
    global $a, $b, $keys;
    return (n($keys[n($a)]) <=> n($keys[n($b)]));
}

function byString(...$_args)
{
    global $a, $b, $keys;
    return pl_cmp($keys[n($a)], $keys[n($b)]);
}

function seijun(...$_args)
{
    global $a, $b, $keys;
    return (n($keys[n($b)]) <=> n($keys[n($a)]));
}

function itiran(...$_args)
{
    global $_, $admin_pass, $alldata, $check_flag, $check_list_host, $data, $f_count, $hour, $img_dir, $in, $key,
        $keys, $list_host, $list_job, $list_k_id, $list_k_yobi3, $list_last_syokuzi, $list_name, $list_sex,
        $list_sousisan, $logfile, $m_count, $maeno_data, $maeno_host, $mday, $min, $rankingMember, $sec, $seibetu,
        $tajuutouroku, $this_script, $total_count, $two, $wday;
    if (((string)$in['admin_pass'] !== (string)$admin_pass)) {
        error('パスワードが違います');
    }
    t_header(...$_args);
    if (!(pl_open('IN', (string)($logfile)))) { error("Open Error : {$logfile}"); }
    $rankingMember = pl_readlines('IN');
    $__s1 = $_ ?? null;
    foreach ($rankingMember ?? [] as $_) {
        $data = $_;
        $key = pl_idx(pl_split('/<>/u', $data), n($in['sort_id']));
        // ソートする要素を選ぶ
        $alldata[] = $data;
        $keys[] = $key;
    }
    $_ = $__s1;
    pl_close('IN');
    if (($in['sort_id'] == '')) {
        $alldata = pl_slice($alldata, pl_sort('gyakujun', pl_range(0, (pl_count($alldata) - 1))));
    } elseif ((((string)$in['sort_id'] === (string)'1') || ((string)$in['sort_id'] === (string)'28'))) {
        $alldata = pl_slice($alldata, pl_sort('byString', pl_range(0, (pl_count($alldata) - 1))));
    } else {
        $alldata = pl_slice($alldata, pl_sort('seijun', pl_range(0, (pl_count($alldata) - 1))));
    }
    // 同一ホストの配列を作成
    if (((string)$in['sort_id'] === (string)'28')) {
        $__s2 = $_ ?? null;
        foreach ($alldata ?? [] as $_) {
            list_sprit($_);
            if (((string)$list_host === (string)(string)($maeno_host))) {
                $tajuutouroku[] = $_;
                $check_flag = 0;
                foreach ($tajuutouroku ?? [] as $two) {
                    $__t3 = pl_split('/<>/u', $two); $check_list_host = array_slice($__t3, 0);
                    if (((string)$list_host === (string)(string)($check_list_host[28]))) {
                        $check_flag++;
                    }
                }
                if (((n($check_flag) <= 1) && ($maeno_data != ''))) {
                    $tajuutouroku[] = $maeno_data;
                }
            }
            $maeno_host = $list_host;
            $maeno_data = $_;
        }
        $_ = $__s2;
    }
    echo <<<EOM
    	<div style="text-align:center">
    	<div align="center" class="tyuu">登録者一覧（ランキングデータ）</div><br>
    <a href="{$this_script}?mode=itiran&sort_id=0&admin_pass={$in['admin_pass']}"><span class="sub3">[新規登録順]</span></a><img src="{$img_dir}/space.gif" width="5" height="1">
    <a href="{$this_script}?mode=itiran&admin_pass={$in['admin_pass']}"><span class="sub3">[ID順]</span></a><img src="{$img_dir}/space.gif" width="5" height="1">
    <a href="{$this_script}?mode=itiran&sort_id=49&admin_pass={$in['admin_pass']}"><span class="sub3">[資産順]</span></a><img src="{$img_dir}/space.gif" width="5" height="1">
    <a href="{$this_script}?mode=itiran&sort_id=1&admin_pass={$in['admin_pass']}"><span class="sub3">[名前順]</span></a><img src="{$img_dir}/space.gif" width="5" height="1">
    <a href="{$this_script}?mode=itiran&sort_id=38&admin_pass={$in['admin_pass']}"><span class="sub3">[最後の食事順]</span></a><img src="{$img_dir}/space.gif" width="5" height="1">
    <a href="{$this_script}?mode=itiran&sort_id=28&admin_pass={$in['admin_pass']}"><span class="sub3">[ホスト順]</span></a><img src="{$img_dir}/space.gif" width="5" height="1">
    </div>
    
    	<table border="0" width="95%" align="center" cellspacing="1" cellpadding="5">
    	<tr><td colspan="10">●名前をクリックするとその人の個人データにアクセスできます。またその画面で「ユーザー削除」「各種データの修正」「お金の振り込み（減金）」「メール送信」等が行えます。<br>※ブラックリストとは二重登録者として記録されたユーザーです。<br>※このリストの数字はすべてデータ保存時のもので、今現在のデータとは異なります。
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="kensaku">
    	<input type="hidden" name="command" value="kozin_file">
    	<input type="hidden" name="name" value="{$list_name}">
    	<input type="hidden" name="admin_pass" value="{$in['admin_pass']}">
    	ID番号 <input type="text" name="k_id" size="6">こちらでIDを入力して個人データを開くこともできます。
    	</form>
    
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="kensaku">
    	<input type="hidden" name="command" value="kozin_file">
    	<input type="hidden" name="admin_pass" value="{$in['admin_pass']}">
    	名前 <input type="text" name="name" size="20">こちらで名前を入力して個人データを開くこともできます。
    	</form>
    
    	</td></tr>
    	<tr  class="jouge" bgcolor="#ffff66"><td align="center" >ID</td><td align="center"  width="120">名前</td><td align="center" >性別</td><td align="center" >資産</td><td align="center" >職業</td><td align="center" >最後の食事</td><td align="center" >ホスト</td><td align="center" >ブラックリスト</td></tr>
    EOM;
    // 多重登録者表示
    if (((string)$in['sort_id'] === (string)'28')) {
        echo '<tr  class="jouge" bgcolor="#bbbbbb"><td colspan="8">多重登録者（同一IP or ホストでの登録者）</td></tr>';
        $__s4 = $_ ?? null;
        foreach ($tajuutouroku ?? [] as $_) {
            list_sprit($_);
            // 性別表示を整形
            if (($list_sex == 'f')) {
                $seibetu = '女';
            } else {
                $seibetu = '男';
            }
            // 最終食事表示を整形
            $_SERVER['TZ'] = 'JST-9';
            [$sec, $min, $hour, $mday, $mon, $year, $wday] = pl_pad(pl_localtime(n($list_last_syokuzi)), 7);
            $mon = (n($mon) + 1);
            $year = (n($year) + 1900);
            $a_date = "{$year}年{$mon}月{$mday}日  {$hour}時{$min}分";
            // リストを順に表示
            echo <<<EOM
            <tr class="sita2"><td align="center">{$list_k_id}</td><td nowrap><a href="{$this_script}?mode=kensaku&command=kozin_file&k_id={$list_k_id}&admin_pass={$in['admin_pass']}">{$list_name}</a></td><td align="center">{$seibetu}</td><td align="right" nowrap>{$list_sousisan}円</td><td>{$list_job}</td><td align="left" nowrap>{$a_date}</td><td align="left" nowrap>{$list_host}</td><td align="left" nowrap>{$list_k_yobi3}</td></tr>
            EOM;
        }
        $_ = $__s4;
        // foreach閉じ
        echo '<tr  class="sita2" bgcolor="#ffffcc"><td colspan="8">以下全参加者のリスト</td></tr>';
    }
    $__s5 = $_ ?? null;
    foreach ($alldata ?? [] as $_) {
        list_sprit($_);
        if (($list_sex == 'f')) {
            $seibetu = '女';
            $f_count++;
        } else {
            $seibetu = '男';
            $m_count++;
        }
        // 最終食事表示を整形
        $_SERVER['TZ'] = 'JST-9';
        [$sec, $min, $hour, $mday, $mon, $year, $wday] = pl_pad(pl_localtime(n($list_last_syokuzi)), 7);
        $mon = (n($mon) + 1);
        $year = (n($year) + 1900);
        $a_date = "{$year}年{$mon}月{$mday}日  {$hour}時{$min}分";
        echo <<<EOM
        <tr class="sita2"><td align="center">{$list_k_id}</td><td nowrap><a href="{$this_script}?mode=kensaku&command=kozin_file&k_id={$list_k_id}&admin_pass={$in['admin_pass']}">{$list_name}</a></td><td align="center">{$seibetu}</td><td align="right" nowrap>{$list_sousisan}円</td><td>{$list_job}</td><td align="left" nowrap>{$a_date}</td><td align="left" nowrap>{$list_host}</td><td align="left" nowrap>{$list_k_yobi3}</td></tr>
        EOM;
    }
    $_ = $__s5;
    pl_close('IN');
    $total_count = (n($m_count) + n($f_count));
    echo <<<EOM
    </table>
    	<div style="text-align:center"><br>
    <a href="{$this_script}?mode=itiran&sort_id=0&admin_pass={$in['admin_pass']}"><span class="sub3">[新規登録順]</span></a><img src="{$img_dir}/space.gif" width="5" height="1">
    <a href="{$this_script}?mode=itiran&admin_pass={$in['admin_pass']}"><span class="sub3">[ID順]</span></a><img src="{$img_dir}/space.gif" width="5" height="1">
    <a href="{$this_script}?mode=itiran&sort_id=49&admin_pass={$in['admin_pass']}"><span class="sub3">[資産順]</span></a><img src="{$img_dir}/space.gif" width="5" height="1">
    <a href="{$this_script}?mode=itiran&sort_id=1&admin_pass={$in['admin_pass']}"><span class="sub3">[名前順]</span></a><img src="{$img_dir}/space.gif" width="5" height="1">
    <a href="{$this_script}?mode=itiran&sort_id=38&admin_pass={$in['admin_pass']}"><span class="sub3">[最後の食事順]</span></a><img src="{$img_dir}/space.gif" width="5" height="1">
    <a href="{$this_script}?mode=itiran&sort_id=28&admin_pass={$in['admin_pass']}"><span class="sub3">[ホスト順]</span></a><img src="{$img_dir}/space.gif" width="5" height="1">
    </div>
    <br><div align="center">総登録者数：{$total_count}人　男性：{$m_count}人　女性：{$f_count}人<br><br>
    </div>
    	</body></html>
    EOM;
    hooter('admin', '戻る', 'admin.php');
    exit;
}

function kensaku(...$_args)
{
    global $_, $a_hour, $a_mday, $a_min, $a_mon, $a_sec, $a_wday, $a_year, $access_byou, $access_date, $admin_name,
        $admin_pass, $backup_comment, $bank, $bijutu, $byouki_sisuu, $byoumei, $data_syuusei_title, $eigo, $energy,
        $etti, $host, $hour, $hukkatu_botan, $hukkatu_flag, $in, $job, $job_kaisuu, $job_keiken, $jobsyu, $k_id,
        $k_sousisan, $k_yobi3, $kenkou, $kensaku_ID, $kokugo, $kyakuryoku, $last_syokuzi, $loan_kaisuu,
        $loan_nitigaku, $logfile, $looks, $love, $mday, $min, $mon, $money, $my_backlog_file, $my_log_file, $my_prof,
        $name, $nou_energy, $ongaku, $page_comment, $power, $rika, $s_date, $script, $sec, $seibetu, $sex, $sintyou,
        $speed, $super_teiki, $suugaku, $syakai, $taijuu, $tairyoku, $this_script, $unique, $wanryoku, $wday, $year;
    if (((string)$in['admin_pass'] !== (string)$admin_pass)) {
        error('パスワードが違います');
    }
    // 名前クリックまたはID検索の場合
    if ($in['k_id']) {
        $kensaku_ID = (string)($in['k_id']);
        // 名前検索の場合
    } elseif ($in['name']) {
        if (!(pl_open('HUK', (string)($logfile)))) { error("Open Error : {$logfile}"); }
        $hukkatu_flag = 0;
        while (($_ = pl_readline('HUK')) !== null) {
            kozin_sprit(...$_args);
            if (((string)$in['name'] === (string)$name)) {
                $hukkatu_flag = 1;
                $kensaku_ID = (string)($k_id);
                break;
            }
        }
        pl_close('HUK');
        if ((n($hukkatu_flag) == 0)) {
            error("{$in['name']}の名前で登録されている方はいません");
        }
    } else {
        error('検索対象がわかりません');
    }
    // 個人ファイル検索の場合
    if (($in['command'] == 'kozin_file')) {
        $my_log_file = './member/' . (string)($kensaku_ID) . '/log.cgi';
        // ver.1.2ここから
        $my_backlog_file = './member/' . (string)($kensaku_ID) . 'backup/backup_log.cgi';
        // if (! -e $my_log_file){&error("ID番号$kensaku_IDのログファイルは存在しません");}
        if (!(pl_exists($my_log_file))) {
            $backup_comment = '<br>ログファイルが存在しないためバックアップデータを開いています。データ復活ボタンを押してデータを復活させることができます。';
            $my_log_file = $my_backlog_file;
            if (!(pl_exists($my_backlog_file))) {
                error("ID番号{$kensaku_ID}のログファイルは存在しません");
            }
        }
        // ver.1.2ここまで
        if (!(pl_open('MYL', (string)($my_log_file)))) { error("ログファイル（{$my_log_file}）が開けません"); }
        $my_prof = pl_readline('MYL');
        kozin_sprit2($my_prof);
        pl_close('MYL');
        $hukkatu_botan = <<<EOM
	<form method="POST" action="{$this_script}">
	<input type="hidden" name="mode" value="deletUsr">
	<input type="hidden" name="command" value="del_kakunin">
	<input type="hidden" name="name" value="{$name}">
	<input type="hidden" name="k_id" value="{$kensaku_ID}">
	<input type="hidden" name="admin_pass" value="{$in['admin_pass']}">
	<input type="submit" value="このユーザーを削除する">
	</form>
EOM;
        $page_comment = "{$name}さんの個人ファイルデータです。{$backup_comment}";
        // ver.1.2
        $data_syuusei_title = <<<EOM
			<div class="honbun2">■データ修正</div>
	※入力欄がある項目はこちらでデータを変更することができます。ブラックリストに文字が記録されていてログインできない方のログインを許可をする場合は、ブラックリストの文字を消去してください。
EOM;
        // データ復活の場合
    } elseif (($in['command'] == 'hukkatu')) {
        $my_log_file = './member/' . (string)($kensaku_ID) . 'backup/backup_log.cgi';
        // ver.1.2
        if (!(pl_exists($my_log_file))) {
            error("ID番号{$kensaku_ID}のログファイルは存在しません");
        }
        if (!(pl_open('MYL', (string)($my_log_file)))) { error("ログファイル（{$my_log_file}）が開けません"); }
        $my_prof = pl_readline('MYL');
        kozin_sprit2($my_prof);
        pl_close('MYL');
        $hukkatu_botan = <<<EOM
	<form method="POST" action="{$this_script}">
	<input type="hidden" name="mode" value="hukkatu">
	<input type="hidden" name="k_id" value="{$kensaku_ID}">
	<input type="hidden" name="admin_pass" value="{$in['admin_pass']}">
	<input type="submit" value="このデータで復活させる">
	</form>
EOM;
        $page_comment = "{$name}さんのバックアップされているデータです。";
        $data_syuusei_title = '<div class="honbun2">■保存されているデータ</div>';
    }
    t_header(...$_args);
    // 性別表示を整形
    if (($sex == 'f')) {
        $seibetu = '女';
    } else {
        $seibetu = '男';
    }
    // 最終食事表示を整形
    $_SERVER['TZ'] = 'JST-9';
    [$sec, $min, $hour, $mday, $mon, $year, $wday] = pl_pad(pl_localtime(n($last_syokuzi)), 7);
    $mon = (n($mon) + 1);
    $year = (n($year) + 1900);
    $s_date = "{$year}/{$mon}/{$mday}  {$hour} : {$min}";
    // アクセス日時を整形
    $_SERVER['TZ'] = 'JST-9';
    [$a_sec, $a_min, $a_hour, $a_mday, $a_mon, $a_year, $a_wday] = pl_pad(pl_localtime(n($access_byou)), 7);
    $a_mon = (n($a_mon) + 1);
    $a_year = (n($a_year) + 1900);
    $access_date = "{$a_year}/{$a_mon}/{$a_mday}  {$a_hour} : {$a_min}";
    echo <<<EOM
    	<div align="center" class="tyuu">{$page_comment}</div><br><br>
    	<table width="95%" border="0" cellspacing="0" cellpadding="5" align="center" style="font-size:11px" class="yosumi"><tr><td>
    			<form method="POST" action="{$this_script}">
    			<input type="hidden" name="mode" value="kozindata_henkou">
    			<input type="hidden" name="admin_pass" value="{$in['admin_pass']}">
    			<input type="hidden" name="k_id" value="{$k_id}">
    			<input type="hidden" name="name" value="{$name}">
    	<table width="95%" border="0" cellspacing="0" cellpadding="3" align="center" style="font-size:11px">
    	<tr><td colspan="6">
    	{$data_syuusei_title}
    	</td></tr>
    	<tr>
    	<td>●名前<br>{$name}</td>
    	<td>●性別<br><input type="text" size="5" value="{$seibetu}" name="seibetu">※男 or 女</td>
    	<td>●パスワード<br><input type="text" size="16" value="" name="pass" autocomplete="new-password"><br>※変更する場合のみ入力</td>
    	<td>●身長<br><input type="text" size="5" value="{$sintyou}" name="sintyou">cm</td>
    	<td>●体重<br><input type="text" size="10" value="{$taijuu}" name="taijuu">kg</td>
    	<td>●最終アクセス<br>{$access_date}</td></tr><tr>
    
    	<td>●総資産<br>{$k_sousisan}円</td>
    	<td>●持ち金<br><input type="text" size="16" value="{$money}" name="money">円</td>
    	<td>●銀行<br><input type="text" size="16" value="{$bank}" name="bank">円</td>
    	<td>●スーパー定期<br><input type="text" size="16" value="{$super_teiki}" name="super_teiki">円</td>
    	<td nowrap>●ローン日額<br><input type="text" size="16" value="{$loan_nitigaku}" name="loan_nitigaku">円</td>
    	<td>●ローン残回数<br><input type="text" size="5" value="{$loan_kaisuu}" name="loan_kaisuu">回</td></tr><tr>
    
    	<td>●最後の食事<br>{$s_date}</td>
    	<td>●身体エネルギー<br><input type="text" size="10" value="{$energy}" name="energy"></td>
    	<td>●頭脳エネルギー<br><input type="text" size="10" value="{$nou_energy}" name="nou_energy"></td>
    	<td colspan="2">●ホスト<br>{$host}</td>
    	<td>●ブラックリスト<br><input type="text" size="20" value="{$k_yobi3}" name="k_yobi3"></td></tr><tr>
    
    	<td>●病気<br><input type="text" size="20" value="{$byoumei}" name="byoumei"></td>
    	<td>●病気指数<br><input type="text" size="20" value="{$byouki_sisuu}" name="byouki_sisuu"></td>
    	<td>●職業<br><input type="text" size="24" value="{$job}" name="job"></td>
    	<td>●マスター職業<br><input type="text" size="24" value="{$jobsyu}" name="jobsyu"></td>
    	<td>●仕事経験値<br><input type="text" size="10" value="{$job_keiken}" name="job_keiken"></td>
    	<td>●勤務回数<br><input type="text" size="10" value="{$job_kaisuu}" name="job_kaisuu">回</td></tr><tr>
    	</table><br>
    
    	<table width="95%" border="0" cellspacing="0" cellpadding="3" align="center"><tr>
    	<td>●国　　語 <input type="text" size="6" value="{$kokugo}" name="kokugo"></td>
    	<td>●数　学 <input type="text" size="6" value="{$suugaku}" name="suugaku"></td>
    	<td>●理　科 <input type="text" size="6" value="{$rika}" name="rika"></td>
    	<td>●社　　会 <input type="text" size="6" value="{$syakai}" name="syakai"></td>
    	<td>●英　語 <input type="text" size="6" value="{$eigo}" name="eigo"></td>
    	<td>●音　楽 <input type="text" size="6" value="{$ongaku}" name="ongaku"></td>
    	<td>●美　術 <input type="text" size="6" value="{$bijutu}" name="bijutu"></td></tr><tr>
    	<td>●ルックス <input type="text" size="6" value="{$looks}" name="looks"></td>
    	<td>●体　力 <input type="text" size="6" value="{$tairyoku}" name="tairyoku"></td>
    	<td>●健　康 <input type="text" size="6" value="{$kenkou}" name="kenkou"></td>
    	<td>●スピード <input type="text" size="6" value="{$speed}" name="speed"></td>
    	<td>●パワー <input type="text" size="6" value="{$power}" name="power"></td>
    	<td>●腕　力 <input type="text" size="6" value="{$wanryoku}" name="wanryoku"></td>
    	<td>●脚　力 <input type="text" size="6" value="{$kyakuryoku}" name="kyakuryoku"></td></tr><tr>
    	<td>●ＬＯＶＥ <input type="text" size="6" value="{$love}" name="love"></td>
    	<td>●面白さ <input type="text" size="6" value="{$unique}" name="unique"></td>
    	<td>●エッチ <input type="text" size="6" value="{$etti}" name="etti"></td>
    	</tr></table>
    EOM;
    if (($in['command'] == 'kozin_file')) {
        echo '<div align="center"><input type="submit" value="データ修正"></div>';
    }
    echo <<<EOM
    	</td></tr></table></form>
    	<br><br>
    EOM;
    if (($in['command'] == 'kozin_file')) {
        echo <<<EOM
        	<table width="95%" border="0" cellspacing="0" cellpadding="5" align="center" style="font-size:11px" class="yosumi"><tr><td colspan="2">
        	<div class="honbun2">■振り込み</div>
        	※任意のお金を振り込むことができます。振り込み者の名前は初期設定の「管理者名」になります。マイナスの金額を入力することで減金させることができます。
        	</td></tr><tr><td width="220">
        	{$name}さんにお金を振り込む</td><td>
        	<form method="POST" action="basic.php">
        	<input type="hidden" name="mode" value="ginkoufurikomi">
        	<input type="hidden" name="command" value="from_system">
        	<input type="hidden" name="name" value="{$admin_name}">
        	<input type="hidden" name="admin_pass" value="{$in['admin_pass']}">
        	<input type="hidden" name="k_id" value="{$kensaku_ID}">
        	<input type="hidden" name="aitenonamae" value="{$name}">
        	振り込み金額 <input type="text" name="hurikomigaku" size="12">円
        	<input type="submit" value="振り込み">
        	</form>
        	</td></tr></table>
        	<br><br>
        	<table width="95%" border="0" cellspacing="0" cellpadding="5" align="center" style="font-size:11px" class="yosumi"><tr><td width="300">
        	<div class="honbun2">■メッセージ送信</div>
        	※{$name}さんにメッセージを送信することができます。送信者名は初期設定の「管理者名」になります。
        	</td><td>
        	<form method="POST" action="{$script}">
        	<input type="hidden" name="mode" value="mail_do">
        	<input type="hidden" name="command" value="from_system">
        	<input type="hidden" name="name" value="{$admin_name}">
        	<input type="hidden" name="admin_pass" value="{$in['admin_pass']}">
        	<input type="hidden" name="sousinsaki_name" value="{$name}">
        	<textarea cols="50" rows="4" name="m_com" wrap="soft"></textarea>
        	<input type="submit" value="メッセージ送信">
        	</form></td></tr></table>
        	<br><br>
        
        <table width="95%" border="0" cellspacing="0" cellpadding="5" align="center" style="font-size:11px" class="yosumi"><tr><td width="300">
        	<div class="honbun2">■バックアップデータの復活</div>
        	※{$name}さんの現行データを最後に保存されたデータに切り替えます。
        	</td><td>
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="kensaku">
        	<input type="hidden" name="command" value="hukkatu">
        	<input type="hidden" name="k_id" value="{$kensaku_ID}">
        	<input type="hidden" name="admin_pass" value="{$in['admin_pass']}">
        	<input type="hidden" name="hukkatu_name" value="{$name}">
        	<input type="submit" value="データ復活">
        	</form></td></tr></table>
        	<br><br>
        EOM;
    }
    echo <<<EOM
    	<div align="center">{$hukkatu_botan}
    	<br><!--TW_BACK--></div>
    	</body></html>
    EOM;
    exit;
}

function kozindata_henkou(...$_args)
{
    global $_, $admin_pass, $bank, $bijutu, $byouki_sisuu, $byoumei, $eigo, $energy, $err, $etti, $henkou_seibetu,
        $in, $job, $job_kaisuu, $job_keiken, $jobsyu, $k_temp, $k_yobi3, $kenkou, $kokugo, $kyakuryoku, $list_bank,
        $list_bijutu, $list_byouki_sisuu, $list_byoumei, $list_eigo, $list_energy, $list_etti, $list_job,
        $list_job_kaisuu, $list_job_keiken, $list_jobsyu, $list_k_yobi3, $list_kenkou, $list_kokugo,
        $list_kyakuryoku, $list_loan_kaisuu, $list_loan_nitigaku, $list_looks, $list_love, $list_money, $list_name,
        $list_nou_energy, $list_ongaku, $list_pass, $list_power, $list_rika, $list_sex, $list_sintyou, $list_speed,
        $list_super_teiki, $list_suugaku, $list_syakai, $list_taijuu, $list_tairyoku, $list_temp, $list_unique,
        $list_wanryoku, $loan_kaisuu, $loan_nitigaku, $logfile, $looks, $love, $mem_lock_num, $money, $my_log_file,
        $my_prof, $name, $new_ranking_data, $nou_energy, $ongaku, $pass, $power, $rankingMember, $rika, $sex,
        $sintyou, $sonzai_flag, $sousisan, $speed, $super_teiki, $suugaku, $syakai, $taijuu, $tairyoku, $unique,
        $wanryoku;
    if (((string)$in['admin_pass'] !== (string)$admin_pass)) {
        error('パスワードが違います');
    }
    // リストデータ修正
    if (!(pl_open('IN', (string)($logfile)))) { error("Open Error : {$logfile}"); }
    $rankingMember = pl_readlines('IN');
    pl_close('IN');
    $sonzai_flag = 0;
    if (($in['seibetu'] == '男')) {
        $henkou_seibetu = 'm';
    } elseif (($in['seibetu'] == '女')) {
        $henkou_seibetu = 'f';
    } else {
        error('性別は「男」か「女」にしてください');
    }
    $__s1 = $_ ?? null;
    foreach ($rankingMember ?? [] as $_) {
        list_sprit($_);
        if (((string)$list_name === (string)(string)($in['name']))) {
            $sonzai_flag = 1;
            $list_name = $in['name'];
            $list_sex = $henkou_seibetu;
            if (($in['pass'] != '')) {
                $list_pass = pl_admin_newpass(...$_args);
            }
            $list_sintyou = $in['sintyou'];
            $list_taijuu = $in['taijuu'];
            // $list_sousisan = $in{'k_sousisan'};		ver.1.3
            $list_money = $in['money'];
            $list_bank = $in['bank'];
            $list_super_teiki = $in['super_teiki'];
            $list_loan_nitigaku = $in['loan_nitigaku'];
            $list_loan_kaisuu = $in['loan_kaisuu'];
            $list_energy = $in['energy'];
            $list_nou_energy = $in['nou_energy'];
            $list_k_yobi3 = $in['k_yobi3'];
            $list_byoumei = $in['byoumei'];
            $list_byouki_sisuu = $in['byouki_sisuu'];
            $list_job = $in['job'];
            $list_jobsyu = $in['jobsyu'];
            $list_job_keiken = $in['job_keiken'];
            $list_job_kaisuu = $in['job_kaisuu'];
            $list_kokugo = $in['kokugo'];
            $list_suugaku = $in['suugaku'];
            $list_rika = $in['rika'];
            $list_syakai = $in['syakai'];
            $list_eigo = $in['eigo'];
            $list_ongaku = $in['ongaku'];
            $list_bijutu = $in['bijutu'];
            $list_looks = $in['looks'];
            $list_tairyoku = $in['tairyoku'];
            $list_kenkou = $in['kenkou'];
            $list_speed = $in['speed'];
            $list_power = $in['power'];
            $list_wanryoku = $in['wanryoku'];
            $list_kyakuryoku = $in['kyakuryoku'];
            $list_love = $in['love'];
            $list_unique = $in['unique'];
            $list_etti = $in['etti'];
        }
        list_temp(...$_args);
        $new_ranking_data[] = $list_temp;
    }
    $_ = $__s1;
    if ((n($sonzai_flag) == 0)) {
        error('リストにその名前の参加者が見つかりません');
    }
    // 個人データ修正
    $my_log_file = "./member/{$in['k_id']}/log.cgi";
    if (!(pl_open('MYL', (string)($my_log_file)))) { error("Open Error : {$my_log_file}"); }
    $my_prof = pl_readline('MYL');
    kozin_sprit2($my_prof);
    pl_close('MYL');
    $name = $in['name'];
    $sex = $henkou_seibetu;
    if (($in['pass'] != '')) {
        $pass = pl_admin_newpass(...$_args);
    }
    $sintyou = $in['sintyou'];
    $taijuu = $in['taijuu'];
    $sousisan = $in['k_sousisan'];
    $money = $in['money'];
    $bank = $in['bank'];
    $super_teiki = $in['super_teiki'];
    $loan_nitigaku = $in['loan_nitigaku'];
    $loan_kaisuu = $in['loan_kaisuu'];
    $energy = $in['energy'];
    $nou_energy = $in['nou_energy'];
    $k_yobi3 = $in['k_yobi3'];
    $byoumei = $in['byoumei'];
    $byouki_sisuu = $in['byouki_sisuu'];
    $job = $in['job'];
    $jobsyu = $in['jobsyu'];
    $job_keiken = $in['job_keiken'];
    $job_kaisuu = $in['job_kaisuu'];
    $kokugo = $in['kokugo'];
    $suugaku = $in['suugaku'];
    $rika = $in['rika'];
    $syakai = $in['syakai'];
    $eigo = $in['eigo'];
    $ongaku = $in['ongaku'];
    $bijutu = $in['bijutu'];
    $looks = $in['looks'];
    $tairyoku = $in['tairyoku'];
    $kenkou = $in['kenkou'];
    $speed = $in['speed'];
    $power = $in['power'];
    $wanryoku = $in['wanryoku'];
    $kyakuryoku = $in['kyakuryoku'];
    $love = $in['love'];
    $unique = $in['unique'];
    $etti = $in['etti'];
    // ログ更新
    temp_routin(...$_args);
    log_kousin($my_log_file, $k_temp);
    lock(...$_args);
    // （ver.1.21ここから）
    if ((n($mem_lock_num) == 0)) {
        $err = data_save($logfile, ...($new_ranking_data ?? []));
        if ($err) {
            error((string)($err));
        }
    } else {
        if (!(pl_open('OUT', ">{$logfile}"))) { error("Write Error : {$logfile}"); }
        pl_print('OUT', pl_join('', $new_ranking_data));
        pl_close('OUT');
    }
    // （ver.1.21ここまで）
    unlock(...$_args);
    message('データ修正をしました。', 'itiran', 'admin.php');
    kensaku(...$_args);
}

function deletUsr(...$_args)
{
    global $_, $admin_pass, $alldata, $as_new_pro_alldata, $as_profile_file, $dir, $err, $file_name, $in, $list_k_id,
        $logfile, $mem_lock_num, $new_pro_alldata, $profile_file, $rankingMember, $this_script;
    if (((string)$in['admin_pass'] !== (string)$admin_pass)) {
        error('パスワードが違います');
    }
    if (($in['command'] == 'del_kakunin')) {
        t_header(...$_args);
        echo <<<EOM
        			<br><br><div align="center">{$in['name']}さんを削除します。よろしいですか？
        			<form method="POST" action="{$this_script}">
        			<input type="hidden" name="mode" value="deletUsr">
        			<input type="hidden" name="name" value="{$in['name']}">
        			<input type="hidden" name="k_id" value="{$in['k_id']}">
        			<input type="hidden" name="admin_pass" value="{$in['admin_pass']}">
        			<input type="submit" value="O K">
        			</form>
        			<!--TW_BACK-->
        			</div>
        			</body></html>
        EOM;
        exit;
    }
    // 確認画面の場合の閉じ
    lock(...$_args);
    // ver.1.3ロック位置変更
    // 家削除処理
    ie_sakujo_syori($in['name']);
    // フォルダー内のファイル名を取得して削除。個人フォルダーの削除
    $dir = pl_opendir('./member/' . (string)($in['k_id']));
    while (($file_name = pl_readdir($dir)) !== null) {
        // 1つ読み込んで$folder_nameに代入
        pl_unlink("./member/{$in['k_id']}/{$file_name}");
    }
    pl_closedir($dir);
    // ディレクトリを閉じる
    if (!(pl_rmdir("./member/{$in['k_id']}"))) { error("memberディレクトリ内、{$in['k_id']}ディレクトリのデータ削除ができません"); }
    // メンバーリストから削除
    if (!(pl_open('IN', (string)($logfile)))) { error("Open Error : {$logfile}"); }
    $rankingMember = pl_readlines('IN');
    $__s1 = $_ ?? null;
    foreach ($rankingMember ?? [] as $_) {
        list_sprit($_);
        if (((string)$in['k_id'] === (string)(string)($list_k_id))) {
            continue;
        }
        $alldata[] = $_;
    }
    $_ = $__s1;
    // プロフィール＆結婚斡旋所削除処理		ver.1.3
    prof_sakujo($in['name']);
    // ver.1.3
    as_prof_sakujo($in['name']);
    // ver.1.3
    // （ver.1.21ここから）
    if ((n($mem_lock_num) == 0)) {
        $err = data_save($logfile, ...($alldata ?? []));
        if ($err) {
            error((string)($err));
        }
    } else {
        if (!(pl_open('OUT', ">{$logfile}"))) { error("{$logfile}が開けません"); }
        pl_print('OUT', pl_join('', $alldata));
        pl_close('OUT');
    }
    // （ver.1.21ここまで）
    if (!(pl_open('PROO', ">{$profile_file}"))) { error("{$profile_file}に書き込めません"); }
    pl_print('PROO', pl_join('', $new_pro_alldata));
    pl_close('PROO');
    if (!(pl_open('ASPO', ">{$as_profile_file}"))) { error("{$as_profile_file}に書き込めません"); }
    pl_print('ASPO', pl_join('', $as_new_pro_alldata));
    pl_close('ASPO');
    unlock(...$_args);
    message("{$in['name']}さんを削除しました。", 'itiran', 'admin.php');
}

function hukkatu(...$_args)
{
    global $_, $back_data, $back_folder_pass, $back_logdata, $check_log, $dir, $err, $file_name, $hukkatu_folder,
        $in, $list_aru_flag, $list_k_id, $logfile, $mem_lock_num, $orig_file_name, $zidouseisei;
    lock(...$_args);
    // ver.1.2ココから
    // 復活させるIDのフォルダが無い場合作成
    $hukkatu_folder = "./member/{$in['k_id']}";
    if (!(pl_exists((string)($hukkatu_folder)))) {
        if (!(pl_mkdir($hukkatu_folder))) { error('Error : can not Make Directry'); }
        if ((n($zidouseisei) == 1)) {
            true;
        } elseif ((n($zidouseisei) == 2)) {
            true;
        } else {
            true;
        }
    }
    // ver.1.2ココまで
    // フォルダー内のbackup_がついたファイル名を取得してバックアップデータを復活
    $back_folder_pass = './member/' . (string)($in['k_id']) . 'backup';
    // ver.1.2
    $dir = pl_opendir((string)($back_folder_pass));
    // ver.1.2
    while (($file_name = pl_readdir($dir)) !== null) {
        // 1つ読み込んで$folder_nameに代入
        if (pl_match('/^backup_/u', $file_name)) {
            if (!(pl_open('BK', "{$back_folder_pass}/{$file_name}"))) { error("Open Error : ./member/{$in['k_id']}/{$file_name}"); }
            // ver.1.2
            $back_data = pl_readlines('BK');
            if (($file_name == 'backup_log.cgi')) {
                $back_logdata = $back_data;
            }
            pl_close('BK');
            $orig_file_name = pl_substr($file_name, 7);
            pl_open('BKO', ">./member/{$in['k_id']}/{$orig_file_name}");
            pl_print('BKO', pl_join('', $back_data));
            pl_close('BKO');
        }
    }
    pl_closedir($dir);
    // ディレクトリを閉じる
    $check_log = pl_tolist(data_read($logfile));
    $list_aru_flag = 0;
    $__s1 = $_ ?? null;
    foreach ($check_log ?? [] as $_) {
        list_sprit($_);
        if (((string)$list_k_id === (string)(string)($in['k_id']))) {
            $list_aru_flag = 1;
            break;
        }
    }
    $_ = $__s1;
    // （ver.1.40ここから）
    if ((n($list_aru_flag) == 0)) {
        pl_push($check_log, ...($back_logdata ?? []));
        if ((n($mem_lock_num) == 0)) {
            $err = data_save($logfile, ...($check_log ?? []));
            if ($err) {
                error((string)($err));
            }
        } else {
            if (!(pl_open('CKO', ">{$logfile}"))) { error("Write Error : {$logfile}"); }
            pl_print('CKO', pl_join('', $check_log));
            pl_close('CKO');
        }
    }
    // （ver.1.40ここまで）
    unlock(...$_args);
    message('最後に保存したデータに切り替えました。', 'itiran', 'admin.php');
}

function by_syu_keys(...$_args)
{
    global $a, $b, $keys;
    return pl_cmp($keys[n($a)], $keys[n($b)]);
}

function ad_orosi_kousin(...$_args)
{
    global $_, $alldata, $data, $date2, $i, $key, $keys, $maintown_logfile, $maintown_para, $mt_orosiflag, $mt_temp,
        $mt_yobi9, $new_syouhin_hairetu, $new_syouhin_hairetu2, $orosi_logfile, $orosi_sinakazu, $syo_syubetu,
        $syo_temp, $syo_zaiko, $ton_zaiko_tyousei, $top_koumoku;
    lock(...$_args);
    // 商品データログを開く
    if (!(pl_open('OL', './dat_dir/syouhin.dat'))) { error('Open Error : ./dat_dir/syouhin.dat'); }
    $top_koumoku = pl_readline('OL');
    // 商品をランダムに並び替えてログを更新
    $new_syouhin_hairetu = [];
    pl_srand(n(((int)n(getmypid()) | (int)pl_time())));
    while (($_ = pl_readline('OL')) !== null) {
        $r = pl_rand((pl_count($new_syouhin_hairetu) + 1));
        $new_syouhin_hairetu[] = $new_syouhin_hairetu[n($r)];
        $new_syouhin_hairetu[n($r)] = $_;
    }
    pl_close('OL');
    $i = 0;
    $__s1 = $_ ?? null;
    foreach ($new_syouhin_hairetu ?? [] as $_) {
        syouhin_sprit($_);
        if (($syo_syubetu == '食')) {
            continue;
        }
        $syo_zaiko = pl_int((n($syo_zaiko) * n($ton_zaiko_tyousei)));
        syouhin_temp(...$_args);
        $new_syouhin_hairetu2[] = $syo_temp;
        $i++;
        if ((n($i) >= n($orosi_sinakazu))) {
            break;
        }
    }
    $_ = $__s1;
    // 種別でソート
    $__s2 = $_ ?? null;
    foreach ($new_syouhin_hairetu2 ?? [] as $_) {
        $data = $_;
        $key = pl_idx(pl_split('/<>/u', $data), 0);
        $alldata[] = $data;
        $keys[] = $key;
    }
    $_ = $__s2;
    $alldata = pl_slice($alldata, pl_sort('by_syu_keys', pl_range(0, (pl_count($alldata) - 1))));
    if (!(pl_open('OLOUT', ">{$orosi_logfile}"))) { error("{$orosi_logfile}に書き込みが出来ません"); }
    pl_print('OLOUT', pl_join('', $alldata));
    pl_close('OLOUT');
    // 更新日時を変更
    if (!(pl_open('IN', (string)($maintown_logfile)))) { error("Open Error : {$maintown_logfile}"); }
    $maintown_para = pl_readline('IN');
    main_town_sprit($maintown_para);
    pl_close('IN');
    time_get(...$_args);
    // 卸フラグを１にしてメインタウンログを更新
    $mt_orosiflag = 1;
    $mt_yobi9 = $date2;
    // 卸更新日時を記録
    main_town_temp(...$_args);
    if (!(pl_open('OUT', ">{$maintown_logfile}"))) { error("Write Error : {$maintown_logfile}"); }
    pl_print('OUT', $mt_temp);
    pl_close('OUT');
    unlock(...$_args);
    message('卸商品を更新しました。', 'admin', 'admin.php');
}

function prof_sakujo(...$_args)
{
    global $_, $new_pro_alldata, $profile_file;
    if (!(pl_open('PRO', (string)($profile_file)))) { error("Open Error : {$profile_file}"); }
    $pro_alldata = pl_readlines('PRO');
    pl_close('PRO');
    $new_pro_alldata = [];
    $__s1 = $_ ?? null;
    foreach ($pro_alldata ?? [] as $_) {
        [$pro_name, $pro_sex, $pro_age, $pro_addr, $pro_p1, $pro_p2, $pro_p3, $pro_p4, $pro_p5, $pro_p6, $pro_p7, $pro_p8, $pro_p9, $pro_p10, $pro_p11, $pro_p12, $pro_p13, $pro_p14, $pro_p15, $pro_p16, $pro_p17, $pro_p18, $pro_p19, $pro_p20] = pl_pad(pl_split('/<>/u', $_), 24);
        if (((string)$_args[0] === (string)(string)($pro_name))) {
            continue;
        }
        $new_pro_alldata[] = $_;
    }
    $_ = $__s1;
    // ログ更新
    if (!(pl_open('PROO', ">{$profile_file}"))) { error("{$profile_file}に書き込めません"); }
    pl_print('PROO', pl_join('', $new_pro_alldata));
    pl_close('PROO');
}

function as_prof_sakujo(...$_args)
{
    global $_, $as_new_pro_alldata, $as_profile_file;
    // ver.1.3
    if (!(pl_open('ASP', (string)($as_profile_file)))) { error("Open Error : {$as_profile_file}"); }
    $as_pro_alldata = pl_readlines('ASP');
    pl_close('ASP');
    $as_new_pro_alldata = [];
    $__s1 = $_ ?? null;
    foreach ($as_pro_alldata ?? [] as $_) {
        [$pro_name, $pro_sex, $pro_age, $pro_addr, $pro_p1, $pro_p2, $pro_p3, $pro_p4, $pro_p5, $pro_p6, $pro_p7, $pro_p8, $pro_p9, $pro_p10, $pro_p11, $pro_p12, $pro_p13, $pro_p14, $pro_p15, $pro_p16, $pro_p17, $pro_p18, $pro_p19, $pro_p20] = pl_pad(pl_split('/<>/u', $_), 24);
        if (((string)$_args[0] === (string)(string)($pro_name))) {
            continue;
        }
        $as_new_pro_alldata[] = $_;
    }
    $_ = $__s1;
    // ログ更新
    if (!(pl_open('ASPO', ">{$as_profile_file}"))) { error("{$as_profile_file}に書き込めません"); }
    pl_print('ASPO', pl_join('', $as_new_pro_alldata));
    pl_close('ASPO');
}

function bbs1_settei(...$_args)
{
    global $admin_bbs_syurui, $bbs1_a_hover_style, $bbs1_body_style, $bbs1_come, $bbs1_leed_style, $bbs1_log_file,
        $bbs1_settei_data, $bbs1_settei_file, $bbs1_siasenbako, $bbs1_table2_style, $bbs1_tablewidth, $bbs1_title,
        $bbs1_title_style, $bbs1_toukousya_style, $bbs1_toukouwidth, $bbs1_yobi10, $bbs1_yobi5, $bbs1_yobi6,
        $bbs1_yobi7, $bbs1_yobi8, $bbs1_yobi9, $in, $my_directry, $this_script, $zidouseisei;
    // 管理者作成BBSの場合
    if (($in['mode'] == 'admin_bbs')) {
        $my_directry = './member/admin';
        if (!(pl_isdir($my_directry))) {
            if (!(pl_mkdir($my_directry))) { error('Error : can not Make Directry'); }
            if ((n($zidouseisei) == 1)) {
                true;
            } elseif ((n($zidouseisei) == 2)) {
                true;
            } else {
                true;
            }
        }
        $bbs1_settei_file = "{$my_directry}/bbs" . $in['bbs_num'] . '_ini.cgi';
        if (!(pl_exists($bbs1_settei_file))) {
            if (!(pl_open('OIB', ">{$bbs1_settei_file}"))) { error("Write Error : {$bbs1_settei_file}"); }
            true;
            pl_close('OIB');
        }
        $bbs1_log_file = "{$my_directry}/bbs" . $in['bbs_num'] . '_log.cgi';
        if (!(pl_exists($bbs1_log_file))) {
            if (!(pl_open('OIL', ">{$bbs1_log_file}"))) { error("Write Error : {$bbs1_log_file}"); }
            true;
            pl_close('OIL');
        }
    } else {
        $my_directry = "./member/{$in['iesettei_id']}";
        $bbs1_settei_file = "{$my_directry}/bbs1_ini.cgi";
        if (!(pl_exists($bbs1_settei_file))) {
            if (!(pl_open('OIB', ">{$bbs1_settei_file}"))) { error("Write Error : {$bbs1_settei_file}"); }
            true;
            pl_close('OIB');
        }
        $bbs1_log_file = "{$my_directry}/bbs1_log.cgi";
        if (!(pl_exists($bbs1_log_file))) {
            if (!(pl_open('OIL', ">{$bbs1_log_file}"))) { error("Write Error : {$bbs1_log_file}"); }
            true;
            pl_close('OIL');
        }
    }
    if (!(pl_open('OIB', (string)($bbs1_settei_file)))) { error("Open Error : {$bbs1_settei_file}"); }
    $bbs1_settei_data = pl_readline('OIB');
    [$bbs1_title, $bbs1_come, $bbs1_body_style, $bbs1_toukousya_style, $bbs1_table2_style, $bbs1_toukouwidth, $bbs1_a_hover_style, $bbs1_tablewidth, $bbs1_title_style, $bbs1_leed_style, $bbs1_siasenbako, $bbs1_yobi5, $bbs1_yobi6, $bbs1_yobi7, $bbs1_yobi8, $bbs1_yobi9, $bbs1_yobi10] = pl_pad(pl_split('/<>/u', $bbs1_settei_data), 17);
    // bbs1_yobi5 = 記事番号のスタイル　bbs1_yobi6＝同じ街の住民専用掲示板　bbs1_yobi7＝inputのスタイル
    pl_close('OIB');
    // スタイルの初期化
    if (($bbs1_body_style == '')) {
        $bbs1_body_style = 'background-color:#ffcc66;';
    }
    if (($bbs1_title_style == '')) {
        $bbs1_title_style = 'font-size: 16px; color: #666666;line-height:180%; text-align:center;';
    }
    if (($bbs1_leed_style == '')) {
        $bbs1_leed_style = 'font-size: 11px; line-height: 16px; color: #336699';
    }
    if (($bbs1_yobi5 == '')) {
        $bbs1_yobi5 = 'font-size: 15px; color: #336699';
    }
    if (($bbs1_toukousya_style == '')) {
        $bbs1_toukousya_style = 'font-size: 11px; color: #ff6600';
    }
    if (($bbs1_table2_style == '')) {
        $bbs1_table2_style = 'font-size: 11px; line-height: 16px; color: #666666; background-color:#ffffcc; border: #336699; border-style: dotted; border-width:4px';
    }
    if (($bbs1_toukouwidth == '')) {
        $bbs1_toukouwidth = '50';
    }
    if (($bbs1_a_hover_style == '')) {
        $bbs1_a_hover_style = ' color:#333333;text-decoration: none';
    }
    if (($bbs1_tablewidth == '')) {
        $bbs1_tablewidth = '500';
    }
    if (($bbs1_siasenbako == '')) {
        $bbs1_siasenbako = 'font-size:11px;color:#000000';
    }
    if (($bbs1_yobi7 == '')) {
        $bbs1_yobi7 = 'font-size:11px;color:#000000';
    }
    echo <<<EOM
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="bbs1_settei_do">
    	<input type="hidden" name="iesettei_id" value="{$in['iesettei_id']}">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="admin_pass" value="{$in['admin_pass']}">
    	<input type="hidden" name="k_id" value="{$in['k_id']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    EOM;
    if (($in['mode'] == 'admin_bbs')) {
        echo "<input type=\"hidden\" name=\"bbs_num\" value=\"{$in['bbs_num']}\">\n";
        echo "<input type=\"hidden\" name=\"command\" value=\"admin_bbs\">\n";
    }
    echo <<<EOM
    	<table width="90%" border="0" cellspacing="0" cellpadding="8" align="center" class="yosumi">
    	<tr><td>
    EOM;
    if (($in['mode'] == 'admin_bbs')) {
        echo "<div class=\"tyuu\">■{$admin_bbs_syurui[n($in['bbs_num'])]}の掲示板設定</div>";
    } else {
        echo '<div class="tyuu">■通常の掲示板設定</div>';
    }
    echo <<<EOM
    	●掲示板のタイトル（タグ指定可。絶対URLならばイメージ画像の指定も可）<br>
    	<textarea  cols="80" rows="4" name="タイトル" wrap="soft">{$bbs1_title}</textarea><br>
    	●タイトル下のコメント<br>
    	<input type="text" name="コメント" size="120"  value="{$bbs1_come}"><br>
    	●背景のスタイル設定<br>
    	<input type="text" name="bbs1_body_style" size="120" value="{$bbs1_body_style}"><br>
    	●タイトルのスタイル設定<br>
    	<input type="text" name="bbs1_title_style" size="120" value="{$bbs1_title_style}"><br>
    	●タイトル下のコメントのスタイル設定<br>
    	<input type="text" name="bbs1_leed_style" size="120" value="{$bbs1_leed_style}"><br>
    	●記事番号のスタイル設定<br>
    	<input type="text" name="bbs1_yobi5" size="120" value="{$bbs1_yobi5}"><br>
    	●投稿者名のスタイル設定<br>
    	<input type="text" name="bbs1_toukousya_style" size="120" value="{$bbs1_toukousya_style}"><br>
    	●掲示板内の基本スタイル設定<br>
    	<input type="text" name="bbs1_table2_style" size="120" value="{$bbs1_table2_style}"><br>
    	●投稿欄のサイズ（半角文字数で指定）<br>
    	<input type="text" name="bbs1_toukouwidth" size="120" value="{$bbs1_toukouwidth}"><br>
    	●リンク（aタグ）のスタイル設定<br>
    	<input type="text" name="bbs1_a_hover_style" size="120" value="{$bbs1_a_hover_style}"><br>
    	●テーブルの横幅<br>
    	<input type="text" name="bbs1_tablewidth" size="120" value="{$bbs1_tablewidth}"><br>
    	●inputとselectのスタイル設定<br>
    	<input type="text" name="bbs1_siasenbako" size="120" value="{$bbs1_siasenbako}"><br>
    	●レス部分のスタイル設定<br>
    	<input type="text" name="bbs1_yobi7" size="120" value="{$bbs1_yobi7}"><br>
    EOM;
    if (($in['mode'] == 'admin_bbs')) {
        echo <<<EOM
        	●同じ街の住民専用掲示板にする（この掲示板を設置した街に住んでいる人だけが閲覧・書き込みできます）<br>
        	<select name="bbs1_yobi6">
        	<option value="">通常</option>
        EOM;
        if (($bbs1_yobi6 == 'on')) {
            echo '<option value="on" selected>同じ街の住民専用</option>';
        } else {
            echo '<option value="on">同じ街の住民専用</option>';
        }
        echo '</select><br><br>';
    }
    echo '<input type="submit" value="設定変更">';
    if (($in['mode'] == 'admin_bbs')) {
        echo <<<EOM
        	<a href="original_house.php?mode=normal_bbs&ori_ie_id=admin&bbs_num={$in['bbs_num']}&name={$in['name']}&admin_pass={$in['admin_pass']}&con_sele=0" target="_blank">[現在の設定内容の確認]</a>
        	</td></tr></table>
        	</form>
        EOM;
    } else {
        echo <<<EOM
        	<a href="original_house.php?mode=houmon&ori_ie_id={$in['iesettei_id']}&name={$in['name']}&pass={$in['pass']}&con_sele=0" target="_blank">[現在の設定内容の確認]</a>
        	</td></tr></table>
        	</form>
        EOM;
    }
}

function bbs1_settei_do(...$_args)
{
    global $bbs1_a_hover_style, $bbs1_body_style, $bbs1_come, $bbs1_leed_style, $bbs1_settei_data, $bbs1_settei_file,
        $bbs1_siasenbako, $bbs1_table2_style, $bbs1_tablewidth, $bbs1_title, $bbs1_title_style,
        $bbs1_toukousya_style, $bbs1_toukouwidth, $bbs1_yobi10, $bbs1_yobi5, $bbs1_yobi6, $bbs1_yobi7, $bbs1_yobi8,
        $bbs1_yobi9, $bbs_settei_temp, $in, $message_win, $this_script;
    // 管理者作成BBSの場合
    if (($in['command'] == 'admin_bbs')) {
        $bbs1_settei_file = './member/admin/bbs' . $in['bbs_num'] . '_ini.cgi';
    } else {
        $bbs1_settei_file = "./member/{$in['iesettei_id']}/bbs1_ini.cgi";
    }
    if (!(pl_open('OIB', (string)($bbs1_settei_file)))) { error("Open Error : {$bbs1_settei_file}"); }
    $bbs1_settei_data = pl_readline('OIB');
    [$bbs1_title, $bbs1_come, $bbs1_body_style, $bbs1_toukousya_style, $bbs1_table2_style, $bbs1_toukouwidth, $bbs1_a_hover_style, $bbs1_tablewidth, $bbs1_title_style, $bbs1_leed_style, $bbs1_siasenbako, $bbs1_yobi5, $bbs1_yobi6, $bbs1_yobi7, $bbs1_yobi8, $bbs1_yobi9, $bbs1_yobi10] = pl_pad(pl_split('/<>/u', $bbs1_settei_data), 17);
    pl_close('OIB');
    lock(...$_args);
    $bbs1_title = $in['タイトル'];
    $bbs1_come = $in['コメント'];
    $bbs1_body_style = $in['bbs1_body_style'];
    $bbs1_toukousya_style = $in['bbs1_toukousya_style'];
    $bbs1_table2_style = $in['bbs1_table2_style'];
    $bbs1_toukouwidth = $in['bbs1_toukouwidth'];
    $bbs1_a_hover_style = $in['bbs1_a_hover_style'];
    $bbs1_tablewidth = $in['bbs1_tablewidth'];
    $bbs1_title_style = $in['bbs1_title_style'];
    $bbs1_leed_style = $in['bbs1_leed_style'];
    $bbs1_siasenbako = $in['bbs1_siasenbako'];
    $bbs1_yobi5 = $in['bbs1_yobi5'];
    $bbs1_yobi6 = $in['bbs1_yobi6'];
    $bbs1_yobi7 = $in['bbs1_yobi7'];
    $bbs1_yobi8 = $in['bbs1_yobi8'];
    $bbs1_yobi9 = $in['bbs1_yobi9'];
    $bbs1_yobi10 = $in['bbs1_yobi10'];
    $bbs_settei_temp = "{$bbs1_title}<>{$bbs1_come}<>{$bbs1_body_style}<>{$bbs1_toukousya_style}<>{$bbs1_table2_style}<>{$bbs1_toukouwidth}<>{$bbs1_a_hover_style}<>{$bbs1_tablewidth}<>{$bbs1_title_style}<>{$bbs1_leed_style}<>{$bbs1_siasenbako}<>{$bbs1_yobi5}<>{$bbs1_yobi6}<>{$bbs1_yobi7}<>{$bbs1_yobi8}<>{$bbs1_yobi9}<>{$bbs1_yobi10}<>\n";
    if (!(pl_open('OLOUT', ">{$bbs1_settei_file}"))) { error("{$bbs1_settei_file}に書き込みが出来ません"); }
    pl_print('OLOUT', $bbs_settei_temp);
    pl_close('OLOUT');
    unlock(...$_args);
    if (($in['command'] == 'admin_bbs')) {
        t_header(...$_args);
        echo <<<EOM
        	<div align="center"><br><table  border="0"  cellspacing="5" cellpadding="0" width="300" style="{$message_win}"><tr><td>設定を変更しました。</td></tr></table><br>
        
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="admin_bbs">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="admin_pass" value="{$in['admin_pass']}">
        	<input type="hidden" name="bbs_num" value="{$in['bbs_num']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<input type="submit" value="戻る">
        	</form></div>
        EOM;
        exit;
    } else {
        my_house_settei(...$_args);
    }
}

function bykeys(...$_args)
{
    global $a, $b, $keys;
    return (n($keys[n($b)]) <=> n($keys[n($a)]));
}

function yakuba(...$_args)
{
    global $_, $alldata, $data, $deleteUser, $dir, $err, $file_name, $global_nouryokuti, $i, $img_dir, $in, $key,
        $keys, $list_bijutu, $list_eigo, $list_etti, $list_job, $list_k_id, $list_kenkou, $list_kokugo,
        $list_kyakuryoku, $list_last_syokuzi, $list_looks, $list_love, $list_name, $list_ongaku, $list_power,
        $list_rika, $list_sex, $list_sousisan, $list_speed, $list_suugaku, $list_syakai, $list_tairyoku,
        $list_unique, $list_wanryoku, $logfile, $mem_lock_num, $news_file, $news_kensuu, $news_kigou, $news_style,
        $now_time, $ori_ie_hairetu, $ori_ie_image, $ori_ie_list, $ori_k_id, $rankMax, $rankingMember,
        $sakujo_folder_id, $seibetu, $sortid, $this_script, $town_news, $unit;
    lock(...$_args);
    // ver.1.3ロック位置変更
    $now_time = pl_time();
    // ver.1.40
    if ($in['sortid']) {
        $sortid = $in['sortid'];
    } else {
        $sortid = 5;
    }
    if (!(pl_open('IN', (string)($logfile)))) { error("Open Error : {$logfile}"); }
    $rankingMember = pl_readlines('IN');
    $__s1 = $_ ?? null;
    foreach ($rankingMember ?? [] as $_) {
        list_sprit($_);
        // 放置ユーザー削除処理
        if ((n($list_last_syokuzi) < (n($now_time) - ((((60 * 60) * 24) * n($deleteUser)))))) {
            // ver.1.40
            // 家削除処理
            ie_sakujo_syori($list_name);
            // ver.1.3ここから
            // プロフィール＆結婚斡旋所削除処理
            prof_sakujo($list_name);
            as_prof_sakujo($list_name);
            // フォルダー内のファイル名を取得して削除。個人フォルダーの削除
            $sakujo_folder_id = "./member/{$list_k_id}";
            if (pl_exists((string)($sakujo_folder_id))) {
                $dir = pl_opendir('./member/' . (string)($list_k_id));
                while (($file_name = pl_readdir($dir)) !== null) {
                    // 1つ読み込んで$folder_nameに代入
                    pl_unlink("./member/{$list_k_id}/{$file_name}");
                }
                pl_closedir($dir);
                // ディレクトリを閉じる
                if (!(pl_rmdir("./member/{$list_k_id}"))) { error("ID番号{$list_k_id}の{$list_name}さんのデータ削除ができません"); }
            }
            news_kiroku('転居', "{$list_name}さんが街を去りました。");
            // ver.1.3ここまで
            continue;
        }
        $data = $_;
        $key = pl_idx(pl_split('/<>/u', $data), n($in['sortid']));
        // ソートする要素を選ぶ
        $alldata[] = $data;
        $keys[] = $key;
    }
    $_ = $__s1;
    pl_close('IN');
    // （ver.1.21ここから）
    if ((n($mem_lock_num) == 0)) {
        $err = data_save($logfile, ...($alldata ?? []));
        if ($err) {
            error((string)($err));
        }
    } else {
        if (!(pl_open('OUT', ">{$logfile}"))) { error("{$logfile}が開けません"); }
        pl_print('OUT', pl_join('', $alldata));
        pl_close('OUT');
    }
    // （ver.1.21ここまで）
    unlock(...$_args);
    $alldata = pl_slice($alldata, pl_sort('bykeys', pl_range(0, (pl_count($alldata) - 1))));
    t_header('yakuba_style');
    // ver.1.30
    echo <<<EOM
    	<table width="98%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr>
    	<td bgcolor="#ffffff">役場では新規入居者や各種ランキングを見ることができます。<br>
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="yakuba">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<select name="sortid">
    <!--ver.1.3-->
    	<option value="">最近の街のニュース{$news_kensuu}件</option>
    EOM;
    echo '<option value="23"';
    if ((n($sortid) == 23)) {
        echo ' selected';
    }
    echo ">最近の入居者{$rankMax}人</option>";
    echo '<option value="49"';
    if ((n($sortid) == 49)) {
        echo ' selected';
    }
    echo ">長者番付ベスト{$rankMax}</option>";
    $global_nouryokuti = ['国語', '数学', '理科', '社会', '英語', '音楽', '美術', 'ルックス', '体力', '健康', 'スピード', 'パワー', '腕力', '脚力', 'LOVE', '面白さ', 'エッチ'];
    $i = 6;
    $__s2 = $_ ?? null;
    foreach ($global_nouryokuti ?? [] as $_) {
        echo "<option value=\"{$i}\"";
        if ((n($sortid) == n($i))) {
            echo ' selected';
        }
        echo ">{$_}ランキングベスト{$rankMax}</option>";
        $i++;
    }
    $_ = $__s2;
    echo <<<EOM
    	</select> <input type="submit" value="OK"></form></td>
    	<td  bgcolor="#333333" align="center" width="35%"><img src="{$img_dir}/yakuba_tytle.gif"></td>
    	</tr></table><br>
    <!--ver.1.3ここから-->
    EOM;
    if (($in['sortid'] == '')) {
        // 街ニュース表示
        if (!(pl_open('NS', (string)($news_file)))) { error("{$news_file}が開けません。"); }
        $town_news = pl_readlines('NS');
        pl_close('NS');
        echo <<<EOM
        					<table width="98%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
        					<tr bgcolor="#eeeecc"><td>
        					<div class="honbun4">◎最近の街のニュース{$news_kensuu}件</div>
        EOM;
        $__s3 = $_ ?? null;
        foreach ($town_news ?? [] as $_) {
            [$hiduke, $nw_syubetu, $nw_kizi] = pl_pad(pl_split('/<>/u', $_), 3);
            if (($nw_syubetu == '恋人')) {
                $news_style = 'color:#ff3366;';
                $news_kigou = '&#9829;';
            } elseif (($nw_syubetu == '結婚')) {
                $news_style = 'color:#ff3300;';
                $news_kigou = '&#9829;';
            } elseif (($nw_syubetu == '入居')) {
                $news_style = 'color:#3366cc;';
                $news_kigou = '◆';
            } elseif (($nw_syubetu == '出産')) {
                $news_style = 'color:#009900;';
                $news_kigou = '●';
            } elseif (($nw_syubetu == '就職')) {
                $news_style = 'color:#009900;';
                $news_kigou = '◎';
            } elseif (($nw_syubetu == '別れ')) {
                $news_style = 'color:#666666;';
                $news_kigou = '&#9829;';
            } elseif (($nw_syubetu == '死亡')) {
                $news_style = 'color:#666666;';
                $news_kigou = '■';
            } elseif (($nw_syubetu == '転居')) {
                $news_style = 'color:#666666;';
                $news_kigou = '▲';
            } elseif (($nw_syubetu == '家')) {
                $news_style = 'color:#990000;';
                $news_kigou = '●';
            }
            echo <<<EOM
            					<div style="color:#666666; line-height:180%;">{$hiduke}<span style="{$news_style}"> {$news_kigou}{$nw_kizi}</span></div>
            EOM;
        }
        $_ = $__s3;
        echo '</td></tr></table><br><br>';
    } else {
        echo <<<EOM
        <!--ver.1.3ここまで-->
        	<table width="98%" border="0" cellspacing="1" cellpadding="3" align="center" class="yosumi">
        	<tr><td colspan="23">
        	※「データを保存」でデータを更新しないとこのランキングに反映されません。<br>
        	※長者番付は、持ち金＋普通預金預け額＋スーパー定期預け額ーローン額で決まります。</td></tr>
        	<tr  class="jouge" bgcolor="#ffff66" align="center"><td>順位</td><td>名　前</td><td>性別</td><td>職　業</td><td>資　産</td><td>国</td><td>数</td><td>理</td><td>社</td><td>英</td><td>音</td><td>美</td><td>ル</td><td>体</td><td>健</td><td>ス</td><td>パ</td><td>腕</td><td>脚</td><td>L</td><td>面</td><td>Ｈ</td><td>家</td></tr>
        EOM;
        // 個人の家情報をunitハッシュに代入
        if (!(pl_open('OI', (string)($ori_ie_list)))) { error("Open Error : {$ori_ie_list}"); }
        $ori_ie_hairetu = pl_readlines('OI');
        $__s4 = $_ ?? null;
        foreach ($ori_ie_hairetu ?? [] as $_) {
            ori_ie_sprit($_);
            $unit[(string)($ori_k_id)] = "<form method=\"POST\" action=\"original_house.php\"><input type=\"hidden\" name=\"mode\" value=\"houmon\"><input type=\"hidden\" name=\"ori_ie_id\" value=\"{$ori_k_id}\"><input type=\"hidden\" name=\"name\" value=\"{$in['name']}\"><input type=\"hidden\" name=\"pass\" value=\"{$in['pass']}\"><input type=\"hidden\" name=\"town_no\" value=\"{$in['town_no']}\"><input type=\"image\" src=\"{$ori_ie_image}\"></form>";
            // ver.1.40
        }
        $_ = $__s4;
        pl_close('OI');
        $i = 1;
        $__s5 = $_ ?? null;
        foreach ($alldata ?? [] as $_) {
            list_sprit($_);
            if (($list_sex == 'f')) {
                $seibetu = '女';
            } else {
                $seibetu = '男';
            }
            echo <<<EOM
            		<tr class="sita"><td align="center">{$i}</td><td nowrap>{$list_name}</td><td align="center">{$seibetu}</td><td>{$list_job}</td><td align="right" nowrap>{$list_sousisan}円</td><td align="right">{$list_kokugo}</td><td align="right" nowrap>{$list_suugaku}</td><td align="right" nowrap>{$list_rika}</td><td align="right" nowrap>{$list_syakai}</td><td align="right" nowrap>{$list_eigo}</td><td align="right" nowrap>{$list_ongaku}</td><td align="right" nowrap>{$list_bijutu}</td><td align="right" nowrap>{$list_looks}</td><td align="right" nowrap>{$list_tairyoku}</td><td align="right" nowrap>{$list_kenkou}</td><td align="right" nowrap>{$list_speed}</td><td align="right" nowrap>{$list_power}</td><td align="right" nowrap>{$list_wanryoku}</td><td align="right" nowrap>{$list_kyakuryoku}</td><td align="right" nowrap>{$list_love}</td><td align="right" nowrap>{$list_unique}</td><td align="right" nowrap>{$list_etti}</td><td align="center" valign="center">{$unit[(string)($list_k_id)]}</td></tr>
            EOM;
            if ((n($i) >= n($rankMax))) {
                break;
            }
            $i++;
        }
        $_ = $__s5;
        echo '</table>';
    }
    // ver.1.3
    hooter('login_view', '戻る');
    exit;
}

