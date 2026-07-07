<?php
require __DIR__ . '/../improvement-lib.php';

ck_log_require_admin();
$items = ck_log_load_all();
$perPage = 20;
$totalItems = count($items);
$totalPages = max(1, (int) ceil($totalItems / $perPage));
$page = max(1, (int) ($_GET['page'] ?? 1));
$page = min($page, $totalPages);
$pagedItems = array_slice($items, ($page - 1) * $perPage, $perPage);
?>
<?php ck_log_render_head('改善ログ管理 | 厨房君', '厨房君改善ログの管理一覧です。', '../'); ?>

<main class="ck-log-page ck-log-admin-page">
  <section class="ck-log-admin-head ck-log-admin-head-simple">
    <div class="ck-home-container">
      <h1>改善ログ管理</h1>
      <div>
        <a href="improvement-edit.php">新規追加</a>
        <a href="../improvement-log.php" target="_blank" rel="noopener">利用者表示を確認</a>
        <a href="improvement-logout.php">ログアウト</a>
      </div>
    </div>
  </section>

  <section class="ck-log-section">
    <div class="ck-home-container">
      <div class="ck-log-admin-list ck-log-simple-list">
        <?php foreach ($pagedItems as $item) : ?>
          <?php
            $statusLabel = ($item['status'] ?? 'published') === 'published' ? '公開' : '下書き';
            $visibilityLabel = !empty($item['public_visible']) ? '一般公開' : '利用者専用';
            $isDraft = ($item['status'] ?? 'published') !== 'published';
            $statusClass = $isDraft ? 'is-draft-status' : 'is-published-status';
            $visibilityClass = !empty($item['public_visible']) ? 'is-public-visibility' : 'is-member-visibility';
            $hasVideo = ck_log_media_src($item['video_path'] ?? '') !== '';
            $hasImages = count(ck_log_images($item['images'] ?? [])) > 0;
          ?>
          <a class="ck-log-row ck-log-admin-row<?php echo $isDraft ? ' is-draft' : ''; ?>" href="improvement-edit.php?slug=<?php echo rawurlencode($item['slug'] ?? ''); ?>">
            <div class="ck-log-row-meta">
              <div class="ck-log-row-meta-top">
                <span><?php echo ck_h($item['number'] ?? ''); ?></span>
                <?php if ($hasVideo || $hasImages) : ?>
                  <span class="ck-log-media-icons">
                    <?php if ($hasVideo) : ?>
                      <i aria-label="動画あり" title="動画あり">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 5h10a3 3 0 0 1 3 3v1.1l2.6-1.7A.9.9 0 0 1 22 8.2v7.6a.9.9 0 0 1-1.4.8L18 14.9V16a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V8a3 3 0 0 1 3-3Z"/></svg>
                      </i>
                    <?php endif; ?>
                    <?php if ($hasImages) : ?>
                      <i aria-label="画像あり" title="画像あり">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4h14a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3Zm0 2a1 1 0 0 0-1 1v8.1l3.6-3.1a2 2 0 0 1 2.7.1l1.8 1.8 2.8-3.5a2 2 0 0 1 3.1 0L20 13V7a1 1 0 0 0-1-1H5Zm13 3.2a1.8 1.8 0 1 1-3.6 0 1.8 1.8 0 0 1 3.6 0Z"/></svg>
                      </i>
                    <?php endif; ?>
                  </span>
                <?php endif; ?>
              </div>
              <div class="ck-log-row-meta-bottom">
                <time><?php echo ck_h($item['published_at'] ?? ''); ?></time>
                <em class="<?php echo ck_h($statusClass); ?>"><?php echo ck_h($statusLabel); ?></em>
                <em class="<?php echo ck_h($visibilityClass); ?>"><?php echo ck_h($visibilityLabel); ?></em>
                <em><?php echo ck_h($item['category'] ?? ''); ?></em>
              </div>
            </div>
            <div class="ck-log-row-main">
              <h2><?php echo ck_h($item['title'] ?? ''); ?></h2>
              <p><?php echo ck_h($item['summary'] ?? ''); ?></p>
            </div>
          </a>
        <?php endforeach; ?>
      </div>

      <?php if ($totalPages > 1) : ?>
        <nav class="ck-log-pagination" aria-label="ページ送り">
          <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
            <a class="<?php echo $i === $page ? 'is-current' : ''; ?>" href="improvement-list.php?page=<?php echo ck_h($i); ?>"><?php echo ck_h($i); ?></a>
          <?php endfor; ?>
        </nav>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php ck_log_render_footer('../'); ?>
