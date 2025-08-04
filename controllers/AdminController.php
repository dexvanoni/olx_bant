<?php
class AdminController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function index() {
        // Verificar se está logado
        if (!isset($_SESSION['admin_id'])) {
            redirect('index.php?route=admin/login');
        }
        
        // Buscar estatísticas
        $total_materiais = $this->db->fetch("SELECT COUNT(*) as total FROM materiais")['total'];
        $materiais_disponiveis = $this->db->fetch("SELECT COUNT(*) as total FROM materiais WHERE status = 'disponivel'")['total'];
        $resgates_pendentes = $this->db->fetch("SELECT COUNT(*) as total FROM resgates WHERE status = 'aguardando_retirada'")['total'];
        
        // Definir a rota atual
        $route = 'admin';
        
        include 'views/layout/admin_header.php';
        include 'views/admin/dashboard.php';
        include 'views/layout/admin_footer.php';
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = sanitize($_POST['usuario'] ?? '');
            $senha = $_POST['senha'] ?? '';
            
            if (empty($usuario) || empty($senha)) {
                showAlert('Usuário e senha são obrigatórios', 'danger');
            } else {
                $admin = $this->db->fetch("SELECT * FROM administradores WHERE usuario = ? AND ativo = 1", [$usuario]);
                
                if ($admin && password_verify($senha, $admin['senha'])) {
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_nome'] = $admin['nome'];
                    $_SESSION['admin_nivel'] = $admin['nivel'];
                    $_SESSION['admin_setor_id'] = $admin['setor_id'];
                    
                    // Buscar nome do setor
                    $setor = $this->db->fetch("SELECT nome FROM setores WHERE id = ?", [$admin['setor_id']]);
                    $_SESSION['admin_setor'] = $setor['nome'] ?? 'N/A';
                    
                    redirect('index.php?route=admin');
                } else {
                    showAlert('Usuário ou senha incorretos', 'danger');
                }
            }
        }
        
        include 'views/layout/header.php';
        include 'views/admin/login.php';
        include 'views/layout/footer.php';
    }
    
    public function logout() {
        session_destroy();
        redirect('index.php');
    }
} 