<?php

// Função para log (apenas declara se não existir)
if (!function_exists('logDebug')) {
    function logDebug($mensagem, $dados = null) {
        $logFile = __DIR__ . '/../logs_cadastro.txt';
        // Criar diretório de logs se não existir
        $logDir = dirname($logFile);
        if (!file_exists($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] [CONFIG] $mensagem";
        if ($dados !== null) {
            $logMessage .= " | Dados: " . print_r($dados, true);
        }
        $logMessage .= "\n";
        @file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}

// Constantes de Conexão
// Suporta variáveis de ambiente do Docker, com fallback para XAMPP
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'petconecta'); // SEU BANCO DE DADOS
define('DB_USER', getenv('DB_USER') ?: 'root');       // Geralmente 'root' (sem senha)
define('DB_PASS', getenv('DB_PASS') ?: '');           // Geralmente vazio no XAMPP/WAMP

$pdo = null; // Inicializa a variável de conexão PDO

try {
    logDebug("Iniciando conexão com banco de dados", ['host' => DB_HOST, 'database' => DB_NAME, 'user' => DB_USER]);
    
    // Primeiro, tenta conectar sem especificar o banco para criar se necessário
    $dsn_sem_banco = "mysql:host=" . DB_HOST . ";charset=utf8";
    logDebug("Tentando conectar sem banco específico");
    
    $pdo_temp = new PDO(
        $dsn_sem_banco,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    logDebug("Conexão temporária estabelecida");
    
    // Tenta criar o banco de dados se não existir
    $pdo_temp->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    logDebug("Banco de dados verificado/criado");
    $pdo_temp = null; // Fecha a conexão temporária
    
    // Agora conecta ao banco de dados específico
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    logDebug("Conectando ao banco específico", ['dsn' => $dsn]);

    // Cria a conexão PDO
    $pdo = new PDO(
        $dsn,
        DB_USER,
        DB_PASS,
        [
            // Configurações importantes para tratamento de erros e codificação
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Configuração explícita de UTF8 para evitar problemas de acentuação
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4" 
        ]
    );
    
    logDebug("Conexão PDO estabelecida com sucesso");
    
    // Criar tabela usuarios se não existir
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS `usuarios` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
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
            `senha` varchar(255) NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `email` (`email`),
            UNIQUE KEY `CPF` (`CPF`),
            UNIQUE KEY `login` (`login`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
        
        // Tentar alterar campo numero para VARCHAR se for INT
        try {
            $pdo->exec("ALTER TABLE usuarios MODIFY numero VARCHAR(10) NOT NULL");
        } catch (PDOException $e) {
            // Ignorar erro se já for VARCHAR ou se houver outros problemas
        }
        
        // Verificar e criar índices únicos se não existirem
        try {
            $indexes = $pdo->query("SHOW INDEX FROM usuarios")->fetchAll();
            $existingIndexes = array_column($indexes, 'Key_name');
            
            if (!in_array('email', $existingIndexes)) {
                $pdo->exec("ALTER TABLE usuarios ADD UNIQUE KEY `email` (`email`)");
            }
            if (!in_array('CPF', $existingIndexes)) {
                $pdo->exec("ALTER TABLE usuarios ADD UNIQUE KEY `CPF` (`CPF`)");
            }
            if (!in_array('login', $existingIndexes)) {
                $pdo->exec("ALTER TABLE usuarios ADD UNIQUE KEY `login` (`login`)");
            }
        } catch (PDOException $e) {
            // Ignorar erros de índices
            error_log("Aviso ao criar índices únicos: " . $e->getMessage());
        }
    } catch (PDOException $e) {
        error_log("Aviso ao criar tabela usuarios: " . $e->getMessage());
    }
    
    // Criar tabela log se não existir
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS `log` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `login` varchar(50) NOT NULL,
            `nome` varchar(80) NOT NULL,
            `cpf` varchar(11) NOT NULL,
            `data_log` date NOT NULL,
            `hora_log` time NOT NULL,
            `status` varchar(100) NOT NULL,
            `usuarios_idusuarios` int(11) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `fk_log_usuarios` (`usuarios_idusuarios`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
        
        // Tentar adicionar foreign key se não existir
        try {
            $fkCheck = $pdo->query("SELECT COUNT(*) as count 
                                   FROM information_schema.TABLE_CONSTRAINTS 
                                   WHERE CONSTRAINT_SCHEMA = '" . DB_NAME . "' 
                                   AND TABLE_NAME = 'log' 
                                   AND CONSTRAINT_NAME = 'fk_log_usuarios'")->fetch();
            
            if ($fkCheck['count'] == 0) {
                $pdo->exec("ALTER TABLE `log` 
                           ADD CONSTRAINT `fk_log_usuarios` 
                           FOREIGN KEY (`usuarios_idusuarios`) 
                           REFERENCES `usuarios` (`id`) 
                           ON DELETE CASCADE ON UPDATE CASCADE");
            }
        } catch (PDOException $e) {
            // Ignorar erro se foreign key já existir ou houver outros problemas
            error_log("Aviso: Não foi possível criar/verificar foreign key: " . $e->getMessage());
        }
    } catch (PDOException $e) {
        error_log("Aviso ao criar tabela log: " . $e->getMessage());
    }
    
} catch (PDOException $e) {
    // Em caso de erro de conexão, registra o erro e exibe uma mensagem genérica.
    logDebug("ERRO na conexão com banco de dados", [
        'mensagem' => $e->getMessage(),
        'codigo' => $e->getCode(),
        'arquivo' => $e->getFile(),
        'linha' => $e->getLine()
    ]);
    
    error_log("Falha na conexão com o banco de dados: " . $e->getMessage()); 
    
    // Em ambiente de desenvolvimento, mostra mais detalhes
    $mensagem = "Serviço indisponível. Falha na conexão com o banco de dados.";
    $isLocalhost = isset($_SERVER['HTTP_HOST']) && 
                   ($_SERVER['HTTP_HOST'] == 'localhost' || 
                    $_SERVER['HTTP_HOST'] == '127.0.0.1' || 
                    strpos($_SERVER['HTTP_HOST'], 'localhost') !== false);
    
    if ($isLocalhost) {
        $mensagem .= " Detalhes: " . $e->getMessage();
        $mensagem .= " Verifique se o MySQL/MariaDB está rodando no XAMPP.";
    }
    
    // Se os headers ainda não foram enviados, redireciona
    if (!headers_sent()) {
        // Como config.php está em conexaodb/, sobe um nível para acessar erro.php na raiz
        header("Location: ../erro.php?mensagem=" . urlencode($mensagem));
        exit();
    } else {
        // Se os headers já foram enviados, exibe o erro diretamente
        echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Erro</title></head><body>";
        echo "<h1>Erro de Conexão com o Banco de Dados</h1>";
        echo "<p>" . htmlspecialchars($mensagem) . "</p>";
        echo "<p><a href='../erro.php?mensagem=" . urlencode($mensagem) . "'>Ir para página de erro</a></p>";
        echo "</body></html>";
        exit();
    }
}
// Se a conexão for bem-sucedida, o objeto $pdo estará disponível para os outros arquivos.
?>