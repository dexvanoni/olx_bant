<?php
// Teste do header
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Teste iniciado<br>";

// Definir constantes necessárias
define('SITE_NAME', 'Sistema de Resgate de Materiais - BANT');
define('UPLOAD_PATH', 'uploads/');

echo "Constantes definidas<br>";

// Testar se o arquivo existe
if (file_exists('views/layout/header.php')) {
    echo "✅ Header existe<br>";
    
    // Testar include
    try {
        include 'views/layout/header.php';
        echo "✅ Header incluído com sucesso<br>";
    } catch (Exception $e) {
        echo "❌ Erro ao incluir header: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ Header não existe<br>";
}

echo "Teste concluído<br>";
?> 