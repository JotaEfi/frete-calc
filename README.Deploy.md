# 🚀 Deploy no Railway - Sistema de Cálculo de Frete

Este guia mostra como fazer deploy gratuito do Sistema de Cálculo de Frete no Railway.

## 🎯 **Por que Railway?**

- ✅ **500 horas/mês gratuitas**
- ✅ **Suporte nativo ao Docker**
- ✅ **MySQL gratuito incluído**
- ✅ **Deploy automático via GitHub**
- ✅ **SSL automático (HTTPS)**
- ✅ **Domínio gratuito (.railway.app)**

## 🛠️ **Pré-requisitos**

1. Conta no GitHub (já tem ✅)
2. Repositório pushado (já tem ✅)
3. Conta no Railway (gratuita)

## 📋 **Passo a Passo para Deploy**

### 1. **Criar conta no Railway**
1. Acesse: https://railway.app
2. Clique em "Start a New Project"
3. Faça login com sua conta GitHub
4. Autorize o Railway a acessar seus repositórios

### 2. **Configurar o Projeto**
1. Clique em "Deploy from GitHub repo"
2. Selecione o repositório `frete-calc`
3. Railway detectará automaticamente o Dockerfile
4. Clique em "Deploy"

### 3. **Adicionar Banco de Dados MySQL**
1. No painel do projeto, clique em "+ New"
2. Selecione "Database"
3. Escolha "Add MySQL"
4. Aguarde a criação do banco

### 4. **Configurar Variáveis de Ambiente**
1. Vá na aba "Variables" do seu serviço web
2. Adicione as seguintes variáveis:

```bash
APP_SECRET=sua-chave-secreta-super-forte-aqui-mude-isso
PASSWORD_SALT=seu-salt-de-senha-mude-isso
```

**Importante:** As variáveis do MySQL são criadas automaticamente pelo Railway.

### 5. **Executar Migrations (Primeira vez)**
1. Vá na aba "Deploy" 
2. Acesse os logs para ver se o deploy foi bem-sucedido
3. As tabelas serão criadas automaticamente pelo `init.sql`

### 6. **Acessar sua aplicação**
1. Na aba "Settings", copie o domínio público
2. Sua aplicação estará disponível em: `https://seu-projeto.railway.app`

## 🔧 **Configurações Importantes**

### **Variáveis de Ambiente Obrigatórias:**
```bash
APP_SECRET=chave-jwt-super-segura-mude-isso-imediatamente
PASSWORD_SALT=salt-para-senhas-mude-isso-tambem
```

### **Variáveis MySQL (Automáticas):**
- `MYSQLHOST` - Host do banco
- `MYSQLPORT` - Porta do banco  
- `MYSQLDATABASE` - Nome do banco
- `MYSQLUSER` - Usuário do banco
- `MYSQLPASSWORD` - Senha do banco

## 📊 **Monitoramento**

### **Logs em Tempo Real:**
1. Vá na aba "Deploy" 
2. Clique em "View Logs"
3. Monitore erros e atividade

### **Métricas:**
1. Aba "Metrics" mostra uso de CPU e memória
2. Aba "Usage" mostra horas consumidas

## 🚨 **Solução de Problemas**

### **Deploy falhou:**
1. Verifique os logs na aba "Deploy"
2. Certifique-se que o Dockerfile está correto
3. Verifique se as variáveis de ambiente estão definidas

### **Banco não conecta:**
1. Aguarde alguns minutos após criar o MySQL
2. Verifique se as variáveis MySQL foram criadas automaticamente
3. Reinicie o deploy se necessário

### **Site não carrega:**
1. Verifique se o domínio público foi gerado
2. Aguarde alguns minutos após o deploy
3. Verifique os logs por erros

## 💰 **Limites Gratuitos**

- **500 horas/mês** de execução
- **100GB** de largura de banda
- **1GB** de armazenamento no banco
- **Ilimitados** projetos

## 🔄 **Deploy Automático**

Após a configuração inicial:
1. Qualquer push na branch `main` fará deploy automático
2. Você receberá notificações por email
3. Pode acompanhar o progresso em tempo real

## 🌐 **URL Final**

Sua aplicação ficará disponível em:
`https://frete-calc-production.up.railway.app`

## 📝 **Próximos Passos**

1. **Domínio personalizado** (opcional): Configure seu próprio domínio
2. **Monitoramento**: Configure alertas por email
3. **Backup**: Railway faz backup automático do banco
4. **SSL**: Já vem configurado automaticamente

---

## ⚡ **Deploy Alternativo: Render**

Se preferir o Render:

1. Acesse: https://render.com
2. Conecte GitHub
3. Selecione o repositório
4. Configure como "Web Service"
5. Use o Dockerfile
6. Adicione PostgreSQL gratuito
7. Ajuste as variáveis de ambiente para PostgreSQL