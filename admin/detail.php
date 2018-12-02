<?php
// データベース情報
require_once '../lib/init.php';
require_once '../models/PostRepository.php';
require_once '../models/ThreadRepository.php';
$post_repository = new PostRepository($pdo);
$thread_repository = new ThreadRepository($pdo);

// idの取得
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
} elseif (empty($_GET['id'])) { // idが空の場合
    header('HTTP', true, 400);
    exit;
}

// IDに該当する投稿の読み込み
$article = $post_repository->findById($id);
$thread = $thread_repository->findByPostId($id);

// 無効なURL
if (empty($article)) {
    header('HTTP', true, 404);
    exit;
}

include 'views/detail.php';