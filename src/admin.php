<?php
// Configurações da página
$title = "Painel Administrativo - FreteCalc";
$active_page = "admin";

// Carregar autoload do Composer
require_once 'vendor/autoload.php';

// Incluir header
include 'includes/header.php';
?>

<div class="container my-5">
    <!-- Verificação de acesso admin -->
    <div id="accessDenied" class="alert alert-danger" style="display: none;">
        <h4><i class="bi bi-shield-exclamation"></i> Acesso Negado</h4>
        <p>Você precisa ser administrador para acessar esta página.</p>
        <a href="auth.php" class="btn btn-primary">Fazer Login</a>
    </div>

    <!-- Conteúdo do painel admin -->
    <div id="adminPanel" style="display: none;">
        <div class="row">
            <div class="col-12">
                <h2><i class="bi bi-gear"></i> Painel Administrativo</h2>
                <p class="text-muted">Gerencie veículos, regras de custo e usuários do sistema.</p>
            </div>
        </div>

        <!-- Cards de estatísticas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Usuários</h6>
                                <h3 id="totalUsers">0</h3>
                            </div>
                            <i class="bi bi-people display-4 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Veículos</h6>
                                <h3 id="totalVehicles">0</h3>
                            </div>
                            <i class="bi bi-truck display-4 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Viagens</h6>
                                <h3 id="totalTrips">0</h3>
                            </div>
                            <i class="bi bi-map display-4 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Regras</h6>
                                <h3 id="totalRules">0</h3>
                            </div>
                            <i class="bi bi-list-check display-4 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs de gerenciamento -->
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="adminTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="vehicles-tab" data-bs-toggle="tab" data-bs-target="#vehicles" type="button">
                            <i class="bi bi-truck"></i> Veículos
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="rules-tab" data-bs-toggle="tab" data-bs-target="#rules" type="button">
                            <i class="bi bi-list-check"></i> Regras de Custo
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button">
                            <i class="bi bi-people"></i> Usuários
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="trips-tab" data-bs-toggle="tab" data-bs-target="#trips" type="button">
                            <i class="bi bi-map"></i> Histórico de Viagens
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="adminTabsContent">

                    <!-- Tab Veículos -->
                    <div class="tab-pane fade show active" id="vehicles" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Gerenciar Veículos</h5>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#vehicleModal">
                                <i class="bi bi-plus"></i> Novo Veículo
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" id="vehiclesTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Consumo (km/l)</th>
                                        <th>Custo Fixo/h</th>
                                        <th>Depreciação/Manutenção</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Regras de Custo -->
                    <div class="tab-pane fade" id="rules" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Regras de Custo</h5>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ruleModal">
                                <i class="bi bi-plus"></i> Nova Regra
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" id="rulesTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Tipo</th>
                                        <th>Valor</th>
                                        <th>Tipo de Cálculo</th>
                                        <th>Valor Mínimo</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Usuários -->
                    <div class="tab-pane fade" id="users" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Gerenciar Usuários</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" id="usersTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Papel</th>
                                        <th>Cadastrado em</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Histórico de Viagens -->
                    <div class="tab-pane fade" id="trips" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Histórico de Viagens</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" id="tripsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuário</th>
                                        <th>Veículo</th>
                                        <th>Origem → Destino</th>
                                        <th>Distância (km)</th>
                                        <th>Valor Total</th>
                                        <th>Data</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading -->
    <div id="loading" class="text-center">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Carregando...</span>
        </div>
        <p>Verificando permissões...</p>
    </div>
</div>

<!-- Modal para Veículo -->
<div class="modal fade" id="vehicleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gerenciar Veículo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="vehicleForm">
                <div class="modal-body">
                    <input type="hidden" id="vehicleId" name="id">
                    <div class="mb-3">
                        <label for="vehicleName" class="form-label">Nome do Veículo</label>
                        <input type="text" class="form-control" id="vehicleName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="fuelConsumption" class="form-label">Consumo de Combustível (km/l)</label>
                        <input type="number" class="form-control" id="fuelConsumption" name="fuel_consumption" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="fixedCost" class="form-label">Custo Fixo por Hora (R$)</label>
                        <input type="number" class="form-control" id="fixedCost" name="fixed_cost_per_hour" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="maintenance" class="form-label">Depreciação/Manutenção (R$)</label>
                        <input type="number" class="form-control" id="maintenance" name="depreciation_maintenance" step="0.01" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Regra de Custo -->
<div class="modal fade" id="ruleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gerenciar Regra de Custo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="ruleForm">
                <div class="modal-body">
                    <input type="hidden" id="ruleId" name="id">
                    <div class="mb-3">
                        <label for="ruleName" class="form-label">Nome da Regra</label>
                        <input type="text" class="form-control" id="ruleName" name="rule_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="ruleType" class="form-label">Tipo da Regra</label>
                        <select class="form-select" id="ruleType" name="rule_type" required>
                            <option value="fuel_price">Preço Combustível</option>
                            <option value="ad_valorem">Ad Valorem</option>
                            <option value="gris">GRIS</option>
                            <option value="icms">ICMS</option>
                            <option value="admin_fee">Taxa Administrativa</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="ruleValue" class="form-label">Valor</label>
                        <input type="number" class="form-control" id="ruleValue" name="value" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="isPercentage" name="is_percentage">
                            <label class="form-check-label" for="isPercentage">
                                É porcentagem?
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="minimumValue" class="form-label">Valor Mínimo (R$)</label>
                        <input type="number" class="form-control" id="minimumValue" name="minimum_value" step="0.01" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Configurar axios
    axios.defaults.baseURL = window.location.origin;

    let currentUser = null;

    document.addEventListener('DOMContentLoaded', function() {
        checkAdminAccess();
    });

    async function checkAdminAccess() {
        const token = localStorage.getItem('jwt_token');

        if (!token) {
            showAccessDenied();
            return;
        }

        try {
            const response = await axios.get('auth-api.php?action=me', {
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            });

            if (response.data.success && response.data.user.role === 'admin') {
                currentUser = response.data.user;
                showAdminPanel();
                loadDashboardData();
            } else {
                showAccessDenied();
            }
        } catch (error) {
            console.error('Erro ao verificar acesso:', error);
            showAccessDenied();
        }
    }

    function showAccessDenied() {
        document.getElementById('loading').style.display = 'none';
        document.getElementById('accessDenied').style.display = 'block';
        document.getElementById('adminPanel').style.display = 'none';
    }

    function showAdminPanel() {
        document.getElementById('loading').style.display = 'none';
        document.getElementById('accessDenied').style.display = 'none';
        document.getElementById('adminPanel').style.display = 'block';
    }

    async function loadDashboardData() {
        const token = localStorage.getItem('jwt_token');

        try {
            const response = await axios.get('admin-api.php?action=dashboard', {
                headers: {
                    'Authorization': 'Bearer ' + token
                }
            });

            if (response.data.success) {
                const stats = response.data.data;
                document.getElementById('totalUsers').textContent = stats.total_users || 0;
                document.getElementById('totalVehicles').textContent = stats.total_vehicles || 0;
                document.getElementById('totalTrips').textContent = stats.total_trips || 0;
                document.getElementById('totalRules').textContent = stats.total_rules || 0;

                // Carregar dados das tabelas
                loadVehicles();
                loadRules();
                loadUsers();
                loadTrips();
            }
        } catch (error) {
            console.error('Erro ao carregar dashboard:', error);
        }
    }

    async function loadVehicles() {
        // Implementar carregamento de veículos
        console.log('Carregando veículos...');
    }

    async function loadRules() {
        // Implementar carregamento de regras
        console.log('Carregando regras...');
    }

    async function loadUsers() {
        // Implementar carregamento de usuários
        console.log('Carregando usuários...');
    }

    async function loadTrips() {
        // Implementar carregamento de viagens
        console.log('Carregando viagens...');
    }
</script>

<?php include 'includes/footer.php'; ?>