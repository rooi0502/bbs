<?php
// データベース情報
require_once '../lib/init.php';
require_once '../models/PostRepository.php';
require_once '../models/ThreadRepository.php';

$post_repository = new PostRepository($pdo);
$thread_repository = new ThreadRepository($pdo);

if (isset($_POST['id'])) {
    // ヘッダーの設定
    header('Content-type:application/json; charset=utf8');
    if ($_POST['attr'] === "edit") {
        $article = $post_repository->findById($_POST['id']);
        $article['attr'] = "parent";
        echo json_encode($article, JSON_UNESCAPED_UNICODE);
    } else {
        $res = $thread_repository->findById($_POST['id']);
        $res['attr'] = "res";
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }
}