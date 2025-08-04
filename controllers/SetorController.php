<?php
class SetorController {
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
        
        $setores = $this->db->fetchAll("
            SELECT s.*, COUNT(a.id) as total_admins
            FROM setores s
            LEFT JOIN administradores a ON s.id = a.setor_id
            GROUP BY s.id
            ORDER BY s.nome ASC
        ");
        
        // Definir a rota atual
        $route = 'admin/setores';
        
        include 'views/layout/admin_header.php';
        include 'views/admin/setores/index.php';
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
            $nome = sanitize($_POST['nome'] ?? '');
            $descricao = sanitize($_POST['descricao'] ?? '');
            
            // Validar campos
            if (empty($nome)) {
                showAlert('Nome do setor é obrigatório', 'danger');
            } else {
                // Verificar se setor já existe
                $existente = $this->db->fetch("SELECT id FROM setores WHERE nome = ?", [$nome]);
                if ($existente) {
                    showAlert('Setor já existe', 'danger');
                } else {
                    $this->db->query("
                        INSERT INTO setores (nome, descricao)
                        VALUES (?, ?)
                    ", [$nome, $descricao]);
                    
                    showAlert('Setor criado com sucesso!', 'success');
                    redirect('index.php?route=admin/setores');
                }
            }
        }
        
        // Definir a rota atual
        $route = 'admin/setores';
        
        include 'views/layout/admin_header.php';
        include 'views/admin/setores/criar.php';
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
        $setor = $this->db->fetch("SELECT * FROM setores WHERE id = ?", [$id]);
        
        if (!$setor) {
            redirect('index.php?route=admin/setores');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = sanitize($_POST['nome'] ?? '');
            $descricao = sanitize($_POST['descricao'] ?? '');
            $ativo = isset($_POST['ativo']) ? 1 : 0;
            
            // Validar campos
            if (empty($nome)) {
                showAlert('Nome do setor é obrigatório', 'danger');
            } else {
                // Verificar se setor já existe (exceto o atual)
                $existente = $this->db->fetch("SELECT id FROM setores WHERE nome = ? AND id != ?", [$nome, $id]);
                if ($existente) {
                    showAlert('Setor já existe', 'danger');
                } else {
                    $this->db->query("
                        UPDATE setores 
                        SET nome = ?, descricao = ?, ativo = ?
                        WHERE id = ?
                    ", [$nome, $descricao, $ativo, $id]);
                    
                    showAlert('Setor atualizado com sucesso!', 'success');
                    redirect('index.php?route=admin/setores');
                }
            }
        }
        
        // Definir a rota atual
        $route = 'admin/setores';
        
        include 'views/layout/admin_header.php';
        include 'views/admin/setores/editar.php';
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
        
        // Verificar se o setor existe
        $setor = $this->db->fetch("SELECT * FROM setores WHERE id = ?", [$id]);
        if (!$setor) {
            redirect('index.php?route=admin/setores');
        }
        
        // Verificar se há administradores usando este setor
        $admins = $this->db->fetch("SELECT COUNT(*) as total FROM administradores WHERE setor_id = ?", [$id]);
        if ($admins['total'] > 0) {
            showAlert('Não é possível excluir um setor que possui administradores', 'danger');
            redirect('index.php?route=admin/setores');
        }
        
        // Excluir setor
        $this->db->query("DELETE FROM setores WHERE id = ?", [$id]);
        
        showAlert('Setor excluído com sucesso!', 'success');
        redirect('index.php?route=admin/setores');
    }
    
    // Método para obter setores via AJAX (usado nos formulários)
    public function getSetores() {
        header('Content-Type: application/json');
        
        $setores = $this->db->fetchAll("
            SELECT id, nome 
            FROM setores 
            WHERE ativo = 1 
            ORDER BY nome ASC
        ");
        
        echo json_encode($setores);
    }
} 