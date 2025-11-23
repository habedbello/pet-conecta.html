<?php
// Inclui o arquivo de conexão com o banco de dados
// Se este arquivo (processar-adocao.php) está na raiz e config.php está em conexaodb/, o caminho é:
require_once 'conexaodb/config.php'; 

// Verifica se a requisição foi feita usando o método POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // Redireciona de volta ou mostra uma mensagem de erro
    header("Location: /"); // Redireciona para a página inicial
    exit();
}

try {
    // 1. Coleta e Sanitiza os Dados
    
    $nome           = $_POST['nome'] ?? '';
    $telefone       = $_POST['telefone'] ?? '';
    $email          = $_POST['email'] ?? '';
    $moradia        = $_POST['moradia'] ?? '';
    $concordancia   = $_POST['concordancia'] ?? '';
    $tempo_sozinho  = $_POST['tempo_sozinho'] ?? 0;
    $outros_pets    = $_POST['outros_pets'] ?? 'Nenhum';

    // 2. Preparação da Query SQL com PDO 
    $sql = "INSERT INTO adocoes (nome, telefone, email, moradia, concordancia, tempo_sozinho, outros_pets) 
            VALUES (:nome, :telefone, :email, :moradia, :concordancia, :tempo_sozinho, :outros_pets)";
    
    // 3. Prepara a Declaração
    $stmt = $pdo->prepare($sql);
    
    // 4. Bind dos Parâmetros
    $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
    $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':moradia', $moradia, PDO::PARAM_STR);
    $stmt->bindParam(':concordancia', $concordancia, PDO::PARAM_STR);
    $stmt->bindParam(':tempo_sozinho', $tempo_sozinho, PDO::PARAM_INT);
    $stmt->bindParam(':outros_pets', $outros_pets, PDO::PARAM_STR);

    // 5. Execução
    $stmt->execute();
    
    // Se a execução foi um sucesso:
    
    // Opcional: Registrar no log
    if (function_exists('logDebug')) {
        logDebug("Formulário de Adoção enviado com sucesso", ['nome' => $nome, 'email' => $email]);
    }
    
    // Redireciona para uma página de sucesso
    header("Location: sucesso.php?tipo=adocao");
    exit();

} catch (PDOException $e) {
    // Em caso de erro no banco de dados
    $errorMessage = "Erro ao salvar o formulário de adoção: " . $e->getMessage();
    
    if (function_exists('logDebug')) {
        logDebug("ERRO PDO - Formulário de Adoção", ['erro' => $errorMessage, 'dados' => $_POST]);
    }

    // Redireciona para uma página de erro
    header("Location: erro.php?mensagem=" . urlencode("Erro ao processar sua candidatura. Por favor, tente novamente mais tarde."));
    exit();

} catch (Exception $e) {
    // Outros erros
    if (function_exists('logDebug')) {
        logDebug("ERRO Geral - Formulário de Adoção", ['erro' => $e->getMessage(), 'dados' => $_POST]);
    }

    header("Location: erro.php?mensagem=" . urlencode("Ocorreu um erro inesperado."));
    exit();
}
// Fecha a conexão (opcional, o script termina aqui de qualquer forma)
$pdo = null;
?>