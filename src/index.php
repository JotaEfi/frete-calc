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
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }
        
        .btn-outline-light:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-truck"></i> FreteCalc
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    
            
                    <li class="nav-item">
                        <a class="nav-link" href="historico.php">
                            <i class="bi bi-clock-history"></i> Histórico
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contato.php">
                            <i class="bi bi-envelope"></i> Contato
                        </a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link" href="auth.php">
                            <i class="bi bi-envelope"></i> Contato
                        </a>
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
                        <!-- Botão que redireciona para calcular.php -->
                        <a href="calcular.php" class="btn btn-primary btn-lg px-4 me-md-2">
                            <i class="bi bi-play-fill"></i> Calcular Frete
                        </a>
                        <!-- Botão que rola para a seção de informações -->
                        <a href="#features" class="btn btn-outline-light btn-lg px-4">
                            <i class="bi bi-info-circle"></i> Saiba Mais
                        </a>
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
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Por que escolher o FreteCalc?</h2>
                <p class="lead text-muted">Sistema completo para cálculo de frete profissional</p>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-speedometer2 display-4 text-primary mb-3"></i>
                            <h5 class="card-title">Cálculo Rápido</h5>
                            <p class="card-text">Calcule o frete de forma rápida e precisa com nosso sistema otimizado baseado em dados reais do banco.</p>
                            <a href="calcular.php" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-right"></i> Calcular Agora
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-shield-check display-4 text-success mb-3"></i>
                            <h5 class="card-title">Seguro e Confiável</h5>
                            <p class="card-text">Sistema seguro com cálculos precisos baseados em dados reais de veículos e regras de custo.</p>
                            <a href="config-test.php" class="btn btn-outline-success">
                                <i class="bi bi-gear"></i> Ver Configurações
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-graph-up display-4 text-warning mb-3"></i>
                            <h5 class="card-title">Relatórios Detalhados</h5>
                            <p class="card-text">Acompanhe o histórico e gere relatórios detalhados dos seus cálculos de frete.</p>
                            <a href="historico.php" class="btn btn-outline-warning">
                                <i class="bi bi-graph-up"></i> Ver Relatórios
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="bg-light py-5">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">Pronto para calcular seu frete?</h2>
            <p class="lead mb-4">Experimente agora nosso sistema completo de cálculo de frete</p>
            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                <a href="calcular.php" class="btn btn-primary btn-lg px-5 me-md-2">
                    <i class="bi bi-calculator"></i> Começar Cálculo
                </a>
                <a href="#" class="btn btn-outline-secondary btn-lg px-5" data-bs-toggle="modal" data-bs-target="#demoModal">
                    <i class="bi bi-play-circle"></i> Ver Demo
                </a>
            </div>
        </div>
    </section>

    <!-- Modal Demo (opcional) -->
    <div class="modal fade" id="demoModal" tabindex="-1" aria-labelledby="demoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="demoModalLabel">
                        <i class="bi bi-play-circle"></i> Demo do Sistema
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="bi bi-truck display-1 text-primary mb-4"></i>
                        <h4>Como usar o FreteCalc:</h4>
                        <ol class="list-group list-group-numbered text-start">
                            <li class="list-group-item">Acesse a página de <strong>Calcular Frete</strong></li>
                            <li class="list-group-item">Preencha os dados da viagem (origem, destino, peso, etc.)</li>
                            <li class="list-group-item">Selecione o veículo desejado</li>
                            <li class="list-group-item">Clique em <strong>Calcular Frete</strong></li>
                            <li class="list-group-item">Receba o resultado detalhado com todos os custos</li>
                        </ol>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <a href="calcular.php" class="btn btn-primary">
                        <i class="bi bi-arrow-right"></i> Ir para Calculadora
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; 2025 FreteCalc. Todos os direitos reservados.</p>
                    <p class="small">Sistema de cálculo de frete profissional</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="mb-2">
                        <a href="#" class="text-light me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-light me-3"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-linkedin"></i></a>
                    </div>
                    <div class="small">
                        <a href="calcular.php" class="text-light me-3">Calcular</a>
                        <a href="historico.php" class="text-light me-3">Histórico</a>
                        <a href="contato.php" class="text-light">Contato</a>
                    </div>
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

    <!-- Script para smooth scrolling -->
    <script>
        // Smooth scrolling para links internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>