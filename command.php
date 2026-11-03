<?php
// このファイルは command.pl (Perl) から自動変換されたものです。
// 町CGI  Copyright (c) 2003-2004 brassiere  http://brassiere.jp/

// #########卸店の出力
// ####建築
// ######施工作業
// #####卸問屋
// ####卸商品購入処理
// ######スポーツクラブ
// ###トレーニング
// ######学校
// ###講習を受ける
// ######食堂
// ###食事処理
// ######デパート
// ###購入処理
// ####購入価格のサーバー側再計算（ポート時に追加）
// メッセージ送信
// メール送信処理
// ギフト送付処理

function town_no_get(...$_args)
{
    global $_, $iearuflag, $in, $ori_ie_list, $ori_ie_name, $ori_ie_para, $ori_ie_town, $this_town_no;
    // 街情報を変数に代入
    if (($_args[0] == '')) {
        // 街指定が無く名前指定がある場合（ログイン時）自分の家のある街をチェック
        if ($in['name']) {
            if (!(pl_open('IN', (string)($ori_ie_list)))) { error("Open Error : {$ori_ie_list}"); }
            $ori_ie_para = pl_readlines('IN');
            pl_close('IN');
            $iearuflag = 0;
            $__s1 = $_ ?? null;
            foreach ($ori_ie_para ?? [] as $_) {
                ori_ie_sprit($_);
                if (((string)$ori_ie_name === (string)(string)($in['name']))) {
                    $this_town_no = $ori_ie_town;
                    $iearuflag = 1;
                    break;
                }
            }
            $_ = $__s1;
            if ((n($iearuflag) == 0)) {
                $this_town_no = 0;
            }
            // 街指定が無く名前指定も無い場合（トップページへのアクセス）
        } else {
            $this_town_no = 0;
        }
        // 街指定があればその街のログを開く
    } else {
        $this_town_no = $_args[0];
    }
    if (!($in['town_no'])) {
        $in['town_no'] = (string)($this_town_no);
    }
}

function by_iekey(...$_args)
{
    global $a, $b, $ie_values;
    return (n($ie_values[n($a)]) <=> n($ie_values[n($b)]));
}

function kentiku(...$_args)
{
    global $_, $housu_nedan, $i, $ie_hash, $ie_key, $ie_keys, $ie_rank, $ie_val, $ie_values, $iegazou, $img_dir, $in,
        $mati_kakunin_open, $mati_sentaku, $script, $tateziku, $town_hairetu, $yokoziku;
    t_header('kentiku_style');
    $i = 0;
    $__s1 = $_ ?? null;
    foreach ($town_hairetu ?? [] as $_) {
        $mati_sentaku .= "街の名前<option value=\"{$i}\">{$_}</option>\n";
        $mati_kakunin_open .= "<a href=\"{$script}?town_no={$i}\" target=\"_blank\">☆{$_}　</a>";
        $i++;
    }
    $_ = $__s1;
    $i = 21;
    $__s2 = $_ ?? null;
    foreach (pl_range('A', 'M') as $_) {
        $tateziku .= "縦軸<option value=\"{$i}\">{$_}</option>\n";
        $i = n($i) + 17;
    }
    $_ = $__s2;
    $__s3 = $_ ?? null;
    foreach (pl_range(1, 16) as $_) {
        $yokoziku .= "横軸<option value=\"{$_}\">{$_}</option>\n";
    }
    $_ = $__s3;
    // 家画像をハッシュから展開
    foreach ($ie_hash ?? [] as $ie_key => $ie_val) {
        $ie_keys[] = $ie_key;
        $ie_values[] = $ie_val;
    }
    $ie_rank = pl_slice($ie_keys, pl_sort('by_iekey', pl_range(0, (pl_count($ie_keys) - 1))));
    $i = 1;
    $__s4 = $_ ?? null;
    foreach ($ie_rank ?? [] as $_) {
        $iegazou .= "<td align=\"center\"><input type=\"radio\" name=\"iegazou\" value=\"{$_}\"><br><img src=\"{$img_dir}/{$_}\" width=\"32\" height=\"32\"><br>{$ie_hash[$_]}万円\n";
        if (((n($i) % 8) == 0)) {
            $iegazou .= '</tr><tr>';
        }
        $i++;
    }
    $_ = $__s4;
    echo <<<EOM
    	<table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr>
    	<td bgcolor="#ffffff">空き地スペースであれば好きな街の好きな場所に自分の家を建てることができます。<br>
    	建築費用は「その街の地価」＋「家の値段（外装費）」＋「内装費（ランクA～D）」です。家を持つと、以後ログイン時に最初にその街へ行くことになります。</td>
    	<td  bgcolor="#333333" align="center" width="35%"><img src="{$img_dir}/kentiku_tytle.gif"></td>
    	</tr></table><br>
    	<form method="POST" action="{$script}">
    	<input type="hidden" name="mode" value="kentiku_do">
    	<input type="hidden" name="command" value="kakunin">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<table width="80%" border="0" cellspacing="0" cellpadding="8" align="center" class="yosumi">
    	<tr><td valign="top">
    	<div class="honbun2">●建てる場所を指定してください。家は<img src="{$img_dir}/akiti.gif" width="32" height="32">の場所にのみ建てることができます。</div><br>
    	街の名前 <select name="mati_sentaku">{$mati_sentaku}</select>
    	縦軸 <select name="tateziku">{$tateziku}</select>
    	横軸 <select name="yokoziku">{$yokoziku}</select><br>
    	▼場所がわからない場合、下のリンクでそれぞれの街を開いて確認してください。<br>
    	{$mati_kakunin_open}<br><br>
    	<div class="honbun2">●家の選択</div>
    	<table boader="0" cellspacing="0" cellpadding="5" width="100%"><tr>
    	{$iegazou}
    	</tr></table>
    	</td></tr><tr><td>
    	<br><div class="honbun2">●家のランクを指定してください（内装費）。</div><br>
    	<input type="radio" name="matirank" value="0">Ａランク：{$housu_nedan[0]}万円（４コンテンツ同時表示が可能）<br>
    	<input type="radio" name="matirank" value="1">Ｂランク：{$housu_nedan[1]}万円（３コンテンツ同時表示が可能）<br>
    	<input type="radio" name="matirank" value="2">Ｃランク：{$housu_nedan[2]}万円（２コンテンツ同時表示が可能）<br>
    	<input type="radio" name="matirank" value="3">Ｄランク：{$housu_nedan[3]}万円（１コンテンツ同時表示が可能）<br><br>
    	※自分の家を建てると以下のコンテンツを家の中に設置することができます。<br><br>
    	<span style="color:#ff6600">○簡易掲示板</span>：家主はもちろん家を訪れた人が誰でも書き込みできる掲示板です。<br>
    	<span style="color:#ff6600">○商売スペース</span>：自分の好きな商品を卸問屋から仕入れ、自由に価格を設定して販売することができます（将来的にはスクールやスポーツジムなど他の商売を開くことも可能になる予定です）。<br>
    	<span style="color:#ff6600">○独自URLスペース</span>：自分の好きなURLを指定してIFRAMEウインドウに表示することができます。アイディア次第で自分の家をどんな内容にすることも可能なので、自分のホームページをお持ちの方にはお奨めです。<br>
    	<span style="color:#ff6600">○家主掲示板</span>：管理者のみが書き込みできる掲示板です。日記やエッセイなど自分の文章を表現したい方にお奨めです。<br><br>
    	Dランクでは上記コンテンツよりひとつだけ選んで設置することができ、Aランクでは全てのコンテンツを設置できることになります。<br>また、全ての家には「さい銭箱」が置かれていて訪問者の方が自由にさい銭することができます。
    EOM;
    echo <<<EOM
    	<br><br><div align="center"><input type="submit" value=" 確認画面へ  "></div>
    	</td></tr></table>
    	<div align="center"><!--TW_BACK--></div>
    	</body></html>
    EOM;
    exit;
}

function kentiku_do(...$_args)
{
    global $_, $date_sec, $housu_nedan, $hyouzi_town_hairetu, $ie_hash, $img_dir, $in, $k_id, $k_temp,
        $kentiku_gazou, $message_win, $money, $my_log_file, $ori_ie_list, $ori_ie_para, $ori_ie_temp, $ori_k_id,
        $script, $town_hairetu, $town_sprit_matrix, $town_temp, $town_tika_hairetu, $write_town_data;
    // 確認画面出力
    if (($in['command'] == 'kakunin')) {
        main_view($in['mati_sentaku']);
        exit;
    }
    if ((!pl_match('/^\\d+$/u', $in['ori_ie_town']) || (n($in['ori_ie_town']) >= pl_count($town_hairetu)))) {
        error('街の指定が不正です。');
    }
    if (!pl_match('/^[0-3]$/u', $in['ori_ie_rank'])) {
        error('家のランクが不正です。');
    }
    if (((!pl_match('/^\\d+$/u', $in['ori_ie_sentaku_point']) || (n($in['ori_ie_sentaku_point']) < 4)) || (n($in['ori_ie_sentaku_point']) > 258))) {
        error('場所の指定が不正です。');
    }
    $in['ori_k_id'] = $k_id;
    $kentiku_gazou = $in['ori_ie_image'];
    $kentiku_gazou = preg_replace('/^.*\\//u', '', (string)$kentiku_gazou, 1);
    if (($ie_hash[$kentiku_gazou] == '')) {
        error('家の画像が不正です。');
    }
    $in['ori_ie_image'] = "{$img_dir}/{$kentiku_gazou}";
    $in['kensetu_hiyou'] = ((n($town_tika_hairetu[n($in['ori_ie_town'])]) + n($ie_hash[$kentiku_gazou])) + n($housu_nedan[n($in['ori_ie_rank'])]));
    if (!(pl_is_admin_player(...$_args))) {
        if (((n($in['kensetu_hiyou']) * 10000) > n($money))) {
            error('お金が足りません。');
        }
    }
    lock(...$_args);
    // 家リストへの書き込み
    if (!(pl_open('IN', (string)($ori_ie_list)))) { error("Open Error : {$ori_ie_list}"); }
    $ori_ie_para = pl_readlines('IN');
    pl_close('IN');
    $__s1 = $_ ?? null;
    foreach ($ori_ie_para ?? [] as $_) {
        ori_ie_sprit($_);
        if (((string)$in['ori_k_id'] === (string)(string)($ori_k_id))) {
            error('家は一人１軒しか建てられません。引っ越しの場合は、家管理画面から家を売却したのち新たに購入してください。');
        }
    }
    $_ = $__s1;
    time_get(...$_args);
    $ori_ie_temp = "{$in['ori_k_id']}<>{$in['name']}<>『{$in['name']}』の家<>{$in['ori_ie_image']}<><>{$date_sec}<>{$in['ori_ie_town']}<>{$in['ori_ie_tateziku']}<>{$in['ori_ie_yokoziku']}<>{$in['ori_ie_sentaku_point']}<>{$in['ori_ie_rank']}<><><><><>\n";
    pl_unshift($ori_ie_para, $ori_ie_temp);
    // タウン情報に書き込み
    $write_town_data = './log_dir/townlog' . $in['ori_ie_town'] . '.cgi';
    if (!(pl_open('TWI', (string)($write_town_data)))) { error("Open Error : {$write_town_data}"); }
    $hyouzi_town_hairetu = pl_readline('TWI');
    pl_close('TWI');
    $town_sprit_matrix = pl_split('/<>/u', $hyouzi_town_hairetu);
    if (($town_sprit_matrix[n($in['ori_ie_sentaku_point'])] != '空地')) {
        error('選択した場所は空き地ではありません。他の方が既に購入してしまったようです。');
    }
    $town_sprit_matrix[n($in['ori_ie_sentaku_point'])] = (string)($in['ori_k_id']);
    $town_temp = pl_join('<>', $town_sprit_matrix);
    // タウン情報更新
    if (!(pl_open('TWO', ">{$write_town_data}"))) { error("{$write_town_data}に書き込めません"); }
    pl_print('TWO', $town_temp);
    pl_close('TWO');
    // 家リスト更新
    if (!(pl_open('OIO', ">{$ori_ie_list}"))) { error("{$ori_ie_list}に書き込めません"); }
    pl_print('OIO', pl_join('', $ori_ie_para));
    pl_close('OIO');
    // ニュース記録
    news_kiroku('家', "{$in['name']}さんが「{$town_hairetu[n($in['ori_ie_town'])]}」に家を建築しました。");
    // ver.1.3
    unlock(...$_args);
    // ログ更新
    if (!(pl_is_admin_player(...$_args))) {
        $money = n($money) - (n($in['kensetu_hiyou']) * 10000);
    }
    temp_routin(...$_args);
    log_kousin($my_log_file, $k_temp);
    t_header('', 'sonomati');
    echo <<<EOM
    	<div align="center"><br><table  border="0"  cellspacing="5" cellpadding="0" width="300" style="{$message_win}"><tr><td>
    <span class="job_messe">
    おめでとうございます！<br>
    {$town_hairetu[n($in['ori_ie_town'])]}に家を建築いたしました。<br>
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

function my_omise_orosi_rou(...$_args)
{
    global $change_form, $house_type, $in, $orosisakinomise, $script, $settei_t_color, $unit;
    $orosisakinomise = '自分のお店の仕入れをします。';
    $settei_t_color = '#336699';
    if ((n($unit[(string)($house_type)]) != 0)) {
        $change_form = "<form method=\"POST\" action=\"{$script}\"><input type=\"hidden\" name=\"mode\" value=\"orosi\"><input type=\"hidden\" name=\"iesettei_id\" value=\"{$house_type}\"><input type=\"hidden\" name=\"name\" value=\"{$in['name']}\"><input type=\"hidden\" name=\"pass\" value=\"{$in['pass']}\"><input type=\"hidden\" name=\"town_no\" value=\"{$in['town_no']}\"><input type=\"submit\" value=\"配偶者のお店の仕入れをする\"></form>";
    }
}

function haiguusya_omise_orosi_rou(...$_args)
{
    global $change_form, $in, $k_id, $orosisakinomise, $script, $settei_t_color, $unit;
    $orosisakinomise = '配偶者のお店の仕入れをします。';
    $settei_t_color = '#ff6666';
    if ((n($unit[(string)($k_id)]) != 0)) {
        $change_form = "<form method=\"POST\" action=\"{$script}\"><input type=\"hidden\" name=\"mode\" value=\"orosi\"><input type=\"hidden\" name=\"iesettei_id\" value=\"{$k_id}\"><input type=\"hidden\" name=\"name\" value=\"{$in['name']}\"><input type=\"hidden\" name=\"pass\" value=\"{$in['pass']}\"><input type=\"hidden\" name=\"town_no\" value=\"{$in['town_no']}\"><input type=\"submit\" value=\"自分のお店の仕入れをする\"></form>";
    }
}

function orosi(...$_args)
{
    global $_, $alldata, $calory_hyouzi, $change_form, $data, $date2, $house_type, $i, $img_dir, $in, $k_id, $key,
        $keys, $kounyuubotan, $kyouno_hairetu, $maeno_syo_syubetu, $maintown_logfile, $maintown_para,
        $mise_zaiko_gendo, $mise_zaiko_limit, $misearuyoflag, $mt_orosiflag, $mt_t_time, $mt_temp, $mt_yobi9,
        $my_item_list, $my_omise_zaiko_itiran, $new_syouhin_hairetu, $new_syouhin_hairetu2, $omise_log_file,
        $ori_ie_hairetu, $ori_ie_list, $ori_k_id, $orosi_logfile, $orosi_sinakazu, $orosisakinomise, $return_hour,
        $script, $settei_t_color, $syo_bijutu, $syo_cal, $syo_comment, $syo_eigo, $syo_etti, $syo_hinmoku,
        $syo_kankaku, $syo_kenkou, $syo_kokugo, $syo_kyakuryoku, $syo_looks, $syo_love, $syo_nedan, $syo_ongaku,
        $syo_power, $syo_rika, $syo_sintai_syouhi, $syo_speed, $syo_suugaku, $syo_syakai, $syo_syubetu, $syo_taikyuu,
        $syo_taikyuu_tani, $syo_tairyoku, $syo_temp, $syo_unique, $syo_wanryoku, $syo_zaiko, $syo_zunou_syouhi,
        $taikyuu_hyouzi_seikei, $ton_zaiko_tyousei, $top_koumoku, $unit;
    if (!(pl_open('IN', (string)($maintown_logfile)))) { error("Open Error : {$maintown_logfile}"); }
    $maintown_para = pl_readline('IN');
    main_town_sprit($maintown_para);
    pl_close('IN');
    if ((n($mt_orosiflag) == 0)) {
        time_get(...$_args);
        // #卸フラグが０で規定時間を過ぎていたら
        if ((n($return_hour) >= n((string)($mt_t_time)))) {
            lock(...$_args);
            // 卸フラグを１にしてメインタウンログを更新
            $mt_orosiflag = 1;
            $mt_yobi9 = $date2;
            // 卸更新日時を記録
            main_town_temp(...$_args);
            if (!(pl_open('OUT', ">{$maintown_logfile}"))) { error("Write Error : {$maintown_logfile}"); }
            pl_print('OUT', $mt_temp);
            pl_close('OUT');
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
            unlock(...$_args);
        }
        // if（商品入れ替え時間が過ぎていた場合）の閉じ
    }
    // if（卸フラグが0の場合）の閉じ
    // 卸商品の表示
    if (!(pl_open('IN', (string)($orosi_logfile)))) { error("Open Error : {$orosi_logfile}"); }
    $kyouno_hairetu = pl_readlines('IN');
    pl_close('IN');
    t_header('orosi_style');
    // ver.1.3ここから
    // 個人の家情報をunitハッシュに代入
    if (!(pl_open('OI', (string)($ori_ie_list)))) { error("Open Error : {$ori_ie_list}"); }
    $ori_ie_hairetu = pl_readlines('OI');
    $__s3 = $_ ?? null;
    foreach ($ori_ie_hairetu ?? [] as $_) {
        ori_ie_sprit($_);
        $unit[(string)($ori_k_id)] = '1';
    }
    $_ = $__s3;
    pl_close('OI');
    if (((string)$in['iesettei_id'] === (string)$k_id)) {
        my_omise_orosi_rou(...$_args);
    } elseif ((((string)$in['iesettei_id'] === (string)$house_type) && (n($house_type) != 0))) {
        haiguusya_omise_orosi_rou(...$_args);
    } else {
        if ((n($unit[(string)($k_id)]) != 0)) {
            my_omise_orosi_rou(...$_args);
            $in['iesettei_id'] = $k_id;
        } elseif ((n($unit[(string)($house_type)]) != 0)) {
            haiguusya_omise_orosi_rou(...$_args);
            $in['iesettei_id'] = $house_type;
        } else {
            $orosisakinomise = 'お店を持っていないので仕入れはできません。';
            $settei_t_color = '#888888';
            $misearuyoflag = 'nasi';
        }
    }
    // 自分の商品在庫チェック
    $omise_log_file = "./member/{$in['iesettei_id']}/omise_log.cgi";
    pl_open('MKF', (string)($omise_log_file));
    $my_item_list = pl_readlines('MKF');
    pl_close('MKF');
    $__s4 = $_ ?? null;
    foreach ($my_item_list ?? [] as $_) {
        syouhin_sprit($_);
        $my_omise_zaiko_itiran .= "○{$syo_hinmoku} {$syo_zaiko}個　";
    }
    $_ = $__s4;
    // ver.1.3ここまで
    // ver.1.30ここから
    echo <<<EOM
    	<table width="100%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr>
    	<td bgcolor="#ffffff">自分のお店に並べる商品をここで仕入れることができます（お店を持ってない方は購入しても意味がありません）。購入したい商品にチェックを入れたうえで、ページ最下部で数量を指定し「OK」ボタンを押してください。品揃えは毎日１回変わりますが、何時に変わるかはランダムに変わります。また、お金は普通口座より引き落とされます。
    	<div class="honbun2">最終卸商品更新日時：{$mt_yobi9}</div>
    	<span style="color:#ff6600">※持っているお店で扱っている種類の商品しか購入できません。スーパーを選択している方は、ここに表示されている価格の1.5倍になります。<br>
    	※お店に置ける商品アイテム数は{$mise_zaiko_gendo}です。<br>
    	※ストックできる一つの商品の在庫数は{$mise_zaiko_limit}です。</span></td>
    	<td  bgcolor="#333333" align="center"><img src="{$img_dir}/orosi_tytle.gif"></td>
    	</tr></table><br>
    	<!--ver.1.3ここから-->
    	<table width="90%" border="0" cellspacing="0" cellpadding="8" align="center" class="yosumi">
    	<tr bgcolor="{$settei_t_color}"><td>
    	<div  align="center" style="color:#ffffff;font-size:13px;">{$orosisakinomise}</div>
    	<div  align="left" style="color:#ffffff;font-size:10px;">現在の在庫状況：{$my_omise_zaiko_itiran}</div>
    	</td>
    	<td>{$change_form}</td>
    	</tr></table><br>
    	<!--ver.1.3ここまで-->
    	<table width="100%" border="0" cellspacing="1" cellpadding="5" align="center" class="yosumi">
    	<tr><td colspan="27"><span style="color:#336699">凡例：(国)＝国語up値、(数)＝数学up値、(理)＝理科up値、(社)＝社会up値、(英)＝英語up値、(音)＝音楽up値、(美)＝美術up値、（ル）=ルックスup値、（体）=体力up値、（健）=健康up値、（ス）=スピードup値、（パ）=パワーup値、（腕）=腕力up値、（脚）=脚力up値、（L）=LOVEup値、（面）=面白さup値、（H）=エッチup値<br>
    	※耐久は、○○回なら使用できる回数、○○日なら使用できる日数です。</span></td></tr>
    EOM;
    echo <<<EOM
    	<form method="POST" action="{$script}">
    	<input type="hidden" name="mode" value="buy_orosi">
    	<input type="hidden" name="iesettei_id" value="{$in['iesettei_id']}">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="k_id" value="{$in['k_id']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    EOM;
    $__s5 = $_ ?? null;
    foreach ($kyouno_hairetu ?? [] as $_) {
        syouhin_sprit($_);
        if ((n($syo_zaiko) <= 0)) {
            $syo_zaiko = '売り切れ';
            $kounyuubotan = '';
        } else {
            if (($misearuyoflag == 'nasi')) {
                $syo_zaiko = '購入不可';
                $kounyuubotan = '';
            } else {
                $kounyuubotan = "<input type=\"radio\" name=\"syo_hinmoku\" value=\"{$syo_hinmoku},&,{$syo_nedan},&,{$syo_zaiko},&,{$syo_syubetu}\">";
            }
        }
        if ((n($syo_cal) > 0)) {
            $calory_hyouzi = "{$syo_cal} kcal";
        } else {
            $calory_hyouzi = 'ー';
        }
        if (((string)$maeno_syo_syubetu !== (string)(string)($syo_syubetu))) {
            echo <<<EOM
            				<tr bgcolor="#ffff66"><td colspan="27">▼{$syo_syubetu}</td></tr>
            		<tr bgcolor="#cc9966"><td align="center"  width="170">商品</td><td align="center" nowrap>在庫</td><td align="center">価格</td><td>国</td><td>数</td><td>理</td><td>社</td><td>英</td><td>音</td><td>美</td><td>ル</td><td>体</td><td>健</td><td>ス</td><td>パ</td><td>腕</td><td>脚</td><td>L</td><td>面</td><td>Ｈ</td><td align="center" nowrap>カロリー</td><td align="center" nowrap>耐久</td><td align="center">使用<br>間隔</td><td align="center">身体<br>消費</td><td align="center">頭脳<br>消費</td><td align="center" nowrap>　備　考　</td></tr>
            EOM;
        }
        $taikyuu_hyouzi_seikei = (string)($syo_taikyuu) . (string)($syo_taikyuu_tani);
        echo <<<EOM
        	<tr bgcolor="#cccc99"><td align="left">{$kounyuubotan}{$syo_hinmoku}</td><td align="right" nowrap>{$syo_zaiko}</td><td align="right" nowrap>{$syo_nedan}円</td><td>{$syo_kokugo}</td><td>{$syo_suugaku}</td><td>{$syo_rika}</td><td>{$syo_syakai}</td><td>{$syo_eigo}</td><td>{$syo_ongaku}</td><td>{$syo_bijutu}</td><td>{$syo_looks}</td><td>{$syo_tairyoku}</td><td>{$syo_kenkou}</td><td>{$syo_speed}</td><td>{$syo_power}</td><td>{$syo_wanryoku}</td><td>{$syo_kyakuryoku}</td><td>{$syo_love}</td><td>{$syo_unique}</td><td>{$syo_etti}</td><td align="right">{$calory_hyouzi}</td><td nowrap>{$taikyuu_hyouzi_seikei}</td><td nowrap>{$syo_kankaku}分</td><td>{$syo_sintai_syouhi}</td><td>{$syo_zunou_syouhi}</td><td align="left">{$syo_comment}</td></tr>
        EOM;
        $maeno_syo_syubetu = (string)($syo_syubetu);
    }
    $_ = $__s5;
    // foreach閉じ
    if (($misearuyoflag != 'nasi')) {
        echo <<<EOM
        	<tr><td align="center" colspan="27">購入数 <input type="text" name="kounyuusuu" size="10">　<input type="submit" value="OK"></td></tr>
        	</table></form>
        EOM;
    } else {
        echo '</table></form>';
    }
    // ver.1.40
    // ver.1.30ここまで
    hooter('login_view', '戻る');
    exit;
}

function buy_orosi(...$_args)
{
    global $_, $bank, $depa_zan, $douitem_ok, $imano_zaiko, $in, $k_temp, $katta_nedan, $katta_syouhin,
        $katta_total_gaku, $kounyuu_hairetu, $kounyuu_ok, $kyouno_hairetu, $message_win, $mise_zaiko_gendo,
        $mise_zaiko_limit, $motteru_flag, $my_item_list, $my_log_file, $my_mise_zaiko, $new_depa_zan,
        $new_myitem_list, $omise_body_style, $omise_come, $omise_log_file, $omise_settei_data, $omise_settei_file,
        $omise_syubetu, $omise_title, $orosi_logfile, $orosi_mitukatta, $orosi_syubetu, $script, $syo_comment,
        $syo_hinmoku, $syo_kounyuubi, $syo_nedan, $syo_syubetu, $syo_temp, $syo_zaiko;
    if ((n($in['kounyuusuu']) < 1)) {
        error('購入数が不適切です');
    }
    if (pl_match('/[^0-9]/u', $in['kounyuusuu'])) {
        error('購入数は半角数字で記入してください');
    }
    [$katta_syouhin, $katta_nedan, $imano_zaiko, $orosi_syubetu] = pl_pad(pl_split('/,&,/u', $in['syo_hinmoku']), 4);
    if ((n($in['kounyuusuu']) > n($imano_zaiko))) {
        error('そんなに在庫がありません');
    }
    if ((n($in['kounyuusuu']) > n($mise_zaiko_limit))) {
        error("お店に置ける在庫の上限は{$mise_zaiko_limit}です。");
    }
    if (!(pl_open('IN', (string)($orosi_logfile)))) { error("Open Error : {$orosi_logfile}"); }
    $kyouno_hairetu = pl_readlines('IN');
    pl_close('IN');
    $orosi_mitukatta = 0;
    $__s1 = $_ ?? null;
    foreach ($kyouno_hairetu ?? [] as $_) {
        syouhin_sprit($_);
        if (((string)$katta_syouhin === (string)(string)($syo_hinmoku))) {
            if ((n($syo_zaiko) <= 0)) {
                error('在庫がありません');
            }
            $katta_nedan = $syo_nedan;
            $imano_zaiko = $syo_zaiko;
            $orosi_syubetu = $syo_syubetu;
            $orosi_mitukatta = 1;
            break;
        }
    }
    $_ = $__s1;
    if ((n($orosi_mitukatta) == 0)) {
        error('その商品は見つかりません');
    }
    if ((n($in['kounyuusuu']) > n($imano_zaiko))) {
        error('そんなに在庫がありません');
    }
    $katta_total_gaku = (n($katta_nedan) * n($in['kounyuusuu']));
    if ((n($katta_total_gaku) > n($bank))) {
        error('普通口座にお金が足りません');
    }
    // 自分のお店の種別をチェック
    $omise_settei_file = "./member/{$in['iesettei_id']}/omise_ini.cgi";
    if (!(pl_open('OIB', (string)($omise_settei_file)))) { error('お店設定ファイルが開けません。家でお店を開いているか確認してください'); }
    $omise_settei_data = pl_readline('OIB');
    [$omise_title, $omise_come, $omise_body_style, $omise_syubetu] = pl_pad(pl_split('/<>/u', $omise_settei_data), 4);
    pl_close('OIB');
    if (($omise_syubetu != 'スーパー')) {
        if (((string)$omise_syubetu !== (string)(string)($orosi_syubetu))) {
            error('あなたのお店ではこの商品を扱うことはできません');
        }
    }
    if (($omise_syubetu == 'スーパー')) {
        $katta_nedan = n($katta_nedan) * 1.5;
        $katta_total_gaku = n($katta_total_gaku) * 1.5;
    }
    // 自分の商品在庫ファイルにその商品があるかチェック
    $omise_log_file = "./member/{$in['iesettei_id']}/omise_log.cgi";
    if (!(pl_open('MKF', (string)($omise_log_file)))) { error('自分の商品在庫ファイルが開けません'); }
    $my_item_list = pl_readlines('MKF');
    pl_close('MKF');
    $motteru_flag = 0;
    $my_mise_zaiko = 0;
    // 持っているかの判別（持っていたら在庫プラス）と商品アイテム数のカウント
    $__s2 = $_ ?? null;
    foreach ($my_item_list ?? [] as $_) {
        syouhin_sprit($_);
        // 持っていた場合
        if (((string)$katta_syouhin === (string)(string)($syo_hinmoku))) {
            if ((($omise_syubetu == 'スーパー') && pl_match('/専門店限定商品/u', $syo_comment))) {
                error("この商品は{$orosi_syubetu}専門店でしか購入できません。");
            }
            // ver.1.30
            $syo_zaiko = n($syo_zaiko) + n($in['kounyuusuu']);
            if ((n($syo_zaiko) > n($mise_zaiko_limit))) {
                error("お店に置ける在庫の上限は{$mise_zaiko_limit}です。");
            }
            // ver.1.3
            $bank = n($bank) - n($katta_total_gaku);
            $motteru_flag = 1;
        }
        if ((n($syo_zaiko) <= 0)) {
            continue;
        }
        syouhin_temp(...$_args);
        $my_mise_zaiko++;
        $new_myitem_list[] = $syo_temp;
    }
    $_ = $__s2;
    // foreachの閉じ
    // アイテム種類が限度以上なら同じアイテムでも買えない処理
    if ((n($douitem_ok) == 1)) {
        if ((n($my_mise_zaiko) >= n($mise_zaiko_gendo))) {
            error("お店に置ける商品アイテム数（{$mise_zaiko_gendo}）を超えているためまだ仕入れができません。");
        }
    }
    // 持っていなかった場合（卸しログファイルよりパラメータごとコピー）
    if ((n($motteru_flag) == 0)) {
        if ((n($my_mise_zaiko) >= n($mise_zaiko_gendo))) {
            error("お店に置ける商品アイテム数は{$mise_zaiko_gendo}です。");
        }
        if (!(pl_open('SP', (string)($orosi_logfile)))) { error("Open Error : {$orosi_logfile}"); }
        $kounyuu_hairetu = pl_readlines('SP');
        pl_close('SP');
        $kounyuu_ok = 0;
        $__s3 = $_ ?? null;
        foreach ($kounyuu_hairetu ?? [] as $_) {
            syouhin_sprit($_);
            if (((string)$katta_syouhin === (string)(string)($syo_hinmoku))) {
                if ((($omise_syubetu == 'スーパー') && pl_match('/専門店限定商品/u', $syo_comment))) {
                    error("この商品は{$orosi_syubetu}専門店でしか購入できません。");
                }
                // ver.1.30
                // 購入日を記録
                $syo_kounyuubi = pl_time();
                $syo_zaiko = $in['kounyuusuu'];
                syouhin_temp(...$_args);
                $new_myitem_list[] = $syo_temp;
                $bank = n($bank) - n($katta_total_gaku);
                $kounyuu_ok = 1;
                break;
            }
        }
        $_ = $__s3;
        if ((n($kounyuu_ok) == 0)) {
            error('購入できませんでした。');
        }
    }
    // 持っていなかった場合の閉じ
    // ログ更新
    temp_routin(...$_args);
    log_kousin($my_log_file, $k_temp);
    // 記帳する
    kityou_syori("仕入れ（{$katta_syouhin}×{$in['kounyuusuu']}）", (string)($katta_total_gaku), '', $bank, '普');
    // 自分の購入物ファイルのログ更新
    lock(...$_args);
    if (!(pl_open('MK', ">{$omise_log_file}"))) { error('自分の商品在庫ファイルに書き込めません'); }
    pl_print('MK', pl_join('', $new_myitem_list));
    pl_close('MK');
    // 卸問屋の残り数を引く
    if (!(pl_open('SYO', (string)($orosi_logfile)))) { error("Open Error : {$orosi_logfile}"); }
    $depa_zan = pl_readlines('SYO');
    pl_close('SYO');
    $new_depa_zan = [];
    $__s4 = $_ ?? null;
    foreach ($depa_zan ?? [] as $_) {
        syouhin_sprit($_);
        if (((string)$katta_syouhin === (string)(string)($syo_hinmoku))) {
            $syo_zaiko = n($syo_zaiko) - n($in['kounyuusuu']);
        }
        syouhin_temp(...$_args);
        $new_depa_zan[] = $syo_temp;
    }
    $_ = $__s4;
    if (!(pl_open('OLOUT', ">{$orosi_logfile}"))) { error("{$orosi_logfile}に書き込みが出来ません"); }
    pl_print('OLOUT', pl_join('', $new_depa_zan));
    pl_close('OLOUT');
    unlock(...$_args);
    t_header('', 'sonomati');
    echo <<<EOM
    	<div align="center"><br><table  border="0"  cellspacing="5" cellpadding="0" width="300" style="{$message_win}"><tr><td>
    <span class="job_messe">
    ●{$katta_syouhin}（{$katta_nedan}円×{$in['kounyuusuu']}）を仕入れました（仕入れ総額：{$katta_total_gaku}円）。
    </span>
    </td></tr></table>
    <br>
    	<form method="POST" action="{$script}">
    	<input type="hidden" name="mode" value="orosi">
    	<input type="hidden" name="iesettei_id" value="{$in['iesettei_id']}">		<!--ver.1.3-->
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value="仕入れを続ける">
    	</form>
    	<form method="POST" action="{$script}">
    	<input type="hidden" name="mode" value="login_view">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value="戻る">
    	</form>
    	</div>
    
    	</body></html>
    EOM;
    exit;
}

function gym(...$_args)
{
    global $gym_etti, $gym_hairetu, $gym_hyouzi, $gym_kankaku, $gym_kenkou, $gym_kyakuryoku, $gym_looks, $gym_name,
        $gym_nedan, $gym_power, $gym_speed, $gym_syouhi, $gym_tairyoku, $gym_wanryoku, $img_dir, $in, $name,
        $next_tra, $script, $top_koumoku, $tore_messe, $toremadeatonanbyou;
    if (!(pl_open('SP', './dat_dir/gym.dat'))) { error('Open Error : ./dat_dir/gym.dat'); }
    $top_koumoku = pl_readline('SP');
    $gym_hairetu = pl_readlines('SP');
    pl_close('SP');
    t_header('gym_style');
    gym_sprit($top_koumoku);
    $toremadeatonanbyou = (n($next_tra) - pl_time());
    if ((n($toremadeatonanbyou) > 0)) {
        $tore_messe = "{$name}さんがトレーニングできるまであと{$toremadeatonanbyou}秒です。";
    } else {
        $tore_messe = '今日も張り切って体を鍛えましょう。';
    }
    echo <<<EOM
    	<table width="100%" border="0" cellspacing="0" cellpadding="10"><tr><td width="554" >
    	<form method="POST" action="{$script}" NAME="foMes5">		<!--ver.1.2-->
    	<INPUT TYPE="hidden" NAME="TeMes5">		<!--ver.1.2-->
    	<input type="hidden" name="mode" value="training">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="k_id" value="{$in['k_id']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<table width="100%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr>
    	<td colspan="8" bgcolor="#ffffff">{$tore_messe}<br></td>
    	<td colspan="4" bgcolor="#333333" align="center"><img src="{$img_dir}/gim_tytle.gif"></td>
    	</tr>
    	<tr><td colspan="13"><span style="color:#336699">凡例：(ル)＝ルックスup値、(体)＝体力up値、(健)＝健康up値、(ス)＝スピードup値、(パ)＝パワーup値、(腕)＝腕力up値、(脚)＝脚力up値、(H)＝エッチup値、(間)＝次にトレーニング出来る間隔、(消)＝消費する身体パワー</span></td></tr>
    	<tr bgcolor="#ffff66"><td align="center">{$gym_name}</td><td>{$gym_looks}</td><td>{$gym_tairyoku}</td><td>{$gym_kenkou}</td><td>{$gym_speed}</td><td>{$gym_power}</td><td>{$gym_wanryoku}</td><td>{$gym_kyakuryoku}</td><td>{$gym_etti}</td><td align="center">{$gym_nedan}</td><td>{$gym_kankaku}</td><td>{$gym_syouhi}</td></tr>
    EOM;
    foreach ($gym_hairetu ?? [] as $gym_hyouzi) {
        gym_sprit($gym_hyouzi);
        echo <<<EOM
        	<tr><td nowrap><input type="radio" value="{$gym_name}" name="tra_menu">{$gym_name}</td><td>{$gym_looks}</td><td>{$gym_tairyoku}</td><td>{$gym_kenkou}</td><td>{$gym_speed}</td><td>{$gym_power}</td><td>{$gym_wanryoku}</td><td>{$gym_kyakuryoku}</td><td>{$gym_etti}</td><td align="right">{$gym_nedan}円</td><td>{$gym_kankaku}分</td><td align="right">{$gym_syouhi}</td></tr>
        EOM;
    }
    echo <<<EOM
    	<tr><td colspan="12"><div align="center"><input type="submit" id="gym_ok" value=" O K "><div id="gym_wait_msg" class="tw-wait"></div></div></td>
    	</table></form></td><td valign="top">
    EOM;
    // 次にトレーニングできる時刻まで OK を押せないようにする（サーバー側でも training で確認）
    $gym_wait = max(0, (int)$toremadeatonanbyou);
    echo <<<EOM
    <script>
    (function () {
      var ok = document.getElementById('gym_ok'), msg = document.getElementById('gym_wait_msg'), left = {$gym_wait};
      if (!ok || left <= 0) return;
      function show() { ok.disabled = left > 0; msg.textContent = left > 0 ? 'トレーニングできるまであと' + left + '秒' : ''; }
      show();
      var t = setInterval(function () { left--; show(); if (left <= 0) clearInterval(t); }, 1000);
    })();
    </script>
    EOM;
    loged_gamen(...$_args);
    echo '</td></tr></table>';
    hooter('login_view', '戻る');
    exit;
}

function training(...$_args)
{
    global $energy, $etti, $gym_etti, $gym_hairetu, $gym_hyouzi, $gym_kankaku, $gym_kenkou, $gym_kyakuryoku,
        $gym_looks, $gym_name, $gym_nedan, $gym_power, $gym_speed, $gym_syouhi, $gym_tairyoku, $gym_wanryoku, $in,
        $k_temp, $kenkou, $kyakuryoku, $looks, $message_win, $money, $my_log_file, $next_tra, $now_time, $power,
        $print_messe, $script, $speed, $taijuu, $taijuu_heri, $tairyoku, $top_koumoku, $wanryoku;
    if (!(pl_open('SP', './dat_dir/gym.dat'))) { error('Open Error : ./dat_dir/gym.dat'); }
    $top_koumoku = pl_readline('SP');
    $gym_hairetu = pl_readlines('SP');
    pl_close('SP');
    foreach ($gym_hairetu ?? [] as $gym_hyouzi) {
        gym_sprit($gym_hyouzi);
        if (((string)$in['tra_menu'] === (string)(string)($gym_name))) {
            $now_time = pl_time();
            if ((n($energy) < n($gym_syouhi))) {
                error('身体パワーが足りません。');
            }
            if ((n($next_tra) > n($now_time))) {
                error('まだトレーニングできません。');
            }
            if ((n($money) < n($gym_nedan))) {
                error('お金が足りません。');
            }
            if ($gym_looks) {
                $looks = n($looks) + n($gym_looks);
                $print_messe .= "・ルックス値が{$gym_looks}アップしました。<br>";
            }
            if ($gym_tairyoku) {
                $tairyoku = n($tairyoku) + n($gym_tairyoku);
                $print_messe .= "・体力が{$gym_tairyoku}アップしました。<br>";
            }
            if ($gym_kenkou) {
                $kenkou = n($kenkou) + n($gym_kenkou);
                $print_messe .= "・健康値が{$gym_kenkou}アップしました。<br>";
            }
            if ($gym_speed) {
                $speed = n($speed) + n($gym_speed);
                $print_messe .= "・スピードが{$gym_speed}アップしました。<br>";
            }
            if ($gym_power) {
                $power = n($power) + n($gym_power);
                $print_messe .= "・パワーが{$gym_power}アップしました。<br>";
            }
            if ($gym_wanryoku) {
                $wanryoku = n($wanryoku) + n($gym_wanryoku);
                $print_messe .= "・腕力が{$gym_wanryoku}アップしました。<br>";
            }
            if ($gym_kyakuryoku) {
                $kyakuryoku = n($kyakuryoku) + n($gym_kyakuryoku);
                $print_messe .= "・脚力が{$gym_kyakuryoku}アップしました。<br>";
            }
            if ($gym_etti) {
                $etti = n($etti) + n($gym_etti);
                $print_messe .= "・エッチ度が{$gym_etti}アップしました。<br>";
            }
            $next_tra = (n($now_time) + ((n($gym_kankaku) * 60)));
            $energy = n($energy) - n($gym_syouhi);
            $print_messe .= "・{$gym_syouhi}の身体エネルギーを使いました。<br>";
            $taijuu_heri = (n($gym_syouhi) / 100);
            $taijuu = n($taijuu) - n($taijuu_heri);
            $print_messe .= "・{$taijuu_heri} kg体重が減りました。<br>";
            $money = n($money) - n($gym_nedan);
            break;
        }
    }
    // ログ更新
    temp_routin(...$_args);
    log_kousin($my_log_file, $k_temp);
    t_header('', 'sonomati');
    echo <<<EOM
    	<div align="center"><br><table  border="0"  cellspacing="5" cellpadding="0" width="300" style="{$message_win}"><tr><td>
    <span class="job_messe">
    ●{$in['tra_menu']}で体を鍛えました。<br>
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

function school(...$_args)
{
    global $_, $date, $img_dir, $in, $last_school, $sc_bijutu, $sc_check, $sc_eigo, $sc_kokugo, $sc_name, $sc_nedan,
        $sc_ongaku, $sc_rika, $sc_suugaku, $sc_syakai, $sc_syouhi, $school_hairetu, $script, $top_koumoku,
        $tore_messe;
    if (!(pl_open('SP', './dat_dir/school.dat'))) { error('Open Error : ./dat_dir/school.dat'); }
    $top_koumoku = pl_readline('SP');
    $school_hairetu = pl_readlines('SP');
    pl_close('SP');
    t_header('school_style');
    school_sprit($top_koumoku);
    time_get(...$_args);
    if (((string)$date === (string)(string)($last_school))) {
        $tore_messe = '受講できるのは１日１回です。今日の受講は終了しています。';
        $sc_check = 'out';
    } else {
        $tore_messe = '今日も頑張って勉強しましょう。';
        $sc_check = 'ok';
    }
    echo <<<EOM
    	<table width="100%" border="0" cellspacing="0" cellpadding="10"><tr><td width="554" >
    	<form method="POST" action="{$script}" NAME="foMes5">		<!--ver.1.2-->
    	<INPUT TYPE="hidden" NAME="TeMes5">		<!--ver.1.2-->
    	<input type="hidden" name="mode" value="do_school">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="k_id" value="{$in['k_id']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="hidden" name="sc_check" value="{$sc_check}">
    	<table width="100%" border="0" cellspacing="0" cellpadding="10" class="yosumi">
    	<tr>
    	<td colspan="7" bgcolor="#ffffff">{$tore_messe}<br></td>
    	<td colspan="3" bgcolor="#333333" align="center"><img src="{$img_dir}/school_tytle.gif"></td>
    	</tr>
    	<tr bgcolor="#ffff66" align="center"><td align="center">{$sc_name}</td><td nowrap>{$sc_kokugo}</td><td nowrap>{$sc_suugaku}</td><td nowrap>{$sc_rika}</td><td nowrap>{$sc_syakai}</td><td nowrap>{$sc_eigo}</td><td nowrap>{$sc_ongaku}</td><td nowrap>{$sc_bijutu}</td><td>{$sc_nedan}</td><td>{$sc_syouhi}</td></tr>
    EOM;
    $__s1 = $_ ?? null;
    foreach ($school_hairetu ?? [] as $_) {
        school_sprit($_);
        echo <<<EOM
        	<tr align="center"><td nowrap align="left"><input type="radio" value="{$sc_name}" name="sc_name">{$sc_name}</td><td>{$sc_kokugo}</td><td>{$sc_suugaku}</td><td>{$sc_rika}</td><td>{$sc_syakai}</td><td>{$sc_eigo}</td><td>{$sc_ongaku}</td><td>{$sc_bijutu}</td><td align="right" nowrap>{$sc_nedan}円</td><td>{$sc_syouhi}</td></tr>
        EOM;
    }
    $_ = $__s1;
    echo <<<EOM
    	<tr><td colspan="10"><div align="center"><input type="submit" value=" O K "></div></td>
    	</table></form></td><td valign="top">
    EOM;
    loged_gamen(...$_args);
    echo '</td></tr></table>';
    hooter('login_view', '戻る');
    exit;
}

function do_school(...$_args)
{
    global $_, $bijutu, $date, $eigo, $in, $k_temp, $kokugo, $last_school, $message_win, $money, $my_log_file,
        $nou_energy, $ongaku, $print_messe, $rika, $sc_bijutu, $sc_eigo, $sc_kokugo, $sc_name, $sc_nedan, $sc_ongaku,
        $sc_rika, $sc_suugaku, $sc_syakai, $sc_syouhi, $school_hairetu, $script, $suugaku, $syakai, $top_koumoku;
    if (($in['sc_check'] == 'out')) {
        error('今日の受講は終了しています。');
    }
    time_get(...$_args);
    // ver.1.2
    if (((string)$date === (string)(string)($last_school))) {
        error('今日の受講は終了しています。');
    }
    // ver.1.2
    if (!(pl_open('SP', './dat_dir/school.dat'))) { error('Open Error : ./dat_dir/school.dat'); }
    $top_koumoku = pl_readline('SP');
    $school_hairetu = pl_readlines('SP');
    pl_close('SP');
    $__s1 = $_ ?? null;
    foreach ($school_hairetu ?? [] as $_) {
        school_sprit($_);
        if (((string)$in['sc_name'] === (string)(string)($sc_name))) {
            if ((n($nou_energy) < n($sc_syouhi))) {
                error('頭脳パワーが足りません。');
            }
            if ((n($money) < n($sc_nedan))) {
                error('お金が足りません。');
            }
            if ($sc_kokugo) {
                $kokugo = n($kokugo) + n($sc_kokugo);
                $print_messe .= "・国語が{$sc_kokugo}アップしました。<br>";
            }
            if ($sc_suugaku) {
                $suugaku = n($suugaku) + n($sc_suugaku);
                $print_messe .= "・数学が{$sc_suugaku}アップしました。<br>";
            }
            if ($sc_rika) {
                $rika = n($rika) + n($sc_rika);
                $print_messe .= "・理科が{$sc_rika}アップしました。<br>";
            }
            if ($sc_syakai) {
                $syakai = n($syakai) + n($sc_syakai);
                $print_messe .= "・社会が{$sc_syakai}アップしました。<br>";
            }
            if ($sc_eigo) {
                $eigo = n($eigo) + n($sc_eigo);
                $print_messe .= "・英語が{$sc_eigo}アップしました。<br>";
            }
            if ($sc_ongaku) {
                $ongaku = n($ongaku) + n($sc_ongaku);
                $print_messe .= "・音楽が{$sc_ongaku}アップしました。<br>";
            }
            if ($sc_bijutu) {
                $bijutu = n($bijutu) + n($sc_bijutu);
                $print_messe .= "・美術が{$sc_bijutu}アップしました。<br>";
            }
            time_get(...$_args);
            $last_school = $date;
            $nou_energy = n($nou_energy) - n($sc_syouhi);
            $print_messe .= "・{$sc_syouhi}の頭脳エネルギーを使いました。<br>";
            $money = n($money) - n($sc_nedan);
            break;
        }
    }
    $_ = $__s1;
    // ログ更新
    temp_routin(...$_args);
    log_kousin($my_log_file, $k_temp);
    t_header(...$_args);
    echo <<<EOM
    	<div align="center"><br><table  border="0"  cellspacing="5" cellpadding="0" width="300" style="{$message_win}"><tr><td>
    <span class="job_messe">
    ●{$in['sc_name']}を受講しました<br>
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

function syokudou(...$_args)
{
    global $_, $deleteUser, $i, $img_dir, $in, $kounyuubotan, $maintown_logfile, $maintown_para, $mt_syokudouflag,
        $mt_temp, $new_syouhin_hairetu, $new_syouhin_hairetu2, $script, $syo_cal, $syo_etti, $syo_hinmoku,
        $syo_kenkou, $syo_kyakuryoku, $syo_looks, $syo_love, $syo_nedan, $syo_power, $syo_speed, $syo_syubetu,
        $syo_tairyoku, $syo_temp, $syo_unique, $syo_wanryoku, $syo_zaiko, $syokudou_hairetu, $syokudou_logfile,
        $syokudou_sinakazu, $syokuzi_kankaku, $top_koumoku, $zaiko_tyousetuti;
    // 食堂フラグが0ならばメニューを更新、フラグを1にする
    if (!(pl_open('IN', (string)($maintown_logfile)))) { error("Open Error : {$maintown_logfile}"); }
    $maintown_para = pl_readline('IN');
    main_town_sprit($maintown_para);
    pl_close('IN');
    if ((n($mt_syokudouflag) == 0)) {
        lock(...$_args);
        $mt_syokudouflag = 1;
        main_town_temp(...$_args);
        if (!(pl_open('OUT', ">{$maintown_logfile}"))) { error("Write Error : {$maintown_logfile}"); }
        pl_print('OUT', $mt_temp);
        pl_close('OUT');
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
            if (($syo_syubetu != '食')) {
                continue;
            }
            $syo_zaiko = pl_int((n($syo_zaiko) / n($zaiko_tyousetuti)));
            if ((n($syo_zaiko) <= 0)) {
                $syo_zaiko = 1;
            }
            syouhin_temp(...$_args);
            $new_syouhin_hairetu2[] = $syo_temp;
            $i++;
            if ((n($i) >= n($syokudou_sinakazu))) {
                break;
            }
        }
        $_ = $__s1;
        if (!(pl_open('OLOUT', ">{$syokudou_logfile}"))) { error("{$syokudou_logfile}に書き込みが出来ません"); }
        pl_print('OLOUT', pl_join('', $new_syouhin_hairetu2));
        pl_close('OLOUT');
        unlock(...$_args);
    }
    // if（日付が変わっていたら）の閉じ
    if (!(pl_open('SP', (string)($syokudou_logfile)))) { error("Open Error : {$syokudou_logfile}"); }
    $syokudou_hairetu = pl_readlines('SP');
    pl_close('SP');
    t_header('syokudou_style');
    echo <<<EOM
    	<table width="100%" border="0" cellspacing="0" cellpadding="10"><tr><td width="554" >
    	<form method="POST" action="{$script}" NAME="foMes5">		<!--ver.1.2-->
    	<INPUT TYPE="hidden" NAME="TeMes5">		<!--ver.1.2-->
    	<input type="hidden" name="mode" value="syokuzisuru">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="k_id" value="{$in['k_id']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<table width="100%" border="0" cellspacing="0" cellpadding="6" align="center" class="yosumi">
    	<tr>
    	<td colspan="11" bgcolor="#ffffff">セントラル食堂です。メニューは毎日変わります。一度食事をすると{$syokuzi_kankaku}分経たないと次の食事がとれません。また、{$deleteUser}日間食事しないと死んでしまいます（ユーザー削除されます）<br></td>
    	<td colspan="3" bgcolor="#333333" align="center"><img src="{$img_dir}/syokudou_tytle.gif"></td>
    	</tr>
    	<tr><td colspan="14"><span style="color:#336699">凡例：（ル）ルックスup値、（体）体力up値、（健）健康up値、（ス）スピードup値、（パ）パワーup値、（腕）腕力up値、（脚）脚力up値、（L）LOVEup値、（面）面白さup値、（H）エッチup値</span></td></tr>
    		<tr bgcolor="#ffff66"><td align="center" nowrap>メニュー</td><td>ル</td><td>体</td><td>健</td><td>ス</td><td>パ</td><td>腕</td><td>脚</td><td>L</td><td>面</td><td>Ｈ</td><td align="center">カロリー</td><td align="center">値段</td><td align="center">残り</td></tr>
    EOM;
    $i = 1;
    $__s2 = $_ ?? null;
    foreach ($syokudou_hairetu ?? [] as $_) {
        if (((n($i) % 10) == 0)) {
            echo <<<EOM
            		<tr bgcolor="#ffff66"><td align="center" nowrap>メニュー</td><td>ル</td><td>体</td><td>健</td><td>ス</td><td>パ</td><td>腕</td><td>脚</td><td>L</td><td>面</td><td>Ｈ</td><td align="center">カロリー</td><td align="center">値段</td><td align="center">残り</td></tr>
            EOM;
        }
        syouhin_sprit($_);
        $syo_nedan = n($syo_nedan) * 3;
        if ((n($syo_zaiko) <= 0)) {
            $kounyuubotan = '';
            $syo_zaiko = '売り切れ';
        } else {
            $kounyuubotan = "<input type=\"radio\" value=\"{$syo_hinmoku}\" name=\"syo_hinmoku\">";
        }
        echo <<<EOM
        		<tr><td nowrap>{$kounyuubotan} {$syo_hinmoku}</td><td>{$syo_looks}</td><td>{$syo_tairyoku}</td><td>{$syo_kenkou}</td><td>{$syo_speed}</td><td>{$syo_power}</td><td>{$syo_wanryoku}</td><td>{$syo_kyakuryoku}</td><td>{$syo_love}</td><td>{$syo_unique}</td><td>{$syo_etti}</td><td>{$syo_cal} kcal</td><td align="right" nowrap>{$syo_nedan}円</td><td align="right">{$syo_zaiko}</td></tr>
        EOM;
        $i++;
    }
    $_ = $__s2;
    echo <<<EOM
    	<tr><td colspan="14"><div align="center"><input type="submit" value=" O K "></div></td>
    	</table></form></td><td valign="top">
    EOM;
    loged_gamen(...$_args);
    echo '</td></tr></table>';
    hooter('login_view', '戻る');
    exit;
}

function syokuzisuru(...$_args)
{
    global $_, $etti, $in, $k_temp, $kenkou, $kyakuryoku, $last_syokuzi, $looks, $love, $money, $my_log_file,
        $new_syoku_zan, $now_time, $power, $speed, $syo_cal, $syo_etti, $syo_hinmoku, $syo_kenkou, $syo_kyakuryoku,
        $syo_looks, $syo_love, $syo_nedan, $syo_power, $syo_speed, $syo_tairyoku, $syo_temp, $syo_unique,
        $syo_wanryoku, $syo_zaiko, $syoku_zan, $syokudou_logfile, $syokuzi_hairetu, $syokuzi_hyouzi,
        $syokuzi_kankaku, $taijuu, $tairyoku, $top_koumoku, $unique, $wanryoku;
    // ver.1.3
    if (($in['syo_hinmoku'] == '')) {
        error('食事が選ばれていません');
    }
    // ver.1.40
    if (!(pl_open('SP', './dat_dir/syouhin.dat'))) { error('Open Error : ./dat_dir/syouhin.dat'); }
    $top_koumoku = pl_readline('SP');
    $syokuzi_hairetu = pl_readlines('SP');
    pl_close('SP');
    foreach ($syokuzi_hairetu ?? [] as $syokuzi_hyouzi) {
        syouhin_sprit($syokuzi_hyouzi);
        if (((string)$in['syo_hinmoku'] === (string)(string)($syo_hinmoku))) {
            $now_time = pl_time();
            if ((n($now_time) < (n($last_syokuzi) + ((n($syokuzi_kankaku) * 60))))) {
                error('まだ食事できません。');
            }
            if ((n($money) < (n($syo_nedan) * 3))) {
                error('お金が足りません。');
            }
            $looks = n($looks) + n($syo_looks);
            $tairyoku = n($tairyoku) + n($syo_tairyoku);
            $kenkou = n($kenkou) + n($syo_kenkou);
            $speed = n($speed) + n($syo_speed);
            $power = n($power) + n($syo_power);
            $wanryoku = n($wanryoku) + n($syo_wanryoku);
            $kyakuryoku = n($kyakuryoku) + n($syo_kyakuryoku);
            $love = n($love) + n($syo_love);
            // ver.1.2
            $unique = n($unique) + n($syo_unique);
            // ver.1.2
            $etti = n($etti) + n($syo_etti);
            $taijuu = n($taijuu) + (n($syo_cal) / 1000);
            $last_syokuzi = $now_time;
            $money = n($money) - ((n($syo_nedan) * 3));
            break;
        }
    }
    // ログ更新
    temp_routin(...$_args);
    log_kousin($my_log_file, $k_temp);
    // 食堂の残り数を引く
    if (!(pl_open('SYO', (string)($syokudou_logfile)))) { error("Open Error : {$syokudou_logfile}"); }
    $syoku_zan = pl_readlines('SYO');
    pl_close('SYO');
    $new_syoku_zan = [];
    $__s1 = $_ ?? null;
    foreach ($syoku_zan ?? [] as $_) {
        syouhin_sprit($_);
        if (((string)$in['syo_hinmoku'] === (string)(string)($syo_hinmoku))) {
            $syo_zaiko = (n($syo_zaiko) - 1);
        }
        syouhin_temp(...$_args);
        $new_syoku_zan[] = $syo_temp;
    }
    $_ = $__s1;
    lock(...$_args);
    if (!(pl_open('OLOUT', ">{$syokudou_logfile}"))) { error("{$syokudou_logfile}に書き込みが出来ません"); }
    pl_print('OLOUT', pl_join('', $new_syoku_zan));
    pl_close('OLOUT');
    unlock(...$_args);
    message("{$in['syo_hinmoku']}を食べました。", 'login_view');
}

function by_syu_keys(...$_args)
{
    global $a, $b, $keys;
    return pl_cmp($keys[n($a)], $keys[n($b)]);
}

function depart_gamen(...$_args)
{
    global $_, $alldata, $calory_hyouzi, $data, $depart_logfile, $depart_sinakazu, $i, $img_dir, $in, $k_id, $key,
        $keys, $kounyuubotan, $maeno_syo_syubetu, $maintown_logfile, $maintown_para, $money, $monokiroku_file,
        $mt_departflag, $mt_temp, $my_kounyuu_list, $name, $new_syouhin_hairetu, $new_syouhin_hairetu2, $script,
        $siharai_houhou, $syo_bijutu, $syo_cal, $syo_comment, $syo_eigo, $syo_etti, $syo_hinmoku, $syo_kankaku,
        $syo_kenkou, $syo_kokugo, $syo_kouka, $syo_kounyuubi, $syo_kyakuryoku, $syo_looks, $syo_love, $syo_nedan,
        $syo_ongaku, $syo_power, $syo_rika, $syo_sintai_syouhi, $syo_speed, $syo_suugaku, $syo_syakai, $syo_syubetu,
        $syo_taikyuu, $syo_taikyuu_tani, $syo_tairyoku, $syo_temp, $syo_unique, $syo_wanryoku, $syo_zaiko,
        $syo_zunou_syouhi, $syokudou_hairetu, $syoyuu_gendosuu, $taikyuu_hyouzi_seikei, $top_koumoku,
        $zaiko_tyousetuti;
    // デパートフラグが0ならばメニューを更新、フラグを1にする
    if (!(pl_open('IN', (string)($maintown_logfile)))) { error("Open Error : {$maintown_logfile}"); }
    $maintown_para = pl_readline('IN');
    main_town_sprit($maintown_para);
    pl_close('IN');
    if ((n($mt_departflag) == 0)) {
        lock(...$_args);
        $mt_departflag = 1;
        main_town_temp(...$_args);
        if (!(pl_open('OUT', ">{$maintown_logfile}"))) { error("Write Error : {$maintown_logfile}"); }
        pl_print('OUT', $mt_temp);
        pl_close('OUT');
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
            $syo_zaiko = pl_int((n($syo_zaiko) / n($zaiko_tyousetuti)));
            if ((n($syo_zaiko) <= 0)) {
                $syo_zaiko = 1;
            }
            syouhin_temp(...$_args);
            $new_syouhin_hairetu2[] = $syo_temp;
            $i++;
            if ((n($i) >= n($depart_sinakazu))) {
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
        if (!(pl_open('OLOUT', ">{$depart_logfile}"))) { error("{$depart_logfile}に書き込みが出来ません"); }
        pl_print('OLOUT', pl_join('', $alldata));
        pl_close('OLOUT');
        unlock(...$_args);
    }
    // if（日付が変わっていたら）の閉じ
    if (!(pl_open('SP', (string)($depart_logfile)))) { error("Open Error : {$depart_logfile}"); }
    $syokudou_hairetu = pl_readlines('SP');
    pl_close('SP');
    t_header('syokudou_style');
    echo <<<EOM
    	<form method="POST" action="{$script}">
    	<input type="hidden" name="mode" value="buy_syouhin">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="k_id" value="{$in['k_id']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<table width="100%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr>
    	<td bgcolor="#ffffff">デパートです。品揃えは毎日変わります。種類は豊富ですが値段は高めです。また一度に持てる所有物の限度は{$syoyuu_gendosuu}品目です。<div class="honbun2">●{$name}さんの所持金：{$money}円</div></td>
    	<td  bgcolor="#333333" align="center"><img src="{$img_dir}/depart_tytle.gif"></td>
    	</tr></table><br>
    	<table width="100%" border="0" cellspacing="1" cellpadding="5" align="center" class="yosumi">
    	<tr><td colspan="26"><span style="color:#336699">凡例：(国)＝国語up値、(数)＝数学up値、(理)＝理科up値、(社)＝社会up値、(英)＝英語up値、(音)＝音楽up値、(美)＝美術up値、（ル）=ルックスup値、（体）=体力up値、（健）=健康up値、（ス）=スピードup値、（パ）=パワーup値、（腕）=腕力up値、（脚）=脚力up値、（L）=LOVEup値、（面）=面白さup値、（H）=エッチup値<br></span>
    	<span style="color:#ff6600">※ギフトは贈り物専用の商品です。自分で使用することはできません。</span></td></tr>
    EOM;
    $__s3 = $_ ?? null;
    foreach ($syokudou_hairetu ?? [] as $_) {
        syouhin_sprit($_);
        $syo_nedan = n($syo_nedan) * 3;
        if ((n($syo_zaiko) <= 0)) {
            $kounyuubotan = '';
            $syo_zaiko = '売り切れ';
        } else {
            $kounyuubotan = "<input type=\"radio\" value=\"{$syo_hinmoku},&,{$syo_taikyuu},&,{$syo_nedan}\" name=\"syo_hinmoku\">";
        }
        if ((n($syo_cal) > 0)) {
            $calory_hyouzi = "{$syo_cal} kcal";
        } else {
            $calory_hyouzi = 'ー';
        }
        if (((string)$maeno_syo_syubetu !== (string)(string)($syo_syubetu))) {
            echo <<<EOM
            		<tr bgcolor="#ff9933"><td align="center" nowrap>商品</td><td>国</td><td>数</td><td>理</td><td>社</td><td>英</td><td>音</td><td>美</td><td>ル</td><td>体</td><td>健</td><td>ス</td><td>パ</td><td>腕</td><td>脚</td><td>L</td><td>面</td><td>Ｈ</td><td align="center" nowrap>カロリー</td><td align="center" nowrap>耐久</td><td align="center">使用<br>間隔</td><td align="center">身体<br>消費</td><td align="center">頭脳<br>消費</td><td align="center">価格</td><td align="center" nowrap>　備　考　</td><td align="center" nowrap>在庫</td></tr>
            				<tr bgcolor="#ffff66"><td colspan="26">▼{$syo_syubetu}</td></tr>
            EOM;
        }
        $taikyuu_hyouzi_seikei = (string)($syo_taikyuu) . (string)($syo_taikyuu_tani);
        // ver.1.3ここから
        $syo_nedan = pl_comma($syo_nedan);
        // ver.1.3ここまで
        echo <<<EOM
        		<tr bgcolor="#ffcc66" align="center"><td width="150" align="left">{$kounyuubotan} {$syo_hinmoku}</td><td>{$syo_kokugo}</td><td>{$syo_suugaku}</td><td>{$syo_rika}</td><td>{$syo_syakai}</td><td>{$syo_eigo}</td><td>{$syo_ongaku}</td><td>{$syo_bijutu}</td><td>{$syo_looks}</td><td>{$syo_tairyoku}</td><td>{$syo_kenkou}</td><td>{$syo_speed}</td><td>{$syo_power}</td><td>{$syo_wanryoku}</td><td>{$syo_kyakuryoku}</td><td>{$syo_love}</td><td>{$syo_unique}</td><td>{$syo_etti}</td><td align="right">{$calory_hyouzi}</td><td nowrap>{$taikyuu_hyouzi_seikei}</td><td nowrap>{$syo_kankaku}分</td><td>{$syo_sintai_syouhi}</td><td>{$syo_zunou_syouhi}</td><td align="right" nowrap>{$syo_nedan}円</td><td align="left">{$syo_comment}</td><td align="right">{$syo_zaiko}</td></tr>
        EOM;
        $maeno_syo_syubetu = (string)($syo_syubetu);
    }
    $_ = $__s3;
    // foreach閉じ
    // ver.1.30ここから
    // 所有物チェック
    $monokiroku_file = "./member/{$k_id}/mono.cgi";
    if (!(pl_open('MK', (string)($monokiroku_file)))) { error('自分の購入物ファイルが開けません'); }
    $my_kounyuu_list = pl_readlines('MK');
    pl_close('MK');
    $__s4 = $_ ?? null;
    foreach ($my_kounyuu_list ?? [] as $_) {
        syouhin_sprit($_);
        if (($syo_kouka == 'クレジット')) {
            if ((n($syo_taikyuu) - (pl_int((((pl_time() - n($syo_kounyuubi))) / (((60 * 60) * 24))))))) {
                $siharai_houhou .= "<option value=\"{$syo_hinmoku}\">{$syo_hinmoku}</option>";
            }
        }
    }
    $_ = $__s4;
    echo <<<EOM
    	<tr><td colspan="26"><div align="center">
    	支払い方法 <select name="siharaihouhou"><option value="現金">現金</option>{$siharai_houhou}</select>
    	　<input type="submit" value=" O K "></div></td></tr>
    	</table></form>
    EOM;
    // ver1.30ここまで
    hooter('login_view', '戻る');
    exit;
}

function kakaku_kensyou(...$_args)
{
    global $_, $depart_logfile, $in, $katta_nedan, $katta_syouhin, $katta_taikyuu;
    $kk_list = [];
    $kk_ini = null;
    $kk_ini_list = [];
    $kk_syubetu = null;
    $kk_kakeritu = null;
    $kk_found = null;
    $kk = [];
    if ($in['ori_ie_id']) {
        if (!(pl_open('KKO', "./member/{$in['ori_ie_id']}/omise_log.cgi"))) { error('そのお店の商品が見つかりません。'); }
        $kk_list = pl_readlines('KKO');
        pl_close('KKO');
        if (!(pl_open('KKI', "./member/{$in['ori_ie_id']}/omise_ini.cgi"))) { error('そのお店が見つかりません。'); }
        $kk_ini = pl_readline('KKI');
        pl_close('KKI');
        $kk_ini_list = pl_split('/<>/u', $kk_ini);
        $kk_syubetu = $kk_ini_list[3];
        $kk_kakeritu = $kk_ini_list[11];
        if (($kk_kakeritu == '')) {
            $kk_kakeritu = 2;
        }
    } else {
        if (!(pl_open('KKO', (string)($depart_logfile)))) { error("Open Error : {$depart_logfile}"); }
        $kk_list = pl_readlines('KKO');
        pl_close('KKO');
    }
    $kk_found = 0;
    $__s1 = $_ ?? null;
    foreach ($kk_list ?? [] as $_) {
        $kk = pl_split('/<>/u', $_);
        if (((string)$kk[1] !== (string)$katta_syouhin)) {
            continue;
        }
        if ((n($kk[24]) <= 0)) {
            error('その商品は売り切れです。');
        }
        if ($in['ori_ie_id']) {
            if ($kk[32]) {
                $katta_nedan = $kk[32];
            } elseif (($kk_syubetu == 'スーパー')) {
                $katta_nedan = pl_int(((n($kk[17]) * 1.5) * n($kk_kakeritu)));
            } else {
                $katta_nedan = pl_int((n($kk[17]) * n($kk_kakeritu)));
            }
        } else {
            $katta_nedan = (n($kk[17]) * 3);
        }
        $katta_taikyuu = $kk[21];
        $kk_found = 1;
        break;
    }
    $_ = $__s1;
    if ((n($kk_found) == 0)) {
        error("{$katta_syouhin}は購入できませんでした。");
    }
}

function buy_syouhin(...$_args)
{
    global $_, $aite_bank, $aite_k_temp, $aite_log_file, $bank, $cashback_flag, $cashback_kingaku, $cashback_message,
        $depa_zan, $depart_logfile, $gift_item_suu, $house_type, $in, $item_kosuuseigen, $k_id, $k_temp,
        $kaenai_seigen, $katta_nedan, $katta_syouhin, $katta_taikyuu, $kounyu_gift_gendo, $kounyuu_hairetu,
        $kounyuu_ok, $message_win, $money, $monokiroku_file, $motteru_flag, $my_item_list, $my_item_suu,
        $my_log_file, $name, $new_depa_zan, $new_myitem_list, $new_omise_zan, $nokotteru_taikyuu, $omise_log_file,
        $omise_zan, $return_my_town, $script, $syo_atta_fg, $syo_hinmoku, $syo_kounyuubi, $syo_syubetu, $syo_taikyuu,
        $syo_taikyuu_tani, $syo_temp, $syo_zaiko, $syoyuu_gendosuu, $tanka;
    // ver.1.30ここから
    if ((n($kaenai_seigen) == 1)) {
        // ver.1.40
        if ((((string)$k_id === (string)(string)($in['ori_ie_id'])) || (((string)$house_type === (string)(string)($in['ori_ie_id'])) && ($in['ori_ie_id'] != '')))) {
            error('自分や配偶者のお店では商品を買うことができません。');
        }
    }
    [$katta_syouhin, $katta_taikyuu, $katta_nedan] = pl_pad(pl_split('/,&,/u', $in['syo_hinmoku']), 3);
    kakaku_kensyou(...$_args);
    if (($in['siharaihouhou'] == '現金')) {
        if ((n($katta_nedan) > n($money))) {
            error('お金が足りません');
        }
    }
    // 自分の購入物ファイルにその商品があるかチェック
    $monokiroku_file = "./member/{$k_id}/mono.cgi";
    if (!(pl_open('MKF', (string)($monokiroku_file)))) { error('自分の購入物ファイルが開けません'); }
    $my_item_list = pl_readlines('MKF');
    pl_close('MKF');
    $__s1 = $_ ?? null;
    foreach ($my_item_list ?? [] as $_) {
        syouhin_sprit($_);
        if ((n($syo_taikyuu) <= 0)) {
            continue;
        }
        if (($syo_syubetu == 'ギフト')) {
            $gift_item_suu++;
            continue;
        }
        if (($syo_syubetu == 'ギフト商品')) {
            continue;
        }
        $my_item_suu++;
    }
    $_ = $__s1;
    // 自分の住んでいる街かチェック
    my_town_check($name);
    if ((((string)$return_my_town === (string)(string)($in['town_no'])) && ((string)$k_id !== (string)(string)($in['ori_ie_id'])))) {
        $cashback_flag = 'on';
        $cashback_kingaku = pl_int((n($katta_nedan) / 10));
    } else {
        $cashback_flag = 'off';
    }
    $motteru_flag = 0;
    $__s2 = $_ ?? null;
    foreach ($my_item_list ?? [] as $_) {
        syouhin_sprit($_);
        // 持っていた場合
        if (((((string)$katta_syouhin === (string)(string)($syo_hinmoku)) && ($syo_syubetu != 'ギフト商品')) && ($syo_syubetu != 'ギフト'))) {
            if ((n($my_item_suu) >= n($syoyuu_gendosuu))) {
                error("これ以上所有できません。持ち物の所有限度数は{$syoyuu_gendosuu}です。");
            }
            if ((($syo_taikyuu_tani == '回') || ($syo_taikyuu_tani == '日'))) {
                $nokotteru_taikyuu = $syo_taikyuu;
                $syo_taikyuu = n($syo_taikyuu) + n($katta_taikyuu);
                if ((n($syo_taikyuu) > (n($katta_taikyuu) * n($item_kosuuseigen)))) {
                    error('これ以上このアイテムを増やすことはできません。');
                }
                // ver.1.2
                if (($cashback_flag == 'on')) {
                    $tanka = pl_int((((((n($tanka) * n($nokotteru_taikyuu)) + n($katta_nedan)) - n($cashback_kingaku))) / n($syo_taikyuu)));
                } else {
                    $tanka = pl_int(((((n($tanka) * n($nokotteru_taikyuu)) + n($katta_nedan))) / n($syo_taikyuu)));
                }
                $motteru_flag = 1;
            }
            if (($in['siharaihouhou'] != '現金')) {
                $bank = n($bank) - n($katta_nedan);
                kityou_syori("クレジット支払い（{$katta_syouhin}）", (string)($katta_nedan), '', $bank, '普');
            } else {
                $money = n($money) - n($katta_nedan);
            }
            // ver.1.30ここまで
        }
        syouhin_temp(...$_args);
        $new_myitem_list[] = $syo_temp;
    }
    $_ = $__s2;
    // foreachの閉じ
    // 持っていなかった場合
    if ((n($motteru_flag) == 0)) {
        if ((n($my_item_suu) >= n($syoyuu_gendosuu))) {
            error("これ以上所有できません。持ち物の所有限度数は{$syoyuu_gendosuu}です。");
        }
        if ((n($gift_item_suu) >= n($kounyu_gift_gendo))) {
            error("これ以上所有できません。ギフトの購入限度数{$kounyu_gift_gendo}に達しています。");
        }
        // 個人の店なら個人の店ログから商品パラメータをコピー
        if ($in['ori_ie_id']) {
            $omise_log_file = "./member/{$in['ori_ie_id']}/omise_log.cgi";
            if (!(pl_open('KOM', (string)($omise_log_file)))) { error("Open Error : {$omise_log_file}"); }
            $kounyuu_hairetu = pl_readlines('KOM');
            pl_close('KOM');
            // デパートならデパートログより商品パラメータをコピー
        } else {
            if (!(pl_open('KOM', (string)($depart_logfile)))) { error("Open Error : {$depart_logfile}"); }
            $kounyuu_hairetu = pl_readlines('KOM');
            pl_close('KOM');
        }
        $kounyuu_ok = 0;
        $__s3 = $_ ?? null;
        foreach ($kounyuu_hairetu ?? [] as $_) {
            syouhin_sprit($_);
            if (((string)$katta_syouhin === (string)(string)($syo_hinmoku))) {
                // ver.1.3
                // 購入日を記録
                $syo_kounyuubi = pl_time();
                // ver.1.30ここから
                if (($cashback_flag == 'on')) {
                    $tanka = pl_int((((n($katta_nedan) - n($cashback_kingaku))) / n($syo_taikyuu)));
                } else {
                    $tanka = pl_int((n($katta_nedan) / n($syo_taikyuu)));
                }
                syouhin_temp(...$_args);
                $new_myitem_list[] = $syo_temp;
                if (($in['siharaihouhou'] != '現金')) {
                    $bank = n($bank) - n($katta_nedan);
                    kityou_syori("クレジット支払い（{$katta_syouhin}）", (string)($katta_nedan), '', $bank, '普');
                } else {
                    $money = n($money) - n($katta_nedan);
                }
                // ver.1.30ここまで
                $kounyuu_ok = 1;
                break;
            }
        }
        $_ = $__s3;
        if ((n($kounyuu_ok) == 0)) {
            error("{$katta_syouhin}購入できませんでした。");
        }
    }
    // 持っていなかった場合の閉じ
    lock(...$_args);
    // 個人のお店なら在庫を引いてお金を入金＆記帳
    if ($in['ori_ie_id']) {
        $omise_log_file = "./member/{$in['ori_ie_id']}/omise_log.cgi";
        if (!(pl_open('SYO', (string)($omise_log_file)))) { error("Open Error : {$omise_log_file}"); }
        $omise_zan = pl_readlines('SYO');
        pl_close('SYO');
        $new_omise_zan = [];
        $syo_atta_fg = 0;
        // ver.1.22
        $__s4 = $_ ?? null;
        foreach ($omise_zan ?? [] as $_) {
            syouhin_sprit($_);
            if (((string)$katta_syouhin === (string)(string)($syo_hinmoku))) {
                if ((n($syo_zaiko) <= 0)) {
                    error('在庫切れです');
                }
                $syo_zaiko = (n($syo_zaiko) - 1);
                $syo_atta_fg = 1;
                // ver.1.22
            }
            if ((n($syo_zaiko) <= 0)) {
                continue;
            }
            syouhin_temp(...$_args);
            $new_omise_zan[] = $syo_temp;
        }
        $_ = $__s4;
        if ((n($syo_atta_fg) == 0)) {
            error('在庫がありません。');
        }
        // ver.1.22
        if (!(pl_open('OLOUT', ">{$omise_log_file}"))) { error("{$omise_log_file}に書き込みが出来ません"); }
        pl_print('OLOUT', pl_join('', $new_omise_zan));
        pl_close('OLOUT');
        // 相手の銀行に振り込み＆記帳処理
        if (((string)$k_id !== (string)(string)($in['ori_ie_id']))) {
            // 自分の店なら売上金は入らない
            openAitelog($in['ori_ie_id']);
            $aite_bank = n($aite_bank) + n($katta_nedan);
            aite_kityou_syori("販売（{$katta_syouhin}）", '', $katta_nedan, $aite_bank, '普', $in['ori_ie_id'], 'lock_off');
            aite_temp_routin(...$_args);
            if (!(pl_open('OUT', ">{$aite_log_file}"))) { error("{$aite_log_file}が開けません"); }
            pl_print('OUT', $aite_k_temp);
            pl_close('OUT');
        }
        // ver.1.30ここから
        // 自分の住んでいる街のお店ならキャッシュバック
        if (($cashback_flag == 'on')) {
            $money = n($money) + n($cashback_kingaku);
            $cashback_message = "<br>★ご近所店特典として、{$cashback_kingaku}円がキャッシュバックされました。";
        }
        // ver.1.30ここまで
        // 街の経済力アップ
        town_keizaiup($katta_nedan, $in['town_no']);
    } else {
        // 個人のお店の場合の閉じ
        // デパートなら在庫を引く
        if (!(pl_open('SYO', (string)($depart_logfile)))) { error("Open Error : {$depart_logfile}"); }
        $depa_zan = pl_readlines('SYO');
        pl_close('SYO');
        $new_depa_zan = [];
        $syo_atta_fg = 0;
        // ver.1.22
        $__s5 = $_ ?? null;
        foreach ($depa_zan ?? [] as $_) {
            syouhin_sprit($_);
            if (((string)$katta_syouhin === (string)(string)($syo_hinmoku))) {
                if ((n($syo_zaiko) <= 0)) {
                    error('在庫切れです');
                }
                // ver1.2
                $syo_zaiko = (n($syo_zaiko) - 1);
                $syo_atta_fg = 1;
                // ver.1.22
            }
            if ((n($syo_zaiko) <= 0)) {
                continue;
            }
            syouhin_temp(...$_args);
            $new_depa_zan[] = $syo_temp;
        }
        $_ = $__s5;
        if ((n($syo_atta_fg) == 0)) {
            error('在庫がありません。');
        }
        // ver.1.22
        if (!(pl_open('OLOUT', ">{$depart_logfile}"))) { error("{$depart_logfile}に書き込みが出来ません"); }
        pl_print('OLOUT', pl_join('', $new_depa_zan));
        pl_close('OLOUT');
    }
    // デパート購入の場合の閉じ
    // ver1.22
    // 自分の購入物ファイルのログ更新
    $monokiroku_file = "./member/{$k_id}/mono.cgi";
    if (!(pl_open('MK', ">{$monokiroku_file}"))) { error('自分の購入物ファイルが開けません'); }
    pl_print('MK', pl_join('', $new_myitem_list));
    pl_close('MK');
    unlock(...$_args);
    // ログ更新
    temp_routin(...$_args);
    log_kousin($my_log_file, $k_temp);
    t_header('', 'sonomati');
    echo <<<EOM
    	<div align="center"><br><table  border="0"  cellspacing="5" cellpadding="0" width="300" style="{$message_win}"><tr><td>
    <span class="job_messe">
    ●{$katta_syouhin}を購入しました。{$cashback_message}
    </span>
    </td></tr></table>
    <br>
    	<!--TW_BACK-->
    	<form method="POST" action="{$script}">
    	<input type="hidden" name="mode" value="login_view">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value="戻る">
    	</form>
    	</div>
    
    	</body></html>
    EOM;
    exit;
}

function mail_sousin(...$_args)
{
    global $_, $address_match, $addresstyou, $all_guest, $err, $genzai_zikoku, $gift_select, $guestfile,
        $hyouzi_check, $i, $img_dir, $in, $k_id, $kodomo_sibou_time, $last_m_check, $m_com, $m_date, $m_name,
        $m_syubetu, $maenideta_ad, $mail_data_add, $mail_hozon_gandosuu, $mem_lock_num, $message_file,
        $monokiroku_file, $my_kakikomi_mail_data, $my_mail_data, $myitem_hairetu, $name, $new_all_guest, $now_time,
        $sanka_name, $sanka_timer, $sanka_tmp, $script, $soumailkensuu, $syo_hinmoku, $syo_syubetu;
    // ver.1.3 mail_sousinサブルーチン全て
    // ver.1.30ここから
    $genzai_zikoku = pl_time();
    pl_open('GUEST', (string)($guestfile));
    $all_guest = pl_readlines('GUEST');
    pl_close('GUEST');
    $new_all_guest = [];
    $__s1 = $_ ?? null;
    foreach ($all_guest ?? [] as $_) {
        [$sanka_timer, $sanka_name, $hyouzi_check] = pl_pad(pl_split('/<>/u', $_), 3);
        if (((string)$name === (string)(string)($sanka_name))) {
            $sanka_timer = $genzai_zikoku;
        }
        $sanka_tmp = "{$sanka_timer}<>{$sanka_name}<>{$hyouzi_check}<>\n";
        $new_all_guest[] = $sanka_tmp;
    }
    $_ = $__s1;
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
    // ver.1.30ここまで
    // 自分のメールログを開いてアクセス時間を書き込み＆ログを読み込み
    $message_file = "./member/{$k_id}/mail.cgi";
    if (!(pl_open('TIMEIN', (string)($message_file)))) { error("Open Error : {$message_file}"); }
    $now_time = pl_time();
    $last_m_check = pl_readline('TIMEIN');
    // 表示用の配列
    $my_mail_data = pl_readlines('TIMEIN');
    $soumailkensuu = pl_count($my_mail_data);
    $last_m_check = "{$now_time}\n";
    pl_close('TIMEIN');
    // 持ち物リストからギフト配列作成
    $monokiroku_file = "./member/{$k_id}/mono.cgi";
    if (!(pl_open('SP', (string)($monokiroku_file)))) { error("Open Error : {$monokiroku_file}"); }
    $myitem_hairetu = pl_readlines('SP');
    pl_close('SP');
    $__s2 = $_ ?? null;
    foreach ($myitem_hairetu ?? [] as $_) {
        syouhin_sprit($_);
        if (($syo_syubetu == 'ギフト')) {
            $gift_select .= "<option value=\"{$syo_hinmoku}\">{$syo_hinmoku}</option>\n";
        }
    }
    $_ = $__s2;
    // 最終アクセス時間を更新
    lock(...$_args);
    // 書き込み用の配列を初期化
    $my_kakikomi_mail_data = [];
    pl_push($my_kakikomi_mail_data, ...($my_mail_data ?? []));
    pl_unshift($my_kakikomi_mail_data, $last_m_check);
    if ((n($soumailkensuu) > n($mail_hozon_gandosuu))) {
        pl_pop($my_kakikomi_mail_data);
    }
    // ver.1.3
    if (!(pl_open('TIMEOUT', ">{$message_file}"))) { error("Write Error : {$message_file}"); }
    pl_print('TIMEOUT', pl_join('', $my_kakikomi_mail_data));
    pl_close('TIMEOUT');
    unlock(...$_args);
    t_header('item_style');
    echo <<<EOM
    	<table width="95%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr>
    	<!--ver.1.3-->
    	<td bgcolor="#ffffff">相手の方の名前を入力してメッセージを送信することができます。受信箱と送信箱にメッセージが残っている相手はアドレス帳に追加されます。このアドレス帳から名前を選べば名前欄の入力は必要ありません。<br>
    	※保存しておけるメッセージは「受信箱」「送信箱」合わせて{$mail_hozon_gandosuu}件で、これを超えると古いメッセージから削除されます。<br>
    	※「ギフト」カテゴリーの商品を持っていれば、プレゼント送付でその商品を選び、メールと同時に送付することができます。</td>
    	<td  bgcolor="#ffcc00" align="center"><img src="{$img_dir}/mail_tytle.gif"></td>
    	</tr></table><br>
    EOM;
    $address_match = [];
    foreach ($my_mail_data ?? [] as $mail_data_add) {
        mail_sprit($mail_data_add);
        foreach ($address_match ?? [] as $maenideta_ad) {
            if (((string)$maenideta_ad === (string)(string)($m_name))) {
                continue 2;
            }
        }
        $addresstyou .= "<option value=\"{$m_name}\">{$m_name}</option>\n";
        $address_match[] = $m_name;
    }
    // 元はここ（画面上部）にも「戻る」があったが、下部と重複するため削除
    echo <<<EOM
    	<table width="100%" border="0" cellspacing="10" cellpadding="0" align="center">
    	<tr><td width="50%" valign="top">
    	<table border="0" cellspacing="0" cellpadding="10" class="yosumi" width="100%">
    	<tr><td>
    	<div class="tyuu">■メッセージ送信</div>
    	<form method="POST" action="{$script}">
    	<input type="hidden" name="mode" value="mail_do">
    	<input type="hidden" name="name" value="{$name}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="id" value="{$k_id}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	●送信先の名前<br>
    	<input type="text" size="58" name="sousinsaki_name" value="{$in['sousinsaki_name']}"><br>
    	<select name="sousinsaki_name2">
    	<option value="">アドレス帳</option>
    	{$addresstyou}
    	</select><br>
    	●メッセージ<br>
    	<textarea cols="58" rows="8" name="m_com" wrap="soft"></textarea><br>
    	●プレゼント送付<br>
    	<select name="gift_souhu">
    	<option value="">無し</option>
    	{$gift_select}
    	</select><br><br>
    	<div align="center"><input type="submit" value=" O K "></div>
    	</form></td></tr></table>
    	<br>
    	<table border="0" cellspacing="0" cellpadding="10" class="yosumi" width="100%">
    	<tr><td>
    	<div class="tyuu">■送信箱</div>
    EOM;
    $i = 0;
    $__s3 = $_ ?? null;
    foreach ($my_mail_data ?? [] as $_) {
        mail_sprit($_);
        if (($m_syubetu == '送信')) {
            echo '<hr size="1">';
            echo "<div class=\"mainasu\">○{$m_name}さんへ送ったメッセージ</div><br>";
            echo "{$m_com}<div class=\"small\">（{$m_date}）</div>";
            $i++;
        } elseif (($m_syubetu == '告白送信')) {
            echo '<hr size="1">';
            echo "<div style=\"color:#ff3366\">○{$m_name}さんへ送った交際申し込みメッセージ</div><br>";
            echo "{$m_com}<div class=\"small\">（{$m_date}）</div>";
            $i++;
        } elseif (($m_syubetu == 'プロポ送信')) {
            echo '<hr size="1">';
            echo "<div style=\"color:#ff3366\">○{$m_name}さんへ送ったプロポーズメッセージ</div><br>";
            echo "{$m_com}<div class=\"small\">（{$m_date}）</div>";
            $i++;
        } elseif (($m_syubetu == 'プロポ承諾送信')) {
            echo '<hr size="1">';
            echo "<div style=\"color:#ff3366\">○{$m_name}さんへ送った結婚承諾メッセージ</div><br>";
            echo "{$m_com}<div class=\"small\">（{$m_date}）</div>";
            $i++;
        } elseif (($m_syubetu == '交際承諾送信')) {
            echo '<hr size="1">';
            echo "<div style=\"color:#ff3366\">○{$m_name}さんへ送った交際承諾メッセージ</div><br>";
            echo "{$m_com}<div class=\"small\">（{$m_date}）</div>";
            $i++;
        }
    }
    $_ = $__s3;
    if ((n($i) == 0)) {
        echo '<br><br>まだ送信したメッセージはありません。';
    }
    echo <<<EOM
    	</td></tr></table>
    	</td><td width="50%" valign="top">
    	<table border="0" cellspacing="0" cellpadding="10" class="yosumi" width="100%">
    	<tr><td>
    	<div class="tyuu">■受信箱</div>
    EOM;
    $i = 0;
    $__s4 = $_ ?? null;
    foreach ($my_mail_data ?? [] as $_) {
        mail_sprit($_);
        if (($m_syubetu == '受信')) {
            echo <<<EOM
            			<hr size="1">
            			<div class="purasu">○{$m_name}さんからのメッセージ</div><br>
            			{$m_com}<div class="small">（{$m_date}）</div>
            EOM;
            $i++;
        } elseif (($m_syubetu == '告白受信')) {
            echo <<<EOM
            			<hr size="1">
            			<div style="color:#ff3366">○{$m_name}さんから交際のお申し込みがありました</div><br>
            			{$m_com}<div class="small">（{$m_date}）<br>
            			<a href="kekkon.php?mode=assenjo&command=easySerch&serch_name={$m_name}&town_no={$in['town_no']}&name={$name}&pass={$in['pass']}">恋人斡旋所で{$m_name}さんのプロフィールを見る</a><!--ver.1.40-->
            			</div>
            	 <form method="POST" action="kekkon.php"><!--ver.1.40-->
            	<input type="hidden" name="mode" value="kokuhaku">
            	<input type="hidden" name="command" value="kousai_ok">
            	<input type="hidden" name="name" value="{$name}">
            	<input type="hidden" name="pass" value="{$in['pass']}">
            	<input type="hidden" name="id" value="{$k_id}">
            	<input type="hidden" name="town_no" value="{$in['town_no']}">
            	<input type="hidden" name="sousinsaki_name" value="{$m_name}">
            	メッセージ <input type="text" name="m_com" size="30">
            	<input type="submit" value="交際をOKする"></form>
            	<div class="small">※交際OKの場合、メッセージを入れて「交際をOKする」ボタンを押してください。断る場合、何もする必要はありません。</div>
            EOM;
            $i++;
        } elseif (($m_syubetu == 'プロポ受信')) {
            echo <<<EOM
            			<hr size="1">
            			<div style="color:#ff3366">○{$m_name}さんからプロポーズされました。</div><br>
            			{$m_com}<div class="small">（{$m_date}）
            			</div>
            	 <form method="POST" action="kekkon.php">
            	<input type="hidden" name="mode" value="kokuhaku"><!--ver.1.40-->
            	<input type="hidden" name="command" value="propose_ok">
            	<input type="hidden" name="name" value="{$name}">
            	<input type="hidden" name="pass" value="{$in['pass']}">
            	<input type="hidden" name="id" value="{$k_id}">
            	<input type="hidden" name="town_no" value="{$in['town_no']}">
            	<input type="hidden" name="sousinsaki_name" value="{$m_name}">
            	メッセージ <input type="text" name="m_com" size="30">
            	<input type="submit" value="結婚をOKする"></form>
            	<div class="small">※結婚OKの場合、メッセージを入れて「結婚をOKする」ボタンを押してください。断る場合、何もする必要はありません。</div>
            EOM;
            $i++;
        } elseif (($m_syubetu == 'プロポ承諾受信')) {
            echo <<<EOM
            			<hr size="1">
            			<div style="color:#ff3366">○おめでとうございます。{$m_name}さんから結婚OKのお返事が来ました。</div><br>
            			{$m_com}<div class="small">（{$m_date}）</div>
            EOM;
            $i++;
        } elseif (($m_syubetu == '交際承諾受信')) {
            echo <<<EOM
            			<hr size="1">
            			<div style="color:#ff3366">○おめでとうございます。{$m_name}さんから交際OKのお返事が来ました。</div><br>
            			{$m_com}<div class="small">（{$m_date}）</div>
            EOM;
            $i++;
        } elseif (($m_syubetu == '出産')) {
            echo <<<EOM
            			<hr size="1">
            			<div style="color:#009900">○{$m_name}さんとの間に子供ができました。<span class="small">（{$m_date}）</span></div><br>
            	 <form method="POST" action="kekkon.php"><!--ver.1.40-->
            	<input type="hidden" name="mode" value="kodomo_naming">
            	<input type="hidden" name="kod_num" value="{$m_com}">
            	<input type="hidden" name="name" value="{$name}">
            	<input type="hidden" name="pass" value="{$in['pass']}">
            	<input type="hidden" name="id" value="{$k_id}">
            	<input type="hidden" name="town_no" value="{$in['town_no']}">
            	<input type="hidden" name="sousinsaki_name" value="{$m_name}">
            	子供のお名前（全角10文字以内） <br>
            	<input type="text" name="kodomo_name" size="20">
            	<select name="umu_umanai">
            		<option value="umu">この名前で出産する</option>
            		<option value="umanai">産まない</option>
            	</select>
            	<input type="submit" value=" O K "></form>
            	<div class="small">※子供の名前を決めてOKボタンを押してください。あとで変更はできませんので、お相手の方とよくご相談してから決めてください。出産しない場合は、「産まない」を選んでOKボタンを押してください。また{$kodomo_sibou_time}日間のうちにこの作業をしないと出産できなくなります。</div>
            EOM;
            $i++;
        }
    }
    $_ = $__s4;
    if ((n($i) == 0)) {
        echo '<br><br>まだ受信したメッセージはありません。';
    }
    echo '</td></tr></table></td></tr></table>';
    hooter('login_view', '戻る');
    exit;
}

function mail_do(...$_args)
{
    global $aite_name, $date2, $date_sec, $in, $k_id, $last_mail_check_time, $m_comment, $mail_cont,
        $mail_hozon_gandosuu, $message_file, $my_last_mail_check_time, $my_m_comment, $my_mail_cont, $my_sousin_file,
        $name, $new_mail, $return_id, $sousin_mail;
    if ((($in['sousinsaki_name'] == '') && ($in['sousinsaki_name2'] == ''))) {
        error('相手先のお名前の入力がありません');
    }
    if (($in['m_com'] == '')) {
        error('メッセージが入力されていません');
    }
    if ($in['sousinsaki_name2']) {
        $aite_name = (string)($in['sousinsaki_name2']);
    } else {
        $aite_name = (string)($in['sousinsaki_name']);
    }
    lock(...$_args);
    id_check($aite_name);
    if ($in['gift_souhu']) {
        if (((string)$aite_name === (string)$name)) {
            error('自分に贈ることはできません');
        }
        gift_souhu_syori(...$_args);
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
    if ($in['gift_souhu']) {
        $m_comment .= "<br><br><div class=\"honbun2\">『{$in['gift_souhu']}』が贈られてきました。</div>";
    }
    time_get(...$_args);
    $new_mail = "受信<>{$in['name']}<>{$m_comment}<>{$date2}<>{$date_sec}<><><><><><>\n";
    pl_unshift($mail_cont, $new_mail);
    if ((pl_count($mail_cont) > n($mail_hozon_gandosuu))) {
        pl_pop($mail_cont);
    }
    // ver.1.30
    // 最終メールチェック時間がなければ１を入れる
    if (($last_mail_check_time == '')) {
        $last_mail_check_time = "1\n";
    }
    pl_unshift($mail_cont, $last_mail_check_time);
    if (!(pl_open('OUT', ">{$message_file}"))) { error("{$message_file}に書き込めません"); }
    pl_print('OUT', pl_join('', $mail_cont));
    pl_close('OUT');
    // 自分のメールにも送信済みメッセージとして記録（管理者メールでなければ）
    if (($in['command'] != 'from_system')) {
        $my_sousin_file = "./member/{$k_id}/mail.cgi";
        if (!(pl_open('ZIB', (string)($my_sousin_file)))) { error("{$my_sousin_file}が開けません。"); }
        $my_last_mail_check_time = pl_readline('ZIB');
        $my_mail_cont = pl_readlines('ZIB');
        pl_close('ZIB');
        $my_m_comment = $in['m_com'];
        if ($in['gift_souhu']) {
            $my_m_comment .= "<br><br><div class=\"honbun2\">『{$in['gift_souhu']}』を贈りました。</div>";
        }
        $sousin_mail = "送信<>{$aite_name}<>{$my_m_comment}<>{$date2}<>{$date_sec}<><><><><><>\n";
        pl_unshift($my_mail_cont, $sousin_mail);
        if ((pl_count($my_mail_cont) > n($mail_hozon_gandosuu))) {
            pl_pop($my_mail_cont);
        }
        // ver.1.30
        // 最終メールチェック時間がなければ今の時間を入れる
        if (($my_last_mail_check_time == '')) {
            $my_last_mail_check_time = "{$date_sec}\n";
        }
        pl_unshift($my_mail_cont, $my_last_mail_check_time);
        if (!(pl_open('ZIBO', ">{$my_sousin_file}"))) { error("{$my_sousin_file}に書き込めません"); }
        pl_print('ZIBO', pl_join('', $my_mail_cont));
        pl_close('ZIBO');
    }
    unlock(...$_args);
    if (($in['command'] != 'from_system')) {
        message_only("{$aite_name}さんへメッセージを送信しました。");
        hooter('mail_sousin', 'メール画面へ');
        hooter('login_view', '戻る');
    } else {
        message("{$aite_name}さんへメッセージを送信しました。", 'itiran', 'admin.php');
        // ver.1.40
    }
    exit;
}

function gift_souhu_syori(...$_args)
{
    global $_, $ageru_gift_copy, $aite_item_hairetu, $aite_monokiroku_file, $gift_count, $gift_gendo, $in, $k_id,
        $monoatta_flag, $monokiroku_file, $myitem_hairetu, $name, $new_myitem_hairetu, $okuraretamono, $return_id,
        $syo_comment, $syo_hinmoku, $syo_kounyuubi, $syo_syubetu, $syo_temp, $tanka;
    // 自分の持ち物リストから商品を消去
    $monokiroku_file = "./member/{$k_id}/mono.cgi";
    if (!(pl_open('SP', (string)($monokiroku_file)))) { error("Open Error : {$monokiroku_file}"); }
    $myitem_hairetu = pl_readlines('SP');
    pl_close('SP');
    $monoatta_flag = 0;
    $__s1 = $_ ?? null;
    foreach ($myitem_hairetu ?? [] as $_) {
        syouhin_sprit($_);
        if (((((string)$syo_hinmoku === (string)(string)($in['gift_souhu'])) && (n($monoatta_flag) == 0)) && ($syo_syubetu != 'ギフト商品'))) {
            $ageru_gift_copy = (string)($_);
            $monoatta_flag = 1;
            continue;
        }
        syouhin_temp(...$_args);
        $new_myitem_hairetu[] = $syo_temp;
    }
    $_ = $__s1;
    if ((n($monoatta_flag) == 0)) {
        error('既に商品はありません。');
    }
    // 購入日を記録、単価は0円、種別をギフト商品にする
    syouhin_sprit($ageru_gift_copy);
    $syo_kounyuubi = pl_time();
    $tanka = 0;
    $syo_syubetu = 'ギフト商品';
    $syo_comment .= "{$name}さんからの贈り物です。";
    syouhin_temp(...$_args);
    $okuraretamono = $syo_temp;
    // 相手の持ち物リストに商品を追加
    $aite_monokiroku_file = "./member/{$return_id}/mono.cgi";
    if (!(pl_open('ASP', (string)($aite_monokiroku_file)))) { error("Open Error : {$aite_monokiroku_file}"); }
    $aite_item_hairetu = pl_readlines('ASP');
    pl_close('ASP');
    $__s2 = $_ ?? null;
    foreach ($aite_item_hairetu ?? [] as $_) {
        syouhin_sprit($_);
        if (($syo_syubetu == 'ギフト商品')) {
            $gift_count++;
        }
    }
    $_ = $__s2;
    if ((n($gift_count) >= n($gift_gendo))) {
        error("お相手の方のギフトが限度数の{$gift_gendo}を超えるため送付できません");
    }
    $aite_item_hairetu[] = $okuraretamono;
    // 相手の所有物ファイルを更新
    if (!(pl_open('ASPO', ">{$aite_monokiroku_file}"))) { error("Write Error : {$aite_monokiroku_file}"); }
    pl_print('ASPO', pl_join('', $aite_item_hairetu));
    pl_close('ASPO');
    // 自分の所有物ファイルを更新
    if (!(pl_open('OUT', ">{$monokiroku_file}"))) { error("Write Error : {$monokiroku_file}"); }
    pl_print('OUT', pl_join('', $new_myitem_hairetu));
    pl_close('OUT');
}

