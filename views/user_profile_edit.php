<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>プロフィール編集</title>
</head>
<body>

<h1>プロフィール編集</h1>
<form name="profile_edit_form" enctype="multipart/form-data" action="user_profile_edit.php?id=<?= $id ?>" method="post">
  <legend>編集フォーム</legend>
  <input type="hidden" name="id" value="<?= $user['id'] ?>">
  <label for="name">現在の名前: </label><br><input type="text" name="name" value="<?= $name = isset($_POST['name']) ? h($_POST['name']) : h($user['name']) ?>">
  <br>
  <label for="id">現在のID: </label><br><input type="text" name="user_id" value="<?= $user_id = isset($_POST['user_id']) ? h($_POST['user_id']) : h($user['user_id']) ?>">
  <br>
  <label for="password">新しいパスワード(任意)</label><br><input type="password" name="password" value="">
  <br>
  <label for="password">新しいパスワード確認(任意)</label><br><input type="password" name="comfirm_password" value="">
  <br>
  <label for="comment">現在のコメント: </label><br><textarea name="comment" rows="10" cols="30"><?= $text = isset($_POST['comment']) ? h($_POST['comment']) : h($user['comment']) ?></textarea><br>
  <br>
  <label for="upfile">現在のプロフィール画像変更</label><br>
  <input type="file" name="upfile" /><br>
  <br>
  <?php if ($user['fname']) : ?>
      <div class="image"><img src="user_profiles/<?= $user['id'] ?>/<?= $user['fname'] ?>"><div><br>
      <input type="checkbox" name="delete" value="delete"> 画像の削除 <br><br>
  <?php endif; ?>
  <input type="submit" name="edit" value="更新"><br><br>
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