<?php
class HomeController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function index() {
        try {
            // Processar disputas expiradas antes de mostrar materiais
            processarDisputasExpiradas($this->db);
            
            // Buscar materiais disponíveis com quantidade (não mostrar em disputa)
            $materiais = $this->db->fetchAll("
                SELECT * FROM materiais 
                WHERE status IN ('disponivel', 'resgatado', 'em_disputa', 'aguardando_retirada')
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