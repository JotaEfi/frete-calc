<?php
// Carregar autoload do Composer
require_once 'vendor/autoload.php';

// Agora você pode usar suas classes sem require manual
use App\Models\FreteCalculator;
use App\Controllers\FreteController;

// Exemplo de uso
try {
    // Suas classes serão carregadas automaticamente
    $calculator = new FreteCalculator();
    $controller = new FreteController();
    
    echo "<h1>Sistema de Cálculo de Frete</h1>";
    echo "<p>Autoload PSR-4 funcionando!</p>";
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}