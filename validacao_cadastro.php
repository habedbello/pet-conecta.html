<?php
// Habilitar exibição de erros para debug (remover em produção)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Função para log detalhado (definir ANTES de usar, apenas se não existir)
if (!function_exists('logDebug')) {
    function logDebug($mensagem, $dados = null) {
        $logFile = __DIR__ . '/logs_cadastro.txt';
        // Criar diretório de logs se não existir
        $logDir = dirname($logFile);
        if (!file_exists($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $mensagem";
        if ($dados !== null) {
            $logMessage .= " | Dados: " . print_r($dados, true);
        }
        $logMessage .= "\n";
        @file_put_contents($logFile, $logMessage, FILE_APPEND);
    }
}

// Tratamento de erros fatais
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        logDebug("ERRO FATAL CAPTURADO", [
            'tipo' => $error['type'],
            'mensagem' => $error['message'],
            'arquivo' => $error['file'],
            'linha' => $error['line']
        ]);
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['feedback_erro'] = "Erro fatal no servidor: " . $error['message'];
            header("Location: cadastro.php");
            exit();
        }
    }
});

try {
    session_start(); // Garante que a sessão seja iniciada para acessar $_SESSION
    logDebug("Sessão iniciada com sucesso");
} catch (Exception $e) {
    logDebug("ERRO ao iniciar sessão", ['erro' => $e->getMessage()]);
    die("Erro ao iniciar sessão: " . $e->getMessage());
}

// Inicializa um array para armazenar os erros de validação
$erros = [];
// Inicializa um array para armazenar os dados válidos ou submetidos (para repopular o formulário)
$dados = [];
// Inicializa array global de dados (usado pela função validarCEP)
if (!isset($GLOBALS['dados'])) {
    $GLOBALS['dados'] = [];
}



/**
 * Valida um campo obrigatório.
 * @param string $valor
 * @param string $nomeCampo
 * @return string|null Retorna a mensagem de erro ou null se válido.
 */
function validarCampoObrigatorio($valor, $nomeCampo) {
    if (empty(trim($valor))) {
        return "$nomeCampo é obrigatório.";
    }
    return null;
}

/**
 * Valida o Nome Completo (15 a 80 caracteres alfabéticos + espaços + acentos).
 * Corresponde à regra do JS.
 * @param string $nome
 * @return string|null
 */
function validarNomeCompleto($nome) {
    $nomeLimpo = trim($nome);
    if (!preg_match('/^[a-zA-ZÀ-ú\s]+$/u', $nomeLimpo)) {
        return "Nome completo inválido. Use apenas letras e espaços.";
    }
    if (mb_strlen($nomeLimpo, 'UTF-8') < 15 || mb_strlen($nomeLimpo, 'UTF-8') > 80) {
        return "O nome completo deve ter entre 15 e 80 caracteres alfabéticos.";
    }
    return null;
}

/**
 * Valida a Data de Nascimento e verifica se o usuário tem no mínimo 18 anos.
 * Corresponde à regra do JS.
 * @param string $data (formato YYYY-MM-DD vindo do input 'date')
 * @return string|null
 */
function validarDataNascimento($data) {
    // O input type="date" envia a data no formato YYYY-MM-DD
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
        return "Data de nascimento inválida. Formato esperado: YYYY-MM-DD.";
    }

    $dataNascimento = new DateTime($data);
    $hoje = new DateTime();
    $idade = $dataNascimento->diff($hoje)->y;

    if ($idade < 18) {
        return "Você deve ter no mínimo 18 anos para se cadastrar.";
    }
    
    // Verifica se a data é válida (ex: 30/02)
    if (!checkdate((int)substr($data, 5, 2), (int)substr($data, 8, 2), (int)substr($data, 0, 4))) {
        return "Data de nascimento inválida.";
    }

    return null;
}

/**
 * Valida o formato e a integridade do CPF.
 * @param string $cpf
 * @return string|null
 */
function validarCPF($cpf) {
    // Remove caracteres não numéricos
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);
    if (strlen($cpf) != 11) {
        return "CPF inválido. Deve conter 11 dígitos.";
    }

    // Sequências inválidas
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return "CPF inválido. Sequência de dígitos repetidos.";
    }

    // Lógica completa de validação de dígitos verificadores
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return "CPF inválido.";
        }
    }
    return null;
}

/**
 * Valida o telefone celular - aceita qualquer número formatado
 * Apenas verifica se não está vazio e tem formato básico válido
 * @param string $celular
 * @return string|null
 */
function validarCelular($celular) {
    // Remove todos os caracteres não numéricos para verificar
    $celularLimpo = preg_replace('/[^0-9]/', '', $celular);
    
    // Verifica se está vazio
    if (empty($celularLimpo)) {
        return "Telefone celular é obrigatório.";
    }
    
    // Verifica se tem pelo menos 3 dígitos (DDD mínimo)
    if (strlen($celularLimpo) < 3) {
        return "Telefone celular inválido. Digite o DDD e o número.";
    }
    
    // Aceita qualquer número formatado - sem validações restritivas
    return null;
}

/**
 * Valida o telefone fixo - aceita qualquer número formatado
 * Apenas verifica se não está vazio e tem formato básico válido
 * @param string $fixo
 * @return string|null
 */
function validarFixo($fixo) {
    // Remove todos os caracteres não numéricos para verificar
    $fixoLimpo = preg_replace('/[^0-9]/', '', $fixo);
    
    // Verifica se está vazio
    if (empty($fixoLimpo)) {
        return "Telefone fixo é obrigatório.";
    }
    
    // Verifica se tem pelo menos 3 dígitos (DDD mínimo)
    if (strlen($fixoLimpo) < 3) {
        return "Telefone fixo inválido. Digite o DDD e o número.";
    }
    
    // Aceita qualquer número formatado - sem validações restritivas
    return null;
}

/**
 * Valida o formato do CEP (aceita com ou sem hífen) e consulta o ViaCEP.
 * @param string $cep
 * @return string|null
 */
function validarCEP($cep) {
    try {
        // Remove caracteres não numéricos para validação
        $cepLimpo = preg_replace('/[^0-9]/', '', $cep);
        
        // Verifica se está vazio
        if (empty($cepLimpo)) {
            return "CEP é obrigatório.";
        }
        
        // Verifica se tem 8 dígitos (formato válido de CEP brasileiro)
        if (strlen($cepLimpo) != 8) {
            return "CEP inválido. Deve conter 8 dígitos (com ou sem hífen).";
        }
        
        // Consultar a API do ViaCEP (opcional - não bloqueia se falhar)
        if (function_exists('curl_init')) {
            $url = "https://viacep.com.br/ws/" . $cepLimpo . "/json/";
            
            // Usando cURL para consulta mais robusta
            $ch = @curl_init();
            if ($ch !== false) {
                @curl_setopt($ch, CURLOPT_URL, $url);
                @curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                @curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Timeout de 5 segundos
                @curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                @curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $resultadoJson = @curl_exec($ch);
                $curlError = @curl_error($ch);
                $httpCode = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
                @curl_close($ch);
                
                // Se houver erro de conexão, permite continuar (CEP será validado apenas no formato)
                if ($curlError || $httpCode !== 200) {
                    // Não retorna erro, apenas registra no log se possível
                    if (function_exists('logDebug')) {
                        @logDebug("Erro ao consultar ViaCEP", ['erro' => $curlError, 'cep' => $cepLimpo, 'http_code' => $httpCode]);
                    }
                    // Permite que o usuário continue - CEP válido em formato
                    return null;
                }
                
                // Tenta decodificar o JSON
                $resultado = @json_decode($resultadoJson);
                
                if (json_last_error() === JSON_ERROR_NONE && is_object($resultado)) {
                    if (isset($resultado->erro) && $resultado->erro) {
                        // CEP não encontrado na API, mas formato é válido
                        // Permite continuar, mas não preenche os campos automaticamente
                        if (function_exists('logDebug')) {
                            @logDebug("CEP não encontrado na API ViaCEP", ['cep' => $cepLimpo]);
                        }
                        // Não retorna erro, permite que o usuário preencha manualmente
                    } else {
                        // CEP encontrado - armazena os dados do ViaCEP para preencher automaticamente
                        if (!isset($GLOBALS['dados'])) {
                            $GLOBALS['dados'] = [];
                        }
                        $GLOBALS['dados']['campo_logradouro'] = isset($resultado->logradouro) ? $resultado->logradouro : '';
                        $GLOBALS['dados']['campo_complemento'] = isset($resultado->complemento) ? $resultado->complemento : '';
                        $GLOBALS['dados']['campo_bairro'] = isset($resultado->bairro) ? $resultado->bairro : '';
                        $GLOBALS['dados']['campo_cidade'] = isset($resultado->localidade) ? $resultado->localidade : '';
                        $GLOBALS['dados']['campo_uf'] = isset($resultado->uf) ? $resultado->uf : '';
                    }
                }
            }
        }
        
        return null;
    } catch (Exception $e) {
        // Se houver qualquer erro, apenas valida o formato do CEP
        // Não bloqueia o cadastro
        if (function_exists('logDebug')) {
            @logDebug("Exceção em validarCEP", ['erro' => $e->getMessage(), 'cep' => $cep]);
        }
        return null; // Retorna null para não bloquear o cadastro
    }
}

/**
 * Valida o login (EXATAMENTE 6 caracteres alfabéticos).
 * Corresponde à regra do JS.
 * @param string $login
 * @return string|null
 */
function validarLogin($login) {
    $loginLimpo = trim($login);
    // Deve ter 6 caracteres alfabéticos (a-z, A-Z)
    if (!preg_match('/^[a-zA-Z]{6}$/', $loginLimpo)) {
        return "O login deve ter exatamente 6 caracteres alfabéticos.";
    }
    return null;
}

/**
 * Valida a senha (EXATAMENTE 8 caracteres alfabéticos).
 * Corresponde à regra do JS.
 * @param string $senha
 * @return string|null
 */
function validarSenha($senha) {
    $senhaLimpa = trim($senha);
    // Deve ter 8 caracteres alfabéticos (a-z, A-Z)
    if (!preg_match('/^[a-zA-Z]{8}$/', $senhaLimpa)) {
        return "A senha deve ter exatamente 8 caracteres alfabéticos.";
    }
    return null;
}

/**
 * Valida a confirmação da senha (EXATAMENTE 8 caracteres alfabéticos e coincide com a senha).
 * Corresponde à regra do JS.
 * @param string $senha
 * @param string $confirmacao
 * @return string|null
 */
function validarConfirmacaoSenha($senha, $confirmacao) {
    // Primeiro, verifica se o formato da confirmação está correto
    $erroFormato = validarSenha($confirmacao);
    if ($erroFormato) {
        return $erroFormato; // Reutiliza a mensagem de erro de formato
    }

    // Depois, verifica se as senhas coincidem
    if ($senha !== $confirmacao) {
        return "As senhas não coincidem.";
    }
    return null;
}



// PROCESSAMENTO DO FORMULÁRIO

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        logDebug("=== INÍCIO DO PROCESSAMENTO DO CADASTRO ===");
        logDebug("POST recebido", array_keys($_POST));
    
    // Função auxiliar para obter e limpar o valor do POST
    $p = function($key) {
        return isset($_POST[$key]) ? trim($_POST[$key]) : '';
    };

    // VALIDAÇÃO DOS CAMPOS OBRIGATÓRIOS
    $camposObrigatorios = [
        'campo_nome' => 'Nome Completo',
        'campo_data' => 'Data de Nascimento',
        'campo_sexo' => 'Sexo',
        'campo_materno' => 'Nome Materno',
        'campo_cpf' => 'CPF',
        'campo_email' => 'E-mail', // Adicionei o e-mail, que é obrigatório no seu formulário
        'campo_celular' => 'Telefone Celular',
        'campo_fixo' => 'Telefone Fixo',
        'campo_cep' => 'CEP',
        'campo_logradouro' => 'Logradouro',
        'campo_no' => 'Número',
        'campo_bairro' => 'Bairro',
        'campo_cidade' => 'Cidade',
        'campo_uf' => 'UF',
        'campo_login' => 'Login',
        'campo_senha' => 'Senha',
        'campo_confirma' => 'Confirmar Senha',
    ];

    foreach ($camposObrigatorios as $key => $nomeCampo) {
        $erros[$key] = validarCampoObrigatorio($p($key), $nomeCampo);
    }
    
    
    // VALIDAÇÃO DOS FORMATOS E REGRAS ESPECÍFICAS
    
    
    // Nome Completo e Nome Materno
    if (empty($erros['campo_nome'])) {
        $erros['campo_nome'] = validarNomeCompleto($p('campo_nome'));
    }
    if (empty($erros['campo_materno'])) {
        $erros['campo_materno'] = validarNomeCompleto($p('campo_materno'));
    }
    
    // Data de Nascimento (formato e idade >= 18)
    if (empty($erros['campo_data'])) {
        $erros['campo_data'] = validarDataNascimento($p('campo_data'));
    }

    // CPF
    if (empty($erros['campo_cpf'])) {
        $erros['campo_cpf'] = validarCPF($p('campo_cpf'));
    }
    
    // E-mail (adicionei a validação de formato básica)
    if (empty($erros['campo_email'])) {
        if (!filter_var($p('campo_email'), FILTER_VALIDATE_EMAIL)) {
            $erros['campo_email'] = "Por favor, insira um e-mail válido.";
        }
    }

    // Telefone Celular
    if (empty($erros['campo_celular'])) {
        $erros['campo_celular'] = validarCelular($p('campo_celular'));
    }
    
    // Telefone Fixo
    if (empty($erros['campo_fixo'])) {
        $erros['campo_fixo'] = validarFixo($p('campo_fixo'));
    }

    // CEP e Endereço (executar a validação do CEP primeiro)
    if (empty($erros['campo_cep'])) {
        $erros['campo_cep'] = validarCEP($p('campo_cep'));
    }
    
    
    // Login (6 caracteres alfabéticos)
    if (empty($erros['campo_login'])) {
        $erros['campo_login'] = validarLogin($p('campo_login'));
        // **TODO:** Adicionar aqui a verificação se o login já existe no banco de dados.
    }
    
    // Senha (8 caracteres alfabéticos)
    if (empty($erros['campo_senha'])) {
        $erros['campo_senha'] = validarSenha($p('campo_senha'));
    }
    
    // Confirmação de Senha
    if (empty($erros['campo_confirma'])) {
        $erros['campo_confirma'] = validarConfirmacaoSenha($p('campo_senha'), $p('campo_confirma'));
    }

    
    // PREPARAÇÃO DOS DADOS E VERIFICAÇÃO FINAL DE ERROS

    
    // Filtra para remover entradas nulas/vazias do array de erros
    $erros = array_filter($erros);

    if (!empty($erros)) {
        // Se houver erros, armazena os erros e os dados POST originais (escapados)
        logDebug("ERROS ENCONTRADOS NA VALIDAÇÃO", $erros);
        
        // Escapa e armazena TODOS os dados POST para repopular o formulário (exceto senhas)
        foreach ($_POST as $key => $value) {
            if ($key !== 'campo_senha' && $key !== 'campo_confirma') {
                $dados[$key] = htmlspecialchars($value);
            }
        }
        
        // Garante que os dados de endereço do ViaCEP sejam usados se o CEP foi válido
        // e não causou erro, mesmo que outros campos tenham erros.
        // O $GLOBALS['dados'] foi preenchido na função validarCEP.
        if (empty($erros['campo_cep']) && !empty($GLOBALS['dados']['campo_logradouro'])) {
             // Mescla os dados de endereço do ViaCEP com os dados POST
             $dados = array_merge($dados, $GLOBALS['dados']);
        }

        $_SESSION['erros'] = $erros;
        $_SESSION['dados'] = $dados;
        
        logDebug("Redirecionando para cadastro.php com erros");
        header("Location: cadastro.php");
        exit();
        
    } else {
        // Se não houver erros, prepara os dados FINAIS para o banco de dados
        logDebug("VALIDAÇÃO PASSOU - Preparando dados para inserção");
        
        try {
            // 1. Escapa todos os campos (exceto senhas, que serão hash)
            foreach ($_POST as $key => $value) {
                if ($key !== 'campo_senha' && $key !== 'campo_confirma') {
                    $dados[$key] = htmlspecialchars(trim($value));
                }
            }
            
            // Mescla com dados do ViaCEP se disponíveis
            if (!empty($GLOBALS['dados'])) {
                $dados = array_merge($dados, $GLOBALS['dados']);
            }
            
            // 2. Hash da senha
            // Use password_hash() para segurança real.
            $dados['campo_senha'] = password_hash($p('campo_senha'), PASSWORD_DEFAULT);
            logDebug("Senha hasheada com sucesso");
            
            // 3. Garante o formato limpo de CPF e Telefones (para banco de dados)
            $dados['campo_cpf'] = preg_replace('/[^0-9]/', '', $dados['campo_cpf']);
            $dados['campo_cep'] = preg_replace('/[^0-9]/', '', $dados['campo_cep']);
            $dados['campo_celular'] = preg_replace('/[^0-9]/', '', $dados['campo_celular']);
            $dados['campo_fixo'] = preg_replace('/[^0-9]/', '', $dados['campo_fixo']);
            
            logDebug("Dados limpos e preparados", array_keys($dados));
            logDebug("Dados completos (sem senha)", array_diff_key($dados, ['campo_senha' => '']));
            
            // 4. Armazena os dados validados na sessão
            $_SESSION['dados_cadastro'] = $dados;
            logDebug("Dados armazenados na sessão");

            // 5. Inclui o arquivo de processamento do cadastro e finaliza
            // Usando __DIR__ para garantir que o caminho seja sempre correto
            $processaPath = __DIR__ . '/processa_cadastro.php';
            if (!file_exists($processaPath)) {
                logDebug("ERRO: Arquivo processa_cadastro.php não encontrado", ['caminho' => $processaPath]);
                error_log("ERRO: Arquivo processa_cadastro.php não encontrado em: " . $processaPath);
                $_SESSION['feedback_erro'] = "Erro ao processar cadastro. Arquivo não encontrado.";
                header("Location: cadastro.php");
                exit();
            }
            logDebug("Incluindo processa_cadastro.php");
            include $processaPath;
            exit();
        } catch (Exception $e) {
            logDebug("ERRO ao processar dados", [
                'mensagem' => $e->getMessage(),
                'arquivo' => $e->getFile(),
                'linha' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            $_SESSION['feedback_erro'] = "Erro ao processar cadastro: " . $e->getMessage();
            header("Location: cadastro.php");
            exit();
        }
    }
    
    } catch (Exception $e) {
        logDebug("ERRO GERAL no processamento POST", [
            'mensagem' => $e->getMessage(),
            'arquivo' => $e->getFile(),
            'linha' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        $_SESSION['feedback_erro'] = "Erro ao processar formulário: " . $e->getMessage();
        header("Location: cadastro.php");
        exit();
    }

} else {
    // Se o acesso não for por POST, redireciona para o formulário
    header("Location: cadastro.php");
    exit();
}
?>