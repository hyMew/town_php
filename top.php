<?php
// このファイルは top.pl (Perl) から自動変換されたものです。
// 町CGI  Copyright (c) 2003-2004 brassiere  http://brassiere.jp/

// ###建築確認画面

function main_view(...$_args)
{
    global $_, $aisatu_data_list, $aisatu_logfile, $aisatu_table, $all_guest, $douzi_login_ninzuu, $err,
        $genzai_zikoku, $genzaino_ninzuu, $guestfile, $hyouzi_check, $hyouzi_town_hairetu, $i, $idousyudan, $in,
        $kakaruzikan_com, $logfile, $logout_time, $master_ad, $matiidou_time2, $mem_lock_num, $mise_type, $name,
        $new_all_guest, $page_back, $return_hour, $return_return_hour, $sanka_flag, $sanka_hyouzi, $sanka_hyouzi_iti,
        $sanka_hyouzi_kinou, $sanka_name, $sanka_timer, $sanka_tmp, $sankaninzuu, $sentaku_point, $sotonoiro,
        $tateziku_kigou, $this_town_no, $top_aisatu_hyouzi, $top_aisatu_hyouzi_iro1, $top_aisatu_hyouzi_iro2,
        $top_aisatu_hyouzikensuu, $top_information, $town_data, $town_sprit_matrix, $unit, $yokoziku_bangou,
        $yokoziku_koumoku;
    if (($in['command'] == 'mati_idou')) {
        t_header('syokudou_style');
        if ((n($matiidou_time2) <= 0)) {
            $kakaruzikan_com = 'すぐに着きます。';
        } else {
            $kakaruzikan_com = "{$matiidou_time2}秒ほどお待ちください。";
        }
        echo <<<EOM
        <div class="tw-dialog" role="status"><div class="tw-dialog-body" style="text-align:center">{$idousyudan}移動中...<br>{$kakaruzikan_com}</div></div>
        </body></html>
        EOM;
        exit;
    }
    // パーツ情報を取得
    town_no_get(...$_args);
    // ver.1.40
    get_unit(...$_args);
    // ver.1.40
    kozin_house(...$_args);
    // ver.1.40
    simaitosi(...$_args);
    // ver.1.40
    admin_parts(...$_args);
    // ver.1.40
    // ####街のログを開いて画面展開
    $town_data = './log_dir/townlog' . $this_town_no . '.cgi';
    if (!(pl_open('TW', (string)($town_data)))) { error("Open Error : {$town_data}"); }
    $hyouzi_town_hairetu = pl_readline('TW');
    pl_close('TW');
    town_sprit($hyouzi_town_hairetu);
    // 家建築確認画面なら予定地を表示
    if ((($in['mode'] == 'kentiku_do') && ($in['command'] == 'kakunin'))) {
        $sentaku_point = (n($in['tateziku']) + n($in['yokoziku']));
        if (($town_sprit_matrix[n($sentaku_point)] != '空地')) {
            error('選択した場所は空き地ではありません！');
        }
        $town_sprit_matrix[n($sentaku_point)] = '地';
    }
    t_header('', 'sonomati');
    get_cookie(...$_args);
    time_get(...$_args);
    // 街の中の背景色
    if ((n($return_return_hour) >= 22)) {
        $sotonoiro = '#333366';
    } elseif ((n($return_hour) >= 18)) {
        $sotonoiro = '#666699';
    } elseif ((n($return_hour) >= 16)) {
        $sotonoiro = '#ff9966';
    } elseif ((n($return_hour) >= 10)) {
        $sotonoiro = '#ffff99';
    } elseif ((n($return_hour) >= 7)) {
        $sotonoiro = '#ffcc66';
    } elseif ((n($return_hour) >= 0)) {
        $sotonoiro = '#333366';
    }
    // ver.1.30ここから
    echo '<div class="tw-layout' . (($in['mode'] == '') ? ' tw-guest' : '') . '">';
    $sanka_html = '';
    // 参加者表示
    if ((n($sanka_hyouzi_kinou) == 1)) {
        pl_open('GUEST', (string)($guestfile));
        $all_guest = pl_readlines('GUEST');
        pl_close('GUEST');
        $genzai_zikoku = pl_time();
        $genzaino_ninzuu = pl_count($all_guest);
        $sanka_flag = 0;
        $new_all_guest = [];
        $__s1 = $_ ?? null;
        foreach ($all_guest ?? [] as $_) {
            [$sanka_timer, $sanka_name, $hyouzi_check] = pl_pad(pl_split('/<>/u', $_), 3);
            if (((string)$name === (string)(string)($sanka_name))) {
                if ($in['sanka_hyouzi_on']) {
                    $hyouzi_check = (string)($in['sanka_hyouzi_on']);
                }
                $sanka_flag = 1;
                $sanka_timer = $genzai_zikoku;
            }
            if (((n($genzai_zikoku) - n($logout_time)) > n($sanka_timer))) {
                continue;
            }
            $sanka_tmp = "{$sanka_timer}<>{$sanka_name}<>{$hyouzi_check}<>\n";
            $new_all_guest[] = $sanka_tmp;
            if (($hyouzi_check == 'on')) {
                $sanka_hyouzi .= "{$sanka_name}<span style=\"color:#FF7F50;font-size:10px\">★</span>";
            }
        }
        $_ = $__s1;
        if (((n($sanka_flag) == 0) && ($in['mode'] != ''))) {
            if (((n($genzaino_ninzuu) >= n($douzi_login_ninzuu)) && ($in['iiyudane'] == ''))) {
                error("現在、同時ログイン制限{$douzi_login_ninzuu}人を超えています。恐れ入りますが、しばらくしてからログインしてください。");
            }
            // ver.1.30
            if ($in['sanka_hyouzi_on']) {
                $new_all_guest[] = "{$genzai_zikoku}<>{$name}<>{$in['sanka_hyouzi_on']}<>\n";
                if (($in['sanka_hyouzi_on'] == 'on')) {
                    $sanka_hyouzi .= "{$name}<span style=\"color:#FF7F50;font-size:10px\">★</span>";
                }
            } else {
                $new_all_guest[] = "{$genzai_zikoku}<>{$name}<>{$mise_type}<>\n";
                if (($mise_type == 'on')) {
                    $sanka_hyouzi .= "{$name}<span style=\"color:#FF7F50;font-size:10px\">★</span>";
                }
            }
        }
        // ver.1.40ここから
        if ((n($mem_lock_num) == 0)) {
            $err = data_save($guestfile, ...($new_all_guest ?? []));
            if ($err) {
                error((string)($err));
            }
        } else {
            lock(...$_args);
            pl_open('GUEST', ">{$guestfile}");
            pl_print('GUEST', pl_join('', $new_all_guest));
            pl_close('GUEST');
            unlock(...$_args);
        }
        // ver.1.40ここまで
        $sankaninzuu = pl_count($new_all_guest);
        if ((n($sanka_hyouzi_iti) == 1)) {
            echo "<details class=\"tw-card tw-fold tw-guests\" data-fold=\"guests\" style=\"flex-basis:100%\" open><summary class=\"tw-fold-title\">現在の総参加者（{$sankaninzuu}人）</summary>{$sanka_hyouzi}</details>";
        }
    }
    // if（参加者を表示させる）の閉じ
    $filesize_check = pl_stat($logfile);
    if ((n($filesize_check[7]) > 600000)) {
        error("{$logfile}の容量に問題があります。管理者（{$master_ad}）までお知らせください。");
    }
    // ヘルプウインドウ
    echo <<<EOM
    <div class="tw-map-col"><div class="tw-map-stick">
    <details class="tw-card tw-fold tw-fold-map" data-fold="map" open><summary class="tw-fold-title">地図</summary>
    <form name="foMes5" class="tw-help" onsubmit="return false"><input type="text" name="TeMes5" readonly aria-label="説明" placeholder="アイコンに触れると説明が表示されます"></form>
    <div class="tw-map-scroll">
          <table class="tw-map" style="background-color:{$sotonoiro};">
            <tr style="{$page_back[n($this_town_no)]}">
              <td></td>
    EOM;
    // ヨコの番号部分出力
    foreach (pl_range(1, 16) as $yokoziku_koumoku) {
        echo "<td height=\"10\" width=\"32\" align=\"center\" class=\"migi\">{$yokoziku_koumoku}</td>";
    }
    echo '</tr>';
    // ヨコの番号(td)の数だけタテの記号(tr)を出力
    $i = 21;
    foreach (pl_range('A', 'L') as $tateziku_kigou) {
        echo "<tr valign=\"center\"><td height=\"32\" width=\"10\" style=\"{$page_back[n($this_town_no)]}\" align=\"center\" class=\"sita\">{$tateziku_kigou}</td>\n";
        foreach (pl_range(1, 16) as $yokoziku_bangou) {
            if ($town_sprit_matrix[(n($yokoziku_bangou) + n($i))]) {
                echo "{$unit[$town_sprit_matrix[(n($yokoziku_bangou) + n($i))]]}\n";
            } else {
                echo "<td height=\"32\" width=\"32\"></td>\n";
            }
        }
        echo "</tr>\n";
        $i = n($i) + 17;
    }
    echo '</table></div></details>';
    // ver.1.3ここから
    if (!(pl_open('IN', (string)($aisatu_logfile)))) { error("Open Error : {$aisatu_logfile}"); }
    $aisatu_data_list = pl_readlines('IN');
    pl_close('IN');
    if ((n($top_aisatu_hyouzi) == 1)) {
        if ((pl_count($aisatu_data_list) != '')) {
            $aisatu_table .= '<details class="tw-card tw-fold tw-aisatu" data-fold="aisatu" open><summary class="tw-fold-title">あいさつ</summary>';
        }
        $i = 0;
        $__s2 = $_ ?? null;
        foreach ($aisatu_data_list ?? [] as $_) {
            [$a_num, $a_name, $a_date, $a_com, $a_syurui, $a_yobi2] = pl_pad(pl_split('/<>/u', $_), 6);
            $aisatu_table .= "<span  style=\"color:{$top_aisatu_hyouzi_iro1};\">{$a_name}：</span><span  style=\"color:{$top_aisatu_hyouzi_iro2};\">{$a_com}</span><br>";
            $i++;
            if ((n($i) >= n($top_aisatu_hyouzikensuu))) {
                break;
            }
        }
        $_ = $__s2;
        if ((pl_count($aisatu_data_list) != '')) {
            $aisatu_table .= '</details>';
        }
    }
    if (($in['name'] == '')) {
        echo (string)($top_information);
    } else {
        echo (string)($aisatu_table);
    }
    echo '</div></div><div class="tw-side-col">';
    // ver.1.3ここまで
    if (($in['mode'] == '')) {
        top_gamen(...$_args);
    } elseif ((($in['mode'] == 'kentiku_do') && ($in['command'] == 'kakunin'))) {
        kentiku_kakunin(...$_args);
    } else {
        town_jouhou($this_town_no);
        loged_gamen(...$_args);
    }
    // ver.1.30ここから
    if (((n($sanka_hyouzi_kinou) == 1) && (n($sanka_hyouzi_iti) == 0))) {
        echo "</div><details class=\"tw-card tw-fold tw-guests\" data-fold=\"guests\" style=\"flex-basis:100%\" open><summary class=\"tw-fold-title\">現在の総参加者（{$sankaninzuu}人）</summary>{$sanka_hyouzi}</details><div>";
    }
    // ver.1.30ここまで
    echo '</div></div>';
    hooter('', '');
}

function kentiku_kakunin(...$_args)
{
    global $housu_nedan, $ie_hash, $img_dir, $in, $k_id, $kauieno_rank, $kensetu_hiyou, $ori_ie_image, $script,
        $sentaku_point, $town_hairetu, $town_tika_hairetu;
    if (($in['iegazou'] == '')) {
        error('家の画像が選択されていません');
    }
    if (($in['matirank'] == '')) {
        error('家のランクが選択されていません');
    }
    $ori_ie_image = "{$img_dir}/{$in['iegazou']}";
    if (((string)$in['matirank'] === (string)'0')) {
        $kauieno_rank = 'A';
    } elseif (((string)$in['matirank'] === (string)'1')) {
        $kauieno_rank = 'B';
    } elseif (((string)$in['matirank'] === (string)'2')) {
        $kauieno_rank = 'C';
    } elseif (((string)$in['matirank'] === (string)'3')) {
        $kauieno_rank = 'D';
    }
    // 金額計算
    $kensetu_hiyou = ((n($town_tika_hairetu[n($in['mati_sentaku'])]) + n($ie_hash[$in['iegazou']])) + n($housu_nedan[n($in['matirank'])]));
    echo <<<EOM
    <div class="tw-card">
    <h2 class="tw-card-title" style="text-align:center">建築仕様書</h2>
    <div class="tyuu">建築する街</div>
    {$town_hairetu[n($in['mati_sentaku'])]}（地価：{$town_tika_hairetu[n($in['mati_sentaku'])]}万円）<br><br>
    <div class="tyuu">場所</div>
    左の<img src ="{$img_dir}/kentiku_yotei.gif" width="32" height="32" border="0" align="middle">の位置に建てられます。<br><br>
    <div class="tyuu">家（外装）</div>
    <img src="{$ori_ie_image}" width="32" height="32"> （{$ie_hash[$in['iegazou']]}万円）<br><br>
    <div class="tyuu">家（内装）</div>
    {$kauieno_rank}（価格：{$housu_nedan[n($in['matirank'])]}万円）<br><br>
    <div class="job_messe">建築費用は<br>
    <div class="dai">{$kensetu_hiyou} 万円</div>
    となります。</div>
    
    <form method="POST"action="{$script}">
    	<input type="hidden" name="mode" value="kentiku_do">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="ori_k_id" value="{$k_id}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="hidden" name="ori_ie_town" value="{$in['mati_sentaku']}">
    	<input type="hidden" name="ori_ie_sentaku_point" value="{$sentaku_point}">
    	<input type="hidden" name="ori_ie_tateziku" value="{$in['tateziku']}">
    	<input type="hidden" name="ori_ie_yokoziku" value="{$in['yokoziku']}">
    	<input type="hidden" name="ori_ie_image" value="{$ori_ie_image}">
    	<input type="hidden" name="ori_ie_rank" value="{$in['matirank']}">
    	<input type="hidden" name="kensetu_hiyou" value="{$kensetu_hiyou}">
    	<input type="submit" class="tw-submit" value="この内容で家を建てる">
    	<div style="text-align:center"><!--TW_BACK--></div>
    </form>
    </div>
    EOM;
}

