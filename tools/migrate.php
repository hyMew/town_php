<?php
/**
 * データ移行ツール（コマンドラインから実行してください）
 *
 *   php tools/migrate.php import-perl <旧Perl版のフォルダ>
 *       旧 Perl 版（Shift_JIS）の log_dir / member / dat_dir / guestlog.cgi を
 *       UTF-8 に変換して、現在の設定のデータフォルダ（$data_dir）へコピーします。
 *       ※パスワードは各ユーザーの次回ログイン時に自動でハッシュ化されます。
 *
 *   php tools/migrate.php file-to-sqlite
 *       データフォルダ（テキストファイル形式）の内容を SQLite（$sqlite_file）へ取り込みます。
 *
 *   php tools/migrate.php sqlite-to-file <出力先フォルダ>
 *       SQLite の内容をテキストファイル形式で書き出します（バックアップ・file 方式への切替用）。
 */
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Forbidden');
}

define('TOWN_ROOT', dirname(__DIR__));
require TOWN_ROOT . '/lib/storage.php';
require TOWN_ROOT . '/town_ini.php';

$cmd = $argv[1] ?? '';
$dataDir = TOWN_ROOT . '/' . preg_replace('#^\./#', '', (string)$data_dir);
$sqlite = (string)$sqlite_file;
if (!str_starts_with($sqlite, '/')) {
    $sqlite = TOWN_ROOT . '/' . preg_replace('#^\./#', '', $sqlite);
}

function conv_tree(string $src, string $dst): int
{
    $n = 0;
    if (!is_dir($dst)) {
        mkdir($dst, 0750, true);
    }
    foreach (scandir($src) as $f) {
        if ($f === '.' || $f === '..') {
            continue;
        }
        $s = "$src/$f";
        $d = "$dst/$f";
        if (is_dir($s)) {
            $n += conv_tree($s, $d);
        } else {
            $raw = file_get_contents($s);
            $utf = mb_convert_encoding($raw, 'UTF-8', 'SJIS-win');
            $utf = str_replace("\r\n", "\n", $utf);
            file_put_contents($d, $utf);
            touch($d, filemtime($s));
            $n++;
        }
    }
    return $n;
}

switch ($cmd) {
    case 'import-perl':
        $old = rtrim($argv[2] ?? '', '/');
        if ($old === '' || !is_dir("$old/log_dir") || !is_dir("$old/member")) {
            fwrite(STDERR, "旧 Perl 版のフォルダ（log_dir と member を含む）を指定してください。\n");
            exit(1);
        }
        $n = 0;
        foreach (['log_dir', 'member', 'dat_dir'] as $d) {
            if (is_dir("$old/$d")) {
                $n += conv_tree("$old/$d", "$dataDir/$d");
            }
        }
        if (is_file("$old/guestlog.cgi")) {
            file_put_contents("$dataDir/guestlog.cgi", mb_convert_encoding(file_get_contents("$old/guestlog.cgi"), 'UTF-8', 'SJIS-win'));
            $n++;
        }
        if (!is_dir("$dataDir/lock")) {
            mkdir("$dataDir/lock", 0750);
        }
        echo "$n 個のファイルを変換しました → $dataDir\n";
        if (($storage_type ?? 'file') === 'sqlite') {
            echo "SQLite 方式の場合は続けて「php tools/migrate.php file-to-sqlite」を実行してください。\n";
        }
        break;

    case 'file-to-sqlite':
        if (is_file($sqlite)) {
            fwrite(STDERR, "既に $sqlite が存在します。上書きする場合は先に削除（またはバックアップ）してください。\n");
            exit(1);
        }
        $st = new SqliteTownStorage($sqlite, null);
        $n = $st->importDir($dataDir);
        echo "$n 件を取り込みました → $sqlite\n";
        break;

    case 'sqlite-to-file':
        $dest = $argv[2] ?? '';
        if ($dest === '') {
            fwrite(STDERR, "出力先フォルダを指定してください。\n");
            exit(1);
        }
        if (!is_file($sqlite)) {
            fwrite(STDERR, "$sqlite が見つかりません。\n");
            exit(1);
        }
        $st = new SqliteTownStorage($sqlite, null);
        $n = $st->exportDir($dest);
        echo "$n 件を書き出しました → $dest\n";
        break;

    default:
        $doc = file_get_contents(__FILE__);
        preg_match('#/\*\*(.*?)\*/#s', $doc, $m);
        echo preg_replace('/^\s*\* ?/m', '', $m[1]), "\n";
        exit($cmd === '' ? 0 : 1);
}
