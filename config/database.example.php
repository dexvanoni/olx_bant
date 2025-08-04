<?php
// Exemplo de configuração do banco de dados
// Copie este arquivo para database.php e configure com suas credenciais

// Configurações do banco de dados
define('DB_HOST', 'localhost');        // Host do banco de dados
define('DB_NAME', 'olx_bant');         // Nome do banco de dados
define('DB_USER', 'root');             // Usuário do banco
define('DB_PASS', '');                 // Senha do banco

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function query($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }
    
    public function fetch($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }
    
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
}

// Criar tabelas se não existirem
function createTables() {
    $db = Database::getInstance();
    
    // Tabela de administradores
    $db->query("
        CREATE TABLE IF NOT EXISTS administradores (
            id INT AUTO_INCREMENT PRIMARY KEY,
            usuario VARCHAR(50) UNIQUE NOT NULL,
            senha VARCHAR(255) NOT NULL,
            nome VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Tabela de materiais com quantidade
    $db->query("
        CREATE TABLE IF NOT EXISTS materiais (
            id INT AUTO_INCREMENT PRIMARY KEY,
            descricao TEXT NOT NULL,
            local_retirada VARCHAR(200) NOT NULL,
            numero_bmp VARCHAR(50) NOT NULL,
            dono_carga VARCHAR(100) NOT NULL,
            condicao_item ENUM('excelente', 'bom', 'regular', 'ruim') NOT NULL,
            tipo_material VARCHAR(100) NOT NULL,
            quantidade_total INT NOT NULL DEFAULT 1,
            quantidade_disponivel INT NOT NULL DEFAULT 1,
            status ENUM('disponivel', 'aguardando_retirada', 'resgatado') DEFAULT 'disponivel',
            fotos JSON,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    
    // Tabela de resgates com quantidade
    $db->query("
        CREATE TABLE IF NOT EXISTS resgates (
            id INT AUTO_INCREMENT PRIMARY KEY,
            material_id INT NOT NULL,
            quantidade_resgatada INT NOT NULL DEFAULT 1,
            posto_graduacao VARCHAR(50) NOT NULL,
            nome_guerra VARCHAR(100) NOT NULL,
            contato VARCHAR(20) NOT NULL,
            email VARCHAR(100) NOT NULL,
            esquadrao VARCHAR(100) NOT NULL,
            setor VARCHAR(100) NOT NULL,
            status ENUM('aguardando_retirada', 'retirado', 'expirado') DEFAULT 'aguardando_retirada',
            data_resgate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            data_limite TIMESTAMP,
            data_retirada TIMESTAMP NULL,
            FOREIGN KEY (material_id) REFERENCES materiais(id) ON DELETE CASCADE
        )
    ");
    
    // Inserir administrador padrão se não existir
    $admin = $db->fetch("SELECT id FROM administradores WHERE usuario = 'admin'");
    if (!$admin) {
        $senha_hash = password_hash('admin123', PASSWORD_DEFAULT);
        $db->query("
            INSERT INTO administradores (usuario, senha, nome, email) 
            VALUES (?, ?, ?, ?)
        ", ['admin', $senha_hash, 'Administrador', 'admin@bant.com']);
    }
}

// Executar criação das tabelas
createTables(); 