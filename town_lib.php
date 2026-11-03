<?php
// このファイルは town_lib.pl (Perl) から自動変換されたものです。
// 町CGI  Copyright (c) 2003-2004 brassiere  http://brassiere.jp/

// 自分の家の設定ファイル分割
// ###BMI算出サブルーチン
// ######ヘッダー出力
// ####フッター
// メッセージ画面出力
// エラー画面出力
// 時間取得
// time値（秒）を日付に変換
// ホスト名を取得
// ロック処理
// #######  ロック解除
// #####クッキーを取得
// ####クッキーを発行
// id番号獲得サブルーチン
// デコード処理
// 多重チェック処理
// ######記帳処理
// ######相手の記帳処理
// ニュース記録サブルーチン		ver.1.3
// 住んでいる街のチェックサブルーチン
// #####街の経済力アップ
// #####街の繁栄度アップ
// 自立後の子供からの仕送り

function kozin_sprit(...$_args)
{
    global $_, $access_byou, $access_time, $bank, $bijutu, $byouki_sisuu, $byoumei, $eigo, $energy, $etti,
        $first_access, $host, $house_name, $house_type, $job, $job_kaisuu, $job_keiken, $jobsyu, $k_id, $k_sousisan,
        $k_yobi3, $k_yobi4, $k_yobi5, $kenkou, $kokugo, $kounyuu, $kyakuryoku, $last_ene_time, $last_mailcheck,
        $last_nouene_time, $last_school, $last_syokuzi, $loan_kaisuu, $loan_nitigaku, $looks, $love, $mise_type,
        $money, $name, $next_tra, $nou_energy, $ongaku, $pass, $power, $rika, $sex, $sintyou, $speed, $super_teiki,
        $suugaku, $syakai, $taijuu, $tairyoku, $unique, $wanryoku;
    [$k_id, $name, $pass, $money, $bank, $job, $kokugo, $suugaku, $rika, $syakai, $eigo, $ongaku, $bijutu, $looks, $tairyoku, $kenkou, $speed, $power, $wanryoku, $kyakuryoku, $love, $unique, $etti, $first_access, $kounyuu, $sex, $access_byou, $access_time, $host, $house_name, $house_type, $byoumei, $mise_type, $last_mailcheck, $super_teiki, $energy, $last_ene_time, $next_tra, $last_syokuzi, $sintyou, $taijuu, $nou_energy, $last_nouene_time, $job_keiken, $job_kaisuu, $last_school, $byouki_sisuu, $loan_nitigaku, $loan_kaisuu, $k_sousisan, $jobsyu, $k_yobi3, $k_yobi4, $k_yobi5] = pl_pad(pl_split('/<>/u', $_), 54);
    // k_yobi3＝多重登録者　jobsyu＝マスターした職業　　kounyuu＝アイコンURL　house_name＝最後に仕事した時間　house_type＝配偶者のID番号　mise_type＝ログイン非表示　last_mailcheck＝未使用
}

function kozin_sprit2(...$_args)
{
    global $access_byou, $access_time, $bank, $bijutu, $byouki_sisuu, $byoumei, $eigo, $energy, $etti, $first_access,
        $host, $house_name, $house_type, $job, $job_kaisuu, $job_keiken, $jobsyu, $k_id, $k_sousisan, $k_yobi3,
        $k_yobi4, $k_yobi5, $kenkou, $kokugo, $kounyuu, $kyakuryoku, $last_ene_time, $last_mailcheck,
        $last_nouene_time, $last_school, $last_syokuzi, $loan_kaisuu, $loan_nitigaku, $looks, $love, $mise_type,
        $money, $name, $next_tra, $nou_energy, $nouryoku_suuzi_hairetu, $ongaku, $pass, $power, $rika, $sex,
        $sintyou, $speed, $super_teiki, $suugaku, $syakai, $taijuu, $tairyoku, $unique, $wanryoku;
    [$k_id, $name, $pass, $money, $bank, $job, $kokugo, $suugaku, $rika, $syakai, $eigo, $ongaku, $bijutu, $looks, $tairyoku, $kenkou, $speed, $power, $wanryoku, $kyakuryoku, $love, $unique, $etti, $first_access, $kounyuu, $sex, $access_byou, $access_time, $host, $house_name, $house_type, $byoumei, $mise_type, $last_mailcheck, $super_teiki, $energy, $last_ene_time, $next_tra, $last_syokuzi, $sintyou, $taijuu, $nou_energy, $last_nouene_time, $job_keiken, $job_kaisuu, $last_school, $byouki_sisuu, $loan_nitigaku, $loan_kaisuu, $k_sousisan, $jobsyu, $k_yobi3, $k_yobi4, $k_yobi5] = pl_pad(pl_split('/<>/u', $_args[0]), 54);
    $nouryoku_suuzi_hairetu = pl_split('/<>/u', $_args[0]);
}

function temp_routin(...$_args)
{
    global $access_byou, $access_time, $bank, $bijutu, $byouki_sisuu, $byoumei, $eigo, $energy, $etti, $first_access,
        $host, $house_name, $house_type, $job, $job_kaisuu, $job_keiken, $jobsyu, $k_id, $k_sousisan, $k_temp,
        $k_yobi3, $k_yobi4, $k_yobi5, $kenkou, $kokugo, $kounyuu, $kyakuryoku, $last_ene_time, $last_mailcheck,
        $last_nouene_time, $last_school, $last_syokuzi, $loan_kaisuu, $loan_nitigaku, $looks, $love, $mise_type,
        $money, $name, $next_tra, $nou_energy, $ongaku, $pass, $power, $rika, $sex, $sintyou, $speed, $super_teiki,
        $suugaku, $syakai, $taijuu, $tairyoku, $unique, $wanryoku;
    $k_temp = "{$k_id}<>{$name}<>{$pass}<>{$money}<>{$bank}<>{$job}<>{$kokugo}<>{$suugaku}<>{$rika}<>{$syakai}<>{$eigo}<>{$ongaku}<>{$bijutu}<>{$looks}<>{$tairyoku}<>{$kenkou}<>{$speed}<>{$power}<>{$wanryoku}<>{$kyakuryoku}<>{$love}<>{$unique}<>{$etti}<>{$first_access}<>{$kounyuu}<>{$sex}<>{$access_byou}<>{$access_time}<>{$host}<>{$house_name}<>{$house_type}<>{$byoumei}<>{$mise_type}<>{$last_mailcheck}<>{$super_teiki}<>{$energy}<>{$last_ene_time}<>{$next_tra}<>{$last_syokuzi}<>{$sintyou}<>{$taijuu}<>{$nou_energy}<>{$last_nouene_time}<>{$job_keiken}<>{$job_kaisuu}<>{$last_school}<>{$byouki_sisuu}<>{$loan_nitigaku}<>{$loan_kaisuu}<>{$k_sousisan}<>{$jobsyu}<>{$k_yobi3}<>{$k_yobi4}<>{$k_yobi5}<>\n";
}

function aite_sprit(...$_args)
{
    global $aite_access_byou, $aite_access_time, $aite_bank, $aite_bijutu, $aite_byouki_sisuu, $aite_byoumei,
        $aite_eigo, $aite_energy, $aite_etti, $aite_first_access, $aite_host, $aite_house_name, $aite_house_type,
        $aite_job, $aite_job_kaisuu, $aite_job_keiken, $aite_jobsyu, $aite_k_id, $aite_kenkou, $aite_kokugo,
        $aite_kounyuu, $aite_kyakuryoku, $aite_last_ene_time, $aite_last_mailcheck, $aite_last_nouene_time,
        $aite_last_school, $aite_last_syokuzi, $aite_loan_kaisuu, $aite_loan_nitigaku, $aite_looks, $aite_love,
        $aite_mise_type, $aite_money, $aite_name, $aite_next_tra, $aite_nou_energy, $aite_nouryoku_suuzi_hairetu,
        $aite_ongaku, $aite_pass, $aite_power, $aite_rika, $aite_sex, $aite_sintyou, $aite_sousisan, $aite_speed,
        $aite_super_teiki, $aite_suugaku, $aite_syakai, $aite_taijuu, $aite_tairyoku, $aite_unique, $aite_wanryoku,
        $aite_yobi3, $aite_yobi4, $aite_yobi5;
    [$aite_k_id, $aite_name, $aite_pass, $aite_money, $aite_bank, $aite_job, $aite_kokugo, $aite_suugaku, $aite_rika, $aite_syakai, $aite_eigo, $aite_ongaku, $aite_bijutu, $aite_looks, $aite_tairyoku, $aite_kenkou, $aite_speed, $aite_power, $aite_wanryoku, $aite_kyakuryoku, $aite_love, $aite_unique, $aite_etti, $aite_first_access, $aite_kounyuu, $aite_sex, $aite_access_byou, $aite_access_time, $aite_host, $aite_house_name, $aite_house_type, $aite_byoumei, $aite_mise_type, $aite_last_mailcheck, $aite_super_teiki, $aite_energy, $aite_last_ene_time, $aite_next_tra, $aite_last_syokuzi, $aite_sintyou, $aite_taijuu, $aite_nou_energy, $aite_last_nouene_time, $aite_job_keiken, $aite_job_kaisuu, $aite_last_school, $aite_byouki_sisuu, $aite_loan_nitigaku, $aite_loan_kaisuu, $aite_sousisan, $aite_jobsyu, $aite_yobi3, $aite_yobi4, $aite_yobi5] = pl_pad(pl_split('/<>/u', $_args[0]), 54);
    $aite_nouryoku_suuzi_hairetu = pl_split('/<>/u', $_args[0]);
    // aite_yobi3＝多重登録
}

function aite_temp_routin(...$_args)
{
    global $aite_access_byou, $aite_access_time, $aite_bank, $aite_bijutu, $aite_byouki_sisuu, $aite_byoumei,
        $aite_eigo, $aite_energy, $aite_etti, $aite_first_access, $aite_host, $aite_house_name, $aite_house_type,
        $aite_job, $aite_job_kaisuu, $aite_job_keiken, $aite_jobsyu, $aite_k_id, $aite_k_temp, $aite_k_temp_list,
        $aite_kenkou, $aite_kokugo, $aite_kounyuu, $aite_kyakuryoku, $aite_last_ene_time, $aite_last_mailcheck,
        $aite_last_nouene_time, $aite_last_school, $aite_last_syokuzi, $aite_loan_kaisuu, $aite_loan_nitigaku,
        $aite_looks, $aite_love, $aite_mise_type, $aite_money, $aite_name, $aite_next_tra, $aite_nou_energy,
        $aite_ongaku, $aite_pass, $aite_power, $aite_rika, $aite_sex, $aite_sintyou, $aite_sousisan, $aite_speed,
        $aite_super_teiki, $aite_suugaku, $aite_syakai, $aite_taijuu, $aite_tairyoku, $aite_unique, $aite_wanryoku,
        $aite_yobi3, $aite_yobi4, $aite_yobi5;
    $aite_k_temp = "{$aite_k_id}<>{$aite_name}<>{$aite_pass}<>{$aite_money}<>{$aite_bank}<>{$aite_job}<>{$aite_kokugo}<>{$aite_suugaku}<>{$aite_rika}<>{$aite_syakai}<>{$aite_eigo}<>{$aite_ongaku}<>{$aite_bijutu}<>{$aite_looks}<>{$aite_tairyoku}<>{$aite_kenkou}<>{$aite_speed}<>{$aite_power}<>{$aite_wanryoku}<>{$aite_kyakuryoku}<>{$aite_love}<>{$aite_unique}<>{$aite_etti}<>{$aite_first_access}<>{$aite_kounyuu}<>{$aite_sex}<>{$aite_access_byou}<>{$aite_access_time}<>{$aite_host}<>{$aite_house_name}<>{$aite_house_type}<>{$aite_byoumei}<>{$aite_mise_type}<>{$aite_last_mailcheck}<>{$aite_super_teiki}<>{$aite_energy}<>{$aite_last_ene_time}<>{$aite_next_tra}<>{$aite_last_syokuzi}<>{$aite_sintyou}<>{$aite_taijuu}<>{$aite_nou_energy}<>{$aite_last_nouene_time}<>{$aite_job_keiken}<>{$aite_job_kaisuu}<>{$aite_last_school}<>{$aite_byouki_sisuu}<>{$aite_loan_nitigaku}<>{$aite_loan_kaisuu}<>{$aite_sousisan}<>{$aite_jobsyu}<>{$aite_yobi3}<>{$aite_yobi4}<>{$aite_yobi5}<>\n";
    $aite_k_temp_list = [];
    $aite_k_temp_list[] = $aite_k_temp;
}

function list_sprit(...$_args)
{
    global $list_access_byou, $list_access_time, $list_bank, $list_bijutu, $list_byouki_sisuu, $list_byoumei,
        $list_eigo, $list_energy, $list_etti, $list_first_access, $list_host, $list_house_name, $list_house_type,
        $list_job, $list_job_kaisuu, $list_job_keiken, $list_jobsyu, $list_k_id, $list_k_yobi3, $list_k_yobi4,
        $list_k_yobi5, $list_kenkou, $list_kokugo, $list_kounyuu, $list_kyakuryoku, $list_last_ene_time,
        $list_last_mailcheck, $list_last_nouene_time, $list_last_school, $list_last_syokuzi, $list_loan_kaisuu,
        $list_loan_nitigaku, $list_looks, $list_love, $list_mise_type, $list_money, $list_name, $list_next_tra,
        $list_nou_energy, $list_ongaku, $list_pass, $list_power, $list_rika, $list_sex, $list_sintyou,
        $list_sousisan, $list_speed, $list_super_teiki, $list_suugaku, $list_syakai, $list_taijuu, $list_tairyoku,
        $list_unique, $list_wanryoku;
    [$list_k_id, $list_name, $list_pass, $list_money, $list_bank, $list_job, $list_kokugo, $list_suugaku, $list_rika, $list_syakai, $list_eigo, $list_ongaku, $list_bijutu, $list_looks, $list_tairyoku, $list_kenkou, $list_speed, $list_power, $list_wanryoku, $list_kyakuryoku, $list_love, $list_unique, $list_etti, $list_first_access, $list_kounyuu, $list_sex, $list_access_byou, $list_access_time, $list_host, $list_house_name, $list_house_type, $list_byoumei, $list_mise_type, $list_last_mailcheck, $list_super_teiki, $list_energy, $list_last_ene_time, $list_next_tra, $list_last_syokuzi, $list_sintyou, $list_taijuu, $list_nou_energy, $list_last_nouene_time, $list_job_keiken, $list_job_kaisuu, $list_last_school, $list_byouki_sisuu, $list_loan_nitigaku, $list_loan_kaisuu, $list_sousisan, $list_jobsyu, $list_k_yobi3, $list_k_yobi4, $list_k_yobi5] = pl_pad(pl_split('/<>/u', $_args[0]), 54);
}

function list_temp(...$_args)
{
    global $list_access_byou, $list_access_time, $list_bank, $list_bijutu, $list_byouki_sisuu, $list_byoumei,
        $list_eigo, $list_energy, $list_etti, $list_first_access, $list_host, $list_house_name, $list_house_type,
        $list_job, $list_job_kaisuu, $list_job_keiken, $list_jobsyu, $list_k_id, $list_k_yobi3, $list_k_yobi4,
        $list_k_yobi5, $list_kenkou, $list_kokugo, $list_kounyuu, $list_kyakuryoku, $list_last_ene_time,
        $list_last_mailcheck, $list_last_nouene_time, $list_last_school, $list_last_syokuzi, $list_loan_kaisuu,
        $list_loan_nitigaku, $list_looks, $list_love, $list_mise_type, $list_money, $list_name, $list_next_tra,
        $list_nou_energy, $list_ongaku, $list_pass, $list_power, $list_rika, $list_sex, $list_sintyou,
        $list_sousisan, $list_speed, $list_super_teiki, $list_suugaku, $list_syakai, $list_taijuu, $list_tairyoku,
        $list_temp, $list_unique, $list_wanryoku;
    $list_temp = "{$list_k_id}<>{$list_name}<>{$list_pass}<>{$list_money}<>{$list_bank}<>{$list_job}<>{$list_kokugo}<>{$list_suugaku}<>{$list_rika}<>{$list_syakai}<>{$list_eigo}<>{$list_ongaku}<>{$list_bijutu}<>{$list_looks}<>{$list_tairyoku}<>{$list_kenkou}<>{$list_speed}<>{$list_power}<>{$list_wanryoku}<>{$list_kyakuryoku}<>{$list_love}<>{$list_unique}<>{$list_etti}<>{$list_first_access}<>{$list_kounyuu}<>{$list_sex}<>{$list_access_byou}<>{$list_access_time}<>{$list_host}<>{$list_house_name}<>{$list_house_type}<>{$list_byoumei}<>{$list_mise_type}<>{$list_last_mailcheck}<>{$list_super_teiki}<>{$list_energy}<>{$list_last_ene_time}<>{$list_next_tra}<>{$list_last_syokuzi}<>{$list_sintyou}<>{$list_taijuu}<>{$list_nou_energy}<>{$list_last_nouene_time}<>{$list_job_keiken}<>{$list_job_kaisuu}<>{$list_last_school}<>{$list_byouki_sisuu}<>{$list_loan_nitigaku}<>{$list_loan_kaisuu}<>{$list_sousisan}<>{$list_jobsyu}<>{$list_k_yobi3}<>{$list_k_yobi4}<>{$list_k_yobi5}<>\n";
}

function main_town_sprit(...$_args)
{
    global $mt_departflag, $mt_hanei, $mt_keizai, $mt_orosiflag, $mt_syokudouflag, $mt_t_time, $mt_today, $mt_y_time,
        $mt_yobi10, $mt_yobi11, $mt_yobi12, $mt_yobi13, $mt_yobi14, $mt_yobi8, $mt_yobi9, $mt_zinkou, $total_ninzuu;
    [$mt_zinkou, $mt_keizai, $mt_hanei, $mt_today, $mt_orosiflag, $mt_t_time, $mt_y_time, $mt_syokudouflag, $mt_departflag, $total_ninzuu, $mt_yobi8, $mt_yobi9, $mt_yobi10, $mt_yobi11, $mt_yobi12, $mt_yobi13, $mt_yobi14] = pl_pad(pl_split('/<>/u', $_args[0]), 17);
    // mt_yobi8 = 創設日（お店の売り上げまたは掲示板書き込みがあった日）mt_yobi9＝卸更新日時　total_ninzuu＝未使用
}

function main_town_temp(...$_args)
{
    global $mt_departflag, $mt_hanei, $mt_keizai, $mt_orosiflag, $mt_syokudouflag, $mt_t_time, $mt_temp,
        $mt_temp_list, $mt_today, $mt_y_time, $mt_yobi10, $mt_yobi11, $mt_yobi12, $mt_yobi13, $mt_yobi14, $mt_yobi8,
        $mt_yobi9, $mt_zinkou, $total_ninzuu;
    $mt_temp = "{$mt_zinkou}<>{$mt_keizai}<>{$mt_hanei}<>{$mt_today}<>{$mt_orosiflag}<>{$mt_t_time}<>{$mt_y_time}<>{$mt_syokudouflag}<>{$mt_departflag}<>{$total_ninzuu}<>{$mt_yobi8}<>{$mt_yobi9}<>{$mt_yobi10}<>{$mt_yobi11}<>{$mt_yobi12}<>{$mt_yobi13}<>{$mt_yobi14}<>\n";
    $mt_temp_list = [];
    $mt_temp_list[] = $mt_temp;
}

function town_sprit(...$_args)
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
        $t_yobi3, $t_yobi4, $t_yobi5, $t_yobi6, $t_yobi7, $tika, $town_name, $town_sprit_matrix, $zinkou;
    [$town_name, $zinkou, $keizai, $hanei, $t_x0, $t_x1, $t_x2, $t_x3, $t_x4, $t_x5, $t_x6, $t_x7, $t_x8, $t_x9, $t_x10, $t_x11, $t_x12, $t_x13, $t_x14, $t_x15, $t_x16, $t_a0, $t_a1, $t_a2, $t_a3, $t_a4, $t_a5, $t_a6, $t_a7, $t_a8, $t_a9, $t_a10, $t_a11, $t_a12, $t_a13, $t_a14, $t_a15, $t_a16, $t_b0, $t_b1, $t_b2, $t_b3, $t_b4, $t_b5, $t_b6, $t_b7, $t_b8, $t_b9, $t_b10, $t_b11, $t_b12, $t_b13, $t_b14, $t_b15, $t_b16, $t_c0, $t_c1, $t_c2, $t_c3, $t_c4, $t_c5, $t_c6, $t_c7, $t_c8, $t_c9, $t_c10, $t_c11, $t_c12, $t_c13, $t_c14, $t_c15, $t_c16, $t_d0, $t_d1, $t_d2, $t_d3, $t_d4, $t_d5, $t_d6, $t_d7, $t_d8, $t_d9, $t_d10, $t_d11, $t_d12, $t_d13, $t_d14, $t_d15, $t_d16, $t_e0, $t_e1, $t_e2, $t_e3, $t_e4, $t_e5, $t_e6, $t_e7, $t_e8, $t_e9, $t_e10, $t_e11, $t_e12, $t_e13, $t_e14, $t_e15, $t_e16, $t_f0, $t_f1, $t_f2, $t_f3, $t_f4, $t_f5, $t_f6, $t_f7, $t_f8, $t_f9, $t_f10, $t_f11, $t_f12, $t_f13, $t_f14, $t_f15, $t_f16, $t_g0, $t_g1, $t_g2, $t_g3, $t_g4, $t_g5, $t_g6, $t_g7, $t_g8, $t_g9, $t_g10, $t_g11, $t_g12, $t_g13, $t_g14, $t_g15, $t_g16, $t_h0, $t_h1, $t_h2, $t_h3, $t_h4, $t_h5, $t_h6, $t_h7, $t_h8, $t_h9, $t_h10, $t_h11, $t_h12, $t_h13, $t_h14, $t_h15, $t_h16, $t_i0, $t_i1, $t_i2, $t_i3, $t_i4, $t_i5, $t_i6, $t_i7, $t_i8, $t_i9, $t_i10, $t_i11, $t_i12, $t_i13, $t_i14, $t_i15, $t_i16, $t_j0, $t_j1, $t_j2, $t_j3, $t_j4, $t_j5, $t_j6, $t_j7, $t_j8, $t_j9, $t_j10, $t_j11, $t_j12, $t_j13, $t_j14, $t_j15, $t_j16, $t_k0, $t_k1, $t_k2, $t_k3, $t_k4, $t_k5, $t_k6, $t_k7, $t_k8, $t_k9, $t_k10, $t_k11, $t_k12, $t_k13, $t_k14, $t_k15, $t_k16, $t_l0, $t_l1, $t_l2, $t_l3, $t_l4, $t_l5, $t_l6, $t_l7, $t_l8, $t_l9, $t_l10, $t_l11, $t_l12, $t_l13, $t_l14, $t_l15, $t_l16, $t_m0, $t_m1, $t_m2, $t_m3, $t_m4, $t_m5, $t_m6, $t_m7, $t_m8, $t_m9, $t_m10, $t_m11, $t_m12, $t_m13, $t_m14, $t_m15, $t_m16, $t_n0, $t_n1, $t_n2, $t_n3, $t_n4, $t_n5, $t_n6, $t_n7, $t_n8, $t_n9, $t_n10, $t_n11, $t_n12, $t_n13, $t_n14, $t_n15, $t_n16, $tika, $t_yobi2, $t_yobi3, $t_yobi4, $t_yobi5, $t_yobi6, $t_yobi7] = pl_pad(pl_split('/<>/u', $_args[0]), 266);
    $town_sprit_matrix = pl_split('/<>/u', $_args[0]);
    // t_yobi2 = 街設立日（その街の売り上げがあった日）
}

function job_sprit(...$_args)
{
    global $job_BMI_max, $job_BMI_min, $job_bijutu, $job_bonus, $job_eigo, $job_energy, $job_etti, $job_kenkou,
        $job_kokugo, $job_kyakuryoku, $job_kyuuyo, $job_looks, $job_love, $job_name, $job_no, $job_nou_energy,
        $job_ongaku, $job_power, $job_rank, $job_rika, $job_sex, $job_siharai, $job_sintyou, $job_speed,
        $job_suugaku, $job_syakai, $job_syoukyuuritu, $job_syurui, $job_tairyoku, $job_unique, $job_wanryoku;
    [$job_no, $job_name, $job_kokugo, $job_suugaku, $job_rika, $job_syakai, $job_eigo, $job_ongaku, $job_bijutu, $job_BMI_min, $job_BMI_max, $job_looks, $job_tairyoku, $job_kenkou, $job_speed, $job_power, $job_wanryoku, $job_kyakuryoku, $job_kyuuyo, $job_siharai, $job_love, $job_unique, $job_etti, $job_sex, $job_sintyou, $job_energy, $job_nou_energy, $job_rank, $job_syurui, $job_bonus, $job_syoukyuuritu] = pl_pad(pl_split('/<>/u', $_args[0]), 31);
}

function syouhin_sprit(...$_args)
{
    global $syo_bijutu, $syo_cal, $syo_comment, $syo_eigo, $syo_etti, $syo_hinmoku, $syo_kankaku, $syo_kenkou,
        $syo_kokugo, $syo_kouka, $syo_kounyuubi, $syo_kyakuryoku, $syo_looks, $syo_love, $syo_nedan, $syo_ongaku,
        $syo_power, $syo_rika, $syo_sintai_syouhi, $syo_siyou_date, $syo_speed, $syo_suugaku, $syo_syakai,
        $syo_syubetu, $syo_taikyuu, $syo_taikyuu_tani, $syo_tairyoku, $syo_unique, $syo_wanryoku, $syo_zaiko,
        $syo_zunou_syouhi, $tanka, $tokubai;
    [$syo_syubetu, $syo_hinmoku, $syo_kokugo, $syo_suugaku, $syo_rika, $syo_syakai, $syo_eigo, $syo_ongaku, $syo_bijutu, $syo_kouka, $syo_looks, $syo_tairyoku, $syo_kenkou, $syo_speed, $syo_power, $syo_wanryoku, $syo_kyakuryoku, $syo_nedan, $syo_love, $syo_unique, $syo_etti, $syo_taikyuu, $syo_taikyuu_tani, $syo_kankaku, $syo_zaiko, $syo_cal, $syo_siyou_date, $syo_sintai_syouhi, $syo_zunou_syouhi, $syo_comment, $syo_kounyuubi, $tanka, $tokubai] = pl_pad(pl_split('/<>/u', $_args[0]), 33);
}

function syouhin_temp(...$_args)
{
    global $syo_bijutu, $syo_cal, $syo_comment, $syo_eigo, $syo_etti, $syo_hinmoku, $syo_kankaku, $syo_kenkou,
        $syo_kokugo, $syo_kouka, $syo_kounyuubi, $syo_kyakuryoku, $syo_looks, $syo_love, $syo_nedan, $syo_ongaku,
        $syo_power, $syo_rika, $syo_sintai_syouhi, $syo_siyou_date, $syo_speed, $syo_suugaku, $syo_syakai,
        $syo_syubetu, $syo_taikyuu, $syo_taikyuu_tani, $syo_tairyoku, $syo_temp, $syo_unique, $syo_wanryoku,
        $syo_zaiko, $syo_zunou_syouhi, $tanka, $tokubai;
    $syo_temp = "{$syo_syubetu}<>{$syo_hinmoku}<>{$syo_kokugo}<>{$syo_suugaku}<>{$syo_rika}<>{$syo_syakai}<>{$syo_eigo}<>{$syo_ongaku}<>{$syo_bijutu}<>{$syo_kouka}<>{$syo_looks}<>{$syo_tairyoku}<>{$syo_kenkou}<>{$syo_speed}<>{$syo_power}<>{$syo_wanryoku}<>{$syo_kyakuryoku}<>{$syo_nedan}<>{$syo_love}<>{$syo_unique}<>{$syo_etti}<>{$syo_taikyuu}<>{$syo_taikyuu_tani}<>{$syo_kankaku}<>{$syo_zaiko}<>{$syo_cal}<>{$syo_siyou_date}<>{$syo_sintai_syouhi}<>{$syo_zunou_syouhi}<>{$syo_comment}<>{$syo_kounyuubi}<>{$tanka}<>{$tokubai}<>\n";
}

function gym_sprit(...$_args)
{
    global $gym_etti, $gym_kankaku, $gym_kenkou, $gym_kouka, $gym_kyakuryoku, $gym_looks, $gym_name, $gym_nedan,
        $gym_power, $gym_speed, $gym_syouhi, $gym_tairyoku, $gym_wanryoku;
    [$gym_name, $gym_looks, $gym_tairyoku, $gym_kenkou, $gym_speed, $gym_power, $gym_wanryoku, $gym_kyakuryoku, $gym_nedan, $gym_kouka, $gym_etti, $gym_kankaku, $gym_syouhi] = pl_pad(pl_split('/<>/u', $_args[0]), 13);
}

function school_sprit(...$_args)
{
    global $sc_bijutu, $sc_eigo, $sc_kokugo, $sc_kouka, $sc_name, $sc_nedan, $sc_ongaku, $sc_rika, $sc_suugaku,
        $sc_syakai, $sc_syouhi;
    [$sc_name, $sc_kokugo, $sc_suugaku, $sc_rika, $sc_syakai, $sc_eigo, $sc_ongaku, $sc_bijutu, $sc_nedan, $sc_kouka, $sc_syouhi] = pl_pad(pl_split('/<>/u', $_args[0]), 11);
}

function ginkou_meisai_sprit(...$_args)
{
    global $meisai_azuke, $meisai_date, $meisai_hikidasi, $meisai_naiyou, $meisai_syubetu, $meisai_zandaka;
    [$meisai_date, $meisai_naiyou, $meisai_hikidasi, $meisai_azuke, $meisai_zandaka, $meisai_syubetu] = pl_pad(pl_split('/<>/u', $_args[0]), 6);
}

function ori_ie_sprit(...$_args)
{
    global $ori_ie_image, $ori_ie_kentikubi, $ori_ie_name, $ori_ie_rank, $ori_ie_sentaku_point, $ori_ie_setumei,
        $ori_ie_syubetu, $ori_ie_tateziku, $ori_ie_town, $ori_ie_yobi10, $ori_ie_yobi7, $ori_ie_yobi8, $ori_ie_yobi9,
        $ori_ie_yokoziku, $ori_k_id;
    [$ori_k_id, $ori_ie_name, $ori_ie_setumei, $ori_ie_image, $ori_ie_syubetu, $ori_ie_kentikubi, $ori_ie_town, $ori_ie_tateziku, $ori_ie_yokoziku, $ori_ie_sentaku_point, $ori_ie_rank, $ori_ie_yobi7, $ori_ie_yobi8, $ori_ie_yobi9, $ori_ie_yobi10] = pl_pad(pl_split('/<>/u', $_args[0]), 15);
}

function ori_ie_temp(...$_args)
{
    global $ori_ie_image, $ori_ie_kentikubi, $ori_ie_name, $ori_ie_rank, $ori_ie_sentaku_point, $ori_ie_setumei,
        $ori_ie_syubetu, $ori_ie_tateziku, $ori_ie_temp, $ori_ie_town, $ori_ie_yobi10, $ori_ie_yobi7, $ori_ie_yobi8,
        $ori_ie_yobi9, $ori_ie_yokoziku, $ori_k_id;
    $ori_ie_temp = "{$ori_k_id}<>{$ori_ie_name}<>{$ori_ie_setumei}<>{$ori_ie_image}<>{$ori_ie_syubetu}<>{$ori_ie_kentikubi}<>{$ori_ie_town}<>{$ori_ie_tateziku}<>{$ori_ie_yokoziku}<>{$ori_ie_sentaku_point}<>{$ori_ie_rank}<>{$ori_ie_yobi7}<>{$ori_ie_yobi8}<>{$ori_ie_yobi9}<>{$ori_ie_yobi10}<>\n";
}

function oriie_settei_sprit(...$_args)
{
    global $my_con1, $my_con1_title, $my_con2, $my_con2_title, $my_con3, $my_con3_title, $my_con4, $my_con4_title,
        $my_yobi10, $my_yobi11, $my_yobi12, $my_yobi13, $my_yobi14, $my_yobi15, $my_yobi16, $my_yobi17, $my_yobi18,
        $my_yobi5, $my_yobi6, $my_yobi7, $my_yobi8, $my_yobi9, $oriie_settei_sprit;
    [$my_con1, $my_con2, $my_con3, $my_con4, $my_con1_title, $my_con2_title, $my_con3_title, $my_con4_title, $my_yobi5, $my_yobi6, $my_yobi7, $my_yobi8, $my_yobi9, $my_yobi10, $my_yobi11, $my_yobi12, $my_yobi13, $my_yobi14, $my_yobi15, $my_yobi16, $my_yobi17, $my_yobi18] = pl_pad(pl_split('/<>/u', $_args[0]), 22);
    $oriie_settei_sprit = pl_split('/<>/u', $_args[0]);
}

function oriie_settei_temp(...$_args)
{
    global $my_con1, $my_con1_title, $my_con2, $my_con2_title, $my_con3, $my_con3_title, $my_con4, $my_con4_title,
        $my_yobi10, $my_yobi11, $my_yobi12, $my_yobi13, $my_yobi14, $my_yobi15, $my_yobi16, $my_yobi17, $my_yobi18,
        $my_yobi5, $my_yobi6, $my_yobi7, $my_yobi8, $my_yobi9, $ori_ie_settei_temp;
    $ori_ie_settei_temp = "{$my_con1}<>{$my_con2}<>{$my_con3}<>{$my_con4}<>{$my_con1_title}<>{$my_con2_title}<>{$my_con3_title}<>{$my_con4_title}<>{$my_yobi5}<>{$my_yobi6}<>{$my_yobi7}<>{$my_yobi8}<>{$my_yobi9}<>{$my_yobi10}<>{$my_yobi11}<>{$my_yobi12}<>{$my_yobi13}<>{$my_yobi14}<>{$my_yobi15}<>{$my_yobi16}<>{$my_yobi17}<>{$my_yobi18}<>\n";
}

function log_kousin(...$_args)
{
    lock(...$_args);
    if (!(pl_open('OUT', ">{$_args[0]}"))) { error("{$_args[0]}が開けません"); }
    pl_print('OUT', $_args[1]);
    pl_close('OUT');
    unlock(...$_args);
}

function mail_sprit(...$_args)
{
    global $m_byou, $m_com, $m_date, $m_name, $m_syubetu, $m_yobi1, $m_yobi2, $m_yobi3, $m_yobi4, $m_yobi5;
    [$m_syubetu, $m_name, $m_com, $m_date, $m_byou, $m_yobi1, $m_yobi2, $m_yobi3, $m_yobi4, $m_yobi5] = pl_pad(pl_split('/<>/u', $_args[0]), 10);
}

function openMylog(...$_args)
{
    global $master_ad, $my_log_file, $my_prof;
    $my_log_file = "./member/{$_args[0]}/log.cgi";
    if (!(pl_open('MYL', (string)($my_log_file)))) { error("ログファイル（{$my_log_file}）が開けません。<br>再度ログインしても同様ならば管理人（{$master_ad}）までメールをください。"); }
    $my_prof = pl_readline('MYL');
    if ((n($my_prof) == 0)) {
        error("ログファイルに問題が起こりました。<br>お手数ですが、管理人（{$master_ad}）までメールをください。");
    }
    kozin_sprit2($my_prof);
    pl_close('MYL');
}

function openAitelog(...$_args)
{
    global $aite_log_file, $aite_prof;
    $aite_log_file = "./member/{$_args[0]}/log.cgi";
    if (!(pl_open('AIT', (string)($aite_log_file)))) { error("お相手の方のログファイル（{$aite_log_file}）が開けません。"); }
    $aite_prof = pl_readline('AIT');
    if ((n($aite_prof) == 0)) {
        error('お相手の方のログファイルに問題があります。');
    }
    aite_sprit($aite_prof);
    pl_close('AIT');
}

function check_pass(...$_args)
{
    pl_check_pass(...$_args);
}

function check_BMI(...$_args)
{
    global $BMI, $taikei;
    $BMI = pl_int(((n($_args[1]) / ((n($_args[0]) / 100))) / ((n($_args[0]) / 100))));
    if ((n($BMI) >= 26)) {
        $taikei = '肥満';
    } elseif ((n($BMI) >= 24)) {
        $taikei = 'やや太り気味';
    } elseif ((n($BMI) >= 20)) {
        $taikei = '標準';
    } elseif ((n($BMI) >= 18)) {
        $taikei = 'やせ気味';
    } elseif ((n($BMI) <= 17)) {
        $taikei = 'やせすぎ';
    }
}

function t_header(...$_args)
{
    global $_, $en_name, $en_pass, $heder_flag, $idou_syudan, $idousyudan, $in, $kensa_flag, $matiidou_time,
        $matiidou_time2, $page_back, $randed, $script, $sokudo, $sokudos, $sonomati_style_settei, $syudan_name,
        $syudan_names, $title, $town_stylesheet, $ziko_flag, $ziko_idousyudan, $ziko_kakuritu;
    $heder_flag = 1;
    pl_html_head(...$_args);
    if (($in['command'] == 'mati_idou')) {
        foreach ($idou_syudan ?? [] as $syudan_name => $sokudo) {
            $syudan_names[] = $syudan_name;
            $sokudos[] = $sokudo;
        }
        // 移動手段選択
        $idousyudan = '徒歩で';
        $ziko_idousyudan = '徒歩';
        $matiidou_time2 = $matiidou_time;
        $__s1 = $_ ?? null;
        foreach ($syudan_names ?? [] as $_) {
            motimono_kensa((string)($_));
            if ((n($kensa_flag) == 1)) {
                $idousyudan = "{$_}で";
                $ziko_idousyudan = (string)($_);
                $matiidou_time2 = $idou_syudan[$_];
                break;
            }
        }
        $_ = $__s1;
        // 事故発生
        $randed = pl_int((pl_rand(n($ziko_kakuritu)) + 1));
        if ((n($randed) == 1)) {
            $ziko_flag = 'on';
        } else {
            $ziko_flag = 'off';
        }
        // 名前とパスをエンコード
        $en_name = $in['name'];
        $en_pass = $in['pass'];
        $en_name = pl_urlencode($en_name);
        $en_pass = pl_urlencode($en_pass);
        $ziko_idousyudan = pl_urlencode($ziko_idousyudan);
        echo "<meta http-equiv=\"refresh\" content=\"{$matiidou_time2};URL={$script}?mode=login_view&town_no={$in['town_no']}&name={$en_name}&pass={$en_pass}&command=idousyuuryou&ziko_flag={$ziko_flag}&ziko_idousyudan={$ziko_idousyudan}\">";
    }
    echo "<title>{$title}</title>\n";
    echo <<<EOM
    <Script Language="JavaScript">
    <!--
    ///////////////////////////////////////////////////
    // メッセージ No.5.1 Produced by「CLUB とむやん君」
    // URL http://www2s.biglobe.ne.jp/~club_tom/
    // 上の2行は著作権表示ですので消さないで下さい
    ///////////////////////////////////////////////////
    
    // フォームにメッセージを書き込む部分です。
    function onMes5(mes){
    	document.foMes5.TeMes5.value=mes;
    }
    
    function pfvsetInterval(){
    	retval = setInterval('countdown()',1000)
    }
    
    function countdown(){
    	if(document.form1==null){clearInterval(retval);return;}
    	if(document.form1.cdown==null){clearInterval(retval);return;}
    	if(document.form1.cdown.value!='O K'){
    		min = document.form1.cdown.value;
    		min--;
    		document.form1.cdown.value = min;
    		if(document.form1.cdown.value==0){
    			document.form1.cdown.value='O K'
    			clearInterval(retval);
    		}
    	}
    	else{clearInterval(retval);}
    }
    
    // End -->
    </Script>
    <style type="text/css">
    <!--
    {$town_stylesheet}
    -->
    </style>
    <link rel="stylesheet" href="css/town.css">
    EOM;
    if ((($in['mode'] == 'login_view') || ($_args[1] == 'sonomati'))) {
        $sonomati_style_settei = (string)($page_back[n($in['town_no'])]);
    }
    echo "</head><body style=\"{$sonomati_style_settei}\" class=\"{$_args[0]}\" onLoad=\"pfvsetInterval()\">\n";
}

function ori_header(...$_args)
{
    global $heder_flag, $title;
    $heder_flag = 1;
    pl_html_head(...$_args);
    echo "<title>{$title}</title>\n";
    echo <<<EOM
    <Script Language="JavaScript">
    <!--
    ///////////////////////////////////////////////////
    // メッセージ No.5.1 Produced by「CLUB とむやん君」
    // URL http://www2s.biglobe.ne.jp/~club_tom/
    // 上の2行は著作権表示ですので消さないで下さい
    ///////////////////////////////////////////////////
    
    // フォームにメッセージを書き込む部分です。
    function onMes5(mes){
    	document.foMes5.TeMes5.value=mes;
    }
    
    // End -->
    </Script>
    <link rel="stylesheet" href="css/town.css">
    <style type="text/css">
    <!--
    a   {{$_args[2]}}
    input  {{$_args[1]}}
    select  {{$_args[1]}}
    body {{$_args[0]}}
    -->
    </style>
    EOM;
    echo "</head><body>\n";
}

function hooter(...$_args)
{
    global $home_hyouzi, $homepage, $in, $master_ad, $script, $version, $yobidasi_script;
    if ($_args[2]) {
        $yobidasi_script = (string)($_args[2]);
    } else {
        $yobidasi_script = (string)($script);
    }
    $php_ver = TOWN_PHP_VERSION;
    echo '<footer class="tw-footer">';
    if (($_args[1] != '')) {
        echo <<<EOM
        <form method="POST" action="{$yobidasi_script}">
        <input type="hidden" name="mode" value="{$_args[0]}">
        <input type="hidden" name="iesettei_id" value="{$in['iesettei_id']}">
        <input type="hidden" name="name" value="{$in['name']}">
        <input type="hidden" name="pass" value="{$in['pass']}">
        <input type="hidden" name="admin_pass" value="{$in['admin_pass']}">
        <input type="hidden" name="town_no" value="{$in['town_no']}">
        <input type="submit" value="{$_args[1]}">
        </form>
        EOM;
    }
    if ((($in['mode'] == 'login_view') || ($in['mode'] == ''))) {
        if ($homepage) {
            $home_hyouzi = "<a href=\"{$homepage}\" target=\"_top\">[HOME]</a><br>";
        } else {
            $home_hyouzi = '';
        }
        echo <<<EOM
        {$home_hyouzi}
        <a href="http://brassiere.jp/" target="_blank">- {$version} script by brassiere -</a> [{$php_ver}]　<a href="mailto:{$master_ad}">お問い合わせ</a>
        EOM;
    }
    echo '</footer>';
    echo <<<'EOM'
    <script>
    // 枠の開閉状態をこのブラウザに記憶する
    (function () {
      document.querySelectorAll('details.tw-fold[data-fold]').forEach(function (d) {
        var key = 'tw-fold-' + d.getAttribute('data-fold');
        try { var v = localStorage.getItem(key); if (v === '0') d.open = false; else if (v === '1') d.open = true; } catch (e) {}
        d.addEventListener('toggle', function () { try { localStorage.setItem(key, d.open ? '1' : '0'); } catch (e) {} });
      });
    })();
    </script>
    </body></html>
    EOM;
}

function message(...$_args)
{
    global $in, $lockflag, $message_win, $script, $yobidasi_script;
    t_header('', 'sonomati');
    // ロックフラグがあればロックディレクトリを削除
    if ($lockflag) {
        unlock(...$_args);
    }
    if ($_args[2]) {
        $yobidasi_script = (string)($_args[2]);
    } else {
        $yobidasi_script = (string)($script);
    }
    echo <<<EOM
    <div class="tw-dialog" role="status">
    <div class="tw-dialog-body">{$_args[0]}</div>
    <form method="POST" action="{$yobidasi_script}">
    <input type="hidden" name="mode" value="{$_args[1]}">
    <input type="hidden" name="iesettei_id" value="{$in['iesettei_id']}">
    <input type="hidden" name="name" value="{$in['name']}">
    <input type="hidden" name="pass" value="{$in['pass']}">
    <input type="hidden" name="town_no" value="{$in['town_no']}">
    <input type="hidden" name="admin_pass" value="{$in['admin_pass']}">
    <input type="submit" value="戻る">
    </form>
    </div>
    </body></html>
    EOM;
    exit;
}

function message_only(...$_args)
{
    global $lockflag, $message_win;
    t_header(...$_args);
    // ロックフラグがあればロックディレクトリを削除
    if ($lockflag) {
        unlock(...$_args);
    }
    echo <<<EOM
    <div class="tw-dialog" role="status"><div class="tw-dialog-body">{$_args[0]}</div></div>
    EOM;
}

function error(...$_args)
{
    global $heder_flag, $lockflag, $message_win;
    if (((string)$heder_flag !== (string)'1')) {
        t_header('', 'sonomati');
    }
    // ロックフラグがあればロックディレクトリを削除
    if ($lockflag) {
        unlock(...$_args);
    }
    echo <<<EOM
    <div class="tw-dialog" role="alert">
    <h3 class="tw-dialog-title">ERROR !</h3>
    <div class="tw-dialog-body">{$_args[0]}</div>
    <!--TW_BACK-->
    </div>
    </body></html>
    EOM;
    exit;
}

function time_get(...$_args)
{
    global $date, $date2, $date_sec, $full_date, $return_hour, $week;
    $date_sec = pl_time();
    $_SERVER['TZ'] = 'JST-9';
    [$sec, $min, $hour, $mday, $mon, $year, $wday] = pl_pad(pl_localtime(pl_time()), 7);
    $week = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    // 日時のフォーマット
    $full_date = pl_sprintf('%04d/%02d/%02d(%s) %02d:%02d:%02d', (n($year) + 1900), (n($mon) + 1), $mday, $week[n($wday)], $hour, $min, $sec);
    $date = pl_sprintf('%04d/%02d/%02d', (n($year) + 1900), (n($mon) + 1), $mday);
    $date2 = pl_sprintf('%04d/%02d/%02d %02d:%02d', (n($year) + 1900), (n($mon) + 1), $mday, $hour, $min);
    $return_hour = $hour;
}

function byou_hiduke(...$_args)
{
    global $bh_date, $bh_full_date, $bh_return_hour, $bh_tukihi, $week;
    $_SERVER['TZ'] = 'JST-9';
    [$sec, $min, $hour, $mday, $mon, $year, $wday] = pl_pad(pl_localtime(n($_args[0])), 7);
    $week = ['日', '月', '火', '水', '木', '金', '土'];
    // 日時のフォーマット
    $bh_full_date = pl_sprintf('%02d月%02d日 %02d時%02d分', (n($mon) + 1), $mday, $hour, $min);
    $bh_date = pl_sprintf('%04d/%02d/%02d', (n($year) + 1900), (n($mon) + 1), $mday);
    $bh_tukihi = pl_sprintf('%02d/%02d', (n($mon) + 1), $mday);
    $bh_return_hour = $hour;
}

function get_host(...$_args)
{
    global $addr, $return_host;
    $return_host = pl_get_host(...$_args);
    $addr = $_SERVER['REMOTE_ADDR'];
}

function lock(...$_args)
{
    global $lockflag;
    $lockflag = 1;
}

function unlock(...$_args)
{
    global $lockflag;
    $lockflag = 0;
}

function get_cookie(...$_args)
{
    pl_get_cookie(...$_args);
}

function set_cookie(...$_args)
{
    pl_set_cookie(...$_args);
}

function id_check(...$_args)
{
    global $_, $name_flag, $pass_logfile, $return_id;
    // ver.1.40
    $pass_id = null;
    $pass_name = null;
    if (!(pl_open('IN', (string)($pass_logfile)))) { error("Open Error : {$pass_logfile}"); }
    $name_flag = 0;
    while (($_ = pl_readline('IN')) !== null) {
        [$pass_id, $pass_name] = pl_pad(pl_split('/<>/u', $_), 2);
        if (((string)$pass_name === (string)$_args[0])) {
            $name_flag = 1;
            $return_id = $pass_id;
            break;
        }
    }
    if ((n($name_flag) == 0)) {
        $return_id = '';
        error('その名前では登録されてません');
    }
    pl_close('IN');
}

function ie_sakujo_syori(...$_args)
{
    global $_, $hyouzi_town_hairetu, $iearuka_flag, $my_point_is, $my_town_is, $new_ori_ie_list, $ori_ie_list,
        $ori_ie_name, $ori_ie_para, $ori_ie_sentaku_point, $ori_ie_temp, $ori_ie_town, $town_sprit_matrix,
        $town_temp, $write_town_data;
    // ver.1.3ロックを排除
    // 家リストへの書き込み
    if (!(pl_open('IN', (string)($ori_ie_list)))) { error("Open Error : {$ori_ie_list}"); }
    $ori_ie_para = pl_readlines('IN');
    pl_close('IN');
    $new_ori_ie_list = [];
    $iearuka_flag = 0;
    $__s1 = $_ ?? null;
    foreach ($ori_ie_para ?? [] as $_) {
        ori_ie_sprit($_);
        if (((string)$_args[0] === (string)(string)($ori_ie_name))) {
            // タウン情報書き換え用に情報を取得
            $my_town_is = $ori_ie_town;
            $my_point_is = $ori_ie_sentaku_point;
            $iearuka_flag = 1;
            continue;
        }
        ori_ie_temp(...$_args);
        $new_ori_ie_list[] = $ori_ie_temp;
    }
    $_ = $__s1;
    // 家があった場合はログ更新
    if ((n($iearuka_flag) != 0)) {
        // タウン情報に書き込み
        $write_town_data = './log_dir/townlog' . $my_town_is . '.cgi';
        if (!(pl_open('TWI', (string)($write_town_data)))) { error("Open Error : {$write_town_data}"); }
        $hyouzi_town_hairetu = pl_readline('TWI');
        pl_close('TWI');
        $town_sprit_matrix = pl_split('/<>/u', $hyouzi_town_hairetu);
        $town_sprit_matrix[n($my_point_is)] = '空地';
        $town_temp = pl_join('<>', $town_sprit_matrix);
        // タウン情報更新
        if (!(pl_open('TWO', ">{$write_town_data}"))) { error("{$write_town_data}に書き込めません"); }
        pl_print('TWO', $town_temp);
        pl_close('TWO');
        // 家リスト更新
        if (!(pl_open('OIO', ">{$ori_ie_list}"))) { error("{$ori_ie_list}に書き込めません"); }
        pl_print('OIO', pl_join('', $new_ori_ie_list));
        pl_close('OIO');
    }
}

function decode(...$_args)
{
    pl_decode(...$_args);
}

function tajuucheck(...$_args)
{
    global $_, $admin_pass, $aite_k_temp, $aite_log_file, $aite_yobi3, $in, $k_temp, $k_yobi3, $list_access_byou,
        $list_access_time, $list_bank, $list_bijutu, $list_eigo, $list_etti, $list_first_access, $list_host,
        $list_job, $list_k_id, $list_kenkou, $list_kokugo, $list_kounyuu, $list_kyakuryoku, $list_looks, $list_love,
        $list_money, $list_name, $list_ongaku, $list_pass, $list_power, $list_rika, $list_sex, $list_speed,
        $list_suugaku, $list_syakai, $list_tairyoku, $list_unique, $list_wanryoku, $logfile, $my_log_file, $name,
        $rankingMember, $return_host;
    get_host(...$_args);
    if (!(pl_open('IN', (string)($logfile)))) { error("Open Error : {$logfile}"); }
    $rankingMember = pl_readlines('IN');
    pl_close('IN');
    $__s1 = $_ ?? null;
    foreach ($rankingMember ?? [] as $_) {
        [$list_k_id, $list_name, $list_pass, $list_money, $list_bank, $list_job, $list_kokugo, $list_suugaku, $list_rika, $list_syakai, $list_eigo, $list_ongaku, $list_bijutu, $list_looks, $list_tairyoku, $list_kenkou, $list_speed, $list_power, $list_wanryoku, $list_kyakuryoku, $list_love, $list_unique, $list_etti, $list_first_access, $list_kounyuu, $list_sex, $list_access_byou, $list_access_time, $list_host] = pl_pad(pl_split('/<>/u', $_), 29);
        // ver.1.30
        if (((string)$list_name === (string)$name)) {
            continue;
        }
        if ((((string)$return_host === (string)$list_host) && ((string)$in['admin_pass'] !== (string)(string)($admin_pass)))) {
            $k_yobi3 = "{$list_name}：{$name}";
            temp_routin(...$_args);
            log_kousin($my_log_file, $k_temp);
            // ver.1.40ここから
            openAitelog($list_k_id);
            $aite_yobi3 = "{$list_name}：{$name}";
            aite_temp_routin(...$_args);
            lock(...$_args);
            if (!(pl_open('OUT', ">{$aite_log_file}"))) { error("{$aite_log_file}が開けません"); }
            pl_print('OUT', $aite_k_temp);
            pl_close('OUT');
            unlock(...$_args);
            // ver.1.40ここまで
            error("多重登録は禁止されています。この記録は保存されました。<br>{$list_name}：{$name}");
        }
    }
    $_ = $__s1;
}

function kityou_syori(...$_args)
{
    global $date, $ginkoumeisai_file, $k_id, $meisai_kensuu, $torihikinaiyou;
    $my_tuutyou = [];
    lock(...$_args);
    $ginkoumeisai_file = "./member/{$k_id}/ginkoumeisai.cgi";
    if (!(pl_open('GM', (string)($ginkoumeisai_file)))) { error('自分の預金通帳ファイルが開けません'); }
    $my_tuutyou = pl_readlines('GM');
    pl_close('GM');
    time_get(...$_args);
    $torihikinaiyou = "{$date}<>{$_args[0]}<>{$_args[1]}<>{$_args[2]}<>{$_args[3]}<>{$_args[4]}<>\n";
    // ("明細",出金額,入金額,残高,普or定)
    pl_unshift($my_tuutyou, $torihikinaiyou);
    $meisai_kensuu = pl_count($my_tuutyou);
    if ((n($meisai_kensuu) > 100)) {
        pl_pop($my_tuutyou);
    }
    if (!(pl_open('GMO', ">{$ginkoumeisai_file}"))) { error('自分の預金通帳ファイルに書き込めません'); }
    pl_print('GMO', pl_join('', $my_tuutyou));
    pl_close('GMO');
    unlock(...$_args);
}

function aite_kityou_syori(...$_args)
{
    global $date, $ginkoumeisai_file, $meisai_kensuu, $torihikinaiyou;
    $aite_tuutyou = [];
    if (($_args[6] != 'lock_off')) {
        lock(...$_args);
    }
    $ginkoumeisai_file = "./member/{$_args[5]}/ginkoumeisai.cgi";
    if (!(pl_open('GM', (string)($ginkoumeisai_file)))) { error('相手の預金通帳ファイルが開けません'); }
    $aite_tuutyou = pl_readlines('GM');
    pl_close('GM');
    time_get(...$_args);
    $torihikinaiyou = "{$date}<>{$_args[0]}<>{$_args[1]}<>{$_args[2]}<>{$_args[3]}<>{$_args[4]}<>\n";
    // ("明細",出金額,入金額,残高,普or定,振込先ID,"lock_off or 無し")
    pl_unshift($aite_tuutyou, $torihikinaiyou);
    $meisai_kensuu = pl_count($aite_tuutyou);
    if ((n($meisai_kensuu) > 100)) {
        pl_pop($aite_tuutyou);
    }
    if (!(pl_open('GMO', ">{$ginkoumeisai_file}"))) { error('相手の預金通帳ファイルに書き込めません'); }
    pl_print('GMO', pl_join('', $aite_tuutyou));
    pl_close('GMO');
    if (($_args[6] != 'lock_off')) {
        unlock(...$_args);
    }
}

function news_kiroku(...$_args)
{
    global $_, $date2, $i, $new_news_kizi, $new_town_news, $news_file, $news_kensuu, $town_news;
    time_get(...$_args);
    if (!(pl_open('NS', (string)($news_file)))) { error("{$news_file}が開けません。"); }
    $town_news = pl_readlines('NS');
    pl_close('NS');
    $new_news_kizi = "{$date2}<>{$_args[0]}<>{$_args[1]}<>\n";
    pl_unshift($town_news, $new_news_kizi);
    $i = 0;
    $new_town_news = [];
    $__s1 = $_ ?? null;
    foreach ($town_news ?? [] as $_) {
        $new_town_news[] = $_;
        $i++;
        if ((n($i) >= n($news_kensuu))) {
            break;
        }
    }
    $_ = $__s1;
    if (!(pl_open('NSO', ">{$news_file}"))) { error("{$news_file}に書き込めません"); }
    pl_print('NSO', pl_join('', $new_town_news));
    pl_close('NSO');
}

function my_town_check(...$_args)
{
    global $_, $my_town_ari, $ori_ie_list, $return_my_town;
    if (!(pl_open('MTC', (string)($ori_ie_list)))) { error("Open Error : {$ori_ie_list}"); }
    $ori_ie_para = pl_readlines('MTC');
    pl_close('MTC');
    $my_town_ari = 0;
    $__s1 = $_ ?? null;
    foreach ($ori_ie_para ?? [] as $_) {
        [$ori_k_id, $ori_ie_name, $ori_ie_setumei, $ori_ie_image, $ori_ie_syubetu, $ori_ie_kentikubi, $ori_ie_town] = pl_pad(pl_split('/<>/u', $_), 7);
        if (((string)$_args[0] === (string)(string)($ori_ie_name))) {
            $return_my_town = (string)($ori_ie_town);
            $my_town_ari = 1;
            break;
        }
    }
    $_ = $__s1;
    if ((n($my_town_ari) == 0)) {
        $return_my_town = 'no_town';
    }
}

function town_keizaiup(...$_args)
{
    global $main_town_temp, $maintown_logfile;
    $town_data = './log_dir/townlog' . $_args[1] . '.cgi';
    if (!(pl_open('TW', (string)($town_data)))) { error("Open Error : {$town_data}"); }
    $keizai_town_hairetu = pl_readline('TW');
    pl_close('TW');
    $town_sprit_matrix = pl_split('/<>/u', $keizai_town_hairetu);
    if ((n($town_sprit_matrix[260]) == 0)) {
        $town_sprit_matrix[260] = pl_time();
    }
    $town_sprit_matrix[2] = n($town_sprit_matrix[2]) + n($_args[0]);
    $town_temp = pl_join('<>', $town_sprit_matrix);
    // タウン情報更新
    if (!(pl_open('TWO', ">{$town_data}"))) { error("{$town_data}に書き込めません"); }
    pl_print('TWO', $town_temp);
    pl_close('TWO');
    // メインタウン情報の更新
    if (!(pl_open('MT', (string)($maintown_logfile)))) { error("Open Error : {$maintown_logfile}"); }
    $maintown_para = pl_readline('MT');
    pl_close('MT');
    $main_town_sprit_matrix = pl_split('/<>/u', $maintown_para);
    if ((n($main_town_sprit_matrix[10]) == 0)) {
        $main_town_sprit_matrix[10] = pl_time();
    }
    $main_town_sprit_matrix[1] = n($main_town_sprit_matrix[1]) + n($_args[0]);
    $main_town_temp = pl_join('<>', $main_town_sprit_matrix);
    if (!(pl_open('OUT', ">{$maintown_logfile}"))) { error("Write Error : {$maintown_logfile}"); }
    pl_print('OUT', $main_town_temp);
    pl_close('OUT');
}

function town_haneiup(...$_args)
{
    global $main_town_temp, $maintown_logfile;
    $town_data = './log_dir/townlog' . $_args[0] . '.cgi';
    if (!(pl_open('TW', (string)($town_data)))) { error("Open Error : {$town_data}"); }
    $keizai_town_hairetu = pl_readline('TW');
    pl_close('TW');
    $town_sprit_matrix = pl_split('/<>/u', $keizai_town_hairetu);
    if ((n($town_sprit_matrix[260]) == 0)) {
        $town_sprit_matrix[260] = pl_time();
    }
    $town_sprit_matrix[3]++;
    $town_temp = pl_join('<>', $town_sprit_matrix);
    // タウン情報更新
    if (!(pl_open('TWO', ">{$town_data}"))) { error("{$town_data}に書き込めません"); }
    pl_print('TWO', $town_temp);
    pl_close('TWO');
    // メインタウン情報の更新
    if (!(pl_open('MT', (string)($maintown_logfile)))) { error("Open Error : {$maintown_logfile}"); }
    $maintown_para = pl_readline('MT');
    pl_close('MT');
    $main_town_sprit_matrix = pl_split('/<>/u', $maintown_para);
    if ((n($main_town_sprit_matrix[10]) == 0)) {
        $main_town_sprit_matrix[10] = pl_time();
    }
    $main_town_sprit_matrix[2]++;
    $main_town_temp = pl_join('<>', $main_town_sprit_matrix);
    if (!(pl_open('OUT', ">{$maintown_logfile}"))) { error("Write Error : {$maintown_logfile}"); }
    pl_print('OUT', $main_town_temp);
    pl_close('OUT');
}

function data_save(...$_args)
{
    return pl_data_save(...($_args ?? []));
}

function data_read(...$_args)
{
    return pl_data_read(...($_args ?? []));
}

function kodomo_siokuri(...$_args)
{
    global $_, $all_kodomo, $bank, $job_hairetu, $job_kihonkyuu, $job_kyuuyo, $job_name, $kod_bijutu, $kod_eigo,
        $kod_etti, $kod_job, $kod_kenkou, $kod_kokugo, $kod_kyakuryoku, $kod_looks, $kod_love, $kod_name, $kod_num,
        $kod_ongaku, $kod_oya1, $kod_oya2, $kod_power, $kod_rika, $kod_speed, $kod_suugaku, $kod_syakai,
        $kod_tairyoku, $kod_unique, $kod_wanryoku, $kod_yobi1, $kod_yobi10, $kod_yobi2, $kod_yobi3, $kod_yobi4,
        $kod_yobi5, $kod_yobi6, $kod_yobi7, $kod_yobi8, $kod_yobi9, $kodomo_file, $kodomoiruka_flag, $kono_nenrei,
        $name, $now_time, $siokuri_kingaku, $top_koumoku;
    // 職業ごとの給料をハッシュに代入
    if (!(pl_open('SP', './dat_dir/job.dat'))) { error('Open Error : ./dat_dir/job.dat'); }
    $top_koumoku = pl_readline('SP');
    $job_hairetu = pl_readlines('SP');
    pl_close('SP');
    $__s1 = $_ ?? null;
    foreach ($job_hairetu ?? [] as $_) {
        job_sprit($_);
        $job_kihonkyuu[$job_name] = $job_kyuuyo;
    }
    $_ = $__s1;
    if (!(pl_open('KOD', (string)($kodomo_file)))) { error("Open Error : {$kodomo_file}"); }
    $all_kodomo = pl_readlines('KOD');
    pl_close('KOD');
    $now_time = pl_time();
    $kodomoiruka_flag = 0;
    $__s2 = $_ ?? null;
    foreach ($all_kodomo ?? [] as $_) {
        [$kod_num, $kod_name, $kod_oya1, $kod_oya2, $kod_job, $kod_kokugo, $kod_suugaku, $kod_rika, $kod_syakai, $kod_eigo, $kod_ongaku, $kod_bijutu, $kod_looks, $kod_tairyoku, $kod_kenkou, $kod_speed, $kod_power, $kod_wanryoku, $kod_kyakuryoku, $kod_love, $kod_unique, $kod_etti, $kod_yobi1, $kod_yobi2, $kod_yobi3, $kod_yobi4, $kod_yobi5, $kod_yobi6, $kod_yobi7, $kod_yobi8, $kod_yobi9, $kod_yobi10] = pl_pad(pl_split('/<>/u', $_), 32);
        if ((n($kod_yobi8) != 1)) {
            continue;
        }
        if ((((string)$kod_oya1 === (string)$name) || ((string)$kod_oya2 === (string)$name))) {
            $kono_nenrei = pl_int((((n($now_time) - n($kod_yobi1))) / (((60 * 60) * 24))));
            $siokuri_kingaku = (((n($job_kihonkyuu[$kod_job]) * n($kono_nenrei))) + ((n($kod_yobi4) * 10)));
            $siokuri_kingaku = pl_int((n($siokuri_kingaku) / 4));
            $bank = n($bank) + n($siokuri_kingaku);
            kityou_syori("仕送り←{$kod_name}", '', $siokuri_kingaku, $bank, '普');
        }
    }
    $_ = $__s2;
}

