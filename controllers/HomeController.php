<?php
class HomeController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function index() {
        try {
            // Processar materiais que podem voltar para disponível (todos resgates cancelados)
            processarMateriaisVoltarDisponivel($this->db);
            
            // Buscar materiais disponíveis com nova lógica de disputa
            $materiais = $this->db->fetchAll("
                SELECT m.* FROM materiais m
                WHERE m.status IN ('disponivel', 'aguardando_retirada', 'resgatado')
                AND m.status != 'disputa_encerrada'
                OR (m.status = 'em_disputa' AND (
                    -- Material em disputa que ainda não expirou (pode receber novos resgates)
                    (m.data_limite_disputa IS NULL OR m.data_limite_disputa > NOW())
                    OR 
                    -- Material em disputa que expirou mas ainda tem resgates pendentes
                    (m.data_limite_disputa <= NOW() AND EXISTS (
                        SELECT 1 FROM resgates r 
                        WHERE r.material_id = m.id 
                        AND r.status IN ('aguardando_retirada', 'em_disputa')
                    ))
                ))
                ORDER BY m.created_at DESC
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