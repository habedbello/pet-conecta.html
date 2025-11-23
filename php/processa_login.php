<?php
session_start();
// Define o fuso horário (consistente com processa_cadastro.php)
date_default_timezone_set('America/Sao_Paulo');

// O caminho foi ajustado: '../conexaodb/config.php'
// Assume-se que este arquivo está em 'php/', então volta um nível (..) e entra em 'conexaodb/'
include '../conexaodb/config.php'; 

// Funções para registro de LOG e redirecionamento de erro/sucesso
// Simplifica o código principal, garantindo que o log seja sempre escrito.
function logAndRedirect($pdo, $login, $nome, $cpf, $status, $idUsuario, $feedbackType, $feedbackMessage, $redirectPage) {
    // 1. REGISTRA O LOG
    try {
        $dataLog = date('Y-m-d'); 
        $horaLog = date('H:i:s');
        
        // Prepara a instrução SQL para inserir na tabela 'log'
        $stmtLog = $pdo->prepare("INSERT INTO log (login, nome, cpf, data_log, hora_log, status, usuarios_idusuarios) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        // Tenta registrar o log. Se falhar (por exemplo, problema de conexão momentâneo), apenas registra o erro.
        $stmtLog->execute([
            $login,
            $nome,
            $cpf,
            $dataLog,
            $horaLog,
            $status,
            $idUsuario
        ]);
    } catch (PDOException $e) {
        error_log("Falha ao registrar log de login: " . $e->getMessage());
    }

    // 2. REDIRECIONA
    // Armazena o feedback na sessão para ser exibido no login.php
    $_SESSION['feedback_tipo'] = $feedbackType; // 'success' ou 'danger'
    $_SESSION['feedback_mensagem'] = $feedbackMessage;

    // Redireciona para a página de destino
    header("Location: " . $redirectPage); 
    exit();
}

// -------------------------------------------------------------

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // =========================================================================
    // VERIFICAÇÃO DE CONEXÃO COM BANCO DE DADOS
    // =========================================================================
    $dbInfo = [
        'conectado' => false,
        'host' => defined('DB_HOST') ? DB_HOST : 'N/A',
        'database' => defined('DB_NAME') ? DB_NAME : 'N/A',
        'usuario' => defined('DB_USER') ? DB_USER : 'N/A',
        'mensagem' => '',
        'erro' => null,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    // Verifica se $pdo existe e está conectado
    if (isset($pdo) && $pdo !== null) {
        try {
            // Testa a conexão executando uma query simples
            $pdo->query("SELECT 1");
            $dbInfo['conectado'] = true;
            $dbInfo['mensagem'] = 'Conexão estabelecida com sucesso';
            
            // Obtém informações adicionais do banco
            try {
                $dbInfo['versao_mysql'] = $pdo->query("SELECT VERSION()")->fetchColumn();
                $dbInfo['charset'] = $pdo->query("SELECT @@character_set_database")->fetchColumn();
            } catch (Exception $e) {
                // Ignora se não conseguir obter informações extras
            }
        } catch (PDOException $e) {
            $dbInfo['conectado'] = false;
            $dbInfo['mensagem'] = 'Erro ao verificar conexão';
            $dbInfo['erro'] = $e->getMessage();
        }
    } else {
        $dbInfo['conectado'] = false;
        $dbInfo['mensagem'] = 'Objeto PDO não está disponível';
        $dbInfo['erro'] = 'Variável $pdo não foi inicializada';
    }
    
    // Armazena informações do banco na sessão para exibir no console
    $_SESSION['db_info'] = $dbInfo;
    
    // Captura e limpa os dados enviados do formulário
    $login = trim($_POST['loginUsername'] ?? '');
    $senha = trim($_POST['loginPassword'] ?? '');
    
    // Adiciona informações sobre os dados recebidos
    $_SESSION['db_info']['dados_recebidos'] = [
        'login' => $login,
        'senha_length' => strlen($senha), // Não armazena a senha, apenas o tamanho
        'login_length' => strlen($login),
        'login_vazio' => empty($login),
        'senha_vazia' => empty($senha),
        'timestamp' => date('Y-m-d H:i:s')
    ];

    // Validação de preenchimento (primeira barreira)
    if (empty($login) || empty($senha)) {
        $_SESSION['db_info']['validacao'] = [
            'passou' => false,
            'etapa' => 'preenchimento',
            'motivo' => empty($login) ? 'Login vazio' : 'Senha vazia',
            'login_preenchido' => !empty($login),
            'senha_preenchida' => !empty($senha)
        ];
        $_SESSION['feedback_tipo'] = 'danger';
        $_SESSION['feedback_mensagem'] = "Login e/ou Senha estão em formato inválido.";
        header("Location: ../login.php");
        exit();
    }
    
    // Validação de formato (consistente com o cadastro)
    $loginValido = preg_match('/^[a-zA-Z]{6}$/', $login);
    $senhaValida = preg_match('/^[a-zA-Z]{8}$/', $senha);
    
    if (!$loginValido || !$senhaValida) {
        $_SESSION['db_info']['validacao'] = [
            'passou' => false,
            'etapa' => 'formato',
            'motivo' => (!$loginValido ? 'Formato de login inválido' : 'Formato de senha inválido'),
            'login_valido' => $loginValido,
            'senha_valida' => $senhaValida,
            'login_tamanho' => strlen($login),
            'senha_tamanho' => strlen($senha),
            'login_alfabetico' => ctype_alpha($login),
            'senha_alfabetica' => ctype_alpha($senha)
        ];
        $_SESSION['feedback_tipo'] = 'danger';
        $_SESSION['feedback_mensagem'] = "Login e/ou Senha estão em formato inválido.";
        header("Location: ../login.php");
        exit();
    }
    
    // Se chegou aqui, a validação passou
    $_SESSION['db_info']['validacao'] = [
        'passou' => true,
        'etapa' => 'validacao_completa',
        'motivo' => 'Todas as validações passaram'
    ];


    try {
        // Verifica novamente a conexão antes da query
        if (!isset($pdo) || $pdo === null) {
            throw new PDOException("Conexão com banco de dados não disponível");
        }
        
        // Atualiza informações do banco
        $_SESSION['db_info']['query_executada'] = true;
        $_SESSION['db_info']['query_tipo'] = 'SELECT';
        $_SESSION['db_info']['query_tabela'] = 'usuarios';
        $_SESSION['db_info']['query_campo_busca'] = 'login';
        $_SESSION['db_info']['query_valor_busca'] = $login;
        $_SESSION['db_info']['query_timestamp'] = date('Y-m-d H:i:s');
        
        // 1. Busca o usuário no banco de dados pelo login
        // SELECT * É PERIGOSO! Apenas selecione os campos necessários.
        $stmt = $pdo->prepare("SELECT id, nome, cpf, senha FROM usuarios WHERE login = ?");
        $stmt->execute([$login]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Atualiza informações sobre o resultado da query
        $_SESSION['db_info']['query_resultado'] = [
            'linhas_encontradas' => $usuario ? 1 : 0,
            'usuario_encontrado' => $usuario !== false,
            'tem_id' => $usuario ? isset($usuario['id']) : false,
            'tem_nome' => $usuario ? isset($usuario['nome']) : false
        ];

        // Verifica se o usuário foi encontrado
        if ($usuario) {
            
            // 2. Verifica a senha usando o hash (Obrigatório!)
            if (password_verify($senha, $usuario['senha'])) {
                // SUCESSO: Autenticação bem-sucedida!

                // 3. Inicia a sessão de login
                $_SESSION['logado'] = true;
                $_SESSION['id_usuario'] = $usuario['id'];
                $_SESSION['login'] = $login;
                // Armazenar apenas o primeiro nome para uma saudação mais amigável
                $primeiroNome = explode(' ', $usuario['nome'])[0]; 
                $_SESSION['nome'] = $primeiroNome; 
                
                // 4. Registra o log e redireciona
                logAndRedirect(
                    $pdo, 
                    $login, 
                    $usuario['nome'], // Nome completo para o log
                    $usuario['cpf'], 
                    'Login Sucesso', 
                    $usuario['id'],
                    'success',
                    "Bem-vindo(a), " . $primeiroNome . "!",
                    '../index.php' // Redireciona para a home page (volta 1 nível)
                );

            } else {
                // FALHA: Senha incorreta (Usuário existe, mas senha está errada)
                logAndRedirect(
                    $pdo, 
                    $login, 
                    $usuario['nome'], 
                    $usuario['cpf'], 
                    'Login Falha - Senha', 
                    $usuario['id'], 
                    'danger',
                    "Login ou senha inválidos.",
                    '../login.php' // Redireciona de volta para o login (volta 1 nível)
                );
            }

        } else {
            // FALHA: Usuário (Login) não encontrado
            
             logAndRedirect(
                $pdo, 
                $login, 
                'N/A', 
                'N/A', 
                'Login Falha - Usuario', 
                0, // ID 0 para usuário não encontrado
                'danger',
                "Login ou senha inválidos.",
                '../login.php' // Redireciona de volta para o login (volta 1 nível)
            );
        }

    } catch (PDOException $e) {
        // Erro no banco de dados
        error_log("Erro durante o processo de login: " . $e->getMessage());
        
        // Atualiza informações de erro do banco
        $_SESSION['db_info']['conectado'] = false;
        $_SESSION['db_info']['erro'] = $e->getMessage();
        $_SESSION['db_info']['erro_codigo'] = $e->getCode();
        $_SESSION['db_info']['erro_arquivo'] = $e->getFile();
        $_SESSION['db_info']['erro_linha'] = $e->getLine();
        $_SESSION['db_info']['query_executada'] = false;
        $_SESSION['db_info']['query_erro'] = $e->getMessage();
        
        $_SESSION['feedback_tipo'] = 'danger';
        $_SESSION['feedback_mensagem'] = "Erro interno do servidor. Tente novamente mais tarde.";
        header("Location: ../login.php");
        exit();
    }

} else {
    // Acesso direto, redireciona para a página de login
    header("Location: ../login.php");
    exit();
}
?>