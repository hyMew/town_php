<?php
// このファイルは game.cgi (Perl) から自動変換されたものです。
// 町CGI  Copyright (c) 2003-2004 brassiere  http://brassiere.jp/

require_once __DIR__ . '/lib/bootstrap.php';
$this_script = 'game.php';
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
if (($in['mode'] == 'battle')) {
    battle();
} elseif (($in['mode'] == 'doukyo')) {
    doukyo();
} elseif (($in['mode'] == 'c_league')) {
    c_league();
} elseif (($in['mode'] == 'new')) {
    t_new();
} elseif (($in['mode'] == 'data_hozon')) {
    data_hozon();
} else {
    error('「戻る」ボタンで街に戻ってください');
}
exit;
// ############以下サブルーチン
// ##攻撃内容ごとのダメージ処理サブルーチン
// 威力判定サブルーチン
// ###同居人
// ###Ｃリーグ
// ####Cリーグへの登録処理
// ##攻撃内容ごとのダメージ処理サブルーチン
// 威力判定サブルーチン
// ##大会初期化処理
// ######新規登録処理
// #####データ保存

function battle(...$_args)
{
    global $_, $aite_bijutu, $aite_eigo, $aite_energy_max, $aite_erabi, $aite_erabi_list, $aite_etti,
        $aite_icon_hyouzi_a, $aite_id, $aite_kenkou, $aite_kokugo, $aite_kounyuu, $aite_kyakuryoku, $aite_looks,
        $aite_love, $aite_name, $aite_nou_energy_max, $aite_ongaku, $aite_power, $aite_rika, $aite_speed,
        $aite_suugaku, $aite_syakai, $aite_tairyoku, $aite_unique, $aite_wanryoku, $bijutu, $eigo, $energy, $etti,
        $get_money, $icon_hyouzi_a, $k_id, $k_temp, $kenkou, $kokugo, $kounyuu, $kyakuryoku, $logfile, $looks, $love,
        $money, $my_log_file, $name, $nou_energy, $ongaku, $power, $randed, $rika, $sentou_kaisuu, $speed,
        $st_win_wak, $suugaku, $syakai, $tairyoku, $turn, $unique, $wanryoku, $win_flag;
    if (!(pl_open('IN', (string)($logfile)))) { error("Open Error : {$logfile}"); }
    $aite_erabi_list = pl_readlines('IN');
    pl_close('IN');
    // srand(time^$$);
    $randed = pl_int(pl_rand((pl_count($aite_erabi_list) - 1)));
    $aite_erabi = pl_last(pl_splice($aite_erabi_list, n($randed), 1));
    $aite_id = pl_idx(pl_split('/<>/u', $aite_erabi), 0);
    if (((string)$aite_id === (string)(string)($k_id))) {
        message('闘う相手が見つからなかった。。', 'login_view');
    }
    openAitelog($aite_id);
    $aite_energy_max = pl_int(((((((((n($aite_looks) / 12)) + ((n($aite_tairyoku) / 4))) + ((n($aite_kenkou) / 4))) + ((n($aite_speed) / 8))) + ((n($aite_power) / 8))) + ((n($aite_wanryoku) / 8))) + ((n($aite_kyakuryoku) / 8))));
    $aite_nou_energy_max = pl_int(((((((((n($aite_kokugo) / 6)) + ((n($aite_suugaku) / 6))) + ((n($aite_rika) / 6))) + ((n($aite_syakai) / 6))) + ((n($aite_eigo) / 6))) + ((n($aite_ongaku) / 6))) + ((n($aite_bijutu) / 6))));
    // アイコンがあれば代入
    if ($kounyuu) {
        $icon_hyouzi_a = "<img src=\"{$kounyuu}\" width=\"32\" height=\"32\" align=\"left\">";
    } else {
        $icon_hyouzi_a = '';
    }
    if ($aite_kounyuu) {
        $aite_icon_hyouzi_a = "<img src=\"{$aite_kounyuu}\" width=\"32\" height=\"32\" align=\"left\">";
    } else {
        $aite_icon_hyouzi_a = '';
    }
    t_header(...$_args);
    echo <<<EOM
    	<br><br><table width="600" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr align="center"><td>
    <table border="0"  cellspacing="0" cellpadding="1" style=" border: {$st_win_wak}; border-style: solid; border-width: 1px;"  width="150">
    <tr><td align="center" colspan="2" bgcolor="#ccff66" >
    {$icon_hyouzi_a}{$name}さん
    </td></tr>
    <tr><td align="right"><span class="honbun3">頭脳パワー</span>：</td><td>{$nou_energy}</td></tr>
    <tr><td align="right"><span class="honbun3">身体パワー</span>：</td><td>{$energy}</td></tr>
    <tr>
    <tr style=" border: {$st_win_wak}; border-style: solid; border-top-width: 1px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px">
    <td align="center"><span class="tyuu" colspan="2">頭　脳</span></td></tr>
    <tr><td align="right"><span class="honbun3">国語</span>：</td><td>{$kokugo}</td></tr>
    <tr><td align="right"><span class="honbun3">数学</span>：</td><td>{$suugaku}</td></tr>
    <tr><td align="right"><span class="honbun3">理科</span>：</td><td>{$rika}</td></tr>
    <tr><td align="right"><span class="honbun3">社会</span>：</td><td>{$syakai}</td></tr>
    <tr><td align="right"><span class="honbun3">英語</span>：</td><td>{$eigo}</td></tr>
    <tr><td align="right"><span class="honbun3">音楽</span>：</td><td>{$ongaku}</td></tr>
    <tr><td align="right"><span class="honbun3">美術</span>：</td><td>{$bijutu}</td></tr>
    <tr style=" border: {$st_win_wak}; border-style: solid; border-top-width: 1px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px">
    <td  colspan="2" align="center"><span class="tyuu">身　体</span></td></tr>
    <tr><td  align="right" nowrap><span class="honbun3">ルックス</span>：</td><td>{$looks}</td></tr>
    <tr><td align="right"><span class="honbun3">体力</span>：</td><td>{$tairyoku}</td></tr>
    <tr><td align="right"><span class="honbun3">健康</span>：</td><td>{$kenkou}</td></tr>
    <tr><td align="right" nowrap><span class="honbun3">スピード</span>：</td><td>{$speed}</td></tr>
    <tr><td align="right"><span class="honbun3">パワー</span>：</td><td>{$power}</td></tr>
    <tr><td align="right"><span class="honbun3">腕力</span>：</td><td>{$wanryoku}</td></tr>
    <tr><td align="right"><span class="honbun3">脚力</span>：</td><td>{$kyakuryoku}</td></tr>
    <tr style=" border: {$st_win_wak}; border-style: solid; border-top-width: 1px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px">
    <td  colspan="2" align="center"><span class="tyuu">その他</span></td>
    <tr><td align="right"><span class="honbun3">LOVE</span>：</td><td>{$love}</td></tr>
    <tr><td align="right"><span class="honbun3">面白さ</span>：</td><td>{$unique}</td></tr>
    <tr><td align="right"><span class="honbun3">エッチ</span>：</td><td>{$etti}</td></tr>
    </table>
    	</td><td>
    	<div class="tyuu">{$aite_name}さんと街で出会った！</div><br><br>
    	<div class="dai">Fight start !!</div>
    	</td><td>
    <table border="0"  cellspacing="0" cellpadding="1" style=" border: {$st_win_wak}; border-style: solid; border-width: 1px;"  width="150">
    <tr><td align="center" colspan="2" bgcolor="#ffcc99">
    {$aite_icon_hyouzi_a}{$aite_name}さん
    </td></tr>
    <tr><td align="right"><span class="honbun3">頭脳パワー</span>：</td><td>{$aite_nou_energy_max}</td></tr>
    <tr><td align="right"><span class="honbun3">身体パワー</span>：</td><td>{$aite_energy_max}</td></tr>
    <tr style=" border: {$st_win_wak}; border-style: solid; border-top-width: 1px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px">
    <td colspan="2" align="center"><span class="tyuu">頭　脳</span></td></tr>
    <tr><td align="right"><span class="honbun3">国語</span>：</td><td>{$aite_kokugo}</td></tr>
    <tr><td align="right"><span class="honbun3">数学</span>：</td><td>{$aite_suugaku}</td></tr>
    <tr><td align="right"><span class="honbun3">理科</span>：</td><td>{$aite_rika}</td></tr>
    <tr><td align="right"><span class="honbun3">社会</span>：</td><td>{$aite_syakai}</td></tr>
    <tr><td align="right"><span class="honbun3">英語</span>：</td><td>{$aite_eigo}</td></tr>
    <tr><td align="right"><span class="honbun3">音楽</span>：</td><td>{$aite_ongaku}</td></tr>
    <tr><td align="right"><span class="honbun3">美術</span>：</td><td>{$aite_bijutu}</td></tr>
    <tr style=" border: {$st_win_wak}; border-style: solid; border-top-width: 1px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px">
    <td  colspan="2" align="center"><span class="tyuu">身　体</span></td></tr>
    <tr><td  align="right" nowrap><span class="honbun3">ルックス</span>：</td><td>{$aite_looks}</td></tr>
    <tr><td align="right"><span class="honbun3">体力</span>：</td><td>{$aite_tairyoku}</td></tr>
    <tr><td align="right"><span class="honbun3">健康</span>：</td><td>{$aite_kenkou}</td></tr>
    <tr><td align="right" nowrap><span class="honbun3">スピード</span>：</td><td>{$aite_speed}</td></tr>
    <tr><td align="right"><span class="honbun3">パワー</span>：</td><td>{$aite_power}</td></tr>
    <tr><td align="right"><span class="honbun3">腕力</span>：</td><td>{$aite_wanryoku}</td></tr>
    <tr><td align="right"><span class="honbun3">脚力</span>：</td><td>{$aite_kyakuryoku}</td></tr>
    <tr style=" border: {$st_win_wak}; border-style: solid; border-top-width: 1px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px">
    <td  colspan="2" align="center"><span class="tyuu">その他</span></td>
    <tr><td align="right"><span class="honbun3">LOVE</span>：</td><td>{$aite_love}</td></tr>
    <tr><td align="right"><span class="honbun3">面白さ</span>：</td><td>{$aite_unique}</td></tr>
    <tr><td align="right"><span class="honbun3">エッチ</span>：</td><td>{$aite_etti}</td></tr>
    </table>
    	</td></tr></table>
    EOM;
    if ((n($speed) > n($aite_speed))) {
        $turn = 1;
    }
    $sentou_kaisuu = 0;
    $__s1 = $_ ?? null;
    foreach (pl_range(1, 50) as $_) {
        echo '<br><br><table width="600" border="0" cellspacing="0" cellpadding="5" align="center" class="yosumi"><tr><td colspan="2">';
        if ((n($turn) == 1)) {
            kougeki(1, $name);
            // 自分の攻撃
        } else {
            kougeki(0, $aite_name);
        }
        // 相手の攻撃
        echo <<<EOM
        			</td></tr>
        			<tr><td align="left">
        			<div class="tyuu">頭脳パワー：{$nou_energy}</div>
        			<div class="tyuu">身体パワー：{$energy}</div>
        			</td>
        			<td align="right">
        			<div class="tyuu">頭脳パワー：{$aite_nou_energy_max}</div>
        			<div class="tyuu">身体パワー：{$aite_energy_max}</div>
        			</td></tr></table>
        EOM;
        $sentou_kaisuu++;
        if ((n($aite_energy_max) <= 0)) {
            $win_flag = 1;
            break;
        }
        if ((n($aite_nou_energy_max) <= 0)) {
            $win_flag = 2;
            break;
        }
        if ((n($energy) <= 0)) {
            $win_flag = 3;
            break;
        }
        if ((n($nou_energy) <= 0)) {
            $win_flag = 4;
            break;
        }
        if ((n($turn) == 1)) {
            $turn = 0;
        } else {
            $turn = 1;
        }
    }
    $_ = $__s1;
    echo '<br><br>';
    if ((n($energy) < 0)) {
        $energy = 0;
    }
    if ((n($nou_energy) < 0)) {
        $nou_energy = 0;
    }
    $get_money = (n($sentou_kaisuu) * 300);
    if ((n($win_flag) == 1)) {
        $get_money = n($get_money) * 5;
        echo "<div align=\"center\" style=\"color:#339933;font-size:14px;\">勝ちました！<br>倒れている{$aite_name}さんの財布から<br>{$get_money}円を奪いました。</div>";
        $money = n($money) + n($get_money);
    } elseif ((n($win_flag) == 2)) {
        $get_money = n($get_money) * 5;
        echo "<div align=\"center\" style=\"color:#339933;font-size:14px;\">勝ちました！<br>考える力の無い{$aite_name}さんの財布から<br>{$get_money}円を奪いました。</div>";
        $money = n($money) + n($get_money);
    } elseif ((n($win_flag) == 3)) {
        echo "<div align=\"center\" style=\"color:#ff3300;font-size:14px;\">負けてしまいました。。<br>ボロボロになった{$name}さんの財布から<br>{$get_money}円を奪われました。</div>";
        $money = n($money) - n($get_money);
    } elseif ((n($win_flag) == 4)) {
        echo "<div align=\"center\" style=\"color:#ff3300;font-size:14px;\">負けてしまいました。。<br>意識がもうろうとした{$name}さんの財布から<br>{$get_money}円を奪われました。</div>";
        $money = n($money) - n($get_money);
    } else {
        echo '決着がつきませんでした。。';
    }
    // データ更新
    temp_routin(...$_args);
    log_kousin($my_log_file, $k_temp);
    hooter('login_view', '戻る');
    exit;
}

function kougeki(...$_args)
{
    global $aite_energy_max, $aite_name, $aite_nou_energy_max, $battle_rand, $damage, $energy, $name, $nou_energy,
        $return_naiyou;
    // 攻撃内容をランダムで選択
    $battle_rand = (pl_int(pl_rand(16)) + 1);
    echo '<table width="600" border="0" cellspacing="0" cellpadding="0" align="center"><tr align="center"><td>';
    // #自分の攻撃の場合
    if ((n($_args[0]) == 1)) {
        echo "<div style=\"color:#339933;font-size:12px;\" align=\"left\">{$_args[1]}の攻撃！<br>";
        // 攻撃内容
        kougekinaiyou(1, $aite_name);
        // 結果表示
        if (($damage == 'no_d')) {
            echo "<div style=\"color:#ff3300;font-size:12px;\" align=\"right\">{$return_naiyou}</div>\n";
        } else {
            echo "<div style=\"color:#339933;font-size:12px;\" align=\"right\">{$return_naiyou}</div>\n";
            if (((n($battle_rand) <= 8) || (n($battle_rand) == 14))) {
                $aite_nou_energy_max = n($aite_nou_energy_max) - n($damage);
            } else {
                $aite_energy_max = n($aite_energy_max) - n($damage);
            }
        }
        // #相手の攻撃の場合
    } else {
        echo "<div style=\"color:#ff3300;font-size:12px;\" align=\"right\">{$_args[1]}の攻撃！<br>";
        kougekinaiyou(0, $name);
        // 結果表示
        // 自分にきかない場合
        if (($damage == 'no_d')) {
            echo "<div style=\"color:#339933;font-size:12px;\" align=\"left\">{$return_naiyou}</div>\n";
        } else {
            // ダメージを受けた場合
            echo "<div style=\"color:#ff3300;font-size:12px;\" align=\"left\">{$return_naiyou}</div>\n";
            // 頭脳ダメージ
            if (((n($battle_rand) <= 8) || (n($battle_rand) == 14))) {
                $nou_energy = n($nou_energy) - n($damage);
            } else {
                // 肉体ダメージ
                $energy = n($energy) - n($damage);
            }
        }
    }
    echo '</td></tr></table>';
}

function kougekinaiyou(...$_args)
{
    global $align_settei, $battle_rand, $damage, $return_naiyou;
    // @_[0]＝1なら自分の攻撃、0なら相手の攻撃
    if ((n($_args[0]) == 1)) {
        $align_settei = 'align=left';
    } else {
        $align_settei = 'align=right';
    }
    // 国語の攻撃
    if ((n($battle_rand) == 1)) {
        echo "この漢字が読めるか？と{$_args[1]}に歩み寄った！</div><br>\n";
        // iryoku_hantei (攻撃者,攻撃の内容)
        iryoku_hantei($_args[0], 1);
        if (($damage == 'no_d')) {
            $return_naiyou = "{$_args[1]}は漢字が得意だったのであっさり答えた。。";
        } else {
            $return_naiyou = "「よ、読めない。。」{$_args[1]}は <span style=\"font-size:18px\">{$damage}</span> の精神的ダメージを受けた！";
        }
    }
    if ((n($battle_rand) == 2)) {
        echo "難しい数学の問題で{$_args[1]}を困らせようとした！</div><br>\n";
        // iryoku_hantei (攻撃者,攻撃の内容)
        iryoku_hantei($_args[0], 2);
        if (($damage == 'no_d')) {
            $return_naiyou = "{$_args[1]}は数学が得意だったので効果が無かった。。";
        } else {
            $return_naiyou = "ちんぷんかんぷんだった{$_args[1]}は <span style=\"font-size:18px\">{$damage}</span> の精神的ダメージを受けた！";
        }
    }
    if ((n($battle_rand) == 3)) {
        echo "理科の公式を唱えて{$_args[1]}の動揺を誘った！</div><br>\n";
        // iryoku_hantei (攻撃者,攻撃の内容)
        iryoku_hantei($_args[0], 3);
        if (($damage == 'no_d')) {
            $return_naiyou = "{$_args[1]}は理科が得意だったので効果が無かった。。";
        } else {
            $return_naiyou = "頭が混乱した{$_args[1]}は <span style=\"font-size:18px\">{$damage}</span> の精神的ダメージを受けた！";
        }
    }
    if ((n($battle_rand) == 4)) {
        echo "有名な歴史の事件の年号を{$_args[1]}に質問攻めした！</div><br>\n";
        // iryoku_hantei (攻撃者,攻撃の内容)
        iryoku_hantei($_args[0], 4);
        if (($damage == 'no_d')) {
            $return_naiyou = "{$_args[1]}は歴史が大得意だった。。";
        } else {
            $return_naiyou = "焦った{$_args[1]}は <span style=\"font-size:18px\">{$damage}</span> の精神的ダメージを受けた！";
        }
    }
    if ((n($battle_rand) == 5)) {
        echo "突然英語をしゃべり出した！</div><br>\n";
        // iryoku_hantei (攻撃者,攻撃の内容)
        iryoku_hantei($_args[0], 5);
        if (($damage == 'no_d')) {
            $return_naiyou = "{$_args[1]}も負けじと英語で答えた。。";
        } else {
            $return_naiyou = "「もっと英語を勉強せねば。。」と{$_args[1]}は <span style=\"font-size:18px\">{$damage}</span> の精神的ダメージを受けた！";
        }
    }
    if ((n($battle_rand) == 6)) {
        echo "近くにあったピアノの鍵盤を叩き、この音階は何？と質問した！</div><br>\n";
        // iryoku_hantei (攻撃者,攻撃の内容)
        iryoku_hantei($_args[0], 6);
        if (($damage == 'no_d')) {
            $return_naiyou = "{$_args[1]}は簡単に質問に答えた。。";
        } else {
            $return_naiyou = "「わ、わからん。。」{$_args[1]}は <span style=\"font-size:18px\">{$damage}</span> の精神的ダメージを受けた！";
        }
    }
    if ((n($battle_rand) == 7)) {
        echo "すらすらと景色を描いて{$_args[1]}に見せた！</div><br>\n";
        // iryoku_hantei (攻撃者,攻撃の内容)
        iryoku_hantei($_args[0], 7);
        if (($damage == 'no_d')) {
            $return_naiyou = "{$_args[1]}は「この下手くそが！」と吐き捨てた。。";
        } else {
            $return_naiyou = "「う、うまい。。」{$_args[1]}は<span style=\"font-size:18px\">{$damage}</span> の精神的ダメージを受けた！";
        }
    }
    if ((n($battle_rand) == 8)) {
        echo "ルックスで勝負しろ！と{$_args[1]}に迫った！</div><br>\n";
        // iryoku_hantei (攻撃者,攻撃の内容)
        iryoku_hantei($_args[0], 8);
        if (($damage == 'no_d')) {
            $return_naiyou = "{$_args[1]}は「勝ったね」とつぶやいた。";
        } else {
            $return_naiyou = "「う。。」{$_args[1]}は <span style=\"font-size:18px\">{$damage}</span> の精神的ダメージを受けた！";
        }
    }
    if ((n($battle_rand) == 9)) {
        echo "体力勝負に持ち込んだ</div><br>\n";
        // iryoku_hantei (攻撃者,攻撃の内容)
        iryoku_hantei($_args[0], 9);
        if (($damage == 'no_d')) {
            $return_naiyou = "{$_args[1]}は体力に自信があった。。";
        } else {
            $return_naiyou = "{$_args[1]}は<span style=\"font-size:18px\">{$damage}</span> の肉体的疲労を受けた！";
        }
    }
    if ((n($battle_rand) == 10)) {
        echo "素早いフットワークで{$_args[1]}を翻弄しようとした！</div><br>\n";
        // iryoku_hantei (攻撃者,攻撃の内容)
        iryoku_hantei($_args[0], 11);
        if (($damage == 'no_d')) {
            $return_naiyou = "{$_args[1]}の方が素早かった。。";
        } else {
            $return_naiyou = "{$_args[1]}は翻弄され <span style=\"font-size:18px\">{$damage}</span> の肉体的疲労を受けた！";
        }
    }
    if ((n($battle_rand) == 11)) {
        echo "パワーで{$_args[1]}を投げ飛ばそうとした！</div><br>\n";
        // iryoku_hantei (攻撃者,攻撃の内容)
        iryoku_hantei($_args[0], 12);
        if (($damage == 'no_d')) {
            $return_naiyou = "{$_args[1]}はもっとパワーがあった。。";
        } else {
            $return_naiyou = "{$_args[1]}は投げ飛ばされ <span style=\"font-size:18px\">{$damage}</span> の肉体的ダメージを受けた！";
        }
    }
    if ((n($battle_rand) == 12)) {
        echo "{$_args[1]}にパンチを浴びせた！</div><br>\n";
        // iryoku_hantei (攻撃者,攻撃の内容)
        iryoku_hantei($_args[0], 13);
        if (($damage == 'no_d')) {
            $return_naiyou = "しかし{$_args[1]}は軽くよけた。。";
        } else {
            $return_naiyou = "{$_args[1]}は パンチを浴び<span style=\"font-size:18px\">{$damage}</span> の肉体的ダメージを受けた！";
        }
    }
    if ((n($battle_rand) == 13)) {
        echo "{$_args[1]}に蹴りかかった！</div><br>\n";
        // iryoku_hantei (攻撃者,攻撃の内容)
        iryoku_hantei($_args[0], 14);
        if (($damage == 'no_d')) {
            $return_naiyou = "しかし{$_args[1]}はさっとよけた。。";
        } else {
            $return_naiyou = "{$_args[1]}は 蹴りを浴び<span style=\"font-size:18px\">{$damage}</span> の肉体的ダメージを受けた！";
        }
    }
    if ((n($battle_rand) == 14)) {
        echo "愛している恋人の自慢を始めた！</div><br>\n";
        // iryoku_hantei (攻撃者,攻撃の内容)
        iryoku_hantei($_args[0], 15);
        if (($damage == 'no_d')) {
            $return_naiyou = "{$_args[1]}はもっと恋人を愛していると言った。。";
        } else {
            $return_naiyou = "「う、うらやましい。。」{$_args[1]}は <span style=\"font-size:18px\">{$damage}</span> の精神的ダメージを受けた！";
        }
    }
    if ((n($battle_rand) == 15)) {
        echo "{$_args[1]}を笑わせ、そのスキに一発おみまいしようとした！</div><br>\n";
        // iryoku_hantei (攻撃者,攻撃の内容)
        iryoku_hantei($_args[0], 16);
        if (($damage == 'no_d')) {
            $return_naiyou = "「つまらん！」と{$_args[1]}は一蹴した。。";
        } else {
            $return_naiyou = "{$_args[1]}は笑い転げ、その隙に <span style=\"font-size:18px\">{$damage}</span> のパンチをくらった！";
        }
    }
    if ((n($battle_rand) == 16)) {
        echo "日頃のエッチで培った秘密技を披露した！</div><br>\n";
        // iryoku_hantei (攻撃者,攻撃の内容)
        iryoku_hantei($_args[0], 17);
        if (($damage == 'no_d')) {
            $return_naiyou = "{$_args[1]}の方が一枚うわてだった。。";
        } else {
            $return_naiyou = "{$_args[1]}は翻弄され <span style=\"font-size:18px\">{$damage}</span> の肉体的ダメージを受けた！";
        }
    }
}

function iryoku_hantei(...$_args)
{
    global $aite_nouryoku_suuzi_hairetu, $damage, $hantei_kakkati, $iryoku_hanteiti, $nouryoku_suuzi_hairetu;
    // 攻撃の内容ごとの能力値
    $iryoku_hanteiti = (n($_args[1]) + 5);
    if ((n($_args[0]) == 1)) {
        $hantei_kakkati = (n($nouryoku_suuzi_hairetu[n($iryoku_hanteiti)]) - n($aite_nouryoku_suuzi_hairetu[n($iryoku_hanteiti)]));
    } else {
        $hantei_kakkati = (n($aite_nouryoku_suuzi_hairetu[n($iryoku_hanteiti)]) - n($nouryoku_suuzi_hairetu[n($iryoku_hanteiti)]));
    }
    if ((n($hantei_kakkati) <= 0)) {
        $damage = 'no_d';
    } else {
        $damage = (string)($hantei_kakkati);
    }
}

function doukyo(...$_args)
{
    global $MaxH, $MaxW, $bijutu, $c_size_comment, $cgi_lib__maxdata, $ch_bijutu, $ch_eigo, $ch_energy, $ch_etti,
        $ch_gazou, $ch_k_id, $ch_k_yobi3, $ch_k_yobi4, $ch_k_yobi5, $ch_kenkou, $ch_kokugo, $ch_kyakuryoku,
        $ch_looks, $ch_love, $ch_me_bijutu, $ch_me_eigo, $ch_me_etti, $ch_me_kenkou, $ch_me_kokugo,
        $ch_me_kyakuryoku, $ch_me_looks, $ch_me_love, $ch_me_ongaku, $ch_me_power, $ch_me_rika, $ch_me_speed,
        $ch_me_suugaku, $ch_me_syakai, $ch_me_tairyoku, $ch_me_unique, $ch_me_wanryoku, $ch_name, $ch_nou_energy,
        $ch_ongaku, $ch_oyaname, $ch_point, $ch_power, $ch_rika, $ch_sintai, $ch_speed, $ch_suugaku, $ch_syakai,
        $ch_tairyoku, $ch_unique, $ch_wanryoku, $ch_yuusyoukai, $ch_zunou, $chara_gazou_size, $chara_settei_data,
        $chara_settei_file, $chara_x_size, $chara_y_size, $charaimage_gazou, $eigo, $etti, $hikukane, $img_dir, $in,
        $k_id, $k_temp, $kenkou, $kokugo, $kyakuryoku, $looks, $love, $make_ch_temp, $message_in, $money,
        $my_log_file, $name, $ongaku, $power, $rika, $speed, $st_win_wak, $suugaku, $syakai, $tairyoku, $this_script,
        $unique, $wanryoku;
    if (((n($chara_x_size) == 0) && (n($chara_y_size) == 0))) {
        $chara_gazou_size = '';
        $c_size_comment = '';
    } else {
        $chara_gazou_size = "width={$chara_x_size} height={$chara_y_size}";
        $c_size_comment = "画像サイズは横{$chara_x_size}ピクセル、縦{$chara_y_size}ピクセルです。";
    }
    $chara_settei_file = "./member/{$k_id}/chara_ini.cgi";
    if (!(pl_exists($chara_settei_file))) {
        if (!(pl_open('OIB', ">{$chara_settei_file}"))) { error("Write Error : {$chara_settei_file}"); }
        true;
        pl_close('OIB');
    }
    if (!(pl_open('CSF', (string)($chara_settei_file)))) { error("Open Error : {$chara_settei_file}"); }
    $chara_settei_data = pl_readline('CSF');
    [$ch_k_id, $ch_name, $ch_oyaname, $ch_gazou, $ch_point, $ch_yuusyoukai, $ch_kokugo, $ch_suugaku, $ch_rika, $ch_syakai, $ch_eigo, $ch_ongaku, $ch_bijutu, $ch_looks, $ch_tairyoku, $ch_kenkou, $ch_speed, $ch_power, $ch_wanryoku, $ch_kyakuryoku, $ch_love, $ch_unique, $ch_etti, $ch_energy, $ch_nou_energy, $ch_sintai, $ch_zunou, $ch_me_kokugo, $ch_me_suugaku, $ch_me_rika, $ch_me_syakai, $ch_me_eigo, $ch_me_ongaku, $ch_me_bijutu, $ch_me_looks, $ch_me_tairyoku, $ch_me_kenkou, $ch_me_speed, $ch_me_power, $ch_me_wanryoku, $ch_me_kyakuryoku, $ch_me_love, $ch_me_unique, $ch_me_etti, $ch_k_yobi3, $ch_k_yobi4, $ch_k_yobi5] = pl_pad(pl_split('/<>/u', $chara_settei_data), 47);
    pl_close('CSF');
    // キャラ設定処理の場合
    if (($in['command'] == 'make_chara')) {
        if ((pl_length($in['ch_name']) > 30)) {
            error('キャラクターの名前は30字以内です');
        }
        $cgi_lib__maxdata = 51200;
        $MaxW = 80;
        // 横幅
        $MaxH = 80;
        // 縦幅
        if (($ch_k_id == '')) {
            $ch_k_id = $k_id;
        }
        if ($in['ch_name']) {
            $ch_name = $in['ch_name'];
        }
        if (($ch_oyaname == '')) {
            $ch_oyaname = $name;
        }
        if ($in['ch_gazou']) {
            $ch_gazou = $in['ch_gazou'];
        }
        if (($in['ch_kokugo'] && (n($kokugo) > n($in['ch_kokugo'])))) {
            $ch_kokugo = n($ch_kokugo) + n($in['ch_kokugo']);
            $message_in .= "国語パラメータを{$in['ch_kokugo']}あげました。<br>";
            $hikukane = n($hikukane) + n($in['ch_kokugo']);
            $kokugo = n($kokugo) - n($in['ch_kokugo']);
        }
        if (($in['ch_suugaku'] && (n($suugaku) > n($in['ch_suugaku'])))) {
            $ch_suugaku = n($ch_suugaku) + n($in['ch_suugaku']);
            $message_in .= "数学パラメータを{$in['ch_suugaku']}あげました。<br>";
            $hikukane = n($hikukane) + n($in['ch_suugaku']);
            $suugaku = n($suugaku) - n($in['ch_suugaku']);
        }
        if (($in['ch_rika'] && (n($rika) > n($in['ch_rika'])))) {
            $ch_rika = n($ch_rika) + n($in['ch_rika']);
            $message_in .= "理科パラメータを{$in['ch_rika']}あげました。<br>";
            $hikukane = n($hikukane) + n($in['ch_rika']);
            $rika = n($rika) - n($in['ch_rika']);
        }
        if (($in['ch_syakai'] && (n($syakai) > n($in['ch_syakai'])))) {
            $ch_syakai = n($ch_syakai) + n($in['ch_syakai']);
            $message_in .= "社会パラメータを{$in['ch_rika']}あげました。<br>";
            $hikukane = n($hikukane) + n($in['ch_syakai']);
            $syakai = n($syakai) - n($in['ch_syakai']);
        }
        if (($in['ch_eigo'] && (n($eigo) > n($in['ch_eigo'])))) {
            $ch_eigo = n($ch_eigo) + n($in['ch_eigo']);
            $message_in .= "英語パラメータを{$in['ch_eigo']}あげました。<br>";
            $hikukane = n($hikukane) + n($in['ch_eigo']);
            $eigo = n($eigo) - n($in['ch_eigo']);
        }
        if (($in['ch_ongaku'] && (n($ongaku) > n($in['ch_ongaku'])))) {
            $ch_ongaku = n($ch_ongaku) + n($in['ch_ongaku']);
            $message_in .= "音楽パラメータを{$in['ch_ongaku']}あげました。<br>";
            $hikukane = n($hikukane) + n($in['ch_ongaku']);
            $ongaku = n($ongaku) - n($in['ch_ongaku']);
        }
        if (($in['ch_bijutu'] && (n($bijutu) > n($in['ch_bijutu'])))) {
            $ch_bijutu = n($ch_bijutu) + n($in['ch_bijutu']);
            $message_in .= "美術パラメータを{$in['ch_bijutu']}あげました。<br>";
            $hikukane = n($hikukane) + n($in['ch_bijutu']);
            $bijutu = n($bijutu) - n($in['ch_bijutu']);
        }
        if (($in['ch_looks'] && (n($looks) > n($in['ch_looks'])))) {
            $ch_looks = n($ch_looks) + n($in['ch_looks']);
            $message_in .= "ルックスパラメータを{$in['ch_looks']}あげました。<br>";
            $hikukane = n($hikukane) + n($in['ch_looks']);
            $looks = n($looks) - n($in['ch_looks']);
        }
        if (($in['ch_tairyoku'] && (n($tairyoku) > n($in['ch_tairyoku'])))) {
            $ch_tairyoku = n($ch_tairyoku) + n($in['ch_tairyoku']);
            $message_in .= "体力パラメータを{$in['ch_tairyoku']}あげました。<br>";
            $hikukane = n($hikukane) + n($in['ch_tairyoku']);
            $tairyoku = n($tairyoku) - n($in['ch_tairyoku']);
        }
        if (($in['ch_kenkou'] && (n($kenkou) > n($in['ch_kenkou'])))) {
            $ch_kenkou = n($ch_kenkou) + n($in['ch_kenkou']);
            $message_in .= "健康パラメータを{$in['ch_kenkou']}あげました。<br>";
            $hikukane = n($hikukane) + n($in['ch_kenkou']);
            $kenkou = n($kenkou) - n($in['ch_kenkou']);
        }
        if (($in['ch_speed'] && (n($speed) > n($in['ch_speed'])))) {
            $ch_speed = n($ch_speed) + n($in['ch_speed']);
            $message_in .= "スピードパラメータを{$in['ch_speed']}あげました。<br>";
            $hikukane = n($hikukane) + n($in['ch_speed']);
            $speed = n($speed) - n($in['ch_speed']);
        }
        if (($in['ch_power'] && (n($power) > n($in['ch_power'])))) {
            $ch_power = n($ch_power) + n($in['ch_power']);
            $message_in .= "パワーパラメータを{$in['ch_power']}あげました。<br>";
            $hikukane = n($hikukane) + n($in['ch_power']);
            $power = n($power) - n($in['ch_power']);
        }
        if (($in['ch_wanryoku'] && (n($wanryoku) > n($in['ch_wanryoku'])))) {
            $ch_wanryoku = n($ch_wanryoku) + n($in['ch_wanryoku']);
            $message_in .= "腕力パラメータを{$in['ch_wanryoku']}あげました。<br>";
            $hikukane = n($hikukane) + n($in['ch_wanryoku']);
            $wanryoku = n($wanryoku) - n($in['ch_wanryoku']);
        }
        if (($in['ch_kyakuryoku'] && (n($kyakuryoku) > n($in['ch_kyakuryoku'])))) {
            $ch_kyakuryoku = n($ch_kyakuryoku) + n($in['ch_kyakuryoku']);
            $message_in .= "脚力パラメータを{$in['ch_kyakuryoku']}あげました。<br>";
            $hikukane = n($hikukane) + n($in['ch_kyakuryoku']);
            $kyakuryoku = n($kyakuryoku) - n($in['ch_kyakuryoku']);
        }
        if (($in['ch_love'] && (n($love) > n($in['ch_love'])))) {
            $ch_love = n($ch_love) + n($in['ch_love']);
            $message_in .= "LOVEパラメータを{$in['ch_love']}あげました。<br>";
            $hikukane = n($hikukane) + n($in['ch_love']);
            $love = n($love) - n($in['ch_love']);
        }
        if (($in['ch_unique'] && (n($unique) > n($in['ch_unique'])))) {
            $ch_unique = n($ch_unique) + n($in['ch_unique']);
            $message_in .= "面白さパラメータを{$in['ch_unique']}あげました。<br>";
            $hikukane = n($hikukane) + n($in['ch_unique']);
            $unique = n($unique) - n($in['ch_unique']);
        }
        if (($in['ch_etti'] && (n($etti) > n($in['ch_etti'])))) {
            $ch_etti = n($ch_etti) + n($in['ch_etti']);
            $message_in .= "エッチパラメータを{$in['ch_etti']}あげました。<br>";
            $hikukane = n($hikukane) + n($in['ch_etti']);
            $etti = n($etti) - n($in['ch_etti']);
        }
        if (pl_match('/[^0-9]/u', $hikukane)) {
            error('数値が不適切です');
        }
        if ((n($hikukane) < 0)) {
            error('数値が不適切です');
        }
        if ((n($hikukane) != 0)) {
            $message_in .= "{$hikukane}万円のお金がかかりました。<br>";
        }
        if ((n($money) < (n($hikukane) * 10000))) {
            error('お金が足りません');
        }
        if ($in['ch_me_kokugo']) {
            $ch_me_kokugo = (string)($in['ch_me_kokugo']);
        }
        if ($in['ch_me_suugaku']) {
            $ch_me_suugaku = (string)($in['ch_me_suugaku']);
        }
        if ($in['ch_me_rika']) {
            $ch_me_rika = (string)($in['ch_me_rika']);
        }
        if ($in['ch_me_syakai']) {
            $ch_me_syakai = (string)($in['ch_me_syakai']);
        }
        if ($in['ch_me_eigo']) {
            $ch_me_eigo = (string)($in['ch_me_eigo']);
        }
        if ($in['ch_me_ongaku']) {
            $ch_me_ongaku = (string)($in['ch_me_ongaku']);
        }
        if ($in['ch_me_bijutu']) {
            $ch_me_bijutu = (string)($in['ch_me_bijutu']);
        }
        if ($in['ch_me_looks']) {
            $ch_me_looks = (string)($in['ch_me_looks']);
        }
        if ($in['ch_me_tairyoku']) {
            $ch_me_tairyoku = (string)($in['ch_me_tairyoku']);
        }
        if ($in['ch_me_kenkou']) {
            $ch_me_kenkou = (string)($in['ch_me_kenkou']);
        }
        if ($in['ch_me_speed']) {
            $ch_me_speed = (string)($in['ch_me_speed']);
        }
        if ($in['ch_me_power']) {
            $ch_me_power = (string)($in['ch_me_power']);
        }
        if ($in['ch_me_wanryoku']) {
            $ch_me_wanryoku = (string)($in['ch_me_wanryoku']);
        }
        if ($in['ch_me_kyakuryoku']) {
            $ch_me_kyakuryoku = (string)($in['ch_me_kyakuryoku']);
        }
        if ($in['ch_me_love']) {
            $ch_me_love = (string)($in['ch_me_love']);
        }
        if ($in['ch_me_unique']) {
            $ch_me_unique = (string)($in['ch_me_unique']);
        }
        if ($in['ch_me_etti']) {
            $ch_me_etti = (string)($in['ch_me_etti']);
        }
        if (($message_in == '')) {
            $message_in .= '変更しました。';
        }
        // if ($in{'upfile'}) { &UpFile; }
        // パワーのMAX値計算
        $ch_sintai = pl_int((((((((((n($ch_looks) / 10)) + ((n($ch_tairyoku) / 10))) + ((n($ch_kenkou) / 10))) + ((n($ch_speed) / 10))) + ((n($ch_power) / 10))) + ((n($ch_wanryoku) / 10))) + ((n($ch_kyakuryoku) / 10))) + ((n($ch_etti) / 10))));
        $ch_zunou = pl_int(((((((((((n($ch_kokugo) / 10)) + ((n($ch_suugaku) / 10))) + ((n($ch_rika) / 10))) + ((n($ch_syakai) / 10))) + ((n($ch_eigo) / 10))) + ((n($ch_ongaku) / 10))) + ((n($ch_bijutu) / 10))) + ((n($ch_love) / 10))) + ((n($ch_unique) / 10))));
        $make_ch_temp = "{$ch_k_id}<>{$ch_name}<>{$ch_oyaname}<>{$ch_gazou}<>{$ch_point}<>{$ch_yuusyoukai}<>{$ch_kokugo}<>{$ch_suugaku}<>{$ch_rika}<>{$ch_syakai}<>{$ch_eigo}<>{$ch_ongaku}<>{$ch_bijutu}<>{$ch_looks}<>{$ch_tairyoku}<>{$ch_kenkou}<>{$ch_speed}<>{$ch_power}<>{$ch_wanryoku}<>{$ch_kyakuryoku}<>{$ch_love}<>{$ch_unique}<>{$ch_etti}<>{$ch_energy}<>{$ch_nou_energy}<>{$ch_sintai}<>{$ch_zunou}<>{$ch_me_kokugo}<>{$ch_me_suugaku}<>{$ch_me_rika}<>{$ch_me_syakai}<>{$ch_me_eigo}<>{$ch_me_ongaku}<>{$ch_me_bijutu}<>{$ch_me_looks}<>{$ch_me_tairyoku}<>{$ch_me_kenkou}<>{$ch_me_speed}<>{$ch_me_power}<>{$ch_me_wanryoku}<>{$ch_me_kyakuryoku}<>{$ch_me_love}<>{$ch_me_unique}<>{$ch_me_etti}<>{$ch_k_yobi3}<>{$ch_k_yobi4}<>{$ch_k_yobi5}";
        lock(...$_args);
        if (!(pl_open('MTLO', ">{$chara_settei_file}"))) { error("Write Error : {$chara_settei_file}"); }
        pl_print('MTLO', $make_ch_temp);
        pl_close('MTLO');
        unlock(...$_args);
        $money = n($money) - (n($hikukane) * 10000);
        temp_routin(...$_args);
        log_kousin($my_log_file, $k_temp);
        message((string)($message_in), 'doukyo', 'game.php');
    }
    // 作成処理の場合の閉じ
    // コメントの初期化
    if (($ch_me_kokugo == '')) {
        $ch_me_kokugo = 'この漢字が読めるか？';
    }
    if (($ch_me_suugaku == '')) {
        $ch_me_suugaku = 'この数学の答えがわかるか？';
    }
    if (($ch_me_rika == '')) {
        $ch_me_rika = 'この公式を考えたのは誰か知ってるか？';
    }
    if (($ch_me_syakai == '')) {
        $ch_me_syakai = 'この事件は何年に起きたか知ってるか？';
    }
    if (($ch_me_eigo == '')) {
        $ch_me_eigo = 'この英単語の意味を知ってるか？';
    }
    if (($ch_me_ongaku == '')) {
        $ch_me_ongaku = 'この曲を作曲したのは誰か知ってるか？';
    }
    if (($ch_me_bijutu == '')) {
        $ch_me_bijutu = 'この絵を見てみろ！';
    }
    if (($ch_me_looks == '')) {
        $ch_me_looks = 'ルックスで勝負だ！';
    }
    if (($ch_me_tairyoku == '')) {
        $ch_me_tairyoku = '体力で勝負しろ！';
    }
    if (($ch_me_kenkou == '')) {
        $ch_me_kenkou = '健康には自信があるぞ！';
    }
    if (($ch_me_speed == '')) {
        $ch_me_speed = 'このスピードについてこられるかな？';
    }
    if (($ch_me_power == '')) {
        $ch_me_power = 'タックルで勝負だ！';
    }
    if (($ch_me_wanryoku == '')) {
        $ch_me_wanryoku = '腕相撲で勝負だ！';
    }
    if (($ch_me_kyakuryoku == '')) {
        $ch_me_kyakuryoku = 'キックをお見舞いしてやる！';
    }
    if (($ch_me_love == '')) {
        $ch_me_love = '愛の深さでは負けないぞ！';
    }
    if (($ch_me_unique == '')) {
        $ch_me_unique = 'このギャグどう？';
    }
    if (($ch_me_etti == '')) {
        $ch_me_etti = 'エッチでは負けない！';
    }
    // 画面表示
    if ($ch_gazou) {
        $charaimage_gazou = "<img src=\"{$ch_gazou}\" {$chara_gazou_size}>";
    } else {
        $charaimage_gazou = '';
    }
    t_header('item_style');
    echo <<<EOM
    	<table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr><td bgcolor="#ffffff">ここでは自分だけのキャラクターを作成することができます。キャラクターは自分のパラメーターとお金を投入することで成長します。<br>最強のキャラクターを作成して「Cリーグ」に参加しましょう。<br>（将来的には自分の家にキャラのコーナーができて来訪者とのやりとりができるようになる予定です）</td>
    	<td  bgcolor="#333333" align="center" width="200"><img src="{$img_dir}/chara_tytle.gif"></td>
    	</tr></table><br>
    EOM;
    if (($in['command'] == '')) {
        echo <<<EOM
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="doukyo">
        	<input type="hidden" name="command" value="make_chara">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="k_id" value="{$in['k_id']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        
        		<table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
        		<tr><td>
        		<div align="center">
        		{$charaimage_gazou}<br>
        		<div class="honbun2">{$ch_name}</div><br>
        		頭の良さ：{$ch_zunou}<br>
        		身体能力：{$ch_sintai}
        		</div>
        
        	</td><td>
        	<div class="honbun2">●キャラの名前（全角15文字以内）</div>※随時変更ができます。<br>
        	<input type="text" size="30" name="ch_name" value="{$ch_name}"><br><br>
        
        	<div class="honbun2">●キャラクター画像</div>（http://～で始まる絶対URL。{$c_size_comment} ※画像はなくても構いません。）<br>※随時変更ができます。<br>
        	<!--<input type=file name="upfile" size=30><br>-->
        	<input type="text" name="ch_gazou" size="60" value="{$ch_gazou}"><br><br>
        
        	<div class="honbun2">●パラメーターアップ</div>
        	入力した数値分のパラメーターが自分から引かれ、さらにその数値×１万円の費用がかかります。<br>
        	キャラクターは与えられた数値分のパラメーターが上がります。<br>
        	<table border="0"><tr>
        	<td>国語</td><td><input type="text" name="ch_kokugo" size="10"></td></td>
        	<td>数学</td><td><input type="text" name="ch_suugaku" size="10"></td>
        	<td>理科</td><td><input type="text" name="ch_rika" size="10"></td>
        	<td>社会</td><td><input type="text" name="ch_syakai" size="10"></td>
        	<td>英語</td><td><input type="text" name="ch_eigo" size="10"></td></tr><tr>
        	<td>音楽</td><td><input type="text" name="ch_ongaku" size="10"></td>
        	<td>美術</td><td><input type="text" name="ch_bijutu" size="10"></td>
        	<td>ルックス</td><td><input type="text" name="ch_looks" size="10"></td>
        	<td>体力</td><td><input type="text" name="ch_tairyoku" size="10"></td>
        	<td>健康</td><td><input type="text" name="ch_kenkou" size="10"></td></tr><tr>
        	<td>スピード</td><td><input type="text" name="ch_speed" size="10"></td>
        	<td>パワー</td><td><input type="text" name="ch_power" size="10"></td>
        	<td>腕力</td><td><input type="text" name="ch_wanryoku" size="10"></td>
        	<td>脚力</td><td><input type="text" name="ch_kyakuryoku" size="10"></td>
        	<td>LOVE</td><td><input type="text" name="ch_love" size="10"></td></tr><tr>
        	<td>面白さ</td><td><input type="text" name="ch_unique" size="10"></td>
        	<td>エッチ</td><td><input type="text" name="ch_etti" size="10">
        	</tr></table>
        
        	</td><td width="150">
        	<table border="0"  cellspacing="0" cellpadding="1" style=" border: {$st_win_wak}; border-style: solid; border-width: 1px;" bgcolor="#ffffcc" width="100%">
        <td colspan="2" align="center"><span class="tyuu">頭　脳</span></td></tr>
        <tr><td align="right"><span class="honbun5">国語</span>：</td><td align="right">{$ch_kokugo}</td></tr>
        <tr><td align="right"><span class="honbun5">数学</span>：</td><td align="right">{$ch_suugaku}</td></tr>
        <tr><td align="right"><span class="honbun5">理科</span>：</td><td align="right">{$ch_rika}</td></tr>
        <tr><td align="right"><span class="honbun5">社会</span>：</td><td align="right">{$ch_syakai}</td></tr>
        <tr><td align="right"><span class="honbun5">英語</span>：</td><td align="right">{$ch_eigo}</td></tr>
        <tr><td align="right"><span class="honbun5">音楽</span>：</td><td align="right">{$ch_ongaku}</td></tr>
        <tr><td align="right"><span class="honbun5">美術</span>：</td><td align="right">{$ch_bijutu}</td></tr>
        <tr style=" border: {$st_win_wak}; border-style: solid; border-top-width: 1px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px">
        <td  colspan="2" align="center"><span class="tyuu">身　体</span></td></tr>
        <tr><td  align="right"><span class="honbun5">ルックス</span>：</td><td align="right">{$ch_looks}</td></tr>
        <tr><td align="right"><span class="honbun5">体力</span>：</td><td align="right">{$ch_tairyoku}</td></tr>
        <tr><td align="right"><span class="honbun5">健康</span>：</td><td align="right">{$ch_kenkou}</td></tr>
        <tr><td align="right"><span class="honbun5">スピード</span>：</td><td align="right">{$ch_speed}</td></tr>
        <tr><td align="right"><span class="honbun5">パワー</span>：</td><td align="right">{$ch_power}</td></tr>
        <tr><td align="right"><span class="honbun5">腕力</span>：</td><td align="right">{$ch_wanryoku}</td></tr>
        <tr><td align="right"><span class="honbun5">脚力</span>：</td><td align="right">{$ch_kyakuryoku}</td></tr>
        <tr style=" border: {$st_win_wak}; border-style: solid; border-top-width: 1px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px">
        <td  colspan="2" align="center"><span class="tyuu">その他</span></td>
        <tr><td align="right"><span class="honbun5">LOVE</span>：</td><td align="right">{$ch_love}</td></tr>
        <tr><td align="right"><span class="honbun5">面白さ</span>：</td><td align="right">{$ch_unique}</td></tr>
        <tr><td align="right"><span class="honbun5">エッチ</span>：</td><td align="right">{$ch_etti}</td></tr>
        </table>
        	</td></tr>
        	<tr><td colspan="3">
        	<div align="center"><input type="submit" value=" O K "></div>
        	</td></tr></table>
        	</form>
        
        	<div align="center"><form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="doukyo">
        	<input type="hidden" name="command" value="com_henkou">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="k_id" value="{$in['k_id']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<input type="submit" value="コメント変更フォーム出力"></form></div>
        
        EOM;
    }
    if (($in['command'] == 'com_henkou')) {
        echo <<<EOM
        	<form method="POST" action="{$this_script}">
        	<input type="hidden" name="mode" value="doukyo">
        	<input type="hidden" name="command" value="make_chara">
        	<input type="hidden" name="name" value="{$in['name']}">
        	<input type="hidden" name="pass" value="{$in['pass']}">
        	<input type="hidden" name="k_id" value="{$in['k_id']}">
        	<input type="hidden" name="town_no" value="{$in['town_no']}">
        	<table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
        	<tr><td>
        	<div class="honbun2">●闘うときのコメント</div>
        	各能力ごとにその能力で勝負する時に言うコメントを記入してください。※40字以内（このフォームの長さに収まるように）<br><br>
        	<table border="0"><tr>
        	<td>国語</td><td><input type="text" name="ch_me_kokugo" size="80" value="{$ch_me_kokugo}"></td></td></tr><tr>
        	<td>数学</td><td><input type="text" name="ch_me_suugaku" size="80" value="{$ch_me_suugaku}"></td></tr><tr>
        	<td>理科</td><td><input type="text" name="ch_me_rika" size="80" value="{$ch_me_rika}"></td></tr><tr>
        	<td>社会</td><td><input type="text" name="ch_me_syakai" size="80" value="{$ch_me_syakai}"></td></tr><tr>
        	<td>英語</td><td><input type="text" name="ch_me_eigo" size="80" value="{$ch_me_eigo}"></td></tr><tr>
        	<td>音楽</td><td><input type="text" name="ch_me_ongaku" size="80" value="{$ch_me_ongaku}"></td></tr><tr>
        	<td>美術</td><td><input type="text" name="ch_me_bijutu" size="80" value="{$ch_me_bijutu}"></td></tr><tr>
        	<td>ルックス</td><td><input type="text" name="ch_me_looks" size="80" value="{$ch_me_looks}"></td></tr><tr>
        	<td>体力</td><td><input type="text" name="ch_me_tairyoku" size="80" value="{$ch_me_tairyoku}"></td></tr><tr>
        	<td>健康</td><td><input type="text" name="ch_me_kenkou" size="80" value="{$ch_me_kenkou}"></td></tr><tr>
        	<td>スピード</td><td><input type="text" name="ch_me_speed" size="80" value="{$ch_me_speed}"></td></tr><tr>
        	<td>パワー</td><td><input type="text" name="ch_me_power" size="80" value="{$ch_me_power}"></td></tr><tr>
        	<td>腕力</td><td><input type="text" name="ch_me_wanryoku" size="80" value="{$ch_me_wanryoku}"></td></tr><tr>
        	<td>脚力</td><td><input type="text" name="ch_me_kyakuryoku" size="80" value="{$ch_me_kyakuryoku}"></td></tr><tr>
        	<td>LOVE</td><td><input type="text" name="ch_me_love" size="80" value="{$ch_me_love}"></td></tr><tr>
        	<td>面白さ</td><td><input type="text" name="ch_me_unique" size="80" value="{$ch_me_unique}"></td></tr><tr>
        	<td>エッチ</td><td><input type="text" name="ch_me_etti" size="80" value="{$ch_me_etti}"></td>
        	</tr></table>
        	<div align="center"><input type="submit" value=" コメント変更 "></div></form><br><br>
        	<div align="center"><!--TW_BACK--></div>
        	</td></tr></table>
        EOM;
    }
    hooter('login_view', '戻る');
    exit;
}

function bykeys3(...$_args)
{
    global $a, $b, $keys3;
    return (n($keys3[n($b)]) <=> n($keys3[n($a)]));
}

function bykeys2(...$_args)
{
    global $a, $b, $keys2;
    return (n($keys2[n($b)]) <=> n($keys2[n($a)]));
}

function c_league(...$_args)
{
    global $_, $aite_chara_image, $aite_chara_para, $aite_chara_power, $aite_erabi_list, $aite_kettei,
        $aite_settei_data, $aite_settei_file, $all_taisen_list, $alldata, $battle_naiyou_hanbetu, $c_nissuu,
        $c_siai_kankaku, $c_siaisuu, $c_sinki_temp, $c_size_comment, $ch_gazou, $ch_hikiwake, $ch_k_id, $ch_kati,
        $ch_lasttime, $ch_make, $ch_name, $ch_name_hyouzi, $ch_oyaname, $ch_randed, $ch_yobi1, $ch_yobi2, $ch_yobi3,
        $ch_yobi4, $ch_yobi5, $ch_yobi6, $ch_yuusyou, $chara_gazou_size, $chara_settei_data, $chara_settei_file,
        $chara_x_size, $chara_y_size, $doukyo_logfile, $i, $img_dir, $in, $junidata, $k_id, $key, $key2, $key3,
        $keys, $keys2, $keys3, $league_meisai, $my_chara_image, $my_chara_para, $my_chara_power,
        $my_genzaino_joukyou, $name, $nankai_taikai, $nannitime, $nitteicheck, $now_time, $sentou_kaisuu,
        $siaisyouka, $sougoujunidata, $st_win_wak, $start_time, $syouhai_hyouzi, $taisen_list, $this_script, $turn,
        $win_flag, $zenkaijunidata, $zibun_gazou, $zibun_hazusi, $zibun_hikiwake, $zibun_k_id, $zibun_kati,
        $zibun_make, $zibun_name, $zibun_oyaname;
    if (((n($chara_x_size) == 0) && (n($chara_y_size) == 0))) {
        $chara_gazou_size = '';
        $c_size_comment = '';
    } else {
        $chara_gazou_size = "width={$chara_x_size} height={$chara_y_size}";
        $c_size_comment = "画像サイズは横{$chara_x_size}ピクセル、縦{$chara_y_size}ピクセルです。";
    }
    if (!(pl_open('IN', (string)($doukyo_logfile)))) { error("Open Error : {$doukyo_logfile}"); }
    $league_meisai = pl_readline('IN');
    $aite_erabi_list = pl_readlines('IN');
    pl_close('IN');
    $now_time = pl_time();
    // ゲーム明細初期化
    if (($league_meisai == '')) {
        lock(...$_args);
        $league_meisai = "{$now_time}<>1<>\n";
        if (!(pl_open('OUT', ">{$doukyo_logfile}"))) { error("Open Error : {$doukyo_logfile}"); }
        pl_print('OUT', $league_meisai);
        pl_close('OUT');
        unlock(...$_args);
    }
    [$start_time, $nankai_taikai] = pl_pad(pl_split('/<>/u', $league_meisai), 2);
    $nannitime = (pl_int((((n($now_time) - n($start_time))) / (((60 * 60) * 24)))) + 1);
    if ((n($nannitime) > n($c_nissuu))) {
        taikai_syokika(...$_args);
    }
    // 試合の場合
    if (($in['command'] == 'game')) {
        lock(...$_args);
        // 自分のキャラデータを開いて変数に入れる
        $chara_settei_file = "./member/{$k_id}/chara_ini.cgi";
        if (!(pl_open('CSF', (string)($chara_settei_file)))) { error('キャラを作成してからでないと試合はできません'); }
        $chara_settei_data = pl_readline('CSF');
        $my_chara_para = pl_split('/<>/u', $chara_settei_data);
        if (($chara_settei_data == '')) {
            error('キャラを作成してからでないと試合はできません');
        }
        pl_close('CSF');
        // 登録されているキャラIDで配列作成。未登録なら登録
        $zibun_hazusi = 0;
        $__s1 = $_ ?? null;
        foreach ($aite_erabi_list ?? [] as $_) {
            [$ch_k_id, $ch_name, $ch_oyaname, $ch_gazou, $ch_kati, $ch_make, $ch_hikiwake, $ch_yuusyou, $ch_lasttime] = pl_pad(pl_split('/<>/u', $_), 9);
            // 自分が見つかったら試合リストから自分をはずす。
            if (((string)$name === (string)$ch_oyaname)) {
                if (((n($now_time) - n($ch_lasttime)) < (60 * n($c_siai_kankaku)))) {
                    error('まだ前回の試合の疲れが残っています。');
                }
                $my_genzaino_joukyou = (string)($_);
                // 自分の状況を変数に入れておく
                $zibun_hazusi = 1;
                continue;
            }
            $taisen_list[] = $ch_k_id;
            $all_taisen_list[] = $_;
        }
        $_ = $__s1;
        if ((n($zibun_hazusi) == 0)) {
            c_league_touroku($chara_settei_data);
        }
        $nitteicheck = pl_split('/<>/u', $my_genzaino_joukyou);
        if ((((n($nitteicheck[4]) + n($nitteicheck[5])) + n($nitteicheck[6])) >= n($c_siaisuu))) {
            error('全試合日程を終了しています。');
        }
        $ch_randed = pl_int(pl_rand((pl_count($taisen_list) - 1)));
        $aite_kettei = pl_last(pl_splice($taisen_list, n($ch_randed), 1));
        if (($aite_kettei == '')) {
            message('闘う相手が見つからなかった。。', 'c_league');
        }
        if (((string)$aite_kettei === (string)(string)($k_id))) {
            message('闘う相手が見つからなかった。。', 'c_league');
        }
        // 相手のキャラデータを開いて変数に入れる
        $aite_settei_file = "./member/{$aite_kettei}/chara_ini.cgi";
        if (!(pl_open('ASF', (string)($aite_settei_file)))) { error('対戦相手の都合により試合は延期となりました。'); }
        $aite_settei_data = pl_readline('ASF');
        $aite_chara_para = pl_split('/<>/u', $aite_settei_data);
        if (($aite_settei_data == '')) {
            error('対戦相手の都合により試合は延期となりました。');
        }
        pl_close('ASF');
        // キャラ画像があれば変数に代入
        if ($my_chara_para[3]) {
            $my_chara_image = "<img src=\"{$my_chara_para[3]}\" {$chara_gazou_size}>";
        } else {
            $my_chara_image = '';
        }
        if ($aite_chara_para[3]) {
            $aite_chara_image = "<img src=\"{$aite_chara_para[3]}\" {$chara_gazou_size}>";
        } else {
            $aite_chara_image = '';
        }
        t_header('item_style');
        // キャラのパワー（体力）を算出
        $my_chara_power = (n($my_chara_para[26]) + n($my_chara_para[25]));
        $aite_chara_power = (n($aite_chara_para[26]) + n($aite_chara_para[25]));
        echo <<<EOM
        	<br><br><table width="600" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
        	<tr align="center"><td>
        <table border="0"  cellspacing="0" cellpadding="1" style=" border: {$st_win_wak}; border-style: solid; border-width: 1px;"  width="150">
        <tr><td align="center" colspan="2">
        {$my_chara_para[2]}のキャラクター<br>
        {$my_chara_para[1]}<br>
        {$my_chara_image}
        </td></tr>
        <tr><td align="center"><span class="honbun3">頭の良さ</span>：</td><td>{$my_chara_para[26]}</td></tr>
        <tr><td align="center"><span class="honbun3">身体能力</span>：</td><td>{$my_chara_para[25]}</td></tr>
        </table>
        	</td><td>
        	<div class="tyuu">{$aite_chara_para[1]}との試合が始まった！</div><br><br>
        	</td><td>
        <table border="0"  cellspacing="0" cellpadding="1" style=" border: {$st_win_wak}; border-style: solid; border-width: 1px;"  width="150">
        <tr><td align="center" colspan="2">
        {$aite_chara_para[2]}のキャラクター<br>
        {$aite_chara_para[1]}<br>
        {$aite_chara_image}
        </td></tr>
        <tr><td align="center"><span class="honbun3">頭の良さ</span>：</td><td>{$aite_chara_para[26]}</td></tr>
        <tr><td align="center"><span class="honbun3">身体能力</span>：</td><td>{$aite_chara_para[25]}</td></tr>
        </table>
        	</td></tr></table>
        EOM;
        if ((n($my_chara_para[16]) > n($aite_chara_para[16]))) {
            $turn = 1;
        }
        $sentou_kaisuu = 0;
        $__s2 = $_ ?? null;
        foreach (pl_range(1, 25) as $_) {
            echo '<br><br><table width="600" border="0" cellspacing="0" cellpadding="5" align="center" class="yosumi"><tr><td colspan="2">';
            if ((n($turn) == 1)) {
                ch_kougeki(1, $my_chara_para[1]);
                // 自分の攻撃
            } else {
                ch_kougeki(0, $aite_chara_para[1]);
            }
            // 相手の攻撃
            echo <<<EOM
            			</td></tr>
            			<tr><td align="left">
            			<div class="tyuu">エネルギー：{$my_chara_power}</div>
            			</td>
            			<td align="right">
            			<div class="tyuu">エネルギー：{$aite_chara_power}</div>
            			</td></tr></table>
            EOM;
            $sentou_kaisuu++;
            if ((n($aite_chara_power) <= 0)) {
                $win_flag = 1;
                break;
            }
            if ((n($my_chara_power) <= 0)) {
                $win_flag = 2;
                break;
            }
            if ((n($turn) == 1)) {
                $turn = 0;
            } else {
                $turn = 1;
            }
        }
        $_ = $__s2;
        echo '<br><br>';
        // 先に入れておいた自分の状況をsplit
        [$ch_k_id, $ch_name, $ch_oyaname, $ch_gazou, $ch_kati, $ch_make, $ch_hikiwake, $ch_yuusyou, $ch_lasttime, $ch_yobi1, $ch_yobi2, $ch_yobi3, $ch_yobi4, $ch_yobi5, $ch_yobi6] = pl_pad(pl_split('/<>/u', $my_genzaino_joukyou), 15);
        // ch_yobi1＝最後の対戦状況　ch_yobi2＝獲得ポイント　ch_yobi3＝前回勝ち　ch_yobi4＝前回負け　ch_yobi5＝前回引き分け
        // 勝った場合
        if ((n($win_flag) == 1)) {
            $ch_kati++;
            // 最後のバトルコメント
            $ch_yobi1 = "<td align=\"center\">{$my_chara_para[1]} vs {$aite_chara_para[1]}</td><td align=\"center\">{$my_chara_para[1]}</td><td>{$my_chara_para[n($battle_naiyou_hanbetu)]}</td>";
            echo '<div align="center" style="color:#339933;font-size:14px;">見事勝利を収めました！</div>';
            // 負けた場合
        } elseif ((n($win_flag) == 2)) {
            // 最後のバトルコメント
            $ch_yobi1 = "<td align=\"center\">{$my_chara_para[1]} vs {$aite_chara_para[1]}</td><td align=\"center\">{$aite_chara_para[1]}</td><td>{$aite_chara_para[n($battle_naiyou_hanbetu)]}</td>";
            $ch_make++;
            echo '<div align="center" style="color:#ff3300;font-size:14px;">負けてしまいました。。</div>';
        } else {
            $ch_yobi1 = "<td align=\"center\">{$my_chara_para[1]} vs {$aite_chara_para[1]}</td><td align=\"center\">引き分け</td><td>ー</td>";
            $ch_hikiwake++;
            echo '<div align="center" style="color:#ff3300;font-size:14px;">決着がつきませんでした。。引き分けです。</div>';
        }
        $ch_gazou = (string)($my_chara_para[3]);
        $ch_lasttime = $now_time;
        $c_sinki_temp = "{$ch_k_id}<>{$ch_name}<>{$ch_oyaname}<>{$ch_gazou}<>{$ch_kati}<>{$ch_make}<>{$ch_hikiwake}<>{$ch_yuusyou}<>{$ch_lasttime}<>{$ch_yobi1}<>{$ch_yobi2}<>{$ch_yobi3}<>{$ch_yobi4}<>{$ch_yobi5}<>{$ch_yobi6}<>\n";
        pl_unshift($all_taisen_list, $c_sinki_temp);
        pl_unshift($all_taisen_list, $league_meisai);
        if (!(pl_open('TOO', ">{$doukyo_logfile}"))) { error("Open Error : {$doukyo_logfile}"); }
        pl_print('TOO', pl_join('', $all_taisen_list));
        pl_close('TOO');
        unlock(...$_args);
        // データ更新
        // &temp_routin;
        // &log_kousin($my_log_file,$k_temp);
        hooter('c_league', '戻る', 'game.php');
        exit;
    }
    // 試合の場合の閉じ
    t_header('keiba_style');
    echo <<<EOM
    	<table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    	<tr><td bgcolor="#ffffff">最強のキャラクターを決める「Cリーグ」です。{$c_nissuu}日間で{$c_siaisuu}試合を行うことができます。もっとも勝利数の多いキャラクターが優勝となります。試合間隔は{$c_siai_kankaku}分です。</td>
    	<td  bgcolor="#333333" align="center" width="200"><img src="{$img_dir}/cleague_tytle.gif"></td>
    	</tr></table><br>
    
    		<table width="90%" border="0" cellspacing="0" cellpadding="10" align="center" class="yosumi">
    		<tr><td>
    EOM;
    $__s3 = $_ ?? null;
    foreach ($aite_erabi_list ?? [] as $_) {
        [$ch_k_id, $ch_name, $ch_oyaname, $ch_gazou, $ch_kati, $ch_make, $ch_hikiwake, $ch_yuusyou, $ch_lasttime, $ch_yobi1, $ch_yobi2, $ch_yobi3, $ch_yobi4, $ch_yobi5, $ch_yobi6] = pl_pad(pl_split('/<>/u', $_), 15);
        if (((string)$name === (string)$ch_oyaname)) {
            $my_genzaino_joukyou = (string)($_);
            // 自分の状況を変数に入れておく
        }
        $key = pl_idx(pl_split('/<>/u', $_), 4);
        // ソートする要素を選ぶ
        $key2 = pl_idx(pl_split('/<>/u', $_), 11);
        // ソートする要素を選ぶ
        $key3 = pl_idx(pl_split('/<>/u', $_), 10);
        // ソートする要素を選ぶ
        $alldata[] = $_;
        $keys[] = $key;
        $keys2[] = $key2;
        $keys3[] = $key3;
    }
    $_ = $__s3;
    $junidata = pl_slice($alldata, pl_sort('bykeys', pl_range(0, (pl_count($alldata) - 1))));
    $sougoujunidata = pl_slice($alldata, pl_sort('bykeys3', pl_range(0, (pl_count($alldata) - 1))));
    $zenkaijunidata = pl_slice($alldata, pl_sort('bykeys2', pl_range(0, (pl_count($alldata) - 1))));
    [$zibun_k_id, $zibun_name, $zibun_oyaname, $zibun_gazou, $zibun_kati, $zibun_make, $zibun_hikiwake] = pl_pad(pl_split('/<>/u', $my_genzaino_joukyou), 7);
    if (($my_genzaino_joukyou != '')) {
        $syouhai_hyouzi = "『{$zibun_name}』は現在、{$zibun_kati}勝{$zibun_make}敗{$zibun_hikiwake}分け";
    }
    echo <<<EOM
    	<div align="center" class="dai">第{$nankai_taikai}回大会 - {$nannitime}日目 -</div>
    	<div align="center"><form method="POST" action="{$this_script}">
    	<input type="hidden" name="mode" value="c_league">
    	<input type="hidden" name="command" value="game">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="k_id" value="{$in['k_id']}">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<input type="submit" value="試合をする"></form>
    	{$syouhai_hyouzi}
    	</div>
    	<hr size="1">
    	<table width="70%" border="0" cellspacing="0" cellpadding="5" align="center">
    	<tr><td colspan="3">
    	●最近の試合
    	</td></tr>
    	<tr  class="jouge" bgcolor="#ffff66" align="center"><td>対　戦</td><td nowrap>勝　者</td><td nowrap>決めのコメント</td></tr>
    EOM;
    $i = 1;
    $__s4 = $_ ?? null;
    foreach ($alldata ?? [] as $_) {
        [$ch_k_id, $ch_name, $ch_oyaname, $ch_gazou, $ch_kati, $ch_make, $ch_hikiwake, $ch_yuusyou, $ch_lasttime, $ch_yobi1, $ch_yobi2, $ch_yobi3, $ch_yobi4, $ch_yobi5, $ch_yobi6] = pl_pad(pl_split('/<>/u', $_), 15);
        echo "<tr class=\"sita2\">{$ch_yobi1}</tr>";
        if ((n($i) >= 5)) {
            break;
        }
        $i++;
    }
    $_ = $__s4;
    echo <<<EOM
    	</table><br><br>
    	<table width="70%" border="0" cellspacing="0" cellpadding="5" align="center">
    	<tr><td colspan="7">
    	●現在までの順位（ベスト10）
    	</td></tr>
    	<tr  class="jouge" bgcolor="#ffff66" align="center"><td></td><td nowrap>名前</td><td>作成者</td><td>勝　利</td><td nowrap>敗　戦</td><td>引き分け</td><td>試合消化数</td><td nowrap>優勝回数</td></tr>
    EOM;
    $i = 1;
    $__s5 = $_ ?? null;
    foreach ($junidata ?? [] as $_) {
        [$ch_k_id, $ch_name, $ch_oyaname, $ch_gazou, $ch_kati, $ch_make, $ch_hikiwake, $ch_yuusyou, $ch_lasttime, $ch_yobi1, $ch_yobi2, $ch_yobi3, $ch_yobi4, $ch_yobi5, $ch_yobi6] = pl_pad(pl_split('/<>/u', $_), 15);
        if (((n($i) <= 3) && ($ch_gazou != ''))) {
            $ch_name_hyouzi = "<img src=\"{$ch_gazou}\" {$chara_gazou_size}><br>{$ch_name}";
        } else {
            $ch_name_hyouzi = (string)($ch_name);
        }
        $siaisyouka = ((n($ch_kati) + n($ch_make)) + n($ch_hikiwake));
        echo <<<EOM
        		<tr class="sita2"><td align="center">{$i}</td><td nowrap align="center">{$ch_name_hyouzi}</td><td align="center">{$ch_oyaname}</td><td align="right" nowrap>{$ch_kati}勝</td><td align="right" nowrap>{$ch_make}敗</td><td align="right">{$ch_hikiwake}分け</td><td align="right" nowrap>{$siaisyouka}</td><td align="right" nowrap>{$ch_yuusyou}回</td></tr>
        EOM;
        if ((n($i) >= 10)) {
            break;
        }
        $i++;
    }
    $_ = $__s5;
    echo '</table>';
    if ((n($nankai_taikai) != 1)) {
        echo <<<EOM
        	<br><br>
        	<table width="70%" border="0" cellspacing="0" cellpadding="5" align="center">
        	<tr><td colspan="7">
        	●前回大会の順位（ベスト5）
        	</td></tr>
        	<tr  class="jouge" bgcolor="#ffff66" align="center"><td></td><td nowrap>名前</td><td>作成者</td><td>勝　利</td><td nowrap>敗　戦</td><td>引き分け</td><td nowrap>優勝回数</td></tr>
        EOM;
        $i = 1;
        $__s6 = $_ ?? null;
        foreach ($zenkaijunidata ?? [] as $_) {
            [$ch_k_id, $ch_name, $ch_oyaname, $ch_gazou, $ch_kati, $ch_make, $ch_hikiwake, $ch_yuusyou, $ch_lasttime, $ch_yobi1, $ch_yobi2, $ch_yobi3, $ch_yobi4, $ch_yobi5, $ch_yobi6] = pl_pad(pl_split('/<>/u', $_), 15);
            if (((n($i) == 1) && ($ch_gazou != ''))) {
                $ch_name_hyouzi = "<img src=\"{$ch_gazou}\" {$chara_gazou_size}><br>{$ch_name}";
            } else {
                $ch_name_hyouzi = (string)($ch_name);
            }
            $siaisyouka = ((n($ch_kati) + n($ch_make)) + n($ch_hikiwake));
            echo <<<EOM
            		<tr class="sita2"><td align="center">{$i}</td><td nowrap align="center">{$ch_name_hyouzi}</td><td align="center">{$ch_oyaname}</td><td align="right" nowrap>{$ch_yobi3}勝</td><td align="right" nowrap>{$ch_yobi4}敗</td><td align="right">{$ch_yobi5}分け</td><td align="right" nowrap>{$ch_yuusyou}回</td></tr>
            EOM;
            if ((n($i) >= 5)) {
                break;
            }
            $i++;
        }
        $_ = $__s6;
        echo '</table>';
        echo <<<EOM
        	<br><br>
        	<table width="70%" border="0" cellspacing="0" cellpadding="5" align="center">
        	<tr><td colspan="7">
        	●総合順位（ポイント獲得ベスト10）<br>
        	優勝＝10ポイント、２位＝5ポイント、３位＝3ポイント、４位＝2ポイント、５位＝1ポイントが加算され、その累積ポイント数で決定します。
        	</td></tr>
        	<tr  class="jouge" bgcolor="#ffff66" align="center"><td></td><td nowrap>名前</td><td>作成者</td><td>獲得ポイント</td><td nowrap>優勝回数</td></tr>
        EOM;
        $i = 1;
        $__s7 = $_ ?? null;
        foreach ($sougoujunidata ?? [] as $_) {
            [$ch_k_id, $ch_name, $ch_oyaname, $ch_gazou, $ch_kati, $ch_make, $ch_hikiwake, $ch_yuusyou, $ch_lasttime, $ch_yobi1, $ch_yobi2, $ch_yobi3, $ch_yobi4, $ch_yobi5, $ch_yobi6] = pl_pad(pl_split('/<>/u', $_), 15);
            if (($ch_yobi2 == '')) {
                continue;
            }
            if (((n($i) <= 3) && ($ch_gazou != ''))) {
                $ch_name_hyouzi = "<img src=\"{$ch_gazou}\" {$chara_gazou_size}><br>{$ch_name}";
            } else {
                $ch_name_hyouzi = (string)($ch_name);
            }
            $siaisyouka = ((n($ch_kati) + n($ch_make)) + n($ch_hikiwake));
            echo <<<EOM
            		<tr class="sita2"><td align="center">{$i}</td><td nowrap align="center">{$ch_name_hyouzi}</td><td align="center">{$ch_oyaname}</td><td align="right" nowrap>{$ch_yobi2}ポイント</td><td align="right" nowrap>{$ch_yuusyou}回</td></tr>
            EOM;
            if ((n($i) >= 10)) {
                break;
            }
            $i++;
        }
        $_ = $__s7;
        echo '</table>';
    }
    // １回大会でない場合の閉じ
    hooter('login_view', '戻る');
    exit;
}

function c_league_touroku(...$_args)
{
    global $c_sinki_temp, $ch2_lasttime, $ch2_yobi1, $ch2_yobi2, $ch2_yobi3, $ch2_yobi4, $ch2_yobi5, $ch2_yobi6,
        $doukyo_logfile, $my_genzaino_joukyou;
    if (!(pl_open('TO', (string)($doukyo_logfile)))) { error("Open Error : {$doukyo_logfile}"); }
    $touroku_list = pl_readlines('TO');
    pl_close('TO');
    [$ch2_k_id, $ch2_name, $ch2_oyaname, $ch2_gazou] = pl_pad(pl_split('/<>/u', $_args[0]), 4);
    $c_sinki_temp = "{$ch2_k_id}<>{$ch2_name}<>{$ch2_oyaname}<>{$ch2_gazou}<>0<>0<>0<>0<>{$ch2_lasttime}<>{$ch2_yobi1}<>{$ch2_yobi2}<>{$ch2_yobi3}<>{$ch2_yobi4}<>{$ch2_yobi5}<>{$ch2_yobi6}<>\n";
    $touroku_list[] = $c_sinki_temp;
    if (!(pl_open('TOO', ">{$doukyo_logfile}"))) { error("Open Error : {$doukyo_logfile}"); }
    pl_print('TOO', pl_join('', $touroku_list));
    pl_close('TOO');
    $my_genzaino_joukyou = (string)($c_sinki_temp);
    // 自分の状況を変数に入れておく
}

function ch_kougeki(...$_args)
{
    global $aite_chara_para, $aite_chara_power, $battle_rand, $damage, $my_chara_para, $my_chara_power,
        $return_naiyou;
    // 攻撃内容をランダムで選択
    $battle_rand = (pl_int(pl_rand(17)) + 1);
    echo '<table width="600" border="0" cellspacing="0" cellpadding="0" align="center"><tr align="center"><td>';
    // #自分の攻撃の場合
    if ((n($_args[0]) == 1)) {
        echo "<div style=\"color:#339933;font-size:12px;\" align=\"left\">{$_args[1]}の攻撃！<br>";
        // 攻撃内容
        ch_kougekinaiyou(1, $aite_chara_para[1]);
        // 結果表示
        if (($damage == 'no_d')) {
            echo "<div style=\"color:#ff3300;font-size:12px;\" align=\"right\">{$return_naiyou}</div>\n";
        } else {
            echo "<div style=\"color:#339933;font-size:12px;\" align=\"right\">{$return_naiyou}</div>\n";
            $aite_chara_power = n($aite_chara_power) - n($damage);
        }
        // #相手の攻撃の場合
    } else {
        echo "<div style=\"color:#ff3300;font-size:12px;\" align=\"right\">{$_args[1]}の攻撃！<br>";
        ch_kougekinaiyou(0, $my_chara_para[1]);
        // 結果表示
        // 自分にきかない場合
        if (($damage == 'no_d')) {
            echo "<div style=\"color:#339933;font-size:12px;\" align=\"left\">{$return_naiyou}</div>\n";
        } else {
            // ダメージを受けた場合
            echo "<div style=\"color:#ff3300;font-size:12px;\" align=\"left\">{$return_naiyou}</div>\n";
            $my_chara_power = n($my_chara_power) - n($damage);
        }
    }
    echo '</td></tr></table>';
}

function ch_kougekinaiyou(...$_args)
{
    global $aite_chara_para, $align_settei, $battle_naiyou_hanbetu, $battle_rand, $damage, $my_chara_para,
        $return_naiyou, $seisinornikutai;
    // @_[0]＝1なら自分の攻撃、0なら相手の攻撃
    if ((n($_args[0]) == 1)) {
        $align_settei = 'align=left';
    } else {
        $align_settei = 'align=right';
    }
    $battle_naiyou_hanbetu = (n($battle_rand) + 26);
    // バトルランダム値の１が国語のコメントになるようにする
    if ((((n($battle_rand) <= 8) || (n($battle_rand) == 15)) || (n($battle_rand) == 16))) {
        $seisinornikutai = '精神的ダメージを受けた！';
    } else {
        $seisinornikutai = '肉体的ダメージを受けた！';
    }
    if ((n($_args[0]) == 1)) {
        echo "{$my_chara_para[n($battle_naiyou_hanbetu)]}</div><br>\n";
    } else {
        echo "{$aite_chara_para[n($battle_naiyou_hanbetu)]}</div><br>\n";
    }
    // iryoku_hantei (攻撃者,攻撃の内容)
    ch_iryoku_hantei($_args[0], $battle_rand);
    if (($damage == 'no_d')) {
        $return_naiyou = "{$_args[1]}はダメージを受けなかった。";
    } else {
        $return_naiyou = "{$_args[1]}は <span style=\"font-size:18px\">{$damage}</span> の{$seisinornikutai}";
    }
}

function ch_iryoku_hantei(...$_args)
{
    global $aite_chara_para, $damage, $hantei_kakkati, $iryoku_hanteiti, $my_chara_para;
    // 攻撃の内容ごとの能力値
    $iryoku_hanteiti = (n($_args[1]) + 5);
    // バトルランダム値の１が国語の能力になるようにする
    if ((n($_args[0]) == 1)) {
        $hantei_kakkati = (n($my_chara_para[n($iryoku_hanteiti)]) - n($aite_chara_para[n($iryoku_hanteiti)]));
    } else {
        $hantei_kakkati = (n($aite_chara_para[n($iryoku_hanteiti)]) - n($my_chara_para[n($iryoku_hanteiti)]));
    }
    if ((n($hantei_kakkati) <= 0)) {
        $damage = 'no_d';
    } else {
        $damage = (string)($hantei_kakkati);
    }
}

function bykeys(...$_args)
{
    global $a, $b, $keys;
    return (n($keys[n($b)]) <=> n($keys[n($a)]));
}

function taikai_syokika(...$_args)
{
    global $_, $aite_erabi_list, $alldata, $c_sinki_temp, $doukyo_logfile, $i, $junidata, $key, $keys,
        $league_meisai, $nankai_taikai, $nannitime, $new_aite_erabi, $now_time;
    lock(...$_args);
    $__s1 = $_ ?? null;
    foreach ($aite_erabi_list ?? [] as $_) {
        [$ch_k_id, $ch_name, $ch_oyaname, $ch_gazou, $ch_kati, $ch_make, $ch_hikiwake, $ch_yuusyou, $ch_lasttime, $ch_yobi1, $ch_yobi2, $ch_yobi3, $ch_yobi4, $ch_yobi5, $ch_yobi6] = pl_pad(pl_split('/<>/u', $_), 15);
        $key = pl_idx(pl_split('/<>/u', $_), 4);
        // ソートする要素を選ぶ
        $alldata[] = $_;
        $keys[] = $key;
    }
    $_ = $__s1;
    $junidata = pl_slice($alldata, pl_sort('bykeys', pl_range(0, (pl_count($alldata) - 1))));
    $i = 1;
    $__s2 = $_ ?? null;
    foreach ($junidata ?? [] as $_) {
        [$ch_k_id, $ch_name, $ch_oyaname, $ch_gazou, $ch_kati, $ch_make, $ch_hikiwake, $ch_yuusyou, $ch_lasttime, $ch_yobi1, $ch_yobi2, $ch_yobi3, $ch_yobi4, $ch_yobi5, $ch_yobi6] = pl_pad(pl_split('/<>/u', $_), 15);
        $ch_yobi3 = $ch_kati;
        $ch_yobi4 = $ch_make;
        $ch_yobi5 = $ch_hikiwake;
        $ch_kati = 0;
        $ch_make = 0;
        $ch_hikiwake = 0;
        if ((n($i) == 1)) {
            $ch_yuusyou++;
            $ch_yobi2 = n($ch_yobi2) + 10;
        }
        if ((n($i) == 2)) {
            $ch_yobi2 = n($ch_yobi2) + 5;
        }
        if ((n($i) == 3)) {
            $ch_yobi2 = n($ch_yobi2) + 3;
        }
        if ((n($i) == 4)) {
            $ch_yobi2 = n($ch_yobi2) + 2;
        }
        if ((n($i) == 5)) {
            $ch_yobi2 = n($ch_yobi2) + 1;
        }
        $c_sinki_temp = "{$ch_k_id}<>{$ch_name}<>{$ch_oyaname}<>{$ch_gazou}<>{$ch_kati}<>{$ch_make}<>{$ch_hikiwake}<>{$ch_yuusyou}<>{$ch_lasttime}<>{$ch_yobi1}<>{$ch_yobi2}<>{$ch_yobi3}<>{$ch_yobi4}<>{$ch_yobi5}<>{$ch_yobi6}<>\n";
        $new_aite_erabi[] = $c_sinki_temp;
        $i++;
    }
    $_ = $__s2;
    $nankai_taikai++;
    $league_meisai = "{$now_time}<>{$nankai_taikai}<>\n";
    $aite_erabi_list = $new_aite_erabi;
    $alldata = [];
    $nannitime = 1;
    pl_unshift($new_aite_erabi, $league_meisai);
    if (!(pl_open('OUT', ">{$doukyo_logfile}"))) { error("Open Error : {$doukyo_logfile}"); }
    pl_print('OUT', pl_join('', $new_aite_erabi));
    pl_close('OUT');
    unlock(...$_args);
}

function t_new(...$_args)
{
    global $_, $all_pass_list, $all_sankasya, $bijutu, $date, $date_sec, $eigo, $err, $etti, $ginkoumeisai_file,
        $hashed_pass, $host, $in, $k_yobi3, $k_yobi4, $k_yobi5, $kenkou, $kokugo, $kyakuryoku, $last_syokuzi,
        $logfile, $looks, $love, $mem_lock_num, $message_file, $monokiroku_file, $my_directry, $my_log_file, $name,
        $new_temp, $new_touroku_per, $ongaku, $para1, $para10, $para11, $para12, $para13, $para14, $para15, $para16,
        $para17, $para2, $para3, $para4, $para5, $para6, $para7, $para8, $para9, $pass_logfile, $pass_temp, $power,
        $return_host, $rika, $saisin_id, $script, $sintyou, $speed, $st_win_back, $st_win_wak, $suugaku, $syakai,
        $syokuzi_kankaku, $taijuu, $tairyoku, $tajuukinsi_flag, $unique, $wanryoku, $zidouseisei;
    if ((n($new_touroku_per) == 1)) {
        error('現在、新規登録を中止しています。');
    }
    // ver.1.40
    lock(...$_args);
    get_host(...$_args);
    if (((($in['name'] == '') || ($in['pass'] == '')) || ($in['sex'] == ''))) {
        error('記入漏れがあります！');
    }
    if ((pl_match('/ /u', $in['name']) || pl_match('/　/u', $in['name']))) {
        error('名前にスペースを使わないでください');
    }
    if (pl_match('/,/u', $in['name'])) {
        error('名前に「,」を使わないでください');
    }
    // ver.1.3
    // ver.1.40ここから
    if (!(pl_open('IN', (string)($logfile)))) { error("Open Error : {$logfile}"); }
    $all_sankasya = pl_readlines('IN');
    $__s1 = $_ ?? null;
    foreach ($all_sankasya ?? [] as $_) {
        kozin_sprit(...$_args);
        if (((string)$in['name'] === (string)$name)) {
            error('その名前はすでに登録されています。別の名前でお試しください。');
        }
        if ((n($tajuukinsi_flag) == 1)) {
            if (((string)$return_host === (string)$host)) {
                error('二重登録は禁止です。');
            }
        }
    }
    $_ = $__s1;
    pl_close('IN');
    // ver.1.40ここから
    // パスワードリストから新規IDを取得
    if (!(pl_open('PA', (string)($pass_logfile)))) { error("Open Error : {$pass_logfile}"); }
    $all_pass_list = pl_readlines('PA');
    pl_close('PA');
    $saisin_id = pl_idx(pl_split('/<>/u', $all_pass_list[0]), 0);
    $saisin_id++;
    $para1 = (5 + (pl_int(pl_rand(15))));
    $para2 = (5 + (pl_int(pl_rand(15))));
    $para3 = (5 + (pl_int(pl_rand(15))));
    $para4 = (5 + (pl_int(pl_rand(15))));
    $para5 = (5 + (pl_int(pl_rand(15))));
    $para6 = (5 + (pl_int(pl_rand(15))));
    $para7 = (5 + (pl_int(pl_rand(15))));
    $para8 = (5 + (pl_int(pl_rand(15))));
    $para9 = (5 + (pl_int(pl_rand(15))));
    $para10 = (5 + (pl_int(pl_rand(15))));
    $para11 = (5 + (pl_int(pl_rand(15))));
    $para12 = (5 + (pl_int(pl_rand(15))));
    $para13 = (5 + (pl_int(pl_rand(15))));
    $para14 = (5 + (pl_int(pl_rand(15))));
    $para15 = (5 + (pl_int(pl_rand(15))));
    $para16 = (5 + (pl_int(pl_rand(15))));
    $para17 = (5 + (pl_int(pl_rand(15))));
    if (($in['sex'] == 'm')) {
        $sintyou = (165 + (pl_int(pl_rand(20))));
    } else {
        $sintyou = (150 + (pl_int(pl_rand(25))));
    }
    if (($in['sex'] == 'm')) {
        $taijuu = (50 + (pl_int(pl_rand(35))));
    } else {
        $taijuu = (48 + (pl_int(pl_rand(20))));
    }
    time_get(...$_args);
    $last_syokuzi = (n($date_sec) - ((n($syokuzi_kankaku) * 60)));
    // ver.1.3
    pl_check_admin_name_register($in['name'], $in['pass']);
    $hashed_pass = pl_password_hash($in['pass']);
    $new_temp = "{$saisin_id}<>{$in['name']}<>{$hashed_pass}<>50000<>0<>学生<>{$para1}<>{$para2}<>{$para3}<>{$para4}<>{$para5}<>{$para6}<>{$para7}<>{$para8}<>{$para9}<>{$para10}<>{$para11}<>{$para12}<>{$para13}<>{$para14}<>{$para15}<>{$para16}<>{$para17}<>{$date_sec}<><>{$in['sex']}<>{$date_sec}<>{$date}<>{$return_host}<><><><><><>0<>100<><><>{$last_syokuzi}<>{$sintyou}<>{$taijuu}<>100<><>0<>0<><>50<><><><><>{$k_yobi3}<>{$k_yobi4}<>{$k_yobi5}<>\n";
    $pass_temp = "{$saisin_id}<>{$in['name']}<>{$hashed_pass}<>\n";
    // 自分用ディレクトリ＆ログファイル作成
    $my_directry = "./member/{$saisin_id}";
    $my_log_file = "{$my_directry}/log.cgi";
    if (!(pl_mkdir($my_directry))) { error("ID番号{$saisin_id}のディレクトリが既に存在しています。"); }
    if ((n($zidouseisei) == 1)) {
        true;
    } elseif ((n($zidouseisei) == 2)) {
        true;
    } else {
        true;
    }
    if (!(pl_open('MYF', ">{$my_log_file}"))) { error("Open Error : {$my_log_file}"); }
    pl_print('MYF', $new_temp);
    true;
    pl_close('MYF');
    // メッセージ交換用ファイル作成
    $message_file = "{$my_directry}/mail.cgi";
    if (!(pl_open('MF', ">{$message_file}"))) { error("Write Error : {$message_file}"); }
    true;
    pl_close('MF');
    // 購入物記録ファイル作成
    $monokiroku_file = "{$my_directry}/mono.cgi";
    if (!(pl_open('MK', ">{$monokiroku_file}"))) { error("Write Error : {$monokiroku_file}"); }
    true;
    pl_close('MK');
    // 銀行明細記録ファイル作成
    $ginkoumeisai_file = "{$my_directry}/ginkoumeisai.cgi";
    if (!(pl_open('GM', ">{$ginkoumeisai_file}"))) { error("Write Error : {$ginkoumeisai_file}"); }
    true;
    pl_close('GM');
    // リスト用ログファイル作成
    $all_sankasya[] = $new_temp;
    if ((n($mem_lock_num) == 0)) {
        $err = data_save($logfile, ...($all_sankasya ?? []));
        if ($err) {
            error((string)($err));
        }
    } else {
        if (!(pl_open('OUT', ">>{$logfile}"))) { error("Write Error : {$logfile}"); }
        pl_print('OUT', $new_temp);
        pl_close('OUT');
    }
    // パスワード記録ファイル更新
    pl_unshift($all_pass_list, $pass_temp);
    if (!(pl_open('PAO', ">{$pass_logfile}"))) { error("Write Error : {$pass_logfile}"); }
    pl_print('PAO', pl_join('', $all_pass_list));
    pl_close('PAO');
    // ver.1.40ここまで
    // ニュース記録
    news_kiroku('入居', "{$in['name']}さんが新しい住民になりました。");
    // ver.1.3
    unlock(...$_args);
    set_cookie(...$_args);
    t_header(...$_args);
    check_pass(...$_args);
    echo <<<EOM
    	<div align="center">
    	<br><br>以下の内容で住民登録が完了しました。<br><br>
    
    <table width="450" border="0" cellspacing="0" cellpadding="4" align="center" style=" border: {$st_win_wak}; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px" bgcolor="{$st_win_back}">
    <tr>
    <td  width="25%"><span class="honbun2">名前</span>：{$name}</td>
    <td><span class="honbun2">パス</span>：（非表示）</td>
    <td width="25%"><span class="honbun2">身長</span>：{$sintyou}</td>
    <td width="25%"><span class="honbun2">体重</span>：{$taijuu}</td></tr>
    <tr style=" border: {$st_win_wak}; border-style: solid; border-top-width: 1px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px">
    <td  width="25%"><span class="tyuu">◆頭脳</span></td>
    <td><span class="honbun2">国語</span>：{$kokugo}</td>
    <td width="25%"><span class="honbun2">数学</span>：{$suugaku}</td>
    <td width="25%"><span class="honbun2">理科</span>：{$rika}</td></tr>
    <tr><td width="25%"><span class="honbun2">社会</span>：{$syakai}</td>
    <td width="25%"><span class="honbun2">英語</span>：{$eigo}</td>
    <td width="25%"><span class="honbun2">音楽</span>：{$ongaku}</td>
    <td width="25%"><span class="honbun2">美術</span>：{$bijutu}</td></tr>
    <tr style=" border: {$st_win_wak}; border-style: solid; border-top-width: 1px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px"><td  width="25%"><span class="tyuu">◆身体</span></td>
    <td><span class="honbun2">ルックス</span>：{$looks}</td>
    <td><span class="honbun2">体力</span>：{$tairyoku}</td>
    <td><span class="honbun2">健康</span>：{$kenkou}</td></tr>
    <tr><td><span class="honbun2">スピード</span>：{$speed}</td>
    <td><span class="honbun2">パワー</span>：{$power}</td>
    <td><span class="honbun2">腕力</span>：{$wanryoku}</td>
    <td><span class="honbun2">脚力</span>：{$kyakuryoku}</td></tr>
    <tr style=" border: {$st_win_wak}; border-style: solid; border-top-width: 1px; border-right-width: 0px; border-bottom-width: 1px; border-left-width: 0px"><td  width="25%"><span class="tyuu">◆その他</span></td>
    <td><span class="honbun2">LOVE</span>：{$love}</td>
    <td><span class="honbun2">面白さ</span>：{$unique}</td>
    <td><span class="honbun2">エッチ</span>：{$etti}</td></tr>
    </table>
    
    		<br><br><a href="{$script}">戻る</a>
    	</div>
    	</body></html>
    EOM;
    exit;
}

function data_hozon(...$_args)
{
    global $_, $all_guest, $back_data, $back_folder_name, $back_folder_pass, $backup_name, $dir, $err, $file_name,
        $guestfile, $hyouzi_check, $i, $k_id, $list_k_id, $logfile, $mem_lock_num, $message_win, $my_prof, $name,
        $new_all_guest, $ranking_data, $sanka_name, $sanka_timer, $sanka_tmp, $script, $sonzai_flag, $zidouseisei;
    // ver.1.40
    lock(...$_args);
    if (!(pl_open('DH', (string)($logfile)))) { error("Open Error : {$logfile}"); }
    $ranking_data = pl_readlines('DH');
    pl_close('DH');
    $sonzai_flag = 0;
    $i = 0;
    $__s1 = $_ ?? null;
    foreach ($ranking_data ?? [] as $_) {
        list_sprit($_);
        if (((string)$k_id === (string)(string)($list_k_id))) {
            $ranking_data[n($i)] = $my_prof;
            $sonzai_flag = 1;
            break;
        }
        $i++;
        // &list_temp;
        // push (@new_ranking_data,$list_temp);
    }
    $_ = $__s1;
    if ((n($sonzai_flag) == 0)) {
        pl_unshift($ranking_data, $my_prof);
    }
    // unshift (@new_ranking_data,$my_prof);
    // （ver.1.40ここから）
    if ((n($mem_lock_num) == 0)) {
        $err = data_save($logfile, ...($ranking_data ?? []));
        if ($err) {
            error((string)($err));
        }
    } else {
        if (!(pl_open('OUT', ">{$logfile}"))) { error("Write Error : {$logfile}"); }
        pl_print('OUT', pl_join('', $ranking_data));
        pl_close('OUT');
    }
    // （ver.1.40ここまで）
    // フォルダー内のファイル名を取得してバックアップログを作成
    $dir = pl_opendir('./member/' . (string)($k_id));
    // ver.1.2ココから
    $back_folder_name = (string)($k_id) . 'backup';
    $back_folder_pass = "./member/{$back_folder_name}";
    if (!(pl_exists("./member/{$back_folder_name}"))) {
        if (!(pl_mkdir($back_folder_pass))) { error('Error : can not Make Directry'); }
        if ((n($zidouseisei) == 1)) {
            true;
        } elseif ((n($zidouseisei) == 2)) {
            true;
        } else {
            true;
        }
    }
    // ver.1.2ココまで
    while (($file_name = pl_readdir($dir)) !== null) {
        // 1つ読み込んで$folder_nameに代入
        if ((((($file_name == '.') || ($file_name == '..')) || pl_match('/^backup_/u', $file_name)) || ($file_name == '.DS_Store'))) {
            continue;
        }
        $backup_name = 'backup_' . (string)($file_name);
        if (!(pl_open('BK', "./member/{$k_id}/{$file_name}"))) { error("Open Error : ./member/{$k_id}/{$file_name}"); }
        $back_data = pl_readlines('BK');
        pl_close('BK');
        if ((pl_count($back_data) != 0)) {
            // ver.1.22
            pl_open('BKO', ">./member/{$back_folder_name}/{$backup_name}");
            // ver.1.2
            pl_print('BKO', pl_join('', $back_data));
            pl_close('BKO');
        }
        // ver.1.22
    }
    pl_closedir($dir);
    // ディレクトリを閉じる
    // ver.1.30ここから
    pl_open('GUEST', (string)($guestfile));
    $all_guest = pl_readlines('GUEST');
    pl_close('GUEST');
    $new_all_guest = [];
    $__s2 = $_ ?? null;
    foreach ($all_guest ?? [] as $_) {
        [$sanka_timer, $sanka_name, $hyouzi_check] = pl_pad(pl_split('/<>/u', $_), 3);
        if (((string)$name === (string)(string)($sanka_name))) {
            continue;
        }
        $sanka_tmp = "{$sanka_timer}<>{$sanka_name}<>{$hyouzi_check}<>\n";
        $new_all_guest[] = $sanka_tmp;
    }
    $_ = $__s2;
    // ver.1.40ここから
    if ((n($mem_lock_num) == 0)) {
        $err = data_save($guestfile, ...($new_all_guest ?? []));
        if ($err) {
            error((string)($err));
        }
    } else {
        pl_open('GUEST', ">{$guestfile}");
        pl_print('GUEST', pl_join('', $new_all_guest));
        pl_close('GUEST');
    }
    // ver.1.40ここまで
    // ver.1.30ここまで
    unlock(...$_args);
    set_cookie(...$_args);
    t_header('', 'sonomati');
    echo <<<EOM
    	<div align="center"><br><table  border="0"  cellspacing="5" cellpadding="0" width="300" style="{$message_win}"><tr><td>
    <span class="job_messe">
    お疲れさまでした。
    </span>
    </td></tr></table>
    <br>
    EOM;
    hooter('login_view', '戻る');
    echo <<<EOM
    	<div align="center"><form method="POST" action="{$script}">
    	<input type="submit" value="TOPへ">
    	</form></div>
    EOM;
    exit;
}

