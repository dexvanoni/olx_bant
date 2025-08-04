<?php
class HomeController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function index() {
        try {
            // Buscar materiais disponíveis com quantidade
            $materiais = $this->db->fetchAll("
                SELECT * FROM materiais 
                WHERE status = 'disponivel' AND quantidade_disponivel > 0
                ORDER BY created_at DESC
            ");
            
            // Incluir view
            include 'views/layout/header.php';
            include 'views/home/index.php';
            include 'views/layout/footer.php';
        } catch (Exception $e) {
            echo "Erro no HomeController: " . $e->getMessage();
        }
    }
} 