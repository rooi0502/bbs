<?php
class UserRepository
{
    const MESSAGE_MAX_IMAGE_SIZE = '2MB';

    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * 名前を調べる(部分一致)
     * 
     * @return array
     */
    public function nameSearch($word)
    {
        $stmt = $this->pdo->prepare(implode(' ', [
            "SELECT",
            "*",
            "FROM user",
            "WHERE name",
            "LIKE ?",
        ]));
        // 実行
        $stmt->bindValue(1, '%' . addcslashes($word, '\_%') . '%', PDO::PARAM_STR);
        $stmt->execute();
        // 投稿の取り出し
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    /**
     * データベースに格納されているuserテーブルに指定したuser_idが存在するか否か
     * 
     */
    public function isExsistId($user_id)
    {
        $stmt = $this->pdo->prepare(implode(' ', [
            'SELECT',
            'user_id',
            'FROM user',
            'WHERE user_id = ?',
        ]));
        // 実行
        $stmt->execute([$user_id]);
        // 取り出し
        $user_id = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user_id ? true : false;
    }

    /**
     * データベースに格納されているuserテーブルのレコードをuser_idで指定して取り出す
     * 
     */
    public function findByUserId($user_id)
    {
        $stmt = $this->pdo->prepare(implode(' ', [
            'SELECT',
            '*',
            'FROM user',
            "WHERE user_id = ?",
        ]));
        // 実行
        $stmt->execute([$user_id]);
        // 取り出し
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    /**
     * データベースに格納されているuserテーブルのレコードをidで指定して取り出す
     * 
     */
    public function findById($id)
    {
        $stmt = $this->pdo->prepare(implode(' ', [
            'SELECT',
            '*',
            'FROM user',
            "WHERE id = ?",
        ]));
        // 実行
        $stmt->execute([$id]);
        // 取り出し
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    /**
     * ユーザー登録
     */
    public function userRegister($values)
    {
        $values['password'] = password_hash($values['password'], PASSWORD_BCRYPT);
        // プリペアドステートメントを生成
        $stmt = $this->pdo->prepare(implode(' ', [
            'INSERT',
            'INTO user(`user_id`,`name`,`password`)',
            'VALUES(?, ?, ?)',
        ]));
        //書き込みを実行
        $stmt->execute([
            $values['user_id'],
            $values['name'],
            $values['password'],
        ]);
    }

    /**
     * ユーザーログインのバリデート
     * 
     * @return string
     */
    public function validateLogin($values)
    {
        $error = [];
        if (empty($values['password']) || empty($values['user_id'])) {
            $error[] = '必要事項が入力されていません。';
        } else {
            if ($this->isExsistId($values['user_id'])) {
                $user = $this->findByUserId($values['user_id']);
                if (!password_verify($values['password'], $user['password'])) {
                    $error[] = 'IDかパスワードが違います。';
                }
            } else {
                $error[] = 'IDかパスワードが違います。';
            }
        }
        return $error;
    }

    /**
     * ユーザープロフィールの更新
     * 
     */
    public function updateProfile($values, $file, $id)
    {
        $user = $this->findById($id);
        // 何も入力されていない場合は元のデータで更新
        if (empty($values['name'])) {
            $values['name'] = $user['name'];
        }
        if (empty($values['user_id'])) {
            $values['user_id'] = $user['user_id'];
        }
        $user_image = ('./user_profiles/'.$user['id']);
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
            // フォルダが存在しない場合フォルダ作成
            if (!file_exists($user_image)) {
                mkdir($user_image);
            }
            // すでに画像が存在する場合削除してからアップロード
            if (glob($user_image.'/'.$user['id'].'_image.*')) {
                foreach (glob($user_image.'/'.$user['id'].'_image.*') as $image) {
                    unlink($image);
                }
                $fname = sprintf('%s_%s%s', $user['id'], "image", $extension);
                $path = sprintf($user_image.'/%s', $fname);
                move_uploaded_file($file['upfile']['tmp_name'], $path);
            } else {
                $fname = sprintf('%s_%s%s', $user['id'], "image", $extension);
                $path = sprintf($user_image.'/%s', $fname);
                move_uploaded_file($file['upfile']['tmp_name'], $path);
            }
        } else {
            // 画像を削除する場合
            if (!empty($values['delete'])) {
                $fname = "";
                if (glob($user_image.'/'.$user['id'].'_image.*')) {
                    foreach (glob($user_image.'/'.$user['id'].'_image.*') as $image) {
                        unlink($image);
                    }
                }
            // 画像を削除せずアップロードもしない場合 
            } else {
                $fname = $user['fname'];
            }
        }
        // 新しいパスワードが入力された場合
        if (!empty($values['password'])) {
            $values['password'] = password_hash($values['password'], PASSWORD_BCRYPT);

            $stmt = $this->pdo->prepare(implode(' ', [
                'UPDATE',
                'user SET',
                'name=?,user_id=?,password=?,fname=?,comment=?',
                'WHERE id = ?',
            ]));
    
            //更新
            $stmt->execute([
                $values['name'],
                $values['user_id'],
                $values['password'],
                $fname,
                $values['comment'],
                $id,
            ]);
        } else {    // パスワードが入力されなかった場合パスワード更新なし
            $stmt = $this->pdo->prepare(implode(' ', [
                'UPDATE',
                'user SET',
                'name=?,user_id=?,fname=?,comment=?',
                'WHERE id = ?',
            ]));
    
            //更新
            $stmt->execute([
                $values['name'],
                $values['user_id'],
                $fname,
                $values['comment'],
                $id,
            ]);
        }
    }

    /**
     * ユーザー登録またはプロフィールアップデートのチェック
     * 
     */
    public function validateUser($values, $file = false, $is_update = false)
    {
        $error = [];
        // プロフィールのアップデートの場合
        if ($is_update) {
            $user = $this->findById($values['id']);
            // 名前のチェック
            if (mb_strlen($values['name']) > 100) {
                $error[] = '名前は100字以内で入力してください';
            }
            // IDのチェック
            if ($values['user_id'] === $user['user_id']) {
            } elseif ($this->isExsistId($values['user_id'])) {
                $error[] = 'そのIDは既に登録済みです。違うIDにしてください。';
            } elseif (mb_strlen($values['user_id']) > 100) {
                $error[] = 'IDは100字以内で入力してください';
            }
            // パスワードのチェック
            if (empty($values['password']) && empty($values['comfirm_password'])) {
            } elseif ($values['password'] !== $values['comfirm_password']) {
                $error[] = 'パスワードが一致しません。';
            } elseif (mb_strlen($values['password']) > 0 && mb_strlen($values['password']) < 4) {
                $error[] = 'パスワードは4文字以上で入力してください。';
            } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $values['password'])) { // パスワードが英数字のみか
                $error[] = 'パスワードは英数字のみで入力してください。';
            }
            // コメントのチェック
            if (mb_strlen($values['comment']) > 300) {
                $error[] = 'コメントは300字以内で入力してください。';
            }
            // ファイルアップロードチェック
            if ($file) {
                if (!isset($file['upfile']['error']) || !is_int($file['upfile']['error'])) {
                    $error[] = 'パラメータが不正です。';
                } elseif (!empty($values['delete']) && $file['upfile']['error'] == UPLOAD_ERR_OK) {
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
            }
        // ユーザー登録の場合
        } else {
            // 名前のチェック
            if (empty($values['name'])) {
                $error[] = '名前を入力してください';
            } elseif (mb_strlen($values['name']) > 100) {
                $error[] = '名前は100字以内で入力してください';
            }
            // IDのチェック
            if (empty($values['user_id'])) {
                $error[] = 'IDを入力してください';
            } elseif ($this->isExsistId($values['user_id'])) {
                $error[] = 'そのIDは既に登録済みです。違うIDにしてください。';
            } elseif (mb_strlen($values['user_id']) > 100) {
                $error[] = 'IDは100字以内で入力してください';
            }
            // パスワードのチェック
            if ($values['password']) {
                if ($values['password'] !== $values['comfirm_password']) {
                    $error[] = '確認用のパスワードと一致しません。';
                } elseif (mb_strlen($values['password']) > 0 && mb_strlen($values['password']) < 4) {
                    $error[] = 'パスワードは4文字以上で入力してください。';
                } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $values['password'])) { // パスワードが英数字のみか
                    $error[] = 'パスワードは英数字のみで入力してください。';
                }
            } else {
                $error[] = 'パスワードを入力してください。';
            }
        }
        return $error;
    }
}