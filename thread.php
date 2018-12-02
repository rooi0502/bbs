<?php
// データベース情報
require_once __DIR__.'/lib/init.php';
require_once __DIR__.'/models/PostRepository.php';
require_once __DIR__.'/models/UserRepository.php';
require_once __DIR__.'/models/ThreadRepository.php';
session_start();
$post_repository = new PostRepository($pdo);
$user_repository = new UserRepository($pdo);
$thread_repository = new ThreadRepository($pdo);

// idの取得
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
} elseif (empty($_GET['id'])) { //idが空の場合
    header('HTTP', true, 400);
    exit;
}

if (!empty($_SESSION['id'])) {
    $user = $user_repository->findById($_SESSION['id']);
}

// IDに該当する投稿の読み込み
$article = $post_repository->findById($id);
$thread = $thread_repository->findByPostId($id);

// 無効なURL
if (empty($article)) {
    header('HTTP', true, 404);
    exit;
}

// 書き込みボタンが押された場合
if (isset($_POST['submit'])) {
    $err_msg = $thread_repository->validate($_POST,$_FILES);
    if (empty($err_msg)) {
        $thread_repository->add($_POST,$_FILES);
        header('Location: thread.php?id='.$id);
        exit;
    }
}

include __DIR__.'/views/thread.php';