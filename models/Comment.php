<?php
// /models/Comment.php

class Comment
{
    private $pdo;

    // Constructor to initialize PDO connection
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get published comments for an event
    public function getPublishedComments($event_id)
    {
        $sql = "SELECT c.*, u.nombre_usuario, u.imagen_perfil FROM comentarios c
                JOIN users u ON c.id_usuario = u.id
                WHERE c.id_evento = ? AND c.estado = 'publicado'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$event_id]);
        return $stmt->fetchAll();
    }
    // Add a new comment (status 'pendiente')
    public function addComment($event_id, $user_id, $comentario)
    {
        $sql = "INSERT INTO comentarios (id_evento, id_usuario, comentario) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$event_id, $user_id, $comentario]);
    }

    // Additional methods for admin to approve/edit/delete comments
    // Approve a comment
    public function approveComment($comment_id)
    {
        $sql = "UPDATE comentarios SET estado = 'publicado' WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$comment_id]);
    }

    // Update a comment
    public function updateComment($comment_id, $comentario)
    {
        $sql = "UPDATE comentarios SET comentario = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$comentario, $comment_id]);
    }

    // Delete a comment
    public function deleteComment($comment_id)
    {
        $sql = "DELETE FROM comentarios WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$comment_id]);
    }

    // Get all comments
    public function getAllComments()
    {
        $sql = "SELECT c.*, u.nombre_usuario, e.titulo FROM comentarios c
            JOIN users u ON c.id_usuario = u.id
            JOIN eventos e ON c.id_evento = e.id";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

}

?>