<?php
// このファイルは event.pl (Perl) から自動変換されたものです。
// 町CGI  Copyright (c) 2003-2004 brassiere  http://brassiere.jp/

// ###挨拶
// ##持ち物チェックサブルーチン

function event_happen(...$_args)
{
    global $a_randed, $aisatu_data, $aisatu_data_list, $aisatu_logfile, $bijutu, $byouki_sisuu, $eigo, $etti,
        $event_rand, $happend_ivent, $hassei_rand, $icon_hyouzi_a, $k_sousisan, $kensa_flag, $kokugo, $kuzi_gaku,
        $kuzi_hyouzi, $kuzi_rand, $love, $money, $myranded, $nouzeigaku, $ongaku, $rika, $speed, $suugaku, $syakai,
        $syou_rand, $taijuu;
    $hassei_rand = (pl_int(pl_rand(10)) + 1);
    if ((n($hassei_rand) == 1)) {
        // イベント発生の場合
        $event_rand = (pl_int(pl_rand(25)) + 1);
        // ver.1.30
        $happend_ivent .= <<<EOM
<span style="color:#000000">イベント発生！</span><br>
EOM;
        if ((n($event_rand) == 1)) {
            $syou_rand = (pl_int(pl_rand(10000)) + 1);
            if ((n($money) <= 0)) {
                $happend_ivent .= '<div class="purasu">●泥棒に入られましたが、盗むお金が無かったので捨てぜりふを残して立ち去りました。</div>';
            } else {
                if ((n($money) < n($syou_rand))) {
                    $syou_rand = $money;
                }
                $money = n($money) - n($syou_rand);
                $happend_ivent .= "<div class=\"mainasu\">●泥棒に入られました。{$syou_rand}円盗まれました。</div>";
            }
        }
        if ((n($event_rand) == 2)) {
            $syou_rand = (pl_int(pl_rand(10000)) + 1);
            $happend_ivent .= "<div class=\"purasu\">●道でお財布を拾いました。{$syou_rand}円入っていたのでやっぱりねこばばしました。</div>";
            $money = n($money) + n($syou_rand);
        }
        if ((n($event_rand) == 3)) {
            if (((pl_int(pl_rand(2)) + 1) == 1)) {
                $happend_ivent .= '<div class="purasu">●宣教師に出会い、親しげに話しかけられたうえ、英語を教えてくれました。英語力３アップ！</div>';
                $eigo = n($eigo) + 3;
                motimono_kensa('英文解釈教室');
                if ((n($kensa_flag) == 1)) {
                    $happend_ivent .= '<div class="purasu">●また、英文解釈教室を持っていたところを見つかり、さらに熱心に教えてくれました。英語力5アップ！</div>';
                    $eigo = n($eigo) + 5;
                }
            } else {
                $happend_ivent .= '<div class="mainasu">●英語の先生に見放されました。英語力３ダウン！</div>';
                $eigo = n($eigo) - 3;
            }
        }
        if ((n($event_rand) == 4)) {
            $happend_ivent .= '<div class="mainasu">●風邪を引いてしまいました。</div>';
            $byouki_sisuu = -15;
        }
        if ((n($event_rand) == 5)) {
            $happend_ivent .= '<div class="mainasu">●NHKの集金がやってきました。1000円払いました。</div>';
            $money = n($money) - 1000;
        }
        if ((n($event_rand) == 6)) {
            $happend_ivent .= '<div class="purasu">●失恋してやけ食いしました。体重が1kg増えました。</div>';
            $taijuu = n($taijuu) + 1;
        }
        if ((n($event_rand) == 7)) {
            if (((pl_int(pl_rand(2)) + 1) == 1)) {
                $happend_ivent .= '<div class="purasu">●文学に目覚めました。国語力３アップ！</div>';
                $kokugo = n($kokugo) + 3;
            } else {
                $happend_ivent .= '<div class="mainasu">●パソコンばかりして漢字を忘れてしまいました。国語力３ダウン！</div>';
                $kokugo = n($kokugo) - 3;
            }
        }
        if ((n($event_rand) == 8)) {
            if (((pl_int(pl_rand(2)) + 1) == 1)) {
                $happend_ivent .= '<div class="purasu">●数学パズルで遊びました。数学力３アップ！</div>';
                $suugaku = n($suugaku) + 3;
            } else {
                $happend_ivent .= '<div class="mainasu">●数字恐怖症になりました。数学力３ダウン！</div>';
                $suugaku = n($suugaku) - 3;
            }
        }
        if ((n($event_rand) == 9)) {
            if (((pl_int(pl_rand(2)) + 1) == 1)) {
                $happend_ivent .= '<div class="purasu">●テレビで科学の番組を見ました。理科の力が３アップ！</div>';
                $rika = n($rika) + 3;
            } else {
                $happend_ivent .= '<div class="mainasu">●理科の実験で試験管を割ってしまいました。理科の力が３ダウン！</div>';
                $rika = n($rika) - 3;
            }
        }
        if ((n($event_rand) == 10)) {
            if (((pl_int(pl_rand(2)) + 1) == 1)) {
                $happend_ivent .= '<div class="purasu">●歴史の本を読みました。社会の力が３アップ！</div>';
                $syakai = n($syakai) + 3;
            } else {
                $happend_ivent .= '<div class="mainasu">●社会への関心が引き潮が引くように無くなっていきました。社会の力が３ダウン！</div>';
                $syakai = n($syakai) - 3;
            }
        }
        if ((n($event_rand) == 11)) {
            if (((pl_int(pl_rand(2)) + 1) == 1)) {
                $happend_ivent .= '<div class="purasu">●ピアノの練習をしました。音楽の力が３アップ！</div>';
                $ongaku = n($ongaku) + 3;
            } else {
                $happend_ivent .= '<div class="mainasu">●飲み会で騒ぎすぎガラガラ声になりました。音楽の力が３ダウン！</div>';
                $ongaku = n($ongaku) - 3;
            }
            motimono_kensa('成り上がり');
            if ((n($kensa_flag) == 1)) {
                $happend_ivent .= '<div class="purasu">●「成り上がり」を読んで音楽へのやる気がわいてきました。音楽5アップ</div>';
                $ongaku = n($ongaku) + 3;
            }
        }
        if ((n($event_rand) == 12)) {
            if (((pl_int(pl_rand(2)) + 1) == 1)) {
                $happend_ivent .= '<div class="purasu">●芸術に目覚めました。美術の力が３アップ！</div>';
                $bijutu = n($bijutu) + 3;
            } else {
                $happend_ivent .= '<div class="mainasu">●美術の授業で居眠りをしてしまいました。美術の力が３ダウン！</div>';
                $bijutu = n($bijutu) - 3;
            }
        }
        if ((n($event_rand) == 13)) {
            $kuzi_rand = (pl_int(pl_rand(15)) + 1);
            $kuzi_gaku = (n($kuzi_rand) * 10000);
            $happend_ivent .= "<div class=\"purasu\">●ラッキーくじが当たりました！{$kuzi_rand}万円ゲット！</div>";
            $money = n($money) + n($kuzi_gaku);
        }
        if ((n($event_rand) == 14)) {
            $happend_ivent .= '<div class="purasu">●不思議に優しい気持ちにつつまれました。LOVE度が５アップ！</div>';
            $love = n($love) + 5;
        }
        if ((n($event_rand) == 15)) {
            $happend_ivent .= '<div class="mainasu">●裸で寝ていたら体調を崩したようです。</div>';
            $byouki_sisuu = -8;
        }
        if ((n($event_rand) == 16)) {
            if ((n($money) <= 0)) {
                $happend_ivent .= '<div class="mainasu">●スリが入りましたが、「ちっ、ここには金がねぇ。。」と言って出て行きました。</div>';
            } else {
                $happend_ivent .= '<div class="mainasu">●スリに遭いました。持ち金が半分になってしまいました。</div>';
                $money = pl_int((n($money) / 2));
            }
        }
        if ((n($event_rand) == 17)) {
            $myranded = (pl_int(pl_rand(2)) + 1);
            motimono_kensa('おばあちゃんの知恵袋');
            if ((n($kensa_flag) == 1)) {
                $happend_ivent .= '<div class="purasu">●おばあちゃんの知恵をまとめて出した本の印税が3000円入りました。</div>';
                $money = (n($money) + 3000);
            } elseif ((n($myranded) == 1)) {
                $happend_ivent .= '<div class="purasu">●出した本の印税が1000円入りました。</div>';
                $money = (n($money) + 1000);
            } else {
                $happend_ivent .= '<div class="purasu">●出した本の印税が500円入りました。</div>';
                $money = (n($money) + 500);
            }
        }
        if ((n($event_rand) == 18)) {
            $myranded = (pl_int(pl_rand(2)) + 1);
            motimono_kensa('フラフープ');
            if ((n($kensa_flag) == 1)) {
                $happend_ivent .= '<div class="purasu">●家にあったフラフープがハッピーな出来事を運んできたようです！家の前に777円落ちてました。</div>';
                $money = (n($money) + 777);
            } elseif ((n($myranded) == 1)) {
                $happend_ivent .= '<div class="purasu">●家の前に100円落ちてました。</div>';
                $money = (n($money) + 100);
            } else {
                $happend_ivent .= '<div class="mainasu">●前に財布をねこばばした事がばれて逆に500円払いました。</div>';
                $money = (n($money) - 500);
            }
        }
        if ((n($event_rand) == 19)) {
            motimono_kensa('谷保天満宮のお守り');
            if ((n($kensa_flag) == 1)) {
                $happend_ivent .= '<div class="purasu">●車にひかれかけましたが『谷保天満宮のお守り』が守ってくれ、さらに謝罪費として3万をもらうことができました。</div>';
                $money = (n($money) + 30000);
            } elseif ((n($speed) <= 180)) {
                $happend_ivent .= '<div class="mainasu">●車にひかれて軽いケガを負いました。入院費として3万円かかりました。</div>';
                $money = (n($money) - 30000);
            } else {
                $happend_ivent .= '<div class="purasu">●車にひかれかけましたが、軽いフットワークでかわしました。</div>';
            }
        }
        if ((n($event_rand) == 20)) {
            $myranded = (pl_int(pl_rand(2)) + 1);
            motimono_kensa('幸せを呼ぶ香水');
            if ((n($kensa_flag) == 1)) {
                $happend_ivent .= '<div class="purasu">●家にあった「幸せを呼ぶ香水」が持ち金を倍にしてくれました！</div>';
                $money = (n($money) * 2);
            } elseif ((n($myranded) == 1)) {
                $happend_ivent .= '<div class="purasu">●家の前に300円落ちてました。</div>';
                $money = (n($money) + 300);
            } else {
                $happend_ivent .= '<div class="mainasu">●パチンコで１万すってしまいました。。</div>';
                $money = (n($money) - 10000);
            }
        }
        if ((n($event_rand) == 21)) {
            $myranded = (pl_int(pl_rand(2)) + 1);
            motimono_kensa('バイアグラ');
            if ((n($kensa_flag) == 1)) {
                $happend_ivent .= '<div class="purasu">●家にあった「バイアグラ」を使ってLOVE度＆エッチ度３アップ！</div>';
                $love = n($love) + 3;
                $etti = n($etti) + 3;
            } elseif ((n($myranded) == 1)) {
                $happend_ivent .= '<div class="purasu">●友人がこっそりスッポンエキスを送ってきてくれました。エッチ度３アップ！</div>';
                $etti = n($etti) + 3;
            } else {
                $happend_ivent .= '<div class="mainasu">●下痢気味でエッチにも力が入りません。。エッチ度３ダウン。。</div>';
                $etti = n($etti) - 3;
            }
        }
        if ((n($event_rand) == 22)) {
            if (((pl_int(pl_rand(2)) + 1) == 1)) {
                $happend_ivent .= '<div class="mainasu">●地震が発生しました！！持ち金が３分の１になってしまいました。</div>';
                $money = pl_int((n($money) / 3));
            } else {
                $happend_ivent .= '<div class="purasu">●資産運用に成功して持ち金が倍になりました。</div>';
                $money = pl_int((n($money) * 2));
            }
        }
        // ver.1.30ここから
        if ((n($event_rand) == 23)) {
            $myranded = (pl_int(pl_rand(4)) + 1);
            motimono_kensa('ブラタンメンバーズカード');
            if ((n($kensa_flag) == 1)) {
                if ((n($myranded) == 1)) {
                    $happend_ivent .= '<div class="purasu">●「ブラタンメンバーズカード」特典♪　現金1万円が当たりました！</div>';
                    $money = n($money) + 10000;
                } elseif ((n($myranded) == 2)) {
                    $happend_ivent .= '<div class="purasu">●「ブラタンメンバーズカード」特典♪　現金10万円が当たりました！</div>';
                    $money = n($money) + 100000;
                } elseif ((n($myranded) == 3)) {
                    $happend_ivent .= '<div class="purasu">●「ブラタンメンバーズカード」特典♪　現金50万円が当たりました！</div>';
                    $money = n($money) + 500000;
                } elseif ((n($myranded) == 4)) {
                    $happend_ivent .= '<div class="purasu">●「ブラタンメンバーズカード」特典♪　現金100万円が当たりました！</div>';
                    $money = n($money) + 1000000;
                }
            } else {
                $nouzeigaku = pl_int((n($k_sousisan) * 0.01));
                if ((n($nouzeigaku) < 0)) {
                    $nouzeigaku = 0;
                }
                $money = n($money) - n($nouzeigaku);
                if ((n($nouzeigaku) == 0)) {
                    $happend_ivent .= '<div class="purasu">●税務署がやってきましたが、あまりに貧乏だったため納税を免れました。</div>';
                } else {
                    $nouzeigaku = pl_comma($nouzeigaku);
                    $happend_ivent .= "<div class=\"mainasu\">●税務署に総資産の１％である{$nouzeigaku}円を納税しました。</div>";
                }
            }
        }
        if ((n($event_rand) == 24)) {
            if (((pl_int(pl_rand(10)) + 1) == 1)) {
                $kuzi_rand = (pl_int(pl_rand(10)) + 1);
                $kuzi_gaku = (n($kuzi_rand) * 1000000);
                $kuzi_hyouzi = $kuzi_rand . '00';
                $happend_ivent .= "<div class=\"purasu\">●宝くじが当たりました！{$kuzi_hyouzi}万円ゲット！</div>";
                $money = n($money) + n($kuzi_gaku);
            } else {
                $happend_ivent .= '<div class="mainasu">●宝くじを3000円分購入しましたがやっぱり外れました。</div>';
                $money = n($money) - 3000;
            }
        }
        if ((n($event_rand) == 25)) {
            $happend_ivent .= '<div class="mainasu">●ゲームばかりしていてノイローゼになりました。体重が1kg減りました。</div>';
            $taijuu = n($taijuu) - 1;
        }
        // ver.1.30ここまで
        // アド表示
    } elseif ((n($hassei_rand) == 2)) {
        // 挨拶の場合
    } else {
        if (!(pl_open('IN', (string)($aisatu_logfile)))) { error("Open Error : {$aisatu_logfile}"); }
        $aisatu_data_list = pl_readlines('IN');
        pl_close('IN');
        pl_srand(n(((int)pl_time() ^ (int)n(getmypid()))));
        $a_randed = pl_rand(((pl_count($aisatu_data_list) - 1) + 1));
        $aisatu_data = pl_last(pl_splice($aisatu_data_list, n($a_randed), 1));
        [$a_num, $a_name, $a_date, $a_com, $a_syurui, $a_yobi2] = pl_pad(pl_split('/<>/u', $aisatu_data), 6);
        if (($a_num == '')) {
            $happend_ivent .= '';
        } else {
            if ($a_yobi2) {
                $icon_hyouzi_a = (string)($a_yobi2);
            } else {
                $icon_hyouzi_a = '';
            }
            $happend_ivent .= "{$icon_hyouzi_a}<span style=\"color:#ff6600\">{$a_name}さんの『{$a_syurui}』</span>：{$a_com} {$a_date}";
        }
    }
}

function aisatu(...$_args)
{
    global $_, $a_toukou, $aisatu_keyword, $aisatu_logfile, $aisatu_max, $aisatu_syurui, $date2, $i, $icon_hensuu,
        $icon_hyouzi, $icon_hyouzi_url, $in, $k_temp, $kounyuu, $message_in, $money, $my_log_file, $name_seikei,
        $new_all_data, $randed, $script;
    $all_data = [];
    $a_total_kizisuu = null;
    $top = null;
    if (($in['command'] == 'icon_touroku')) {
        $kounyuu = $in['my_icon'];
        temp_routin(...$_args);
        log_kousin($my_log_file, $k_temp);
        message('アイコンの設定をしました。', 'login_view');
        exit;
        // 投稿処理の場合
    } elseif (($in['command'] == 'aisatu_do')) {
        if ((pl_length($in['a_com']) > 120)) {
            error('挨拶は60字以内です');
        }
        if (($in['a_com'] == '')) {
            error('コメントが入力されていません');
        }
        lock(...$_args);
        // ログファイル更新
        // ログを読み込み
        if (!(pl_open('IN', (string)($aisatu_logfile)))) { error("Open Error : {$aisatu_logfile}"); }
        // 先頭行を取得
        $top = pl_readline('IN');
        $all_data = pl_readlines('IN');
        $a_total_kizisuu = pl_count($all_data);
        [$a_num, $a_name, $a_date, $a_com, $a_syurui, $a_yobi2] = pl_pad(pl_split('/<>/u', $top), 6);
        // a_yobi2 = アイコンのURL
        pl_close('IN');
        // タグ禁止処理
        $in['a_com'] = preg_replace('/</u', '&lt;', (string)$in['a_com'], -1);
        $in['a_com'] = preg_replace('/>/u', '&gt;', (string)$in['a_com'], -1);
        $in['a_com'] = preg_replace('/([^=^\\"]|^)(https?\\:[\\w\\.\\~\\-\\/\\?\\&\\+\\=\\:\\@\\%\\;\\#\\%]+)/u', '${1}<a href="${2}" target="_blank">${2}</a>', (string)$in['a_com'], -1);
        // ver.1.40ここから
        $name_seikei = $in['name'] . "<input type=\"hidden\" name=\"sousinsaki_name\" value=\"{$in['name']}\">";
        if ((((string)$name_seikei === (string)(string)($a_name)) && ((string)$in['a_com'] === (string)(string)($a_com)))) {
            error('二重投稿です');
        }
        // ver.1.40ここまで
        time_get(...$_args);
        // 更新配列を定義
        $a_num++;
        if ($kounyuu) {
            $icon_hensuu = "<img src=\"{$kounyuu}\" width=\"32\" height=\"32\" align=\"left\">";
        } else {
            $icon_hensuu = '';
        }
        $a_toukou = "{$a_num}<>{$in['name']}<input type=\"hidden\" name=\"sousinsaki_name\" value=\"{$in['name']}\"><>（{$date2}）<input type=\"submit\" value=\"メールする\" style=\"font-size:9px;border-style: solid; border-width: 0px;\"><>{$in['a_com']}<>{$in['a_syurui']}<>{$icon_hensuu}<>\n";
        $new_all_data = [];
        $new_all_data[0] = $a_toukou;
        $new_all_data[1] = $top;
        $i = 0;
        $__s1 = $_ ?? null;
        foreach ($all_data ?? [] as $_) {
            $i++;
            $new_all_data[] = $_;
            if ((n($i) >= (n($aisatu_max) - 2))) {
                break;
            }
        }
        $_ = $__s1;
        // ログを更新
        if (!(pl_open('OUT', ">{$aisatu_logfile}"))) { error("Write Error : {$aisatu_logfile}"); }
        pl_print('OUT', pl_join('', $new_all_data));
        pl_close('OUT');
        // ロック解除
        unlock(...$_args);
        // お金をゲット
        $randed = n($randed) + (pl_int(pl_rand(10)) + 1);
        if ((n($randed) == 7)) {
            $randed = n($randed) + (pl_int(pl_rand(10000)) + 5000);
            $money = n($money) + n($randed);
            $message_in = "ボーナスです！{$randed}円のお金をゲットしました。";
        } else {
            $randed = n($randed) + (pl_int(pl_rand(2000)) + 1000);
            $money = n($money) + n($randed);
            $message_in = "{$randed}円のお金を得ました。";
        }
        temp_routin(...$_args);
        log_kousin($my_log_file, $k_temp);
        message("投稿しました。{$message_in}", 'login_view');
        exit;
    }
    // 投稿の場合の閉じ
    $__s2 = $_ ?? null;
    foreach ($aisatu_keyword ?? [] as $_) {
        $aisatu_syurui .= "<option value=\"{$_}\">{$_}</option>\n";
    }
    $_ = $__s2;
    if ($kounyuu) {
        $icon_hyouzi_url = (string)($kounyuu);
        $icon_hyouzi = "<img src=\"{$kounyuu}\" width=\"32\" height=\"32\">";
    } else {
        $icon_hyouzi_url = 'http://';
        $icon_hyouzi = '';
    }
    t_header('');
    echo <<<EOM
    	<form method="POST" action="{$script}">
    	<input type="hidden" name="mode" value="aisatu">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="command" value="aisatu_do">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<br><br><table  width="550" border="0" cellspacing="0" cellpadding="15" align="center" class="yosumi">
    	<tr><td bgcolor="#ffffcc">
    	<div align="center"><select name="a_syurui">
    	{$aisatu_syurui}
    	</select><br><br>
    	<input type="text" name="a_com" size="80"></div>
    	<br>※ここで投稿したコメントはメッセージ窓にランダムに表示されます。文字数は60字以内。タグ禁止です。<br><br><div align="center">
    	<input type="submit" value=" O K "></div>
      </td>
    </tr>
    </table>
    	</form>
    
    	<form method="POST" action="{$script}">
    	<input type="hidden" name="mode" value="aisatu">
    	<input type="hidden" name="name" value="{$in['name']}">
    	<input type="hidden" name="pass" value="{$in['pass']}">
    	<input type="hidden" name="command" value="icon_touroku">
    	<input type="hidden" name="town_no" value="{$in['town_no']}">
    	<br><br><table width="550" border="0" cellspacing="0" cellpadding="15" align="center" class="yosumi">
    	<tr><td>
    	<div class="honbun2">○自分のアイコン画像設定</div>
    	{$icon_hyouzi}<br>
    	※こちらで自分のアイコン画像を設定することができます。http://～で始まる絶対URLにて指定してください。サイズは32×32限定です。<br>
    	画像のURL <input type="text" name="my_icon" size="60" value="{$icon_hyouzi_url}"></div>
    	<br>※設定したアイコンは、挨拶ウインドウやストリートファイトで表示されます。<br><br><div align="center">
    	<input type="submit" value=" O K "></div>
      </td>
    </tr>
    </table>
    	</form>
    EOM;
    hooter('login_view', '戻る');
    exit;
}

function motimono_kensa(...$_args)
{
    global $check_item, $k_id, $kensa_flag, $monokiroku_file, $my_kounyuu_list, $one_kounyuu_item, $syo_hinmoku,
        $syo_taikyuu;
    // ver.1.40
    $monokiroku_file = "./member/{$k_id}/mono.cgi";
    if (!(pl_exists($monokiroku_file))) {
        if (!(pl_open('MOF', ">{$monokiroku_file}"))) { error('自分の購入物ファイルを作成できません'); }
    }
    pl_close('MOF');
    if (!(pl_open('MK', (string)($monokiroku_file)))) { error('自分の購入物ファイルが開けません'); }
    $my_kounyuu_list = pl_readlines('MK');
    pl_close('MK');
    $kensa_flag = 0;
    foreach ($my_kounyuu_list ?? [] as $one_kounyuu_item) {
        syouhin_sprit($one_kounyuu_item);
        if ((n($syo_taikyuu) <= 0)) {
            continue;
        }
        foreach ($_args ?? [] as $check_item) {
            if (((string)$check_item === (string)(string)($syo_hinmoku))) {
                $kensa_flag = 1;
                break;
            }
        }
    }
}

