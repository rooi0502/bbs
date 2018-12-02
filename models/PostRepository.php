<?php
class PostRepository
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

    /**
     * 本文を調べる(部分一致)
     * 
     * @return array
     */
    public function postSearch($word)
    {
        $stmt = $this->pdo->prepare(implode(' ', [
            "SELECT",
            "*",
            "FROM post",
            "WHERE text",
            "LIKE ?",
        ]));
        // 実行
        $stmt->bindValue(1, '%' . addcslashes($word, '\_%') . '%', PDO::PARAM_STR);
        $stmt->execute();
        // 投稿の取り出し
        $post_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $post_results;
    }

    /**
     * 検索のバリデート
     * 
     */
    public function searchValidate($value)
    {
        $error = [];
        // 名前のチェック
        if (empty($value)) {
            $error[] = '検索したい言葉（キーワード）が入力されていません。'."\n".'キーワードを入力し、再度「検索」ボタンを押してください。';
        }
        return $error;
    }

    /**
     * 名前で投稿内容を検索(部分一致)
     * 
     * @return array
     */
    public function searchByName($name)
    {
        $stmt = $this->pdo->prepare(implode(' ', [
            "SELECT",
            "*",
            "FROM post",
            "WHERE name",
            "LIKE ?",
        ]));
        // 実行
        $stmt->bindValue(1, '%' . addcslashes($name, '\_%') . '%', PDO::PARAM_STR);
        $stmt->execute();
        // 投稿の取り出し
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    /**
     * データベースに格納されているpostテーブルのレコードを配列で返す
     * 
     * @return array
     */
    public function find($limit = null,$offset = null)
    {
        if ($limit === null || $offset === null) {
            $stmt = $this->pdo->prepare(implode(' ', [
                'SELECT',
                ' * ',
                'FROM post',
                'ORDER BY `id` DESC',
            ]));
        } else {
            $stmt = $this->pdo->prepare(implode(' ', [
                'SELECT',
                ' * ',
                'FROM post',
                'ORDER BY `id` DESC',
                'LIMIT ? OFFSET ?',
            ]));
            // //値をバインド
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        }
        // 実行
        $stmt->execute();
        // 投稿の取り出し
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $articles;
    }

    /**
     * 投稿の更新
     * 
     */
    public function updatePost($values, $file)
    {
        $article = $this->findById($values['id']);
        $article_image = ('../uploads');
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
            if (!empty($article['fname'])) {
                foreach (glob($article_image.'/'.$article['fname']) as $image) {
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
                unlink($article_image.'/'.$article['fname']);
            // 画像を削除せずアップロードもしない場合 
            } else {
                $fname = $article['fname'];
            }
        }
        $stmt = $this->pdo->prepare(implode(' ', [
            'UPDATE',
            'post SET',
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
     * データベースに格納されているpostテーブルのレコードをidで指定して取り出す
     * 
     * @return string
     */
    public function findById($id)
    {
        $stmt = $this->pdo->prepare(implode(' ', [
            'SELECT',
            '*',
            'FROM post',
            "WHERE id = ?",
        ]));
        // 実行
        $stmt->execute([$id]);
        // 投稿の取り出し
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        return $article;
    }

    public function add($values,$file)
    {
        //アップロードファイル
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
        if (!empty($values['user_id'])) {
            // プリペアドステートメントを生成
            $stmt = $this->pdo->prepare(implode(' ', [
                'INSERT',
                'INTO post(`name`,`text`,`time`,`color`,`fname`,`user_id`)',
                'VALUES(?, ?, ?, ?, ?, ?)',
            ]));
            //書き込みを実行
            $stmt->execute([
                $values['name'],
                $values['text'],
                date('Y-m-d H:i:s'),
                $values['color'],
                $fname,
                $values['user_id'],
            ]);
        } else {
            // プリペアドステートメントを生成
            $stmt = $this->pdo->prepare(implode(' ', [
                'INSERT',
                'INTO post(`name`,`text`,`time`,`color`,`password`,`fname`)',
                'VALUES(?, ?, ?, ?, ?, ?)',
            ]));
            //書き込みを実行
            $stmt->execute([
                $values['name'],
                $values['text'],
                date('Y-m-d H:i:s'),
                $values['color'],
                $values['password'],
                $fname,
            ]);
        }
    }

    /**
     * 投稿の送信、編集時のチェック
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
        //色のチェック
        if (empty($values['color'])) {
            $error[] = '色の選択は必須です。';
        } elseif (!array_key_exists($values['color'], self::getColors())) {
            $error[] = '正しい値(色)を入力してください。';
        }
        // パスワードのチェック(投稿するときのみ)
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
        } elseif (!empty($values['delete']) && $file['upfile']['error'] === UPLOAD_ERR_OK ) {
            $error[] = '画像の削除とアップロードは同時に行えません。';
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
     * 削除できない場合はエラーメッセージを配列で返す
     * @return string
     */
    public function validateDelete($id, $password)
    {
        $error = [];
        $article = $this->findById($id);
        if(!empty($password)) {
            if (!($password == $article['password'])) {
                $error[] = 'パスワードが違います。';
            }
        } else {
                $error[] = 'パスワードを入力してください。';
        }
        return $error;
    }

    public function delete($id, $admin=false)
    {
        //画像の削除
        $article = $this->findById($id);
        if ($admin) {
            if (!empty($article['fname'])) {
                if (!unlink("../uploads/".$article['fname'])) {
                    throw new Exception('画像の削除に失敗しました (' . $article['fname'] . ')');
                }
            }
        } else {
            if (!empty($article['fname'])) {
                if (!unlink("uploads/".$article['fname'])) {
                    throw new Exception('画像の削除に失敗しました (' . $article['fname'] . ')');
                }
            }
        }
        //削除
        $stmt = $this->pdo->prepare(implode(' ', [
            'DELETE',
            'FROM post',
            'WHERE id = ?',
        ]));
        // 実行
        $stmt->execute([$id]);
    }
}