# Relatório Administrativo

Este documento descreve a nova funcionalidade de relatórios para administradores gerais.

## Funcionalidades
- Página de relatórios com filtros por data, usuário, setor e material.  
- Gráfico de série temporal (resgates ao longo do tempo) usando Chart.js.  
- KPIs: total de resgates, materiais disponíveis, materiais retirados.  
- Tabela detalhada com materiais entregues e destinatários.  
- Exportação para PDF (server-side) usando DOMPDF (requer composer: `composer require dompdf/dompdf`).

## Rotas
- `index.php?route=admin/relatorios` — página de relatórios.  
- `index.php?route=admin/relatorios/data` — endpoint AJAX que retorna dados em JSON.  
- `index.php?route=admin/relatorios/exportPdf` — gera e envia PDF.

## Instalação da dependência PDF
Execute no servidor (diretório raiz do projeto):

```bash
composer require dompdf/dompdf
```

## Observações
- Apenas administradores com `nivel === 'admin'` têm acesso à página.  
- Para incluir os gráficos no PDF (imagens), recomende-se gerar as imagens client-side (Chart.js) e enviar como base64 no POST para `exportPdf`. A versão atual gera um PDF baseado em tabelas e números se o DOMPDF estiver presente.

