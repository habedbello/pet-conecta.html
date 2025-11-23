<?php
session_start();
?>

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
     <?php include ('navbar.php');?>
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
    
     <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>

</body>

</html>