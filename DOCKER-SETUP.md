# 🐳 Configuração do Docker - Pet Conecta

## ⚠️ Importante: Configurar Compartilhamento de Arquivos no macOS

No macOS, o Docker Desktop precisa ter permissão para acessar o diretório do projeto.

### Passo a Passo:

1. **Abra o Docker Desktop**
   - Clique no ícone do Docker na barra de menu
   - Selecione **Preferences...** (ou **Settings...**)

2. **Configure o File Sharing**
   - Vá em **Resources** → **File Sharing**
   - Clique no botão **+** para adicionar um diretório
   - Adicione o diretório: `/Applications/XAMPP/xamppfiles/htdocs`
   - Ou adicione o diretório completo: `/Applications/XAMPP/xamppfiles/htdocs/pet-conecta.html`
   - Clique em **Apply & Restart**

3. **Aguarde o Docker reiniciar**

4. **Teste novamente**
   ```bash
   docker-compose up -d
   ```

## 🚀 Comandos Úteis

### Iniciar os containers
```bash
docker-compose up -d
```

### Ver logs
```bash
docker-compose logs -f
```

### Parar os containers
```bash
docker-compose down
```

### Parar e remover volumes (apaga o banco)
```bash
docker-compose down -v
```

### Reconstruir as imagens
```bash
docker-compose build --no-cache
docker-compose up -d
```

### Ver status dos containers
```bash
docker-compose ps
```

### Acessar o container PHP
```bash
docker exec -it pet-conecta-web bash
```

### Acessar o MySQL
```bash
docker exec -it pet-conecta-db mysql -u root -prootpassword petconecta
```

## 🌐 URLs de Acesso

Após iniciar os containers:

- **Aplicação Web**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081
- **MySQL**: localhost:3307

## 🔧 Credenciais Padrão

- **MySQL Root**: `root` / `rootpassword`
- **MySQL User**: `petuser` / `petpassword`
- **Database**: `petconecta`

## 📝 Notas

- As tabelas `usuarios` e `log` serão criadas automaticamente pelo `config.php`
- Os logs da aplicação ficam em `./logs/`
- O banco de dados persiste no volume `db_data`
- Alterações nos arquivos PHP são refletidas imediatamente (devido ao volume montado)

## 🐛 Troubleshooting

### Erro: "mounts denied"
- Configure o File Sharing no Docker Desktop (ver instruções acima)

### Erro: "port already in use"
- Pare outros serviços usando as portas 8080, 3307 ou 8081
- Ou altere as portas no `docker-compose.yml`

### Container não inicia
- Verifique os logs: `docker-compose logs web`
- Verifique os logs do MySQL: `docker-compose logs db`

### Banco de dados não conecta
- Aguarde alguns segundos para o MySQL inicializar completamente
- Verifique se o container está rodando: `docker-compose ps`
- Teste a conexão: `docker exec -it pet-conecta-db mysql -u root -prootpassword -e "SHOW DATABASES;"`





