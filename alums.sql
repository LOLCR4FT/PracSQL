-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Temps de generació: 20-04-2021 a les 12:22:18
-- Versió del servidor: 10.4.11-MariaDB
-- Versió de PHP: 7.2.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de dades: `alums`
--

-- --------------------------------------------------------

--
-- Estructura de la taula `autor`
--

CREATE TABLE `autor` (
  `id` int(11) NOT NULL,
  `nom` varchar(32) NOT NULL,
  `data_naix` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Bolcament de dades per a la taula `autor`
--

INSERT INTO `autor` (`id`, `nom`, `data_naix`) VALUES
(1, 'Albert Sánchez Piñol', '1965-07-12'),
(3, 'Miguel de Cervantes', '1547-09-29');

-- --------------------------------------------------------

--
-- Estructura de la taula `llibre`
--

CREATE TABLE `llibre` (
  `id` int(11) NOT NULL,
  `id_autor` int(11) NOT NULL,
  `titol` varchar(80) NOT NULL,
  `any` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índexs per a les taules bolcades
--

--
-- Índexs per a la taula `autor`
--
ALTER TABLE `autor`
  ADD PRIMARY KEY (`id`);

--
-- Índexs per a la taula `llibre`
--
ALTER TABLE `llibre`
  ADD PRIMARY KEY (`id`,`id_autor`);

--
-- AUTO_INCREMENT per les taules bolcades
--

--
-- AUTO_INCREMENT per la taula `autor`
--
ALTER TABLE `autor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la taula `llibre`
--
ALTER TABLE `llibre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
