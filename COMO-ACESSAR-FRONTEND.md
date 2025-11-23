# 🌐 Como Acessar o Frontend com Docker

## 🎯 URL de Acesso

Quando o container web estiver rodando, acesse:

**http://localhost:8080**

## 📋 Passo a Passo

### 1️⃣ Configurar File Sharing (Primeira Vez)

No macOS, você precisa dar permissão ao Docker para acessar os arquivos:

1. **Abra o Docker Desktop**
2. Vá em **Preferences** (⚙️) → **Resources** → **File Sharing**
3. Clique no botão **+** (adicionar)
4. Adicione o diretório: `/Applications/XAMPP/xamppfiles/htdocs`
5. Clique em **Apply & Restart**
6. Aguarde o Docker reiniciar

### 2️⃣ Iniciar os Containers

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/pet-conecta.html
docker-compose up -d
```

### 3️⃣ Verificar se Está Rodando

```bash
docker-compose ps
```

Você deve ver os 3 containers rodando:
- ✅ `pet-conecta-web` (STATUS: Up)
- ✅ `pet-conecta-db` (STATUS: Up)
- ✅ `pet-conecta-phpmyadmin` (STATUS: Up)

### 4️⃣ Acessar o Frontend

Abra seu navegador e acesse:

- **Página Inicial**: http://localhost:8080
- **Página de Cadastro**: http://localhost:8080/cadastro.php
- **Página de Login**: http://localhost:8080/login.php
- **Bem-Estar**: http://localhost:8080/bemestar.php
- **Adoção**: http://localhost:8080/adoção.php
- **Sobre Nós**: http://localhost:8080/saiba-mais.php

## 🔍 Verificar se o Container Web Está Rodando

```bash
# Ver status
docker-compose ps

# Ver logs do container web
docker-compose logs web

# Ver logs em tempo real
docker-compose logs -f web
```

## 🐛 Solução de Problemas

### Container Web Não Inicia

**Erro**: `mounts denied: The path ... is not shared`

**Solução**:
1. Configure o File Sharing no Docker Desktop (passo 1 acima)
2. Reinicie o Docker Desktop
3. Execute: `docker-compose up -d`

### Porta 8080 Já Está em Uso

**Erro**: `Bind for 0.0.0.0:8080 failed: port is already allocated`

**Solução**:
1. Pare o serviço que está usando a porta 8080
2. Ou altere a porta no `docker-compose.yml`:
   ```yaml
   ports:
     - "8081:80"  # Mude 8080 para outra porta (ex: 8082)
   ```
3. Execute: `docker-compose up -d`

### Página Não Carrega (404 ou Erro)

**Verifique**:
1. O container está rodando: `docker-compose ps`
2. Os logs não mostram erros: `docker-compose logs web`
3. O arquivo `index.php` existe no diretório
4. As permissões estão corretas

### Banco de Dados Não Conecta

**Aguarde alguns segundos** após iniciar os containers, o MySQL precisa de tempo para inicializar.

**Verifique**:
```bash
# Ver logs do MySQL
docker-compose logs db

# Testar conexão
docker exec -it pet-conecta-db mysql -u root -prootpassword -e "SHOW DATABASES;"
```

## 📱 Páginas Disponíveis

- **Home**: http://localhost:8080/index.php ou http://localhost:8080/
- **Cadastro**: http://localhost:8080/cadastro.php
- **Login**: http://localhost:8080/login.php
- **Bem-Estar Animal**: http://localhost:8080/bemestar.php
- **Adoção/Doação**: http://localhost:8080/adoção.php
- **Sobre Nós**: http://localhost:8080/saiba-mais.php

## 🔄 Atualizações em Tempo Real

Como os arquivos estão montados como volume, **qualquer alteração nos arquivos PHP será refletida imediatamente** no navegador. Basta recarregar a página (F5).

## 🛑 Parar os Containers

```bash
# Parar todos os containers
docker-compose down

# Parar e remover volumes (apaga o banco de dados)
docker-compose down -v
```

## ✅ Checklist Rápido

- [ ] Docker Desktop instalado e rodando
- [ ] File Sharing configurado no Docker Desktop
- [ ] Containers iniciados: `docker-compose up -d`
- [ ] Container web rodando: `docker-compose ps`
- [ ] Acessar: http://localhost:8080

## 🎉 Pronto!

Se tudo estiver configurado, você deve ver a página inicial do **PET CONECTA** em http://localhost:8080





