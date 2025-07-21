CREATE DATABASE IF NOT EXISTS fretecalc_db;
USE fretecalc_db;

-- Tabela de usuários com campo role e name
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de veículos
CREATE TABLE vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    fuel_consumption DECIMAL(10,2) NOT NULL,
    fixed_cost_per_hour DECIMAL(10,2) NOT NULL,
    depreciation_maintenance DECIMAL(10,2) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de regras de custo atualizada
CREATE TABLE cost_rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rule_name VARCHAR(100) NOT NULL,
    rule_type VARCHAR(50) NOT NULL,
    value DECIMAL(10,2) NOT NULL,
    is_percentage BOOLEAN DEFAULT FALSE,
    minimum_value DECIMAL(10,2) DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de viagens/histórico com mais campos
CREATE TABLE trips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    vehicle_id INT NOT NULL,
    origin VARCHAR(255),
    destination VARCHAR(255),
    distance DECIMAL(10,2) NOT NULL,
    cargo_value DECIMAL(10,2) NOT NULL,
    weight DECIMAL(10,2) NOT NULL,
    travel_time_hours DECIMAL(10,2) NOT NULL,
    fuel_cost DECIMAL(10,2),
    fixed_cost DECIMAL(10,2),
    maintenance_cost DECIMAL(10,2),
    ad_valorem_cost DECIMAL(10,2),
    gris_cost DECIMAL(10,2),
    icms_cost DECIMAL(10,2),
    total_cost DECIMAL(10,2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Inserir usuário admin padrão
INSERT INTO users (name, email, password, role) VALUES
('Administrador', 'admin@fretecalc.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Usuário Teste', 'user@fretecalc.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');
-- Senha para ambos: password

-- Dados iniciais para veículos
INSERT INTO vehicles (name, fuel_consumption, fixed_cost_per_hour, depreciation_maintenance) VALUES
('Caminhão 1620', 3.4, 102.98, 7208.60),
('DAF Carreta', 2.22, 102.98, 2100.79);

-- Dados iniciais para regras de custo
INSERT INTO cost_rules (rule_name, rule_type, value, is_percentage, minimum_value) VALUES
('Preço Combustível', 'fuel_price', 5.50, FALSE, 0),
('Ad Valorem', 'ad_valorem', 0.3, TRUE, 0),
('GRIS', 'gris', 0.5, TRUE, 100.00),
('ICMS', 'icms', 12.0, TRUE, 0),
('Taxa Administrativa', 'admin_fee', 50.00, FALSE, 0);