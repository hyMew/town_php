<?php
// このファイルは town_maker.cgi (Perl) から自動変換されたものです。
// 町CGI  Copyright (c) 2003-2004 brassiere  http://brassiere.jp/

require_once __DIR__ . '/lib/bootstrap.php';
// +++++++++++++++++++++++++++++++++++++
// Copyright (c) 2003-2004 brassiere
$version = 'TOWN ver.1.40';
// web：http://brassiere.jp/
// mail：shohei@brassiere.jp
// このプログラムによって起きた事に責任を負いません。
// +++++++++++++++++++++++++++++++++++++
// ※このプログラムはフリーウエアですが、ゲーム内で時々「テキスト広告」が表示されます。また、予想以上に労力と時間がかかってしまったため、任意の使用料金のお支払いを歓迎しています。実際に設置し、運用した上で料金を支払うだけの価値があると思われた方は、適当と思われる額を支払っていただけるととても嬉しいです。今後の開発を維持していくために応援いただけると非常に助かります
// もちろん支払い義務はありませんし、機能制限もありません。また料金を支払っていただいた方とそうでない方で区別することはありません。支払わないのが普通だと思ってますので、恨むこともありません（笑。それでも料金を支払って下さるという方は、下記振込先まで送金をお願いいたします。
// ＜任意の料金支払先＞
// みずほ銀行　集中第一支店　普通預金
// 支店番号：822
// 口座番号：9502477
// 受取人名：イーバンクギンコウ(カ
// ※料金の目安（あくまで参考で、これ以下でもこれ以上でも構いませんｗ）
// ○満足した＝500円
// ○ものすごく満足した＝1000円
// ○感動した！素晴らしい！＝1500円
// ＜重要＞　このゲームは画像面積が大きく、さらに頻繁に読み込みをするため、特にトラフィック容量において注意を要します（当サイトでの稼働実績ではまったく制約をしない場合、10日で10Ｇ強のトラフィック量となっています）。設置の際はそのサーバーで定められたトラフィック容量を確認した上で「街の移動時間」および「行動の制限時間」をなるべく長く取り、常にトラフィック量の推移をチェックすることを強くお勧めします。また、サーバーを複数お持ちであれば画像のみ別サーバーに置き（初期設定で画像フォルダーの指定をする）、トラフィック量を分散させることも有効です。
require_once __DIR__ . '/top.php';
require_once __DIR__ . '/town_ini.php';
require_once __DIR__ . '/command.php';
require_once __DIR__ . '/event.php';
require_once __DIR__ . '/town_lib.php';
// ver.1.4
require_once __DIR__ . '/unit.php';
// ver.1.4
decode();
// 指定ホストアクセス拒否
// ホスト名を取得
$get_host = pl_get_host();
$get_addr = $_SERVER['REMOTE_ADDR'];
if (($get_host == '')) {
    error('恐れ入りますがホストが取得できない環境ではアクセスできません');
}
$__s1 = $_ ?? null;
foreach ($deny ?? [] as $__k1 => $_) {
    try {
        if (($_ == '')) {
            continue;
        }
        $_ = preg_replace('/\\*/u', '.*', (string)$_, -1);
        if (pl_match('/' . pl_redelim($_) . '/iu', $get_host)) {
            error('恐れ入りますがご利用中のホストからはアクセスできません');
        }
    } finally { $deny[$__k1] = $_; }
}
$_ = $__s1;
// メンテチェック
if ((((n($mente_flag) == 1) && ($in['admin_pass'] == '')) && ($in['mode'] != ''))) {
    error((string)($mente_message));
}
// ver.1.2
$seigenyou_now_time = pl_time();
// 「更新」ボタン（画面の再表示）は行動とみなさない
$is_reload = (($in['mode'] ?? '') === 'login_view' && ($in['command'] ?? '') === 'reload');
// 制限時間チェック
$ato_nanbyou = (n($koudou_seigen) - ((n($seigenyou_now_time) - n($access_byou))));
if (!$is_reload && ((n($seigenyou_now_time) - n($access_byou)) < n($koudou_seigen))) {
    error("まだ行動できません。あと{$ato_nanbyou}秒お待ちください。");
}
// ver.1.40ここから
// パスワードログ作成
if (!(pl_exists($pass_logfile))) {
    if (!(pl_open('LOGF', (string)($logfile)))) { error("Open Error : {$logfile}"); }
    $pass_sakuse = pl_readlines('LOGF');
    pl_close('LOGF');
    $__s2 = $_ ?? null;
    foreach ($pass_sakuse ?? [] as $_) {
        list_sprit($_);
        $henkan_temp = "{$list_k_id}<>{$list_name}<>{$list_pass}<>\n";
        $henkan_pass[] = $henkan_temp;
    }
    $_ = $__s2;
    $henkan_pass = pl_sort('by_r_number', $henkan_pass);
    if (!(pl_open('PSS', ">{$pass_logfile}"))) { error("Write Error : {$pass_logfile}"); }
    pl_print('PSS', pl_join('', $henkan_pass));
    true;
    pl_close('PSS');
}
// 条件分岐
if (($in['mode'] == 'login_view')) {
    login_view();
}
if (($in['mode'] == 'orosi')) {
    orosi();
}
if (($in['mode'] == 'buy_orosi')) {
    buy_orosi();
}
if (($in['mode'] == 'gym')) {
    gym();
}
if (($in['mode'] == 'training')) {
    training();
}
if (($in['mode'] == 'syokudou')) {
    syokudou();
}
if (($in['mode'] == 'syokuzisuru')) {
    syokuzisuru();
}
if (($in['mode'] == 'school')) {
    school();
}
if (($in['mode'] == 'do_school')) {
    do_school();
}
if (($in['mode'] == 'depart_gamen')) {
    depart_gamen();
}
if (($in['mode'] == 'buy_syouhin')) {
    buy_syouhin();
}
if (($in['mode'] == 'kentiku')) {
    kentiku();
}
if (($in['mode'] == 'kentiku_do')) {
    kentiku_do();
}
if (($in['mode'] == 'aisatu')) {
    aisatu();
}
if (($in['mode'] == 'mail_sousin')) {
    mail_sousin();
}
if (($in['mode'] == 'mail_do')) {
    mail_do();
}
// ver.1.40ここまで
main_view($in['town_no']);
exit;
// ##################サブルーチン
// ログイン画面
// トップ画面右部分
// 街情報窓の出力
// コマンドボタンの出力
// 個人パラメータ出力
// ##ログバックアップ処理

function joukenbunki(...$_args)
{
}

function by_r_number(...$_args)
{
    global $a, $b;
    return (n($b) <=> n($a));
}

function login_view(...$_args)
{
    global $_, $access_time, $bank, $date, $in, $k_id, $k_sousisan, $loan_kaisuu, $loan_nitigaku, $money,
        $monokiroku_file, $mycar_hairetu, $new_mycar_hairetu, $super_teiki, $syo_hinmoku, $syo_taikyuu, $syo_temp,
        $taiha_comment, $ziko_sya_flg;
    // 日付けが変わっていた場合のイベント
    time_get(...$_args);
    $hutuu_risoku = null;
    $teiki_risoku = null;
    if (((string)$access_time !== (string)(string)($date))) {
        $access_time = $date;
        // 預金の利息計算
        $hutuu_risoku = pl_int((n($bank) * 0.005));
        if ((n($hutuu_risoku) > 0)) {
            $bank = n($bank) + n($hutuu_risoku);
            kityou_syori('普通預金利息', '', $hutuu_risoku, $bank, '普');
        }
        $teiki_risoku = pl_int((n($super_teiki) * 0.01));
        if ((n($teiki_risoku) > 0)) {
            $super_teiki = n($super_teiki) + n($teiki_risoku);
            kityou_syori('スーパー定期利息', '', $teiki_risoku, $super_teiki, '定');
        }
        // 住宅ローン引き落とし
        if ((n($loan_kaisuu) > 0)) {
            $bank = n($bank) - n($loan_nitigaku);
            $loan_kaisuu--;
            kityou_syori('住宅ローン支払い', (string)($loan_nitigaku), '', $bank, '普');
        }
        // 子供からの仕送り処理
        kodomo_siokuri(...$_args);
    }
    // 事故だった場合
    if ((($in['ziko_flag'] == 'on') && ($in['ziko_idousyudan'] != '徒歩'))) {
        $monokiroku_file = "./member/{$k_id}/mono.cgi";
        if (!(pl_open('CAR', (string)($monokiroku_file)))) { error("Open Error : {$monokiroku_file}"); }
        $mycar_hairetu = pl_readlines('CAR');
        pl_close('CAR');
        $ziko_sya_flg = 0;
        // ver.1.40ここから
        $__s1 = $_ ?? null;
        foreach ($mycar_hairetu ?? [] as $_) {
            syouhin_sprit($_);
            if (((string)$in['ziko_idousyudan'] === (string)(string)($syo_hinmoku))) {
                if ((n($ziko_sya_flg) == 0)) {
                    $syo_taikyuu--;
                    if ((n($syo_taikyuu) <= 0)) {
                        $taiha_comment = "{$in['ziko_idousyudan']}は大破しました。";
                        $ziko_sya_flg = 1;
                        continue;
                    } else {
                        $taiha_comment = "残りの耐久（{$syo_taikyuu}）";
                    }
                    $ziko_sya_flg = 1;
                }
            }
            // ver.1.40ここまで
            syouhin_temp(...$_args);
            $new_mycar_hairetu[] = $syo_temp;
        }
        $_ = $__s1;
        // 自分の所有物ファイルを更新
        lock(...$_args);
        if (!(pl_open('OUT', ">{$monokiroku_file}"))) { error("Write Error : {$monokiroku_file}"); }
        pl_print('OUT', pl_join('', $new_mycar_hairetu));
        pl_close('OUT');
        unlock(...$_args);
        message("<span style=\"color:#ff6600\">交通事故を起こしてしまいました！<br>「{$in['ziko_idousyudan']}」の耐久度が１減ります。<br>{$taiha_comment}</span>", 'login_view');
    }
    // 事故だった場合閉じ
    event_happen(...$_args);
    $k_sousisan = (((n($money) + n($bank)) + n($super_teiki)) - ((n($loan_nitigaku) * n($loan_kaisuu))));
    main_view((string)($in['town_no']));
    exit;
}

function top_gamen(...$_args)
{
    global $ck, $date, $date_sec, $douzi_login_ninzuu, $genzaino_ninzuu, $logfile, $maintown_logfile, $maintown_para,
        $master_ad, $message_win, $mt_departflag, $mt_hanei, $mt_keizai, $mt_orosiflag, $mt_syokudouflag, $mt_t_time,
        $mt_temp, $mt_today, $mt_y_time, $mt_yobi8, $new_touroku_per, $osirase, $rankingMember, $saidai_ninzuu,
        $sanka_hyouzi_kinou, $sankasyasuu, $script, $tajuucomment, $tajuukinsi_deny, $tajuukinsi_flag, $title;
    if (!(pl_open('IN', (string)($maintown_logfile)))) { error("Open Error : {$maintown_logfile}"); }
    $maintown_para = pl_readline('IN');
    if (($maintown_para == '')) {
        error("{$maintown_logfile}に問題があります。お手数ですが管理人（{$master_ad}）までご連絡ください。");
    }
    // 空ログチェック		ver.1.22
    main_town_sprit($maintown_para);
    pl_close('IN');
    time_get(...$_args);
    // 日付が変わっていたらメインタウンログを今日の日付に更新、卸時刻、明日の卸時刻を更新、卸フラグと食堂フラグを0にする
    if (((string)$date !== (string)$mt_today)) {
        $mt_today = $date;
        $mt_t_time = $mt_y_time;
        $mt_y_time = (pl_int(pl_rand(20)) + 1);
        // ver.1.3
        $mt_orosiflag = 0;
        $mt_syokudouflag = 0;
        $mt_departflag = 0;
        main_town_temp(...$_args);
        lock(...$_args);
        if (!(pl_open('OUT', ">{$maintown_logfile}"))) { error("Write Error : {$maintown_logfile}"); }
        pl_print('OUT', $mt_temp);
        pl_close('OUT');
        list_log_backup(...$_args);
        unlock(...$_args);
    }
    if (!(pl_open('IN', (string)($logfile)))) { error("Open Error : {$logfile}"); }
    $rankingMember = pl_readlines('IN');
    $sankasyasuu = pl_count($rankingMember);
    pl_close('IN');
    $mt_keizai_hyouzi = pl_int((n($mt_keizai) / ((pl_int((((n($date_sec) - n($mt_yobi8))) / (((60 * 60) * 24)))) + 1))));
    $mt_henei_hyouzi = pl_int((n($mt_hanei) / ((pl_int((((n($date_sec) - n($mt_yobi8))) / (((60 * 60) * 24)))) + 1))));
    if ((n($tajuukinsi_flag) == 1)) {
        $tajuucomment = '<br>※多重登録は禁止です。';
    }
    if ((n($tajuukinsi_deny) == 1)) {
        $tajuucomment .= '多重登録が発覚した時点でログインできなくなりますのでご注意ください。';
    }
    echo <<<EOM
    <div class="tw-card">
      <h1 class="tw-top-title">{$title}</h1>
      <div class="tw-stats" style="justify-content:center;margin-top:6px">
        <span>人口 <strong>{$sankasyasuu}人</strong></span>
        <span onMouseOver='onMes5("住民のお店の平均１日売り上げ額（街全体）")' onMouseOut='onMes5("")'>経済力 <strong>{$mt_keizai_hyouzi}円</strong></span>
        <span onMouseOver='onMes5("掲示板の平均１日書き込み数（街全体）")' onMouseOut='onMes5("")'>繁栄度 <strong>{$mt_henei_hyouzi}</strong></span>
      </div>
    </div>
    <div class="tw-card tw-osirase">{$osirase}</div>
    EOM;
    if ((n($genzaino_ninzuu) >= n($douzi_login_ninzuu))) {
        echo <<<EOM
        <div class="tw-card tw-notice">現在、同時ログイン制限{$douzi_login_ninzuu}人を超えています。恐れ入りますが、しばらくしてからログインしてください。</div>
        EOM;
    } else {
        echo <<<EOM
        <form method="POST" action="{$script}" class="tw-card">
        <input type="hidden" name="mode" value="login_view">
        <h2 class="tw-card-title">ログイン（登録済みの方）</h2>
        <div class="tw-field"><label for="login_name">お名前</label><input type="text" id="login_name" name="name" value="{$ck['name']}" autocomplete="username"></div>
        <div class="tw-field"><label for="login_pass">パスワード</label><input type="password" id="login_pass" name="pass" value="" autocomplete="current-password"></div>
        EOM;
        if ((n($sanka_hyouzi_kinou) == 1)) {
            echo <<<EOM
            <div class="tw-field"><span>参加者一覧に名前を</span>
            <div class="tw-choice"><label><input type="radio" name="sanka_hyouzi_on" value="off"> 表示させない</label><label><input type="radio" name="sanka_hyouzi_on" value="on" checked> 表示させる</label></div></div>
            EOM;
        }
        echo <<<EOM
        <input type="submit" class="tw-submit" value="ログイン">
        </form>
        EOM;
        if ((n($sankasyasuu) >= n($saidai_ninzuu))) {
            echo '<div class="tw-card tw-notice">※現在、最大登録人数に達していますので新規登録はできません。</div>';
        } elseif ((n($new_touroku_per) == 1)) {
            echo '<div class="tw-card tw-notice">※現在、新規登録を中止しています。</div>';
        } else {
            echo <<<EOM
            <form method="POST" action="game.php" class="tw-card">
            <input type="hidden" name="mode" value="new">
            <h2 class="tw-card-title">新規登録</h2>
            <p class="tw-sub" style="margin:0 0 10px">最大登録人数：{$saidai_ninzuu}人{$tajuucomment}</p>
            <div class="tw-field"><label for="new_name">お名前</label><input type="text" id="new_name" name="name" autocomplete="off"></div>
            <div class="tw-field"><label for="new_pass">パスワード</label><input type="password" id="new_pass" name="pass" autocomplete="new-password"></div>
            <div class="tw-field"><span>性別</span>
            <div class="tw-choice"><label><input type="radio" name="sex" value="m"> 男</label><label><input type="radio" name="sex" value="f"> 女</label></div></div>
            <input type="submit" class="tw-submit" value="登録する">
            </form>
            EOM;
        }
    }
    // 制限人数を超えていない場合の閉じ
    echo <<<EOM
    <form method="POST" action="admin.php" class="tw-card tw-admin">
    <input type="hidden" name="mode" value="admin">
    <label for="admin_pass" class="tw-sub">管理者パスワード</label>
    <input type="password" id="admin_pass" name="admin_pass" autocomplete="off"> <input type="submit" value="管理者メニュー">
    </form>
    EOM;
}

function town_jouhou(...$_args)
{
    global $date_sec, $hanei, $hanei_hyouzi, $keizai, $keizai_hyouzi, $t_yobi2, $title, $town_name,
        $town_tika_hairetu;
    $keizai_hyouzi = pl_int((n($keizai) / ((pl_int((((n($date_sec) - n($t_yobi2))) / (((60 * 60) * 24)))) + 1))));
    $hanei_hyouzi = pl_int((n($hanei) / ((pl_int((((n($date_sec) - n($t_yobi2))) / (((60 * 60) * 24)))) + 1))));
    echo <<<EOM
    <details class="tw-card tw-fold tw-townhead" data-fold="town" open>
    <summary class="tw-fold-title"><span class="tw-townname"><small>「{$title}」内</small><strong>{$town_name}</strong></span></summary>
    <div class="tw-stats"><span>地価 <strong>{$town_tika_hairetu[n($_args[0])]}万</strong></span><span onMouseOver='onMes5("この街の住民のお店の平均１日売り上げ額")' onMouseOut='onMes5("")'>経済力 <strong>{$keizai_hyouzi}円</strong></span><span onMouseOver='onMes5("この街にある掲示板の平均１日書き込み数")' onMouseOut='onMes5("")'>繁栄度 <strong>{$hanei_hyouzi}</strong></span></div>
    </details>
    EOM;
}

function command_botan(...$_args)
{
    global $house_name, $work_seigen_time;
    global $_, $botan_narabi_suu, $botanyou_kouka_check, $botanyou_mono_check, $condition, $condition_name, $deleteUser, $house_type,
        $img_dir, $in, $job, $job_kaisuu, $job_keiken, $k_id, $kaigyou_flag, $love, $mono_kouka_keys,
        $mono_name_keys, $monokiroku_file, $my_kounyuu_list, $need_love, $renai_system_on, $script, $syo_hinmoku,
        $syo_kouka, $syo_taikyuu, $top_botan, $unit;
    // ver.1.3	恋愛ボタン、配偶者家設定ボタン追加、子育てボタン追加、いとしき我が家ボタン削除、ボタンの並び数変更
    // 購入物ファイルを開く
    $monokiroku_file = "./member/{$k_id}/mono.cgi";
    if (!(pl_exists($monokiroku_file))) {
        if (!(pl_open('MOF', ">{$monokiroku_file}"))) { error('自分の購入物ファイルを作成できません'); }
        pl_close('MOF');
    }
    if (!(pl_open('MK', (string)($monokiroku_file)))) { error('自分の購入物ファイルが開けません'); }
    $my_kounyuu_list = pl_readlines('MK');
    $mono_name_keys = [];
    $mono_kouka_keys = [];
    $__s1 = $_ ?? null;
    foreach ($my_kounyuu_list ?? [] as $_) {
        syouhin_sprit($_);
        if ((n($syo_taikyuu) <= 0)) {
            continue;
        }
        $mono_name_keys[] = $syo_hinmoku;
        $mono_kouka_keys[] = $syo_kouka;
    }
    $_ = $__s1;
    $botanyou_mono_check = pl_join('<>', $mono_name_keys);
    $botanyou_kouka_check = pl_join('<>', $mono_kouka_keys);
    pl_close('MK');
    // コマンドボタン（アイコン + 名前。スマホではマウスオーバーの説明が出ないため名前を表示）
    $hidden = "<input type=\"hidden\" name=\"name\" value=\"{$in['name']}\"><input type=\"hidden\" name=\"pass\" value=\"{$in['pass']}\"><input type=\"hidden\" name=\"k_id\" value=\"{$k_id}\"><input type=\"hidden\" name=\"town_no\" value=\"{$in['town_no']}\">";
    $btn = function (string $action, string $mode, string $img, string $label, string $help, string $extra = '', string $btnAttr = '') use ($hidden, $img_dir): string {
        $help = htmlspecialchars($help, ENT_QUOTES, 'UTF-8');
        return "<form method=\"POST\" action=\"{$action}\"><input type=\"hidden\" name=\"mode\" value=\"{$mode}\">{$extra}{$hidden}"
            . "<button type=\"submit\" title=\"{$help}\" onMouseOver='onMes5(this.title)' onMouseOut='onMes5(\"\")'{$btnAttr}><img src=\"{$img_dir}/{$img}\" width=\"32\" height=\"32\" alt=\"\">{$label}</button></form>";
    };
    $top_botan = '';
    $top_botan .= $btn($script, 'login_view', 'reload.gif', '更新', '画面を更新します。「パワー」は休息後、このボタンを押すことで徐々に増えます。（行動制限の対象外）', '<input type="hidden" name="command" value="reload">');
    if (($job != '学生')) {
        // 前回の仕事から $work_seigen_time 分たつまでは押せない（サーバー側でも basic.php で確認）
        $work_wait = (60 * n($work_seigen_time)) - (pl_time() - n($house_name));
        if ($work_wait > 0) {
            $wait_min = (int)ceil($work_wait / 60);
            $top_botan .= $btn('basic.php', 'do_work', 'go_work.gif', "仕事<span class=\"tw-wait\" data-wait=\"{$work_wait}\">あと{$wait_min}分</span>", "仕事できる間隔は{$work_seigen_time}分です。あと{$wait_min}分お待ちください。", "<input type=\"hidden\" name=\"cond\" value=\"{$condition_name}\">", ' disabled class="tw-work"');
        } else {
            $top_botan .= $btn('basic.php', 'do_work', 'go_work.gif', '仕事', "仕事に出かけます。現在、経験値：{$job_keiken}　勤務数：{$job_kaisuu}回", "<input type=\"hidden\" name=\"cond\" value=\"{$condition_name}\">");
        }
    }
    $top_botan .= $btn('basic.php', 'item', 'item.gif', 'アイテム', 'アイテムを使用します。');
    $top_botan .= $btn($script, 'aisatu', 'aisatu.gif', 'あいさつ', '今日の出来事や気分などあいさつをしましょう。');
    $top_botan .= $btn($script, 'mail_sousin', 'mail.gif', 'メール', '街の住人あてにメッセージを送信することができます。');
    $top_botan .= $btn('game.php', 'doukyo', 'doukyo.gif', 'キャラ', '自分のキャラクターを作成し育てていきます。');
    $top_botan .= $btn('game.php', 'battle', 'battle.gif', 'バトル', 'ストリートファイトに出かけます。');
    if (($unit[$k_id] != '')) {
        $top_botan .= $btn('original_house.php', 'my_house_settei', 'my_housein.gif', '家の設定', '自分の家に関する各種設定を行えます。', "<input type=\"hidden\" name=\"iesettei_id\" value=\"{$k_id}\">");
    }
    if (($unit[$house_type] != '')) {
        $top_botan .= $btn('original_house.php', 'my_house_settei', 'my_housein2.gif', '配偶者の家', '配偶者の家に関する各種設定を行えます。', "<input type=\"hidden\" name=\"iesettei_id\" value=\"{$house_type}\">");
    }
    if (((n($love) >= n($need_love)) && (n($renai_system_on) == 1))) {
        $top_botan .= $btn('kekkon.php', 'renai', 'renai.gif', '恋愛', '恋人とデートをしたり、プロポーズしたりできます。');
        $top_botan .= $btn('kekkon.php', 'kosodate', 'kosodate.gif', '子育て', '子育てをします。');
    }
    $top_botan .= $btn('game.php', 'data_hozon', 'off.gif', '保存・終了', "データ保存します。最後に保存した食事時間が{$deleteUser}日を超えるとユーザー削除されます。");
    echo "<div class=\"tw-cmds\">{$top_botan}</div>";
    // 待ち時間が過ぎたら「仕事」ボタンを押せるようにする
    echo <<<'EOM'
    <script>
    (function () {
      var b = document.querySelector('button.tw-work');
      if (!b) return;
      var s = b.querySelector('.tw-wait'), left = parseInt(s.getAttribute('data-wait'), 10);
      var t = setInterval(function () {
        left--;
        if (left <= 0) { clearInterval(t); b.disabled = false; s.remove(); b.title = '仕事に出かけます。'; return; }
        s.textContent = 'あと' + Math.ceil(left / 60) + '分';
      }, 1000);
    })();
    </script>
    EOM;
}

function loged_gamen(...$_args)
{
    global $BMI, $_, $access_byou, $all_couple, $ato_nanbyou, $bh_full_date, $bijutu, $byouki_sisuu, $byoumei,
        $cn_joutai, $cn_kodomo, $cn_name1, $cn_name2, $cn_number, $cn_omoide1, $cn_omoide2, $cn_omoide3, $cn_omoide4,
        $cn_omoide5, $cn_total_aijou, $cn_yobi1, $cn_yobi2, $cn_yobi3, $cn_yobi4, $cn_yobi5, $condition, $condition_name,
        $condition_sisuu, $couple_file, $deleteUser, $eigo, $ener_parcent, $energy, $energy_bar, $energy_max, $etti,
        $get_host, $happend_ivent, $host, $img_dir, $in, $job, $job_kaisuu, $job_keiken, $job_level, $k_id,
        $k_sousisan, $k_temp, $kenkou, $kokugo, $koudou_seigen, $is_reload, $kuuhukudo, $kyakuryoku, $lastCheckTime,
        $lastMailTime, $lastMailTime_list, $last_access_byou, $last_ene_time, $last_nouene_time, $last_syokuzi,
        $last_syokuzi_henkan, $looks, $love, $m_byou, $m_syubetu, $manpuku_time, $message_file, $message_window,
        $mise_type, $money, $my_haiguusya, $my_koibito, $my_kounyuu_list, $my_log_file, $name, $next_levelup,
        $nokori_parcent, $nou_ener_parcent, $nou_energy, $nou_energy_bar, $nou_energy_max, $nou_nokori_parcent,
        $nouryoku_bar, $nouryoku_goukeiti, $nouryoku_suuzi_hairetu, $ongaku, $onsen_times, $power, $rika,
        $saigono_kita_mail, $saikoroprint, $script, $seigen_width, $seigenyou_now_time, $sintai_kaihuku, $sintyou,
        $speed, $st_win_back, $st_win_wak, $suugaku, $syakai, $syo_hinmoku, $syo_taikyuu, $syokuzi_kankaku,
        $tabetenaizikan, $taijuu, $taikei, $tairyoku, $tokubetu_times, $unique, $wanryoku, $zunou_kaihuku;
    // パワーのMAX値計算
    $energy_max = pl_int(((((((((n($looks) / 12)) + ((n($tairyoku) / 4))) + ((n($kenkou) / 4))) + ((n($speed) / 8))) + ((n($power) / 8))) + ((n($wanryoku) / 8))) + ((n($kyakuryoku) / 8))));
    $nou_energy_max = pl_int(((((((((n($kokugo) / 6)) + ((n($suugaku) / 6))) + ((n($rika) / 6))) + ((n($syakai) / 6))) + ((n($eigo) / 6))) + ((n($ongaku) / 6))) + ((n($bijutu) / 6))));
    $date_sec = pl_idx([pl_time()], 0);
    // 身体パワー計算		#ver.1.3
    if (($in['iiyudane'] == 'one')) {
        $energy = (n($energy) + pl_int(((((n($date_sec) - n($last_ene_time))) / n($sintai_kaihuku)) * n($onsen_times))));
    } elseif (($in['iiyudane'] == 'two')) {
        $energy = (n($energy) + pl_int(((((n($date_sec) - n($last_ene_time))) / n($sintai_kaihuku)) * n($tokubetu_times))));
    } else {
        $energy = (n($energy) + pl_int((((n($date_sec) - n($last_ene_time))) / n($sintai_kaihuku))));
    }
    $last_ene_time = $date_sec;
    if ((n($energy) > n($energy_max))) {
        $energy = $energy_max;
    }
    if ((n($energy) < 0)) {
        $energy = 0;
    }
    // 頭脳パワー計算		#ver.1.3
    if (($in['iiyudane'] == 'one')) {
        $nou_energy = (n($nou_energy) + pl_int(((((n($date_sec) - n($last_nouene_time))) / n($zunou_kaihuku)) * n($onsen_times))));
    } elseif (($in['iiyudane'] == 'two')) {
        $nou_energy = (n($nou_energy) + pl_int(((((n($date_sec) - n($last_nouene_time))) / n($zunou_kaihuku)) * n($tokubetu_times))));
    } else {
        $nou_energy = (n($nou_energy) + pl_int((((n($date_sec) - n($last_nouene_time))) / n($zunou_kaihuku))));
    }
    $last_nouene_time = $date_sec;
    if ((n($nou_energy) > n($nou_energy_max))) {
        $nou_energy = $nou_energy_max;
    }
    if ((n($nou_energy) < 0)) {
        $nou_energy = 0;
    }
    // newメールのチェック
    $message_file = "./member/{$k_id}/mail.cgi";
    if (!(pl_open('MS', (string)($message_file)))) { error('自分のメールログファイルが開けません'); }
    $lastCheckTime = pl_readline('MS');
    // 最終メール時間の取得
    $lastMailTime_list = pl_readlines('MS');
    // メールデータ配列
    pl_close('MS');
    $__s1 = $_ ?? null;
    foreach ($lastMailTime_list ?? [] as $_) {
        mail_sprit($_);
        if (($m_syubetu != '送信')) {
            $saigono_kita_mail = $m_byou;
            break;
        }
    }
    $_ = $__s1;
    $lastMailTime_list = pl_split('/<>/u', $lastMailTime);
    if ((n($lastCheckTime) < n($saigono_kita_mail))) {
        $happend_ivent .= '<div style="color:#ff3300">★受信箱に新しいメッセージが届いています！</div>';
    }
    // 身体パワーバー（％）算出
    if (!($energy_max)) {
        $energy_max = 1;
    }
    $ener_parcent = pl_int(((n($energy) / n($energy_max)) * 100));
    $nokori_parcent = (100 - n($ener_parcent));
    if ((n($ener_parcent) > 59)) {
        $energy_bar = 'energy_ao.gif';
    } elseif ((n($ener_parcent) > 19)) {
        $energy_bar = 'energy_ki.gif';
    } else {
        $energy_bar = 'energy_aka.gif';
    }
    // 頭脳パワーバー（％）算出
    if (!($nou_energy_max)) {
        $nou_energy_max = 1;
    }
    $nou_ener_parcent = pl_int(((n($nou_energy) / n($nou_energy_max)) * 100));
    $nou_nokori_parcent = (100 - n($nou_ener_parcent));
    if ((n($nou_ener_parcent) > 59)) {
        $nou_energy_bar = 'energy_ao.gif';
    } elseif ((n($nou_ener_parcent) > 19)) {
        $nou_energy_bar = 'energy_ki.gif';
    } else {
        $nou_energy_bar = 'energy_aka.gif';
    }
    // 最後に食事した時間を日付に変換
    byou_hiduke($last_syokuzi);
    $last_syokuzi_henkan = $bh_full_date;
    // 空腹度を算出
    $tabetenaizikan = (n($date_sec) - n($last_syokuzi));
    $manpuku_time = (n($syokuzi_kankaku) * 60);
    if ((n($tabetenaizikan) < n($manpuku_time))) {
        $kuuhukudo = '<span style="color:#ff3300">満腹（まだ食事できません）</span>';
    } elseif ((n($tabetenaizikan) < (n($manpuku_time) + ((60 * 60) * 2)))) {
        $kuuhukudo = '丁度いい';
    } elseif ((n($tabetenaizikan) < (n($manpuku_time) + ((60 * 60) * 12)))) {
        $kuuhukudo = 'やや空腹';
    } elseif ((n($tabetenaizikan) < (n($manpuku_time) + ((60 * 60) * 24)))) {
        $kuuhukudo = '空腹';
    } elseif ((n($tabetenaizikan) < (n($manpuku_time) + ((60 * 60) * 48)))) {
        $kuuhukudo = 'かなり空腹';
    } elseif ((n($tabetenaizikan) < (n($manpuku_time) + (((60 * 60) * 24) * 4)))) {
        $kuuhukudo = 'すごい空腹';
    } elseif ((n($tabetenaizikan) < (n($manpuku_time) + (((60 * 60) * 24) * ((n($deleteUser) - 3)))))) {
        $kuuhukudo = '死にそう。。';
    } else {
        $kuuhukudo = '危険！死ぬ寸前です。';
    }
    // ver.1.3
    // BMIをチェック
    $taijuu = pl_sprintf('%.1f', $taijuu);
    check_BMI($sintyou, $taijuu);
    // #コンディション計算
    // 残パワーを加味
    $condition_sisuu = (((n($nou_ener_parcent) + n($ener_parcent))) / 2);
    // 健康値を加味
    $condition_sisuu = n($condition_sisuu) + (n($kenkou) / 100);
    // 空腹度を加味
    if (($kuuhukudo == '<span style="color:#ff3300">満腹（まだ食事できません）</span>')) {
        $condition_sisuu = n($condition_sisuu) * 0.8;
    } elseif (($kuuhukudo == '丁度いい')) {
        $condition_sisuu = n($condition_sisuu) * 1;
    } elseif (($kuuhukudo == 'やや空腹')) {
        $condition_sisuu = n($condition_sisuu) * 0.9;
    } elseif (($kuuhukudo == '空腹')) {
        $condition_sisuu = n($condition_sisuu) * 0.7;
    } elseif (($kuuhukudo == 'かなり空腹')) {
        $condition_sisuu = n($condition_sisuu) * 0.6;
    } elseif (($kuuhukudo == 'すごい空腹')) {
        $condition_sisuu = n($condition_sisuu) * 0.5;
    } else {
        $condition_sisuu = n($condition_sisuu) * 0.3;
    }
    // 体系指数を加味
    if (($taikei == '肥満')) {
        $condition_sisuu = n($condition_sisuu) * 0.8;
    } elseif (($taikei == 'やや太り気味')) {
        $condition_sisuu = n($condition_sisuu) * 0.95;
    } elseif (($taikei == '標準')) {
        $condition_sisuu = n($condition_sisuu) * 1;
    } elseif (($taikei == 'やせ気味')) {
        $condition_sisuu = n($condition_sisuu) * 0.95;
    } else {
        $condition_sisuu = n($condition_sisuu) * 0.8;
    }
    if ((n($condition_sisuu) > 98)) {
        $condition = '最高';
        $byouki_sisuu = n($byouki_sisuu) + 2;
    } elseif ((n($condition_sisuu) > 75)) {
        $condition = '良好';
        $byouki_sisuu = n($byouki_sisuu) + 1;
    } elseif ((n($condition_sisuu) > 50)) {
        $condition = '普通';
    } elseif ((n($condition_sisuu) > 30)) {
        $condition = '不良';
        $byouki_sisuu = n($byouki_sisuu) - -1;
    } elseif ((n($condition_sisuu) > 10)) {
        $condition = '悪い';
        $byouki_sisuu = n($byouki_sisuu) - 3;
    } else {
        $condition = '最悪';
    }
    if ((n($byouki_sisuu) < -100)) {
        $byoumei = '癌';
    } elseif ((n($byouki_sisuu) < -70)) {
        $byoumei = '脳腫瘍';
    } elseif ((n($byouki_sisuu) < -40)) {
        $byoumei = '結核';
    } elseif ((n($byouki_sisuu) < -20)) {
        $byoumei = '肺炎';
    } elseif ((n($byouki_sisuu) < -10)) {
        $byoumei = '風邪';
    } elseif ((n($byouki_sisuu) < 0)) {
        $byoumei = '風邪ぎみ';
    } else {
        $byoumei = '';
    }
    // 仕事ボタンに渡す体調名（HTML を含めない）
    $condition_name = $byoumei ?: $condition;
    if ($byoumei) {
        $condition = "<span style=\"color:#ff6600\">{$byoumei}</span>";
    }
    // ジョブレベル算出
    $job_level = pl_int((n($job_keiken) / 100));
    // ホスト保存
    $host = $get_host;
    // ログイン非表示on off		#ver.1.30
    if (($in['sanka_hyouzi_on'] == 'on')) {
        $mise_type = 'on';
    }
    // ver.1.30
    if (($in['sanka_hyouzi_on'] == 'off')) {
        $mise_type = 'off';
    }
    // ver.1.30
    // 最終アクセス保存
    $last_access_byou = $access_byou;
    if (((($in['mode'] != 'syokudou') && ($in['mode'] != 'school')) && ($in['mode'] != 'gym')) && !$is_reload) {
        $access_byou = $date_sec;
    }
    // ログ更新
    temp_routin(...$_args);
    log_kousin($my_log_file, $k_temp);
    // 個人パラメータ表示
    // ログインモードならコマンドボタンを表示
    if (($in['mode'] == 'login_view')) {
        echo '<details class="tw-card tw-fold tw-cmd-card" data-fold="cmd" open><summary class="tw-fold-title">コマンド</summary>';
        command_botan(...$_args);
        // 制限時間があれば制限表示
        if ((n($koudou_seigen) > 0)) {
            // 通常の画面表示では今から制限時間が始まる。「更新」では前回の行動からの残り時間
            $ato_nanbyou = $is_reload ? max(0, (int)(n($koudou_seigen) - (n($seigenyou_now_time) - n($last_access_byou)))) : (int)n($koudou_seigen);
            $cdown_val = ($ato_nanbyou > 0) ? $ato_nanbyou : 'O K';
            $saikoroprint = "<span>行動できるまであと<input type=\"text\" name=\"cdown\" value=\"{$cdown_val}\" readonly aria-label=\"残り秒数\">秒</span>";
        }
        echo <<<EOM
        <form name="form1" method="POST" action="{$script}" style="margin:12px 0 0"><input type="hidden" name="mode" value="mail_sousin"><input type="hidden" name="name" value="{$in['name']}"><input type="hidden" name="pass" value="{$in['pass']}"><input type="hidden" name="k_id" value="{$k_id}"><input type="hidden" name="town_no" value="{$in['town_no']}">
        <div class="tw-notice">{$happend_ivent}{$saikoroprint}</div>
        </form>
        EOM;
        echo '</details>';
    }
    $next_levelup = (100 - ((n($job_keiken) % 100)));
    // ver.1.3ここから
    $money = pl_comma($money);
    $k_sousisan = pl_comma($k_sousisan);
    // ver.1.3ここまで
    echo <<<EOM
    <details class="tw-card tw-fold" data-fold="status" open><summary class="tw-fold-title">ステータス</summary>
    <dl class="tw-status">
    <dt>名前</dt><dd>{$name}</dd>
    <dt onMouseOver='onMes5("総資産＝持ち金 + 普通預金 + スーパー定期 - ローン額")' onMouseOut='onMes5("")'>持ち金</dt><dd>{$money}円 <span class="tw-sub">（総資産：{$k_sousisan}円）</span></dd>
    <dt onMouseOver='onMes5("経験値：{$job_keiken}（次のレベルアップまであと{$next_levelup}）　勤務数：{$job_kaisuu}回")' onMouseOut='onMes5("")'>職業</dt><dd>{$job}（レベル {$job_level}）<br><span class="tw-sub">経験値 {$job_keiken}（次まで {$next_levelup}）・勤務 {$job_kaisuu}回</span></dd>
    <dt>身体パワー</dt><dd class="tw-power">{$energy} <span class="tw-sub">（MAX {$energy_max}）</span><span class="tw-bar"><img src="{$img_dir}/{$energy_bar}" alt="" style="width:{$ener_parcent}%"><img src="{$img_dir}/nokori_bar.gif" alt="" style="width:{$nokori_parcent}%"></span></dd>
    <dt>頭脳パワー</dt><dd class="tw-power">{$nou_energy} <span class="tw-sub">（MAX {$nou_energy_max}）</span><span class="tw-bar"><img src="{$img_dir}/{$nou_energy_bar}" alt="" style="width:{$nou_ener_parcent}%"><img src="{$img_dir}/nokori_bar.gif" alt="" style="width:{$nou_nokori_parcent}%"></span></dd>
    <dt>コンディション</dt><dd>{$condition}</dd>
    <dt>身長・体重</dt><dd>{$sintyou} cm ／ {$taijuu} kg</dd>
    <dt>体格指数</dt><dd>{$BMI}（{$taikei}）</dd>
    <dt>空腹度</dt><dd>{$kuuhukudo}<br><span class="tw-sub">前回の食事：{$last_syokuzi_henkan}</span></dd>
    EOM;
    // 配偶者、恋人の表示
    if (!(pl_open('COA', (string)($couple_file)))) { error("{$couple_file}に書き込めません"); }
    $all_couple = pl_readlines('COA');
    pl_close('COA');
    $__s2 = $_ ?? null;
    foreach ($all_couple ?? [] as $_) {
        [$cn_number, $cn_name1, $cn_name2, $cn_joutai, $cn_total_aijou, $cn_omoide1, $cn_omoide2, $cn_omoide3, $cn_omoide4, $cn_omoide5, $cn_kodomo, $cn_yobi1, $cn_yobi2, $cn_yobi3, $cn_yobi4, $cn_yobi5] = pl_pad(pl_split('/<>/u', $_), 16);
        if (((string)$name === (string)(string)($cn_name1))) {
            if (($cn_joutai == '恋人')) {
                $my_koibito .= "{$cn_name2}　";
            } elseif (($cn_joutai == '配偶者')) {
                $my_haiguusya = (string)($cn_name2);
            }
        }
        if (((string)$name === (string)(string)($cn_name2))) {
            if (($cn_joutai == '恋人')) {
                $my_koibito .= "{$cn_name1}　";
            } elseif (($cn_joutai == '配偶者')) {
                $my_haiguusya = (string)($cn_name1);
            }
        }
    }
    $_ = $__s2;
    echo <<<EOM
    <dt>配偶者</dt><dd>{$my_haiguusya}</dd>
    <dt>恋人</dt><dd>{$my_koibito}</dd>
    <dt>所有物</dt><dd>
    EOM;
    // 所有物の表示
    $__s3 = $_ ?? null;
    foreach ($my_kounyuu_list ?? [] as $_) {
        syouhin_sprit($_);
        if ((n($syo_taikyuu) <= 0)) {
            continue;
        }
        if (($syo_hinmoku == 'いとしき我が家')) {
            echo <<<EOM
            <form method="POST" action="original_house.php" class="tw-cmds" style="display:block;margin:4px 0"><input type="hidden" name="mode" value="houmon"><input type="hidden" name="name" value="{$in['name']}"><input type="hidden" name="pass" value="{$in['pass']}"><input type="hidden" name="k_id" value="{$k_id}"><input type="hidden" name="town_no" value="{$in['town_no']}"><input type="hidden" name="ori_ie_id" value="{$k_id}"><button type="submit" class="tw-wide" style="min-height:44px"><img src="{$img_dir}/go_home2.gif" width="100" height="10" alt="">自分の家へ行く</button></form>
            EOM;
            continue;
        }
        echo "○{$syo_hinmoku}　";
    }
    $_ = $__s3;
    // ver.1.3ここまで
    $nouryoku_goukeiti = (((((((((((((n($kokugo) + n($suugaku)) + n($rika)) + n($syakai)) + n($eigo)) + n($ongaku)) + n($bijutu)) + n($looks)) + n($tairyoku)) + n($kenkou)) + n($speed)) + n($power)) + n($wanryoku)) + n($kyakuryoku));
    $__s4 = $_ ?? null;
    foreach (pl_range(6, 22) as $_) {
        $nouryoku_bar[n($_)] = pl_int((((((n($nouryoku_suuzi_hairetu[n($_)]) / n($nouryoku_goukeiti))) * 100) * n($nouryoku_goukeiti)) * 0.002));
    }
    $_ = $__s4;
    // 能力値（元の表示幅 px をそのまま 0〜100% の目安として使う）
    $pb = function (int $i, string $label, $value) use ($nouryoku_bar): string {
        $w = max(0, min(100, (int)$nouryoku_bar[$i]));
        return "<div class=\"tw-param\"><span>{$label}</span><span class=\"tw-pbar\"><span style=\"width:{$w}%\"></span></span><strong>{$value}</strong></div>";
    };
    $zunou = $pb(6, '国語', $kokugo) . $pb(7, '数学', $suugaku) . $pb(8, '理科', $rika) . $pb(9, '社会', $syakai) . $pb(10, '英語', $eigo) . $pb(11, '音楽', $ongaku) . $pb(12, '美術', $bijutu);
    $sintai = $pb(13, 'ルックス', $looks) . $pb(14, '体力', $tairyoku) . $pb(15, '健康', $kenkou) . $pb(16, 'スピード', $speed) . $pb(17, 'パワー', $power) . $pb(18, '腕力', $wanryoku) . $pb(19, '脚力', $kyakuryoku);
    $sonota = $pb(20, 'LOVE', $love) . $pb(21, '面白さ', $unique) . $pb(22, 'エッチ', $etti);
    echo <<<EOM
    </dd>
    </dl>
    <p class="tw-sub" style="margin:10px 0 0"><span style="color:#A33A12">※ゲームを終了するときは必ず「保存・終了」ボタンを押してください。</span><br>※コンディションによって仕事で得られる経験値が変わってきます。</p>
    </details>
    <details class="tw-card tw-fold" data-fold="params" open><summary class="tw-fold-title">能力値</summary>
    <div class="tw-params">
    <div><h3>頭脳</h3>{$zunou}</div>
    <div><h3>身体</h3>{$sintai}</div>
    <div><h3>その他</h3>{$sonota}</div>
    </div>
    </details>
    EOM;
}

function list_log_backup(...$_args)
{
    global $dir, $file_name;
    // if ($in{'admin_pass'} ne $admin_pass){&error("パスワードが違います");}		#ver.1.22
    // フォルダー内のファイル名を取得してバックアップログを作成
    $dir = pl_opendir('./log_dir');
    while (($file_name = pl_readdir($dir)) !== null) {
        // 1つ読み込んで$folder_nameに代入
        if ((((($file_name == '.') || ($file_name == '..')) || pl_match('/^backup_/u', $file_name)) || ($file_name == '.DS_Store'))) {
            continue;
        }
        $backup_name = 'backup_' . (string)($file_name);
        if (!(pl_open('BK', "./log_dir/{$file_name}"))) { error("Open Error : ./log_dir/{$file_name}"); }
        $back_data = pl_readlines('BK');
        pl_close('BK');
        if ((pl_count($back_data) != 0)) {
            if (!(pl_open('BKO', ">./log_dir/backup_dir/{$backup_name}"))) { error("Write Error : ./log_dir/backup_dir/{$backup_name}"); }
            pl_print('BKO', pl_join('', $back_data));
            pl_close('BKO');
        }
    }
    pl_closedir($dir);
    // ディレクトリを閉じる
}

