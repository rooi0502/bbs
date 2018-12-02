<?php
//データベース情報
require_once __DIR__.'/lib/init.php';
require_once __DIR__.'/models/PostRepository.php';
require_once __DIR__.'/models/ThreadRepository.php';

$thread_repository = new ThreadRepository($pdo);
session_start();

// idの取得
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
} elseif (empty($_GET['id'])) { // idが空の場合
    header('HTTP', true, 400);
    exit;
}

// IDに該当する投稿の読み込み
$article = $thread_repository->findById($id);

// 無効なURL
if (!empty($article)) {
    if (!empty($_SESSION['id']) && !empty($article['user_id'])) {
        if ($_SESSION['id'] !== $article['user_id']) {   // 投稿したユーザー以外のアクセスは無効
            header('HTTP', true, 400);
            exit;
        }
    } elseif (empty($article['password'])) {
        header('HTTP', true, 400);
        exit;
    }
} else { // idが存在しない投稿にアクセスした場合
    header('HTTP', true, 404);
    exit;
}

// 削除ボタンが押された場合
if (isset($_POST['submit_delete'])) {
    $err_msg = $thread_repository->validateDelete($id,$_POST['password']);
    // エラーが発生していなければ
    if (empty($err_msg)) {
        $thread_repository->delete($id);
        header('Location: delete_complete.php');
        exit;
    }
// ユーザーの投稿の削除の場合
} elseif (isset($_POST['submit_delete_user'])) {
    $thread_repository->delete($id);
    header('Location: delete_complete.php');
    exit;
}

include __DIR__.'/views/delete_thread.php';