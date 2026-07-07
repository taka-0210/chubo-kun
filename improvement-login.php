<?php
require __DIR__ . '/improvement-lib.php';

ck_log_start_session();

if (ck_log_is_fc_logged_in()) {
  header('Location: improvement-log.php');
  exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!ck_log_auth_ready('fc')) {
    $error = 'ログイン設定が未完了です。管理者に確認してください。';
  } elseif (!ck_log_verify_csrf($_POST['csrf'] ?? '')) {
    $error = '送信内容を確認できませんでした。もう一度お試しください。';
  } elseif (ck_log_password_verify('fc', $_POST['password'] ?? '')) {
    $_SESSION['ck_improvement_fc'] = true;
    header('Location: improvement-log.php');
    exit;
  } else {
    $error = 'パスワードが違います。';
  }
}
?>
<?php ck_log_render_head('改善ログ ログイン | 厨房君', '厨房君の改善ログを確認するためのFC加盟店向けログインページです。'); ?>
<?php ck_log_render_header(); ?>

<main class="ck-log-page">
  <section class="ck-log-login">
    <div class="ck-log-login-card">
      <span class="ck-log-label">IMPROVEMENT LOG</span>
      <h1>改善ログ</h1>
      <p>厨房君の新機能、修正、運用上の確認ポイントをFC加盟店向けに記録しています。</p>
      <?php if ($error !== '') : ?>
        <p class="ck-log-error"><?php echo ck_h($error); ?></p>
      <?php endif; ?>
      <form method="post">
        <input type="hidden" name="csrf" value="<?php echo ck_h(ck_log_csrf_token()); ?>">
        <label>
          <span>パスワード</span>
          <input type="password" name="password" autocomplete="current-password" required>
        </label>
        <button type="submit">ログイン</button>
      </form>
    </div>
  </section>
</main>

<?php ck_log_render_footer(); ?>

