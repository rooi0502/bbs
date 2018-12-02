<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript" src="./script/admin_pop-up.js"></script>
<script type="text/javascript" src="./script/admin_edit.js"></script>
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

<p>ここは管理画面です。。。</p>

<!-- 検索フォーム -->
<?php require __DIR__.'/search.php' ?>

<!-- 投稿一覧 -->
<?php require __DIR__.'/admin_view.php' ?>

<!-- ページング -->
<?php require __DIR__.'/pagination.php' ?>

<?php require __DIR__.'/admin_pop-up.php' ?>

</body>
</html>