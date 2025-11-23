    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Formulários de Adoção e Doação de Animais</title>
        <!-- Carrega o Tailwind CSS para estilização moderna e responsiva -->
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            /* Define a fonte e garante que o corpo ocupe a altura total */
            body {
                font-family: 'Inter', sans-serif;
                min-height: 100vh;
            }
        </style>
    </head>
    <body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 p-4 sm:p-8">

        <div class="max-w-4xl mx-auto space-y-12">
            
            <h1 class="text-4xl font-extrabold text-center text-indigo-600 dark:text-indigo-400">
                Conectando Corações: Formulários
            </h1>

            
            <!-- 1. FORMULÁRIO DE ADOÇÃO RESPONSÁVEL -->
            
            <div id="form-adocao" class="bg-white dark:bg-gray-800 shadow-2xl rounded-xl p-6 sm:p-10 border-t-8 border-indigo-500">
                <h2 class="text-3xl font-bold mb-6 text-indigo-700 dark:text-indigo-300 border-b pb-2">
                    🐾 Formulário de Adoção Responsável
                </h2>
                <p class="mb-8 text-gray-600 dark:text-gray-400">
                    Preencha os dados abaixo com responsabilidade. A adoção é um compromisso de uma vida inteira.
                </p>

                <form action="processar-adocao.php" method="POST" class="space-y-6">

                    <!-- Seção 1: Dados Pessoais -->
                    <fieldset class="border border-gray-300 dark:border-gray-700 p-4 rounded-lg">
                        <legend class="text-lg font-semibold px-2 text-gray-700 dark:text-gray-200">Seus Dados</legend>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                            <!-- Nome Completo -->
                            <div>
                                <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome Completo</label>
                                <input type="text" id="nome" name="nome" required 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                                    placeholder="Seu nome">
                            </div>
                            <!-- Telefone -->
                            <div>
                                <label for="telefone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefone (com DDD)</label>
                                <input type="tel" id="telefone" name="telefone" required 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                                    placeholder="(XX) XXXXX-XXXX">
                            </div>
                            <!-- Email -->
                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" id="email" name="email" required 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                                    placeholder="seu@email.com">
                            </div>
                        </div>
                    </fieldset>

                    <!-- Seção 2: Condições de Moradia -->
                    <fieldset class="border border-gray-300 dark:border-gray-700 p-4 rounded-lg">
                        <legend class="text-lg font-semibold px-2 text-gray-700 dark:text-gray-200">Seu Lar</legend>

                        <!-- Tipo de Moradia -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">1. Qual é o seu tipo de moradia?</label>
                            <div class="flex flex-wrap gap-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="moradia" value="casa_com_quintal" required class="form-radio h-4 w-4 text-indigo-600">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Casa com quintal</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="moradia" value="casa_sem_quintal" class="form-radio h-4 w-4 text-indigo-600">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Casa sem quintal</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="moradia" value="apartamento" class="form-radio h-4 w-4 text-indigo-600">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Apartamento</span>
                                </label>
                            </div>
                        </div>

                        <!-- Todos na casa concordam? -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">2. Todos os moradores da casa concordam com a adoção?</label>
                            <div class="flex flex-wrap gap-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="concordancia" value="sim" required class="form-radio h-4 w-4 text-indigo-600">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Sim</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="concordancia" value="nao" class="form-radio h-4 w-4 text-indigo-600">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Não</span>
                                </label>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Seção 3: Experiência e Rotina -->
                    <fieldset class="border border-gray-300 dark:border-gray-700 p-4 rounded-lg">
                        <legend class="text-lg font-semibold px-2 text-gray-700 dark:text-gray-200">Cuidado e Rotina</legend>
                        
                        <!-- Tempo dedicado -->
                        <div class="mb-4">
                            <label for="tempo_sozinho" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantas horas o animal passaria sozinho por dia?</label>
                            <input type="number" id="tempo_sozinho" name="tempo_sozinho" min="0" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                                placeholder="Ex: 8 horas">
                        </div>

                        <!-- Outros Pets -->
                        <div class="mb-4">
                            <label for="outros_pets" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Você possui ou já possuiu outros animais de estimação? Quais?</label>
                            <textarea id="outros_pets" name="outros_pets" rows="3" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                                placeholder="Ex: Tive um gato (Pingo) por 10 anos. Atualmente tenho um peixe."></textarea>
                        </div>
                    </fieldset>

                
                    <div class="flex justify-end">
                        <button type="submit" 
                            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-lg transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50">
                            Enviar Candidatura de Adoção 🐾
                        </button>
                    </div>
                </form>
            </div>

        
            <!-- 2. FORMULÁRIO DE DOAÇÃO DE ANIMAL -->
            
            <div id="form-doacao" class="bg-white dark:bg-gray-800 shadow-2xl rounded-xl p-6 sm:p-10 border-t-8 border-pink-500">
                <h2 class="text-3xl font-bold mb-6 text-pink-700 dark:text-pink-300 border-b pb-2">
                    💖 Formulário de Doação de Animal
                </h2>
                <p class="mb-8 text-gray-600 dark:text-gray-400">
                    Entendemos que mudanças de vida acontecem. Preencha este formulário para nos ajudar a encontrar o melhor novo lar para seu amigo.
                </p>

                <form action="processar-doacao.php" method="POST" class="space-y-6">

                    <!-- Seção 1: Dados do Animal -->
                    <fieldset class="border border-gray-300 dark:border-gray-700 p-4 rounded-lg">
                        <legend class="text-lg font-semibold px-2 text-gray-700 dark:text-gray-200">Dados do Pet</legend>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                            <!-- Nome do Animal -->
                            <div>
                                <label for="pet_nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome do Animal</label>
                                <input type="text" id="pet_nome" name="pet_nome" required 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                                    placeholder="Ex: Rex ou Mimi">
                            </div>
                            <!-- Espécie -->
                            <div>
                                <label for="pet_especie" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Espécie</label>
                                <select id="pet_especie" name="pet_especie" required 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="" disabled selected>Selecione</option>
                                    <option value="cachorro">Cachorro</option>
                                    <option value="gato">Gato</option>
                                    <option value="outro">Outro (Especifique abaixo)</option>
                                </select>
                            </div>
                            <!-- Idade -->
                            <div>
                                <label for="pet_idade" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Idade (em anos)</label>
                                <input type="number" id="pet_idade" name="pet_idade" min="0" required 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                                    placeholder="Ex: 2">
                            </div>
                            <!-- Sexo -->
                            <div>
                                <label for="pet_sexo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sexo</label>
                                <select id="pet_sexo" name="pet_sexo" required 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="" disabled selected>Selecione</option>
                                    <option value="macho">Macho</option>
                                    <option value="femea">Fêmea</option>
                                </select>
                            </div>
                            <!-- Raça / Porte -->
                            <div class="md:col-span-2">
                                <label for="pet_raca_porte" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Raça / Porte</label>
                                <input type="text" id="pet_raca_porte" name="pet_raca_porte" 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                                    placeholder="Ex: Vira-lata, Médio ou Labrador">
                            </div>
                        </div>
                    </fieldset>

                    <!-- Seção 2: Saúde e Temperamento -->
                    <fieldset class="border border-gray-300 dark:border-gray-700 p-4 rounded-lg">
                        <legend class="text-lg font-semibold px-2 text-gray-700 dark:text-gray-200">Saúde e Comportamento</legend>

                        <!-- Castrado? -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">1. O animal é castrado(a)?</label>
                            <div class="flex flex-wrap gap-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="castrado" value="sim" required class="form-radio h-4 w-4 text-pink-600">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Sim</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="castrado" value="nao" class="form-radio h-4 w-4 text-pink-600">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Não</span>
                                </label>
                            </div>
                        </div>

                        <!-- Temperamento -->
                        <div>
                            <label for="temperamento" class="block text-sm font-medium text-gray-700 dark:text-gray-300">2. Descreva o temperamento do animal (dócil, brincalhão, medroso, se dá bem com crianças/outros pets, etc.).</label>
                            <textarea id="temperamento" name="temperamento" rows="4" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                                placeholder="Descreva aqui o comportamento..."></textarea>
                        </div>
                    </fieldset>

                    <!-- Seção 3: Motivo da Doação -->
                    <fieldset class="border border-gray-300 dark:border-gray-700 p-4 rounded-lg">
                        <legend class="text-lg font-semibold px-2 text-gray-700 dark:text-gray-200">Motivo e Contato</legend>
                        
                        <!-- Motivo -->
                        <div class="mb-4">
                            <label for="motivo_doacao" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Motivo principal da doação</label>
                            <textarea id="motivo_doacao" name="motivo_doacao" rows="3" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                                placeholder="Ex: Mudança de país, alergia na família, etc."></textarea>
                        </div>

                        <!-- Telefone de Contato (Para garantir que a ONG possa ligar para a pessoa que está doando) -->
                        <div>
                            <label for="doador_telefone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Seu Telefone para Contato</label>
                            <input type="tel" id="doador_telefone" name="doador_telefone" required 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" 
                                placeholder="(XX) XXXXX-XXXX">
                        </div>
                    </fieldset>

                    <!-- Botão de Envio da Doação -->
                    <div class="flex justify-end">
                        <button type="submit" 
                            class="px-6 py-3 bg-pink-600 hover:bg-pink-700 text-white font-semibold rounded-lg shadow-lg transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-pink-500 focus:ring-opacity-50">
                            Enviar Pedido de Doação 💖
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- Script de Dark Mode (Simulação básica para demonstração) -->
        <script>
            // Função para alternar o dark mode (você pode substituir isso pelo seu arquivo 'darkmodee.js')
            function toggleDarkMode() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                } else {
                    document.documentElement.classList.add('dark');
                }
            }

            // Adiciona um listener no body para simular a mudança de dark mode
            document.body.addEventListener('dblclick', toggleDarkMode);
            console.log("Dê um duplo clique na tela para alternar entre os modos Claro e Escuro (simulação).");
        </script>
    </body>
    </html>