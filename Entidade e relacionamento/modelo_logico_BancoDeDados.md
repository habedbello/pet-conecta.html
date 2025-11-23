# Modelo Lógico do Banco de Dados "Pet Conecta"

 funcionalidades de cadastro de usuários, registro de logs, doação de animais, candidaturas de adoção e o registro final das adoções.

## Entidades e Relacionamentos

O modelo é composto por 5 entidades principais: `USUARIO`, `PET`, `FORMULARIO_ADOCAO`, `LOG` e `RELACIONAMENTO_ADOCAO`.

### 1. USUARIO (Usuários do Sistema)

| Atributo | Tipo de Dado | Chave | Observações |
| :--- | :--- | :--- | :--- |
| **id** | INT | PK, AI | Chave Primária |
| nome | VARCHAR(80) | | Nome completo |
| email | VARCHAR(100) | UNIQUE | E-mail (único) |
| dataNascimento | DATE | | Data de nascimento |
| CPF | VARCHAR(11) | UNIQUE | CPF (único) |
| celular | VARCHAR(20) | | Número de celular |
| CEP | VARCHAR(10) | | Endereço (demais campos de endereço) |
| login | VARCHAR(50) | UNIQUE | Login (único) |
| senha | VARCHAR(255) | | Senha criptografada |
|  *demais campos de endereço e pessoais* | 

### 2. PET (Animais)

| Atributo | Tipo de Dado | Chave | Observações |
| :--- | :--- | :--- | :--- |
| **id** | INT | PK, AI | Chave Primária |
| nome | VARCHAR(80) | | Nome do animal |
| especie | VARCHAR(50) | | Ex: Cachorro, Gato |
| raca | VARCHAR(50) | | Raça |
| idade | INT | | Idade em anos |
| sexo | VARCHAR(10) | | Macho/Fêmea |
| porte | VARCHAR(20) | | Pequeno, Médio, Grande |
| castrado | BOOLEAN | | Status de castração |
| vacinado | BOOLEAN | | Status de vacinação |
| temperamento | TEXT | | Descrição do temperamento |
| historico_saude | TEXT | | Histórico de saúde |
| motivo_doacao | TEXT | | Motivo da doação |
| status_adocao | ENUM | | 'Disponível', 'Em Processo', 'Adotado' |
| **id_doador** | INT | FK | Chave Estrangeira para `USUARIO.id` (Doador) |

### 3. FORMULARIO_ADOCAO (Candidaturas)

| Atributo | Tipo de Dado | Chave | Observações |
| :--- | :--- | :--- | :--- |
| **id** | INT | PK, AI | Chave Primária |
| **id_usuario** | INT | FK | Chave Estrangeira para `USUARIO.id` (Candidato) |
| data_envio | DATETIME | | Data e hora do envio |
| tipo_moradia | VARCHAR(50) | | Tipo de moradia do candidato |
| concordancia_moradores | BOOLEAN | | Concordância dos moradores |
| tempo_sozinho_horas | INT | | Horas que o pet passará sozinho |
| outros_pets | TEXT | | Descrição de outros pets |
| status_candidatura | ENUM | | 'Pendente', 'Aprovado', 'Rejeitado' |

### 4. LOG (Registro de Ações)

| Atributo | Tipo de Dado | Chave | Observações |
| :--- | :--- | :--- | :--- |
| **id** | INT | PK, AI | Chave Primária |
| data_log | DATE | | Data da ação |
| hora_log | TIME | | Hora da ação |
| status | VARCHAR(100) | | Descrição do log |
| **id_usuario** | INT | FK | Chave Estrangeira para `USUARIO.id` |
| *demais campos de log (login, nome, cpf)* |

### 5. RELACIONAMENTO_ADOCAO (Adoções Concluídas)

| Atributo | Tipo de Dado | Chave | Observações |
| :--- | :--- | :--- | :--- |
| **id_pet** | INT | PK, FK | Chave Estrangeira para `PET.id` |
| **id_adotante** | INT | PK, FK | Chave Estrangeira para `USUARIO.id` |
| data_adocao | DATE | | Data em que a adoção foi concluída |
| termo_assinado | BOOLEAN | | Confirmação de assinatura do termo |
| observacoes | TEXT | | Notas adicionais |

## Relacionamentos e Cardinalidades

A tabela a seguir resume os relacionamentos e as cardinalidades entre as entidades:

| Entidade A | Cardinalidade | Relacionamento | Cardinalidade | Entidade B | Chave Estrangeira (FK) |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **USUARIO** | 1 | Gera | N | **LOG** | `LOG.id_usuario` |
| **USUARIO** | 1 | Doa | N | **PET** | `PET.id_doador` |
| **USUARIO** | 1 | Candidata-se | N | **FORMULARIO_ADOCAO** | `FORMULARIO_ADOCAO.id_usuario` |
| **USUARIO** | 1 | Adota | N | **RELACIONAMENTO_ADOCAO** | `RELACIONAMENTO_ADOCAO.id_adotante` |
| **PET** | 1 | É Adotado em | N | **RELACIONAMENTO_ADOCAO** | `RELACIONAMENTO_ADOCAO.id_pet` |


## Diagrama Entidade-Relacionamento (Conceitual)

O diagrama conceitual do modelo é o seguinte:

erDiagram
    USUARIO ||--o{ LOG : gera
    USUARIO ||--o{ PET : doa
    USUARIO ||--o{ FORMULARIO_ADOCAO : candidata_se
    USUARIO ||--o{ RELACIONAMENTO_ADOCAO : adota
    PET ||--o{ RELACIONAMENTO_ADOCAO : e_adotado_em

    USUARIO {
        int id PK
        varchar nome
        varchar email UK
        date dataNascimento
        varchar CPF UK
        varchar celular
        varchar CEP
        varchar login UK
        varchar senha
    }

    LOG {
        int id PK
        int id_usuario FK
        date data_log
        time hora_log
        varchar status
    }

    PET {
        int id PK
        int id_doador FK
        varchar nome
        varchar especie
        varchar raca
        int idade
        varchar sexo
        varchar porte
        boolean castrado
        enum status_adocao
    }

    FORMULARIO_ADOCAO {
        int id PK
        int id_usuario FK
        datetime data_envio
        varchar tipo_moradia
        boolean concordancia_moradores
        int tempo_sozinho_horas
        text outros_pets
        enum status_candidatura
    }

    RELACIONAMENTO_ADOCAO {
        int id_pet PK, FK
        int id_adotante PK, FK
        date data_adocao
        boolean termo_assinado
    }
