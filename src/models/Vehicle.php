<?php

namespace App\Models;

use App\Config\Database;

class Vehicle
{
    private $id;
    private $name;
    private $fuel_consumption;
    private $fixed_cost_per_hour;
    private $depreciation_maintenance;

    public function __construct($data = []){
        if(!empty($data)){
            $this->fill($data);
            
        } 
    }
    
    private function fill($data)
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->fuel_consumption = $data['fuel_consumption'] ?? 0;
        $this->fixed_cost_per_hour = $data['fixed_cost_per_hour'] ?? 0;
        $this->depreciation_maintenance = $data['depreciation_maintenance'] ?? 0;
    }

    public static function find($id)
    {
        $pdo = Database::getConnection();
        $sql = "SELECT * FROM vehicles WHERE id = ? AND is_active = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $data = $stmt->fetch();
        return $data ? new self($data) : null;
    }

    public static function all()
    {
        $pdo = Database::getConnection();
        $sql = "SELECT * FROM vehicles WHERE is_active = 1 ORDER BY name";
        $stmt = $pdo->query($sql);
        $vehicles = [];
        while ($data = $stmt->fetch()) {
            $vehicles[] = new self($data);
        }
        return $vehicles;
    }

    public static function create($data)
    {
        $pdo = Database::getConnection();
        $sql = "INSERT INTO vehicles (name, fuel_consumption, fixed_cost_per_hour, depreciation_maintenance, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $data['name'],
            $data['fuel_consumption'],
            $data['fixed_cost_per_hour'],
            $data['depreciation_maintenance']
        ]);
        return $pdo->lastInsertId();
    }

    public static function update($id, $data)
    {
        $pdo = Database::getConnection();
        $sql = "UPDATE vehicles SET name = ?, fuel_consumption = ?, fixed_cost_per_hour = ?, depreciation_maintenance = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['fuel_consumption'],
            $data['fixed_cost_per_hour'],
            $data['depreciation_maintenance'],
            $id
        ]);
    }

    public static function delete($id)
    {
        $pdo = Database::getConnection();
        $sql = "UPDATE vehicles SET is_active = 0, updated_at = NOW() WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    public static function count()
    {
        $pdo = Database::getConnection();
        $sql = "SELECT COUNT(*) as total FROM vehicles WHERE is_active = 1";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getFuelConsumption() { return $this->fuel_consumption; }
    public function getFixedCostPerHour() { return $this->fixed_cost_per_hour; }
    public function getDepreciationMaintenance() { return $this->depreciation_maintenance; }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'fuel_consumption' => $this->fuel_consumption,
            'fixed_cost_per_hour' => $this->fixed_cost_per_hour,
            'depreciation_maintenance' => $this->depreciation_maintenance
        ];
    }
}