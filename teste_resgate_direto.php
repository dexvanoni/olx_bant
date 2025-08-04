<?php
// Teste direto do resgate
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Teste Direto do Resgate</h1>";

// Simular dados POST
$_POST = [
    'material_id' => '1',
    'quantidade_resgatada' => '1',
    'posto_graduacao' => 'Sgt',
    'nome_guerra' => 'Teste',
    'contato' => '123456789',
    'email' => 'teste@teste.com',
    'esquadrao' => 'Esquadrão Teste',
    'setor' => 'Setor Teste'
];

echo "<h2>Dados simulados:</h2>";
echo "<pre>" . print_r($_POST, true) . "</pre>";

// Carregar configurações
require_once 'config/database.php';
require_once 'config/config.php';
require_once 'controllers/ResgateController.php';

// Simular método POST
$_SERVER['REQUEST_METHOD'] = 'POST';

echo "<h2>Executando resgate...</h2>";

try {
    $controller = new ResgateController();
    $controller->salvar();
} catch (Exception $e) {
    echo "<h3>Erro:</h3>";
    echo "<p><strong>Mensagem:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Arquivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Linha:</strong> " . $e->getLine() . "</p>";
    echo "<p><strong>Trace:</strong></p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>Teste concluído!</h2>";
?> 