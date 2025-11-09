<?php

// Constantes de Conexão
define('DB_HOST', 'localhost');
define('DB_NAME', 'petconecta'); // SEU BANCO DE DADOS
define('DB_USER', 'root');       // Geralmente 'root' (sem senha)
define('DB_PASS', '');           // Geralmente vazio no XAMPP/WAMP

$pdo = null; // Inicializa a variável de conexão PDO

try {
    // String DSN corrigida: removemos o $host indefinido
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";

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
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" 
        ]
    );
    
} catch (PDOException $e) {
    // Em caso de erro de conexão, registra o erro e exibe uma mensagem genérica.
    error_log("Falha na conexão com o banco de dados: " . $e->getMessage()); 
    
    // Redireciona para a página de erro
    // Ajuste o caminho de 'erro.php' se ele não estiver na raiz do projeto
    header("Location: ../erro.php?mensagem=" . urlencode("Serviço indisponível. Falha na conexão com o banco de dados."));
    exit();
}
// Se a conexão for bem-sucedida, o objeto $pdo estará disponível para os outros arquivos.
?>