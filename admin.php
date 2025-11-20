<?php
// buat_admin.php (jalankan sekali)
$pw = password_hash('dias', PASSWORD_DEFAULT);
echo $pw . PHP_EOL;