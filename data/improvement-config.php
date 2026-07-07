<?php

return [
  'fc_password_hash' => getenv('CK_LOG_FC_PASSWORD_HASH') ?: '$2y$10$AnwK7yknPpYcumBK5Z9WAO/lQv98fiV4YNGyxKs0alA/zDGTi8T8.',
  'admin_password_hash' => getenv('CK_LOG_ADMIN_PASSWORD_HASH') ?: '$2y$10$XRigxx9vLa8FxsO3JTneKugbEKo5SMBt9hBTIuaIX6kwKB8rvX8/a',
];
