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
// 新規登録ボタンが押された場合
if (isset($_POST['signup'])) {
    $err_msg = $user_repository->validateUser($_POST);
    if (empty($err_msg)) {
        $user_repository->userRegister($_POST);
        header('Location: user_register_success.php');
        exit;
    }
}

include __DIR__.'/views/user_register.php';