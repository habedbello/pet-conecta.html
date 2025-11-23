<?php
session_start(); // 1. INICIA A SESSÃO PHP
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Login - PET CONECTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- ATENÇÃO: Os arquivos CSS devem ser ajustados para o caminho correto se estiverem em pastas diferentes -->
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
            <!-- Botoes Login/Cadastro - visíveis se NÃO estiver logado (padrão) -->
            <div class="login-cadastro" id="cadastro-login">
                <a href="login.php"><button id="btn-login-cadastro">Login</button></a>
                <a href="cadastro.php"><button id="btn-login-cadastro">Cadastro</button></a>
            </div>

            <button id="toggle-dark-mode"> ◐ </button>

            <!-- Info do Usuário - OCULTO por padrão ('d-none') -->
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
                    <input type="text" class="form-control" id="loginUsername" name="loginUsername" required placeholder="Seu login" minlength="6" maxlength="6">
                    <div class="invalid-feedback">Por favor, insira seu login.</div>
                </div>
                <div class="mb-3">
                    <label for="loginPassword" class="form-label">Senha:</label>
                    <input type="password" class="form-control" id="loginPassword" name="loginPassword" required placeholder="Sua senha" minlength="8" maxlength="8">
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
        // =========================================================================
        // LOGS DE DEBUG - CONSOLE
        // =========================================================================
        console.log('════════════════════════════════════════');
        console.log('🚀 PÁGINA DE LOGIN CARREGADA');
        console.log('════════════════════════════════════════');
        console.log('✅ JavaScript está funcionando!');
        console.log('📍 Página: Login de Usuário');
        console.log('🕐 Carregado em:', new Date().toLocaleString('pt-BR'));
        console.log('🌐 URL:', window.location.href);
        console.log('════════════════════════════════════════');
        
        // Log colorido
        console.log('%c🚀 PÁGINA DE LOGIN CARREGADA', 'color: #00ff00; font-size: 16px; font-weight: bold;');
        console.log('%c✅ JavaScript está funcionando!', 'color: #00ff00; font-size: 14px;');
        
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

        // BLOCO ORIGINAL: Exibe feedback da sessão PHP (Sucesso do Cadastro ou Erro de Login)
        (function () {
            // =========================================================================
            // VERIFICAÇÃO DE CONEXÃO COM BANCO DE DADOS
            // =========================================================================
            const dbInfo = <?php echo isset($_SESSION['db_info']) ? json_encode($_SESSION['db_info'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : 'null'; ?>;
            
            if (dbInfo) {
                console.log('════════════════════════════════════════');
                console.log('🗄️ INFORMAÇÕES DO BANCO DE DADOS');
                console.log('════════════════════════════════════════');
                
                if (dbInfo.conectado) {
                    console.log('%c✅ CONEXÃO ESTABELECIDA', 'color: #00ff00; font-size: 14px; font-weight: bold;');
                    console.log('📊 Status:', dbInfo.mensagem);
                    console.log('🖥️ Host:', dbInfo.host);
                    console.log('💾 Database:', dbInfo.database);
                    console.log('👤 Usuário:', dbInfo.usuario);
                    if (dbInfo.versao_mysql) {
                        console.log('🔢 Versão MySQL:', dbInfo.versao_mysql);
                    }
                    if (dbInfo.charset) {
                        console.log('🔤 Charset:', dbInfo.charset);
                    }
                    console.log('🕐 Timestamp:', dbInfo.timestamp);
                    
                    // Informações sobre dados recebidos
                    if (dbInfo.dados_recebidos) {
                        console.log('════════════════════════════════════════');
                        console.log('📥 DADOS RECEBIDOS DO FORMULÁRIO');
                        console.log('════════════════════════════════════════');
                        console.log('👤 Login:', dbInfo.dados_recebidos.login || '(vazio)');
                        console.log('📏 Tamanho do login:', dbInfo.dados_recebidos.login_length, 'caracteres');
                        console.log('📏 Tamanho da senha:', dbInfo.dados_recebidos.senha_length, 'caracteres');
                        console.log('🔍 Login vazio:', dbInfo.dados_recebidos.login_vazio ? '❌ Sim' : '✅ Não');
                        console.log('🔍 Senha vazia:', dbInfo.dados_recebidos.senha_vazia ? '❌ Sim' : '✅ Não');
                        console.log('🕐 Timestamp:', dbInfo.dados_recebidos.timestamp);
                    }
                    
                    // Informações sobre validação
                    if (dbInfo.validacao) {
                        console.log('════════════════════════════════════════');
                        console.log('✅ VALIDAÇÃO DOS DADOS');
                        console.log('════════════════════════════════════════');
                        console.log('📊 Passou:', dbInfo.validacao.passou ? '✅ Sim' : '❌ Não');
                        console.log('📋 Etapa:', dbInfo.validacao.etapa);
                        console.log('💬 Motivo:', dbInfo.validacao.motivo);
                        
                        if (dbInfo.validacao.etapa === 'formato') {
                            console.log('🔍 Login válido:', dbInfo.validacao.login_valido ? '✅ Sim' : '❌ Não');
                            console.log('🔍 Senha válida:', dbInfo.validacao.senha_valida ? '✅ Sim' : '❌ Não');
                            console.log('📏 Tamanho do login:', dbInfo.validacao.login_tamanho, 'caracteres');
                            console.log('📏 Tamanho da senha:', dbInfo.validacao.senha_tamanho, 'caracteres');
                            console.log('🔤 Login alfabético:', dbInfo.validacao.login_alfabetico ? '✅ Sim' : '❌ Não');
                            console.log('🔤 Senha alfabética:', dbInfo.validacao.senha_alfabetica ? '✅ Sim' : '❌ Não');
                        } else if (dbInfo.validacao.etapa === 'preenchimento') {
                            console.log('🔍 Login preenchido:', dbInfo.validacao.login_preenchido ? '✅ Sim' : '❌ Não');
                            console.log('🔍 Senha preenchida:', dbInfo.validacao.senha_preenchida ? '✅ Sim' : '❌ Não');
                        }
                    }
                    
                    // Informações sobre query
                    if (dbInfo.query_executada) {
                        console.log('════════════════════════════════════════');
                        console.log('📝 INFORMAÇÕES DA QUERY');
                        console.log('════════════════════════════════════════');
                        console.log('✅ Query executada:', dbInfo.query_executada);
                        console.log('📋 Tipo:', dbInfo.query_tipo);
                        console.log('📊 Tabela:', dbInfo.query_tabela);
                        console.log('🔍 Campo de busca:', dbInfo.query_campo_busca);
                        console.log('💬 Valor buscado:', dbInfo.query_valor_busca);
                        console.log('🕐 Timestamp da query:', dbInfo.query_timestamp);
                        
                        if (dbInfo.query_resultado) {
                            console.log('════════════════════════════════════════');
                            console.log('📊 RESULTADO DA QUERY');
                            console.log('════════════════════════════════════════');
                            console.log('📈 Linhas encontradas:', dbInfo.query_resultado.linhas_encontradas);
                            console.log('👤 Usuário encontrado:', dbInfo.query_resultado.usuario_encontrado ? '✅ Sim' : '❌ Não');
                            console.log('🆔 Tem ID:', dbInfo.query_resultado.tem_id ? '✅ Sim' : '❌ Não');
                            console.log('📝 Tem Nome:', dbInfo.query_resultado.tem_nome ? '✅ Sim' : '❌ Não');
                        }
                    } else {
                        console.log('ℹ️ Query não foi executada (validação falhou antes)');
                    }
                } else {
                    console.log('%c❌ ERRO NA CONEXÃO', 'color: #ff0000; font-size: 14px; font-weight: bold;');
                    console.log('📊 Status:', dbInfo.mensagem);
                    console.log('🖥️ Host:', dbInfo.host);
                    console.log('💾 Database:', dbInfo.database);
                    console.log('👤 Usuário:', dbInfo.usuario);
                    if (dbInfo.erro) {
                        console.error('❌ Erro:', dbInfo.erro);
                    }
                    if (dbInfo.erro_codigo) {
                        console.error('🔢 Código do erro:', dbInfo.erro_codigo);
                    }
                    if (dbInfo.erro_arquivo) {
                        console.error('📁 Arquivo:', dbInfo.erro_arquivo);
                    }
                    if (dbInfo.erro_linha) {
                        console.error('📍 Linha:', dbInfo.erro_linha);
                    }
                    if (dbInfo.query_erro) {
                        console.error('❌ Erro na query:', dbInfo.query_erro);
                    }
                    console.log('🕐 Timestamp:', dbInfo.timestamp);
                }
                console.log('════════════════════════════════════════');
                
                // Limpa informações do banco da sessão após exibir
                <?php unset($_SESSION['db_info']); ?>
            } else {
                console.log('ℹ️ Nenhuma informação de banco de dados disponível (primeira carga da página)');
            }
            
            // Assegura que o JavaScript não quebre se a variável de sessão não existir
            const feedbackMessage = "<?php echo isset($_SESSION['feedback_mensagem']) ? addslashes($_SESSION['feedback_mensagem']) : ''; ?>";
            const feedbackType = "<?php echo isset($_SESSION['feedback_tipo']) ? addslashes($_SESSION['feedback_tipo']) : ''; ?>";
            
            console.log('🔍 VERIFICANDO FEEDBACK DA SESSÃO');
            console.log('feedbackMessage:', feedbackMessage || 'Nenhuma mensagem');
            console.log('feedbackType:', feedbackType || 'Nenhum tipo');
            
            if (feedbackMessage && feedbackType) {
                console.log('📢 Exibindo feedback:', feedbackMessage, 'Tipo:', feedbackType);
                showFeedback(feedbackMessage, feedbackType);
                
                // Limpa o feedback da sessão após a exibição
                <?php 
                unset($_SESSION['feedback_mensagem']); 
                unset($_SESSION['feedback_tipo']);
                ?>
            } else {
                console.log('ℹ️ Nenhum feedback para exibir');
            }
        })();
        
        // =========================================================================
        // NOVO BLOCO: Gestão do Estado de Login na Interface (Header)
        // ESSA LÓGICA DEVE SER COLOCADA EM TODAS AS PÁGINAS QUE POSSUEM O CABEÇALHO
        // =========================================================================
        (function () {
            console.log('════════════════════════════════════════');
            console.log('👤 GESTÃO DO ESTADO DE LOGIN NO HEADER');
            console.log('════════════════════════════════════════');
            
            // 1. Recebe dados da sessão PHP (AJUSTADO PARA USAR $_SESSION['logado'] e $_SESSION['nome'])
            const usuarioLogado = <?php echo isset($_SESSION['logado']) && $_SESSION['logado'] ? 'true' : 'false'; ?>;
            const nomeUsuario = "<?php echo isset($_SESSION['nome']) ? addslashes($_SESSION['nome']) : ''; ?>";
            
            console.log('Status da Sessão PHP: Logado=', usuarioLogado, 'Nome:', nomeUsuario || '(Não Logado)');

            // 2. Elementos DOM
            const divLoginCadastro = document.getElementById('cadastro-login');
            const divUserInfo = document.getElementById('user-info');
            const spanLoggedInUser = document.getElementById('logged-in-user');
            const btnLogout = document.getElementById('logout-btn');
            
            if (usuarioLogado) {
                // Usuário está logado: Oculta Login/Cadastro, Exibe Info do Usuário
                console.log('✅ Usuário está logado. Atualizando Header...');
                
                if (divLoginCadastro) {
                    divLoginCadastro.classList.add('d-none'); // Oculta os botões Login/Cadastro
                    console.log('    - Ocultado: Div Login/Cadastro');
                } else {
                    console.error('    ❌ Div #cadastro-login não encontrada!');
                }
                
                if (divUserInfo && spanLoggedInUser) {
                    // Remove 'd-none' do divUserInfo para mostrar as opções
                    divUserInfo.classList.remove('d-none'); 
                    
                    // Define a mensagem de boas-vindas com o link Alterar Senha
                    spanLoggedInUser.innerHTML = `Bem-vindo(a), <strong>${nomeUsuario}</strong>! | <a href="alterar_senha.php" class="text-white text-decoration-none">Alterar Senha</a>`;
                    console.log('    - Exibido: Div User Info com nome:', nomeUsuario);
                } else {
                    console.error('    ❌ Div #user-info ou Span #logged-in-user não encontrados!');
                }
                
                // 3. Configura o botão Sair
                if (btnLogout) {
                    btnLogout.addEventListener('click', function() {
                        console.log('🚪 Botão Sair clicado. Redirecionando para Logout...');
                        // Redireciona para o script de logout PHP
                        window.location.href = 'php/logout.php'; 
                    });
                    console.log('    - Listener configurado para botão Sair');
                } else {
                    console.error('    ❌ Botão #logout-btn não encontrado!');
                }
                
            } else {
                // Usuário NÃO está logado: Garante que Login/Cadastro está visível e Info está oculta
                console.log('❌ Usuário NÃO está logado. Header padrão.');
                if (divLoginCadastro) {
                    divLoginCadastro.classList.remove('d-none');
                }
                if (divUserInfo) {
                    divUserInfo.classList.add('d-none');
                }
            }
            console.log('════════════════════════════════════════');
        })();


        // Listener para o evento de submissão do formulário de login
        const loginForm = document.getElementById('loginForm');
        if (!loginForm) {
            console.error('❌ ERRO: Formulário de login não encontrado!');
        } else {
            console.log('✅ Formulário de login encontrado:', loginForm);
            console.log('📋 Action do formulário:', loginForm.action);
            console.log('📋 Method do formulário:', loginForm.method);
        }
        
        loginForm.addEventListener('submit', function (event) {
            console.log('════════════════════════════════════════');
            console.log('📝 EVENTO SUBMIT DO FORMULÁRIO DE LOGIN CAPTURADO');
            console.log('════════════════════════════════════════');
            const form = event.target;
            const loginInput = document.getElementById('loginUsername');
            const passwordInput = document.getElementById('loginPassword');
            
            // Verifica se os inputs existem
            if (!loginInput) {
                console.error('❌ ERRO: Campo loginUsername não encontrado!');
                return;
            }
            if (!passwordInput) {
                console.error('❌ ERRO: Campo loginPassword não encontrado!');
                return;
            }
            
            console.log('✅ Campos encontrados:', {
                loginInput: loginInput ? 'Sim' : 'Não',
                passwordInput: passwordInput ? 'Sim' : 'Não'
            });
            
            // Limpa classes de validação anteriores
            loginInput.classList.remove('is-invalid', 'is-valid');
            passwordInput.classList.remove('is-invalid', 'is-valid');

            let isValid = true; // Flag para controlar a validade geral do formulário
            const loginValue = loginInput.value.trim();
            const passwordValue = passwordInput.value.trim();
            
            console.log('🔍 Dados do formulário ANTES da validação:', {
                login_raw: loginInput.value,
                login_trimmed: loginValue,
                login_length: loginValue.length,
                senha_length: passwordValue.length,
                senha: '***' // Não logar senha por segurança
            });
            
            // Verifica se os campos têm o atributo name correto
            console.log('🔍 Atributos dos campos:', {
                login_name: loginInput.getAttribute('name'),
                login_id: loginInput.getAttribute('id'),
                senha_name: passwordInput.getAttribute('name'),
                senha_id: passwordInput.getAttribute('id')
            });

            // Validação de formato Login (Exatamente 6 caracteres alfabéticos)
            console.log('🔍 Validando login:', {
                valor: loginValue,
                tamanho: loginValue.length,
                formatoValido: /^[a-zA-Z]{6}$/.test(loginValue)
            });
            
            if (loginValue.length === 6 && /^[a-zA-Z]{6}$/.test(loginValue)) {
                loginInput.classList.add('is-valid');
                console.log('✅ Login válido');
            } else {
                loginInput.classList.add('is-invalid');
                isValid = false;
                console.log('❌ Login inválido');
                showFeedback('Login deve ter exatamente 6 caracteres alfabéticos.', 'danger');
            }

            // Validação de formato Senha (Exatamente 8 caracteres alfabéticos)
            console.log('🔍 Validando senha:', {
                tamanho: passwordValue.length,
                formatoValido: /^[a-zA-Z]{8}$/.test(passwordValue)
            });
            
            if (passwordValue.length === 8 && /^[a-zA-Z]{8}$/.test(passwordValue)) {
                passwordInput.classList.add('is-valid');
                console.log('✅ Senha válida');
            } else {
                passwordInput.classList.add('is-invalid');
                isValid = false;
                console.log('❌ Senha inválida');
                showFeedback('Senha deve ter exatamente 8 caracteres alfabéticos.', 'danger');
            }

            // Se a validação falhar, IMPEDE o envio
            if (!isValid) {
                console.log('❌ Validação falhou - Formulário NÃO será enviado');
                event.preventDefault(); // Impede o envio apenas se houver erro
                return false;
            }
            
            // Se a validação passou, PERMITE o envio normal (não cancela o evento)
            console.log('✅ Validação passou - Permitindo envio do formulário para o servidor');
            console.log('📤 Dados que serão enviados:', {
                login: loginValue,
                senha_length: passwordValue.length,
                action: form.action,
                method: form.method
            });
            // Não faz preventDefault() - deixa o formulário ser enviado normalmente
        });

        // Listener para o botão 'Limpar'
        document.getElementById('limparLogin').addEventListener('click', function () {
            console.log('🧹 Botão Limpar clicado');
            document.getElementById('loginForm').reset(); // Reseta os campos do formulário
            // Remove as classes de validação dos campos
            document.getElementById('loginUsername').classList.remove('is-invalid', 'is-valid');
            document.getElementById('loginPassword').classList.remove('is-invalid', 'is-valid');
            console.log('✅ Campos de login limpos');
            showFeedback('Campos de login limpos.', 'info');
        });
        
        console.log('✅ Todos os event listeners do formulário de login foram configurados');
    </script>
    <script src="javaScript/darkmodee.js"></script>

    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>


</body>

</html>