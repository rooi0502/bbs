<?php

// データベース情報
require_once __DIR__.'/lib/init.php';
require_once __DIR__.'/models/PostRepository.php';
require_once __DIR__.'/models/UserRepository.php';
require_once __DIR__.'/models/ThreadRepository.php';
require_once __DIR__.'/lib/Paging.php';
session_start();

if (!isset($_GET["page"])) {
    $page = 1;
} else {
    $page = $_GET["page"];
}

if (!preg_match("/^[0-9]+$/", $page)) {
    header('HTTP', true, 400);
    exit;
}

$post_repository = new PostRepository($pdo);
$user_repository = new UserRepository($pdo);
$thread_repository = new ThreadRepository($pdo);

if (!empty($_SESSION['id'])) {
    $user = $user_repository->findById($_SESSION['id']);
}

// 書き込みボタンが押された場合
if (isset($_POST['submit'])) {
    $err_msg = $post_repository->validate($_POST,$_FILES);
    if (empty($err_msg)) {
        $post_repository->add($_POST,$_FILES);
        header('Location: index.php');
        exit;
    }
}
// ページャー処理
$pager_range = 5;
 // １ページの表示数
$disp_max = 10;
// 総件数をセット
$whole_count = (int)$pdo->query('SELECT COUNT(*) FROM post')->fetchColumn();

$paging = new Paging($page, $disp_max, $pager_range, $whole_count);
$pager = $paging->getPagerRange();

if (isset($_GET["page"])) {
    if ($_GET["page"] > $paging->getTotalPage() || $_GET["page"] <= 0) {
        header('HTTP', true, 404);
        exit;
    } else {
        $page = (int)$_GET["page"];
    }
}

$offset = ($page-1)*$disp_max;
$limit = $disp_max;

$articles = $post_repository->find($limit, $offset);

foreach ($articles as $article) {
    $article_ids[] = $article['id'];
}

$counts = $thread_repository->countPostId($article_ids);

include __DIR__.'/views/list.php';