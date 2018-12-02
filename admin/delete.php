<?php
// データベース情報
require_once '../lib/init.php';
require_once '../models/PostRepository.php';
require_once '../models/ThreadRepository.php';

$thread_repository = new ThreadRepository($pdo);
$post_repository = new PostRepository($pdo);

if (isset($_POST['id'])) {
    // ヘッダーの設定
    header('Content-type:application/json; charset=utf8');
    if ($_POST['attr'] === "delete") {
        $thread_repository->deleteThread($_POST['id'], $admin=true);
        $post_repository->delete($_POST['id'], $admin=true);
    }
    else {
        $thread_repository->delete($_POST['id'], $admin=true);
    }
    $url = "delete_success.php";
    echo json_encode($url, JSON_UNESCAPED_UNICODE);
}