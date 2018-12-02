<?php foreach ($thread as $res) : ?>
  <div class="res">
  <div class="res_number"><?= h($res['number']) ?></a></div>
    <?php if (isset($res['user_id'])):?>
      <div class="res_user_id">ユーザーID:<?= h($res['user_id']) ?></a></div>
    <?php endif; ?>
    <?php if (isset($res['user_id'])) : ?>
      <div class="res_name"><a href="user_profile.php?id=<?= $res['user_id'] ?>"><?= h($res['name']) ?></a></div>
    <?php else : ?>
      <div class="res_name"><?= h($res['name']) ?></div>
    <?php endif; ?>
    <div class="res_text" style="color:<?= $res['color'] ?>"><?= nl2br(h($res['text'])) ?></div>
    <?php if ($res['fname']) : ?>
      <div><img src="uploads/<?= $res['fname'] ?>"><div><br>
    <?php endif; ?>
    <div class="res_time"><?= h($res['time']) ?></div>
    <?php if (isset($_SESSION['id'])) : ?>
      <?php if ($res['user_id'] === $_SESSION['id']) : ?>
        <div class="delete"><a href="delete_thread.php?id=<?= h($res['id']) ?>">削除</a></div>
      <?php endif; ?>
    <?php endif; ?>
    <?php if ($res['password']) : ?>
      <div class="delete"><a href="delete_thread.php?id=<?= h($res['id']) ?>">削除</a></div>
    <?php endif; ?>
  </div>
  <hr>
<?php endforeach; ?>