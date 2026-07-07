<?php
require __DIR__ . '/improvement-lib.php';

$year = isset($_GET['year']) ? preg_replace('/[^0-9]/', '', $_GET['year']) : '';
$month = isset($_GET['month']) ? preg_replace('/[^0-9]/', '', $_GET['month']) : '';
$items = ck_log_public_items(ck_log_load_all());
$archives = ck_log_archives($items);
$selectedItems = ck_log_selected_items($items, $year, $month);
?>
<?php ck_log_render_head('アップデート情報 | 厨房君', '厨房君の新機能、改善、修正情報をお知らせします。'); ?>

<header class="ck-site-header">
  <div class="ck-header-bar">
    <a class="ck-header-logo" href="index.php" aria-label="厨房君 トップページ">
      <img src="image/logo/header_logo.png" alt="厨房君">
    </a>
    <nav class="ck-header-nav" id="ck-header-nav" aria-label="主要ナビゲーション">
      <a href="first.php">はじめての方へ</a>
      <a href="esl-solution.php">電子棚札のメリット</a>
      <a href="business-support.php">経営サポート</a>
      <a href="service.php">機能とプラン</a>
      <a href="future.php">業界の未来</a>
      <a href="company.php">COMPANY</a>
    </nav>
    <a class="ck-header-cta" href="https://rise-up.net/franchise/" target="_blank" rel="noopener noreferrer">FC募集</a>
    <button class="ck-header-menu" type="button" aria-label="メニュー" aria-controls="ck-header-nav" aria-expanded="false">
      <span></span>
      <span></span>
    </button>
  </div>
</header>

<main class="ck-log-page ck-public-updates">
  <section class="ck-public-update-hero">
    <div class="ck-home-container">
      <span>UPDATE</span>
      <h1>厨房君の新機能や改修などのお知らせ</h1>
      <p>新機能、改善、修正情報のうち、一般公開できる内容をまとめています。</p>
    </div>
  </section>

  <section class="ck-log-section">
    <div class="ck-home-container ck-log-layout">
      <div class="ck-log-list">
        <?php if (!$selectedItems) : ?>
          <article class="ck-log-empty">
            <h2>該当するアップデートはありません。</h2>
            <p>別の年月を選択してください。</p>
          </article>
        <?php endif; ?>

        <?php foreach ($selectedItems as $item) : ?>
          <article class="ck-public-update-card">
            <div class="ck-log-card-meta">
              <time datetime="<?php echo ck_h($item['published_at'] ?? ''); ?>"><?php echo ck_h($item['published_at'] ?? ''); ?></time>
              <em><?php echo ck_h($item['category'] ?? ''); ?></em>
            </div>
            <h2><?php echo ck_h($item['title'] ?? ''); ?></h2>
            <p><?php echo ck_h(($item['public_summary'] ?? '') !== '' ? $item['public_summary'] : ($item['summary'] ?? '')); ?></p>
            <?php if (!empty($item['public_body'])) : ?>
              <div><?php echo nl2br(ck_h($item['public_body'])); ?></div>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>

      <aside class="ck-log-archive" aria-label="アップデートアーカイブ">
        <h2>ARCHIVE</h2>
        <a class="<?php echo $year === '' ? 'is-current' : ''; ?>" href="updates.php">すべて</a>
        <?php foreach ($archives as $archiveYear => $months) : ?>
          <div>
            <strong><?php echo ck_h($archiveYear); ?></strong>
            <?php foreach ($months as $archiveMonth => $count) : ?>
              <a class="<?php echo $year === $archiveYear && $month === $archiveMonth ? 'is-current' : ''; ?>" href="updates.php?year=<?php echo ck_h($archiveYear); ?>&month=<?php echo ck_h($archiveMonth); ?>">
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
