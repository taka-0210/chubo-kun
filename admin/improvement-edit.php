<?php
require __DIR__ . '/../improvement-lib.php';

ck_log_require_admin();

$items = ck_log_load_all();
$editingSlug = $_GET['slug'] ?? '';
$editing = $editingSlug !== '' ? ck_log_find_by_slug($items, $editingSlug) : null;
$isNew = !$editing;
$message = '';
$error = '';

$defaults = [
  'number' => ck_log_next_number($items),
  'slug' => '',
  'title' => '',
  'category' => '改善',
  'target' => '全体',
  'status' => 'draft',
  'public_visible' => false,
  'published_at' => date('Y-m-d'),
  'updated_at' => date('Y-m-d'),
  'summary' => '',
  'public_summary' => '',
  'public_body' => '',
  'problem' => '',
  'changed' => '',
  'reason' => '',
  'fc_note' => '',
  'next' => '',
  'video_path' => '',
  'video_caption' => '',
  'history_text' => date('Y-m-d') . ' 初回作成',
];

if ($editing) {
  $defaults = array_merge($defaults, $editing);
  $historyLines = [];
  if (!empty($editing['history']) && is_array($editing['history'])) {
    foreach ($editing['history'] as $history) {
      $historyLines[] = trim(($history['date'] ?? '') . ' ' . ($history['note'] ?? ''));
    }
  }
  $defaults['history_text'] = implode("\n", $historyLines);
}

$form = $defaults;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!ck_log_verify_csrf($_POST['csrf'] ?? '')) {
    $error = '送信内容を確認できませんでした。もう一度お試しください。';
  } else {
    $form = [
      'number' => trim($_POST['number'] ?? ''),
      'slug' => trim($_POST['slug'] ?? ''),
      'title' => trim($_POST['title'] ?? ''),
      'category' => trim($_POST['category'] ?? ''),
      'target' => trim($_POST['target'] ?? ''),
      'status' => ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft',
      'public_visible' => !empty($_POST['public_visible']),
      'published_at' => trim($_POST['published_at'] ?? ''),
      'updated_at' => trim($_POST['updated_at'] ?? ''),
      'summary' => trim($_POST['summary'] ?? ''),
      'public_summary' => trim($_POST['public_summary'] ?? ''),
      'public_body' => trim($_POST['public_body'] ?? ''),
      'problem' => trim($_POST['problem'] ?? ''),
      'changed' => trim($_POST['changed'] ?? ''),
      'reason' => trim($_POST['reason'] ?? ''),
      'fc_note' => trim($_POST['fc_note'] ?? ''),
      'next' => trim($_POST['next'] ?? ''),
      'video_path' => trim($_POST['video_path'] ?? ''),
      'video_caption' => trim($_POST['video_caption'] ?? ''),
      'history_text' => trim($_POST['history_text'] ?? ''),
    ];

    if ($form['title'] === '' || $form['summary'] === '') {
      $error = 'タイトルと概要は必須です。';
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $form['published_at']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $form['updated_at'])) {
      $error = '日付は YYYY-MM-DD 形式で入力してください。';
    } else {
      if ($form['number'] === '') $form['number'] = ck_log_next_number($items);
      if ($form['slug'] === '') $form['slug'] = ck_log_slug($form['title'], $form['published_at']);

      $uploadError = $_FILES['video_file']['error'] ?? UPLOAD_ERR_NO_FILE;
      if ($uploadError !== UPLOAD_ERR_NO_FILE) {
        if ($uploadError !== UPLOAD_ERR_OK) {
          $error = '動画ファイルをアップロードできませんでした。ファイルサイズや形式を確認してください。';
        } else {
          $extension = strtolower(pathinfo($_FILES['video_file']['name'] ?? '', PATHINFO_EXTENSION));
          $allowedExtensions = ['mp4', 'm4v', 'webm', 'mov'];
          if (!in_array($extension, $allowedExtensions, true)) {
            $error = '動画ファイルは mp4、m4v、webm、mov のいずれかを選択してください。';
          } else {
            $uploadDir = dirname(__DIR__) . '/video/improvements';
            if (!is_dir($uploadDir)) {
              mkdir($uploadDir, 0755, true);
            }

            $baseName = strtolower(preg_replace('/[^a-z0-9-]+/i', '-', $form['slug']));
            $baseName = trim($baseName, '-');
            if ($baseName === '') {
              $baseName = 'improvement-video';
            }
            $fileName = $baseName . '-' . date('YmdHis') . '.' . $extension;
            $uploadPath = $uploadDir . '/' . $fileName;

            if (move_uploaded_file($_FILES['video_file']['tmp_name'] ?? '', $uploadPath)) {
              $form['video_path'] = 'video/improvements/' . $fileName;
            } else {
              $error = '動画ファイルを保存できませんでした。';
            }
          }
        }
      }

      $history = [];
      foreach (preg_split('/\R/u', $form['history_text']) as $line) {
        $line = trim($line);
        if ($line === '') continue;
        if (preg_match('/^(\d{4}-\d{2}-\d{2})\s+(.+)$/u', $line, $matches)) {
          $history[] = ['date' => $matches[1], 'note' => $matches[2]];
        } else {
          $history[] = ['date' => $form['updated_at'], 'note' => $line];
        }
      }

      if ($error === '') {
        $savedItem = $form;
      unset($savedItem['history_text']);
      $savedItem['history'] = $history;

      $found = false;
      foreach ($items as $index => $item) {
        if (($item['slug'] ?? '') === $editingSlug || ($item['slug'] ?? '') === $savedItem['slug']) {
          $items[$index] = $savedItem;
          $found = true;
          break;
        }
      }
      if (!$found) {
        $items[] = $savedItem;
      }

      if (ck_log_save_all($items)) {
        header('Location: improvement-edit.php?slug=' . rawurlencode($savedItem['slug']) . '&saved=1');
        exit;
      }

      $error = '保存できませんでした。';
      }
    }
  }
}

if (isset($_GET['saved'])) {
  $message = '保存しました。';
}
?>
<?php ck_log_render_head(($isNew ? '改善ログ追加' : '改善ログ編集') . ' | 厨房君', '厨房君改善ログの編集画面です。', '../'); ?>

<main class="ck-log-page ck-log-admin-page">
  <section class="ck-log-admin-head">
    <div class="ck-home-container">
      <span>ADMIN</span>
      <h1><?php echo $isNew ? '改善ログ追加' : '改善ログ編集'; ?></h1>
      <div>
        <a href="improvement-list.php">一覧へ戻る</a>
        <a href="improvement-logout.php">ログアウト</a>
      </div>
    </div>
  </section>

  <section class="ck-log-section">
    <div class="ck-home-container">
      <?php if ($message !== '') : ?><p class="ck-log-success"><?php echo ck_h($message); ?></p><?php endif; ?>
      <?php if ($error !== '') : ?><p class="ck-log-error"><?php echo ck_h($error); ?></p><?php endif; ?>

      <form class="ck-log-form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?php echo ck_h(ck_log_csrf_token()); ?>">

        <div class="ck-log-form-grid">
          <label><span>番号</span><input name="number" value="<?php echo ck_h($form['number']); ?>"></label>
          <label><span>スラッグ</span><input name="slug" value="<?php echo ck_h($form['slug']); ?>" placeholder="未入力なら自動生成"></label>
          <label><span>公開日</span><input name="published_at" value="<?php echo ck_h($form['published_at']); ?>"></label>
          <label><span>更新日</span><input name="updated_at" value="<?php echo ck_h($form['updated_at']); ?>"></label>
          <label><span>カテゴリ</span><input name="category" value="<?php echo ck_h($form['category']); ?>"></label>
          <label><span>対象機能</span><input name="target" value="<?php echo ck_h($form['target']); ?>"></label>
          <label><span>公開状態</span>
            <select name="status">
              <option value="draft"<?php echo $form['status'] === 'draft' ? ' selected' : ''; ?>>下書き</option>
              <option value="published"<?php echo $form['status'] === 'published' ? ' selected' : ''; ?>>公開</option>
            </select>
          </label>
          <label><span>一般公開</span>
            <select name="public_visible">
              <option value="0"<?php echo empty($form['public_visible']) ? ' selected' : ''; ?>>非公開</option>
              <option value="1"<?php echo !empty($form['public_visible']) ? ' selected' : ''; ?>>公開する</option>
            </select>
          </label>
        </div>

        <label><span>タイトル</span><input name="title" value="<?php echo ck_h($form['title']); ?>" required></label>
        <label><span>概要</span><textarea name="summary" required><?php echo ck_h($form['summary']); ?></textarea></label>
        <label><span>一般公開用の短い説明</span><textarea name="public_summary" placeholder="未入力の場合は概要を使用します"><?php echo ck_h($form['public_summary']); ?></textarea></label>
        <label><span>一般公開用の本文</span><textarea name="public_body" placeholder="公開サイトに出してよい範囲だけ入力します"><?php echo ck_h($form['public_body']); ?></textarea></label>
        <label><span>課題</span><textarea name="problem"><?php echo ck_h($form['problem']); ?></textarea></label>
        <label><span>変更内容</span><textarea name="changed"><?php echo ck_h($form['changed']); ?></textarea></label>
        <label><span>理由</span><textarea name="reason"><?php echo ck_h($form['reason']); ?></textarea></label>
        <label><span>FC加盟店への確認ポイント</span><textarea name="fc_note"><?php echo ck_h($form['fc_note']); ?></textarea></label>
        <label><span>次に進めること</span><textarea name="next"><?php echo ck_h($form['next']); ?></textarea></label>
        <div class="ck-log-form-grid">
          <label><span>動画ファイル</span><input type="file" name="video_file" accept="video/mp4,video/webm,video/quicktime"></label>
          <label><span>動画パス</span><input name="video_path" value="<?php echo ck_h($form['video_path']); ?>" placeholder="video/improvements/sample.mp4"></label>
          <label><span>動画キャプション</span><input name="video_caption" value="<?php echo ck_h($form['video_caption']); ?>"></label>
        </div>
        <label><span>履歴（1行ずつ：YYYY-MM-DD 内容）</span><textarea name="history_text"><?php echo ck_h($form['history_text']); ?></textarea></label>

        <button type="submit">保存する</button>
      </form>
    </div>
  </section>
</main>

<?php ck_log_render_footer('../'); ?>
