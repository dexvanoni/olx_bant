<?php
/**
 * Script de migração para adicionar suporte a disputas
 * Execute este script uma vez para atualizar o banco de dados
 */

require_once 'config/database.php';
require_once 'config/config.php';

$db = Database::getInstance();

echo "Iniciando migração de disputas...\n";

try {
    // 1. Adicionar coluna data_limite_disputa na tabela materiais
    echo "Adicionando coluna data_limite_disputa...\n";
    $db->query("
        ALTER TABLE materiais 
        ADD COLUMN data_limite_disputa TIMESTAMP NULL 
        AFTER status
    ");
    echo "✓ Coluna data_limite_disputa adicionada\n";
    
    // 2. Adicionar coluna data_disputa na tabela resgates
    echo "Adicionando coluna data_disputa...\n";
    $db->query("
        ALTER TABLE resgates 
        ADD COLUMN data_disputa TIMESTAMP NULL 
        AFTER data_limite
    ");
    echo "✓ Coluna data_disputa adicionada\n";
    
    // 3. Adicionar status 'em_disputa' na tabela resgates
    echo "Atualizando ENUM de status...\n";
    $db->query("
        ALTER TABLE resgates 
        MODIFY COLUMN status ENUM('aguardando_retirada', 'retirado', 'expirado', 'cancelado', 'em_disputa') 
        DEFAULT 'aguardando_retirada'
    ");
    echo "✓ Status 'em_disputa' adicionado\n";
    
    // 4. Adicionar status 'em_disputa' na tabela materiais
    echo "Atualizando ENUM de status dos materiais...\n";
    $db->query("
        ALTER TABLE materiais 
        MODIFY COLUMN status ENUM('disponivel', 'aguardando_retirada', 'resgatado', 'em_disputa') 
        DEFAULT 'disponivel'
    ");
    echo "✓ Status 'em_disputa' adicionado aos materiais\n";
    
    echo "\n✅ Migração concluída com sucesso!\n";
    echo "O sistema agora suporta disputas de materiais.\n";
    
} catch (Exception $e) {
    echo "❌ Erro na migração: " . $e->getMessage() . "\n";
} 