<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sucesso!</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center">
    <?php 
    $tipo = $_GET['tipo'] ?? 'envio';
    if ($tipo === 'adocao') {
        $titulo = "Candidatura Enviada! 🐾";
        $mensagem = "Obrigado por iniciar o processo de adoção. Nossa equipe entrará em contato em breve para dar continuidade.";
        $cor = "green";
    } elseif ($tipo === 'doacao') {
        $titulo = "Doação Registrada! 💖";
        $mensagem = "Agradecemos seu ato de responsabilidade. Entraremos em contato para avaliar o acolhimento do seu pet.";
        $cor = "pink";
    } else {
        $titulo = "Formulário Enviado!";
        $mensagem = "Seu formulário foi processado com sucesso.";
        $cor = "indigo";
    }
    ?>
    <div class="max-w-md w-full p-8 bg-white dark:bg-gray-800 shadow-2xl rounded-xl border-t-8 border-<?php echo $cor; ?>-500 text-center">
        <h1 class="text-3xl font-bold mb-4 text-<?php echo $cor; ?>-600 dark:text-<?php echo $cor; ?>-400"><?php echo $titulo; ?></h1>
        <p class="mb-6 text-gray-700 dark:text-gray-300"><?php echo $mensagem; ?></p>
        <a href="/" class="px-6 py-3 bg-<?php echo $cor; ?>-600 hover:bg-<?php echo $cor; ?>-700 text-white font-semibold rounded-lg shadow-lg transition">Voltar para a Página Inicial</a>
    </div>
</body>
</html>