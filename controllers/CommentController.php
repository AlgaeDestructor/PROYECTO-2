<?php

session_start();
require_once '../config/config.php';
require_once '../models/Comment.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../views/user/login.php');
    exit;
}

$commentModel = new Comment($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user']['id'];
    $comentario = $_POST['comentario'];

    $commentModel->addComment($event_id, $user_id, $comentario);

    header('Location: ../event.php?id=' . $event_id);
}
?>