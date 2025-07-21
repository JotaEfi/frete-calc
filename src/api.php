<?php
// Configuração do header para JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Carregar autoload do Composer
require_once 'vendor/autoload.php';

use App\Config\Environment;
use App\Config\JWTManager;
use App\Models\Vehicle;
use App\Models\CostRule;
use App\Models\Trip;
use App\Models\User;

// Carregar configurações
Environment::load();

try {
    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'vehicles':
            // Retorna todos os veículos
            $vehicles = Vehicle::all();
            $vehiclesArray = array_map(function ($vehicle) {
                return $vehicle->toArray();
            }, $vehicles);

            echo json_encode([
                'success' => true,
                'data' => $vehiclesArray
            ]);
            break;

        case 'cost-rules':
            // Retorna todas as regras de custo
            $rules = CostRule::all();
            $rulesArray = array_map(function ($rule) {
                return $rule->toArray();
            }, $rules);

            echo json_encode([
                'success' => true,
                'data' => $rulesArray
            ]);
            break;

        case 'vehicle':
            // Retorna um veículo específico
            $id = $_GET['id'] ?? 0;
            $vehicle = Vehicle::find($id);

            if ($vehicle) {
                echo json_encode([
                    'success' => true,
                    'data' => $vehicle->toArray()
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Veículo não encontrado'
                ]);
            }
            break;

        case 'calculate':
            // Calcula o frete e salva no histórico
            $user = JWTManager::requireAuth();
            $input = json_decode(file_get_contents('php://input'), true);

            if (!$input) {
                throw new Exception('Dados inválidos');
            }

            $vehicleId = $input['vehicle_id'] ?? 0;
            $distance = $input['distance'] ?? 0;
            $weight = $input['weight'] ?? 0;
            $cargoValue = $input['cargo_value'] ?? 0;
            $travelTime = $input['travel_time'] ?? 0;
            $origin = $input['origin'] ?? null;
            $destination = $input['destination'] ?? null;

            // Buscar veículo
            $vehicle = Vehicle::find($vehicleId);
            if (!$vehicle) {
                throw new Exception('Veículo não encontrado');
            }

            // Buscar regras de custo
            $fuelPrice = CostRule::getByType('fuel_price');
            $adValorem = CostRule::getByType('ad_valorem');
            $gris = CostRule::getByType('gris');
            $icms = CostRule::getByType('icms');

            // Calcular custos
            $fuelCost = 0;
            if ($fuelPrice) {
                $fuelConsumption = $distance / $vehicle->getFuelConsumption();
                $fuelCost = $fuelConsumption * $fuelPrice->getValue();
            }

            $fixedCost = $vehicle->getFixedCostPerHour() * $travelTime;
            $maintenanceCost = $vehicle->getDepreciationMaintenance() * $distance;

            // Calcular taxas
            $adValoremCost = $adValorem ? $adValorem->calcular($cargoValue) : 0;
            $grisCost = $gris ? $gris->calcular($cargoValue) : 0;
            $icmsCost = $icms ? $icms->calcular($cargoValue) : 0;

            $totalCost = $fuelCost + $fixedCost + $maintenanceCost + $adValoremCost + $grisCost + $icmsCost;

            // Salvar no histórico
            $tripData = [
                'user_id' => $user['user_id'],
                'vehicle_id' => $vehicleId,
                'origin' => $origin,
                'destination' => $destination,
                'distance' => $distance,
                'cargo_value' => $cargoValue,
                'weight' => $weight,
                'travel_time_hours' => $travelTime,
                'fuel_cost' => $fuelCost,
                'fixed_cost' => $fixedCost,
                'maintenance_cost' => $maintenanceCost,
                'ad_valorem_cost' => $adValoremCost,
                'gris_cost' => $grisCost,
                'icms_cost' => $icmsCost,
                'total_cost' => $totalCost
            ];

            $tripId = Trip::create($tripData);

            echo json_encode([
                'success' => true,
                'data' => [
                    'trip_id' => $tripId,
                    'fuel_cost' => round($fuelCost, 2),
                    'fixed_cost' => round($fixedCost, 2),
                    'maintenance_cost' => round($maintenanceCost, 2),
                    'ad_valorem_cost' => round($adValoremCost, 2),
                    'gris_cost' => round($grisCost, 2),
                    'icms_cost' => round($icmsCost, 2),
                    'total_cost' => round($totalCost, 2),
                    'vehicle_name' => $vehicle->getName()
                ]
            ]);
            break;

        case 'history':
            // Retorna histórico do usuário
            $user = JWTManager::requireAuth();
            $limit = $_GET['limit'] ?? 50;

            $trips = Trip::getByUser($user['user_id'], $limit);
            $stats = Trip::getStats($user['user_id']);

            echo json_encode([
                'success' => true,
                'data' => [
                    'trips' => $trips,
                    'stats' => $stats
                ]
            ]);
            break;

        case 'delete-trip':
            // Deleta uma viagem do histórico
            $user = JWTManager::requireAuth();
            $tripId = $_GET['trip_id'] ?? 0;

            // Verificar se a viagem pertence ao usuário (segurança)
            $pdo = \App\Config\Database::getConnection();
            $stmt = $pdo->prepare("SELECT user_id FROM trips WHERE id = ?");
            $stmt->execute([$tripId]);
            $trip = $stmt->fetch();

            if (!$trip) {
                throw new Exception('Viagem não encontrada');
            }

            if ($trip['user_id'] != $user['user_id']) {
                throw new Exception('Você não tem permissão para deletar esta viagem');
            }

            Trip::delete($tripId);

            echo json_encode([
                'success' => true,
                'message' => 'Viagem removida do histórico'
            ]);
            break;

        default:
            throw new Exception('Ação não encontrada');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
