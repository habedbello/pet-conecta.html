<?php
session_start();
// Incluir arquivo de configuração de conexão. Assume-se que 'config.php' define $pdo.
require_once __DIR__ . '/conexaodb/config.php'; 

// --- Lógica de Nível de Acesso (Placeholder) ---
// EM UM SISTEMA REAL: A tabela 'usuarios' deveria ter uma coluna 'nivel_acesso'.
// Para esta simulação, assumimos um login fixo como 'master'.
define('MASTER_LOGIN', 'adminn'); 
$is_master = isset($_SESSION['login']) && $_SESSION['login'] === MASTER_LOGIN;
$is_logged_in = isset($_SESSION['logado']) && $_SESSION['logado'] === true;

// Redireciona se não estiver logado ou não for master
if (!$is_logged_in || !$is_master) {
    header('Location: erro.php?mensagem=' . urlencode('Acesso negado. Funcionalidade exclusiva para o Usuário Master.'));
    exit;
}

$searchQuery = $_GET['search'] ?? '';
$feedback_sucesso = $_SESSION['feedback_sucesso'] ?? null;
unset($_SESSION['feedback_sucesso']);

try {
    $sql = "SELECT id, nome, email, CPF, celular, login FROM usuarios WHERE login != :masterLogin"; // Exclui o próprio master
    $params = ['masterLogin' => MASTER_LOGIN];
    
    // Adiciona o filtro de pesquisa por substring no nome
    if (!empty($searchQuery)) {
        $sql .= " AND nome LIKE :searchName";
        $params['searchName'] = '%' . $searchQuery . '%';
    }
    
    $sql .= " ORDER BY nome ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $feedback_erro = "Erro ao carregar usuários: " . htmlspecialchars($e->getMessage());
    $usuarios = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Usuário - PET CONECTA (Master)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .container { max-width: 1200px; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">🔍 Consulta de Usuários Comuns</h1>
        
        <div class="mb-3">
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar para a Página Inicial
            </a>
        </div>
        <?php if ($feedback_sucesso): ?>
            <div class="alert alert-success" role="alert"><?= $feedback_sucesso ?></div>
        <?php endif; ?>
        <?php if (isset($feedback_erro)): ?>
            <div class="alert alert-danger" role="alert"><?= $feedback_erro ?></div>
        <?php endif; ?>
        
        <div class="card mb-4">
            <div class="card-header">Pesquisar Usuário</div>
            <div class="card-body">
                <form method="GET" action="consulta_usuario.php">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Digite parte do nome do usuário" value="<?= htmlspecialchars($searchQuery) ?>">
                        <button class="btn btn-primary" type="submit">Pesquisar</button>
                        <a href="consulta_usuario.php" class="btn btn-outline-secondary">Limpar</a>
                    </div>
                </form>
            </div>
        </div>

        <h2 class="mb-3">Lista de Usuários (<?= count($usuarios) ?> encontrados)</h2>
        <?php if (empty($usuarios)): ?>
            <div class="alert alert-info">Nenhum usuário comum encontrado com o critério de pesquisa.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>CPF</th>
                            <th>Login</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['id']) ?></td>
                            <td><?= htmlspecialchars($usuario['nome']) ?></td>
                            <td><?= htmlspecialchars($usuario['email']) ?></td>
                            <td><?= htmlspecialchars($usuario['CPF']) ?></td>
                            <td><?= htmlspecialchars($usuario['login']) ?></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-user-id="<?= $usuario['id'] ?>" data-user-name="<?= htmlspecialchars($usuario['nome']) ?>">
                                    <i class="fas fa-trash-alt"></i> Excluir
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Tem certeza de que deseja **excluir permanentemente** o usuário <strong id="userNameToDelete"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form method="POST" action="php/processa_exclusao.php" style="display:inline;">
                        <input type="hidden" name="user_id" id="userIdToDelete">
                        <button type="submit" class="btn btn-danger">Sim, Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Lógica para preencher o Modal com os dados do usuário a ser excluído
        document.addEventListener('DOMContentLoaded', function () {
            const deleteModal = document.getElementById('confirmDeleteModal');
            deleteModal.addEventListener('show.bs.modal', function (event) {
                // Botão que acionou o modal
                const button = event.relatedTarget;
                // Extrai as informações dos atributos data-*
                const userId = button.getAttribute('data-user-id');
                const userName = button.getAttribute('data-user-name');
                
                // Atualiza o conteúdo do modal
                const modalUserName = deleteModal.querySelector('#userNameToDelete');
                const modalUserIdInput = deleteModal.querySelector('#userIdToDelete');
                
                modalUserName.textContent = userName;
                modalUserIdInput.value = userId;
            });
        });
    </script>
</body>
</html>