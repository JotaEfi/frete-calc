<?php

namespace App\Models;

use App\Config\Database;
use Exception;

class User
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $role;
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
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->role = $data['role'] ?? 'user';
        $this->created_at = $data['created_at'] ?? null;
    }

    /**
     * Busca usuário por email
     */
    public static function findByEmail($email)
    {
        try {
            $pdo = Database::getConnection();
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            $data = $stmt->fetch();

            return $data ? new self($data) : null;
        } catch (Exception $e) {
            throw new Exception("Erro ao buscar usuário: " . $e->getMessage());
        }
    }

    /**
     * Busca usuário por ID
     */
    public static function find($id)
    {
        try {
            $pdo = Database::getConnection();
            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $data = $stmt->fetch();

            return $data ? new self($data) : null;
        } catch (Exception $e) {
            throw new Exception("Erro ao buscar usuário: " . $e->getMessage());
        }
    }

    /**
     * Cria um novo usuário
     */
    public static function create($name, $email, $password, $role = 'user')
    {
        try {
            $pdo = Database::getConnection();

            // Verificar se email já existe
            if (self::findByEmail($email)) {
                throw new Exception("Email já cadastrado");
            }

            // Hash da senha
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $email, $hashedPassword, $role]);

            $userId = $pdo->lastInsertId();
            return self::find($userId);
        } catch (Exception $e) {
            throw new Exception("Erro ao criar usuário: " . $e->getMessage());
        }
    }

    /**
     * Verifica se a senha está correta
     */
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

    /**
     * Verifica se o usuário é admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Busca histórico de viagens do usuário
     */
    public function getTrips($limit = 50)
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
            $stmt->execute([$this->id, $limit]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("Erro ao buscar histórico: " . $e->getMessage());
        }
    }

    /**
     * Retorna dados do usuário (sem senha)
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'is_admin' => $this->isAdmin(),
            'created_at' => $this->created_at
        ];
    }

    /**
     * Busca todos os usuários (para admin)
     */
    public static function all()
    {
        try {
            $pdo = Database::getConnection();
            $sql = "SELECT * FROM users ORDER BY created_at DESC";
            $stmt = $pdo->query($sql);

            $users = [];
            while ($data = $stmt->fetch()) {
                $users[] = new self($data);
            }

            return $users;
        } catch (Exception $e) {
            throw new Exception("Erro ao buscar usuários: " . $e->getMessage());
        }
    }

    /**
     * Conta total de usuários
     */
    public static function count()
    {
        try {
            $pdo = Database::getConnection();
            $sql = "SELECT COUNT(*) as total FROM users";
            $stmt = $pdo->query($sql);
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            throw new Exception("Erro ao contar usuários: " . $e->getMessage());
        }
    }

    /**
     * Atualiza o role de um usuário (para admin)
     */
    public static function updateRole($id, $role)
    {
        try {
            $pdo = Database::getConnection();
            $sql = "UPDATE users SET role = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$role, $id]);
        } catch (Exception $e) {
            throw new Exception("Erro ao atualizar role do usuário: " . $e->getMessage());
        }
    }

    /**
     * Deleta um usuário
     */
    public static function delete($id)
    {
        try {
            $pdo = Database::getConnection();
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            throw new Exception("Erro ao deletar usuário: " . $e->getMessage());
        }
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getRole()
    {
        return $this->role;
    }
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}
