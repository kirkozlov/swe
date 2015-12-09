-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 07. Dez 2015 um 15:07
-- Server-Version: 10.1.9-MariaDB
-- PHP-Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `swegehos_swe`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `detailedtexts`
--

CREATE TABLE `detailedtexts` (
  `id` int(10) UNSIGNED NOT NULL,
  `offersid` int(10) UNSIGNED NOT NULL,
  `detailledtext` text NOT NULL,
  `insideid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `images`
--

CREATE TABLE `images` (
  `id` int(10) UNSIGNED NOT NULL,
  `offersid` int(10) UNSIGNED NOT NULL,
  `image` longblob NOT NULL,
  `insideid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `interests`
--

CREATE TABLE `interests` (
  `id` int(10) UNSIGNED NOT NULL,
  `offersid` int(10) UNSIGNED NOT NULL,
  `userid` int(10) UNSIGNED NOT NULL,
  `interestsdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `offers`
--

CREATE TABLE `offers` (
  `id` int(10) UNSIGNED NOT NULL,
  `userid` int(10) UNSIGNED NOT NULL,
  `mainimage` longblob NOT NULL,
  `maintext` varchar(50) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longtitude` decimal(11,8) NOT NULL,
  `createdata` datetime NOT NULL,
  `changedata` datetime NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `offers_tags`
--

CREATE TABLE `offers_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `offersid` int(10) UNSIGNED NOT NULL,
  `tagsid` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tags`
--

CREATE TABLE `tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `adminflag` tinyint(1) NOT NULL DEFAULT '0',
  `goldflag` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `users`
--



--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `detailedtexts`
--
ALTER TABLE `detailedtexts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offersid` (`offersid`);

--
-- Indizes für die Tabelle `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offersid` (`offersid`);

--
-- Indizes für die Tabelle `interests`
--
ALTER TABLE `interests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offersid` (`offersid`),
  ADD KEY `userid` (`userid`);

--
-- Indizes für die Tabelle `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- Indizes für die Tabelle `offers_tags`
--
ALTER TABLE `offers_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offersid` (`offersid`),
  ADD KEY `tagsid` (`tagsid`);

--
-- Indizes für die Tabelle `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `detailedtexts`
--
ALTER TABLE `detailedtexts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `images`
--
ALTER TABLE `images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `interests`
--
ALTER TABLE `interests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `offers_tags`
--
ALTER TABLE `offers_tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `detailedtexts`
--
ALTER TABLE `detailedtexts`
  ADD CONSTRAINT `detailedtexts_ibfk_1` FOREIGN KEY (`offersid`) REFERENCES `offers` (`id`);

--
-- Constraints der Tabelle `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`offersid`) REFERENCES `offers` (`id`);

--
-- Constraints der Tabelle `interests`
--
ALTER TABLE `interests`
  ADD CONSTRAINT `interests_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `interests_ibfk_2` FOREIGN KEY (`offersid`) REFERENCES `offers` (`id`);

--
-- Constraints der Tabelle `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `offers_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`);

--
-- Constraints der Tabelle `offers_tags`
--
ALTER TABLE `offers_tags`
  ADD CONSTRAINT `offers_tags_ibfk_1` FOREIGN KEY (`offersid`) REFERENCES `offers` (`id`),
  ADD CONSTRAINT `offers_tags_ibfk_2` FOREIGN KEY (`tagsid`) REFERENCES `tags` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
