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

<h1><a href="index.php" style="text-decoration:none;">掲示板</a></h1>
<p><a href="" style="text-decoration:none;">スレッドTOP</a></p>
<!-- 投稿一覧 -->
<?php require __DIR__.'/parent.php' ?>

<!-- 投稿フォーム -->
<?php require __DIR__.'/thread_form.php' ?>

<!-- エラーメッセージ表示 -->
<?php if (!empty($err_msg)): ?>
  <?php foreach ($err_msg as $msg): ?>
    <div><?= h($msg) ?></div>
  <?php endforeach; ?>
  <hr>
<?php endif; ?>

<!-- 投稿一覧 -->
<?php if (empty($err_msg)): ?>
  <?php require __DIR__.'/response.php' ?>
<?php endif; ?>

</body>
</html>