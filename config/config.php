<?php
// Configurações gerais do sistema
define('SITE_NAME', 'Sistema de Resgate de Materiais - BANT');
define('SITE_URL', 'http://10.68.44.144/olx_bant');
define('UPLOAD_PATH', 'uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);
define('RESGATE_TIMEOUT_HOURS', 48); // 48 horas para retirada

// Verificar se a pasta uploads existe
if (!is_dir(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0755, true);
}

// Configurações de e-mail
define('ADMIN_EMAIL', 'dex.vanoni@gmail.com');
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'dex.vanoni@gmail.com');
define('SMTP_PASSWORD', 'dhekfxvqblpabpbe');

// Funções utilitárias
function redirect($url) {
    header("Location: $url");
    exit;
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function generateToken() {
    return bin2hex(random_bytes(32));
}

function validateToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Função para mostrar alertas
function showAlert($message, $type = 'success') {
    $_SESSION['alert'] = [
        'message' => $message,
        'type' => $type
    ];
}

// Função para determinar status do material baseado na quantidade
function determinarStatusMaterial($quantidade_disponivel, $quantidade_total) {
    if ($quantidade_disponivel <= 0) {
        return 'resgatado';
    } elseif ($quantidade_disponivel >= $quantidade_total) {
        return 'disponivel';
    } else {
        return 'aguardando_retirada';
    }
}

// Função para verificar se material deve entrar em disputa
function verificarDisputa($material_id, $db) {
    // Buscar informações do material e seus resgates
    $material = $db->fetch("SELECT * FROM materiais WHERE id = ?", [$material_id]);
    if (!$material) return false;
    
    // Contar resgates pendentes
    $resgates_pendentes = $db->fetch("
        SELECT COUNT(*) as total 
        FROM resgates 
        WHERE material_id = ? AND status = 'aguardando_retirada'
    ", [$material_id]);
    
    // Se há mais resgates pendentes que quantidade total, entrar em disputa
    if ($resgates_pendentes['total'] > $material['quantidade_total']) {
        return true;
    }
    
    return false;
}

// Função para processar disputas expiradas
function processarDisputasExpiradas($db) {
    $prazo_disputa = 24; // 24 horas
    
    // Buscar materiais em disputa há mais de 24 horas
    $materiais_disputa = $db->fetchAll("
        SELECT DISTINCT m.id, m.descricao
        FROM materiais m
        JOIN resgates r ON m.id = r.material_id
        WHERE m.status = 'em_disputa'
        AND r.data_disputa < DATE_SUB(NOW(), INTERVAL ? HOUR)
    ", [$prazo_disputa]);
    
    foreach ($materiais_disputa as $material) {
        // Cancelar todos os resgates pendentes do material
        $db->query("
            UPDATE resgates 
            SET status = 'cancelado' 
            WHERE material_id = ? AND status IN ('aguardando_retirada', 'em_disputa')
        ", [$material['id']]);
        
        // Manter material como resgatado (não volta para disponível)
        $db->query("
            UPDATE materiais 
            SET status = 'resgatado' 
            WHERE id = ?
        ", [$material['id']]);
    }
} 