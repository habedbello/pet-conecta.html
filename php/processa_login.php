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
    
    // Captura e limpa os dados enviados do formulário
    $login = trim($_POST['loginUsername'] ?? '');
    $senha = trim($_POST['loginPassword'] ?? '');

    // Validação de preenchimento (primeira barreira)
    if (empty($login) || empty($senha)) {
        $_SESSION['feedback_tipo'] = 'danger';
        $_SESSION['feedback_mensagem'] = "Por favor, preencha todos os campos.";
        header("Location: ../login.php");
        exit();
    }
    
    // Validação de formato (consistente com o cadastro)
    if (!preg_match('/^[a-zA-Z]{6}$/', $login) || !preg_match('/^[a-zA-Z]{8}$/', $senha)) {
         $_SESSION['feedback_tipo'] = 'danger';
         $_SESSION['feedback_mensagem'] = "Login e/ou Senha estão em formato inválido.";
         header("Location: ../login.php");
         exit();
    }


    try {
        // 1. Busca o usuário no banco de dados pelo login
        // SELECT * É PERIGOSO! Apenas selecione os campos necessários.
        $stmt = $pdo->prepare("SELECT idusuarios, nome, cpf, senha FROM usuarios WHERE login = ?");
        $stmt->execute([$login]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se o usuário foi encontrado
        if ($usuario) {
            
            // 2. Verifica a senha usando o hash (Obrigatório!)
            if (password_verify($senha, $usuario['senha'])) {
                // SUCESSO: Autenticação bem-sucedida!

                // 3. Inicia a sessão de login
                $_SESSION['logado'] = true;
                $_SESSION['id_usuario'] = $usuario['idusuarios'];
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
                    $usuario['idusuarios'],
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
                    $usuario['idusuarios'], 
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