<!DOCTYPE html5>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PET CONECTA</title>
    <link rel="stylesheet" href="style.css/index.css">
    <link rel="stylesheet" href="style.css/darkmode.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="php" >

</head>

<body>
    <header>
        <nav class="menu_superior">
            <a class="logo animate__animated animate__zoomIn" href="index.php"><img src="img/casa-de-animais.png"></a>
            <a href="index.php" class="animate__animated animate__zoomIn" id="navh1"><em>PET CONECTA</em></a>
            <ul>
                <li class="animate__animated animate__zoomIn"><a href="index.php">Home</a></li>
                <li class="animate__animated animate__zoomIn"><a href="bemestar.php">Bem-Estar Animal</a></li>
                <li class="animate__animated animate__zoomIn"><a href="adoção.php">Adoção/Doação</a></li>
                <li class="animate__animated animate__zoomIn"><a href="saiba-mais.php">Sobre nós</a></li>
            </ul>
            <div class="login-cadastro" id="cadastro-login">
                <a href="login.php"><button id="btn-login-cadastro"
                        class="btn animate__animated animate__zoomIn">Login</button></a>
                <a href="cadastro.php"><button id="btn-login-cadastro"
                        class="animate__animated animate__zoomIn">Cadastro</button></a>
            </div>
            <!-- ------------------------------BOTÕES DE FUNCIONALIDADE---------------------------------------->
            <button id="toggle-dark-mode" class="btn animate__animated animate__zoomIn"> ◐ </button>

            <!-----------------------------------FINAL DOS BOTÕES----------------------------------------------->

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
        <a href="cadastro.php"><img src="img/carrocel1.png" id="topo-img"></a>
        <div class="baixo-img">
        </div><br><br>

        <!--inicio da parte de doações-->
        <h1 class="doação-h1">Conheça alguns de nossos amiguinhos </h1><br><br>
        <h3 class="doação-h3">clique abaixo para conhecer melhor !</3><br>

            <section class="adoção">
                <div class="img1-adoção">
                    <a href="adoção.php#como-adotar"><img src="img/img1-adoção.png" id="img1"></a>
                </div>
                <div class="img2-adoção">
                    <a href="adoção.php#adote"><img src="img/img2-adoção.png" id="img2"></a>
                </div>
                <div class="img3-adoção">
                    <a href="adoção.php#adote"><img src="img/img3-adoção.png" id="img3"></a>
                </div>
                <div class="img4-adoção">
                    <a href="adoção.php#adote"><img src="img/img4-adoção.png" id="img4"></a>
                </div>
            </section><br><br><br><br>
            <!--final da parte de doações-->


            <section class="sobrenos">
                <div class="doacao">
                    <h1 class="sobrenosh1">Doações</h1>
                    <p><em>A PET CONECTA tem a missão de promover a adoção responsável de todos
                            os animais domésticos e de cativeiro, para que encontrem lares seguros, cheios de amor e
                            carinho,
                            evitando o abandono e garantindo uma vida digna a eles.</em></p><br>

                    <p><em>Com este serviço, também teremos o controle e acompanhamento de animais adotados,
                            a quantidade por regiões, sexo, e idade, dando um panorama real dos animais que encontraram
                            um lar em nosso meio.</em></p>
                    <a href="adoção.php#doe"> <button id="btnsobrenos" type="button">SAIBA MAIS <span
                                class="pata">🐾</span></button></a>
                </div>
                <div class="imgsobrenos">
                    <div class="bordaimg"></div>
                    <img src="img/thumbs.jpg">
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

    <script src="javaScript/darkmodee.js"></script>
    <!-- JavaScript Personalizado para funcionalidades gerais e de login/logout -->
    <script src="javaScript/main.js"></script>
    
     <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>

</body>

</html>