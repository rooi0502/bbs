<?php
require_once __DIR__.'/lib/init.php';
require __DIR__.'/models/UserRepository.php';
$user_repository = New UserRepository($pdo);

// idの取得
if (!empty($_GET['id'])) {
    $id = (int)$_GET['id'];
} elseif (empty($_GET['id'])) { //idが空の場合
    header('HTTP', true, 400);
    exit;
}

$user = $user_repository->findById($id);

if (!$user) {
    header('HTTP', true, 404);
    exit;
}

include __DIR__.'/views/user_profile.php';