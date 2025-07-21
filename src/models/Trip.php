<?php

namespace App\Models;

use App\Config\Database;
use Exception;

class Trip
{
    private $id;
    private $user_id;
    private $vehicle_id;
    private $origin;
    private $destination;
    private $distance;
    private $cargo_value;
    private $weight;
    private $travel_time_hours;
    private $fuel_cost;
    private $fixed_cost;
    private $maintenance_cost;
    private $ad_valorem_cost;
    private $gris_cost;
    private $icms_cost;
    private $total_cost;
    private $created_at;

    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->fill($data);
        }
    }

    private function fill($data)
    {
        $this->id = $data['id'] ?? null;
        $this->user_id = $data['user_id'] ?? null;
        $this->vehicle_id = $data['vehicle_id'] ?? null;
        $this->origin = $data['origin'] ?? '';
        $this->destination = $data['destination'] ?? '';
        $this->distance = $data['distance'] ?? 0;
        $this->cargo_value = $data['cargo_value'] ?? 0;
        $this->weight = $data['weight'] ?? 0;
        $this->travel_time_hours = $data['travel_time_hours'] ?? 0;
        $this->fuel_cost = $data['fuel_cost'] ?? 0;
        $this->fixed_cost = $data['fixed_cost'] ?? 0;
        $this->maintenance_cost = $data['maintenance_cost'] ?? 0;
        $this->ad_valorem_cost = $data['ad_valorem_cost'] ?? 0;
        $this->gris_cost = $data['gris_cost'] ?? 0;
        $this->icms_cost = $data['icms_cost'] ?? 0;
        $this->total_cost = $data['total_cost'] ?? 0;
        $this->created_at = $data['created_at'] ?? null;
    }

    /**
     * Salva uma nova viagem no histórico
     */
    public static function create($data)
    {
        try {
            $pdo = Database::getConnection();

            $sql = "INSERT INTO trips (
                        user_id, vehicle_id, origin, destination, distance, cargo_value, 
                        weight, travel_time_hours, fuel_cost, fixed_cost, maintenance_cost,
                        ad_valorem_cost, gris_cost, icms_cost, total_cost, created_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $data['user_id'],
                $data['vehicle_id'],
                $data['origin'] ?? null,
                $data['destination'] ?? null,
                $data['distance'],
                $data['cargo_value'],
                $data['weight'],
                $data['travel_time_hours'],
                $data['fuel_cost'],
                $data['fixed_cost'],
                $data['maintenance_cost'],
                $data['ad_valorem_cost'],
                $data['gris_cost'],
                $data['icms_cost'],
                $data['total_cost']
            ]);

            return $pdo->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Erro ao salvar viagem: " . $e->getMessage());
        }
    }

    /**
     * Busca todas as viagens (para admin)
     */
    public static function all($limit = 100)
    {
        try {
            $pdo = Database::getConnection();
            $sql = "SELECT t.*, u.name as user_name, v.name as vehicle_name 
                    FROM trips t 
                    LEFT JOIN users u ON t.user_id = u.id 
                    LEFT JOIN vehicles v ON t.vehicle_id = v.id 
                    ORDER BY t.created_at DESC 
                    LIMIT ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("Erro ao buscar viagens: " . $e->getMessage());
        }
    }

    /**
     * Busca viagens por usuário
     */
    public static function getByUser($userId, $limit = 50)
    {
        try {
            $pdo = Database::getConnection();
            $sql = "SELECT t.*, v.name as vehicle_name 
                    FROM trips t 
                    LEFT JOIN vehicles v ON t.vehicle_id = v.id 
                    WHERE t.user_id = ? 
                    ORDER BY t.created_at DESC 
                    LIMIT ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId, $limit]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("Erro ao buscar viagens do usuário: " . $e->getMessage());
        }
    }

    /**
     * Estatísticas de viagens
     */
    public static function getStats($userId = null)
    {
        try {
            $pdo = Database::getConnection();

            $whereClause = $userId ? "WHERE t.user_id = ?" : "";
            $params = $userId ? [$userId] : [];

            $sql = "SELECT 
                        COUNT(*) as total_trips,
                        COALESCE(SUM(t.total_cost), 0) as total_revenue,
                        COALESCE(AVG(t.total_cost), 0) as avg_cost,
                        COALESCE(SUM(t.distance), 0) as total_distance,
                        COALESCE(AVG(t.distance), 0) as avg_distance
                    FROM trips t 
                    $whereClause";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception("Erro ao buscar estatísticas: " . $e->getMessage());
        }
    }

    /**
     * Deleta uma viagem
     */
    public static function delete($id)
    {
        try {
            $pdo = Database::getConnection();
            $sql = "DELETE FROM trips WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            throw new Exception("Erro ao deletar viagem: " . $e->getMessage());
        }
    }

    /**
     * Conta o total de viagens
     */
    public static function count()
    {
        try {
            $pdo = Database::getConnection();
            $sql = "SELECT COUNT(*) as total FROM trips";
            $stmt = $pdo->query($sql);
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            throw new Exception("Erro ao contar viagens: " . $e->getMessage());
        }
    }

    /**
     * Busca uma viagem por ID
     */
    public static function find($id)
    {
        try {
            $pdo = Database::getConnection();
            $sql = "SELECT t.*, u.name as user_name, v.name as vehicle_name FROM trips t LEFT JOIN users u ON t.user_id = u.id LEFT JOIN vehicles v ON t.vehicle_id = v.id WHERE t.id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $data = $stmt->fetch();
            return $data ? new self($data) : null;
        } catch (Exception $e) {
            throw new Exception("Erro ao buscar viagem: " . $e->getMessage());
        }
    }

    /**
     * Busca todas as viagens com detalhes (para admin)
     */
    public static function allWithDetails($limit = 100)
    {
        try {
            $pdo = Database::getConnection();
            $sql = "SELECT t.*, u.name as user_name, v.name as vehicle_name FROM trips t LEFT JOIN users u ON t.user_id = u.id LEFT JOIN vehicles v ON t.vehicle_id = v.id ORDER BY t.created_at DESC LIMIT ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$limit]);
            $result = [];
            while ($data = $stmt->fetch()) {
                $result[] = (new self($data))->toArray();
            }
            return $result;
        } catch (Exception $e) {
            throw new Exception("Erro ao buscar viagens detalhadas: " . $e->getMessage());
        }
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getUserId()
    {
        return $this->user_id;
    }
    public function getVehicleId()
    {
        return $this->vehicle_id;
    }
    public function getTotalCost()
    {
        return $this->total_cost;
    }
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Retorna dados como array para JSON
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'vehicle_id' => $this->vehicle_id,
            'origin' => $this->origin,
            'destination' => $this->destination,
            'distance' => $this->distance,
            'cargo_value' => $this->cargo_value,
            'weight' => $this->weight,
            'travel_time_hours' => $this->travel_time_hours,
            'fuel_cost' => $this->fuel_cost,
            'fixed_cost' => $this->fixed_cost,
            'maintenance_cost' => $this->maintenance_cost,
            'ad_valorem_cost' => $this->ad_valorem_cost,
            'gris_cost' => $this->gris_cost,
            'icms_cost' => $this->icms_cost,
            'total_cost' => $this->total_cost,
            'created_at' => $this->created_at
        ];
    }
}
