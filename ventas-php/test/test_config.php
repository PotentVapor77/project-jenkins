<?php
require __DIR__ . '/../config/constants.php';

echo 'DB_HOST: ' . (defined('DB_HOST') ? DB_HOST : 'NOT DEFINED') . PHP_EOL;
echo 'DB_NAME: ' . (defined('DB_NAME') ? DB_NAME : 'NOT DEFINED') . PHP_EOL;