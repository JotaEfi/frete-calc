<?php
// Configuração do header para JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
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
use App\Models\Vehicle;
use App\Models\CostRule;
use App\Models\Trip;


try {
    // Carregar configurações
    Environment::load();

    // Verificar se usuário é admin
    $user = JWTManager::requireAuth();

    if ($user['role'] !== 'admin') {
        throw new Exception('Acesso negado. Apenas administradores podem acessar esta API.');
    }

    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'dashboard':
            // Estatísticas do dashboard
            $stats = [
                'total_users' => User::count(),
                'total_vehicles' => Vehicle::count(),
                'total_trips' => Trip::count(),
                'total_rules' => CostRule::count()
            ];

            echo json_encode([
                'success' => true,
                'data' => $stats
            ]);
            break;

        case 'vehicles':
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $vehicles = Vehicle::all();
                $vehiclesArray = [];
                foreach ($vehicles as $vehicle) {
                    $vehiclesArray[] = $vehicle->toArray();
                }
                echo json_encode([
                    'success' => true,
                    'data' => $vehiclesArray
                ]);
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                if (!$input) {
                    throw new Exception('Dados não recebidos ou JSON inválido');
                }
                $id = Vehicle::create($input);
                $vehicle = Vehicle::find($id);
                echo json_encode([
                    'success' => true,
                    'data' => $vehicle ? $vehicle->toArray() : null,
                    'message' => 'Veículo criado com sucesso'
                ]);
            } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                $input = json_decode(file_get_contents('php://input'), true);
                if (!$input || !isset($input['id'])) {
                    throw new Exception('ID do veículo é obrigatório para atualização');
                }
                $ok = Vehicle::update($input['id'], $input);
                $vehicle = Vehicle::find($input['id']);
                echo json_encode([
                    'success' => $ok,
                    'data' => $vehicle ? $vehicle->toArray() : null,
                    'message' => 'Veículo atualizado com sucesso'
                ]);
            } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                $input = json_decode(file_get_contents('php://input'), true);
                if (!$input || !isset($input['id'])) {
                    throw new Exception('ID do veículo é obrigatório');
                }
                $ok = Vehicle::delete($input['id']);
                echo json_encode([
                    'success' => $ok,
                    'message' => 'Veículo deletado com sucesso'
                ]);
            }
            break;

        case 'rules':
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $rules = CostRule::all();
                $rulesArray = [];
                foreach ($rules as $rule) {
                    $rulesArray[] = $rule->toArray();
                }
                echo json_encode([
                    'success' => true,
                    'data' => $rulesArray
                ]);
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                if (!$input) {
                    throw new Exception('Dados não recebidos ou JSON inválido');
                }
                $id = CostRule::create($input);
                $rule = CostRule::find($id);
                echo json_encode([
                    'success' => true,
                    'data' => $rule ? $rule->toArray() : null,
                    'message' => 'Regra criada com sucesso'
                ]);
            } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                $input = json_decode(file_get_contents('php://input'), true);
                if (!$input || !isset($input['id'])) {
                    throw new Exception('ID da regra é obrigatório para atualização');
                }
                $ok = CostRule::update($input['id'], $input);
                $rule = CostRule::find($input['id']);
                echo json_encode([
                    'success' => $ok,
                    'data' => $rule ? $rule->toArray() : null,
                    'message' => 'Regra atualizada com sucesso'
                ]);
            } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                $input = json_decode(file_get_contents('php://input'), true);
                if (!$input || !isset($input['id'])) {
                    throw new Exception('ID da regra é obrigatório');
                }
                $ok = CostRule::delete($input['id']);
                echo json_encode([
                    'success' => $ok,
                    'message' => 'Regra deletada com sucesso'
                ]);
            }
            break;

        case 'users':
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $users = User::all();
                $usersArray = [];
                foreach ($users as $userObj) {
                    $userArray = $userObj->toArray();
                    unset($userArray['password']);
                    $usersArray[] = $userArray;
                }
                echo json_encode([
                    'success' => true,
                    'data' => $usersArray
                ]);
            } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                $input = json_decode(file_get_contents('php://input'), true);
                if (!$input || !isset($input['id'])) {
                    throw new Exception('ID do usuário é obrigatório');
                }
                $ok = User::updateRole($input['id'], $input['role'] ?? null);
                $targetUser = User::find($input['id']);
                $userArray = $targetUser ? $targetUser->toArray() : null;
                unset($userArray['password']);
                echo json_encode([
                    'success' => $ok,
                    'data' => $userArray,
                    'message' => 'Usuário atualizado com sucesso'
                ]);
            } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                $input = json_decode(file_get_contents('php://input'), true);
                if (!$input || !isset($input['id'])) {
                    throw new Exception('ID do usuário é obrigatório');
                }
                if ($input['id'] == $user['user_id']) {
                    throw new Exception('Você não pode deletar sua própria conta');
                }
                $ok = User::delete($input['id']);
                echo json_encode([
                    'success' => $ok,
                    'message' => 'Usuário deletado com sucesso'
                ]);
            }
            break;

        case 'trips':
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $trips = method_exists(Trip::class, 'allWithDetails') ? Trip::allWithDetails() : Trip::all();
                $tripsArray = [];
                foreach ($trips as $trip) {
                    $tripsArray[] = $trip->toArray();
                }
                echo json_encode([
                    'success' => true,
                    'data' => $tripsArray
                ]);
            } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                $input = json_decode(file_get_contents('php://input'), true);
                if (!$input || !isset($input['id'])) {
                    throw new Exception('ID da viagem é obrigatório');
                }
                $ok = Trip::delete($input['id']);
                echo json_encode([
                    'success' => $ok,
                    'message' => 'Viagem deletada com sucesso'
                ]);
            }
            break;

        default:
            throw new Exception('Ação não encontrada: ' . $action);
    }
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
