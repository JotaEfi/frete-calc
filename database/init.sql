CREATE DATABASE IF NOT EXISTS fretecalc_db;
USE fretecalc_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    fuel_consumption DECIMAL(10,2) NOT NULL,
    fixed_cost_per_hour DECIMAL(10,2) NOT NULL,
    depreciation_maintenance DECIMAL(10,2) NOT NULL
);

CREATE TABLE cost_rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    percentage DECIMAL(5,2),
    fixed_amount DECIMAL(10,2),
    is_active BOOLEAN DEFAULT TRUE
);

CREATE TABLE trips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id INT,
    distance DECIMAL(10,2),
    cargo_value DECIMAL(10,2),
    weight DECIMAL(10,2),
    travel_time_hours DECIMAL(10,2),
    user_id INT,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Dados iniciais para veículos
INSERT INTO vehicles (name, fuel_consumption, fixed_cost_per_hour, depreciation_maintenance) VALUES
('Caminhão 1620', 3.4, 102.98, 7208.60),
('DAF Carreta', 2.22, 102.98, 2100.79);

-- Dados iniciais para regras de custo
INSERT INTO cost_rules (name, percentage, fixed_amount) VALUES
('Taxa Ad Valorem Caminhão', 0.3, NULL),
('Taxa Ad Valorem DAF', 0.3, NULL),
('Taxa GRIS Caminhão', 0.5, 100000.00),
('Taxa GRIS DAF', 0.5, 10000.00),
('ICMS', 12.0, NULL);