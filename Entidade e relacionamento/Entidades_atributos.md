# Entidades e Atributos do Modelo de Banco de Dados "Pet Conecta"

 Entidade-Relacionamento 

## 1. USUARIO (Usuários do Sistema)

Esta entidade representa as pessoas cadastradas no sistema, que podem ser adotantes, doadores ou apenas usuários da plataforma.

| Atributo | Tipo de Dado (MySQL) | Chave | Observações |
| :--- | :--- | :--- | :--- |
| **id** | INT(11) | PK, AI | Chave Primária, Auto Incremento |
| nome | VARCHAR(80) | | Nome completo do usuário |
| email | VARCHAR(100) | UNIQUE | Endereço de e-mail (único) |
| dataNascimento | DATE | | Data de nascimento |
| sexo | VARCHAR(50) | | Sexo do usuário |
| nomeMaterno | VARCHAR(80) | | Nome materno |
| CPF | VARCHAR(11) | UNIQUE | Cadastro de Pessoa Física (único) |
| celular | VARCHAR(20) | | Número de celular |
| telefone | VARCHAR(20) | | Número de telefone fixo (opcional) |
| CEP | VARCHAR(10) | | Código de Endereçamento Postal |
| logradouro | VARCHAR(100) | | Nome da rua/avenida |
| numero | VARCHAR(10) | | Número do endereço |
| complemento | VARCHAR(50) | | Complemento (opcional) |
| bairro | VARCHAR(50) | | Bairro |
| cidade | VARCHAR(50) | | Cidade |
| estado | VARCHAR(2) | | Estado (UF) |
| login | VARCHAR(50) | UNIQUE | Nome de usuário para login (único) |
| senha | VARCHAR(255) | | Senha criptografada (hash) |

## 2. PET (Animais para Adoção/Doação)

Esta entidade representa os animais que estão disponíveis para adoção ou que foram doados para a ONG.

| Atributo | Tipo de Dado (MySQL) | Chave | Observações |
| :--- | :--- | :--- | :--- |
| **id** | INT(11) | PK, AI | Chave Primária, Auto Incremento |
| nome | VARCHAR(80) | | Nome do animal |
| especie | VARCHAR(50) | | Ex: Cachorro, Gato, Outro |
| raca | VARCHAR(50) | | Raça do animal |
| idade | INT | | Idade em anos |
| sexo | VARCHAR(10) | | Macho/Fêmea |
| porte | VARCHAR(20) | | Ex: Pequeno, Médio, Grande |
| castrado | BOOLEAN | | 1 (Sim) ou 0 (Não) |
| vacinado | BOOLEAN | | 1 (Sim) ou 0 (Não) |
| temperamento | TEXT | | Descrição do temperamento |
| historico_saude | TEXT | | Histórico de saúde relevante |
| motivo_doacao | TEXT | | Motivo pelo qual o animal está sendo doado |
| status_adocao | ENUM | | Ex: 'Disponível', 'Em Processo', 'Adotado' |
| **id_doador** | INT(11) | FK | Chave Estrangeira para `USUARIO.id` (Doador) |

## 3. FORMULARIO_ADOCAO (Candidaturas de Adoção)

Esta entidade armazena as respostas do formulário de adoção preenchido por um usuário interessado.

| Atributo | Tipo de Dado (MySQL) | Chave | Observações |
| :--- | :--- | :--- | :--- |
| **id** | INT(11) | PK, AI | Chave Primária, Auto Incremento |
| **id_usuario** | INT(11) | FK | Chave Estrangeira para `USUARIO.id` (Candidato) |
| data_envio | DATETIME | | Data e hora do envio do formulário |
| tipo_moradia | VARCHAR(50) | | Ex: Casa com quintal, Apartamento |
| concordancia_moradores | BOOLEAN | | 1 (Sim) ou 0 (Não) |
| tempo_sozinho_horas | INT | | Horas que o pet passará sozinho |
| outros_pets | TEXT | | Descrição de outros pets na casa |
| status_candidatura | ENUM | | Ex: 'Pendente', 'Aprovado', 'Rejeitado' |

## 4. LOG (Registro de Ações do Usuário)

Esta entidade armazena o histórico de login e outras ações importantes do usuário.

| Atributo | Tipo de Dado (MySQL) | Chave | Observações |
| :--- | :--- | :--- | :--- |
| **id** | INT(11) | PK, AI | Chave Primária, Auto Incremento |
| login | VARCHAR(50) | | Login do usuário no momento do log |
| nome | VARCHAR(80) | | Nome do usuário no momento do log |
| cpf | VARCHAR(11) | | CPF do usuário no momento do log |
| data_log | DATE | | Data da ação |
| hora_log | TIME | | Hora da ação |
| status | VARCHAR(100) | | Descrição do status/ação (Ex: 'Login Sucesso') |
| **id_usuario** | INT(11) | FK | Chave Estrangeira para `USUARIO.id` |

## 5. RELACIONAMENTO_ADOCAO (Registro de Adoções Concluídas)

Esta entidade representa a relação (Muitos para Muitos) entre um `PET` e um `USUARIO` (Adotante), servindo como uma tabela de ligação e registro final da adoção.

| Atributo | Tipo de Dado (MySQL) | Chave | Observações |
| :--- | :--- | :--- | :--- |
| **id_pet** | INT(11) | PK, FK | Chave Estrangeira para `PET.id` |
| **id_adotante** | INT(11) | PK, FK | Chave Estrangeira para `USUARIO.id` |
| data_adocao | DATE | | Data em que a adoção foi concluída |
| termo_assinado | BOOLEAN | | Confirmação de assinatura do termo |
| observacoes | TEXT | | Notas adicionais sobre a adoção |

**Chave Primária Composta:** (`id_pet`, `id_adotante`)
