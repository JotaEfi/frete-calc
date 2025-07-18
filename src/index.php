<?php
// Carregar autoload do Composer
require_once 'vendor/autoload.php';

// Agora você pode usar suas classes sem require manual
use App\Models\FreteCalculator;
use App\Controllers\FreteController;

// Exemplo de uso
try {
    // Suas classes serão carregadas automaticamente
    $message = "Sistema de Cálculo de Frete - Autoload PSR-4 funcionando!";

} catch (Exception $e) {
    $message = "Erro: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Cálculo de Frete</title>
    
    <!-- Bootstrap CSS - Escolha uma das opções abaixo -->
    
    <!-- OPÇÃO 1: CDN (Rápido, requer internet) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- OPÇÃO 2: Local (Offline, descomente para usar)
    <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    -->
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- CSS customizado -->
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
        }
        
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(45deg, #5a6fd8, #6a4190);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-truck"></i> FreteCalc
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Calcular Frete</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Histórico</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contato</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        <i class="bi bi-calculator"></i> Sistema de Cálculo de Frete
                    </h1>
                    <p class="lead mb-4"><?php echo $message; ?></p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <button type="button" class="btn btn-primary btn-lg px-4 me-md-2">
                            <i class="bi bi-play-fill"></i> Calcular Frete
                        </button>
                        <button type="button" class="btn btn-outline-light btn-lg px-4">
                            <i class="bi bi-info-circle"></i> Saiba Mais
                        </button>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <i class="bi bi-truck display-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-speedometer2 display-4 text-primary mb-3"></i>
                            <h5 class="card-title">Cálculo Rápido</h5>
                            <p class="card-text">Calcule o frete de forma rápida e precisa com nosso sistema otimizado.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-shield-check display-4 text-success mb-3"></i>
                            <h5 class="card-title">Seguro e Confiável</h5>
                            <p class="card-text">Sistema seguro com cálculos precisos baseados em dados reais.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-graph-up display-4 text-warning mb-3"></i>
                            <h5 class="card-title">Relatórios Detalhados</h5>
                            <p class="card-text">Acompanhe o histórico e gere relatórios detalhados dos seus cálculos.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; 2025 FreteCalc. Todos os direitos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-light me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-light me-3"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-light"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS - Escolha uma das opções abaixo -->
    
    <!-- OPÇÃO 1: CDN (Rápido, requer internet) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- OPÇÃO 2: Local (Offline, descomente para usar)
    <script src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    -->
</body>
</html>

