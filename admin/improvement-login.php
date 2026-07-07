<?php
require __DIR__ . '/../improvement-lib.php';

ck_log_start_session();

if (ck_log_is_admin_logged_in()) {
  header('Location: improvement-list.php');
  exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!ck_log_auth_ready('admin')) {
    $error = '管理画面のログイン設定が未完了です。';
  } elseif (!ck_log_verify_csrf($_POST['csrf'] ?? '')) {
    $error = '送信内容を確認できませんでした。もう一度お試しください。';
  } elseif (ck_log_password_verify('admin', $_POST['password'] ?? '')) {
    $_SESSION['ck_improvement_admin'] = true;
    header('Location: improvement-list.php');
    exit;
  } else {
    $error = 'パスワードが違います。';
  }
}
?>
<?php ck_log_render_head('改善ログ管理 ログイン | 厨房君', '厨房君改善ログの管理画面です。', '../'); ?>

<main class="ck-log-page ck-log-admin-page">
  <section class="ck-log-login">
    <div class="ck-log-login-card">
      <span class="ck-log-label">ADMIN</span>
      <h1>改善ログ管理</h1>
      <p>厨房君の改善ログを追加・編集します。</p>
      <?php if ($error !== '') : ?>
        <p class="ck-log-error"><?php echo ck_h($error); ?></p>
      <?php endif; ?>
      <form method="post">
        <input type="hidden" name="csrf" value="<?php echo ck_h(ck_log_csrf_token()); ?>">
        <label>
          <span>管理パスワード</span>
          <input type="password" name="password" autocomplete="current-password" required>
        </label>
        <button type="submit">ログイン</button>
      </form>
    </div>
  </section>
</main>

<?php ck_log_render_footer('../'); ?>

