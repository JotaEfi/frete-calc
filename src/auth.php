<?php
// =======================================
// CONFIGURAÇÕES DA PÁGINA
// =======================================
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$title = "Autenticação - FreteCalc";
$active_page = "auth";

// Carregar autoload do Composer
require_once 'vendor/autoload.php';

// Incluir header
include 'includes/header.php';
?>



<!-- =====================================
     FORMULÁRIO DE AUTENTICAÇÃO
     ======================================= -->

<section class="py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-7">

        <!-- Card de Autenticação -->
        <div class="card shadow-lg border-0">
          <div class="card-header bg-primary text-white text-center py-4">
            <h4 class="mb-0">
              <i class="bi bi-truck"></i> FreteCalc
            </h4>
            <small class="opacity-75">Sistema de Cálculo de Frete</small>
          </div>

          <div class="card-body p-5">
            <!-- Tabs de Login/Registro -->
            <ul class="nav nav-pills nav-fill mb-4" id="authTabs" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="login-tab" data-bs-toggle="pill" data-bs-target="#login-panel" type="button">
                  <i class="bi bi-box-arrow-in-right"></i> Entrar
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="register-tab" data-bs-toggle="pill" data-bs-target="#register-panel" type="button">
                  <i class="bi bi-person-plus"></i> Cadastrar
                </button>
              </li>
            </ul>

            <!-- Conteúdo das Tabs -->
            <div class="tab-content" id="authTabsContent">

              <!-- Painel de Login -->
              <div class="tab-pane fade show active" id="login-panel" role="tabpanel">
                <form id="loginForm">
                  <div class="mb-3">
                    <label for="loginEmail" class="form-label">
                      <i class="bi bi-envelope text-primary"></i> Email
                    </label>
                    <input
                      type="email"
                      class="form-control form-control-lg"
                      id="loginEmail"
                      name="email"
                      value="admin@fretecalc.com"
                      placeholder="seu@email.com"
                      required>
                  </div>

                  <div class="mb-4">
                    <label for="loginPassword" class="form-label">
                      <i class="bi bi-lock text-primary"></i> Senha
                    </label>
                    <input
                      type="password"
                      class="form-control form-control-lg"
                      id="loginPassword"
                      name="password"
                      value="123456"
                      placeholder="Sua senha"
                      required>
                  </div>

                  <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-lg">
                      <i class="bi bi-box-arrow-in-right"></i>
                      Entrar no Sistema
                    </button>
                  </div>
                </form>
              </div>

              <!-- Painel de Registro -->
              <div class="tab-pane fade" id="register-panel" role="tabpanel">
                <form id="registerForm">
                  <div class="mb-3">
                    <label for="registerName" class="form-label">
                      <i class="bi bi-person text-primary"></i> Nome Completo
                    </label>
                    <input
                      type="text"
                      class="form-control form-control-lg"
                      id="registerName"
                      name="name"
                      placeholder="Seu nome completo"
                      value="joao"
                      required>
                  </div>

                  <div class="mb-3">
                    <label for="registerEmail" class="form-label">
                      <i class="bi bi-envelope text-primary"></i> Email
                    </label>
                    <input
                      type="email"
                      class="form-control form-control-lg"
                      id="registerEmail"
                      name="email"
                      placeholder="seu@email.com"
                      value="joao@gmail.com"
                      required>
                  </div>

                  <div class="mb-4">
                    <label for="registerPassword" class="form-label">
                      <i class="bi bi-lock text-primary"></i> Senha
                    </label>
                    <input
                      type="password"
                      class="form-control form-control-lg"
                      id="registerPassword"
                      name="password"
                      placeholder="Mínimo 6 caracteres"
                      value="joaojoao"
                      minlength="6"
                      required>
                    <div class="form-text">
                      <i class="bi bi-info-circle"></i>
                      A senha deve ter pelo menos 6 caracteres
                    </div>
                  </div>

                  <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-success btn-lg">
                      <i class="bi bi-person-plus"></i>
                      Criar Conta
                    </button>
                  </div>
                </form>
              </div>

            </div>

            <!-- Área de Status/Mensagens -->
            <div id="authStatus" class="mt-4"></div>

            <!-- Divisor -->
            <hr class="my-4">

            <!-- Botão de Verificação (para teste) -->
            <div class="text-center">
              <button id="btnCheckAuth" class="btn btn-outline-primary">
                <i class="bi bi-shield-check"></i>
                Verificar Autenticação
              </button>
            </div>

          </div>
        </div>

        <!-- Links adicionais -->
        <div class="text-center mt-4">
          <small class="text-muted">
            <a href="index.php" class="text-decoration-none">
              <i class="bi bi-arrow-left"></i> Voltar ao início
            </a>
            <span class="mx-3">|</span>
            <a href="#" class="text-decoration-none">
              <i class="bi bi-question-circle"></i> Precisa de ajuda?
            </a>
          </small>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- =====================================
     SEÇÃO DE BENEFÍCIOS
     ======================================= -->

<section class="bg-light py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center">
        <h3 class="fw-bold mb-4">
          <i class="bi bi-star"></i> Por que se cadastrar?
        </h3>
        <div class="row">
          <div class="col-md-4 mb-3">
            <div class="d-flex flex-column align-items-center">
              <i class="bi bi-save display-6 text-primary mb-2"></i>
              <h6>Salvar Cálculos</h6>
              <small class="text-muted">Histórico completo dos seus fretes</small>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="d-flex flex-column align-items-center">
              <i class="bi bi-graph-up display-6 text-success mb-2"></i>
              <h6>Relatórios</h6>
              <small class="text-muted">Análises detalhadas dos custos</small>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="d-flex flex-column align-items-center">
              <i class="bi bi-gear display-6 text-warning mb-2"></i>
              <h6>Personalização</h6>
              <small class="text-muted">Configure veículos e regras</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- =====================================
     SCRIPTS DE AUTENTICAÇÃO
     ======================================= -->

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
  // =======================================
  // CONFIGURAÇÃO DO AXIOS
  // =======================================

  axios.defaults.baseURL = 'http://localhost:8080';
  axios.defaults.headers.post['Content-Type'] = 'application/json';

  // =======================================
  // ELEMENTOS DO DOM
  // =======================================

  const loginForm = document.getElementById('loginForm');
  const registerForm = document.getElementById('registerForm');
  const btnCheckAuth = document.getElementById('btnCheckAuth');
  const authStatus = document.getElementById('authStatus');

  // =======================================
  // GERENCIADOR DE AUTENTICAÇÃO
  // =======================================

  class AuthManager {
    static showMessage(message, type = 'info', icon = 'info-circle') {
      const alertClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
      };

      authStatus.innerHTML = `
            <div class="alert ${alertClass[type]} alert-dismissible fade show">
                <i class="bi bi-${icon}"></i>
                <strong>${message}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
    }

    static showLoading(message = 'Processando...') {
      authStatus.innerHTML = `
            <div class="text-center">
                <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                <small class="text-muted">${message}</small>
            </div>
        `;
    }

    static saveToken(token) {
      localStorage.setItem('jwt_token', token);
    }

    static getToken() {
      return localStorage.getItem('jwt_token');
    }

    static removeToken() {
      localStorage.removeItem('jwt_token');
    }

    static redirectToCalculator() {
      setTimeout(() => {
        window.location.href = 'calcular.php';
      }, 1500);
    }
  }

  // =======================================
  // EVENT LISTENERS
  // =======================================

  // Login Form
  if (loginForm) {
    loginForm.addEventListener('submit', async function(event) {
      event.preventDefault();

      AuthManager.showLoading('Fazendo login...');

      try {
        const data = {
          email: document.getElementById('loginEmail').value,
          password: document.getElementById('loginPassword').value
        };

        const response = await axios.post('auth-api.php?action=login', data, {
          headers: {
            'Content-Type': 'application/json'
          }
        });

        if (response.data.success) {
          AuthManager.saveToken(response.data.token);
          AuthManager.showMessage(
            `Bem-vindo, ${response.data.user.name}! Redirecionando...`,
            'success',
            'check-circle'
          );
          AuthManager.redirectToCalculator();
        } else {
          AuthManager.showMessage(response.data.error, 'error', 'x-circle');
        }

      } catch (error) {
        console.error('Erro no login:', error);
        AuthManager.showMessage(
          error.response?.data?.error || 'Erro de conexão com o servidor',
          'error',
          'exclamation-triangle'
        );
      }
    });
  }

  // Register Form
  if (registerForm) {
    registerForm.addEventListener('submit', async function(event) {
      event.preventDefault();

      AuthManager.showLoading('Criando conta...');

      try {
        const formData = new FormData(event.target);
        const data = {
          name: document.getElementById('registerName').value,
          email: document.getElementById('registerEmail').value,
          password: document.getElementById('registerPassword').value
        };

        const response = await axios.post('auth-api.php?action=register', data, {
          headers: {
            'Content-Type': 'application/json'
          }
        });

        if (response.data.success) {
          AuthManager.saveToken(response.data.token);
          AuthManager.showMessage(
            `Conta criada com sucesso! Bem-vindo, ${response.data.user.name}!`,
            'success',
            'check-circle'
          );
          AuthManager.redirectToCalculator();
        } else {
          AuthManager.showMessage(response.data.error, 'error', 'x-circle');
        }

      } catch (error) {
        console.error('Erro no registro:', error);
        AuthManager.showMessage(
          error.response?.data?.error || 'Erro de conexão com o servidor',
          'error',
          'exclamation-triangle'
        );
      }
    });
  }

  // Check Auth Button
  if (btnCheckAuth) {
    btnCheckAuth.addEventListener('click', async function() {
      const token = AuthManager.getToken();

      if (!token) {
        AuthManager.showMessage('Nenhum token encontrado. Faça login primeiro.', 'warning', 'exclamation-triangle');
        return;
      }

      AuthManager.showLoading('Verificando autenticação...');

      try {
        const response = await axios.get('auth-api.php?action=me', {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });

        if (response.data.success) {
          const user = response.data.user;
          AuthManager.showMessage(
            `✅ Autenticado como: ${user.name} (${user.email})`,
            'success',
            'person-check'
          );
        }

      } catch (error) {
        console.error('Erro na verificação:', error);

        if (error.response?.status === 401) {
          AuthManager.showMessage('Token expirado ou inválido. Faça login novamente.', 'error', 'x-circle');
          AuthManager.removeToken();
        } else {
          AuthManager.showMessage('Erro ao verificar autenticação', 'error', 'exclamation-triangle');
        }
      }
    });
  }

  // =======================================
  // VERIFICAÇÃO INICIAL
  // =======================================

  document.addEventListener('DOMContentLoaded', function() {
    const token = AuthManager.getToken();

    if (token) {
      AuthManager.showMessage('Você já está logado no sistema!', 'info', 'info-circle');

      // Opcional: redirecionar automaticamente se já logado
      // AuthManager.redirectToCalculator();
    }

    console.log('🚛 FreteCalc - Sistema de Autenticação carregado');
  });
</script>

<?php include 'includes/footer.php'; ?>