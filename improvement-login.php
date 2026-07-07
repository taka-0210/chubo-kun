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
<?php ck_log_render_head('改善ログ ログイン | 厨房君', '厨房君の改善ログを確認するための利用者向けログインページです。'); ?>

<main class="ck-log-page ck-log-login-page">
  <section class="ck-log-login">
    <div class="ck-log-login-card">
      <img class="ck-log-login-logo" src="image/logo/header_logo.png" alt="厨房君">
      <h1>利用者専用画面</h1>
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
