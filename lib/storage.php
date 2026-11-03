<?php
/**
 * データ保存層
 *
 * 元の Perl 版はすべてテキストファイルにデータを保存していた。ゲームロジックはファイルパス
 * （例: ./member/12/log.cgi）単位で読み書きするため、ここではそのパスをキーにした
 * 「仮想ファイルシステム」を提供し、保存先をテキストファイル / SQLite で切り替えられるようにしている。
 *
 * どちらの方式でも 1 リクエストを 1 トランザクション（排他ロック）として扱うため、
 * 元のロックディレクトリ方式よりもデータ破損が起きにくい。
 */

interface TownStorage
{
    public function begin(): void;
    public function commit(): void;
    public function rollback(): void;
    public function read(string $path): ?string;
    public function write(string $path, string $data): bool;
    public function append(string $path, string $data): bool;
    public function exists(string $path): bool;
    public function isDir(string $path): bool;
    public function isFile(string $path): bool;
    public function mkdir(string $path): bool;
    public function rmdir(string $path): bool;
    public function unlink(string $path): bool;
    public function rename(string $from, string $to): bool;
    public function mtime(string $path): ?int;
    /** @return string[] ディレクトリ直下の名前 */
    public function listDir(string $path): array;
}

/**
 * パスを正規化して保存キーにする。ディレクトリトラバーサル等は拒否。
 */
function town_storage_key(string $path): ?string
{
    $p = str_replace('\\', '/', trim($path));
    while (str_starts_with($p, './')) {
        $p = substr($p, 2);
    }
    $p = preg_replace('#/+#', '/', $p);
    $p = rtrim($p, '/');
    if ($p === '' || $p[0] === '/' || !preg_match('#^[A-Za-z0-9_\-./]+$#', $p)) {
        return null;
    }
    foreach (explode('/', $p) as $seg) {
        if ($seg === '' || $seg === '.' || $seg === '..') {
            return null;
        }
    }
    return $p;
}

class FileTownStorage implements TownStorage
{
    private string $base;
    /** @var resource|null */
    private $lockFp = null;

    public function __construct(string $baseDir)
    {
        if (!is_dir($baseDir)) {
            throw new RuntimeException('データフォルダが見つかりません: ' . $baseDir);
        }
        $this->base = rtrim(realpath($baseDir), '/');
    }

    private function full(string $key): string
    {
        return $this->base . '/' . $key;
    }

    public function begin(): void
    {
        if ($this->lockFp) {
            return;
        }
        $fp = fopen($this->base . '/.lock', 'c');
        if (!$fp) {
            throw new RuntimeException('ロックファイルを作成できません');
        }
        $start = microtime(true);
        while (!flock($fp, LOCK_EX | LOCK_NB)) {
            if (microtime(true) - $start > 20) {
                throw new RuntimeException('ただいまサイトが混み合っています。少々お待ちください。');
            }
            usleep(50000);
        }
        $this->lockFp = $fp;
    }

    public function commit(): void
    {
        if ($this->lockFp) {
            flock($this->lockFp, LOCK_UN);
            fclose($this->lockFp);
            $this->lockFp = null;
        }
    }

    public function rollback(): void
    {
        // ファイル方式では書き込み済みの内容は戻せない（元の Perl 版と同じ挙動）
        $this->commit();
    }

    public function read(string $path): ?string
    {
        $f = $this->full($path);
        if (!is_file($f)) {
            return null;
        }
        $d = file_get_contents($f);
        return $d === false ? null : $d;
    }

    public function write(string $path, string $data): bool
    {
        $f = $this->full($path);
        if (!is_dir(dirname($f))) {
            return false;
        }
        $tmp = $f . '.tmp' . getmypid();
        if (file_put_contents($tmp, $data) === false) {
            return false;
        }
        @chmod($tmp, 0640);
        return rename($tmp, $f);
    }

    public function append(string $path, string $data): bool
    {
        $f = $this->full($path);
        if (!is_dir(dirname($f))) {
            return false;
        }
        return file_put_contents($f, $data, FILE_APPEND) !== false;
    }

    public function exists(string $path): bool
    {
        return file_exists($this->full($path));
    }

    public function isDir(string $path): bool
    {
        return is_dir($this->full($path));
    }

    public function isFile(string $path): bool
    {
        return is_file($this->full($path));
    }

    public function mkdir(string $path): bool
    {
        $f = $this->full($path);
        if (file_exists($f) || !is_dir(dirname($f))) {
            return false;
        }
        return @mkdir($f, 0750);
    }

    public function rmdir(string $path): bool
    {
        return @rmdir($this->full($path));
    }

    public function unlink(string $path): bool
    {
        $f = $this->full($path);
        return is_file($f) && @unlink($f);
    }

    public function rename(string $from, string $to): bool
    {
        return @rename($this->full($from), $this->full($to));
    }

    public function mtime(string $path): ?int
    {
        $f = $this->full($path);
        return file_exists($f) ? filemtime($f) : null;
    }

    public function listDir(string $path): array
    {
        $f = $this->full($path);
        if (!is_dir($f)) {
            return [];
        }
        return array_values(array_diff(scandir($f), ['.', '..']));
    }
}

class SqliteTownStorage implements TownStorage
{
    private PDO $db;
    private bool $inTx = false;

    public function __construct(string $file, ?string $seedDir = null)
    {
        $dir = dirname($file);
        if (!is_dir($dir)) {
            throw new RuntimeException('SQLite の保存フォルダが見つかりません: ' . $dir);
        }
        $this->db = new PDO('sqlite:' . $file, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        @chmod($file, 0640);
        $this->db->exec('PRAGMA busy_timeout = 20000');
        $this->db->exec('PRAGMA journal_mode = WAL');
        $this->db->exec('PRAGMA synchronous = NORMAL');
        $this->db->exec('CREATE TABLE IF NOT EXISTS town_files (
            path    TEXT PRIMARY KEY,
            is_dir  INTEGER NOT NULL DEFAULT 0,
            content BLOB,
            mtime   INTEGER NOT NULL
        )');
        $cnt = (int)$this->db->query('SELECT COUNT(*) FROM town_files')->fetchColumn();
        if ($cnt === 0 && $seedDir !== null) {
            $this->importDir($seedDir);
        }
    }

    /** テキストファイル形式のデータフォルダを取り込む（初回起動時・移行時） */
    public function importDir(string $seedDir): int
    {
        $seedDir = rtrim(realpath($seedDir) ?: $seedDir, '/');
        $n = 0;
        $this->db->beginTransaction();
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($seedDir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        $ins = $this->db->prepare('INSERT OR REPLACE INTO town_files(path, is_dir, content, mtime) VALUES(?,?,?,?)');
        foreach ($it as $f) {
            $rel = substr($f->getPathname(), strlen($seedDir) + 1);
            $rel = str_replace('\\', '/', $rel);
            if ($rel === '' || str_starts_with(basename($rel), '.') || preg_match('/\.(sqlite|sqlite-wal|sqlite-shm|htaccess)$/', $rel)) {
                continue;
            }
            if (town_storage_key($rel) === null) {
                continue;
            }
            if ($f->isDir()) {
                $ins->execute([$rel, 1, null, $f->getMTime()]);
            } else {
                $ins->bindValue(1, $rel);
                $ins->bindValue(2, 0, PDO::PARAM_INT);
                $ins->bindValue(3, file_get_contents($f->getPathname()), PDO::PARAM_LOB);
                $ins->bindValue(4, $f->getMTime(), PDO::PARAM_INT);
                $ins->execute();
            }
            $n++;
        }
        $this->db->commit();
        return $n;
    }

    /** SQLite の内容をテキストファイル形式で書き出す（バックアップ・移行用） */
    public function exportDir(string $destDir): int
    {
        $n = 0;
        $rows = $this->db->query('SELECT path, is_dir, content, mtime FROM town_files ORDER BY is_dir DESC, path')->fetchAll();
        foreach ($rows as $r) {
            $full = rtrim($destDir, '/') . '/' . $r['path'];
            if ($r['is_dir']) {
                if (!is_dir($full)) {
                    mkdir($full, 0750, true);
                }
            } else {
                if (!is_dir(dirname($full))) {
                    mkdir(dirname($full), 0750, true);
                }
                file_put_contents($full, (string)$r['content']);
                touch($full, (int)$r['mtime']);
            }
            $n++;
        }
        return $n;
    }

    public function begin(): void
    {
        if (!$this->inTx) {
            $this->db->exec('BEGIN IMMEDIATE');
            $this->inTx = true;
        }
    }

    public function commit(): void
    {
        if ($this->inTx) {
            $this->db->exec('COMMIT');
            $this->inTx = false;
        }
    }

    public function rollback(): void
    {
        if ($this->inTx) {
            $this->db->exec('ROLLBACK');
            $this->inTx = false;
        }
    }

    private function row(string $path): ?array
    {
        $st = $this->db->prepare('SELECT path, is_dir, content, mtime FROM town_files WHERE path = ?');
        $st->execute([$path]);
        $r = $st->fetch();
        return $r ?: null;
    }

    private function parentOk(string $path): bool
    {
        $p = dirname($path);
        if ($p === '.' || $p === '') {
            return true;
        }
        $r = $this->row($p);
        return $r !== null && (int)$r['is_dir'] === 1;
    }

    public function read(string $path): ?string
    {
        $r = $this->row($path);
        if ($r === null || (int)$r['is_dir'] === 1) {
            return null;
        }
        return (string)$r['content'];
    }

    public function write(string $path, string $data): bool
    {
        if (!$this->parentOk($path) || $this->isDir($path)) {
            return false;
        }
        $st = $this->db->prepare('INSERT OR REPLACE INTO town_files(path, is_dir, content, mtime) VALUES(?, 0, ?, ?)');
        $st->bindValue(1, $path);
        $st->bindValue(2, $data, PDO::PARAM_LOB);
        $st->bindValue(3, time(), PDO::PARAM_INT);
        return $st->execute();
    }

    public function append(string $path, string $data): bool
    {
        $cur = $this->read($path);
        return $this->write($path, ($cur ?? '') . $data);
    }

    public function exists(string $path): bool
    {
        return $this->row($path) !== null;
    }

    public function isDir(string $path): bool
    {
        $r = $this->row($path);
        return $r !== null && (int)$r['is_dir'] === 1;
    }

    public function isFile(string $path): bool
    {
        $r = $this->row($path);
        return $r !== null && (int)$r['is_dir'] === 0;
    }

    public function mkdir(string $path): bool
    {
        if ($this->exists($path) || !$this->parentOk($path)) {
            return false;
        }
        $st = $this->db->prepare('INSERT INTO town_files(path, is_dir, content, mtime) VALUES(?, 1, NULL, ?)');
        return $st->execute([$path, time()]);
    }

    public function rmdir(string $path): bool
    {
        if (!$this->isDir($path) || $this->listDir($path)) {
            return false;
        }
        $st = $this->db->prepare('DELETE FROM town_files WHERE path = ?');
        return $st->execute([$path]);
    }

    public function unlink(string $path): bool
    {
        if (!$this->isFile($path)) {
            return false;
        }
        $st = $this->db->prepare('DELETE FROM town_files WHERE path = ? AND is_dir = 0');
        return $st->execute([$path]);
    }

    public function rename(string $from, string $to): bool
    {
        if (!$this->exists($from) || !$this->parentOk($to)) {
            return false;
        }
        $this->db->prepare('DELETE FROM town_files WHERE path = ?')->execute([$to]);
        $st = $this->db->prepare('UPDATE town_files SET path = ? || substr(path, ?) WHERE path = ? OR path LIKE ? ESCAPE \'\\\'');
        $like = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $from) . '/%';
        return $st->execute([$to, strlen($from) + 1, $from, $like]);
    }

    public function mtime(string $path): ?int
    {
        $r = $this->row($path);
        return $r ? (int)$r['mtime'] : null;
    }

    public function listDir(string $path): array
    {
        $like = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $path) . '/%';
        $st = $this->db->prepare('SELECT path FROM town_files WHERE path LIKE ? ESCAPE \'\\\'');
        $st->execute([$like]);
        $out = [];
        $plen = strlen($path) + 1;
        foreach ($st->fetchAll(PDO::FETCH_COLUMN) as $p) {
            $rest = substr($p, $plen);
            if ($rest !== '' && !str_contains($rest, '/')) {
                $out[] = $rest;
            }
        }
        sort($out);
        return $out;
    }
}

/**
 * 設定（town_ini.php）に従ってストレージを生成する。
 */
function town_storage_if_started(): ?TownStorage
{
    return $GLOBALS['__TOWN_STORAGE'] ?? null;
}

function town_storage(): TownStorage
{
    if (isset($GLOBALS['__TOWN_STORAGE'])) {
        return $GLOBALS['__TOWN_STORAGE'];
    }
    global $storage_type, $data_dir, $sqlite_file;
    $base = TOWN_ROOT . '/' . ltrim(preg_replace('#^\./#', '', (string)($data_dir ?? 'data')), '/');
    if (($storage_type ?? 'file') === 'sqlite') {
        $file = (string)($sqlite_file ?? './data/town.sqlite');
        if (!str_starts_with($file, '/')) {
            $file = TOWN_ROOT . '/' . preg_replace('#^\./#', '', $file);
        }
        $st = new SqliteTownStorage($file, $base);
    } else {
        $st = new FileTownStorage($base);
    }
    $st->begin();
    $GLOBALS['__TOWN_STORAGE'] = $st;
    return $st;
}
