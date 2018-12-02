<?php
if (!isset($_SERVER['HTTP_REFERER'])) {
    header('Location: index.php');
    exit;
} else {
    $url = $_SERVER['HTTP_REFERER'];
}
include __DIR__.'/views/user_profile_edit_success.php';
