<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste AJAX Simples</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Teste AJAX Simples</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Teste de Resgate</h5>
                    </div>
                    <div class="card-body">
                        <form id="testeForm">
                            <div class="mb-3">
                                <label for="material_id" class="form-label">Material ID</label>
                                <input type="number" class="form-control" id="material_id" name="material_id" value="1" required>
                            </div>
                            <div class="mb-3">
                                <label for="quantidade_resgatada" class="form-label">Quantidade</label>
                                <input type="number" class="form-control" id="quantidade_resgatada" name="quantidade_resgatada" value="1" required>
                            </div>
                            <div class="mb-3">
                                <label for="posto_graduacao" class="form-label">Posto/Graduação</label>
                                <input type="text" class="form-control" id="posto_graduacao" name="posto_graduacao" value="Sgt" required>
                            </div>
                            <div class="mb-3">
                                <label for="nome_guerra" class="form-label">Nome de Guerra</label>
                                <input type="text" class="form-control" id="nome_guerra" name="nome_guerra" value="Teste" required>
                            </div>
                            <div class="mb-3">
                                <label for="contato" class="form-label">Contato</label>
                                <input type="text" class="form-control" id="contato" name="contato" value="123456789" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="teste@teste.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="esquadrao" class="form-label">Esquadrão</label>
                                <input type="text" class="form-control" id="esquadrao" name="esquadrao" value="Esquadrão Teste" required>
                            </div>
                            <div class="mb-3">
                                <label for="setor" class="form-label">Setor</label>
                                <input type="text" class="form-control" id="setor" name="setor" value="Setor Teste" required>
                            </div>
                            <button type="submit" class="btn btn-primary" id="btnTeste">
                                <i class="bi bi-check-circle"></i> Testar Resgate
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Log de Resposta</h5>
                    </div>
                    <div class="card-body">
                        <div id="log" style="height: 400px; overflow-y: auto; background: #f8f9fa; padding: 10px; font-family: monospace; font-size: 12px;">
                            Aguardando teste...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('testeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btnTeste = document.getElementById('btnTeste');
            const log = document.getElementById('log');
            
            // Mostrar loading
            btnTeste.disabled = true;
            btnTeste.innerHTML = '<i class="bi bi-hourglass-split"></i> Processando...';
            
            // Limpar log
            log.innerHTML = 'Iniciando teste...<br>';
            
            const formData = new FormData(this);
            
            // Mostrar dados enviados
            log.innerHTML += 'Dados enviados:<br>';
            for (let [key, value] of formData.entries()) {
                log.innerHTML += `${key}: ${value}<br>`;
            }
            log.innerHTML += '<br>';
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'index.php?route=resgatar/salvar', true);
            xhr.timeout = 10000;
            
            xhr.onload = function() {
                log.innerHTML += `Status: ${xhr.status}<br>`;
                log.innerHTML += `Resposta: ${xhr.responseText}<br>`;
                
                try {
                    const data = JSON.parse(xhr.responseText);
                    log.innerHTML += `JSON parseado: ${JSON.stringify(data, null, 2)}<br>`;
                    
                    if (data.success) {
                        log.innerHTML += '<span style="color: green;">✅ Sucesso!</span><br>';
                    } else {
                        log.innerHTML += '<span style="color: red;">❌ Erro: ' + data.message + '</span><br>';
                    }
                } catch (e) {
                    log.innerHTML += '<span style="color: red;">❌ Erro ao parsear JSON: ' + e.message + '</span><br>';
                }
                
                btnTeste.disabled = false;
                btnTeste.innerHTML = '<i class="bi bi-check-circle"></i> Testar Resgate';
            };
            
            xhr.onerror = function() {
                log.innerHTML += '<span style="color: red;">❌ Erro na requisição XHR</span><br>';
                btnTeste.disabled = false;
                btnTeste.innerHTML = '<i class="bi bi-check-circle"></i> Testar Resgate';
            };
            
            xhr.ontimeout = function() {
                log.innerHTML += '<span style="color: red;">❌ Timeout na requisição</span><br>';
                btnTeste.disabled = false;
                btnTeste.innerHTML = '<i class="bi bi-check-circle"></i> Testar Resgate';
            };
            
            log.innerHTML += 'Enviando requisição...<br>';
            xhr.send(formData);
        });
    </script>
</body>
</html> 