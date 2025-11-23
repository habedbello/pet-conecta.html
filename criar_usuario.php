<?php
/**
 * Script para Criar Usuário de Teste
 * Use este script para criar um usuário diretamente no banco de dados
 */

// Incluir arquivo de configuração
require_once __DIR__ . '/conexaodb/config.php';

// Dados do usuário padrão (você pode alterar aqui)
$dadosUsuario = [
    'nome' => 'Usuário Teste',
    'email' => 'teste@exemplo.com',
    'dataNascimento' => '1990-01-15',
    'sexo' => 'M',
    'nomeMaterno' => 'Silva Teste',
    'CPF' => '12345678901',
    'celular' => '5511987654321',
    'telefone' => '551123456789',
    'CEP' => '01310100',
    'logradouro' => 'Avenida Paulista',
    'numero' => '1000',
    'complemento' => 'Apto 101',
    'bairro' => 'Bela Vista',
    'cidade' => 'São Paulo',
    'estado' => 'SP',
    'login' => 'testea',  // 6 caracteres alfabéticos
    'senha' => 'senhaabc'  // 8 caracteres alfabéticos (apenas letras)
];

// Se receber dados via POST, usar esses dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dadosUsuario = [
        'nome' => trim($_POST['nome'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'dataNascimento' => trim($_POST['dataNascimento'] ?? ''),
        'sexo' => trim($_POST['sexo'] ?? ''),
        'nomeMaterno' => trim($_POST['nomeMaterno'] ?? ''),
        'CPF' => preg_replace('/[^0-9]/', '', $_POST['CPF'] ?? ''),
        'celular' => trim($_POST['celular'] ?? ''),
        'telefone' => trim($_POST['telefone'] ?? ''),
        'CEP' => preg_replace('/[^0-9]/', '', $_POST['CEP'] ?? ''),
        'logradouro' => trim($_POST['logradouro'] ?? ''),
        'numero' => trim($_POST['numero'] ?? ''),
        'complemento' => trim($_POST['complemento'] ?? ''),
        'bairro' => trim($_POST['bairro'] ?? ''),
        'cidade' => trim($_POST['cidade'] ?? ''),
        'estado' => trim($_POST['estado'] ?? ''),
        'login' => trim($_POST['login'] ?? ''),
        'senha' => trim($_POST['senha'] ?? '')
    ];
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Usuário - PET CONECTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        .info { color: #17a2b8; font-weight: bold; }
        .card { max-width: 800px; margin: 20px auto; }
        .credentials-box {
            background: #f8f9fa;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .credentials-box h4 {
            color: #28a745;
            margin-bottom: 15px;
        }
        .credential-item {
            font-family: 'Courier New', monospace;
            font-size: 18px;
            padding: 10px;
            background: white;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4 text-center">👤 Criar Usuário de Teste</h1>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2>Formulário de Criação de Usuário</h2>
            </div>
            <div class="card-body">
                
                <?php
                if (!isset($pdo) || $pdo === null) {
                    echo "<div class='alert alert-danger'>";
                    echo "<h4>❌ Erro de Conexão</h4>";
                    echo "<p>Não foi possível conectar ao banco de dados. Verifique se o MySQL está rodando.</p>";
                    echo "</div>";
                    exit;
                }
                
                // Processar criação do usuário
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['criar'])) {
                    $erros = [];
                    $sucesso = false;
                    $mensagem = '';
                    
                    // Validações básicas
                    if (empty($dadosUsuario['nome'])) $erros[] = "Nome é obrigatório";
                    if (empty($dadosUsuario['email'])) $erros[] = "Email é obrigatório";
                    if (empty($dadosUsuario['login'])) $erros[] = "Login é obrigatório";
                    if (empty($dadosUsuario['senha'])) $erros[] = "Senha é obrigatória";
                    
                    // Validar formato do login (6 caracteres alfabéticos)
                    if (!empty($dadosUsuario['login']) && !preg_match('/^[a-zA-Z]{6}$/', $dadosUsuario['login'])) {
                        $erros[] = "Login deve ter exatamente 6 caracteres alfabéticos";
                    }
                    
                    // Validar formato da senha (8 caracteres alfabéticos)
                    if (!empty($dadosUsuario['senha']) && !preg_match('/^[a-zA-Z]{8}$/', $dadosUsuario['senha'])) {
                        $erros[] = "Senha deve ter exatamente 8 caracteres alfabéticos";
                    }
                    
                    // Validar email
                    if (!empty($dadosUsuario['email']) && !filter_var($dadosUsuario['email'], FILTER_VALIDATE_EMAIL)) {
                        $erros[] = "Email inválido";
                    }
                    
                    if (empty($erros)) {
                        try {
                            // Verificar se login já existe
                            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE login = ?");
                            $stmt->execute([$dadosUsuario['login']]);
                            if ($stmt->fetch()) {
                                $erros[] = "Login já existe. Escolha outro.";
                            }
                            
                            // Verificar se email já existe
                            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
                            $stmt->execute([$dadosUsuario['email']]);
                            if ($stmt->fetch()) {
                                $erros[] = "Email já existe. Escolha outro.";
                            }
                            
                            // Verificar se CPF já existe
                            if (!empty($dadosUsuario['CPF'])) {
                                $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE CPF = ?");
                                $stmt->execute([$dadosUsuario['CPF']]);
                                if ($stmt->fetch()) {
                                    $erros[] = "CPF já existe. Escolha outro.";
                                }
                            }
                            
                            if (empty($erros)) {
                                // Gerar hash da senha
                                $senhaHash = password_hash($dadosUsuario['senha'], PASSWORD_DEFAULT);
                                
                                // Inserir usuário
                                $sql = "INSERT INTO usuarios (
                                    nome, email, dataNascimento, sexo, nomeMaterno, CPF,
                                    celular, telefone, CEP, logradouro, numero, complemento,
                                    bairro, cidade, estado, login, senha
                                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([
                                    $dadosUsuario['nome'],
                                    $dadosUsuario['email'],
                                    $dadosUsuario['dataNascimento'] ?: '1990-01-01',
                                    $dadosUsuario['sexo'] ?: 'M',
                                    $dadosUsuario['nomeMaterno'] ?: 'N/A',
                                    $dadosUsuario['CPF'] ?: '00000000000',
                                    $dadosUsuario['celular'] ?: '',
                                    $dadosUsuario['telefone'] ?: '',
                                    $dadosUsuario['CEP'] ?: '00000000',
                                    $dadosUsuario['logradouro'] ?: 'N/A',
                                    $dadosUsuario['numero'] ?: '0',
                                    $dadosUsuario['complemento'] ?: '',
                                    $dadosUsuario['bairro'] ?: 'N/A',
                                    $dadosUsuario['cidade'] ?: 'N/A',
                                    $dadosUsuario['estado'] ?: 'SP',
                                    $dadosUsuario['login'],
                                    $senhaHash
                                ]);
                                
                                $sucesso = true;
                                $mensagem = "Usuário criado com sucesso!";
                            }
                            
                        } catch (PDOException $e) {
                            $erros[] = "Erro ao criar usuário: " . $e->getMessage();
                        }
                    }
                    
                    // Exibir resultado
                    if ($sucesso) {
                        echo "<div class='alert alert-success'>";
                        echo "<h4>✅ $mensagem</h4>";
                        echo "<div class='credentials-box'>";
                        echo "<h4>🔑 Credenciais de Acesso:</h4>";
                        echo "<div class='credential-item'><strong>Login:</strong> " . htmlspecialchars($dadosUsuario['login']) . "</div>";
                        echo "<div class='credential-item'><strong>Senha:</strong> " . htmlspecialchars($dadosUsuario['senha']) . "</div>";
                        echo "</div>";
                        echo "<p class='info mt-3'>Você pode usar essas credenciais para fazer login em: <a href='login.php'>login.php</a></p>";
                        echo "</div>";
                    } else {
                        echo "<div class='alert alert-danger'>";
                        echo "<h4>❌ Erro ao criar usuário</h4>";
                        echo "<ul>";
                        foreach ($erros as $erro) {
                            echo "<li>" . htmlspecialchars($erro) . "</li>";
                        }
                        echo "</ul>";
                        echo "</div>";
                    }
                }
                ?>
                
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nome" class="form-label">Nome Completo *</label>
                            <input type="text" class="form-control" id="nome" name="nome" 
                                   value="<?= htmlspecialchars($dadosUsuario['nome']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($dadosUsuario['email']) ?>" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="login" class="form-label">Login * (6 letras)</label>
                            <input type="text" class="form-control" id="login" name="login" 
                                   value="<?= htmlspecialchars($dadosUsuario['login']) ?>" 
                                   pattern="[a-zA-Z]{6}" maxlength="6" required
                                   title="Exatamente 6 caracteres alfabéticos">
                            <small class="form-text text-muted">Exemplo: teste, admin, usuario</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="senha" class="form-label">Senha * (8 letras)</label>
                            <input type="text" class="form-control" id="senha" name="senha" 
                                   value="<?= htmlspecialchars($dadosUsuario['senha']) ?>" 
                                   pattern="[a-zA-Z]{8}" maxlength="8" required
                                   title="Exatamente 8 caracteres alfabéticos">
                            <small class="form-text text-muted">Exemplo: senhaabc, password, minhasen</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="CPF" class="form-label">CPF</label>
                            <input type="text" class="form-control" id="CPF" name="CPF" 
                                   value="<?= htmlspecialchars($dadosUsuario['CPF']) ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="dataNascimento" class="form-label">Data de Nascimento</label>
                            <input type="date" class="form-control" id="dataNascimento" name="dataNascimento" 
                                   value="<?= htmlspecialchars($dadosUsuario['dataNascimento']) ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="sexo" class="form-label">Sexo</label>
                            <select class="form-control" id="sexo" name="sexo">
                                <option value="M" <?= $dadosUsuario['sexo'] === 'M' ? 'selected' : '' ?>>Masculino</option>
                                <option value="F" <?= $dadosUsuario['sexo'] === 'F' ? 'selected' : '' ?>>Feminino</option>
                                <option value="O" <?= $dadosUsuario['sexo'] === 'O' ? 'selected' : '' ?>>Outro</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="nomeMaterno" class="form-label">Nome Materno</label>
                            <input type="text" class="form-control" id="nomeMaterno" name="nomeMaterno" 
                                   value="<?= htmlspecialchars($dadosUsuario['nomeMaterno']) ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="celular" class="form-label">Celular</label>
                            <input type="text" class="form-control" id="celular" name="celular" 
                                   value="<?= htmlspecialchars($dadosUsuario['celular']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefone" class="form-label">Telefone Fixo</label>
                            <input type="text" class="form-control" id="telefone" name="telefone" 
                                   value="<?= htmlspecialchars($dadosUsuario['telefone']) ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="CEP" class="form-label">CEP</label>
                            <input type="text" class="form-control" id="CEP" name="CEP" 
                                   value="<?= htmlspecialchars($dadosUsuario['CEP']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="logradouro" class="form-label">Logradouro</label>
                            <input type="text" class="form-control" id="logradouro" name="logradouro" 
                                   value="<?= htmlspecialchars($dadosUsuario['logradouro']) ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="numero" class="form-label">Número</label>
                            <input type="text" class="form-control" id="numero" name="numero" 
                                   value="<?= htmlspecialchars($dadosUsuario['numero']) ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="complemento" class="form-label">Complemento</label>
                            <input type="text" class="form-control" id="complemento" name="complemento" 
                                   value="<?= htmlspecialchars($dadosUsuario['complemento']) ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="bairro" class="form-label">Bairro</label>
                            <input type="text" class="form-control" id="bairro" name="bairro" 
                                   value="<?= htmlspecialchars($dadosUsuario['bairro']) ?>">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="cidade" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="cidade" name="cidade" 
                                   value="<?= htmlspecialchars($dadosUsuario['cidade']) ?>">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <input type="text" class="form-control" id="estado" name="estado" 
                                   value="<?= htmlspecialchars($dadosUsuario['estado']) ?>" maxlength="2">
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="submit" name="criar" class="btn btn-primary btn-lg">Criar Usuário</button>
                        <a href="login.php" class="btn btn-success btn-lg">Ir para Login</a>
                        <a href="index.php" class="btn btn-secondary btn-lg">Voltar para Home</a>
                    </div>
                </form>
                
                <div class="alert alert-info mt-4">
                    <h5>ℹ️ Informações Importantes:</h5>
                    <ul>
                        <li><strong>Login:</strong> Deve ter exatamente <strong>6 caracteres alfabéticos</strong> (sem números ou símbolos)</li>
                        <li><strong>Senha:</strong> Deve ter exatamente <strong>8 caracteres alfabéticos</strong> (sem números ou símbolos)</li>
                        <li>Os campos marcados com * são obrigatórios</li>
                        <li>O sistema verifica se o login, email ou CPF já existem no banco</li>
                    </ul>
                </div>
                
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validação em tempo real do login
        document.getElementById('login').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^a-zA-Z]/g, '');
            if (value.length > 6) value = value.substring(0, 6);
            e.target.value = value;
        });
        
        // Validação em tempo real da senha
        document.getElementById('senha').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^a-zA-Z]/g, '');
            if (value.length > 8) value = value.substring(0, 8);
            e.target.value = value;
        });
    </script>
</body>
</html>

