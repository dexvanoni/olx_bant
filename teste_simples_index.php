<?php
// Teste simples do index com header simplificado
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Iniciando teste...<br>";

// Definir constantes
define('SITE_NAME', 'Sistema de Resgate de Materiais - BANT');

// Incluir header simplificado
include 'views/layout/header_simples.php';

echo "Teste concluído!<br>";
?> 