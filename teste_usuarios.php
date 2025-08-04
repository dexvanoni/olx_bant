<?php
// Teste do sistema de usuários
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Teste do Sistema de Usuários</h1>";

try {
    echo "1. Carregando configurações...<br>";
    require_once 'config/database.php';
    require_once 'config/config.php';
    echo "✅ Configurações carregadas<br>";
    
    echo "2. Testando conexão...<br>";
    $db = Database::getInstance();
    echo "✅ Conexão estabelecida<br>";
    
    echo "3. Verificando tabela de administradores...<br>";
    $admins = $db->fetchAll("SELECT * FROM administradores ORDER BY nome");
    echo "✅ Encontrados " . count($admins) . " administradores<br>";
    
    echo "<h2>Lista de Administradores:</h2>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Nome</th><th>Usuário</th><th>Email</th><th>Setor</th><th>Nível</th><th>Ativo</th></tr>";
    
    foreach ($admins as $admin) {
        echo "<tr>";
        echo "<td>" . $admin['id'] . "</td>";
        echo "<td>" . htmlspecialchars($admin['nome']) . "</td>";
        echo "<td>" . htmlspecialchars($admin['usuario']) . "</td>";
        echo "<td>" . htmlspecialchars($admin['email']) . "</td>";
        echo "<td>" . htmlspecialchars($admin['setor']) . "</td>";
        echo "<td>" . htmlspecialchars($admin['nivel']) . "</td>";
        echo "<td>" . ($admin['ativo'] ? 'Sim' : 'Não') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h2>Verificando materiais com administradores:</h2>";
    $materiais = $db->fetchAll("
        SELECT m.*, a.nome as admin_nome 
        FROM materiais m 
        LEFT JOIN administradores a ON m.administrador_id = a.id 
        ORDER BY m.id DESC 
        LIMIT 5
    ");
    
    if (count($materiais) > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Descrição</th><th>Setor</th><th>Responsável</th><th>Status</th></tr>";
        
        foreach ($materiais as $material) {
            echo "<tr>";
            echo "<td>" . $material['id'] . "</td>";
            echo "<td>" . htmlspecialchars($material['descricao']) . "</td>";
            echo "<td>" . htmlspecialchars($material['setor']) . "</td>";
            echo "<td>" . htmlspecialchars($material['admin_nome'] ?? 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($material['status']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Nenhum material encontrado.</p>";
    }
    
    echo "<h2>Links de Teste:</h2>";
    echo "<p><a href='index.php?route=admin/login'>Login Administrativo</a></p>";
    echo "<p><a href='index.php?route=admin/usuarios'>Gerenciar Usuários (apenas admin)</a></p>";
    echo "<p><a href='index.php?route=admin/materiais'>Gerenciar Materiais</a></p>";
    
    echo "<h2>Credenciais de Teste:</h2>";
    echo "<p><strong>Administrador Geral:</strong></p>";
    echo "<ul>";
    echo "<li>Usuário: admin</li>";
    echo "<li>Senha: admin123</li>";
    echo "<li>Nível: admin</li>";
    echo "<li>Setor: Sistema</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<h2>❌ Erro encontrado:</h2>";
    echo "<p><strong>Erro:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Arquivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Linha:</strong> " . $e->getLine() . "</p>";
}
?> 