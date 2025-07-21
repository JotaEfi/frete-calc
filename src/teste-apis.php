<?php
header('Content-Type: text/html; charset=utf-8');
echo "<h1>Teste das APIs - Railway</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;} .info{color:blue;} .test{margin:10px 0;padding:10px;border:1px solid #ddd;border-radius:5px;}</style>";

// Função para testar uma URL
function testURL($url, $title, $postData = null) {
    echo "<div class='test'>";
    echo "<h3>{$title}</h3>";
    echo "<strong>URL:</strong> {$url}<br>";
    
    try {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        if ($postData) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            echo "<span class='error'>❌ Erro cURL: {$error}</span><br>";
        } else {
            echo "<span class='info'>📊 HTTP Code: {$httpCode}</span><br>";
            
            if ($httpCode == 200) {
                echo "<span class='success'>✅ Resposta OK</span><br>";
                $json = json_decode($response, true);
                if ($json) {
                    echo "<pre>" . json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
                } else {
                    echo "<pre>" . htmlspecialchars(substr($response, 0, 500)) . "...</pre>";
                }
            } else {
                echo "<span class='error'>❌ Erro HTTP</span><br>";
                echo "<pre>" . htmlspecialchars(substr($response, 0, 500)) . "</pre>";
            }
        }
    } catch (Exception $e) {
        echo "<span class='error'>❌ Exceção: " . $e->getMessage() . "</span><br>";
    }
    
    echo "</div>";
}

$baseUrl = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];

echo "<h2>🔍 Testando APIs do Sistema</h2>";

// Teste 1: Listar veículos
testURL($baseUrl . '/api.php?action=vehicles', '1. Listar Veículos');

// Teste 2: Listar regras de custo  
testURL($baseUrl . '/api.php?action=cost-rules', '2. Listar Regras de Custo');

// Teste 3: Tentar login
testURL($baseUrl . '/auth-api.php?action=login', '3. Teste de Login', [
    'email' => 'admin@fretecalc.com',
    'password' => 'admin123'
]);

echo "<h2>📋 Interpretação dos Resultados:</h2>";
echo "<ul>";
echo "<li><strong>HTTP 200:</strong> ✅ API funcionando</li>";
echo "<li><strong>HTTP 400:</strong> ⚠️ Erro de dados/parâmetros</li>";
echo "<li><strong>HTTP 500:</strong> ❌ Erro interno do servidor</li>";
echo "<li><strong>success: true:</strong> ✅ Operação bem-sucedida</li>";
echo "<li><strong>success: false:</strong> ❌ Erro na operação</li>";
echo "</ul>";

echo "<h2>🎯 Próximos Passos:</h2>";
echo "<div style='background:#e8f5e8;padding:15px;border-radius:5px;'>";
echo "<p>Se os testes mostrarem <strong>success: true</strong>, as APIs estão funcionando!</p>";
echo "<p>Se houver erros, me envie os resultados para analisar.</p>";
echo "</div>";
?>