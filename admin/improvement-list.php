<?php
require __DIR__ . '/../improvement-lib.php';

ck_log_require_admin();
$items = ck_log_load_all();
?>
<?php ck_log_render_head('改善ログ管理 | 厨房君', '厨房君改善ログの管理一覧です。', '../'); ?>

<main class="ck-log-page ck-log-admin-page">
  <section class="ck-log-admin-head">
    <div class="ck-home-container">
      <span>ADMIN</span>
      <h1>改善ログ管理</h1>
      <div>
        <a href="improvement-edit.php">新規追加</a>
        <a href="../improvement-log.php" target="_blank" rel="noopener">FC表示を確認</a>
        <a href="improvement-logout.php">ログアウト</a>
      </div>
    </div>
  </section>

  <section class="ck-log-section">
    <div class="ck-home-container">
      <div class="ck-log-admin-list">
        <?php foreach ($items as $item) : ?>
          <article>
            <div class="ck-log-card-meta">
              <span><?php echo ck_h($item['number'] ?? ''); ?></span>
              <time><?php echo ck_h($item['published_at'] ?? ''); ?></time>
              <em><?php echo ck_h($item['status'] ?? 'published'); ?></em>
              <em><?php echo !empty($item['public_visible']) ? '一般公開' : 'FC専用'; ?></em>
              <em><?php echo ck_h($item['category'] ?? ''); ?></em>
            </div>
            <h2><?php echo ck_h($item['title'] ?? ''); ?></h2>
            <p><?php echo ck_h($item['summary'] ?? ''); ?></p>
            <a href="improvement-edit.php?slug=<?php echo rawurlencode($item['slug'] ?? ''); ?>">編集する</a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>

<?php ck_log_render_footer('../'); ?>
