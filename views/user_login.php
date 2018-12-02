<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ユーザーログイン</title>
</head>
<body>

<h1>ユーザーログイン</h1>
<form name="login_form" action="user_login.php" method="post">
    <legend>ログインフォーム</legend>
    <label for="user_id">ユーザーID</label><input type="text" name="user_id" value="<?= $user_id = isset($_POST['user_id']) ? h($_POST['user_id'])  : "" ?>">
    <br>
    <label for="password">パスワード</label><input type="password" name="password" value="">
    <br>
    <input type="submit" name="signin" value="ログイン">
    <a href="user_register.php">まだ会員でない方</a>
</form>
<a href="index.php">戻る</a>

<!-- エラーメッセージ表示 -->
<?php if (!empty($err_msg)): ?>
  <hr>
  <?php foreach ($err_msg as $msg): ?>
    <div><?= h($msg) ?></div>
  <?php endforeach; ?>
  <hr>
<?php endif; ?>

</body>
</html>