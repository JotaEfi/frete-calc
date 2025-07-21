<?php
// Configuração do header para JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Sistema de carregamento robusto para Railway
function loadAutoloader() {
    $paths = [
        'vendor/autoload.php',
        __DIR__ . '/vendor/autoload.php',
        __DIR__ . '/../vendor/autoload.php',
        '/var/www/html/vendor/autoload.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return true;
        }
    }
    return false;
}

if (!loadAutoloader()) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Autoload não encontrado']);
    exit;
}

// Carregar classes manualmente se necessário (fallback)
function loadClassManually($className) {
    $classMap = [
        'App\\Config\\Environment' => __DIR__ . '/config/Environment.php',
        'App\\Config\\Database' => __DIR__ . '/config/Database.php',
        'App\\Config\\JWTManager' => __DIR__ . '/config/JWTManager.php',
        'App\\Models\\User' => __DIR__ . '/models/User.php',
    ];
    
    if (isset($classMap[$className]) && file_exists($classMap[$className])) {
        require_once $classMap[$className];
        return true;
    }
    return false;
}

// Registrar autoloader manual como fallback
spl_autoload_register('loadClassManually');

// Tentar carregar classes necessárias
try {
    if (!class_exists('App\\Config\\Environment')) {
        loadClassManually('App\\Config\\Environment');
    }
    if (!class_exists('App\\Config\\JWTManager')) {
        loadClassManually('App\\Config\\JWTManager');
    }
    if (!class_exists('App\\Models\\User')) {
        loadClassManually('App\\Models\\User');
    }
} catch (Exception $e) {
    error_log("Erro ao carregar classes: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erro ao carregar classes necessárias']);
    exit;
}

use App\Config\Environment;
use App\Config\JWTManager;
use App\Models\User;

// Carregar configurações de forma segura
try {
    if (class_exists('App\\Config\\Environment')) {
        Environment::load();
    }
} catch (Exception $e) {
    error_log("Erro ao carregar environment: " . $e->getMessage());
}

try {
    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'login':
            // Login do usuário
            $input = json_decode(file_get_contents('php://input'), true);

            if (!$input) {
                throw new Exception('Dados não recebidos ou JSON inválido');
            }

            if (!isset($input['email']) || !isset($input['password'])) {
                throw new Exception('Email e senha são obrigatórios');
            }

            // Verificar se o usuário existe
            $user = User::findByEmail($input['email']);

            if (!$user) {
                throw new Exception('Usuário não encontrado');
            }

            if (!$user->verifyPassword($input['password'])) {
                throw new Exception('Senha incorreta');
            }

            // Gerar token JWT
            $token = JWTManager::generateToken($user->toArray());

            echo json_encode([
                'success' => true,
                'token' => $token,
                'user' => $user->toArray()
            ]);
            break;

        case 'register':
            // Registro de novo usuário
            $input = json_decode(file_get_contents('php://input'), true);

            if (!$input) {
                throw new Exception('Dados não recebidos ou JSON inválido');
            }

            if (!isset($input['name']) || !isset($input['email']) || !isset($input['password'])) {
                throw new Exception('Nome, email e senha são obrigatórios');
            }

            // Validação básica
            if (strlen($input['password']) < 6) {
                throw new Exception('A senha deve ter pelo menos 6 caracteres');
            }

            if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email inválido');
            }

            // Verificar se email já existe
            $existingUser = User::findByEmail($input['email']);
            if ($existingUser) {
                throw new Exception('Este email já está cadastrado');
            }

            // Criar usuário
            $user = User::create(
                $input['name'],
                $input['email'],
                $input['password'],
                'user' // Novo usuário sempre começa como 'user'
            );

            if (!$user) {
                throw new Exception('Erro ao criar usuário');
            }

            // Gerar token JWT
            $token = JWTManager::generateToken($user->toArray());

            echo json_encode([
                'success' => true,
                'token' => $token,
                'user' => $user->toArray()
            ]);
            break;

        case 'me':
            // Retorna dados do usuário autenticado
            $user = JWTManager::requireAuth();

            // Buscar dados atualizados do usuário no banco
            $userModel = User::find($user['user_id']);

            if (!$userModel) {
                throw new Exception('Usuário não encontrado');
            }

            echo json_encode([
                'success' => true,
                'user' => $userModel->toArray()
            ]);
            break;

        case 'refresh':
            // Renova o token JWT
            $user = JWTManager::requireAuth();

            // Buscar dados atualizados do usuário
            $userModel = User::find($user['user_id']);

            if (!$userModel) {
                throw new Exception('Usuário não encontrado');
            }

            // Gerar novo token
            $newToken = JWTManager::generateToken($userModel->toArray());

            echo json_encode([
                'success' => true,
                'token' => $newToken,
                'user' => $userModel->toArray()
            ]);
            break;

        default:
            throw new Exception('Ação não encontrada: ' . $action);
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} catch (Error $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erro fatal: ' . $e->getMessage()
    ]);
}
?>
