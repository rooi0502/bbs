<div class="article">
    <div class="article_count">1</div>
    <div class="article_id">id:<?=h($article['id'])?></div>
    <?php if (isset($article['user_id'])): ?>
        <div class="article_user_id">ユーザーID:<?=h($article['user_id'])?></a></div>
    <?php endif; ?>
    <?php if (isset($article['user_id'])): ?>
        <div class="article_name"><a href="user_profile.php?id=<?= $article['user_id'] ?>"><?=h($article['name'])?></a></div>
    <?php else : ?>
        <div class="article_name"><?=h($article['name'])?></div>
    <?php endif; ?>
    <div class="article_text" style="color:<?=$article['color']?>"><?= nl2br(h($article['text']))?></div>
    <?php if ($article['fname']) : ?>
        <div><img src="uploads/<?= $article['fname'] ?>"><div><br>
    <?php endif; ?>
    <div class="article_time"><?= h($article['time']) ?></div>
    <?php if (isset($_SESSION['id'])) : ?>
        <?php if ($article['user_id'] === $_SESSION['id']) : ?>
        <div class="delete"><a href="delete.php?id=<?= h($article['id']) ?>">削除</a></div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($article['password']) : ?>
        <div class="delete"><a href="delete.php?id=<?= h($article['id']) ?>">削除</a></div>
    <?php endif; ?>
</div>
<hr>