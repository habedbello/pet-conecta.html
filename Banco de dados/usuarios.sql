-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22/11/2025 às 17:12
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
-- Banco de dados: `petconecta`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `adocoes`
--

CREATE TABLE `adocoes` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `moradia` enum('casa_com_quintal','casa_sem_quintal','apartamento') NOT NULL,
  `concordancia` enum('sim','nao') NOT NULL,
  `tempo_sozinho` int(3) NOT NULL COMMENT 'Horas que o animal passaria sozinho por dia',
  `outros_pets` text DEFAULT NULL COMMENT 'Quais outros pets possuiu/possui',
  `data_envio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `adocoes`
--

INSERT INTO `adocoes` (`id`, `nome`, `telefone`, `email`, `moradia`, `concordancia`, `tempo_sozinho`, `outros_pets`, `data_envio`) VALUES
(1, 'severina de souza bello da silva', '2133328126', 'habedbello123@gmail.com', 'casa_com_quintal', 'sim', 5, 'Gato de 5 anos', '2025-11-22 14:58:59');

-- --------------------------------------------------------

--
-- Estrutura para tabela `doacoes_animais`
--

CREATE TABLE `doacoes_animais` (
  `id` int(11) NOT NULL,
  `pet_nome` varchar(255) NOT NULL,
  `pet_especie` enum('cachorro','gato','outro') NOT NULL,
  `pet_idade` int(3) NOT NULL COMMENT 'Idade do animal em anos',
  `pet_sexo` enum('macho','femea') NOT NULL,
  `pet_raca_porte` varchar(255) DEFAULT NULL,
  `castrado` enum('sim','nao') NOT NULL,
  `temperamento` text NOT NULL COMMENT 'Descrição do comportamento do animal',
  `motivo_doacao` text NOT NULL,
  `doador_telefone` varchar(20) NOT NULL,
  `data_envio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `nome` varchar(80) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `data_log` date NOT NULL,
  `hora_log` time NOT NULL,
  `status` varchar(100) NOT NULL,
  `usuarios_idusuarios` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `log`
--

INSERT INTO `log` (`id`, `login`, `nome`, `cpf`, `data_log`, `hora_log`, `status`, `usuarios_idusuarios`) VALUES
(4, 'habedb', 'severina de souza bello da silva', '16226650745', '2025-11-22', '11:40:25', 'Cadastro Sucesso', 1),
(5, 'habedb', 'severina de souza bello da silva', '16226650745', '2025-11-22', '11:40:30', 'Login Sucesso', 1),
(6, 'habedb', 'severina de souza bello da silva', '16226650745', '2025-11-22', '11:41:29', 'Login Sucesso', 1),
(9, 'habedb', 'severina de souza bello da silva', '16226650745', '2025-11-22', '11:49:42', 'Login Sucesso', 1),
(17, 'habedb', 'severina de souza bello da silva', '16226650745', '2025-11-22', '12:10:39', 'Login Sucesso', 1),
(24, 'habedb', 'severina de souza bello da silva', '16226650745', '2025-11-22', '12:24:22', 'Login Sucesso', 1),
(34, 'habedb', 'habed bello da silva neto', '16226650745', '2025-11-22', '12:49:04', 'Login Sucesso', 1),
(36, 'adminn', 'Usuário Master', '000.000.000', '2025-11-22', '12:52:54', 'Login Sucesso', 7);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(80) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dataNascimento` date NOT NULL,
  `sexo` varchar(50) NOT NULL,
  `nomeMaterno` varchar(80) NOT NULL,
  `CPF` varchar(11) NOT NULL,
  `celular` varchar(20) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `CEP` varchar(10) NOT NULL,
  `logradouro` varchar(100) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `complemento` varchar(50) DEFAULT NULL,
  `bairro` varchar(50) NOT NULL,
  `cidade` varchar(50) NOT NULL,
  `estado` varchar(2) NOT NULL,
  `login` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `dataNascimento`, `sexo`, `nomeMaterno`, `CPF`, `celular`, `telefone`, `CEP`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `login`, `senha`) VALUES
(1, 'habed bello da silva neto', 'habedbello123@gmail.com', '1998-11-30', 'M', 'severina de souza bello da silva', '16226650745', '552133328126', '552133328126', '21820080', 'Rua Barão de Capanema', '340', 'casa', 'Bangu', 'Rio de Janeiro', 'RJ', 'habedb', '$2y$10$XdnVjryuhdkuEMPsiC3jgeGAUZZmRjahIKtHvgE9ILRWO6FhYtVpi'),
(7, 'Usuário Master', 'master.pet@sistema.com', '1970-01-01', 'Masculino', 'Mae Master', '000.000.000', '(00) 90000-0000', '(00) 0000-0000', '00000-000', 'Rua Master', '1', 'N/A', 'Bairro Master', 'Cidade Master', 'SP', 'adminn', '$2y$10$IQe4EKYXyNNxqZwRHiHDsOIAki5WlB0wSaruZwrRRKIS.qDtZ1aaO');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `adocoes`
--
ALTER TABLE `adocoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `doacoes_animais`
--
ALTER TABLE `doacoes_animais`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_log_usuarios` (`usuarios_idusuarios`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `CPF` (`CPF`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `adocoes`
--
ALTER TABLE `adocoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `doacoes_animais`
--
ALTER TABLE `doacoes_animais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `fk_log_usuarios` FOREIGN KEY (`usuarios_idusuarios`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
