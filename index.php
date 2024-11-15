<?php
// index.php

session_start();
require_once 'config/config.php';
require_once 'models/Event.php';

$eventModel = new Event($pdo);

// Obtener los parámetros de búsqueda si existen
$search = $_GET['search'] ?? ''; // Check if search parameter exists
$date = $_GET['date'] ?? ''; // Check if date parameter exists

$eventos = $eventModel->getUpcomingEvents($search, $date); // Fetch upcoming events based on search and date

include 'views/templates/header.php'; // Include the header template
?>

<main>
    <div class="container mt-4">
        <div class="jumbotron text-center">
            <h1 class="display-4">Eventos Sostenibles</h1>
            <p class="lead">Aquí podrás ver nuestros eventos sostenibles</p>
            <hr class="my-4">
            <p>Regístrate para poder tener favoritos, comentar y valorar</p>
            <?php if (!isset($_SESSION['user'])): ?>
                <a class="btn btn-primary btn-lg" href="views/user/register.php" role="button">Regístrate Ahora</a> <!-- Register button for non-logged-in users -->
            <?php endif; ?>
        </div>

        <!-- Search form -->
        <form method="get" action="index.php" class="mb-4">
            <div class="form-row">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre" value="<?= htmlspecialchars($search) ?>"> <!-- Search by event name -->
                </div>
                <div class="col-md-5">
                    <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($date) ?>"> <!-- Search by event date -->
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">Buscar</button> <!-- Submit button for search -->
                </div>
            </div>
        </form>

        <!-- List of upcoming events -->
        <section aria-labelledby="eventos-proximos">
            <h2 id="eventos-proximos">Próximos Eventos</h2>
            <?php if (count($eventos) > 0): ?>
                <div class="row">
                    <?php foreach ($eventos as $evento): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <?php if ($evento['imagenes']): ?>
                                    <?php $imagenes = explode(',', $evento['imagenes']); ?>
                                    <img src="<?= BASE_URL ?>uploads/event_images/<?= htmlspecialchars($imagenes[0]) ?>" alt="<?= htmlspecialchars($evento['titulo']) ?>" class="card-img-top"> <!-- Event image if available -->
                                <?php else: ?>
                                    <img src="<?= BASE_URL ?>assets/images/default_event.png" alt="Imagen por defecto del evento" class="card-img-top"> <!-- Default event image -->
                                <?php endif; ?>
                                <div class="card-body d-flex flex-column">
                                    <h3 class="card-title">
                                        <a href="event.php?id=<?= $evento['id'] ?>"><?= htmlspecialchars($evento['titulo']) ?></a> <!-- Event title with link to event details -->
                                    </h3>
                                    <p class="card-text"><?= htmlspecialchars(substr($evento['descripcion'], 0, 100)) ?>...</p> <!-- Event description preview -->
                                    <p class="card-text event-details">
                                        <strong>Fecha:</strong> <?= $evento['fecha'] ?> <strong>Hora:</strong> <?= $evento['hora'] ?> <!-- Event date and time -->
                                    </p>
                                    <a href="event.php?id=<?= $evento['id'] ?>" class="btn btn-primary mt-auto">Ver Detalles</a> <!-- Button to view event details -->
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No se encontraron eventos con los criterios de búsqueda.</p> <!-- No events found message -->
            <?php endif; ?>
        </section>

        <!-- Map section -->
        <section aria-labelledby="mapa-eventos">
            <h2 id="mapa-eventos">Mapa de Eventos</h2>
            <div id="map" role="img" aria-label="Mapa mostrando la ubicación de los próximos eventos"></div> <!-- Map container for event locations -->
        </section>
    </div>
</main>

<!-- Include Leaflet JS before your custom script -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    // Initialize the map
    var map = L.map('map').setView([40.416775, -3.703790], 6); // Set initial map view to Spain coordinates

    // Add OpenStreetMap layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Add markers for each event
    <?php foreach ($eventos as $evento): ?>
        <?php if ($evento['latitud'] && $evento['longitud']): ?>
            var marker = L.marker([<?= $evento['latitud'] ?>, <?= $evento['longitud'] ?>]).addTo(map)
                .bindPopup('<a href="event.php?id=<?= $evento['id'] ?>"><?= htmlspecialchars($evento['titulo']) ?></a>'); <!-- Popup with event title -->

            // Add ARIA attributes to the marker
            marker._icon.setAttribute('role', 'button');
            marker._icon.setAttribute('aria-label', 'Ubicación del evento <?= htmlspecialchars($evento['titulo']) ?>'); <!-- Accessibility label for event location -->

            // Mark Leaflet tile images as aria-hidden
            map.eachLayer(function (layer) {
                if (layer instanceof L.TileLayer) {
                    layer.on('tileload', function (event) {
                        event.tile.setAttribute('alt', ''); // Remove alt text for tiles
                        event.tile.setAttribute('role', 'presentation'); // Mark tile images as decorative
                        event.tile.setAttribute('aria-hidden', 'true'); // Hide tile images from screen readers
                    });
                }
            });
        <?php endif; ?>
    <?php endforeach; ?>
</script>
<?php include 'views/templates/footer.php'; ?>
