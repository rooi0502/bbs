<?php
require_once __DIR__.'/lib/init.php';
require __DIR__.'/models/UserRepository.php';
$user_repository = New UserRepository($pdo);
session_start();

// idの取得
if (!empty($_GET['id'])) {
    $id = (int)$_GET['id'];
} elseif (empty($_GET['id'])) { //idが空の場合
    header('HTTP', true, 400);
    exit;
}

$user = $user_repository->findById($id);

// 存在しないユーザーにアクセスしようとした場合
if (!$user) {
    header('HTTP', true, 404);
    exit;
}

// そのユーザー以外がアクセスした場合
if (!isset($_SESSION) || $_SESSION['id'] !== $id) {
    header('Location: index.php');
    exit;
}

// 編集ボタンが押された時
if (isset($_POST['edit'])) {
    $err_msg = $user_repository->validateUser($_POST, $_FILES, true);
    if (empty($err_msg)) {
        $user_repository->updateProfile($_POST, $_FILES, $id);
        header('Location: user_profile_edit_success.php');
        exit;
    }
}

include __DIR__.'/views/user_profile_edit.php';