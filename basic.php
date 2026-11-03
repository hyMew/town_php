<?php
// このファイルは basic.cgi (Perl) から自動変換されたものです。
// 町CGI  Copyright (c) 2003-2004 brassiere  http://brassiere.jp/

require_once __DIR__ . '/lib/bootstrap.php';
$this_script = 'basic.php';
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
if (($in['mode'] == 'keiba')) {
    keiba();
} elseif (($in['mode'] == 'donus')) {
    donus();
} elseif (($in['mode'] == 'byouin')) {
    byouin();
} elseif (($in['mode'] == 'onsen')) {
    onsen();
} elseif (($in['mode'] == 'prof')) {
    prof();
} elseif (($in['mode'] == 'item')) {
    item();
} elseif (($in['mode'] == 'item_do')) {
    item_do();
} elseif (($in['mode'] == 'job_change')) {
    job_change();
} elseif (($in['mode'] == 'job_change_go')) {
    job_change_go();
} elseif (($in['mode'] == 'do_work')) {
    do_work();
} elseif (($in['mode'] == 'ginkou')) {
    ginkou();
} elseif (($in['mode'] == 'ginkoumeisai')) {
    ginkoumeisai();
} elseif (($in['mode'] == 'ginkoufurikomi')) {
    ginkoufurikomi();
} elseif (($in['mode'] == 'loan')) {
    loan();
} else {
    error('「戻る」ボタンで街に戻ってください');
}
exit;
// ############以下サブルーチン
// ##########銀行
// sub ginkouの閉じ
// ###銀行振り込み処理
// ######ローン査定額の計算（サーバー側）
// ######ローン
// ######銀行明細
// ###競馬
// 競馬用ロック処理
// ###ドーナツゲーム
// ##病院
// ##温泉
// #登録フォーム
// #########アイテム使用
// ######アイテム使用
// #####職業安定所
// #####職業チェンジ処理
// ###仕事する

function ginkou(...$_args)
{
    global $bank, $gohyaku, $gojuu, $hyaku, $ima_azuke, $img_dir, $in, $issen, $iti, $juu, $k_temp, $loan_kaisuu,
        $loan_nitigaku, $loan_zandaka_kei, $message_in, $money, $my_log_file, $name, $orosumaeno_super_teiki,
        $saidai_azuke, $super_azuke_kin, $super_gaku, $super_teiki, $this_script, $zandaka_keisan;
    openMylog($in['k_id']);
    if (($in['command'] == '預ける')) {
        if (pl_match('/[^0-9]/u', $in['azukegaku'])) {
            error('金額は半角数字で記入してください');
        }
        if ((n($in['azukegaku']) <= 0)) {
            error('マイナスのお金は預けられません！');
        }
        if ((n($in['azukegaku']) > n($money))) {
            error('そんなにお金を持ってないです！');
        }
        $bank = (n($bank) + n($in['azukegaku']));
        $money = (n($money) - n($in['azukegaku']));
        $message_in = "銀行へお金を{$in['azukegaku']}円預けました。";
        kityou_syori('預け入れ', '', $in['azukegaku'], $bank, '普');
    } elseif (($in['command'] == 'おろす')) {
        if (pl_match('/[^0-9]/u', $in['orosigaku'])) {
            error('金額は半角数字で記入してください');
        }
        // ver.1.40
        if ((n($in['orosigaku']) <= 0)) {
            error('マイナスのお金をおろすことはできません！');
        }
        if ((n($in['orosigaku']) > n($bank))) {
            error('銀行にそんなに預けてありません！');
        }
        $bank = (n($bank) - n($in['orosigaku']));
        $money = (n($money) + n($in['orosigaku']));
        $message_in = "銀行からお金を{$in['orosigaku']}円おろしました。";
        kityou_syori('お引き出し', (string)($in['orosigaku']), '', $bank, '普');
    } elseif (($in['command'] == 'スーパー')) {
        if (pl_match('/[^0-9]/u', $in['azukegaku'])) {
            error('金額は半角数字で記入してください');
        }
        $super_azuke_kin = ((n($in['azukegaku']) * 1000000));
        if ((n($super_azuke_kin) > n($money))) {
            error('そんなにお金を持ってないです！');
        }
        $super_teiki = (n($super_teiki) + n($super_azuke_kin));
        $money = (n($money) - n($super_azuke_kin));
        $message_in = "スーパー定期へお金を{$super_azuke_kin}円預けました。";
        kityou_syori('預け入れ', '', (n($in['azukegaku']) * 1000000), $super_teiki, '定');
    } elseif (($in['command'] == '解約')) {
        $money = (n($money) + n($super_teiki));
        $orosumaeno_super_teiki = $super_teiki;
        $message_in = "スーパー定期（{$super_teiki}円）を解約しました。";
        $super_teiki = 0;
        kityou_syori('解約', (string)($orosumaeno_super_teiki), '', $super_teiki, '定');
    } else {
        // 銀行画面出力
        t_header('ginkou_style');
        if (($bank == '')) {
            $ima_azuke = '無し';
        } else {
            $ima_azuke = "{$bank}円";
        }
        if ((n($super_teiki) > 0)) {
            $super_gaku = "●スーパー定期預金額：{$super_teiki}円";
        }
        // ver.1.3ここから
        $saidai_teiki = pl_int((n($money) * 0.000001));
        if ((n($saidai_teiki) <= 0)) {
            $saidai_teiki = '';
        }
        if ((n($money) > 0)) {
            $saidai_azuke = $money;
        } else {
            $saidai_azuke = '';
        }
        if ((n($money) > 10000000)) {
            $issen = (n($money) - 10000000);
            $zandaka_keisan .= "<option value=\"{$issen}\">1000万残して</option>";
        }
        if ((n($money) > 5000000)) {
            $gohyaku = (n($money) - 5000000);
            $zandaka_keisan .= "<option value=\"{$gohyaku}\">500万残して</option>";
        }
        if ((n($money) > 1000000)) {
            $hyaku = (n($money) - 1000000);
            $zandaka_keisan .= "<option value=\"{$hyaku}\">100万残して</option>";
        }
        if ((n($money) > 500000)) {
            $gojuu = (n($money) - 500000);
            $zandaka_keisan .= "<option value=\"{$gojuu}\">50万残して</option>";
        }
        if ((n($money) > 100000)) {
            $juu = (n($money) - 100000);
            $zandaka_keisan .= "<option value=\"{$juu}\">10万残して</option>";
        }
        if ((n($money) > 10000)) {
            $iti = (n($money) - 10000);
            $zandaka_keisan .= "<option value=\"{$iti}\">1万残して</option>";
        }
        if (($zandaka_keisan == '')) {
            $zandaka_keisan = '<option value="">残高指定できません</option>';
        }
        // ver.1.3ここまで
        echo <<<EOM
        	<table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
        	<tr>
        	<td bgcolor="#ffffff">いらっしゃいませ。<div class="honbun2">●{$name}さんの所持金：{$money}円</div></td>
        	<td  bgcolor="#333333" align="center" width="35%"><img src="{$img_dir}/ginkou_tytle.gif"></td>
        	</tr></table><br>
        
        	<table width="90%" border="0" cellspacing="0" cellpadding="8" align="center" class="yosumi"><tr>
        
        	<td width="50%" valign="top"><span style="font-size:14px;color:#ff3300">■普通口座</span><span class="honbun2">●現在の預け入れ額：{$ima_azuke}</span><br><br>
        			※普通口座にお金を預けておくと、１日１回0.5％の利息がつきます。また、家を持っていた場合のおさい銭や、お店の商品が売れた時の入金などにも利用されます。<br>
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="ginkou">
        	<input type="hidden" name="command" value="預ける">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="k_id" value="{$in['k_id']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<!--ver.1.3ここから-->
        	◆お　預　け <input type="text" name="azukegaku" value="{$saidai_azuke}" size="12">円 <input type="submit" value="預ける">
        	</form>
        
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="ginkou">
        	<input type="hidden" name="command" value="預ける">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="k_id" value="{$in['k_id']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	◆残高で預入 <select name = "azukegaku">{$zandaka_keisan}</select>　<input type="submit" value="預ける">
        	</form>
        
        
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="ginkou">
        	<input type="hidden" name="command" value="おろす">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="k_id" value="{$in['k_id']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	◆お引き出し <input type="text" name="orosigaku" size="12">円 <input type="submit" value="引き出す">
        	</form><hr size="1"></td>
        
        	<td valign="top"><span style="font-size:14px;color:#ff3300">■スーパー定期</span><span class="honbun2">{$super_gaku}</span><br><br>
        		※スーパー定期では１日１回１％の利息がつきます。ただし、預け入れ額は100万単位で、お金をおろすさいは解約として全額おろさねばなりません。<br>
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="ginkou">
        	<input type="hidden" name="command" value="スーパー">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="k_id" value="{$in['k_id']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	◆最大可能額 <input type="text" name="azukegaku" value="{$saidai_teiki}" size="6"> 00万 <input type="submit" value="可能額を預ける">
        	</form>
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="ginkou">
        	<input type="hidden" name="command" value="スーパー">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="k_id" value="{$in['k_id']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	◆お　預　け <input type="text" name="azukegaku" size="6"> 00万 <input type="submit" value="預ける">
        	</form>
        <!--ver.1.3ここまで-->
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="ginkou">
        	<input type="hidden" name="command" value="解約">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="k_id" value="{$in['k_id']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	◆ご　解　約 <input type="submit" value="解約する">
        	</form>
        	<hr size="1"></td></tr>
        	<tr>
        	<td valign="top"><span style="font-size:14px;color:#ff3300">■入出金明細</span><br><br>
        		※普通預金の入出金明細を見ることができます。<br>
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="ginkoumeisai">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="k_id" value="{$in['k_id']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<input type="hidden" name="syubetu" value="普通預金入出金明細">
        	<input type="submit" value="入出金明細を見る">
        	</form>
        	<hr size="1"></td>
        
        	<td valign="top"><span style="font-size:14px;color:#ff3300">■スーパー定期明細</span><br><br>
        		※スーパー定期の入出金明細を見ることができます。<br>
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="ginkoumeisai">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="k_id" value="{$in['k_id']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<input type="hidden" name="syubetu" value="スーパー定期明細">
        	<input type="submit" value="入出金明細を見る">
        	</form>
        	<hr size="1"></td></tr>
        	<tr>
        	<td valign="top"><span style="font-size:14px;color:#ff3300">■振り込み</span><br><br>
        		※参加者のメンバー名がわかれば送金することができます。お金は普通口座より引き落とされます。<br>
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="ginkoufurikomi">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="k_id" value="{$in['k_id']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	振込先のお名前 <input type="text" name="aitenonamae" size="16">
        	振り込み金額 <input type="text" name="hurikomigaku" size="8">円
        	<input type="submit" value="振り込み">
        	</form>
        	</td>
        
        	<td valign="top"><span style="font-size:14px;color:#ff3300">■ローン</span><br><br>
        EOM;
        if ((n($loan_kaisuu) > 0)) {
            $loan_zandaka_kei = (n($loan_nitigaku) * n($loan_kaisuu));
            echo <<<EOM
            	●現在のローン残金＝{$loan_zandaka_kei}円（{$loan_nitigaku}円×{$loan_kaisuu}回）<br>
            	※残金を一括返済される場合は下のボタンを押してください。その場合、普通口座より引き落とされます。
            	<form method="POST" action="{$this_script}">
            	<input type="hidden" name="mode" value="loan">
            	<input type="hidden" name="command" value="ikkatu_hensai">
            	<input type="hidden" name="name" value="{$in['name']}">
            	<input type="hidden" name="pass" value="{$in['pass']}">
            	<input type="hidden" name="k_id" value="{$in['k_id']}">
            	<input type="hidden" name="town_no" value="{$in['town_no']}">
            	<input type="submit" value="ローンの一括返済">
            	</form>
            EOM;
        } else {
            echo <<<EOM
            		※当銀行へのご利用度や収入に応じてお金を借りることができます。返済は一括で行うこともできます。<br>
            	<form method="POST" action="{$this_script}">
            	<input type="hidden" name="mode" value="loan">
            	<input type="hidden" name="name" value="{$in['name']}">
            	<input type="hidden" name="pass" value="{$in['pass']}">
            	<input type="hidden" name="k_id" value="{$in['k_id']}">
            	<input type="hidden" name="town_no" value="{$in['town_no']}">
            	<input type="submit" value="融資額の査定">
            	</form>
            EOM;
        }
        echo <<<EOM
        	</td></tr>
        	</table>
        	<div align="center"><!--TW_BACK--></div>
        	</body></html>
        EOM;
        exit;
    }
    // else（銀行画面出力）の閉じ
    // 画面出力以外の場合のログ更新処理
    temp_routin(...$_args);
    log_kousin($my_log_file, $k_temp);
    message($message_in, 'login_view');
}

function ginkoufurikomi(...$_args)
{
    global $aite_bank, $aite_k_temp, $aite_log_file, $bank, $in, $k_temp, $my_log_file, $name, $return_id;
    if (($in['command'] != 'from_system')) {
        if ((n($in['hurikomigaku']) < 0)) {
            error('マイナスの金額は振り込むことができません');
        }
        if (pl_match('/[^0-9]/u', $in['hurikomigaku'])) {
            error('金額は半角数字で記入してください');
        }
        if ((n($bank) < n($in['hurikomigaku']))) {
            error('普通口座に必要なお金が足りません。');
        }
        if (((string)$name === (string)$in['aitenonamae'])) {
            error('自分宛に振り込むことはできません。');
        }
        // ver.1.3
        $bank = n($bank) - n($in['hurikomigaku']);
    }
    id_check($in['aitenonamae']);
    openAitelog($return_id);
    $aite_bank = n($aite_bank) + n($in['hurikomigaku']);
    if (($in['command'] != 'from_system')) {
        kityou_syori("振り込み→{$in['aitenonamae']}", (string)($in['hurikomigaku']), '', $bank, '普');
    }
    if ((n($in['hurikomigaku']) < 0)) {
        aite_kityou_syori('減金', $in['hurikomigaku'], '', $aite_bank, '普', $return_id, '');
    } else {
        aite_kityou_syori("振り込み←{$in['name']}", '', $in['hurikomigaku'], $aite_bank, '普', $return_id, '');
    }
    // ログ更新
    lock(...$_args);
    if (($in['command'] != 'from_system')) {
        temp_routin(...$_args);
        if (!(pl_open('OUT', ">{$my_log_file}"))) { error("@{$my_log_file}が開けません"); }
        pl_print('OUT', $k_temp);
        pl_close('OUT');
    }
    aite_temp_routin(...$_args);
    if (!(pl_open('OUT', ">{$aite_log_file}"))) { error("{$aite_log_file}が開けません"); }
    pl_print('OUT', $aite_k_temp);
    pl_close('OUT');
    unlock(...$_args);
    if (($in['command'] != 'from_system')) {
        message("{$in['aitenonamae']}さんの普通口座に{$in['hurikomigaku']}円を振り込みました。", 'login_view');
    } else {
        message("{$in['aitenonamae']}さんの普通口座に{$in['hurikomigaku']}円を振り込みました。", 'itiran', 'admin.php');
    }
}

function loan_satei(...$_args)
{
    global $_, $bank, $job, $job_hairetu, $job_kaisuu, $job_keiken, $job_kyuuyo, $job_name, $super_teiki,
        $top_koumoku, $yuusi_kanougaku;
    if (!(pl_open('SP', './dat_dir/job.dat'))) { error('Open Error : ./dat_dir/job.dat'); }
    $top_koumoku = pl_readline('SP');
    $job_hairetu = pl_readlines('SP');
    pl_close('SP');
    $__s1 = $_ ?? null;
    foreach ($job_hairetu ?? [] as $_) {
        job_sprit($_);
        if (((string)$job_name === (string)(string)($job))) {
            break;
        }
    }
    $_ = $__s1;
    $yuusi_kanougaku = pl_int((((((n($job_kyuuyo) * ((n($job_keiken) / 50))) * ((n($job_kaisuu) / 50)))) + ((n($bank) * 2))) + ((n($super_teiki) * 2.5))));
    $yuusi_kanougaku = n($yuusi_kanougaku) - (n($yuusi_kanougaku) % 10000);
}

function loan(...$_args)
{
    global $_, $bank, $ikkatu_hensai_gaku, $in, $job, $job_hairetu, $job_kaisuu, $job_keiken, $job_kyuuyo, $job_name,
        $k_temp, $kai108, $kai12, $kai120, $kai24, $kai36, $kai48, $kai60, $kai72, $kai84, $kai96, $loan_kaisuu,
        $loan_nitigaku, $loan_riritu, $my_log_file, $name, $super_teiki, $this_script, $top_koumoku, $yuusi_kanougaku;
    // 借り入れ処理の場合
    if ($in['hensai_kaisuu']) {
        if ((n($loan_kaisuu) > 0)) {
            error('ローンを完済するまで新しい融資はできません。');
        }
        [$nitigaku, $nitigaku_kaisuu] = pl_pad(pl_split('/×/u', $in['hensai_kaisuu']), 2);
        loan_satei(...$_args);
        $loan_riritu = [12 => 0.05, 24 => 0.06, 36 => 0.07, 48 => 0.08, 60 => 0.09, 72 => 0.1, 84 => 0.11, 96 => 0.12, 108 => 0.13, 120 => 0.14];
        if (($loan_riritu[$nitigaku_kaisuu] == '')) {
            error('返済回数が不正です。');
        }
        if ((n($yuusi_kanougaku) <= 0)) {
            error('ご融資できる金額がありません。');
        }
        $nitigaku = pl_int((((n($yuusi_kanougaku) + ((n($yuusi_kanougaku) * n($loan_riritu[$nitigaku_kaisuu]))))) / n($nitigaku_kaisuu)));
        $in['yuusi_kanougaku'] = $yuusi_kanougaku;
        $loan_nitigaku = (string)($nitigaku);
        $loan_kaisuu = (string)($nitigaku_kaisuu);
        $bank = n($bank) + n($in['yuusi_kanougaku']);
        kityou_syori('住宅ローン', '', $in['yuusi_kanougaku'], $bank, '普');
        // ログ更新
        temp_routin(...$_args);
        log_kousin($my_log_file, $k_temp);
        message("{$in['yuusi_kanougaku']}円を普通預金口座に振り込みました。", 'login_view');
        // 一括返済の場合
    } elseif (($in['command'] == 'ikkatu_hensai')) {
        $ikkatu_hensai_gaku = (n($loan_nitigaku) * n($loan_kaisuu));
        if ((n($bank) < n($ikkatu_hensai_gaku))) {
            error('普通口座に十分な預金がありません');
        }
        $bank = n($bank) - n($ikkatu_hensai_gaku);
        $loan_nitigaku = 0;
        $loan_kaisuu = 0;
        kityou_syori('住宅ローン一括返済', (string)($ikkatu_hensai_gaku), '', $bank, '普');
        // ログ更新
        temp_routin(...$_args);
        log_kousin($my_log_file, $k_temp);
        message('住宅ローンの一括返済をしました。', 'login_view');
        // 査定処理画面出力
    } else {
        if (!(pl_open('SP', './dat_dir/job.dat'))) { error('Open Error : ./dat_dir/job.dat'); }
        $top_koumoku = pl_readline('SP');
        $job_hairetu = pl_readlines('SP');
        pl_close('SP');
        $__s1 = $_ ?? null;
        foreach ($job_hairetu ?? [] as $_) {
            job_sprit($_);
            if (((string)$job_name === (string)(string)($job))) {
                break;
            }
        }
        $_ = $__s1;
        $yuusi_kanougaku = pl_int((((((n($job_kyuuyo) * ((n($job_keiken) / 50))) * ((n($job_kaisuu) / 50)))) + ((n($bank) * 2))) + ((n($super_teiki) * 2.5))));
        $yuusi_kanougaku = n($yuusi_kanougaku) - (n($yuusi_kanougaku) % 10000);
        $kai12 = pl_int((((n($yuusi_kanougaku) + ((n($yuusi_kanougaku) * 0.05)))) / 12));
        $kai24 = pl_int((((n($yuusi_kanougaku) + ((n($yuusi_kanougaku) * 0.06)))) / 24));
        $kai36 = pl_int((((n($yuusi_kanougaku) + ((n($yuusi_kanougaku) * 0.07)))) / 36));
        $kai48 = pl_int((((n($yuusi_kanougaku) + ((n($yuusi_kanougaku) * 0.08)))) / 48));
        $kai60 = pl_int((((n($yuusi_kanougaku) + ((n($yuusi_kanougaku) * 0.09)))) / 60));
        $kai72 = pl_int((((n($yuusi_kanougaku) + ((n($yuusi_kanougaku) * 0.1)))) / 72));
        $kai84 = pl_int((((n($yuusi_kanougaku) + ((n($yuusi_kanougaku) * 0.11)))) / 84));
        $kai96 = pl_int((((n($yuusi_kanougaku) + ((n($yuusi_kanougaku) * 0.12)))) / 96));
        $kai108 = pl_int((((n($yuusi_kanougaku) + ((n($yuusi_kanougaku) * 0.13)))) / 108));
        $kai120 = pl_int((((n($yuusi_kanougaku) + ((n($yuusi_kanougaku) * 0.14)))) / 120));
        t_header('ginkou_style');
        echo <<<EOM
        		<table width="400" border="0" cellspacing="0" cellpadding="20" align="center" class="yosumi">
        	<tr>
        	<td bgcolor="#ffffff">
        	<div class="dai" align="center">ご　査　定</div><hr size="1"><br>
        	<div class="job_messe">{$name}様の当銀行へのお預け入れ額や<br>
        	ご職業、経験、勤続期間などから査定いたしまして、<br>
        	ご融資できます金額は以下の通りとなります。
        	<br><br><div class="dai">{$yuusi_kanougaku}円</div>
        
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="loan">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="k_id" value="{$in['k_id']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<input type="hidden" name="yuusi_kanougaku" value="{$yuusi_kanougaku}">
        	ご返済は毎日（ログイン時）、返済回数に応じて5～14%の利子がついた額が普通預金口座より引き落とされます。<br>
        	また、完済するまでは次にお金を借りることはできません。
        
        	<select name="hensai_kaisuu">
        	<option value="{$kai12}×12">12回払い（日額{$kai12}円）</option>
        	<option value="{$kai24}×24">24回払い（日額{$kai24}円）</option>
        	<option value="{$kai36}×36">36回払い（日額{$kai36}円）</option>
        	<option value="{$kai48}×48">48回払い（日額{$kai48}円）</option>
        	<option value="{$kai60}×60">60回払い（日額{$kai60}円）</option>
        	<option value="{$kai72}×72">72回払い（日額{$kai72}円）</option>
        	<option value="{$kai84}×84">84回払い（日額{$kai84}円）</option>
        	<option value="{$kai96}×96">96回払い（日額{$kai96}円）</option>
        	<option value="{$kai108}×108">108回払い（日額{$kai108}円）</option>
        	<option value="{$kai120}×120">120回払い（日額{$kai120}円）</option>
        	</select>
        	<input type="submit" value="借りる">
        	</form>
        	</td></tr>
        	</table>
        	<div align="center"><!--TW_BACK--></div>
        	</body></html>
        EOM;
        exit;
    }
    // else（借り入れ処理で無い場合）の閉じ
}

function ginkoumeisai(...$_args)
{
    global $_, $ginkoumeisai_file, $in, $k_id, $meisai_azuke, $meisai_date, $meisai_hikidasi, $meisai_naiyou,
        $meisai_syubetu, $meisai_zandaka, $my_tuutyou;
    $ginkoumeisai_file = "./member/{$k_id}/ginkoumeisai.cgi";
    if (!(pl_exists($ginkoumeisai_file))) {
        if (!(pl_open('GM', ">{$ginkoumeisai_file}"))) { error('自分の預金通帳ファイルを作成できません'); }
        pl_close('GM');
    }
    if (!(pl_open('GM', (string)($ginkoumeisai_file)))) { error('自分の預金通帳ファイルが開けません'); }
    $my_tuutyou = pl_readlines('GM');
    pl_close('GM');
    t_header('ginkou_style');
    echo <<<EOM
    	<table width="90%" border="0" cellspacing="1" cellpadding="5" align="center" class="yosumi">
    	<tr class="sita"><td colspan="5">■{$in['syubetu']}　※明細は普通預金、スーパー定期合わせて100件まで記帳されます。</td></tr>
    	<tr class="sita" bgcolor="#cccc99" align="center">
    	<td>年　月　日</td><td>お取り引き内容</td><td>出　金　額</td><td>入　金　額</td><td>差し引き残高</td></tr>
    EOM;
    $__s1 = $_ ?? null;
    foreach ($my_tuutyou ?? [] as $_) {
        ginkou_meisai_sprit($_);
        if (($in['syubetu'] == '普通預金入出金明細')) {
            if (($meisai_syubetu != '普')) {
                continue;
            }
        }
        if (($in['syubetu'] == 'スーパー定期明細')) {
            if (($meisai_syubetu != '定')) {
                continue;
            }
        }
        // ver.1.3ここから
        $meisai_zandaka = pl_comma($meisai_zandaka);
        $meisai_hikidasi = pl_comma($meisai_hikidasi);
        $meisai_azuke = pl_comma($meisai_azuke);
        // ver.1.3ここまで
        echo <<<EOM
        		<tr class="sita"><td>{$meisai_date}</td><td>{$meisai_naiyou}</td><td align="right"><span style="color:#ff3333">{$meisai_hikidasi}</span></td><td align="right"><span style="color:#009933">{$meisai_azuke}</span></td><td align="right">{$meisai_zandaka}円</td></tr>
        EOM;
    }
    $_ = $__s1;
    echo <<<EOM
    		</table>
    		<div align="center"><!--TW_BACK--></div>
    		</body></html>
    EOM;
    exit;
}

function bykeys(...$_args)
{
    global $a, $b, $keys;
    return (n($keys[n($b)]) <=> n($keys[n($a)]));
}

function keiba(...$_args)
{
    global $_, $__, $alldata, $deleteUser, $hid_kake, $hid_kane, $hid_name, $hikarerugaku, $i, $img_dir, $in,
        $k_temp, $kakekin, $kaketaumanokazu, $kakutokugaku, $kakutokuhyouzi, $kane, $keiba_gendomaisuu,
        $keiba_hairetu, $keiba_logfile, $keiba_ranking, $keiba_temp, $keibalockfile, $keibarank_logfile,
        $kekkahantei, $kekkahappyou, $key, $keys, $kizon_flag, $kounyuu_soumaisuu, $kr_kakutokugaku, $kr_moukegaku,
        $kr_name, $kr_rank_temp, $kr_tounyuugaku, $kr_yobi1, $kr_yobi2, $kr_yobi3, $kr_yobi4, $kyori, $message_win,
        $money, $moukegaku, $my_log_file, $name, $new_entry_list, $new_kakeritu, $new_keiba_ranking, $now_race, $ods,
        $rank_html, $soukounyuu, $start_html, $syasin_randed, $this_script, $umagazou, $umaname, $umaname_map,
        $win_hairetu, $win_uma, $winner;
    $now_time = pl_time();
    $__t1 = ['◇ダンスパートナー', '◇エアグルーヴ', '◇ビワハヤヒデ', '◇イシノサンデー', '◇ジャングルポケット', '◇マンハッタンカフェ', '◇ノーリーズン', '◇オグリキャップ', '◇サクラプレジデント', '◇エアシャカール', '◇ビワハイジ', '◇ナリタブライアン', '◇テイエムオーシャン', '◇トウカイテイオー', '◇ローマンエンパイア']; $umaname_list = array_slice($__t1, 0);
    $__t2 = ['8', '28', '2', '12', '16', '4', '30', '3', '5']; $umakakeritu = array_slice($__t2, 0);
    $umagazou = ['◇ダンスパートナー' => 'danpa.gif', '◇エアグルーヴ' => 'groove.gif', '◇ビワハヤヒデ' => 'hayahide.gif', '◇イシノサンデー' => 'ishisun.gif', '◇ジャングルポケット' => 'junpoke.gif', '◇マンハッタンカフェ' => 'manhattan.gif', '◇ノーリーズン' => 'noreason.gif', '◇オグリキャップ' => 'oguri.gif', '◇サクラプレジデント' => 'sakura_predi.gif', '◇エアシャカール' => 'shakur.gif', '◇ビワハイジ' => 'heidi.gif', '◇ナリタブライアン' => 'brian.gif', '◇テイエムオーシャン' => 'tm_ocean.gif', '◇トウカイテイオー' => 'tokai_teio.gif', '◇ローマンエンパイア' => 'roman.gif'];
    // ランキングファイル読み込み
    if (!(pl_open('KR', (string)($keibarank_logfile)))) { error("Open Error : {$keibarank_logfile}"); }
    $keiba_ranking = pl_readlines('KR');
    pl_close('KR');
    $__s3 = $_ ?? null;
    foreach ($keiba_ranking ?? [] as $_) {
        $key = pl_idx(pl_split('/<>/u', $_), 1);
        // ソートする要素を選ぶ
        $alldata[] = $_;
        $keys[] = $key;
    }
    $_ = $__s3;
    $alldata = pl_slice($alldata, pl_sort('bykeys', pl_range(0, (pl_count($alldata) - 1))));
    $i = 0;
    $__s4 = $_ ?? null;
    foreach ($alldata ?? [] as $_) {
        [$kr_name, $kr_moukegaku, $kr_tounyuugaku, $kr_kakutokugaku, $kr_yobi1, $kr_yobi2, $kr_yobi3, $kr_yobi4] = pl_pad(pl_split('/<>/u', $_), 8);
        // kr_yobi1 = 最終ゲーム時間
        $rank_html .= "<tr><td>{$kr_name}</td><td align=\"right\">{$kr_moukegaku}円</td><td align=\"right\">{$kr_tounyuugaku}円</td><td align=\"right\">{$kr_kakutokugaku}円</td></tr>";
        $i++;
        if ((n($i) >= 10)) {
            break;
        }
    }
    $_ = $__s4;
    // 馬をランダムに並び替え
    $new_entry_list = [];
    $__s5 = $_ ?? null;
    foreach ($umaname_list ?? [] as $_) {
        $r = pl_rand((pl_count($new_entry_list) + 1));
        $new_entry_list[] = $new_entry_list[n($r)];
        $new_entry_list[n($r)] = $_;
    }
    $_ = $__s5;
    // 掛け率をランダムに並び替え
    $new_kakeritu = [];
    $__s6 = $_ ?? null;
    foreach ($umakakeritu ?? [] as $_) {
        $s = pl_rand((pl_count($new_kakeritu) + 1));
        $new_kakeritu[] = $new_kakeritu[n($s)];
        $new_kakeritu[n($s)] = $_;
    }
    $_ = $__s6;
    $__s7 = $_ ?? null;
    foreach (pl_range(0, 5) as $_) {
        $umaname_map[$new_entry_list[n($_)]] = (string)($new_kakeritu[n($_)]);
    }
    $_ = $__s7;
    // 賭ける画面
    if (($in['command'] == '')) {
        t_header('keiba_style');
        echo <<<EOM
        	<table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
        	<tr>
        	<td bgcolor="#ffff99">馬券は１枚500円です。購入枚数を入力して「レース開始」ボタンを押してください。２頭まで賭けることができます。<br>
        	{$name}さんの持ち金：{$money}円<br>
        	※一度に購入できる馬券の枚数は{$keiba_gendomaisuu}枚までです。<br>
        	※{$deleteUser}日間ゲームしていないユーザーはランキングから削除されます。</td>
        	<td  bgcolor="#333333" align="center" width="35%"><img src="{$img_dir}/keiba_tytle.gif"></td>
        	</tr></table><br>
        	<table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
        
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="keiba">
        	<input type="hidden" name="command" value="start">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<tr><td valign="top">
        	<table cellspacing="0" cellpadding="8" width="100%" class="yosumi">
        	<tr bgcolor="#99cc66">
        	<td align="center" colspan="3">本日の出走馬</td></tr>
        	<tr bgcolor="#ffff99">
        	<td align="center">馬</td><td align="center">オッズ</td><td align="center">購入数</td></tr>
        EOM;
        $__s8 = $_ ?? null;
        foreach (pl_range(0, 5) as $_) {
            $hid_name = 'uma' . (string)($_);
            $hid_kake = 'kake' . (string)($_);
            $hid_kane = 'kane' . (string)($_);
            echo <<<EOM
            			<tr><td>
            			<input type="hidden" name="{$hid_name}" value="{$new_entry_list[n($_)]}">
            			{$new_entry_list[n($_)]}
            			</td>
            			<td align="right">
            			<input type="hidden" name="{$hid_kake}" value="{$new_kakeritu[n($_)]}">
            			{$new_kakeritu[n($_)]}倍
            			</td>
            			<td align="right"><input type="text" name="{$hid_kane}" size="10"> 枚</td></tr>
            EOM;
        }
        $_ = $__s8;
        echo <<<EOM
        		<tr><td align="center" colspan="3"><input type="submit" value="レース開始"></td></tr>
        		</table></form>
        		</td><td  valign="top" width="60%">
        		<table cellspacing="0" cellpadding="4" class="yosumi" width="100%">
        		<tr bgcolor="#ff9900"><td colspan="4" align="center">
        		ギャンブル王ベスト10
        		</td></tr>
        		<tr bgcolor="#ffffcc"><td align="center">名前</td><td align="center">トータル儲け額</td><td align="center">総投入額</td><td align="center">総獲得額</td></tr>
        		{$rank_html}
        		</table>
        		</tr></table>
        EOM;
        hooter('login_view', '戻る');
        exit;
    }
    // スタート開始画面
    if (($in['command'] == 'start')) {
        keibalock(...$_args);
        $start_html .= <<<EOM
	<form method="POST" action="{$this_script}">
	<input type="hidden" name="mode" value="keiba">
	<input type="hidden" name="command" value="go">
	<input type="hidden" name="name" value="{$in['name']}">
	<input type="hidden" name="pass" value="{$in['pass']}">
	<input type="hidden" name="town_no" value="{$in['town_no']}">
	<table border="0" width="620" bgcolor="#cc9933" align="center" cellspacing="0" cellpadding="0">
	<tr><td width="20">
	<table border="0" width="20" height="100%" bgcolor="#ffff99"><tr><td align="center"><img src="{$img_dir}/goal.gif" width="11" height="33"></td></tr></table>
	</td><td align="right">
	<table border="0" bgcolor="#ffffff"><tr><td width="120" align="center">馬</td><td width="40" align="center">オッズ</td><td width="40" align="center">購入</td></tr></table>
	<hr size="2" color="#ffffff">
EOM;
        $__s9 = $_ ?? null;
        foreach (pl_range(0, 5) as $_) {
            if (pl_match('/[^0-9]/u', $in['kane' . $_])) {
                keibaunlock(...$_args);
                error('購入数が不適切です。');
            }
            // ver.1.3
            if ($in['kane' . $_]) {
                $kaketaumanokazu++;
            }
            $kyori = pl_int((((pl_rand(60) + 10)) / ((1 + ((n($in['kake' . $_]) / 70))))));
            $keiba_temp = "{$in['uma' . $_]}<>{$in['kake' . $_]}<>{$in['kane' . $_]}<>{$kyori}<>\n";
            $hikarerugaku = n($hikarerugaku) + (n($in['kane' . $_]) * 500);
            $kounyuu_soumaisuu = n($kounyuu_soumaisuu) + n($in['kane' . $_]);
            // ver.1.2
            $now_race[] = $keiba_temp;
            if ($in['kane' . $_]) {
                $kakekin = "{$in['kane' . $_]}枚";
            } else {
                $kakekin = '';
            }
            $start_html .= <<<EOM
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td width="{$kyori}" align="left"><img src="{$img_dir}/uma/{$umagazou[(string)($in['uma' . $_])]}" width="30" height="30"></td>
		<td width="120">{$in['uma' . $_]}</td>
		<td width="40" align="right">{$in['kake' . $_]}倍</td>
		<td width="40" align="right">{$kakekin}</td>
		</tr></table><hr size="2" color="#ffffff" noshade>
EOM;
        }
        $_ = $__s9;
        if ((n($hikarerugaku) == 0)) {
            keibaunlock(...$_args);
            error('馬券が購入されていません');
        }
        if ((n($money) < n($hikarerugaku))) {
            keibaunlock(...$_args);
            error('お金が足りません');
        }
        if ((n($kounyuu_soumaisuu) > n($keiba_gendomaisuu))) {
            keibaunlock(...$_args);
            error("一度に購入できる馬券の枚数は{$keiba_gendomaisuu}枚までです");
        }
        // ver.1.2
        if ((n($kaketaumanokazu) > 2)) {
            keibaunlock(...$_args);
            error('２頭までしか賭けることができません');
        }
        t_header(...$_args);
        echo <<<EOM
        	{$start_html}
        		※「GO ! GO !」ボタンを押してレースを進めてください。<br>
        		※この画面の状態で終了させないでください（お金も支払われただけで終わります）。
        
        	</td><tr></table>
        	<br><br><div align="center"><input type="submit" value="GO ! GO !"></div>
        	</form>
        	</body></html>
        EOM;
        if (!(pl_open('KB', ">{$keiba_logfile}"))) { error("Open Error : {$keiba_logfile}"); }
        pl_print('KB', pl_join('', $now_race));
        pl_close('KB');
        $money = n($money) - n($hikarerugaku);
        // データ更新
        temp_routin(...$_args);
        log_kousin($my_log_file, $k_temp);
        exit;
    }
    // ゴーゴー画面
    if (($in['command'] == 'go')) {
        if (!(pl_exists($keibalockfile))) {
            error('時間切れのためレースは棄権扱いとなりました。');
        }
        if (!(pl_open('KB', (string)($keiba_logfile)))) { error("Open Error : {$keiba_logfile}"); }
        $keiba_hairetu = pl_readlines('KB');
        pl_close('KB');
        t_header(...$_args);
        echo <<<EOM
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="keiba">
        	<input type="hidden" name="command" value="go">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<table border="0" width="620" bgcolor="#cc9933" align="center" cellspacing="0" cellpadding="0">
        	<tr><td width="20">
        	<table border="0" width="20" height="100%" bgcolor="#ffff99"><tr><td align="center"><img src="{$img_dir}/goal.gif" width="11" height="33"></td></tr></table>
        	</td><td align="right">
        	<table border="0" bgcolor="#ffffff"><tr><td width="120" align="center">馬</td><td width="40" align="center">オッズ</td><td width="40" align="center">購入</td></tr></table>
        	<hr size="2" color="#ffffff">
        EOM;
        $now_race = [];
        $kekkahantei = [];
        // １ターン
        $__s10 = $_ ?? null;
        foreach ($keiba_hairetu ?? [] as $_) {
            [$umaname, $ods, $kane, $kyori] = pl_pad(pl_split('/<>/u', $_), 4);
            $kyori = n($kyori) + pl_int((((pl_rand(100) + 0)) / ((1 + ((n($ods) / 80))))));
            if ((n($kyori) >= 400)) {
                $kyori = 400;
            }
            $kekkahantei[] = $kyori;
            $keiba_temp = "{$umaname}<>{$ods}<>{$kane}<>{$kyori}<>\n";
            $now_race[] = $keiba_temp;
            if ($kane) {
                $kakekin = "{$kane}枚";
            } else {
                $kakekin = '';
            }
            echo <<<EOM
            		<table border="0" cellspacing="0" cellpadding="0">
            		<tr>
            		<td width="{$kyori}" align="left"><img src="{$img_dir}/uma/{$umagazou[(string)($umaname)]}" width="30" height="30"></td>
            		<td width="120">{$umaname}</td>
            		<td width="40" align="right">{$ods}倍</td>
            		<td width="40" align="right">{$kakekin}</td>
            		</tr></table><hr size="2" color="#ffffff" noshade>
            EOM;
        }
        $_ = $__s10;
        // 結果判定
        $__s11 = $_ ?? null;
        foreach (pl_range(0, 5) as $_) {
            if ((n($kekkahantei[n($_)]) >= 400)) {
                $winner = pl_idx(pl_split('/<>/u', $now_race[n($_)]), 0);
                $win_hairetu[] = $winner;
            }
        }
        $_ = $__s11;
        // ゴールした場合
        if (!empty($win_hairetu)) {
            if ((pl_count($win_hairetu) >= 2)) {
                $syasin_randed = pl_rand(((pl_count($win_hairetu) - 1) + 1));
                $kekkahappyou = "{$__(pl_join(' ', $win_hairetu))}がほぼ同時にゴールインしましたが、写真判定の結果、{$win_hairetu[n($syasin_randed)]}が１着となりました！";
                $win_uma = (string)($win_hairetu[n($syasin_randed)]);
            } else {
                $kekkahappyou = "{$__(pl_join(' ', $win_hairetu))}が１着でゴールイン！";
                $win_uma = (string)(pl_join(' ', $win_hairetu));
            }
            $__s12 = $_ ?? null;
            foreach ($now_race ?? [] as $_) {
                [$umaname, $ods, $kane, $kyori] = pl_pad(pl_split('/<>/u', $_), 4);
                if (((string)$umaname === (string)(string)($win_uma))) {
                    $kakutokugaku = ((n($ods) * n($kane)) * 500);
                    if ((n($kakutokugaku) == 0)) {
                        $kakutokuhyouzi = '残念ながら配当金はありません';
                    } else {
                        $kakutokuhyouzi = "{$kakutokugaku}円";
                    }
                }
                $soukounyuu = n($soukounyuu) + (n($kane) * 500);
            }
            $_ = $__s12;
            echo <<<EOM
            		<div align="center"><br><table  border="0"  cellspacing="5" cellpadding="0" width="300" style="{$message_win}"><tr><td>
            		<div  class="tyuu">{$kekkahappyou}</div>
            		購入金額：{$soukounyuu}円<br>
            		獲得金額：{$kakutokuhyouzi}
            		</td></tr></table><br>
            		</td></tr>
            		</table>
            		</form>
            
            	<div align="center"><form method="POST" action="{$this_script}">
            	<input type="hidden" name="mode" value="keiba">
            	<input type="hidden" name="iesettei_id" value="{$in['iesettei_id']}">
            	<input type="hidden" name="name" value="{$in['name']}">
            	<input type="hidden" name="pass" value="{$in['pass']}">
            	<input type="hidden" name="admin_pass" value="{$in['admin_pass']}">
            	<input type="hidden" name="town_no" value="{$in['town_no']}">
            	<input type="submit" value="再挑戦">
            	</form></div>
            
            EOM;
            hooter('login_view', '戻る');
            echo '</body></html>';
            $money = n($money) + n($kakutokugaku);
            // データ更新
            temp_routin(...$_args);
            log_kousin($my_log_file, $k_temp);
            // ランキングファイル更新
            if (!(pl_open('KR', (string)($keibarank_logfile)))) { error("Open Error : {$keibarank_logfile}"); }
            $keiba_ranking = pl_readlines('KR');
            pl_close('KR');
            $kizon_flag = 0;
            $__s13 = $_ ?? null;
            foreach ($keiba_ranking ?? [] as $_) {
                [$kr_name, $kr_moukegaku, $kr_tounyuugaku, $kr_kakutokugaku, $kr_yobi1, $kr_yobi2, $kr_yobi3, $kr_yobi4] = pl_pad(pl_split('/<>/u', $_), 8);
                if (((string)$name === (string)(string)($kr_name))) {
                    $kr_tounyuugaku = n($kr_tounyuugaku) + n($soukounyuu);
                    $kr_kakutokugaku = n($kr_kakutokugaku) + n($kakutokugaku);
                    $kr_moukegaku = (n($kr_kakutokugaku) - n($kr_tounyuugaku));
                    $kizon_flag = 1;
                    $kr_yobi1 = $now_time;
                }
                // if ($now_time - $kr_yobi1> 60*60*24*$deleteUser){next;}
                $kr_rank_temp = "{$kr_name}<>{$kr_moukegaku}<>{$kr_tounyuugaku}<>{$kr_kakutokugaku}<>{$kr_yobi1}<>{$kr_yobi2}<>{$kr_yobi3}<>{$kr_yobi4}<>\n";
                $new_keiba_ranking[] = $kr_rank_temp;
            }
            $_ = $__s13;
            if ((n($kizon_flag) == 0)) {
                $moukegaku = (n($kakutokugaku) - n($soukounyuu));
                $kr_rank_temp = "{$name}<>{$moukegaku}<>{$soukounyuu}<>{$kakutokugaku}<>{$now_time}<><><><>\n";
                $new_keiba_ranking[] = $kr_rank_temp;
            }
            if (!(pl_open('KRO', ">{$keibarank_logfile}"))) { error("Open Error : {$keibarank_logfile}"); }
            pl_print('KRO', pl_join('', $new_keiba_ranking));
            pl_close('KRO');
            keibaunlock(...$_args);
            // レース続行
        } else {
            echo <<<EOM
            		※「GO ! GO !」ボタンを押してレースを進めてください。<br>
            		※この画面の状態で終了させないでください（お金も支払われただけで終わります）。
            
            		</td></tr>
            		</table>
            		<br><br><div align="center"><input type="submit" value="GO ! GO !"></div>
            		</form>
            		</body></html>
            EOM;
            if (!(pl_open('KB', ">{$keiba_logfile}"))) { error("Open Error : {$keiba_logfile}"); }
            pl_print('KB', pl_join('', $now_race));
            pl_close('KB');
        }
        exit;
    }
    // goコマンドの場合の閉じ
}

function keibalock(...$_args)
{
    global $keibalockfile, $keibalockflag;
    $retry = null;
    $mtime = null;
    // 5分以上古いロックは削除する
    if (pl_exists($keibalockfile)) {
        $mtime = pl_idx([pl_idx(pl_stat($keibalockfile), 9)], 0);
        if ((n($mtime) < (pl_time() - 300))) {
            keibaunlock(...$_args);
        }
    }
    // リトライは10回
    $retry = 1;
    while (!(pl_mkdir($keibalockfile))) {
        if ((n(--$retry) <= 0)) {
            error('ただいまレース開催中です。少々お待ちください。');
        }
        pl_sleep(1);
    }
    // ロックフラグを立てる
    $keibalockflag = 1;
}

function keibaunlock(...$_args)
{
    global $clear, $keiba_logfile, $keibalockfile, $keibalockflag;
    if (!(pl_open('KB', ">{$keiba_logfile}"))) { error("Open Error : {$keiba_logfile}"); }
    pl_print('KB', pl_join('', $clear));
    pl_close('KB');
    // ロックディレクトリ削除
    pl_rmdir($keibalockfile);
    // ロックフラグを解除
    $keibalockflag = 0;
}

function donus(...$_args)
{
    global $_, $card_bangou, $card_suu, $comment, $crad_game_time, $do2_hantei, $do2_hiitakard, $do2_last, $do2_name,
        $do2_narandacard, $do2_yobi1, $do_hantei, $do_hiitakard, $do_last, $do_name, $do_narandacard, $do_yobi1,
        $donuts_alldata, $donuts_logfile, $i, $ikura, $img_dir, $in, $k_temp, $kingaku, $line, $money, $my_card,
        $my_card_gazou, $my_log_file, $name, $next_temp, $now_time, $out_gaku, $randed, $saigonohito, $sankasyasuu,
        $siharai, $table_card_image, $tajuukinsi_flag, $this_script, $your_card;
    if ((n($tajuukinsi_flag) == 1)) {
        tajuucheck(...$_args);
    }
    if (!(pl_open('MA', (string)($donuts_logfile)))) { error("{$donuts_logfile}が開けません"); }
    $saigonohito = pl_readline('MA');
    $donuts_alldata = pl_readlines('MA');
    pl_close('MA');
    $sankasyasuu = pl_count($donuts_alldata);
    [$do_name, $do_hantei, $do_hiitakard, $do_narandacard, $do_yobi1, $do_last] = pl_pad(pl_split('/<>/u', $saigonohito), 6);
    // do_yobi1 = 支払額
    if (($do_narandacard == '')) {
        $do_narandacard = 1;
    }
    $card_suu = pl_length($do_narandacard);
    $my_card = "<img src=\"{$img_dir}/donuts/ura.gif\" width=\"64\" heith=\"84\">";
    // 引くコマンドだった場合
    if (($in['command'] == 'hiku')) {
        $now_time = pl_time();
        if (((string)$name === (string)$do_name)) {
            error('同じ方が続けてカードを引くことはできません');
        }
        $__s1 = $_ ?? null;
        foreach ($donuts_alldata ?? [] as $_) {
            [$do2_name, $do2_hantei, $do2_hiitakard, $do2_narandacard, $do2_yobi1, $do2_last] = pl_pad(pl_split('/<>/u', $_), 6);
            if (((string)$name === (string)$do2_name)) {
                if (((n($now_time) - n($do2_last)) < (60 * n($crad_game_time)))) {
                    error("最後にゲームしてからまだ{$crad_game_time}分すぎていません。");
                }
            }
        }
        $_ = $__s1;
        $randed = (pl_int(pl_rand(5)) + 1);
        $my_card_gazou = "{$img_dir}/donuts/" . (string)($randed) . 'a.gif';
        $my_card = "<img src=\"{$my_card_gazou}\" width=\"64\" heith=\"84\">";
        $kingaku = (n($card_suu) * 10000);
        // アウトの場合
        if ((n($randed) == n($do_hiitakard))) {
            $money = n($money) - (n($kingaku) * 1);
            $do_hantei = 'out';
            $do_narandacard = (string)($randed);
            $out_gaku = (n($card_suu) * 1);
            $comment = "<div class=\"mainasu\">アウトォーー！！<br>{$out_gaku}万円を支払いました！</div>";
            $siharai = (n($card_suu) * 1);
            $card_suu = 1;
            // セーフの場合
        } else {
            $money = n($money) + n($kingaku);
            $do_hantei = 'safe';
            $do_narandacard = (string)($do_narandacard) . (string)($randed);
            $comment = "<div class=\"purasu\">セーフ！！<br>{$card_suu}万円をゲットしました！</div>";
            $siharai = $card_suu;
        }
        $next_temp = "{$name}<>{$do_hantei}<>{$randed}<>{$do_narandacard}<>{$siharai}<>{$now_time}<>\n";
        pl_unshift($donuts_alldata, $saigonohito);
        pl_unshift($donuts_alldata, $next_temp);
        if ((n($sankasyasuu) >= 19)) {
            pl_pop($donuts_alldata);
        }
        // データ更新
        lock(...$_args);
        if (!(pl_open('KB', ">{$donuts_logfile}"))) { error("Open Error : {$donuts_logfile}"); }
        pl_print('KB', pl_join('', $donuts_alldata));
        pl_close('KB');
        unlock(...$_args);
        temp_routin(...$_args);
        log_kousin($my_log_file, $k_temp);
    }
    // 引く場合の閉じ
    t_header('gym_style');
    echo <<<EOM
    	<table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr>
    	<td bgcolor="#ffffff">●ルール説明<br>
    	・参加者がすることはカードを１枚引くことだけです。<br>・引いたカードが前の人のカードと違っていれば、その時点でテーブルにたまっているカードの数×１万円のお金がもらえ、またそのカードがテーブルにたまっていきます。<br>・同じ数が出てしまった場合、逆にカードの数×１万円のお金を支払わなければいけません。またテーブルのカードは１枚からスタートとなります。<br>※同じ人が続けてカードを引くことはできません。※ゲーム間隔は{$crad_game_time}分です。</td>
    	<td  bgcolor="#333333" align="center" width="35%"><img src="{$img_dir}/donuts_tytle.gif"></td>
    	</tr></table><br>
    	<table width="90%" border="0" cellspacing="0" cellpadding="8" align="center" class="yosumi"><tr><td>
    	<div align="center">
    	{$my_card}
    	{$comment}
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="donus">
    	<input type="hidden" name="command" value="hiku">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value="カードを引く">
    	</form></div>
    EOM;
    if (($do_hiitakard != '')) {
        $your_card = "{$img_dir}/donuts/{$do_hiitakard}" . '.gif';
        echo <<<EOM
        	<div align="center"><img src="{$your_card}" width="60" heith="80"></div>
        	<div align="center">前の人が引いたカード</div>
        EOM;
    }
    for ($i = 0; (n($i) < n($card_suu)); $i++) {
        $card_bangou = pl_substr($do_narandacard, n($i), 1);
        $table_card_image = "{$img_dir}/donuts/{$card_bangou}" . '.gif';
        $line .= "<img src=\"{$table_card_image}\" width=\"30\" height=\"40\">\n";
    }
    echo <<<EOM
    	</td><td width="60%" valign="top">
    		<div class="job_messe">＜現在までの引かれたカード＞</div>
    		<table  border="0" cellspacing="0" cellpadding="10" width="100%" height="80%" bgcolor="#66cc33" style="border: #663300; border-style: solid; border-width: 2px;">
    		<tr><td>
    		{$line}
    		</td></tr></table>
    	</td></tr></table>
    
    <br><table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr><td>
    	<div class="honbun2">■最近のゲーム</div>
    EOM;
    if (($in['command'] == '')) {
        pl_unshift($donuts_alldata, $saigonohito);
    }
    if ((pl_length($saigonohito) != 0)) {
        $__s2 = $_ ?? null;
        foreach ($donuts_alldata ?? [] as $_) {
            [$do_name, $do_hantei, $do_hiitakard, $do_narandacard, $do_yobi1, $do_last] = pl_pad(pl_split('/<>/u', $_), 6);
            $ikura = pl_length($do_narandacard);
            if (($do_hantei == 'out')) {
                echo "<div class=\"mainasu\">{$do_name}さんが{$do_yobi1}万円を支払いました。</div>";
            } else {
                echo "<div class=\"purasu\">{$do_name}さんが{$do_yobi1}万円をゲットしました。</div>";
            }
        }
        $_ = $__s2;
    }
    echo <<<EOM
    	</td></tr></table>
    EOM;
    hooter('login_view', '戻る');
    exit;
}

function byouin(...$_args)
{
    global $bank, $byouki_sisuu, $byoumei, $ima_azuke, $img_dir, $in, $k_temp, $money, $my_log_file, $super_gaku,
        $super_teiki, $this_script, $tiryouhi;
    if ($in['command']) {
        if (($in['command'] == '風邪ぎみ')) {
            $tiryouhi = 18000;
        } elseif (($in['command'] == '風邪')) {
            $tiryouhi = 28000;
        } elseif (($in['command'] == '肺炎')) {
            $tiryouhi = 35000;
        } elseif (($in['command'] == '結核')) {
            $tiryouhi = 48000;
        } elseif (($in['command'] == '癌')) {
            $tiryouhi = 88000;
        } elseif (($in['command'] == '元気')) {
            $tiryouhi = 10000;
        }
        if ((n($money) < n($tiryouhi))) {
            error('お金が足りません');
        }
        $money = n($money) - n($tiryouhi);
        $byouki_sisuu = 50;
        $byoumei = '';
        // ログ更新
        temp_routin(...$_args);
        log_kousin($my_log_file, $k_temp);
        message('病気の治療をしました。もうこれで安心です。病気のさいはまた当院をご利用くださいませ。', 'login_view');
    }
    t_header('ginkou_style');
    if (($bank == '')) {
        $ima_azuke = '無し';
    } else {
        $ima_azuke = "{$bank}円";
    }
    if ((n($super_teiki) > 0)) {
        $super_gaku = "●スーパー定期預金額：{$super_teiki}円";
    }
    echo <<<EOM
    	<table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr>
    	<td bgcolor="#ffffff">いらっしゃいませ。どんな病気もたちどころに治します。</td>
    	<td  bgcolor="#333333" align="center" width="35%"><img src="{$img_dir}/byouin_tytle.gif"></td>
    	</tr></table><br>
    
    	<table width="90%" border="0" cellspacing="0" cellpadding="8" align="center" class="yosumi"><tr>
    	<td>
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="byouin">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    EOM;
    if (($byoumei == '風邪ぎみ')) {
        echo <<<EOM
        		<div class="honbun4" align="center">
        		軽度の風邪のようですね。<br>注射を打っておきましょう。<br>治療費に18000円かかります。<br>よろしいですか？<br>
        		<input type="hidden" name="command" value="風邪ぎみ">
        		<input type="submit" value="お願いします"></form>
        		</div>
        EOM;
        hooter('login_view', 'ぼったくりっぽいのでやめる');
    } elseif (($byoumei == '風邪')) {
        echo <<<EOM
        		<div class="honbun4" align="center">
        		ふむふむ。単なる風邪ですね。注射を打てばすぐに治りますよ。<br>治療費に28000円かかります。<br>よろしいですね？<br>
        		<input type="hidden" name="command" value="風邪">
        		<input type="submit" value="お願いします"></form>
        		</div>
        EOM;
        hooter('login_view', 'ぼったくりっぽいのでやめる');
    } elseif (($byoumei == '肺炎')) {
        echo <<<EOM
        		<div class="honbun4" align="center">
        		うーむ。肺炎ですね。風邪を引いてから無理するからですよ。<br>でもご安心ください。<br>うちの注射を打てばすぐに治りますよ。<br>費用はたった35000円です。<br>安いもんですよね。<br>
        		<input type="hidden" name="command" value="肺炎">
        		<input type="submit" value="お願いします"></form>
        		</div>
        EOM;
        hooter('login_view', '自力で治すから結構です');
    } elseif (($byoumei == '結核')) {
        echo <<<EOM
        		<div class="honbun4" align="center">
        		むむ。。いけませんなぁ。結核のようです。。<br>もっと早くうちへ来てくれれば良かったのに。。<br>でももう大丈夫。本来入院が必要ですが、<br>うちの注射を打てばあっという間に治ってしまいますよ。<br>ただし費用は48000円かかります。。<br>まぁ、これで結核が治れば安いものかと。。。<br>
        		<input type="hidden" name="command" value="結核">
        		<input type="submit" value="お願いします"></form>
        		</div>
        EOM;
        hooter('login_view', '高い！やめる！');
    } elseif (($byoumei == '脳腫瘍')) {
        echo <<<EOM
        		<div class="honbun4" align="center">
        		ま、まずいですよぉ。当病院の的確な検査の結果、脳腫瘍と診断されました。。<br>このままでは生死にかかわります。<br>ただちに手術しましょう！<br>そうすればすぐに治ります。<br>費用は64000円かかりますが、お金のことをとやかく言ってる場合ではありません。<br>
        		<input type="hidden" name="command" value="脳腫瘍">
        		<input type="submit" value="お願いします"></form>
        		</div>
        EOM;
        hooter('login_view', '高すぎだ！やめる！');
    } elseif (($byoumei == '癌')) {
        echo <<<EOM
        		<div class="honbun4" align="center">
        		ガーン。。なんちゃって。い、いや、大変なことになりました！！<br>あなたは癌です！これは治るから告知しているのですよ。<br>ただし、真っ先に手術が必要です！<br>この街のブラックジャックと呼ばれた私の腕にかかれば<br>癌ですらすぐに治すことが可能です。<br>ただし費用はちょっとかかりますが。。。<br>大負けに負けて88000円です。<br>でもこのままでは死んでしまいます！<br>迷ってるヒマはありません！<br>
        		<input type="hidden" name="command" value="癌">
        		<input type="submit" value="お願いします"></form>
        		</div>
        EOM;
        hooter('login_view', '死んでもいい！そんな金払えん！');
    } elseif (($byoumei == '')) {
        echo <<<EOM
        		<div class="honbun4" align="center">
        		どこも悪いところはないようです。<br>念のため注射を打っておきますか？<br>10000円かかりますが。。<br><br>
        		<input type="hidden" name="command" value="元気">
        		<input type="submit" value="お願いします"></form>
        		</div>
        EOM;
        hooter('login_view', '金を取られる前に退散する');
    } else {
        echo <<<EOM
        		<div class="honbun4" align="center">
        		むむむむむ？？　これは。。。私の手に負えない病気です。。。<br>どこか他を当たってくださいませ。</div>
        EOM;
        hooter('login_view', '失意のまま病院を後にする');
    }
    echo <<<EOM
    	</td></tr></table>
    EOM;
    hooter('login_view', '戻る');
    exit;
}

function onsen(...$_args)
{
    global $img_dir, $in, $k_temp, $money, $my_log_file, $nyuuyokuryou, $o_kakaruhiyou, $on_gazou_suu, $onsen_times,
        $onsensyurui, $script, $this_script, $tokubetu_times, $tokubetuburo_hiyou;
    if (($in['onsensyurui'] == 'koukyuu')) {
        $onsensyurui = 'two';
        $o_kakaruhiyou = $tokubetuburo_hiyou;
    } else {
        $onsensyurui = 'one';
        $o_kakaruhiyou = $nyuuyokuryou;
    }
    $money = n($money) - n($o_kakaruhiyou);
    // ログ更新
    temp_routin(...$_args);
    log_kousin($my_log_file, $k_temp);
    $gazou_bangou = (pl_int(pl_rand(n($on_gazou_suu))) + 1);
    $onsen_gazou = (string)($gazou_bangou) . '.jpg';
    ori_header("background-color : #336699; background-repeat : no-repeat; background-position : center center; background-image : url( {$img_dir}/onsen/{$onsen_gazou})");
    echo <<<EOM
    	<table  border="0"  cellspacing="5" cellpadding="0" width="100%" height="70%"><tr><td valign="top">
    	<br><table  border="0"  cellspacing="2" cellpadding="0" width="370" align="center" bgcolor="#ffffcc"><tr><td>
    EOM;
    if (($in['onsensyurui'] == 'koukyuu')) {
        echo "<div style=\"font-size:11px\">特別風呂入浴中のため通常の{$tokubetu_times}倍の早さでパワーが回復します。</div>";
    } else {
        echo "<div style=\"font-size:11px\">入浴中は通常の{$onsen_times}倍の早さでパワーが回復します。\n\t<br>※{$tokubetuburo_hiyou}円払って特別風呂に入ると{$tokubetu_times}倍の早さでパワーが回復します。</div>";
    }
    echo <<<EOM
    	</td></tr></table><br>
    	<table  border="0"  cellspacing="5" cellpadding="0" width="200" align="center"><tr><td>
    	<div align="center"><img src="{$img_dir}/nyuuyoku.gif" width="110" height="30"></div>
    	</td></tr></table>
    	</td></tr></table>
    	<div align="center">
    EOM;
    if (($in['onsensyurui'] != 'koukyuu')) {
        echo <<<EOM
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="onsen">
        	<input type="hidden" name="onsensyurui" value="koukyuu">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<input type="submit" value="特別風呂に入る">
        	</form>
        EOM;
    }
    echo <<<EOM
    	<form method="POST" action="{$script}">
    	<input type="hidden" name="mode" value="login_view">
    	<input type="hidden" name="iiyudane" value="{$onsensyurui}">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value="お湯から出る">
    	</form>
    	</div>
    	</body></html>
    EOM;
    exit;
}

function prof(...$_args)
{
    global $_, $address_array, $age_array, $alldata, $atta_flag, $back, $hyouzi_max_grobal, $i, $i3, $img_dir, $in,
        $k_id, $kijutu_prof, $my_prof_hairetu, $name, $new_alldata, $newrank, $next, $page, $pr_koumokumei,
        $pro_addr, $pro_age, $pro_name, $pro_p1, $pro_p10, $pro_p11, $pro_p12, $pro_p13, $pro_p14, $pro_p15,
        $pro_p16, $pro_p17, $pro_p18, $pro_p19, $pro_p2, $pro_p20, $pro_p3, $pro_p4, $pro_p5, $pro_p6, $pro_p7,
        $pro_p8, $pro_p9, $pro_sex, $prof_array1, $prof_array2, $prof_array3, $prof_array4, $prof_array5,
        $prof_name1, $prof_name2, $prof_name3, $prof_name4, $prof_name5, $profile_file, $script, $sex_array,
        $sex_style, $this_script, $total_touroku_suu;
    if (!(pl_open('IN', (string)($profile_file)))) { error("Open Error : {$profile_file}"); }
    $alldata = pl_readlines('IN');
    $total_touroku_suu = pl_count($alldata);
    pl_close('IN');
    // 登録フォームの出力
    if (($in['command'] == 'touroku_form')) {
        prof_form(...$_args);
        exit;
    }
    // 登録の場合
    if (($in['command'] == 'touroku')) {
        $atta_flag = 0;
        $new_alldata = [];
        $__s1 = $_ ?? null;
        foreach ($alldata ?? [] as $_) {
            [$pro_name, $pro_sex, $pro_age, $pro_addr, $pro_p1, $pro_p2, $pro_p3, $pro_p4, $pro_p5, $pro_p6, $pro_p7, $pro_p8, $pro_p9, $pro_p10, $pro_p11, $pro_p12, $pro_p13, $pro_p14, $pro_p15, $pro_p16, $pro_p17, $pro_p18, $pro_p19, $pro_p20] = pl_pad(pl_split('/<>/u', $_), 24);
            // 修正の場合
            if (((string)$name === (string)(string)($pro_name))) {
                continue;
            }
            $new_alldata[] = $_;
        }
        $_ = $__s1;
        $new_entry = pl_idx(["{$name}<>{$in['pro_sex']}<>{$in['pro_age']}<>{$in['pro_addr']}<>{$in['pro_p1']}<>{$in['pro_p2']}<>{$in['pro_p3']}<>{$in['pro_p4']}<>{$in['pro_p5']}<>{$in['pro_p6']}<>{$in['pro_p7']}<>{$in['pro_p8']}<>{$in['pro_p9']}<>{$in['pro_p10']}<>{$in['pro_p11']}<>{$in['pro_p12']}<>{$in['pro_p13']}<>{$in['pro_p14']}<>{$in['pro_p15']}<>{$in['pro_p16']}<>{$in['pro_p17']}<>{$in['pro_p18']}<>{$in['pro_p19']}<>{$in['pro_p20']}<>\n"], 0);
        pl_unshift($new_alldata, $new_entry);
        // ログ更新
        lock(...$_args);
        if (!(pl_open('OUT', ">{$profile_file}"))) { error("{$profile_file}に書き込めません"); }
        pl_print('OUT', pl_join('', $new_alldata));
        pl_close('OUT');
        unlock(...$_args);
        message('プロフィールの登録を行いました。', 'prof', 'basic.php');
        exit;
    }
    t_header('prof_style');
    echo <<<EOM
    	<table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr>
    	<td bgcolor="#ffffff">バーチャルではなく、本当のプロフィールを登録したり、閲覧したりする場所です。現在登録数：{$total_touroku_suu}人
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="prof">
    	<input type="hidden" name="command" value="touroku_form">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value="プロフィール登録＆修正">
    	</form>
    	</td>
    	<td  bgcolor="#333333" align="center" width="35%"><img src="{$img_dir}/prof_tytle.gif">
    	</td></tr></table><br>
    
    	<table width="90%" border="0" cellspacing="0" cellpadding="8" align="center" bgcolor="#ffcc66">
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="prof">
    	<input type="hidden" name="command" value="easySerch">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<tr>
    EOM;
    // 検索フォーム
    // 名前
    echo "<td>名　前 <input type=\"text\" name=\"serch_name\" size=\"20\"></td>\n";
    // 性別
    echo "<td><select name=\"sex\">\n";
    echo "<option value=\"99\">性別\n";
    for ($i = 1; (n($i) < pl_count($sex_array)); $i++) {
        $option = '<option>';
        if ((((string)$in['sex'] === (string)$sex_array[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $sex_array[n($i)];
    }
    echo "</select></td>\n";
    // 年齢
    echo "<td><select name=\"age\">\n";
    echo "<option value=\"99\">年齢\n";
    for ($i = 1; (n($i) < pl_count($age_array)); $i++) {
        $option = '<option>';
        if ((((string)$in['age'] === (string)$age_array[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $age_array[n($i)];
    }
    echo "</select></td>\n";
    // 住所
    echo "<td><select name=\"address\">\n";
    echo "<option value=\"99\">住所\n";
    for ($i = 1; (n($i) < pl_count($address_array)); $i++) {
        $option = '<option>';
        if ((((string)$in['address'] === (string)$address_array[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $address_array[n($i)];
    }
    echo "</select></td>\n";
    // プロフ1
    echo "<td valign=\"top\" nowrap>\n";
    echo "<select name=\"p1\">\n";
    echo "<option value=\"99\">{$prof_name1}\n";
    for ($i = 1; (n($i) < pl_count($prof_array1)); $i++) {
        $option = '<option>';
        if ((((string)$in['p1'] === (string)$prof_array1[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $prof_array1[n($i)];
    }
    echo "</select></td></tr><tr>\n";
    // プロフ2
    echo "<td valign=\"top\" nowrap>\n";
    echo "<select name=\"p2\">\n";
    echo "<option value=\"99\">{$prof_name2}\n";
    for ($i = 1; (n($i) < pl_count($prof_array2)); $i++) {
        $option = '<option>';
        if ((((string)$in['p2'] === (string)$prof_array2[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $prof_array2[n($i)];
    }
    echo "</select></td>\n";
    // プロフ3
    echo "<td valign=\"top\" nowrap>\n";
    echo "<select name=\"p3\">\n";
    echo "<option value=\"99\" selected>{$prof_name3}\n";
    for ($i = 1; (n($i) < pl_count($prof_array3)); $i++) {
        $option = '<option>';
        if ((((string)$in['p3'] === (string)$prof_array3[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $prof_array3[n($i)];
    }
    echo "</select></td>\n";
    // プロフ4
    echo "<td valign=\"top\" nowrap>\n";
    echo "<select name=\"p4\">\n";
    echo "<option value=\"99\" selected>{$prof_name4}\n";
    for ($i = 1; (n($i) < pl_count($prof_array4)); $i++) {
        $option = '<option>';
        if ((((string)$in['p4'] === (string)$prof_array4[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $prof_array4[n($i)];
    }
    echo "</select></td>\n";
    // プロフ5
    echo "<td valign=\"top\" nowrap>\n";
    echo "<select name=\"p5\">\n";
    echo "<option value=\"99\" selected>{$prof_name5}\n";
    for ($i = 1; (n($i) < pl_count($prof_array5)); $i++) {
        $option = '<option>';
        if ((((string)$in['p5'] === (string)$prof_array5[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $prof_array5[n($i)];
    }
    echo <<<EOM
    	</select></td>
    	<td>
    	<input type="submit" value=" 検索する ">
    	</form>
    	  </td>
        </tr>
      </table><br>
    EOM;
    hooter('login_view', '街へ戻る');
    // 簡易検索の場合
    if (($in['command'] == 'easySerch')) {
        $i = 0;
        $__s2 = $_ ?? null;
        foreach ($alldata ?? [] as $_) {
            [$pro_name, $pro_sex, $pro_age, $pro_addr, $pro_p1, $pro_p2, $pro_p3, $pro_p4, $pro_p5, $pro_p6, $pro_p7, $pro_p8, $pro_p9, $pro_p10, $pro_p11, $pro_p12, $pro_p13, $pro_p14, $pro_p15, $pro_p16, $pro_p17, $pro_p18, $pro_p19, $pro_p20] = pl_pad(pl_split('/<>/u', $_), 24);
            if ((($in['serch_name'] != '') && ((string)$in['serch_name'] !== (string)$pro_name))) {
                continue;
            }
            // 条件が一致しなかった時点で次の人へ
            if (((n($in['sex']) != 99) && ((string)$in['sex'] !== (string)$pro_sex))) {
                continue;
            }
            if (((n($in['age']) != 99) && ((string)$in['age'] !== (string)$pro_age))) {
                continue;
            }
            if (((n($in['address']) != 99) && ((string)$in['address'] !== (string)$pro_addr))) {
                continue;
            }
            if (((n($in['p1']) != 99) && ((string)$in['p1'] !== (string)$pro_p1))) {
                continue;
            }
            if (((n($in['p2']) != 99) && ((string)$in['p2'] !== (string)$pro_p2))) {
                continue;
            }
            if (((n($in['p3']) != 99) && ((string)$in['p3'] !== (string)$pro_p3))) {
                continue;
            }
            if (((n($in['p4']) != 99) && ((string)$in['p4'] !== (string)$pro_p4))) {
                continue;
            }
            if (((n($in['p5']) != 99) && ((string)$in['p5'] !== (string)$pro_p5))) {
                continue;
            }
            $i++;
            $newrank[] = $_;
        }
        $_ = $__s2;
        $alldata = $newrank;
        echo "<div align=\"center\" class=\"sub2\">条件にヒットしたのは{$i}件です。</div><br>";
        hooter('prof', '全て表示', 'basic.php');
    }
    $page = $in['page'];
    if (($page == '')) {
        $page = 0;
    }
    $i3 = 0;
    // #ログ表示処理
    $__s3 = $_ ?? null;
    foreach ($alldata ?? [] as $_) {
        [$pro_name, $pro_sex, $pro_age, $pro_addr, $pro_p1, $pro_p2, $pro_p3, $pro_p4, $pro_p5, $pro_p6, $pro_p7, $pro_p8, $pro_p9, $pro_p10, $pro_p11, $pro_p12, $pro_p13, $pro_p14, $pro_p15, $pro_p16, $pro_p17, $pro_p18, $pro_p19, $pro_p20] = pl_pad(pl_split('/<>/u', $_), 24);
        $my_prof_hairetu = pl_split('/<>/u', $_);
        $i3++;
        if ((n($i3) < (n($page) + 1))) {
            continue;
        }
        if ((n($i3) > (n($page) + n($hyouzi_max_grobal)))) {
            break;
        }
        if (($pro_sex == '男')) {
            $sex_style = 'border: #99ccff; border-style: solid; border-width: 3px; background-color:#ffffcc';
        } elseif (($pro_sex == '女')) {
            $sex_style = 'border: #ff9999; border-style: solid; border-width: 3px; background-color:#ffffcc';
        } else {
            $sex_style = 'border: #cccccc; border-style: solid; border-width: 3px; background-color:#ffffcc';
        }
        echo "<table style=\"{$sex_style}\" align=\"center\" width=\"450\">";
        $i = 1;
        $__s4 = $_ ?? null;
        foreach ($my_prof_hairetu ?? [] as $__k4 => $_) {
            try {
                $_ = preg_replace('/([^=^\\"]|^)(https?\\:[\\w\\.\\~\\-\\/\\?\\&\\+\\=\\:\\@\\%\\;\\#\\%]+)/u', '${1}<a href="${2}" target="_blank">${2}</a>', (string)$_, -1);
                if ((n($i) == 1)) {
                    $pr_koumokumei = '名前';
                } elseif ((n($i) == 2)) {
                    $pr_koumokumei = '性別';
                } elseif ((n($i) == 3)) {
                    $pr_koumokumei = '年齢';
                } elseif ((n($i) == 4)) {
                    $pr_koumokumei = '住所';
                } elseif ((n($i) == 5)) {
                    $pr_koumokumei = (string)($prof_name1);
                } elseif ((n($i) == 6)) {
                    $pr_koumokumei = (string)($prof_name2);
                } elseif ((n($i) == 7)) {
                    $pr_koumokumei = (string)($prof_name3);
                } elseif ((n($i) == 8)) {
                    $pr_koumokumei = (string)($prof_name4);
                } elseif ((n($i) == 9)) {
                    $pr_koumokumei = (string)($prof_name5);
                } else {
                    $pr_koumokumei = $kijutu_prof[(n($i) - 10)];
                }
                if ((($_ != '') && ($_ != "\n"))) {
                    echo <<<EOM
                    					<tr><td align="right" width="120"><div class="honbun2">{$pr_koumokumei}</div></td>
                    					<td>：{$_}</td></tr>
                    EOM;
                }
                $i++;
            } finally { $my_prof_hairetu[$__k4] = $_; }
        }
        $_ = $__s4;
        // foreach閉じ
        echo <<<EOM
             <tr><td colspan="2" align="center"><form method="POST" action="{$script}">
        	<input type="hidden" name="mode" value="mail_sousin">
        	<input type="hidden" name="name" value="{$name}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="id" value="{$k_id}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<input type="hidden" name="sousinsaki_name" value="{$pro_name}">
        	<input type="submit" value="メッセージ送信"></form>
        	</td></tr>
        	</table><br>
        EOM;
    }
    $_ = $__s3;
    // foreachの閉じ（ログ表示処理ここまで）
    $next = (n($page) + n($hyouzi_max_grobal));
    $back = (n($page) - n($hyouzi_max_grobal));
    echo '<div align="center"><table border="0"><tr>';
    if ((n($back) >= 0)) {
        // 検索の場合のボタン
        if (($in['command'] == 'easySerch')) {
            echo <<<EOM
            			<form method="POST" action="{$this_script}">
            			<input type="hidden" name="mode" value="prof">
            			<input type="hidden" name="command" value="easySerch">
            			<input type="hidden" name="name" value="{$in['name']}">
            			<input type="hidden" name="pass" value="{$in['pass']}">
            			<input type="hidden" name="town_no" value="{$in['town_no']}">
            			<input type="hidden" name="sex" value="{$in['sex']}">
            			<input type="hidden" name="age" value="{$in['age']}">
            			<input type="hidden" name="address" value="{$in['address']}">
            			<input type="hidden" name="p1" value="{$in['p1']}">
            			<input type="hidden" name="p2" value="{$in['p2']}">
            			<input type="hidden" name="p3" value="{$in['p3']}">
            			<input type="hidden" name="p4" value="{$in['p4']}">
            			<input type="hidden" name="p5" value="{$in['p5']}">
            			<input type="hidden" name="page" value="{$back}">
            			<input type="submit" value="BACK">
            			</form>
            EOM;
        } else {
            // 通常の場合
            echo <<<EOM
            			<td><form method="POST" action="{$this_script}">
            			<input type="hidden" name="mode" value="prof">
            			<input type="hidden" name="name" value="{$in['name']}">
            			<input type="hidden" name="pass" value="{$in['pass']}">
            			<input type="hidden" name="town_no" value="{$in['town_no']}">
            			<input type="hidden" name="page" value="{$back}">
            			<input type="submit" value="BACK"></form></td>
            EOM;
        }
    }
    if ((n($next) < n($i3))) {
        if (($in['command'] == 'easySerch')) {
            echo <<<EOM
            			<form method="POST" action="{$this_script}">
            			<input type="hidden" name="mode" value="prof">
            			<input type="hidden" name="command" value="easySerch">
            			<input type="hidden" name="name" value="{$in['name']}">
            			<input type="hidden" name="pass" value="{$in['pass']}">
            			<input type="hidden" name="town_no" value="{$in['town_no']}">
            			<input type="hidden" name="sex" value="{$in['sex']}">
            			<input type="hidden" name="age" value="{$in['age']}">
            			<input type="hidden" name="address" value="{$in['address']}">
            			<input type="hidden" name="p1" value="{$in['p1']}">
            			<input type="hidden" name="p2" value="{$in['p2']}">
            			<input type="hidden" name="p3" value="{$in['p3']}">
            			<input type="hidden" name="p4" value="{$in['p4']}">
            			<input type="hidden" name="p5" value="{$in['p5']}">
            			<input type="hidden" name="page" value="{$next}">
            			<input type="submit" value="NEXT">
            </form>
            EOM;
        } else {
            echo <<<EOM
            			<td><form method="POST" action="{$this_script}">
            			<input type="hidden" name="mode" value="prof">
            			<input type="hidden" name="name" value="{$in['name']}">
            			<input type="hidden" name="pass" value="{$in['pass']}">
            			<input type="hidden" name="town_no" value="{$in['town_no']}">
            			<input type="hidden" name="page" value="{$next}">
            			<input type="submit" value="NEXT"></form></td>
            EOM;
        }
    }
    echo '</tr></table>';
    hooter('login_view', '街へ戻る');
    exit;
}

function prof_form(...$_args)
{
    global $_, $address_array, $age_array, $alldata, $atta_flag, $i, $i2, $in, $kijutu_prof, $my_prof_hairetu, $name,
        $pro_addr, $pro_age, $pro_name, $pro_p1, $pro_p10, $pro_p11, $pro_p12, $pro_p13, $pro_p14, $pro_p15,
        $pro_p16, $pro_p17, $pro_p18, $pro_p19, $pro_p2, $pro_p20, $pro_p3, $pro_p4, $pro_p5, $pro_p6, $pro_p7,
        $pro_p8, $pro_p9, $pro_sex, $prof_array1, $prof_array2, $prof_array3, $prof_array4, $prof_array5,
        $prof_name1, $prof_name2, $prof_name3, $prof_name4, $prof_name5, $sex_array, $this_script;
    $atta_flag = 0;
    $__s1 = $_ ?? null;
    foreach ($alldata ?? [] as $_) {
        [$pro_name, $pro_sex, $pro_age, $pro_addr, $pro_p1, $pro_p2, $pro_p3, $pro_p4, $pro_p5, $pro_p6, $pro_p7, $pro_p8, $pro_p9, $pro_p10, $pro_p11, $pro_p12, $pro_p13, $pro_p14, $pro_p15, $pro_p16, $pro_p17, $pro_p18, $pro_p19, $pro_p20] = pl_pad(pl_split('/<>/u', $_), 24);
        $my_prof_hairetu = pl_split('/<>/u', $_);
        if (((string)$pro_name === (string)(string)($name))) {
            $atta_flag = 1;
            break;
        }
    }
    $_ = $__s1;
    if ((n($atta_flag) == 0)) {
        $pro_name = '';
        $pro_sex = '';
        $pro_age = '';
        $pro_addr = '';
        $pro_p1 = '';
        $pro_p2 = '';
        $pro_p3 = '';
        $pro_p4 = '';
        $pro_p5 = '';
        $pro_p6 = '';
        $pro_p7 = '';
        $pro_p8 = '';
        $pro_p9 = '';
        $pro_p10 = '';
        $pro_p11 = '';
        $pro_p12 = '';
        $pro_p13 = '';
        $pro_p14 = '';
        $pro_p15 = '';
        $pro_p16 = '';
        $pro_p17 = '';
        $pro_p18 = '';
        $pro_p19 = '';
        $pro_p20 = '';
        $my_prof_hairetu = [];
    }
    t_header('prof_style');
    echo <<<EOM
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="prof">
    	<input type="hidden" name="command" value="touroku">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    <table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr>
    	<td bgcolor="#ffffff">公開したい項目のみ選択または記述し、その他は空白のままでOKです。修正・更新はいつでもできます。
    	</td>
    	<td  bgcolor="#ff6633" align="center" width="35%"><div style="font-size:13px; color:#ffffff">プロフィール登録</div>
    	</td></tr></table><br>
    
    	<table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr><td>
    EOM;
    echo '性別<br><select name="pro_sex">';
    for ($i = 0; (n($i) < pl_count($sex_array)); $i++) {
        $option = '<option>';
        if ((((string)$pro_sex === (string)$sex_array[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $sex_array[n($i)];
    }
    echo '</select></td><td>';
    echo ' 年齢<br><select name="pro_age">';
    for ($i = 0; (n($i) < pl_count($age_array)); $i++) {
        $option = '<option>';
        if ((((string)$pro_age === (string)$age_array[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $age_array[n($i)];
    }
    echo '</select></td><td>';
    echo ' 住所<br><select name="pro_addr">';
    for ($i = 0; (n($i) < pl_count($address_array)); $i++) {
        $option = '<option>';
        if ((((string)$pro_addr === (string)$address_array[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $address_array[n($i)];
    }
    echo '</select></td><td>';
    echo "{$prof_name1}<br><select name=\"pro_p1\">";
    for ($i = 0; (n($i) < pl_count($prof_array1)); $i++) {
        $option = '<option>';
        if ((((string)$pro_p1 === (string)$prof_array1[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $prof_array1[n($i)];
    }
    echo '</select></td></tr><tr><td>';
    echo "{$prof_name2}<br><select name=\"pro_p2\">";
    for ($i = 0; (n($i) < pl_count($prof_array2)); $i++) {
        $option = ' <option>';
        if ((((string)$pro_p2 === (string)$prof_array2[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $prof_array2[n($i)];
    }
    echo '</select></td><td>';
    echo "{$prof_name3}<br><select name=\"pro_p3\">";
    for ($i = 0; (n($i) < pl_count($prof_array3)); $i++) {
        $option = ' <option>';
        if ((((string)$pro_p3 === (string)$prof_array3[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $prof_array3[n($i)];
    }
    echo '</select></td><td>';
    echo "{$prof_name4}<br><select name=\"pro_p4\">";
    for ($i = 0; (n($i) < pl_count($prof_array4)); $i++) {
        $option = ' <option>';
        if ((((string)$pro_p4 === (string)$prof_array4[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $prof_array4[n($i)];
    }
    echo '</select></td><td>';
    echo "{$prof_name5}<br><select name=\"pro_p5\">";
    for ($i = 0; (n($i) < pl_count($prof_array5)); $i++) {
        $option = ' <option>';
        if ((((string)$pro_p5 === (string)$prof_array5[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $prof_array5[n($i)];
    }
    echo '</select></td></tr>';
    $i = 6;
    $i2 = 9;
    $__s2 = $_ ?? null;
    foreach ($kijutu_prof ?? [] as $_) {
        echo "<tr><td align=\"right\">{$_}</td><td colspan=\"3\"><input type=\"text\" name=\"pro_p{$i}\" size=\"80\" value=\"{$my_prof_hairetu[n($i2)]}\"></td></tr>\n";
        $i++;
        $i2++;
    }
    $_ = $__s2;
    echo <<<EOM
    	<tr><td colspan="4" align="center">
    	<input type="submit" value=" O K "><br>
    	</td></tr></table>
    	</form>
    
    EOM;
    hooter('prof', '戻る', 'basic.php');
}

function by_syu_keys(...$_args)
{
    global $a, $b, $keys;
    return pl_cmp($keys[n($a)], $keys[n($b)]);
}

function item(...$_args)
{
    global $_, $alldata, $baikyaku_hyouzi, $bh_tukihi, $calory_hyouzi, $data, $energy, $img_dir, $in, $k_id,
        $keikanissuu, $key, $keys, $maeno_syo_syubetu, $monokiroku_file, $myitem_hairetu, $name, $new_myitem_hairetu,
        $nokorinissuu, $nou_energy, $now_time, $syo_bijutu, $syo_cal, $syo_comment, $syo_eigo, $syo_etti,
        $syo_hinmoku, $syo_kankaku, $syo_kenkou, $syo_kokugo, $syo_kounyuubi, $syo_kyakuryoku, $syo_looks, $syo_love,
        $syo_ongaku, $syo_power, $syo_rika, $syo_sintai_syouhi, $syo_speed, $syo_suugaku, $syo_syakai, $syo_syubetu,
        $syo_taikyuu, $syo_taikyuu_tani, $syo_tairyoku, $syo_temp, $syo_unique, $syo_wanryoku, $syo_zunou_syouhi,
        $taikyuu_hyouzi_seikei, $tanka, $this_script;
    global $syo_siyou_date, $last_syokuzi, $syokuzi_kankaku;
    $monokiroku_file = "./member/{$k_id}/mono.cgi";
    if (!(pl_open('SP', (string)($monokiroku_file)))) { error("Open Error : {$monokiroku_file}"); }
    $myitem_hairetu = pl_readlines('SP');
    pl_close('SP');
    // 種別でソート
    $__s1 = $_ ?? null;
    foreach ($myitem_hairetu ?? [] as $_) {
        $data = $_;
        $key = pl_idx(pl_split('/<>/u', $data), 0);
        $alldata[] = $data;
        $keys[] = $key;
    }
    $_ = $__s1;
    $alldata = pl_slice($alldata, pl_sort('by_syu_keys', pl_range(0, (pl_count($alldata) - 1))));
    t_header('item_style');
    echo <<<EOM
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="item_do">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="k_id" value="{$in['k_id']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<table width="100%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr>
    	<td bgcolor="#ffffff">使用したいアイテムにチェックをいれ、「使用する」か「売却する」を選んでOKボタンを押してください。<br>
    	※備考に（※アイコン）とある商品は、持っていることで新しいアイコンが現れます。「使用」してしまうと無くなってしまい効果も消えますのでご注意ください。<br>
    	※自分で購入した「ギフト」はメール送信画面のセレクトメニューに現れます。このリストには表示されません。<br>
    	現在の{$name}さんの身体エネルギー：{$energy}　頭脳エネルギー：{$nou_energy}</td>
    	<td  bgcolor="#ffcc00" align="center"><img src="{$img_dir}/item_tytle.gif"></td>
    	</tr></table><br>
    
    	<table width="100%" border="0" cellspacing="1" cellpadding="5" align="center" class="yosumi">
    	<tr><td colspan="26"><span style="color:#336699">凡例：(国)＝国語up値、(数)＝数学up値、(理)＝理科up値、(社)＝社会up値、(英)＝英語up値、(音)＝音楽up値、(美)＝美術up値、（ル）=ルックスup値、（体）=体力up値、（健）=健康up値、（ス）=スピードup値、（パ）=パワーup値、（腕）=腕力up値、（脚）=脚力up値、（L）=LOVEup値、（面）=面白さup値、（H）=エッチup値<br>
    	※耐久は、○回なら使用できる回数、○日なら使用できる日数です。<br>
    	※カロリーは摂取できる数値です。<br>
    	</span></td></tr>
    		<tr bgcolor="#ccff33"><td align="center" nowrap>商品</td><td>国</td><td>数</td><td>理</td><td>社</td><td>英</td><td>音</td><td>美</td><td>ル</td><td>体</td><td>健</td><td>ス</td><td>パ</td><td>腕</td><td>脚</td><td>L</td><td>面</td><td>Ｈ</td><td align="center" nowrap>カロリー</td><td align="center">使用<br>間隔</td><td align="center">身体<br>消費</td><td align="center">頭脳<br>消費</td><td align="center">売却<br>値段</td><td align="center" nowrap>　備　考　</td><td align="center" nowrap>購入日</td><td align="center" nowrap>残り</td></tr>
    EOM;
    $now_time = pl_time();
    // ver.1.3
    $new_myitem_hairetu = [];
    // ver.1.3
    $__s2 = $_ ?? null;
    foreach ($alldata ?? [] as $_) {
        syouhin_sprit($_);
        // ver.1.30ここから
        if (($syo_taikyuu_tani == '日')) {
            $keikanissuu = pl_int((((n($now_time) - n($syo_kounyuubi))) / (((60 * 60) * 24))));
            $nokorinissuu = (n($syo_taikyuu) - n($keikanissuu));
            if ((n($nokorinissuu) <= 0)) {
                continue;
            }
        }
        // ver.1.30ここまで
        if ((n($syo_taikyuu) <= 0)) {
            continue;
        }
        if (($syo_syubetu != 'ギフト')) {
            // ver.1.3
            if ((n($syo_cal) > 0)) {
                $calory_hyouzi = "{$syo_cal} kcal";
            } else {
                $calory_hyouzi = '<div align="center">ー</div>';
            }
            // 使用間隔（アイテムごとの間隔と、食べ物は食事の間隔）が空くまでの残り秒数
            $item_now = pl_time();
            $item_wait = (n($syo_siyou_date) + (n($syo_kankaku) * 60)) - $item_now;
            if (($syo_syubetu == '食料品') || ($syo_syubetu == 'ファーストフード')) {
                $item_wait = max($item_wait, (n($last_syokuzi) + (n($syokuzi_kankaku) * 60)) - $item_now);
            }
            $item_wait = max(0, (int)$item_wait);
            $item_wait_label = ($item_wait > 0) ? '<span class="tw-wait tw-item-wait">あと' . (int)ceil($item_wait / 60) . '分</span>' : '';
            if ((n($syo_kankaku) <= 0)) {
                $syo_kankaku = '<div align="center">ー</div>';
            }
            if (((string)$maeno_syo_syubetu !== (string)(string)($syo_syubetu))) {
                echo "<tr bgcolor=\"#ffff66\"><td colspan=\"26\">▼{$syo_syubetu}</td></tr>";
            }
            // ver.1.3ここから
            if (($syo_taikyuu_tani == '日')) {
                $taikyuu_hyouzi_seikei = "{$nokorinissuu}日";
                $baikyaku_hyouzi = ((n($tanka) * n($nokorinissuu)));
            } else {
                $taikyuu_hyouzi_seikei = (string)($syo_taikyuu) . (string)($syo_taikyuu_tani);
                $baikyaku_hyouzi = ((n($tanka) * n($syo_taikyuu)));
            }
            // ver.1.3ここまで
            byou_hiduke($syo_kounyuubi);
            echo <<<EOM
            		<tr bgcolor="#ccff99" align="center"><td nowrap align="left"><label><input type="radio" value="{$syo_hinmoku}" name="syo_hinmoku" data-wait="{$item_wait}">{$syo_hinmoku}</label>{$item_wait_label}</td><td>{$syo_kokugo}</td><td>{$syo_suugaku}</td><td>{$syo_rika}</td><td>{$syo_syakai}</td><td>{$syo_eigo}</td><td>{$syo_ongaku}</td><td>{$syo_bijutu}</td><td>{$syo_looks}</td><td>{$syo_tairyoku}</td><td>{$syo_kenkou}</td><td>{$syo_speed}</td><td>{$syo_power}</td><td>{$syo_wanryoku}</td><td>{$syo_kyakuryoku}</td><td>{$syo_love}</td><td>{$syo_unique}</td><td>{$syo_etti}</td><td align="right">{$calory_hyouzi}</td><td nowrap>{$syo_kankaku}分</td><td>{$syo_sintai_syouhi}</td><td>{$syo_zunou_syouhi}</td><td align="right" nowrap>{$baikyaku_hyouzi}円</td><td align="left">{$syo_comment}</td><td nowrap>{$bh_tukihi}</td><td nowrap>{$taikyuu_hyouzi_seikei}</td></tr>
            EOM;
            $maeno_syo_syubetu = (string)($syo_syubetu);
        }
        // ギフトで無い場合閉じ		ver.1.3
        syouhin_temp(...$_args);
        // ver.1.3
        $new_myitem_hairetu[] = $syo_temp;
        // ver.1.3
    }
    $_ = $__s2;
    // foreach閉じ
    if (!(!empty($alldata))) {
        echo '<tr><td colspan="26">現在所有しているアイテムはありません。</td></tr>';
    }
    echo <<<EOM
    	<tr><td colspan="26">
    	<div align="center">
    	<select name="command" id="item_command">
    	<option value="siyou">使用する</option>
    	<option value="baikyaku">売却する</option>
    	</select>
    	<input type="submit" id="item_ok" value=" O K "><div id="item_wait_msg" class="tw-wait"></div></div></td></tr>
    	</table></form>
    <script>
    // 使用間隔が空いていないアイテムを「使用する」ときは OK を押せないようにする（売却はいつでも可）
    (function () {
      var ok = document.getElementById('item_ok'), cmd = document.getElementById('item_command'), msg = document.getElementById('item_wait_msg');
      if (!ok || !cmd) return;
      var radios = document.querySelectorAll('input[name=syo_hinmoku][data-wait]');
      function update() {
        var sel = document.querySelector('input[name=syo_hinmoku][data-wait]:checked');
        var wait = sel ? parseInt(sel.getAttribute('data-wait'), 10) : 0;
        var blocked = cmd.value === 'siyou' && wait > 0;
        ok.disabled = blocked;
        msg.textContent = blocked ? 'このアイテムはあと' + Math.ceil(wait / 60) + '分使用できません' : '';
      }
      setInterval(function () {
        radios.forEach(function (r) {
          var w = parseInt(r.getAttribute('data-wait'), 10);
          if (w > 0) {
            w--; r.setAttribute('data-wait', w);
            var lab = r.closest('td').querySelector('.tw-item-wait');
            if (lab) { if (w > 0) lab.textContent = 'あと' + Math.ceil(w / 60) + '分'; else lab.remove(); }
          }
        });
        update();
      }, 1000);
      radios.forEach(function (r) { r.addEventListener('change', update); });
      cmd.addEventListener('change', update);
      update();
    })();
    </script>
    EOM;
    // 自分の所有物ファイルを更新
    lock(...$_args);
    if (!(pl_open('OUT', ">{$monokiroku_file}"))) { error("Write Error : {$monokiroku_file}"); }
    pl_print('OUT', pl_join('', $new_myitem_hairetu));
    pl_close('OUT');
    unlock(...$_args);
    hooter('login_view', '戻る');
    exit;
}

function item_do(...$_args)
{
    global $_, $baikyaku_hyouzi, $bijutu, $byouki_sisuu, $byoumei, $eigo, $energy, $etti, $in, $k_id, $k_temp,
        $keikanissuu, $kenkou, $kokugo, $koukahadou, $kyakuryoku, $last_syokuzi, $looks, $love, $message_win, $money,
        $monokiroku_file, $my_log_file, $myitem_hairetu, $new_myitem_hairetu, $nokorinissuu, $nou_energy, $now_time,
        $ongaku, $power, $print_messe, $rika, $script, $sintyou, $siyouzumi_flag, $sonoiryoku, $speed, $suugaku,
        $syakai, $syo_bijutu, $syo_cal, $syo_eigo, $syo_etti, $syo_hinmoku, $syo_kankaku, $syo_kenkou, $syo_kokugo,
        $syo_kouka, $syo_kounyuubi, $syo_kyakuryoku, $syo_looks, $syo_love, $syo_ongaku, $syo_power, $syo_rika,
        $syo_sintai_syouhi, $syo_siyou_date, $syo_speed, $syo_suugaku, $syo_syakai, $syo_syubetu, $syo_taikyuu,
        $syo_taikyuu_tani, $syo_tairyoku, $syo_temp, $syo_unique, $syo_wanryoku, $syo_zunou_syouhi, $syokuzi_kankaku,
        $taijuu, $taijuu_hue, $tairyoku, $tanka, $unique, $wanryoku;
    if (($in['syo_hinmoku'] == '')) {
        error('アイテムが選ばれていません。');
    }
    $monokiroku_file = "./member/{$k_id}/mono.cgi";
    if (!(pl_open('SP', (string)($monokiroku_file)))) { error("Open Error : {$monokiroku_file}"); }
    $myitem_hairetu = pl_readlines('SP');
    pl_close('SP');
    $siyouzumi_flag = 0;
    $__s1 = $_ ?? null;
    foreach ($myitem_hairetu ?? [] as $_) {
        syouhin_sprit($_);
        if ((((string)$in['syo_hinmoku'] === (string)(string)($syo_hinmoku)) && ($syo_syubetu != 'ギフト'))) {
            // 「使用」コマンドだった場合
            if ((($in['command'] == 'siyou') && (n($siyouzumi_flag) == 0))) {
                $siyouzumi_flag = 1;
                $now_time = pl_time();
                if ((($syo_syubetu == '食料品') || ($syo_syubetu == 'ファーストフード'))) {
                    if ((n($now_time) < (n($last_syokuzi) + ((n($syokuzi_kankaku) * 60))))) {
                        error('まだ食事できません。');
                    }
                    $print_messe .= "●{$in['syo_hinmoku']}を食べました。<br>";
                    $last_syokuzi = $now_time;
                } else {
                    $print_messe .= "●{$in['syo_hinmoku']}を使用しました。<br>";
                }
                if (((n($syo_siyou_date) + ((n($syo_kankaku) * 60))) > n($now_time))) {
                    error('間隔が短いためまだ使用できません。');
                }
                if ((n($energy) < n($syo_sintai_syouhi))) {
                    error('身体パワーが足りません。');
                }
                if ((n($nou_energy) < n($syo_zunou_syouhi))) {
                    error('頭脳パワーが足りません。');
                }
                if ($syo_kokugo) {
                    $kokugo = n($kokugo) + n($syo_kokugo);
                    $print_messe .= "・国語が{$syo_kokugo}アップしました。<br>";
                }
                if ($syo_suugaku) {
                    $suugaku = n($suugaku) + n($syo_suugaku);
                    $print_messe .= "・数学が{$syo_suugaku}アップしました。<br>";
                }
                if ($syo_rika) {
                    $rika = n($rika) + n($syo_rika);
                    $print_messe .= "・理科が{$syo_rika}アップしました。<br>";
                }
                if ($syo_syakai) {
                    $syakai = n($syakai) + n($syo_syakai);
                    $print_messe .= "・社会が{$syo_syakai}アップしました。<br>";
                }
                if ($syo_eigo) {
                    $eigo = n($eigo) + n($syo_eigo);
                    $print_messe .= "・英語が{$syo_eigo}アップしました。<br>";
                }
                if ($syo_ongaku) {
                    $ongaku = n($ongaku) + n($syo_ongaku);
                    $print_messe .= "・音楽が{$syo_ongaku}アップしました。<br>";
                }
                if ($syo_bijutu) {
                    $bijutu = n($bijutu) + n($syo_bijutu);
                    $print_messe .= "・美術が{$syo_bijutu}アップしました。<br>";
                }
                if ($syo_looks) {
                    $looks = n($looks) + n($syo_looks);
                    $print_messe .= "・ルックス値が{$syo_looks}アップしました。<br>";
                }
                if ($syo_tairyoku) {
                    $tairyoku = n($tairyoku) + n($syo_tairyoku);
                    $print_messe .= "・体力が{$syo_tairyoku}アップしました。<br>";
                }
                if ($syo_kenkou) {
                    $kenkou = n($kenkou) + n($syo_kenkou);
                    $print_messe .= "・健康値が{$syo_kenkou}アップしました。<br>";
                }
                if ($syo_speed) {
                    $speed = n($speed) + n($syo_speed);
                    $print_messe .= "・スピードが{$syo_speed}アップしました。<br>";
                }
                if ($syo_power) {
                    $power = n($power) + n($syo_power);
                    $print_messe .= "・パワーが{$syo_power}アップしました。<br>";
                }
                if ($syo_wanryoku) {
                    $wanryoku = n($wanryoku) + n($syo_wanryoku);
                    $print_messe .= "・腕力が{$syo_wanryoku}アップしました。<br>";
                }
                if ($syo_kyakuryoku) {
                    $kyakuryoku = n($kyakuryoku) + n($syo_kyakuryoku);
                    $print_messe .= "・脚力が{$syo_kyakuryoku}アップしました。<br>";
                }
                if ($syo_love) {
                    $love = n($love) + n($syo_love);
                    $print_messe .= "・LOVE度が{$syo_love}アップしました。<br>";
                }
                if ($syo_unique) {
                    $unique = n($unique) + n($syo_unique);
                    $print_messe .= "・面白さが{$syo_unique}アップしました。<br>";
                }
                if ($syo_etti) {
                    $etti = n($etti) + n($syo_etti);
                    $print_messe .= "・エッチ度が{$syo_etti}アップしました。<br>";
                }
                // 効果の設定
                if (($syo_kouka != '無')) {
                    [$koukahadou, $sonoiryoku] = pl_pad(pl_split('/,/u', $syo_kouka), 2);
                    if (($koukahadou == '万能')) {
                        $byouki_sisuu = n($byouki_sisuu) + n($sonoiryoku);
                    }
                    if (($koukahadou == '風邪')) {
                        if (pl_match('/風邪/u', $byoumei)) {
                            $byouki_sisuu = n($byouki_sisuu) + n($sonoiryoku);
                        }
                    }
                    if (($koukahadou == '肺炎')) {
                        if (pl_match('/肺炎/u', $byoumei)) {
                            $byouki_sisuu = n($byouki_sisuu) + n($sonoiryoku);
                        }
                    }
                    if (($koukahadou == '結核')) {
                        if (pl_match('/結核/u', $byoumei)) {
                            $byouki_sisuu = n($byouki_sisuu) + n($sonoiryoku);
                        }
                    }
                    if (($koukahadou == 'ウエイトアップ')) {
                        $taijuu = n($taijuu) + n($sonoiryoku);
                        $print_messe .= "・体重が{$sonoiryoku} kg増えました。<br>";
                    }
                    if (($koukahadou == 'ダイエット')) {
                        $taijuu = n($taijuu) - n($sonoiryoku);
                        $print_messe .= "・体重が{$sonoiryoku} kg減りました。<br>";
                    }
                    if (($koukahadou == '身長')) {
                        $sintyou = n($sintyou) + n($sonoiryoku);
                        $print_messe .= "・身長が{$sonoiryoku} cm伸びました。<br>";
                    }
                    if (($koukahadou == '縮み')) {
                        $sintyou = n($sintyou) - n($sonoiryoku);
                        $print_messe .= "・身長が{$sonoiryoku} cm縮みました。<br>";
                    }
                }
                $syo_siyou_date = $now_time;
                if ($syo_sintai_syouhi) {
                    $energy = n($energy) - n($syo_sintai_syouhi);
                    $print_messe .= "・{$syo_sintai_syouhi}の身体エネルギーを使いました。<br>";
                }
                if ($syo_zunou_syouhi) {
                    $nou_energy = n($nou_energy) - n($syo_zunou_syouhi);
                    $print_messe .= "・{$syo_zunou_syouhi}の頭脳エネルギーを使いました。<br>";
                }
                if ($syo_cal) {
                    $taijuu_hue = (n($syo_cal) / 1000);
                    $taijuu = n($taijuu) + n($taijuu_hue);
                    $print_messe .= "・{$taijuu_hue} kg体重が増えました。<br>";
                }
                if (($syo_taikyuu_tani == '回')) {
                    $syo_taikyuu--;
                }
                // 売却だった場合
            } elseif ((($in['command'] == 'baikyaku') && (n($siyouzumi_flag) == 0))) {
                // ver.1.3ここから
                $now_time = pl_time();
                if (($syo_taikyuu_tani == '日')) {
                    $keikanissuu = pl_int((((n($now_time) - n($syo_kounyuubi))) / (((60 * 60) * 24))));
                    $nokorinissuu = (n($syo_taikyuu) - n($keikanissuu));
                    $baikyaku_hyouzi = ((n($tanka) * n($nokorinissuu)));
                } else {
                    $baikyaku_hyouzi = ((n($tanka) * n($syo_taikyuu)));
                }
                $siyouzumi_flag = 1;
                // ver.1.3ここまで
                $money = n($money) + n($baikyaku_hyouzi);
                $print_messe .= "●{$in['syo_hinmoku']}を売却し、{$baikyaku_hyouzi}円を得ました。";
                continue;
            }
            // if（「売却」コマンドだった場合）の閉じ
        }
        // if（アイテム名一致）の閉じ
        if ((n($syo_taikyuu) <= 0)) {
            continue;
        }
        syouhin_temp(...$_args);
        $new_myitem_hairetu[] = $syo_temp;
    }
    $_ = $__s1;
    // foreachの閉じ
    // ログ更新
    temp_routin(...$_args);
    log_kousin($my_log_file, $k_temp);
    // 自分の所有物ファイルを更新
    lock(...$_args);
    if (!(pl_open('OUT', ">{$monokiroku_file}"))) { error("Write Error : {$monokiroku_file}"); }
    pl_print('OUT', pl_join('', $new_myitem_hairetu));
    pl_close('OUT');
    unlock(...$_args);
    t_header('', 'sonomati');
    echo <<<EOM
    	<div align="center"><br><table  border="0"  cellspacing="5" cellpadding="0" width="300" style="{$message_win}"><tr><td>
    <span class="job_messe">
    {$print_messe}
    </span>
    </td></tr></table>
    <br>
    
    <div align="center"><!--TW_BACK--></div>
    	<form method="POST" action="{$script}">
    	<input type="hidden" name="mode" value="login_view">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value="戻る">
    	</form></div>
    
    	</body></html>
    EOM;
    exit;
}

function job_change(...$_args)
{
    global $BMI, $BMI_hani, $_, $bijutu, $eigo, $etti, $i, $img_dir, $in, $job_BMI_max, $job_BMI_min, $job_bijutu,
        $job_bonus, $job_eigo, $job_energy, $job_etti, $job_hairetu, $job_hosi, $job_kenkou, $job_kokugo,
        $job_kyakuryoku, $job_kyuuyo, $job_looks, $job_love, $job_name, $job_no, $job_nou_energy, $job_ongaku,
        $job_option, $job_power, $job_rank, $job_rika, $job_sex, $job_siharai, $job_sintyou, $job_speed,
        $job_suugaku, $job_syakai, $job_syurui, $job_tairyoku, $job_unique, $job_wanryoku, $jobsyu, $kenkou, $kokugo,
        $kyakuryoku, $looks, $love, $name, $ongaku, $power, $rika, $sex, $sihrai_seikei, $sintyou, $speed, $suugaku,
        $syakai, $taijuu, $tairyoku, $this_script, $top_koumoku, $unique, $wanryoku;
    if (!(pl_open('SP', './dat_dir/job.dat'))) { error('Open Error : ./dat_dir/job.dat'); }
    $top_koumoku = pl_readline('SP');
    $job_hairetu = pl_readlines('SP');
    pl_close('SP');
    // BMIをチェック
    $taijuu = pl_sprintf('%.1f', $taijuu);
    check_BMI($sintyou, $taijuu);
    // 条件に満たない職業を可能リストから排除
    $__s1 = $_ ?? null;
    foreach ($job_hairetu ?? [] as $_) {
        job_sprit($_);
        if ((n($kokugo) < n($job_kokugo))) {
            continue;
        }
        if ((n($suugaku) < n($job_suugaku))) {
            continue;
        }
        if ((n($rika) < n($job_rika))) {
            continue;
        }
        if ((n($syakai) < n($job_syakai))) {
            continue;
        }
        if ((n($eigo) < n($job_eigo))) {
            continue;
        }
        if ((n($ongaku) < n($job_ongaku))) {
            continue;
        }
        if ((n($bijutu) < n($job_bijutu))) {
            continue;
        }
        if ((n($BMI) < n($job_BMI_min))) {
            continue;
        }
        if ($job_BMI_max) {
            if ((n($BMI) > n($job_BMI_max))) {
                continue;
            }
        }
        if ((n($looks) < n($job_looks))) {
            continue;
        }
        if ((n($tairyoku) < n($job_tairyoku))) {
            continue;
        }
        if ((n($kenkou) < n($job_kenkou))) {
            continue;
        }
        if ((n($speed) < n($job_speed))) {
            continue;
        }
        if ((n($power) < n($job_power))) {
            continue;
        }
        if ((n($wanryoku) < n($job_wanryoku))) {
            continue;
        }
        if ((n($kyakuryoku) < n($job_kyakuryoku))) {
            continue;
        }
        if ((n($love) < n($job_love))) {
            continue;
        }
        if ((n($unique) < n($job_unique))) {
            continue;
        }
        if ((n($etti) < n($job_etti))) {
            continue;
        }
        if ($job_sex) {
            if (((string)$sex !== (string)(string)($job_sex))) {
                continue;
            }
        }
        if ((n($sintyou) < n($job_sintyou))) {
            continue;
        }
        if ($job_syurui) {
            if (((string)$job_syurui !== (string)$jobsyu)) {
                continue;
            }
        }
        if (((string)$job_rank === (string)'2')) {
            $job_hosi = '*';
        } elseif (((string)$job_rank === (string)'3')) {
            $job_hosi = '**';
        } elseif (((string)$job_rank === (string)'4')) {
            $job_hosi = '***';
        } elseif (((string)$job_rank === (string)'5')) {
            $job_hosi = '****';
        } else {
            $job_hosi = '';
        }
        $job_option .= "<option value=\"{$job_no}\">{$job_name}{$job_hosi}</option>";
    }
    $_ = $__s1;
    if (($job_option == '')) {
        $job_option = '<option value="">現在就ける職業はありません</option>';
    }
    t_header('job_style');
    echo <<<EOM
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="job_change_go">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="k_id" value="{$in['k_id']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<table width="95%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi"><tr>
    	<td bgcolor="#ffffff">必要なパラメータ、条件を満たしていればその職業に就くことができます。自分のなりたい職業めざして、勉強、トレーニングなどに励みましょう。尚、転職をすると経験値、出勤回数は0に戻ります。<br>
    	※職業のレベルが15を超えるとその職業をマスターしたことになり、よりレベルアップした職業が出現することがあります（職業名のあとの「*」がレベルを表します）。
    	</td>
    	<td bgcolor="#333333" align="center" width="300"><img src="{$img_dir}/job_tytle.gif"></td></tr>
    	<tr><td colspan="2">
    	<span class="tyuu">{$name}さんが就職可能な職業</span><img src="{$img_dir}/space.gif" width="10" height="1">
    	<select name="job_sentaku">
            {$job_option}
         </select><img src="{$img_dir}/space.gif" width="10" height="1">
    	 <input type="submit" value="この職業に就く">
    	 </td></tr></table></form>
    EOM;
    // 性別の表示整形
    if (($sex == 'm')) {
        $sex = '男';
    } else {
        $sex = '女';
    }
    echo <<<EOM
    	<table width="95%" border="0" cellspacing="1" cellpadding="4" align="center" class="yosumi">
    	<tr><td colspan="25"><span style="color:#336699">凡例：(国)＝国語、(数)＝数学、(理)＝理科、(社)＝社会、(英)＝英語、(音)＝音楽、(美)＝美術、(ル)＝ルックス、(体)＝体力、(健)＝健康、(ス)＝スピード、(パ)＝パワー、(腕)＝腕力、(脚)＝脚力、(H)＝エッチ</span></td></tr>
    	<tr bgcolor="#ff9933" align="center"><td nowrap rowspan="2">職業</td><td colspan="17">必要なパラメータ</td><td align="center" colspan="3">条　件</td><td rowspan="2" align="center">給　料<br>（1回出勤）</td><td rowspan="2" align="center">ボーナス</td><td rowspan="2">支払い</td><td rowspan="2" nowrap>身体<br>消費</td><td rowspan="2" nowrap>頭脳<br>消費</td></tr>
    	<tr bgcolor="#ffcc33" align="center"><td>国</td><td>数</td><td>理</td><td>社</td><td>英</td><td>音</td><td>美</td><td>ル</td><td>体</td><td>健</td><td>ス</td><td>パ</td><td>腕</td><td>脚</td><td>L</td><td>面</td><td>Ｈ</td><td nowrap>体格指数</td><td nowrap>性別</td><td nowrap>身長</td></tr>
    
    	<tr bgcolor="#ffff33" align="center"><td>現在の{$name}さんのパラメータ</td><td>{$kokugo}</td><td>{$suugaku}</td><td>{$rika}</td><td>{$syakai}</td><td>{$eigo}</td><td>{$ongaku}</td><td>{$bijutu}</td><td>{$looks}</td><td>{$tairyoku}</td><td>{$kenkou}</td><td>{$speed}</td><td>{$power}</td><td>{$wanryoku}</td><td>{$kyakuryoku}</td><td>{$love}</td><td>{$unique}</td><td>{$etti}</td><td nowrap>{$BMI}</td><td>{$sex}</td><td>{$sintyou}</td><td align="center" nowrap>-</td><td nowrap>-</td><td>-</td><td>-</td><td>-</td></tr>
    EOM;
    $i = 1;
    $__s2 = $_ ?? null;
    foreach ($job_hairetu ?? [] as $_) {
        job_sprit($_);
        if ($job_syurui) {
            if (((string)$job_syurui !== (string)$jobsyu)) {
                continue;
            }
        }
        if ((($job_BMI_min == '') && ($job_BMI_max == ''))) {
            $BMI_hani = '';
        } elseif (($job_BMI_min == '')) {
            $BMI_hani = "{$job_BMI_max}以下";
        } elseif (($job_BMI_max == '')) {
            $BMI_hani = "{$job_BMI_min}以上";
        } else {
            $BMI_hani = "{$job_BMI_min}～{$job_BMI_max}";
        }
        if (((string)$job_siharai === (string)'1')) {
            $sihrai_seikei = '日払い';
        } else {
            $sihrai_seikei = "{$job_siharai}回出勤ごと";
        }
        if ($job_sintyou) {
            $job_sintyou = "{$job_sintyou}以上";
        }
        if (($job_sex == 'm')) {
            $job_sex = '男';
        } elseif (($job_sex == 'f')) {
            $job_sex = '女';
        }
        if (((string)$job_rank === (string)'2')) {
            $job_hosi = '*';
        } elseif (((string)$job_rank === (string)'3')) {
            $job_hosi = '**';
        } elseif (((string)$job_rank === (string)'4')) {
            $job_hosi = '***';
        } elseif (((string)$job_rank === (string)'5')) {
            $job_hosi = '****';
        } else {
            $job_hosi = '';
        }
        echo <<<EOM
        	<tr bgcolor="#ffcc66" align="center"><td nowrap>{$job_name}{$job_hosi}</td><td>{$job_kokugo}</td><td>{$job_suugaku}</td><td>{$job_rika}</td><td>{$job_syakai}</td><td>{$job_eigo}</td><td>{$job_ongaku}</td><td>{$job_bijutu}</td><td>{$job_looks}</td><td>{$job_tairyoku}</td><td>{$job_kenkou}</td><td>{$job_speed}</td><td>{$job_power}</td><td>{$job_wanryoku}</td><td>{$job_kyakuryoku}</td><td>{$job_love}</td><td>{$job_unique}</td><td>{$job_etti}</td><td nowrap>{$BMI_hani}</td><td>{$job_sex}</td><td nowrap>{$job_sintyou}</td><td align="right" nowrap>{$job_kyuuyo}円</td><td nowrap>×{$job_bonus}</td><td nowrap>{$sihrai_seikei}</td><td>{$job_energy}</td><td>{$job_nou_energy}</td></tr>
        EOM;
        // ver.1.30
        if (((n($i) % 10) == 0)) {
            echo <<<EOM
            	<tr bgcolor="#ff9933" align="center"><td nowrap rowspan="2">職業</td><td colspan="17">必要なパラメータ</td><td align="center" colspan="3">条　件</td><td rowspan="2" align="center">給　料<br>（1回出勤）</td><td rowspan="2" align="center">ボーナス</td><td rowspan="2">支払い</td><td rowspan="2" nowrap>身体<br>消費</td><td rowspan="2" nowrap>頭脳<br>消費</td></tr>
            	<tr bgcolor="#ffcc33" align="center"><td>国</td><td>数</td><td>理</td><td>社</td><td>英</td><td>音</td><td>美</td><td>ル</td><td>体</td><td>健</td><td>ス</td><td>パ</td><td>腕</td><td>脚</td><td>L</td><td>面</td><td>Ｈ</td><td nowrap>体格指数</td><td nowrap>性別</td><td nowrap>身長</td></tr>
            	<tr bgcolor="#ffff33" align="center"><td>現在の{$name}さんのパラメータ</td><td>{$kokugo}</td><td>{$suugaku}</td><td>{$rika}</td><td>{$syakai}</td><td>{$eigo}</td><td>{$ongaku}</td><td>{$bijutu}</td><td>{$looks}</td><td>{$tairyoku}</td><td>{$kenkou}</td><td>{$speed}</td><td>{$power}</td><td>{$wanryoku}</td><td>{$kyakuryoku}</td><td>{$love}</td><td>{$unique}</td><td>{$etti}</td><td nowrap>{$BMI}</td><td>{$sex}</td><td>{$sintyou}</td><td align="center" nowrap>-</td><td nowrap>-</td><td>-</td><td>-</td><td>-</td></tr>
            EOM;
        }
        $i++;
    }
    $_ = $__s2;
    echo <<<EOM
    	</table>
    EOM;
    hooter('login_view', '戻る');
    exit;
}

function job_change_go(...$_args)
{
    global $_, $in, $job, $job_hairetu, $job_kaisuu, $job_keiken, $job_name, $job_no, $k_temp, $my_log_file,
        $top_koumoku;
    if (!(pl_open('SP', './dat_dir/job.dat'))) { error('Open Error : ./dat_dir/job.dat'); }
    $top_koumoku = pl_readline('SP');
    $job_hairetu = pl_readlines('SP');
    pl_close('SP');
    $__s1 = $_ ?? null;
    foreach ($job_hairetu ?? [] as $_) {
        job_sprit($_);
        if (((string)$in['job_sentaku'] === (string)(string)($job_no))) {
            $job = $job_name;
            $job_keiken = 0;
            $job_kaisuu = 0;
            break;
        }
    }
    $_ = $__s1;
    // ログ更新
    temp_routin(...$_args);
    log_kousin($my_log_file, $k_temp);
    message("{$job_name}になりました。", 'login_view');
}

function do_work(...$_args)
{
    global $BMI, $_, $ato_job_level, $bijutu, $bonusgaku, $date_sec, $eigo, $energy, $energy_max, $etti, $house_name,
        $in, $job, $job_BMI_max, $job_BMI_min, $job_bijutu, $job_bonus, $job_eigo, $job_energy, $job_etti,
        $job_hairetu, $job_kaisuu, $job_keiken, $job_kenkou, $job_kokugo, $job_kyakuryoku, $job_kyuuyo, $job_looks,
        $job_love, $job_name, $job_nou_energy, $job_ongaku, $job_power, $job_rika, $job_siharai, $job_speed,
        $job_suugaku, $job_syakai, $job_syoukyuuritu, $job_tairyoku, $job_unique, $job_wanryoku, $jobsyu, $k_temp,
        $kenkou, $kokugo, $kyakuryoku, $kyounosiharai, $last_ene_time, $last_nouene_time, $looks, $love,
        $mae_job_level, $message_win, $money, $my_log_file, $nou_energy, $nou_energy_max, $ongaku, $power,
        $print_messe, $randed, $rika, $script, $sintai_kaihuku, $sintyou, $speed, $suugaku, $syakai, $syoukyuugaku,
        $taijuu, $taijuu_heri, $tairyoku, $top_koumoku, $unique, $wanryoku, $work_seigen_time, $zunou_kaihuku;
    $date_sec = pl_time();
    // ver.1.2
    if (((n($date_sec) - n($house_name)) < (60 * n($work_seigen_time)))) {
        error("仕事できる間隔は{$work_seigen_time}分です。");
    }
    if (!(pl_open('SP', './dat_dir/job.dat'))) { error('Open Error : ./dat_dir/job.dat'); }
    $top_koumoku = pl_readline('SP');
    $job_hairetu = pl_readlines('SP');
    pl_close('SP');
    $__s1 = $_ ?? null;
    foreach ($job_hairetu ?? [] as $_) {
        job_sprit($_);
        if (((string)$job_name === (string)(string)($job))) {
            break;
        }
    }
    $_ = $__s1;
    // 仕事の能力をチェック
    if ((((((((((((((((((n($kokugo) < n($job_kokugo)) || (n($suugaku) < n($job_suugaku))) || (n($rika) < n($job_rika))) || (n($syakai) < n($job_syakai))) || (n($eigo) < n($job_eigo))) || (n($ongaku) < n($job_ongaku))) || (n($bijutu) < n($job_bijutu))) || (n($looks) < n($job_looks))) || (n($tairyoku) < n($job_tairyoku))) || (n($kenkou) < n($job_kenkou))) || (n($speed) < n($job_speed))) || (n($power) < n($job_power))) || (n($wanryoku) < n($job_wanryoku))) || (n($kyakuryoku) < n($job_kyakuryoku))) || (n($love) < n($job_love))) || (n($unique) < n($job_unique))) || (n($etti) < n($job_etti)))) {
        error('この仕事に必要な能力値を下回っているため仕事できません。');
    }
    // BMIをチェック
    $taijuu = pl_sprintf('%.1f', $taijuu);
    check_BMI($sintyou, $taijuu);
    if ((n($energy) < n((string)($job_energy)))) {
        error("身体パワーが足りません！。<br>仕事するには{$job_energy}の身体パワーが必要です。");
    }
    if ((n($nou_energy) < n((string)($job_nou_energy)))) {
        error("頭脳パワーが足りません！<br>仕事するには{$job_nou_energy}の頭脳パワーが必要です。");
    }
    if ($job_BMI_min) {
        if ((n($BMI) < n((string)($job_BMI_min)))) {
            error('体格指数が条件値以下のため仕事できません！');
        }
    }
    if ($job_BMI_max) {
        if ((n($BMI) > n((string)($job_BMI_max)))) {
            error('体格指数が条件値以上のため仕事できません！');
        }
    }
    // 身体パワー計算
    $energy = (n($energy) + pl_int((((n($date_sec) - n($last_ene_time))) / n($sintai_kaihuku))));
    $last_ene_time = $date_sec;
    $energy_max = pl_int(((((((n($tairyoku) / 2)) + ((n($kenkou) / 3))) + ((n($power) / 5))) + ((n($wanryoku) / 8))) + ((n($kyakuryoku) / 8))));
    if ((n($energy) > n($energy_max))) {
        $energy = $energy_max;
    }
    if ((n($energy) < 0)) {
        $energy = 0;
    }
    // 頭脳パワー計算
    $nou_energy = (n($nou_energy) + pl_int((((n($date_sec) - n($last_nouene_time))) / n($zunou_kaihuku))));
    $last_nouene_time = $date_sec;
    $nou_energy_max = pl_int(((((((((n($kokugo) / 4)) + ((n($suugaku) / 4))) + ((n($rika) / 4))) + ((n($syakai) / 4))) + ((n($eigo) / 4))) + ((n($ongaku) / 4))) + ((n($bijutu) / 4))));
    if ((n($nou_energy) > n($nou_energy_max))) {
        $nou_energy = $nou_energy_max;
    }
    if ((n($nou_energy) < 0)) {
        $nou_energy = 0;
    }
    $mae_job_level = pl_int((n($job_keiken) / 100));
    // 経験値をプラス（体調名。旧形式の色付き HTML で送られてきても名前だけを取り出す）
    $cond = trim(strip_tags(html_entity_decode((string)$in['cond'], ENT_QUOTES | ENT_HTML5, 'UTF-8')));
    if (($cond == '最高')) {
        $randed = '15';
    } elseif (($cond == '良好')) {
        $randed = '10';
    } elseif (($cond == '普通')) {
        $randed = '5';
    } elseif (($cond == '不良')) {
        $randed = '1';
    } elseif (($cond == '悪い')) {
        $randed = '-5';
    } elseif (($cond == '風邪ぎみ')) {
        $randed = '-8';
    } elseif (($cond == '風邪')) {
        $randed = '-14';
    } elseif (($cond == '肺炎')) {
        $randed = '-20';
    } else {
        error('その体では仕事できません！');
    }
    $randed = n($randed) + (pl_int(pl_rand(5)) + 1);
    $job_keiken = n($job_keiken) + n($randed);
    if ((n($randed) > 0)) {
        $print_messe .= "・{$randed}の経験値を得ました。<br>";
    } elseif ((n($randed) < 0)) {
        $print_messe .= "・経験値が{$randed}減ってしまいました。<br>";
    } else {
        $print_messe .= '・経験値を得ることができませんでした。<br>';
    }
    // レベルアップ計算（100経験値ごと）、昇給額計算
    $ato_job_level = pl_int((n($job_keiken) / 100));
    $syoukyuugaku = ((((n($job_kyuuyo) * n($ato_job_level)) / 100)) * n($job_syoukyuuritu));
    $job_kyuuyo = n($job_kyuuyo) + n($syoukyuugaku);
    // レベルアップ表示
    if ((n($ato_job_level) > n($mae_job_level))) {
        $print_messe .= '・レベルが上がりました！<br>';
        $print_messe .= "・{$job_kyuuyo}円／1回に昇給しました。<br>";
        $bonusgaku = (n($job_kyuuyo) * n($job_bonus));
        $money = n($money) + n($bonusgaku);
        if ((n($bonusgaku) > 0)) {
            $print_messe .= "・{$bonusgaku}円のボーナスが出ました！<br>";
        }
        if ((n($ato_job_level) >= 15)) {
            if (((string)$jobsyu !== (string)(string)($job))) {
                $jobsyu = (string)($job);
                $print_messe .= "・{$job}の仕事をマスターしました！<br>";
            }
        }
    }
    // 勤務回数をプラス
    $job_kaisuu++;
    // 支払い規定回で割り切れたら給料支給
    if (((n($job_kaisuu) % n($job_siharai)) == 0)) {
        $kyounosiharai = (n($job_kyuuyo) * n($job_siharai));
        $money = n($money) + n($kyounosiharai);
        if ((n($job_siharai) == 1)) {
            $print_messe .= "・{$kyounosiharai}円の給料をもらいました！<br>";
        } else {
            $print_messe .= "・{$kyounosiharai}円（{$job_kyuuyo}円×{$job_siharai}回出勤）の給料が出ました！<br>";
        }
    }
    // 身体パワーと頭脳パワーを引く
    $energy = n($energy) - n($job_energy);
    $nou_energy = n($nou_energy) - n($job_nou_energy);
    // 体重からエネルギー分を引く
    $taijuu_heri = ((n($job_energy) / 100));
    $taijuu = n($taijuu) - n($taijuu_heri);
    $print_messe .= "・{$taijuu_heri} kg体重が減りました。";
    // 最後に仕事した時間を記録（v1.11）
    $house_name = $date_sec;
    // ログ更新
    temp_routin(...$_args);
    log_kousin($my_log_file, $k_temp);
    t_header('', 'sonomati');
    echo <<<EOM
    	<div align="center"><br><table  border="0"  cellspacing="5" cellpadding="0" width="300" style="{$message_win}"><tr><td>
    <span class="job_messe">
    ●仕事に出かけました（{$job_kaisuu}回目）<br>
    {$print_messe}
    </span>
    </td></tr></table>
    <br>
    
    	<form method="POST" action="{$script}">
    	<input type="hidden" name="mode" value="login_view">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value="戻る">
    	</form></div>
    
    	</body></html>
    EOM;
    exit;
}

