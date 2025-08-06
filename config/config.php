<?php
// Configurações gerais do sistema
define('SITE_NAME', 'Sistema de Resgate de Materiais - BANT');
define('SITE_URL', 'http://10.68.44.144/olx_bant');
define('UPLOAD_PATH', 'uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);
define('RESGATE_TIMEOUT_HOURS', 48); // 48 horas para retirada
define('DISPUTA_TIMEOUT_HOURS', 48); // 48 horas para disputa

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

// Função para marcar material como disputa encerrada
function marcarDisputaEncerrada($material_id, $db) {
    $db->query("
        UPDATE materiais 
        SET status = 'disputa_encerrada', 
            quantidade_disponivel = 0,
            data_limite_disputa = NULL 
        WHERE id = ?
    ", [$material_id]);
}

// Função para verificar se material deve entrar em disputa
function verificarDisputa($material_id, $db) {
    // Buscar informações do material e seus resgates
    $material = $db->fetch("SELECT * FROM materiais WHERE id = ?", [$material_id]);
    if (!$material) return false;
    
    // Calcular quantidade total solicitada em resgates pendentes
    $resgates_pendentes = $db->fetch("
        SELECT SUM(quantidade_resgatada) as total_solicitado 
        FROM resgates 
        WHERE material_id = ? AND status IN ('aguardando_retirada', 'em_disputa')
    ", [$material_id]);
    
    $total_solicitado = $resgates_pendentes['total_solicitado'] ?? 0;
    
    // Se há mais quantidade solicitada que quantidade total disponível, entrar em disputa
    if ($total_solicitado > $material['quantidade_total']) {
        return true;
    }
    
    return false;
}

// Função para verificar se disputa expirou (apenas para impedir novos resgates)
function verificarDisputaExpirada($material_id, $db) {
    $material = $db->fetch("SELECT * FROM materiais WHERE id = ?", [$material_id]);
    if (!$material) return false;
    
    // Se o material está em disputa e passou do prazo, não permite novos resgates
    if ($material['status'] === 'em_disputa' && 
        $material['data_limite_disputa'] && 
        $material['data_limite_disputa'] < date('Y-m-d H:i:s')) {
        return true;
    }
    
    return false;
}

// Função para verificar se material pode voltar para disponível (todos resgates cancelados)
function verificarMaterialPodeVoltarDisponivel($material_id, $db) {
    $material = $db->fetch("SELECT * FROM materiais WHERE id = ?", [$material_id]);
    if (!$material) return false;
    
    // Se o material está em disputa, verificar se todos os resgates foram cancelados
    if ($material['status'] === 'em_disputa') {
        $resgates_ativos = $db->fetch("
            SELECT COUNT(*) as total FROM resgates 
            WHERE material_id = ? AND status IN ('aguardando_retirada', 'em_disputa')
        ", [$material_id]);
        
        // Se não há resgates ativos, pode voltar para disponível
        if ($resgates_ativos['total'] == 0) {
            return true;
        }
    }
    
    return false;
}

// Função para processar materiais que podem voltar para disponível
function processarMateriaisVoltarDisponivel($db) {
    // Buscar materiais em disputa que podem voltar para disponível
    $materiais_disputa = $db->fetchAll("
        SELECT m.* FROM materiais m
        WHERE m.status = 'em_disputa'
    ");
    
    foreach ($materiais_disputa as $material) {
        if (verificarMaterialPodeVoltarDisponivel($material['id'], $db)) {
            // Voltar material para disponível
            $db->query("
                UPDATE materiais 
                SET status = 'disponivel', 
                    quantidade_disponivel = quantidade_total,
                    data_limite_disputa = NULL 
                WHERE id = ?
            ", [$material['id']]);
        }
    }
} 

 