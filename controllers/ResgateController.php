<?php
class ResgateController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function index() {
        // Verificar se está logado
        if (!isset($_SESSION['admin_id'])) {
            redirect('index.php?route=admin/login');
        }
        
        $admin = $this->db->fetch("SELECT * FROM administradores WHERE id = ?", [$_SESSION['admin_id']]);
        
        // Se for admin geral, mostra todos os resgates
        if ($admin['nivel'] === 'admin') {
            $resgates = $this->db->fetchAll("
                SELECT r.*, m.descricao as material_descricao, m.local_retirada
                FROM resgates r
                JOIN materiais m ON r.material_id = m.id
                ORDER BY r.data_resgate DESC
            ");
        } else {
            // Se for admin de setor, mostra apenas resgates dos materiais do seu setor
            $resgates = $this->db->fetchAll("
                SELECT r.*, m.descricao as material_descricao, m.local_retirada
                FROM resgates r
                JOIN materiais m ON r.material_id = m.id
                WHERE m.setor_id = ?
                ORDER BY r.data_resgate DESC
            ", [$admin['setor_id']]);
        }
        
        // Definir a rota atual
        $route = 'admin/resgates';
        
        include 'views/layout/admin_header.php';
        include 'views/admin/resgates/index.php';
        include 'views/layout/admin_footer.php';
    }
    
    public function resgatar() {
        $material_id = $_GET['id'] ?? 0;
        $material = $this->db->fetch("SELECT * FROM materiais WHERE id = ? AND (status = 'disponivel' OR status = 'resgatado' OR status = 'em_disputa')", [$material_id]);
        
        if (!$material) {
            redirect('index.php');
        }
        
        include 'views/layout/header.php';
        include 'views/resgate/form.php';
        include 'views/layout/footer.php';
    }
    
    public function salvar() {
        // Definir header JSON
        header('Content-Type: application/json');
        
        // Log para debug
        error_log("ResgateController::salvar() chamado");
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        try {
            $material_id = $_POST['material_id'] ?? 0;
            $quantidade_resgatada = (int)($_POST['quantidade_resgatada'] ?? 1);
            $posto_graduacao = sanitize($_POST['posto_graduacao'] ?? '');
            $nome_guerra = sanitize($_POST['nome_guerra'] ?? '');
            $contato = sanitize($_POST['contato'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $esquadrao = sanitize($_POST['esquadrao'] ?? '');
            $setor = sanitize($_POST['setor'] ?? '');
            $justificativa = sanitize($_POST['justificativa'] ?? '');
            
            // Validar campos obrigatórios
            if (empty($material_id) || empty($posto_graduacao) || empty($nome_guerra) || 
                empty($contato) || empty($email) || empty($esquadrao) || empty($setor) || 
                empty($justificativa) || $quantidade_resgatada <= 0) {
                echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios e a quantidade deve ser maior que zero']);
                return;
            }
            
            // Buscar o material, permitindo disponível, resgatado, E em disputa
            $material = $this->db->fetch("SELECT * FROM materiais WHERE id = ? AND status IN ('disponivel', 'resgatado', 'em_disputa', 'aguardando_retirada')", [$material_id]);
            if (!$material) {
                echo json_encode(['success' => false, 'message' => 'Material não está disponível']);
                return;
            }
            
            // Calcular data limite
            $data_limite = date('Y-m-d H:i:s', strtotime('+' . RESGATE_TIMEOUT_HOURS . ' hours'));
            
            // Inserir resgate normalmente
            $this->db->query("
                INSERT INTO resgates (material_id, quantidade_resgatada, posto_graduacao, nome_guerra, contato, email, esquadrao, setor, justificativa, data_limite)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ", [$material_id, $quantidade_resgatada, $posto_graduacao, $nome_guerra, $contato, $email, $esquadrao, $setor, $justificativa, $data_limite]);
            
            // Atualizar quantidade disponível do material
            $nova_quantidade = $material['quantidade_disponivel'] - $quantidade_resgatada;
            $novo_status = determinarStatusMaterial($nova_quantidade, $material['quantidade_total']);
            
            // Verificar se deve entrar em disputa
            if (verificarDisputa($material_id, $this->db)) {
                $novo_status = 'em_disputa';
                // Marcar data da disputa nos resgates pendentes
                $this->db->query("
                    UPDATE resgates 
                    SET status = 'em_disputa', data_disputa = NOW() 
                    WHERE material_id = ? AND status = 'aguardando_retirada'
                ", [$material_id]);
            }
            
            $this->db->query("UPDATE materiais SET quantidade_disponivel = ?, status = ? WHERE id = ?", 
                [$nova_quantidade, $novo_status, $material_id]);
            
            // Mensagem de disputa
            $mensagem = 'Resgate realizado com sucesso! Você tem até ' . RESGATE_TIMEOUT_HOURS . ' horas para retirar este item.';
            if ($material['quantidade_disponivel'] < $quantidade_resgatada) {
                $mensagem .= ' <b>Atenção:</b> Este material está em disputa. O administrador do setor irá decidir quem receberá o item.';
            }
            
            $response = ['success' => true, 'message' => $mensagem];
            echo json_encode($response);
            return;
            
        } catch (Exception $e) {
            $response = ['success' => false, 'message' => 'Erro interno do servidor: ' . $e->getMessage()];
            error_log("Erro no resgate: " . $e->getMessage());
            echo json_encode($response);
        }
    }
    
    private function enviarEmailResgate($material, $posto_graduacao, $nome_guerra, $email) {
        $to = ADMIN_EMAIL;
        $subject = "Novo resgate de material - BANT";
        $message = "
            Novo resgate realizado:
            
            Material: {$material['descricao']}
            Posto/Graduação: {$posto_graduacao}
            Nome de Guerra: {$nome_guerra}
            Email: {$email}
            Data: " . date('d/m/Y H:i:s') . "
            
            Acesse o painel administrativo para mais detalhes.
        ";
        
        $headers = "From: noreply@bant.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        mail($to, $subject, $message, $headers);
    }
    
    public function processarTimeout() {
        // Processar resgates expirados
        $this->db->query("
            UPDATE resgates 
            SET status = 'expirado' 
            WHERE status = 'aguardando_retirada' 
            AND data_limite < NOW()
        ");
        
        // Retornar materiais expirados para disponível
        $this->db->query("
            UPDATE materiais m
            JOIN resgates r ON m.id = r.material_id
            SET m.status = 'disponivel'
            WHERE r.status = 'expirado'
        ");
    }

    public function detalhes() {
        if (!isset($_SESSION['admin_id'])) {
            redirect('index.php?route=admin/login');
        }
        
        $admin = $this->db->fetch("SELECT * FROM administradores WHERE id = ?", [$_SESSION['admin_id']]);
        $id = $_GET['id'] ?? 0;
        
        // Query base
        $query = "SELECT r.*, m.descricao as material_descricao, m.local_retirada, m.numero_bmp, m.dono_carga, m.tipo_material, m.condicao_item, m.fotos, a.nome as admin_nome, s.nome as setor_nome FROM resgates r JOIN materiais m ON r.material_id = m.id LEFT JOIN administradores a ON m.administrador_id = a.id LEFT JOIN setores s ON m.setor_id = s.id WHERE r.id = ?";
        $params = [$id];
        
        // Se for admin de setor, adicionar filtro por setor
        if ($admin['nivel'] !== 'admin') {
            $query .= " AND m.setor_id = ?";
            $params[] = $admin['setor_id'];
        }
        
        $resgate = $this->db->fetch($query, $params);
        
        if (!$resgate) {
            redirect('index.php?route=admin/resgates');
        }
        
        $route = 'admin/resgates';
        include 'views/layout/admin_header.php';
        include 'views/admin/resgates/detalhes.php';
        include 'views/layout/admin_footer.php';
    }

    public function retirar() {
        if (!isset($_SESSION['admin_id'])) {
            redirect('index.php?route=admin/login');
        }
        
        $admin = $this->db->fetch("SELECT * FROM administradores WHERE id = ?", [$_SESSION['admin_id']]);
        $id = $_GET['id'] ?? 0;
        
        // Query base
        $query = "SELECT r.* FROM resgates r JOIN materiais m ON r.material_id = m.id WHERE r.id = ?";
        $params = [$id];
        
        // Se for admin de setor, adicionar filtro por setor
        if ($admin['nivel'] !== 'admin') {
            $query .= " AND m.setor_id = ?";
            $params[] = $admin['setor_id'];
        }
        
        $resgate = $this->db->fetch($query, $params);
        
        if (!$resgate || $resgate['status'] !== 'aguardando_retirada') {
            redirect('index.php?route=admin/resgates');
        }
        
        // Atualizar status do resgate
        $this->db->query("UPDATE resgates SET status = 'retirado', data_retirada = NOW() WHERE id = ?", [$id]);
        showAlert('Resgate marcado como retirado!', 'success');
        redirect('index.php?route=admin/resgates');
    }

    public function cancelar() {
        if (!isset($_SESSION['admin_id'])) {
            redirect('index.php?route=admin/login');
        }
        
        $admin = $this->db->fetch("SELECT * FROM administradores WHERE id = ?", [$_SESSION['admin_id']]);
        $id = $_GET['id'] ?? 0;
        
        // Query base
        $query = "SELECT r.* FROM resgates r JOIN materiais m ON r.material_id = m.id WHERE r.id = ?";
        $params = [$id];
        
        // Se for admin de setor, adicionar filtro por setor
        if ($admin['nivel'] !== 'admin') {
            $query .= " AND m.setor_id = ?";
            $params[] = $admin['setor_id'];
        }
        
        $resgate = $this->db->fetch($query, $params);
        
        if (!$resgate || $resgate['status'] !== 'aguardando_retirada') {
            redirect('index.php?route=admin/resgates');
        }
        
        // Devolver quantidade ao estoque
        $material = $this->db->fetch("SELECT * FROM materiais WHERE id = ?", [$resgate['material_id']]);
        if ($material) {
            $nova_quantidade = $material['quantidade_disponivel'] + $resgate['quantidade_resgatada'];
            $novo_status = determinarStatusMaterial($nova_quantidade, $material['quantidade_total']);
            
            $this->db->query("UPDATE materiais SET quantidade_disponivel = ?, status = ? WHERE id = ?", [$nova_quantidade, $novo_status, $material['id']]);
        }
        
        // Atualizar status do resgate
        $this->db->query("UPDATE resgates SET status = 'cancelado' WHERE id = ?", [$id]);
        showAlert('Resgate cancelado e quantidade devolvida ao estoque!', 'success');
        redirect('index.php?route=admin/resgates');
    }
} 