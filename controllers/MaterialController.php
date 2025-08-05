<?php
class MaterialController {
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
        
        // Se for admin geral, mostra todos os materiais
        if ($admin['nivel'] === 'admin') {
            $materiais = $this->db->fetchAll("
                SELECT m.*,
                       COUNT(r.id) as total_resgates,
                       SUM(CASE WHEN r.status IN ('aguardando_retirada', 'em_disputa') THEN 1 ELSE 0 END) as resgates_pendentes,
                       SUM(r.quantidade_resgatada) as total_resgatado,
                       a.nome as admin_nome,
                       s.nome as setor_nome
                FROM materiais m
                LEFT JOIN resgates r ON m.id = r.material_id
                LEFT JOIN administradores a ON m.administrador_id = a.id
                LEFT JOIN setores s ON m.setor_id = s.id
                GROUP BY m.id
                ORDER BY m.created_at DESC
            ");
        } else {
            // Se for admin de setor, mostra apenas materiais do seu setor
            $materiais = $this->db->fetchAll("
                SELECT m.*,
                       COUNT(r.id) as total_resgates,
                       SUM(CASE WHEN r.status IN ('aguardando_retirada', 'em_disputa') THEN 1 ELSE 0 END) as resgates_pendentes,
                       SUM(r.quantidade_resgatada) as total_resgatado,
                       a.nome as admin_nome,
                       s.nome as setor_nome
                FROM materiais m
                LEFT JOIN resgates r ON m.id = r.material_id
                LEFT JOIN administradores a ON m.administrador_id = a.id
                LEFT JOIN setores s ON m.setor_id = s.id
                WHERE m.setor_id = ?
                GROUP BY m.id
                ORDER BY m.created_at DESC
            ", [$admin['setor_id']]);
        }
        
        // Definir a rota atual
        $route = 'admin/materiais';
        
        include 'views/layout/admin_header.php';
        include 'views/admin/materiais/index.php';
        include 'views/layout/admin_footer.php';
    }
    
    public function criar() {
        if (!isset($_SESSION['admin_id'])) {
            redirect('index.php?route=admin/login');
        }
        
        // Buscar setores para o select
        $setores = $this->db->fetchAll("SELECT * FROM setores WHERE ativo = 1 ORDER BY nome ASC");
        
        // Definir a rota atual
        $route = 'admin/materiais';
        
        include 'views/layout/admin_header.php';
        include 'views/admin/materiais/criar.php';
        include 'views/layout/admin_footer.php';
    }
    
    public function salvar() {
        if (!isset($_SESSION['admin_id'])) {
            redirect('index.php?route=admin/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $descricao = sanitize($_POST['descricao']);
            $local_retirada = sanitize($_POST['local_retirada']);
            $numero_bmp = sanitize($_POST['numero_bmp']);
            $dono_carga = sanitize($_POST['dono_carga']);
            $condicao_item = sanitize($_POST['condicao_item']);
            $tipo_material = sanitize($_POST['tipo_material']);
            $quantidade_total = (int)($_POST['quantidade_total'] ?? 1);
            $quantidade_disponivel = $quantidade_total;
            $setor_id = (int)($_POST['setor_id'] ?? 0);
            
            // Validar campos obrigatórios
            if (empty($descricao) || empty($local_retirada) || empty($numero_bmp) || empty($dono_carga) || empty($tipo_material) || $setor_id <= 0) {
                showAlert('Todos os campos obrigatórios devem ser preenchidos', 'danger');
                redirect('index.php?route=admin/materiais/criar');
            }
            
            // Processar upload de fotos
            $fotos = [];
            if (isset($_FILES['fotos'])) {
                $upload_dir = UPLOAD_PATH;
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                foreach ($_FILES['fotos']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['fotos']['error'][$key] === UPLOAD_ERR_OK) {
                        $file_name = $_FILES['fotos']['name'][$key];
                        $file_size = $_FILES['fotos']['size'][$key];
                        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                        
                        // Validar arquivo
                        if ($file_size <= MAX_FILE_SIZE && in_array($file_ext, ALLOWED_EXTENSIONS)) {
                            $new_name = uniqid() . '.' . $file_ext;
                            $upload_path = $upload_dir . $new_name;
                            
                            if (move_uploaded_file($tmp_name, $upload_path)) {
                                $fotos[] = $new_name;
                            }
                        }
                    }
                }
            }
            
            $this->db->query("
                INSERT INTO materiais (descricao, local_retirada, numero_bmp, dono_carga, condicao_item, tipo_material, quantidade_total, quantidade_disponivel, fotos, administrador_id, setor_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ", [$descricao, $local_retirada, $numero_bmp, $dono_carga, $condicao_item, $tipo_material, $quantidade_total, $quantidade_disponivel, json_encode($fotos), $_SESSION['admin_id'], $setor_id]);
            
            redirect('index.php?route=admin/materiais');
        }
    }
    
    public function editar() {
        if (!isset($_SESSION['admin_id'])) {
            redirect('index.php?route=admin/login');
        }
        $id = $_GET['id'] ?? 0;
        $material = $this->db->fetch("SELECT * FROM materiais WHERE id = ?", [$id]);
        if (!$material) {
            redirect('index.php?route=admin/materiais');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $descricao = sanitize($_POST['descricao']);
            $local_retirada = sanitize($_POST['local_retirada']);
            $numero_bmp = sanitize($_POST['numero_bmp']);
            $dono_carga = sanitize($_POST['dono_carga']);
            $condicao_item = sanitize($_POST['condicao_item']);
            $tipo_material = sanitize($_POST['tipo_material']);
            $quantidade_total = (int)($_POST['quantidade_total'] ?? 1);
            $setor = sanitize($_POST['setor']);
            // Fotos
            $fotos = json_decode($material['fotos'], true) ?: [];
            if (isset($_FILES['fotos']) && is_array($_FILES['fotos']['name'])) {
                for ($i = 0; $i < count($_FILES['fotos']['name']); $i++) {
                    if ($_FILES['fotos']['error'][$i] === UPLOAD_ERR_OK) {
                        $file = $_FILES['fotos'];
                        $ext = strtolower(pathinfo($file['name'][$i], PATHINFO_EXTENSION));
                        if (in_array($ext, ALLOWED_EXTENSIONS) && $file['size'][$i] <= MAX_FILE_SIZE) {
                            $filename = uniqid() . '.' . $ext;
                            $filepath = UPLOAD_PATH . $filename;
                            if (move_uploaded_file($file['tmp_name'][$i], $filepath)) {
                                $fotos[] = $filename;
                            }
                        }
                    }
                }
            }
            // Atualizar material
            $this->db->query("
                UPDATE materiais SET descricao = ?, local_retirada = ?, numero_bmp = ?, dono_carga = ?, condicao_item = ?, tipo_material = ?, quantidade_total = ?, setor = ?, fotos = ? WHERE id = ?
            ", [$descricao, $local_retirada, $numero_bmp, $dono_carga, $condicao_item, $tipo_material, $quantidade_total, $setor, json_encode($fotos), $id]);
            showAlert('Material atualizado com sucesso!', 'success');
            redirect('index.php?route=admin/materiais');
        }
        include 'views/layout/admin_header.php';
        include 'views/admin/materiais/editar.php';
        include 'views/layout/admin_footer.php';
    }
    
    public function excluir() {
        if (!isset($_SESSION['admin_id'])) {
            redirect('index.php?route=admin/login');
        }
        
        $id = $_POST['id'] ?? 0;
        
        // Buscar fotos para deletar
        $material = $this->db->fetch("SELECT fotos FROM materiais WHERE id = ?", [$id]);
        if ($material && $material['fotos']) {
            $fotos = json_decode($material['fotos'], true);
            foreach ($fotos as $foto) {
                $file_path = UPLOAD_PATH . $foto;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
        }
        
        $this->db->query("DELETE FROM materiais WHERE id = ?", [$id]);
        
        echo json_encode(['success' => true]);
    }

    // AJAX: Buscar resgates de um material
    public function getResgatesAjax() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['admin_id'])) {
            echo json_encode(['success' => false, 'message' => 'Não autorizado']);
            exit;
        }
        $material_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($material_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID inválido']);
            exit;
        }
        $resgates = $this->db->fetchAll(
            "SELECT r.id, r.nome_guerra as nome_usuario, r.data_resgate as data_solicitacao, r.status, r.justificativa, r.posto_graduacao, r.esquadrao, r.setor, r.contato
             FROM resgates r
             WHERE r.material_id = ?
             ORDER BY r.data_resgate ASC",
            [$material_id]
        );
        echo json_encode(['success' => true, 'resgates' => $resgates]);
        exit;
    }

    // AJAX: Marcar um resgate como retirado e cancelar os outros
    public function marcarRetiradoAjax() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['admin_id'])) {
            echo json_encode(['success' => false, 'message' => 'Não autorizado']);
            exit;
        }
        $input = json_decode(file_get_contents('php://input'), true);
        $resgate_id = isset($input['resgate_id']) ? (int)$input['resgate_id'] : 0;
        $material_id = isset($input['material_id']) ? (int)$input['material_id'] : 0;
        if ($resgate_id <= 0 || $material_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
            exit;
        }
        // Marcar o resgate selecionado como retirado
        $this->db->query("UPDATE resgates SET status = 'retirado' WHERE id = ?", [$resgate_id]);
        // Cancelar os outros resgates do mesmo material que ainda não foram retirados
        $this->db->query("UPDATE resgates SET status = 'cancelado' WHERE material_id = ? AND id != ? AND status IN ('aguardando_retirada', 'em_disputa')", [$material_id, $resgate_id]);
        
        // Se o material estava em disputa, resolver a disputa
        $material = $this->db->fetch("SELECT status FROM materiais WHERE id = ?", [$material_id]);
        if ($material && $material['status'] === 'em_disputa') {
            $this->db->query("UPDATE materiais SET status = 'resgatado' WHERE id = ?", [$material_id]);
        }
        echo json_encode(['success' => true]);
        exit;
    }
} 