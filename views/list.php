<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>BBS</title>
<!-- 確認用 -->
<style>
body {
  text-align: center;
  background: #d8bfd8;
  color: #4b0082;
}
</style>
<!-- ここまで -->
</head>
<body>

<!-- ログインヘッダー -->
<?php if (!isset($_SESSION["id"])) : ?>
  <a href="user_login.php">ログイン</a>
  <a href="user_register.php">新規登録</a>
<!-- ログアウトヘッダー -->
<?php else: ?>
  <p>ログイン中: <?= h($user['name']) ?> さん</p>
  <p>ユーザーID: <?= h($user['id']) ?></p>
  <a href="user_logout.php">ログアウト</a>
  <a href="user_profile.php?id=<?= $user["id"] ?>">プロフィール</a><br>
  <a href="user_profile_edit.php?id=<?= $user["id"] ?>">プロフィール編集</a><br>
<?php endif; ?>

<a href="admin/index.php">(チェック用)管理画面</a><br>

<h1><a href="index.php" style="text-decoration:none;">掲示板</a></h1>

<!-- 投稿フォーム -->
<?php require __DIR__.'/form.php' ?>

<!-- エラーメッセージ表示 -->
<?php if(!empty($err_msg)): ?>
  <?php foreach($err_msg as $msg): ?>
    <div><?=h($msg)?></div>
  <?php endforeach; ?>
  <hr>
<?php endif; ?>

<!-- 投稿一覧 -->
<?php if(empty($err_msg)): ?>
  <?php require __DIR__.'/view.php' ?>
  <!-- ページング -->
  <?php require __DIR__.'/pagination.php' ?>
<?php endif; ?>
</body>
</html>
