<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Alerta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Teste de Alerta</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Teste de Função showAlert</h5>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-success mb-2" onclick="testeAlert('success', 'Resgate realizado com sucesso!')">
                            Teste Sucesso
                        </button>
                        <br>
                        <button type="button" class="btn btn-danger mb-2" onclick="testeAlert('danger', 'Erro no resgate!')">
                            Teste Erro
                        </button>
                        <br>
                        <button type="button" class="btn btn-warning mb-2" onclick="testeAlert('warning', 'Aviso importante!')">
                            Teste Aviso
                        </button>
                        <br>
                        <button type="button" class="btn btn-info mb-2" onclick="testeAlert('info', 'Informação importante!')">
                            Teste Info
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Console Log</h5>
                    </div>
                    <div class="card-body">
                        <p>Abra o console do navegador (F12) para ver os logs.</p>
                        <p>Os alertas devem aparecer no canto superior direito da tela.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <h3>Links de Teste</h3>
            <p><a href="index.php">Página Principal</a></p>
            <p><a href="teste_resgate_completo.php">Teste Completo de Resgate</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Função para mostrar alertas (igual ao footer.php)
        function showAlert(message, type = 'success') {
            console.log('showAlert chamado:', message, type);
            
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.style.position = 'fixed';
            alertDiv.style.top = '20px';
            alertDiv.style.right = '20px';
            alertDiv.style.zIndex = '9999';
            alertDiv.style.minWidth = '300px';
            alertDiv.innerHTML = `
                <strong>${type === 'success' ? '✅' : type === 'danger' ? '❌' : '⚠️'}</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);
            
            // Auto-remover após 5 segundos
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
            
            console.log('Alerta criado:', alertDiv);
        }
        
        // Função para testar alertas
        function testeAlert(type, message) {
            console.log('testeAlert chamado:', type, message);
            showAlert(message, type);
        }
        
        // Teste automático ao carregar a página
        window.addEventListener('load', function() {
            console.log('Página carregada, testando alerta...');
            setTimeout(() => {
                showAlert('Página carregada com sucesso!', 'success');
            }, 1000);
        });
    </script>
</body>
</html> 