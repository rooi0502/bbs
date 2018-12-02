<?php
require_once __DIR__.'/lib/init.php';
require __DIR__.'/models/UserRepository.php';
$user_repository = New UserRepository($pdo);
session_start();

if (isset($_SESSION['id'])) {
    if ($_SESSION['id']) {
        header('Location: index.php');
        exit;
    }
}

// ログインボタンが押された場合
if (isset($_POST['signin'])) {
    $err_msg = $user_repository->validateLogin($_POST);
    if (empty($err_msg)) {
        $user = $user_repository->findByUserId($_POST['user_id']);
        $_SESSION["id"] = $user["id"];
        header('Location: user_login_success.php');
        exit;
    }
}
include __DIR__.'/views/user_login.php';