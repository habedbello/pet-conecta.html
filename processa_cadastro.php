<?php
// CORREÇÃO 1: Evita o aviso de "sessão já ativa" verificando se uma sessão já existe.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Sao_Paulo');

// Verifica se os dados validados existem na sessão
if (isset($_SESSION['dados_cadastro'])) {
    
    $dadosParaInserir = $_SESSION['dados_cadastro'];
    
    // CORREÇÃO 2: Verificação e ajuste do caminho de inclusão.
    // O caminho relativo está correto (../ sobe para pet-conecta.html/, 
    // e depois desce para conexaodb/config.php), mas o erro anterior
    // indica que a pasta pet-conecta.html/conexaodb pode não existir 
    // ou o caminho estava com erro de digitação/espaço. 
    // Vamos manter o caminho, mas certificar que não haja espaços.
    include '../conexaodb/config.php'; 
    
    // Limpa os dados da sessão após recuperá-los para evitar reenvio
    unset($_SESSION['dados_cadastro']);

    // Verifica se a conexão PDO foi estabelecida antes de tentar usá-la.
    if (!isset($pdo) || is_null($pdo)) {
        // Loga o erro, pois a inclusão falhou ou config.php não criou $pdo
        error_log("ERRO: Variável PDO não está disponível após inclusão de config.php.");
        $_SESSION['feedback_erro'] = "Erro ao cadastrar. Falha na configuração do servidor (PDO). Tente novamente.";
        header("Location: ../cadastro.php");
        exit();
    }
    
    try {
        // Início da transação para garantir que ambas as inserções (usuário e log) sejam atômicas
        $pdo->beginTransaction();

        
        // 1. INSERÇÃO NA TABELA 'usuarios'
        

        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, dataNascimento, sexo, nomeMaterno, CPF, celular, telefone, CEP, logradouro, numero, complemento, bairro, cidade, estado, login, senha) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // O e-mail, login e senha já estão hash/limpos em validacao_cadastro.php
        $email = $dadosParaInserir['campo_email'];
        $senhaHash = $dadosParaInserir['campo_senha'];
        $login = $dadosParaInserir['campo_login'];

        // Ajuste no formato da data: O input type="date" envia AAAA-MM-DD
        $dataNascimentoDB = $dadosParaInserir['campo_data'];

        $stmt->execute([
            $dadosParaInserir['campo_nome'],
            $email, 
            $dataNascimentoDB,
            $dadosParaInserir['campo_sexo'],
            $dadosParaInserir['campo_materno'],
            $dadosParaInserir['campo_cpf'],
            $dadosParaInserir['campo_celular'],
            $dadosParaInserir['campo_fixo'], // Usando 'campo_fixo' em vez de 'campo_telefone' no INSERT
            $dadosParaInserir['campo_cep'],
            $dadosParaInserir['campo_logradouro'],
            $dadosParaInserir['campo_no'],
            $dadosParaInserir['campo_complemento'],
            $dadosParaInserir['campo_bairro'],
            $dadosParaInserir['campo_cidade'],
            $dadosParaInserir['campo_uf'],
            $login,
            $senhaHash // Senha já está hasheada
        ]);

        $idUsuarioInserido = $pdo->lastInsertId();

        // 2. INSERÇÃO NA TABELA 'log'
        
        $dataLog = date('Y-m-d'); 
        $horaLog = date('H:i:s');
        $cpfLog = $dadosParaInserir['campo_cpf'];
        $statusLog = 'Cadastro Sucesso';

        $stmtLog = $pdo->prepare("INSERT INTO log (login, nome, cpf, data_log, hora_log, status, usuarios_idusuarios) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $stmtLog->execute([
            $login,
            $dadosParaInserir['campo_nome'],
            $cpfLog,
            $dataLog,
            $horaLog,
            $statusLog,
            $idUsuarioInserido // Chave estrangeira
        ]);

        // Se tudo deu certo, comita a transação
        $pdo->commit();

        
        
        // Mensagem de feedback
        $_SESSION['feedback_sucesso'] = "Cadastro de **{$dadosParaInserir['campo_nome']}** realizado com sucesso! Faça login para continuar.";
        
        // Redirecionar para a tela de login ou sucesso
        // Caminho mantido: "../login.php"
        header("Location: ../login.php"); 
        exit();

    } catch (PDOException $e) {
        // Algo deu errado, desfaz a transação
        if (isset($pdo) && $pdo->inTransaction()) { // VERIFICA SE PDO EXISTE antes de tentar rollBack
            $pdo->rollBack();
        }
        
        // Log do erro real (apenas em ambiente de desenvolvimento)
        error_log("Erro no cadastro: " . $e->getMessage());

        // Mensagem de erro para o usuário
        $_SESSION['feedback_erro'] = "Erro ao cadastrar. Ocorreu uma falha no servidor (DB). Tente novamente.";
        
        // Redireciona de volta para o formulário de cadastro (subir 1 nível)
        header("Location: ../cadastro.php");
        exit();
    }

} else {
    // Se não houver dados na sessão, o acesso foi direto ou inválido
    $_SESSION['feedback_erro'] = "Acesso inválido ao processamento de cadastro.";
    header("Location: ../cadastro.php");
    exit();
}
?>