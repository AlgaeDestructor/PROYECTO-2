<?php
// /controllers/AdminCommentController.php

session_start();
require_once '../config/config.php';
require_once '../models/Comment.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$commentModel = new Comment($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle comment actions
    $comment_id = $_POST['comment_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        $commentModel->approveComment($comment_id);
    } elseif ($action == 'update') {
        $comentario = $_POST['comentario'];
        $commentModel->updateComment($comment_id, $comentario);
    }

    header('Location: ../admin/comments_list.php');
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['delete'])) {
    // Delete comment
    $comment_id = $_GET['delete'];
    $commentModel->deleteComment($comment_id);
    header('Location: ../admin/comments_list.php');
    exit;
}
?>