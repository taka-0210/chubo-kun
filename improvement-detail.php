<?php
require __DIR__ . '/improvement-lib.php';

ck_log_require_fc();

$slug = $_GET['slug'] ?? '';
$item = ck_log_find_by_slug(ck_log_published(ck_log_load_all()), $slug);

if (!$item) {
  http_response_code(404);
}
?>
<?php ck_log_render_head(($item['title'] ?? '改善ログ詳細') . ' | 厨房君', $item['summary'] ?? ''); ?>
<?php ck_log_render_header(); ?>

<main class="ck-log-page">
  <?php if (!$item) : ?>
    <section class="ck-log-section">
      <div class="ck-home-container ck-log-detail">
        <h1>改善ログが見つかりません。</h1>
        <a href="improvement-log.php">一覧へ戻る</a>
      </div>
    </section>
  <?php else : ?>
    <section class="ck-log-detail-hero">
      <div class="ck-home-container">
        <div class="ck-log-card-meta">
          <span><?php echo ck_h($item['number'] ?? ''); ?></span>
          <time datetime="<?php echo ck_h($item['published_at'] ?? ''); ?>"><?php echo ck_h($item['published_at'] ?? ''); ?></time>
          <em><?php echo ck_h($item['category'] ?? ''); ?></em>
          <em><?php echo ck_h($item['target'] ?? ''); ?></em>
        </div>
        <h1><?php echo ck_h($item['title'] ?? ''); ?></h1>
        <p><?php echo ck_h($item['summary'] ?? ''); ?></p>
      </div>
    </section>

    <section class="ck-log-section">
      <div class="ck-home-container ck-log-detail">
        <?php
          $videoSrc = ck_log_media_src($item['video_path'] ?? '');
          $videoType = ck_log_video_type($videoSrc);
        ?>
        <?php if ($videoSrc !== '') : ?>
          <article class="ck-log-video">
            <span>動画</span>
            <video controls preload="metadata" playsinline>
              <source src="<?php echo ck_h($videoSrc); ?>"<?php echo $videoType !== '' ? ' type="' . ck_h($videoType) . '"' : ''; ?>>
            </video>
            <?php if (trim((string) ($item['video_caption'] ?? '')) !== '') : ?>
              <p><?php echo ck_h($item['video_caption']); ?></p>
            <?php endif; ?>
          </article>
        <?php endif; ?>

        <?php
          $blocks = [
            '課題' => $item['problem'] ?? '',
            '変更内容' => $item['changed'] ?? '',
            '理由' => $item['reason'] ?? '',
            'FC加盟店への確認ポイント' => $item['fc_note'] ?? '',
            '次に進めること' => $item['next'] ?? '',
          ];
        ?>
        <?php foreach ($blocks as $label => $body) : ?>
          <?php if (trim((string) $body) !== '') : ?>
            <article>
              <span><?php echo ck_h($label); ?></span>
              <p><?php echo nl2br(ck_h($body)); ?></p>
            </article>
          <?php endif; ?>
        <?php endforeach; ?>

        <?php if (!empty($item['history']) && is_array($item['history'])) : ?>
          <article>
            <span>履歴</span>
            <ul>
              <?php foreach ($item['history'] as $history) : ?>
                <li><time><?php echo ck_h($history['date'] ?? ''); ?></time><?php echo ck_h($history['note'] ?? ''); ?></li>
              <?php endforeach; ?>
            </ul>
          </article>
        <?php endif; ?>

        <a class="ck-log-back" href="improvement-log.php">一覧へ戻る</a>
      </div>
    </section>
  <?php endif; ?>
</main>

<?php ck_log_render_footer(); ?>
