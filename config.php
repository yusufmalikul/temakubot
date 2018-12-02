<?php
define('DEBUG', true);
define('LOCAL', true);
date_default_timezone_set('Asia/Jakarta');

if (LOCAL) {
  $host = 'localhost';
  $db = 'chatbot';
  $user = 'root';
  $pass = '';
  $charset = 'utf8';
} else {
  $host = 'localhost';
  $db = 'chatbot';
  $user = 'root';
  $pass = 'xxxxxx';
  $charset = 'utf8';
}

if (DEBUG) {
  error_reporting(E_ALL);
} else {
  error_reporting(0);
}

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
  PDO::ATTR_ERRMODE   => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);

define('BOT_TOKEN', '0:AAE9-Sh16WcZjm2874WmzRfWQXXXXXXXXXX');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
?>
