<!DOCTYPE php>
<php lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro</title>
    <!-- Bootstrap CSS para estilização e responsividade -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css/erro.css">
</head>
<body>

    <!-- VLibras Plugin -->
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>


    <main class="container my-5 text-center">
        <!-- Card de erro com estilos do Bootstrap -->
        <div class="card p-4 mx-auto text-white bg-danger" style="max-width: 500px;">
            <i class="fas fa-exclamation-triangle fa-5x mb-3"></i> <!-- Ícone de aviso -->
            <h2 class="card-title mb-3">Ocorreu um Erro!</h2>
            <!-- Mensagem de erro dinâmica, pode ser preenchida via URL -->
            <p class="card-text fs-5" id="errorMessage">Algo inesperado aconteceu. Por favor, tente novamente.</p>
            <!-- Botões para navegação -->
            <a href="javascript:history.back()" class="btn btn-light mt-3">Voltar</a>
            <a href="index.php" class="btn btn-warning mt-2">Ir para a Página Inicial</a>
        </div>
    </main>

    <script>
        // Ao carregar o DOM, verifica se há uma mensagem de erro na URL e a exibe
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const mensagem = urlParams.get('mensagem'); // Obtém o valor do parâmetro 'mensagem'
            if (mensagem) {
                // Decodifica a mensagem da URL e a exibe no elemento 'errorMessage'
                document.getElementById('errorMessage').textContent = decodeURIComponent(mensagem);
            }
        });
    </script>

     <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>

    
</body>
</php>
