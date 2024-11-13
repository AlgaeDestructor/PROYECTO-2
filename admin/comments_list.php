<?php

session_start();
require_once '../config/config.php';
require_once '../models/Comment.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$commentModel = new Comment($pdo);
$comments = $commentModel->getAllComments();

include '../views/templates/header.php';
?>

<div class="container">
    <h1>Gestión de Comentarios</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Evento</th>
                <th>Comentario</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($comments as $comment): ?>
            <tr>
                <td><?= htmlspecialchars($comment['nombre_usuario']) ?></td>
                <td><?= htmlspecialchars($comment['titulo']) ?></td>
                <td><?= htmlspecialchars($comment['comentario']) ?></td>
                <td><?= $comment['estado'] ?></td>
                <td>
                    <?php if ($comment['estado'] == 'pendiente'): ?>
                    <form method="post" action="../controllers/AdminCommentController.php" style="display:inline;">
                        <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                        <input type="hidden" name="action" value="approve">
                        <button type="submit" class="btn btn-success btn-sm">Aprobar</button>
                    </form>
                    <?php endif; ?>
                    <button class="btn btn-secondary btn-sm" onclick="editComment(<?= $comment['id'] ?>, '<?= htmlspecialchars($comment['comentario'], ENT_QUOTES) ?>')">Editar</button>
                    <a href="../controllers/AdminCommentController.php?delete=<?= $comment['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este comentario?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal" tabindex="-1" role="dialog" id="editCommentModal">
  <div class="modal-dialog" role="document">
    <form method="post" action="../controllers/AdminCommentController.php">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Comentario</h5>
        </div>
        <div class="modal-body">
          <input type="hidden" name="comment_id" id="edit_comment_id">
          <input type="hidden" name="action" value="update">
          <div class="form-group">
              <label for="comentario">Comentario:</label>
              <textarea name="comentario" class="form-control" id="edit_comentario" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
function editComment(commentId, commentText) {
    $('#edit_comment_id').val(commentId);
    $('#edit_comentario').val(commentText);
    $('#editCommentModal').modal('show');
}
</script>

<?php include '../views/templates/footer.php'; ?>
