<?php
session_start();
require_once __DIR__ . '/../conexaodb/config.php';

// --- Lógica de Nível de Acesso (Placeholder) --- AQUIVO PRECISA ESTAR DENTRO DO PHP << PASTA
define('MASTER_LOGIN', 'adminn'); 
$is_master = isset($_SESSION['login']) && $_SESSION['login'] === MASTER_LOGIN;
$is_logged_in = isset($_SESSION['logado']) && $_SESSION['logado'] === true;

// Redireciona se não for um POST, não estiver logado ou não for master
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$is_logged_in || !$is_master) {
    header('Location: ../erro.php?mensagem=' . urlencode('Tentativa de acesso não autorizada ou método inválido.'));
    exit;
}

$userId = $_POST['user_id'] ?? null;

if (!filter_var($userId, FILTER_VALIDATE_INT)) {
    $_SESSION['feedback_erro'] = "ID de usuário inválido.";
    header('Location: ../consulta_usuario.php');
    exit;
}

try {
    // 1. Inicia a transação (garante que a deleção seja completa)
    $pdo->beginTransaction();
    
    // 2. A FK na tabela 'log' deve estar configurada com ON DELETE CASCADE (conforme 'criar_tabelas.php')
    // A exclusão de um usuário deve apagar automaticamente seus logs.

    // 3. Exclui o usuário
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
    $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    
    $rowCount = $stmt->rowCount();
    
    // 4. Confirma a transação
    $pdo->commit();
    
    if ($rowCount > 0) {
        $_SESSION['feedback_sucesso'] = "Usuário ID {$userId} excluído com sucesso!";
    } else {
        $_SESSION['feedback_sucesso'] = "Nenhum usuário encontrado com o ID {$userId} para exclusão.";
    }

} catch (PDOException $e) {
    // 5. Reverte a transação em caso de erro
    $pdo->rollBack();
    $_SESSION['feedback_erro'] = "Erro ao excluir usuário: " . htmlspecialchars($e->getMessage());
}

header('Location: ../consulta_usuario.php');
exit;
?>