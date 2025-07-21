<?php

namespace App\Models;

use App\Config\Database;
use Exception;

class CostRule
{
    private $id;
    private $rule_name;
    private $rule_type;
    private $value;
    private $is_percentage;
    private $minimum_value;
    private $is_active;

    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->fill($data);
        }
    }

    private function fill($data)
    {
        $this->id = $data['id'] ?? null;
        $this->rule_name = $data['rule_name'] ?? '';
        $this->rule_type = $data['rule_type'] ?? '';
        $this->value = $data['value'] ?? 0;
        $this->is_percentage = $data['is_percentage'] ?? 0;
        $this->minimum_value = $data['minimum_value'] ?? 0;
        $this->is_active = $data['is_active'] ?? 1;
    }

    /**
     * Busca todas as regras de custo ativas
     */
    public static function all()
    {
        try {
            $pdo = Database::getConnection();
            $sql = "SELECT * FROM cost_rules WHERE is_active = 1 ORDER BY rule_name";
            $stmt = $pdo->query($sql);

            $rules = [];
            while ($data = $stmt->fetch()) {
                $rules[] = new self($data);
            }

            return $rules;
        } catch (Exception $e) {
            throw new Exception("Erro ao buscar regras de custo: " . $e->getMessage());
        }
    }

    /**
     * Busca regra por ID
     */
    public static function find($id)
    {
        try {
            $pdo = Database::getConnection();
            $sql = "SELECT * FROM cost_rules WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $data = $stmt->fetch();

            return $data ? new self($data) : null;
        } catch (Exception $e) {
            throw new Exception("Erro ao buscar regra por ID: " . $e->getMessage());
        }
    }

    /**
     * Busca regra por tipo
     */
    public static function getByType($type)
    {
        try {
            $pdo = Database::getConnection();
            $sql = "SELECT * FROM cost_rules WHERE rule_type = ? AND is_active = 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$type]);
            $data = $stmt->fetch();

            return $data ? new self($data) : null;
        } catch (Exception $e) {
            throw new Exception("Erro ao buscar regra por tipo: " . $e->getMessage());
        }
    }

    /**
     * Cria uma nova regra de custo
     */
    public static function create($data)
    {
        try {
            $pdo = Database::getConnection();
            $sql = "INSERT INTO cost_rules (rule_name, rule_type, value, is_percentage, minimum_value, created_at) 
                    VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $data['rule_name'],
                $data['rule_type'],
                $data['value'],
                $data['is_percentage'] ?? 0,
                $data['minimum_value'] ?? 0
            ]);

            return $pdo->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Erro ao criar regra de custo: " . $e->getMessage());
        }
    }

    /**
     * Atualiza uma regra de custo
     */
    public static function update($id, $data)
    {
        try {
            $pdo = Database::getConnection();
            $sql = "UPDATE cost_rules SET 
                        rule_name = ?, 
                        rule_type = ?, 
                        value = ?, 
                        is_percentage = ?, 
                        minimum_value = ?,
                        updated_at = NOW()
                    WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                $data['rule_name'],
                $data['rule_type'],
                $data['value'],
                $data['is_percentage'] ?? 0,
                $data['minimum_value'] ?? 0,
                $id
            ]);
        } catch (Exception $e) {
            throw new Exception("Erro ao atualizar regra de custo: " . $e->getMessage());
        }
    }

    /**
     * Deleta uma regra de custo (soft delete)
     */
    public static function delete($id)
    {
        try {
            $pdo = Database::getConnection();
            $sql = "UPDATE cost_rules SET is_active = 0, updated_at = NOW() WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            throw new Exception("Erro ao deletar regra de custo: " . $e->getMessage());
        }
    }

    /**
     * Conta total de regras ativas
     */
    public static function count()
    {
        try {
            $pdo = Database::getConnection();
            $sql = "SELECT COUNT(*) as total FROM cost_rules WHERE is_active = 1";
            $stmt = $pdo->query($sql);
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            throw new Exception("Erro ao contar regras de custo: " . $e->getMessage());
        }
    }

    /**
     * Calcula valor baseado na regra
     */
    public function calcular($baseValue)
    {
        if ($this->is_percentage) {
            $calculated = $baseValue * ($this->value / 100);
            return max($calculated, $this->minimum_value);
        } else {
            return $this->value;
        }
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getRuleName()
    {
        return $this->rule_name;
    }
    public function getRuleType()
    {
        return $this->rule_type;
    }
    public function getValue()
    {
        return $this->value;
    }
    public function isPercentage()
    {
        return $this->is_percentage;
    }
    public function getMinimumValue()
    {
        return $this->minimum_value;
    }

    /**
     * Retorna dados como array para JSON
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'rule_name' => $this->rule_name,
            'rule_type' => $this->rule_type,
            'value' => $this->value,
            'is_percentage' => $this->is_percentage,
            'minimum_value' => $this->minimum_value,
            'is_active' => $this->is_active
        ];
    }
}
