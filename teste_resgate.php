<?php
// Arquivo de teste para verificar o funcionamento do resgate
require_once 'config/database.php';
require_once 'config/config.php';

// Simular dados de POST
$_POST = [
    'material_id' => '1',
    'posto_graduacao' => 'Sgt',
    'nome_guerra' => 'Teste',
    'contato' => '123456789',
    'email' => 'teste@bant.com',
    'esquadrao' => 'Esquadrão Teste',
    'setor' => 'Setor Teste'
];

// Simular método POST
$_SERVER['REQUEST_METHOD'] = 'POST';

// Incluir o controller
require_once 'controllers/ResgateController.php';

// Executar o método salvar
$controller = new ResgateController();
$controller->salvar();
?> 