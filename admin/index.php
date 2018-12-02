<?php
// データベース情報
require_once '../lib/init.php';
require_once '../models/PostRepository.php';
require_once '../lib/Paging.php';

$post_repository = new PostRepository($pdo);

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

// ページャー処理
$pager_range = 5;
 // １ページの表示数
$disp_max = 30;
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

include 'views/index.php';