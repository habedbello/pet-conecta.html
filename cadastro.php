<?php
// Função de log para cadastro.php
if (!function_exists('logDebugCadastro')) {
    function logDebugCadastro($mensagem, $dados = null) {
        $logFile = __DIR__ . '/logs_cadastro.txt';
        $logDir = dirname($logFile);
        if (!file_exists($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] [CADASTRO.PHP] $mensagem";
        if ($dados !== null) {
            $logMessage .= " | Dados: " . print_r($dados, true);
        }
        $logMessage .= "\n";
        @file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}

session_start();

logDebugCadastro("=== INÍCIO CADASTRO.PHP ===");
logDebugCadastro("Estado da sessão ANTES de recuperar variáveis", [
    'session_id' => session_id(),
    'session_keys' => array_keys($_SESSION),
    'feedback_sucesso' => $_SESSION['feedback_sucesso'] ?? 'NÃO DEFINIDO',
    'feedback_erro' => $_SESSION['feedback_erro'] ?? 'NÃO DEFINIDO',
    'erros' => isset($_SESSION['erros']) ? count($_SESSION['erros']) : 0,
    'nome_cadastrado' => $_SESSION['nome_cadastrado'] ?? 'NÃO DEFINIDO'
]);

// Recupera erros e dados da sessão para repopular o formulário e exibir mensagens
// IMPORTANTE: Verificar sucesso PRIMEIRO para evitar conflitos
$feedback_sucesso = $_SESSION['feedback_sucesso'] ?? null;
$nome_cadastrado = $_SESSION['nome_cadastrado'] ?? null;

logDebugCadastro("Variáveis recuperadas da sessão", [
    'feedback_sucesso' => $feedback_sucesso,
    'nome_cadastrado' => $nome_cadastrado
]);

// Se houver sucesso, limpar todos os erros para evitar mensagens conflitantes
if ($feedback_sucesso) {
    logDebugCadastro("✅ SUCESSO DETECTADO - Limpando erros");
    unset($_SESSION['erros']);
    unset($_SESSION['dados']);
    unset($_SESSION['feedback_erro']);
    $erros = [];
    $dados = [];
    $feedback_erro = null;
    logDebugCadastro("Erros limpos", [
        'erros' => count($erros),
        'feedback_erro' => $feedback_erro
    ]);
} else {
    // Só recuperar erros se não houver sucesso
    logDebugCadastro("⚠️ NÃO HÁ SUCESSO - Recuperando erros");
    $erros = $_SESSION['erros'] ?? [];
    $dados = $_SESSION['dados'] ?? [];
    $feedback_erro = $_SESSION['feedback_erro'] ?? null;
    logDebugCadastro("Erros recuperados", [
        'erros_count' => count($erros),
        'erros' => $erros,
        'feedback_erro' => $feedback_erro,
        'dados_count' => count($dados)
    ]);
}

// Limpa as variáveis de sessão após recuperá-las
unset($_SESSION['erros']);
unset($_SESSION['dados']);
unset($_SESSION['feedback_erro']);
unset($_SESSION['feedback_sucesso']);

logDebugCadastro("Estado FINAL das variáveis", [
    'feedback_sucesso' => $feedback_sucesso,
    'feedback_erro' => $feedback_erro,
    'erros_count' => count($erros),
    'nome_cadastrado' => $nome_cadastrado
]);

// Não limpa nome_cadastrado ainda - será usado na exibição e limpo depois

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
    
    <!-- SCRIPT DE TESTE IMEDIATO - DEVE APARECER PRIMEIRO NO CONSOLE -->
    <script>
        // Este script executa IMEDIATAMENTE quando a página carrega
        // Usar try-catch para garantir que sempre execute
        try {
            // Teste básico - sempre deve aparecer
            if (typeof console !== 'undefined' && console.log) {
                console.log('════════════════════════════════════════');
                console.log('🚀 SISTEMA DE LOGS ATIVADO');
                console.log('════════════════════════════════════════');
                console.log('✅ JavaScript está funcionando!');
                console.log('📍 Página: Cadastro de Usuário');
                console.log('🕐 Carregado em:', new Date().toLocaleString('pt-BR'));
                console.log('🌐 URL:', window.location.href);
                console.log('📋 Abra o Console (F12) para ver todos os logs');
                console.log('════════════════════════════════════════');
                
                // Log colorido
                console.log('%c🚀 SISTEMA DE LOGS ATIVADO', 'color: #00ff00; font-size: 16px; font-weight: bold;');
                console.log('%c✅ JavaScript está funcionando!', 'color: #00ff00; font-size: 14px;');
            } else {
                // Fallback se console não estiver disponível
                alert('⚠️ Console não disponível. Use F12 para abrir as ferramentas de desenvolvedor.');
            }
        } catch (e) {
            // Se houver erro, tentar alerta
            alert('Erro ao inicializar logs: ' + e.message);
        }
    </script>
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

            <?php 
            logDebugCadastro("Verificando qual mensagem exibir", [
                'feedback_sucesso' => $feedback_sucesso,
                'feedback_erro' => $feedback_erro,
                'erros_count' => count($erros)
            ]);
            if ($feedback_sucesso): 
                logDebugCadastro("✅ EXIBINDO MENSAGEM DE SUCESSO");
                unset($_SESSION['nome_cadastrado']); // Limpa após usar ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border: 3px solid #28a745; border-radius: 12px; padding: 25px; margin-bottom: 25px; animation: slideIn 0.5s ease-out;">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-4">
                            <div style="width: 60px; height: 60px; background-color: #28a745; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);">
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 12l2 2 4-4" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="alert-heading mb-2" style="color: #155724; font-weight: bold; font-size: 24px; margin: 0;">
                                <i class="fas fa-check-circle me-2" style="color: #28a745;"></i>Cadastrado com Sucesso!
                            </h4>
                            <p class="mb-2" style="color: #155724; font-size: 16px; line-height: 1.6;">
                                <?php 
                                if ($nome_cadastrado) {
                                    echo "Parabéns <strong>" . htmlspecialchars($nome_cadastrado) . "</strong>! Seu cadastro foi realizado com sucesso. ";
                                } else {
                                    echo $feedback_sucesso . " ";
                                }
                                ?>
                                Agora você pode fazer login para acessar sua conta.
                            </p>
                            <div class="mt-3">
                                <a href="login.php" class="btn btn-success btn-lg px-4 shadow-sm">
                                    <i class="fas fa-sign-in-alt me-2"></i>Ir para Login
                                </a>
                                <button type="button" onclick="window.location.href='cadastro.php'" class="btn btn-outline-secondary btn-lg px-4 ms-2">
                                    <i class="fas fa-times me-2"></i>Fechar
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="opacity: 0.7;"></button>
                    </div>
                </div>
                <style>
                    @keyframes slideIn {
                        from {
                            opacity: 0;
                            transform: translateY(-20px);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }
                </style>
            <?php elseif ($feedback_erro): 
                logDebugCadastro("❌ EXIBINDO MENSAGEM DE ERRO", [
                    'feedback_erro' => $feedback_erro,
                    'feedback_sucesso' => $feedback_sucesso,
                    'erros_count' => count($erros)
                ]); ?>
                <div class="alert alert-danger text-center" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?= $feedback_erro ?>
                </div>
            <?php 
            logDebugCadastro("Nenhuma mensagem para exibir", [
                'feedback_sucesso' => $feedback_sucesso,
                'feedback_erro' => $feedback_erro
            ]);
            endif; ?>

            <?php if (!$feedback_sucesso): // Só mostra o formulário se não houver sucesso ?>
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
                        value="<?= valorCampo('campo_celular', $dados) ?>" placeholder="(+55)21-992302861" required maxlength="20">
                    <small class="form-text text-muted">Digite o DDD e o número. O formato será aplicado automaticamente.</small>
                    <?php if (isset($erros['campo_celular'])): ?>
                        <div class="invalid-feedback d-block"><?= $erros['campo_celular'] ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <label for="telefoneFixo" class="form-label">Telefone Fixo:</label>
                    <input type="text" class="form-control <?= classeInvalida('campo_fixo', $erros) ?>" id="telefoneFixo" name="campo_fixo"
                        value="<?= valorCampo('campo_fixo', $dados) ?>" placeholder="(+55)21-24035149" required maxlength="20">
                    <small class="form-text text-muted">Digite o DDD e o número. O formato será aplicado automaticamente.</small>
                    <?php if (isset($erros['campo_fixo'])): ?>
                        <div class="invalid-feedback d-block"><?= $erros['campo_fixo'] ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <label for="cep" class="form-label">CEP:</label>
                    <input type="text" class="form-control <?= classeInvalida('campo_cep', $erros) ?>" id="cep" name="campo_cep"
                        value="<?= valorCampo('campo_cep', $dados) ?>" placeholder="00000-000" required>
                    <div class="invalid-feedback"><?= exibirErro('campo_cep', $erros, 'CEP inválido. Deve conter 8 dígitos (com ou sem hífen).') ?></div>
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
                    <button type="button" class="btn btn-info" id="testarConsole" onclick="testarConsoleLogs()">🧪 Testar Console</button>
                </div>
            </form>
            <?php else: ?>
                <!-- Espaço vazio quando há sucesso para manter o layout -->
                <div class="text-center py-5">
                    <p class="text-muted">Seu cadastro foi realizado com sucesso!</p>
                </div>
            <?php endif; ?>

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
    
    <!-- Carregar scripts externos com tratamento de erro -->
    <script>
        console.log('📦 Carregando scripts externos...');
    </script>
    <script src="javaScript/main.js" onerror="console.error('❌ Erro ao carregar main.js')"></script>
    <script src="javaScript/validacoes.js" onerror="console.error('❌ Erro ao carregar validacoes.js')"></script>
    <script>
        console.log('✅ Scripts externos carregados');
    </script>

    <script>
        // =========================================================================
        // SISTEMA DE LOGS NO CONSOLE DO NAVEGADOR
        // =========================================================================
        
        console.log('%c════════════════════════════════════════', 'color: #569cd6; font-size: 14px;');
        console.log('%c📝 SCRIPT DE CADASTRO INICIADO', 'color: #569cd6; font-size: 14px; font-weight: bold;');
        console.log('%c════════════════════════════════════════', 'color: #569cd6; font-size: 14px;');
        
        // LOG IMEDIATO PARA TESTE - DEVE APARECER PRIMEIRO
        console.log('🚀 SCRIPT DE CADASTRO CARREGADO - TESTE INICIAL');
        console.log('==========================================');
        console.log('✅ Se você está vendo isso, o JavaScript está funcionando!');
        console.log('==========================================');
        
        // Verificar se console está disponível
        if (typeof console === 'undefined') {
            alert('❌ Console não está disponível neste navegador!');
        } else {
            console.log('✅ Console disponível e funcionando');
        }
        
        // Função para log no console com formatação
        function logConsole(tipo, mensagem, dados = null) {
            try {
                const timestamp = new Date().toLocaleTimeString('pt-BR');
                const estilo = {
                    'info': 'color: #569cd6; font-weight: bold;',
                    'success': 'color: #4ec9b0; font-weight: bold;',
                    'error': 'color: #f48771; font-weight: bold;',
                    'warning': 'color: #dcdcaa; font-weight: bold;',
                    'debug': 'color: #ce9178; font-weight: bold;'
                };
                
                const emoji = {
                    'info': 'ℹ️',
                    'success': '✅',
                    'error': '❌',
                    'warning': '⚠️',
                    'debug': '🔍'
                };
                
                // Log básico sempre funciona
                console.log(`[${timestamp}] ${emoji[tipo] || '📝'} ${mensagem}`);
                
                // Log com formatação colorida
                if (console.log && typeof console.log === 'function') {
                    console.log(
                        `%c[${timestamp}] ${emoji[tipo] || '📝'} ${mensagem}`,
                        estilo[tipo] || 'color: #d4d4d4;',
                        dados || ''
                    );
                }
                
                // Se houver dados, mostrar em tabela ou objeto
                if (dados && typeof dados === 'object') {
                    if (console.table && typeof console.table === 'function') {
                        console.table(dados);
                    } else {
                        console.log('Dados:', dados);
                    }
                }
            } catch (e) {
                // Fallback para log simples se houver erro
                console.log('LOG:', tipo, mensagem, dados);
            }
        }
        
        // Log inicial - TESTE
        console.log('✅ Função logConsole definida com sucesso');
        logConsole('info', 'Sistema de cadastro carregado');
        console.log('✅ Log inicial executado');
        
        // Log dos dados do PHP (se houver)
        console.log('🔍 VERIFICANDO VARIÁVEIS PHP NO JAVASCRIPT');
        console.log('feedback_sucesso:', <?= $feedback_sucesso ? "'" . addslashes($feedback_sucesso) . "'" : 'null' ?>);
        console.log('feedback_erro:', <?= $feedback_erro ? "'" . addslashes($feedback_erro) . "'" : 'null' ?>);
        console.log('erros_count:', <?= count($erros) ?>);
        console.log('erros:', <?= json_encode($erros) ?>);
        
        <?php if (count($erros) > 0): ?>
            logConsole('error', 'Erros encontrados no servidor', <?= json_encode($erros) ?>);
            console.error('❌ ERROS DO PHP:', <?= json_encode($erros) ?>);
        <?php endif; ?>
        
        <?php if ($feedback_erro): ?>
            logConsole('error', 'Feedback de erro', '<?= addslashes($feedback_erro) ?>');
            console.error('❌ FEEDBACK DE ERRO DO PHP:', '<?= addslashes($feedback_erro) ?>');
        <?php endif; ?>
        
        <?php if ($feedback_sucesso): ?>
            logConsole('success', 'Feedback de sucesso', '<?= addslashes($feedback_sucesso) ?>');
            console.log('✅ FEEDBACK DE SUCESSO DO PHP:', '<?= addslashes($feedback_sucesso) ?>');
        <?php endif; ?>
        
        // Verificar elementos HTML na página
        const alertSuccess = document.querySelector('.alert-success');
        const alertDanger = document.querySelector('.alert-danger');
        console.log('🔍 ELEMENTOS HTML ENCONTRADOS:');
        console.log('alert-success:', alertSuccess ? 'ENCONTRADO' : 'NÃO ENCONTRADO');
        console.log('alert-danger:', alertDanger ? 'ENCONTRADO' : 'NÃO ENCONTRADO');
        if (alertSuccess) {
            console.log('Conteúdo do alert-success:', alertSuccess.textContent);
        }
        if (alertDanger) {
            console.log('Conteúdo do alert-danger:', alertDanger.textContent);
        }
        
        // Função para exibir mensagens de feedback utilizando o componente Toast do Bootstrap
        function showFeedback(message, type) {
            logConsole(type === 'success' ? 'success' : type === 'danger' ? 'error' : 'info', 
                      'Feedback exibido', message);
            
            const toastElement = document.getElementById('feedbackToast');
            const toastBody = toastElement.querySelector('.toast-body');
            toastBody.textContent = message;
            toastElement.classList.remove('text-bg-success', 'text-bg-danger', 'text-bg-warning', 'text-bg-info');
            toastElement.classList.add(`text-bg-${type}`);
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
        }

        
        // RESTAURAÇÃO COMPLETA DA LÓGICA DE VALIDAÇÃO JAVASCRIPT
        

        // Verificar se o formulário existe
        console.log('🔍 Procurando formulário com ID: cadastroForm');
        const formElement = document.getElementById('cadastroForm');
        if (!formElement) {
            console.error('❌ ERRO: Formulário com ID "cadastroForm" não encontrado!');
            console.log('Elementos disponíveis:', document.querySelectorAll('form'));
        } else {
            console.log('✅ Formulário encontrado:', formElement);
        }
        
        // evento de submissão do formulário de cadastro
        if (formElement) {
            formElement.addEventListener('submit', function (event) {
                console.log('📝 EVENTO SUBMIT CAPTURADO!');
                event.preventDefault(); // Impede o envio padrão do formulário
                
                console.log('=== INÍCIO DA VALIDAÇÃO DO FORMULÁRIO ===');
                logConsole('info', '=== INÍCIO DA VALIDAÇÃO DO FORMULÁRIO ===');
            
            const form = event.target;
            let isValid = true; //controlar a validade geral do formulário
            
            // Coleta todos os dados do formulário
            const formData = new FormData(form);
            const dadosForm = {};
            for (let [key, value] of formData.entries()) {
                dadosForm[key] = value;
            }
            
            logConsole('debug', 'Dados do formulário coletados', dadosForm);

            // Limpa as classes de validação e mensagens de feedback
            form.querySelectorAll('.form-control, .form-select').forEach(input => {
                input.classList.remove('is-invalid', 'is-valid');
            });

            // Revalidação dos campos

            // Validação de Nome Completo
            const nomeCompleto = document.getElementById('nomeCompleto');
            logConsole('debug', 'Validando nome completo', { valor: nomeCompleto.value, tamanho: nomeCompleto.value.length });
            
            if (nomeCompleto.value.length < 15 || nomeCompleto.value.length > 80 || !/^[a-zA-Z\sÀ-ú]+$/.test(nomeCompleto.value)) {
                nomeCompleto.classList.add('is-invalid');
                nomeCompleto.nextElementSibling.textContent = 'O nome completo deve ter entre 15 e 80 caracteres alfabéticos.';
                logConsole('error', 'Nome completo inválido', { valor: nomeCompleto.value });
                isValid = false;
            } else {
                nomeCompleto.classList.add('is-valid');
                logConsole('success', 'Nome completo válido');
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

            // Validação de Telefone Celular - apenas verifica se não está vazio
            const telefoneCelular = document.getElementById('telefoneCelular');
            const valorCelularLimpo = telefoneCelular.value.replace(/\D/g, '');
            logConsole('debug', 'Validando telefone celular', { 
                valor: telefoneCelular.value, 
                valorLimpo: valorCelularLimpo, 
                tamanho: valorCelularLimpo.length 
            });
            
            if (valorCelularLimpo.length < 3 || telefoneCelular.value.trim() === '' || telefoneCelular.value === '(+55)') {
                telefoneCelular.classList.remove('is-valid');
                telefoneCelular.classList.add('is-invalid');
                logConsole('error', 'Telefone celular inválido', { valor: telefoneCelular.value });
                isValid = false;
            } else {
                telefoneCelular.classList.remove('is-invalid');
                telefoneCelular.classList.add('is-valid');
                logConsole('success', 'Telefone celular válido', { valor: telefoneCelular.value });
            }

            // Validação de Telefone Fixo - apenas verifica se não está vazio
            const telefoneFixo = document.getElementById('telefoneFixo');
            const valorFixoLimpo = telefoneFixo.value.replace(/\D/g, '');
            logConsole('debug', 'Validando telefone fixo', { 
                valor: telefoneFixo.value, 
                valorLimpo: valorFixoLimpo, 
                tamanho: valorFixoLimpo.length 
            });
            
            if (valorFixoLimpo.length < 3 || telefoneFixo.value.trim() === '' || telefoneFixo.value === '(+55)') {
                telefoneFixo.classList.remove('is-valid');
                telefoneFixo.classList.add('is-invalid');
                logConsole('error', 'Telefone fixo inválido', { valor: telefoneFixo.value });
                isValid = false;
            } else {
                telefoneFixo.classList.remove('is-invalid');
                telefoneFixo.classList.add('is-valid');
                logConsole('success', 'Telefone fixo válido', { valor: telefoneFixo.value });
            }

            // Validação de CEP - aceita com ou sem hífen, deve ter 8 dígitos
            const cep = document.getElementById('cep');
            const cepLimpo = cep.value.replace(/\D/g, '');
            logConsole('debug', 'Validando CEP', { 
                valor: cep.value, 
                valorLimpo: cepLimpo, 
                tamanho: cepLimpo.length 
            });
            
            if (cep.value.trim() === '' || cepLimpo.length === 0) {
                cep.classList.remove('is-valid');
                cep.classList.add('is-invalid');
                cep.nextElementSibling.textContent = 'CEP é obrigatório.';
                logConsole('error', 'CEP vazio', { valor: cep.value });
                isValid = false;
            } else if (cepLimpo.length !== 8) {
                cep.classList.remove('is-valid');
                cep.classList.add('is-invalid');
                cep.nextElementSibling.textContent = 'CEP inválido. Deve conter 8 dígitos (com ou sem hífen).';
                logConsole('error', 'CEP inválido - tamanho incorreto', { valor: cep.value, tamanho: cepLimpo.length });
                isValid = false;
            } else {
                cep.classList.remove('is-invalid');
                cep.classList.add('is-valid');
                logConsole('success', 'CEP válido', { valor: cep.value, cepLimpo: cepLimpo });
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


            // Resumo das validações
            const camposInvalidos = form.querySelectorAll('.is-invalid').length;
            const camposValidos = form.querySelectorAll('.is-valid').length;
            
            logConsole('info', '=== RESUMO DAS VALIDAÇÕES ===', {
                camposInvalidos: camposInvalidos,
                camposValidos: camposValidos,
                formularioValido: isValid
            });

            // Se todas as validações passarem
            if (isValid) {
                logConsole('success', '=== VALIDAÇÃO FRONT-END PASSOU ===');
                logConsole('info', 'Enviando formulário para o servidor', {
                    action: form.action,
                    method: form.method,
                    campos: Object.keys(dadosForm).length,
                    url: form.action
                });
                
                // Intercepta o envio para logar
                const formDataEnvio = new FormData(form);
                const dadosEnvio = {};
                for (let [key, value] of formDataEnvio.entries()) {
                    if (key !== 'campo_senha' && key !== 'campo_confirma') {
                        dadosEnvio[key] = value;
                    } else {
                        dadosEnvio[key] = '***';
                    }
                }
                logConsole('debug', 'Dados que serão enviados (senhas ocultas)', dadosEnvio);
                
                // Log antes de enviar
                logConsole('info', 'Submetendo formulário...', { 
                    timestamp: new Date().toISOString() 
                });
                
                console.log('✅ Formulário válido - ENVIANDO...');
                form.submit(); // Envia para validacao_cadastro.php
            } else {
                console.error('❌ Formulário inválido - NÃO ENVIADO');
                logConsole('error', '=== VALIDAÇÃO FRONT-END FALHOU ===');
                logConsole('warning', 'Formulário contém erros. Corrija antes de enviar.', {
                    camposComErro: camposInvalidos,
                    listaErros: Array.from(form.querySelectorAll('.is-invalid')).map(el => ({
                        campo: el.id || el.name,
                        valor: el.value
                    }))
                });
                showFeedback('Por favor, corrija os erros no formulário antes de enviar.', 'danger');
            }
            });
        } else {
            console.error('❌ Não foi possível adicionar listener ao formulário - elemento não encontrado');
        }

        // =========================================================================
        // FORMATAÇÃO AUTOMÁTICA DE TELEFONE
        // =========================================================================

        // Função para formatar telefone celular: (+55)XX-XXXXXXXXX (aceita qualquer quantidade de dígitos)
        function formatarTelefoneCelular(input) {
            // Remove tudo que não é dígito
            let valor = input.value.replace(/\D/g, '');
            
            // Se começar com 55, remove (já vamos adicionar +55)
            if (valor.startsWith('55')) {
                valor = valor.substring(2);
            }
            
            // Aceita qualquer quantidade de dígitos após o DDD (mínimo 1, máximo 15)
            if (valor.length > 17) {
                valor = valor.substring(0, 17);
            }
            
            // Se tiver mais de 2 dígitos, formata
            if (valor.length > 2) {
                // DDD (2 dígitos) + número (qualquer quantidade)
                const ddd = valor.substring(0, 2);
                const numero = valor.substring(2);
                
                input.value = `(+55)${ddd}-${numero}`;
            } else if (valor.length > 0) {
                // Apenas DDD digitado ou parcial
                input.value = `(+55)${valor}`;
            } else {
                // Campo vazio
                input.value = '';
            }
        }

        // Função para formatar telefone fixo: (+55)XX-XXXXXXXX (aceita qualquer quantidade de dígitos)
        function formatarTelefoneFixo(input) {
            // Remove tudo que não é dígito
            let valor = input.value.replace(/\D/g, '');
            
            // Se começar com 55, remove (já vamos adicionar +55)
            if (valor.startsWith('55')) {
                valor = valor.substring(2);
            }
            
            // Aceita qualquer quantidade de dígitos após o DDD (mínimo 1, máximo 15)
            if (valor.length > 17) {
                valor = valor.substring(0, 17);
            }
            
            // Se tiver mais de 2 dígitos, formata
            if (valor.length > 2) {
                // DDD (2 dígitos) + número (qualquer quantidade)
                const ddd = valor.substring(0, 2);
                const numero = valor.substring(2);
                input.value = `(+55)${ddd}-${numero}`;
            } else if (valor.length > 0) {
                // Apenas DDD digitado
                input.value = `(+55)${valor}`;
            } else {
                // Campo vazio
                input.value = '';
            }
        }

        // Aplicar formatação automática no campo de telefone celular
        const telefoneCelularInput = document.getElementById('telefoneCelular');
        telefoneCelularInput.addEventListener('input', function() {
            const valorAntes = this.value;
            formatarTelefoneCelular(this);
            if (valorAntes !== this.value) {
                logConsole('debug', 'Telefone celular formatado', { antes: valorAntes, depois: this.value });
            }
        });
        
        // Ao colar um valor, formata imediatamente
        telefoneCelularInput.addEventListener('paste', function(e) {
            logConsole('debug', 'Valor colado no campo telefone celular');
            setTimeout(() => {
                formatarTelefoneCelular(this);
                logConsole('debug', 'Telefone celular formatado após colar', { valor: this.value });
            }, 10);
        });
        
        // Ao focar no campo, se estiver vazio, já mostra (+55)
        telefoneCelularInput.addEventListener('focus', function() {
            if (this.value === '') {
                this.value = '(+55)';
                this.setSelectionRange(5, 5); // Posiciona cursor após (+55)
                logConsole('debug', 'Campo telefone celular focado - (+55) adicionado');
            } else if (!this.value.startsWith('(+55)')) {
                // Se já tem algum valor mas não começa com (+55), formata
                formatarTelefoneCelular(this);
            }
        });
        
        // Ao perder o foco, se só tiver (+55) ou formatação incompleta, limpa o campo
        telefoneCelularInput.addEventListener('blur', function() {
            const valor = this.value.replace(/\D/g, '');
            if (valor.length < 3 || this.value === '(+55)' || this.value === '(+55)-') {
                this.value = '';
                logConsole('debug', 'Telefone celular limpo - valor incompleto');
            }
        });

        // Aplicar formatação automática no campo de telefone fixo
        const telefoneFixoInput = document.getElementById('telefoneFixo');
        telefoneFixoInput.addEventListener('input', function() {
            const valorAntes = this.value;
            formatarTelefoneFixo(this);
            if (valorAntes !== this.value) {
                logConsole('debug', 'Telefone fixo formatado', { antes: valorAntes, depois: this.value });
            }
        });
        
        // Ao colar um valor, formata imediatamente
        telefoneFixoInput.addEventListener('paste', function(e) {
            logConsole('debug', 'Valor colado no campo telefone fixo');
            setTimeout(() => {
                formatarTelefoneFixo(this);
                logConsole('debug', 'Telefone fixo formatado após colar', { valor: this.value });
            }, 10);
        });
        
        // Ao focar no campo, se estiver vazio, já mostra (+55)
        telefoneFixoInput.addEventListener('focus', function() {
            if (this.value === '') {
                this.value = '(+55)';
                this.setSelectionRange(5, 5); // Posiciona cursor após (+55)
                logConsole('debug', 'Campo telefone fixo focado - (+55) adicionado');
            } else if (!this.value.startsWith('(+55)')) {
                // Se já tem algum valor mas não começa com (+55), formata
                formatarTelefoneFixo(this);
            }
        });
        
        // Ao perder o foco, se só tiver (+55) ou formatação incompleta, limpa o campo
        telefoneFixoInput.addEventListener('blur', function() {
            const valor = this.value.replace(/\D/g, '');
            if (valor.length < 3 || this.value === '(+55)' || this.value === '(+55)-') {
                this.value = '';
                logConsole('debug', 'Telefone fixo limpo - valor incompleto');
            }
        });

        // =========================================================================
        // LÓGICA MANTIDA DO JS (Limpar Tela, Busca de CEP, Exibição de Toast)
        // =========================================================================

        // Bloco de feedback do PHP (para erros que vieram do servidor)
        <?php if (count($erros) > 0 || $feedback_erro): ?>
            logConsole('error', 'Erros retornados do servidor', {
                erros: <?= json_encode($erros) ?>,
                feedback_erro: '<?= addslashes($feedback_erro ?? '') ?>'
            });
            showFeedback('Ocorreu um problema no cadastro. Por favor, revise os campos e tente novamente.', 'danger');
        <?php elseif ($feedback_sucesso): ?>
            logConsole('success', 'Cadastro realizado com sucesso!', {
                mensagem: '<?= addslashes($feedback_sucesso) ?>'
            });
            // Mensagem de sucesso já está sendo exibida no HTML acima
            console.log('✅ Cadastro realizado com sucesso!'); 
        <?php endif; ?>
        
        // Log quando a página carrega - LOGS BÁSICOS PRIMEIRO
        console.log('🌐 PÁGINA CARREGADA');
        console.log('URL:', window.location.href);
        console.log('Timestamp:', new Date().toISOString());
        console.log('Referrer:', document.referrer);
        
        // Verificar se há erros do PHP
        <?php if (count($erros) > 0): ?>
            console.error('❌ ERROS DO PHP ENCONTRADOS:', <?= json_encode($erros) ?>);
        <?php endif; ?>
        
        <?php if ($feedback_erro): ?>
            console.error('❌ FEEDBACK DE ERRO:', '<?= addslashes($feedback_erro) ?>');
        <?php endif; ?>
        
        <?php if ($feedback_sucesso): ?>
            console.log('✅ FEEDBACK DE SUCESSO:', '<?= addslashes($feedback_sucesso) ?>');
        <?php endif; ?>
        
        logConsole('info', 'Página de cadastro carregada', {
            url: window.location.href,
            timestamp: new Date().toISOString(),
            referrer: document.referrer
        });
        
        console.log('✅ Todos os scripts carregados com sucesso!');
        console.log('📋 Abra o console (F12) para ver os logs detalhados');
        console.log('==========================================');
        console.log('TESTE: Se você vê esta mensagem, o console está funcionando!');
        console.log('==========================================');
        
        // Teste de console - escrever múltiplas vezes para garantir que aparece
        console.log('');
        console.log('🔍 TESTE DE CONSOLE - MENSAGEM 1');
        console.log('🔍 TESTE DE CONSOLE - MENSAGEM 2');
        console.log('🔍 TESTE DE CONSOLE - MENSAGEM 3');
        console.warn('⚠️ Esta é uma mensagem de AVISO (amarelo)');
        console.error('❌ Esta é uma mensagem de ERRO (vermelho)');
        console.info('ℹ️ Esta é uma mensagem de INFO (azul)');
        console.log('');
        console.log('💡 Se você NÃO está vendo estas mensagens:');
        console.log('   1. Verifique se o Console está aberto (F12)');
        console.log('   2. Verifique se está na aba "Console" (não "Elements" ou "Network")');
        console.log('   3. Verifique se há filtros ativos no console');
        console.log('   4. Limpe o console (ícone de lixeira) e recarregue a página');
        console.log('');
        
        // Interceptar erros do navegador
        window.addEventListener('error', function(event) {
            console.error('❌ ERRO JavaScript:', event.message, event.filename, event.lineno);
            logConsole('error', 'Erro JavaScript capturado', {
                mensagem: event.message,
                arquivo: event.filename,
                linha: event.lineno,
                coluna: event.colno,
                erro: event.error
            });
        });
        
        // Interceptar erros de recursos não carregados
        window.addEventListener('unhandledrejection', function(event) {
            console.error('❌ Promise rejeitada:', event.reason);
            logConsole('error', 'Promise rejeitada não tratada', {
                motivo: event.reason,
                promise: event.promise
            });
        });
        
        // Log quando a página está prestes a ser descarregada (antes do submit)
        window.addEventListener('beforeunload', function() {
            console.log('🔄 Página sendo descarregada (enviando formulário)');
            logConsole('info', 'Página sendo descarregada (enviando formulário)');
        });
        
        // Teste final - deve aparecer sempre
        console.log('🎯 TESTE FINAL: Se você vê isso, tudo está funcionando!');
        console.log('📍 Próximo passo: Preencha o formulário e envie para ver mais logs');
        
        // Função para testar console (chamada pelo botão)
        window.testarConsoleLogs = function() {
            console.log('%c════════════════════════════════════════', 'color: #00ff00; font-size: 16px; font-weight: bold;');
            console.log('%c🧪 TESTE DE CONSOLE - BOTÃO PRESSIONADO', 'color: #00ff00; font-size: 16px; font-weight: bold;');
            console.log('%c════════════════════════════════════════', 'color: #00ff00; font-size: 16px; font-weight: bold;');
            console.log('✅ Se você vê esta mensagem, o console ESTÁ FUNCIONANDO!');
            console.log('🕐 Timestamp:', new Date().toLocaleString('pt-BR'));
            console.log('🌐 URL:', window.location.href);
            console.log('📦 Navegador:', navigator.userAgent);
            console.log('✅ Função testarConsoleLogs() executada com sucesso!');
            console.log('%c════════════════════════════════════════', 'color: #00ff00; font-size: 16px; font-weight: bold;');
            
            alert('✅ Teste executado! Verifique o Console (F12) para ver as mensagens.');
        };
        
        console.log('✅ Função testarConsoleLogs() definida. Clique no botão "🧪 Testar Console" para testar.');

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
            const cep = this.value.replace(/\D/g, ''); // Remove tudo que não é dígito
            logConsole('debug', 'Buscando CEP', { cep: cep, tamanho: cep.length, valorOriginal: this.value });
            
            const logradouroInput = document.getElementById('logradouro');
            const bairroInput = document.getElementById('bairro');
            const cidadeInput = document.getElementById('cidade');
            const estadoInput = document.getElementById('estado');
            const numeroInput = document.getElementById('numero');
            const cepInput = document.getElementById('cep');

            // Limpa dados e validação prévios
            logradouroInput.value = '';
            bairroInput.value = '';
            cidadeInput.value = '';
            estadoInput.value = '';
            [logradouroInput, bairroInput, cidadeInput, estadoInput, numeroInput, cepInput].forEach(input => 
                input.classList.remove('is-invalid', 'is-valid')
            );

            // Reseta a propriedade readonly em caso de erro no CEP anterior
            logradouroInput.readOnly = true;
            bairroInput.readOnly = true;
            cidadeInput.readOnly = true;
            estadoInput.readOnly = true;

            // Validação: CEP deve ter 8 dígitos (aceita com ou sem hífen)
            if (cep.length === 0) {
                // CEP vazio - será validado como campo obrigatório
                logConsole('warning', 'CEP vazio', { valor: this.value });
                cepInput.classList.add('is-invalid');
                cepInput.nextElementSibling.textContent = 'CEP é obrigatório.';
            } else if (cep.length === 8) {
                // CEP válido (8 dígitos) - tenta buscar na API
                cepInput.classList.remove('is-invalid');
                cepInput.classList.add('is-valid');
                
                try {
                    logConsole('info', 'Consultando API ViaCEP', { url: `https://viacep.com.br/ws/${cep}/json/` });
                    const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                    const data = await response.json();
                    logConsole('debug', 'Resposta da API ViaCEP', data);

                    if (!data.erro) {
                        // CEP encontrado - preenche automaticamente
                        logradouroInput.value = data.logradouro || '';
                        bairroInput.value = data.bairro || '';
                        cidadeInput.value = data.localidade || '';
                        estadoInput.value = data.uf || '';
                        
                        logradouroInput.readOnly = true; 
                        bairroInput.readOnly = true;
                        cidadeInput.readOnly = true;
                        estadoInput.readOnly = true;
                        numeroInput.focus();
                        
                        logConsole('success', 'Endereço preenchido automaticamente', {
                            logradouro: data.logradouro,
                            bairro: data.bairro,
                            cidade: data.localidade,
                            estado: data.uf
                        });
                    } else {
                        // CEP não encontrado na API, mas formato é válido - permite preenchimento manual
                        logConsole('warning', 'CEP não encontrado na API ViaCEP, mas formato é válido', { cep: cep });
                        logradouroInput.readOnly = false; 
                        bairroInput.readOnly = false;
                        cidadeInput.readOnly = false;
                        estadoInput.readOnly = false;
                        // Não marca como inválido, apenas permite preenchimento manual
                        logradouroInput.focus();
                    }
                } catch (error) {
                    // Erro de conexão/API - formato válido, permite preenchimento manual
                    logConsole('error', 'Erro ao consultar API ViaCEP', { erro: error.message, cep: cep });
                    logradouroInput.readOnly = false; 
                    bairroInput.readOnly = false;
                    cidadeInput.readOnly = false;
                    estadoInput.readOnly = false;
                    // Não marca como inválido se o formato está correto
                    logradouroInput.focus();
                }
            } else {
                // CEP com formato incorreto (não tem 8 dígitos)
                logConsole('error', 'CEP com formato incorreto', { cep: cep, tamanho: cep.length, valorOriginal: this.value });
                cepInput.classList.add('is-invalid');
                cepInput.nextElementSibling.textContent = 'CEP inválido. Deve conter 8 dígitos (com ou sem hífen).';
                logradouroInput.readOnly = false; 
                bairroInput.readOnly = false;
                cidadeInput.readOnly = false;
                estadoInput.readOnly = false;
            }
        });
    </script>
    <script src="javaScript/darkmodee.js"></script>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>

