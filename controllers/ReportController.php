<?php
class ReportController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    private function ensureAdmin() {
        if (!isset($_SESSION['admin_id']) || ($_SESSION['admin_nivel'] ?? '') !== 'admin') {
            redirect('index.php?route=admin/login');
        }
    }

    public function index() {
        $this->ensureAdmin();

        // Buscar opções para filtros
        $usuarios = $this->db->fetchAll("SELECT id, nome FROM administradores ORDER BY nome ASC");
        $setores = $this->db->fetchAll("SELECT id, nome FROM setores ORDER BY nome ASC");
        $materiais = $this->db->fetchAll("SELECT id, descricao FROM materiais ORDER BY descricao ASC");

        include 'views/layout/admin_header.php';
        include 'views/admin/relatorios/index.php';
        include 'views/layout/admin_footer.php';
    }

    // AJAX: retornar dados do relatório em JSON
    public function dataAjax() {
        header('Content-Type: application/json');
        $this->ensureAdmin();

        $filters = [
            'start' => $_GET['start'] ?? null,
            'end' => $_GET['end'] ?? null,
            'usuario' => isset($_GET['usuario']) && $_GET['usuario'] !== '' ? (int)$_GET['usuario'] : null,
            'setor' => isset($_GET['setor']) && $_GET['setor'] !== '' ? (int)$_GET['setor'] : null,
            'material' => isset($_GET['material']) && $_GET['material'] !== '' ? (int)$_GET['material'] : null,
        ];

        $data = $this->buildReportData($filters);

        echo json_encode(array_merge(['success' => true], $data));
        exit;
    }

    // Construir dados do relatório (reutilizável por dataAjax e exportPdf)
    private function buildReportData(array $filters = []) {
        $start = $filters['start'] ?? null;
        $end = $filters['end'] ?? null;
        $usuario = $filters['usuario'] ?? null;
        $setor = $filters['setor'] ?? null;
        $material = $filters['material'] ?? null;

        $params = [];
        $whereResgates = " WHERE 1=1 ";
        if ($start) { $whereResgates .= " AND r.data_resgate >= ? "; $params[] = $start . ' 00:00:00'; }
        if ($end) { $whereResgates .= " AND r.data_resgate <= ? "; $params[] = $end . ' 23:59:59'; }
        if ($usuario) { $whereResgates .= " AND r.administrador_id = ? "; $params[] = $usuario; }
        if ($material) { $whereResgates .= " AND r.material_id = ? "; $params[] = $material; }
        if ($setor) { $whereResgates .= " AND m.setor_id = ? "; $params[] = $setor; }

        // Série temporal: resgates por dia
        $sqlSeries = "
            SELECT DATE(r.data_resgate) as dia, COUNT(*) as total
            FROM resgates r
            JOIN materiais m ON r.material_id = m.id
            $whereResgates
            GROUP BY DATE(r.data_resgate)
            ORDER BY DATE(r.data_resgate) ASC
        ";
        $series = $this->db->fetchAll($sqlSeries, $params);

        // KPIs
        $kpis = [];
        // total resgates no período
        $kpis['total_resgates'] = $this->db->fetch("SELECT COUNT(*) as total FROM resgates r JOIN materiais m ON r.material_id = m.id $whereResgates", $params)['total'] ?? 0;
        // materiais disponíveis (no estado atual, ignorando período)
        $kpis['materiais_disponiveis'] = $this->db->fetch("SELECT COUNT(*) as total FROM materiais WHERE status = 'disponivel' AND ativo = 1")['total'] ?? 0;
        // materiais retirados no período
        $kpis['materiais_retirados'] = $this->db->fetch("SELECT COUNT(*) as total FROM resgates r JOIN materiais m ON r.material_id = m.id $whereResgates AND r.status = 'retirado'", $params)['total'] ?? 0;

        // Lista detalhada de materiais entregues (retirados)
        $sqlDetalhe = "
            SELECT r.id, r.data_retirada, r.quantidade_resgatada, r.nome_guerra, r.posto_graduacao, r.esquadrao, m.id AS material_id, m.descricao, m.numero_bmp
            FROM resgates r
            JOIN materiais m ON r.material_id = m.id
            WHERE r.status = 'retirado'
        ";
        $paramsDetalhe = [];
        if ($start) { $sqlDetalhe .= " AND r.data_retirada >= ? "; $paramsDetalhe[] = $start . ' 00:00:00'; }
        if ($end) { $sqlDetalhe .= " AND r.data_retirada <= ? "; $paramsDetalhe[] = $end . ' 23:59:59'; }
        if ($usuario) { $sqlDetalhe .= " AND r.administrador_id = ? "; $paramsDetalhe[] = $usuario; }
        if ($material) { $sqlDetalhe .= " AND r.material_id = ? "; $paramsDetalhe[] = $material; }
        if ($setor) { $sqlDetalhe .= " AND m.setor_id = ? "; $paramsDetalhe[] = $setor; }
        $sqlDetalhe .= " ORDER BY r.data_retirada DESC LIMIT 1000";
        $detalhes = $this->db->fetchAll($sqlDetalhe, $paramsDetalhe);

        return [
            'series' => $series,
            'kpis' => $kpis,
            'detalhes' => $detalhes
        ];
    }

    // Exportar PDF usando DOMPDF (server-side)
    public function exportPdf() {
        $this->ensureAdmin();
        // Coletar filtros do POST
        $start = $_POST['start'] ?? null;
        $end = $_POST['end'] ?? null;
        $usuario = isset($_POST['usuario']) && $_POST['usuario'] !== '' ? (int)$_POST['usuario'] : null;
        $setor = isset($_POST['setor']) && $_POST['setor'] !== '' ? (int)$_POST['setor'] : null;
        $material = isset($_POST['material']) && $_POST['material'] !== '' ? (int)$_POST['material'] : null;

        // Construir filtros e obter dados do relatório diretamente
        $filters = [
            'start' => $start,
            'end' => $end,
            'usuario' => $usuario,
            'setor' => $setor,
            'material' => $material,
        ];
        $data = $this->buildReportData($filters);

        // Renderizar HTML mais elaborado para PDF (com estilos)
        $style = '
            <style>
                body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #222; }
                .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
                .title { font-size:18px; font-weight:700; }
                .meta { font-size:12px; color:#555; }
                .kpis { display:flex; gap:10px; margin:12px 0 18px 0; }
                .kpi { flex:1; padding:10px; border-radius:6px; background:#f5f6fb; text-align:center; }
                .kpi .num { font-size:18px; font-weight:700; color:#0d6efd; }
                .chart { text-align:center; margin:10px 0; }
                table { width:100%; border-collapse:collapse; margin-top:8px; font-size:12px; }
                th, td { border:1px solid #ddd; padding:6px 8px; text-align:left; }
                th { background:#f1f3f5; font-weight:700; }
                tbody tr:nth-child(even) { background:#fafafa; }
                .small { font-size:11px; color:#666; }
            </style>
        ';

        $html = '<html><head><meta charset="utf-8" />' . $style . '</head><body>';
        $html .= '<div class="header"><div class="title">Relatório - Sistema de Resgate</div><div class="meta">Gerado em: ' . date('d/m/Y H:i') . '</div></div>';
        $html .= '<div class="meta">Período: <strong>' . ($start ?: '-') . '</strong> até <strong>' . ($end ?: '-') . '</strong></div>';

        // Incluir imagem do gráfico se enviada (base64)
        if (!empty($_POST['chart_image'])) {
            $chartHtml = '<div class="chart"><img src="' . $_POST['chart_image'] . '" style="max-width:100%;height:auto;"></div>';
            $html .= $chartHtml;
        }

        $html .= '<div class="kpis">';
        $html .= '<div class="kpi"><div class="small">Total de Resgates</div><div class="num">' . ($data['kpis']['total_resgates'] ?? 0) . '</div></div>';
        $html .= '<div class="kpi"><div class="small">Materiais Disponíveis</div><div class="num">' . ($data['kpis']['materiais_disponiveis'] ?? 0) . '</div></div>';
        $html .= '<div class="kpi"><div class="small">Materiais Retirados</div><div class="num">' . ($data['kpis']['materiais_retirados'] ?? 0) . '</div></div>';
        $html .= '</div>';

        $html .= '<h3>Materiais entregues (detalhado)</h3>';
        $html .= '<table><thead><tr><th style="width:140px">Data</th><th>Material (BMP)</th><th style="width:90px">Quantidade</th><th>Destinatário</th></tr></thead><tbody>';
        foreach ($data['detalhes'] as $d) {
            $html .= '<tr>';
            $html .= '<td>' . ($d['data_retirada'] ?? '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($d['descricao']) . ' <span class="small">(' . htmlspecialchars($d['numero_bmp']) . ')</span></td>';
            $html .= '<td style="text-align:center;">' . htmlspecialchars($d['quantidade_resgatada']) . '</td>';
            $html .= '<td>' . htmlspecialchars($d['posto_graduacao'] . ' ' . $d['nome_guerra'] . ' - ' . $d['esquadrao']) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        $html .= '</body></html>';

        // Gerar PDF com DOMPDF se disponível
        if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
            require_once __DIR__ . '/../vendor/autoload.php';
        }
        $dompdfClass = '\\Dompdf\\Dompdf';
        if (class_exists($dompdfClass)) {
            $dompdf = new $dompdfClass();
            $dompdf->loadHtml($html);
            // Usar orientação landscape conforme preferência
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            $dompdf->stream('relatorio_resgates_' . date('Ymd_His') . '.pdf', ['Attachment' => 1]);
            exit;
        } else {
            // Se dompdf não estiver instalado, retornar HTML para download manual
            header('Content-Type: text/html; charset=utf-8');
            echo '<h3>DOMPDF não encontrado</h3>';
            echo '<p>Instale a biblioteca <code>dompdf/dompdf</code> via composer para habilitar a exportação em PDF.</p>';
            echo $html;
            exit;
        }
    }
}

