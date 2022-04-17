<?php
require_once dirname(__FILE__) . '/../../microblogging_conf/config.php';

try {
    $db = new PDO("mysql:dbname=$dbname;host=$host;charset=utf8", $username, $password);
} catch (PDOException $e) {
    print('DB接続エラー: ' . $e->getMessage());
}
