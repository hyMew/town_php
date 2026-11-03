<?php
/**
 * 共通初期化
 */
if (PHP_VERSION_ID < 80300) {
    http_response_code(500);
    exit('PHP 8.3 以上が必要です。');
}

define('TOWN_ROOT', dirname(__DIR__));

// PHP 版のバージョン（原作のバージョン $version と併記して表示）
const TOWN_PHP_VERSION = 'PHPver.0.9';

mb_internal_encoding('UTF-8');
date_default_timezone_set('Asia/Tokyo');
ini_set('precision', '15');          // Perl の数値表示（%.15g）に合わせる
ini_set('display_errors', '0');
ini_set('log_errors', '1');

/*
 * 自動変換コードは Perl の「未定義値は空文字/0 として扱う」挙動に依存しているため、
 * 未定義変数・未定義キー参照の警告は抑止する。それ以外は通常どおりログに残す。
 */
set_error_handler(function (int $no, string $msg, string $file = '', int $line = 0): bool {
    if ($no === E_WARNING || $no === E_NOTICE || $no === E_USER_NOTICE) {
        if (preg_match('/^(Undefined (variable|array key|offset|index|global variable)|Trying to access array offset|foreach\(\) argument must be|Array to string conversion|A non-numeric value|Uninitialized string offset)/', $msg)) {
            return true;
        }
    }
    if ($no === E_DEPRECATED || $no === E_USER_DEPRECATED) {
        return true;
    }
    error_log("[town] $msg in $file:$line");
    return true;
});

require_once __DIR__ . '/storage.php';
require_once __DIR__ . '/runtime.php';
require_once __DIR__ . '/security.php';

/** 補間文字列中の式評価用 ("{$__(式)}") */
$__ = static fn($v) => is_array($v) ? implode(' ', $v) : $v;

// 画面中の <!--TW_BACK--> を「戻る」ボタンに置き換える
ob_start(static function (string $buf): string {
    if (str_contains($buf, '<!--TW_BACK-->')) {
        // 元から「戻る」ボタンがある画面では追加しない（重複防止）
        if (preg_match('/<input[^>]*type=["\']?submit["\']?[^>]*value=["\']?\s*戻る/u', $buf)) {
            return str_replace('<!--TW_BACK-->', '', $buf);
        }
        try {
            [$button, $form] = tw_back_html();
            $buf = str_replace('<!--TW_BACK-->', $button, $buf);
            if ($form !== '') {
                $pos = strripos($buf, '</body>');
                $buf = ($pos === false) ? $buf . $form : substr($buf, 0, $pos) . $form . substr($buf, $pos);
            }
        } catch (Throwable $e) {
            $buf = str_replace('<!--TW_BACK-->', '<a class="tw-back" href="town_maker.php">戻る</a>', $buf);
        }
    }
    return $buf;
});

register_shutdown_function(function () {
    $err = error_get_last();
    $fatal = $err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true);
    try {
        if ($fatal) {
            if (function_exists('town_storage_if_started') && ($st = town_storage_if_started())) {
                $st->rollback();
            }
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            if (!headers_sent()) {
                http_response_code(500);
                header('Content-Type: text/html; charset=UTF-8');
            }
            echo '<html><body><div align="center"><br>システムエラーが発生しました。時間をおいて再度お試しください。</div></body></html>';
            error_log('[town] FATAL ' . $err['message'] . ' in ' . $err['file'] . ':' . $err['line']);
            return;
        }
        pl_close_all();
        if ($st = town_storage_if_started()) {
            $st->commit();
        }
    } catch (Throwable $e) {
        error_log('[town] shutdown: ' . $e->getMessage());
    }
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_write_close();
    }
    while (ob_get_level() > 0) {
        ob_end_flush();
    }
});

set_exception_handler(function (Throwable $e) {
    error_log('[town] ' . get_class($e) . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    if ($st = town_storage_if_started()) {
        try {
            $st->rollback();
        } catch (Throwable $e2) {
        }
    }
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/html; charset=UTF-8');
    }
    $msg = $e instanceof RuntimeException ? htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') : 'システムエラーが発生しました。';
    echo '<html><body><div align="center"><br>' . $msg . '</div></body></html>';
});
