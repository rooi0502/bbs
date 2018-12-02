<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>BBS</title>
</head>
<body>

<h1>ユーザー新規登録</h1>
<form name="register_form" action="user_register.php" method="post">
    <legend>新規登録フォーム</legend>
    <label for="user_id">ユーザーID</label><input type="text" name="user_id" value="<?php if(!empty($_POST['user_id'])) echo $_POST['user_id'] ?>">
    <br>
    <label for="username">ユーザー名</label><input type="text" name="name" value="<?php if(!empty($_POST['name'])) echo $_POST['name'] ?>">
    <br>
    <label for="password">パスワード</label><input type="password" name="password" value="">
    <br>
    <label for="password">パスワード(確認)</label><input type="password" name="comfirm_password" value="">
    <br>
    <input type="submit" name="signup" value="新規登録">
    <a href="user_login.php">すでにユーザーの方はこちら</a>
</form>
<a href="index.php">戻る</a>

<!-- エラーメッセージ表示 -->
<?php if (!empty($err_msg)): ?>
  <hr>
  <?php foreach($err_msg as $msg): ?>
    <div><?= h($msg) ?></div>
  <?php endforeach; ?>
  <hr>
<?php endif; ?>

</body>
</html>