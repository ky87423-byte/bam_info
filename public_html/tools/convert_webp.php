<?php
/**
 * BAMtube 기존 이미지 WebP 일괄 변환 스크립트
 *
 * 실행 방법 (서버 SSH):
 *   php tools/convert_webp.php            → 실제 변환 + DB 업데이트 + 원본 삭제
 *   php tools/convert_webp.php --dry-run  → 실제 파일 변환 없이 변환 대상/예상 절감량만 확인
 *   php tools/convert_webp.php --no-db    → 파일만 변환, DB 업데이트 생략
 *
 * ⚠️  반드시 CLI(SSH 터미널)에서만 실행하세요. 웹 브라우저로 접근 시 즉시 종료됩니다.
 * ⚠️  실행 전 DB 백업을 권장합니다: mysqldump -u USER -p DB_NAME nf_shop > nf_shop_backup.sql
 */

// ── 웹 접근 차단 ──────────────────────────────────────────────────────────────
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit("CLI only.\n");
}

// ── 설정 ──────────────────────────────────────────────────────────────────────
define('NFE_PATH', dirname(__DIR__));   // public_html 루트
define('WEBP_QUALITY', 82);            // WebP 품질 (75~85 권장)
define('MAX_WIDTH',   1920);           // 최대 가로 px
define('MAX_HEIGHT',  1920);           // 최대 세로 px
define('SCAN_DIR', NFE_PATH.'/data/shop');  // 스캔 대상 디렉터리

// ── 인수 파싱 ─────────────────────────────────────────────────────────────────
$dry_run  = in_array('--dry-run', $argv);
$no_db    = in_array('--no-db',   $argv);

// ── GD WebP 지원 확인 ─────────────────────────────────────────────────────────
if (!function_exists('imagewebp')) {
    exit("[ERROR] PHP GD 확장이 imagewebp()를 지원하지 않습니다.\n"
        ."       GD 재컴파일 또는 php-gd 패키지를 확인하세요.\n");
}

// ── DB 연결 ───────────────────────────────────────────────────────────────────
if (!$no_db && !$dry_run) {
    $db_conf_path = NFE_PATH.'/data/db_config.php';
    if (!file_exists($db_conf_path)) exit("[ERROR] db_config.php 를 찾을 수 없습니다.\n");

    // db_config.php 는 변수를 define 하거나 $db_* 변수로 세팅할 수 있음
    // 직접 include 후 변수를 읽음
    include $db_conf_path;

    // db_config.php 가 어떤 방식으로 설정을 노출하는지 자동 탐지
    $db_host = defined('DB_HOST') ? DB_HOST : (isset($db_host) ? $db_host : 'localhost');
    $db_name = defined('DB_NAME') ? DB_NAME : (isset($db_name) ? $db_name : '');
    $db_user = defined('DB_USER') ? DB_USER : (isset($db_user) ? $db_user : '');
    $db_pass = defined('DB_PASS') ? DB_PASS : (isset($db_pass) ? $db_pass : '');

    $pdo = new PDO(
        "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4",
        $db_user, $db_pass,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
}

// ── 유틸 함수 ─────────────────────────────────────────────────────────────────
function fmt_bytes($bytes) {
    if ($bytes >= 1073741824) return round($bytes/1073741824, 2).' GB';
    if ($bytes >= 1048576)    return round($bytes/1048576, 2).' MB';
    return round($bytes/1024, 2).' KB';
}

function load_image($path, $type) {
    switch ($type) {
        case 1:  return @imagecreatefromgif($path);
        case 2:  return @imagecreatefromjpeg($path);
        case 3:  return @imagecreatefrompng($path);
        case 18: return function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false;
    }
    return false;
}

function resize_and_save_webp($src_path, $dst_path, $max_w, $max_h, $quality) {
    $info = @getimagesize($src_path);
    if (!$info || !in_array($info[2], array(1,2,3,18))) return false;

    $ow = $info[0]; $oh = $info[1]; $type = $info[2];
    $per = 1.0;
    if ($ow > $max_w) $per = $max_w / $ow;
    if ($oh * $per > $max_h) $per = $max_h / $oh;
    $nw = (int)($ow * $per);
    $nh = (int)($oh * $per);

    $src = load_image($src_path, $type);
    if (!$src) return false;

    $dst = imagecreatetruecolor($nw, $nh);
    // 투명 → 흰 배경 합성
    $white = imagecolorallocate($dst, 255, 255, 255);
    imagefill($dst, 0, 0, $white);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $ow, $oh);
    imagedestroy($src);

    $ok = imagewebp($dst, $dst_path, $quality);
    imagedestroy($dst);
    return $ok;
}

// ── 스캔: YYYYMM 서브디렉터리의 이미지 파일 수집 ──────────────────────────────
$targets = array(); // ['orig'=>..., 'webp'=>..., 'rel'=>...] (rel = YYYYMM/filename)
$skip_dirs = array('tmp', 'icon');

$dir_iter = new DirectoryIterator(SCAN_DIR);
foreach ($dir_iter as $sub) {
    if (!$sub->isDir() || $sub->isDot()) continue;
    $dname = $sub->getFilename();
    if (in_array($dname, $skip_dirs)) continue;

    $sub_path = SCAN_DIR.'/'.$dname;
    foreach (new DirectoryIterator($sub_path) as $file) {
        if (!$file->isFile()) continue;
        $ext = strtolower($file->getExtension());
        if (!in_array($ext, array('jpg','jpeg','png','gif'))) continue;

        $orig = $file->getPathname();
        $base = $file->getBasename('.'.$file->getExtension());
        $webp = $sub_path.'/'.$base.'.webp';
        $rel  = $dname.'/'.$file->getFilename();

        $targets[] = array('orig' => $orig, 'webp' => $webp, 'rel' => $rel,
                           'base' => $base, 'dir' => $dname, 'ext' => $ext);
    }
}

$total = count($targets);
echo "==========================================================\n";
echo " BAMtube WebP 일괄 변환\n";
echo " 대상 파일: {$total}개".($dry_run ? " [DRY-RUN 모드]" : "")."\n";
echo "==========================================================\n\n";

if ($total === 0) {
    echo "변환할 파일이 없습니다.\n";
    exit(0);
}

// ── 변환 루프 ─────────────────────────────────────────────────────────────────
$done = 0; $fail = 0;
$saved_bytes = 0;
$orig_total_bytes = 0;
$db_updates = array(); // ['orig_rel' => 'new_rel']

foreach ($targets as $i => $t) {
    $orig_size = filesize($t['orig']);
    $orig_total_bytes += $orig_size;

    $new_rel  = $t['dir'].'/'.$t['base'].'.webp';

    if ($dry_run) {
        // dry-run: getimagesize만 확인
        $info = @getimagesize($t['orig']);
        $ok   = ($info && in_array($info[2], array(1,2,3,18)));
        $status = $ok ? '[OK]' : '[SKIP]';
        printf("  %s %s (%s)\n", $status, $t['rel'], fmt_bytes($orig_size));
        if ($ok) $done++;
        continue;
    }

    // 이미 변환된 경우 건너뜀
    if (file_exists($t['webp'])) {
        $webp_size = filesize($t['webp']);
        $saved_bytes += ($orig_size - $webp_size);
        printf("  [SKIP-EXISTS] %s\n", $t['rel']);
        $db_updates[$t['rel']] = $new_rel;
        $done++;
        continue;
    }

    $ok = resize_and_save_webp($t['orig'], $t['webp'], MAX_WIDTH, MAX_HEIGHT, WEBP_QUALITY);
    if ($ok) {
        $webp_size    = filesize($t['webp']);
        $saving       = $orig_size - $webp_size;
        $saved_bytes += $saving;
        $ratio        = $orig_size > 0 ? round((1 - $webp_size/$orig_size)*100) : 0;
        printf("  [OK] %s  %s → %s (-%d%%)\n",
            $t['rel'], fmt_bytes($orig_size), fmt_bytes($webp_size), $ratio);

        $db_updates[$t['rel']] = $new_rel;
        $done++;
    } else {
        printf("  [FAIL] %s\n", $t['rel']);
        $fail++;
        if (file_exists($t['webp'])) @unlink($t['webp']); // 불완전 파일 삭제
    }

    // 진행 표시 (100개마다)
    if (($i+1) % 100 === 0) {
        echo "  ... {$done}/".($done+$fail)." 완료, 절감 ".fmt_bytes($saved_bytes)."\n";
    }
}

// ── DB 업데이트 ───────────────────────────────────────────────────────────────
if (!$dry_run && !$no_db && !empty($db_updates)) {
    echo "\n[DB] nf_shop 파일명 업데이트 중 ...\n";

    // wr_photo, wr_main_photo 컬럼에서 원본 파일명을 WebP 로 치환
    // wr_photo 예: "202401/photo_xxx.jpg,202401/photo_yyy.jpg,"
    // wr_main_photo 예: "202401/photo_xxx.jpg"

    $db_done = 0;
    foreach ($db_updates as $orig_rel => $new_rel) {
        $orig_basename = basename($orig_rel); // "photo_xxx.jpg"
        $new_basename  = basename($new_rel);  // "photo_xxx.webp"

        // wr_photo (콤마 구분 경로 문자열)
        $pdo->prepare(
            "UPDATE nf_shop SET wr_photo = REPLACE(wr_photo, ?, ?) WHERE wr_photo LIKE ?"
        )->execute(array($orig_rel, $new_rel, '%'.$orig_rel.'%'));

        // wr_main_photo
        $pdo->prepare(
            "UPDATE nf_shop SET wr_main_photo = REPLACE(wr_main_photo, ?, ?) WHERE wr_main_photo LIKE ?"
        )->execute(array($orig_rel, $new_rel, '%'.$orig_rel.'%'));

        $db_done++;
        if ($db_done % 500 === 0) echo "  ... DB {$db_done}건 처리\n";
    }
    echo "  [DB] {$db_done}건 업데이트 완료\n";
}

// ── 원본 삭제 (DB 업데이트 성공 후) ──────────────────────────────────────────
if (!$dry_run && !empty($db_updates)) {
    echo "\n[CLEAN] 원본 파일 삭제 중 ...\n";
    $del_count = 0;
    foreach ($db_updates as $orig_rel => $new_rel) {
        $orig_path = SCAN_DIR.'/'.$orig_rel;
        if (file_exists($orig_path) && file_exists(SCAN_DIR.'/'.$new_rel)) {
            @unlink($orig_path);
            $del_count++;
        }
    }
    echo "  [CLEAN] {$del_count}개 원본 삭제 완료\n";
}

// ── 요약 ─────────────────────────────────────────────────────────────────────
echo "\n==========================================================\n";
if ($dry_run) {
    echo " [DRY-RUN] 변환 가능: {$done}개 / 전체 {$total}개\n";
    echo " 원본 총합: ".fmt_bytes($orig_total_bytes)."\n";
    echo " ※ 실제 절감량은 --dry-run 없이 실행해야 확인됩니다\n";
} else {
    echo " 완료: {$done}개 / 실패: {$fail}개 / 전체: {$total}개\n";
    echo " 원본 총합: ".fmt_bytes($orig_total_bytes)."\n";
    echo " 절감 용량: ".fmt_bytes($saved_bytes)."\n";
    if ($orig_total_bytes > 0)
        echo " 평균 압축률: ".round((1-($orig_total_bytes-$saved_bytes)/$orig_total_bytes)*100)."%\n";
}
echo "==========================================================\n";
