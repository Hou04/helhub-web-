-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2025 at 05:08 PM
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
-- Database: `helphub`
--

-- --------------------------------------------------------

--
-- Table structure for table `donateur`
--

CREATE TABLE `donateur` (
  `id_donateur` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `CIN` varchar(10) NOT NULL,
  `pseudo` varchar(20) NOT NULL CHECK (`pseudo` regexp '^[A-Za-z]+$'),
  `pwrd` varchar(20) NOT NULL CHECK (`pwrd` regexp '^.*[#$]$')
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donateur_projet`
--

CREATE TABLE `donateur_projet` (
  `id` int(11) NOT NULL,
  `id_projet` int(11) DEFAULT NULL,
  `id_donateur` int(11) DEFAULT NULL,
  `montant_participation` float NOT NULL,
  `date_participation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projet`
--

CREATE TABLE `projet` (
  `id_projet` int(11) NOT NULL,
  `titre` varchar(30) NOT NULL,
  `description` varchar(200) NOT NULL,
  `date_limite` date NOT NULL,
  `montant_total_a_collecter` double NOT NULL,
  `montant_total_collecte` double DEFAULT 0,
  `id_responsable_association` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `responsable_association`
--

CREATE TABLE `responsable_association` (
  `id_reponsable` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `CIN` varchar(10) NOT NULL,
  `email` varchar(20) NOT NULL,
  `nom_association` varchar(30) NOT NULL,
  `adresse_association` varchar(50) NOT NULL,
  `matricule_fiscal` varchar(20) NOT NULL CHECK (`matricule_fiscal` regexp '^\\$[A-Z]{3}\\d{2}$'),
  `logo` longblob DEFAULT NULL,
  `pseudo` varchar(20) NOT NULL CHECK (`pseudo` regexp '^[A-Za-z]+$'),
  `pwrd` varchar(20) NOT NULL CHECK (`pwrd` regexp '^.*[#$]$')
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `donateur`
--
ALTER TABLE `donateur`
  ADD PRIMARY KEY (`id_donateur`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `CIN` (`CIN`),
  ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- Indexes for table `donateur_projet`
--
ALTER TABLE `donateur_projet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_projet` (`id_projet`),
  ADD KEY `id_donateur` (`id_donateur`);

--
-- Indexes for table `projet`
--
ALTER TABLE `projet`
  ADD PRIMARY KEY (`id_projet`),
  ADD KEY `id_responsable_association` (`id_responsable_association`);

--
-- Indexes for table `responsable_association`
--
ALTER TABLE `responsable_association`
  ADD PRIMARY KEY (`id_reponsable`),
  ADD UNIQUE KEY `CIN` (`CIN`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `matricule_fiscal` (`matricule_fiscal`),
  ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donateur`
--
ALTER TABLE `donateur`
  MODIFY `id_donateur` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donateur_projet`
--
ALTER TABLE `donateur_projet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projet`
--
ALTER TABLE `projet`
  MODIFY `id_projet` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `responsable_association`
--
ALTER TABLE `responsable_association`
  MODIFY `id_reponsable` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donateur_projet`
--
ALTER TABLE `donateur_projet`
  ADD CONSTRAINT `donateur_projet_ibfk_1` FOREIGN KEY (`id_projet`) REFERENCES `projet` (`id_projet`) ON DELETE CASCADE,
  ADD CONSTRAINT `donateur_projet_ibfk_2` FOREIGN KEY (`id_donateur`) REFERENCES `donateur` (`id_donateur`) ON DELETE CASCADE;

--
-- Constraints for table `projet`
--
ALTER TABLE `projet`
  ADD CONSTRAINT `projet_ibfk_1` FOREIGN KEY (`id_responsable_association`) REFERENCES `responsable_association` (`id_reponsable`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
