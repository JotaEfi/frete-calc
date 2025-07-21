<?php
// Configurações da página
$title = "Calcular Frete - FreteCalc";
$active_page = "calcular";

// Carregar autoload do Composer
require_once 'vendor/autoload.php';

// Importar classes necessárias
use App\Models\Vehicle;

// Buscar veículos disponíveis
try {
    $vehicles = Vehicle::all();
} catch (Exception $e) {
    $vehicles = [];
    error_log("Erro ao buscar veículos: " . $e->getMessage());
}

// Incluir header
include 'includes/header.php';
?>

<!-- Conteúdo da página -->
<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-calculator"></i> Calcular Frete
                    </h4>
                </div>
                <div class="card-body">
                    <form id="freteForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="origem" class="form-label">Origem</label>
                                <input type="text" class="form-control" id="origem" placeholder="CEP ou cidade de origem" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="destino" class="form-label">Destino</label>
                                <input type="text" class="form-control" id="destino" placeholder="CEP ou cidade de destino" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="peso" class="form-label">Peso (kg)</label>
                                <input type="number" class="form-control" id="peso" step="0.01" min="0" placeholder="Ex: 10.5" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="valor" class="form-label">Valor da Carga (R$)</label>
                                <input type="number" class="form-control" id="valor" step="0.01" min="0" placeholder="Ex: 1500.00" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="distancia" class="form-label">Distância (km)</label>
                                <input type="number" class="form-control" id="distancia" step="0.1" min="0" placeholder="Ex: 250.5" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="veiculo" class="form-label">Veículo</label>
                                <select class="form-select" id="veiculo" required>
                                    <option value="">Selecione o veículo</option>
                                    <?php foreach ($vehicles as $vehicle): ?>
                                        <option value="<?= $vehicle->getId() ?>">
                                            <?= htmlspecialchars($vehicle->getName()) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="tempo" class="form-label">Tempo de Viagem (horas)</label>
                            <input type="number" class="form-control" id="tempo" step="0.1" min="0" placeholder="Ex: 4.5" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-calculator"></i> Calcular Frete
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resultado -->
            <div id="resultado" class="card mt-4" style="display: none;">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-check-circle"></i> Resultado do Cálculo
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Custos Operacionais:</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Combustível:</span>
                                    <strong id="custoCombustivel">R$ 0,00</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Custo Fixo:</span>
                                    <strong id="custoFixo">R$ 0,00</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Manutenção:</span>
                                    <strong id="custoManutencao">R$ 0,00</strong>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Taxas:</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Ad Valorem:</span>
                                    <strong id="taxaAdValorem">R$ 0,00</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>GRIS:</span>
                                    <strong id="taxaGris">R$ 0,00</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>ICMS:</span>
                                    <strong id="taxaIcms">R$ 0,00</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <h4 class="text-success">
                            <i class="bi bi-currency-dollar"></i>
                            Valor Total: <span id="valorTotal">R$ 0,00</span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('freteForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Verificar se usuário está logado
        const token = localStorage.getItem('jwt_token');
        if (!token) {
            alert('Você precisa fazer login para calcular frete.');
            window.location.href = 'auth.php';
            return;
        }

        // Coletar dados do formulário
        const formData = {
            vehicle_id: parseInt(document.getElementById('veiculo').value),
            weight: parseFloat(document.getElementById('peso').value),
            cargo_value: parseFloat(document.getElementById('valor').value),
            distance: parseFloat(document.getElementById('distancia').value),
            travel_time: parseFloat(document.getElementById('tempo').value),
            origin: document.getElementById('origem').value,
            destination: document.getElementById('destino').value
        };

        // Mostrar loading
        const submitBtn = document.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Calculando...';
        submitBtn.disabled = true;

        // Fazer requisição para a API
        fetch('api.php?action=calculate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                // Restaurar botão
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;

                if (data.success) {
                    // Exibir resultados
                    const result = data.data;

                    document.getElementById('custoCombustivel').textContent = `R$ ${result.fuel_cost.toFixed(2)}`;
                    document.getElementById('custoFixo').textContent = `R$ ${result.fixed_cost.toFixed(2)}`;
                    document.getElementById('custoManutencao').textContent = `R$ ${result.maintenance_cost.toFixed(2)}`;
                    document.getElementById('taxaAdValorem').textContent = `R$ ${result.ad_valorem_cost.toFixed(2)}`;
                    document.getElementById('taxaGris').textContent = `R$ ${result.gris_cost.toFixed(2)}`;
                    document.getElementById('taxaIcms').textContent = `R$ ${result.icms_cost.toFixed(2)}`;
                    document.getElementById('valorTotal').textContent = `R$ ${result.total_cost.toFixed(2)}`;

                    // Mostrar resultado
                    document.getElementById('resultado').style.display = 'block';
                    document.getElementById('resultado').scrollIntoView({
                        behavior: 'smooth'
                    });

                    // Mostrar notificação de sucesso
                    showNotification('Cálculo realizado e salvo no histórico!', 'success');

                } else {
                    showNotification(data.error, 'error');
                }
            })
            .catch(error => {
                // Restaurar botão
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;

                showNotification('Erro na comunicação com o servidor: ' + error.message, 'error');
            });
    });
    
</script>

<?php
// Incluir footer
include 'includes/footer.php';
?>