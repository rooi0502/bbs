<div class="name">名前:<?= h($user['name']) ?></div>
<?php if ($user['fname']) : ?>
      <div class="image"><img src="user_profiles/<?= $user['id'] ?>/<?= $user['fname'] ?>"><div><br>
<?php endif; ?>
<div class="comment">コメント:<br><?= $comment = (isset($user['comment'])) ? nl2br(h($user['comment'])) : "" ?></div>
<a href="index.php">戻る</a>