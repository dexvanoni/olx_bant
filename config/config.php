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