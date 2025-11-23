#!/bin/bash

# Script para verificar o status do Docker e acessar o frontend

echo "🔍 Verificando status do Docker..."
echo ""

# Verificar se Docker está rodando
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker não está rodando!"
    echo "   Por favor, inicie o Docker Desktop primeiro."
    exit 1
fi

echo "✅ Docker está rodando"
echo ""

# Verificar containers
echo "📦 Status dos Containers:"
docker-compose ps
echo ""

# Verificar se o container web está rodando
if docker ps | grep -q "pet-conecta-web.*Up"; then
    echo "✅ Container Web está RODANDO!"
    echo ""
    echo "🌐 Acesse o frontend em:"
    echo "   http://localhost:8080"
    echo ""
    echo "📄 Páginas disponíveis:"
    echo "   - Home: http://localhost:8080/"
    echo "   - Cadastro: http://localhost:8080/cadastro.php"
    echo "   - Login: http://localhost:8080/login.php"
    echo "   - Bem-Estar: http://localhost:8080/bemestar.php"
    echo "   - Adoção: http://localhost:8080/adoção.php"
    echo ""
elif docker ps -a | grep -q "pet-conecta-web.*Created"; then
    echo "⚠️  Container Web está CRIADO mas não INICIADO"
    echo ""
    echo "❌ Problema: File Sharing não configurado"
    echo ""
    echo "📋 Para resolver:"
    echo "   1. Abra o Docker Desktop"
    echo "   2. Vá em Preferences → Resources → File Sharing"
    echo "   3. Adicione: /Applications/XAMPP/xamppfiles/htdocs"
    echo "   4. Clique em Apply & Restart"
    echo "   5. Execute: docker-compose up -d"
    echo ""
else
    echo "⚠️  Container Web não está rodando"
    echo ""
    echo "🔄 Para iniciar, execute:"
    echo "   docker-compose up -d"
    echo ""
fi

# Verificar MySQL
if docker ps | grep -q "pet-conecta-db.*Up"; then
    echo "✅ MySQL está rodando na porta 3307"
fi

# Verificar phpMyAdmin
if docker ps | grep -q "pet-conecta-phpmyadmin.*Up"; then
    echo "✅ phpMyAdmin está rodando em http://localhost:8081"
fi

echo ""
echo "📝 Para ver logs: docker-compose logs -f web"
echo "📝 Para parar: docker-compose down"





