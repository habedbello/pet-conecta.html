<?php
// Garante que a sessão está ativa para acessar as variáveis
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define o estado de login e o nível de acesso Master
$is_logged_in = isset($_SESSION['logado']) && $_SESSION['logado'] === true;

// Usa o 'login' como nome de exibição.
$user_login = $is_logged_in ? ($_SESSION['login'] ?? 'Usuário') : ''; 


define('MASTER_LOGIN', 'adminn');
// Verifica se está logado E se o login corresponde ao login Master
$is_master = $is_logged_in && ($user_login === MASTER_LOGIN);
?>

<nav class="menu_superior">
    <a class="logo animate__animated animate__zoomIn" href="index.php"><img src="img/casa-de-animais.png"></a>
    <a href="index.php" class="animate__animated animate__zoomIn" id="navh1"><em>PET CONECTA</em></a>
    <ul>
        <li class="animate__animated animate__zoomIn"><a href="index.php">Home</a></li>
        <li class="animate__animated animate__zoomIn"><a href="bemestar.php">Bem-Estar Animal</a></li>
        <li class="animate__animated animate__zoomIn"><a href="adoção.php">Adoção/Doação</a></li>
        <li class="animate__animated animate__zoomIn"><a href="saiba-mais.php">Sobre nós</a></li>
    </ul>

    <?php if (!$is_logged_in): ?>
        <div class="login-cadastro" id="cadastro-login">
            <a href="login.php"><button id="btn-login-cadastro"
                    class="btn animate__animated animate__zoomIn">Login</button></a>
            <a href="cadastro.php"><button id="btn-login-cadastro"
                    class="animate__animated animate__zoomIn">Cadastro</button></a>
        </div>
    <?php else: ?>
        <div class="login-cadastro usuario-logado dropdown" style="position: relative;">
            
            <a href="#" id="user-menu-trigger" class="animate__animated animate__zoomIn" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none; color: inherit; padding: 10px 15px; border-radius: 5px;">
                Olá, **<?= htmlspecialchars(ucfirst($user_login)) ?>** ▼
            </a>
            
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="user-menu-trigger" style="position: absolute; right: 0; z-index: 1000; min-width: 200px;">
                
                <?php if (!$is_master): ?> 
                    <li><a class="dropdown-item" href="alteracao_senha.php">⚙️ Alterar Senha</a></li>
                <?php endif; ?>
                
                <?php if ($is_master): ?>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="consulta_usuario.php">❌ Excluir Cadastro</a></li>
                    <li><a class="dropdown-item" href="tela_log.php">📊 Ver Logs do Site</a></li>
                <?php endif; ?>
                
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="logout.php">🚪 Sair (Logout)</a></li>
            </ul>
        </div>
    <?php endif; ?>
    <button id="toggle-dark-mode" class="btn animate__animated animate__zoomIn"> ◐ </button>

    </nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>