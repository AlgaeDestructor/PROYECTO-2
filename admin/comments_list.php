<?php
// /admin/comments_list.php

// Start the session
session_start();

// Include necessary configuration and model files
require_once '../config/config.php';
require_once '../models/Comment.php';

// Check if the user is an admin, if not, redirect to the homepage
if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

// Instantiate the 'Comment' model
$commentModel = new Comment($pdo);

// Retrieve all comments from the database
$comments = $commentModel->getAllComments();

// Include the header template
include '../views/templates/header.php';
?>

<div class="container">
    <!-- Display the title for the 'Comments' management page -->
    <h1>Gestión de Comentarios</h1>
    
    <!-- Table displaying all comments -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <!-- Table headers -->
                <th>Usuario</th>
                <th>Evento</th>
                <th>Comentario</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop through each 'comment' and display its details in the table -->
            <?php foreach($comments as $comment): ?>
            <tr>
                <!-- Display the 'nombre_usuario', 'titulo', 'comentario', and 'estado' for each comment -->
                <td><?= htmlspecialchars($comment['nombre_usuario']) ?></td>
                <td><?= htmlspecialchars($comment['titulo']) ?></td>
                <td><?= htmlspecialchars($comment['comentario']) ?></td>
                <td><?= $comment['estado'] ?></td>
                <td>
                    <!-- If the comment is pending, display the 'Approve' button -->
                    <?php if ($comment['estado'] == 'pendiente'): ?>
                    <form method="post" action="../controllers/AdminCommentController.php" style="display:inline;">
                        <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                        <input type="hidden" name="action" value="approve">
                        <button type="submit" class="btn btn-success btn-sm">Aprobar</button>
                    </form>
                    <?php endif; ?>
                    <!-- Button to edit the comment -->
                    <button class="btn btn-secondary btn-sm" onclick="editComment(<?= $comment['id'] ?>, '<?= htmlspecialchars($comment['comentario'], ENT_QUOTES) ?>')">Editar</button>
                    <!-- Button to delete the comment, with a confirmation prompt -->
                    <a href="../controllers/AdminCommentController.php?delete=<?= $comment['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este comentario?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal window for editing a comment -->
<div class="modal" tabindex="-1" role="dialog" id="editCommentModal">
  <div class="modal-dialog" role="document">
    <form method="post" action="../controllers/AdminCommentController.php">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Comentario</h5>
          <!-- The 'x' close button has been removed -->
        </div>
        <div class="modal-body">
          <!-- Hidden fields to store the comment id and action type -->
          <input type="hidden" name="comment_id" id="edit_comment_id">
          <input type="hidden" name="action" value="update">
          
          <!-- Textarea for editing the comment -->
          <div class="form-group">
              <label for="comentario">Comentario:</label>
              <textarea name="comentario" class="form-control" id="edit_comentario" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <!-- Button to save changes -->
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
// Function to show the edit comment modal and pre-fill the form with the comment data
function editComment(commentId, commentText) {
    $('#edit_comment_id').val(commentId);
    $('#edit_comentario').val(commentText);
    $('#editCommentModal').modal('show');
}
</script>

<?php 
// Include the footer template
include '../views/templates/footer.php'; 
?>
