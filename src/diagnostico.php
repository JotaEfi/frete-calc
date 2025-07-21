<?php
// Teste de diagnóstico para Railway
echo "<h1>Diagnóstico do Sistema - Railway</h1>";

// 1. Verificar se o autoload está funcionando
echo "<h2>1. Testando Autoload do Composer</h2>";

if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
    echo "✅ Autoload encontrado e carregado<br>";
} else {
    echo "❌ Autoload não encontrado<br>";
    exit;
}

// 2. Verificar se as variáveis de ambiente estão definidas
echo "<h2>2. Verificando Variáveis de Ambiente</h2>";
echo "MYSQL_URL: " . (getenv('MYSQL_URL') ?: '❌ Não definida') . "<br>";
echo "APP_SECRET: " . (getenv('APP_SECRET') ? '✅ Definida' : '❌ Não definida') . "<br>";
echo "PASSWORD_SALT: " . (getenv('PASSWORD_SALT') ? '✅ Definida' : '❌ Não definida') . "<br>";

// 3. Testar carregamento da classe Environment
echo "<h2>3. Testando Classe Environment</h2>";
try {
    $envClass = new App\Config\Environment();
    echo "✅ Classe Environment carregada com sucesso<br>";
} catch (Exception $e) {
    echo "❌ Erro ao carregar Environment: " . $e->getMessage() . "<br>";
}

// 4. Testar conexão com banco
echo "<h2>4. Testando Conexão MySQL</h2>";
try {
    // Tentar carregar configurações
    App\Config\Environment::load();
    echo "✅ Configurações carregadas<br>";
    
    // Tentar conectar com banco
    $pdo = App\Config\Database::getConnection();
    echo "✅ Conexão com MySQL estabelecida<br>";
    
    // Testar uma query simples
    $stmt = $pdo->query("SELECT 1 as test");
    $result = $stmt->fetch();
    echo "✅ Query de teste executada: " . $result['test'] . "<br>";
    
} catch (Exception $e) {
    echo "❌ Erro na conexão MySQL: " . $e->getMessage() . "<br>";
    echo "Detalhes: " . $e->getTraceAsString() . "<br>";
}

// 5. Verificar se as tabelas existem
echo "<h2>5. Verificando Tabelas do Banco</h2>";
try {
    $pdo = App\Config\Database::getConnection();
    
    $tables = ['users', 'vehicles', 'cost_rules', 'trips'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '{$table}'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Tabela '{$table}' existe<br>";
        } else {
            echo "❌ Tabela '{$table}' não existe<br>";
        }
    }
} catch (Exception $e) {
    echo "❌ Erro ao verificar tabelas: " . $e->getMessage() . "<br>";
}

echo "<h2>6. Informações do Sistema</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current Directory: " . getcwd() . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";

?>