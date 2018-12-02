<?php
// データベース情報
require_once '../lib/init.php';
require_once '../models/PostRepository.php';
require_once '../models/ThreadRepository.php';

$post_repository = new PostRepository($pdo);
$thread_repository = new ThreadRepository($pdo);

if (isset($_POST)) {
    header('Content-type:application/json; charset=utf8');
    if ($_POST['attr'] === "parent") {
        $err_msg = $post_repository->validate($_POST, $_FILES, $edit = true);
        if (empty($err_msg)) {
            $post_repository->updatePost($_POST, $_FILES);
            $article = $post_repository->findById($_POST['id']);
            if (isset($_POST['detail'])) {
                $article['detail'] = "detail";
            }
            echo json_encode($article, JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(400);
            echo json_encode($err_msg, JSON_UNESCAPED_UNICODE);
        }
    } else {
        $err_msg = $thread_repository->validate($_POST, $_FILES, $edit=true);
        if (empty($err_msg)) {
            $thread_repository->updateThread($_POST, $_FILES);
            $res = $thread_repository->findById($_POST['id']);
            if (isset($_POST['detail'])) {
                $res['detail'] = "detail";
            }
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(400);
            echo json_encode($err_msg, JSON_UNESCAPED_UNICODE);
        }
    }
}