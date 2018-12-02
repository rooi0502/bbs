<?php
// データベース情報
require_once '../lib/init.php';
require_once '../models/PostRepository.php';
require_once '../models/UserRepository.php';
$post_repository = new PostRepository($pdo);
$user_repository = new UserRepository($pdo);

// 名前で検索
if (isset($_POST['submit_name'])) {
    $err_msg = $post_repository->searchValidate($_POST['name']);
    if (empty($err_msg)) {
        $user_results = $post_repository->searchByName($_POST['name']);
        if (empty($user_result)) {
            $err_msg[] = "検索結果はありませんでした。。。";
        }
    }
}

// 本文を検索
if (isset($_POST['submit_post'])) {
    $err_msg = $post_repository->searchValidate($_POST['post']);
    if (empty($err_msg)) {
        $post_results = $post_repository->postSearch($_POST['post']);
        if (empty($post_results)) {
            $err_msg[] = '検索結果はありませんでした。。。。';
        }
    }
}

include 'views/search_result.php';
