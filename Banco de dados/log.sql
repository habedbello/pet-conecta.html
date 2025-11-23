-- Criação da tabela log para registro de atividades do sistema
-- Esta tabela é usada por processa_cadastro.php e processa_login.php

CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cpf` varchar(50) NOT NULL,
  `data_log` date NOT NULL,
  `hora_log` time NOT NULL,
  `status` varchar(100) NOT NULL,
  `usuarios_idusuarios` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_log_usuarios` (`usuarios_idusuarios`),
  CONSTRAINT `fk_log_usuarios` FOREIGN KEY (`usuarios_idusuarios`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

