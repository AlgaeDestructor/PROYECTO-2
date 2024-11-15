<?php
// event.php

session_start(); // Start the session to access session variables like user data
require_once 'config/config.php'; // Include the configuration file for the database connection
require_once 'models/Event.php'; // Include the Event model to interact with events data
require_once 'models/Comment.php'; // Include the Comment model to manage comments
require_once 'models/Valoracion.php'; // Include the Valoracion (Rating) model to manage ratings
require_once 'models/Favorite.php'; // Include the Favorite model to manage user favorites

// Create instances of the models to interact with the database
$eventModel = new Event($pdo);
$commentModel = new Comment($pdo);
$valoracionModel = new Valoracion($pdo);
$favoriteModel = new Favorite($pdo);

// Get the event ID from the URL
$id_evento = $_GET['id'] ?? null; // If the id is not set, default to null

if (!$id_evento) { // If no event ID is provided, redirect to the home page
    header('Location: index.php');
    exit;
}

// Increment the views for the event when it is accessed
$eventModel->incrementViews($id_evento);

// Fetch the event details from the database
$event = $eventModel->getEventById($id_evento);
if (!$event) { // If the event is not found, show an error message
    echo "Evento no encontrado";
    exit;
}

// Fetch published comments for the event
$comments = $commentModel->getPublishedComments($id_evento);

// Fetch the average rating for the event
$averageRating = $valoracionModel->getAverageRating($id_evento);

// Fetch the user's rating for the event, if they are logged in
$userRating = isset($_SESSION['user']) ? $valoracionModel->getUserRating($id_evento, $_SESSION['user']['id']) : null;

// Check if the event is in the user's favorites list, if they are logged in
$isFavorite = false;
if (isset($_SESSION['user'])) {
    $isFavorite = $favoriteModel->isFavorite($_SESSION['user']['id'], $id_evento);
}

// Include the header template
include 'views/templates/header.php';
?>

<main>
    <div class="container mt-5">
        <div class="card border-0 shadow-lg" style="background-color: #A5D6A7;">
            <div class="card-body">
                <!-- Event title and description -->
                <h1 class="card-title display-4" style="color: #0A2E0A;"><?= htmlspecialchars($event['titulo']) ?></h1>
                <p class="lead"><?= htmlspecialchars($event['descripcion']) ?></p>
                
                <!-- Event details (date, time, type, and average rating) -->
                <div class="mb-4" style="color: #0A2E0A;">
                    <p><strong>Fecha:</strong> <?= $event['fecha'] ?> <strong>Hora:</strong> <?= $event['hora'] ?></p>
                    <p><strong>Tipo:</strong> <?= htmlspecialchars($event['tipo']) ?></p>
                    <?php if ($averageRating !== null): ?>
                        <p><strong>Puntuación promedio:</strong> <?= $averageRating ?>/5</p>
                    <?php else: ?>
                        <p><strong>Puntuación promedio:</strong> Sin valoraciones</p>
                    <?php endif; ?>
                </div>

                <!-- Favorite button, only visible to logged-in users -->
                <?php if (isset($_SESSION['user'])): ?>
                    <form method="post" action="controllers/FavoriteController.php">
                        <input type="hidden" name="event_id" value="<?= $id_evento ?>">
                        <input type="hidden" name="action" value="<?= $isFavorite ? 'remove' : 'add' ?>">
                        <button type="submit" class="btn btn-<?= $isFavorite ? 'danger' : 'success' ?> mb-4">
                            <?= $isFavorite ? 'Eliminar de Favoritos' : 'Agregar a Favoritos' ?>
                        </button>
                    </form>
                <?php else: ?>
                    <p><a href="views/user/login.php">Inicia sesión</a> para agregar este evento a tus favoritos.</p>
                <?php endif; ?>

                <!-- Event images gallery -->
                <?php if ($event['imagenes']): ?>
                    <div class="row">
                        <?php $imagenes = explode(',', $event['imagenes']); ?>
                        <?php foreach ($imagenes as $imagen): ?>
                            <div class="col-md-4 mb-3">
                                <img src="<?= BASE_URL ?>uploads/event_images/<?= htmlspecialchars($imagen) ?>" alt="Imagen del evento: <?= htmlspecialchars($event['titulo']) ?>" class="img-fluid rounded">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Event location map -->
                <div class="mt-5">
                    <h2>Ubicación del Evento</h2>
                    <div id="map" role="img" aria-label="Mapa mostrando la ubicación del evento" class="rounded shadow" style="height: 400px;"></div>
                </div>
            </div>
        </div>

         <!-- Average rating display -->
         <div class="mt-5">
            <?php if ($averageRating !== null): ?>
                <h2>Puntuación promedio: <?= $averageRating ?>/5</h2>
            <?php else: ?>
                <h2>Puntuación promedio: Sin valoraciones</h2>
            <?php endif; ?>
        </div>

        <!-- Rating section -->
        <?php if (isset($_SESSION['user'])): ?>
            <div class="card mt-3 border-0 shadow-lg" style="background-color: #E8F5E9;">
                <div class="card-body">
                    <h2 class="card-title" style="color: #0A2E0A;">Valorar este evento</h2>
                    <form method="post" action="controllers/ValoracionController.php">
                        <input type="hidden" name="id_evento" value="<?= $id_evento ?>">
                        <div class="form-group">
                            <label for="puntuacion">Tu puntuación:</label>
                            <select id="puntuacion" name="puntuacion" class="form-control" required>
                                <option value="">Selecciona una puntuación</option>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?= $i ?>" <?= $userRating == $i ? 'selected' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar Valoración</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <p><a href="views/user/login.php">Inicia sesión</a> para valorar este evento.</p>
        <?php endif; ?>

        <!-- Comments section -->
        <div class="card mt-5 border-0 shadow-lg" style="background-color: #E8F5E9;">
            <div class="card-body">
                <h2 class="card-title" style="color: #0A2E0A;">Comentarios</h2>
                <?php foreach ($comments as $comment): ?>
                    <div class="media p-3 mb-3 rounded" style="background-color: #ffffff; border: 1px solid #A5D6A7;">
                        <?php
                        // Set default profile image if none exists for the comment author
                        $profileImage = $comment['imagen_perfil'] ? BASE_URL . 'uploads/profile_images/' . htmlspecialchars($comment['imagen_perfil']) : BASE_URL . 'assets/images/default_profile.png';
                        ?>
                        <img src="<?= $profileImage ?>" alt="Imagen de <?= htmlspecialchars($comment['nombre_usuario']) ?>" class="mr-3 rounded-circle" style="width:60px; height:60px; object-fit: cover;">
                        <div class="media-body">
                            <h5 class="mt-0"><?= htmlspecialchars($comment['nombre_usuario']) ?></h5>
                            <p><?= htmlspecialchars($comment['comentario']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Comment submission form, visible only to logged-in users -->
                <?php if (isset($_SESSION['user'])): ?>
                    <h3>Añadir Comentario</h3>
                    <form method="post" action="controllers/CommentController.php">
                        <input type="hidden" name="event_id" value="<?= $id_evento ?>">
                        <div class="form-group">
                            <textarea id="comentario" name="comentario" class="form-control" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar Comentario</button>
                    </form>
                <?php else: ?>
                    <p><a href="views/user/login.php">Inicia sesión</a> para comentar.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<!-- Map with Leaflet -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    var latitud = <?= json_encode($event['latitud']) ?>;
    var longitud = <?= json_encode($event['longitud']) ?>;

    if (latitud && longitud) {
        var map = L.map('map').setView([latitud, longitud], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var marker = L.marker([latitud, longitud]).addTo(map)
            .bindPopup('<?= htmlspecialchars($event['titulo']) ?>')
            .openPopup();

        marker._icon.setAttribute('role', 'button');
        marker._icon.setAttribute('aria-label', 'Ubicación del evento <?= htmlspecialchars($event['titulo']) ?>');

        map.eachLayer(function (layer) {
            if (layer instanceof L.TileLayer) {
                layer.on('tileload', function (event) {
                    event.tile.setAttribute('alt', '');
                    event.tile.setAttribute('role', 'presentation');
                    event.tile.setAttribute('aria-hidden', 'true');
                });
            }
        });
    } else {
        console.error('Coordenadas inválidas para el mapa.');
    }
</script>

<?php include 'views/templates/footer.php'; ?>
