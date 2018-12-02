<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>削除確認</title>
</head>
<body>
<p>削除しますか?</p>
<hr>
<!-- 削除する投稿を表示 -->
<div class="article">
  <?php if (isset($article['user_id'])): ?>
    <div class="article_user_id">ユーザーID:<?= h($article['user_id']) ?></a></div>
  <?php endif; ?>
  <div class="article_name"><?= h($article['name']) ?></a></div>
  <div class="article_text" style="color:<?=$article['color']?>"><?= nl2br(h($article['text'])) ?></div>
  <!-- 画像を表示 -->
  <?php if ($article['fname']): ?>
    <div><img src="uploads/<?= $article['fname'] ?>"><div><br>
  <?php endif; ?>
  <div class="article_time"><?= h($article['time']) ?></div>
</div>
<hr>
<!-- 削除パスワード送信フォーム -->
<!-- 登録ユーザーの書き込みではない場合 -->
<?php if (empty($article['user_id'])): ?>
  <form name="post" method="post" action="delete_thread.php?id=<?= $article['id'] ?>">
    <p>削除キー：</p>
    <input type="text" name="password">
    <input type="submit" name="submit_delete" value="削除"><br>
  </form>
<!-- 登録ユーザーの書き込みの場合 -->
<?php else: ?>
  <form name="post" method="post" action="delete_thread.php?id=<?= $article['id'] ?>">
    <input type="submit" name="submit_delete_user" value="削除"><br>
  </form>
<?php endif; ?>

<!-- エラーメッセージ表示 -->
<?php if (!empty($err_msg)): ?>
  <hr>
  <?php foreach ($err_msg as $msg): ?>
    <div><?= h($msg) ?></div>
  <?php endforeach; ?>
  <hr>
<?php endif; ?>

<a href="thread.php?id=<?= $article['post_id'] ?>">戻る</a>
</body>
</html>