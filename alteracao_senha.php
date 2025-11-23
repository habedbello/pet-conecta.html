<?php
session_start();
require_once __DIR__ . '/conexaodb/config.php'; 

// Redireciona se não estiver logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: login.php');
    exit;
}

$feedback_erro = null;
$feedback_sucesso = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $user_id = $_SESSION['id_usuario'] ?? null; 
    
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $feedback_erro = "Todos os campos de senha são obrigatórios.";
    } elseif ($new_password !== $confirm_password) {
        $feedback_erro = "A Nova Senha e a Confirmação de Senha não coincidem.";
    } elseif (strlen($new_password) !== 8 || !preg_match('/^[a-zA-Z]{8}$/', $new_password)) {
        // Validação de senha conforme o login.php (8 caracteres alfabéticos)
        $feedback_erro = "A Nova Senha deve ter exatamente 8 caracteres alfabéticos.";
    } else {
        try {
            // 1. Busca a senha atual hasheada no banco de dados
            $stmt = $pdo->prepare("SELECT senha FROM usuarios WHERE id = :id");
            $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $feedback_erro = "Erro: Usuário não encontrado no sistema.";
            } elseif (!password_verify($current_password, $user['senha'])) {
                $feedback_erro = "Senha atual incorreta.";
            } else {
                // 2. Se a senha atual estiver correta, hasheia a nova senha
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                // 3. Atualiza a senha no banco de dados
                $update_stmt = $pdo->prepare("UPDATE usuarios SET senha = :new_senha WHERE id = :id");
                $update_stmt->bindValue(':new_senha', $new_password_hash);
                $update_stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
                $update_stmt->execute();

                $feedback_sucesso = "🎉 Sua senha foi alterada com sucesso!";
            }
        } catch (PDOException $e) {
            $feedback_erro = "Erro ao alterar a senha: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alteração de Senha - PET CONECTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
<body>
    <div class="container mt-5" style="max-width: 500px;">
        <h1 class="mb-4">🔑 Alterar Minha Senha</h1>
        <p class="mb-4">Olá, **<?= htmlspecialchars($_SESSION['nome'] ?? 'Usuário') ?>**. Você pode alterar sua senha abaixo.</p>

        <?php if ($feedback_sucesso): ?>
            <div class="alert alert-success" role="alert"><?= $feedback_sucesso ?></div>
        <?php endif; ?>
        <?php if ($feedback_erro): ?>
            <div class="alert alert-danger" role="alert"><?= $feedback_erro ?></div>
        <?php endif; ?>

        <div class="card p-4">
            <form method="POST" action="alteracao_senha.php">
                <div class="mb-3">
                    <label for="current_password" class="form-label">Senha Atual</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>
                
                <div class="mb-3">
                    <label for="new_password" class="form-label">Nova Senha (8 caracteres alfabéticos)</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required pattern="[a-zA-Z]{8}" title="A senha deve ter exatamente 8 caracteres alfabéticos.">
                </div>
                
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirmar Nova Senha</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">Salvar Nova Senha</button>
            </form>
            <a href="index.php" class="btn btn-secondary mt-3">Voltar</a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>