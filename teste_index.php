<?php
// Teste simplificado do index.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Teste do Index</h1>";

try {
    echo "1. Iniciando sessão...<br>";
    session_start();
    echo "✅ Sessão iniciada<br>";
    
    echo "2. Carregando configurações...<br>";
    require_once 'config/database.php';
    require_once 'config/config.php';
    echo "✅ Configurações carregadas<br>";
    
    echo "3. Carregando controllers...<br>";
    require_once 'controllers/HomeController.php';
    require_once 'controllers/AdminController.php';
    require_once 'controllers/MaterialController.php';
    require_once 'controllers/ResgateController.php';
    echo "✅ Controllers carregados<br>";
    
    echo "4. Definindo rota...<br>";
    $route = $_GET['route'] ?? 'home';
    echo "Rota: " . $route . "<br>";
    
    echo "5. Executando controller...<br>";
    $controller = new HomeController();
    echo "✅ Controller criado<br>";
    
    echo "6. Executando método index...<br>";
    $controller->index();
    echo "✅ Método executado<br>";
    
} catch (Exception $e) {
    echo "<h2>❌ Erro encontrado:</h2>";
    echo "<p><strong>Erro:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Arquivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Linha:</strong> " . $e->getLine() . "</p>";
    echo "<p><strong>Trace:</strong></p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?> 