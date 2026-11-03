<?php
// このファイルは kekkon.cgi (Perl) から自動変換されたものです。
// 町CGI  Copyright (c) 2003-2004 brassiere  http://brassiere.jp/

require_once __DIR__ . '/lib/bootstrap.php';
$this_script = 'kekkon.php';
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
if (($in['mode'] == 'assenjo')) {
    assenjo();
} elseif (($in['mode'] == 'kokuhaku')) {
    kokuhaku();
} elseif (($in['mode'] == 'renai')) {
    renai();
} elseif (($in['mode'] == 'kodomo_naming')) {
    kodomo_naming();
} elseif (($in['mode'] == 'kosodate')) {
    kosodate();
} else {
    error('「戻る」ボタンで街に戻ってください');
}
exit;
// ############以下サブルーチン
// #登録フォーム
// ##########告白メール
// 恋愛ログの記録
// 恋愛コマンド
// 子供の名前決め
// 子育て
// 子供自立サブルーチン
// 離婚時の配偶者ID削除処理

function bykeys1(...$_args)
{
    global $a, $b, $keys1;
    return (n($keys1[n($b)]) <=> n($keys1[n($a)]));
}

function bykeys2(...$_args)
{
    global $a, $b, $keys2;
    return (n($keys2[n($b)]) <=> n($keys2[n($a)]));
}

function bykeys3(...$_args)
{
    global $a, $b, $keys3;
    return (n($keys3[n($b)]) <=> n($keys3[n($a)]));
}

function bykeys4(...$_args)
{
    global $a, $b, $keys4;
    return (n($keys4[n($b)]) <=> n($keys4[n($a)]));
}

function bykeys5(...$_args)
{
    global $a, $b, $keys5;
    return (n($keys5[n($b)]) <=> n($keys5[n($a)]));
}

function assenjo(...$_args)
{
    global $_, $all_couple, $all_couple_sort, $all_couple_sort0, $all_couple_sort1, $all_couple_sort2,
        $all_couple_sort3, $all_couple_sort4, $all_couple_sort5, $all_kodomo, $all_kodomo_sort, $alldata,
        $as_address_array, $as_age_array, $as_new_pro_alldata, $as_prof_array1, $as_prof_array2, $as_prof_array3,
        $as_prof_array4, $as_prof_array5, $as_prof_array6, $as_prof_name1, $as_prof_name2, $as_prof_name3,
        $as_prof_name4, $as_prof_name5, $as_prof_name6, $as_profile_file, $as_sex_array, $atta_flag, $back, $c_color,
        $cn_joutai, $cn_kodomo, $cn_name1, $cn_name2, $cn_number, $cn_omoide1, $cn_omoide2, $cn_omoide3, $cn_omoide4,
        $cn_omoide5, $cn_total_aijou, $cn_yobi1, $cn_yobi2, $cn_yobi3, $cn_yobi4, $cn_yobi5, $couple_file, $data,
        $douseiai_per, $huuhu, $hyouzi_max_grobal, $i, $i3, $ie_hyouzi, $img_dir, $in, $job, $juni, $k_id,
        $k_sousisan, $keikoku, $key, $key1, $key2, $key3, $key4, $key5, $keys, $keys1, $keys2, $keys3, $keys4,
        $keys5, $ko_meisai_td, $kod_bijutu, $kod_eigo, $kod_etti, $kod_job, $kod_kenkou, $kod_kokugo,
        $kod_kyakuryoku, $kod_looks, $kod_love, $kod_name, $kod_num, $kod_ongaku, $kod_oya1, $kod_oya2, $kod_power,
        $kod_rika, $kod_speed, $kod_suugaku, $kod_syakai, $kod_tairyoku, $kod_unique, $kod_wanryoku, $kod_yobi1,
        $kod_yobi10, $kod_yobi2, $kod_yobi3, $kod_yobi4, $kod_yobi5, $kod_yobi6, $kod_yobi7, $kod_yobi8, $kod_yobi9,
        $kodomo_file, $kodomo_sakujo_flg, $kodomo_sibou_time, $kodomo_sibou_time2, $koibito_seigen, $kono_nenrei,
        $koukou_hairetu, $koumoku_sentaku, $love, $my_prof_hairetu, $name, $need_love, $new_all_couple_sort,
        $new_all_kodomo, $new_alldata, $new_kodomo_temp, $newrank, $next, $now_time, $one_couple, $ori_ie_hairetu,
        $ori_ie_image, $ori_ie_list, $ori_k_id, $page, $pr_koumokumei, $prof_atta_flg, $profile_file, $return_job,
        $sex, $sex_style, $sort_no, $suuti, $syougaku_hairetu, $this_script, $top_koumoku, $tourokusya_hyouki,
        $two_couple, $tyuugaku_hairetu, $unit, $wakare_limit_haiguu, $wakare_limit_koibito, $wakare_time,
        $wakareflag, $yobikata, $youzi_hairetu, $zibun_prof_com;
    // 性別のselect
    $as_sex_array = ['男', '女'];
    // 選択式プロフィール項目
    $as_prof_name1 = 'ただいま';
    $as_prof_array1 = ['', '結婚を前提に恋人募集中', '結婚しているが恋人募集中', '恋人募集中', '結婚しています', '恋人が規定数に達しています', '今は恋人を募集していません'];
    if (!(pl_open('IN', (string)($as_profile_file)))) { error("Open Error : {$as_profile_file}"); }
    $alldata = pl_readlines('IN');
    pl_close('IN');
    // カップルランキングの場合
    if (($in['command'] == 'couple_ranking')) {
        if (!(pl_open('COA', (string)($couple_file)))) { error("{$couple_file}に書き込めません"); }
        $all_couple = pl_readlines('COA');
        pl_close('COA');
        if (!(pl_open('REN', './dat_dir/love.dat'))) { error('Open Error : ./dat_dir/love.dat'); }
        $top_koumoku = pl_readline('REN');
        $__t1 = pl_split('/<>/u', $top_koumoku); $koumokumei_hairetu = array_slice($__t1, 0);
        pl_close('REN');
        lock(...$_args);
        $wakareflag = 0;
        $now_time = pl_time();
        $__s2 = $_ ?? null;
        foreach ($all_couple ?? [] as $_) {
            [$cn_number, $cn_name1, $cn_name2, $cn_joutai, $cn_total_aijou, $cn_omoide1, $cn_omoide2, $cn_omoide3, $cn_omoide4, $cn_omoide5, $cn_kodomo, $cn_yobi1, $cn_yobi2, $cn_yobi3, $cn_yobi4, $cn_yobi5] = pl_pad(pl_split('/<>/u', $_), 16);
            if (($cn_joutai == '恋人')) {
                $yobikata = '恋人';
                $wakare_time = $wakare_limit_koibito;
            } else {
                $yobikata = '夫婦';
                $wakare_time = $wakare_limit_haiguu;
            }
            // 別れ処理
            if (((n($now_time) - n($cn_yobi1)) > (((n($wakare_time) * 60) * 60) * 24))) {
                $wakareflag = 1;
                news_kiroku('別れ', "{$cn_name1}さんと{$cn_name2}さんが別れました。");
                if (($yobikata == '夫婦')) {
                    kekkon_id_sakujo($cn_name1);
                    kekkon_id_sakujo($cn_name2);
                }
                continue;
            }
            $new_all_couple_sort[] = $_;
        }
        $_ = $__s2;
        // foreach閉じ
        // データ更新
        if ((n($wakareflag) == 1)) {
            if (!(pl_open('COP', ">{$couple_file}"))) { error("{$couple_file}に書き込めません"); }
            pl_print('COP', pl_join('', $new_all_couple_sort));
            pl_close('COP');
        }
        unlock(...$_args);
        // ver.1.40ここから
        $__s3 = $_ ?? null;
        foreach ($new_all_couple_sort ?? [] as $_) {
            $data = $_;
            $key = pl_idx(pl_split('/<>/u', $data), 4);
            // ソートする要素を選ぶ
            $key1 = pl_idx(pl_split('/<>/u', $data), 5);
            // ソートする要素を選ぶ
            $key2 = pl_idx(pl_split('/<>/u', $data), 6);
            // ソートする要素を選ぶ
            $key3 = pl_idx(pl_split('/<>/u', $data), 7);
            // ソートする要素を選ぶ
            $key4 = pl_idx(pl_split('/<>/u', $data), 8);
            // ソートする要素を選ぶ
            $key5 = pl_idx(pl_split('/<>/u', $data), 9);
            // ソートする要素を選ぶ
            $all_couple_sort[] = $data;
            $keys[] = $key;
            $keys1[] = $key1;
            $keys2[] = $key2;
            $keys3[] = $key3;
            $keys4[] = $key4;
            $keys5[] = $key5;
        }
        $_ = $__s3;
        $all_couple_sort0 = pl_slice($all_couple_sort, pl_sort('bykeys', pl_range(0, (pl_count($all_couple_sort) - 1))));
        $all_couple_sort1 = pl_slice($all_couple_sort, pl_sort('bykeys1', pl_range(0, (pl_count($all_couple_sort) - 1))));
        $all_couple_sort2 = pl_slice($all_couple_sort, pl_sort('bykeys2', pl_range(0, (pl_count($all_couple_sort) - 1))));
        $all_couple_sort3 = pl_slice($all_couple_sort, pl_sort('bykeys3', pl_range(0, (pl_count($all_couple_sort) - 1))));
        $all_couple_sort4 = pl_slice($all_couple_sort, pl_sort('bykeys4', pl_range(0, (pl_count($all_couple_sort) - 1))));
        $all_couple_sort5 = pl_slice($all_couple_sort, pl_sort('bykeys5', pl_range(0, (pl_count($all_couple_sort) - 1))));
        t_header('assen_style');
        echo <<<EOM
        	<table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
        	<tr>
        	<td bgcolor="#ffffff">現在成立している全てのカップルです。<br>ラブラブ度の多い順に並んでいます。
        	</td>
        	<td  bgcolor="#ff6633" align="center" width="35%"><div style="font-size:13px; color:#ffffff">ラブラブ&#9829;カップルランキング</div>
        	</td></tr></table><br>
        	<table width="90%" border="0" cellspacing="0" cellpadding="5" align="center" class="yosumi">
        EOM;
        echo "<tr  class=\"jouge\" bgcolor=\"#ffffcc\"><td style=\"color:#3366ff\">「{$koumokumei_hairetu[2]}」の多いカップル</td>";
        foreach (pl_range(1, 3) as $juni) {
            $sort_no = (n($koumoku_sentaku) - 1);
            $one_couple = pl_idx(pl_split('/<>/u', $all_couple_sort1[(n($juni) - 1)]), 1);
            $two_couple = pl_idx(pl_split('/<>/u', $all_couple_sort1[(n($juni) - 1)]), 2);
            $suuti = pl_idx(pl_split('/<>/u', $all_couple_sort1[(n($juni) - 1)]), 5);
            echo "<td><span style=\"color:#3366ff\">NO.{$juni}</span>：{$one_couple} & {$two_couple}（{$suuti}）</td>";
        }
        echo '</tr>';
        echo "<tr  class=\"jouge\" bgcolor=\"#ffffff\"><td style=\"color:#3366ff\">「{$koumokumei_hairetu[3]}」の多いカップル</td>";
        foreach (pl_range(1, 3) as $juni) {
            $sort_no = (n($koumoku_sentaku) - 1);
            $one_couple = pl_idx(pl_split('/<>/u', $all_couple_sort2[(n($juni) - 1)]), 1);
            $two_couple = pl_idx(pl_split('/<>/u', $all_couple_sort2[(n($juni) - 1)]), 2);
            $suuti = pl_idx(pl_split('/<>/u', $all_couple_sort2[(n($juni) - 1)]), 6);
            echo "<td><span style=\"color:#3366ff\">NO.{$juni}</span>：{$one_couple} & {$two_couple}（{$suuti}）</td>";
        }
        echo '</tr>';
        echo "<tr  class=\"jouge\" bgcolor=\"#ffffcc\"><td style=\"color:#3366ff\">「{$koumokumei_hairetu[4]}」の多いカップル</td>";
        foreach (pl_range(1, 3) as $juni) {
            $sort_no = (n($koumoku_sentaku) - 1);
            $one_couple = pl_idx(pl_split('/<>/u', $all_couple_sort3[(n($juni) - 1)]), 1);
            $two_couple = pl_idx(pl_split('/<>/u', $all_couple_sort3[(n($juni) - 1)]), 2);
            $suuti = pl_idx(pl_split('/<>/u', $all_couple_sort3[(n($juni) - 1)]), 7);
            echo "<td><span style=\"color:#3366ff\">NO.{$juni}</span>：{$one_couple} & {$two_couple}（{$suuti}）</td>";
        }
        echo '</tr>';
        echo "<tr  class=\"jouge\" bgcolor=\"#ffffff\"><td style=\"color:#3366ff\">「{$koumokumei_hairetu[5]}」の多いカップル</td>";
        foreach (pl_range(1, 3) as $juni) {
            $sort_no = (n($koumoku_sentaku) - 1);
            $one_couple = pl_idx(pl_split('/<>/u', $all_couple_sort4[(n($juni) - 1)]), 1);
            $two_couple = pl_idx(pl_split('/<>/u', $all_couple_sort4[(n($juni) - 1)]), 2);
            $suuti = pl_idx(pl_split('/<>/u', $all_couple_sort4[(n($juni) - 1)]), 8);
            echo "<td><span style=\"color:#3366ff\">NO.{$juni}</span>：{$one_couple} & {$two_couple}（{$suuti}）</td>";
        }
        echo '</tr>';
        echo "<tr  class=\"jouge\" bgcolor=\"#ffffcc\"><td style=\"color:#3366ff\">「{$koumokumei_hairetu[6]}」の多いカップル</td>";
        foreach (pl_range(1, 3) as $juni) {
            $sort_no = (n($koumoku_sentaku) - 1);
            $one_couple = pl_idx(pl_split('/<>/u', $all_couple_sort5[(n($juni) - 1)]), 1);
            $two_couple = pl_idx(pl_split('/<>/u', $all_couple_sort5[(n($juni) - 1)]), 2);
            $suuti = pl_idx(pl_split('/<>/u', $all_couple_sort5[(n($juni) - 1)]), 9);
            echo "<td><span style=\"color:#3366ff\">NO.{$juni}</span>：{$one_couple} & {$two_couple}（{$suuti}）</td>";
        }
        echo '</tr>';
        echo <<<EOM
        	</table><br>
        	<table width="90%" border="0" cellspacing="0" cellpadding="5" align="center" class="yosumi">
        	<tr  class="jouge" bgcolor="#ffffaa" align="center" nowrap><td>順位</td><td>名前</td><td>関係</td><td>ラブラブ度</td><td>{$koumokumei_hairetu[2]}</td><td>{$koumokumei_hairetu[3]}</td><td>{$koumokumei_hairetu[4]}</td><td>{$koumokumei_hairetu[5]}</td><td>{$koumokumei_hairetu[6]}</td></tr>
        EOM;
        $__s4 = $_ ?? null;
        foreach ($all_couple_sort0 ?? [] as $_) {
            [$cn_number, $cn_name1, $cn_name2, $cn_joutai] = pl_pad(pl_split('/<>/u', $_), 4);
            if (($cn_joutai == '配偶者')) {
                $huuhu[(string)($cn_name1)] = 'on';
                $huuhu[(string)($cn_name2)] = 'on';
            }
        }
        $_ = $__s4;
        $i = 1;
        $__s5 = $_ ?? null;
        foreach ($all_couple_sort0 ?? [] as $_) {
            [$cn_number, $cn_name1, $cn_name2, $cn_joutai, $cn_total_aijou, $cn_omoide1, $cn_omoide2, $cn_omoide3, $cn_omoide4, $cn_omoide5, $cn_kodomo, $cn_yobi1, $cn_yobi2, $cn_yobi3, $cn_yobi4, $cn_yobi5] = pl_pad(pl_split('/<>/u', $_), 16);
            if (($cn_joutai == '恋人')) {
                if ((($huuhu[(string)($cn_name1)] == 'on') || ($huuhu[(string)($cn_name2)] == 'on'))) {
                    $yobikata = '愛人';
                    $c_color = '#6699cc';
                } else {
                    $yobikata = '恋人';
                    $c_color = '#ff6600';
                }
            } else {
                $yobikata = '夫婦';
                $c_color = '#ff0000';
            }
            echo <<<EOM
            	<tr align="right"><td align="center">{$i}</td><td  align="left" style="color:{$c_color};">{$cn_name1} &#9829; {$cn_name2}</td><td align="center" style="color:{$c_color};">{$yobikata}</td><td class="mainasu">{$cn_total_aijou}</td><td>{$cn_omoide1}</td><td>{$cn_omoide2}</td><td>{$cn_omoide3}</td><td>{$cn_omoide4}</td><td>{$cn_omoide5}</td></tr>
            EOM;
            $i++;
        }
        $_ = $__s5;
        // foreach閉じ
        // ver.1.40ここまで
        echo '</table>';
        hooter('assenjo', '戻る', 'kekkon.php');
        hooter('login_view', '街へ戻る');
        exit;
    }
    // カップルランキングの場合閉じ
    // 子供ランキングの場合
    if (($in['command'] == 'kodomo_ranking')) {
        if (!(pl_open('KOD', (string)($kodomo_file)))) { error("Open Error : {$kodomo_file}"); }
        $all_kodomo = pl_readlines('KOD');
        pl_close('KOD');
        $now_time = pl_time();
        $kodomo_sakujo_flg = 0;
        $__s6 = $_ ?? null;
        foreach ($all_kodomo ?? [] as $_) {
            [$kod_num, $kod_name, $kod_oya1, $kod_oya2, $kod_job, $kod_kokugo, $kod_suugaku, $kod_rika, $kod_syakai, $kod_eigo, $kod_ongaku, $kod_bijutu, $kod_looks, $kod_tairyoku, $kod_kenkou, $kod_speed, $kod_power, $kod_wanryoku, $kod_kyakuryoku, $kod_love, $kod_unique, $kod_etti, $kod_yobi1, $kod_yobi2, $kod_yobi3, $kod_yobi4, $kod_yobi5, $kod_yobi6, $kod_yobi7, $kod_yobi8, $kod_yobi9, $kod_yobi10] = pl_pad(pl_split('/<>/u', $_), 32);
            $kono_nenrei = pl_int((((n($now_time) - n($kod_yobi1))) / (((60 * 60) * 24))));
            // 子供削除処理
            if ((n($kono_nenrei) > n($kodomo_sibou_time2))) {
                $kodomo_sakujo_flg = 1;
                continue;
            }
            // 子供流産処理
            if (((($kod_name == '') && ((n($now_time) - n($kod_yobi1)) > (((n($kodomo_sibou_time) * 60) * 60) * 24))) && (n($kod_yobi8) != 1))) {
                lock(...$_args);
                $kodomo_sakujo_flg = 1;
                unlock(...$_args);
                continue;
            }
            // 子供死亡処理
            if ((((n($now_time) - n($kod_yobi7)) > (((n($kodomo_sibou_time) * 60) * 60) * 24)) && (n($kod_yobi8) != 1))) {
                lock(...$_args);
                $kodomo_sakujo_flg = 1;
                unlock(...$_args);
                continue;
            }
            // 子供自立処理
            if (((n($kono_nenrei) >= 19) && (n($kod_yobi8) != 1))) {
                kodomo_ziritu($_);
                $kod_job = $return_job;
                $kod_yobi8 = 1;
                $kodomo_sakujo_flg = 1;
            }
            $new_kodomo_temp = "{$kod_num}<>{$kod_name}<>{$kod_oya1}<>{$kod_oya2}<>{$kod_job}<>{$kod_kokugo}<>{$kod_suugaku}<>{$kod_rika}<>{$kod_syakai}<>{$kod_eigo}<>{$kod_ongaku}<>{$kod_bijutu}<>{$kod_looks}<>{$kod_tairyoku}<>{$kod_kenkou}<>{$kod_speed}<>{$kod_power}<>{$kod_wanryoku}<>{$kod_kyakuryoku}<>{$kod_love}<>{$kod_unique}<>{$kod_etti}<>{$kod_yobi1}<>{$kod_yobi2}<>{$kod_yobi3}<>{$kod_yobi4}<>{$kod_yobi5}<>{$kod_yobi6}<>{$kod_yobi7}<>{$kod_yobi8}<>{$kod_yobi9}<>{$kod_yobi10}<>\n";
            $new_all_kodomo[] = $new_kodomo_temp;
        }
        $_ = $__s6;
        // foreach閉じ
        // 子供データ更新
        if ((n($kodomo_sakujo_flg) == 1)) {
            lock(...$_args);
            if (!(pl_open('KODO', ">{$kodomo_file}"))) { error("{$kodomo_file}に書き込めません"); }
            pl_print('KODO', pl_join('', $new_all_kodomo));
            pl_close('KODO');
            unlock(...$_args);
        }
        $__s7 = $_ ?? null;
        foreach ($new_all_kodomo ?? [] as $_) {
            $data = $_;
            $key = pl_idx(pl_split('/<>/u', $data), 25);
            // ソートする要素を選ぶ
            $all_kodomo_sort[] = $data;
            $keys[] = $key;
        }
        $_ = $__s7;
        // ソート処理
        $all_kodomo_sort = pl_slice($all_kodomo_sort, pl_sort('bykeys', pl_range(0, (pl_count($all_kodomo_sort) - 1))));
        $__s8 = $_ ?? null;
        foreach ($all_kodomo_sort ?? [] as $_) {
            [$kod_num, $kod_name, $kod_oya1, $kod_oya2, $kod_job, $kod_kokugo, $kod_suugaku, $kod_rika, $kod_syakai, $kod_eigo, $kod_ongaku, $kod_bijutu, $kod_looks, $kod_tairyoku, $kod_kenkou, $kod_speed, $kod_power, $kod_wanryoku, $kod_kyakuryoku, $kod_love, $kod_unique, $kod_etti, $kod_yobi1, $kod_yobi2, $kod_yobi3, $kod_yobi4, $kod_yobi5, $kod_yobi6, $kod_yobi7, $kod_yobi8, $kod_yobi9, $kod_yobi10] = pl_pad(pl_split('/<>/u', $_), 32);
            $kono_nenrei = pl_int((((n($now_time) - n($kod_yobi1))) / (((60 * 60) * 24))));
            $kod_yobi5 = pl_sprintf('%.1f', $kod_yobi5);
            $kod_yobi6 = pl_sprintf('%.1f', $kod_yobi6);
            $ko_meisai_td = "<td>{$kod_name}</td><td>{$kod_oya1}&#9829;{$kod_oya2}</td><td align=\"right\">{$kono_nenrei}歳</td><td align=\"right\">{$kod_yobi5} cm</td><td align=\"right\">{$kod_yobi6} kg</td><td align=\"right\">{$kod_yobi4}</td>";
            if (($kod_name == '')) {
                continue;
            }
            if ((n($kono_nenrei) >= 19)) {
                continue;
            }
            if ((n($kono_nenrei) >= 16)) {
                $koukou_hairetu[] = $ko_meisai_td;
            } elseif ((n($kono_nenrei) >= 13)) {
                $tyuugaku_hairetu[] = $ko_meisai_td;
            } elseif ((n($kono_nenrei) >= 7)) {
                $syougaku_hairetu[] = $ko_meisai_td;
            } else {
                $youzi_hairetu[] = $ko_meisai_td;
            }
        }
        $_ = $__s8;
        t_header('assen_style');
        echo <<<EOM
        	<table width="95%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
        	<tr>
        	<td bgcolor="#ffffff">子供の能力ランキングです。年齢によって「幼児部門」「小学生部門」「中学生部門」「高校生部門」に分かれます。
        	</td>
        	<td  bgcolor="#ff6633" align="center" width="35%"><div style="font-size:13px; color:#ffffff">子供ランキングベスト20</div>
        	</td></tr></table><br>
        	<table width="95%" border="0" cellspacing="0" cellpadding="5" align="center" class="yosumi">
        	<tr><td valign="top" width="50%">
        	<div class="tyuu" align="center">幼児部門</div>
        	<table width="100%" border="0" cellspacing="0" cellpadding="5" align="center" class="yosumi">
        	<tr  class="jouge" bgcolor="#ffffaa" align="center" nowrap><td>順位</td><td>子供の名前</td><td>親の名前</td><td>年齢</td><td>身長</td><td>体重</td><td>能力値</td></tr>
        EOM;
        $i = 1;
        $__s9 = $_ ?? null;
        foreach ($youzi_hairetu ?? [] as $_) {
            echo "<tr><td align=\"center\">{$i}</td>{$_}</tr>";
            $i++;
            if ((n($i) >= 20)) {
                break;
            }
        }
        $_ = $__s9;
        echo <<<EOM
        	</table></td><td valign="top" width="50%">
        	<div class="tyuu" align="center">小学生部門</div>
        	<table width="100%" border="0" cellspacing="0" cellpadding="5" align="center" class="yosumi">
        	<tr  class="jouge" bgcolor="#ffffaa" align="center" nowrap><td>順位</td><td>子供の名前</td><td>親の名前</td><td>年齢</td><td>身長</td><td>体重</td><td>能力値</td></tr>
        EOM;
        $i = 1;
        $__s10 = $_ ?? null;
        foreach ($syougaku_hairetu ?? [] as $_) {
            echo "<tr><td align=\"center\">{$i}</td>{$_}</tr>";
            $i++;
            if ((n($i) >= 20)) {
                break;
            }
        }
        $_ = $__s10;
        echo <<<EOM
        	</table></td></tr></table><br><br>
        	<table width="95%" border="0" cellspacing="0" cellpadding="5" align="center" class="yosumi">
        	<tr><td valign="top" width="50%">
        	<div class="tyuu" align="center">中学生部門</div>
        	<table width="100%" border="0" cellspacing="0" cellpadding="5" align="center" class="yosumi">
        	<tr  class="jouge" bgcolor="#ffffaa" align="center" nowrap><td>順位</td><td>子供の名前</td><td>親の名前</td><td>年齢</td><td>身長</td><td>体重</td><td>能力値</td></tr>
        EOM;
        $i = 1;
        $__s11 = $_ ?? null;
        foreach ($tyuugaku_hairetu ?? [] as $_) {
            echo "<tr><td align=\"center\">{$i}</td>{$_}</tr>";
            $i++;
            if ((n($i) >= 20)) {
                break;
            }
        }
        $_ = $__s11;
        echo <<<EOM
        	</table></td><td valign="top" width="50%">
        	<div class="tyuu" align="center">高校生部門</div>
        	<table width="100%" border="0" cellspacing="0" cellpadding="5" align="center" class="yosumi">
        	<tr  class="jouge" bgcolor="#ffffaa" align="center" nowrap><td>順位</td><td>子供の名前</td><td>親の名前</td><td>年齢</td><td>身長</td><td>体重</td><td>能力値</td></tr>
        EOM;
        $i = 1;
        $__s12 = $_ ?? null;
        foreach ($koukou_hairetu ?? [] as $_) {
            echo "<tr><td align=\"center\">{$i}</td>{$_}</tr>";
            $i++;
            if ((n($i) >= 20)) {
                break;
            }
        }
        $_ = $__s12;
        echo '</table></td></tr></table>';
        echo '<br><div align="center"><!--TW_BACK--></div>';
        hooter('login_view', '街へ戻る');
        exit;
    }
    // 子供ランキングの場合閉じ
    // プロフィール削除の場合
    if (($in['command'] == 'touroku_sakujo')) {
        lock(...$_args);
        // ver.1.40ここから
        if (!(pl_open('ASP', (string)($as_profile_file)))) { error("Open Error : {$as_profile_file}"); }
        $as_pro_alldata = pl_readlines('ASP');
        pl_close('ASP');
        $as_new_pro_alldata = [];
        $__s13 = $_ ?? null;
        foreach ($as_pro_alldata ?? [] as $_) {
            [$pro_name, $pro_sex, $pro_age, $pro_addr, $pro_p1, $pro_p2, $pro_p3, $pro_p4, $pro_p5, $pro_p6, $pro_p7, $pro_p8, $pro_p9, $pro_p10, $pro_p11, $pro_p12, $pro_p13, $pro_p14, $pro_p15, $pro_p16, $pro_p17, $pro_p18, $pro_p19, $pro_p20] = pl_pad(pl_split('/<>/u', $_), 24);
            if (((string)$in['name'] === (string)(string)($pro_name))) {
                continue;
            }
            $as_new_pro_alldata[] = $_;
        }
        $_ = $__s13;
        // ログ更新
        if (!(pl_open('ASPO', ">{$as_profile_file}"))) { error("{$as_profile_file}に書き込めません"); }
        pl_print('ASPO', pl_join('', $as_new_pro_alldata));
        pl_close('ASPO');
        // &as_prof_sakujo($in{'name'});
        // ver.1.40ここまで
        unlock(...$_args);
        message_only('恋人斡旋所のプロフィールを削除しました。');
        hooter('assenjo', '戻る', 'kekkon.php');
        exit;
    }
    // 登録フォームの出力
    if (($in['command'] == 'touroku_form')) {
        as_prof_form(...$_args);
        exit;
    }
    // 登録の場合
    if (($in['command'] == 'touroku')) {
        if ((n($love) < n($need_love))) {
            error('登録するのに必要なLOVEパラメータが足りません');
        }
        $atta_flag = 0;
        $new_alldata = [];
        $__s14 = $_ ?? null;
        foreach ($alldata ?? [] as $_) {
            [$pro_name, $pro_sex, $pro_age, $pro_addr, $pro_p1, $pro_p2, $pro_p3, $pro_p4, $pro_p5, $pro_p6, $pro_p7, $pro_p8, $pro_p9, $pro_p10, $pro_p11, $pro_p12, $pro_p13, $pro_p14, $pro_p15, $pro_p16, $pro_p17, $pro_p18, $pro_p19, $pro_p20] = pl_pad(pl_split('/<>/u', $_), 24);
            // 修正の場合
            if (((string)$name === (string)(string)($pro_name))) {
                continue;
            }
            $new_alldata[] = $_;
        }
        $_ = $__s14;
        $new_entry = pl_idx(["{$name}<>{$in['pro_sex']}<>{$in['pro_age']}<>{$in['pro_addr']}<>{$in['pro_p1']}<>{$in['pro_p2']}<>{$in['pro_p3']}<>{$in['pro_p4']}<>{$in['pro_p5']}<>{$in['pro_p6']}<>{$in['pro_p7']}<>{$k_sousisan}<>{$k_id}<>{$job}<>{$in['pro_p11']}<>{$in['pro_p12']}<>{$in['pro_p13']}<>{$in['pro_p14']}<>{$in['pro_p15']}<>{$in['pro_p16']}<>{$in['pro_p17']}<>{$in['pro_p18']}<>{$in['pro_p19']}<>{$in['pro_p20']}<>\n"], 0);
        // pro_p7までがプロフィール　pro_p8＝総資産　pro_p9＝ID番号（家表示用）pro_p10＝職業
        pl_unshift($new_alldata, $new_entry);
        // ログ更新
        lock(...$_args);
        if (!(pl_open('OUT', ">{$as_profile_file}"))) { error("{$profile_file}に書き込めません"); }
        pl_print('OUT', pl_join('', $new_alldata));
        pl_close('OUT');
        unlock(...$_args);
        message('恋人斡旋所のプロフィール登録を行いました。', 'assenjo', 'kekkon.php');
        exit;
    }
    if ((n($love) < n($need_love))) {
        $keikoku = "※LOVEパラメータが足らないため、{$name}さんはまだ登録できません。";
    }
    t_header('assen_style');
    echo <<<EOM
    	<table width="95%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr>
    	<td bgcolor="#ffffff">●ここに登録することでバーチャルな恋愛や結婚をすることができるようになります。<br>
    	●登録するにはLOVEパラメータが{$need_love}以上必要です（パラメータウインドウの上部には新たにハートマークのアイコンが現れます）。<br>
    	●登録が済んだ方は、こちらで好きな方に交際の申し込みをしたり、交際申し込みメールを受け取ったりできるようになります。<br>
    	●配偶者、恋人を合わせ、同時に{$koibito_seigen}人の方とおつき合いができます。<br>
    	●恋人ができると、ハートマークのアイコンから恋愛コマンドが使えるようになります。<br>
    	●結婚するためには、恋愛コマンドによって恋人になった方とのラブラブ度を深めていく必要があります。<br>
    	<span style="color:#336699">{$keikoku}</span>
    	</td>
    	<td  bgcolor="#ffff99" align="center" width="35%"><img src="{$img_dir}/assen_tytle.gif">
    	</td></tr></table><br>
    
    	<table width="95%" border="0" cellspacing="0" cellpadding="8" align="center" bgcolor="#ffffff">
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="assenjo">
    	<input type="hidden" name="command" value="easySerch">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<tr>
    EOM;
    // 検索フォーム
    // 名前
    echo "<td>名　前 <input type=\"text\" name=\"serch_name\" size=\"20\"></td>\n";
    // 年齢
    echo "<td><select name=\"age\">\n";
    echo "<option value=\"\">年齢\n";
    for ($i = 1; (n($i) < pl_count($as_age_array)); $i++) {
        $option = '<option>';
        if ((((string)$in['age'] === (string)$as_age_array[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $as_age_array[n($i)];
    }
    echo "</select></td>\n";
    // 住所
    echo "<td><select name=\"address\">\n";
    echo "<option value=\"\">住所\n";
    for ($i = 1; (n($i) < pl_count($as_address_array)); $i++) {
        $option = '<option>';
        if ((((string)$in['address'] === (string)$as_address_array[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $as_address_array[n($i)];
    }
    echo "</select></td>\n";
    // プロフ1
    echo "<td valign=\"top\" nowrap>\n";
    echo "<select name=\"p1\">\n";
    echo "<option value=\"\">{$as_prof_name1}\n";
    for ($i = 1; (n($i) < pl_count($as_prof_array1)); $i++) {
        $option = '<option>';
        if ((((string)$in['p1'] === (string)$as_prof_array1[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $as_prof_array1[n($i)];
    }
    echo "</select></td>\n";
    // プロフ2
    echo "<td valign=\"top\" nowrap>\n";
    echo "<select name=\"p2\">\n";
    echo "<option value=\"\">{$as_prof_name2}\n";
    for ($i = 1; (n($i) < pl_count($as_prof_array2)); $i++) {
        $option = '<option>';
        if ((((string)$in['p2'] === (string)$as_prof_array2[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $as_prof_array2[n($i)];
    }
    echo "</select></td></tr><tr>\n";
    // プロフ3
    echo "<td valign=\"top\" nowrap>\n";
    echo "<select name=\"p3\">\n";
    echo "<option value=\"\" selected>{$as_prof_name3}\n";
    for ($i = 1; (n($i) < pl_count($as_prof_array3)); $i++) {
        $option = '<option>';
        if ((((string)$in['p3'] === (string)$as_prof_array3[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $as_prof_array3[n($i)];
    }
    echo "</select></td>\n";
    // プロフ4
    echo "<td valign=\"top\" nowrap>\n";
    echo "<select name=\"p4\">\n";
    echo "<option value=\"\" selected>{$as_prof_name4}\n";
    for ($i = 1; (n($i) < pl_count($as_prof_array4)); $i++) {
        $option = '<option>';
        if ((((string)$in['p4'] === (string)$as_prof_array4[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $as_prof_array4[n($i)];
    }
    echo "</select></td>\n";
    // プロフ5
    echo "<td valign=\"top\" nowrap>\n";
    echo "<select name=\"p5\">\n";
    echo "<option value=\"\" selected>{$as_prof_name5}\n";
    for ($i = 1; (n($i) < pl_count($as_prof_array5)); $i++) {
        $option = '<option>';
        if ((((string)$in['p5'] === (string)$as_prof_array5[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $as_prof_array5[n($i)];
    }
    echo "</select></td>\n";
    // プロフ6
    echo "<td valign=\"top\" nowrap>\n";
    echo "<select name=\"p6\">\n";
    echo "<option value=\"\" selected>{$as_prof_name6}\n";
    for ($i = 1; (n($i) < pl_count($as_prof_array6)); $i++) {
        $option = '<option>';
        if ((((string)$in['p6'] === (string)$as_prof_array6[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $as_prof_array6[n($i)];
    }
    echo <<<EOM
    	</select></td><td>
    	<input type="submit" value=" 検索する ">
    	</form>
    	  </td>
        </tr>
      </table><br>
    	<table border="0" align="center" width="400">
    	<tr><td align="left">
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="assenjo">
    	<input type="hidden" name="command" value="couple_ranking">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value="カップルランキング">
    	</form>
    	</td><td align="right">
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="assenjo">
    	<input type="hidden" name="command" value="kodomo_ranking">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value="子供ランキング">
    	</form>
    	</td></tr></table><br>
    EOM;
    // &hooter("login_view","街へ戻る");
    $page = $in['page'];
    if (($page == '')) {
        $page = 0;
    }
    $i3 = 0;
    // #ログ表示処理
    // 個人の家情報をunitハッシュに代入
    if (!(pl_open('OI', (string)($ori_ie_list)))) { error("Open Error : {$ori_ie_list}"); }
    $ori_ie_hairetu = pl_readlines('OI');
    $__s15 = $_ ?? null;
    foreach ($ori_ie_hairetu ?? [] as $_) {
        ori_ie_sprit($_);
        $unit[(string)($ori_k_id)] = "<input type=\"hidden\" name=\"mode\" value=\"houmon\"><input type=\"hidden\" name=\"ori_ie_id\" value=\"{$ori_k_id}\"><input type=\"hidden\" name=\"name\" value=\"{$in['name']}\"><input type=\"hidden\" name=\"pass\" value=\"{$in['pass']}\"><input type=\"hidden\" name=\"town_no\" value=\"{$in['town_no']}\"><input type=\"image\" src=\"{$ori_ie_image}\"></form>";
    }
    $_ = $__s15;
    pl_close('OI');
    // 自分のプロフィールを表示
    $__s16 = $_ ?? null;
    foreach ($alldata ?? [] as $_) {
        [$pro_name, $pro_sex, $pro_age, $pro_addr, $pro_p1, $pro_p2, $pro_p3, $pro_p4, $pro_p5, $pro_p6, $pro_p7, $pro_p8, $pro_p9, $pro_p10, $pro_p11, $pro_p12, $pro_p13, $pro_p14, $pro_p15, $pro_p16, $pro_p17, $pro_p18, $pro_p19, $pro_p20] = pl_pad(pl_split('/<>/u', $_), 24);
        $my_prof_hairetu = pl_split('/<>/u', $_);
        if (((string)$name === (string)(string)($pro_name))) {
            $prof_atta_flg = 1;
            break;
        }
    }
    $_ = $__s16;
    if (($in['command'] != 'easySerch')) {
        // 検索の場合は自分のプロフィールを表示しない
        if (($pro_sex == '男')) {
            $sex_style = 'border: #ffffff; border-style: solid; border-width: 2px; background-color:#ffffff';
        } elseif (($pro_sex == '女')) {
            $sex_style = 'border: #ffffff; border-style: solid; border-width: 2px; background-color:#ffffff';
        }
        if (($unit[(string)($pro_p9)] != '')) {
            $ie_hyouzi = (string)($unit[$pro_p9]);
        } else {
            $ie_hyouzi = '：無し</form>';
        }
        if ((n($prof_atta_flg) == 0)) {
            $zibun_prof_com = '<tr><td align="center" colspan="8">登録していません。</td></tr>';
        } else {
            $zibun_prof_com = <<<EOM
				<tr><td width="120"><span class="honbun2">名前</span>：{$pro_name}（{$pro_p10}）</td>
				<td width="120"><span class="honbun2">性別</span>：{$pro_sex}</td>
				<td width="120"><span class="honbun2">総資産</span>：{$pro_p8}円</td>
				<form method="POST" action="original_house.php"><td width="120"><span class="honbun2">家</span>{$ie_hyouzi}</td></tr><!--ver.1.40-->
				<tr><td width="120"><span class="honbun2">年齢</span>：{$pro_age}</td>
				<td width="120"><span class="honbun2">住所</span>：{$pro_addr}</td>
				<td width="120"><span class="honbun2">{$as_prof_name1}</span>：{$pro_p1}</td>
				<td width="120"><span class="honbun2">{$as_prof_name2}</span>：{$pro_p2}</td></tr>
				<tr><td width="120"><span class="honbun2">{$as_prof_name3}</span>：{$pro_p3}</td>
				<td width="120"><span class="honbun2">{$as_prof_name4}</span>：{$pro_p4}</td>
				<td width="120"><span class="honbun2">{$as_prof_name5}</span>：{$pro_p5}</td>
				<td width="120"><span class="honbun2">{$as_prof_name6}</span>：{$pro_p6}</td></tr>
				<tr><td width="120" colspan="8"><span class="honbun2">一言コメント</span>：{$pro_p7}<br><br>
				※職業、総資産、家などこちらで更新しないと最新の情報になりません。<br>
				※結婚したり、すでに規定の恋人数に達している方は「ただいま」の項目を随意変更してください。</td></tr>
EOM;
        }
        echo <<<EOM
        			<table style="{$sex_style}" align="center" width="95%">
        			<tr><td width="120" colspan="8" align="center"><span class="honbun2"><strong>＜自分のプロフィール＞</strong></td></tr>
        			{$zibun_prof_com}
        	<tr><td width="120" colspan="8" align="center">
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="assenjo">
        	<input type="hidden" name="command" value="touroku_form">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<input type="submit" value="プロフィール登録＆修正">
        	</form>
        	</td></tr>
        			</table><br><br>
        EOM;
    }
    // 簡易検索の場合
    if (($in['command'] == 'easySerch')) {
        $i = 0;
        $__s17 = $_ ?? null;
        foreach ($alldata ?? [] as $_) {
            [$pro_name, $pro_sex, $pro_age, $pro_addr, $pro_p1, $pro_p2, $pro_p3, $pro_p4, $pro_p5, $pro_p6, $pro_p7, $pro_p8, $pro_p9, $pro_p10, $pro_p11, $pro_p12, $pro_p13, $pro_p14, $pro_p15, $pro_p16, $pro_p17, $pro_p18, $pro_p19, $pro_p20] = pl_pad(pl_split('/<>/u', $_), 24);
            if ((($in['serch_name'] != '') && ((string)$in['serch_name'] !== (string)$pro_name))) {
                continue;
            }
            // 条件が一致しなかった時点で次の人へ
            if ((($in['age'] != '') && ((string)$in['age'] !== (string)$pro_age))) {
                continue;
            }
            if ((($in['address'] != '') && ((string)$in['address'] !== (string)$pro_addr))) {
                continue;
            }
            if ((($in['p1'] != '') && ((string)$in['p1'] !== (string)$pro_p1))) {
                continue;
            }
            if ((($in['p2'] != '') && ((string)$in['p2'] !== (string)$pro_p2))) {
                continue;
            }
            if ((($in['p3'] != '') && ((string)$in['p3'] !== (string)$pro_p3))) {
                continue;
            }
            if ((($in['p4'] != '') && ((string)$in['p4'] !== (string)$pro_p4))) {
                continue;
            }
            if ((($in['p5'] != '') && ((string)$in['p5'] !== (string)$pro_p5))) {
                continue;
            }
            if ((($in['p6'] != '') && ((string)$in['p6'] !== (string)$pro_p6))) {
                continue;
            }
            if (((string)$name === (string)(string)($pro_name))) {
                continue;
            }
            if ((n($douseiai_per) == 0)) {
                if ((($sex == 'm') && ($pro_sex == '男'))) {
                    continue;
                }
                if ((($sex == 'f') && ($pro_sex == '女'))) {
                    continue;
                }
            }
            $i++;
            $newrank[] = $_;
        }
        $_ = $__s17;
        $alldata = $newrank;
        echo "<div align=\"center\" class=\"sub2\">条件にヒットしたのは{$i}件です。<br>\n\t\t\t\t<!--TW_BACK-->\n\t\t\t\t</div><br>";
        hooter('assenjo', '全て表示', 'kekkon.php');
    }
    // 全員のプロフィールを表示
    if ((n($douseiai_per) == 0)) {
        if (($sex == 'm')) {
            $tourokusya_hyouki = '＜現在の女性登録者＞';
        } elseif (($sex == 'f')) {
            $tourokusya_hyouki = '＜現在の男性登録者＞';
        }
    } else {
        $tourokusya_hyouki = '＜現在の登録者＞';
    }
    echo <<<EOM
    	<div align="center" class="honbun4">{$tourokusya_hyouki}</div><br>
    EOM;
    $__s18 = $_ ?? null;
    foreach ($alldata ?? [] as $_) {
        [$pro_name, $pro_sex, $pro_age, $pro_addr, $pro_p1, $pro_p2, $pro_p3, $pro_p4, $pro_p5, $pro_p6, $pro_p7, $pro_p8, $pro_p9, $pro_p10, $pro_p11, $pro_p12, $pro_p13, $pro_p14, $pro_p15, $pro_p16, $pro_p17, $pro_p18, $pro_p19, $pro_p20] = pl_pad(pl_split('/<>/u', $_), 24);
        $my_prof_hairetu = pl_split('/<>/u', $_);
        if (((string)$name === (string)(string)($pro_name))) {
            continue;
        }
        if ((n($douseiai_per) == 0)) {
            if ((($sex == 'm') && ($pro_sex == '男'))) {
                continue;
            }
            if ((($sex == 'f') && ($pro_sex == '女'))) {
                continue;
            }
        }
        $i3++;
        if ((n($i3) < (n($page) + 1))) {
            continue;
        }
        if ((n($i3) > (n($page) + n($hyouzi_max_grobal)))) {
            break;
        }
        if (($pro_sex == '男')) {
            $sex_style = 'border: #ffff99; border-style: double; border-width: 7px; background-color:#ffff99';
        } elseif (($pro_sex == '女')) {
            $sex_style = 'border: #ffff99; border-style: double; border-width: 7px; background-color:#ffff99';
        }
        if (($unit[(string)($pro_p9)] != '')) {
            $ie_hyouzi = (string)($unit[$pro_p9]);
        } else {
            $ie_hyouzi = '：無し</form>';
        }
        // 総資産のコンマ処理
        $pro_p8 = pl_comma($pro_p8);
        echo <<<EOM
        			<table style="{$sex_style}" align="center" width="450">
        			<tr><td align="right" width="120"><div class="honbun2">名前</div></td><td>：{$pro_name}（{$pro_p10}）</td></tr>
        			<tr><td align="right" width="120"><div class="honbun2">性別</div></td><td>：{$pro_sex}</td></tr>
        			<tr><td align="right" width="120"><div class="honbun2">総資産</div></td><td>：{$pro_p8}円</td></tr>
        			<tr><td align="right" width="120"><div class="honbun2">家</div></td><form method="POST" action="original_house.php"><td>{$ie_hyouzi}<!--ver.1.40-->
        EOM;
        $i = 0;
        $__s19 = $_ ?? null;
        foreach ($my_prof_hairetu ?? [] as $__k19 => $_) {
            try {
                $_ = preg_replace('/([^=^\\"]|^)(https?\\:[\\w\\.\\~\\-\\/\\?\\&\\+\\=\\:\\@\\%\\;\\#\\%]+)/u', '${1}<a href="${2}" target="_blank">${2}</a>', (string)$_, -1);
                $i++;
                if ((n($i) <= 2)) {
                    continue;
                }
                if ((n($i) >= 12)) {
                    break;
                } elseif ((n($i) == 3)) {
                    $pr_koumokumei = '年齢';
                } elseif ((n($i) == 4)) {
                    $pr_koumokumei = '住所';
                } elseif ((n($i) == 5)) {
                    $pr_koumokumei = (string)($as_prof_name1);
                } elseif ((n($i) == 6)) {
                    $pr_koumokumei = (string)($as_prof_name2);
                } elseif ((n($i) == 7)) {
                    $pr_koumokumei = (string)($as_prof_name3);
                } elseif ((n($i) == 8)) {
                    $pr_koumokumei = (string)($as_prof_name4);
                } elseif ((n($i) == 9)) {
                    $pr_koumokumei = (string)($as_prof_name5);
                } elseif ((n($i) == 10)) {
                    $pr_koumokumei = (string)($as_prof_name6);
                } elseif ((n($i) == 11)) {
                    $pr_koumokumei = '一言コメント';
                }
                if ((($_ != '') && ($_ != "\n"))) {
                    echo <<<EOM
                    					<tr><td align="right" width="120"><div class="honbun2">{$pr_koumokumei}</div></td>
                    					<td>：{$_}</td></tr>
                    EOM;
                }
            } finally { $my_prof_hairetu[$__k19] = $_; }
        }
        $_ = $__s19;
        // foreach閉じ
        // LOVEパラメータが超えて登録が済んでいたらフォーム出力
        if (((n($love) >= n($need_love)) && (n($prof_atta_flg) == 1))) {
            echo <<<EOM
                 <tr><td colspan="2" align="center">
            	 <form method="POST" action="{$this_script}">
            	<input type="hidden" name="mode" value="kokuhaku">
            	<input type="hidden" name="kokuhaku_syubetu" value="mousikomi">
            	<input type="hidden" name="name" value="{$name}">
            	<input type="hidden" name="pass" value="{$in['pass']}">
            	<input type="hidden" name="id" value="{$k_id}">
            	<input type="hidden" name="town_no" value="{$in['town_no']}">
            	<input type="hidden" name="sousinsaki_name" value="{$pro_name}">
            	告白の言葉 <input type="text" name="m_com" size="30">
            	<input type="submit" value="交際を申し込む"></form>
            EOM;
        }
        echo <<<EOM
        	</td></tr>
        	</table><br>
        EOM;
    }
    $_ = $__s18;
    // foreachの閉じ（ログ表示処理ここまで）
    $next = (n($page) + n($hyouzi_max_grobal));
    $back = (n($page) - n($hyouzi_max_grobal));
    echo '<div align="center"><table border="0"><tr>';
    if ((n($back) >= 0)) {
        // 検索の場合のボタン
        if (($in['command'] == 'easySerch')) {
            echo <<<EOM
            			<form method="POST" action="{$this_script}">
            			<input type="hidden" name="mode" value="assenjo">
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
            			<input type="hidden" name="p5" value="{$in['p6']}">
            			<input type="hidden" name="page" value="{$back}">
            			<input type="submit" value="BACK">
            			</form>
            EOM;
        } else {
            // 通常の場合
            echo <<<EOM
            			<td><form method="POST" action="{$this_script}">
            			<input type="hidden" name="mode" value="assenjo">
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
            			<input type="hidden" name="mode" value="assenjo">
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
            			<input type="hidden" name="p6" value="{$in['p6']}">
            			<input type="hidden" name="page" value="{$next}">
            			<input type="submit" value="NEXT">
            </form>
            EOM;
        } else {
            echo <<<EOM
            			<td><form method="POST" action="{$this_script}">
            			<input type="hidden" name="mode" value="assenjo">
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

function as_prof_form(...$_args)
{
    global $_, $alldata, $as_address_array, $as_age_array, $as_prof_array1, $as_prof_array2, $as_prof_array3,
        $as_prof_array4, $as_prof_array5, $as_prof_array6, $as_prof_name1, $as_prof_name2, $as_prof_name3,
        $as_prof_name4, $as_prof_name5, $as_prof_name6, $atta_flag, $i, $in, $love, $my_prof_hairetu, $name,
        $need_love, $pro_addr, $pro_age, $pro_name, $pro_p1, $pro_p10, $pro_p11, $pro_p12, $pro_p13, $pro_p14,
        $pro_p15, $pro_p16, $pro_p17, $pro_p18, $pro_p19, $pro_p2, $pro_p20, $pro_p3, $pro_p4, $pro_p5, $pro_p6,
        $pro_p7, $pro_p8, $pro_p9, $pro_sex, $sex, $sex_sentaku, $this_script;
    if ((n($love) < n($need_love))) {
        error('登録するのに必要なLOVEパラメータが足りません');
    }
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
    t_header('assen_style');
    if (($sex == 'm')) {
        $sex_sentaku = '男';
    } else {
        $sex_sentaku = '女';
    }
    echo <<<EOM
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="assenjo">
    	<input type="hidden" name="command" value="touroku">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="pro_sex" value="{$sex_sentaku}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    <table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr>
    	<td bgcolor="#ffffff">●公開したい項目のみ選択または記述し、その他は空白のままでOKです。<br>
    	●「ただいま」の項目はゲーム内での状態を選択してください。結婚したり、すでに規定の恋人数に達している方は随意変更するようにしてください。<br>
    	●修正・更新はいつでもできます。
    	</td>
    	<td  bgcolor="#ff6633" align="center" width="35%"><div style="font-size:13px; color:#ffffff">恋人斡旋所プロフィール登録</div>
    	</td></tr></table><br>
    
    	<table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr><td>
    EOM;
    echo ' 年齢<br><select name="pro_age">';
    for ($i = 0; (n($i) < pl_count($as_age_array)); $i++) {
        $option = '<option>';
        if ((((string)$pro_age === (string)$as_age_array[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $as_age_array[n($i)];
    }
    echo '</select></td><td>';
    echo ' 住所<br><select name="pro_addr">';
    for ($i = 0; (n($i) < pl_count($as_address_array)); $i++) {
        $option = '<option>';
        if ((((string)$pro_addr === (string)$as_address_array[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $as_address_array[n($i)];
    }
    echo '</select></td><td>';
    echo "{$as_prof_name1}<br><select name=\"pro_p1\">";
    for ($i = 0; (n($i) < pl_count($as_prof_array1)); $i++) {
        $option = '<option>';
        if ((((string)$pro_p1 === (string)$as_prof_array1[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $as_prof_array1[n($i)];
    }
    echo '</select></td><td>';
    echo "{$as_prof_name2}<br><select name=\"pro_p2\">";
    for ($i = 0; (n($i) < pl_count($as_prof_array2)); $i++) {
        $option = ' <option>';
        if ((((string)$pro_p2 === (string)$as_prof_array2[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $as_prof_array2[n($i)];
    }
    echo '</select></td></tr><tr><td>';
    echo "{$as_prof_name3}<br><select name=\"pro_p3\">";
    for ($i = 0; (n($i) < pl_count($as_prof_array3)); $i++) {
        $option = ' <option>';
        if ((((string)$pro_p3 === (string)$as_prof_array3[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $as_prof_array3[n($i)];
    }
    echo '</select></td><td>';
    echo "{$as_prof_name4}<br><select name=\"pro_p4\">";
    for ($i = 0; (n($i) < pl_count($as_prof_array4)); $i++) {
        $option = ' <option>';
        if ((((string)$pro_p4 === (string)$as_prof_array4[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $as_prof_array4[n($i)];
    }
    echo '</select></td><td>';
    echo "{$as_prof_name5}<br><select name=\"pro_p5\">";
    for ($i = 0; (n($i) < pl_count($as_prof_array5)); $i++) {
        $option = ' <option>';
        if ((((string)$pro_p5 === (string)$as_prof_array5[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $as_prof_array5[n($i)];
    }
    echo '</select></td><td>';
    echo "{$as_prof_name6}<br><select name=\"pro_p6\">";
    for ($i = 0; (n($i) < pl_count($as_prof_array6)); $i++) {
        $option = ' <option>';
        if ((((string)$pro_p6 === (string)$as_prof_array6[n($i)]))) {
            $option = '<option selected>';
        }
        echo $option, $as_prof_array6[n($i)];
    }
    echo '</select></td></tr>';
    echo "<tr><td align=\"right\">一言コメント</td><td colspan=\"3\"><input type=\"text\" name=\"pro_p7\" size=\"80\" value=\"{$my_prof_hairetu[10]}\"></td></tr>\n";
    echo <<<EOM
    	<tr><td colspan="4" align="center">
    	<input type="submit" value=" この内容で登録する "><br>
    	</td></tr></table>
    	</form><br>
    	<div align="center"><form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="assenjo">
    	<input type="hidden" name="command" value="touroku_sakujo">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value=" 登録してあるプロフィールを削除する ">
    	</form></div>
    EOM;
    hooter('assenjo', '戻る', 'kekkon.php');
}

function kokuhaku(...$_args)
{
    global $_, $aite_count, $aite_house_type, $aite_k_temp, $aite_log_file, $aite_name, $aiteatta_flg, $all_couple,
        $alldata, $as_profile_file, $cn_joutai, $cn_kodomo, $cn_name1, $cn_name2, $cn_number, $cn_omoide1,
        $cn_omoide2, $cn_omoide3, $cn_omoide4, $cn_omoide5, $cn_total_aijou, $cn_yobi1, $cn_yobi2, $cn_yobi3,
        $cn_yobi4, $cn_yobi5, $couple_file, $date2, $date_sec, $house_type, $in, $k_id, $k_temp, $koibito_seigen,
        $last_mail_check_time, $love, $m_comment, $mail_cont, $message_file, $my_last_mail_check_time, $my_log_file,
        $my_m_comment, $my_mail_cont, $my_sousin_file, $name, $need_love, $new_mail, $pro_name, $pro_sex, $return_id,
        $sinkon_number, $sousin_mail, $zibun_count, $zibunatta_flg;
    if ((n($love) < n($need_love))) {
        error('LOVEパラメータが足らないためメールを送ることはできません。');
    }
    if (($in['sousinsaki_name'] == '')) {
        error('相手先のお名前の入力がありません');
    }
    if (($in['m_com'] == '')) {
        error('メッセージが入力されていません');
    }
    $aite_name = (string)($in['sousinsaki_name']);
    lock(...$_args);
    // エラーチェック処理
    if (!(pl_open('COA', (string)($couple_file)))) { error("{$couple_file}に書き込めません"); }
    $all_couple = pl_readlines('COA');
    pl_close('COA');
    $zibun_count = 0;
    $aite_count = 0;
    $__s1 = $_ ?? null;
    foreach ($all_couple ?? [] as $_) {
        [$cn_number, $cn_name1, $cn_name2, $cn_joutai, $cn_total_aijou, $cn_omoide1, $cn_omoide2, $cn_omoide3, $cn_omoide4, $cn_omoide5, $cn_kodomo, $cn_yobi1, $cn_yobi2, $cn_yobi3, $cn_yobi4, $cn_yobi5] = pl_pad(pl_split('/<>/u', $_), 16);
        // cn_yobi1＝次にデートできる日時　cn_yobi2＝最後にしたデート（日時）　cn_yobi3＝その前のデート（日時）cn_yobi4＝最後のデートを誘った人　cn_kodomo＝未使用
        if (((string)$name === (string)(string)($cn_name1))) {
            // 結婚OKの場合
            if (($in['command'] == 'propose_ok')) {
                if (((string)$cn_name2 === (string)(string)($aite_name))) {
                    if (($cn_joutai == '配偶者')) {
                        error('この方はすでにあなたの配偶者です。');
                    }
                    $sinkon_number = (string)($cn_number);
                }
                // プロポーズの場合、恋人であるエラーを回避
            } elseif (($in['kokuhaku_syubetu'] == 'propose')) {
                // 交際申し込み、交際OKの場合
            } else {
                $zibun_count++;
                if (((string)$cn_name2 === (string)(string)($aite_name))) {
                    error("この方はすでにあなたの{$cn_joutai}です。");
                }
            }
        }
        if (((string)$name === (string)(string)($cn_name2))) {
            // 結婚OKの場合
            if (($in['command'] == 'propose_ok')) {
                if (((string)$cn_name1 === (string)(string)($aite_name))) {
                    if (($cn_joutai == '配偶者')) {
                        error('この方はすでにあなたの配偶者です。');
                    }
                    $sinkon_number = (string)($cn_number);
                }
                // プロポーズの場合、恋人であるエラーを回避
            } elseif (($in['kokuhaku_syubetu'] == 'propose')) {
                // 交際OKの場合
            } else {
                $zibun_count++;
                if (((string)$cn_name1 === (string)(string)($aite_name))) {
                    error("この方はすでにあなたの{$cn_joutai}です。");
                }
            }
        }
        // 相手の名前があった場合
        if ((((string)$aite_name === (string)(string)($cn_name1)) || ((string)$aite_name === (string)(string)($cn_name2)))) {
            // 結婚OKで相手が結婚していたらエラー表示
            if ((($in['command'] == 'propose_ok') && ($cn_joutai == '配偶者'))) {
                error("この方は既に結婚しています。\t");
            }
            // プロポーズの場合、相手が結婚していたらエラー表示
            if ((($in['kokuhaku_syubetu'] == 'propose') && ($cn_joutai == '配偶者'))) {
                error("この方は既に結婚しています。\t");
            }
            $aite_count++;
        }
    }
    $_ = $__s1;
    // foreach閉じ
    // 結婚OKの場合、恋人かどうかのチェック
    if (($in['command'] == 'propose_ok')) {
        if (($sinkon_number == '')) {
            error('二人は恋人ではありません。');
        }
        // プロポーズの場合、恋人であるエラーを回避		#ver.1.30
    } elseif (($in['kokuhaku_syubetu'] == 'propose')) {
        // ver.1.30
        // 交際申し込み、交際OKの場合の恋人人数チェック
    } else {
        if ((n($zibun_count) >= n($koibito_seigen))) {
            error("{$name}さんはすでに{$koibito_seigen}人の方とつき合っているため新たに恋人は作れません。");
        }
        if ((n($aite_count) >= n($koibito_seigen))) {
            error("{$aite_name}さんはすでに{$koibito_seigen}人の方とつき合っているため新たに恋人は作れません。");
        }
    }
    if (!(pl_open('PR', (string)($as_profile_file)))) { error("Open Error : {$as_profile_file}"); }
    $alldata = pl_readlines('PR');
    pl_close('PR');
    $zibunatta_flg = 0;
    $aiteatta_flg = 0;
    $__s2 = $_ ?? null;
    foreach ($alldata ?? [] as $_) {
        [$pro_name, $pro_sex] = pl_pad(pl_split('/<>/u', $_), 2);
        if (((string)$name === (string)(string)($pro_name))) {
            $zibunatta_flg = 1;
        }
        if (((string)$aite_name === (string)(string)($pro_name))) {
            $aiteatta_flg = 1;
        }
    }
    $_ = $__s2;
    if ((n($zibunatta_flg) == 0)) {
        error("{$name}さんは恋人斡旋所に登録されていません。");
    }
    if ((n($aiteatta_flg) == 0)) {
        error("{$aite_name}さんは恋人斡旋所に登録されていません。");
    }
    // 告白メール送信
    id_check($aite_name);
    if (((string)$aite_name === (string)$name)) {
        error('自分に告白することはできません');
    }
    $message_file = "./member/{$return_id}/mail.cgi";
    if (!(pl_open('AIT', (string)($message_file)))) { error("お相手の方のメール記録ファイル（{$message_file}）が開けません。"); }
    $last_mail_check_time = pl_readline('AIT');
    $mail_cont = pl_readlines('AIT');
    pl_close('AIT');
    // <>禁止処理
    $in['m_com'] = preg_replace('/<>/u', '&lt;&gt;', (string)$in['m_com'], -1);
    // コメントの改行処理
    $in['m_com'] = preg_replace('/\\r\\n/u', '<br>', (string)$in['m_com'], -1);
    $in['m_com'] = preg_replace('/\\r/u', '<br>', (string)$in['m_com'], -1);
    $in['m_com'] = preg_replace('/\\n/u', '<br>', (string)$in['m_com'], -1);
    $m_comment = $in['m_com'];
    time_get(...$_args);
    if (($in['command'] == 'kousai_ok')) {
        $new_mail = "交際承諾受信<>{$in['name']}<>{$m_comment}<>{$date2}<>{$date_sec}<><><><><><>\n";
    } elseif (($in['command'] == 'propose_ok')) {
        $new_mail = "プロポ承諾受信<>{$in['name']}<>{$m_comment}<>{$date2}<>{$date_sec}<><><><><><>\n";
    } elseif (($in['kokuhaku_syubetu'] == 'propose')) {
        $new_mail = "プロポ受信<>{$in['name']}<>{$m_comment}<>{$date2}<>{$date_sec}<><><><><><>\n";
    } else {
        $new_mail = "告白受信<>{$in['name']}<>{$m_comment}<>{$date2}<>{$date_sec}<><><><><><>\n";
    }
    pl_unshift($mail_cont, $new_mail);
    // 最終メールチェック時間がなければ１を入れる
    if (($last_mail_check_time == '')) {
        $last_mail_check_time = "1\n";
    }
    pl_unshift($mail_cont, $last_mail_check_time);
    // 自分のメールにも送信済みメッセージとして記録（管理者メールでなければ）
    if (($in['command'] != 'from_system')) {
        $my_sousin_file = "./member/{$k_id}/mail.cgi";
        if (!(pl_open('ZIB', (string)($my_sousin_file)))) { error("{$my_sousin_file}が開けません。"); }
        $my_last_mail_check_time = pl_readline('ZIB');
        $my_mail_cont = pl_readlines('ZIB');
        pl_close('ZIB');
        $my_m_comment = $in['m_com'];
        if (($in['command'] == 'kousai_ok')) {
            $sousin_mail = "交際承諾送信<>{$aite_name}<>{$my_m_comment}<>{$date2}<><><><><><><>\n";
        } elseif (($in['command'] == 'propose_ok')) {
            $sousin_mail = "プロポ承諾送信<>{$aite_name}<>{$my_m_comment}<>{$date2}<><><><><><><>\n";
        } elseif (($in['kokuhaku_syubetu'] == 'propose')) {
            $sousin_mail = "プロポ送信<>{$aite_name}<>{$my_m_comment}<>{$date2}<><><><><><><>\n";
        } else {
            $sousin_mail = "告白送信<>{$aite_name}<>{$my_m_comment}<>{$date2}<><><><><><><>\n";
        }
        pl_unshift($my_mail_cont, $sousin_mail);
        // 最終メールチェック時間がなければ今の時間を入れる
        if (($my_last_mail_check_time == '')) {
            $my_last_mail_check_time = "{$date_sec}\n";
        }
        pl_unshift($my_mail_cont, $my_last_mail_check_time);
        // 相手のメールログへの書き込み処理
        if (!(pl_open('OUT', ">{$message_file}"))) { error("{$message_file}に書き込めません"); }
        pl_print('OUT', pl_join('', $mail_cont));
        pl_close('OUT');
        // 自分のメールログへ書き込み処理
        if (!(pl_open('ZIBO', ">{$my_sousin_file}"))) { error("{$my_sousin_file}に書き込めません"); }
        pl_print('ZIBO', pl_join('', $my_mail_cont));
        pl_close('ZIBO');
        // 交際返事の場合の恋愛ログ更新
        if (($in['command'] == 'kousai_ok')) {
            couple_kiroku($name, $aite_name, '恋人', '');
            // 名前１,名前２,状態,カップル番号
            // 街のニュースに記録
            news_kiroku('恋人', "{$name}さんと{$aite_name}さんが恋人になりました。");
            // 結婚返事の場合の恋愛ログ更新
        } elseif (($in['command'] == 'propose_ok')) {
            couple_kiroku($name, $aite_name, '結婚', (string)($sinkon_number));
            news_kiroku('結婚', "{$name}さんと{$aite_name}さんが結婚しました。");
            // 相手の個人ログの配偶者IDに自分のIDを記録
            id_check($aite_name);
            openAitelog($return_id);
            $aite_house_type = (string)($k_id);
            aite_temp_routin(...$_args);
            if (!(pl_open('OUT', ">{$aite_log_file}"))) { error("{$aite_log_file}が開けません"); }
            pl_print('OUT', $aite_k_temp);
            pl_close('OUT');
        }
    }
    unlock(...$_args);
    // 結婚の場合自分の個人ログに配偶者IDを記録
    if (($in['command'] == 'propose_ok')) {
        $house_type = (string)($return_id);
        temp_routin(...$_args);
        log_kousin($my_log_file, $k_temp);
    }
    if (($in['command'] == 'kousai_ok')) {
        message_only("{$aite_name}さんと恋人になりました。");
        hooter('mail_sousin', 'メール画面へ');
        hooter('login_view', '戻る');
    } elseif (($in['command'] == 'propose_ok')) {
        message_only("{$aite_name}さんと結婚しました。");
        hooter('mail_sousin', 'メール画面へ');
        hooter('login_view', '戻る');
    } elseif (($in['kokuhaku_syubetu'] == 'propose')) {
        message_only("{$aite_name}さんにプロポーズしました。");
        hooter('renai', '戻る', 'kekkon.php');
    } else {
        message_only("{$aite_name}さんに告白しました。");
        hooter('assenjo', '戻る', 'kekkon.php');
    }
    exit;
}

function couple_kiroku(...$_args)
{
    global $_, $all_couple, $cn_joutai, $cn_kodomo, $cn_name1, $cn_name2, $cn_number, $cn_omoide1, $cn_omoide2,
        $cn_omoide3, $cn_omoide4, $cn_omoide5, $cn_total_aijou, $cn_yobi1, $cn_yobi2, $cn_yobi3, $cn_yobi4,
        $cn_yobi5, $couple_file, $couple_news, $couple_tmp, $last_couple, $new_all_couple, $new_cn_number,
        $new_couple_tmp, $now_time;
    // 結婚成立の場合
    if (($_args[2] == '結婚')) {
        if (!(pl_open('COA', (string)($couple_file)))) { error("{$couple_file}に書き込めません"); }
        $all_couple = pl_readlines('COA');
        pl_close('COA');
        $__s1 = $_ ?? null;
        foreach ($all_couple ?? [] as $_) {
            [$cn_number, $cn_name1, $cn_name2, $cn_joutai, $cn_total_aijou, $cn_omoide1, $cn_omoide2, $cn_omoide3, $cn_omoide4, $cn_omoide5, $cn_kodomo, $cn_yobi1, $cn_yobi2, $cn_yobi3, $cn_yobi4, $cn_yobi5] = pl_pad(pl_split('/<>/u', $_), 16);
            if (((string)$_args[3] === (string)(string)($cn_number))) {
                $cn_joutai = '配偶者';
            }
            $couple_tmp = "{$cn_number}<>{$cn_name1}<>{$cn_name2}<>{$cn_joutai}<>{$cn_total_aijou}<>{$cn_omoide1}<>{$cn_omoide2}<>{$cn_omoide3}<>{$cn_omoide4}<>{$cn_omoide5}<>{$cn_kodomo}<>{$cn_yobi1}<>{$cn_yobi2}<>{$cn_yobi3}<>{$cn_yobi4}<>{$cn_yobi5}<>\n";
            $new_all_couple[] = $couple_tmp;
        }
        $_ = $__s1;
        if (!(pl_open('COP', ">{$couple_file}"))) { error("{$couple_file}に書き込めません"); }
        pl_print('COP', pl_join('', $new_all_couple));
        pl_close('COP');
        // 恋人成立の場合
    } elseif (($_args[2] == '恋人')) {
        if (!(pl_open('CO', (string)($couple_file)))) { error("{$couple_file}に書き込めません"); }
        $last_couple = pl_readline('CO');
        $couple_news = pl_readlines('CO');
        [$new_cn_number, $cn_name1, $cn_name2, $cn_joutai] = pl_pad(pl_split('/<>/u', $last_couple), 4);
        $new_cn_number++;
        pl_close('CO');
        $now_time = pl_time();
        $new_couple_tmp = "{$new_cn_number}<>{$_args[0]}<>{$_args[1]}<>恋人<>0<>0<>0<>0<>0<>0<>0<>{$now_time}<><><><><>\n";
        pl_unshift($couple_news, $last_couple);
        pl_unshift($couple_news, $new_couple_tmp);
        if (!(pl_open('COP', ">{$couple_file}"))) { error("{$couple_file}に書き込めません"); }
        pl_print('COP', pl_join('', $couple_news));
        pl_close('COP');
    }
}

function bykeys(...$_args)
{
    global $a, $b, $keys;
    return (n($keys[n($b)]) <=> n($keys[n($a)]));
}

function renai(...$_args)
{
    global $_, $aijou_kijun, $aitenonamae, $all_couple, $all_couple_sort, $all_kodomo, $bank, $bh_full_date,
        $cn_joutai, $cn_kodomo, $cn_name1, $cn_name2, $cn_number, $cn_omoide1, $cn_omoide2, $cn_omoide3, $cn_omoide4,
        $cn_omoide5, $cn_total_aijou, $cn_yobi1, $cn_yobi2, $cn_yobi3, $cn_yobi4, $cn_yobi5, $couple_file, $data,
        $date2, $date_aite_iru_flg, $date_comment, $date_hairetu, $date_sec, $date_sec_tmp, $date_tmp, $dekityatta,
        $haiguusya_name, $in, $k_id, $k_temp, $key, $keys, $ko_randed, $kod_name, $kod_num, $kodomo_file,
        $kodomo_kakuritu1, $kodomo_kakuritu2, $koibito_iru, $koibito_name, $kosodate_kankaku, $last_kodomo,
        $message_win, $money, $monokiroku_file, $my_kounyuu_list, $my_last_mail_check_time, $my_log_file,
        $my_mail_cont, $my_sousin_file, $name, $new_all_couple, $new_kodomo_temp, $next_d, $omoide_kijun,
        $sasoi_aite_folo, $script, $siharai_houhou, $sousin_mail, $syo_hinmoku, $syo_kouka, $syo_kounyuubi,
        $syo_taikyuu, $this_script, $top_koumoku, $wakare_limit_haiguu, $wakare_limit_koibito;
    if (!(pl_open('COA', (string)($couple_file)))) { error("{$couple_file}に書き込めません"); }
    $all_couple = pl_readlines('COA');
    pl_close('COA');
    if (!(pl_open('REN', './dat_dir/love.dat'))) { error('Open Error : ./dat_dir/love.dat'); }
    $top_koumoku = pl_readline('REN');
    $date_hairetu = pl_readlines('REN');
    pl_close('REN');
    $__t1 = pl_split('/<>/u', $top_koumoku); $koumokumei_hairetu = array_slice($__t1, 0);
    // デートの場合
    if (($in['command'] == 'do_date')) {
        $dekityatta = 0;
        $date_aite_iru_flg = 0;
        $__s2 = $_ ?? null;
        foreach ($all_couple ?? [] as $_) {
            [$cn_number, $cn_name1, $cn_name2, $cn_joutai, $cn_total_aijou, $cn_omoide1, $cn_omoide2, $cn_omoide3, $cn_omoide4, $cn_omoide5, $cn_kodomo, $cn_yobi1, $cn_yobi2, $cn_yobi3, $cn_yobi4, $cn_yobi5] = pl_pad(pl_split('/<>/u', $_), 16);
            if (((string)$cn_number === (string)(string)($in['date_aite']))) {
                $date_aite_iru_flg = 1;
                if (((string)$name === (string)(string)($cn_yobi4))) {
                    error('次は相手の方がデートに誘う番です。');
                }
                time_get(...$_args);
                if ((n($cn_yobi1) > n($date_sec))) {
                    error('まだデートできません。');
                }
                if (((string)$name === (string)(string)($cn_name1))) {
                    $aitenonamae = (string)($cn_name2);
                } else {
                    $aitenonamae = (string)($cn_name1);
                }
                $__s3 = $_ ?? null;
                foreach ($date_hairetu ?? [] as $_) {
                    [$date_name, $date_dousi, $date_tanosii, $date_h, $date_omosiroi, $date_kandou, $date_oisii, $date_hiyou, $date_kankaku, $kodomo_kakuritu] = pl_pad(pl_split('/<>/u', $_), 10);
                    if (((string)$date_name === (string)(string)($in['date_koumoku']))) {
                        $date_comment .= "{$aitenonamae}さんと{$date_dousi}<br>";
                        if ($date_tanosii) {
                            $cn_omoide1 = n($cn_omoide1) + n($date_tanosii);
                            $date_comment .= "{$koumokumei_hairetu[2]}が{$date_tanosii}増えました。<br>";
                        }
                        if ($date_h) {
                            $cn_omoide2 = n($cn_omoide2) + n($date_h);
                            $date_comment .= "{$koumokumei_hairetu[3]}が{$date_h}増えました。<br>";
                        }
                        if ($date_omosiroi) {
                            $cn_omoide3 = n($cn_omoide3) + n($date_omosiroi);
                            $date_comment .= "{$koumokumei_hairetu[4]}が{$date_omosiroi}増えました。<br>";
                        }
                        if ($date_kandou) {
                            $cn_omoide4 = n($cn_omoide4) + n($date_kandou);
                            $date_comment .= "{$koumokumei_hairetu[5]}が{$date_kandou}増えました。<br>";
                        }
                        if ($date_oisii) {
                            $cn_omoide5 = n($cn_omoide5) + n($date_oisii);
                            $date_comment .= "{$koumokumei_hairetu[6]}が{$date_oisii}増えました。<br>";
                        }
                        if (($in['siharaihouhou'] != '現金')) {
                            $bank = n($bank) - n($date_hiyou);
                            kityou_syori('クレジット支払い（デート費用）', (string)($date_hiyou), '', $bank, '普');
                        } else {
                            if ((n($money) < n($date_hiyou))) {
                                error('お金が足りません');
                            }
                            $money = n($money) - n($date_hiyou);
                        }
                        $cn_yobi1 = (n($date_sec) + (((60 * 60) * n($date_kankaku))));
                        // 次にデートできる時間
                        $cn_yobi3 = (string)($cn_yobi2);
                        // 一つ前のデート
                        $cn_yobi2 = "{$date_dousi}（{$date2}）";
                        // 最後のデート
                        // 子供が生まれるイベント
                        if ((n($kodomo_kakuritu) == 1)) {
                            if (($cn_joutai == '配偶者')) {
                                $ko_randed = pl_int((pl_rand(n($kodomo_kakuritu1)) + 1));
                            } elseif (($cn_joutai == '恋人')) {
                                $ko_randed = pl_int((pl_rand(n($kodomo_kakuritu2)) + 1));
                            }
                            if ((n($ko_randed) == 1)) {
                                // 子供が生まれた場合
                                $dekityatta = 1;
                                $cn_yobi2 = "{$date_dousi}<span class=\"mainasu\">子供ができました！</span>（{$date2}）";
                                $date_comment .= '<div class="mainasu">おめでとうございます！<br>二人の間に子供ができました！<br>子供の名前を決めるための書類をメールでお送りしましたのでご覧ください。</div>';
                            }
                        }
                        $cn_yobi4 = (string)($name);
                        // 誘った人
                        $cn_total_aijou = ((((n($cn_omoide1) + n($cn_omoide2)) + n($cn_omoide3)) + n($cn_omoide4)) + n($cn_omoide5));
                        break;
                    }
                    // デート内容一致の場合の閉じ
                }
                $_ = $__s3;
                // デート内容foreachの閉じ
            }
            // カップル番号一致の閉じ
            $date_tmp = "{$cn_number}<>{$cn_name1}<>{$cn_name2}<>{$cn_joutai}<>{$cn_total_aijou}<>{$cn_omoide1}<>{$cn_omoide2}<>{$cn_omoide3}<>{$cn_omoide4}<>{$cn_omoide5}<>{$cn_kodomo}<>{$cn_yobi1}<>{$cn_yobi2}<>{$cn_yobi3}<>{$cn_yobi4}<>{$cn_yobi5}<>\n";
            $new_all_couple[] = $date_tmp;
        }
        $_ = $__s2;
        // カップル番号foreach閉じ
        if ((n($date_aite_iru_flg) == 0)) {
            error('デート相手が見つかりません。');
        }
        // ログ更新
        lock(...$_args);
        // 子供ができた場合
        if ((n($dekityatta) == 1)) {
            time_get(...$_args);
            if (!(pl_open('KOD', (string)($kodomo_file)))) { error("Open Error : {$kodomo_file}"); }
            $last_kodomo = pl_readline('KOD');
            $all_kodomo = pl_readlines('KOD');
            pl_close('KOD');
            [$kod_num, $kod_name] = pl_pad(pl_split('/<>/u', $last_kodomo), 2);
            $kod_num++;
            $date_sec_tmp = (n($date_sec) - (((60 * 60) * n($kosodate_kankaku))));
            $new_kodomo_temp = "{$kod_num}<><>{$name}<>{$aitenonamae}<><>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>0<>{$date_sec_tmp}<>{$date_sec_tmp}<><><>50<>3<>{$date_sec_tmp}<><><><>\n";
            pl_unshift($all_kodomo, $last_kodomo);
            pl_unshift($all_kodomo, $new_kodomo_temp);
            // 子供の名前を決めるメール送信
            $my_sousin_file = "./member/{$k_id}/mail.cgi";
            if (!(pl_open('ZIB', (string)($my_sousin_file)))) { error("{$my_sousin_file}が開けません。"); }
            $my_last_mail_check_time = pl_readline('ZIB');
            $my_mail_cont = pl_readlines('ZIB');
            pl_close('ZIB');
            $sousin_mail = "出産<>{$aitenonamae}<>{$kod_num}<>{$date2}<>{$date_sec}<><><><><><>\n";
            pl_unshift($my_mail_cont, $sousin_mail);
            pl_unshift($my_mail_cont, $my_last_mail_check_time);
            // メールログ更新
            if (!(pl_open('ZIBO', ">{$my_sousin_file}"))) { error("{$my_sousin_file}に書き込めません"); }
            pl_print('ZIBO', pl_join('', $my_mail_cont));
            pl_close('ZIBO');
            // 子供ログ更新
            if (!(pl_open('KODO', ">{$kodomo_file}"))) { error("{$kodomo_file}に書き込めません"); }
            pl_print('KODO', pl_join('', $all_kodomo));
            pl_close('KODO');
        }
        // 子供ができた場合閉じ
        // カップルログ更新
        if (!(pl_open('COP', ">{$couple_file}"))) { error("{$couple_file}に書き込めません"); }
        pl_print('COP', pl_join('', $new_all_couple));
        pl_close('COP');
        unlock(...$_args);
        // 個人ログ更新
        temp_routin(...$_args);
        log_kousin($my_log_file, $k_temp);
        t_header('', 'sonomati');
        echo <<<EOM
        		<div align="center"><br><table  border="0"  cellspacing="5" cellpadding="0" width="300" style="{$message_win}"><tr><td>
        	<span class="job_messe">
        	{$date_comment}
        	</span>
        	</td></tr></table>
        	<br>
        EOM;
        hooter('renai', '恋愛画面へ', 'kekkon.php');
        hooter('login_view', '戻る');
        exit;
    }
    // デートの場合閉じ
    // ラブラブ度順にソート
    $__s4 = $_ ?? null;
    foreach ($all_couple ?? [] as $_) {
        $data = $_;
        $key = pl_idx(pl_split('/<>/u', $data), 4);
        // ソートする要素を選ぶ
        $all_couple_sort[] = $data;
        $keys[] = $key;
    }
    $_ = $__s4;
    $all_couple_sort = pl_slice($all_couple_sort, pl_sort('bykeys', pl_range(0, (pl_count($all_couple_sort) - 1))));
    t_header('assen_style');
    echo <<<EOM
    <table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr>
    	<td bgcolor="#ffffff">●恋人や配偶者ができるとこちらでデートすることができます。ただし、デートは二人が交互に誘う必要があります。<br>
    	●デートによって二人の思い出を増やしていき、ラブラブ度（思い出数値の合計）が{$aijou_kijun}を超え、さらに全ての思い出数値が最低基準の{$omoide_kijun}を超えていれば結婚の申し込みができるようになります。<br>
    	●自分、またはお相手に配偶者がいる場合は結婚できません。<br>
    	●結婚後は、配偶者が住んでいる家の各種設定やお店の仕入れなどを共同でできるようになります（二人とも家を持っている場合も、家を手放す必要はありません）。<br>
    	●恋人の場合{$wakare_limit_koibito}日間、配偶者の場合は{$wakare_limit_haiguu}日間デートをしないと二人は別れてしまいます。
    	</td>
    	<td  bgcolor="#ff6633" align="center" width="35%"><div style="font-size:13px; color:#ffffff">恋　愛</div>
    	<div align="center"><form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="assenjo">
    	<input type="hidden" name="command" value="couple_ranking">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value="カップルランキング">
    	</form></div>
    	</td></tr></table><br>
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="renai">
    	<input type="hidden" name="command" value="do_date">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr bgcolor="#ffcc66">
    	<td colspan="3" class="honbun3">お相手とデート内容を選んでください。かっこ内は「かかる費用」と「次にデートできるまでの時間」です。<br>
    	デートの内容によっては子供ができることがあります（相手が配偶者なら{$kodomo_kakuritu1}分の１、恋人なら{$kodomo_kakuritu2}分の１の確率）。</td></tr>
    	<tr bgcolor="#ffcc66"><td align="right">
    	お相手 <select name="date_aite">
    EOM;
    $my_koibito_hairetu = [];
    $my_haiguusya_hairetu = null;
    $koibito_iru = 0;
    $__s5 = $_ ?? null;
    foreach ($all_couple_sort ?? [] as $_) {
        [$cn_number, $cn_name1, $cn_name2, $cn_joutai, $cn_total_aijou, $cn_omoide1, $cn_omoide2, $cn_omoide3, $cn_omoide4, $cn_omoide5, $cn_kodomo, $cn_yobi1, $cn_yobi2, $cn_yobi3, $cn_yobi4, $cn_yobi5] = pl_pad(pl_split('/<>/u', $_), 16);
        if (((string)$name === (string)(string)($cn_name1))) {
            if (($cn_joutai == '恋人')) {
                $my_koibito_hairetu[] = $_;
                echo "<option value=\"{$cn_number}\">{$cn_name2}</option>";
            } elseif (($cn_joutai == '配偶者')) {
                $my_haiguusya_hairetu = (string)($_);
                echo "<option value=\"{$cn_number}\">{$cn_name2}</option>";
            }
            $koibito_iru++;
        }
        if (((string)$name === (string)(string)($cn_name2))) {
            if (($cn_joutai == '恋人')) {
                $my_koibito_hairetu[] = $_;
                echo "<option value=\"{$cn_number}\">{$cn_name1}</option>";
            } elseif (($cn_joutai == '配偶者')) {
                $my_haiguusya_hairetu = (string)($_);
                echo "<option value=\"{$cn_number}\">{$cn_name1}</option>";
            }
            $koibito_iru++;
        }
    }
    $_ = $__s5;
    if ((n($koibito_iru) == 0)) {
        echo '<option value="">現在つき合っている方はいません</option>';
    }
    echo <<<EOM
    	</selct></td><td align="center">
    	デート内容 <select name="date_koumoku">
    EOM;
    $__s6 = $_ ?? null;
    foreach ($date_hairetu ?? [] as $_) {
        [$date_name, $date_dousi, $date_tanosii, $date_h, $date_omosiroi, $date_kandou, $date_oisii, $date_hiyou, $date_kankaku] = pl_pad(pl_split('/<>/u', $_), 9);
        echo <<<EOM
        	<option value="{$date_name}">{$date_name}（{$date_hiyou}円、{$date_kankaku}時間）</option>
        EOM;
    }
    $_ = $__s6;
    echo '</select></td><td>';
    // 所有物チェック
    $monokiroku_file = "./member/{$k_id}/mono.cgi";
    if (!(pl_open('MK', (string)($monokiroku_file)))) { error('自分の購入物ファイルが開けません'); }
    $my_kounyuu_list = pl_readlines('MK');
    pl_close('MK');
    $__s7 = $_ ?? null;
    foreach ($my_kounyuu_list ?? [] as $_) {
        syouhin_sprit($_);
        if (($syo_kouka == 'クレジット')) {
            if ((n($syo_taikyuu) - (pl_int((((pl_time() - n($syo_kounyuubi))) / (((60 * 60) * 24))))))) {
                $siharai_houhou .= "<option value=\"{$syo_hinmoku}\">{$syo_hinmoku}</option>";
            }
        }
    }
    $_ = $__s7;
    echo <<<EOM
    	支払い <select name="siharaihouhou"><option value="現金">現金</option>{$siharai_houhou}</select>
    	<input type="submit" value=" O K ">
    	</td></tr>
    	</table></form>
    EOM;
    // 配偶者の表示
    if (($my_haiguusya_hairetu != '')) {
        [$cn_number, $cn_name1, $cn_name2, $cn_joutai, $cn_total_aijou, $cn_omoide1, $cn_omoide2, $cn_omoide3, $cn_omoide4, $cn_omoide5, $cn_kodomo, $cn_yobi1, $cn_yobi2, $cn_yobi3, $cn_yobi4, $cn_yobi5] = pl_pad(pl_split('/<>/u', $my_haiguusya_hairetu), 16);
        if (((string)$cn_name1 === (string)$name)) {
            $haiguusya_name = $cn_name2;
        } else {
            $haiguusya_name = $cn_name1;
        }
        if ((n($cn_yobi1) < pl_time())) {
            $next_d = '<span class="purasu"> O K </span>';
        } else {
            byou_hiduke($cn_yobi1);
            $next_d = "<span class=\"mainasu\">{$bh_full_date}</span>";
        }
        echo <<<EOM
        	<div class="honbun4" align="center">＜{$name}さんの配偶者＞</div>
        	<table width="90%" border="0" cellspacing="0" cellpadding="6" align="center" class="yosumi">
        	<tr bgcolor="#ffffaa">
        	<td style="font-size:12px;color:ff3300" width="90" nowrap>{$haiguusya_name}</td>
        	<td><span class="honbun2">ラブラブ度：{$cn_total_aijou}</span></td>
        	<td><span class="honbun2">{$koumokumei_hairetu[2]}：</span>{$cn_omoide1}</td>
        	<td><span class="honbun2">{$koumokumei_hairetu[3]}：</span>{$cn_omoide2}</td>
        	<td><span class="honbun2">{$koumokumei_hairetu[4]}：</span>{$cn_omoide3}</td>
        	<td><span class="honbun2">{$koumokumei_hairetu[5]}：</span>{$cn_omoide4}</td>
        	<td><span class="honbun2">{$koumokumei_hairetu[6]}：</span>{$cn_omoide5}</td></tr>
        	<tr bgcolor="#ffffaa"><td colspan="7"><hr size="1"><span class="honbun2">次にデートできる時間：</span>{$next_d}<br>
        	<span class="honbun2">最後にしたデート：</span>{$cn_yobi2}<span class="purasu">{$cn_yobi4}さんが誘いました。</span><br>
        	<span class="honbun2">ひとつ前のデート：</span>{$cn_yobi3}<br>
        	<form method="POST" action="{$script}">
        	<input type="hidden" name="mode" value="mail_do">
        	<input type="hidden" name="name" value="{$name}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="id" value="{$k_id}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<input type="hidden" name="sousinsaki_name" value="{$haiguusya_name}">
        	メッセージ <input type="text" size="64" name="m_com">
        	<input type="submit" value=" メールを送る ">
        	</form>
        	</td></tr></table><br><br>
        EOM;
    }
    // 配偶者がいる場合の閉じ
    // 恋人の表示
    if ((pl_count($my_koibito_hairetu) != 0)) {
        echo "<div class=\"honbun4\" align=\"center\">＜{$name}さんの恋人＞</div>";
    }
    $__s8 = $_ ?? null;
    foreach ($my_koibito_hairetu ?? [] as $_) {
        [$cn_number, $cn_name1, $cn_name2, $cn_joutai, $cn_total_aijou, $cn_omoide1, $cn_omoide2, $cn_omoide3, $cn_omoide4, $cn_omoide5, $cn_kodomo, $cn_yobi1, $cn_yobi2, $cn_yobi3, $cn_yobi4, $cn_yobi5] = pl_pad(pl_split('/<>/u', $_), 16);
        if (((string)$cn_name1 === (string)$name)) {
            $koibito_name = $cn_name2;
        } else {
            $koibito_name = $cn_name1;
        }
        if ((n($cn_yobi1) < pl_time())) {
            $next_d = '<span class="purasu"> O K </span>';
        } else {
            byou_hiduke($cn_yobi1);
            $next_d = "<span class=\"mainasu\">{$bh_full_date}</span>";
        }
        if (($cn_yobi4 != '')) {
            $sasoi_aite_folo = 'さんが誘いました。';
        } else {
            $sasoi_aite_folo = '';
        }
        echo <<<EOM
        	<table width="90%" border="0" cellspacing="0" cellpadding="6" align="center" class="yosumi">
        	<tr bgcolor="#ffffff">
        	<td style="font-size:12px;color:ff3300" width="90" nowrap>{$koibito_name}</td>
        	<td><span class="honbun2">ラブラブ度：{$cn_total_aijou}</span></td>
        	<td><span class="honbun2">{$koumokumei_hairetu[2]}：</span>{$cn_omoide1}</td>
        	<td><span class="honbun2">{$koumokumei_hairetu[3]}：</span>{$cn_omoide2}</td>
        	<td><span class="honbun2">{$koumokumei_hairetu[4]}：</span>{$cn_omoide3}</td>
        	<td><span class="honbun2">{$koumokumei_hairetu[5]}：</span>{$cn_omoide4}</td>
        	<td><span class="honbun2">{$koumokumei_hairetu[6]}：</span>{$cn_omoide5}</td></tr>
        	<tr><td colspan="7"><hr size="1"><span class="honbun2">次にデートできる時間：</span>{$next_d}<br>
        	<span class="honbun2">最後にしたデート：</span>{$cn_yobi2}<span class="purasu">{$cn_yobi4}{$sasoi_aite_folo}</span><br>
        	<span class="honbun2">ひとつ前のデート：</span>{$cn_yobi3}<br>
        	<form method="POST" action="{$script}">
        	<input type="hidden" name="mode" value="mail_do">
        	<input type="hidden" name="name" value="{$name}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="id" value="{$k_id}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<input type="hidden" name="sousinsaki_name" value="{$koibito_name}">
        	メッセージ <input type="text" size="64" name="m_com">
        	<input type="submit" value=" メールを送る ">
        	</form>
        	</td></tr>
        EOM;
        // ラブラブ度と思い出数値が基準を超えていたらプロポーズフォーム出力
        if ((((((((n($cn_total_aijou) >= n($aijou_kijun)) && (n($cn_omoide1) >= n($omoide_kijun))) && (n($cn_omoide2) >= n($omoide_kijun))) && (n($cn_omoide3) >= n($omoide_kijun))) && (n($cn_omoide4) >= n($omoide_kijun))) && (n($cn_omoide5) >= n($omoide_kijun))) && ($my_haiguusya_hairetu == ''))) {
            echo <<<EOM
            	 <tr bgcolor="#ffcccc"><td colspan="7"><form method="POST" action="{$this_script}">
            	<input type="hidden" name="mode" value="kokuhaku">
            	<input type="hidden" name="kokuhaku_syubetu" value="propose">
            	<input type="hidden" name="name" value="{$name}">
            	<input type="hidden" name="pass" value="{$in['pass']}">
            	<input type="hidden" name="id" value="{$k_id}">
            	<input type="hidden" name="town_no" value="{$in['town_no']}">
            	<input type="hidden" name="sousinsaki_name" value="{$koibito_name}">
            	<span class="mainasu">プロポーズの言葉</span> <input type="text" name="m_com" size="70">
            	<input type="submit" value="結婚を申し込む"></form>
            	</td></tr>
            EOM;
        }
        echo '</table><br><br>';
    }
    $_ = $__s8;
    hooter('login_view', '街へ戻る');
    exit;
}

function kodomo_naming(...$_args)
{
    global $_, $all_kodomo, $in, $kod_bijutu, $kod_eigo, $kod_etti, $kod_job, $kod_kenkou, $kod_kokugo,
        $kod_kyakuryoku, $kod_looks, $kod_love, $kod_name, $kod_num, $kod_ongaku, $kod_oya1, $kod_oya2, $kod_power,
        $kod_rika, $kod_speed, $kod_suugaku, $kod_syakai, $kod_tairyoku, $kod_unique, $kod_wanryoku, $kod_yobi1,
        $kod_yobi10, $kod_yobi2, $kod_yobi3, $kod_yobi4, $kod_yobi5, $kod_yobi6, $kod_yobi7, $kod_yobi8, $kod_yobi9,
        $kodomo_file, $kodomo_temp, $kodomoatta_flg, $message_in, $new_all_kodomo, $news_messe, $umu_news;
    if (!(pl_open('KOD', (string)($kodomo_file)))) { error("Open Error : {$kodomo_file}"); }
    $all_kodomo = pl_readlines('KOD');
    pl_close('KOD');
    $new_all_kodomo = [];
    $kodomoatta_flg = 0;
    $umu_news = '0';
    $__s1 = $_ ?? null;
    foreach ($all_kodomo ?? [] as $_) {
        [$kod_num, $kod_name, $kod_oya1, $kod_oya2, $kod_job, $kod_kokugo, $kod_suugaku, $kod_rika, $kod_syakai, $kod_eigo, $kod_ongaku, $kod_bijutu, $kod_looks, $kod_tairyoku, $kod_kenkou, $kod_speed, $kod_power, $kod_wanryoku, $kod_kyakuryoku, $kod_love, $kod_unique, $kod_etti, $kod_yobi1, $kod_yobi2, $kod_yobi3, $kod_yobi4, $kod_yobi5, $kod_yobi6, $kod_yobi7, $kod_yobi8, $kod_yobi9, $kod_yobi10] = pl_pad(pl_split('/<>/u', $_), 32);
        if (((string)$kod_num === (string)(string)($in['kod_num']))) {
            $kodomoatta_flg = 1;
            // 産む場合
            if (($in['umu_umanai'] == 'umu')) {
                if ((pl_length($in['kodomo_name']) > 20)) {
                    error('子供の名前は全角10文字以内でお願いします。');
                }
                if (($kod_name != '')) {
                    error('すでにこの子には名前がついています。');
                }
                $kod_name = (string)($in['kodomo_name']);
                $message_in = "「{$kod_name}」ちゃんが産まれました！";
                $kod_yobi1 = pl_time();
                $umu_news = '1';
                $news_messe = "{$kod_oya1}さんと{$kod_oya2}さんとの間に子供が産まれました。「{$kod_name}」ちゃんと命名したようです。";
                // 産まない場合削除
            } elseif (($in['umu_umanai'] == 'umanai')) {
                $message_in = '子供をおろしました。';
                $umu_news = '2';
                $news_messe = "{$kod_oya1}さんと{$kod_oya2}さんとの間に子供が産まれましたがおろしたようです。";
                continue;
            }
        }
        $kodomo_temp = "{$kod_num}<>{$kod_name}<>{$kod_oya1}<>{$kod_oya2}<>{$kod_job}<>{$kod_kokugo}<>{$kod_suugaku}<>{$kod_rika}<>{$kod_syakai}<>{$kod_eigo}<>{$kod_ongaku}<>{$kod_bijutu}<>{$kod_looks}<>{$kod_tairyoku}<>{$kod_kenkou}<>{$kod_speed}<>{$kod_power}<>{$kod_wanryoku}<>{$kod_kyakuryoku}<>{$kod_love}<>{$kod_unique}<>{$kod_etti}<>{$kod_yobi1}<>{$kod_yobi2}<>{$kod_yobi3}<>{$kod_yobi4}<>{$kod_yobi5}<>{$kod_yobi6}<>{$kod_yobi7}<>{$kod_yobi8}<>{$kod_yobi9}<>{$kod_yobi10}<>\n";
        $new_all_kodomo[] = $kodomo_temp;
    }
    $_ = $__s1;
    // foreach閉じ
    lock(...$_args);
    if ((n($kodomoatta_flg) == 0)) {
        error('この子供はすでに出産、または流産しています。');
    }
    // 子供ログ更新
    if (!(pl_open('KODO', ">{$kodomo_file}"))) { error("{$kodomo_file}に書き込めません"); }
    pl_print('KODO', pl_join('', $new_all_kodomo));
    pl_close('KODO');
    // 街ニュースに記録
    if ((n($umu_news) == 1)) {
        news_kiroku('出産', (string)($news_messe));
    } elseif ((n($umu_news) == 2)) {
        // &news_kiroku("死亡","$news_messe");
    }
    unlock(...$_args);
    message_only((string)($message_in));
    hooter('mail_sousin', 'メール画面へ');
    hooter('login_view', '戻る');
    exit;
}

function kosodate(...$_args)
{
    global $BMI, $_, $all_kodomo, $bh_tukihi, $bijutu, $date2, $date_sec, $eigo, $etti, $ima_kasegi, $in,
        $job_hairetu, $job_kihonkyuu, $job_kyuuyo, $job_name, $k_id, $k_temp, $kenkou, $kod_bijutu, $kod_eigo,
        $kod_etti, $kod_job, $kod_kenkou, $kod_kokugo, $kod_kyakuryoku, $kod_looks, $kod_love, $kod_name, $kod_num,
        $kod_ongaku, $kod_oya1, $kod_oya2, $kod_power, $kod_rika, $kod_speed, $kod_suugaku, $kod_syakai,
        $kod_taijuu_hue, $kod_tairyoku, $kod_unique, $kod_wanryoku, $kod_yobi1, $kod_yobi10, $kod_yobi2, $kod_yobi3,
        $kod_yobi4, $kod_yobi5, $kod_yobi6, $kod_yobi7, $kod_yobi8, $kod_yobi9, $kodomo_file, $kodomo_sibou_time,
        $kodomoiruka_flag, $kokugo, $kono_nenrei, $konoagatta_suuti, $konokonooya, $kosodate_kankaku, $kyakuryoku,
        $looks, $love, $message_in, $money, $monokiroku_file, $my_kounyuu_list, $my_log_file, $name,
        $nenreibetu_kakeritu, $new_all_kodomo, $new_kodomo_temp, $new_myitem_hairetu, $now_time, $ongaku, $power,
        $randed, $rika, $sintyouup, $sodate_taisyou_flg, $sogo_sisuu, $speed, $suugaku, $syakai, $syo_cal,
        $syo_hinmoku, $syo_kenkou, $syo_kyakuryoku, $syo_power, $syo_speed, $syo_syubetu, $syo_taikyuu,
        $syo_taikyuu_tani, $syo_tairyoku, $syo_temp, $syo_wanryoku, $syokuryou_form, $syokuryouattane_flag,
        $syouhinattaflg, $taikei, $tairyoku, $this_script, $top_koumoku, $unique, $wanryoku, $youikuhi, $youikuhiyou;
    if (!(pl_open('KOD', (string)($kodomo_file)))) { error("Open Error : {$kodomo_file}"); }
    $all_kodomo = pl_readlines('KOD');
    pl_close('KOD');
    $monokiroku_file = "./member/{$k_id}/mono.cgi";
    if (!(pl_open('MK', (string)($monokiroku_file)))) { error('自分の購入物ファイルが開けません'); }
    $my_kounyuu_list = pl_readlines('MK');
    pl_close('MK');
    // パラメータアップの場合
    if (($in['command'] == 'do_kosodate')) {
        $sodate_taisyou_flg = 0;
        $__s1 = $_ ?? null;
        foreach ($all_kodomo ?? [] as $_) {
            [$kod_num, $kod_name, $kod_oya1, $kod_oya2, $kod_job, $kod_kokugo, $kod_suugaku, $kod_rika, $kod_syakai, $kod_eigo, $kod_ongaku, $kod_bijutu, $kod_looks, $kod_tairyoku, $kod_kenkou, $kod_speed, $kod_power, $kod_wanryoku, $kod_kyakuryoku, $kod_love, $kod_unique, $kod_etti, $kod_yobi1, $kod_yobi2, $kod_yobi3, $kod_yobi4, $kod_yobi5, $kod_yobi6, $kod_yobi7, $kod_yobi8, $kod_yobi9, $kod_yobi10] = pl_pad(pl_split('/<>/u', $_), 32);
            // $kod_num<>$kod_name<>$kod_oya1<>$kod_oya2<>$kod_job<>$kod_kokugo<>$kod_suugaku<>$kod_rika<>$kod_syakai<>$kod_eigo<>$kod_ongaku<>$kod_bijutu<>$kod_looks<>$kod_tairyoku<>$kod_kenkou<>$kod_speed<>$kod_power<>$kod_wanryoku<>$kod_kyakuryoku<>$kod_love<>$kod_unique<>$kod_etti<>$kod_yobi1<>$kod_yobi2<>$kod_yobi3<>$kod_yobi4<>$kod_yobi5<>$kod_yobi6<>$kod_yobi7<>$kod_yobi8<>$kod_yobi9<>$kod_yobi10
            // kod_yobi1＝出産時間（秒）kod_yobi2＝最後に子育てした時間　kod_yobi3＝最後の子育てコメント　kod_yobi4＝トータル能力値　kod_yobi5＝身長　kod_yobi6＝体重　kod_yobi7＝最後の食事時間　kod_yobi8＝自立フラグ　kod_yobi9＝最後に子育てした人
            if (((string)$in['kod_num'] === (string)(string)($kod_num))) {
                $sodate_taisyou_flg = 1;
                time_get(...$_args);
                if ((((n($date_sec) - n($kod_yobi2))) < (((60 * 60) * n($kosodate_kankaku))))) {
                    error('まだ子育てできません。');
                }
                if (((string)$kod_yobi9 === (string)(string)($name))) {
                    error('次は配偶者の方が子育てする番です。');
                }
                $konoagatta_suuti = (n($in['par_suuti']) / 10);
                if (($in['nouryoku'] == '国語')) {
                    $kod_kokugo = n($kod_kokugo) + n($konoagatta_suuti);
                    $kokugo = n($kokugo) - n($in['par_suuti']);
                } elseif (($in['nouryoku'] == '数学')) {
                    $kod_suugaku = n($kod_suugaku) + n($konoagatta_suuti);
                    $suugaku = n($suugaku) - n($in['par_suuti']);
                } elseif (($in['nouryoku'] == '理科')) {
                    $kod_rika = n($kod_rika) + n($konoagatta_suuti);
                    $rika = n($rika) - n($in['par_suuti']);
                } elseif (($in['nouryoku'] == '社会')) {
                    $kod_syakai = n($kod_syakai) + n($konoagatta_suuti);
                    $syakai = n($syakai) - n($in['par_suuti']);
                } elseif (($in['nouryoku'] == '英語')) {
                    $kod_eigo = n($kod_eigo) + n($konoagatta_suuti);
                    $eigo = n($eigo) - n($in['par_suuti']);
                } elseif (($in['nouryoku'] == '音楽')) {
                    $kod_ongaku = n($kod_ongaku) + n($konoagatta_suuti);
                    $ongaku = n($ongaku) - n($in['par_suuti']);
                } elseif (($in['nouryoku'] == '美術')) {
                    $kod_bijutu = n($kod_bijutu) + n($konoagatta_suuti);
                    $bijutu = n($bijutu) - n($in['par_suuti']);
                } elseif (($in['nouryoku'] == '体力')) {
                    $kod_tairyoku = n($kod_tairyoku) + n($konoagatta_suuti);
                    $tairyoku = n($tairyoku) - n($in['par_suuti']);
                } elseif (($in['nouryoku'] == '健康')) {
                    $kod_kenkou = n($kod_kenkou) + n($konoagatta_suuti);
                    $kenkou = n($kenkou) - n($in['par_suuti']);
                } elseif (($in['nouryoku'] == 'スピード')) {
                    $kod_speed = n($kod_speed) + n($konoagatta_suuti);
                    $speed = n($speed) - n($in['par_suuti']);
                } elseif (($in['nouryoku'] == 'パワー')) {
                    $kod_power = n($kod_power) + n($konoagatta_suuti);
                    $power = n($power) - n($in['par_suuti']);
                } elseif (($in['nouryoku'] == '腕力')) {
                    $kod_wanryoku = n($kod_wanryoku) + n($konoagatta_suuti);
                    $wanryoku = n($wanryoku) - n($in['par_suuti']);
                } elseif (($in['nouryoku'] == '脚力')) {
                    $kod_kyakuryoku = n($kod_kyakuryoku) + n($konoagatta_suuti);
                    $kyakuryoku = n($kyakuryoku) - n($in['par_suuti']);
                } elseif (($in['nouryoku'] == 'ルックス')) {
                    $kod_looks = n($kod_looks) + n($konoagatta_suuti);
                    $looks = n($looks) - n($in['par_suuti']);
                } elseif (($in['nouryoku'] == 'LOVE')) {
                    $kod_love = n($kod_love) + n($konoagatta_suuti);
                    $love = n($love) - n($in['par_suuti']);
                } elseif (($in['nouryoku'] == '面白さ')) {
                    $kod_unique = n($kod_unique) + n($konoagatta_suuti);
                    $unique = n($unique) - n($in['par_suuti']);
                } elseif (($in['nouryoku'] == 'エッチ')) {
                    $kod_etti = n($kod_etti) + n($konoagatta_suuti);
                    $etti = n($etti) - n($in['par_suuti']);
                } elseif ($in['syokuryou']) {
                    $syouhinattaflg = 0;
                    $__s2 = $_ ?? null;
                    foreach ($my_kounyuu_list ?? [] as $_) {
                        syouhin_sprit($_);
                        if (((string)$in['syokuryou'] === (string)(string)($syo_hinmoku))) {
                            // 年齢別掛け率を算出
                            $kono_nenrei = pl_int((((n($now_time) - n($kod_yobi1))) / (((60 * 60) * 24))));
                            if ((n($kono_nenrei) >= 16)) {
                                $nenreibetu_kakeritu = 0.8;
                            } elseif ((n($kono_nenrei) >= 14)) {
                                $nenreibetu_kakeritu = 1;
                            } elseif ((n($kono_nenrei) >= 10)) {
                                $nenreibetu_kakeritu = 1.2;
                            } elseif ((n($kono_nenrei) >= 5)) {
                                $nenreibetu_kakeritu = 1.5;
                            } elseif ((n($kono_nenrei) >= 3)) {
                                $nenreibetu_kakeritu = 2.5;
                            } else {
                                $nenreibetu_kakeritu = 3.5;
                            }
                            $syouhinattaflg = 1;
                            $kod_taijuu_hue = (n($nenreibetu_kakeritu) * ((n($syo_cal) / 1000)));
                            $kod_yobi6 = n($kod_yobi6) + n($kod_taijuu_hue);
                            $randed = ((pl_int((pl_rand(10) + 10))) / 10);
                            $sintyouup = (n($randed) * ((((((((n($syo_tairyoku) + n($syo_kenkou)) + n($syo_speed)) + n($syo_power)) + n($syo_wanryoku)) + n($syo_kyakuryoku))) / 10)));
                            $kod_yobi5 = n($kod_yobi5) + (n($nenreibetu_kakeritu) * n($sintyouup));
                            if (($syo_taikyuu_tani == '回')) {
                                $syo_taikyuu--;
                            }
                            $kod_yobi7 = $date_sec;
                        }
                        // 商品が見つかった場合
                        if ((n($syo_taikyuu) <= 0)) {
                            continue;
                        }
                        syouhin_temp(...$_args);
                        $new_myitem_hairetu[] = $syo_temp;
                    }
                    $_ = $__s2;
                    // foreach閉じ
                    if ((n($syouhinattaflg) == 0)) {
                        error('商品がありません。');
                    }
                } else {
                    error('指示が不明確です。');
                }
                // 食事の場合閉じ
                if ((((((((((((((((((n($kokugo) < 0) || (n($suugaku) < 0)) || (n($rika) < 0)) || (n($syakai) < 0)) || (n($eigo) < 0)) || (n($ongaku) < 0)) || (n($bijutu) < 0)) || (n($looks) < 0)) || (n($tairyoku) < 0)) || (n($kenkou) < 0)) || (n($speed) < 0)) || (n($power) < 0)) || (n($wanryoku) < 0)) || (n($kyakuryoku) < 0)) || (n($love) < 0)) || (n($unique) < 0)) || (n($etti) < 0))) {
                    error('パラメータが足りません。親はすべてのパラメータにおいてプラスである必要があります。');
                }
                // 最後の子育て時間を更新
                $kod_yobi2 = $date_sec;
                // 最後に子育てした人を記録
                $kod_yobi9 = (string)($name);
                // 食事の場合
                if ($in['syokuryou']) {
                    $kod_yobi3 = "{$name}さんが子供に{$in['syokuryou']}を食べさせました（{$date2}）";
                    $message_in = "{$kod_name}ちゃんに{$in['syokuryou']}を食べさせました。";
                    // パラメータアップの場合
                } else {
                    $youikuhi = (n($konoagatta_suuti) * n($youikuhiyou));
                    $kod_yobi3 = "{$name}さんが子供の{$in['nouryoku']}パラメータを{$konoagatta_suuti}あげました（{$date2}）";
                    $message_in = "{$kod_name}ちゃんの{$in['nouryoku']}パラメータが{$konoagatta_suuti}あがりました。養育費として{$youikuhi}円かかりました。";
                    $money = n($money) - n($youikuhi);
                }
                // 総合能力値計算
                $sogo_sisuu = (((n($kod_yobi5) + n($kod_yobi6))) / 50);
                $kod_yobi4 = pl_int(((((((((((((((((((n($kod_kokugo) + n($kod_suugaku)) + n($kod_rika)) + n($kod_syakai)) + n($kod_eigo)) + n($kod_ongaku)) + n($kod_bijutu)) + n($kod_looks)) + n($kod_tairyoku)) + n($kod_kenkou)) + n($kod_speed)) + n($kod_power)) + n($kod_wanryoku)) + n($kod_kyakuryoku)) + n($kod_love)) + n($kod_unique)) + n($kod_etti))) * n($sogo_sisuu)));
            }
            // 子供番号一致の場合閉じ
            $new_kodomo_temp = "{$kod_num}<>{$kod_name}<>{$kod_oya1}<>{$kod_oya2}<>{$kod_job}<>{$kod_kokugo}<>{$kod_suugaku}<>{$kod_rika}<>{$kod_syakai}<>{$kod_eigo}<>{$kod_ongaku}<>{$kod_bijutu}<>{$kod_looks}<>{$kod_tairyoku}<>{$kod_kenkou}<>{$kod_speed}<>{$kod_power}<>{$kod_wanryoku}<>{$kod_kyakuryoku}<>{$kod_love}<>{$kod_unique}<>{$kod_etti}<>{$kod_yobi1}<>{$kod_yobi2}<>{$kod_yobi3}<>{$kod_yobi4}<>{$kod_yobi5}<>{$kod_yobi6}<>{$kod_yobi7}<>{$kod_yobi8}<>{$kod_yobi9}<>{$kod_yobi10}<>\n";
            $new_all_kodomo[] = $new_kodomo_temp;
        }
        $_ = $__s1;
        // foreach閉じ
        if ((n($sodate_taisyou_flg) == 0)) {
            error('対象の子供が見つかりません。');
        }
        // 子供ログ更新
        lock(...$_args);
        if (!(pl_open('KODO', ">{$kodomo_file}"))) { error("{$kodomo_file}に書き込めません"); }
        pl_print('KODO', pl_join('', $new_all_kodomo));
        pl_close('KODO');
        // 自分の所有物ファイルを更新
        if ($in['syokuryou']) {
            if (!(pl_open('OUT', ">{$monokiroku_file}"))) { error("Write Error : {$monokiroku_file}"); }
            pl_print('OUT', pl_join('', $new_myitem_hairetu));
            pl_close('OUT');
        }
        unlock(...$_args);
        // 個人ログ更新
        temp_routin(...$_args);
        log_kousin($my_log_file, $k_temp);
        message_only((string)($message_in));
        hooter('kosodate', '子育て画面へ', 'kekkon.php');
        hooter('login_view', '戻る');
        exit;
    }
    // パラメータアップの場合閉じ
    t_header('assen_style');
    echo <<<EOM
    <table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr>
    	<td bgcolor="#ffffff">●自分の持っているパラメータを子供のために使ったり、食料品を与えることで子育てができます。ただしお相手と交互にしかできません。<br>
    	●食事は身長と体重に影響を与えますが、パラメータには関係しません。<br>
    	●子供は与えたパラメータの10分の1しか得ることができません。<br>
    	●子供のパラメータ１に対して{$youikuhiyou}円の養育費がかかります。<br>
    	●子育てできる間隔は{$kosodate_kankaku}時間です。<br>
    	●子供は１日１歳ずつ歳をとり、19歳になると自立して親元からいなくなります。自立後は稼ぎに応じた仕送りを一定期間するようになります。<br>
    	●{$kodomo_sibou_time}日間、食事させないと子供は死んでしまいます。
    	</td>
    	<td  bgcolor="#ff6633" align="center" width="35%"><div style="font-size:13px; color:#ffffff">子育て</div>
    	<div align="center"><form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="assenjo">
    	<input type="hidden" name="command" value="kodomo_ranking">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value="子供ランキング">
    	</form></div>
    	</td></tr></table><br>
    EOM;
    // 持ち物フォーム作成
    $syokuryouattane_flag = 0;
    $__s3 = $_ ?? null;
    foreach ($my_kounyuu_list ?? [] as $_) {
        syouhin_sprit($_);
        if ((n($syo_taikyuu) <= 0)) {
            continue;
        }
        if ((($syo_syubetu != '食料品') && ($syo_syubetu != 'ファーストフード'))) {
            continue;
        }
        $syokuryou_form .= "<option value=\"{$syo_hinmoku}\">{$syo_hinmoku}を</option>";
        $syokuryouattane_flag = 1;
    }
    $_ = $__s3;
    if ((n($syokuryouattane_flag) == 0)) {
        $syokuryou_form .= '<option value="">食料を持っていません</option>';
    }
    // 職業ごとの給料をハッシュに代入
    if (!(pl_open('SP', './dat_dir/job.dat'))) { error('Open Error : ./dat_dir/job.dat'); }
    $top_koumoku = pl_readline('SP');
    $job_hairetu = pl_readlines('SP');
    pl_close('SP');
    $__s4 = $_ ?? null;
    foreach ($job_hairetu ?? [] as $_) {
        job_sprit($_);
        $job_kihonkyuu[$job_name] = $job_kyuuyo;
    }
    $_ = $__s4;
    // 子供の表示
    $now_time = pl_time();
    $kodomoiruka_flag = 0;
    $__s5 = $_ ?? null;
    foreach ($all_kodomo ?? [] as $_) {
        [$kod_num, $kod_name, $kod_oya1, $kod_oya2, $kod_job, $kod_kokugo, $kod_suugaku, $kod_rika, $kod_syakai, $kod_eigo, $kod_ongaku, $kod_bijutu, $kod_looks, $kod_tairyoku, $kod_kenkou, $kod_speed, $kod_power, $kod_wanryoku, $kod_kyakuryoku, $kod_love, $kod_unique, $kod_etti, $kod_yobi1, $kod_yobi2, $kod_yobi3, $kod_yobi4, $kod_yobi5, $kod_yobi6, $kod_yobi7, $kod_yobi8, $kod_yobi9, $kod_yobi10] = pl_pad(pl_split('/<>/u', $_), 32);
        if (((string)$kod_oya1 === (string)$name)) {
            $konokonooya = $kod_oya2;
        } elseif (((string)$kod_oya2 === (string)$name)) {
            $konokonooya = $kod_oya1;
        } else {
            continue;
        }
        // 子供の名前が無い場合
        if (($kod_name == '')) {
            echo <<<EOM
            		<table width="90%" border="0" cellspacing="0" cellpadding="6" align="center" class="yosumi">
            		<tr bgcolor="#ffffff"><td style="font-size:12px;color:ff3300" width="90" nowrap>{$konokonooya}さんとの間に子供ができましたがまだ出産していません。</td></tr>
            		</table><br><br>
            EOM;
            $kodomoiruka_flag = 1;
            continue;
        }
        // 自立している場合
        if ((n($kod_yobi8) == 1)) {
            $kono_nenrei = pl_int((((n($now_time) - n($kod_yobi1))) / (((60 * 60) * 24))));
            $ima_kasegi = (((n($job_kihonkyuu[$kod_job]) * n($kono_nenrei))) + ((n($kod_yobi4) * 10)));
            echo <<<EOM
            		<table width="90%" border="0" cellspacing="0" cellpadding="6" align="center" class="yosumi">
            		<tr bgcolor="#ffffff"><td style="font-size:12px;color:339933" width="90" nowrap>{$konokonooya}さんとの子供「{$kod_name}」さんは自立して「{$kod_job}」になりました。現在、{$kono_nenrei}歳、給料：{$ima_kasegi}円</td></tr>
            		</table><br><br>
            EOM;
            $kodomoiruka_flag = 1;
            continue;
        }
        // 子供の名前がある場合
        $kono_nenrei = pl_int((((n($now_time) - n($kod_yobi1))) / (((60 * 60) * 24))));
        $kod_yobi5 = pl_sprintf('%.1f', $kod_yobi5);
        $kod_yobi6 = pl_sprintf('%.1f', $kod_yobi6);
        check_BMI($kod_yobi5, $kod_yobi6);
        byou_hiduke($kod_yobi7);
        echo <<<EOM
        	<table width="90%" border="0" cellspacing="0" cellpadding="6" align="center" class="yosumi">
        	<tr bgcolor="#ffffaa"><td colspan="7"><span  style="font-size:12px;color:ff3300">{$kod_name}</span>
        	（{$kono_nenrei}歳）{$konokonooya}さんとの子供　<span class="honbun2">最後の子育て：{$kod_yobi3}</span></td></tr>
        	<tr bgcolor="#dddddd"><td colspan="7">
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="kosodate">
        	<input type="hidden" name="command" value="do_kosodate">
        	<input type="hidden" name="kod_num" value="{$kod_num}">
        	<input type="hidden" name="name" value="{$name}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="id" value="{$k_id}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<select name="nouryoku">
        	<option value="国語">国語パラメータを</option>
        	<option value="数学">数学パラメータを</option>
        	<option value="理科">理科パラメータを</option>
        	<option value="社会">社会パラメータを</option>
        	<option value="英語">英語パラメータを</option>
        	<option value="音楽">音楽パラメータを</option>
        	<option value="美術">美術パラメータを</option>
        	<option value="体力">体力パラメータを</option>
        	<option value="健康">健康パラメータを</option>
        	<option value="スピード">スピードパラメータを</option>
        	<option value="パワー">パワーパラメータを</option>
        	<option value="腕力">腕力パラメータを</option>
        	<option value="脚力">脚力パラメータを</option>
        	<option value="ルックス">ルックスパラメータを</option>
        	<option value="LOVE">LOVEパラメータを</option>
        	<option value="面白さ">面白さパラメータを</option>
        	<option value="エッチ">エッチパラメータを</option>
        	</select>
        	 <select name="par_suuti">
        	<option value="10">10</option>
        	<option value="50">50</option>
        	<option value="100">100</option>
        	<option value="200">200</option>
        	<option value="300">300</option>
        	<option value="500">500</option>
        	<option value="800">800</option>
        	<option value="1000">1000</option>
        	</select>
        	　<input type="submit" value=" あげる ">
        	</form>
        
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="kosodate">
        	<input type="hidden" name="command" value="do_kosodate">
        	<input type="hidden" name="kod_num" value="{$kod_num}">
        	<input type="hidden" name="name" value="{$name}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="id" value="{$k_id}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<select name="syokuryou">
        	{$syokuryou_form}
        	</select>
        	<input type="submit" value=" 与える ">
        	</form>
        
        	</td></tr>
        	<tr><td><span class="honbun2">総合能力値：</span>{$kod_yobi4}</td>
        	<td>身長：{$kod_yobi5} cm</td>
        	<td>体重：{$kod_yobi6} kg</td>
        	<td>体格指数：{$BMI}（{$taikei}）</td>
        	<td>最後の食事：{$bh_tukihi}</td>
        	</tr>
        	<tr>
        	<td>国語：{$kod_kokugo}</td>
        	<td>数学：{$kod_suugaku}</td>
        	<td>理科：{$kod_rika}</td>
        	<td>社会：{$kod_syakai}</td>
        	<td>英語：{$kod_eigo}</td>
        	<td>音楽：{$kod_ongaku}</td>
        	<td>美術：{$kod_bijutu}</td></tr><tr>
        	<td>ルックス：{$kod_looks}</td>
        	<td>体力：{$kod_tairyoku}</td>
        	<td>健康：{$kod_kenkou}</td>
        	<td>スピード：{$kod_speed}</td>
        	<td>パワー：{$kod_power}</td>
        	<td>腕力：{$kod_wanryoku}</td>
        	<td>脚力：{$kod_kyakuryoku}</td></tr><tr>
        	<td>LOVE：{$kod_love}</td>
        	<td>面白さ：{$kod_unique}</td>
        	<td>エッチ：{$kod_etti}</td>
        	</tr></table>
        	<br><br>
        EOM;
        $kodomoiruka_flag = 1;
    }
    $_ = $__s5;
    // foreach閉じ
    if ((n($kodomoiruka_flag) == 0)) {
        echo '<div align="center" class="honbun4">現在、子供はいません。</div>';
    }
    hooter('login_view', '街へ戻る');
    exit;
}

function bykeys_up(...$_args)
{
    global $a, $b, $ko_keys;
    return (n($ko_keys[n($b)]) <=> n($ko_keys[n($a)]));
}

function kodomo_ziritu(...$_args)
{
    global $BMI, $_, $data, $job_BMI_max, $job_BMI_min, $job_bijutu, $job_eigo, $job_etti, $job_hairetu_sort,
        $job_kenkou, $job_kokugo, $job_kyakuryoku, $job_looks, $job_love, $job_name, $job_ongaku, $job_power,
        $job_rika, $job_sintyou, $job_speed, $job_suugaku, $job_syakai, $job_tairyoku, $job_unique, $job_wanryoku,
        $key, $ko_keys, $kod_name, $kod_yobi5, $kod_yobi6, $return_job;
    [$kodziritu_num, $kodziritu_name, $kodziritu_oya1, $kodziritu_oya2, $kodziritu_job, $kodziritu_kokugo, $kodziritu_suugaku, $kodziritu_rika, $kodziritu_syakai, $kodziritu_eigo, $kodziritu_ongaku, $kodziritu_bijutu, $kodziritu_looks, $kodziritu_tairyoku, $kodziritu_kenkou, $kodziritu_speed, $kodziritu_power, $kodziritu_wanryoku, $kodziritu_kyakuryoku, $kodziritu_love, $kodziritu_unique, $kodziritu_etti, $kodziritu_yobi1, $kodziritu_yobi2, $kodziritu_yobi3, $kodziritu_yobi4, $kodziritu_yobi5, $kodziritu_yobi6, $kodziritu_yobi7, $kodziritu_yobi8, $kodziritu_yobi9, $kodziritu_yobi10] = pl_pad(pl_split('/<>/u', $_args[0]), 32);
    if (!(pl_open('SP', './dat_dir/job.dat'))) { error('Open Error : ./dat_dir/job.dat'); }
    $top_koumoku = pl_readline('SP');
    $job_hairetu = pl_readlines('SP');
    pl_close('SP');
    // BMIをチェック
    check_BMI($kod_yobi5, $kod_yobi6);
    // 給料の多い順にソート
    $__s1 = $_ ?? null;
    foreach ($job_hairetu ?? [] as $_) {
        $data = $_;
        $key = pl_idx(pl_split('/<>/u', $data), 18);
        // ソートする要素を選ぶ
        $job_hairetu_sort[] = $data;
        $ko_keys[] = $key;
    }
    $_ = $__s1;
    $job_hairetu_sort = pl_slice($job_hairetu_sort, pl_sort('bykeys_up', pl_range(0, (pl_count($job_hairetu_sort) - 1))));
    // 条件を満たしている職業を検索
    $__s2 = $_ ?? null;
    foreach ($job_hairetu_sort ?? [] as $_) {
        job_sprit($_);
        if ((n($kodziritu_kokugo) < n($job_kokugo))) {
            continue;
        }
        if ((n($kodziritu_suugaku) < n($job_suugaku))) {
            continue;
        }
        if ((n($kodziritu_rika) < n($job_rika))) {
            continue;
        }
        if ((n($kodziritu_syakai) < n($job_syakai))) {
            continue;
        }
        if ((n($kodziritu_eigo) < n($job_eigo))) {
            continue;
        }
        if ((n($kodziritu_ongaku) < n($job_ongaku))) {
            continue;
        }
        if ((n($kodziritu_bijutu) < n($job_bijutu))) {
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
        if ((n($kodziritu_looks) < n($job_looks))) {
            continue;
        }
        if ((n($kodziritu_tairyoku) < n($job_tairyoku))) {
            continue;
        }
        if ((n($kodziritu_kenkou) < n($job_kenkou))) {
            continue;
        }
        if ((n($kodziritu_speed) < n($job_speed))) {
            continue;
        }
        if ((n($kodziritu_power) < n($job_power))) {
            continue;
        }
        if ((n($kodziritu_wanryoku) < n($job_wanryoku))) {
            continue;
        }
        if ((n($kodziritu_kyakuryoku) < n($job_kyakuryoku))) {
            continue;
        }
        if ((n($kodziritu_love) < n($job_love))) {
            continue;
        }
        if ((n($kodziritu_unique) < n($job_unique))) {
            continue;
        }
        if ((n($kodziritu_etti) < n($job_etti))) {
            continue;
        }
        if ((n($kodziritu_yobi5) < n($job_sintyou))) {
            continue;
        }
        break;
    }
    $_ = $__s2;
    // foreach閉じ
    if (($job_name == '')) {
        $job_name = '浮浪者';
    }
    $return_job = $job_name;
    lock(...$_args);
    news_kiroku('就職', "{$kodziritu_oya1}さんと{$kodziritu_oya2}さんの子供「{$kod_name}」さんが自立して{$job_name}になりました。");
    unlock(...$_args);
}

function kekkon_id_sakujo(...$_args)
{
    global $aite_house_type, $aite_k_temp, $aite_log_file, $return_id;
    id_check($_args[0]);
    openAitelog($return_id);
    $aite_house_type = '';
    aite_temp_routin(...$_args);
    if (!(pl_open('OUT', ">{$aite_log_file}"))) { error("{$aite_log_file}が開けません"); }
    pl_print('OUT', $aite_k_temp);
    pl_close('OUT');
}

