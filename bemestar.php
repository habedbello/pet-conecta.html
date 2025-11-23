<?php
session_start();
?>

<!DOCTYPE php>
<php lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saúde e Bem-Estar Animal</title>
    <link rel="stylesheet" href="style.css/bemestar.css">
    <link rel="stylesheet" href="style.css/darkmode.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
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
            <h1>Bem-Estar Animal: Seu Guia Completo!</h1>
            <p>Descubra tudo sobre a saúde e os cuidados que seu companheiro merece. Dicas, precauções e curiosidades para uma vida longa e feliz!</p>
        </header>

        <section id="dicas-cuidados" class="secao">
            <h2>Dicas Essenciais para o Dia a Dia</h2>
            <p>Pequenas atitudes fazem uma grande diferença na vida do seu pet!</p>

            <div class="secao-conteudo">
                <div class="cartao-info">
                    <img src="img/comida de cachorro.avif" alt="Comida de cachorro em tigela">
                    <div class="conteudo-cartao">
                        <h3>Alimentação Equilibrada</h3>
                        <p>A nutrição é a base da saúde! Ofereça ração de qualidade e na quantidade certa para a idade, porte e nível de atividade do seu animal. Evite alimentos humanos que podem ser tóxicos.</p>
                        <a href="https://blog.popularpet.com.br/alimentacao-saudavel-para-seu-pet/" target="_blank" class="link-saiba-mais">Ver Mais Dicas de Alimentação</a>
                    </div>
                </div>

                <div class="cartao-info">
                    <img src="img/cachorro brincando.webp" alt="Cachorro brincando ">
                    <div class="conteudo-cartao">
                        <h3>Atividade Física Regular</h3>
                        <p>Brincadeiras e passeios são vitais para a saúde física e mental. Ajuda a controlar o peso, fortalece os músculos e alivia o estresse. Adapte a atividade à raça e idade.</p>
                        <a href="https://vetpopular.com.br/impacto-da-atividade-fisica-regular-na-saude-de-animais-de-estimacao/" target="_blank" class="link-saiba-mais">Ideias de Brincadeiras e Exercícios</a>
                    </div>
                </div>

                <div class="cartao-info">
                    <img src="img/banho cachorro.avif" alt="banho em cachorro">
                    <div class="conteudo-cartao">
                        <h3>Higiene e Bem-Estar</h3>
                        <p>Banhos, escovação de pelos e dentes, e corte de unhas são cruciais para prevenir doenças e manter seu pet confortável. Consulte um veterinário para a frequência ideal.</p>
                        <a href="https://panelacopet.com.br/blog/guia-de-higiene-para-pets-e-cuidados-com-as-patinhas-no-verao" target="_blank" class="link-saiba-mais">Guia Completo de Higiene</a>
                    </div>
                </div>

                <div class="cartao-info">
                    <img src="img/veterinario gato.jpg" alt="Veterinário gato">
                    <div class="conteudo-cartao">
                        <h3>Check-ups Veterinários</h3>
                        <p>Visitas regulares ao veterinário são indispensáveis para vacinação, vermifugação e detecção precoce de problemas. Não espere seu pet ficar doente para levá-lo!</p>
                        <a href="https://blog.popularpet.com.br/hora-do-check-up-cuidando-do-seu-pet/" target="_blank" class="link-saiba-mais">Importância da Prevenção</a>
                    </div>
                </div>

                <div class="cartao-info">
                    <img src="img/area animais.png" alt="Área para animais de estimação">
                    <div class="conteudo-cartao">
                        <h3>Ambiente Seguro e Confortável</h3>
                        <p>Certifique-se de que sua casa seja um local seguro para seu amigo, livre de objetos perigosos, plantas tóxicas e produtos químicos. Ofereça um local de descanso tranquilo.</p>
                        <a href="https://emergencialvet.com.br/ambientes-pet-friendly-criando-um-espaco-seguro-e-confortavel-para-os-pets-em-casa/" target="_blank" class="link-saiba-mais">Dicas para um Lar Pet-Friendly</a>
                    </div>
                </div>

                <div class="cartao-info">
                    <img src="img/amor com animais.webp" alt="amor animal">
                    <div class="conteudo-cartao">
                        <h3>Amor, Atenção e Carinho</h3>
                        <p>O bem-estar emocional é tão importante quanto o físico. Dedique tempo para brincar, fazer carinho e treinar seu pet. Isso fortalece o vínculo e previne problemas comportamentais.</p>
                        <a href="https://panelacopet.com.br/blog/terapia-do-carinho" target="_blank" class="link-saiba-mais">Construa um Vínculo Forte</a>
                    </div>
                </div>
            </div>
        </section>

        <hr>

        <section id="precaucoes" class="secao">
            <h2>Fique de Olho! Precauções Importantes</h2>
            <p>Saber identificar sinais de alerta e tomar precauções pode salvar uma vida!</p>

            <div class="secao-conteudo">
                <div class="cartao-info">
                    <img src="img/cachorro com dor.avif" alt="Cachorro com dor">
                    <div class="conteudo-cartao">
                        <h3>Sinais de Intoxicação</h3>
                        <p>Vômitos, diarreia, tremores, letargia ou salivação excessiva podem indicar intoxicação. Tenha sempre à mão o telefone de emergência veterinária e identifique plantas tóxicas em casa.</p>
                        <a href="https://doctor.vet.br/entenda-o-que-fazer-quando-o-animal-tem-uma-intoxicacao/" target="_blank" class="link-saiba-mais">O que fazer em caso de Intoxicação</a>
                    </div>
                </div>

                <div class="cartao-info">
                    <img src="img/cachorro pulgas.jpg" alt="Coleira pulgas">
                    <div class="conteudo-cartao">
                        <h3>Combate a Pulgas e Carrapatos</h3>
                        <p>Esses parasitas causam coceira, irritação e transmitem doenças graves. Use produtos antiparasitários recomendados pelo veterinário e inspecione o pelo do seu pet regularmente.</p>
                        <a href="https://www.petz.com.br/blog/como-evitar-pulgas-e-carrapatos/" target="_blank" class="link-saiba-mais">Melhores Tratamentos e Prevenção</a>
                    </div>
                </div>

                <div class="cartao-info">
                    <img src="img/primeiros socorros.jpeg" alt="Kit de primeiros socorros para animais">
                    <div class="conteudo-cartao">
                        <h3>Kit de Primeiros Socorros</h3>
                        <p>Tenha um kit básico de primeiros socorros para emergências, incluindo gaze, esparadrapo, soro fisiológico e antisséptico suave. Saiba o básico para agir em acidentes.</p>
                        <a href="https://www.vetprofissional.com.br/artigos/kit-de-primeiros-socorros-para-caes-e-gatos-como-montar" target="_blank" class="link-saiba-mais">Saiba como montar e como usar</a>
                    </div>
                </div>
            </div>
        </section>

        <hr>

        <section id="curiosidades" class="secao">
            <h2>Curiosidades Fascinantes do Mundo Animal</h2>
            <p>O universo dos nossos amigos é cheio de surpresas!</p>

            <div class="curiosidades">
                <div class="item-curiosidade">
                    <span class="icone">🧠</span>
                    <div>
                        <h3>Cães Sonham como Nós?</h3>
                        <p>Sim! Estudos mostram que cães, assim como humanos, passam por fases de sono REM e podem sonhar. Você já viu seu pet mexer as patinhas enquanto dorme? Provavelmente ele está sonhando com uma corrida no parque!</p>
                    </div>
                </div>
                <div class="item-curiosidade">
                    <span class="icone">👅</span>
                    <div>
                        <h3>A Língua Áspera dos Gatos</h3>
                        <p>A língua dos gatos é coberta por pequenas espículas chamadas papilas, que agem como um pente. É por isso que eles são tão eficazes na auto-limpeza e na remoção de pelos mortos. É quase uma escova natural!</p>
                    </div>
                </div>
                <div class="item-curiosidade">
                    <span class="icone">👃</span>
                    <div>
                        <h3>O Super Olfato Canino</h3>
                        <p>O olfato de um cão é milhares de vezes mais apurado que o nosso! Eles possuem até 300 milhões de receptores olfativos, enquanto nós temos apenas 6 milhões. É por isso que são usados para farejar drogas, pessoas e até doenças.</p>
                    </div>
                </div>
                <div class="item-curiosidade">
                    <span class="icone">👂</span>
                    <div>
                        <h3>Ouvido Seletivo dos Gatos</h3>
                        <p>Gatos podem mover suas orelhas 180 graus e direcioná-las para a fonte de um som. Isso lhes permite identificar a origem de um ruído com precisão incrível, mesmo que estejam dormindo!</p>
                    </div>
                </div>
            </div>
        </section>

        <hr>

        <section id="faq" class="secao">
            <h2>Perguntas Frequentes sobre Saúde Pet</h2>
            <p>Respostas rápidas para as dúvidas mais comuns.</p>

            <div class="perguntas-frequentes">
                <div class="pergunta-item">
                    <h3>Com que frequência devo levar meu pet ao veterinário?</h3>
                    <p>Idealmente, uma vez ao ano para check-ups de rotina, vacinação e vermifugação. Filhotes e idosos podem precisar de visitas mais frequentes.</p>
                </div>
                <div class="pergunta-item">
                    <h3>Qual a melhor forma de escovar os dentes do meu cachorro/gato?</h3>
                    <p>Use uma escova e pasta de dente específicas para animais. Comece aos poucos, acostumando o pet, e faça o processo de forma suave e regular. O veterinário pode demonstrar a técnica correta.</p>
                </div>
                <div class="pergunta-item">
                    <h3>Meu animal está coçando muito, o que pode ser?</h3>
                    <p>Coceira excessiva pode indicar pulgas, carrapatos, alergias, sarna ou infecções de pele. É fundamental levá-lo ao veterinário para um diagnóstico preciso e tratamento adequado.</p>
                </div>
                <div class="pergunta-item">
                    <h3>Posso dar comida caseira para meu pet?</h3>
                    <p>Apenas sob orientação e supervisão de um veterinário nutricionista. Dietas caseiras desbalanceadas podem causar deficiências nutricionais graves. Ração de qualidade geralmente é a opção mais segura e completa.</p>
                </div>
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