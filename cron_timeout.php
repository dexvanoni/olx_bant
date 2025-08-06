<?php
/**
 * Script para processar timeouts de resgates automaticamente
 * Deve ser executado via cron job a cada hora
 * 
 * Exemplo de cron: 0 * * * * php /path/to/olx_bant/cron_timeout.php
 * 
 * NOTA: Este script NÃO processa disputas automaticamente.
 * O administrador sempre decide quem vence a disputa.
 */

require_once 'config/database.php';
require_once 'config/config.php';

$db = Database::getInstance();

echo "Iniciando processamento em " . date('Y-m-d H:i:s') . "\n";

// 1. Processar resgates expirados
echo "Processando resgates expirados...\n";
$resgates = $db->fetchAll("SELECT * FROM resgates WHERE status = 'aguardando_retirada' AND data_limite < NOW()");
foreach ($resgates as $resgate) {
    // Devolver quantidade ao estoque
    $material = $db->fetch("SELECT * FROM materiais WHERE id = ?", [$resgate['material_id']]);
    if ($material) {
        $nova_quantidade = $material['quantidade_disponivel'] + $resgate['quantidade_resgatada'];
        $novo_status = determinarStatusMaterial($nova_quantidade, $material['quantidade_total']);
        
        $db->query("UPDATE materiais SET quantidade_disponivel = ?, status = ? WHERE id = ?", [$nova_quantidade, $novo_status, $material['id']]);
    }
    // Atualizar status do resgate
    $db->query("UPDATE resgates SET status = 'cancelado' WHERE id = ?", [$resgate['id']]);
}
echo "Resgates expirados processados: " . count($resgates) . "\n";

// 2. Processar materiais que podem voltar para disponível (todos resgates cancelados)
echo "Processando materiais que podem voltar para disponível...\n";
processarMateriaisVoltarDisponivel($db);
echo "Materiais processados para voltar disponível.\n";

echo "NOTA: Disputas não são processadas automaticamente. O administrador decide os vencedores.\n";
echo "Processamento concluído em " . date('Y-m-d H:i:s') . "\n"; 