<?php
/**
 * Script Automático para Criar Usuário de Teste
 * Este script cria automaticamente um usuário com credenciais padrão
 * Acesse: http://localhost/pet-conecta.html/criar_usuario_auto.php
 */

require_once __DIR__ . '/conexaodb/config.php';

// Credenciais padrão (você pode alterar aqui)
$login = 'testea';  // 6 caracteres alfabéticos
$senha = 'senhaabc';  // 8 caracteres alfabéticos (apenas letras)
$nome = 'Usuário Teste';
$email = 'teste@exemplo.com';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Usuário Automático - PET CONECTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .credentials-box {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 3px solid #28a745;
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
        }
        .credential-item {
            font-family: 'Courier New', monospace;
            font-size: 24px;
            font-weight: bold;
            padding: 15px;
            background: white;
            border-radius: 8px;
            margin: 15px 0;
            color: #155724;
        }
        .success-icon {
            font-size: 64px;
            color: #28a745;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white text-center">
                <h1>👤 Criar Usuário Automático</h1>
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
                
                // Validar formato
                if (!preg_match('/^[a-zA-Z]{6}$/', $login)) {
                    echo "<div class='alert alert-danger'>";
                    echo "<h4>❌ Erro: Login inválido</h4>";
                    echo "<p>O login deve ter exatamente 6 caracteres alfabéticos. Login atual: '$login'</p>";
                    echo "</div>";
                    exit;
                }
                
                if (!preg_match('/^[a-zA-Z]{8}$/', $senha)) {
                    echo "<div class='alert alert-danger'>";
                    echo "<h4>❌ Erro: Senha inválida</h4>";
                    echo "<p>A senha deve ter exatamente 8 caracteres alfabéticos. Senha atual: '$senha'</p>";
                    echo "</div>";
                    exit;
                }
                
                try {
                    // Verificar se usuário já existe
                    $stmt = $pdo->prepare("SELECT id, nome FROM usuarios WHERE login = ?");
                    $stmt->execute([$login]);
                    $usuarioExistente = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($usuarioExistente) {
                        echo "<div class='alert alert-warning'>";
                        echo "<h4>⚠️ Usuário já existe!</h4>";
                        echo "<p>O login <strong>'$login'</strong> já está cadastrado no sistema.</p>";
                        echo "<div class='credentials-box'>";
                        echo "<div class='success-icon'>✅</div>";
                        echo "<h3>🔑 Credenciais Existentes:</h3>";
                        echo "<div class='credential-item'><strong>Login:</strong> $login</div>";
                        echo "<div class='credential-item'><strong>Senha:</strong> (use a senha que você definiu)</div>";
                        echo "<p class='mt-3'><strong>Nome:</strong> " . htmlspecialchars($usuarioExistente['nome']) . "</p>";
                        echo "</div>";
                        echo "<div class='text-center mt-4'>";
                        echo "<a href='login.php' class='btn btn-success btn-lg me-2'>Ir para Login</a>";
                        echo "<a href='criar_usuario.php' class='btn btn-primary btn-lg'>Criar Outro Usuário</a>";
                        echo "</div>";
                    } else {
                        // Gerar hash da senha
                        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                        
                        // Dados completos do usuário
                        $dados = [
                            'nome' => $nome,
                            'email' => $email,
                            'dataNascimento' => '1990-01-15',
                            'sexo' => 'M',
                            'nomeMaterno' => 'Silva',
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
                            'login' => $login,
                            'senha' => $senhaHash
                        ];
                        
                        // Inserir usuário
                        $sql = "INSERT INTO usuarios (
                            nome, email, dataNascimento, sexo, nomeMaterno, CPF,
                            celular, telefone, CEP, logradouro, numero, complemento,
                            bairro, cidade, estado, login, senha
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                            $dados['nome'],
                            $dados['email'],
                            $dados['dataNascimento'],
                            $dados['sexo'],
                            $dados['nomeMaterno'],
                            $dados['CPF'],
                            $dados['celular'],
                            $dados['telefone'],
                            $dados['CEP'],
                            $dados['logradouro'],
                            $dados['numero'],
                            $dados['complemento'],
                            $dados['bairro'],
                            $dados['cidade'],
                            $dados['estado'],
                            $dados['login'],
                            $dados['senha']
                        ]);
                        
                        $idUsuario = $pdo->lastInsertId();
                        
                        echo "<div class='alert alert-success'>";
                        echo "<h4>✅ Usuário criado com sucesso!</h4>";
                        echo "<p>ID do usuário: <strong>$idUsuario</strong></p>";
                        echo "</div>";
                        
                        echo "<div class='credentials-box'>";
                        echo "<div class='success-icon'>🎉</div>";
                        echo "<h3>🔑 Suas Credenciais de Acesso:</h3>";
                        echo "<div class='credential-item'>";
                        echo "<strong>Login:</strong> <span style='color: #155724;'>$login</span>";
                        echo "</div>";
                        echo "<div class='credential-item'>";
                        echo "<strong>Senha:</strong> <span style='color: #155724;'>$senha</span>";
                        echo "</div>";
                        echo "<p class='mt-4'><strong>Nome:</strong> $nome</p>";
                        echo "<p><strong>Email:</strong> $email</p>";
                        echo "</div>";
                        
                        echo "<div class='alert alert-info'>";
                        echo "<h5>📝 Próximos Passos:</h5>";
                        echo "<ol class='text-start'>";
                        echo "<li>Anote suas credenciais acima</li>";
                        echo "<li>Clique no botão abaixo para ir para a página de login</li>";
                        echo "<li>Use o Login: <strong>$login</strong> e Senha: <strong>$senha</strong></li>";
                        echo "</ol>";
                        echo "</div>";
                        
                        echo "<div class='text-center mt-4'>";
                        echo "<a href='login.php' class='btn btn-success btn-lg me-2'>🚀 Ir para Login</a>";
                        echo "<a href='criar_usuario.php' class='btn btn-primary btn-lg'>Criar Outro Usuário</a>";
                        echo "</div>";
                    }
                    
                } catch (PDOException $e) {
                    echo "<div class='alert alert-danger'>";
                    echo "<h4>❌ Erro ao criar usuário</h4>";
                    echo "<p><strong>Erro:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
                    
                    // Verificar se é erro de duplicata
                    if ($e->getCode() == 23000) {
                        echo "<p class='mt-3'><strong>Possível causa:</strong> Login, Email ou CPF já existe no banco de dados.</p>";
                        echo "<p>Use o formulário completo em <a href='criar_usuario.php'>criar_usuario.php</a> para criar um usuário com dados diferentes.</p>";
                    }
                    echo "</div>";
                }
                ?>
                
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-body">
                <h5>ℹ️ Informações:</h5>
                <ul>
                    <li>Este script cria automaticamente um usuário com as credenciais padrão</li>
                    <li>Se o usuário já existir, ele mostrará as credenciais existentes</li>
                    <li>Para criar um usuário personalizado, use: <a href="criar_usuario.php">criar_usuario.php</a></li>
                    <li><strong>Login padrão:</strong> <?= htmlspecialchars($login) ?> (6 letras)</li>
                    <li><strong>Senha padrão:</strong> <?= htmlspecialchars($senha) ?> (8 letras)</li>
                </ul>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

