<?php
// Configuração do header para JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Carregar autoload do Composer - com fallback para diferentes ambientes
if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    die(json_encode(['success' => false, 'error' => 'Autoload não encontrado. Execute: composer install']));
}

use App\Config\Environment;
use App\Config\JWTManager;
use App\Models\User;

// Carregar configurações
try {
    Environment::load();
} catch (Exception $e) {
    error_log("Erro ao carregar environment: " . $e->getMessage());
}

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
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
} catch (Error $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erro fatal: ' . $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
