SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `anuncios` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagenes` text DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `estado` enum('borrador','publicado','caducado','eliminado') DEFAULT 'borrador',
  `soft_delete` tinyint(1) DEFAULT 0,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `anuncios` (`id`, `id_usuario`, `titulo`, `descripcion`, `imagenes`, `categoria_id`, `estado`, `soft_delete`, `fecha`) VALUES
(3, 5, 'Anuncio 1', 'Ejemplo de que se ofrece, vendo motorola 2', NULL, 1, 'publicado', 0, '2024-11-14 16:00:44'),
(4, 5, 'Recogemos muebles', 'Recogemos todos los muebles que vayais a tirar llamar a:\r\n+33 444 992 283', NULL, 3, 'publicado', 0, '2024-11-14 16:02:28'),
(5, 5, 'Punto de reciclaje para ordenadores', 'aaaaaaa', NULL, 4, 'publicado', 0, '2024-11-14 16:02:48');


CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `categorias` (`id`, `nombre`) VALUES
(2, 'Comprar'),
(4, 'Reciclar'),
(3, 'Recojer'),
(1, 'Vender');



CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `id_evento` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `estado` enum('pendiente','publicado') DEFAULT 'pendiente',
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `comentarios` (`id`, `id_evento`, `id_usuario`, `comentario`, `estado`, `fecha`) VALUES
(6, 4, 5, 'Estoy exaltado esperando este momento!11!!!', 'publicado', '2024-11-14 16:21:15'),
(7, 6, 5, 'Meow', 'publicado', '2024-11-14 16:21:48'),
(8, 5, 5, '你好，帮我逃离中共吧', 'publicado', '2024-11-14 16:23:31');


CREATE TABLE `consejos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion_breve` text DEFAULT NULL,
  `texto_explicativo` text DEFAULT NULL,
  `etiquetas` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



INSERT INTO `consejos` (`id`, `titulo`, `descripcion_breve`, `texto_explicativo`, `etiquetas`) VALUES
(2, 'Consejo1', 'ei ei consejo1 ei', 'SAMPLE TEXT (meme)', 'Consejos 1'),
(3, 'Consejo2', 'conseejo2', 'El consejo 1 esta mejor...', 'Consejamos'),
(4, 'Conserva el agua potable', 'Conservalo que hace falta', 'Si no lo conservas te quedaras sin', 'Eco');


CREATE TABLE `eventos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `latitud` decimal(10,8) NOT NULL,
  `longitud` decimal(11,8) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagenes` text DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `valoracion_promedio` float DEFAULT 0,
  `num_valoraciones` int(11) DEFAULT 0,
  `num_visualizaciones` int(11) DEFAULT 0,
  `estado` enum('vigente','vencido') DEFAULT 'vigente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `eventos` (`id`, `titulo`, `latitud`, `longitud`, `descripcion`, `imagenes`, `tipo`, `fecha`, `hora`, `valoracion_promedio`, `num_valoraciones`, `num_visualizaciones`, `estado`) VALUES
(4, 'Evento 1 CENDRASSOS', 42.27375049, 2.96445573, 'En el cendrassos preparan un concierto donde Chayanne cantará a favor de el recilaje de poliestireno', '673612baaa2cc.jpg', 'Concierto', '2025-11-13', '23:59:00', 0, 0, 4, 'vigente'),
(5, 'Asaltar la Petrem', 42.26984300, 2.96717900, 'Los combustibles fosiles son muy malos para la Tierra\r\nLos combustibles fósiles son cuatro: petróleo, carbón, gas natural y gas licuado del petróleo. Se han formado a partir de la acumulación de grandes cantidades de restos orgánicos provenientes de plantas y de animales.', '6736131ca5563.png', 'Exposicion', '2055-02-02', '00:00:00', 0, 0, 6, 'vigente'),
(6, 'Los beneficios del Oregano', 42.27161100, 2.96565800, 'Mmmm pizza', '6736139756ffa.jpg', 'Conferencia', '2026-05-07', '14:09:00', 0, 0, 4, 'vigente');



CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_evento` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



INSERT INTO `favoritos` (`id`, `id_usuario`, `id_evento`, `fecha`) VALUES
(12, 5, 5, '2024-11-14 16:20:11');



CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `imagen_perfil` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('usuario','admin') DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `nombre`, `apellidos`, `nombre_usuario`, `imagen_perfil`, `email`, `contrasena`, `rol`) VALUES
(5, 'admin', 'admin', 'admin', '67360e0313b4d.gif', 'admin@admin.com', '$2y$10$c8R9cC194zQoC7AXkzP.ee3ZUNdf.q/.k2kNIZY07KcvyzmeNewP.', 'admin'),
(7, 'user', 'user', 'user', NULL, 'user@user.com', '$2y$10$RMchhj0hIiPqXZcMr.iLwee6FQBW.yBd9cwc.cTqJ25AXdCisf01a', 'usuario');



CREATE TABLE `valoraciones` (
  `id` int(11) NOT NULL,
  `id_evento` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `puntuacion` int(11) NOT NULL CHECK (`puntuacion` between 1 and 5),
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `valoraciones` (`id`, `id_evento`, `id_usuario`, `puntuacion`, `fecha`) VALUES
(25, 4, 5, 4, '2024-11-14 16:21:26'),
(27, 6, 5, 1, '2024-11-14 16:21:52'),
(28, 5, 5, 2, '2024-11-14 16:23:36');


ALTER TABLE `anuncios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `categoria_id` (`categoria_id`);


ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);


ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_evento` (`id_evento`),
  ADD KEY `id_usuario` (`id_usuario`);


ALTER TABLE `consejos`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`,`id_evento`),
  ADD KEY `id_evento` (`id_evento`);


ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD UNIQUE KEY `email` (`email`);


ALTER TABLE `valoraciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_evento` (`id_evento`,`id_usuario`),
  ADD KEY `id_usuario` (`id_usuario`);


ALTER TABLE `anuncios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;


ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;


ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE `consejos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;


ALTER TABLE `eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;


ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;


ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;


ALTER TABLE `valoraciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;


ALTER TABLE `anuncios`
  ADD CONSTRAINT `anuncios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `anuncios_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL;

ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_evento`) REFERENCES `eventos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE;


ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`id_evento`) REFERENCES `eventos` (`id`) ON DELETE CASCADE;


ALTER TABLE `valoraciones`
  ADD CONSTRAINT `valoraciones_ibfk_1` FOREIGN KEY (`id_evento`) REFERENCES `eventos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `valoraciones_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;
