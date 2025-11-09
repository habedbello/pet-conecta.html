<?php
session_start(); // Garante que a sessão seja iniciada para acessar $_SESSION

// Inicializa um array para armazenar os erros de validação
$erros = [];
// Inicializa um array para armazenar os dados válidos ou submetidos (para repopular o formulário)
$dados = [];



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
 * Valida o formato do telefone celular (corresponde à regex do JS: (+55)XX-XXXXXXXXX)
 * O campo no HTML usa (+55)XX-XXXXXXXXX.
 * @param string $celular
 * @return string|null
 */
function validarCelular($celular) {
    // Regex adaptada para o formato do JS: /^\(\+55\)\d{2}-\d{8,9}$/
    // A entrada POST é (XX) XXXXX-XXXX ou (+XX)XX-XXXXX-XXXX, dependendo de como você mascara no front-end.
    // Usando a regex do front-end: /^\(\+55\)\d{2}-\d{8,9}$/
    // Simplificando para 13 dígitos numéricos (DD DDDDDDDDD):
    $celularLimpo = preg_replace('/[^0-9]/', '', $celular);
    if (!preg_match('/^55\d{10,11}$/', $celularLimpo)) {
        return "Telefone celular inválido. Formato esperado: (+55)XX-XXXXXXXXX.";
    }
    return null;
}

/**
 * Valida o formato do telefone fixo (corresponde à regex do JS: (+55)XX-XXXXXXXX)
 * O campo no HTML usa (+55)XX-XXXXXXXX.
 * @param string $fixo
 * @return string|null
 */
function validarFixo($fixo) {
    // Simplificando para 12 dígitos numéricos (DD DDDDDDDD):
    $fixoLimpo = preg_replace('/[^0-9]/', '', $fixo);
    if (!preg_match('/^55\d{10}$/', $fixoLimpo)) {
        return "Telefone fixo inválido. Formato esperado: (+55)XX-XXXXXXXX.";
    }
    return null;
}

/**
 * Valida o formato do CEP (XXXXX-XXX) e consulta o ViaCEP.
 * @param string $cep
 * @return string|null
 */
function validarCEP($cep) {
    if (!preg_match('/^\d{5}-\d{3}$/', $cep)) {
        return "CEP inválido. Formato esperado: XXXXX-XXX.";
    }

    $cepLimpo = preg_replace('/[^0-9]/', '', $cep);
    
    // Consultar a API do ViaCEP
    $url = "https://viacep.com.br/ws/" . $cepLimpo . "/json/";
    
    // Usando cURL para consulta mais robusta
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $resultadoJson = curl_exec($ch);
    curl_close($ch);
    
    $resultado = json_decode($resultadoJson);

    if (isset($resultado->erro) && $resultado->erro) {
        return "CEP não encontrado.";
    }

    // Armazena os dados do ViaCEP para preencher automaticamente
    $GLOBALS['dados']['campo_logradouro'] = $resultado->logradouro ?? '';
    $GLOBALS['dados']['campo_complemento'] = $resultado->complemento ?? '';
    $GLOBALS['dados']['campo_bairro'] = $resultado->bairro ?? '';
    $GLOBALS['dados']['campo_cidade'] = $resultado->localidade ?? '';
    $GLOBALS['dados']['campo_uf'] = $resultado->uf ?? '';

    return null;
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
        
        header("Location: cadastro.php");
        exit();
        
    } else {
        // Se não houver erros, prepara os dados FINAIS para o banco de dados
        
        // 1. Escapa todos os campos (exceto senhas, que serão hash)
        foreach ($_POST as $key => $value) {
            if ($key !== 'campo_senha' && $key !== 'campo_confirma') {
                $dados[$key] = htmlspecialchars(trim($value));
            }
        }
        
        // 2. Hash da senha
        // Use password_hash() para segurança real.
        $dados['campo_senha'] = password_hash($p('campo_senha'), PASSWORD_DEFAULT);
        
        // 3. Garante o formato limpo de CPF e Telefones (para banco de dados)
        $dados['campo_cpf'] = preg_replace('/[^0-9]/', '', $dados['campo_cpf']);
        $dados['campo_cep'] = preg_replace('/[^0-9]/', '', $dados['campo_cep']);
        $dados['campo_celular'] = preg_replace('/[^0-9]/', '', $dados['campo_celular']);
        $dados['campo_fixo'] = preg_replace('/[^0-9]/', '', $dados['campo_fixo']);
        
        // 4. Armazena os dados validados na sessão
        $_SESSION['dados_cadastro'] = $dados;

        // 5. Inclui o arquivo de processamento do cadastro e finaliza
        include 'processa_cadastro.php';
        exit();
    }

} else {
    // Se o acesso não for por POST, redireciona para o formulário
    header("Location: cadastro.php");
    exit();
}
?>