<?php
// Definir a variável $route se não estiver definida
if (!isset($route)) {
    $route = $_GET['route'] ?? 'admin';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - <?= SITE_NAME ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #0d6efd;
            --secondary-blue: #0a58ca;
            --light-blue: #e7f1ff;
            --dark-blue: #052c65;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--dark-blue) 0%, var(--secondary-blue) 100%);
            color: white;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin: 0.25rem 0;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .main-content {
            margin-left: 0;
        }
        
        @media (min-width: 768px) {
            .main-content {
                margin-left: 250px;
            }
        }
        
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-blue);
            border-color: var(--secondary-blue);
        }
        
        .navbar-brand {
            font-weight: bold;
            color: var(--primary-blue) !important;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar position-fixed top-0 start-0 d-flex flex-column flex-shrink-0 p-3" style="width: 250px; z-index: 1000;">
        <a href="index.php?route=admin" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <i class="bi bi-building fs-4 me-2"></i>
            <span class="fs-4">BANT Admin</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="index.php?route=admin" class="nav-link <?= $route === 'admin' ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="index.php?route=admin/materiais" class="nav-link <?= strpos($route, 'materiais') !== false ? 'active' : '' ?>">
                    <i class="bi bi-box me-2"></i>
                    Materiais
                </a>
            </li>
                                  <li>
                          <a href="index.php?route=admin/resgates" class="nav-link <?= strpos($route, 'resgates') !== false ? 'active' : '' ?>">
                              <i class="bi bi-people me-2"></i>
                              Resgates
                          </a>
                      </li>
                      <?php if (isset($_SESSION['admin_nivel']) && $_SESSION['admin_nivel'] === 'admin'): ?>
                      <li>
                          <a href="index.php?route=admin/usuarios" class="nav-link <?= strpos($route, 'usuarios') !== false ? 'active' : '' ?>">
                              <i class="bi bi-person-gear me-2"></i>
                              Usuários
                          </a>
                      </li>
                      <?php endif; ?>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-2"></i>
                <strong><?= $_SESSION['admin_nome'] ?? 'Admin' ?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                <li><a class="dropdown-item" href="index.php?route=admin/logout">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    Sair
                </a></li>
            </ul>
        </div>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <!-- Top navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">
                                <i class="bi bi-house"></i> Voltar ao Site
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page content -->
        <div class="container-fluid p-4"> 