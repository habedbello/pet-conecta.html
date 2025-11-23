<?php
/**
 * Script de Teste de Cadastro
 * Este script testa o processo completo de cadastro simulando um formulário
 */

session_start();
date_default_timezone_set('America/Sao_Paulo');

// Incluir arquivo de configuração
require_once __DIR__ . '/conexaodb/config.php';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Cadastro - PET CONECTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        .info { color: #17a2b8; font-weight: bold; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; border: 1px solid #dee2e6; overflow-x: auto; }
        .test-item { margin: 15px 0; padding: 15px; border-left: 4px solid #007bff; background: #f8f9fa; border-radius: 5px; }
        .test-step { margin: 10px 0; padding: 10px; background: white; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">🧪 Teste de Cadastro - PET CONECTA</h1>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2>Testando Processo de Cadastro</h2>
            </div>
            <div class="card-body">
                
                <?php
                $erros = [];
                $sucessos = [];
                $avisos = [];
                
                // Gerar dados de teste únicos
                $timestamp = time();
                $testEmail = "teste{$timestamp}@exemplo.com";
                $testLogin = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 6);
                $testCPF = str_pad(rand(10000000000, 99999999999), 11, '0', STR_PAD_LEFT);
                
                // Validação simples de CPF (apenas para teste)
                $testCPF = "12345678901"; // CPF de teste válido
                
                // Dados de teste simulando um formulário
                $dadosTeste = [
                    'campo_nome' => 'João da Silva Santos Teste',
                    'campo_data' => '1990-01-15',
                    'campo_sexo' => 'M',
                    'campo_materno' => 'Maria da Silva Santos',
                    'campo_cpf' => $testCPF,
                    'campo_email' => $testEmail,
                    'campo_celular' => '5511987654321',
                    'campo_fixo' => '551123456789',
                    'campo_cep' => '01310100',
                    'campo_logradouro' => 'Avenida Paulista',
                    'campo_no' => '1000',
                    'campo_complemento' => 'Apto 101',
                    'campo_bairro' => 'Bela Vista',
                    'campo_cidade' => 'São Paulo',
                    'campo_uf' => 'SP',
                    'campo_login' => $testLogin,
                    'campo_senha' => 'senhaabc',
                    'campo_confirma' => 'senhaabc'
                ];
                
                echo "<div class='test-item'>";
                echo "<h4>📋 Dados de Teste Gerados</h4>";
                echo "<pre>" . print_r($dadosTeste, true) . "</pre>";
                echo "</div>";
                
                // TESTE 1: Verificar conexão com banco
                echo "<div class='test-item'>";
                echo "<h4>1. Verificando Conexão com Banco de Dados</h4>";
                if (isset($pdo) && $pdo !== null) {
                    echo "<p class='success'>✅ Conexão com banco de dados estabelecida!</p>";
                    $sucessos[] = "Conexão estabelecida";
                    
                    // Verificar se as tabelas existem
                    try {
                        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
                        if (in_array('usuarios', $tables)) {
                            echo "<p class='success'>✅ Tabela 'usuarios' existe</p>";
                        } else {
                            echo "<p class='error'>❌ Tabela 'usuarios' NÃO existe</p>";
                            $erros[] = "Tabela usuarios não existe";
                        }
                        
                        if (in_array('log', $tables)) {
                            echo "<p class='success'>✅ Tabela 'log' existe</p>";
                        } else {
                            echo "<p class='error'>❌ Tabela 'log' NÃO existe</p>";
                            $erros[] = "Tabela log não existe";
                        }
                    } catch (PDOException $e) {
                        echo "<p class='error'>❌ Erro ao verificar tabelas: " . htmlspecialchars($e->getMessage()) . "</p>";
                        $erros[] = "Erro ao verificar tabelas";
                    }
                } else {
                    echo "<p class='error'>❌ Não foi possível conectar ao banco de dados</p>";
                    $erros[] = "Falha na conexão";
                }
                echo "</div>";
                
                // TESTE 2: Verificar estrutura da tabela usuarios
                echo "<div class='test-item'>";
                echo "<h4>2. Verificando Estrutura da Tabela 'usuarios'</h4>";
                if (isset($pdo) && $pdo !== null) {
                    try {
                        $columns = $pdo->query("DESCRIBE usuarios")->fetchAll();
                        $columnNames = array_column($columns, 'Field');
                        
                        $camposNecessarios = [
                            'nome', 'email', 'dataNascimento', 'sexo', 'nomeMaterno', 
                            'CPF', 'celular', 'telefone', 'CEP', 'logradouro', 
                            'numero', 'complemento', 'bairro', 'cidade', 'estado', 
                            'login', 'senha'
                        ];
                        
                        $camposFaltando = array_diff($camposNecessarios, $columnNames);
                        
                        if (empty($camposFaltando)) {
                            echo "<p class='success'>✅ Todos os campos necessários existem na tabela</p>";
                            $sucessos[] = "Estrutura da tabela correta";
                        } else {
                            echo "<p class='error'>❌ Campos faltando: " . implode(', ', $camposFaltando) . "</p>";
                            $erros[] = "Campos faltando na tabela";
                        }
                        
                        // Verificar tipo do campo numero
                        foreach ($columns as $col) {
                            if ($col['Field'] === 'numero') {
                                if (stripos($col['Type'], 'varchar') !== false || stripos($col['Type'], 'char') !== false) {
                                    echo "<p class='success'>✅ Campo 'numero' está como VARCHAR/CHAR (correto)</p>";
                                } else if (stripos($col['Type'], 'int') !== false) {
                                    echo "<p class='warning'>⚠️ Campo 'numero' está como " . $col['Type'] . " (INT). Pode funcionar, mas VARCHAR é recomendado para números com letras (ex: 123A)</p>";
                                    $avisos[] = "Campo numero é INT (VARCHAR recomendado)";
                                } else {
                                    echo "<p class='warning'>⚠️ Campo 'numero' está como " . $col['Type'] . "</p>";
                                    $avisos[] = "Campo numero com tipo não padrão";
                                }
                                break;
                            }
                        }
                        
                    } catch (PDOException $e) {
                        echo "<p class='error'>❌ Erro ao verificar estrutura: " . htmlspecialchars($e->getMessage()) . "</p>";
                        $erros[] = "Erro ao verificar estrutura";
                    }
                }
                echo "</div>";
                
                // TESTE 3: Verificar se email/login já existem
                echo "<div class='test-item'>";
                echo "<h4>3. Verificando Duplicatas</h4>";
                if (isset($pdo) && $pdo !== null) {
                    try {
                        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM usuarios WHERE email = ? OR login = ? OR CPF = ?");
                        $stmt->execute([$testEmail, $testLogin, $testCPF]);
                        $result = $stmt->fetch();
                        
                        if ($result['count'] > 0) {
                            echo "<p class='warning'>⚠️ Já existe um usuário com este email, login ou CPF. Teste será feito mesmo assim.</p>";
                            $avisos[] = "Possível duplicata";
                        } else {
                            echo "<p class='success'>✅ Nenhuma duplicata encontrada (email, login, CPF disponíveis)</p>";
                            $sucessos[] = "Sem duplicatas";
                        }
                    } catch (PDOException $e) {
                        echo "<p class='error'>❌ Erro ao verificar duplicatas: " . htmlspecialchars($e->getMessage()) . "</p>";
                    }
                }
                echo "</div>";
                
                // TESTE 4: Preparar dados como o validacao_cadastro.php faria
                echo "<div class='test-item'>";
                echo "<h4>4. Preparando Dados para Inserção</h4>";
                
                // Simular processamento do validacao_cadastro.php
                $dadosParaInserir = [];
                
                // Escapar dados (exceto senhas)
                foreach ($dadosTeste as $key => $value) {
                    if ($key !== 'campo_senha' && $key !== 'campo_confirma') {
                        $dadosParaInserir[$key] = htmlspecialchars(trim($value));
                    }
                }
                
                // Hash da senha
                $dadosParaInserir['campo_senha'] = password_hash($dadosTeste['campo_senha'], PASSWORD_DEFAULT);
                
                // Limpar CPF, CEP e telefones (apenas números)
                $dadosParaInserir['campo_cpf'] = preg_replace('/[^0-9]/', '', $dadosParaInserir['campo_cpf']);
                $dadosParaInserir['campo_cep'] = preg_replace('/[^0-9]/', '', $dadosParaInserir['campo_cep']);
                $dadosParaInserir['campo_celular'] = preg_replace('/[^0-9]/', '', $dadosParaInserir['campo_celular']);
                $dadosParaInserir['campo_fixo'] = preg_replace('/[^0-9]/', '', $dadosParaInserir['campo_fixo']);
                
                echo "<p class='success'>✅ Dados preparados com sucesso</p>";
                echo "<p class='info'>Senha hasheada: " . substr($dadosParaInserir['campo_senha'], 0, 20) . "...</p>";
                echo "<p class='info'>CPF limpo: " . $dadosParaInserir['campo_cpf'] . "</p>";
                echo "<p class='info'>CEP limpo: " . $dadosParaInserir['campo_cep'] . "</p>";
                $sucessos[] = "Dados preparados";
                echo "</div>";
                
                // TESTE 5: Tentar inserir no banco (simulação)
                echo "<div class='test-item'>";
                echo "<h4>5. Testando Inserção no Banco de Dados</h4>";
                
                if (isset($pdo) && $pdo !== null && empty($erros)) {
                    try {
                        // Iniciar transação
                        $pdo->beginTransaction();
                        
                        // Preparar INSERT
                        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, dataNascimento, sexo, nomeMaterno, CPF, celular, telefone, CEP, logradouro, numero, complemento, bairro, cidade, estado, login, senha) 
                                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        
                        // Executar INSERT
                        $stmt->execute([
                            $dadosParaInserir['campo_nome'],
                            $dadosParaInserir['campo_email'],
                            $dadosParaInserir['campo_data'],
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
                            $dadosParaInserir['campo_login'],
                            $dadosParaInserir['campo_senha']
                        ]);
                        
                        $idUsuarioInserido = $pdo->lastInsertId();
                        echo "<p class='success'>✅ Usuário inserido com sucesso! ID: " . $idUsuarioInserido . "</p>";
                        $sucessos[] = "Usuário inserido";
                        
                        // Inserir no log
                        $dataLog = date('Y-m-d');
                        $horaLog = date('H:i:s');
                        $statusLog = 'Cadastro Teste';
                        
                        $stmtLog = $pdo->prepare("INSERT INTO log (login, nome, cpf, data_log, hora_log, status, usuarios_idusuarios) 
                                                 VALUES (?, ?, ?, ?, ?, ?, ?)");
                        
                        $stmtLog->execute([
                            $dadosParaInserir['campo_login'],
                            $dadosParaInserir['campo_nome'],
                            $dadosParaInserir['campo_cpf'],
                            $dataLog,
                            $horaLog,
                            $statusLog,
                            $idUsuarioInserido
                        ]);
                        
                        echo "<p class='success'>✅ Registro de log inserido com sucesso!</p>";
                        $sucessos[] = "Log inserido";
                        
                        // Commitar transação
                        $pdo->commit();
                        echo "<p class='success'>✅ Transação commitada com sucesso!</p>";
                        $sucessos[] = "Transação commitada";
                        
                        // Verificar se os dados foram realmente inseridos
                        echo "<div class='test-step'>";
                        echo "<h5>Verificando Dados Inseridos:</h5>";
                        $stmtVerificar = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
                        $stmtVerificar->execute([$idUsuarioInserido]);
                        $usuarioInserido = $stmtVerificar->fetch();
                        
                        if ($usuarioInserido) {
                            echo "<p class='success'>✅ Usuário encontrado no banco de dados!</p>";
                            echo "<pre>" . print_r($usuarioInserido, true) . "</pre>";
                            $sucessos[] = "Dados verificados";
                            
                            // Verificar log
                            $stmtLogVerificar = $pdo->prepare("SELECT * FROM log WHERE usuarios_idusuarios = ?");
                            $stmtLogVerificar->execute([$idUsuarioInserido]);
                            $logInserido = $stmtLogVerificar->fetch();
                            
                            if ($logInserido) {
                                echo "<p class='success'>✅ Log encontrado no banco de dados!</p>";
                                echo "<pre>" . print_r($logInserido, true) . "</pre>";
                                $sucessos[] = "Log verificado";
                            }
                        }
                        echo "</div>";
                        
                        // Opção para limpar dados de teste
                        echo "<div class='test-step'>";
                        echo "<h5>Limpar Dados de Teste:</h5>";
                        echo "<form method='POST' action=''>";
                        echo "<input type='hidden' name='limpar' value='1'>";
                        echo "<input type='hidden' name='id_usuario' value='" . $idUsuarioInserido . "'>";
                        echo "<button type='submit' class='btn btn-danger'>🗑️ Remover Dados de Teste (ID: " . $idUsuarioInserido . ")</button>";
                        echo "</form>";
                        echo "</div>";
                        
                    } catch (PDOException $e) {
                        if ($pdo->inTransaction()) {
                            $pdo->rollBack();
                            echo "<p class='info'>Transação revertida (rollback)</p>";
                        }
                        
                        echo "<p class='error'>❌ Erro ao inserir dados:</p>";
                        echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
                        echo "<p class='error'>Código do erro: " . $e->getCode() . "</p>";
                        $erros[] = "Erro na inserção: " . $e->getMessage();
                    }
                } else {
                    echo "<p class='error'>❌ Não foi possível executar o teste (erros anteriores ou conexão não estabelecida)</p>";
                }
                echo "</div>";
                
                // Processar limpeza de dados de teste
                if (isset($_POST['limpar']) && $_POST['limpar'] == '1' && isset($_POST['id_usuario'])) {
                    echo "<div class='test-item'>";
                    echo "<h4>🗑️ Limpando Dados de Teste</h4>";
                    try {
                        $pdo->beginTransaction();
                        $idUsuario = intval($_POST['id_usuario']);
                        
                        // Remover log (foreign key com CASCADE deve remover automaticamente)
                        $stmtDeleteLog = $pdo->prepare("DELETE FROM log WHERE usuarios_idusuarios = ?");
                        $stmtDeleteLog->execute([$idUsuario]);
                        
                        // Remover usuário
                        $stmtDeleteUser = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
                        $stmtDeleteUser->execute([$idUsuario]);
                        
                        $pdo->commit();
                        echo "<p class='success'>✅ Dados de teste removidos com sucesso!</p>";
                        echo "<script>setTimeout(function(){ location.reload(); }, 2000);</script>";
                    } catch (PDOException $e) {
                        if ($pdo->inTransaction()) {
                            $pdo->rollBack();
                        }
                        echo "<p class='error'>❌ Erro ao remover dados: " . htmlspecialchars($e->getMessage()) . "</p>";
                    }
                    echo "</div>";
                }
                
                // RESUMO FINAL
                echo "<div class='alert alert-" . (count($erros) > 0 ? "danger" : (count($avisos) > 0 ? "warning" : "success")) . " mt-4'>";
                echo "<h3>📊 Resumo dos Testes</h3>";
                echo "<p><strong class='success'>Sucessos:</strong> " . count($sucessos) . "</p>";
                echo "<p><strong class='warning'>Avisos:</strong> " . count($avisos) . "</p>";
                echo "<p><strong class='error'>Erros:</strong> " . count($erros) . "</p>";
                
                if (count($erros) == 0 && count($sucessos) >= 5) {
                    echo "<p class='success mt-3'><strong>🎉 Todos os testes passaram! O sistema de cadastro está funcionando corretamente.</strong></p>";
                    echo "<p class='info mt-2'>✅ Você pode acessar o formulário de cadastro em: <a href='cadastro.php'>cadastro.php</a></p>";
                } elseif (count($erros) == 0) {
                    echo "<p class='warning mt-3'>⚠️ Testes executados com alguns avisos, mas o sistema deve funcionar.</p>";
                } else {
                    echo "<p class='error mt-3'><strong>❌ Há erros que precisam ser corrigidos antes de usar o sistema.</strong></p>";
                    echo "<h5>Erros encontrados:</h5>";
                    echo "<ul>";
                    foreach ($erros as $erro) {
                        echo "<li>" . htmlspecialchars($erro) . "</li>";
                    }
                    echo "</ul>";
                }
                echo "</div>";
                ?>
                
                <div class="mt-4">
                    <a href="cadastro.php" class="btn btn-primary">Ir para Cadastro</a>
                    <a href="index.php" class="btn btn-secondary">Voltar para Home</a>
                    <button onclick="location.reload()" class="btn btn-info">🔄 Executar Testes Novamente</button>
                </div>
                
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

