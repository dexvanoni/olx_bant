<?php
session_start();
require_once 'config/database.php';
require_once 'config/config.php';

// Roteamento simples
$route = $_GET['route'] ?? 'home';

// Controllers
require_once 'controllers/HomeController.php';
require_once 'controllers/AdminController.php';
require_once 'controllers/MaterialController.php';
require_once 'controllers/ResgateController.php';
require_once 'controllers/UsuarioController.php';
require_once 'controllers/SetorController.php';

// Roteamento
switch ($route) {
    case 'admin':
        $controller = new AdminController();
        $controller->index();
        break;
    case 'admin/login':
        $controller = new AdminController();
        $controller->login();
        break;
    case 'admin/logout':
        $controller = new AdminController();
        $controller->logout();
        break;
    case 'admin/materiais':
        $controller = new MaterialController();
        $controller->index();
        break;
    case 'admin/materiais/criar':
        $controller = new MaterialController();
        $controller->criar();
        break;
    case 'admin/materiais/salvar':
        $controller = new MaterialController();
        $controller->salvar();
        break;
    case 'admin/materiais/editar':
        $controller = new MaterialController();
        $controller->editar();
        break;
    case 'admin/materiais/excluir':
        $controller = new MaterialController();
        $controller->excluir();
        break;
    case 'admin/resgates':
        $controller = new ResgateController();
        $controller->index();
        break;
    case 'admin/resgates/detalhes':
        $controller = new ResgateController();
        $controller->detalhes();
        break;
    case 'admin/resgates/retirar':
        $controller = new ResgateController();
        $controller->retirar();
        break;
    case 'admin/resgates/cancelar':
        $controller = new ResgateController();
        $controller->cancelar();
        break;
    case 'admin/usuarios':
        $controller = new UsuarioController();
        $controller->index();
        break;
    case 'admin/usuarios/criar':
        $controller = new UsuarioController();
        $controller->criar();
        break;
    case 'admin/usuarios/editar':
        $controller = new UsuarioController();
        $controller->editar();
        break;
    case 'admin/usuarios/excluir':
        $controller = new UsuarioController();
        $controller->excluir();
        break;
    case 'admin/setores':
        $controller = new SetorController();
        $controller->index();
        break;
    case 'admin/setores/criar':
        $controller = new SetorController();
        $controller->criar();
        break;
    case 'admin/setores/editar':
        $controller = new SetorController();
        $controller->editar();
        break;
    case 'admin/setores/excluir':
        $controller = new SetorController();
        $controller->excluir();
        break;
    case 'admin/setores/get':
        $controller = new SetorController();
        $controller->getSetores();
        break;
    case 'resgatar':
        $controller = new ResgateController();
        $controller->resgatar();
        break;
    case 'resgatar/salvar':
        $controller = new ResgateController();
        $controller->salvar();
        break;
    default:
        $controller = new HomeController();
        $controller->index();
        break;
} 