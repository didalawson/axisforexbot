<?php
session_start();
require_once '../config/database.php';
require_once '../controllers/AuthController.php';

$auth = new AuthController($conn);
$auth->logout();

header("Location: " . BASE_URL . "/login.php");
exit();
?> 