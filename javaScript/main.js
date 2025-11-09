document.addEventListener('DOMContentLoaded', function() {
    // Seleciona os elementos php relevantes
    const userInfoDiv = document.getElementById('user-info'); // Div que exibe as informações do usuário
    const loggedInUserSpan = document.getElementById('logged-in-user'); // Span que exibe o nome do usuário
    const logoutBtn = document.getElementById('logout-btn'); // Botão de logout
    const mainNav = document.getElementById('main-nav'); // Barra de navegação principal


    // Função para verificar o status de login do usuário
    function checkLoginStatus() {
        // Tenta obter o nome do usuário logado do localStorage
        const loggedInUser = localStorage.getItem('loggedInUser');

        if (loggedInUser) {
            // Se houver um usuário logado:
            userInfoDiv.classList.remove('d-none'); // Exibe a div de informações do usuário
            loggedInUserSpan.textContent = `Olá, ${loggedInUser}!`; // Define o texto de boas-vindas
            mainNav.classList.remove('d-none'); // Exibe a barra de navegação principal
            // Se não houver usuário logado:
            userInfoDiv.classList.add('d-none'); // Oculta a div de informações do usuário
            loggedInUserSpan.textContent = ''; // Limpa o texto de boas-vindas
            
            // Lógica para controle de visibilidade do menu em páginas específicas quando não logado:
            // Permite que o menu apareça nas páginas de login, cadastro e erro para navegação básica.
            // Em outras páginas (como a principal), o menu é ocultado se o usuário não estiver logado.
            const pathname = window.location.pathname;
            if (pathname.endsWith('login.php') || pathname.endsWith('cadastro.php') || pathname.endsWith('erro.php') || pathname.endsWith('respostas_formulario.php')) {
                mainNav.classList.remove('d-none'); // Garante que o menu esteja visível nessas páginas
            } else {
                 mainNav.classList.add('d-none'); // Oculta o menu principal em outras telas se não logado
            }
        }
    }

    // Adiciona um event listener para o botão de logout
    // A verificação 'if (logoutBtn)' é importante para evitar erros caso o botão não exista na página
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            // Remove o status de login do localStorage
            localStorage.removeItem('loggedInUser');
            // Remove as credenciais temporárias (login e senha) também
            localStorage.removeItem('tempLogin');
            localStorage.removeItem('tempSenha');
            // Exibe uma mensagem de desconexão (poderia ser um toast também)
            alert('Você foi desconectado.'); 
            // Redireciona o usuário para a página inicial
            window.location.href = 'index.php'; 
        });
    }

    
    // Chama a função checkLoginStatus assim que o DOM é completamente carregado
    checkLoginStatus();
});


/*----------- DESAPARECER OS BOTÕES LOGIN/CADSTRO AO ESTAR LOGADO-----------------------------*/


document.addEventListener('DOMContentLoaded', function() {
    // Seleciona os elementos php relevantes
    const userInfoDiv = document.getElementById('user-info'); // Div que exibe as informações do usuário
    const loggedInUserSpan = document.getElementById('logged-in-user'); // Span que exibe o nome do usuário
    const logoutBtn = document.getElementById('logout-btn'); // Botão de logout
    const loginCadastroDiv = document.getElementById('cadastro-login'); // Div que contém os botões de login e cadastro

    // Função para verificar o status de login do usuário
    function checkLoginStatus() {
        // Tenta obter o nome do usuário logado do localStorage
        const loggedInUser = localStorage.getItem('loggedInUser');

        if (loggedInUser) {
            // Se houver um usuário logado:
            userInfoDiv.classList.remove('d-none'); // Exibe a div de informações do usuário
            loggedInUserSpan.textContent = `Olá, ${loggedInUser}!`; // Define o texto de boas-vindas
            loginCadastroDiv.classList.add('d-none'); // Oculta a div de login e cadastro
        } else {
            // Se não houver usuário logado:
            userInfoDiv.classList.add('d-none'); // Oculta a div de informações do usuário
            loggedInUserSpan.textContent = ''; // Limpa o texto de boas-vindas
            loginCadastroDiv.classList.remove('d-none'); // Exibe a div de login e cadastro
        }
    }

    // Adiciona um event listener para o botão de logout
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            // Remove o status de login do localStorage
            localStorage.removeItem('loggedInUser');
            // Remove as credenciais temporárias (login e senha) também
            localStorage.removeItem('tempLogin');
            localStorage.removeItem('tempSenha');
            // Exibe uma mensagem de desconexão (opcional)
            alert('Você foi desconectado.'); 
            // Redireciona o usuário para a página inicial
            window.location.href = 'index.php'; 
            // Re-checa o status de login para atualizar a interface imediatamente
            checkLoginStatus(); 
        });
    }

    // Chama a função checkLoginStatus assim que o DOM é completamente carregado
    checkLoginStatus();
});



