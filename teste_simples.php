<?php
// Teste simples para verificar se o PHP está funcionando
echo "<h1>Teste Simples - Sistema BANT</h1>";
echo "<p>✅ PHP está funcionando!</p>";

// Verificar se os arquivos existem
echo "<h2>Verificando arquivos:</h2>";
$files = [
    'config/database.php',
    'config/config.php',
    'controllers/HomeController.php',
    'views/layout/header.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ $file existe<br>";
    } else {
        echo "❌ $file não existe<br>";
    }
}

// Verificar se as pastas existem
echo "<h2>Verificando pastas:</h2>";
$dirs = [
    'config',
    'controllers',
    'views',
    'uploads'
];

foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        echo "✅ $dir existe<br>";
    } else {
        echo "❌ $dir não existe<br>";
    }
}

echo "<h2>Informações do servidor:</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";

echo "<h2>Links de teste:</h2>";
echo "<p><a href='index.php'>Acessar sistema principal</a></p>";
echo "<p><a href='teste_conexao.php'>Testar conexão com banco</a></p>";
?> 