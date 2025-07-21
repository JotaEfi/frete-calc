<?php
// =======================================
// CONFIGURAÇÕES DA PÁGINA
// =======================================

$title = "Histórico de Cálculos - FreteCalc";
$active_page = "historico";

// Carregar autoload do Composer
require_once 'vendor/autoload.php';

// Incluir header
include 'includes/header.php';
?>

<!-- =======================================
     CONTEÚDO PRINCIPAL
     ======================================= -->

<div class="container my-5">
    <div class="row">
        <div class="col-lg-12">

            <!-- Cabeçalho -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="bi bi-clock-history"></i> Meu Histórico de Fretes
                </h2>
                <div>
                    <button class="btn btn-outline-primary" onclick="loadHistory()">
                        <i class="bi bi-arrow-clockwise"></i> Atualizar
                    </button>
                    <a href="calcular.php" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Novo Cálculo
                    </a>
                </div>
            </div>

            <!-- Estatísticas -->
            <div id="statsSection" class="row mb-4" style="display: none;">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="text-primary" id="totalTrips">-</h4>
                            <small class="text-muted">Total de Cálculos</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="text-success" id="totalRevenue">-</h4>
                            <small class="text-muted">Valor Total</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="text-info" id="avgCost">-</h4>
                            <small class="text-muted">Custo Médio</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="text-warning" id="totalDistance">-</h4>
                            <small class="text-muted">Distância Total</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading -->
            <div id="loading" class="text-center py-5" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                <p class="mt-3 text-muted">Carregando histórico...</p>
            </div>

            <!-- Tabela de Histórico -->
            <div id="historySection" class="card" style="display: none;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-table"></i> Histórico Detalhado
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Rota</th>
                                    <th>Veículo</th>
                                    <th>Distância</th>
                                    <th>Peso</th>
                                    <th>Valor Total</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="historyTableBody">
                                <!-- Dados serão inseridos aqui via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Mensagem quando vazio -->
            <div id="emptyMessage" class="text-center py-5" style="display: none;">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <h4 class="text-muted mt-3">Nenhum cálculo encontrado</h4>
                <p class="text-muted">Faça seu primeiro cálculo de frete!</p>
                <a href="calcular.php" class="btn btn-primary">
                    <i class="bi bi-calculator"></i> Calcular Frete
                </a>
            </div>

            <!-- Alerta de não logado -->
            <div id="notLoggedMessage" class="alert alert-warning" style="display: none;">
                <i class="bi bi-exclamation-triangle"></i>
                <strong>Faça login</strong> para ver seu histórico de cálculos.
                <a href="auth.php" class="btn btn-sm btn-warning ms-2">
                    <i class="bi bi-box-arrow-in-right"></i> Fazer Login
                </a>
            </div>

        </div>
    </div>
</div>

<!-- Modal de Detalhes -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-info-circle"></i> Detalhes do Cálculo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Conteúdo será inserido via JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- =======================================
     JAVASCRIPT
     ======================================= -->

<script>
    class HistoryManager {
        constructor() {
            this.token = localStorage.getItem('jwt_token');
            this.init();
        }

        init() {
            if (!this.token) {
                this.showNotLoggedMessage();
                return;
            }
            this.loadHistory();
        }

        showNotLoggedMessage() {
            document.getElementById('notLoggedMessage').style.display = 'block';
        }

        async loadHistory() {
            try {
                document.getElementById('loading').style.display = 'block';

                const response = await fetch('api.php?action=history', {
                    headers: {
                        'Authorization': 'Bearer ' + this.token
                    }
                });

                const data = await response.json();

                document.getElementById('loading').style.display = 'none';

                if (data.success) {
                    this.displayStats(data.data.stats);
                    this.displayHistory(data.data.trips);
                } else {
                    throw new Error(data.error);
                }

            } catch (error) {
                document.getElementById('loading').style.display = 'none';
                console.error('Erro ao carregar histórico:', error);

                if (error.message.includes('Token')) {
                    localStorage.removeItem('jwt_token');
                    this.showNotLoggedMessage();
                }
            }
        }

        displayStats(stats) {
            document.getElementById('totalTrips').textContent = stats.total_trips || '0';
            document.getElementById('totalRevenue').textContent = 'R$ ' + (parseFloat(stats.total_revenue) || 0).toFixed(2);
            document.getElementById('avgCost').textContent = 'R$ ' + (parseFloat(stats.avg_cost) || 0).toFixed(2);
            document.getElementById('totalDistance').textContent = (parseFloat(stats.total_distance) || 0).toFixed(1) + ' km';

            document.getElementById('statsSection').style.display = 'flex';
        }

        displayHistory(trips) {
            const tbody = document.getElementById('historyTableBody');

            if (!trips || trips.length === 0) {
                document.getElementById('emptyMessage').style.display = 'block';
                return;
            }

            tbody.innerHTML = '';
            document.getElementById('historySection').style.display = 'block';

            trips.forEach(trip => {
                const row = document.createElement('tr');
                const route = `${trip.origin || 'N/A'} → ${trip.destination || 'N/A'}`;
                const date = new Date(trip.created_at).toLocaleString('pt-BR');

                row.innerHTML = `
                <td><strong>#${trip.id}</strong></td>
                <td><small>${route}</small></td>
                <td>
                    <i class="bi bi-truck text-primary"></i>
                    ${trip.vehicle_name || 'N/A'}
                </td>
                <td>${parseFloat(trip.distance).toFixed(1)} km</td>
                <td>${parseFloat(trip.weight).toFixed(2)} kg</td>
                <td class="fw-bold text-success">R$ ${parseFloat(trip.total_cost).toFixed(2)}</td>
                <td><small class="text-muted">${date}</small></td>
                <td>
                    <button class="btn btn-sm btn-outline-info me-1" onclick="historyManager.showDetails(${trip.id})">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="historyManager.deleteTrip(${trip.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;

                tbody.appendChild(row);
            });
        }

        async showDetails(tripId) {
            // Buscar detalhes específicos da viagem
            try {
                const response = await fetch('api.php?action=history', {
                    headers: {
                        'Authorization': 'Bearer ' + this.token
                    }
                });

                const data = await response.json();
                const trip = data.data.trips.find(t => t.id == tripId);

                if (trip) {
                    const modalBody = document.getElementById('modalBody');
                    modalBody.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informações da Viagem</h6>
                            <p><strong>ID:</strong> #${trip.id}</p>
                            <p><strong>Origem:</strong> ${trip.origin || 'Não informado'}</p>
                            <p><strong>Destino:</strong> ${trip.destination || 'Não informado'}</p>
                            <p><strong>Veículo:</strong> ${trip.vehicle_name}</p>
                            <p><strong>Distância:</strong> ${parseFloat(trip.distance).toFixed(1)} km</p>
                            <p><strong>Peso:</strong> ${parseFloat(trip.weight).toFixed(2)} kg</p>
                            <p><strong>Tempo:</strong> ${parseFloat(trip.travel_time_hours).toFixed(1)}h</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Detalhamento de Custos</h6>
                            <p><strong>Combustível:</strong> R$ ${parseFloat(trip.fuel_cost).toFixed(2)}</p>
                            <p><strong>Custo Fixo:</strong> R$ ${parseFloat(trip.fixed_cost).toFixed(2)}</p>
                            <p><strong>Manutenção:</strong> R$ ${parseFloat(trip.maintenance_cost).toFixed(2)}</p>
                            <p><strong>Ad Valorem:</strong> R$ ${parseFloat(trip.ad_valorem_cost).toFixed(2)}</p>
                            <p><strong>GRIS:</strong> R$ ${parseFloat(trip.gris_cost).toFixed(2)}</p>
                            <p><strong>ICMS:</strong> R$ ${parseFloat(trip.icms_cost).toFixed(2)}</p>
                            <hr>
                            <h5 class="text-success">Total: R$ ${parseFloat(trip.total_cost).toFixed(2)}</h5>
                        </div>
                    </div>
                `;

                    new bootstrap.Modal(document.getElementById('detailsModal')).show();
                }
            } catch (error) {
                console.error('Erro ao buscar detalhes:', error);
            }
        }

        async deleteTrip(tripId) {
            if (!confirm('Tem certeza que deseja remover este cálculo do histórico?')) {
                return;
            }

            try {
                const response = await fetch(`api.php?action=delete-trip&trip_id=${tripId}`, {
                    headers: {
                        'Authorization': 'Bearer ' + this.token
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.loadHistory(); // Recarregar histórico
                    this.showNotification('Cálculo removido do histórico!', 'success');
                } else {
                    throw new Error(data.error);
                }

            } catch (error) {
                console.error('Erro ao deletar:', error);
                this.showNotification('Erro ao remover cálculo: ' + error.message, 'error');
            }
        }

        showNotification(message, type = 'info') {
            const alertClass = {
                'success': 'alert-success',
                'error': 'alert-danger',
                'info': 'alert-info'
            };

            const notification = document.createElement('div');
            notification.className = `alert ${alertClass[type]} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
            notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

            document.body.appendChild(notification);

            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 5000);
        }
    }

    // Inicializar quando a página carregar
    let historyManager;
    document.addEventListener('DOMContentLoaded', function() {
        historyManager = new HistoryManager();
    });

    // Função global para atualizar histórico
    function loadHistory() {
        if (historyManager) {
            historyManager.loadHistory();
        }
    }
</script>

<?php include 'includes/footer.php'; ?>