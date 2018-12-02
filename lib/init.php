<?php
/* 設定 */
define('DB_DSN', 'mysql:dbname=bbs;host=127.0.0.1;charset=utf8');
define('DB_USER', 'root');               // ユーザー名
define('DB_PASS', '');                   // パスワード
mb_internal_encoding('UTF-8');           // 内部エンコーディング
date_default_timezone_set('Asia/Tokyo'); // タイムゾーン


/**
 * 単位変換
 */
function convertToByte($image_size) {
  if (!preg_match('/^(?<int>([0-9])+(\.[0-9]+)?)(?<str>(G|M|K)?B)$/', $image_size, $matches)) {
    return false;
  }

  $int = $matches['int'];
  $str = $matches['str'];

  switch($str) {
    case 'GB':
      $sum = $int * pow(1024,3);
      break;
    case 'MB':
      $sum = $int * pow(1024,2);
      break;
    case 'KB':
      $sum = $int * 1024;
      break;
    case 'B':
      $sum = $int;
      break;
    default:
      $sum = false;
      break;
  }

  return $sum;
}


/**
 * HTML特殊文字をエスケープする関数
 */
function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * ファイルサイズの単位変換
 * @param int $size
 *
 * @return string
 */
function calcFileSize($size)
{
  $b = 1024;    // バイト
  $mb = pow($b, 2);   // メガバイト
  $gb = pow($b, 3);   // ギガバイト

  switch(true){
    case $size >= $gb:
      $target = $gb;
      $unit = 'GB';
      break;
    case $size >= $mb:
      $target = $mb;
      $unit = 'MB';
      break;
    default:
      $target = $b;
      $unit = 'KB';
      break;
  }

  $new_size = round($size / $target, 2);
  $file_size = number_format($new_size, 2, '.', ',') . $unit;

  return $file_size;
}

/* データベース接続 */
try {
    /* データベース接続 */
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
    /* エラーモード設定 */
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  } catch(Exception $e) { 
    die('接続エラー:');
  }