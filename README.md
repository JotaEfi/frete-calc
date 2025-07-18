# 🚛 Sistema de Cálculo de Frete

Sistema web para cálculo de frete de cargas desenvolvido em PHP com Bootstrap e Docker.

## 📋 Funcionalidades

- ✅ Cálculo de frete baseado em peso, distância e valor da carga
- ✅ Interface moderna e responsiva com Bootstrap 5
- ✅ Suporte a múltiplos tipos de veículos
- ✅ Cálculo de taxas (Ad Valorem, GRIS, ICMS)
- ✅ Histórico de cálculos
- ✅ Autoload PSR-4 com Composer
- ✅ Banco de dados MySQL com migrations
- ✅ Ambiente Docker completo

## 🛠️ Tecnologias Utilizadas

- **Backend**: PHP 8.2, MySQL 8.0
- **Frontend**: Bootstrap 5.3, HTML5, CSS3, JavaScript
- **Containerização**: Docker, Docker Compose
- **Autoload**: Composer PSR-4
- **Banco de dados**: MySQL com phpMyAdmin
- **Arquitetura**: MVC (Model-View-Controller)

## 📁 Estrutura do Projeto

```
frete-calc/
├── .env                    # Configurações do ambiente
├── .env.example           # Template de configuração
├── .gitignore             # Arquivos ignorados pelo Git
├── .dockerignore          # Arquivos ignorados pelo Docker
├── docker-compose.yml     # Orquestração dos containers
├── Dockerfile            # Imagem do container web
├── composer.json         # Dependências globais
├── database/
│   └── init.sql          # Migrations do banco
└── src/                  # Código fonte da aplicação
    ├── index.php         # Página principal
    ├── calcular.php      # Página de cálculo
    ├── composer.json     # Dependências do projeto
    ├── includes/         # Componentes reutilizáveis
    │   ├── header.php    # Header com Bootstrap
    │   └── footer.php    # Footer com Bootstrap
    ├── config/           # Configurações da aplicação
    ├── controllers/      # Controladores MVC
    ├── models/          # Modelos de dados
    ├── public/          # Arquivos públicos (CSS, JS, imagens)
    └── vendor/          # Dependências do Composer
```

## 🚀 Instalação e Configuração

### 1. Pré-requisitos

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Git](https://git-scm.com/)

### 2. Clonando o Repositório

```bash
git clone https://github.com/seu-usuario/frete-calc.git
cd frete-calc
```

### 3. Configuração do Ambiente (.env)

O arquivo `.env` contém todas as configurações necessárias para o funcionamento da aplicação.

#### 3.1. Copiando o arquivo de exemplo

```bash
cp .env.example .env
```

#### 3.2. Configurações principais do .env

```bash
# ==============================================
# CONFIGURAÇÕES DO AMBIENTE
# ==============================================

# Ambiente da aplicação (development, production, testing)
APP_ENV=development
APP_NAME="Sistema de Cálculo de Frete"
APP_URL=http://localhost:8080
APP_DEBUG=true

# ==============================================
# CONFIGURAÇÕES DO BANCO DE DADOS
# ==============================================

# Configurações do MySQL
DB_CONNECTION=mysql
DB_HOST=db                    # Nome do serviço no Docker
DB_PORT=3306
DB_DATABASE=fretecalc_db
DB_USERNAME=root
DB_PASSWORD=your-secure-password-here

# ==============================================
# CONFIGURAÇÕES DO DOCKER
# ==============================================

# Portas dos serviços
WEB_PORT=8080                 # Porta do site
DB_PORT=3306                  # Porta do MySQL
PHPMYADMIN_PORT=8000          # Porta do phpMyAdmin

# ==============================================
# CONFIGURAÇÕES DE SEGURANÇA
# ==============================================

# Chave secreta da aplicação (ALTERE EM PRODUÇÃO!)
APP_SECRET=your-secret-key-here-change-in-production

# Salt para hashing de senhas
PASSWORD_SALT=your-password-salt-here
```

#### 3.3. Variáveis importantes do .env

| Variável          | Descrição              | Valor Padrão                |
| ----------------- | ---------------------- | --------------------------- |
| `APP_ENV`         | Ambiente da aplicação  | `development`               |
| `APP_DEBUG`       | Habilita modo debug    | `true`                      |
| `DB_HOST`         | Host do banco de dados | `db`                        |
| `DB_PASSWORD`     | Senha do banco MySQL   | `your-secure-password-here` |
| `WEB_PORT`        | Porta do servidor web  | `8080`                      |
| `PHPMYADMIN_PORT` | Porta do phpMyAdmin    | `8000`                      |

### 4. Iniciando o Projeto

```bash
# Construir e iniciar os containers
docker-compose up -d

# Verificar se os containers estão rodando
docker-compose ps
```

### 5. Instalando Dependências

```bash
# Instalar dependências do Composer
docker-compose exec web composer install --optimize-autoloader
```

## 🌐 Acessando a Aplicação

Após iniciar os containers, você pode acessar:

- **Site Principal**: http://localhost:8080
- **Página de Cálculo**: http://localhost:8080/calcular.php
- **phpMyAdmin**: http://localhost:8000
  - Usuário: `root`
  - Senha: (valor definido em `DB_PASSWORD` no .env)

## 🔧 Comandos Úteis

### Docker

```bash
# Iniciar containers
docker-compose up -d

# Parar containers
docker-compose down

# Reiniciar containers
docker-compose restart

# Ver logs
docker-compose logs -f web

# Acessar container web
docker-compose exec web bash

# Reconstruir containers
docker-compose build --no-cache
```

### Composer

```bash
# Instalar dependências
docker-compose exec web composer install

# Atualizar dependências
docker-compose exec web composer update

# Gerar autoload
docker-compose exec web composer dump-autoload --optimize

# Adicionar nova dependência
docker-compose exec web composer require package/name
```

### Banco de Dados

```bash
# Reiniciar banco com migrations
docker-compose down -v
docker-compose up -d

# Backup do banco
docker-compose exec db mysqldump -u root -p fretecalc_db > backup.sql

# Restaurar backup
docker-compose exec -T db mysql -u root -p fretecalc_db < backup.sql
```

## ⚙️ Configuração para Produção

### 1. Variáveis de ambiente para produção

```bash
# .env para produção
APP_ENV=production
APP_DEBUG=false
APP_SECRET=sua-chave-secreta-muito-forte-aqui
DB_PASSWORD=senha-muito-segura-para-producao
```

### 2. Configurações de segurança

- ✅ Altere todas as senhas e chaves secretas
- ✅ Desabilite o modo debug (`APP_DEBUG=false`)
- ✅ Use HTTPS em produção
- ✅ Configure backup automático do banco
- ✅ Implemente monitoramento de logs

## 🔒 Segurança

### Arquivo .env

⚠️ **IMPORTANTE**: O arquivo `.env` contém informações sensíveis e **NUNCA** deve ser commitado no repositório.

### O que NÃO fazer:

```bash
# ❌ NUNCA faça isso
git add .env
git commit -m "Adicionando configurações"
```

### O que fazer:

```bash
# ✅ Sempre use o .env.example como template
cp .env.example .env

# ✅ Edite o .env com suas configurações
nano .env

# ✅ O .env está no .gitignore e não será commitado
```

## 📚 Estrutura do Autoload PSR-4

O projeto usa autoload PSR-4 com Composer:

```php
// composer.json
{
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    }
}
```

### Exemplo de uso:

```php
// src/Models/FreteCalculator.php
namespace App\Models;

class FreteCalculator {
    public function calcular($peso, $distancia) {
        // Lógica de cálculo
    }
}

// Em qualquer arquivo PHP
require_once 'vendor/autoload.php';

use App\Models\FreteCalculator;

$calculator = new FreteCalculator();
```

## 🐛 Resolução de Problemas

### Container não inicia

```bash
# Verificar logs
docker-compose logs web

# Reconstruir containers
docker-compose build --no-cache
docker-compose up -d
```

### Erro de conexão com banco

```bash
# Verificar se o banco está rodando
docker-compose ps

# Verificar configurações no .env
cat .env | grep DB_
```

### Problemas com autoload

```bash
# Regenerar autoload
docker-compose exec web composer dump-autoload --optimize
```

## 🤝 Contribuindo

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas mudanças (`git commit -m 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para detalhes.

## 👥 Autores

- **Seu Nome** - _Desenvolvimento inicial_ - [seu-usuario](https://github.com/seu-usuario)

## 🙏 Agradecimentos

- Bootstrap pela excelente framework CSS
- Docker pela containerização
- Composer pelo gerenciamento de dependências
- MySQL pela base de dados robusta

## Atenção:

$file = $this->findFileWithExtension($class, '.php');

        // Search for Hack files if we are running on HHVM
        if (false === $file && defined('HHVM_VERSION')) {
            $file = $this->findFileWithExtension($class, '.hh');
        }

        if (null !== $this->apcuPrefix && function_exists('apcu_add')) {
            apcu_add($this->apcuPrefix . $class, $file);
        }

        if (false === $file) {
            // Remember that this class does not exist.
            $this->missingClasses[$class] = true;
        }

no ClassLoader.php essas linhas foram removidas para evitar conflito, mas elas tem um papel importante de evitar invasão hacker no projeto em deploy, depois conferir.
