<?php
class UsuarioController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function index() {
        // Verificar se está logado e é admin
        if (!isset($_SESSION['admin_id'])) {
            redirect('index.php?route=admin/login');
        }
        
        $admin = $this->db->fetch("SELECT * FROM administradores WHERE id = ?", [$_SESSION['admin_id']]);
        if (!$admin || $admin['nivel'] !== 'admin') {
            redirect('index.php?route=admin');
        }
        
        $usuarios = $this->db->fetchAll("
            SELECT a.*, s.nome as setor_nome 
            FROM administradores a
            LEFT JOIN setores s ON a.setor_id = s.id
            ORDER BY a.nome ASC
        ");
        
        // Definir a rota atual
        $route = 'admin/usuarios';
        
        include 'views/layout/admin_header.php';
        include 'views/admin/usuarios/index.php';
        include 'views/layout/admin_footer.php';
    }
    
    public function criar() {
        // Verificar se está logado e é admin
        if (!isset($_SESSION['admin_id'])) {
            redirect('index.php?route=admin/login');
        }
        
        $admin = $this->db->fetch("SELECT * FROM administradores WHERE id = ?", [$_SESSION['admin_id']]);
        if (!$admin || $admin['nivel'] !== 'admin') {
            redirect('index.php?route=admin');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = sanitize($_POST['usuario'] ?? '');
            $senha = $_POST['senha'] ?? '';
            $nome = sanitize($_POST['nome'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $setor_id = (int)($_POST['setor_id'] ?? 0);
            $nivel = sanitize($_POST['nivel'] ?? 'setor');
            
            // Validar campos
            if (empty($usuario) || empty($senha) || empty($nome) || empty($email) || $setor_id <= 0) {
                showAlert('Todos os campos são obrigatórios', 'danger');
            } else {
                // Verificar se usuário já existe
                $existente = $this->db->fetch("SELECT id FROM administradores WHERE usuario = ?", [$usuario]);
                if ($existente) {
                    showAlert('Nome de usuário já existe', 'danger');
                } else {
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    
                    $this->db->query("
                        INSERT INTO administradores (usuario, senha, nome, email, setor_id, nivel)
                        VALUES (?, ?, ?, ?, ?, ?)
                    ", [$usuario, $senha_hash, $nome, $email, $setor_id, $nivel]);
                    
                    showAlert('Usuário criado com sucesso!', 'success');
                    redirect('index.php?route=admin/usuarios');
                }
            }
        }
        
        // Buscar setores para o select
        $setores = $this->db->fetchAll("SELECT * FROM setores WHERE ativo = 1 ORDER BY nome ASC");
        
        // Definir a rota atual
        $route = 'admin/usuarios';
        
        include 'views/layout/admin_header.php';
        include 'views/admin/usuarios/criar.php';
        include 'views/layout/admin_footer.php';
    }
    
    public function editar() {
        // Verificar se está logado e é admin
        if (!isset($_SESSION['admin_id'])) {
            redirect('index.php?route=admin/login');
        }
        
        $admin = $this->db->fetch("SELECT * FROM administradores WHERE id = ?", [$_SESSION['admin_id']]);
        if (!$admin || $admin['nivel'] !== 'admin') {
            redirect('index.php?route=admin');
        }
        
        $id = $_GET['id'] ?? 0;
        $usuario = $this->db->fetch("SELECT * FROM administradores WHERE id = ?", [$id]);
        
        if (!$usuario) {
            redirect('index.php?route=admin/usuarios');
        }
        
        // Buscar setores para o select
        $setores = $this->db->fetchAll("SELECT * FROM setores WHERE ativo = 1 ORDER BY nome ASC");
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = sanitize($_POST['nome'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $senha = $_POST['senha'] ?? '';
            $setor_id = (int)($_POST['setor_id'] ?? 0);
            $nivel = sanitize($_POST['nivel'] ?? 'setor');
            $ativo = isset($_POST['ativo']) ? 1 : 0;
            
            // Validar campos
            if (empty($nome) || empty($email) || $setor_id <= 0) {
                showAlert('Nome, email e setor são obrigatórios', 'danger');
            } else {
                if (!empty($senha)) {
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    $this->db->query("
                        UPDATE administradores 
                        SET nome = ?, email = ?, senha = ?, setor_id = ?, nivel = ?, ativo = ?
                        WHERE id = ?
                    ", [$nome, $email, $senha_hash, $setor_id, $nivel, $ativo, $id]);
                } else {
                    $this->db->query("
                        UPDATE administradores 
                        SET nome = ?, email = ?, setor_id = ?, nivel = ?, ativo = ?
                        WHERE id = ?
                    ", [$nome, $email, $setor_id, $nivel, $ativo, $id]);
                }
                
                showAlert('Usuário atualizado com sucesso!', 'success');
                redirect('index.php?route=admin/usuarios');
            }
        }
        
        // Definir a rota atual
        $route = 'admin/usuarios';
        
        include 'views/layout/admin_header.php';
        include 'views/admin/usuarios/editar.php';
        include 'views/layout/admin_footer.php';
    }
    
    public function excluir() {
        // Verificar se está logado e é admin
        if (!isset($_SESSION['admin_id'])) {
            redirect('index.php?route=admin/login');
        }
        
        $admin = $this->db->fetch("SELECT * FROM administradores WHERE id = ?", [$_SESSION['admin_id']]);
        if (!$admin || $admin['nivel'] !== 'admin') {
            redirect('index.php?route=admin');
        }
        
        $id = $_GET['id'] ?? 0;
        
        // Não permitir excluir o próprio usuário
        if ($id == $_SESSION['admin_id']) {
            showAlert('Não é possível excluir seu próprio usuário', 'danger');
            redirect('index.php?route=admin/usuarios');
        }
        
        // Verificar se o usuário existe
        $usuario = $this->db->fetch("SELECT * FROM administradores WHERE id = ?", [$id]);
        if (!$usuario) {
            redirect('index.php?route=admin/usuarios');
        }
        
        // Excluir usuário
        $this->db->query("DELETE FROM administradores WHERE id = ?", [$id]);
        
        showAlert('Usuário excluído com sucesso!', 'success');
        redirect('index.php?route=admin/usuarios');
    }
} 