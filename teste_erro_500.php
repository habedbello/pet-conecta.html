<?php
// Script de teste para diagnosticar erro 500
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

echo "Teste 1: PHP está funcionando<br>";
echo "Versão do PHP: " . phpversion() . "<br>";

echo "Teste 2: Verificando extensões<br>";
echo "cURL: " . (function_exists('curl_init') ? 'OK' : 'NÃO DISPONÍVEL') . "<br>";
echo "PDO: " . (class_exists('PDO') ? 'OK' : 'NÃO DISPONÍVEL') . "<br>";
echo "session: " . (function_exists('session_start') ? 'OK' : 'NÃO DISPONÍVEL') . "<br>";

echo "Teste 3: Verificando permissões de escrita<br>";
$logFile = __DIR__ . '/logs_cadastro.txt';
$logDir = dirname($logFile);
echo "Diretório de logs existe: " . (is_dir($logDir) ? 'SIM' : 'NÃO') . "<br>";
echo "Diretório de logs é gravável: " . (is_writable($logDir) ? 'SIM' : 'NÃO') . "<br>";

if (!is_dir($logDir)) {
    @mkdir($logDir, 0755, true);
    echo "Diretório criado<br>";
}

echo "Teste 4: Testando escrita de arquivo<br>";
$testWrite = @file_put_contents($logFile, "Teste de escrita\n", FILE_APPEND);
echo "Escrita no arquivo: " . ($testWrite !== false ? 'OK' : 'FALHOU') . "<br>";

echo "Teste 5: Testando session_start<br>";
try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    echo "Session iniciada: OK<br>";
} catch (Exception $e) {
    echo "Erro ao iniciar session: " . $e->getMessage() . "<br>";
}

echo "Teste 6: Testando função logDebug<br>";
function logDebug($mensagem, $dados = null) {
    $logFile = __DIR__ . '/logs_cadastro.txt';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $mensagem";
    if ($dados !== null) {
        $logMessage .= " | Dados: " . print_r($dados, true);
    }
    $logMessage .= "\n";
    return @file_put_contents($logFile, $logMessage, FILE_APPEND);
}

$testLog = logDebug("Teste de log");
echo "Função logDebug: " . ($testLog !== false ? 'OK' : 'FALHOU') . "<br>";

echo "Teste 7: Testando validacao_cadastro.php<br>";
$validacaoPath = __DIR__ . '/validacao_cadastro.php';
if (file_exists($validacaoPath)) {
    echo "Arquivo existe: OK<br>";
    echo "Arquivo é legível: " . (is_readable($validacaoPath) ? 'SIM' : 'NÃO') . "<br>";
} else {
    echo "Arquivo NÃO existe!<br>";
}

echo "<br><strong>Teste concluído!</strong><br>";
echo "Se todos os testes passaram, o problema pode estar nos dados do formulário.<br>";
?>







