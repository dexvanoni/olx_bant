<?php
/**
 * Migração: Adiciona coluna `ativo` em `materiais` para controle de visibilidade na página pública.
 * Execute este script uma vez: php migrate_ativar_material.php
 */

require_once 'config/database.php';
require_once 'config/config.php';

$db = Database::getInstance();

echo "Iniciando migração: adicionar coluna ativo em materiais...\n";

try {
    // Verificar se a coluna já existe (consulta simples)
    $col = $db->fetch("SHOW COLUMNS FROM materiais LIKE 'ativo'");
    if ($col) {
        echo "A coluna 'ativo' já existe. Nada a fazer.\n";
        exit;
    }

    echo "Adicionando coluna 'ativo' (TINYINT(1) DEFAULT 1)...\n";
    $db->query("
        ALTER TABLE materiais
        ADD COLUMN ativo TINYINT(1) NOT NULL DEFAULT 1 AFTER updated_at
    ");

    echo "✓ Coluna 'ativo' adicionada com sucesso.\n";
    echo "\nMigração concluída. Registros existentes permanecerão ativos por padrão.\n";
    echo "Reversão: ALTER TABLE materiais DROP COLUMN ativo;\n";
} catch (Exception $e) {
    echo "❌ Erro na migração: " . $e->getMessage() . "\n";
    exit(1);
}

