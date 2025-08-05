    <!-- Footer - Deve aparecer em todas as páginas -->
    <!-- DEBUG: Footer sendo incluído - <?= date('Y-m-d H:i:s') ?> -->
    <footer class="footer" id="mainFooter">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="bi bi-building"></i> Base Aérea de Natal</h5>
                    <p>Sistema de Resgate de Materiais</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; 2024 BANT. Todos os direitos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        // Inicializar DataTables
        $(document).ready(function() {
            $('.datatable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
                },
                responsive: true,
                pageLength: 25
            });
        });
        
        // Garantir que o footer seja sempre visível
        document.addEventListener('DOMContentLoaded', function() {
            const footer = document.querySelector('.footer');
            console.log('Footer encontrado:', footer);
            console.log('Todos os elementos footer:', document.querySelectorAll('footer'));
            if (footer) {
                footer.style.display = 'block';
                footer.style.visibility = 'visible';
                footer.style.opacity = '1';
                console.log('Footer configurado como visível');
            } else {
                console.error('Footer não encontrado!');
            }
        });
        
        // Monitorar mudanças no DOM para garantir que o footer permaneça visível
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                    const footer = document.querySelector('.footer');
                    if (footer && (footer.style.display === 'none' || footer.style.visibility === 'hidden')) {
                        footer.style.display = 'block';
                        footer.style.visibility = 'visible';
                        footer.style.opacity = '1';
                    }
                }
            });
        });
        
        // Iniciar observação quando o DOM estiver carregado
        document.addEventListener('DOMContentLoaded', function() {
            const footer = document.querySelector('.footer');
            if (footer) {
                observer.observe(footer, { attributes: true, attributeFilter: ['style'] });
            }
        });
        
        // Forçar visibilidade do footer a cada segundo
        setInterval(function() {
            const footer = document.querySelector('.footer');
            if (footer) {
                footer.style.display = 'block';
                footer.style.visibility = 'visible';
                footer.style.opacity = '1';
                console.log('Footer mantido visível');
            } else {
                console.warn('Footer não encontrado no intervalo');
            }
        }, 1000);
        
        // Função para mostrar alertas
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
    </script>
</body>
</html> 