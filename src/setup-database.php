<?php
// Script para criar as tabelas do banco de dados
header('Content-Type: text/html; charset=utf-8');
echo "<h1>Setup do Banco de Dados - Railway</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;} .info{color:blue;}</style>";

// Carregar autoload
if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
} else {
    die('<span class="error">❌ Autoload não encontrado</span>');
}

// Carregar classes manualmente
if (file_exists('config/Environment.php')) {
    require_once 'config/Environment.php';
}
if (file_exists('config/Database.php')) {
    require_once 'config/Database.php';
}

use App\Config\Environment;
use App\Config\Database;

echo "<h2>1. Carregando Configurações</h2>";
try {
    Environment::load();
    echo "<span class='success'>✅ Configurações carregadas</span><br>";
} catch (Exception $e) {
    echo "<span class='error'>❌ Erro ao carregar configurações: " . $e->getMessage() . "</span><br>";
    exit;
}

echo "<h2>2. Conectando ao Banco de Dados</h2>";
try {
    $pdo = Database::getConnection();
    echo "<span class='success'>✅ Conexão estabelecida</span><br>";
} catch (Exception $e) {
    echo "<span class='error'>❌ Erro na conexão: " . $e->getMessage() . "</span><br>";
    exit;
}

echo "<h2>3. Criando Tabelas</h2>";

// SQL para criar as tabelas
$sqlStatements = [
    // Tabela de usuários
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('user', 'admin') DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Tabela de veículos
    "CREATE TABLE IF NOT EXISTS vehicles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        type VARCHAR(100) NOT NULL,
        fuel_consumption DECIMAL(8,2) NOT NULL COMMENT 'km por litro',
        fixed_cost_per_hour DECIMAL(10,2) NOT NULL COMMENT 'custo fixo por hora',
        depreciation_maintenance DECIMAL(10,2) NOT NULL COMMENT 'depreciação + manutenção por km',
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Tabela de regras de custo
    "CREATE TABLE IF NOT EXISTS cost_rules (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type VARCHAR(50) NOT NULL COMMENT 'fuel_price, ad_valorem, gris, icms',
        name VARCHAR(255) NOT NULL,
        value DECIMAL(10,4) NOT NULL COMMENT 'valor ou percentual',
        is_percentage BOOLEAN DEFAULT FALSE COMMENT 'se é percentual ou valor fixo',
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Tabela de viagens/cálculos
    "CREATE TABLE IF NOT EXISTS trips (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        vehicle_id INT NOT NULL,
        origin VARCHAR(255) NOT NULL,
        destination VARCHAR(255) NOT NULL,
        distance DECIMAL(10,2) NOT NULL COMMENT 'distância em km',
        cargo_value DECIMAL(10,2) NOT NULL COMMENT 'valor da carga',
        weight DECIMAL(10,2) DEFAULT NULL COMMENT 'peso da carga em kg',
        travel_time_hours DECIMAL(6,2) NOT NULL COMMENT 'tempo de viagem em horas',
        fuel_cost DECIMAL(10,2) NOT NULL,
        fixed_cost DECIMAL(10,2) NOT NULL,
        maintenance_cost DECIMAL(10,2) NOT NULL,
        ad_valorem_cost DECIMAL(10,2) DEFAULT 0,
        gris_cost DECIMAL(10,2) DEFAULT 0,
        icms_cost DECIMAL(10,2) DEFAULT 0,
        total_cost DECIMAL(10,2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
];

foreach ($sqlStatements as $index => $sql) {
    try {
        $pdo->exec($sql);
        $tableName = ['users', 'vehicles', 'cost_rules', 'trips'][$index];
        echo "<span class='success'>✅ Tabela '{$tableName}' criada com sucesso</span><br>";
    } catch (Exception $e) {
        echo "<span class='error'>❌ Erro ao criar tabela: " . $e->getMessage() . "</span><br>";
    }
}

echo "<h2>4. Inserindo Dados Iniciais</h2>";

// Verificar se já existem dados
$stmt = $pdo->query("SELECT COUNT(*) as count FROM vehicles");
$vehicleCount = $stmt->fetch()['count'];

if ($vehicleCount == 0) {
    echo "<span class='info'>📝 Inserindo veículos padrão...</span><br>";
    
    $vehicles = [
        ['Caminhão Truck', 'truck', 3.5, 25.00, 0.80, 'Caminhão para cargas pesadas'],
        ['Van de Carga', 'van', 8.0, 15.00, 0.45, 'Van para entregas urbanas'],
        ['Carreta', 'carreta', 2.8, 35.00, 1.20, 'Carreta para longas distâncias']
    ];
    
    $stmt = $pdo->prepare("
        INSERT INTO vehicles (name, type, fuel_consumption, fixed_cost_per_hour, depreciation_maintenance, description)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    foreach ($vehicles as $vehicle) {
        try {
            $stmt->execute($vehicle);
            echo "<span class='success'>✅ Veículo '{$vehicle[0]}' inserido</span><br>";
        } catch (Exception $e) {
            echo "<span class='error'>❌ Erro ao inserir veículo: " . $e->getMessage() . "</span><br>";
        }
    }
}

// Verificar regras de custo
$stmt = $pdo->query("SELECT COUNT(*) as count FROM cost_rules");
$rulesCount = $stmt->fetch()['count'];

if ($rulesCount == 0) {
    echo "<span class='info'>📝 Inserindo regras de custo padrão...</span><br>";
    
    $rules = [
        ['fuel_price', 'Preço do Combustível', 6.50, 0, 'Preço médio do diesel por litro'],
        ['ad_valorem', 'Taxa Ad Valorem', 0.0030, 1, 'Taxa de 0.3% sobre o valor da carga'],
        ['gris', 'GRIS', 0.0015, 1, 'Gerenciamento de Risco - 0.15% sobre valor da carga'],
        ['icms', 'ICMS', 0.12, 1, 'Imposto sobre Circulação de Mercadorias - 12%']
    ];
    
    $stmt = $pdo->prepare("
        INSERT INTO cost_rules (type, name, value, is_percentage, description)
        VALUES (?, ?, ?, ?, ?)
    ");
    
    foreach ($rules as $rule) {
        try {
            $stmt->execute($rule);
            echo "<span class='success'>✅ Regra '{$rule[1]}' inserida</span><br>";
        } catch (Exception $e) {
            echo "<span class='error'>❌ Erro ao inserir regra: " . $e->getMessage() . "</span><br>";
        }
    }
}

// Criar usuário admin padrão se não existir
$stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role = 'admin'");
$adminCount = $stmt->fetch()['count'];

if ($adminCount == 0) {
    echo "<span class='info'>📝 Criando usuário administrador padrão...</span><br>";
    
    $adminEmail = 'admin@fretecalc.com';
    $adminPassword = password_hash('admin123', PASSWORD_BCRYPT);
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password, role)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute(['Administrador', $adminEmail, $adminPassword, 'admin']);
        echo "<span class='success'>✅ Usuário admin criado!</span><br>";
        echo "<span class='info'>📋 Login: admin@fretecalc.com | Senha: admin123</span><br>";
    } catch (Exception $e) {
        echo "<span class='error'>❌ Erro ao criar admin: " . $e->getMessage() . "</span><br>";
    }
}

echo "<h2>5. Verificação Final</h2>";

$tables = ['users', 'vehicles', 'cost_rules', 'trips'];
foreach ($tables as $table) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM {$table}");
        $count = $stmt->fetch()['count'];
        echo "<span class='success'>✅ Tabela '{$table}': {$count} registros</span><br>";
    } catch (Exception $e) {
        echo "<span class='error'>❌ Erro ao verificar tabela '{$table}': " . $e->getMessage() . "</span><br>";
    }
}

echo "<h2>🎉 Setup Concluído!</h2>";
echo "<div style='background:#e8f5e8;padding:15px;border-radius:5px;margin:20px 0;'>";
echo "<h3 style='color:green;'>✅ Banco de dados configurado com sucesso!</h3>";
echo "<p><strong>Próximos passos:</strong></p>";
echo "<ol>";
echo "<li>Acesse sua aplicação: <a href='index.php' target='_blank'>Página Inicial</a></li>";
echo "<li>Faça login como admin: <a href='auth.php' target='_blank'>Login</a> (admin@fretecalc.com / admin123)</li>";
echo "<li>Teste o cálculo de frete: <a href='calcular.php' target='_blank'>Calcular Frete</a></li>";
echo "<li>Gerencie o sistema: <a href='admin.php' target='_blank'>Painel Admin</a></li>";
echo "</ol>";
echo "</div>";

echo "<p><em>Você pode executar este script novamente se precisar recriar as tabelas.</em></p>";
?>