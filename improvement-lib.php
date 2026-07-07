<?php

function ck_log_start_session() {
  if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
  }
}

function ck_h($value) {
  return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function ck_log_media_src($path, $base = '') {
  $path = trim((string) $path);
  if ($path === '') {
    return '';
  }

  if (preg_match('/^[a-z][a-z0-9+.-]*:/i', $path) && !preg_match('/^https?:\/\//i', $path)) {
    return '';
  }

  if (preg_match('/^https?:\/\//i', $path)) {
    return $path;
  }

  $path = str_replace('\\', '/', $path);
  $path = ltrim($path, '/');
  $parts = explode('/', $path);
  if (in_array('..', $parts, true)) {
    return '';
  }

  return $base . $path;
}

function ck_log_delete_media_file($path) {
  $path = ck_log_media_src($path);
  if ($path === '' || preg_match('/^https?:\/\//i', $path)) {
    return false;
  }

  $allowedPrefixes = [
    'image/improvements/',
    'video/improvements/',
  ];
  $allowed = false;
  foreach ($allowedPrefixes as $prefix) {
    if (strpos($path, $prefix) === 0) {
      $allowed = true;
      break;
    }
  }
  if (!$allowed) {
    return false;
  }

  $target = __DIR__ . '/' . $path;
  if (!is_file($target)) {
    return false;
  }

  $realTarget = realpath($target);
  if ($realTarget === false) {
    return false;
  }

  $allowedDirs = [
    realpath(__DIR__ . '/image/improvements'),
    realpath(__DIR__ . '/video/improvements'),
  ];
  foreach ($allowedDirs as $allowedDir) {
    if ($allowedDir !== false && strpos($realTarget, $allowedDir . DIRECTORY_SEPARATOR) === 0) {
      return @unlink($realTarget);
    }
  }

  return false;
}

function ck_log_video_type($path) {
  $extension = strtolower(pathinfo(parse_url((string) $path, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION));
  $types = [
    'mp4' => 'video/mp4',
    'm4v' => 'video/mp4',
    'webm' => 'video/webm',
    'mov' => 'video/quicktime',
  ];

  return $types[$extension] ?? '';
}

function ck_log_images($images) {
  if (!is_array($images)) {
    return [];
  }

  $paths = [];
  foreach ($images as $image) {
    $path = is_array($image) ? ($image['path'] ?? '') : $image;
    $path = ck_log_media_src($path);
    if ($path !== '') {
      $paths[] = $path;
    }
  }

  return array_values(array_slice($paths, 0, 5));
}

function ck_log_image_upload_ready() {
  return function_exists('imagewebp')
    && function_exists('imagecreatetruecolor')
    && function_exists('getimagesize');
}

function ck_log_image_resource($path, $mime) {
  if ($mime === 'image/jpeg' && function_exists('imagecreatefromjpeg')) {
    return imagecreatefromjpeg($path);
  }
  if ($mime === 'image/png' && function_exists('imagecreatefrompng')) {
    return imagecreatefrompng($path);
  }
  if ($mime === 'image/webp' && function_exists('imagecreatefromwebp')) {
    return imagecreatefromwebp($path);
  }

  return false;
}

function ck_log_save_image_resource($source, $slug, $prefix, &$error) {
  $sourceWidth = (int) imagesx($source);
  $sourceHeight = (int) imagesy($source);
  $maxSize = 1600;
  $ratio = min(1, $maxSize / max($sourceWidth, $sourceHeight));
  $targetWidth = max(1, (int) round($sourceWidth * $ratio));
  $targetHeight = max(1, (int) round($sourceHeight * $ratio));

  $target = imagecreatetruecolor($targetWidth, $targetHeight);
  imagealphablending($target, true);
  imagesavealpha($target, true);
  imagecopyresampled($target, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);

  $uploadDir = __DIR__ . '/image/improvements';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
  }

  $baseName = strtolower(preg_replace('/[^a-z0-9-]+/i', '-', $slug));
  $baseName = trim($baseName, '-') ?: $prefix;
  $fileName = $baseName . '-' . date('YmdHis') . '-' . substr(bin2hex(random_bytes(3)), 0, 6) . '.webp';
  $uploadPath = $uploadDir . '/' . $fileName;

  $saved = imagewebp($target, $uploadPath, 82);
  imagedestroy($target);

  if (!$saved) {
    $error = '画像をWebP形式で保存できませんでした。';
    return '';
  }

  return 'image/improvements/' . $fileName;
}

function ck_log_save_uploaded_image($file, $slug, &$error) {
  if (!ck_log_image_upload_ready()) {
    $error = '画像処理機能（GD/WebP）が有効ではありません。サーバー設定を確認してください。';
    return '';
  }

  if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    $error = '画像をアップロードできませんでした。';
    return '';
  }

  $info = @getimagesize($file['tmp_name'] ?? '');
  if (!$info || empty($info['mime'])) {
    $error = '画像ファイルを確認できませんでした。';
    return '';
  }

  $mime = $info['mime'];
  $source = ck_log_image_resource($file['tmp_name'], $mime);
  if (!$source) {
    $error = '対応している画像形式は jpg、png、webp です。';
    return '';
  }

  $sourceWidth = (int) imagesx($source);
  $sourceHeight = (int) imagesy($source);
  $maxSize = 1600;
  $ratio = min(1, $maxSize / max($sourceWidth, $sourceHeight));
  $targetWidth = max(1, (int) round($sourceWidth * $ratio));
  $targetHeight = max(1, (int) round($sourceHeight * $ratio));

  $target = imagecreatetruecolor($targetWidth, $targetHeight);
  imagealphablending($target, true);
  imagesavealpha($target, true);
  imagecopyresampled($target, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);

  $uploadDir = __DIR__ . '/image/improvements';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
  }

  $baseName = strtolower(preg_replace('/[^a-z0-9-]+/i', '-', $slug));
  $baseName = trim($baseName, '-') ?: 'improvement-image';
  $fileName = $baseName . '-' . date('YmdHis') . '-' . substr(bin2hex(random_bytes(3)), 0, 6) . '.webp';
  $uploadPath = $uploadDir . '/' . $fileName;

  $saved = imagewebp($target, $uploadPath, 82);
  imagedestroy($source);
  imagedestroy($target);

  if (!$saved) {
    $error = '画像をWebP形式で保存できませんでした。';
    return '';
  }

  return 'image/improvements/' . $fileName;
}

function ck_log_save_data_image($dataUrl, $slug, $prefix, &$error) {
  if (!ck_log_image_upload_ready() || !function_exists('imagecreatefromstring')) {
    $error = '画像処理機能（GD/WebP）が有効ではありません。サーバー設定を確認してください。';
    return '';
  }

  $dataUrl = trim((string) $dataUrl);
  if (!preg_match('/^data:image\/(?:webp|png|jpeg);base64,([A-Za-z0-9+\/=]+)$/', $dataUrl, $matches)) {
    $error = '動画キャプチャ画像を確認できませんでした。';
    return '';
  }

  $binary = base64_decode($matches[1], true);
  if ($binary === false || strlen($binary) > 8 * 1024 * 1024) {
    $error = '動画キャプチャ画像を保存できませんでした。';
    return '';
  }

  $source = imagecreatefromstring($binary);
  if (!$source) {
    $error = '動画キャプチャ画像を読み込めませんでした。';
    return '';
  }

  $sourceWidth = (int) imagesx($source);
  $sourceHeight = (int) imagesy($source);
  $maxSize = 1600;
  $ratio = min(1, $maxSize / max($sourceWidth, $sourceHeight));
  $targetWidth = max(1, (int) round($sourceWidth * $ratio));
  $targetHeight = max(1, (int) round($sourceHeight * $ratio));

  $target = imagecreatetruecolor($targetWidth, $targetHeight);
  imagealphablending($target, true);
  imagesavealpha($target, true);
  imagecopyresampled($target, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);

  $uploadDir = __DIR__ . '/image/improvements';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
  }

  $baseName = strtolower(preg_replace('/[^a-z0-9-]+/i', '-', $slug));
  $baseName = trim($baseName, '-') ?: $prefix;
  $fileName = $baseName . '-capture-' . date('YmdHis') . '-' . substr(bin2hex(random_bytes(3)), 0, 6) . '.webp';
  $uploadPath = $uploadDir . '/' . $fileName;

  $saved = imagewebp($target, $uploadPath, 82);
  imagedestroy($source);
  imagedestroy($target);

  if (!$saved) {
    $error = '動画キャプチャ画像をWebP形式で保存できませんでした。';
    return '';
  }

  return 'image/improvements/' . $fileName;
}

function ck_log_config() {
  $path = __DIR__ . '/data/improvement-config.php';
  $config = is_readable($path) ? require $path : [];

  return array_merge([
    'fc_password_hash' => '',
    'admin_password_hash' => '',
  ], is_array($config) ? $config : []);
}

function ck_log_password_verify($role, $password) {
  $config = ck_log_config();
  $key = $role === 'admin' ? 'admin_password_hash' : 'fc_password_hash';
  $hash = $config[$key] ?? '';

  return $hash !== '' && password_verify((string) $password, $hash);
}

function ck_log_auth_ready($role) {
  $config = ck_log_config();
  $key = $role === 'admin' ? 'admin_password_hash' : 'fc_password_hash';
  return !empty($config[$key]);
}

function ck_log_data_path() {
  return __DIR__ . '/data/improvements.json';
}

function ck_log_load_all() {
  $path = ck_log_data_path();
  if (!is_readable($path)) {
    return [];
  }

  $json = file_get_contents($path);
  $items = json_decode($json, true);

  if (!is_array($items)) {
    return [];
  }

  ck_log_sort($items);
  return $items;
}

function ck_log_save_all($items) {
  ck_log_sort($items);
  $json = json_encode(array_values($items), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  return file_put_contents(ck_log_data_path(), $json . PHP_EOL, LOCK_EX) !== false;
}

function ck_log_sort(&$items) {
  usort($items, function ($a, $b) {
    $dateCompare = strcmp($b['published_at'] ?? '', $a['published_at'] ?? '');
    if ($dateCompare !== 0) return $dateCompare;
    return strcmp($b['number'] ?? '', $a['number'] ?? '');
  });
}

function ck_log_published($items) {
  return array_values(array_filter($items, function ($item) {
    return ($item['status'] ?? 'published') === 'published';
  }));
}

function ck_log_public_items($items) {
  return array_values(array_filter(ck_log_published($items), function ($item) {
    return !empty($item['public_visible']);
  }));
}

function ck_log_find_by_slug($items, $slug) {
  foreach ($items as $item) {
    if (($item['slug'] ?? '') === $slug) {
      return $item;
    }
  }

  return null;
}

function ck_log_next_number($items) {
  $max = 0;
  foreach ($items as $item) {
    if (preg_match('/(\d+)/', $item['number'] ?? '', $matches)) {
      $max = max($max, (int) $matches[1]);
    }
  }

  return 'LOG ' . sprintf('%03d', $max + 1);
}

function ck_log_slug($title, $date = '') {
  $base = strtolower(trim($title));
  $base = preg_replace('/[^a-z0-9]+/u', '-', $base);
  $base = trim($base, '-');

  if ($base === '') {
    $base = 'log-' . preg_replace('/[^0-9]/', '', $date ?: date('Ymd')) . '-' . substr(md5($title . microtime(true)), 0, 6);
  }

  return $base;
}

function ck_log_archives($items) {
  $archives = [];
  foreach ($items as $item) {
    $date = $item['published_at'] ?? '';
    if (!preg_match('/^(\d{4})-(\d{2})-\d{2}$/', $date, $matches)) {
      continue;
    }

    $year = $matches[1];
    $month = $matches[2];
    if (!isset($archives[$year])) $archives[$year] = [];
    if (!isset($archives[$year][$month])) $archives[$year][$month] = 0;
    $archives[$year][$month]++;
  }

  krsort($archives);
  foreach ($archives as &$months) {
    krsort($months);
  }

  return $archives;
}

function ck_log_selected_items($items, $year, $month) {
  return array_values(array_filter($items, function ($item) use ($year, $month) {
    $date = $item['published_at'] ?? '';
    if ($year !== '' && strpos($date, $year . '-') !== 0) return false;
    if ($month !== '' && !preg_match('/^\d{4}-' . preg_quote($month, '/') . '-/u', $date)) return false;
    return true;
  }));
}

function ck_log_is_fc_logged_in() {
  ck_log_start_session();
  return !empty($_SESSION['ck_improvement_fc']);
}

function ck_log_is_admin_logged_in() {
  ck_log_start_session();
  return !empty($_SESSION['ck_improvement_admin']);
}

function ck_log_require_fc() {
  if (!ck_log_is_fc_logged_in()) {
    header('Location: improvement-login.php');
    exit;
  }
}

function ck_log_require_admin() {
  if (!ck_log_is_admin_logged_in()) {
    header('Location: improvement-login.php');
    exit;
  }
}

function ck_log_csrf_token() {
  ck_log_start_session();
  if (empty($_SESSION['ck_improvement_csrf'])) {
    $_SESSION['ck_improvement_csrf'] = bin2hex(random_bytes(16));
  }

  return $_SESSION['ck_improvement_csrf'];
}

function ck_log_verify_csrf($token) {
  ck_log_start_session();
  return isset($_SESSION['ck_improvement_csrf']) && hash_equals($_SESSION['ck_improvement_csrf'], (string) $token);
}

function ck_log_render_head($title, $description = '', $base = '') {
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo ck_h($title); ?></title>
<meta name="description" content="<?php echo ck_h($description); ?>">
<link rel="stylesheet" href="<?php echo ck_h($base); ?>css/core.css?v=20260707-02">
<link rel="stylesheet" href="<?php echo ck_h($base); ?>css/components.css?v=20260701-1">
<link rel="stylesheet" href="<?php echo ck_h($base); ?>css/pages.css?v=20260707-29">
<?php
}

function ck_log_render_header($current = '', $base = '') {
?>
<header class="ck-site-header ck-log-header">
  <div class="ck-header-bar">
    <div class="ck-log-brand">
      <a class="ck-header-logo" href="<?php echo ck_h($base); ?>index.php" aria-label="厨房君 トップページ">
        <img src="<?php echo ck_h($base); ?>image/logo/header_logo.png" alt="厨房君">
      </a>
      <span class="ck-log-screen-label">利用者専用画面</span>
    </div>
    <nav class="ck-header-nav" id="ck-header-nav" aria-label="主要ナビゲーション">
      <span class="ck-header-page-title">新機能、改善、不具合修正のお知らせ</span>
    </nav>
    <?php if (ck_log_is_fc_logged_in()) : ?>
      <a class="ck-header-cta" href="<?php echo ck_h($base); ?>improvement-logout.php">ログアウト</a>
    <?php endif; ?>
    <button class="ck-header-menu" type="button" aria-label="メニュー" aria-controls="ck-header-nav" aria-expanded="false">
      <span></span>
      <span></span>
    </button>
  </div>
</header>
<?php
}

function ck_log_render_footer($base = '') {
?>
<script src="<?php echo ck_h($base); ?>js/script.js?v=20260701-2"></script>
<?php
}
