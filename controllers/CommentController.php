<?php
// /controllers/CommentController.php

session_start();
require_once '../config/config.php';
require_once '../models/Comment.php';

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user'])) {
    header('Location: ../views/user/login.php');
    exit;
}

$commentModel = new Comment($pdo);

// Handle form submission for adding a comment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id']; // Get the event ID
    $user_id = $_SESSION['user']['id']; // Get the logged-in user's ID
    $comentario = $_POST['comentario']; // Get the comment content

    // Add the comment to the database
    $commentModel->addComment($event_id, $user_id, $comentario);

    // Redirect the user back to the event page after the comment is added
    header('Location: ../event.php?id=' . $event_id);
}
?>
