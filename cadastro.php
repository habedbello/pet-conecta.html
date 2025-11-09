<?php
session_start();

// Recupera erros e dados da sessão para repopular o formulário e exibir mensagens
$erros = $_SESSION['erros'] ?? [];
$dados = $_SESSION['dados'] ?? [];
$feedback_erro = $_SESSION['feedback_erro'] ?? null;
$feedback_sucesso = $_SESSION['feedback_sucesso'] ?? null;

// Limpa as variáveis de sessão após recuperá-las
unset($_SESSION['erros']);
unset($_SESSION['dados']);
unset($_SESSION['feedback_erro']);
unset($_SESSION['feedback_sucesso']);

// Função auxiliar para repopular campos
function valorCampo($nomeCampo, $dados) {
    return htmlspecialchars($dados[$nomeCampo] ?? '');
}

// Função auxiliar para verificar a classe de erro
function classeInvalida($nomeCampo, $erros) {
    // Se há um erro do PHP, retorna 'is-invalid'. Senão, se o dado veio (e não é erro), retorna 'is-valid' para campos preenchidos.
    return isset($erros[$nomeCampo]) ? 'is-invalid' : (isset($dados[$nomeCampo]) && $dados[$nomeCampo] !== '' ? 'is-valid' : '');
}

// Função auxiliar para exibir a mensagem de erro
function exibirErro($nomeCampo, $erros, $mensagemPadrao) {
    return isset($erros[$nomeCampo]) ? $erros[$nomeCampo] : $mensagemPadrao;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário - PET CONECTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css/cadastro.css">
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

    <main>
        <h2 class="text-center mb-4">Cadastro de Usuário</h2>
        <div class="card p-4 mx-auto" style="max-width: 700px;">

            <?php if ($feedback_sucesso): ?>
                <div class="alert alert-success text-center" role="alert"><?= $feedback_sucesso ?></div>
            <?php elseif ($feedback_erro): ?>
                <div class="alert alert-danger text-center" role="alert"><?= $feedback_erro ?></div>
            <?php endif; ?>

            <form method="POST" action="validacao_cadastro.php" id="cadastroForm" novalidate>
                
                <div class="mb-3">
                    <label for="nomeCompleto" class="form-label">Nome Completo:</label>
                    <input type="text" class="form-control <?= classeInvalida('campo_nome', $erros) ?>" id="nomeCompleto" name="campo_nome"
                        value="<?= valorCampo('campo_nome', $dados) ?>" required minlength="15" maxlength="80"
                        placeholder="Seu nome completo">
                    <div class="invalid-feedback"><?= exibirErro('campo_nome', $erros, 'O nome completo deve ter entre 15 e 80 caracteres alfabéticos.') ?></div>
                </div>
                
                <div class="mb-3">
                    <label for="dataNascimento" class="form-label">Data de Nascimento:</label>
                    <input type="date" class="form-control <?= classeInvalida('campo_data', $erros) ?>" id="dataNascimento" name="campo_data"
                        value="<?= valorCampo('campo_data', $dados) ?>" required>
                    <div class="invalid-feedback"><?= exibirErro('campo_data', $erros, 'Você deve ter no mínimo 18 anos para se cadastrar.') ?></div>
                </div>
                
                <div class="mb-3">
                    <label for="sexo" class="form-label">Sexo:</label>
                    <select class="form-select <?= classeInvalida('campo_sexo', $erros) ?>" id="sexo" name="campo_sexo" required aria-label="Selecione seu sexo">
                        <option value="" <?= !isset($dados['campo_sexo']) || $dados['campo_sexo'] == '' ? 'selected' : '' ?>>Selecione</option>
                        <option value="M" <?= valorCampo('campo_sexo', $dados) == 'M' ? 'selected' : '' ?>>Masculino</option>
                        <option value="F" <?= valorCampo('campo_sexo', $dados) == 'F' ? 'selected' : '' ?>>Feminino</option>
                        <option value="O" <?= valorCampo('campo_sexo', $dados) == 'O' ? 'selected' : '' ?>>Outro</option>
                    </select>
                    <div class="invalid-feedback"><?= exibirErro('campo_sexo', $erros, 'Por favor, selecione seu sexo.') ?></div>
                </div>
                
                <div class="mb-3">
                    <label for="nomeMaterno" class="form-label">Nome Materno:</label>
                    <input type="text" class="form-control <?= classeInvalida('campo_materno', $erros) ?>" id="nomeMaterno" name="campo_materno"
                        value="<?= valorCampo('campo_materno', $dados) ?>" required
                        placeholder="Nome completo da sua mãe">
                    <div class="invalid-feedback"><?= exibirErro('campo_materno', $erros, 'Por favor, insira o nome materno.') ?></div>
                </div>
                
                <div class="mb-3">
                    <label for="cpf" class="form-label">CPF:</label>
                    <input type="text" class="form-control <?= classeInvalida('campo_cpf', $erros) ?>" id="cpf" name="campo_cpf"
                        value="<?= valorCampo('campo_cpf', $dados) ?>" placeholder="000.000.000-00" required>
                    <div class="invalid-feedback"><?= exibirErro('campo_cpf', $erros, 'CPF inválido.') ?></div>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail:</label>
                    <input type="email" class="form-control <?= classeInvalida('campo_email', $erros) ?>" id="email" name="campo_email"
                        value="<?= valorCampo('campo_email', $dados) ?>" required placeholder="seu.email@exemplo.com">
                    <div class="invalid-feedback"><?= exibirErro('campo_email', $erros, 'Por favor, insira um e-mail válido.') ?></div>
                </div>
                
                <div class="mb-3">
                    <label for="telefoneCelular" class="form-label">Telefone Celular:</label>
                    <input type="text" class="form-control <?= classeInvalida('campo_celular', $erros) ?>" id="telefoneCelular" name="campo_celular"
                        value="<?= valorCampo('campo_celular', $dados) ?>" placeholder="(+55)XX-XXXXXXXXX" required>
                    <div class="invalid-feedback"><?= exibirErro('campo_celular', $erros, 'Formato inválido. Ex: (+55)XX-XXXXXXXXX') ?></div>
                </div>
                
                <div class="mb-3">
                    <label for="telefoneFixo" class="form-label">Telefone Fixo:</label>
                    <input type="text" class="form-control <?= classeInvalida('campo_fixo', $erros) ?>" id="telefoneFixo" name="campo_fixo"
                        value="<?= valorCampo('campo_fixo', $dados) ?>" placeholder="(+55)XX-XXXXXXXX" required>
                    <div class="invalid-feedback"><?= exibirErro('campo_fixo', $erros, 'Formato inválido. Ex: (+55)XX-XXXXXXXX') ?></div>
                </div>
                
                <div class="mb-3">
                    <label for="cep" class="form-label">CEP:</label>
                    <input type="text" class="form-control <?= classeInvalida('campo_cep', $erros) ?>" id="cep" name="campo_cep"
                        value="<?= valorCampo('campo_cep', $dados) ?>" placeholder="00000-000" required>
                    <div class="invalid-feedback"><?= exibirErro('campo_cep', $erros, 'CEP inválido.') ?></div>
                </div>
                
                <div class="mb-3">
                    <label for="logradouro" class="form-label">Logradouro:</label>
                    <input type="text" class="form-control <?= classeInvalida('campo_logradouro', $erros) ?>" id="logradouro" name="campo_logradouro"
                        value="<?= valorCampo('campo_logradouro', $dados) ?>" required readonly>
                    <div class="invalid-feedback"><?= exibirErro('campo_logradouro', $erros, 'Por favor, insira o logradouro.') ?></div>
                </div>
                
                <div class="mb-3">
                    <label for="numero" class="form-label">Número:</label>
                    <input type="text" class="form-control <?= classeInvalida('campo_no', $erros) ?>" id="numero" name="campo_no"
                        value="<?= valorCampo('campo_no', $dados) ?>" required>
                    <div class="invalid-feedback"><?= exibirErro('campo_no', $erros, 'Por favor, insira o número.') ?></div>
                </div>
                
                <div class="mb-3">
                    <label for="complemento" class="form-label">Complemento:</label>
                    <input type="text" class="form-control" id="complemento" name="campo_complemento"
                        value="<?= valorCampo('campo_complemento', $dados) ?>">
                </div>
                
                <div class="mb-3">
                    <label for="bairro" class="form-label">Bairro:</label>
                    <input type="text" class="form-control <?= classeInvalida('campo_bairro', $erros) ?>" id="bairro" name="campo_bairro"
                        value="<?= valorCampo('campo_bairro', $dados) ?>" required readonly>
                    <div class="invalid-feedback"><?= exibirErro('campo_bairro', $erros, 'Por favor, insira o bairro.') ?></div>
                </div>
                
                <div class="mb-3">
                    <label for="cidade" class="form-label">Cidade:</label>
                    <input type="text" class="form-control <?= classeInvalida('campo_cidade', $erros) ?>" id="cidade" name="campo_cidade"
                        value="<?= valorCampo('campo_cidade', $dados) ?>" required readonly>
                    <div class="invalid-feedback"><?= exibirErro('campo_cidade', $erros, 'Por favor, insira a cidade.') ?></div>
                </div>
                
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado:</label>
                    <input type="text" class="form-control <?= classeInvalida('campo_uf', $erros) ?>" id="estado" name="campo_uf"
                        value="<?= valorCampo('campo_uf', $dados) ?>" required readonly>
                    <div class="invalid-feedback"><?= exibirErro('campo_uf', $erros, 'Por favor, insira o estado.') ?></div>
                </div>
                
                <div class="mb-3">
                    <label for="login" class="form-label">Login:</label>
                    <input type="text" class="form-control <?= classeInvalida('campo_login', $erros) ?>" id="login" name="campo_login"
                        value="<?= valorCampo('campo_login', $dados) ?>" required minlength="6" maxlength="6"
                        placeholder="Login com 6 caracteres">
                    <div class="invalid-feedback"><?= exibirErro('campo_login', $erros, 'O login deve ter exatamente 6 caracteres alfabéticos.') ?></div>
                </div>
                
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha:</label>
                    <input type="password" class="form-control <?= classeInvalida('campo_senha', $erros) ?>" id="senha" name="campo_senha"
                        required placeholder="Senha com 8 caracteres" maxlength="8" minlength="8">
                    <div class="invalid-feedback"><?= exibirErro('campo_senha', $erros, 'A senha deve ter exatamente 8 caracteres alfabéticos.') ?></div>
                </div>
                
                <div class="mb-3">
                    <label for="confirmaSenha" class="form-label">Confirmação da Senha:</label>
                    <input type="password" class="form-control <?= classeInvalida('campo_confirma', $erros) ?>" id="confirmaSenha" name="campo_confirma"
                        required maxlength="8" minlength="8" placeholder="Confirme sua senha">
                    <div class="invalid-feedback"><?= exibirErro('campo_confirma', $erros, 'As senhas não coincidem e devem ter 8 caracteres alfabéticos.') ?></div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                    <button type="button" class="btn btn-secondary" id="limparCadastro">Limpar Tela</button>
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
    <script src="javaScript/validacoes.js"></script>

    <script>
        // Função para exibir mensagens de feedback utilizando o componente Toast do Bootstrap
        function showFeedback(message, type) {
            const toastElement = document.getElementById('feedbackToast');
            const toastBody = toastElement.querySelector('.toast-body');
            toastBody.textContent = message;
            toastElement.classList.remove('text-bg-success', 'text-bg-danger', 'text-bg-warning', 'text-bg-info');
            toastElement.classList.add(`text-bg-${type}`);
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
        }

        
        // RESTAURAÇÃO COMPLETA DA LÓGICA DE VALIDAÇÃO JAVASCRIPT
        

        // evento de submissão do formulário de cadastro
        document.getElementById('cadastroForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Impede o envio padrão do formulário
            const form = event.target;
            let isValid = true; //controlar a validade geral do formulário

            // Limpa as classes de validação e mensagens de feedback
            form.querySelectorAll('.form-control, .form-select').forEach(input => {
                input.classList.remove('is-invalid', 'is-valid');
            });

            // Revalidação dos campos

            // Validação de Nome Completo
            const nomeCompleto = document.getElementById('nomeCompleto');
            if (nomeCompleto.value.length < 15 || nomeCompleto.value.length > 80 || !/^[a-zA-Z\sÀ-ú]+$/.test(nomeCompleto.value)) {
                nomeCompleto.classList.add('is-invalid');
                nomeCompleto.nextElementSibling.textContent = 'O nome completo deve ter entre 15 e 80 caracteres alfabéticos.';
                isValid = false;
            } else {
                nomeCompleto.classList.add('is-valid');
            }

            // Data de Nascimento e Idade Mínima (18 Anos)
            const dataNascimentoInput = document.getElementById('dataNascimento');

            if (!dataNascimentoInput.value) {
                dataNascimentoInput.classList.remove('is-valid');
                dataNascimentoInput.classList.add('is-invalid');
                dataNascimentoInput.nextElementSibling.textContent = 'Por favor, insira sua data de nascimento.';
                isValid = false;
            } else {
                const dataNascimento = new Date(dataNascimentoInput.value);
                const hoje = new Date();
                
                let idade = hoje.getFullYear() - dataNascimento.getFullYear();
                const mesAtual = hoje.getMonth();
                const mesNascimento = dataNascimento.getMonth();

                const aniversarioNaoPassou = mesAtual < mesNascimento || (mesAtual === mesNascimento && hoje.getDate() < dataNascimento.getDate());

                if (aniversarioNaoPassou) {
                    idade--;
                }

                if (idade < 18) {
                    dataNascimentoInput.classList.remove('is-valid');
                    dataNascimentoInput.classList.add('is-invalid');
                    dataNascimentoInput.nextElementSibling.textContent = 'Você deve ter no mínimo 18 anos para se cadastrar.';
                    isValid = false;
                } else {
                    dataNascimentoInput.classList.remove('is-invalid');
                    dataNascimentoInput.classList.add('is-valid');
                }
            }

            // Validação de Sexo
            const sexo = document.getElementById('sexo');
            if (!sexo.value) {
                sexo.classList.add('is-invalid');
                sexo.nextElementSibling.textContent = 'Por favor, selecione seu sexo.';
                isValid = false;
            } else {
                sexo.classList.add('is-valid');
            }

            // Validação de Nome Materno
            const nomeMaterno = document.getElementById('nomeMaterno');
            if (nomeMaterno.value.trim() === '') {
                nomeMaterno.classList.add('is-invalid');
                nomeMaterno.nextElementSibling.textContent = 'Por favor, insira o nome materno.';
                isValid = false;
            } else {
                nomeMaterno.classList.add('is-valid');
            }

            // Validação de CPF (depende de 'validacoes.js')
            const cpf = document.getElementById('cpf');
            if (typeof validarCPF === 'undefined' || !validarCPF(cpf.value)) { // Verifica se a função existe
                cpf.classList.add('is-invalid');
                cpf.nextElementSibling.textContent = 'CPF inválido.';
                isValid = false;
            } else {
                cpf.classList.add('is-valid');
            }

            // Validação de E-mail (formato básico)
            const email = document.getElementById('email');
            if (!email.value.includes('@') || !email.value.includes('.')) {
                email.classList.add('is-invalid');
                email.nextElementSibling.textContent = 'Por favor, insira um e-mail válido.';
                isValid = false;
            } else {
                email.classList.add('is-valid');
            }

            // Validação de Telefone Celular
            const telefoneCelular = document.getElementById('telefoneCelular');
            const regexCelular = /^\(\+55\)\d{2}-\d{8,9}$/;
            if (!regexCelular.test(telefoneCelular.value)) {
                telefoneCelular.classList.add('is-invalid');
                telefoneCelular.nextElementSibling.textContent = 'Formato inválido. Ex: (+55)XX-XXXXXXXXX';
                isValid = false;
            } else {
                telefoneCelular.classList.add('is-valid');
            }

            // Validação de Telefone Fixo
            const telefoneFixo = document.getElementById('telefoneFixo');
            const regexFixo = /^\(\+55\)\d{2}-\d{8}$/;
            if (!regexFixo.test(telefoneFixo.value)) {
                telefoneFixo.classList.add('is-invalid');
                telefoneFixo.nextElementSibling.textContent = 'Formato inválido. Ex: (+55)XX-XXXXXXXX';
                isValid = false;
            } else {
                telefoneFixo.classList.add('is-valid');
            }

            // Validação de Logradouro (Endereço)
            const logradouro = document.getElementById('logradouro');
            if (logradouro.value.trim() === '') {
                logradouro.classList.add('is-invalid');
                logradouro.nextElementSibling.textContent = 'Por favor, insira o logradouro.';
                isValid = false;
            } else {
                logradouro.classList.add('is-valid');
            }

            // Validação de Número
            const numero = document.getElementById('numero');
            if (numero.value.trim() === '') {
                numero.classList.add('is-invalid');
                numero.nextElementSibling.textContent = 'Por favor, insira o número.';
                isValid = false;
            } else {
                numero.classList.add('is-valid');
            }
            // O complemento é opcional, não precisa de validação de 'required'.

            // Validação de Bairro
            const bairro = document.getElementById('bairro');
            if (bairro.value.trim() === '') {
                bairro.classList.add('is-invalid');
                bairro.nextElementSibling.textContent = 'Por favor, insira o bairro.';
                isValid = false;
            } else {
                bairro.classList.add('is-valid');
            }

            // Validação de Cidade
            const cidade = document.getElementById('cidade');
            if (cidade.value.trim() === '') {
                cidade.classList.add('is-invalid');
                cidade.nextElementSibling.textContent = 'Por favor, insira a cidade.';
                isValid = false;
            } else {
                cidade.classList.add('is-valid');
            }

            // Validação de Estado
            const estado = document.getElementById('estado');
            if (estado.value.trim() === '') {
                estado.classList.add('is-invalid');
                estado.nextElementSibling.textContent = 'Por favor, insira o estado.';
                isValid = false;
            } else {
                estado.classList.add('is-valid');
            }

            // Validação de Login
            const login = document.getElementById('login');
            // Seu código original: if (login.value.length !== 6 || !/^[a-zA-Z]+$/.test(login.value))
            if (login.value.length !== 6 || !/^[a-zA-Z]+$/.test(login.value)) {
                login.classList.add('is-invalid');
                login.nextElementSibling.textContent = 'O login deve ter exatamente 6 caracteres alfabéticos.';
                isValid = false;
            } else {
                login.classList.add('is-valid');
            }

            // Validação de Senha
            const senha = document.getElementById('senha');
            const confirmaSenha = document.getElementById('confirmaSenha');
            const regexAlfabetico = /^[a-zA-Z]{8}$/; 

            if (!regexAlfabetico.test(senha.value)) {
                senha.classList.remove('is-valid'); 
                senha.classList.add('is-invalid');
                senha.nextElementSibling.textContent = 'A senha deve ter exatamente 8 caracteres alfabéticos.';
                isValid = false;
            } else {
                senha.classList.remove('is-invalid'); 
                senha.classList.add('is-valid');
                senha.nextElementSibling.textContent = ''; 
            }

            // Validação de Confirmação da Senha
            if (confirmaSenha.value !== senha.value) {
                confirmaSenha.classList.remove('is-valid');
                confirmaSenha.classList.add('is-invalid');
                confirmaSenha.nextElementSibling.textContent = 'As senhas não coincidem.';
                isValid = false;
            } else if (!regexAlfabetico.test(confirmaSenha.value)) { 
                confirmaSenha.classList.remove('is-valid');
                confirmaSenha.classList.add('is-invalid');
                confirmaSenha.nextElementSibling.textContent = 'A confirmação de senha deve ter exatamente 8 caracteres alfabéticos.';
                isValid = false;
            } else {
                confirmaSenha.classList.remove('is-invalid');
                confirmaSenha.classList.add('is-valid');
                confirmaSenha.nextElementSibling.textContent = '';
            }


            // Se todas as validações passarem
            if (isValid) {
                // Se for válido no Front-end (JS), ele submete o formulário para a validação final (PHP).
                // Comentado o bloco de localStorage/redirecionamento falso do seu código original.
                // showFeedback('Cadastro validado no front-end. Enviando para o servidor...', 'info');
                form.submit(); // Envia para validacao_cadastro.php
            } else {
                showFeedback('Por favor, corrija os erros no formulário antes de enviar.', 'danger');
            }
        });

        // =========================================================================
        // LÓGICA MANTIDA DO JS (Limpar Tela, Busca de CEP, Exibição de Toast)
        // =========================================================================

        // Bloco de feedback do PHP (para erros que vieram do servidor)
        <?php if (count($erros) > 0 || $feedback_erro): ?>
            showFeedback('Ocorreu um problema no cadastro. Por favor, revise os campos e tente novamente.', 'danger');
        <?php elseif ($feedback_sucesso): ?>
            showFeedback('Sucesso! Redirecionando...', 'success');
            // Se você quiser um redirecionamento imediato após sucesso, descomente:
            // setTimeout(() => { window.location.href = 'login.php'; }, 2000); 
        <?php endif; ?>


        // Botão Limpar Tela
        document.getElementById('limparCadastro').addEventListener('click', function () {
            document.getElementById('cadastroForm').reset();
            // Remove as classes de validação de todos os campos
            document.querySelectorAll('.form-control, .form-select').forEach(input => {
                input.classList.remove('is-invalid', 'is-valid');
            });
            showFeedback('Formulário limpo.', 'info');
        });

        // Preenchimento automático de endereço por CEP (API ViaCEP)
        document.getElementById('cep').addEventListener('blur', async function () {
            const cep = this.value.replace(/\D/g, ''); 
            const logradouroInput = document.getElementById('logradouro');
            const bairroInput = document.getElementById('bairro');
            const cidadeInput = document.getElementById('cidade');
            const estadoInput = document.getElementById('estado');
            const numeroInput = document.getElementById('numero');

            // Limpa dados e validação prévios
            logradouroInput.value = '';
            bairroInput.value = '';
            cidadeInput.value = '';
            estadoInput.value = '';
            [logradouroInput, bairroInput, cidadeInput, estadoInput, numeroInput].forEach(input => 
                input.classList.remove('is-invalid', 'is-valid')
            );

            // Reseta a propriedade readonly em caso de erro no CEP anterior
            logradouroInput.readOnly = true;
            bairroInput.readOnly = true;
            cidadeInput.readOnly = true;
            estadoInput.readOnly = true;

            if (cep.length === 8) {
                try {
                    const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                    const data = await response.json();

                    if (!data.erro) {
                        logradouroInput.value = data.logradouro;
                        bairroInput.value = data.bairro;
                        cidadeInput.value = data.localidade;
                        estadoInput.value = data.uf;
                        
                        logradouroInput.readOnly = true; 
                        bairroInput.readOnly = true;
                        cidadeInput.readOnly = true;
                        estadoInput.readOnly = true;
                        numeroInput.focus();
                    } else {
                        // CEP não encontrado - permite preenchimento manual
                        logradouroInput.readOnly = false; 
                        bairroInput.readOnly = false;
                        cidadeInput.readOnly = false;
                        estadoInput.readOnly = false;
                        
                        document.getElementById('cep').classList.add('is-invalid');
                        document.getElementById('cep').nextElementSibling.textContent = 'CEP não encontrado. Preencha o endereço manualmente.';
                        logradouroInput.focus();
                    }
                } catch (error) {
                    // Erro de conexão/API - permite preenchimento manual
                    logradouroInput.readOnly = false; 
                    bairroInput.readOnly = false;
                    cidadeInput.readOnly = false;
                    estadoInput.readOnly = false;
                    document.getElementById('cep').classList.add('is-invalid');
                    document.getElementById('cep').nextElementSibling.textContent = 'Erro ao buscar CEP. Preencha o endereço manualmente.';
                }
            } else if (cep.length > 0) {
                 // Formato de CEP incorreto
                document.getElementById('cep').classList.add('is-invalid');
                document.getElementById('cep').nextElementSibling.textContent = 'CEP inválido. Formato esperado: XXXXX-XXX.';
            }
        });
    </script>
    <script src="javaScript/darkmodee.js"></script>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>

