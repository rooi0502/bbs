<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>検索結果</title>
</head>
<body>
<p>検索結果</p>
<hr>

<!-- エラーメッセージ表示 -->
<?php if (!empty($err_msg)): ?>
  <?php foreach ($err_msg as $msg): ?>
    <div><?= h($msg) ?></div>
  <?php endforeach; ?>
  <hr>
<?php endif; ?>

<!-- 投稿内容検索結果 -->
<?php if (isset($post_results)): ?>
    <?php foreach ($post_results as $post_result): ?>

    <div class="post_result_id">id:<?= h($post_result['id']) ?></div>
    <?php if (isset($post_result['user_id'])): ?>
        <div class="post_result_user_id">ユーザーID:<?= h($post_result['user_id']) ?></a></div>
    <?php endif; ?>
    <?php if (isset($post_result['user_id'])): ?>
        <div class="post_result_name"><a href="user_profile.php?id=<?= $post_result['user_id'] ?>"><?= h($post_result['name']) ?></a></div>
    <?php else: ?>
        <div class="post_result_name"><?= h($post_result['name']) ?></div>
    <?php endif; ?>
    <div class="post_result_text" style="color:<?=$post_result['color']?>"><?= nl2br(h($post_result['text']))?></div>
    <?php if ($post_result['fname']): ?>
        <div><img src="../uploads/<?= $post_result['fname'] ?>"><div><br>
    <?php endif; ?>
    <div class="post_result_time"><?= h($post_result['time']) ?></div>
    <?php if (isset($_SESSION['id'])): ?>
        <?php if ($post_result['user_id'] === $_SESSION['id']): ?>
        <div class="delete"><a href="delete.php?id=<?= h($post_result['id']) ?>">削除</a></div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($post_result['password']): ?>
        <div class="delete"><a href="delete.php?id=<?= h($post_result['id']) ?>">削除</a></div>
    <?php endif; ?>
    <div class="post_result_res"><a href="thread.php?id=<?= $post_result['id'] ?>">レス(<?= (!empty($counts[$post_result['id']])) ? h($counts[$post_result['id']]) : 0 ?>)</a></div>
    <hr>

    <?php endforeach; ?>
<?php endif; ?>

<!-- 名前での検索結果 -->
<?php if (isset($user_results)): ?>
    <?php foreach ($user_results as $user_result): ?>

    <div class="user_result">
    <div class="user_result_id">id:<?= h($user_result['id']) ?></div>
    <?php if (isset($user_result['user_id'])): ?>
        <div class="user_result_user_id">ユーザーID:<?= h($user_result['user_id']) ?></a></div>
    <?php endif; ?>
    <?php if (isset($user_result['user_id'])): ?>
        <div class="user_result_name">名前:<a href="user_profile.php?id=<?= $user_result['user_id'] ?>"><?= h($user_result['name']) ?></a></div>
    <?php else: ?>
        <div class="user_result_name">名前:<?= h($user_result['name']) ?></div>
    <?php endif; ?>
    <div class="user_result_text" style="color:<?=$user_result['color']?>"><?= nl2br(h($user_result['text']))?></div>
    <?php if ($user_result['fname']): ?>
        <div><img src="uploads/<?= $user_result['fname'] ?>"><div><br>
    <?php endif; ?>
    <div class="user_result_time"><?= h($user_result['time']) ?></div>
    <?php if (isset($_SESSION['id'])): ?>
        <?php if ($user_result['user_id'] === $_SESSION['id']): ?>
        <div class="delete"><a href="delete.php?id=<?= h($user_result['id']) ?>">削除</a></div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($user_result['password']): ?>
        <div class="delete"><a href="delete.php?id=<?= h($user_result['id']) ?>">削除</a></div>
    <?php endif; ?>
        <div class="user_result_res">
            <a href="thread.php?id=<?= $user_result['id'] ?>">レス(<?= (!empty($counts[$user_result['id']])) ? h($counts[$post_result['id']]) : 0 ?>)</a>
        </div>
    </div>
    <hr>

    <?php endforeach; ?>
<?php endif; ?>

<a href="index.php">戻る</a>
</body>
</html>