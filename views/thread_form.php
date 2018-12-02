<form name="post" method="post" enctype="multipart/form-data" action="">
  <input type="hidden" name="MAX_FILE_SIZE" value="<?= convertToByte(PostRepository::MESSAGE_MAX_IMAGE_SIZE) ?>"/><!-- ファイルサイズ -->
  <?php if (isset($id)): ?>
    <input type="hidden" name="post_id" value="<?= $id ?>"/>
  <?php endif; ?>
  <?php if (isset($_SESSION["id"])): ?>
    <input type="hidden" name="name" value="<?= $user['name'] ?>"/>
    <p>名前: <?= $user['name'] ?></p>
  <?php else : ?>
  名前:<input type="text" name="name" 
        value="<?= $name = isset($_POST['name']) ? h($_POST['name']) : "" ?>"><br><br>
  内容：<br>
  <?php endif; ?>
  <textarea name="text" rows="10" cols="30"><?= $text = isset($_POST['text']) ? h($_POST['text']) : "" ?></textarea><br><br>
  <input type="file" name="upfile" /><br>
  <select name="color" id="color">
  <?php if ($_POST) : ?>
    <?php $val_color = $_POST['color'] ?>
      <?php else:?>
        <?php $val_color = "black" ?>
  <?php endif; ?>
  <?php foreach (ThreadRepository::getColors() as $code => $label) : ?>
    <option value="<?= $code ?>" <?= ($val_color === $code) ? 'selected="selected"' : '' ?>> <?= $label ?></option>
  <?php endforeach ?>
  </select>
  <br>
  <?php if (isset($_SESSION['id'])) : ?>
    <input type="hidden" name="user_id" value="<?= $_SESSION["id"] ?>"/>
  <?php else : ?>
    削除キー：
    <input type="text" name="password" value="<?= $password = isset($_POST['password']) ? h($_POST['password']) : "" ?>"><br>
  <?php endif; ?>
  <input type="submit" name="submit" value="送信">
  <hr>
</form>