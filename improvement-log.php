<?php
require __DIR__ . '/improvement-lib.php';

ck_log_require_fc();

$year = isset($_GET['year']) ? preg_replace('/[^0-9]/', '', $_GET['year']) : '';
$month = isset($_GET['month']) ? preg_replace('/[^0-9]/', '', $_GET['month']) : '';
$items = ck_log_published(ck_log_load_all());
$archives = ck_log_archives($items);
$selectedItems = ck_log_selected_items($items, $year, $month);
?>
<?php ck_log_render_head('改善ログ | 厨房君', '厨房君の新機能、修正、改善内容を年度別・月別に確認できます。'); ?>
<?php ck_log_render_header(); ?>

<main class="ck-log-page">
  <section class="ck-log-hero">
    <div class="ck-home-container">
      <span>IMPROVEMENT LOG</span>
      <h1>厨房君の改善内容を、<br>いつでも確認できます。</h1>
      <p>新機能、修正、運用上の確認ポイントを年度別・月別に整理しています。</p>
      <a href="improvement-logout.php">ログアウト</a>
    </div>
  </section>

  <section class="ck-log-section">
    <div class="ck-home-container ck-log-layout">
      <div class="ck-log-list">
        <?php if (!$selectedItems) : ?>
          <article class="ck-log-empty">
            <h2>該当する改善ログはありません。</h2>
            <p>別の年月を選択してください。</p>
          </article>
        <?php endif; ?>

        <?php foreach ($selectedItems as $item) : ?>
          <article class="ck-log-card">
            <div class="ck-log-card-meta">
              <span><?php echo ck_h($item['number'] ?? ''); ?></span>
              <time datetime="<?php echo ck_h($item['published_at'] ?? ''); ?>"><?php echo ck_h($item['published_at'] ?? ''); ?></time>
              <em><?php echo ck_h($item['category'] ?? ''); ?></em>
              <em><?php echo ck_h($item['target'] ?? ''); ?></em>
            </div>
            <h2><?php echo ck_h($item['title'] ?? ''); ?></h2>
            <p><?php echo ck_h($item['summary'] ?? ''); ?></p>
            <a href="improvement-detail.php?slug=<?php echo rawurlencode($item['slug'] ?? ''); ?>">詳細を見る</a>
          </article>
        <?php endforeach; ?>
      </div>

      <aside class="ck-log-archive" aria-label="改善ログアーカイブ">
        <h2>ARCHIVE</h2>
        <a class="<?php echo $year === '' ? 'is-current' : ''; ?>" href="improvement-log.php">すべて</a>
        <?php foreach ($archives as $archiveYear => $months) : ?>
          <div>
            <strong><?php echo ck_h($archiveYear); ?></strong>
            <?php foreach ($months as $archiveMonth => $count) : ?>
              <a class="<?php echo $year === $archiveYear && $month === $archiveMonth ? 'is-current' : ''; ?>" href="improvement-log.php?year=<?php echo ck_h($archiveYear); ?>&month=<?php echo ck_h($archiveMonth); ?>">
                <?php echo ck_h((int) $archiveMonth); ?>月 <span><?php echo ck_h($count); ?></span>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      </aside>
    </div>
  </section>
</main>

<?php ck_log_render_footer(); ?>

