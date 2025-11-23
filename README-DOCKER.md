# 🐳 Docker - Pet Conecta

Este projeto agora suporta execução via Docker, facilitando o desenvolvimento e deploy.

## 📋 Pré-requisitos

- Docker instalado
- Docker Compose instalado

## 🚀 Como Usar

### ⚠️ Primeira Vez: Configurar File Sharing (macOS)

No macOS, você precisa dar permissão ao Docker para acessar os arquivos:

1. Abra o **Docker Desktop**
2. Vá em **Preferences** (⚙️) → **Resources** → **File Sharing**
3. Clique no botão **+** (adicionar)
4. Adicione: `/Applications/XAMPP/xamppfiles/htdocs`
5. Clique em **Apply & Restart**
6. Aguarde o Docker reiniciar

### 1. Iniciar os Containers

```bash
docker-compose up -d
```

Este comando irá:
- Construir a imagem PHP/Apache
- Iniciar o MySQL
- Iniciar o phpMyAdmin (opcional)
- Criar a rede e volumes necessários

### 2. Acessar a Aplicação (Frontend)

🌐 **URL Principal**: http://localhost:8080

**Páginas disponíveis:**
- 🏠 **Home**: http://localhost:8080/ ou http://localhost:8080/index.php
- 📝 **Cadastro**: http://localhost:8080/cadastro.php
- 🔐 **Login**: http://localhost:8080/login.php
- 💚 **Bem-Estar Animal**: http://localhost:8080/bemestar.php
- 🐾 **Adoção/Doação**: http://localhost:8080/adoção.php
- ℹ️ **Sobre Nós**: http://localhost:8080/saiba-mais.php

**Outros serviços:**
- 📊 **phpMyAdmin**: http://localhost:8081
- 🗄️ **MySQL**: localhost:3307

### 3. Parar os Containers

```bash
docker-compose down
```

Para remover também os volumes (apaga o banco de dados):

```bash
docker-compose down -v
```

## 🔧 Configuração

### Variáveis de Ambiente

As configurações do banco de dados podem ser alteradas no arquivo `docker-compose.yml`:

```yaml
environment:
  - DB_HOST=db
  - DB_NAME=petconecta
  - DB_USER=root
  - DB_PASS=rootpassword
```

### Portas

- **8080**: Aplicação PHP/Apache
- **3307**: MySQL (para conectar de fora do Docker)
- **8081**: phpMyAdmin

Para alterar as portas, edite o arquivo `docker-compose.yml`.

## 📁 Estrutura

- `Dockerfile`: Configuração da imagem PHP/Apache
- `docker-compose.yml`: Orquestração dos serviços
- `.dockerignore`: Arquivos ignorados no build

## 🗄️ Banco de Dados

O banco de dados `petconecta` será criado automaticamente na primeira execução.

As tabelas `usuarios` e `log` serão criadas automaticamente pelo `config.php`.

### Scripts SQL Iniciais

Se você tiver scripts SQL na pasta `Banco de dados/`, eles serão executados automaticamente na primeira inicialização do MySQL.

## 🐛 Troubleshooting

### Ver logs dos containers

```bash
docker-compose logs -f
```

### Ver logs de um serviço específico

```bash
docker-compose logs -f web
docker-compose logs -f db
```

### Acessar o container PHP

```bash
docker exec -it pet-conecta-web bash
```

### Acessar o MySQL via linha de comando

```bash
docker exec -it pet-conecta-db mysql -u root -prootpassword petconecta
```

### Reconstruir as imagens

```bash
docker-compose build --no-cache
docker-compose up -d
```

## 🔄 Desenvolvimento

Os arquivos do projeto são montados como volumes, então qualquer alteração nos arquivos PHP será refletida imediatamente no container. Basta recarregar a página no navegador (F5).

## ✅ Verificar Status

Use o script de verificação:

```bash
./verificar-docker.sh
```

Ou manualmente:

```bash
# Ver status dos containers
docker-compose ps

# Ver logs do container web
docker-compose logs -f web

# Verificar se está acessível
curl http://localhost:8080
```

## 🐛 Problemas Comuns

### Container Web Não Inicia

**Erro**: `mounts denied: The path ... is not shared`

**Solução**: Configure o File Sharing no Docker Desktop (veja seção acima)

### Porta 8080 Já Está em Uso

**Solução**: Altere a porta no `docker-compose.yml`:
```yaml
ports:
  - "8082:80"  # Use outra porta
```

### Frontend Não Carrega

1. Verifique se o container está rodando: `docker-compose ps`
2. Verifique os logs: `docker-compose logs web`
3. Aguarde alguns segundos após iniciar (MySQL precisa inicializar)

## 📝 Notas

- O `config.php` detecta automaticamente se está rodando no Docker ou no XAMPP
- Os logs são salvos em `logs/` dentro do container e no volume mapeado
- O phpMyAdmin é opcional e pode ser removido do `docker-compose.yml` se não for necessário

