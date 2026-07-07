<?php
require __DIR__ . '/../improvement-lib.php';

ck_log_start_session();
unset($_SESSION['ck_improvement_admin']);

header('Location: improvement-login.php');
exit;

