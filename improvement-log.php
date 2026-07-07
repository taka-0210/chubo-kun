<?php
require __DIR__ . '/improvement-lib.php';

ck_log_require_fc();

$year = isset($_GET['year']) ? preg_replace('/[^0-9]/', '', $_GET['year']) : '';
$month = isset($_GET['month']) ? preg_replace('/[^0-9]/', '', $_GET['month']) : '';
$items = ck_log_published(ck_log_load_all());
$archives = ck_log_archives($items);
$selectedItems = ck_log_selected_items($items, $year, $month);
?>
<?php ck_log_render_head('改善ログ | 厨房君', '厨房君の新機能、改善、不具合修正のお知らせを確認できます。'); ?>
<?php ck_log_render_header(); ?>

<main class="ck-log-page">
  <section class="ck-log-section">
    <div class="ck-home-container ck-log-member-list">
      <div class="ck-log-list ck-log-simple-list">
        <?php if (!$selectedItems) : ?>
          <article class="ck-log-empty">
            <h2>該当する改善ログはありません。</h2>
            <p>別の年月を選択してください。</p>
          </article>
        <?php endif; ?>

        <?php foreach ($selectedItems as $item) : ?>
          <?php
            $hasVideo = ck_log_media_src($item['video_path'] ?? '') !== '';
            $hasImages = count(ck_log_images($item['images'] ?? [])) > 0;
          ?>
          <a class="ck-log-row ck-log-member-row" href="improvement-detail.php?slug=<?php echo rawurlencode($item['slug'] ?? ''); ?>">
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
                <time datetime="<?php echo ck_h($item['published_at'] ?? ''); ?>"><?php echo ck_h($item['published_at'] ?? ''); ?></time>
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

      <nav class="ck-log-archive ck-log-archive-filter" aria-label="年月で絞り込み">
        <h2>年月で絞り込み</h2>
        <div>
          <a class="ck-log-archive-all <?php echo $year === '' ? 'is-current' : ''; ?>" href="improvement-log.php">
            <strong>すべて</strong>
            <span><?php echo ck_h(count($items)); ?></span>
          </a>
        <?php foreach ($archives as $archiveYear => $months) : ?>
          <?php foreach ($months as $archiveMonth => $count) : ?>
            <a class="<?php echo $year === $archiveYear && $month === $archiveMonth ? 'is-current' : ''; ?>" href="improvement-log.php?year=<?php echo ck_h($archiveYear); ?>&month=<?php echo ck_h($archiveMonth); ?>">
              <strong><?php echo ck_h($archiveYear); ?>年<?php echo ck_h((int) $archiveMonth); ?>月</strong>
              <span><?php echo ck_h($count); ?></span>
            </a>
          <?php endforeach; ?>
        <?php endforeach; ?>
        </div>
      </nav>
    </div>
  </section>
</main>

<?php ck_log_render_footer(); ?>
