-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2026 at 01:21 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `alquilersecm`
--

-- --------------------------------------------------------

--
-- Table structure for table `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` bigint(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `numero_documento` varchar(20) NOT NULL,
  `edad` int(11) NOT NULL,
  `direccion` varchar(300) DEFAULT NULL,
  `contacto` varchar(15) DEFAULT NULL,
  `correo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `nombre`, `numero_documento`, `edad`, `direccion`, `contacto`, `correo`) VALUES
(1, 'Gerardo Enrique Campos Martinez', '045058551', 34, 'Residencial la gloria, Pasaje F-2, casa #39, mejicanos', '+50371494009', '0'),
(2, 'Gerardo Enrique Campos Martinez', '045058552', 34, 'Residencial la gloria, Pasaje F-2, casa #39, mejicanos', '+50371494009', 'reynadocampos19@gmail.com'),
(4, '', '', 0, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` int(11) NOT NULL,
  `id_cliente` bigint(20) NOT NULL,
  `id_vehiculo` bigint(20) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `estado_pago` varchar(30) DEFAULT NULL,
  `numero_reserva` varchar(50) NOT NULL,
  `monto_total` double DEFAULT NULL,
  `fecha_reserva` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehiculo`
--

CREATE TABLE `vehiculo` (
  `id_vehiculo` bigint(20) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `anio` varchar(6) NOT NULL,
  `descripcion` varchar(300) DEFAULT NULL,
  `categoria` varchar(15) NOT NULL,
  `preciodiario` decimal(10,2) NOT NULL,
  `imagepath` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehiculo`
--

INSERT INTO `vehiculo` (`id_vehiculo`, `marca`, `modelo`, `anio`, `descripcion`, `categoria`, `preciodiario`, `imagepath`) VALUES
(1, 'Kia', 'Forte', '2018', 'Automático;Motor 1.8;Vidrios eléctricos;Pantalla digital;Aire acondicionado', 'Sedan', 25.00, 'images/kiaforte2018/'),
(2, 'Kia', 'Forte', '2019', 'Automático;Motor 1.8;Vidrios eléctricos;Pantalla digital;Aire acondicionado', 'Sedan', 30.00, 'images/kiaforte2019/'),
(3, 'Kia', 'Forte', '2020', 'Automático;Motor 1.8;Vidrios eléctricos;Pantalla digital;Aire acondicionado', 'Sedan', 26.00, 'images/kiaforte2019/'),
(4, 'Kia', 'Forte', '2021', 'Automático;Motor 1.8;Vidrios eléctricos;Pantalla digital;Aire acondicionado', 'Sedan', 27.00, 'images/kiaforte2018/'),
(5, 'Kia', 'Forte', '2022', 'Automático;Motor 1.8;Vidrios eléctricos;Pantalla digital;Aire acondicionado', 'Sedan', 28.00, 'images/kiaforte2019/');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `numero_documento` (`numero_documento`);

--
-- Indexes for table `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `reserva_vehiculo` (`id_vehiculo`),
  ADD KEY `reserva_cliente` (`id_cliente`);

--
-- Indexes for table `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD PRIMARY KEY (`id_vehiculo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehiculo`
--
ALTER TABLE `vehiculo`
  MODIFY `id_vehiculo` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `reserva_cliente` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `reserva_vehiculo` FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculo` (`id_vehiculo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
