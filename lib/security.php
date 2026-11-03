<?php
/**
 * 入力処理・認証・セッション（セキュリティ強化部分）
 *
 * 元の Perl 版からの主な変更点:
 *  - 入力値はすべて HTML エスケープしてから扱う（XSS・ログ区切り "<>" の混入防止）
 *  - パスワードは password_hash() でハッシュ化して保存（旧形式の平文は初回ログイン時に自動変換）
 *  - ログイン後の画面遷移では、パスワードの代わりにセッションに紐づくトークンをフォームに埋め込む
 *    （パスワードが HTML や URL に出ない・CSRF 対策を兼ねる）
 *  - 管理者パスワードも同様にトークン化。初期値（空/1234）のままでは管理画面に入れない
 *  - 数値 ID・街番号など、ファイルパスに使われる値は形式を検証
 *  - 画像・リンク用の URL は http(s) のみ許可
 */

const TOWN_SESSION_NAME = 'TOWNSESSID';

/** URL として保存される入力項目（http/https 以外は破棄） */
const TOWN_URL_FIELDS = ['ch_gazou', 'my_icon', 'dokuzi_url'];

function town_session_start(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    if (PHP_SAPI === 'cli' && empty($_SERVER['REQUEST_METHOD'])) {
        $_SESSION = $_SESSION ?? [];
        return;
    }
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    ini_set('session.use_strict_mode', '1');
    session_cache_limiter('');
    ini_set('session.use_only_cookies', '1');
    session_name(TOWN_SESSION_NAME);
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    @session_start();
}

/** 0 以上の整数のみ受け付ける入力項目（金額・数量・座標など） */
const TOWN_UINT_FIELDS = ['azukegaku', 'orosigaku', 'hurikomigaku', 'saisengaku', 'okane', 'suuti', 'par_suuti',
    'kounyuusuu', 'hanbaikakaku', 'mati_sentaku', 'tateziku', 'yokoziku', 'ori_ie_town', 'ori_ie_sentaku_point',
    'ori_ie_rank', 'gentei_kensuu', 'b_num', 'bbs_num', 'con_sele', 'sort_id', 'sortid',
    'ch_kokugo', 'ch_suugaku', 'ch_rika', 'ch_syakai', 'ch_eigo', 'ch_ongaku', 'ch_bijutu', 'ch_looks', 'ch_tairyoku',
    'ch_kenkou', 'ch_speed', 'ch_power', 'ch_wanryoku', 'ch_kyakuryoku', 'ch_love', 'ch_unique', 'ch_etti'];

/** 入力値の無害化 */
function town_clean_input(string $v, bool $post): string
{
    if (!mb_check_encoding($v, 'UTF-8')) {
        $v = mb_convert_encoding($v, 'UTF-8', 'SJIS-win');
    }
    // 制御文字の除去（改行・タブは残す）
    $v = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $v) ?? '';
    if (mb_strlen($v) > 20000) {
        $v = mb_substr($v, 0, 20000);
    }
    // < > " ' を実体参照に変換（タグ・属性の注入とログ区切り "<>" の混入を防ぐ）。
    // & はそのまま（元のデータ形式 ",&," 区切り等との互換のため）。
    $v = strtr($v, ['<' => '&lt;', '>' => '&gt;', '"' => '&quot;', "'" => '&#039;']);
    if ($post) {
        $v = str_replace(["\r\n", "\r", "\n"], '<br>', $v);
    }
    return $v;
}

function town_safe_url(string $v): string
{
    $raw = html_entity_decode($v, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $raw = trim($raw);
    if ($raw === '' || $raw === 'http://' || $raw === 'https://') {
        return $v === 'http://' ? $v : '';
    }
    if (!preg_match('#^https?://[^\s"\'<>`]+$#i', $raw)) {
        return '';
    }
    return strtr($raw, ['<' => '&lt;', '>' => '&gt;', '"' => '&quot;', "'" => '&#039;']);
}

/** 設定ファイルの管理者パスワード（平文または password_hash の値） */
function town_admin_pass_conf(): string
{
    return (string)($GLOBALS['__ADMIN_PASS_CONF'] ?? '');
}

function town_admin_enabled(): bool
{
    $p = town_admin_pass_conf();
    return $p !== '' && $p !== '1234';
}

function town_verify_secret(string $input, string $stored): bool
{
    if ($stored === '' || $input === '') {
        return false;
    }
    if (preg_match('/^\$(2y|2a|argon2id|argon2i)\$/', $stored)) {
        return password_verify($input, $stored)
            || password_verify(html_entity_decode($input, ENT_QUOTES | ENT_HTML5, 'UTF-8'), $stored);
    }
    // 旧形式（平文）
    return hash_equals($stored, $input)
        || hash_equals($stored, html_entity_decode($input, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
}

function pl_password_hash(mixed $p): string
{
    return password_hash((string)$p, PASSWORD_DEFAULT);
}

function town_new_token(): string
{
    return bin2hex(random_bytes(20));
}

/** 元の decode サブルーチン */
function pl_decode(): void
{
    global $in, $admin_pass;
    if (!isset($GLOBALS['__ADMIN_PASS_CONF'])) {
        $GLOBALS['__ADMIN_PASS_CONF'] = (string)$admin_pass;
    }
    town_session_start();

    $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    $src = $method === 'POST' ? $_POST : $_GET;
    $in = [];
    foreach ($src as $k => $v) {
        if (!is_string($k) || !preg_match('/^[^\x00-\x20<>"\'&\x7f]{1,64}$/u', $k)) {
            continue;
        }
        if (is_array($v)) {
            continue;
        }
        $in[$k] = town_clean_input((string)$v, $method === 'POST');
    }
    foreach (TOWN_URL_FIELDS as $f) {
        if (isset($in[$f])) {
            $in[$f] = town_safe_url($in[$f]);
        }
    }
    foreach ($in as $k => $v) {
        if ($v !== '' && (in_array($k, TOWN_UINT_FIELDS, true) || preg_match('/^kane\d+$/', $k)) && !preg_match('/^\d{1,12}$/', $v)) {
            error('数値は半角数字（0以上）で入力してください。');
        }
    }
    // ファイルパスに使われる値の検証
    foreach (['k_id', 'iesettei_id', 'town_no'] as $f) {
        if (isset($in[$f]) && $in[$f] !== '' && !preg_match('/^\d{1,9}$/', $in[$f])) {
            if ($f === 'town_no') {
                $in[$f] = '0';
            } else {
                error('不正なパラメータです。');
            }
        }
    }
    if (isset($in['ori_ie_id']) && $in['ori_ie_id'] !== '' && $in['ori_ie_id'] !== 'admin' && !preg_match('/^\d{1,9}$/', $in['ori_ie_id'])) {
        error('不正なパラメータです。');
    }

    // GET でのアクセスを拒否（元の仕様どおり）
    $mode = $in['mode'] ?? '';
    $getOk = ['', 'houmon', 'parts_taiou_hyou', 'itiran', 'kensaku'];
    if (!in_array($mode, $getOk, true) && ($in['ori_ie_id'] ?? '') !== 'admin'
        && ($in['command'] ?? '') !== 'idousyuuryou' && ($in['command'] ?? '') !== 'easySerch') {
        if ($method !== 'POST') {
            error('不正アクセスのため強制終了されます。');
        }
    }

    // 管理者認証
    $isAdmin = false;
    $given = (string)($in['admin_pass'] ?? '');
    if ($given !== '') {
        $sess = $_SESSION['town_admin'] ?? '';
        if ($sess !== '' && hash_equals($sess, $given)) {
            $isAdmin = true;
        } elseif (!town_admin_enabled()) {
            error('管理者パスワードが未設定（または初期値「1234」のまま）のため、管理機能は使えません。<br>town_ini.php の $admin_pass を変更してください。');
        } elseif (town_verify_secret($given, town_admin_pass_conf())) {
            session_regenerate_id(true);
            $_SESSION['town_admin'] = town_new_token();
            $in['admin_pass'] = $_SESSION['town_admin'];
            $isAdmin = true;
        } else {
            usleep(800000);
        }
    }
    $GLOBALS['__IS_ADMIN'] = $isAdmin;
    // 以降の元コードは「$in{'admin_pass'} eq $admin_pass」で管理者判定しているため、
    // 管理者ならトークンを、そうでなければ推測できない値を $admin_pass に入れておく
    $admin_pass = $isAdmin ? $in['admin_pass'] : town_new_token();

    // 内部専用のコマンド値はクライアントから受け付けない
    if (($in['command'] ?? '') === 'from_system' && !$isAdmin) {
        $in['command'] = '';
    }

    // パスワードチェック
    if (!$isAdmin) {
        if ($mode !== '' && $mode !== 'new' && $mode !== 'parts_taiou_hyou') {
            check_pass();
        }
    }
}

function town_is_admin(): bool
{
    return !empty($GLOBALS['__IS_ADMIN']);
}

/** 設定の管理者名で登録・ログインしている本人か */
function pl_is_admin_player(): bool
{
    global $admin_name, $name;
    return (string)$admin_name !== '' && (string)$name === (string)$admin_name && !empty($_SESSION['town_user']);
}

/** 管理者名での新規登録には管理者パスワードが必要 */
function pl_check_admin_name_register(mixed $newName, mixed $newPass): void
{
    global $admin_name;
    if ((string)$admin_name !== '' && (string)$newName === (string)$admin_name) {
        if (!town_admin_enabled() || !town_verify_secret((string)$newPass, town_admin_pass_conf())) {
            error('その名前は登録できません。');
        }
    }
}

/** 元の check_pass サブルーチン */
function pl_check_pass(): void
{
    global $in, $k_id, $my_log_file, $my_prof, $pass, $master_ad, $tajuukinsi_deny, $k_yobi3, $return_id;
    if (($in['k_id'] ?? '') === '') {
        if (($in['name'] ?? '') === '') {
            error('ログインしてください。');
        }
        id_check($in['name']);
        $k_id = $return_id;
    } else {
        $k_id = $in['k_id'];
    }
    if (!preg_match('/^\d{1,9}$/', (string)$k_id)) {
        error('名前とパスワードが一致しません！');
    }
    $my_log_file = "./member/{$k_id}/log.cgi";
    if (!pl_open('MYL', $my_log_file)) {
        error("ログファイルが開けません。<br>再度ログインしても同様ならば管理人（{$master_ad}）までメールをください。");
    }
    $my_prof = pl_readline('MYL');
    pl_close('MYL');
    if ((string)$my_prof === '') {
        error("ログファイルに問題が起こりました。<br>お手数ですが、管理人（{$master_ad}）までメールをください。");
    }
    kozin_sprit2($my_prof);

    $given = (string)($in['pass'] ?? '');
    $sess = $_SESSION['town_user'] ?? null;
    $ok = false;
    if (is_array($sess) && (string)$sess['k_id'] === (string)$k_id && $given !== '' && hash_equals((string)$sess['token'], $given)) {
        $ok = true;
    } elseif (town_verify_secret($given, (string)$pass)) {
        $ok = true;
        // ログイン成立: セッションを発行し、以後のフォームにはトークンを埋め込む
        session_regenerate_id(true);
        $token = town_new_token();
        $_SESSION['town_user'] = ['k_id' => (string)$k_id, 'token' => $token];
        // 旧形式（平文）のパスワードはハッシュに置き換える
        if (!preg_match('/^\$(2y|2a|argon2id|argon2i)\$/', (string)$pass) || password_needs_rehash((string)$pass, PASSWORD_DEFAULT)) {
            $pass = pl_password_hash($given);
            temp_routin();
            log_kousin($my_log_file, $GLOBALS['k_temp']);
            $my_prof = $GLOBALS['k_temp'];
            town_update_pass_log((string)$k_id, $pass);
        }
        $in['pass'] = $token;
    }
    if (!$ok) {
        usleep(800000);
        error('名前とパスワードが一致しません！');
    }
    if (n($tajuukinsi_deny) == 1 && (string)$k_yobi3 !== '') {
        error("多重登録者は入居できません。<br>{$k_yobi3}");
    }
}

/** 管理画面でのパスワード変更（ハッシュ化して保存） */
function pl_admin_newpass(): string
{
    global $in;
    static $h = null;
    if ($h === null) {
        $h = pl_password_hash(html_entity_decode((string)$in['pass'], ENT_QUOTES | ENT_HTML5, 'UTF-8') === (string)$in['pass'] ? $in['pass'] : $in['pass']);
        if (preg_match('/^\d+$/', (string)($in['k_id'] ?? ''))) {
            town_update_pass_log((string)$in['k_id'], $h);
        }
    }
    return $h;
}

/** パスワード記録ファイル中の旧パスワードを置き換える */
function town_update_pass_log(string $kid, string $hash): void
{
    global $pass_logfile;
    $lines = pl_data_read($pass_logfile);
    $changed = false;
    foreach ($lines as $i => $l) {
        $f = explode('<>', $l);
        if (($f[0] ?? '') === $kid && isset($f[2])) {
            $f[2] = $hash;
            $lines[$i] = implode('<>', $f);
            $changed = true;
        }
    }
    if ($changed) {
        pl_data_save($pass_logfile, ...$lines);
    }
}

function pl_get_host(): string
{
    global $host_lookup;
    $addr = (string)($_SERVER['REMOTE_ADDR'] ?? '');
    if (n($host_lookup ?? 0) == 1 && filter_var($addr, FILTER_VALIDATE_IP)) {
        $h = @gethostbyaddr($addr);
        if (is_string($h) && $h !== '') {
            return $h;
        }
    }
    return $addr;
}

/** クッキー（名前のみ保存。パスワードは保存しない） */
function pl_get_cookie(): void
{
    global $ck, $in;
    $ck = [];
    $raw = (string)($_COOKIE['town_maker'] ?? '');
    if ($raw !== '') {
        $parts = explode('<>', $raw);
        for ($i = 0; $i + 1 < count($parts); $i += 2) {
            if ($parts[$i] === 'name') {
                $v = html_entity_decode($parts[$i + 1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $ck['name'] = strtr($v, ['<' => '&lt;', '>' => '&gt;', '"' => '&quot;', "'" => '&#039;']);
            }
        }
    }
    if (!empty($in['name'])) {
        $ck['name'] = $in['name'];
    }
    $ck['pass'] = '';
}

function pl_set_cookie(): void
{
    global $in;
    if (headers_sent()) {
        return;
    }
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    setcookie('town_maker', 'name<>' . (string)($in['name'] ?? '') . '<>', [
        'expires' => time() + 60 * 86400,
        'path' => '/',
        'secure' => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

/** HTML 出力開始（元の header サブルーチンの Content-type 出力部分） */
function pl_html_head(): void
{
    if (!headers_sent()) {
        header('Content-Type: text/html; charset=UTF-8');
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('Referrer-Policy: same-origin');
        header('Cache-Control: private');
    }
    echo "<!DOCTYPE html>\n<html lang=\"ja\">\n<head>\n<meta charset=\"UTF-8\">\n<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n<meta name=\"referrer\" content=\"same-origin\">\n";
    echo town_ogp_tags();
}

/** JavaScript 文字列（HTML 属性内）に値を埋め込むためのエスケープ */
function pl_js(mixed $v): string
{
    $raw = html_entity_decode((string)$v, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $j = json_encode($raw, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    return htmlspecialchars(substr($j, 1, -1), ENT_QUOTES, 'UTF-8');
}

/**
 * 「戻る」ボタン（元の「前の画面に戻る」= history.back の置き換え）
 *  - 管理者としてログイン中 → 管理者メニュー
 *  - 住民としてログイン中   → 街の画面
 *  - それ以外               → トップページ
 * 画面中の <!--TW_BACK--> がこのボタンに置き換わる（lib/bootstrap.php の出力処理）。
 */
function tw_back_html(): array
{
    // 戻り先へ送るフォームは、ボタンを押したときにスクリプトで作って送信する。
    // 画面のどこに置かれても、ほかのフォーム（閉じていないものを含む）の送信内容に混ざらない。
    global $in;
    $town = (string)($in['town_no'] ?? '0');
    $target = null;
    if (town_is_admin() && !empty($in['admin_pass'])) {
        $target = ['action' => 'admin.php', 'fields' => ['mode' => 'admin', 'admin_pass' => (string)$in['admin_pass'], 'town_no' => $town]];
    } else {
        $sess = $_SESSION['town_user'] ?? null;
        if (is_array($sess) && isset($in['pass']) && hash_equals((string)$sess['token'], (string)$in['pass'])) {
            $target = ['action' => 'town_maker.php', 'fields' => ['mode' => 'login_view', 'name' => (string)($in['name'] ?? ''),
                'pass' => (string)$in['pass'], 'k_id' => (string)$sess['k_id'], 'town_no' => $town]];
        }
    }
    if ($target === null) {
        return ['<a class="tw-back" href="town_maker.php">戻る</a>', ''];
    }
    $json = htmlspecialchars(json_encode($target, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8');
    $button = '<button type="button" class="tw-back" data-back="' . $json . '" onclick="twBack(this)">戻る</button>';
    $script = <<<'JS'
<script>
function twBack(b) {
  var t = JSON.parse(b.getAttribute('data-back')), f = document.createElement('form');
  f.method = 'POST'; f.action = t.action; f.style.display = 'none';
  Object.keys(t.fields).forEach(function (k) { var i = document.createElement('input'); i.type = 'hidden'; i.name = k; i.value = t.fields[k]; f.appendChild(i); });
  document.body.appendChild(f); f.submit();
}
</script>
JS;
    return [$button, $script];
}

/** サイトのトップ URL（$ogp_url が空ならアクセスされた URL から判定） */
function town_base_url(): string
{
    global $ogp_url;
    if (!empty($ogp_url)) {
        return rtrim((string)$ogp_url, '/') . '/';
    }
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    $host = (string)($_SERVER['HTTP_HOST'] ?? 'localhost');
    if (!preg_match('/^[A-Za-z0-9.\-]+(:\d+)?$/', $host)) {
        $host = 'localhost';
    }
    $dir = rtrim(str_replace('\\', '/', dirname((string)($_SERVER['SCRIPT_NAME'] ?? '/'))), '/');
    return ($https ? 'https' : 'http') . '://' . $host . $dir . '/';
}

/** OGP / Twitter カードの meta タグ */
function town_ogp_tags(): string
{
    global $title, $ogp_description, $ogp_image, $twitter_card, $twitter_site, $twitter_creator, $twitter_image, $twitter_image_alt;
    $e = fn($v) => htmlspecialchars(strip_tags((string)$v), ENT_QUOTES, 'UTF-8');
    $base = town_base_url();
    $abs = function ($img) use ($base): string {
        $img = (string)$img;
        if ($img === '' || preg_match('#^https?://#i', $img)) {
            return $img;
        }
        return $base . ltrim(preg_replace('#^\./#', '', $img), '/');
    };
    $image = $abs($ogp_image ?? '');
    $t = $e($title ?? '');
    $d = $e($ogp_description ?? '');
    $m = fn($attr, $key, $val) => "<meta {$attr}=\"{$key}\" content=\"{$val}\">\n";
    $out = $m('name', 'description', $d)
        . $m('property', 'og:type', 'website')
        . $m('property', 'og:site_name', $t)
        . $m('property', 'og:title', $t)
        . $m('property', 'og:description', $d)
        . $m('property', 'og:url', $e($base))
        . $m('property', 'og:locale', 'ja_JP');
    if ($image !== '') {
        $out .= $m('property', 'og:image', $e($image));
        if (($ogp_image ?? '') === './img/ogp.png') {
            $out .= $m('property', 'og:image:width', '1200') . $m('property', 'og:image:height', '630');
        }
    }
    // Twitter（X）カード
    $card = (string)($twitter_card ?? 'summary_large_image');
    if (in_array($card, ['summary', 'summary_large_image'], true)) {
        $out .= $m('name', 'twitter:card', $card)
            . $m('name', 'twitter:title', $t)
            . $m('name', 'twitter:description', $d);
        $account = fn($v) => preg_match('/^@?[A-Za-z0-9_]{1,15}$/', (string)$v) ? '@' . ltrim((string)$v, '@') : '';
        if (($s = $account($twitter_site ?? '')) !== '') {
            $out .= $m('name', 'twitter:site', $e($s));
        }
        if (($c = $account($twitter_creator ?? '')) !== '') {
            $out .= $m('name', 'twitter:creator', $e($c));
        }
        $timg = $abs(($twitter_image ?? '') !== '' ? $twitter_image : ($ogp_image ?? ''));
        if ($timg !== '') {
            $out .= $m('name', 'twitter:image', $e($timg));
            if (($twitter_image_alt ?? '') !== '') {
                $out .= $m('name', 'twitter:image:alt', $e($twitter_image_alt));
            }
        }
    }
    return $out;
}
