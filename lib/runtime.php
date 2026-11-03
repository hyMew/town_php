<?php
/**
 * Perl 互換ランタイム
 *
 * 自動変換されたコードが Perl と同じ振る舞いをするための補助関数群。
 * （空文字列の数値化、split の末尾空要素除去、ファイルハンドル操作など）
 */

/** Perl の数値化（"12abc" → 12、"" / undef → 0）。PHP 8 では "" + 1 が TypeError になるため必須 */
function n(mixed $v): int|float
{
    if (is_int($v) || is_float($v)) {
        return $v;
    }
    if ($v === null || $v === '' || $v === false) {
        return 0;
    }
    if ($v === true) {
        return 1;
    }
    if (is_array($v)) {
        return count($v);
    }
    $s = (string)$v;
    if (is_numeric($s)) {
        return $s + 0;
    }
    if (preg_match('/^\s*([+-]?(?:\d+(?:\.\d*)?|\.\d+)(?:[eE][+-]?\d+)?)/', $s, $m)) {
        return $m[1] + 0;
    }
    return 0;
}

function pl_int(mixed $v): int|float
{
    $f = n($v);
    if (is_int($f)) {
        return $f;
    }
    if (is_nan($f) || is_infinite($f)) {
        return 0;
    }
    $t = $f < 0 ? ceil($f) : floor($f);
    return ($t >= PHP_INT_MIN && $t <= PHP_INT_MAX) ? (int)$t : $t;
}

function pl_count(mixed $a): int
{
    if (is_array($a)) {
        return count($a);
    }
    return 0;
}

function pl_idx(mixed $a, int|float|string|null $i): mixed
{
    if (!is_array($a)) {
        return null;
    }
    $i = (int)n($i);
    if ($i < 0) {
        $a = array_values($a);
        $i += count($a);
    }
    return $a[$i] ?? null;
}

function pl_slice(mixed $a, mixed $idx): array
{
    $out = [];
    if (!is_array($idx)) {
        $idx = [$idx];
    }
    foreach ($idx as $i) {
        $out[] = is_array($a) ? ($a[is_int($i) || is_numeric($i) ? (int)n($i) : $i] ?? null) : null;
    }
    return $out;
}

/** リスト代入用: 要素数を揃える */
function pl_pad(mixed $a, int $n): array
{
    if (!is_array($a)) {
        $a = $a === null ? [] : [$a];
    }
    $a = array_values($a);
    return count($a) < $n ? array_pad($a, $n, null) : $a;
}

function pl_tolist(mixed $v): array
{
    if (is_array($v)) {
        return $v;
    }
    return $v === null ? [] : [$v];
}

function pl_last(mixed $a): mixed
{
    if (!is_array($a) || !$a) {
        return null;
    }
    return $a[array_key_last($a)];
}

function pl_hash(mixed $list): array
{
    $h = [];
    $list = array_values(pl_tolist($list));
    for ($i = 0; $i + 1 < count($list); $i += 2) {
        $h[(string)$list[$i]] = $list[$i + 1];
    }
    if (count($list) % 2 === 1) {
        $h[(string)$list[count($list) - 1]] = null;
    }
    return $h;
}

function pl_range(mixed $a, mixed $b): array
{
    if (is_string($a) && $a !== '' && !is_numeric($a)) {
        return range($a, (string)$b);
    }
    $a = (int)n($a);
    $b = (int)n($b);
    return $b < $a ? [] : range($a, $b);
}

/** Perl の split。limit 省略時は末尾の空要素を取り除く */
function pl_split(string $re, mixed $s, int|float $limit = 0): array
{
    $s = (string)$s;
    if ($s === '') {
        return [];
    }
    if ($re === ' ') {
        $s = ltrim($s);
        $re = '/\s+/u';
    }
    $limit = (int)$limit;
    $parts = preg_split($re, $s, $limit > 0 ? $limit : -1);
    if ($parts === false) {
        // 不正な UTF-8 などで失敗した場合はバイト単位で再試行
        $parts = preg_split(preg_replace('/u$/', '', $re), $s, $limit > 0 ? $limit : -1) ?: [$s];
    }
    // パターンが先頭で長さ0マッチした場合の先頭空要素は Perl では生成されない
    if ($limit <= 0) {
        while ($parts && end($parts) === '') {
            array_pop($parts);
        }
    }
    return $parts;
}

function pl_join(mixed $sep, mixed $list): string
{
    if (!is_array($list)) {
        return $list === null ? '' : (string)$list;
    }
    return implode((string)$sep, array_map(fn($v) => is_array($v) ? count($v) : (string)$v, $list));
}

function pl_push(mixed &$a, mixed ...$items): int
{
    if (!is_array($a)) {
        $a = [];
    }
    foreach ($items as $it) {
        $a[] = $it;
    }
    return count($a);
}

function pl_unshift(mixed &$a, mixed ...$items): int
{
    if (!is_array($a)) {
        $a = [];
    }
    array_unshift($a, ...$items);
    return count($a);
}

function pl_pop(mixed &$a): mixed
{
    if (!is_array($a) || !$a) {
        return null;
    }
    return array_pop($a);
}

function pl_shift(mixed &$a): mixed
{
    if (!is_array($a) || !$a) {
        return null;
    }
    return array_shift($a);
}

function pl_splice(mixed &$a, int|float $off, int|float|null $len = null, mixed ...$repl): array
{
    if (!is_array($a)) {
        $a = [];
    }
    $a = array_values($a);
    $off = (int)$off;
    return $len === null ? array_splice($a, $off) : array_splice($a, $off, (int)$len, $repl);
}

/**
 * sort。$cmp はサブルーチン名またはクロージャ（$a, $b はグローバル変数として渡す）
 */
function pl_sort(callable|string|null $cmp, mixed $list): array
{
    $list = array_values(pl_tolist($list));
    if ($cmp === null) {
        usort($list, fn($x, $y) => pl_cmp($x, $y));
        return $list;
    }
    usort($list, function ($x, $y) use ($cmp) {
        $GLOBALS['a'] = $x;
        $GLOBALS['b'] = $y;
        return (int)n($cmp());
    });
    return $list;
}

/**
 * 文字列比較（Perl の cmp / lt / gt）。元のデータは Shift_JIS だったため、
 * 並び順が変わらないよう Shift_JIS のバイト順で比較する。
 */
function pl_cmp(mixed $a, mixed $b): int
{
    $a = (string)$a;
    $b = (string)$b;
    if (!preg_match('/[\x80-\xff]/', $a . $b)) {
        return strcmp($a, $b) <=> 0;
    }
    static $cache = [];
    $sa = $cache[$a] ??= mb_convert_encoding($a, 'SJIS-win', 'UTF-8');
    $sb = $cache[$b] ??= mb_convert_encoding($b, 'SJIS-win', 'UTF-8');
    if (count($cache) > 5000) {
        $cache = [];
    }
    return strcmp($sa, $sb) <=> 0;
}

// ---------------------------------------------------------------- 正規表現
$GLOBALS['__PL_M'] = [];

function pl_match(string $re, mixed $s): bool
{
    $m = [];
    $r = @preg_match($re, (string)$s, $m);
    if ($r === false || $r === 0) {
        if ($r === false) {
            $r2 = @preg_match(preg_replace('/u$/', '', $re), (string)$s, $m);
            if ($r2) {
                $GLOBALS['__PL_M'] = $m;
                return true;
            }
        }
        return false;
    }
    $GLOBALS['__PL_M'] = $m;
    return true;
}

function pl_match_list(string $re, mixed $s, bool $global): array
{
    if ($global) {
        preg_match_all($re, (string)$s, $m);
        return count($m) > 1 ? array_merge(...array_map(null, ...array_slice($m, 1))) : $m[0];
    }
    if (!pl_match($re, $s)) {
        return [];
    }
    $m = $GLOBALS['__PL_M'];
    return count($m) > 1 ? array_slice($m, 1) : [1];
}

function pl_m(int $n): ?string
{
    return $GLOBALS['__PL_M'][$n] ?? null;
}

function pl_mm(array $m, int $n): ?string
{
    return $m[$n] ?? null;
}

/** 変数を正規表現に埋め込む際に区切り文字 / をエスケープ */
function pl_redelim(mixed $s): string
{
    return preg_replace('#(?<!\\\\)/#', '\\/', (string)$s);
}

// ---------------------------------------------------------------- 文字列
/** Shift_JIS 換算のバイト数（元の文字数制限を保つため） */
function pl_length(mixed $s): int
{
    // 入力時に実体参照へ変換した記号は 1 文字として数える
    $s = strtr((string)$s, ['&lt;' => '<', '&gt;' => '>', '&quot;' => '"', '&#039;' => "'"]);
    if ($s === '') {
        return 0;
    }
    return strlen(mb_convert_encoding($s, 'SJIS-win', 'UTF-8'));
}

function pl_substr(mixed $s, int|float $off, int|float|null $len = null): string
{
    $s = (string)$s;
    if (preg_match('/^[\x00-\x7f]*$/', $s)) {
        return $len === null ? substr($s, (int)$off) : substr($s, (int)$off, (int)$len);
    }
    $sj = mb_convert_encoding($s, 'SJIS-win', 'UTF-8');
    $off = (int)$off;
    if ($off < 0) {
        $off += strlen($sj);
    }
    $cut = mb_strcut($sj, $off, $len === null ? null : (int)$len, 'SJIS-win');
    return mb_convert_encoding($cut, 'UTF-8', 'SJIS-win');
}

function pl_index(mixed $s, mixed $t): int
{
    $p = strpos((string)$s, (string)$t);
    return $p === false ? -1 : $p;
}

function pl_sprintf(mixed $fmt, mixed ...$args): string
{
    $fmt = (string)$fmt;
    $need = preg_match_all('/%(?!%)/', $fmt);
    while (count($args) < $need) {
        $args[] = '';
    }
    foreach ($args as &$a) {
        if (is_array($a)) {
            $a = count($a);
        }
    }
    try {
        return vsprintf($fmt, $args);
    } catch (\Throwable $e) {
        return $fmt;
    }
}

/** 数値を3桁区切りにする（元コードの substr/pos を使った定型処理の置き換え） */
function pl_comma(mixed $v): string
{
    $s = (string)$v;
    if (!preg_match('/^([-+]?)(\d{4,})(.*)$/s', $s, $m)) {
        return $s;
    }
    return $m[1] . strrev(implode(',', str_split(strrev($m[2]), 3))) . $m[3];
}

function pl_urlencode(mixed $s): string
{
    return rawurlencode((string)$s);
}

function pl_echo(mixed ...$a): int
{
    echo implode('', array_map('strval', $a));
    return 1;
}

function pl_noop(): void
{
}

// ---------------------------------------------------------------- 乱数・時刻
/*
 * テストモード（環境変数 TOWN_TEST=1）では、元の Perl 版との比較テストのために
 * 乱数列と現在時刻を固定できる。通常運用では使われない。
 */
function pl_test_mode(): bool
{
    static $t = null;
    return $t ??= (getenv('TOWN_TEST') === '1');
}

function pl_time(): int
{
    if (pl_test_mode() && isset($_SERVER['HTTP_X_TEST_TIME'])) {
        return (int)$_SERVER['HTTP_X_TEST_TIME'];
    }
    return time();
}

function pl_rand(int|float $n = 1): float
{
    if ($n == 0) {
        $n = 1;
    }
    if (pl_test_mode()) {
        static $state = 12345;
        $state = ($state * 48271) % 2147483647;
        return $state / 2147483647 * $n;
    }
    return mt_rand() / (mt_getrandmax() + 1) * $n;
}

function pl_srand(int|float|null $seed = null): int
{
    // 乱数の種は PHP が自動で設定する。元コードの srand(time) 等で同じ乱数列が
    // 繰り返されるのを避けるため、ここでは何もしない。
    return 0;
}

function pl_sleep(int|float $s): int
{
    // 待ち時間は最大 2 秒に制限（排他ロック保持中のため）
    usleep((int)(min(2, max(0, $s)) * 1000000));
    return (int)$s;
}

function pl_localtime(int|float|null $t = null): array
{
    $t = $t === null ? time() : (int)$t;
    $d = new DateTime('@' . $t);
    $d->setTimezone(new DateTimeZone(date_default_timezone_get()));
    return [
        (int)$d->format('s'), (int)$d->format('i'), (int)$d->format('G'),
        (int)$d->format('j'), (int)$d->format('n') - 1, (int)$d->format('Y') - 1900,
        (int)$d->format('w'), (int)$d->format('z'), (int)$d->format('I'),
    ];
}

function pl_gmtime(int|float|null $t = null): array
{
    $t = $t === null ? time() : (int)$t;
    return [
        (int)gmdate('s', $t), (int)gmdate('i', $t), (int)gmdate('G', $t),
        (int)gmdate('j', $t), (int)gmdate('n', $t) - 1, (int)gmdate('Y', $t) - 1900,
        (int)gmdate('w', $t), (int)gmdate('z', $t), 0,
    ];
}

function pl_localtime_str(int|float|null $t = null): string
{
    return date('D M j H:i:s Y', $t === null ? time() : (int)$t);
}

function pl_gmtime_str(int|float|null $t = null): string
{
    return gmdate('D M j H:i:s Y', $t === null ? time() : (int)$t);
}

// ---------------------------------------------------------------- ファイル操作（ストレージ経由）
$GLOBALS['__PL_FH'] = [];

function pl_key(mixed $path): ?string
{
    return town_storage_key((string)$path);
}

/** content を Perl の <FH> と同じ行単位（改行を含む）に分割 */
function pl_lines(string $content): array
{
    if ($content === '') {
        return [];
    }
    return preg_split('/(?<=\n)/', $content, -1, PREG_SPLIT_NO_EMPTY);
}

/**
 * open(FH, "path") / open(FH, ">path") / open(FH, ">>path") の代替
 */
function pl_open(string $fh, mixed $spec, mixed $path = null): bool
{
    $spec = trim((string)$spec);
    if ($path !== null) {
        $mode = $spec;
        $p = (string)$path;
    } elseif (preg_match('/^(\+?[<>]{1,2})\s*(.*)$/s', $spec, $m)) {
        $mode = $m[1];
        $p = $m[2];
    } else {
        $mode = '<';
        $p = $spec;
    }
    if (str_contains($p, '|') || str_contains($mode, '|')) {
        return false;
    }
    $key = pl_key($p);
    if ($key === null) {
        return false;
    }
    pl_close($fh);
    $st = town_storage();
    if ($mode === '<' || $mode === '+<') {
        $data = $st->read($key);
        if ($data === null) {
            return false;
        }
        $GLOBALS['__PL_FH'][$fh] = ['mode' => 'r', 'key' => $key, 'lines' => pl_lines($data), 'pos' => 0];
        return true;
    }
    if ($mode === '>') {
        if (!$st->write($key, '')) {
            return false;
        }
        $GLOBALS['__PL_FH'][$fh] = ['mode' => 'w', 'key' => $key, 'buf' => ''];
        return true;
    }
    if ($mode === '>>') {
        if (!$st->append($key, '')) {
            return false;
        }
        $GLOBALS['__PL_FH'][$fh] = ['mode' => 'a', 'key' => $key, 'buf' => ''];
        return true;
    }
    return false;
}

function pl_readline(string $fh): ?string
{
    $h = &$GLOBALS['__PL_FH'][$fh];
    if (!isset($h) || $h['mode'] !== 'r') {
        return null;
    }
    if ($h['pos'] >= count($h['lines'])) {
        return null;
    }
    return $h['lines'][$h['pos']++];
}

function pl_readlines(string $fh): array
{
    $h = &$GLOBALS['__PL_FH'][$fh];
    if (!isset($h) || $h['mode'] !== 'r') {
        return [];
    }
    $out = array_slice($h['lines'], $h['pos']);
    $h['pos'] = count($h['lines']);
    return $out;
}

function pl_print(string $fh, mixed $data): bool
{
    if (!isset($GLOBALS['__PL_FH'][$fh])) {
        return false;
    }
    $h = &$GLOBALS['__PL_FH'][$fh];
    if ($h['mode'] === 'r') {
        return false;
    }
    $h['buf'] .= is_array($data) ? implode('', $data) : (string)$data;
    return true;
}

function pl_close(string $fh): bool
{
    if (!isset($GLOBALS['__PL_FH'][$fh])) {
        return false;
    }
    $h = $GLOBALS['__PL_FH'][$fh];
    unset($GLOBALS['__PL_FH'][$fh]);
    if ($h['mode'] === 'w') {
        return town_storage()->write($h['key'], $h['buf']);
    }
    if ($h['mode'] === 'a' && $h['buf'] !== '') {
        return town_storage()->append($h['key'], $h['buf']);
    }
    return true;
}

function pl_close_all(): void
{
    foreach (array_keys($GLOBALS['__PL_FH'] ?? []) as $fh) {
        pl_close((string)$fh);
    }
}

function pl_exists(mixed $p): bool
{
    $k = pl_key($p);
    return $k !== null && town_storage()->exists($k);
}

function pl_isdir(mixed $p): bool
{
    $k = pl_key($p);
    return $k !== null && town_storage()->isDir($k);
}

function pl_isfile(mixed $p): bool
{
    $k = pl_key($p);
    return $k !== null && town_storage()->isFile($k);
}

function pl_filesize(mixed $p): int|false
{
    $k = pl_key($p);
    $d = $k === null ? null : town_storage()->read($k);
    return ($d === null || $d === '') ? false : strlen($d);
}

function pl_iszero(mixed $p): bool
{
    $k = pl_key($p);
    $d = $k === null ? null : town_storage()->read($k);
    return $d === '';
}

function pl_age_days(mixed $p): float|false
{
    $k = pl_key($p);
    $t = $k === null ? null : town_storage()->mtime($k);
    return $t === null ? false : (time() - $t) / 86400;
}

function pl_mkdir(mixed $p): bool
{
    $k = pl_key($p);
    return $k !== null && town_storage()->mkdir($k);
}

function pl_rmdir(mixed $p): bool
{
    $k = pl_key($p);
    return $k !== null && town_storage()->rmdir($k);
}

function pl_unlink(mixed ...$paths): int
{
    $n = 0;
    foreach ($paths as $p) {
        $k = pl_key($p);
        if ($k !== null && town_storage()->unlink($k)) {
            $n++;
        }
    }
    return $n;
}

function pl_rename(mixed $a, mixed $b): bool
{
    $ka = pl_key($a);
    $kb = pl_key($b);
    return $ka !== null && $kb !== null && town_storage()->rename($ka, $kb);
}

function pl_stat(mixed $p): array
{
    $k = pl_key($p);
    $t = $k === null ? null : town_storage()->mtime($k);
    if ($t === null) {
        return [];
    }
    return [0, 0, 0, 1, 0, 0, 0, 0, $t, $t, $t, 4096, 0];
}

/** new DirHandle(path) の代替 */
function pl_opendir(mixed $p): ?array
{
    $k = pl_key($p);
    if ($k === null || !town_storage()->isDir($k)) {
        return null;
    }
    $GLOBALS['__PL_DIR_SEQ'] = ($GLOBALS['__PL_DIR_SEQ'] ?? 0) + 1;
    $id = $GLOBALS['__PL_DIR_SEQ'];
    $GLOBALS['__PL_DIRS'][$id] = ['.', '..', ...town_storage()->listDir($k)];
    return ['dir' => $id];
}

function pl_readdir(mixed $d): ?string
{
    if (!is_array($d) || !isset($GLOBALS['__PL_DIRS'][$d['dir']])) {
        return null;
    }
    $v = array_shift($GLOBALS['__PL_DIRS'][$d['dir']]);
    return $v;
}

function pl_closedir(mixed $d): bool
{
    if (is_array($d)) {
        unset($GLOBALS['__PL_DIRS'][$d['dir']]);
    }
    return true;
}

/** 元の data_save(path, @lines) */
function pl_data_save(mixed $path, mixed ...$lines): string
{
    $k = pl_key($path);
    if ($k === null) {
        return 'bad Filename';
    }
    $data = '';
    foreach ($lines as $l) {
        $data .= is_array($l) ? implode('', $l) : (string)$l;
    }
    return town_storage()->write($k, $data) ? '' : 'Write Error : ' . $path;
}

/** 元の data_read(path) */
function pl_data_read(mixed $path): array
{
    $k = pl_key($path);
    $d = $k === null ? null : town_storage()->read($k);
    return $d === null ? [] : pl_lines($d);
}
