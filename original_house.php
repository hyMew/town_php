<?php
// このファイルは original_house.cgi (Perl) から自動変換されたものです。
// 町CGI  Copyright (c) 2003-2004 brassiere  http://brassiere.jp/

require_once __DIR__ . '/lib/bootstrap.php';
$this_script = 'original_house.php';
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
if (($in['mode'] == 'my_house_settei')) {
    my_house_settei();
} elseif (($in['mode'] == 'my_house_settei_do')) {
    my_house_settei_do();
} elseif (($in['mode'] == 'houmon')) {
    houmon();
} elseif (($in['mode'] == 'bbs1_settei_do')) {
    bbs1_settei_do();
} elseif (($in['mode'] == 'omise_settei_do')) {
    omise_settei_do();
} elseif (($in['mode'] == 'dokuzi_settei_do')) {
    dokuzi_settei_do();
} elseif (($in['mode'] == 'gentei_settei_do')) {
    gentei_settei_do();
} elseif (($in['mode'] == 'bbs_regist')) {
    bbs_regist();
} elseif (($in['mode'] == 'gentei_delete')) {
    gentei_delete();
} elseif (($in['mode'] == 'bbs_delete')) {
    bbs_delete();
} elseif (($in['mode'] == 'gentei_regist')) {
    gentei_regist();
} elseif (($in['mode'] == 'saisensuru')) {
    saisensuru();
} elseif (($in['mode'] == 'comment_change')) {
    comment_change();
} elseif (($in['mode'] == 'normal_bbs')) {
    normal_bbs();
} elseif (($in['mode'] == 'house_change')) {
    house_change();
} elseif (($in['mode'] == 'my_syouhin')) {
    my_syouhin();
} else {
    error('「戻る」ボタンで街に戻ってください');
}
exit;
// ############以下サブルーチン
// ###自分の家の設定
// ##コメント変更
// ##自分の家の設定処理
// ###BBS1の設定
// ###家主掲示板の設定
// ###お店の設定
// ###独自URLの設定
// ######家の変更
// ####訪問処理
// ####通常のBBS
// BBS投稿処理
// ####家主掲示板
// 家主掲示板投稿処理
// ####お店
// ####独自URL
// ##さい銭箱入金処理
// #########自分のお店の商品リスト
// ##BBS記事削除
// ##家主限定掲示板記事削除

function my_house_settei(...$_args)
{
    global $_, $i, $in, $k_id, $kihon_oriie_settei, $my_con1, $my_con2, $my_con3, $my_con4, $my_directry,
        $ori_ie_list, $ori_ie_para, $ori_ie_rank, $ori_ie_setumei, $ori_k_id, $oriie_atta, $oriie_settei_file,
        $oriie_settei_sprit, $selectcount, $settei_t_color, $settei_title, $this_script, $titlecount;
    // ver.1.3
    if (!(pl_open('IN', (string)($ori_ie_list)))) { error("Open Error : {$ori_ie_list}"); }
    $ori_ie_para = pl_readlines('IN');
    pl_close('IN');
    $oriie_atta = 0;
    $__s1 = $_ ?? null;
    foreach ($ori_ie_para ?? [] as $_) {
        ori_ie_sprit($_);
        if (((string)$in['iesettei_id'] === (string)(string)($ori_k_id))) {
            $oriie_atta = 1;
            break;
        }
    }
    $_ = $__s1;
    if ((n($oriie_atta) == 0)) {
        error('家が見つかりません。');
    }
    $my_directry = "./member/{$in['iesettei_id']}";
    $oriie_settei_file = "{$my_directry}/oriie_settei.cgi";
    // 家設定ファイルがなければ作成
    if (!(pl_exists($oriie_settei_file))) {
        if (!(pl_open('OIS', ">{$oriie_settei_file}"))) { error("Write Error : {$oriie_settei_file}"); }
        true;
        pl_close('OIS');
    }
    if (!(pl_open('OIS', (string)($oriie_settei_file)))) { error("Open Error : {$oriie_settei_file}"); }
    $kihon_oriie_settei = pl_readline('OIS');
    oriie_settei_sprit($kihon_oriie_settei);
    pl_close('OIS');
    t_header('kentiku_style');
    if (((string)$in['iesettei_id'] === (string)(string)($k_id))) {
        $settei_title = '自分の家設定';
        $settei_t_color = '#336699';
    } else {
        $settei_title = '配偶者の家設定';
        $settei_t_color = '#ff6666';
    }
    echo <<<EOM
    	<table width="90%" border="0" cellspacing="0" cellpadding="8" align="center" class="yosumi">
    	<tr bgcolor="{$settei_t_color}"><td align="center" style="color:#ffffff;font-size:13px;">{$settei_title}</td></tr></table><br>
    EOM;
    if (((string)$my_con1 === (string)'0')) {
        bbs1_settei(...$_args);
    } elseif (((string)$my_con1 === (string)'1')) {
        omise_settei(...$_args);
    } elseif (((string)$my_con1 === (string)'2')) {
        dokuzi_settei(...$_args);
    } elseif (((string)$my_con1 === (string)'3')) {
        gentei_settei(...$_args);
    }
    if (((string)$my_con2 === (string)'0')) {
        bbs1_settei(...$_args);
    } elseif (((string)$my_con2 === (string)'1')) {
        omise_settei(...$_args);
    } elseif (((string)$my_con2 === (string)'2')) {
        dokuzi_settei(...$_args);
    } elseif (((string)$my_con2 === (string)'3')) {
        gentei_settei(...$_args);
    }
    if (((string)$my_con3 === (string)'0')) {
        bbs1_settei(...$_args);
    } elseif (((string)$my_con3 === (string)'1')) {
        omise_settei(...$_args);
    } elseif (((string)$my_con3 === (string)'2')) {
        dokuzi_settei(...$_args);
    } elseif (((string)$my_con3 === (string)'3')) {
        gentei_settei(...$_args);
    }
    if (((string)$my_con4 === (string)'0')) {
        bbs1_settei(...$_args);
    } elseif (((string)$my_con4 === (string)'1')) {
        omise_settei(...$_args);
    } elseif (((string)$my_con4 === (string)'2')) {
        dokuzi_settei(...$_args);
    } elseif (((string)$my_con4 === (string)'3')) {
        gentei_settei(...$_args);
    }
    echo <<<EOM
    	<table width="90%" border="0" cellspacing="0" cellpadding="8" align="center" class="yosumi">
    	<tr><td>
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="comment_change">
    	<input type="hidden" name="iesettei_id" value="{$in['iesettei_id']}">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<div class="tyuu">■基本設定</div>
    	●街で家にマウスがのった時に表示されるコメント（40字以内）<br>
    	<input type="text" name="ori_ie_setumei" size="80" value="{$ori_ie_setumei}"><input type="submit" value="OK"></form><hr size="1">
    
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="my_house_settei_do">
    	<input type="hidden" name="iesettei_id" value="{$in['iesettei_id']}">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	●コンテンツ選択（※設置するコンテンツを選択してください。後で変更も可能です。ここのタイトルはページ上のボタンに表示させるものです）<br>
    EOM;
    if ((n($ori_ie_rank) != 3)) {
        echo 'ここで一番上にあるコンテンツが家に入ったとき最初に表示されます。<br>';
    }
    if ((n($ori_ie_rank) == 0)) {
        $i = 4;
    } elseif ((n($ori_ie_rank) == 1)) {
        $i = 3;
    } elseif ((n($ori_ie_rank) == 2)) {
        $i = 2;
    } elseif ((n($ori_ie_rank) == 3)) {
        $i = 1;
    }
    $selectcount = 0;
    $titlecount = 4;
    $__s2 = $_ ?? null;
    foreach (pl_range(1, n($i)) as $_) {
        if ((n($ori_ie_rank) != 3)) {
            echo "<div class=\"honbun2\">○{$_}つめのコンテンツ</div>";
        }
        echo <<<EOM
        		<select name="コンテンツ{$_}">
        		<option value="">公開しない</option>
        EOM;
        echo '<option value="0"';
        if (((string)$oriie_settei_sprit[n($selectcount)] === (string)'0')) {
            echo ' selected';
        }
        echo ">通常の掲示板</option>\n";
        echo '<option value="1"';
        if (((string)$oriie_settei_sprit[n($selectcount)] === (string)'1')) {
            echo ' selected';
        }
        echo ">お店</option>\n";
        echo '<option value="2"';
        if (((string)$oriie_settei_sprit[n($selectcount)] === (string)'2')) {
            echo ' selected';
        }
        echo ">独自URL</option>\n";
        echo '<option value="3"';
        if (((string)$oriie_settei_sprit[n($selectcount)] === (string)'3')) {
            echo ' selected';
        }
        echo ">家主のみ書ける掲示板</option>\n";
        echo "</select>\n";
        if ((n($ori_ie_rank) != 3)) {
            echo "タイトル <input type=\"text\" name=\"タイトル{$_}\" value=\"{$oriie_settei_sprit[n($titlecount)]}\">";
        }
        $selectcount++;
        $titlecount++;
    }
    $_ = $__s2;
    echo <<<EOM
    		<input type="submit" value="OK">
    	</form>
    	<hr size="1">
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="house_change">
    	<input type="hidden" name="iesettei_id" value="{$in['iesettei_id']}">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	●家の外観、内装（コンテンツ内容）の変更<br>
    	場所はそのままに、家の外観を変更したり、内装をアップグレードすることができます。<br>
    	<input type="submit" value=" 選択画面へ "></form>
    	<hr size="1">
    EOM;
    if (((string)$in['iesettei_id'] === (string)(string)($k_id))) {
        echo <<<EOM
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="house_change">
        	<input type="hidden" name="command" value="baikyaku">
        	<input type="hidden" name="iesettei_id" value="{$in['iesettei_id']}">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	●家の売却<br>
        	家の場所を変更したい場合は、一度家を売却してから再度購入してくだい。売却で得られるのは土地の価格だけです。現在のコンテンツ内容は新たに家を購入した場合も保持されています。<br>
        	<input type="submit" value=" 家の売却 "></form>
        
        		<div align="center"><!--TW_BACK--></div>
        		</td></tr></table>
        EOM;
    } else {
        echo '※配偶者の家の場合、売却処理はできません。';
    }
    hooter('login_view', '戻る');
    exit;
}

function comment_change(...$_args)
{
    global $_, $in, $new_ori_ie_para, $ori_ie_list, $ori_ie_para, $ori_ie_setumei, $ori_ie_temp, $ori_k_id;
    if ((pl_length($in['ori_ie_setumei']) > 80)) {
        error('コメントは40字以内です');
    }
    if (pl_match('/\'/u', $in['ori_ie_setumei'])) {
        error('半角の「\'」は使用できません。');
    }
    // ver.1.3
    lock(...$_args);
    if (!(pl_open('IN', (string)($ori_ie_list)))) { error("Open Error : {$ori_ie_list}"); }
    $ori_ie_para = pl_readlines('IN');
    pl_close('IN');
    $__s1 = $_ ?? null;
    foreach ($ori_ie_para ?? [] as $_) {
        ori_ie_sprit($_);
        if (((string)$in['iesettei_id'] === (string)$ori_k_id)) {
            $ori_ie_setumei = $in['ori_ie_setumei'];
        }
        ori_ie_temp(...$_args);
        $new_ori_ie_para[] = $ori_ie_temp;
    }
    $_ = $__s1;
    if (!(pl_open('OLOUT', ">{$ori_ie_list}"))) { error("{$ori_ie_list}に書き込みが出来ません"); }
    pl_print('OLOUT', pl_join('', $new_ori_ie_para));
    pl_close('OLOUT');
    unlock(...$_args);
    message('家のコメントを変更しました。', 'my_house_settei', 'original_house.php');
}

function my_house_settei_do(...$_args)
{
    global $in, $kihon_oriie_settei, $my_con1, $my_con1_title, $my_con2, $my_con2_title, $my_con3, $my_con3_title,
        $my_con4, $my_con4_title, $my_directry, $my_yobi5, $my_yobi6, $my_yobi7, $ori_ie_settei_temp,
        $oriie_settei_file;
    $my_directry = "./member/{$in['iesettei_id']}";
    $oriie_settei_file = "{$my_directry}/oriie_settei.cgi";
    if (!(pl_open('OIS', (string)($oriie_settei_file)))) { error("Open Error : {$oriie_settei_file}"); }
    $kihon_oriie_settei = pl_readline('OIS');
    oriie_settei_sprit($kihon_oriie_settei);
    pl_close('OIS');
    $my_con1 = $in['コンテンツ1'];
    $my_con2 = $in['コンテンツ2'];
    $my_con3 = $in['コンテンツ3'];
    $my_con4 = $in['コンテンツ4'];
    $my_con1_title = $in['タイトル1'];
    $my_con2_title = $in['タイトル2'];
    $my_con3_title = $in['タイトル3'];
    $my_con4_title = $in['タイトル4'];
    $my_yobi5 = $in['my_yobi5'];
    $my_yobi6 = $in['my_yobi6'];
    $my_yobi7 = $in['my_yobi7'];
    oriie_settei_temp(...$_args);
    lock(...$_args);
    if (!(pl_open('OLOUT', ">{$oriie_settei_file}"))) { error("{$oriie_settei_file}に書き込みが出来ません"); }
    pl_print('OLOUT', $ori_ie_settei_temp);
    pl_close('OLOUT');
    unlock(...$_args);
    my_house_settei(...$_args);
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
        	<a href="{$this_script}?mode=normal_bbs&ori_ie_id=admin&bbs_num={$in['bbs_num']}&name={$in['name']}&admin_pass={$in['admin_pass']}&con_sele=0" target="_blank">[現在の設定内容の確認]</a>
        	</td></tr></table>
        	</form>
        EOM;
    } else {
        echo <<<EOM
        	<a href="{$this_script}?mode=houmon&ori_ie_id={$in['iesettei_id']}&name={$in['name']}&pass={$in['pass']}&con_sele=0" target="_blank">[現在の設定内容の確認]</a>
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
        $bbs1_yobi9, $bbs_settei_temp, $in, $message_win, $script;
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
        
        	<form method="POST" action="{$script}">
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

function gentei_settei(...$_args)
{
    global $gentei_body_style, $gentei_come, $gentei_daimei_style, $gentei_kensuu, $gentei_leed_style,
        $gentei_log_file, $gentei_settei_data, $gentei_settei_file, $gentei_siasenbako, $gentei_table2_style,
        $gentei_tablewidth, $gentei_title, $gentei_title_style, $gentei_yobi10, $gentei_yobi5, $gentei_yobi6,
        $gentei_yobi7, $gentei_yobi8, $gentei_yobi9, $in, $k_id, $my_directry, $this_script;
    $my_directry = "./member/{$in['iesettei_id']}";
    $gentei_settei_file = "{$my_directry}/gentei_ini.cgi";
    if (!(pl_exists($gentei_settei_file))) {
        if (!(pl_open('OIB', ">{$gentei_settei_file}"))) { error("Write Error : {$gentei_settei_file}"); }
        true;
        pl_close('OIB');
    }
    $gentei_log_file = "{$my_directry}/gentei_log.cgi";
    if (!(pl_exists($gentei_log_file))) {
        if (!(pl_open('OIL', ">{$gentei_log_file}"))) { error("Write Error : {$gentei_log_file}"); }
        true;
        pl_close('OIL');
    }
    if (!(pl_open('OIB', (string)($gentei_settei_file)))) { error("Open Error : {$gentei_settei_file}"); }
    $gentei_settei_data = pl_readline('OIB');
    [$gentei_title, $gentei_come, $gentei_body_style, $gentei_daimei_style, $gentei_table2_style, $gentei_kensuu, $gentei_tablewidth, $gentei_title_style, $gentei_leed_style, $gentei_siasenbako, $gentei_yobi5, $gentei_yobi6, $gentei_yobi7, $gentei_yobi8, $gentei_yobi9, $gentei_yobi10] = pl_pad(pl_split('/<>/u', $gentei_settei_data), 16);
    pl_close('OIB');
    // スタイルの初期化
    if (($gentei_body_style == '')) {
        $gentei_body_style = 'background-color:#99cc99;';
    }
    if (($gentei_title_style == '')) {
        $gentei_title_style = 'font-size: 20px; color: #339966;line-height:150%; text-align:center;';
    }
    if (($gentei_leed_style == '')) {
        $gentei_leed_style = 'font-size: 11px; color: #ff6600;line-height:160%;';
    }
    if (($gentei_daimei_style == '')) {
        $gentei_daimei_style = 'font-size: 14px; color: #445555;line-height:200%;';
    }
    if (($gentei_table2_style == '')) {
        $gentei_table2_style = 'font-size: 11px; line-height: 16px; color: #666666; background-color:#ffffcc; border: #339966; border-style: dotted; border-width:4px;';
    }
    if (($gentei_kensuu == '')) {
        $gentei_kensuu = '5';
    }
    if (($gentei_tablewidth == '')) {
        $gentei_tablewidth = '520';
    }
    if (($gentei_siasenbako == '')) {
        $gentei_siasenbako = 'font-size:11px;color:#000000';
    }
    echo <<<EOM
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="gentei_settei_do">
    	<input type="hidden" name="iesettei_id" value="{$in['iesettei_id']}">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="k_id" value="{$in['k_id']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<table width="90%" border="0" cellspacing="0" cellpadding="8" align="center" class="yosumi">
    	<tr><td>
    	<div class="tyuu">■家主掲示板の設定</div>
    	●掲示板のタイトル（タグ指定可。絶対URLならばイメージ画像の指定も可）<br>
    	<textarea  cols="80" rows="4" name="タイトル" wrap="soft">{$gentei_title}</textarea><br>
    	●タイトル下のコメント<br>
    	<input type="text" name="コメント" size="120"  value="{$gentei_come}"><br>
    	●背景のスタイル設定<br>
    	<input type="text" name="gentei_body_style" size="120" value="{$gentei_body_style}"><br>
    	●掲示板タイトルのスタイル設定<br>
    	<input type="text" name="gentei_title_style" size="120" value="{$gentei_title_style}"><br>
    	●タイトル下のコメントのスタイル設定<br>
    	<input type="text" name="gentei_leed_style" size="120" value="{$gentei_leed_style}"><br>
    	●記事の題名のスタイル設定<br>
    	<input type="text" name="gentei_daimei_style" size="120" value="{$gentei_daimei_style}"><br>
    	●掲示板内の基本スタイル設定<br>
    	<input type="text" name="gentei_table2_style" size="120" value="{$gentei_table2_style}"><br>
    	●１ページに表示する記事件数（半角文字数で指定）<br>
    	<input type="text" name="gentei_kensuu" size="120" value="{$gentei_kensuu}"><br>
    	●テーブルの横幅<br>
    	<input type="text" name="gentei_tablewidth" size="120" value="{$gentei_tablewidth}"><br>
    	●inputとselectのスタイル設定<br>
    	<input type="text" name="gentei_siasenbako" size="120" value="{$gentei_siasenbako}"><br>
    	<input type="submit" value="設定変更">
    	<a href="{$this_script}?mode=houmon&ori_ie_id={$in['iesettei_id']}&name={$in['name']}&pass={$in['pass']}&con_sele=3" target="_blank">[現在の設定内容の確認]</a>
    	</form>
    
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="gentei_regist">
    	<input type="hidden" name="iesettei_id" value="{$in['iesettei_id']}">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="ori_ie_id" value="{$k_id}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}"><br><br>
    	<div class="tyuu">■投稿（タグ可）</div>
    	タイトル<br>
    	<input type="text" name="b_title" size="80"><br>
    	内容<br>
    	<textarea  cols="90" rows="7" name="b_com" wrap="soft"></textarea><br>
    	<input type="submit" value="投稿">
    	</form>
    	</td></tr></table>
    
    EOM;
}

function gentei_settei_do(...$_args)
{
    global $gentei_body_style, $gentei_come, $gentei_daimei_style, $gentei_kensuu, $gentei_leed_style,
        $gentei_settei_data, $gentei_settei_file, $gentei_settei_temp, $gentei_siasenbako, $gentei_table2_style,
        $gentei_tablewidth, $gentei_title, $gentei_title_style, $gentei_yobi10, $gentei_yobi5, $gentei_yobi6,
        $gentei_yobi7, $gentei_yobi8, $gentei_yobi9, $in;
    $gentei_settei_file = "./member/{$in['iesettei_id']}/gentei_ini.cgi";
    if (!(pl_open('OIB', (string)($gentei_settei_file)))) { error("Open Error : {$gentei_settei_file}"); }
    $gentei_settei_data = pl_readline('OIB');
    [$gentei_title, $gentei_come, $gentei_body_style, $gentei_daimei_style, $gentei_table2_style, $gentei_kensuu, $gentei_tablewidth, $gentei_title_style, $gentei_leed_style, $gentei_siasenbako, $gentei_yobi5, $gentei_yobi6, $gentei_yobi7, $gentei_yobi8, $gentei_yobi9, $gentei_yobi10] = pl_pad(pl_split('/<>/u', $gentei_settei_data), 16);
    pl_close('OIB');
    lock(...$_args);
    $gentei_title = $in['タイトル'];
    $gentei_come = $in['コメント'];
    $gentei_body_style = $in['gentei_body_style'];
    $gentei_daimei_style = $in['gentei_daimei_style'];
    $gentei_table2_style = $in['gentei_table2_style'];
    $gentei_kensuu = $in['gentei_kensuu'];
    $gentei_tablewidth = $in['gentei_tablewidth'];
    $gentei_title_style = $in['gentei_title_style'];
    $gentei_leed_style = $in['gentei_leed_style'];
    $gentei_siasenbako = $in['gentei_siasenbako'];
    $gentei_yobi5 = $in['gentei_yobi5'];
    $gentei_yobi6 = $in['gentei_yobi6'];
    $gentei_yobi7 = $in['gentei_yobi7'];
    $gentei_yobi8 = $in['gentei_yobi8'];
    $gentei_yobi9 = $in['gentei_yobi9'];
    $gentei_yobi10 = $in['gentei_yobi10'];
    $gentei_settei_temp = "{$gentei_title}<>{$gentei_come}<>{$gentei_body_style}<>{$gentei_daimei_style}<>{$gentei_table2_style}<>{$gentei_kensuu}<>{$gentei_tablewidth}<>{$gentei_title_style}<>{$gentei_leed_style}<>{$gentei_siasenbako}<>{$gentei_yobi5}<>{$gentei_yobi6}<>{$gentei_yobi7}<>{$gentei_yobi8}<>{$gentei_yobi9}<>{$gentei_yobi10}<>\n";
    if (!(pl_open('OLOUT', ">{$gentei_settei_file}"))) { error("{$gentei_settei_file}に書き込みが出来ません"); }
    pl_print('OLOUT', $gentei_settei_temp);
    pl_close('OLOUT');
    unlock(...$_args);
    my_house_settei(...$_args);
}

function omise_settei(...$_args)
{
    global $_, $global_syouhin_syubetu, $in, $my_directry, $omise_body_style, $omise_come, $omise_koumokumei,
        $omise_leed_style, $omise_log_file, $omise_settei_data, $omise_settei_file, $omise_siasenbako,
        $omise_syouhin_table, $omise_syubetu, $omise_table1_style, $omise_table2_style, $omise_title,
        $omise_title_style, $omise_yobi10, $omise_yobi5, $omise_yobi6, $omise_yobi7, $omise_yobi8, $omise_yobi9,
        $script, $this_script;
    $my_directry = "./member/{$in['iesettei_id']}";
    $omise_settei_file = "{$my_directry}/omise_ini.cgi";
    if (!(pl_exists($omise_settei_file))) {
        if (!(pl_open('OIB', ">{$omise_settei_file}"))) { error("Write Error : {$omise_settei_file}"); }
        true;
        pl_close('OIB');
    }
    $omise_log_file = "{$my_directry}/omise_log.cgi";
    if (!(pl_exists($omise_log_file))) {
        if (!(pl_open('OIL', ">{$omise_log_file}"))) { error("Write Error : {$omise_log_file}"); }
        true;
        pl_close('OIL');
    }
    if (!(pl_open('OIB', (string)($omise_settei_file)))) { error("Open Error : {$omise_settei_file}"); }
    $omise_settei_data = pl_readline('OIB');
    [$omise_title, $omise_come, $omise_body_style, $omise_syubetu, $omise_table1_style, $omise_table2_style, $omise_koumokumei, $omise_syouhin_table, $omise_title_style, $omise_leed_style, $omise_siasenbako, $omise_yobi5, $omise_yobi6, $omise_yobi7, $omise_yobi8, $omise_yobi9, $omise_yobi10] = pl_pad(pl_split('/<>/u', $omise_settei_data), 17);
    // omise_yobi5＝基本販売掛け率　omise_yobi6＝商品カテゴリーのスタイル設定　omise_yobi7＝リンクのスタイル
    pl_close('OIB');
    // スタイルの初期化
    if (($omise_yobi5 == '')) {
        $omise_yobi5 = '2';
    }
    if (($omise_body_style == '')) {
        $omise_body_style = 'background-color:#ffcc33;';
    }
    if (($omise_title_style == '')) {
        $omise_title_style = 'font-size: 18px; color: #ff6600; line-height:160%;';
    }
    if (($omise_leed_style == '')) {
        $omise_leed_style = 'font-size: 11px; line-height: 16px; color: #000000';
    }
    if (($omise_table1_style == '')) {
        $omise_table1_style = 'font-size: 11px; line-height: 18px; color: #666666; background-color:#ffffff; border: #666666; border-style: solid; border-width:1px';
    }
    if (($omise_table2_style == '')) {
        $omise_table2_style = 'font-size: 10px; color: #336699; background-color:#ffffff; border: #666666; border-style: solid; border-width:1px';
    }
    if (($omise_syouhin_table == '')) {
        $omise_syouhin_table = 'font-size: 11px; color: #333333; background-color:#ffffaa; ';
    }
    if (($omise_koumokumei == '')) {
        $omise_koumokumei = 'font-size: 11px; color: #000000; background-color:#ffcc66; ';
    }
    if (($omise_yobi6 == '')) {
        $omise_yobi6 = 'background-color:#ffff88;';
    }
    if (($omise_yobi7 == '')) {
        $omise_yobi7 = 'font-size: 11px; color:#333333;text-decoration: none';
    }
    if (($omise_siasenbako == '')) {
        $omise_siasenbako = 'font-size:11px;color:#000000';
    }
    echo <<<EOM
    	<table width="90%" border="0" cellspacing="0" cellpadding="8" align="center" class="yosumi">
    	<tr><td>
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="omise_settei_do">
    	<input type="hidden" name="iesettei_id" value="{$in['iesettei_id']}">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="k_id" value="{$in['k_id']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<div class="tyuu">■お店の設定</div>
    	●お店のタイトル（タグ指定可。絶対URLならばイメージ画像の指定も可）<br>
    	<textarea  cols="80" rows="4" name="タイトル" wrap="soft">{$omise_title}</textarea><br>
    	●お店の種類（スーパーは全ての商品を扱うことができますが、仕入れ額が表示価格の1.5倍になります。また、あとでお店の種類を変えた場合、現在仕入れてある商品は全て無くなりますのでご注意ください。）<br>
    	<select name="omise_syubetu">
    EOM;
    $__s1 = $_ ?? null;
    foreach ($global_syouhin_syubetu ?? [] as $_) {
        echo "<option value=\"{$_}\"";
        if (((string)$omise_syubetu === (string)(string)($_))) {
            echo ' selected';
        }
        echo ">{$_}</option>\n";
    }
    $_ = $__s1;
    echo <<<EOM
    	</select>
    	<br>
    	●お店のコメント<br>
    	<textarea  cols="80" rows="3" name="コメント" wrap="soft">{$omise_come}</textarea><br>
    	●基本販売掛け率（デパートでは仕入れ値の３倍の金額で売られています。この価格より高く設定することはできません。商品リストで個別に設定することもできます）<br>
    	<input type="text" name="omise_yobi5" size="120"  value="{$omise_yobi5}"><br>
    	●背景のスタイル設定<br>
    	<input type="text" name="omise_body_style" size="120" value="{$omise_body_style}"><br>
    	●タイトルのスタイル設定<br>
    	<input type="text" name="omise_title_style" size="120" value="{$omise_title_style}"><br>
    	●コメントのスタイル設定<br>
    	<input type="text" name="omise_leed_style" size="120" value="{$omise_leed_style}"><br>
    	●タイトル部テーブルのスタイル設定<br>
    	<input type="text" name="omise_table1_style" size="120" value="{$omise_table1_style}"><br>
    	●商品リストテーブルの枠および凡例のスタイル設定<br>
    	<input type="text" name="omise_table2_style" size="120" value="{$omise_table2_style}"><br>
    	●商品リストテーブル内スタイル設定<br>
    	<input type="text" name="omise_syouhin_table" size="120" value="{$omise_syouhin_table}"><br>
    	●商品リストの項目名部分のスタイル設定<br>
    	<input type="text" name="omise_koumokumei" size="120" value="{$omise_koumokumei}"><br>
    	●商品カテゴリー部分のスタイル設定<br>
    	<input type="text" name="omise_yobi6" size="120" value="{$omise_yobi6}"><br>
    	●inputとselectのスタイル設定<br>
    	<input type="text" name="omise_siasenbako" size="120" value="{$omise_siasenbako}"><br>
    	●aタグのスタイル設定<br>
    	<input type="text" name="omise_yobi7" size="120" value="{$omise_yobi7}"><br>
    	<input type="submit" value="設定変更">
    	<a href="{$this_script}?mode=houmon&ori_ie_id={$in['iesettei_id']}&name={$in['name']}&pass={$in['pass']}&con_sele=1" target="_blank">[現在の設定内容の確認]</a><br><br>
    	</form>
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="my_syouhin">
    	<input type="hidden" name="iesettei_id" value="{$in['iesettei_id']}">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="k_id" value="{$in['k_id']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value="仕入れた商品のリスト">
    	</form>
    	<form method="POST" action="{$script}">
    	<input type="hidden" name="mode" value="orosi">
    	<input type="hidden" name="iesettei_id" value="{$in['iesettei_id']}">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value="仕入れに出かける">（街の卸問屋に行く）</form></td></tr></table>
    EOM;
}

function omise_settei_do(...$_args)
{
    global $change_syubetu_flag, $i, $in, $omise_body_style, $omise_come, $omise_koumokumei, $omise_leed_style,
        $omise_log_file, $omise_settei_data, $omise_settei_file, $omise_settei_temp, $omise_siasenbako,
        $omise_syouhin_table, $omise_syubetu, $omise_table1_style, $omise_table2_style, $omise_title,
        $omise_title_style, $omise_yobi10, $omise_yobi5, $omise_yobi6, $omise_yobi7, $omise_yobi8, $omise_yobi9;
    $omise_settei_file = "./member/{$in['iesettei_id']}/omise_ini.cgi";
    if (!(pl_open('OIB', (string)($omise_settei_file)))) { error("Open Error : {$omise_settei_file}"); }
    $omise_settei_data = pl_readline('OIB');
    [$omise_title, $omise_come, $omise_body_style, $omise_syubetu, $omise_table1_style, $omise_table2_style, $omise_koumokumei, $omise_syouhin_table, $omise_title_style, $omise_leed_style, $omise_siasenbako, $omise_yobi5, $omise_yobi6, $omise_yobi7, $omise_yobi8, $omise_yobi9, $omise_yobi10] = pl_pad(pl_split('/<>/u', $omise_settei_data), 17);
    pl_close('OIB');
    lock(...$_args);
    $change_syubetu_flag = '';
    $omise_title = $in['タイトル'];
    $omise_come = $in['コメント'];
    if (((string)$omise_syubetu !== (string)$in['omise_syubetu'])) {
        $change_syubetu_flag = 'on';
    }
    $omise_syubetu = $in['omise_syubetu'];
    $omise_body_style = $in['omise_body_style'];
    $omise_table1_style = $in['omise_table1_style'];
    $omise_table2_style = $in['omise_table2_style'];
    $omise_koumokumei = $in['omise_koumokumei'];
    $omise_syouhin_table = $in['omise_syouhin_table'];
    $omise_title_style = $in['omise_title_style'];
    $omise_leed_style = $in['omise_leed_style'];
    $omise_siasenbako = $in['omise_siasenbako'];
    if ((n($in['omise_yobi5']) > 3)) {
        error('販売掛け率は3倍以下にしてください');
    }
    if ((n($in['omise_yobi5']) <= 0)) {
        error('販売掛け率が不適切です。');
    }
    // ver.1.3
    $omise_yobi5 = $in['omise_yobi5'];
    $omise_yobi6 = $in['omise_yobi6'];
    $omise_yobi7 = $in['omise_yobi7'];
    $omise_yobi8 = $in['omise_yobi8'];
    $omise_yobi9 = $in['omise_yobi9'];
    $omise_yobi10 = $in['omise_yobi10'];
    $omise_settei_temp = "{$omise_title}<>{$omise_come}<>{$omise_body_style}<>{$omise_syubetu}<>{$omise_table1_style}<>{$omise_table2_style}<>{$omise_koumokumei}<>{$omise_syouhin_table}<>{$omise_title_style}<>{$omise_leed_style}<>{$omise_siasenbako}<>{$omise_yobi5}<>{$omise_yobi6}<>{$omise_yobi7}<>{$omise_yobi8}<>{$omise_yobi9}<>{$omise_yobi10}<>\n";
    if (!(pl_open('OLOUT', ">{$omise_settei_file}"))) { error("{$omise_settei_file}に書き込みが出来ません"); }
    pl_print('OLOUT', $omise_settei_temp);
    pl_close('OLOUT');
    // お店の種類変更なら商品リストを初期化
    if (($change_syubetu_flag == 'on')) {
        $i = '';
        $omise_log_file = "./member/{$in['iesettei_id']}/omise_log.cgi";
        if (!(pl_open('SP', ">{$omise_log_file}"))) { error("Open Error : {$omise_log_file}"); }
        pl_print('SP', $i);
        pl_close('SP');
    }
    unlock(...$_args);
    my_house_settei(...$_args);
}

function dokuzi_settei(...$_args)
{
    global $dokuzi_come, $dokuzi_haikei_style, $dokuzi_height, $dokuzi_leed_style, $dokuzi_settei_data,
        $dokuzi_settei_file, $dokuzi_siasenbako, $dokuzi_title, $dokuzi_title_style, $dokuzi_url, $dokuzi_width,
        $dokuzi_yobi10, $in, $my_directry, $this_script;
    $my_directry = "./member/{$in['iesettei_id']}";
    $dokuzi_settei_file = "{$my_directry}/dokuzi_ini.cgi";
    if (!(pl_exists($dokuzi_settei_file))) {
        if (!(pl_open('OIB', ">{$dokuzi_settei_file}"))) { error("Write Error : {$dokuzi_settei_file}"); }
        true;
        pl_close('OIB');
    }
    if (!(pl_open('OIB', (string)($dokuzi_settei_file)))) { error("Open Error : {$dokuzi_settei_file}"); }
    $dokuzi_settei_data = pl_readline('OIB');
    [$dokuzi_url, $dokuzi_width, $dokuzi_height, $dokuzi_haikei_style, $dokuzi_title, $dokuzi_come, $dokuzi_title_style, $dokuzi_leed_style, $dokuzi_siasenbako, $dokuzi_yobi10] = pl_pad(pl_split('/<>/u', $dokuzi_settei_data), 10);
    pl_close('OIB');
    // スタイルの初期化
    if (($dokuzi_haikei_style == '')) {
        $dokuzi_haikei_style = 'background-color:#ffffff;';
    }
    if (($dokuzi_title_style == '')) {
        $dokuzi_title_style = 'font-size: 16px; color: #666666;line-height:160%;';
    }
    if (($dokuzi_leed_style == '')) {
        $dokuzi_leed_style = 'font-size: 11px; line-height: 16px; color: #336699';
    }
    if (($dokuzi_width == '')) {
        $dokuzi_width = '800';
    }
    if (($dokuzi_height == '')) {
        $dokuzi_height = '400';
    }
    if (($dokuzi_siasenbako == '')) {
        $dokuzi_siasenbako = 'font-size:11px;color:#000000';
    }
    echo <<<EOM
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="dokuzi_settei_do">
    	<input type="hidden" name="iesettei_id" value="{$in['iesettei_id']}">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="k_id" value="{$in['k_id']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<table width="90%" border="0" cellspacing="0" cellpadding="8" align="center" class="yosumi">
    	<tr><td>
    	<div class="tyuu">■独自URLの設定</div>
    	●コーナータイトル（タグ指定可。絶対URLならばイメージ画像の指定も可。また指定しなくてもOKです。）<br>
    	<textarea  cols="80" rows="4" name="タイトル" wrap="soft">{$dokuzi_title}</textarea><br>
    	●URL指定<br>
    	<input type="text" name="dokuzi_url" size="120" value="{$dokuzi_url}"><br>
    	●タイトル下のコメント<br>
    	<input type="text" name="コメント" size="120"  value="{$dokuzi_come}"><br>
    	●背景のスタイル設定<br>
    	<input type="text" name="dokuzi_haikei_style" size="120" value="{$dokuzi_haikei_style}"><br>
    	●タイトルのスタイル設定<br>
    	<input type="text" name="dokuzi_title_style" size="120" value="{$dokuzi_title_style}"><br>
    	●タイトル下のコメントのスタイル設定<br>
    	<input type="text" name="dokuzi_leed_style" size="120" value="{$dokuzi_leed_style}"><br>
    	●IFRAMEの横サイズ<br>
    	<input type="text" name="dokuzi_width" size="120" value="{$dokuzi_width}"><br>
    	●IFRAMEの縦サイズ<br>
    	<input type="text" name="dokuzi_height" size="120" value="{$dokuzi_height}"><br>
    	●さい銭箱のスタイル設定<br>
    	<input type="text" name="dokuzi_siasenbako" size="120" value="{$dokuzi_siasenbako}"><br>
    
    	<input type="submit" value="設定変更">
    	<a href="{$this_script}?mode=houmon&ori_ie_id={$in['iesettei_id']}&name={$in['name']}&pass={$in['pass']}&con_sele=2" target="_blank">[現在の設定内容の確認]</a>
    	</td></tr></table>
    	</form>
    EOM;
}

function dokuzi_settei_do(...$_args)
{
    global $dokuzi_come, $dokuzi_haikei_style, $dokuzi_height, $dokuzi_leed_style, $dokuzi_settei_data,
        $dokuzi_settei_file, $dokuzi_settei_temp, $dokuzi_siasenbako, $dokuzi_title, $dokuzi_title_style,
        $dokuzi_url, $dokuzi_width, $dokuzi_yobi10, $in;
    $dokuzi_settei_file = "./member/{$in['iesettei_id']}/dokuzi_ini.cgi";
    if (!(pl_open('OIB', (string)($dokuzi_settei_file)))) { error("Open Error : {$dokuzi_settei_file}"); }
    $dokuzi_settei_data = pl_readline('OIB');
    [$dokuzi_url, $dokuzi_width, $dokuzi_height, $dokuzi_haikei_style, $dokuzi_title, $dokuzi_come, $dokuzi_title_style, $dokuzi_leed_style, $dokuzi_siasenbako, $dokuzi_yobi10] = pl_pad(pl_split('/<>/u', $dokuzi_settei_data), 10);
    pl_close('OIB');
    lock(...$_args);
    $dokuzi_title = $in['タイトル'];
    $dokuzi_come = $in['コメント'];
    $dokuzi_url = $in['dokuzi_url'];
    $dokuzi_haikei_style = $in['dokuzi_haikei_style'];
    $dokuzi_title_style = $in['dokuzi_title_style'];
    $dokuzi_leed_style = $in['dokuzi_leed_style'];
    $dokuzi_width = $in['dokuzi_width'];
    $dokuzi_height = $in['dokuzi_height'];
    $dokuzi_siasenbako = $in['dokuzi_siasenbako'];
    $dokuzi_yobi10 = $in['dokuzi_yobi10'];
    $dokuzi_settei_temp = "{$dokuzi_url}<>{$dokuzi_width}<>{$dokuzi_height}<>{$dokuzi_haikei_style}<>{$dokuzi_title}<>{$dokuzi_come}<>{$dokuzi_title_style}<>{$dokuzi_leed_style}<>{$dokuzi_siasenbako}<>{$dokuzi_yobi10}<>\n";
    if (!(pl_open('OLOUT', ">{$dokuzi_settei_file}"))) { error("{$dokuzi_settei_file}に書き込みが出来ません"); }
    pl_print('OLOUT', $dokuzi_settei_temp);
    pl_close('OLOUT');
    unlock(...$_args);
    my_house_settei(...$_args);
}

function by_iekey(...$_args)
{
    global $a, $b, $ie_values;
    return (n($ie_values[n($a)]) <=> n($ie_values[n($b)]));
}

function house_change(...$_args)
{
    global $_, $housu_nedan, $hyouzi_town_hairetu, $i, $ie_hash, $ie_key, $ie_keys, $ie_rank, $ie_val, $ie_values,
        $iegazou, $img_dir, $in, $k_temp, $kensetu_hiyou, $message_win, $money, $my_log_file, $my_point_is,
        $my_town_is, $name, $new_ori_ie_list, $ori_ie_image, $ori_ie_list, $ori_ie_name, $ori_ie_para, $ori_ie_rank,
        $ori_ie_sentaku_point, $ori_ie_temp, $ori_ie_town, $ori_k_id, $script, $this_script, $town_hairetu,
        $town_sprit_matrix, $town_temp, $town_tika_hairetu, $write_town_data;
    // 売却の場合
    if (($in['command'] == 'baikyaku')) {
        lock(...$_args);
        // 家リストへの書き込み
        if (!(pl_open('IN', (string)($ori_ie_list)))) { error("Open Error : {$ori_ie_list}"); }
        $ori_ie_para = pl_readlines('IN');
        pl_close('IN');
        $__s1 = $_ ?? null;
        foreach ($ori_ie_para ?? [] as $_) {
            ori_ie_sprit($_);
            if (((string)$name === (string)(string)($ori_ie_name))) {
                // タウン情報書き換え用に情報を取得
                $my_town_is = $ori_ie_town;
                $my_point_is = $ori_ie_sentaku_point;
                continue;
            }
            ori_ie_temp(...$_args);
            $new_ori_ie_list[] = $ori_ie_temp;
        }
        $_ = $__s1;
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
        // ニュース記録
        news_kiroku('家', "{$name}さんが「{$town_hairetu[n($my_town_is)]}」の家を売却しました。");
        // ver.1.3
        unlock(...$_args);
        // ログ更新
        if (!(pl_is_admin_player(...$_args))) {
            $money = n($money) + (n($town_tika_hairetu[n($my_town_is)]) * 10000);
        }
        temp_routin(...$_args);
        log_kousin($my_log_file, $k_temp);
        t_header('', 'sonomati');
        echo <<<EOM
        	<div align="center"><br><table  border="0"  cellspacing="5" cellpadding="0" width="300" style="{$message_win}"><tr><td>
        <span class="job_messe">
        家を売却しました。
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
    // 変更処理
    if (($in['command'] == 'do_change')) {
        if (($in['matirank'] == '')) {
            error('家のランクが選択されていません');
        }
        if (!pl_match('/^([0-3]|99)$/u', $in['matirank'])) {
            error('家のランクが不正です');
        }
        if ((($in['iegazou'] != '') && ($ie_hash[$in['iegazou']] == ''))) {
            error('家の画像が不正です');
        }
        $kensetu_hiyou = (((n($ie_hash[$in['iegazou']]) + n($housu_nedan[n($in['matirank'])]))) * 10000);
        if (!(pl_is_admin_player(...$_args))) {
            if ((n($kensetu_hiyou) > n($money))) {
                error('お金が足りません。');
            }
        }
        lock(...$_args);
        // 家リストへの書き込み
        if (!(pl_open('IN', (string)($ori_ie_list)))) { error("Open Error : {$ori_ie_list}"); }
        $ori_ie_para = pl_readlines('IN');
        pl_close('IN');
        $__s2 = $_ ?? null;
        foreach ($ori_ie_para ?? [] as $_) {
            ori_ie_sprit($_);
            if (((string)$in['iesettei_id'] === (string)$ori_k_id)) {
                // ver.1.3
                if (($in['iegazou'] != '')) {
                    $ori_ie_image = "{$img_dir}/{$in['iegazou']}";
                }
                if (((string)$in['matirank'] !== (string)'99')) {
                    $ori_ie_rank = $in['matirank'];
                }
            }
            ori_ie_temp(...$_args);
            $new_ori_ie_list[] = $ori_ie_temp;
        }
        $_ = $__s2;
        // 家リスト更新
        if (!(pl_open('OIO', ">{$ori_ie_list}"))) { error("{$ori_ie_list}に書き込めません"); }
        pl_print('OIO', pl_join('', $new_ori_ie_list));
        pl_close('OIO');
        unlock(...$_args);
        // ログ更新
        if (!(pl_is_admin_player(...$_args))) {
            $money = n($money) - n($kensetu_hiyou);
        }
        temp_routin(...$_args);
        log_kousin($my_log_file, $k_temp);
        t_header(...$_args);
        echo <<<EOM
        	<div align="center"><br><table  border="0"  cellspacing="5" cellpadding="0" width="300" style="{$message_win}"><tr><td>
        <span class="job_messe">
        家の変更をしました。
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
    // 変更処理の場合の閉じ
    // 家画像をハッシュから展開
    t_header('kentiku_style');
    foreach ($ie_hash ?? [] as $ie_key => $ie_val) {
        $ie_keys[] = $ie_key;
        $ie_values[] = $ie_val;
    }
    $ie_rank = pl_slice($ie_keys, pl_sort('by_iekey', pl_range(0, (pl_count($ie_keys) - 1))));
    $i = 1;
    $__s3 = $_ ?? null;
    foreach ($ie_rank ?? [] as $_) {
        $iegazou .= "<td align=\"center\"><input type=\"radio\" name=\"iegazou\" value=\"{$_}\"><br><img src=\"{$img_dir}/{$_}\" width=\"32\" height=\"32\"><br>{$ie_hash[$_]}万円\n";
        if (((n($i) % 12) == 0)) {
            $iegazou .= '</tr><tr>';
        }
        $i++;
    }
    $_ = $__s3;
    $iegazou .= "<td align=\"center\"><input type=\"radio\" name=\"iegazou\" value=\"\"><br>現状のまま\n";
    if (((n($i) % 12) == 0)) {
        $iegazou .= '</tr><tr>';
    }
    echo <<<EOM
    	<table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr>
    	<td bgcolor="#ffffff">外観、内装の変更は、建築時と同額の費用がかかります。</td>
    	</tr></table><br>
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="house_change">
    	<input type="hidden" name="command" value="do_change">
    	<input type="hidden" name="iesettei_id" value="{$in['iesettei_id']}">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<table width="90%" border="0" cellspacing="0" cellpadding="8" align="center" class="yosumi">
    	<tr><td valign="top">
    	<div class="honbun2">●家の選択</div>
    	<table boader="0" cellspacing="0" cellpadding="5" width="100%"><tr>
    	{$iegazou}
    	</tr></table><br>
    
    	<table boader="0" cellspacing="0" cellpadding="5" width="100%"><tr>
    	<td><div class="honbun2">●家のランク（内装費）</div><br>
    	<input type="radio" name="matirank" value="0">Ａランク：{$housu_nedan[0]}万円（４コンテンツ同時表示が可能）<br>
    	<input type="radio" name="matirank" value="1">Ｂランク：{$housu_nedan[1]}万円（３コンテンツ同時表示が可能）<br>
    	<input type="radio" name="matirank" value="2">Ｃランク：{$housu_nedan[2]}万円（２コンテンツ同時表示が可能）<br>
    	<input type="radio" name="matirank" value="3">Ｄランク：{$housu_nedan[3]}万円（１コンテンツ同時表示が可能）<br>
    	<input type="radio" name="matirank" value="99">現状のまま
    	</td></tr></table>
    EOM;
    echo <<<EOM
    	<br><br><div align="center"><input type="submit" value=" OK  "></div>
    	</td></tr></table>
    	<div align="center"><!--TW_BACK--></div>
    	</body></html>
    EOM;
    exit;
}

function houmon(...$_args)
{
    global $centents_botan, $houmonsaki_settei_file, $in, $kihon_oriie_settei, $my_con1, $my_con1_title, $my_con2,
        $my_con2_title, $my_con3, $my_con3_title, $my_con4, $my_con4_title, $saisenbako_data, $this_script;
    $houmonsaki_settei_file = "./member/{$in['ori_ie_id']}/oriie_settei.cgi";
    if (!(pl_open('HS', (string)($houmonsaki_settei_file)))) { error('まだ工事中です'); }
    $kihon_oriie_settei = pl_readline('HS');
    oriie_settei_sprit($kihon_oriie_settei);
    pl_close('HS');
    if (($my_con1 == '')) {
        error('まだ人に見せられる家では無いようです。');
    }
    // ver.1.30
    // さい銭箱を変数に入れる
    $saisenbako_data = <<<EOM
	<form method="POST" action="{$this_script}">
	<input type="hidden" name="mode" value="saisensuru">
	<input type="hidden" name="name" value="{$in['name']}">
	<input type="hidden" name="pass" value="{$in['pass']}">
	<input type="hidden" name="ori_ie_id" value="{$in['ori_ie_id']}">
	<input type="hidden" name="town_no" value="{$in['town_no']}">
	<select name="saisengaku">
	<option value="100">100円</option>
	<option value="500">500円</option>
	<option value="1000">1000円</option>
	<option value="2000">2000円</option>
	<option value="5000">5000円</option>
	<option value="10000">10000円</option>
	</select>
	<input type="submit" value="さい銭する">
	</form>
EOM;
    if ($my_con1_title) {
        $centents_botan .= "<td><form method=\"POST\" action=\"{$this_script}\"><input type=\"hidden\" name=\"mode\" value=\"houmon\"><input type=\"hidden\" name=\"ori_ie_id\" value=\"{$in['ori_ie_id']}\"><input type=\"hidden\" name=\"name\" value=\"{$in['name']}\"><input type=\"hidden\" name=\"pass\" value=\"{$in['pass']}\"><input type=\"hidden\" name=\"con_sele\" value=\"{$my_con1}\"><input type=\"hidden\" name=\"town_no\" value=\"{$in['town_no']}\"><input type=\"submit\" value=\"{$my_con1_title}\"></form></td>";
    }
    if ($my_con2_title) {
        $centents_botan .= "<td><form method=\"POST\" action=\"{$this_script}\"><input type=\"hidden\" name=\"mode\" value=\"houmon\"><input type=\"hidden\" name=\"ori_ie_id\" value=\"{$in['ori_ie_id']}\"><input type=\"hidden\" name=\"name\" value=\"{$in['name']}\"><input type=\"hidden\" name=\"pass\" value=\"{$in['pass']}\"><input type=\"hidden\" name=\"con_sele\" value=\"{$my_con2}\"><input type=\"hidden\" name=\"town_no\" value=\"{$in['town_no']}\"><input type=\"submit\" value=\"{$my_con2_title}\"></form></td>";
    }
    if ($my_con3_title) {
        $centents_botan .= "<td><form method=\"POST\" action=\"{$this_script}\"><input type=\"hidden\" name=\"mode\" value=\"houmon\"><input type=\"hidden\" name=\"ori_ie_id\" value=\"{$in['ori_ie_id']}\"><input type=\"hidden\" name=\"name\" value=\"{$in['name']}\"><input type=\"hidden\" name=\"pass\" value=\"{$in['pass']}\"><input type=\"hidden\" name=\"con_sele\" value=\"{$my_con3}\"><input type=\"hidden\" name=\"town_no\" value=\"{$in['town_no']}\"><input type=\"submit\" value=\"{$my_con3_title}\"></form></td>";
    }
    if ($my_con4_title) {
        $centents_botan .= "<td><form method=\"POST\" action=\"{$this_script}\"><input type=\"hidden\" name=\"mode\" value=\"houmon\"><input type=\"hidden\" name=\"ori_ie_id\" value=\"{$in['ori_ie_id']}\"><input type=\"hidden\" name=\"name\" value=\"{$in['name']}\"><input type=\"hidden\" name=\"pass\" value=\"{$in['pass']}\"><input type=\"hidden\" name=\"con_sele\" value=\"{$my_con4}\"><input type=\"hidden\" name=\"town_no\" value=\"{$in['town_no']}\"><input type=\"submit\" value=\"{$my_con4_title}\"></form></td>";
    }
    if (($in['con_sele'] == '')) {
        if (((string)$my_con1 === (string)'0')) {
            normal_bbs(...$_args);
        } elseif (((string)$my_con1 === (string)'1')) {
            omise(...$_args);
        } elseif (((string)$my_con1 === (string)'2')) {
            dokuzi_url(...$_args);
        } elseif (((string)$my_con1 === (string)'3')) {
            gentei(...$_args);
        }
    } elseif (((string)$in['con_sele'] === (string)'0')) {
        normal_bbs(...$_args);
    } elseif (((string)$in['con_sele'] === (string)'1')) {
        omise(...$_args);
    } elseif (((string)$in['con_sele'] === (string)'2')) {
        dokuzi_url(...$_args);
    } elseif (((string)$in['con_sele'] === (string)'3')) {
        gentei(...$_args);
    }
}

function normal_bbs(...$_args)
{
    global $_, $admin_pass, $all_guest, $b_com, $b_date, $b_mail, $b_name, $b_num, $b_res, $back,
        $bbs1_a_hover_style, $bbs1_body_style, $bbs1_come, $bbs1_leed_style, $bbs1_log_file, $bbs1_settei_data,
        $bbs1_settei_file, $bbs1_siasenbako, $bbs1_table2_style, $bbs1_tablewidth, $bbs1_title, $bbs1_title_style,
        $bbs1_toukousya_style, $bbs1_toukouwidth, $bbs1_yobi10, $bbs1_yobi5, $bbs1_yobi6, $bbs1_yobi7, $bbs1_yobi8,
        $bbs1_yobi9, $bbs_alldata, $centents_botan, $err, $genzai_zikoku, $guestfile, $hyouzi_check, $i, $in, $job,
        $k_yobi3, $mem_lock_num, $name, $new_all_guest, $next, $page, $return_my_town, $saisenbako_data, $sanka_name,
        $sanka_timer, $sanka_tmp, $tajuukinsi_flag, $this_script;
    if ((n($tajuukinsi_flag) == 1)) {
        if (($k_yobi3 != '')) {
            error("多重登録は禁止されています。<br>{$k_yobi3}");
        }
    }
    if ((n($tajuukinsi_flag) == 1)) {
        tajuucheck(...$_args);
    }
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
    if (($in['ori_ie_id'] == 'admin')) {
        $bbs1_settei_file = './member/admin/bbs' . $in['bbs_num'] . '_ini.cgi';
    } else {
        $bbs1_settei_file = "./member/{$in['ori_ie_id']}/bbs1_ini.cgi";
    }
    if (!(pl_open('OIB', (string)($bbs1_settei_file)))) { error("Open Error : {$bbs1_settei_file}"); }
    $bbs1_settei_data = pl_readline('OIB');
    [$bbs1_title, $bbs1_come, $bbs1_body_style, $bbs1_toukousya_style, $bbs1_table2_style, $bbs1_toukouwidth, $bbs1_a_hover_style, $bbs1_tablewidth, $bbs1_title_style, $bbs1_leed_style, $bbs1_siasenbako, $bbs1_yobi5, $bbs1_yobi6, $bbs1_yobi7, $bbs1_yobi8, $bbs1_yobi9, $bbs1_yobi10] = pl_pad(pl_split('/<>/u', $bbs1_settei_data), 17);
    pl_close('OIB');
    // 住民専用掲示板の場合チェック
    if (($bbs1_yobi6 == 'on')) {
        if (((string)$in['admin_pass'] !== (string)$admin_pass)) {
            my_town_check($name);
            if (($return_my_town == 'no_town')) {
                error('この街に住んでいる人以外は見ることができません');
            }
            if (((string)$return_my_town !== (string)(string)($in['town_no']))) {
                error('この街に住んでいる人以外は見ることができません');
            }
        }
    }
    ori_header((string)($bbs1_body_style), (string)($bbs1_siasenbako), (string)($bbs1_a_hover_style));
    echo <<<EOM
    	<table  border="0" cellspacing="0" cellpadding="5" align="center" >
    	<tr>{$centents_botan}<td align="right">{$saisenbako_data}</td></tr>
    	</table>
    EOM;
    echo <<<EOM
    	<br><table width="{$bbs1_tablewidth}" border="0" cellspacing="0" cellpadding="14" align="center" style="{$bbs1_table2_style}">
    	<tr><td>
    	<div style = "{$bbs1_title_style}">{$bbs1_title}</div>
    	<div style = "{$bbs1_leed_style}">{$bbs1_come}</div>
    	</td></tr>
    	<td>
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="bbs_regist">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="job" value="{$job}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="ori_ie_id" value="{$in['ori_ie_id']}">
    	<input type="hidden" name="bbs_num" value="{$in['bbs_num']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<textarea cols="{$bbs1_toukouwidth}" rows="4" name="b_com" wrap="soft"></textarea>
    	<input type="submit" value="新規投稿">
    	</form>
    EOM;
    $page = $in['page'];
    if (($page == '')) {
        $page = 0;
    }
    $i = 0;
    if (($in['ori_ie_id'] == 'admin')) {
        $bbs1_log_file = './member/admin/bbs' . $in['bbs_num'] . '_log.cgi';
    } else {
        $bbs1_log_file = "./member/{$in['ori_ie_id']}/bbs1_log.cgi";
    }
    if (!(pl_open('IN', (string)($bbs1_log_file)))) { error("Open Error : {$bbs1_log_file}"); }
    $bbs_alldata = pl_readlines('IN');
    pl_close('IN');
    $__s2 = $_ ?? null;
    foreach ($bbs_alldata ?? [] as $_) {
        [$b_num, $b_name, $b_date, $b_res, $b_mail, $b_com] = pl_pad(pl_split('/<>/u', $_), 6);
        if ($b_num) {
            $i++;
        }
        if ((n($i) == 1)) {
            continue;
        }
        if ((n($i) < (n($page) + 1))) {
            continue;
        }
        if ((n($i) > (n($page) + 10))) {
            break;
        }
        if ($b_res) {
            echo "<div><span style=\"{$bbs1_toukousya_style}\">{$b_name}</span>：{$b_com}（{$b_date}）<span style=\"font-size:9px\">記事no.{$b_mail}</span></div>\n";
            // ver.1.40
        } else {
            echo <<<EOM
            	<br><hr size="1"><br>
            	<div><span style="{$bbs1_yobi5}">NO.{$b_num}</span><br> <span style="{$bbs1_toukousya_style}">{$b_name}</span>：{$b_com}（{$b_date}）<span style="font-size:9px">記事no.{$b_mail}</span><!--ver.1.40-->
            	<form method="POST" action="{$this_script}">
            	<input type="hidden" name="mode" value="bbs_regist">
            	<input type="hidden" name="name" value="{$in['name']}">
            	<input type="hidden" name="job" value="{$job}">
            	<input type="hidden" name="pass" value="{$in['pass']}">
            	<input type="hidden" name="ori_ie_id" value="{$in['ori_ie_id']}">
            	<input type="hidden" name="bbs_num" value="{$in['bbs_num']}">
            	<input type="hidden" name="b_res" value="{$b_num}">
            	<input type="hidden" name="town_no" value="{$in['town_no']}">
            	<input type="text" size="{$bbs1_toukouwidth}" name="b_com" style="{$bbs1_yobi7}"> <input type="submit" value="レス">
            	</form></div>
            EOM;
        }
    }
    $_ = $__s2;
    // foreach閉じ
    $next = (n($page) + 10);
    $back = (n($page) - 10);
    echo '<div align="center"><table border="0"><tr>';
    if ((n($back) >= 0)) {
        // 管理者BBSの場合
        if (($in['ori_ie_id'] == 'admin')) {
            echo <<<EOM
            			<form method="POST" action="{$this_script}">
            			<input type="hidden" name="mode" value="normal_bbs">
            			<input type="hidden" name="ori_ie_id" value="admin">
            			<input type="hidden" name="bbs_num" value="{$in['bbs_num']}">
            			<input type="hidden" name="name" value="{$in['name']}">
            			<input type="hidden" name="pass" value="{$in['pass']}">
            			<input type="hidden" name="town_no" value="{$in['town_no']}">
            			<input type="hidden" name="page" value="{$back}">
            			<input type="submit" value="BACK">
            			</form>
            EOM;
        } else {
            // 個人の家のBBSの場合
            echo <<<EOM
            			<td><form method="POST" action="{$this_script}">
            			<input type="hidden" name="mode" value="houmon">
            			<input type="hidden" name="con_sele" value="0">
            			<input type="hidden" name="name" value="{$in['name']}">
            			<input type="hidden" name="pass" value="{$in['pass']}">
            			<input type="hidden" name="town_no" value="{$in['town_no']}">
            			<input type="hidden" name="ori_ie_id" value="{$in['ori_ie_id']}">
            			<input type="hidden" name="page" value="{$back}">
            			<input type="submit" value="BACK"></form></td>
            EOM;
        }
    }
    if ((n($next) < n($i))) {
        if (($in['ori_ie_id'] == 'admin')) {
            echo <<<EOM
            			<form method="POST" action="{$this_script}">
            			<input type="hidden" name="mode" value="normal_bbs">
            			<input type="hidden" name="ori_ie_id" value="admin">
            			<input type="hidden" name="bbs_num" value="{$in['bbs_num']}">
            			<input type="hidden" name="name" value="{$in['name']}">
            			<input type="hidden" name="pass" value="{$in['pass']}">
            			<input type="hidden" name="town_no" value="{$in['town_no']}">
            			<input type="hidden" name="page" value="{$next}">
            			<input type="submit" value="NEXT">
            </form>
            EOM;
        } else {
            echo <<<EOM
            			<td><form method="POST" action="{$this_script}">
            			<input type="hidden" name="mode" value="houmon">
            			<input type="hidden" name="con_sele" value="0">
            			<input type="hidden" name="name" value="{$in['name']}">
            			<input type="hidden" name="pass" value="{$in['pass']}">
            			<input type="hidden" name="town_no" value="{$in['town_no']}">
            			<input type="hidden" name="ori_ie_id" value="{$in['ori_ie_id']}">
            			<input type="hidden" name="page" value="{$next}">
            			<input type="submit" value="NEXT"></form></td>
            EOM;
        }
    }
    echo '</tr></table></div>';
    echo <<<EOM
    	</td></tr></table>
    	<table width="{$bbs1_tablewidth}" border="0" cellspacing="0" cellpadding="0" align="center">
    	<tr><td>
    	<form method="POST" action="{$this_script}">
    	<div style=" font-size: 10px; color: #444444"><br>
    	<div style="text-align:center">
    	<input type="hidden" name="mode" value="bbs_delete">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="ori_ie_id" value="{$in['ori_ie_id']}">
    	<input type="hidden" name="bbs_num" value="{$in['bbs_num']}">
    	記事no. <input type="text" name="b_count" size="8">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value="削除">
    	</div>
    	<br>※ゲーム管理者のみ「記事no.」を指定して記事を削除することができます。<br>
    	※投稿することでお金を得ることができますが、無意味な発言、荒らし行為など不適切な投稿があった場合、減金、ホストの公開、アクセス拒否などのペナルティがありますのでご注意ください。</div>
    	</form></td></tr><tr><td>
    	<br><div style=" font-size: 11px;" align="center"><!--TW_BACK--></div>
    	</td></tr>
    	</table>
    EOM;
    if (($in['ori_ie_id'] == 'admin')) {
        hooter('login_view', '戻る');
    } else {
        hooter('login_view', '家を出る');
    }
    exit;
}

function bbs_regist(...$_args)
{
    global $_, $all_data, $all_total_counter, $b_mail, $bbs1_log_file, $bbs_kizi_max, $date2, $in, $k_temp,
        $message_in, $message_win, $money, $my_log_file, $name_seikei, $new_all_data, $new_toukou, $randed,
        $this_script, $top, $top_idou, $total_counter, $total_kizisuu;
    lock(...$_args);
    // ログファイル更新
    // ログを読み込み
    if (($in['ori_ie_id'] == 'admin')) {
        $bbs1_log_file = './member/admin/bbs' . $in['bbs_num'] . '_log.cgi';
    } else {
        $bbs1_log_file = "./member/{$in['ori_ie_id']}/bbs1_log.cgi";
    }
    if (!(pl_open('IN', (string)($bbs1_log_file)))) { error("Open Error : {$bbs1_log_file}"); }
    // 先頭行を取得
    $total_counter = pl_readline('IN');
    [$total_counter, $all_total_counter] = pl_pad(pl_split('/<>/u', $total_counter), 2);
    // ver.1.40
    $top = pl_readline('IN');
    [$b_num, $b_name, $b_date, $b_res, $b_count, $b_com] = pl_pad(pl_split('/<>/u', $top), 6);
    // ver.1.40
    pl_close('IN');
    $in['b_com'] = preg_replace('/<>/u', '&lt;&gt;', (string)$in['b_com'], -1);
    // タグ禁止処理
    // $in{'b_com'} =~ s/</&lt;/g;
    // $in{'b_com'} =~ s/>/&gt;/g;
    // コメントの改行処理
    $in['b_com'] = preg_replace('/\\r\\n/u', '<br>', (string)$in['b_com'], -1);
    $in['b_com'] = preg_replace('/\\r/u', '<br>', (string)$in['b_com'], -1);
    $in['b_com'] = preg_replace('/\\n/u', '<br>', (string)$in['b_com'], -1);
    $in['b_com'] = preg_replace('/([^=^\\"]|^)(https?\\:[\\w\\.\\~\\-\\/\\?\\&\\+\\=\\:\\@\\%\\;\\#\\%]+)/u', '${1}<a href="${2}" target="_blank">${2}</a>', (string)$in['b_com'], -1);
    // ver.1.30ここから
    $name_seikei = $in['name'] . "<span style=\"font-size:9px\">（{$in['job']}）</span>";
    if ((((string)$name_seikei === (string)(string)($b_name)) && ((string)$in['b_com'] === (string)(string)($b_com)))) {
        error('二重投稿です');
    }
    // ver.1.30ここまで
    if (($in['b_com'] == '')) {
        error('コメントが入力されていません');
    }
    if (!(pl_open('IN', (string)($bbs1_log_file)))) { error("Open Error : {$bbs1_log_file}"); }
    $all_data = pl_readlines('IN');
    pl_shift($all_data);
    $total_kizisuu = pl_count($all_data);
    pl_close('IN');
    time_get(...$_args);
    $all_total_counter++;
    // トータル記事数をカウントver.1.40
    // 新規投稿なら新記事Noを取得
    if (($in['b_res'] == '')) {
        $total_counter++;
        // 更新配列を定義
        $new_toukou = "{$total_counter}<>{$in['name']}<span style=\"font-size:9px\">（{$in['job']}）</span><>{$date2}<>{$in['b_res']}<>{$all_total_counter}<>{$in['b_com']}<>\n";
        // ver.1.40
        pl_unshift($all_data, $new_toukou);
        $total_counter = "{$total_counter}<>{$all_total_counter}<>\n";
        // ver.1.40
        pl_unshift($all_data, $total_counter);
        // レスの場合
    } else {
        // 更新配列を定義
        $new_toukou = "<>{$in['name']}<span style=\"font-size:9px\">（{$in['job']}）</span><>{$date2}<>{$in['b_res']}<>{$all_total_counter}<>{$in['b_com']}<>\n";
        // ver.1.40
        $__s1 = $_ ?? null;
        foreach ($all_data ?? [] as $_) {
            [$b_num, $b_name, $b_date, $b_res, $b_mail, $b_com] = pl_pad(pl_split('/<>/u', $_), 6);
            if ((((string)$b_num === (string)(string)($in['b_res'])) || ((string)$b_res === (string)(string)($in['b_res'])))) {
                $top_idou[] = $_;
                continue;
            }
            $new_all_data[] = $_;
        }
        $_ = $__s1;
        $top_idou[] = $new_toukou;
        pl_unshift($new_all_data, ...($top_idou ?? []));
        $total_counter = "{$total_counter}<>{$all_total_counter}<>\n";
        // ver.1.40
        pl_unshift($new_all_data, $total_counter);
        $all_data = [];
        $all_data = $new_all_data;
    }
    // レスの場合閉じ
    if ((n($total_kizisuu) >= n($bbs_kizi_max))) {
        pl_pop($all_data);
    }
    // ログを更新
    if (!(pl_open('OUT', ">{$bbs1_log_file}"))) { error("Write Error : {$bbs1_log_file}"); }
    pl_print('OUT', pl_join('', $all_data));
    pl_close('OUT');
    // 街の繁栄度アップ
    town_haneiup($in['town_no']);
    // ロック解除
    unlock(...$_args);
    // お金をゲット
    $randed = n($randed) + (pl_int(pl_rand(10)) + 1);
    if ((n($randed) == 7)) {
        $randed = n($randed) + (pl_int(pl_rand(10000)) + 5000);
        $money = n($money) + n($randed);
        $message_in = "●ボーナスです！{$randed}円のお金をゲットしました。";
    } else {
        $randed = n($randed) + (pl_int(pl_rand(2000)) + 1000);
        $money = n($money) + n($randed);
        $message_in = "●{$randed}円のお金を得ました。";
    }
    temp_routin(...$_args);
    log_kousin($my_log_file, $k_temp);
    t_header('', 'sonomati');
    echo <<<EOM
    	<div align="center"><br><table  border="0"  cellspacing="5" cellpadding="0" width="300" style="{$message_win}"><tr><td>
    <span class="job_messe">
    {$message_in}
    </span>
    </td></tr></table>
    <br>
    	<form method="POST" action="{$this_script}">
    EOM;
    if (($in['ori_ie_id'] == 'admin')) {
        echo '<input type="hidden" name="mode" value="normal_bbs">';
    } else {
        echo '<input type="hidden" name="mode" value="houmon">';
    }
    echo <<<EOM
    	<input type="hidden" name="con_sele" value="0">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="ori_ie_id" value="{$in['ori_ie_id']}">
    	<input type="hidden" name="bbs_num" value="{$in['bbs_num']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value="戻る">
    	</form></div>
    EOM;
    exit;
}

function gentei(...$_args)
{
    global $_, $b_com, $b_date, $b_mail, $b_name, $b_num, $b_title, $back, $bbs_alldata, $centents_botan,
        $gentei_body_style, $gentei_come, $gentei_daimei_style, $gentei_kensuu, $gentei_leed_style, $gentei_log_file,
        $gentei_settei_data, $gentei_settei_file, $gentei_siasenbako, $gentei_table2_style, $gentei_tablewidth,
        $gentei_title, $gentei_title_style, $gentei_yobi10, $gentei_yobi5, $gentei_yobi6, $gentei_yobi7,
        $gentei_yobi8, $gentei_yobi9, $i, $in, $next, $page, $saisenbako_data, $this_script;
    $gentei_settei_file = "./member/{$in['ori_ie_id']}/gentei_ini.cgi";
    if (!(pl_open('OIB', (string)($gentei_settei_file)))) { error("Open Error : {$gentei_settei_file}"); }
    $gentei_settei_data = pl_readline('OIB');
    [$gentei_title, $gentei_come, $gentei_body_style, $gentei_daimei_style, $gentei_table2_style, $gentei_kensuu, $gentei_tablewidth, $gentei_title_style, $gentei_leed_style, $gentei_siasenbako, $gentei_yobi5, $gentei_yobi6, $gentei_yobi7, $gentei_yobi8, $gentei_yobi9, $gentei_yobi10] = pl_pad(pl_split('/<>/u', $gentei_settei_data), 16);
    pl_close('OIB');
    ori_header((string)($gentei_body_style), (string)($gentei_siasenbako));
    echo <<<EOM
    	<table  border="0" cellspacing="0" cellpadding="5" align="center" >
    	<tr>{$centents_botan}<td align="right">{$saisenbako_data}</td></tr>
    	</table>
    EOM;
    echo <<<EOM
    	<table width="{$gentei_tablewidth}" border="0" cellspacing="0" cellpadding="8" align="center" style="{$gentei_table2_style}">
    	<tr><td>
    	<div style = "{$gentei_title_style}">{$gentei_title}</div>
    	<div style = "{$gentei_leed_style}">{$gentei_come}</div>
    	</td></tr>
    	<td>
    EOM;
    $page = $in['page'];
    if (($page == '')) {
        $page = 0;
    }
    $i = 0;
    $gentei_log_file = "./member/{$in['ori_ie_id']}/gentei_log.cgi";
    if (!(pl_open('IN', (string)($gentei_log_file)))) { error("Open Error : {$gentei_log_file}"); }
    $bbs_alldata = pl_readlines('IN');
    $__s1 = $_ ?? null;
    foreach ($bbs_alldata ?? [] as $_) {
        [$b_num, $b_name, $b_date, $b_title, $b_mail, $b_com] = pl_pad(pl_split('/<>/u', $_), 6);
        $i++;
        if ((n($i) < (n($page) + 1))) {
            continue;
        }
        if ((n($i) > (n($page) + n($gentei_kensuu)))) {
            break;
        }
        echo "<div style=\"{$gentei_daimei_style}\">{$b_title}</div><div>{$b_com}（{$b_date}）<span style=\"font-size:9px\">記事no.{$b_num}</span></div><br>\n";
        // ver.1.40
    }
    $_ = $__s1;
    // foreach閉じ
    pl_close('IN');
    $next = (n($page) + n($gentei_kensuu));
    $back = (n($page) - n($gentei_kensuu));
    echo '<div align="center"><table border="0"><tr>';
    if ((n($back) >= 0)) {
        echo <<<EOM
        	<td><form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="houmon">
        	<input type="hidden" name="con_sele" value="3">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<input type="hidden" name="ori_ie_id" value="{$in['ori_ie_id']}">
        	<input type="hidden" name="page" value="{$back}">
        	<input type="submit" value="BACK"></form></td>
        EOM;
    }
    if ((n($next) < n($i))) {
        echo <<<EOM
        	<td><form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="houmon">
        	<input type="hidden" name="con_sele" value="3">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<input type="hidden" name="ori_ie_id" value="{$in['ori_ie_id']}">
        	<input type="hidden" name="page" value="{$next}">
        	<input type="submit" value="NEXT"></form></td>
        EOM;
    }
    echo <<<EOM
    	</tr></table></div>
    	</td></tr>
    	</table>
    	<div align="center">
    	<form method="POST" action="{$this_script}">
    	<div style=" font-size: 10px; color: #444444"><br>
    	<input type="hidden" name="mode" value="gentei_delete">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="ori_ie_id" value="{$in['ori_ie_id']}">
    	記事no. <input type="text" name="b_num" size="8">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value="削除">
    	</form>
    	※記事を書いた本人とゲーム管理者のみ「記事no.」を指定して記事を削除することができます。<br>
    	</div>
    EOM;
    hooter('login_view', '家を出る');
    exit;
}

function gentei_regist(...$_args)
{
    global $_, $date2, $gentei_log_file, $i, $in, $new, $top;
    lock(...$_args);
    // ログファイル更新
    // ログを読み込み
    $gentei_log_file = "./member/{$in['iesettei_id']}/gentei_log.cgi";
    // ver.1.3
    if (!(pl_open('IN', (string)($gentei_log_file)))) { error("Open Error : {$gentei_log_file}"); }
    // 先頭行を取得
    $top = pl_readline('IN');
    [$b_num, $b_name, $b_date, $b_title, $b_mail, $b_com] = pl_pad(pl_split('/<>/u', $top), 6);
    // タグ禁止処理
    // $in{'b_com'} =~ s/</&lt;/g;
    // $in{'b_com'} =~ s/>/&gt;/g;
    if ((((string)$in['name'] === (string)$b_name) && ((string)$in['b_com'] === (string)$b_com))) {
        error('二重投稿です');
    }
    if (($in['b_com'] == '')) {
        error('内容が入力されていません');
    }
    // 新記事Noを取得
    $b_num++;
    // 更新配列を定義
    time_get(...$_args);
    $new[0] = "{$b_num}<>{$in['name']}<>{$date2}<>{$in['b_title']}<>{$in['b_mail']}<>{$in['b_com']}<>\n";
    $new[1] = $top;
    while (($_ = pl_readline('IN')) !== null) {
        $i++;
        $new[] = $_;
        if ((n($i) >= 50)) {
            break;
        }
    }
    pl_close('IN');
    // ログを更新
    if (!(pl_open('OUT', ">{$gentei_log_file}"))) { error("Write Error : {$gentei_log_file}"); }
    pl_print('OUT', pl_join('', $new));
    pl_close('OUT');
    // ロック解除
    unlock(...$_args);
    message('投稿しました。', 'my_house_settei', 'original_house.php');
}

function omise(...$_args)
{
    global $_, $calory_hyouzi, $centents_botan, $data, $house_type, $in, $k_id, $kaenai_seigen, $key, $keys,
        $kounyuubotan, $maeno_syo_syubetu, $monokiroku_file, $my_kounyuu_list, $omise_alldata, $omise_body_style,
        $omise_come, $omise_koumokumei, $omise_leed_style, $omise_log_file, $omise_settei_data, $omise_settei_file,
        $omise_siasenbako, $omise_syouhin_table, $omise_syubetu, $omise_table1_style, $omise_table2_style,
        $omise_title, $omise_title_style, $omise_yobi10, $omise_yobi5, $omise_yobi6, $omise_yobi7, $omise_yobi8,
        $omise_yobi9, $saisenbako_data, $script, $siharai_houhou, $syo_bijutu, $syo_cal, $syo_comment, $syo_eigo,
        $syo_etti, $syo_hinmoku, $syo_kankaku, $syo_kenkou, $syo_kokugo, $syo_kouka, $syo_kounyuubi, $syo_kyakuryoku,
        $syo_looks, $syo_love, $syo_nedan, $syo_ongaku, $syo_power, $syo_rika, $syo_sintai_syouhi, $syo_speed,
        $syo_suugaku, $syo_syakai, $syo_syubetu, $syo_taikyuu, $syo_taikyuu_tani, $syo_tairyoku, $syo_unique,
        $syo_wanryoku, $syo_zaiko, $syo_zunou_syouhi, $taikyuu_hyouzi_seikei, $tinretu_alldata, $tokubai;
    $omise_log_file = "./member/{$in['ori_ie_id']}/omise_log.cgi";
    if (!(pl_open('IN', (string)($omise_log_file)))) { error("Open Error : {$omise_log_file}"); }
    $omise_alldata = pl_readlines('IN');
    pl_close('IN');
    // 種別でソート
    $__s1 = $_ ?? null;
    foreach ($omise_alldata ?? [] as $_) {
        $data = $_;
        $key = pl_idx(pl_split('/<>/u', $data), 0);
        $tinretu_alldata[] = $data;
        $keys[] = $key;
    }
    $_ = $__s1;
    $tinretu_alldata = pl_slice($tinretu_alldata, pl_sort('by_syu_keys', pl_range(0, (pl_count($tinretu_alldata) - 1))));
    $omise_settei_file = "./member/{$in['ori_ie_id']}/omise_ini.cgi";
    if (!(pl_open('OIB', (string)($omise_settei_file)))) { error("Open Error : {$omise_settei_file}"); }
    $omise_settei_data = pl_readline('OIB');
    [$omise_title, $omise_come, $omise_body_style, $omise_syubetu, $omise_table1_style, $omise_table2_style, $omise_koumokumei, $omise_syouhin_table, $omise_title_style, $omise_leed_style, $omise_siasenbako, $omise_yobi5, $omise_yobi6, $omise_yobi7, $omise_yobi8, $omise_yobi9, $omise_yobi10] = pl_pad(pl_split('/<>/u', $omise_settei_data), 17);
    // omise_yobi5＝基本販売掛け率　omise_yobi6＝商品カテゴリーのスタイル設定　omise_yobi7＝リンクのスタイル
    pl_close('OIB');
    ori_header((string)($omise_body_style), (string)($omise_siasenbako), (string)($omise_yobi7));
    echo <<<EOM
    	<table  border="0" cellspacing="0" cellpadding="5" align="center" >
    	<tr>{$centents_botan}<td align="right">{$saisenbako_data}</td></tr>
    	</table>
    EOM;
    echo <<<EOM
    	<form method="POST" action="{$script}">
    	<input type="hidden" name="mode" value="buy_syouhin">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="k_id" value="{$in['k_id']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="hidden" name="ori_ie_id" value="{$in['ori_ie_id']}">
    	<table width="100%" border="0" cellspacing="0" cellpadding="10" align="center" style="{$omise_table1_style}">
    	<tr>
    	<td  style="{$omise_title_style}" nowrap>{$omise_title}</td>
    	<td style="{$omise_leed_style}" width="65%">{$omise_come}</td>
    	</tr></table><br>
    	<table width="100%" border="0" cellspacing="1" cellpadding="5" align="center" style="{$omise_table2_style}">
    	<tr><td colspan="26">凡例：(国)＝国語up値、(数)＝数学up値、(理)＝理科up値、(社)＝社会up値、(英)＝英語up値、(音)＝音楽up値、(美)＝美術up値、（ル）=ルックスup値、（体）=体力up値、（健）=健康up値、（ス）=スピードup値、（パ）=パワーup値、（腕）=腕力up値、（脚）=脚力up値、（L）=LOVEup値、（面）=面白さup値、（H）=エッチup値<br>
    	※ギフトは贈り物専用の商品です。自分で使用することはできません。
    EOM;
    if ((n($kaenai_seigen) == 1)) {
        // ver.1.40
        if ((((string)$k_id === (string)(string)($in['ori_ie_id'])) || ((string)$house_type === (string)(string)($in['ori_ie_id'])))) {
            // ver.1.3
            echo '<br><span style="color:#ff6600">※自分や配偶者のお店で商品を買うことはできません。</span>';
        }
    }
    echo <<<EOM
    	</td></tr>
    		<tr style="{$omise_koumokumei}"><td align="center" nowrap>商品</td><td>国</td><td>数</td><td>理</td><td>社</td><td>英</td><td>音</td><td>美</td><td>ル</td><td>体</td><td>健</td><td>ス</td><td>パ</td><td>腕</td><td>脚</td><td>L</td><td>面</td><td>Ｈ</td><td align="center" nowrap>カロリー</td><td align="center" nowrap>耐久</td><td align="center">使用<br>間隔</td><td align="center">身体<br>消費</td><td align="center">頭脳<br>消費</td><td align="center">価格</td><td align="center" nowrap>　備　考　</td><td align="center" nowrap>在庫</td></tr>
    EOM;
    $__s2 = $_ ?? null;
    foreach ($tinretu_alldata ?? [] as $_) {
        syouhin_sprit($_);
        if ((n($syo_zaiko) <= 0)) {
            continue;
        }
        // 独自価格の設定があればその価格
        if ($tokubai) {
            $syo_nedan = (string)($tokubai);
        } else {
            if (($omise_syubetu == 'スーパー')) {
                $syo_nedan = pl_int(((n($syo_nedan) * 1.5) * n($omise_yobi5)));
            } else {
                $syo_nedan = pl_int((n($syo_nedan) * n($omise_yobi5)));
            }
        }
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
            echo "<tr style=\"{$omise_yobi6}\"><td colspan=\"26\">▼{$syo_syubetu}</td></tr>";
        }
        $taikyuu_hyouzi_seikei = (string)($syo_taikyuu) . (string)($syo_taikyuu_tani);
        // ver.1.3ここから
        $syo_nedan = pl_comma($syo_nedan);
        // ver.1.3ここまで
        echo <<<EOM
        		<tr style="{$omise_syouhin_table}" align="center"><td nowrap align="left">{$kounyuubotan} {$syo_hinmoku}</td><td>{$syo_kokugo}</td><td>{$syo_suugaku}</td><td>{$syo_rika}</td><td>{$syo_syakai}</td><td>{$syo_eigo}</td><td>{$syo_ongaku}</td><td>{$syo_bijutu}</td><td>{$syo_looks}</td><td>{$syo_tairyoku}</td><td>{$syo_kenkou}</td><td>{$syo_speed}</td><td>{$syo_power}</td><td>{$syo_wanryoku}</td><td>{$syo_kyakuryoku}</td><td>{$syo_love}</td><td>{$syo_unique}</td><td>{$syo_etti}</td><td align="right">{$calory_hyouzi}</td><td nowrap>{$taikyuu_hyouzi_seikei}</td><td nowrap>{$syo_kankaku}分</td><td>{$syo_sintai_syouhi}</td><td>{$syo_zunou_syouhi}</td><td align="right" nowrap>{$syo_nedan}円</td><td align="left">{$syo_comment}</td><td align="right">{$syo_zaiko}</td></tr>
        EOM;
        $maeno_syo_syubetu = (string)($syo_syubetu);
    }
    $_ = $__s2;
    // foreach閉じ
    // ver.1.30ここから
    // 所有物チェック
    $monokiroku_file = "./member/{$k_id}/mono.cgi";
    if (!(pl_open('MK', (string)($monokiroku_file)))) { error('自分の購入物ファイルが開けません'); }
    $my_kounyuu_list = pl_readlines('MK');
    pl_close('MK');
    $__s3 = $_ ?? null;
    foreach ($my_kounyuu_list ?? [] as $_) {
        syouhin_sprit($_);
        if (($syo_kouka == 'クレジット')) {
            if ((n($syo_taikyuu) - (pl_int((((pl_time() - n($syo_kounyuubi))) / (((60 * 60) * 24))))))) {
                $siharai_houhou .= "<option value=\"{$syo_hinmoku}\">{$syo_hinmoku}</option>";
            }
        }
    }
    $_ = $__s3;
    echo <<<EOM
    	<tr><td colspan="26"><div align="center">
    	支払い方法 <select name="siharaihouhou"><option value="現金">現金</option>{$siharai_houhou}</select>
    	<input type="submit" value=" O K "></div></td></tr>
    	</table></form>
    	<div align="center"><!--TW_BACK--></div>
    EOM;
    // ver1.30ここまで
    hooter('login_view', '家を出る');
    exit;
}

function dokuzi_url(...$_args)
{
    global $centents_botan, $dokuzi_come, $dokuzi_haikei_style, $dokuzi_height, $dokuzi_leed_style,
        $dokuzi_settei_data, $dokuzi_settei_file, $dokuzi_siasenbako, $dokuzi_table2_style, $dokuzi_tablewidth,
        $dokuzi_title, $dokuzi_title_style, $dokuzi_url, $dokuzi_width, $dokuzi_yobi10, $in, $saisenbako_data;
    $dokuzi_settei_file = "./member/{$in['ori_ie_id']}/dokuzi_ini.cgi";
    if (!(pl_open('OIB', (string)($dokuzi_settei_file)))) { error("Open Error : {$dokuzi_settei_file}"); }
    $dokuzi_settei_data = pl_readline('OIB');
    [$dokuzi_url, $dokuzi_width, $dokuzi_height, $dokuzi_haikei_style, $dokuzi_title, $dokuzi_come, $dokuzi_title_style, $dokuzi_leed_style, $dokuzi_siasenbako, $dokuzi_yobi10] = pl_pad(pl_split('/<>/u', $dokuzi_settei_data), 10);
    pl_close('OIB');
    ori_header((string)($dokuzi_haikei_style), (string)($dokuzi_siasenbako));
    echo <<<EOM
    	<table  border="0" cellspacing="0" cellpadding="5" align="center" >
    	<tr>{$centents_botan}<td align="right">{$saisenbako_data}</td></tr>
    	</table>
    EOM;
    echo <<<EOM
    	<table width="{$dokuzi_tablewidth}" border="0" cellspacing="0" cellpadding="8" align="center" style="{$dokuzi_table2_style}">
    	<tr><td>
    	<div style = "{$dokuzi_title_style}">{$dokuzi_title}</div>
    	<div style = "{$dokuzi_leed_style}">{$dokuzi_come}</div>
    	</td></tr>
    	<tr><td align="center">
    	<IFRAME src="{$dokuzi_url}"  width="{$dokuzi_width}" height="{$dokuzi_height}" scrolling="auto" marginheight="0" FRAMEBORDER="0"></IFRAME>
    	</td></tr>
    	</table></body></html>
    EOM;
    hooter('login_view', '家を出る');
    exit;
}

function saisensuru(...$_args)
{
    global $aite_bank, $aite_k_temp, $aite_log_file, $aite_name, $in, $k_temp, $message_win, $money, $my_log_file,
        $name;
    // ver.1.3
    if ((n($money) < n($in['saisengaku']))) {
        error('お金が足りません。');
    }
    $money = n($money) - n($in['saisengaku']);
    // ログ更新
    lock(...$_args);
    temp_routin(...$_args);
    if (!(pl_open('OUT', ">{$my_log_file}"))) { error("{$my_log_file}に書き込めません"); }
    pl_print('OUT', $k_temp);
    pl_close('OUT');
    openAitelog($in['ori_ie_id']);
    $aite_bank = n($aite_bank) + n($in['saisengaku']);
    aite_temp_routin(...$_args);
    if (!(pl_open('OUT', ">{$aite_log_file}"))) { error("{$aite_log_file}が開けません"); }
    pl_print('OUT', $aite_k_temp);
    pl_close('OUT');
    // ver.1.40ここまで
    aite_kityou_syori("おさい銭←{$name}", '', $in['saisengaku'], $aite_bank, '普', $in['ori_ie_id'], 'lock_off');
    unlock(...$_args);
    t_header('', 'sonomati');
    echo <<<EOM
    	<div align="center"><br><table  border="0"  cellspacing="5" cellpadding="0" width="300" style="{$message_win}"><tr><td>
    	{$aite_name}さんに{$in['saisengaku']}円のおさい銭をしました。
    	</td></tr></table>
    	<br><br><!--TW_BACK--></div>
    	</body></html>
    EOM;
    exit;
}

function by_syu_keys(...$_args)
{
    global $a, $b, $keys;
    return pl_cmp($keys[n($a)], $keys[n($b)]);
}

function my_syouhin(...$_args)
{
    global $_, $alldata, $bh_tukihi, $calory_hyouzi, $data, $hannbaityuu, $in, $key, $keys, $maeno_syo_syubetu,
        $myitem_hairetu, $new_myitem_hairetu, $omise_body_style, $omise_come, $omise_koumokumei, $omise_leed_style,
        $omise_log_file, $omise_settei_data, $omise_settei_file, $omise_siasenbako, $omise_syouhin_table,
        $omise_syubetu, $omise_table1_style, $omise_table2_style, $omise_title, $omise_title_style, $omise_yobi10,
        $omise_yobi5, $omise_yobi6, $omise_yobi7, $omise_yobi8, $omise_yobi9, $syo_bijutu, $syo_cal, $syo_comment,
        $syo_eigo, $syo_etti, $syo_hinmoku, $syo_kankaku, $syo_kenkou, $syo_kokugo, $syo_kounyuubi, $syo_kyakuryoku,
        $syo_looks, $syo_love, $syo_nedan, $syo_ongaku, $syo_power, $syo_rika, $syo_sintai_syouhi, $syo_speed,
        $syo_suugaku, $syo_syakai, $syo_syubetu, $syo_taikyuu, $syo_taikyuu_tani, $syo_tairyoku, $syo_temp,
        $syo_unique, $syo_wanryoku, $syo_zaiko, $syo_zunou_syouhi, $taikyuu_hyouzi_seikei, $this_script, $tokubai;
    $omise_log_file = "./member/{$in['iesettei_id']}/omise_log.cgi";
    if (!(pl_open('SP', (string)($omise_log_file)))) { error("Open Error : {$omise_log_file}"); }
    $myitem_hairetu = pl_readlines('SP');
    pl_close('SP');
    // 価格変更の場合
    if (($in['command'] == '価格設定')) {
        if (((n($in['syo_nedan']) * 3) < n($in['hanbaikakaku']))) {
            error('販売価格は仕入れ価格の３倍以内にしてください。');
        }
        // if ($in{'syo_nedan'} / 10 > $in{'hanbaikakaku'}){&error("販売価格は仕入れ価格の10分の1以上にしてください。");}
        if (pl_match('/[^0-9]/u', $in['hanbaikakaku'])) {
            error('販売価格が不適切です。');
        }
        // ver.1.3
        $__s1 = $_ ?? null;
        foreach ($myitem_hairetu ?? [] as $_) {
            syouhin_sprit($_);
            if (((string)$in['syo_hinmoku'] === (string)(string)($syo_hinmoku))) {
                $tokubai = $in['hanbaikakaku'];
            }
            syouhin_temp(...$_args);
            $new_myitem_hairetu[] = $syo_temp;
        }
        $_ = $__s1;
        lock(...$_args);
        if (!(pl_open('OUT', ">{$omise_log_file}"))) { error("Write Error : {$omise_log_file}"); }
        pl_print('OUT', pl_join('', $new_myitem_hairetu));
        pl_close('OUT');
        unlock(...$_args);
        message("{$in['syo_hinmoku']} の価格を{$in['hanbaikakaku']}円に設定しました。", 'my_syouhin', 'original_house.php');
    }
    // 種別でソート
    $__s2 = $_ ?? null;
    foreach ($myitem_hairetu ?? [] as $_) {
        $data = $_;
        $key = pl_idx(pl_split('/<>/u', $data), 0);
        $alldata[] = $data;
        $keys[] = $key;
    }
    $_ = $__s2;
    $alldata = pl_slice($alldata, pl_sort('by_syu_keys', pl_range(0, (pl_count($alldata) - 1))));
    // お店設定ファイルより販売掛け率を取得
    $omise_settei_file = "./member/{$in['iesettei_id']}/omise_ini.cgi";
    if (!(pl_open('OIB', (string)($omise_settei_file)))) { error("Open Error : {$omise_settei_file}"); }
    $omise_settei_data = pl_readline('OIB');
    [$omise_title, $omise_come, $omise_body_style, $omise_syubetu, $omise_table1_style, $omise_table2_style, $omise_koumokumei, $omise_syouhin_table, $omise_title_style, $omise_leed_style, $omise_siasenbako, $omise_yobi5, $omise_yobi6, $omise_yobi7, $omise_yobi8, $omise_yobi9, $omise_yobi10] = pl_pad(pl_split('/<>/u', $omise_settei_data), 17);
    pl_close('OIB');
    t_header('omise_list_style');
    echo <<<EOM
    	<table width="100%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr>
    	<td bgcolor="#ffffff">各商品の販売価格はこちらで個別に設定することができます。ただし、仕入れ価格の３倍以上に設定することはできません。<br>
    	現在のお店の種類 = {$omise_syubetu}</td>
    	</tr></table><br>
    
    	<table width="100%" border="0" cellspacing="1" cellpadding="5" align="center" class="yosumi">
    	<tr><td colspan="28"><span style="color:#330033">凡例：(国)＝国語up値、(数)＝数学up値、(理)＝理科up値、(社)＝社会up値、(英)＝英語up値、(音)＝音楽up値、(美)＝美術up値、（ル）=ルックスup値、（体）=体力up値、（健）=健康up値、（ス）=スピードup値、（パ）=パワーup値、（腕）=腕力up値、（脚）=脚力up値、（L）=LOVEup値、（面）=面白さup値、（H）=エッチup値<br>
    	※耐久は、○回なら使用できる回数、○日なら使用できる日数です。<br>
    	※カロリーは摂取できる数値です。</span></td></tr>
    		<tr bgcolor="#996699"><td align="center" nowrap>商品</td><td align="center" nowrap>販売価格</td><td align="center" nowrap>仕入れ価格</td><td align="center" nowrap>在庫</td><td>国</td><td>数</td><td>理</td><td>社</td><td>英</td><td>音</td><td>美</td><td>ル</td><td>体</td><td>健</td><td>ス</td><td>パ</td><td>腕</td><td>脚</td><td>L</td><td>面</td><td>Ｈ</td><td align="center" nowrap>カロリー</td><td align="center">使用<br>間隔</td><td align="center">身体<br>消費</td><td align="center">頭脳<br>消費</td><td align="center" nowrap>耐久</td><td align="center" nowrap>　備　考　</td><td align="center" nowrap>購入日</td></tr>
    EOM;
    $__s3 = $_ ?? null;
    foreach ($alldata ?? [] as $_) {
        syouhin_sprit($_);
        if (($omise_syubetu != 'スーパー')) {
            if (((string)$omise_syubetu !== (string)(string)($syo_syubetu))) {
                continue;
            }
        }
        if ((n($syo_cal) > 0)) {
            $calory_hyouzi = "{$syo_cal} kcal";
        } else {
            $calory_hyouzi = '<div align="center">ー</div>';
        }
        if (((string)$maeno_syo_syubetu !== (string)(string)($syo_syubetu))) {
            echo "<tr bgcolor=\"#cc9999\"><td colspan=\"28\">▼{$syo_syubetu}</td></tr>";
        }
        $taikyuu_hyouzi_seikei = (string)($syo_taikyuu) . (string)($syo_taikyuu_tani);
        byou_hiduke($syo_kounyuubi);
        if (($omise_syubetu == 'スーパー')) {
            $syo_nedan = n($syo_nedan) * 1.5;
        }
        // 独自の価格設定をしている場合その価格、そうでなければ設定の掛け率
        if ($tokubai) {
            $hannbaityuu = (string)($tokubai);
        } else {
            $hannbaityuu = (n($syo_nedan) * n($omise_yobi5));
        }
        echo <<<EOM
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="my_syouhin">
        	<input type="hidden" name="command" value="価格設定">
        	<input type="hidden" name="iesettei_id" value="{$in['iesettei_id']}">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="k_id" value="{$in['k_id']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<input type="hidden" name="syo_hinmoku" value="{$syo_hinmoku}">
        	<input type="hidden" name="syo_nedan" value="{$syo_nedan}">
        		<tr bgcolor="#ffcccc" align="center"><td nowrap align="left">{$syo_hinmoku}</td><td nowrap><input type="text" name="hanbaikakaku" size="8" value="{$hannbaityuu}"><input type="submit" value="OK"></form><td nowrap align="right">{$syo_nedan}</td></td><td nowrap>{$syo_zaiko}</td><td>{$syo_kokugo}</td><td>{$syo_suugaku}</td><td>{$syo_rika}</td><td>{$syo_syakai}</td><td>{$syo_eigo}</td><td>{$syo_ongaku}</td><td>{$syo_bijutu}</td><td>{$syo_looks}</td><td>{$syo_tairyoku}</td><td>{$syo_kenkou}</td><td>{$syo_speed}</td><td>{$syo_power}</td><td>{$syo_wanryoku}</td><td>{$syo_kyakuryoku}</td><td>{$syo_love}</td><td>{$syo_unique}</td><td>{$syo_etti}</td><td align="right">{$calory_hyouzi}</td><td nowrap>{$syo_kankaku}分</td><td>{$syo_sintai_syouhi}</td><td>{$syo_zunou_syouhi}</td><td nowrap>{$taikyuu_hyouzi_seikei}</td><td align="left">{$syo_comment}</td><td nowrap>{$bh_tukihi}</td></tr>
        EOM;
        $maeno_syo_syubetu = (string)($syo_syubetu);
    }
    $_ = $__s3;
    // foreach閉じ
    if (!(!empty($alldata))) {
        echo '<tr><td colspan="26">現在所有しているアイテムはありません。</td></tr>';
    }
    echo <<<EOM
    	</table>
    EOM;
    hooter('my_house_settei', '戻る', 'original_house.php');
    exit;
}

function bbs_delete(...$_args)
{
    global $_, $admin_name, $all_data, $b_com, $b_date, $b_mail, $b_name, $b_num, $b_res, $bbs1_log_file, $bbs_temp,
        $count_gyou, $in, $kizi_atta_flag, $message_win, $new_all_data, $sakujo_b_num, $this_script;
    if (($in['b_count'] == '')) {
        error('記事no.が指定されていません');
    }
    lock(...$_args);
    // ログファイル更新
    // ログを読み込み
    if (($in['ori_ie_id'] == 'admin')) {
        $bbs1_log_file = './member/admin/bbs' . $in['bbs_num'] . '_log.cgi';
    } else {
        $bbs1_log_file = "./member/{$in['ori_ie_id']}/bbs1_log.cgi";
    }
    if (!(pl_open('IN', (string)($bbs1_log_file)))) { error("Open Error : {$bbs1_log_file}"); }
    $count_gyou = pl_readline('IN');
    $all_data = pl_readlines('IN');
    pl_close('IN');
    $kizi_atta_flag = 0;
    $sakujo_b_num = '';
    $new_all_data = [];
    $__s1 = $_ ?? null;
    foreach ($all_data ?? [] as $_) {
        [$b_num, $b_name, $b_date, $b_res, $b_mail, $b_com] = pl_pad(pl_split('/<>/u', $_), 6);
        if (((string)$in['b_count'] === (string)(string)($b_mail))) {
            $kizi_atta_flag = 1;
            if ($b_num) {
                $sakujo_b_num = (string)($b_num);
            } else {
                $sakujo_b_num = 'res';
            }
            $b_name = preg_replace('/<span style="font-size:9px">（.*/u', '', (string)$b_name, 1);
            if (((string)$in['name'] !== (string)$admin_name)) {
                error('管理者以外は記事削除できません。');
            } else {
                continue;
            }
        }
        if ($b_res) {
            if (((string)$b_res === (string)(string)($sakujo_b_num))) {
                error('子記事のついた親記事は削除できません。');
            }
        }
        $bbs_temp = "{$b_num}<>{$b_name}<>{$b_date}<>{$b_res}<>{$b_mail}<>{$b_com}<>\n";
        $new_all_data[] = $bbs_temp;
    }
    $_ = $__s1;
    if ((n($kizi_atta_flag) == 0)) {
        error('該当する記事no.が見つかりません。');
    }
    pl_unshift($new_all_data, $count_gyou);
    if (!(pl_open('OUT', ">{$bbs1_log_file}"))) { error("Write Error : {$bbs1_log_file}"); }
    pl_print('OUT', pl_join('', $new_all_data));
    pl_close('OUT');
    unlock(...$_args);
    t_header('', 'sonomati');
    echo <<<EOM
    	<div align="center"><br><table  border="0"  cellspacing="5" cellpadding="0" width="300" style="{$message_win}"><tr><td>
    <span class="job_messe">記事を削除しました。</span>
    </td></tr></table>
    <br>
    	<form method="POST" action="{$this_script}">
    EOM;
    if (($in['ori_ie_id'] == 'admin')) {
        echo '<input type="hidden" name="mode" value="normal_bbs">';
    } else {
        echo '<input type="hidden" name="mode" value="houmon">';
    }
    echo <<<EOM
    	<input type="hidden" name="con_sele" value="0">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="ori_ie_id" value="{$in['ori_ie_id']}">
    	<input type="hidden" name="bbs_num" value="{$in['bbs_num']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value="戻る">
    	</form></div>
    EOM;
    exit;
}

function gentei_delete(...$_args)
{
    global $_, $admin_name, $all_data, $b_com, $b_date, $b_mail, $b_name, $b_num, $b_title, $bbs_temp,
        $gentei_log_file, $house_type, $in, $k_id, $kizi_atta_flag, $message_win, $name, $new_all_data, $this_script;
    if ((((n($in['ori_ie_id']) != n($k_id)) && (n($in['ori_ie_id']) != n($house_type))) && ((string)$in['name'] !== (string)$admin_name))) {
        error('家主（配偶者）、ゲーム管理者以外は記事削除できません。');
    }
    if (($in['b_num'] == '')) {
        error('記事no.が指定されていません');
    }
    lock(...$_args);
    // ログファイル更新
    // ログを読み込み
    $gentei_log_file = "./member/{$in['ori_ie_id']}/gentei_log.cgi";
    if (!(pl_open('IN', (string)($gentei_log_file)))) { error("Open Error : {$gentei_log_file}"); }
    $all_data = pl_readlines('IN');
    pl_close('IN');
    $kizi_atta_flag = 0;
    $new_all_data = [];
    $__s1 = $_ ?? null;
    foreach ($all_data ?? [] as $_) {
        [$b_num, $b_name, $b_date, $b_title, $b_mail, $b_com] = pl_pad(pl_split('/<>/u', $_), 6);
        if (((string)$in['b_num'] === (string)(string)($b_num))) {
            $kizi_atta_flag = 1;
            if ((((string)$name !== (string)$b_name) && ((string)$in['name'] !== (string)$admin_name))) {
                error('書いた本人以外は記事削除できません。');
            } else {
                continue;
            }
        }
        $bbs_temp = "{$b_num}<>{$b_name}<>{$b_date}<>{$b_title}<>{$b_mail}<>{$b_com}<>\n";
        $new_all_data[] = $bbs_temp;
    }
    $_ = $__s1;
    if ((n($kizi_atta_flag) == 0)) {
        error('該当する記事no.が見つかりません。');
    }
    if (!(pl_open('OUT', ">{$gentei_log_file}"))) { error("Write Error : {$gentei_log_file}"); }
    pl_print('OUT', pl_join('', $new_all_data));
    pl_close('OUT');
    unlock(...$_args);
    t_header('', 'sonomati');
    echo <<<EOM
    	<div align="center"><br><table  border="0"  cellspacing="5" cellpadding="0" width="300" style="{$message_win}"><tr><td>
    <span class="job_messe">記事を削除しました。</span>
    </td></tr></table>
    <br>
    	<form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="houmon">
    	<input type="hidden" name="con_sele" value="3">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="ori_ie_id" value="{$in['ori_ie_id']}">
    	<input type="hidden" name="bbs_num" value="{$in['bbs_num']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value="戻る">
    	</form></div>
    EOM;
    exit;
}

