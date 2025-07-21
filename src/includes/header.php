<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Sistema de Cálculo de Frete'; ?></title>

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

        .navbar-brand {
            font-weight: bold;
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
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= ($active_page ?? '') === 'index' ? 'active' : '' ?>" href="index.php">
                            <i class="bi bi-house"></i> Início
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($active_page ?? '') === 'calcular' ? 'active' : '' ?>" href="calcular.php">
                            <i class="bi bi-calculator"></i> Calcular
                        </a>
                    </li>
                </ul>

                <!-- Menu do usuário -->
                <ul class="navbar-nav">
                    <!-- Links para usuários não logados -->
                    <li class="nav-item" id="loginLink">
                        <a class="nav-link" href="auth.php">
                            <i class="bi bi-box-arrow-in-right"></i> Login/Registro
                        </a>
                    </li>

                    <!-- Menu para usuários logados (inicialmente oculto) -->
                    <li class="nav-item dropdown" id="userMenu" style="display: none;">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <span id="userName">Usuário</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="historico.php">
                                    <i class="bi bi-clock-history"></i> Histórico
                                </a></li>
                            <li id="adminLink" style="display: none;"><a class="dropdown-item" href="admin.php">
                                    <i class="bi bi-gear"></i> Painel Admin
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#" id="logoutBtn">
                                    <i class="bi bi-box-arrow-right"></i> Sair
                                </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Script para gerenciar estado de login -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            checkUserAuth();

            // Event listener para logout
            document.getElementById('logoutBtn')?.addEventListener('click', function(e) {
                e.preventDefault();
                logout();
            });
        });

        async function checkUserAuth() {
            const token = localStorage.getItem('jwt_token');

            if (!token) {
                showNotLoggedInState();
                return;
            }

            try {
                const response = await fetch('auth-api.php?action=me', {
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        showLoggedInState(data.user);
                    } else {
                        showNotLoggedInState();
                        localStorage.removeItem('jwt_token');
                    }
                } else {
                    showNotLoggedInState();
                    localStorage.removeItem('jwt_token');
                }
            } catch (error) {
                console.error('Erro ao verificar autenticação:', error);
                showNotLoggedInState();
            }
        }

        function showLoggedInState(user) {
            document.getElementById('loginLink').style.display = 'none';
            document.getElementById('userMenu').style.display = 'block';
            document.getElementById('userName').textContent = user.name;

            // Mostrar link admin se for admin
            if (user.role === 'admin') {
                document.getElementById('adminLink').style.display = 'block';
            }
        }

        function showNotLoggedInState() {
            document.getElementById('loginLink').style.display = 'block';
            document.getElementById('userMenu').style.display = 'none';
            document.getElementById('adminLink').style.display = 'none';
        }

        function logout() {
            localStorage.removeItem('jwt_token');
            showNotLoggedInState();

            // Mostrar notificação de logout
            if (typeof showNotification === 'function') {
                showNotification('Logout realizado com sucesso!', 'success');
            } else {
                alert('Logout realizado com sucesso!');
            }

            // Redirecionar para página inicial após um tempo
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 1500);
        }

        // Função global para notificações (caso não exista)
        function showNotification(message, type = 'info') {
            // Implementação simples se não houver sistema de notificação
            console.log(`${type.toUpperCase()}: ${message}`);
        }
    </script>