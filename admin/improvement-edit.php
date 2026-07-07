<?php
require __DIR__ . '/../improvement-lib.php';

ck_log_require_admin();

$items = ck_log_load_all();
$editingSlug = $_GET['slug'] ?? '';
$editing = $editingSlug !== '' ? ck_log_find_by_slug($items, $editingSlug) : null;
$isNew = !$editing;
$message = '';
$error = '';
$categoryOptions = ['新機能', '改善', '不具合修正', 'その他'];

$defaults = [
  'number' => ck_log_next_number($items),
  'slug' => '',
  'title' => '',
  'category' => '改善',
  'target' => '',
  'status' => 'draft',
  'public_visible' => false,
  'published_at' => date('Y-m-d'),
  'updated_at' => date('Y-m-d'),
  'summary' => '',
  'public_summary' => '',
  'public_body' => '',
  'problem' => '',
  'changed' => '',
  'video_path' => '',
  'video_caption' => '',
  'video_capture' => '',
  'images' => [],
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
$filesToDelete = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!ck_log_verify_csrf($_POST['csrf'] ?? '')) {
    $error = '送信内容を確認できませんでした。もう一度お試しください。';
  } else {
    $form = [
      'number' => trim($_POST['number'] ?? ''),
      'slug' => trim($_POST['slug'] ?? ''),
      'title' => trim($_POST['title'] ?? ''),
      'category' => trim($_POST['category'] ?? ''),
      'target' => '',
      'status' => ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft',
      'public_visible' => !empty($_POST['public_visible']),
      'published_at' => trim($_POST['published_at'] ?? ''),
      'updated_at' => trim($_POST['updated_at'] ?? ''),
      'summary' => trim($_POST['summary'] ?? ''),
      'public_summary' => trim($_POST['public_summary'] ?? ''),
      'public_body' => trim($_POST['public_body'] ?? ''),
      'problem' => trim($_POST['problem'] ?? ''),
      'changed' => trim($_POST['changed'] ?? ''),
      'reason' => '',
      'fc_note' => '',
      'next' => '',
      'video_path' => trim($_POST['video_path'] ?? ''),
      'video_caption' => '',
      'video_capture' => ck_log_media_src($_POST['video_capture'] ?? ''),
      'images' => ck_log_images($_POST['existing_images'] ?? []),
      'history_text' => trim($_POST['history_text'] ?? ''),
    ];

    if ($form['title'] === '' || $form['summary'] === '') {
      $error = 'タイトルと概要は必須です。';
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $form['published_at']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $form['updated_at'])) {
      $error = '日付は YYYY-MM-DD 形式で入力してください。';
    } else {
      if (!in_array($form['category'], $categoryOptions, true)) {
        $form['category'] = 'その他';
      }
      if ($form['number'] === '') $form['number'] = ck_log_next_number($items);
      if ($form['slug'] === '') $form['slug'] = ck_log_slug($form['title'], $form['published_at']);

      $deleteImages = array_map('intval', $_POST['delete_images'] ?? []);
      if ($deleteImages) {
        foreach ($deleteImages as $deleteImageIndex) {
          if (isset($form['images'][$deleteImageIndex])) {
            $filesToDelete[] = $form['images'][$deleteImageIndex];
          }
        }
        $form['images'] = array_values(array_filter($form['images'], function ($path, $index) use ($deleteImages) {
          return !in_array($index, $deleteImages, true);
        }, ARRAY_FILTER_USE_BOTH));
      }

      $imageMove = (string) ($_POST['image_move'] ?? '');
      if (preg_match('/^(up|down):(\d+)$/', $imageMove, $matches)) {
        $index = (int) $matches[2];
        $swapIndex = $matches[1] === 'up' ? $index - 1 : $index + 1;
        if (isset($form['images'][$index], $form['images'][$swapIndex])) {
          $tmp = $form['images'][$index];
          $form['images'][$index] = $form['images'][$swapIndex];
          $form['images'][$swapIndex] = $tmp;
        }
      }

      if (!empty($_POST['delete_video'])) {
        if ($form['video_path'] !== '') {
          $filesToDelete[] = $form['video_path'];
        }
        if ($form['video_capture'] !== '') {
          $filesToDelete[] = $form['video_capture'];
        }
        $form['video_path'] = '';
        $form['video_caption'] = '';
        $form['video_capture'] = '';
      }

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

      $videoCaptureData = trim($_POST['video_capture_data'] ?? '');
      if ($error === '' && $videoCaptureData !== '') {
        $capturePath = ck_log_save_data_image($videoCaptureData, $form['slug'], 'improvement-video', $error);
        if ($capturePath !== '') {
          $form['video_capture'] = $capturePath;
        }
      }

      $imageFiles = $_FILES['image_files'] ?? null;
      if ($error === '' && is_array($imageFiles) && !empty($imageFiles['name']) && is_array($imageFiles['name'])) {
        $currentCount = count($form['images']);
        foreach ($imageFiles['name'] as $index => $name) {
          $uploadError = $imageFiles['error'][$index] ?? UPLOAD_ERR_NO_FILE;
          if ($uploadError === UPLOAD_ERR_NO_FILE) {
            continue;
          }
          if (!ck_log_image_upload_ready()) {
            break;
          }
          if ($currentCount >= 5) {
            $error = '画像は最大5件まで登録できます。';
            break;
          }

          $imageFile = [
            'name' => $name,
            'type' => $imageFiles['type'][$index] ?? '',
            'tmp_name' => $imageFiles['tmp_name'][$index] ?? '',
            'error' => $uploadError,
            'size' => $imageFiles['size'][$index] ?? 0,
          ];
          $imagePath = ck_log_save_uploaded_image($imageFile, $form['slug'], $error);
          if ($imagePath === '') {
            break;
          }
          $form['images'][] = $imagePath;
          $currentCount++;
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
        foreach (array_unique($filesToDelete) as $fileToDelete) {
          ck_log_delete_media_file($fileToDelete);
        }
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
  <section class="ck-log-admin-head ck-log-admin-head-simple">
    <div class="ck-home-container">
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

        <?php $selectedCategory = in_array($form['category'] ?? '', $categoryOptions, true) ? $form['category'] : 'その他'; ?>
        <input type="hidden" name="slug" value="<?php echo ck_h($form['slug']); ?>">
        <div class="ck-log-form-grid">
          <label>
            <span class="ck-log-label-with-note">
              番号
              <small><?php echo $form['slug'] !== '' ? ck_h($form['slug']) : 'スラッグは保存時に自動生成'; ?></small>
            </span>
            <input name="number" value="<?php echo ck_h($form['number']); ?>">
          </label>
          <label><span>公開日</span><input type="date" name="published_at" value="<?php echo ck_h($form['published_at']); ?>"></label>
          <label><span>更新日</span><input type="date" name="updated_at" value="<?php echo ck_h($form['updated_at']); ?>"></label>
          <label><span>カテゴリ</span>
            <select name="category">
              <?php foreach ($categoryOptions as $categoryOption) : ?>
                <option value="<?php echo ck_h($categoryOption); ?>"<?php echo $selectedCategory === $categoryOption ? ' selected' : ''; ?>><?php echo ck_h($categoryOption); ?></option>
              <?php endforeach; ?>
            </select>
          </label>
          <label><span>公開状態</span>
            <select name="status">
              <option value="draft"<?php echo $form['status'] === 'draft' ? ' selected' : ''; ?>>下書き</option>
              <option value="published"<?php echo $form['status'] === 'published' ? ' selected' : ''; ?>>公開</option>
            </select>
          </label>
        </div>

        <label><span>タイトル</span><input name="title" value="<?php echo ck_h($form['title']); ?>" required data-title-input></label>
        <label><span>概要</span><textarea class="ck-log-textarea-2" name="summary" rows="2" required><?php echo ck_h($form['summary']); ?></textarea></label>
        <label><span>課題</span><textarea class="ck-log-textarea-3" name="problem" rows="3"><?php echo ck_h($form['problem']); ?></textarea></label>
        <label><span>内容</span><textarea class="ck-log-textarea-6" name="changed" rows="6"><?php echo ck_h($form['changed']); ?></textarea></label>
        <div class="ck-log-form-grid">
          <label><span>動画ファイル</span><input type="file" name="video_file" accept="video/mp4,video/webm,video/quicktime" data-video-file-input></label>
          <label><span>動画パス</span><input name="video_path" value="<?php echo ck_h($form['video_path']); ?>" placeholder="video/improvements/sample.mp4" readonly></label>
        </div>
        <input type="hidden" name="video_capture" value="<?php echo ck_h($form['video_capture'] ?? ''); ?>">
        <input type="hidden" name="video_capture_data" value="" data-video-capture-data>
        <div class="ck-log-video-capture" data-video-capture-preview>
          <?php if (ck_log_media_src($form['video_capture'] ?? '') !== '') : ?>
            <figure>
              <img src="../<?php echo ck_h(ck_log_media_src($form['video_capture'] ?? '')); ?>" alt="">
              <figcaption>動画キャプチャ</figcaption>
            </figure>
          <?php endif; ?>
        </div>
        <?php if (trim((string) $form['video_path']) !== '') : ?>
          <label class="ck-log-inline-check"><input type="checkbox" name="delete_video" value="1">動画を削除</label>
        <?php endif; ?>
        <section class="ck-log-image-admin">
          <div>
            <span>画像</span>
            <p>最大5件まで。アップロード時に長辺1600px以内へ調整し、WebP形式で保存します。</p>
          </div>
          <?php $images = ck_log_images($form['images'] ?? []); ?>
          <?php if ($images) : ?>
            <div class="ck-log-image-admin-list">
              <?php foreach ($images as $index => $imagePath) : ?>
                <div class="ck-log-image-admin-item">
                  <img src="../<?php echo ck_h($imagePath); ?>" alt="">
                  <input type="hidden" name="existing_images[]" value="<?php echo ck_h($imagePath); ?>">
                  <div>
                    <button type="submit" name="image_move" value="up:<?php echo ck_h($index); ?>"<?php echo $index === 0 ? ' disabled' : ''; ?>>上へ</button>
                    <button type="submit" name="image_move" value="down:<?php echo ck_h($index); ?>"<?php echo $index === count($images) - 1 ? ' disabled' : ''; ?>>下へ</button>
                    <label><input type="checkbox" name="delete_images[]" value="<?php echo ck_h($index); ?>">削除</label>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <?php if (ck_log_image_upload_ready()) : ?>
            <?php if (count($images) < 5) : ?>
              <label><span>画像を追加</span><input type="file" name="image_files[]" accept="image/jpeg,image/png,image/webp" multiple data-image-upload-input data-max-images="<?php echo ck_h(5 - count($images)); ?>"></label>
              <div class="ck-log-image-upload-preview" data-image-upload-preview></div>
            <?php else : ?>
              <p class="ck-log-image-admin-notice">画像は最大5件まで登録済みです。追加する場合は先に不要な画像を削除してください。</p>
            <?php endif; ?>
          <?php else : ?>
            <p class="ck-log-image-admin-notice">この環境では画像アップロード機能が無効です。PHPのGD/WebPを有効にすると、画像追加が使えます。</p>
          <?php endif; ?>
        </section>
        <section class="ck-log-public-admin">
          <div>
            <span>一般公開用</span>
            <p>公開サイトに出してよい範囲の説明をここで管理します。</p>
          </div>
          <label><span>一般公開</span>
            <select name="public_visible">
              <option value="0"<?php echo empty($form['public_visible']) ? ' selected' : ''; ?>>非公開</option>
              <option value="1"<?php echo !empty($form['public_visible']) ? ' selected' : ''; ?>>公開する</option>
            </select>
          </label>
          <label><span>一般公開用の短い説明</span><textarea name="public_summary" placeholder="未入力の場合は概要を使用します"><?php echo ck_h($form['public_summary']); ?></textarea></label>
          <label><span>一般公開用の本文</span><textarea name="public_body" placeholder="公開サイトに出してよい範囲だけ入力します"><?php echo ck_h($form['public_body']); ?></textarea></label>
        </section>
        <label><span>履歴（1行ずつ：YYYY-MM-DD 内容）</span><textarea name="history_text"><?php echo ck_h($form['history_text']); ?></textarea></label>

        <button type="submit">保存する</button>
      </form>
    </div>
  </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var input = document.querySelector('[data-image-upload-input]');
  var preview = document.querySelector('[data-image-upload-preview]');
  var videoInput = document.querySelector('[data-video-file-input]');
  var videoCaptureData = document.querySelector('[data-video-capture-data]');
  var videoCapturePreview = document.querySelector('[data-video-capture-preview]');

  var objectUrls = [];
  function clearPreview() {
    objectUrls.forEach(function (url) {
      URL.revokeObjectURL(url);
    });
    objectUrls = [];
    if (preview) {
      preview.innerHTML = '';
    }
  }

  if (input && preview) {
    input.addEventListener('change', function () {
      clearPreview();
      var maxImages = Number(input.getAttribute('data-max-images') || 5);
      var files = Array.prototype.slice.call(input.files || [], 0, maxImages);
      files.forEach(function (file) {
        if (!file.type || file.type.indexOf('image/') !== 0) return;
        var url = URL.createObjectURL(file);
        objectUrls.push(url);

        var figure = document.createElement('figure');
        var image = document.createElement('img');
        var caption = document.createElement('figcaption');
        image.src = url;
        image.alt = '';
        caption.textContent = file.name;
        figure.appendChild(image);
        figure.appendChild(caption);
        preview.appendChild(figure);
      });
    });
  }

  if (videoInput) {
    videoInput.addEventListener('change', function () {
      var file = videoInput.files && videoInput.files[0] ? videoInput.files[0] : null;
      if (!file) return;
      captureVideoFrame(file);
    });
  }

  function captureVideoFrame(file) {
    if (!videoCaptureData || !videoCapturePreview) return;
    if (!file.type || file.type.indexOf('video/') !== 0) return;

    var url = URL.createObjectURL(file);
    objectUrls.push(url);
    var video = document.createElement('video');
    video.muted = true;
    video.playsInline = true;
    video.preload = 'metadata';
    video.src = url;

    video.addEventListener('loadedmetadata', function () {
      var targetTime = Math.min(1, Math.max(0, (video.duration || 1) * 0.15));
      try {
        video.currentTime = targetTime;
      } catch (error) {
        drawCapture(video);
      }
    });

    video.addEventListener('seeked', function () {
      drawCapture(video);
    }, { once: true });

    video.addEventListener('loadeddata', function () {
      if (!video.duration) {
        drawCapture(video);
      }
    }, { once: true });
  }

  function drawCapture(video) {
    if (!video.videoWidth || !video.videoHeight) return;
    var maxSize = 960;
    var ratio = Math.min(1, maxSize / Math.max(video.videoWidth, video.videoHeight));
    var canvas = document.createElement('canvas');
    canvas.width = Math.max(1, Math.round(video.videoWidth * ratio));
    canvas.height = Math.max(1, Math.round(video.videoHeight * ratio));
    var context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    var dataUrl = canvas.toDataURL('image/webp', 0.82);
    videoCaptureData.value = dataUrl;

    videoCapturePreview.innerHTML = '';
    var figure = document.createElement('figure');
    var image = document.createElement('img');
    var caption = document.createElement('figcaption');
    image.src = dataUrl;
    image.alt = '';
    caption.textContent = '動画キャプチャ';
    figure.appendChild(image);
    figure.appendChild(caption);
    videoCapturePreview.appendChild(figure);
  }

  window.addEventListener('beforeunload', clearPreview);
});
</script>

<?php ck_log_render_footer('../'); ?>
