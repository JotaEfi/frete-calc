<?php
// Teste de diagnóstico para Railway
echo "<h1>Diagnóstico do Sistema - Railway</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;}</style>";

// 1. Verificar se o autoload está funcionando
echo "<h2>1. Testando Autoload do Composer</h2>";

if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
    echo "<span class='success'>✅ Autoload encontrado e carregado</span><br>";
} else {
    echo "<span class='error'>❌ Autoload não encontrado</span><br>";
    exit;
}

// 2. Verificar se as variáveis de ambiente estão definidas
echo "<h2>2. Verificando Variáveis de Ambiente</h2>";
$mysqlUrl = getenv('MYSQL_URL');
echo "MYSQL_URL: " . ($mysqlUrl ? "<span class='success'>✅ Definida: " . substr($mysqlUrl, 0, 50) . "...</span>" : "<span class='error'>❌ Não definida</span>") . "<br>";
echo "APP_SECRET: " . (getenv('APP_SECRET') ? "<span class='success'>✅ Definida</span>" : "<span class='error'>❌ Não definida</span>") . "<br>";
echo "PASSWORD_SALT: " . (getenv('PASSWORD_SALT') ? "<span class='success'>✅ Definida</span>" : "<span class='error'>❌ Não definida</span>") . "<br>";

// 3. Verificar estrutura de arquivos
echo "<h2>3. Verificando Estrutura de Arquivos</h2>";
$files = [
    'config/Environment.php',
    'config/Database.php',
    'models/Vehicle.php',
    'models/User.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "<span class='success'>✅ {$file} existe</span><br>";
    } else {
        echo "<span class='error'>❌ {$file} não encontrado</span><br>";
    }
}

// 4. Tentar carregar classes manualmente
echo "<h2>4. Testando Carregamento de Classes</h2>";

// Carregar Environment manualmente
if (file_exists('config/Environment.php')) {
    require_once 'config/Environment.php';
    echo "<span class='success'>✅ Environment.php carregado manualmente</span><br>";
    
    try {
        if (class_exists('App\\Config\\Environment')) {
            echo "<span class='success'>✅ Classe App\\Config\\Environment disponível</span><br>";
            
            // Testar método load
            App\Config\Environment::load();
            echo "<span class='success'>✅ Environment::load() executado</span><br>";
            
        } else {
            echo "<span class='error'>❌ Classe App\\Config\\Environment não encontrada após require</span><br>";
        }
    } catch (Exception $e) {
        echo "<span class='error'>❌ Erro ao usar Environment: " . $e->getMessage() . "</span><br>";
    }
} else {
    echo "<span class='error'>❌ config/Environment.php não encontrado</span><br>";
}

// 5. Testar conexão com banco (se MYSQL_URL estiver definida)
echo "<h2>5. Testando Conexão MySQL</h2>";

if (!$mysqlUrl) {
    echo "<span class='warning'>⚠️ Não é possível testar conexão MySQL - MYSQL_URL não configurada</span><br>";
    echo "<span class='warning'>📝 Configure a variável MYSQL_URL no Railway: <code>\${{ MySQL.MYSQL_URL }}</code></span><br>";
} else {
    try {
        // Tentar conexão direta usando a URL
        $parsed = parse_url($mysqlUrl);
        $host = $parsed['host'] ?? 'localhost';
        $port = $parsed['port'] ?? 3306;
        $dbname = ltrim($parsed['path'] ?? '/test', '/');
        $username = $parsed['user'] ?? 'root';
        $password = $parsed['pass'] ?? '';
        
        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        ]);
        
        echo "<span class='success'>✅ Conexão MySQL estabelecida</span><br>";
        echo "<span class='success'>✅ Host: {$host}:{$port}</span><br>";
        echo "<span class='success'>✅ Database: {$dbname}</span><br>";
        
        // Testar query
        $stmt = $pdo->query("SELECT 1 as test");
        $result = $stmt->fetch();
        echo "<span class='success'>✅ Query de teste executada: " . $result['test'] . "</span><br>";
        
        // Verificar tabelas
        echo "<h3>5.1 Verificando Tabelas</h3>";
        $tables = ['users', 'vehicles', 'cost_rules', 'trips'];
        foreach ($tables as $table) {
            $stmt = $pdo->query("SHOW TABLES LIKE '{$table}'");
            if ($stmt->rowCount() > 0) {
                echo "<span class='success'>✅ Tabela '{$table}' existe</span><br>";
            } else {
                echo "<span class='error'>❌ Tabela '{$table}' não existe</span><br>";
            }
        }
        
    } catch (Exception $e) {
        echo "<span class='error'>❌ Erro na conexão MySQL: " . $e->getMessage() . "</span><br>";
    }
}

// 6. Informações do sistema
echo "<h2>6. Informações do Sistema</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current Directory: " . getcwd() . "<br>";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "<br>";
echo "Script Path: " . __FILE__ . "<br>";

// 7. Resumo e próximos passos
echo "<h2>7. Resumo e Próximos Passos</h2>";

if (!$mysqlUrl) {
    echo "<div style='background:#ffebee;padding:15px;border-radius:5px;'>";
    echo "<h3 style='color:red;'>🚨 PROBLEMA PRINCIPAL: MYSQL_URL não configurada</h3>";
    echo "<p><strong>Para resolver:</strong></p>";
    echo "<ol>";
    echo "<li>Vá no painel do Railway</li>";
    echo "<li>Clique na aba <strong>Variables</strong> do seu serviço web</li>";
    echo "<li>Adicione uma nova variável:</li>";
    echo "<ul><li><strong>Nome:</strong> <code>MYSQL_URL</code></li>";
    echo "<li><strong>Valor:</strong> <code>\${{ MySQL.MYSQL_URL }}</code></li></ul>";
    echo "<li>Salve e aguarde o redeploy</li>";
    echo "</ol>";
    echo "</div>";
} else {
    echo "<div style='background:#e8f5e8;padding:15px;border-radius:5px;'>";
    echo "<h3 style='color:green;'>✅ Configuração do MySQL OK!</h3>";
    echo "<p>Sua aplicação deve estar funcionando corretamente agora.</p>";
    echo "</div>";
}

?>

<script>
// Auto-refresh a cada 30 segundos para monitorar mudanças
setTimeout(() => {
    location.reload();
}, 30000);

console.log('Diagnóstico Railway - Auto-refresh em 30s');
</script>
