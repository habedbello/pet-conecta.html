<?php
session_start(); // 1. INICIA A SESSÃO PHP
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Login - PET CONECTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css/login.css">
    <link rel="stylesheet" href="style.css/darkmode.css">
</head>

<body>
    <header>
        <nav class="menu_superior">
            <a href="index.php"><img src="img/casa-de-animais.png"></a>
            <a href="index.php" id="navh1"><em>PET CONECTA</em></a>


            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="bemestar.php">Bem-Estar Animal</a></li>
                <li><a href="adoção.php">Adoção/Doação</a></li>
                <li><a href="saiba-mais.php">Sobre nós</a></li>
            </ul>
            <div class="login-cadastro" id="cadastro-login">
                <a href="login.php"><button id="btn-login-cadastro">Login</button></a>
                <a href="cadastro.php"><button id="btn-login-cadastro">Cadastro</button></a>
            </div>

            <button id="toggle-dark-mode"> ◐ </button>

            <div id="user-info" class="d-none">
                <span id="logged-in-user"></span>
                <button class="btn btn-sm btn-outline-light ms-2" id="logout-btn">Sair</button>
            </div>
        </nav>
    </header>

    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>


    <main class="container my-5">
        <h2 class="text-center mb-4">Login de Usuário</h2>
        <div class="card p-4 mx-auto" style="max-width: 400px;">
            <form method="POST" action="php/processa_login.php" id="loginForm" novalidate>
                <div class="mb-3">
                    <label for="loginUsername" class="form-label">Login:</label>
                    <input type="text" class="form-control" id="loginUsername" **name="loginUsername"** required placeholder="Seu login" minlength="6" maxlength="6">
                    <div class="invalid-feedback">Por favor, insira seu login.</div>
                </div>
                <div class="mb-3">
                    <label for="loginPassword" class="form-label">Senha:</label>
                    <input type="password" class="form-control" id="loginPassword" **name="loginPassword"** required placeholder="Sua senha" minlength="8" maxlength="8">
                    <div class="invalid-feedback">Por favor, insira sua senha.</div>
                </div>
                <div class="d-grid gap-2 mb-3">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                    <button type="button" class="btn btn-secondary" id="limparLogin">Limpar</button>
                </div>
                <div class="text-center">
                    <a href="cadastro.php">Novo por aqui? Cadastre-se!</a>
                </div>
            </form>
            <div class="toast-container position-fixed bottom-0 end-0 p-3">
                <div id="feedbackToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">Notificação</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body"></div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-logo">
                <p>&copy; 2025 PET CONECTA - Conectando Pets e Amantes de Animais</p>
                <div class="footer-contact">
                    <p>Entre em contato:</p>
                    <p>Email: contato@petconecta.com.br</p>
                    <p>Telefone: (XX) XXXXX-XXXX</p>
                </div>
            </div>
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="javaScript/main.js"></script>
    <script src="javaScript/acessibilidade.js"></script>
    <script>
        // Função para exibir mensagens de feedback utilizando o componente Toast do Bootstrap
        function showFeedback(message, type) {
            const toastElement = document.getElementById('feedbackToast');
            const toastBody = toastElement.querySelector('.toast-body');
            
            // Define o tipo de cor do Toast
            const typeClass = type === 'success' ? 'text-bg-success' : 'text-bg-danger';

            toastBody.textContent = message;
            toastElement.classList.remove('text-bg-success', 'text-bg-danger', 'text-bg-warning', 'text-bg-info');
            toastElement.classList.add(typeClass); // Aplica a classe de cor
            
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
        }

        // NOVO BLOCO: Exibe feedback da sessão PHP (Sucesso do Cadastro ou Erro de Login)
        (function () {
            // Assegura que o JavaScript não quebre se a variável de sessão não existir
            const feedbackMessage = "<?php echo isset($_SESSION['feedback_mensagem']) ? addslashes($_SESSION['feedback_mensagem']) : ''; ?>";
            const feedbackType = "<?php echo isset($_SESSION['feedback_tipo']) ? addslashes($_SESSION['feedback_tipo']) : ''; ?>";
            
            if (feedbackMessage && feedbackType) {
                showFeedback(feedbackMessage, feedbackType);
                
                // Limpa o feedback da sessão após a exibição
                <?php 
                unset($_SESSION['feedback_mensagem']); 
                unset($_SESSION['feedback_tipo']);
                ?>
            }
        })();

        // Listener para o evento de submissão do formulário de login
        document.getElementById('loginForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Impede o envio padrão para aplicar a validação JS
            const form = event.target;
            const loginInput = document.getElementById('loginUsername');
            const passwordInput = document.getElementById('loginPassword');

            // Limpa classes de validação anteriores
            loginInput.classList.remove('is-invalid', 'is-valid');
            passwordInput.classList.remove('is-invalid', 'is-valid');

            let isValid = true; // Flag para controlar a validade geral do formulário
            const loginValue = loginInput.value.trim();
            const passwordValue = passwordInput.value.trim();

            // Validação de formato Login (Exatamente 6 caracteres alfabéticos)
            if (loginValue.length === 6 && /^[a-zA-Z]{6}$/.test(loginValue)) {
                loginInput.classList.add('is-valid');
            } else {
                loginInput.classList.add('is-invalid');
                isValid = false;
                showFeedback('Login deve ter exatamente 6 caracteres alfabéticos.', 'danger');
            }

            // Validação de formato Senha (Exatamente 8 caracteres alfabéticos)
            if (passwordValue.length === 8 && /^[a-zA-Z]{8}$/.test(passwordValue)) {
                passwordInput.classList.add('is-valid');
            } else {
                passwordInput.classList.add('is-invalid');
                isValid = false;
                showFeedback('Senha deve ter exatamente 8 caracteres alfabéticos.', 'danger');
            }

            // Se a validação de formato for bem-sucedida, ENVIE para o PHP
            if (isValid) {
                // Remove a simulação de autenticação do localStorage e deixa o formulário ser submetido
                form.submit(); // Envia para php/processa_login.php
            } 
            // Se NÃO for válido, o showFeedback já foi chamado dentro dos blocos 'else'
        });

        // Listener para o botão 'Limpar'
        document.getElementById('limparLogin').addEventListener('click', function () {
            document.getElementById('loginForm').reset(); // Reseta os campos do formulário
            // Remove as classes de validação dos campos
            document.getElementById('loginUsername').classList.remove('is-invalid', 'is-valid');
            document.getElementById('loginPassword').classList.remove('is-invalid', 'is-valid');
            showFeedback('Campos de login limpos.', 'info');
        });
    </script>
    <script src="javaScript/darkmodee.js"></script>

    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>


</body>

</html>