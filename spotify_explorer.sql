-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:8889
-- Généré le :  Dim 22 Janvier 2017 à 22:27
-- Version du serveur :  5.6.33
-- Version de PHP :  7.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `spotify_explorer`
--

-- --------------------------------------------------------

--
-- Structure de la table `albums`
--

CREATE TABLE `albums` (
  `ID` int(11) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `picture_url` varchar(1000) NOT NULL,
  `spotify_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `albums`
--

INSERT INTO `albums` (`ID`, `name`, `picture_url`, `spotify_id`) VALUES
(1, 'FRONTIERES', 'https://i.scdn.co/image/d0328a15336f4fdf1812dbcf96670d33cabc31c0', '0JaFk0xmeVyRTG7wNm0uwT'),
(2, 'AT.LONG.LAST.A$AP', 'https://i.scdn.co/image/5b443d8535895230eee04fde7172ccfb399ccf64', '3arNdjotCvtiiLFfjKngMc'),
(3, 'Fagner & Zé Ramalho (Ao Vivo)', 'https://i.scdn.co/image/b9b4d20f3a4ecebbf22b3230c725a0afbbfad8ba', '3Xu4Xnowomxlmg26bTpLPJ'),
(4, 'Christmas & Chill', 'https://i.scdn.co/image/c82b30ae6e4a240bd705e5c1111778d5425df98a', '5BFg8l4NYyZ90DWqcBjbt6'),
(5, 'Olympia 1964 (Live)', 'https://i.scdn.co/image/9ff2267d06f05088f72b5d0853d4a95f24f6089c', '2rUCBUojo3bWwFYyR9GvDx'),
(6, '50 Plus Belles Chansons', 'https://i.scdn.co/image/71cd0d82a5569c09f6271cb18dd2eb967abc4459', '5lGpuDcDXCo2LdkmNVpLL8'),
(7, 'Richard D. James Album', 'https://i.scdn.co/image/04c4f802178f4822a57c5bfd0279849fd300da0f', '43s2fKRQsOSB6rSrxtAXKK'),
(8, 'Drukqs', 'https://i.scdn.co/image/a09ff0c061b9e6009f97c4d75aef6cffc7a43f41', '1maoQPAmw44bbkNOxKlwsx');

-- --------------------------------------------------------

--
-- Structure de la table `albums_collections`
--

CREATE TABLE `albums_collections` (
  `ID_user` int(11) NOT NULL,
  `ID_album` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `albums_collections`
--

INSERT INTO `albums_collections` (`ID_user`, `ID_album`) VALUES
(34, 5),
(34, 8);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `picture_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`ID`, `name`, `password`, `mail`, `picture_path`) VALUES
(35, 'bob', '09e3c180c75ad28c6c44eea9d9f1c67e', 'bob@bob.com', NULL);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `albums`
--
ALTER TABLE `albums`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
