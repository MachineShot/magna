-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2021 at 01:23 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.2.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `isp`
--

-- --------------------------------------------------------

--
-- Table structure for table `agentura`
--

CREATE TABLE `agentura` (
  `id` int(11) NOT NULL,
  `pavadinimas` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `sukurimo_data` date NOT NULL,
  `adresas` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `aprasymas` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `imones_kodas` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `miestas` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `pasto_kodas` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `fk_vadovo_slapyvardis` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Dumping data for table `agentura`
--

INSERT INTO `agentura` (`id`, `pavadinimas`, `sukurimo_data`, `adresas`, `aprasymas`, `imones_kodas`, `miestas`, `pasto_kodas`, `fk_vadovo_slapyvardis`) VALUES
(1, 'P4v4d1n1m4s', '2021-08-13', 'adresas 15g.', 'aprasymas wow!', '12365465', 'Vilnius', 'LT-987714', 'vad1');

-- --------------------------------------------------------

--
-- Table structure for table `el_laisko_sablonas`
--

CREATE TABLE `el_laisko_sablonas` (
  `id` int(11) NOT NULL,
  `zinute` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `paskirtis` enum('registracijos_patvirtinimas','slaptazodzio_keitimas','saskaitos_sukurimas') COLLATE utf8_lithuanian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fizine_reklama`
--

CREATE TABLE `fizine_reklama` (
  `id` int(11) NOT NULL,
  `miestas` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `adresas` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `koordinates` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `dydis` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `fk_reklamos_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Dumping data for table `fizine_reklama`
--

INSERT INTO `fizine_reklama` (`id`, `miestas`, `adresas`, `koordinates`, `dydis`, `fk_reklamos_id`) VALUES
(1, 'Kaunas', 'gatvė 5-6', '20.3332, 15.99', '5x3m', 3),
(2, 'Klaipėda', 'Adresas 16', '20.5, 16.4', '2x2m', 4);

-- --------------------------------------------------------

--
-- Table structure for table `idarbina`
--

CREATE TABLE `idarbina` (
  `fk_agentura_id` int(11) NOT NULL,
  `fk_tiekejas_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Dumping data for table `idarbina`
--

INSERT INTO `idarbina` (`fk_agentura_id`, `fk_tiekejas_id`) VALUES
(1, 7),
(1, 10),
(1, 11);

-- --------------------------------------------------------

--
-- Table structure for table `internetine_reklama`
--

CREATE TABLE `internetine_reklama` (
  `id` int(11) NOT NULL,
  `puslapio_adresas` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `tipas` enum('animuota','statine') COLLATE utf8_lithuanian_ci NOT NULL,
  `fk_reklamos_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Dumping data for table `internetine_reklama`
--

INSERT INTO `internetine_reklama` (`id`, `puslapio_adresas`, `tipas`, `fk_reklamos_id`) VALUES
(1, 'https://www.dqwdqwd.com', 'statine', 1),
(2, 'http://www.bitconnect.io', 'statine', 3),
(3, 'https://www.12-aaa.org', 'animuota', 2),
(4, 'http://www.yo.yo', 'statine', 4);

-- --------------------------------------------------------

--
-- Table structure for table `komentaras`
--

CREATE TABLE `komentaras` (
  `id` int(11) NOT NULL,
  `zinute` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `issiuntimo_data` datetime NOT NULL,
  `tipas` enum('atsiliepimas','prasymas') COLLATE utf8_lithuanian_ci NOT NULL,
  `fk_uzsakymo_nr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mokejimo_duomenys`
--

CREATE TABLE `mokejimo_duomenys` (
  `id` int(11) NOT NULL,
  `korteles_nr` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `cvv` int(11) NOT NULL,
  `galiojimo_data` datetime NOT NULL,
  `vardas` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `pavarde` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `adresas` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `miestas` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `pasto_kodas` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `fk_naudotojo_slapyvardis` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `naudotojas`
--

CREATE TABLE `naudotojas` (
  `slapyvardis` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `vardas` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `pavarde` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `tel_nr` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `slaptazodis` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `gimimo_data` date NOT NULL,
  `tipas` enum('tiekejas','uzsakovas','vadovas') COLLATE utf8_lithuanian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Dumping data for table `naudotojas`
--

INSERT INTO `naudotojas` (`slapyvardis`, `email`, `vardas`, `pavarde`, `tel_nr`, `slaptazodis`, `gimimo_data`, `tipas`) VALUES
('Jonas99', 'ddddawd@pgg.com', 'Jonas', 'Jonaitis', '+37099971587', '123', '1979-09-09', 'tiekejas'),
('Petras5', 'dddawd@pgg.com', 'Petras', 'Petraitis', '+37069871587', '123', '1999-09-09', 'tiekejas'),
('tie1', 'tie1@gmail.com', 'tiek3j4s', 'pavard33', '+370999999999', '123', '1992-12-19', 'tiekejas'),
('tie2', 'dawda@gadwa.com', 'VARDAS1', 'Pavard33455', '+370884848', '123', '1988-07-02', 'tiekejas'),
('tOMAS', 'd32d@pgg.com', 'Tomas', 'Tomaitis', '+37069877787', '123', '1982-09-09', 'tiekejas'),
('uzs1', 'naud1@gmail.com', 'Naudoooja', 'NaudotoPAVar', '+37065555555', '123', '1999-10-21', 'uzsakovas'),
('vad1', 'vad1@gmail.com', 'vadovasS', 'Pavardd', '+37063333333', '123', '1988-12-10', 'vadovas');

-- --------------------------------------------------------

--
-- Table structure for table `reklama`
--

CREATE TABLE `reklama` (
  `id` int(11) NOT NULL,
  `kaina` decimal(18,2) NOT NULL,
  `pavadinimas` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `sudarymo_data` datetime NOT NULL,
  `galiojimo_laikotarpis` datetime NOT NULL,
  `aktyvi` tinyint(1) NOT NULL,
  `fk_tiekejo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Dumping data for table `reklama`
--

INSERT INTO `reklama` (`id`, `kaina`, `pavadinimas`, `sudarymo_data`, `galiojimo_laikotarpis`, `aktyvi`, `fk_tiekejo_id`) VALUES
(1, '158.99', 'Epic reklama', '2021-11-01 00:00:00', '2021-11-30 00:00:00', 1, 7),
(2, '69.40', 'Hot moms near you CALL NOW', '2021-11-03 14:07:28', '2021-11-30 00:00:00', 0, 11),
(3, '13.80', 'Buy bitcoin it\'s not a scam!', '2021-12-01 14:34:06', '2022-02-28 00:00:00', 1, 11),
(4, '9.99', 'yo', '2021-12-02 04:00:00', '2022-03-19 00:00:00', 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `saskaita`
--

CREATE TABLE `saskaita` (
  `nr` int(11) NOT NULL,
  `apmokejimo_data` datetime NOT NULL,
  `suma` decimal(18,2) NOT NULL,
  `fk_uzsakymo_nr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tiekejas`
--

CREATE TABLE `tiekejas` (
  `id` int(11) NOT NULL,
  `isidarbinimo_data` date NOT NULL,
  `adresas` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `darbo_stazas` float NOT NULL,
  `fk_naudotojo_slapyvardis` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Dumping data for table `tiekejas`
--

INSERT INTO `tiekejas` (`id`, `isidarbinimo_data`, `adresas`, `darbo_stazas`, `fk_naudotojo_slapyvardis`) VALUES
(7, '2021-11-30', 'adresas nauj4s', 7.6, 'Jonas99'),
(10, '2021-12-01', 'hjiljil', 2.6, 'tie2'),
(11, '2021-12-01', 'Vilnius kaak 5', 7.9, 'tOMAS');

-- --------------------------------------------------------

--
-- Table structure for table `uzsakymas`
--

CREATE TABLE `uzsakymas` (
  `nr` int(11) NOT NULL,
  `kaina` decimal(18,2) NOT NULL,
  `sudarymo_data` datetime NOT NULL,
  `pabaigos_data` datetime NOT NULL,
  `busena` enum('ruosiama','vykdoma','neaktyvi') COLLATE utf8_lithuanian_ci NOT NULL,
  `fk_uzsakovo_slapyvardis` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL,
  `fk_reklama_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Dumping data for table `uzsakymas`
--

INSERT INTO `uzsakymas` (`nr`, `kaina`, `sudarymo_data`, `pabaigos_data`, `busena`, `fk_uzsakovo_slapyvardis`, `fk_reklama_id`) VALUES
(1, '69.40', '2021-11-18 12:00:00', '2021-11-24 18:00:00', 'neaktyvi', 'uzs1', 2),
(2, '13.80', '2021-12-01 00:00:00', '2021-12-25 00:00:00', 'vykdoma', 'uzs1', 3),
(3, '13.80', '2021-12-01 15:00:00', '2021-12-15 00:00:00', 'ruosiama', 'uzs1', 3),
(4, '25.00', '2021-11-05 00:00:00', '2021-11-06 00:00:00', 'neaktyvi', 'uzs1', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agentura`
--
ALTER TABLE `agentura`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fk_vadovo_slapyvardis` (`fk_vadovo_slapyvardis`);

--
-- Indexes for table `el_laisko_sablonas`
--
ALTER TABLE `el_laisko_sablonas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fizine_reklama`
--
ALTER TABLE `fizine_reklama`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turi_fizine_reklama` (`fk_reklamos_id`);

--
-- Indexes for table `idarbina`
--
ALTER TABLE `idarbina`
  ADD PRIMARY KEY (`fk_tiekejas_id`,`fk_agentura_id`);

--
-- Indexes for table `internetine_reklama`
--
ALTER TABLE `internetine_reklama`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turi_internetine_reklama` (`fk_reklamos_id`);

--
-- Indexes for table `komentaras`
--
ALTER TABLE `komentaras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parasomas` (`fk_uzsakymo_nr`);

--
-- Indexes for table `mokejimo_duomenys`
--
ALTER TABLE `mokejimo_duomenys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `apmoka_su` (`fk_naudotojo_slapyvardis`);

--
-- Indexes for table `naudotojas`
--
ALTER TABLE `naudotojas`
  ADD PRIMARY KEY (`slapyvardis`);

--
-- Indexes for table `reklama`
--
ALTER TABLE `reklama`
  ADD PRIMARY KEY (`id`),
  ADD KEY `daro` (`fk_tiekejo_id`);

--
-- Indexes for table `saskaita`
--
ALTER TABLE `saskaita`
  ADD PRIMARY KEY (`nr`),
  ADD KEY `ieina` (`fk_uzsakymo_nr`);

--
-- Indexes for table `tiekejas`
--
ALTER TABLE `tiekejas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fk_naudotojo_slapyvardis` (`fk_naudotojo_slapyvardis`);

--
-- Indexes for table `uzsakymas`
--
ALTER TABLE `uzsakymas`
  ADD PRIMARY KEY (`nr`),
  ADD KEY `uzsako` (`fk_uzsakovo_slapyvardis`),
  ADD KEY `priklauso` (`fk_reklama_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agentura`
--
ALTER TABLE `agentura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fizine_reklama`
--
ALTER TABLE `fizine_reklama`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `internetine_reklama`
--
ALTER TABLE `internetine_reklama`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `komentaras`
--
ALTER TABLE `komentaras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mokejimo_duomenys`
--
ALTER TABLE `mokejimo_duomenys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reklama`
--
ALTER TABLE `reklama`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tiekejas`
--
ALTER TABLE `tiekejas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agentura`
--
ALTER TABLE `agentura`
  ADD CONSTRAINT `valdo` FOREIGN KEY (`fk_vadovo_slapyvardis`) REFERENCES `naudotojas` (`slapyvardis`);

--
-- Constraints for table `fizine_reklama`
--
ALTER TABLE `fizine_reklama`
  ADD CONSTRAINT `turi_fizine_reklama` FOREIGN KEY (`fk_reklamos_id`) REFERENCES `reklama` (`id`);

--
-- Constraints for table `idarbina`
--
ALTER TABLE `idarbina`
  ADD CONSTRAINT `idarbina` FOREIGN KEY (`fk_tiekejas_id`) REFERENCES `tiekejas` (`id`);

--
-- Constraints for table `internetine_reklama`
--
ALTER TABLE `internetine_reklama`
  ADD CONSTRAINT `turi_internetine_reklama` FOREIGN KEY (`fk_reklamos_id`) REFERENCES `reklama` (`id`);

--
-- Constraints for table `komentaras`
--
ALTER TABLE `komentaras`
  ADD CONSTRAINT `parasomas` FOREIGN KEY (`fk_uzsakymo_nr`) REFERENCES `uzsakymas` (`nr`);

--
-- Constraints for table `mokejimo_duomenys`
--
ALTER TABLE `mokejimo_duomenys`
  ADD CONSTRAINT `apmoka_su` FOREIGN KEY (`fk_naudotojo_slapyvardis`) REFERENCES `naudotojas` (`slapyvardis`);

--
-- Constraints for table `reklama`
--
ALTER TABLE `reklama`
  ADD CONSTRAINT `daro` FOREIGN KEY (`fk_tiekejo_id`) REFERENCES `tiekejas` (`id`);

--
-- Constraints for table `saskaita`
--
ALTER TABLE `saskaita`
  ADD CONSTRAINT `ieina` FOREIGN KEY (`fk_uzsakymo_nr`) REFERENCES `uzsakymas` (`nr`);

--
-- Constraints for table `tiekejas`
--
ALTER TABLE `tiekejas`
  ADD CONSTRAINT `yra` FOREIGN KEY (`fk_naudotojo_slapyvardis`) REFERENCES `naudotojas` (`slapyvardis`);

--
-- Constraints for table `uzsakymas`
--
ALTER TABLE `uzsakymas`
  ADD CONSTRAINT `priklauso` FOREIGN KEY (`fk_reklama_id`) REFERENCES `reklama` (`id`),
  ADD CONSTRAINT `uzsako` FOREIGN KEY (`fk_uzsakovo_slapyvardis`) REFERENCES `naudotojas` (`slapyvardis`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
