-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: 192.168.101.64:3306
-- Czas generowania: 20 Gru 2021, 18:00
-- Wersja serwera: 5.7.30-33-log
-- Wersja PHP: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `kajkosho_skyshop_zestawy`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `allegro_account`
--

CREATE TABLE `allegro_account` (
  `allegro_id` int(11) NOT NULL,
  `allegro_token` varchar(1300) DEFAULT NULL,
  `allegro_refresh` varchar(1300) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `allegro_expire` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `bundle`
--

CREATE TABLE `bundle` (
  `bundle_id` int(11) NOT NULL,
  `bundle_name` text,
  `bundle_price` decimal(11,2) DEFAULT NULL,
  `bundle_max_products` int(11) DEFAULT NULL,
  `bundle_max_groups` int(11) DEFAULT NULL,
  `bundle_max_users` int(11) DEFAULT NULL,
  `bundle_max_shops` int(11) DEFAULT NULL,
  `bundle_script_time` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `buy_history`
--

CREATE TABLE `buy_history` (
  `buy_id` int(11) NOT NULL,
  `buy_datetime` datetime DEFAULT CURRENT_TIMESTAMP,
  `buy_quantity` int(11) DEFAULT NULL,
  `buy_status` varchar(25) DEFAULT NULL,
  `bundle_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `connected_shop`
--

CREATE TABLE `connected_shop` (
  `shop_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `shop_link` varchar(50) DEFAULT NULL,
  `shop_api` text,
  `shop_added_on` datetime DEFAULT CURRENT_TIMESTAMP,
  `shop_name` varchar(6) NOT NULL,
  `shop_source` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `mail_template`
--

CREATE TABLE `mail_template` (
  `id_template` int(2) NOT NULL,
  `template` text NOT NULL,
  `AltBody` text NOT NULL,
  `mail_status` int(1) NOT NULL DEFAULT '0',
  `Subject` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `source_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `mail_users`
--

CREATE TABLE `mail_users` (
  `id_user` int(2) NOT NULL,
  `email` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `HostSMTP` varchar(30) NOT NULL,
  `HostIMAP` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `mail_status` int(1) NOT NULL DEFAULT '0',
  `catalog` varchar(50) NOT NULL,
  `template_id` int(2) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `nav`
--

CREATE TABLE `nav` (
  `nav_id` int(11) NOT NULL,
  `nav_title` varchar(40) DEFAULT NULL,
  `nav_icon` varchar(40) DEFAULT NULL,
  `nav_href` varchar(100) DEFAULT NULL,
  `nav_show` tinyint(1) NOT NULL DEFAULT '1',
  `nav_privilege` int(1) DEFAULT NULL,
  `nav_force` tinyint(1) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `nav_group`
--

CREATE TABLE `nav_group` (
  `group_id` int(11) NOT NULL,
  `group_name` text,
  `group_privilege` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_email` varchar(50) DEFAULT NULL,
  `user_password` text,
  `user_privilege` int(1) DEFAULT NULL,
  `bundle_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_session`
--

CREATE TABLE `user_session` (
  `session_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_hash` text,
  `session_expired` datetime DEFAULT NULL,
  `session_saved` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zestaw`
--

CREATE TABLE `zestaw` (
  `id_glowny` int(11) NOT NULL,
  `id_produktu` text NOT NULL,
  `ilosc` int(11) NOT NULL,
  `ss_ilosc` decimal(11,0) DEFAULT NULL,
  `adddate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_prim` int(11) NOT NULL,
  `ss_sprzedaz` decimal(11,2) DEFAULT NULL,
  `ss_zakup` decimal(11,2) DEFAULT NULL,
  `ss_nazwa` text,
  `user_id` int(11) DEFAULT NULL,
  `shop_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `allegro_account`
--
ALTER TABLE `allegro_account`
  ADD PRIMARY KEY (`allegro_id`);

--
-- Indeksy dla tabeli `bundle`
--
ALTER TABLE `bundle`
  ADD PRIMARY KEY (`bundle_id`);

--
-- Indeksy dla tabeli `buy_history`
--
ALTER TABLE `buy_history`
  ADD PRIMARY KEY (`buy_id`);

--
-- Indeksy dla tabeli `connected_shop`
--
ALTER TABLE `connected_shop`
  ADD PRIMARY KEY (`shop_id`);

--
-- Indeksy dla tabeli `mail_template`
--
ALTER TABLE `mail_template`
  ADD PRIMARY KEY (`id_template`);

--
-- Indeksy dla tabeli `mail_users`
--
ALTER TABLE `mail_users`
  ADD PRIMARY KEY (`id_user`);

--
-- Indeksy dla tabeli `nav`
--
ALTER TABLE `nav`
  ADD PRIMARY KEY (`nav_id`);

--
-- Indeksy dla tabeli `nav_group`
--
ALTER TABLE `nav_group`
  ADD PRIMARY KEY (`group_id`);

--
-- Indeksy dla tabeli `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indeksy dla tabeli `user_session`
--
ALTER TABLE `user_session`
  ADD PRIMARY KEY (`session_id`);

--
-- Indeksy dla tabeli `zestaw`
--
ALTER TABLE `zestaw`
  ADD PRIMARY KEY (`id_prim`) USING BTREE;

--
-- AUTO_INCREMENT dla tabel zrzutów
--

--
-- AUTO_INCREMENT dla tabeli `allegro_account`
--
ALTER TABLE `allegro_account`
  MODIFY `allegro_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `bundle`
--
ALTER TABLE `bundle`
  MODIFY `bundle_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `buy_history`
--
ALTER TABLE `buy_history`
  MODIFY `buy_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `connected_shop`
--
ALTER TABLE `connected_shop`
  MODIFY `shop_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `mail_template`
--
ALTER TABLE `mail_template`
  MODIFY `id_template` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `mail_users`
--
ALTER TABLE `mail_users`
  MODIFY `id_user` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `nav`
--
ALTER TABLE `nav`
  MODIFY `nav_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `nav_group`
--
ALTER TABLE `nav_group`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `user_session`
--
ALTER TABLE `user_session`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `zestaw`
--
ALTER TABLE `zestaw`
  MODIFY `id_prim` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
