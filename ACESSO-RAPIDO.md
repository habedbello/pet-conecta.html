# ⚡ Acesso Rápido ao Frontend

## 🎯 URL Principal

**http://localhost:8080**

## 🚀 Início Rápido

### 1. Verificar Status
```bash
./verificar-docker.sh
```

### 2. Iniciar Containers
```bash
docker-compose up -d
```

### 3. Acessar no Navegador
Abra: **http://localhost:8080**

## 📋 URLs das Páginas

| Página | URL |
|--------|-----|
| 🏠 Home | http://localhost:8080/ |
| 📝 Cadastro | http://localhost:8080/cadastro.php |
| 🔐 Login | http://localhost:8080/login.php |
| 💚 Bem-Estar | http://localhost:8080/bemestar.php |
| 🐾 Adoção | http://localhost:8080/adoção.php |
| ℹ️ Sobre Nós | http://localhost:8080/saiba-mais.php |

## ⚠️ Se Não Funcionar

1. **Configure File Sharing** (primeira vez no macOS):
   - Docker Desktop → Preferences → Resources → File Sharing
   - Adicione: `/Applications/XAMPP/xamppfiles/htdocs`
   - Apply & Restart

2. **Verifique se está rodando**:
   ```bash
   docker-compose ps
   ```

3. **Veja os logs**:
   ```bash
   docker-compose logs web
   ```

## 🔍 Comandos Úteis

```bash
# Ver status
docker-compose ps

# Ver logs
docker-compose logs -f web

# Reiniciar
docker-compose restart web

# Parar tudo
docker-compose down
```





