<?php
session_start();
?>

<!DOCTYPE php>
<php lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adote ou Doe um Amigo!</title>
    <link rel="stylesheet" href="style.css/adoção.css">
    <link rel="stylesheet" href="style.css/darkmode.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="header1">
        <?php include ('navbar.php');?>
    </header>

    <!-- VLibras Plugin -->
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>


    <div class="container">
        <header class="header">
            <h1>Onde Corações se Encontram!</h1>
            <p>Sua chance de mudar uma vida para sempre. Adote um amigo ou ajude-nos a cuidar de mais!</p>
            <div class="botoes-acao">
                <a href="#adote" class="botao-adocao">🐾 Quero Adotar!</a>
                <a href="#doe" class="botao-doar">💖 Quero Doar!</a>
            </div>
        </header>

        <section id="adote" class="secao">
            <h2>Nossos Amigos Prontos Para um Lar!</h2>
            <p>Conheça os animaizinhos incríveis que estão aguardando ansiosamente por uma família. Cada um com sua história e muito amor para dar!</p>

            <div class="cartoes-container">
                <div class="cartao">
                    <img src="img/rex.jpeg" alt="Cachorro Alegre">
                    <div class="conteudo-cartao">
                        <h3>Rex</h3>
                        <p>Rex é um labrador dócil e brincalhão de 2 anos, que adora passear e interagir com crianças. Ideal para famílias ativas!</p>
                        <a href="#como-adotar" class="botao-cartao">Saiba Mais e Adote Rex</a>
                    </div>
                </div>

                <div class="cartao">
                    <img src="img/mimi.jpg" alt="Gato Meigo">
                    <div class="conteudo-cartao">
                        <h3>Mimi</h3>
                        <p>Mimi é uma gatinha persa de 1 ano, super carinhosa e tranquila. Perfeita para quem busca um companheiro calmo e amoroso.</p>
                        <a href="#como-adotar" class="botao-cartao">Saiba Mais e Adote Mimi</a>
                    </div>
                </div>

                <div class="cartao">
                    <img src="img/pipoca.avif" alt="Cachorro Filhote">
                    <div class="conteudo-cartao">
                        <h3>Pipoca</h3>
                        <p>Pipoca é uma filhotinha de vira-lata de 4 meses, cheia de energia e curiosidade. Será uma adulta de porte médio.</p>
                        <a href="#como-adotar" class="botao-cartao">Saiba Mais e Adote Pipoca</a>
                    </div>
                </div>

                <div class="cartao">
                    <img src="img/simba.avif" alt="Gato Curioso">
                    <div class="conteudo-cartao">
                        <h3>Simba</h3>
                        <p>Simba é um gato laranja de 3 anos, muito independente, mas adora um carinho. Se dá bem com outros animais.</p>
                        <a href="#como-adotar" class="botao-cartao">Saiba Mais e Adote Simba</a>
                    </div>
                </div>
                </div>

            <div class="como-adotar" id="como-adotar">
                <h2>Como Adotar? É Simples e Rápido!</h2>
                <p>Nós queremos garantir que nossos amigos encontrem o lar perfeito. Siga os passos abaixo para iniciar o processo de adoção:</p>
                <ol>
                    <li>Preencha nosso <strong>Formulário de Adoção Online</strong>, contando um pouco sobre você e o tipo de animal que busca.</li>
                    <li>Nossa equipe entrará em contato para agendar uma <strong>conversa e visita</strong> (presencial ou virtual) para te conhecer melhor.</li>
                    <li>Após a aprovação, você poderá <strong>conhecer o animal</strong> escolhido em nosso abrigo ou em um evento de adoção.</li>
                    <li>Assinatura do <strong>Termo de Adoção Responsável</strong> e o novo membro da família estará pronto para ir para casa!</li>
                </ol>
               <a href="form_doacao.php" target="_blank" class="btn btn-primary"> 🐾 Preencha o Formulário de Adoção Agora!</a>
            </div>
        </section>

        <hr>

        <section id="doe" class="secao">
            <h2>Doe um Anjo de Quatro Patas!</h2>
            <p>Se você não pode mais cuidar do seu bichinho, nós podemos ajudar a encontrar um novo lar amoroso para ele. Sua doação é um ato de amor e responsabilidade.</p>

            <div class="info-doacao">
                <p>Para doar um animal, por favor, considere as seguintes informações:</p>
                <ul>
                    <li>Animais devem estar com a <strong>saúde em dia</strong> e vacinação atualizada (se aplicável).</li>
                    <li>Disponibilidade para fornecer o <strong>histórico completo</strong> do animal (temperamento, hábitos, etc.).</li>
                    <li>Entendimento de que a ONG fará uma <strong>avaliação</strong> para garantir que possuímos recursos para acolhê-lo.</li>
                    <li>Compromisso em auxiliar, se possível, na transição e adaptação do animal ao novo ambiente.</li>
                </ul>
                <p>Cada doação nos ajuda a diminuir o número de animais abandonados e a encontrar famílias que possam oferecer a eles uma vida digna e feliz.</p>
                <a href="form_doacao.php/#form-doacao" target="_blank" class="btn btn-success">💖 Quero Doar um Animal</a>
            </div>
        </section>
    </div>
<footer>
        <div class="footer-content">
            <div class="footer-logo">
                <p>&copy; 2025 PET CONECTA - Conectando Pets e Amantes de Animais</p>
                <div class="footer-contact">
                    <p>Entre em contato:</p>
                    <p>Email: contato@petconecta.com.br</p>
                    <p>Telefone: (XX) XXXXX-XXXX</p>
                </div>
            </div>
        </div>
    </footer>


     <script src="javaScript/darkmodee.js"></script>
    <!-- JavaScript Personalizado para funcionalidades gerais e de login/logout -->
    <script src="javaScript/main.js"></script>

     <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>

    
</body>
</php>