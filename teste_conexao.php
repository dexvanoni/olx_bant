<?php
// Teste de conexão com o banco de dados
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Teste de Conexão - Sistema BANT</h1>";

try {
    // Testar configurações
    echo "<h2>1. Verificando configurações...</h2>";
    
    if (!file_exists('config/database.php')) {
        die("❌ Arquivo config/database.php não encontrado!");
    }
    
    require_once 'config/database.php';
    
    echo "✅ Arquivo de configuração carregado<br>";
    echo "Host: " . DB_HOST . "<br>";
    echo "Database: " . DB_NAME . "<br>";
    echo "User: " . DB_USER . "<br>";
    
    // Testar conexão
    echo "<h2>2. Testando conexão...</h2>";
    
    $db = Database::getInstance();
    echo "✅ Conexão com banco de dados estabelecida<br>";
    
    // Testar criação de tabelas
    echo "<h2>3. Criando tabelas...</h2>";
    
    createTables();
    echo "✅ Tabelas criadas/verificadas com sucesso<br>";
    
    // Verificar se as tabelas existem
    echo "<h2>4. Verificando tabelas...</h2>";
    
    $tables = $db->fetchAll("SHOW TABLES");
    echo "Tabelas encontradas:<br>";
    foreach ($tables as $table) {
        $tableName = array_values($table)[0];
        echo "- " . $tableName . "<br>";
    }
    
    // Verificar administrador
    echo "<h2>5. Verificando administrador...</h2>";
    
    $admin = $db->fetch("SELECT * FROM administradores WHERE usuario = 'admin'");
    if ($admin) {
        echo "✅ Administrador encontrado: " . $admin['nome'] . "<br>";
        echo "Usuário: admin<br>";
        echo "Senha: admin123<br>";
    } else {
        echo "❌ Administrador não encontrado<br>";
    }
    
    // Verificar materiais
    echo "<h2>6. Verificando materiais...</h2>";
    
    $materiais = $db->fetchAll("SELECT COUNT(*) as total FROM materiais");
    echo "Total de materiais: " . $materiais[0]['total'] . "<br>";
    
    // Verificar resgates
    echo "<h2>7. Verificando resgates...</h2>";
    
    $resgates = $db->fetchAll("SELECT COUNT(*) as total FROM resgates");
    echo "Total de resgates: " . $resgates[0]['total'] . "<br>";
    
    echo "<h2>✅ Sistema funcionando corretamente!</h2>";
    echo "<p><a href='index.php'>Clique aqui para acessar o sistema</a></p>";
    
} catch (Exception $e) {
    echo "<h2>❌ Erro encontrado:</h2>";
    echo "<p><strong>Erro:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Arquivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Linha:</strong> " . $e->getLine() . "</p>";
    
    echo "<h3>Possíveis soluções:</h3>";
    echo "<ul>";
    echo "<li>Verifique se o MySQL está rodando</li>";
    echo "<li>Verifique as credenciais no arquivo config/database.php</li>";
    echo "<li>Verifique se o banco de dados 'olx_bant' existe</li>";
    echo "<li>Verifique se o usuário tem permissões</li>";
    echo "</ul>";
}
?> 