<table border="1" width="500" cellspacing="0" cellpadding="5" bordercolor="#333333" align="center">
    <tr>
        <th bgcolor="#EE0000"><font color="#FFFFFF">id</font></th>
        <th bgcolor="#EE0000" width="150"><font color="#FFFFFF">名前</font></th>
        <th bgcolor="#EE0000" width="200"><font color="#FFFFFF">本文</font></th>
        <th bgcolor="#EE0000" width="200"><font color="#FFFFFF">日時</font></th>
    </tr>
    <?php foreach ($articles as $article): ?>
        <tr>
            <td id="view_id" class="<?= $article['id'] ?>"><?= h($article['id']) ?></td>
            <td id="view_name" class="<?= $article['id'] ?>"><?= h($article['name']) ?></td>
            <td id="view_text" class="<?= $article['id'] ?>" style="color:<?= $article['color'] ?>"><?= h($article['text']) ?></td>
            <td id="view_time" class="<?= $article['id'] ?>" ><?= h($article['time']) ?></td>
            <td ><input type="button" id="edit" class="<?= ($article['id']) ?>" value= "編集" ></td>
            <td ><a href="detail.php?id=<?= $article['id'] ?>" >詳細</a></td>
        </tr>
    <?php endforeach; ?>
</table>