<!DOCTYPE php>
<php lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saiba mais</title>
    <link rel="stylesheet" href="style.css/saiba-mais.css">
    <link rel="stylesheet" href="style.css/darkmode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <nav class="menu_superior">
            <a  href="index.php"><img src="img/casa-de-animais.png"></a>
            <a href="index.php"  id="navh1"><em>PET CONECTA</em></a>
            
            <ul>
                <li ><a href="index.php">Home</a></li>
                <li ><a href="bemestar.php">Bem-Estar Animal</a></li>
                <li ><a href="adoção.php">Adoção/Doação</a></li>
                <li ><a href="saiba-mais.php">Sobre nós</a></li>
            </ul>
            <div class="login-cadastro" id="cadastro-login">
                <a href="login.php"><button id="btn-login-cadastro" >Login</button></a>
                <a href="cadastro.php"><button id="btn-login-cadastro" >Cadastro</button></a>
            </div>

            <button id="toggle-dark-mode" > ◐ </button>

            <!-- Informações do usuário logado (inicialmente ocultas) -->
            <div id="user-info" class="d-none">
                <span id="logged-in-user"></span>
                <button class="btn btn-sm btn-outline-light ms-2" id="logout-btn">Sair</button>
            </div>
        </nav>
    </header>

    <!-- VLibras Plugin -->
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>

    <main>
        <section id="sobre-nos" class="sobre-nos">
            <div class="container">
                <div class="sobre-nos__cabecalho animate__animated animate__flipInX">
                    <h2>Sobre a <em>PET CONECTA</em></h2>
                    <p class="subtitulo">Conheça nossa história, missão e valores na luta pela causa animal.</p>
                </div>

                <div class="sobre-nos__conteudo">
                    <div class="sobre-nos__historia">
                        <h3>Nossa História</h3>
                        <p>Fundada em nossos corações, a <b>PET CONECTA</b> nasceu da paixão de um grupo de pessoas
                            dedicadas ao bem-estar animal. Inicialmente, nosso trabalho se concentrava em arrecadar
                            fundos para dar alimentos a animais de rua. Com o tempo, percebemos a crescente necessidade de tratar desses
                            animais e dar um lar feliz para eles, com pessoas que irão os amar em nossa comunidade, e assim expandimos nossas ações para
                            incluir adoções, tratamentos, cuidados para esses Pets.</p>
                        <p>Ao longo dos anos, enfrentamos desafios significativos, mas cada animal resgatado, cada lar
                            encontrado e cada vida transformada nos motiva a seguir em frente com ainda mais dedicação e
                            esperança.</p>
                    </div>

                    <div class="sobre-nos__missao-visao">
                        <div class="sobre-nos__missao">
                            <h3>Nossa Missão</h3>
                            <p>Nossa missão é conectar animais resgatados a lares cheios de amor, 
                                promovendoa adoção responsávele o respeito à vida, também conecta-los com pessoas
                                que têm amor a essa causa, podendo assim crescermos em unidade, buscando sempre o melhor
                                para esses pets.</p>
                        </div>
                        <div class="sobre-nos__visao">
                            <h3>Nossa Visão</h3>
                            <p>Nossa visão é um futuro onde esses animais que um dia foram tão maltratados,
                                abandonados, esquecidos, possam enfim viver em um lar feliz. Trabalhamos incansavelmente para
                                construir esse futuro, um passo de cada vez, com a ajuda de nossos voluntários,
                                colaboradores e apoiadores.</p>
                        </div>
                    </div>

                    <div class="sobre-nos__valores">
                        <h3>Nossos Valores</h3>
                        <ul>
                            <li><strong>Amor e Compaixão:</strong> Colocamos o bem-estar animal no centro de todas as
                                nossas ações, agindo com empatia e respeito por cada vida.</li>
                            <li><strong>Responsabilidade:</strong> Promovemos a posse responsável e a conscientização
                                sobre os direitos e necessidades dos animais.</li>
                            <li><strong>Transparência:</strong> Mantemos nossas atividades e finanças de forma clara e
                                aberta para nossos apoiadores e a comunidade.</li>
                            <li><strong>Colaboração:</strong> Acreditamos no poder da união e trabalhamos em parceria
                                com outras organizações, voluntários e a sociedade em geral.</li>
                            <li><strong>Dedicação:</strong> Nosso trabalho é movido pela paixão e pelo comprometimento
                                em fazer a diferença na vida dos animais.</li>
                        </ul>
                    </div>

                    <div class="sobre-nos__equipe">
                        <h3>Nossa Equipe</h3>
                        <p>A <b>PET CONECTA</b> é formada por uma equipe dedicada de voluntários, colaboradores e, em alguns
                            casos, profissionais contratados, todos unidos por um amor incondicional pelos animais. Cada
                            membro contribui com suas habilidades e tempo para tornar nosso trabalho possível.</p>
                    </div>

                    <div class="sobre-nos__agradecimento">
                        <h3>Agradecimento</h3>
                        <p>Nosso trabalho não seria possível sem o apoio fundamental de nossos voluntários, doadores,
                            parceiros e da comunidade em geral. Agradecemos imensamente a cada pessoa que dedica seu
                            tempo, recursos e carinho para ajudar os animais que mais precisam.</p>
                        <p>Juntos, podemos fazer a diferença na vida de muitos animais!</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
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

    <!-- JavaScript Personalizado para funcionalidades gerais e de login/logout -->
    <script src="javaScript/main.js"></script>
    <script src="javaScript/darkmodee.js"></script>

     <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>

    
</body>

</php>