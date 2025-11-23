<?php
/**
 * Script para Criar Tabelas do Banco de Dados
 * Execute este script uma vez para criar as tabelas necessárias
 */

// Incluir arquivo de configuração
require_once __DIR__ . '/conexaodb/config.php';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Tabelas - PET CONECTA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        .info { color: #17a2b8; font-weight: bold; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 5px; border: 1px solid #dee2e6; overflow-x: auto; }
        .test-item { margin: 15px 0; padding: 15px; border-left: 4px solid #007bff; background: #f8f9fa; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">🏗️ Criar Tabelas do Banco de Dados</h1>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2>Criando Tabelas Necessárias</h2>
            </div>
            <div class="card-body">
                
                <?php
                if (!isset($pdo) || $pdo === null) {
                    echo "<div class='alert alert-danger'>";
                    echo "<h4>❌ Erro de Conexão</h4>";
                    echo "<p>Não foi possível conectar ao banco de dados. Verifique se o MySQL está rodando no XAMPP.</p>";
                    echo "</div>";
                    exit;
                }
                
                $erros = [];
                $sucessos = [];
                
                // SQL para criar tabela usuarios (com estrutura corrigida)
                $sqlUsuarios = "
                CREATE TABLE IF NOT EXISTS `usuarios` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `nome` varchar(80) NOT NULL,
                    `email` varchar(100) NOT NULL,
                    `dataNascimento` date NOT NULL,
                    `sexo` varchar(50) NOT NULL,
                    `nomeMaterno` varchar(80) NOT NULL,
                    `CPF` varchar(11) NOT NULL,
                    `celular` varchar(20) NOT NULL,
                    `telefone` varchar(20) NOT NULL,
                    `CEP` varchar(10) NOT NULL,
                    `logradouro` varchar(100) NOT NULL,
                    `numero` varchar(10) NOT NULL,
                    `complemento` varchar(50) DEFAULT NULL,
                    `bairro` varchar(50) NOT NULL,
                    `cidade` varchar(50) NOT NULL,
                    `estado` varchar(2) NOT NULL,
                    `login` varchar(50) NOT NULL,
                    `senha` varchar(255) NOT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `email` (`email`),
                    UNIQUE KEY `CPF` (`CPF`),
                    UNIQUE KEY `login` (`login`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
                ";
                
                // SQL para criar tabela log
                $sqlLog = "
                CREATE TABLE IF NOT EXISTS `log` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `login` varchar(50) NOT NULL,
                    `nome` varchar(80) NOT NULL,
                    `cpf` varchar(11) NOT NULL,
                    `data_log` date NOT NULL,
                    `hora_log` time NOT NULL,
                    `status` varchar(100) NOT NULL,
                    `usuarios_idusuarios` int(11) NOT NULL,
                    PRIMARY KEY (`id`),
                    KEY `fk_log_usuarios` (`usuarios_idusuarios`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
                ";
                
                // Verificar se as tabelas já existem
                echo "<div class='test-item'>";
                echo "<h4>1. Verificando Tabelas Existentes</h4>";
                try {
                    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
                    echo "<p class='info'>Tabelas encontradas no banco: " . (count($tables) > 0 ? implode(', ', $tables) : 'Nenhuma') . "</p>";
                    
                    if (in_array('usuarios', $tables)) {
                        echo "<p class='warning'>⚠️ Tabela 'usuarios' já existe. Será verificada a estrutura...</p>";
                    } else {
                        echo "<p class='info'>ℹ️ Tabela 'usuarios' não existe. Será criada.</p>";
                    }
                    
                    if (in_array('log', $tables)) {
                        echo "<p class='warning'>⚠️ Tabela 'log' já existe. Será verificada a estrutura...</p>";
                    } else {
                        echo "<p class='info'>ℹ️ Tabela 'log' não existe. Será criada.</p>";
                    }
                } catch (PDOException $e) {
                    echo "<p class='error'>❌ Erro ao verificar tabelas: " . htmlspecialchars($e->getMessage()) . "</p>";
                    $erros[] = "Erro ao verificar tabelas";
                }
                echo "</div>";
                
                // Criar tabela usuarios
                echo "<div class='test-item'>";
                echo "<h4>2. Criando/Verificando Tabela 'usuarios'</h4>";
                try {
                    $pdo->exec($sqlUsuarios);
                    echo "<p class='success'>✅ Tabela 'usuarios' criada/verificada com sucesso!</p>";
                    $sucessos[] = "Tabela usuarios criada";
                    
                    // Verificar estrutura
                    $columns = $pdo->query("DESCRIBE usuarios")->fetchAll();
                    echo "<p class='info'>Campos na tabela usuarios: " . count($columns) . "</p>";
                    
                    // Verificar se campo numero é VARCHAR
                    foreach ($columns as $col) {
                        if ($col['Field'] === 'numero') {
                            if (stripos($col['Type'], 'varchar') !== false) {
                                echo "<p class='success'>✅ Campo 'numero' está como VARCHAR (correto)</p>";
                            } else {
                                echo "<p class='warning'>⚠️ Campo 'numero' está como " . $col['Type'] . ". Tentando alterar para VARCHAR...</p>";
                                try {
                                    $pdo->exec("ALTER TABLE usuarios MODIFY numero VARCHAR(10) NOT NULL");
                                    echo "<p class='success'>✅ Campo 'numero' alterado para VARCHAR com sucesso!</p>";
                                } catch (PDOException $e) {
                                    echo "<p class='error'>❌ Erro ao alterar campo 'numero': " . htmlspecialchars($e->getMessage()) . "</p>";
                                }
                            }
                            break;
                        }
                    }
                    
                    // Verificar índices únicos
                    $indexes = $pdo->query("SHOW INDEX FROM usuarios")->fetchAll();
                    $uniqueIndexes = array_filter($indexes, function($idx) {
                        return $idx['Non_unique'] == 0 && $idx['Key_name'] != 'PRIMARY';
                    });
                    
                    $requiredUnique = ['email', 'CPF', 'login'];
                    $existingUnique = array_map(function($idx) {
                        return $idx['Column_name'];
                    }, $uniqueIndexes);
                    
                    $missingUnique = array_diff($requiredUnique, $existingUnique);
                    
                    if (empty($missingUnique)) {
                        echo "<p class='success'>✅ Todos os índices únicos estão configurados (email, CPF, login)</p>";
                    } else {
                        echo "<p class='warning'>⚠️ Índices únicos faltando: " . implode(', ', $missingUnique) . ". Tentando criar...</p>";
                        foreach ($missingUnique as $field) {
                            try {
                                $pdo->exec("ALTER TABLE usuarios ADD UNIQUE KEY `{$field}` (`{$field}`)");
                                echo "<p class='success'>✅ Índice único '{$field}' criado com sucesso!</p>";
                            } catch (PDOException $e) {
                                echo "<p class='error'>❌ Erro ao criar índice único '{$field}': " . htmlspecialchars($e->getMessage()) . "</p>";
                            }
                        }
                    }
                    
                } catch (PDOException $e) {
                    echo "<p class='error'>❌ Erro ao criar tabela 'usuarios':</p>";
                    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
                    $erros[] = "Erro ao criar tabela usuarios";
                }
                echo "</div>";
                
                // Criar tabela log
                echo "<div class='test-item'>";
                echo "<h4>3. Criando/Verificando Tabela 'log'</h4>";
                try {
                    $pdo->exec($sqlLog);
                    echo "<p class='success'>✅ Tabela 'log' criada/verificada com sucesso!</p>";
                    $sucessos[] = "Tabela log criada";
                    
                    // Verificar se foreign key existe
                    $fkExists = false;
                    try {
                        $fkCheck = $pdo->query("SELECT CONSTRAINT_NAME 
                                                FROM information_schema.KEY_COLUMN_USAGE 
                                                WHERE TABLE_SCHEMA = DATABASE() 
                                                AND TABLE_NAME = 'log' 
                                                AND CONSTRAINT_NAME = 'fk_log_usuarios'")->fetch();
                        $fkExists = ($fkCheck !== false);
                    } catch (PDOException $e) {
                        // Ignorar erro de verificação
                    }
                    
                    if (!$fkExists) {
                        echo "<p class='info'>ℹ️ Foreign key não encontrada. Tentando criar...</p>";
                        try {
                            $pdo->exec("ALTER TABLE `log` 
                                       ADD CONSTRAINT `fk_log_usuarios` 
                                       FOREIGN KEY (`usuarios_idusuarios`) 
                                       REFERENCES `usuarios` (`id`) 
                                       ON DELETE CASCADE ON UPDATE CASCADE");
                            echo "<p class='success'>✅ Foreign key 'fk_log_usuarios' criada com sucesso!</p>";
                            $sucessos[] = "Foreign key criada";
                        } catch (PDOException $e) {
                            echo "<p class='warning'>⚠️ Não foi possível criar a foreign key: " . htmlspecialchars($e->getMessage()) . "</p>";
                            echo "<p class='info'>Isso pode acontecer se a tabela usuarios não existir ou se a foreign key já existir com outro nome.</p>";
                            $avisos[] = "Foreign key não criada";
                        }
                    } else {
                        echo "<p class='success'>✅ Foreign key 'fk_log_usuarios' já existe!</p>";
                    }
                    
                } catch (PDOException $e) {
                    echo "<p class='error'>❌ Erro ao criar tabela 'log':</p>";
                    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
                    $erros[] = "Erro ao criar tabela log";
                }
                echo "</div>";
                
                // Verificação final
                echo "<div class='test-item'>";
                echo "<h4>4. Verificação Final</h4>";
                try {
                    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
                    
                    if (in_array('usuarios', $tables) && in_array('log', $tables)) {
                        echo "<p class='success'>✅ Ambas as tabelas existem no banco de dados!</p>";
                        
                        // Contar registros
                        $countUsuarios = $pdo->query("SELECT COUNT(*) as count FROM usuarios")->fetch()['count'];
                        $countLog = $pdo->query("SELECT COUNT(*) as count FROM log")->fetch()['count'];
                        
                        echo "<p class='info'>Registros na tabela usuarios: " . $countUsuarios . "</p>";
                        echo "<p class='info'>Registros na tabela log: " . $countLog . "</p>";
                        
                        $sucessos[] = "Verificação final concluída";
                    } else {
                        $missing = [];
                        if (!in_array('usuarios', $tables)) $missing[] = 'usuarios';
                        if (!in_array('log', $tables)) $missing[] = 'log';
                        echo "<p class='error'>❌ Tabelas faltando: " . implode(', ', $missing) . "</p>";
                        $erros[] = "Tabelas faltando";
                    }
                } catch (PDOException $e) {
                    echo "<p class='error'>❌ Erro na verificação final: " . htmlspecialchars($e->getMessage()) . "</p>";
                    $erros[] = "Erro na verificação";
                }
                echo "</div>";
                
                // RESUMO
                echo "<div class='alert alert-" . (count($erros) > 0 ? "danger" : "success") . " mt-4'>";
                echo "<h3>📊 Resumo</h3>";
                echo "<p><strong class='success'>Sucessos:</strong> " . count($sucessos) . "</p>";
                echo "<p><strong class='error'>Erros:</strong> " . count($erros) . "</p>";
                
                if (count($erros) == 0) {
                    echo "<p class='success mt-3'><strong>🎉 Tabelas criadas com sucesso! O sistema está pronto para uso.</strong></p>";
                    echo "<p class='info mt-2'>✅ Você pode agora testar o cadastro em: <a href='teste_cadastro.php'>teste_cadastro.php</a></p>";
                    echo "<p class='info'>✅ Ou acessar o formulário de cadastro em: <a href='cadastro.php'>cadastro.php</a></p>";
                } else {
                    echo "<p class='error mt-3'><strong>❌ Houve erros ao criar as tabelas. Verifique os detalhes acima.</strong></p>";
                }
                echo "</div>";
                ?>
                
                <div class="mt-4">
                    <a href="teste_cadastro.php" class="btn btn-primary">Testar Cadastro</a>
                    <a href="cadastro.php" class="btn btn-success">Ir para Cadastro</a>
                    <a href="index.php" class="btn btn-secondary">Voltar para Home</a>
                    <button onclick="location.reload()" class="btn btn-info">🔄 Executar Novamente</button>
                </div>
                
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>







