-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 27/02/2025 às 18:01
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_epuber`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_projetos`
--

CREATE TABLE `tb_projetos` (
  `id_proj` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `autor` varchar(100) NOT NULL,
  `tipo` int(1) NOT NULL,
  `etapa` int(1) NOT NULL DEFAULT 1,
  `criado` datetime NOT NULL DEFAULT current_timestamp(),
  `toc` varchar(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tb_projetos`
--

INSERT INTO `tb_projetos` (`id_proj`, `titulo`, `autor`, `tipo`, `etapa`, `criado`, `toc`) VALUES
(2, 'Historical Tales for Young Protestants', 'J. H. Crosse', 2, 2, '2025-02-18 09:36:56', 'HISTORICAL TALES FOR YOUNG PROTESTANTS. ? CONTENTS ? PREFACE ? THE MERCHANT OF LYONS ? THE GOOD PARSON OF LUTTERWORTH ? THE BOHEMIAN WITNESS ? THE MONK THAT SHOOK THE WORLD ? THE YOUTHFUL MARTYR ? THE MAIDEN MARTYR ? THE PROTESTANTS ? THE TRAGEDY OF ST. BARTHOLOMEW\'S DAY ? THE FLIGHT OF THE HUGUENOTS ? THE NUN OF JOUARRE ? THE GUNPOWDER PLOT ? THE FORFEITED CROWN ? THE STORY OF THE ENGLISH BIBLE'),
(3, 'Le miroir de l\'âme pechéresse', 'Marguerite de Navarre', 1, 1, '2025-02-18 09:37:41', '');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tb_projetos`
--
ALTER TABLE `tb_projetos`
  ADD PRIMARY KEY (`id_proj`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_projetos`
--
ALTER TABLE `tb_projetos`
  MODIFY `id_proj` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
