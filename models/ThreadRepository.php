<?php
class ThreadRepository
{
    const MESSAGE_MAX_IMAGE_SIZE = '2MB';

    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public static function getColors()
    {
        return [
            'red' => '赤',
            'blue' => '青',
            'yellow' => '黄',
            'green' => '緑',
            'black' => '黒',
        ];
    }

    public function countPostId($post_ids = [])
    {
        if (!empty($post_ids)) {
            $stmt = $this->pdo->prepare(implode(' ', [
                'SELECT post_id, count(*) as cnt',
                'FROM thread',
                'WHERE post_id IN (' . implode(',', $post_ids) . ')',
                'group by post_id',
            ]));
        } else {
            $stmt = $this->pdo->prepare(implode(' ', [
                'SELECT post_id, count(*) as cnt',
                'FROM thread',
                'group by post_id',
            ]));
        }
        // 実行
        $stmt->execute();
        // 取り出し
        $counts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = [];
        foreach ($counts as $cnt) {
            $count[$cnt['post_id']] = $cnt['cnt'];
        }
        return $count;
    }

    public function findById($id)
    {
        $stmt = $this->pdo->prepare(implode(' ', [
            'SELECT',
            '*',
            'FROM thread',
            'WHERE id = ?',
        ]));
        // 実行
        $stmt->execute([$id]);
        // 投稿の取り出し
        $response = $stmt->fetch(PDO::FETCH_ASSOC);
        return $response;
    }

    /**
     * データベースに格納されているthreadテーブルのレコードpost_idで指定して配列で返す
     * 
     * @return array
     */
    public function findbyPostId($post_id)
    {
        $stmt = $this->pdo->prepare(implode(' ', [
            'SELECT',
            ' * ',
            'FROM thread',
            'WHERE post_id = ?',
            'ORDER by `time` DESC',
        ]));
        // 実行
        $stmt->execute([$post_id]);
        // 投稿の取り出し
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $response;
    }

    public function numberSet($value)
    {
        $check = $this->findbyPostId($value);
        if (!empty($check)) {
            $stmt = $this->pdo->prepare("SELECT MAX(number) FROM `thread` WHERE post_id = ?");
            $stmt->execute([$value]);
            $number = $stmt->fetch(PDO::FETCH_ASSOC);
            return $number['MAX(number)'] + 1;
        }
    }

    public function add($values,$file)
    {
        // アップロードファイル
        if ($file['upfile']['error'] == UPLOAD_ERR_OK) {
            $type = @exif_imagetype($file['upfile']['tmp_name']);
            if ($type == IMAGETYPE_GIF) {
                $extension = '.gif';
            } elseif ($type == IMAGETYPE_JPEG) {
                $extension = '.jpeg';
            } elseif ($type == IMAGETYPE_PNG) {
                $extension = '.png';
            }
            $time = date('Ymd_His');
            $sha1 = sha1_file($file['upfile']['tmp_name']);
            // ファイル名の決定
            $fname = sprintf('%s_%s%s', $time, $sha1, $extension);
            $path = sprintf('./uploads/%s', $fname);
            move_uploaded_file($file['upfile']['tmp_name'], $path);
        } else {
            $fname = '';
        }

        $number = $this->numberSet($values['post_id']);

        if (!empty($values['user_id'])) {
            if ($number) {
                // プリペアドステートメントを生成
                $stmt = $this->pdo->prepare(implode(' ', [
                    'INSERT',
                    'INTO thread(`name`,`number`,`text`,`fname`,`color`,`time`,`user_id`,`post_id`)',
                    'VALUES(?, ?, ?, ?, ?, ?, ?, ?)',
                ]));
                // 書き込みを実行
                $stmt->execute([
                    $values['name'],
                    $number,
                    $values['text'],
                    $fname,
                    $values['color'],
                    date('Y-m-d H:i:s'),
                    $values['user_id'],
                    $values['post_id'],
                ]);
            } else {
                // プリペアドステートメントを生成
                $stmt = $this->pdo->prepare(implode(' ', [
                    'INSERT',
                    'INTO thread(`name`,`number`,`text`,`fname`,`color`,`time`,`user_id`,`post_id`)',
                    'VALUES(?, ?, ?, ?, ?, ?, ?)',
                ]));
                // 書き込みを実行
                $stmt->execute([
                    $values['name'],
                    2,
                    $values['text'],
                    $fname,
                    $values['color'],
                    date('Y-m-d H:i:s'),
                    $values['user_id'],
                    $values['post_id'],
                ]);
            }
        } else {
            if ($number) {
                // プリペアドステートメントを生成
                $stmt = $this->pdo->prepare(implode(' ', [
                    'INSERT',
                    'INTO thread(`name`,`number`,`text`,`time`,`color`,`password`,`fname`,`post_id`)',
                    'VALUES(?, ?, ?, ?, ?, ?, ? ,?)',
                ]));
                // 書き込みを実行
                $stmt->execute([
                    $values['name'],
                    $number,
                    $values['text'],
                    date('Y-m-d H:i:s'),
                    $values['color'],
                    $values['password'],
                    $fname,
                    $values['post_id'],
                ]);
            } else {
                $number = 2;
                // プリペアドステートメントを生成
                $stmt = $this->pdo->prepare(implode(' ', [
                    'INSERT',
                    'INTO thread(`name`,`text`,`time`,`color`,`password`,`fname`,`post_id`)',
                    'VALUES(?, ?, ?, ?, ?, ?, ?)',
                ]));
                // 書き込みを実行
                $stmt->execute([
                    $values['name'],
                    $values['text'],
                    date('Y-m-d H:i:s'),
                    $values['color'],
                    $values['password'],
                    $fname,
                    $values['post_id'],
                ]);
            }
        }
    }

    /**
     * 送信されたフォーム内容のチェック
     * 
     * @return string
     */
    public function validate($values, $file, $edit=false)
    {
        $error = [];
        // 名前のチェック
        if (empty($values['name'])) {
            $error[] = '名前を入力してください';
        } elseif (mb_strlen($values['name']) > 100) {
            $error[] = '名前は100字以内で入力してください';
        }
        // テキストのチェック
        if (empty($values['text'])) {
            $error[] = '本文を入力してください';
        } elseif (mb_strlen($values['text']) > 100) {
            $error[] = '本文は100字以内で入力してください';
        }
        // 色のチェック
        if (empty($values['color'])) {
            $error[] = '色の選択は必須です。';
        } elseif (!array_key_exists($values['color'], self::getColors())) {
            $error[] = '正しい値(色)を入力してください。';
        }
        // パスワードのチェック
        if (!$edit) {
            if (empty($values['user_id'])) {
                if ($values['password']) {
                    if (mb_strlen($values['password']) > 0 && mb_strlen($values['password']) < 4) {
                        $error[] = 'パスワードは4文字以上で入力してください。';
                    } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $values['password'])) { // パスワードが英数字のみか
                        $error[] = 'パスワードは英数字のみで入力してください。';
                    }
                }
            }
        }
        // ファイルアップロードチェック
        if (!isset($file['upfile']['error']) || !is_int($file['upfile']['error'])) {
            $error[] = 'パラメータが不正です。';
          } else {
            switch ($file['upfile']['error']) {
              case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
              case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過 (設定した場合のみ)
                $error[] = 'サイズオーバーです。' . self::MESSAGE_MAX_IMAGE_SIZE . 'まででお願いします。';
                break;
              case UPLOAD_ERR_NO_FILE:   // ファイル未選択
                break;
              case UPLOAD_ERR_OK:
                // $_FILSES['upfile']['mime']の値はブラウザ側で偽装可能なので、MIMEタイプを自前でチェックする
                $type = @exif_imagetype($file['upfile']['tmp_name']);
                if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
                    $error[] = '画像形式が未対応です。';
                } else {
                    //TODO filesize check
                    $filesize = filesize($file['upfile']['tmp_name']);
                    if ($filesize > convertToByte(self::MESSAGE_MAX_IMAGE_SIZE)) {
                        $error[] = 'サイズオーバーです。' . self::MESSAGE_MAX_IMAGE_SIZE . 'まででお願いします。。。';
                    }
                }
            }
        }
        return $error;
    }

    /**
     * 投稿の更新
     * 
     */
    public function updateThread($values, $file)
    {
        $res = $this->findById($values['id']);
        $res_image = ('../uploads');

        // ファイルアップロード
        if ($file['upfile']['error'] == UPLOAD_ERR_OK) {
            $type = @exif_imagetype($file['upfile']['tmp_name']);
            if ($type == IMAGETYPE_GIF) {
                $extension = '.gif';
            } elseif ($type == IMAGETYPE_JPEG) {
                $extension = '.jpeg';
            } elseif ($type == IMAGETYPE_PNG) {
                $extension = '.png';
            }
            // 画像が存在する場合削除してからアップロード
            if (!empty($res['fname'])) {
                foreach (glob($res_image.'/'.$res['fname']) as $res) {
                    unlink($image);
                }
                $time = date('Ymd_His');
                $sha1 = sha1_file($file['upfile']['tmp_name']);
                // ファイル名の決定
                $fname = sprintf('%s_%s%s', $time, $sha1, $extension);
                $path = sprintf('../uploads/%s', $fname);
                move_uploaded_file($file['upfile']['tmp_name'], $path);
            } else { // 画像がない場合
                $time = date('Ymd_His');
                $sha1 = sha1_file($file['upfile']['tmp_name']);
                // ファイル名の決定
                $fname = sprintf('%s_%s%s', $time, $sha1, $extension);
                $path = sprintf('../uploads/%s', $fname);
                move_uploaded_file($file['upfile']['tmp_name'], $path);
            }
        } else { // UPLOAD_ERR_NO_FILE:
            // 画像を削除する場合
            if (!empty($values['delete'])) {
                $fname = "";
                if (glob($res_image.'/'.$user['id'].'_image.*')) {
                    foreach (glob($res_image.'/'.$user['id'].'_image.*') as $image) {
                        unlink($image);
                    }
                }
            // 画像を削除せずアップロードもしない場合 
            } else {
                $fname = $res['fname'];
            }
        }
    
        $stmt = $this->pdo->prepare(implode(' ', [
            'UPDATE',
            'thread SET',
            'name=?,text=?,color=?,fname=?',
            'WHERE id = ?',
        ]));

        // 更新
        $stmt->execute([
            $values['name'],
            $values['text'],
            $values['color'],
            $fname,
            $values['id'],
        ]);
    }

    /**
     * 削除
     * 
     */
    public function delete($id, $admin=false)
    {
        // 画像の削除
        $res = $this->findById($id);
        if (!empty($res['fname'])) {
            if ($admin) {
                if (!unlink("../uploads/".$res['fname'])) {
                    throw new Exception('画像の削除に失敗しました (' . $res['fname'] . ')');
                }
            } else {
                if (!unlink("uploads/".$res['fname'])) {
                    throw new Exception('画像の削除に失敗しました (' . $res['fname'] . ')');
                }
            }
        }
        // 削除
        $stmt = $this->pdo->prepare(implode(' ', [
            'DELETE',
            'FROM thread',
            'WHERE id = ?',
        ]));
        // 実行
        $stmt->execute([$id]);
    }

    /**
     * 親が削除されたら紐づけられている子記事も全削除
     * 
     */
    public function deleteThread($post_id, $admin=false)
    {
        // 画像の削除
        $ress = $this->findByPostId($post_id);
        foreach ($ress as $res) {
            if (!empty($res['fname'])) {
                if ($admin) {
                    if (!unlink("../uploads/".$res['fname'])) {
                        throw new Exception('画像の削除に失敗しました (' . $res['fname'] . ')');
                    }
                } else {
                    if (!unlink("uploads/".$res['fname'])) {
                        throw new Exception('画像の削除に失敗しました (' . $res['fname'] . ')');
                    }
                }
            }
        }
        // 削除
        $stmt = $this->pdo->prepare(implode(' ', [
            'DELETE',
            'FROM thread',
            'WHERE post_id = ?',
        ]));
        // 実行
        $stmt->execute([$post_id]);
    }

    /**
     * 削除できない場合はエラーメッセージを配列で返す
     * @return string
     */
    public function validateDelete($id, $password)
    {
        $error = [];
        $res = $this->findById($id);
        if(!empty($password)) {
            if (!($password == $res['password'])) {
                $error[] = 'パスワードが違います。';
            }
        } else {
                $error[] = 'パスワードを入力してください。';
        }
        return $error;
    }
}