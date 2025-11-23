<?php
session_start();
require_once __DIR__ . '/conexaodb/config.php'; 

// --- Lógica de Nível de Acesso (Placeholder) ---
define('MASTER_LOGIN', 'adminn'); 
$is_master = isset($_SESSION['login']) && $_SESSION['login'] === MASTER_LOGIN;
$is_logged_in = isset($_SESSION['logado']) && $_SESSION['logado'] === true;

// Redireciona se não estiver logado ou não for master
if (!$is_logged_in || !$is_master) {
    header('Location: erro.php?mensagem=' . urlencode('Acesso negado. Funcionalidade exclusiva para o Usuário Master/Auditoria.'));
    exit;
}

try {
    // Seleciona todos os logs, ordenados do mais recente para o mais antigo
    $sql = "SELECT id, login, nome, cpf, data_log, hora_log, status, usuarios_idusuarios 
            FROM log 
            ORDER BY data_log DESC, hora_log DESC";
            
    $stmt = $pdo->query($sql);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $feedback_erro = "Erro ao carregar logs: " . htmlspecialchars($e->getMessage());
    $logs = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Logs - PET CONECTA (Master)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid mt-5">
        <h1 class="mb-4">📜 Histórico de Logs do Sistema</h1>
        
        <?php if (isset($feedback_erro)): ?>
            <div class="alert alert-danger" role="alert"><?= $feedback_erro ?></div>
        <?php endif; ?>

        <a href="index.php" class="btn btn-secondary mb-3">Voltar para Home</a>

        <h2 class="mb-3">Registros Encontrados (<?= count($logs) ?>)</h2>
        
        <?php if (empty($logs)): ?>
            <div class="alert alert-info">Nenhum registro de log encontrado.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>ID Log</th>
                            <th>ID Usuário</th>
                            <th>Login</th>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Status/Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= htmlspecialchars($log['id']) ?></td>
                            <td><?= htmlspecialchars($log['usuarios_idusuarios']) ?></td>
                            <td><?= htmlspecialchars($log['login']) ?></td>
                            <td><?= htmlspecialchars($log['nome']) ?></td>
                            <td><?= htmlspecialchars($log['cpf']) ?></td>
                            <td><?= date('d/m/Y', strtotime($log['data_log'])) ?></td>
                            <td><?= htmlspecialchars($log['hora_log']) ?></td>
                            <td><?= htmlspecialchars($log['status']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>