<?php
/**
 * Script para visualizar os logs de cadastro
 * Acesse: http://localhost/pet-conecta.html/ver_logs.php
 */
$logFile = __DIR__ . '/logs_cadastro.txt';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Logs - PET CONECTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        pre {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.6;
        }
        .log-entry {
            margin-bottom: 5px;
        }
        .log-cadastro { color: #4ec9b0; }
        .log-config { color: #569cd6; }
        .log-success { color: #4ec9b0; }
        .log-error { color: #f48771; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h1>📋 Logs de Cadastro</h1>
                <div>
                    <a href="ver_logs.php?limpar=1" class="btn btn-warning btn-sm">🗑️ Limpar Logs</a>
                    <a href="cadastro.php" class="btn btn-secondary btn-sm">← Voltar</a>
                </div>
            </div>
            <div class="card-body">
                <?php
                if (isset($_GET['limpar']) && $_GET['limpar'] == 1) {
                    if (file_exists($logFile)) {
                        file_put_contents($logFile, '');
                        echo '<div class="alert alert-success">Logs limpos com sucesso!</div>';
                    }
                }
                
                if (file_exists($logFile)) {
                    $logs = file_get_contents($logFile);
                    if (empty(trim($logs))) {
                        echo '<div class="alert alert-info">Nenhum log encontrado. Faça um cadastro para gerar logs.</div>';
                    } else {
                        echo '<h5>Últimos logs (mais recentes no final):</h5>';
                        echo '<pre>';
                        echo htmlspecialchars($logs);
                        echo '</pre>';
                        echo '<p class="text-muted mt-3">Total de linhas: ' . substr_count($logs, "\n") . '</p>';
                    }
                } else {
                    echo '<div class="alert alert-warning">Arquivo de log não encontrado. O arquivo será criado quando houver logs.</div>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
