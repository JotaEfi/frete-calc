<?php
// Configurações da página
$title = "Teste de Configuração - FreteCalc";
$active_page = "config";

// Carregar autoload do Composer
require_once 'vendor/autoload.php';

// Carregar configurações do .env
use App\Config\Environment;
use App\Config\Database;

// Incluir header
include 'includes/header.php';

// Carregar configurações
Environment::load();
?>

<!-- Conteúdo da página -->
<div class="container my-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-gear"></i> Configurações do Sistema
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Configurações da Aplicação</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Nome da Aplicação:</span>
                                    <strong><?= Environment::get('APP_NAME', 'N/A') ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Ambiente:</span>
                                    <span class="badge bg-<?= Environment::isProduction() ? 'danger' : 'success' ?>">
                                        <?= Environment::getEnvironment() ?>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Modo Debug:</span>
                                    <span class="badge bg-<?= Environment::isDebug() ? 'warning' : 'secondary' ?>">
                                        <?= Environment::isDebug() ? 'Ativo' : 'Inativo' ?>
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>URL da Aplicação:</span>
                                    <strong><?= Environment::get('APP_URL', 'N/A') ?></strong>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Configurações do Banco de Dados</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Host:</span>
                                    <strong><?= Environment::get('DB_HOST', 'N/A') ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Porta:</span>
                                    <strong><?= Environment::get('DB_PORT', 'N/A') ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Banco:</span>
                                    <strong><?= Environment::get('DB_DATABASE', 'N/A') ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Usuário:</span>
                                    <strong><?= Environment::get('DB_USERNAME', 'N/A') ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Conexão:</span>
                                    <?php if (Database::testConnection()): ?>
                                                <span class="badge bg-success">Conectado</span>
                                    <?php else: ?>
                                                <span class="badge bg-danger">Falha na conexão</span>
                                    <?php endif; ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Configurações de Portas</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Porta Web:</span>
                                    <strong><?= Environment::get('WEB_PORT', 'N/A') ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Porta MySQL:</span>
                                    <strong><?= Environment::get('DB_PORT', 'N/A') ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Porta phpMyAdmin:</span>
                                    <strong><?= Environment::get('PHPMYADMIN_PORT', 'N/A') ?></strong>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Configurações de Segurança</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Chave Secreta:</span>
                                    <strong><?= Environment::has('APP_SECRET') ? 'Configurada' : 'Não configurada' ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Salt de Senha:</span>
                                    <strong><?= Environment::has('PASSWORD_SALT') ? 'Configurado' : 'Não configurado' ?></strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <?php if (Environment::isDebug()): ?>
                            <hr>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                                <strong>Modo Debug Ativo!</strong> 
                                Em produção, certifique-se de definir <code>APP_DEBUG=false</code> no arquivo .env
                            </div>
                    <?php endif; ?>
                    
                    <div class="mt-4">
                        <h5>Como usar o .env:</h5>
                        <pre class="bg-light p-3 rounded"><code># Exemplo de uso da classe Environment
use App\Config\Environment;

// Carregar configurações
Environment::load();

// Obter configuração específica
$appName = Environment::get('APP_NAME', 'Valor padrão');

// Verificar se está em debug
if (Environment::isDebug()) {
    echo "Debug ativo";
}

// Obter configurações do banco
$dbConfig = Environment::database();</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>