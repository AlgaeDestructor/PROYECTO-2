<?php
// /controllers/FavoriteController.php

session_start();
require_once '../config/config.php';
require_once '../models/Favorite.php';

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['user'])) {
    header('Location: ../views/user/login.php');
    exit;
}

$favoriteModel = new Favorite($pdo);

// Handle form submission for adding or removing a favorite event
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id']; // Event ID to be added or removed from favorites
    $user_id = $_SESSION['user']['id']; // Logged in user's ID
    $action = $_POST['action']; // Action to either add or remove the favorite

    // Add the event to favorites
    if ($action == 'add') {
        $favoriteModel->addFavorite($user_id, $event_id);
    } 
    // Remove the event from favorites
    elseif ($action == 'remove') {
        $favoriteModel->removeFavorite($user_id, $event_id);
    }

    // Redirect back to the previous page after action
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
?>
