<?php


session_start();
require_once '../config/config.php';
require_once '../models/Favorite.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../views/user/login.php');
    exit;
}

$favoriteModel = new Favorite($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user']['id'];
    $action = $_POST['action'];

    if ($action == 'add') {
        $favoriteModel->addFavorite($user_id, $event_id);
    } elseif ($action == 'remove') {
        $favoriteModel->removeFavorite($user_id, $event_id);
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
?>