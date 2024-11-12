<?php
session_start();
require_once 'config/config.php';
require_once 'models/Event.php';
include 'views/templates/header.php';
?>
<div class="container">
    <h1>Eventos Próximos</h1>
<?php include 'views/templates/footer.php'; ?>
