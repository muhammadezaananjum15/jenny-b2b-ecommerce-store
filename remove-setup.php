<?php
if (file_exists(__DIR__ . '/setup-images.php')) unlink(__DIR__ . '/setup-images.php');
if (file_exists(__DIR__ . '/cleanup.php')) unlink(__DIR__ . '/cleanup.php');
echo "Cleaned setup files";
?>
