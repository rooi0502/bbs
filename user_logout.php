<?php
session_start();
$_SESSION = array();
session_destroy();

include 'views/user_logout.php';