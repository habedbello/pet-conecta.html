<?php
// Função para log detalhado (apenas declara se não existir)
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

// CORREÇÃO 1: Evita o aviso de "sessão já ativa" verificando se uma sessão já existe.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Sao_Paulo');

logDebug("=== PROCESSANDO CADASTRO ===");
logDebug("Sessão iniciada", ['session_id' => session_id()]);

// Verifica se os dados validados existem na sessão
if (isset($_SESSION['dados_cadastro'])) {
    
    logDebug("Dados encontrados na sessão");
    $dadosParaInserir = $_SESSION['dados_cadastro'];
    logDebug("Dados para inserir (chaves)", array_keys($dadosParaInserir));
    
    // CORREÇÃO 2: Caminho correto para config.php usando __DIR__ para garantir o caminho absoluto
    // __DIR__ retorna o diretório onde este arquivo está localizado (raiz do projeto)
    $configPath = __DIR__ . '/conexaodb/config.php';
    if (!file_exists($configPath)) {
        logDebug("ERRO: Arquivo config.php não encontrado", ['caminho' => $configPath]);
        error_log("ERRO: Arquivo config.php não encontrado em: " . $configPath);
        $_SESSION['feedback_erro'] = "Erro ao cadastrar. Arquivo de configuração não encontrado.";
        header("Location: cadastro.php");
        exit();
    }
    logDebug("Incluindo config.php");
    include $configPath; 
    
    // Limpa os dados da sessão após recuperá-los para evitar reenvio
    unset($_SESSION['dados_cadastro']);

    // Verifica se a conexão PDO foi estabelecida antes de tentar usá-la.
    if (!isset($pdo) || is_null($pdo)) {
        // Loga o erro, pois a inclusão falhou ou config.php não criou $pdo
        logDebug("ERRO: Variável PDO não está disponível após inclusão de config.php");
        error_log("ERRO: Variável PDO não está disponível após inclusão de config.php.");
        $_SESSION['feedback_erro'] = "Erro ao cadastrar. Falha na configuração do servidor (PDO). Tente novamente.";
        header("Location: cadastro.php");
        exit();
    }
    
    logDebug("Conexão PDO estabelecida com sucesso");
    
    try {
        // Início da transação para garantir que ambas as inserções (usuário e log) sejam atômicas
        logDebug("Iniciando transação");
        $pdo->beginTransaction();

        
        // 1. INSERÇÃO NA TABELA 'usuarios'
        logDebug("Preparando INSERT na tabela usuarios");

        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, dataNascimento, sexo, nomeMaterno, CPF, celular, telefone, CEP, logradouro, numero, complemento, bairro, cidade, estado, login, senha) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // O e-mail, login e senha já estão hash/limpos em validacao_cadastro.php
        $email = $dadosParaInserir['campo_email'];
        $senhaHash = $dadosParaInserir['campo_senha'];
        $login = $dadosParaInserir['campo_login'];

        // Ajuste no formato da data: O input type="date" envia AAAA-MM-DD
        $dataNascimentoDB = $dadosParaInserir['campo_data'];
        
        // Valores para inserção (sem senha no log)
        $valoresInsercao = [
            $dadosParaInserir['campo_nome'],
            $email, 
            $dataNascimentoDB,
            $dadosParaInserir['campo_sexo'],
            $dadosParaInserir['campo_materno'],
            $dadosParaInserir['campo_cpf'],
            $dadosParaInserir['campo_celular'],
            $dadosParaInserir['campo_fixo'],
            $dadosParaInserir['campo_cep'],
            $dadosParaInserir['campo_logradouro'],
            $dadosParaInserir['campo_no'],
            $dadosParaInserir['campo_complemento'],
            $dadosParaInserir['campo_bairro'],
            $dadosParaInserir['campo_cidade'],
            $dadosParaInserir['campo_uf'],
            $login,
            $senhaHash
        ];
        
        logDebug("Valores para inserção (sem senha)", array_slice($valoresInsercao, 0, -1));
        logDebug("Executando INSERT");

        $stmt->execute($valoresInsercao);

        $idUsuarioInserido = $pdo->lastInsertId();
        logDebug("Usuário inserido com sucesso", ['id' => $idUsuarioInserido]);

        // 2. INSERÇÃO NA TABELA 'log'
        logDebug("Preparando INSERT na tabela log");
        
        $dataLog = date('Y-m-d'); 
        $horaLog = date('H:i:s');
        $cpfLog = $dadosParaInserir['campo_cpf'];
        $statusLog = 'Cadastro Sucesso';

        $stmtLog = $pdo->prepare("INSERT INTO log (login, nome, cpf, data_log, hora_log, status, usuarios_idusuarios) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $valoresLog = [
            $login,
            $dadosParaInserir['campo_nome'],
            $cpfLog,
            $dataLog,
            $horaLog,
            $statusLog,
            $idUsuarioInserido // Chave estrangeira
        ];
        
        logDebug("Valores para log", $valoresLog);
        logDebug("Executando INSERT no log");
        
        $stmtLog->execute($valoresLog);

        // Se tudo deu certo, comita a transação
        logDebug("Commitando transação");
        $pdo->commit();
        logDebug("Transação commitada com sucesso");

        // Limpar qualquer mensagem de erro anterior
        logDebug("Estado da sessão ANTES de limpar erros", [
            'feedback_erro' => $_SESSION['feedback_erro'] ?? 'NÃO DEFINIDO',
            'erros' => isset($_SESSION['erros']) ? count($_SESSION['erros']) : 0,
            'feedback_sucesso' => $_SESSION['feedback_sucesso'] ?? 'NÃO DEFINIDO'
        ]);
        
        unset($_SESSION['feedback_erro']);
        unset($_SESSION['erros']);
        
        logDebug("Erros limpos da sessão");
        
        // Mensagem de feedback de sucesso
        $_SESSION['feedback_sucesso'] = "Cadastro realizado com sucesso!";
        $_SESSION['nome_cadastrado'] = $dadosParaInserir['campo_nome'];
        
        logDebug("Mensagens de sucesso definidas na sessão", [
            'feedback_sucesso' => $_SESSION['feedback_sucesso'],
            'nome_cadastrado' => $_SESSION['nome_cadastrado']
        ]);
        
        logDebug("Estado da sessão ANTES do redirecionamento", [
            'session_id' => session_id(),
            'session_keys' => array_keys($_SESSION),
            'feedback_sucesso' => $_SESSION['feedback_sucesso'] ?? 'NÃO DEFINIDO',
            'feedback_erro' => $_SESSION['feedback_erro'] ?? 'NÃO DEFINIDO',
            'erros' => isset($_SESSION['erros']) ? count($_SESSION['erros']) : 0
        ]);
        
        logDebug("Cadastro realizado com sucesso", [
            'nome' => $dadosParaInserir['campo_nome'],
            'email' => $dadosParaInserir['campo_email'],
            'login' => $dadosParaInserir['campo_login']
        ]);
        logDebug("Redirecionando para cadastro.php para exibir mensagem de sucesso");
        
        // Redirecionar para a página de cadastro para exibir mensagem de sucesso
        header("Location: cadastro.php"); 
        exit();

    } catch (PDOException $e) {
        // Algo deu errado, desfaz a transação
        logDebug("ERRO PDO CAPTURADO", [
            'mensagem' => $e->getMessage(),
            'codigo' => $e->getCode(),
            'arquivo' => $e->getFile(),
            'linha' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        if (isset($pdo) && $pdo->inTransaction()) { // VERIFICA SE PDO EXISTE antes de tentar rollBack
            logDebug("Fazendo rollback da transação");
            $pdo->rollBack();
        }
        
        // Log do erro real (apenas em ambiente de desenvolvimento)
        error_log("Erro no cadastro: " . $e->getMessage());
        error_log("Código do erro: " . $e->getCode());
        error_log("Arquivo: " . $e->getFile() . " Linha: " . $e->getLine());
        error_log("Trace: " . $e->getTraceAsString());

        // Mensagem de erro para o usuário
        $mensagemErro = "Erro ao cadastrar. Ocorreu uma falha no servidor (DB). Tente novamente.";
        
        // Em ambiente de desenvolvimento, mostra mais detalhes
        if (isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1' || strpos($_SERVER['HTTP_HOST'], 'localhost') !== false)) {
            $mensagemErro .= " Detalhes: " . $e->getMessage();
        }
        
        $_SESSION['feedback_erro'] = $mensagemErro;
        
        logDebug("Redirecionando para cadastro.php com erro");
        
        // Redireciona de volta para o formulário de cadastro
        header("Location: cadastro.php");
        exit();
    } catch (Exception $e) {
        // Captura qualquer outro tipo de exceção
        logDebug("ERRO GENÉRICO CAPTURADO", [
            'mensagem' => $e->getMessage(),
            'codigo' => $e->getCode(),
            'arquivo' => $e->getFile(),
            'linha' => $e->getLine(),
            'tipo' => get_class($e),
            'trace' => $e->getTraceAsString()
        ]);
        
        error_log("Erro genérico no cadastro: " . $e->getMessage());
        $_SESSION['feedback_erro'] = "Erro ao cadastrar. Erro inesperado: " . $e->getMessage();
        header("Location: cadastro.php");
        exit();
    }

} else {
    // Se não houver dados na sessão, o acesso foi direto ou inválido
    logDebug("ERRO: Dados não encontrados na sessão", ['session_keys' => array_keys($_SESSION)]);
    $_SESSION['feedback_erro'] = "Acesso inválido ao processamento de cadastro.";
    header("Location: cadastro.php");
    exit();
}
?>