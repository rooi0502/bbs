<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="script/admin_pop-up.js"></script>
<script type="text/javascript" src="script/admin_edit.js"></script>
<script type="text/javascript" src="script/admin_delete.js"></script>
<title>管理画面</title>
<style>
body {
  text-align: center;
  background: #d8bfd8;
  color: #4b0082;
}
</style>
</head>
<body>

<p>投稿詳細</p>
<div id="detail"></div>
<div id="view_number">1</div>
<div id="view_id">ID:<?= h($article['id']) ?></div>
<div id="view_name">名前:<?= h($article['name']) ?></div>
<div id="view_text" style="color:<?= $article['color'] ?>"><?= nl2br(h($article['text'])) ?></div>
<?php if ($article['fname']):?>
    <div><img src="../uploads/<?= $article['fname'] ?>"><div><br>
<?php endif; ?>
<div id="view_time"><?= h($article['time']) ?></div>
<div><input type="button" id="edit" class="<?= $article['id'] ?>" value= "編集" ></div>
<div><input type="button" value= "削除" id="delete" class="<?= $article['id'] ?>"></div>
<hr>

<?php foreach ($thread as $res): ?>
  <div class="res">
    <div id="res_number"><?= h($res['number']) ?></div>
    <?php if (isset($res['user_id'])):?>
      <div class="res_user_id">ユーザーID:<?= h($res['user_id']) ?></a></div>
    <?php endif; ?>
    <?php if (isset($res['user_id'])):?>
      <div class="res_name"><a href="../user_profile.php?id=<?= $res['user_id'] ?>"><?= h($res['name']) ?></a></div>
    <?php else : ?>
      <div class="res_name"><?= h($res['name']) ?></div>
    <?php endif; ?>
    <div class="res_text" style="color:<?= $res['color'] ?>"><?= nl2br(h($res['text'])) ?></div>
    <?php if ($res['fname']):?>
      <div><img src="../uploads/<?= $res['fname'] ?>"><div><br>
    <?php endif; ?>
    <div class="res_time"><?= h($res['time']) ?></div>
    <div><input type="button" id="res_edit" class="<?= $res['id'] ?>" value= "編集" ></div>
    <div><input type="button" value= "削除" id="res_delete" class="<?= $res['id'] ?>"></div>
  </div>
  <hr>
<?php endforeach; ?>

<?php require 'view_pop-up.php' ?>

<a href="index.php">戻る</a>

</body>
</html>
