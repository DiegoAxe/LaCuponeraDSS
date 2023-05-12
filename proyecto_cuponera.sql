-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-05-2023 a las 04:33:52
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto_cuponera`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cupon`
--

CREATE TABLE `cupon` (
  `CodCupon` char(13) COLLATE utf8_spanish_ci NOT NULL,
  `CodPromocion` int(11) NOT NULL,
  `CodUsuario` int(11) NOT NULL,
  `EstadoCupon` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `FechaCanje` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cupon`
--

INSERT INTO `cupon` (`CodCupon`, `CodPromocion`, `CodUsuario`, `EstadoCupon`, `FechaCanje`) VALUES
('EMP0031142356', 8, 5, 'Disponible', NULL),
('EMP0031234568', 8, 5, 'Disponible', NULL),
('EMP0037894562', 8, 5, 'Disponible', NULL),
('EMP0047897485', 7, 6, 'Canjeado', '2023-03-15'),
('EMP0064521141', 6, 6, 'Disponible', NULL),
('EMP0067412369', 6, 6, 'Canjeado', '2023-02-15'),
('EMP0067845121', 6, 5, 'Canjeado', '2023-05-08'),
('EMP0077451111', 10, 4, 'Canjeado', '2023-03-09'),
('EMP0104567891', 5, 4, 'Vencido', NULL),
('EMP0104682159', 5, 4, 'Vencido', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `CodEmpresa` char(6) COLLATE utf8_spanish_ci NOT NULL,
  `NombreEmpresa` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `PorcentajeComision` int(11) NOT NULL,
  `CodRubro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`CodEmpresa`, `NombreEmpresa`, `PorcentajeComision`, `CodRubro`) VALUES
('EMP001', 'Samsung', 20, 1),
('EMP002', 'ADOC', 15, 4),
('EMP003', 'Mattel', 10, 9),
('EMP004', 'Conocimiento en Páginas', 10, 2),
('EMP005', 'Unilever', 20, 10),
('EMP006', 'Microsoft Xbox', 5, 5),
('EMP007', 'ADIDAS', 15, 7),
('EMP008', 'GUCCI', 25, 3),
('EMP009', 'Disney', 20, 6),
('EMP010', 'Pfizer', 10, 8),
('EMP591', 'Xandar Corporation', 17, 9),
('EMP849', 'Guevo Corpo', 23, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocion`
--

CREATE TABLE `promocion` (
  `CodPromocion` int(11) NOT NULL,
  `Titulo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `FotoPromocion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `Descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `Detalle` text COLLATE utf8_spanish_ci NOT NULL,
  `CantidadCupones` int(11) DEFAULT NULL,
  `FechaInicio` date NOT NULL,
  `FechaVencimiento` date NOT NULL,
  `FechaLimiteCupon` date NOT NULL,
  `PrecioRegular` float NOT NULL,
  `PrecioOferta_Cupon` float NOT NULL,
  `Estado` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `CodEmpresa` char(6) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `promocion`
--

INSERT INTO `promocion` (`CodPromocion`, `Titulo`, `FotoPromocion`, `Descripcion`, `Detalle`, `CantidadCupones`, `FechaInicio`, `FechaVencimiento`, `FechaLimiteCupon`, `PrecioRegular`, `PrecioOferta_Cupon`, `Estado`, `CodEmpresa`) VALUES
(1, 'Cartera GUCCI Colección 2023', 'promocion.jpg', 'De muy buena calidad, diseño muy cómodo y de materiales reutilizables', 'Por el precio de una lleve 3', 10, '2023-02-01', '2023-02-28', '2023-03-08', 250, 30, 'Aprobada', 'EMP009'),
(2, 'Zapatos Escolares', 'promocion.jpg', 'Zapatos de lustrar, formales y de diversas tallas, para que siempre vistas bien en cualquier lugar.', 'Pague un par de zapatos y lleve 2.', 5, '2023-02-06', '2023-02-27', '2023-03-14', 50, 15, 'Rechazada', 'EMP009'),
(3, 'Alisado de cabello2', 'promocion.jpg', 'Alisado de pelo en descuento2', 'Recibe un alisado de $10 a precio regular por tan solo $4.99', 15, '2023-03-01', '2023-04-05', '2023-04-15', 10, 4.99, 'En Espera de aprobación', 'EMP009'),
(4, 'Un Blu-Ray para un amigo', 'promocion.jpg', 'SOLO EN EL MES DE LA AMISTAD, TODOS LOS BLU-RAY A TAN SOLO $7.89', 'Ven y aprovecha esta promoción para compartir con tus amigos una tarde de excelentes películas.', 70, '2023-02-01', '2023-03-06', '2023-03-28', 15, 7.89, 'En Espera de aprobación', 'EMP009'),
(5, 'Sesiones Intensivas para Reducción de Peso', 'promocion.jpg', 'Pague $49 por 5 sesiones que cada una incluye:\r\n- Una Hidrolipoclasias Ultrasónicas: consiste en la inyección de líquido lipolíticos en zonas de adiposidad localizada y/o celulitis, seguida de una sesión de ultrasonido para aprovechar el poder de cavitación. La conjunción del ultrasonido y las sustancias previamente inyectadas produce la ruptura de las células grasas y su eliminación mediante la circulación sanguínea y linfática. También elimina la fibrosis, principal componente de la piel de naranja.\r\n- Ultracavitaciones: destruye la grasa a nivel celular por medio del ultrasonido, genera pequeñas burbujas que destruyen las células grasas, de esta forma la grasa se transforma en estado líquido y se elimina de nuestro cuerpo. También contribuye a reafirmar y aportar elasticidad. Recibirás además una asesoría nutricional y evaluación clínica que ayudarán a optimizar los resultados.', 'Sucursal: Válido en sucursal Santa Elena, Luceiro y Escalón. \r\nCita: Es necesario que reserves tu espacio llamando a Santa Elena: 2510-9989, Escalón: 7786-0592 y 2508-1775 o Luceiro: 2281-1500 o 6975-6456. Horarios: Lunes a viernes de 8:00 am a 5:00 pm y sábados de 8:00 am a 2:00 p.m. Otros: Se evaluará paciente previamente. No válido para embarazadas. Tratamiento brindado por especialistas.', 10, '2023-02-16', '2023-02-28', '2023-03-16', 300, 49, 'Descartada', 'EMP009'),
(6, 'Todos los juegos de la Saga Halo a $15.75', 'promocion.jpg', 'Aprovecha de conseguir todos los títulos de la franquicia de juegos: Halo y disfruta de una experiencia fuera de este mundo, sumérgete en aventuras emocionantes en solitario o con amigos.', 'Cada videojuego está a $15.75 incluyendo títulos, compatibles con consolas Xbox y PC, como:\r\n- HALO: Combat Evolved\r\n- HALO 2\r\n- HALO 3\r\n- HALO 3: ODST\r\n- HALO: REACH\r\n- HALO 4\r\n- HALO 5: Guardians\r\n- HALO Infinite', 45, '2023-02-25', '2023-04-06', '2023-04-20', 60.99, 15.75, 'Pasada', 'EMP009'),
(7, 'Saga de Harry Potter a tu alcance', 'promocion.jpg', 'Aprovecha de conseguir todas las novelas J. K. Rowling y conoce la historia principal de Harry Potter', 'Cada libro se vende por $11.55 y se aplica a los siguientes:\r\n- Harry Potter y la piedra filosofal\r\n- Harry Potter y la cámara secreta\r\n- Harry Potter y el prisionero de Azkaban\r\n- Harry Potter y el cáliz de fuego\r\n- Harry Potter y la Orden del Fénix\r\n- Harry Potter y el misterio del príncipe\r\n- Harry Potter y las reliquias de la Muerte ', 110, '2023-02-14', '2023-05-28', '2023-03-07', 45, 11.55, 'Activa', 'EMP009'),
(8, '¡Infección Hot Wheels!', 'promocion.jpg', 'La coleccion de vehículos Hot Wheels está a tu alcance, consigue tus favoritos y descubre nuevos modelos para tu colección', 'Paga $1.50 por cada vehículo de colección  (no se aplica a paquetes de 3, 5, 10, etc.).', 35, '2023-04-13', '2023-05-11', '2023-05-23', 5, 1.5, 'Pasada', 'EMP009'),
(9, 'Obtén tu Samsung J6 PRIME ', 'promocion.jpg', 'Aprovecha de obtener tu Samsung J6 PRIME por tan solo $109.99 y disfruta del mejor rendimiento que puedas experimentar.', 'Se aplica a cada modelo que tienen las siguientes expecificaciones:\r\n- Pantalla: 5.6\", 720 x 1480 pixels\r\n- Procesador: Exynos 7870 1.6GHz\r\n- RAM: 3GB/4GB\r\n- Almacenamiento: 32GB/64GB\r\n- Expansión: microSD\r\n- Cámara: 13 MP\r\n- Batería: 3000 mAh\r\n- OS: Android 8.0\r\n- Perfil: 8.2 mm\r\n- Peso: 154 g', 50, '2023-01-31', '2023-03-15', '2023-03-22', 350, 109.99, 'Descartada', 'EMP009'),
(10, 'Corrre más rápido, usa ADIDAS', 'promocion.jpg', 'Consigue tus zapatillas aerodinámicas ideales para trotar, correr, hacer deporte y toda actividad física que te propongas.', 'Compra tus zapatos deportivos en cualquier talla, para niños, niñas, jovenes y adultos por tan solo $25', 25, '2023-02-22', '2023-05-15', '2023-03-31', 100, 25, 'Activa', 'EMP009'),
(11, 'Boletos para película \"Elemental\"', 'promocion.jpg', 'Interesado en ver la película \"Elemental\"? Pues felicidades, tenemos una oferta para usted, para que disfrute al máximo el ver esa película con los boletos a precio rebajado', 'Boletos en promoción de la película \"Elemental\"', 72, '2023-06-20', '2023-07-19', '2023-08-07', 50, 24.99, 'Futura', 'EMP009'),
(12, 'oferta uwu', 'promocion.jpg', 'uwuwuwuwuwu x23', 'owowowowo x52', 2332, '2023-05-18', '2023-06-29', '2023-07-20', 56.77, 24.99, 'En Espera de aprobación', 'EMP009');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `CodRol` int(11) NOT NULL,
  `NombreRol` varchar(30) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`CodRol`, `NombreRol`) VALUES
(1, 'Administrador'),
(2, 'Cliente'),
(3, 'Empresa'),
(4, 'Empleado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rubro`
--

CREATE TABLE `rubro` (
  `CodRubro` int(11) NOT NULL,
  `NombreRubro` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `Descripcion` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `rubro`
--

INSERT INTO `rubro` (`CodRubro`, `NombreRubro`, `Descripcion`) VALUES
(1, 'Electrónicos', 'Descubre todas las ofertas referentes a computadoras, teléfonos, impresoras, etc.\r\n\r\nQue te serán útiles en tus tareas diarias, entretenimiento y hasta para comunicarte con tus conocidos.'),
(2, 'Libros', 'Entérate de todas las obras, novelas, libros y recursos escritos que están en oferta.'),
(3, 'Ropa', '¿En busca de un cambio de closet? No te preocupes, descubre todas las promociones en camisas, pantalones y prendas que te interesarán.'),
(4, 'Zapatos', 'Encuentra las promociones en zapatos ya sean de vestir, deportivos, casuales etc. Que te permitirán ir más lejos. '),
(5, 'Videojuegos', 'Si buscas nuevas experiencias digitales, entérate de las promociones en videojuegos que te asegurarán diversión, adrenalina e historias que seguro te gustarán.'),
(6, 'Películas', 'Si buscas medios para entretenerte, entonces descubre todas las promociones relacionadas a películas de tu interés.'),
(7, 'Deportes', 'Si te apasiona estar en una cancha defendiendo el honor de tu equipo, hazlo obteniendo las promociones en artículos deportivos que tenemos para ti y sé el mejor equipado. '),
(8, 'Salud', 'Consigue las mejores promociones en lo referente a salud como tratamientos, medicamentos, consultas, etc. Para que siempre puedas contar con una buena salud y puedas seguir comprando con nosotros.'),
(9, 'Juguetes', 'Descubre las promociones en juguetes y artículos de colección para que tu pasión por tus franquicias favoritas no se pierda.'),
(10, 'Belleza', 'Con las promociones en artículos de belleza que tenemos para ti, estás listo para que siempre puedas mostrar la mejor versión de ti.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `CodUsuario` int(11) NOT NULL,
  `DUI` char(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Foto` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Nombre` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `Apellido` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `Direccion` varchar(150) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Telefono` char(9) COLLATE utf8_spanish_ci NOT NULL,
  `Correo` varchar(35) COLLATE utf8_spanish_ci NOT NULL,
  `Contrasena` varchar(256) COLLATE utf8_spanish_ci NOT NULL,
  `CodRol` int(11) DEFAULT NULL,
  `CodEmpresa` char(6) COLLATE utf8_spanish_ci DEFAULT NULL,
  `codigo_verificacion` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Verificado` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`CodUsuario`, `DUI`, `Foto`, `Nombre`, `Apellido`, `Direccion`, `Telefono`, `Correo`, `Contrasena`, `CodRol`, `CodEmpresa`, `codigo_verificacion`, `Verificado`) VALUES
(1, '', 'foto.jpg', 'Xander Israel', 'Martínez Hernández', NULL, '6008-2268', 'xander798b@gmail.com', '63844598e0bf09e178004aa841512ce67a219a9d6959f33febc63bb6aea8257544db68398ddbf44f7afd3e45f2d19d07260a86f8fe1f3a8c9ce30352b1c37a1b', 1, NULL, '', 0),
(3, '', 'foto.jpg', 'Roberto Samuel', 'Campos Miranda', NULL, '7888-2268', 'camposRmirandaEmpleado@gmail.com', '216a2ec89d677dc90a445f9ba161654e03849dcff2c9b5a1f7b89a5f59048ed7957bdf72f189db755cc6394b0ceba0d5c99c46fd4d3b683586fd2b2482198957', 4, 'EMP001', '', 0),
(4, '24536598-8', 'foto.jpg', 'Sandra Magdalena', 'Chávez Parada', 'San Salvador, Soyapango, Calle Principal, Edificio #7', '7684-5541', 'Sandracliente@gmail.com', '6e942af1d24675ebe729080e31bd9ae418ae983518c2a3eeb62e970f040eaafcfc2e4cfdc3be65ec7c37032316ab3a2d1757bc49143fea3a380ead3b89cd923f', 2, NULL, '', 0),
(5, '24777598-8', 'foto.jpg', 'Angel Eliseo', 'Martínez Hernández', 'Chalatenango, Dulce Nombre de María, Calle Principal, Casa #12', '7684-1545', 'angelMart112@gmail.com', '1d28aff4d2c19c8cd85a29ff0f05d705ee7568a0a6492daa95826f4956f19df6ba0db61e3fe2fa944cd066b155c9009f94afaf56cba0fce4bc57a3500450e4da', 2, NULL, '', 0),
(6, '89564741-5', 'foto.jpg', 'Alvaro Antonio', 'Martínez Aragón', 'San Salvador, Soyapango, Plan del Pino, Colonia Sandra, Pasaje 2, Casa #4', '7898-8887', 'aragonAlvaro23b@gmail.com', '7d0080cbe64ab8dbb49780f3d8b3f759521e2d5c9254567b1a523db30ac1ced5c450fde31d0aec45d6aa8c9fae287e31366191ac056d63969bee66be69fb9631', 2, NULL, '', 0),
(7, '', 'EMP009.jpg', 'Carl Jean', 'Richter Fabrou', 'San Salvador, 1era calle Poniente y 4 avenida Norte, edificio #422', '7899-2268', 'jeanDisneyEmpresa@gmail.com', '7a55921fdfa31183a31ec5fced5800fc02008cfac47318b622482291a801e590ed5f1338de0e0e92945c10ee5c60cb5756f3e819c228cf37008e39beedd73008', 3, 'EMP009', '', 0),
(8, '', 'foto.jpg', 'Martín Augusto', 'Córdova Moreno', NULL, '7654-0087', 'morenoEmpleado@gmail.com', '8c8f6730a18563b6014630b9734c96174df374eff3ef93f0428ed27df84ed6c1e49aa719af79ca4ab51a7fa78ce37e61faa0b9f5bf1e3b9a8173dd4b82de810e', 4, 'EMP009', '', 0),
(9, '79898988-8', 'foto.jpg', 'Marta Juaquina', 'Cardenal Herrera', 'La Libertad, colonia Monte Sion, senda \"B\", bloc. \"D\", casa #75', '7684-1777', 'martaHerreraCliente@gmail.com', '697236699e4bf69f0bbfdb99c7b8966346820f259b90029eee6c8ac0c902d31f2f653023826cb027123ecb3500c9ed2c93f27e5dabdfbd68a33c52da487de6d5', 2, NULL, '', 0),
(10, '', 'foto.jpg', 'Romeo Adalberto', 'Rivera Montes', '', '7890-1213', 'riveraAdaRom@gmail.com', '219dc249329bbc9cd4270b593b6135b13c796a8e9284aae753a325f26ef353e0a137411715c1c29de1ad406b120e1305adc3306f94a399f56b0b4bd9e54f30bf', 4, 'EMP009', '', 0),
(21, '', 'EMP849.jpg', 'Mandraque', 'Martínez Hernández', 'Rio Bravo', '7898-7842', 'xander723b@gmail.com', '9abf53e6d8967e33a2f2857c070b5f103cea63820d9d7698bc477c3ee9422f3d8cac83ce2a315e728e8312c4d1cd67c4062a9140fa8b54812a4033f139cf9e35', 3, 'EMP849', '', 0),
(22, '', 'EMP591.jpg', 'Henry Camilo', 'Martínez Gomez', 'San Salvador La Capital', '7896-8798', 'asdsa@gmail.com', 'd654623d3b19f858745cf8348059a8078fd880fb8011bf70df3849d9496cf717bc604fd4a380c2d524516f0f64ca618b0065f764c3e05f06ae3783cc0f3eef58', 3, 'EMP591', '', 0),
(49, '06568862-7', NULL, 'Ludwin Ludwin', 'Martinez Alfaro', 'Bosques de la Paz', '7989-5209', 'ludwin604@gmail.com', 'd335956d508dd49c67c5ef92b80c27f641d6d69e89c8d6ad72b2ca0bedb8b3fe70093092590d73ea10f98aceffd5863010d79f979e195391d0cb483afaafadd5', 2, NULL, NULL, 0),
(53, '12345674-3', NULL, 'Sass Sasa', 'Ert Ert', 'D3d', '7878-6757', 'asdadsad@gmail.com', '09aa5980041b496ddd3519a5dfa1c4a3d3af9a903c75cce175377e6ffe16810c92e6a0195e149ca0e6712c141e77ec621e0ed6be2266f9bb22a6e1a813d27b8d', 2, NULL, NULL, 0),
(54, '12345672-1', NULL, 'Zsdfs Xsdf', 'Ssdsd Xsdfsfd', 'asdadad', '7654-7654', 'z123456789@gmail.com', '09aa5980041b496ddd3519a5dfa1c4a3d3af9a903c75cce175377e6ffe16810c92e6a0195e149ca0e6712c141e77ec621e0ed6be2266f9bb22a6e1a813d27b8d', 2, NULL, NULL, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cupon`
--
ALTER TABLE `cupon`
  ADD PRIMARY KEY (`CodCupon`),
  ADD KEY `CodPromocion` (`CodPromocion`),
  ADD KEY `CodUsuario` (`CodUsuario`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`CodEmpresa`),
  ADD KEY `CodRubro` (`CodRubro`);

--
-- Indices de la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD PRIMARY KEY (`CodPromocion`),
  ADD KEY `CodEmpresa` (`CodEmpresa`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`CodRol`);

--
-- Indices de la tabla `rubro`
--
ALTER TABLE `rubro`
  ADD PRIMARY KEY (`CodRubro`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`CodUsuario`),
  ADD KEY `CodRol` (`CodRol`),
  ADD KEY `CodEmpresa` (`CodEmpresa`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `promocion`
--
ALTER TABLE `promocion`
  MODIFY `CodPromocion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `CodRol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `rubro`
--
ALTER TABLE `rubro`
  MODIFY `CodRubro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `CodUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD CONSTRAINT `empresa_ibfk_2` FOREIGN KEY (`CodRubro`) REFERENCES `rubro` (`CodRubro`);

--
-- Filtros para la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD CONSTRAINT `promocion_ibfk_1` FOREIGN KEY (`CodEmpresa`) REFERENCES `empresa` (`CodEmpresa`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`CodRol`) REFERENCES `rol` (`CodRol`),
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`CodEmpresa`) REFERENCES `empresa` (`CodEmpresa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
