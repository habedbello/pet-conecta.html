<?php
// Inclui o arquivo de conexão com o banco de dados
require_once 'conexaodb/config.php'; 

// Verifica se a requisição foi feita usando o método POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: /"); 
    exit();
}

try {
    // 1. Coleta e Sanitiza os Dados
    $pet_nome       = $_POST['pet_nome'] ?? '';
    $pet_especie    = $_POST['pet_especie'] ?? '';
    $pet_idade      = (int)($_POST['pet_idade'] ?? 0); // Converte para inteiro
    $pet_sexo       = $_POST['pet_sexo'] ?? '';
    $pet_raca_porte = $_POST['pet_raca_porte'] ?? 'Não informado';
    $castrado       = $_POST['castrado'] ?? '';
    $temperamento   = $_POST['temperamento'] ?? '';
    $motivo_doacao  = $_POST['motivo_doacao'] ?? '';
    $doador_telefone= $_POST['doador_telefone'] ?? '';

    // 2. Preparação da Query SQL com PDO
    $sql = "INSERT INTO doacoes (pet_nome, pet_especie, pet_idade, pet_sexo, pet_raca_porte, castrado, temperamento, motivo_doacao, doador_telefone) 
            VALUES (:pet_nome, :pet_especie, :pet_idade, :pet_sexo, :pet_raca_porte, :castrado, :temperamento, :motivo_doacao, :doador_telefone)";
    
    // 3. Prepara a Declaração
    $stmt = $pdo->prepare($sql);
    
    // 4. Bind dos Parâmetros
    $stmt->bindParam(':pet_nome', $pet_nome, PDO::PARAM_STR);
    $stmt->bindParam(':pet_especie', $pet_especie, PDO::PARAM_STR);
    $stmt->bindParam(':pet_idade', $pet_idade, PDO::PARAM_INT);
    $stmt->bindParam(':pet_sexo', $pet_sexo, PDO::PARAM_STR);
    $stmt->bindParam(':pet_raca_porte', $pet_raca_porte, PDO::PARAM_STR);
    $stmt->bindParam(':castrado', $castrado, PDO::PARAM_STR);
    $stmt->bindParam(':temperamento', $temperamento, PDO::PARAM_STR);
    $stmt->bindParam(':motivo_doacao', $motivo_doacao, PDO::PARAM_STR);
    $stmt->bindParam(':doador_telefone', $doador_telefone, PDO::PARAM_STR);

    // 5. Execução
    $stmt->execute();
    
    // Se a execução foi um sucesso:

    // Opcional: Registrar no log
    if (function_exists('logDebug')) {
        logDebug("Formulário de Doação enviado com sucesso", ['pet_nome' => $pet_nome, 'telefone' => $doador_telefone]);
    }

    // Redireciona para uma página de sucesso
    header("Location: sucesso.php?tipo=doacao");
    exit();

} catch (PDOException $e) {
    // Em caso de erro no banco de dados
    $errorMessage = "Erro ao salvar o formulário de doação: " . $e->getMessage();
    
    if (function_exists('logDebug')) {
        logDebug("ERRO PDO - Formulário de Doação", ['erro' => $errorMessage, 'dados' => $_POST]);
    }

    // Redireciona para uma página de erro
    header("Location: erro.php?mensagem=" . urlencode("Erro ao processar sua doação. Por favor, tente novamente mais tarde."));
    exit();

} catch (Exception $e) {
    // Outros erros
    if (function_exists('logDebug')) {
        logDebug("ERRO Geral - Formulário de Doação", ['erro' => $e->getMessage(), 'dados' => $_POST]);
    }

    header("Location: erro.php?mensagem=" . urlencode("Ocorreu um erro inesperado."));
    exit();
}
$pdo = null;
?>