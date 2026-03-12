<!-- Conteúdo Principal - Tutorial de Uso do Sistema -->
<main class="py-4">
    <div class="container">
        <!-- Cabeçalho do Tutorial -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body p-4">
                        <h1 class="h2 mb-2">
                            <i class="bi bi-book"></i> Tutorial de Utilização do Sistema
                        </h1>
                        <p class="lead mb-0 opacity-90">
                            Guia completo para Administradores, Cadastradores e Solicitantes do Sistema de Resgate de Materiais - BANT
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navegação Rápida -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Navegação Rápida</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <a href="#acesso-plataforma" class="btn btn-outline-primary w-100 text-start">
                                    <i class="bi bi-box-arrow-in-right"></i> Acesso à Plataforma
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="#cadastro-itens" class="btn btn-outline-primary w-100 text-start">
                                    <i class="bi bi-plus-circle"></i> Cadastro de Itens
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="#solicitacao-resgate" class="btn btn-outline-primary w-100 text-start">
                                    <i class="bi bi-hand-index"></i> Solicitação de Resgate
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="#disputas" class="btn btn-outline-primary w-100 text-start">
                                    <i class="bi bi-exclamation-triangle"></i> Disputas
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="#vencimento-disputa" class="btn btn-outline-primary w-100 text-start">
                                    <i class="bi bi-trophy"></i> Vencimento de Disputa
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="#administradores" class="btn btn-outline-primary w-100 text-start">
                                    <i class="bi bi-person-gear"></i> Administradores
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accordion de Tutoriais -->
        <div class="accordion" id="tutorialAccordion">
            
            <!-- 1. Acesso à Plataforma -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAcesso">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAcesso" id="acesso-plataforma">
                        <i class="bi bi-box-arrow-in-right me-2"></i> 1. Acesso à Plataforma
                    </button>
                </h2>
                <div id="collapseAcesso" class="accordion-collapse collapse show" data-bs-parent="#tutorialAccordion">
                    <div class="accordion-body">
                        <h6>Quem pode acessar?</h6>
                        <ul>
                            <li><strong>Página inicial (pública):</strong> Qualquer pessoa pode visualizar os materiais disponíveis.</li>
                            <li><strong>Painel administrativo:</strong> Apenas administradores e cadastradores com credenciais válidas.</li>
                        </ul>
                        
                        <h6 class="mt-3">Como acessar o painel administrativo?</h6>
                        <ol>
                            <li>Acesse a página inicial do sistema.</li>
                            <li>Clique em <strong>"Administração"</strong> no menu superior (ou acesse diretamente <code>index.php?route=admin</code>).</li>
                            <li>Na tela de login, informe seu <strong>usuário</strong> e <strong>senha</strong>.</li>
                            <li>Clique em <strong>"Entrar"</strong>.</li>
                        </ol>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> <strong>Exemplo:</strong> Se você é responsável pelo Setor de Registro, use as credenciais fornecidas pelo administrador geral. O sistema redirecionará você ao Dashboard após o login.
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Cadastro de Itens -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingCadastro">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCadastro" id="cadastro-itens">
                        <i class="bi bi-plus-circle me-2"></i> 2. Cadastro de Itens (Cadastradores)
                    </button>
                </h2>
                <div id="collapseCadastro" class="accordion-collapse collapse" data-bs-parent="#tutorialAccordion">
                    <div class="accordion-body">
                        <p>Usuários com permissão de cadastro (administradores de setor ou admin geral) podem incluir materiais para resgate.</p>
                        
                        <h6>Passo a passo:</h6>
                        <ol>
                            <li>Faça login no painel administrativo.</li>
                            <li>No menu lateral, clique em <strong>"Materiais"</strong>.</li>
                            <li>Clique no botão <strong>"Novo Material"</strong> (ou acesse <code>index.php?route=admin/materiais/criar</code>).</li>
                            <li>Preencha o formulário:
                                <ul>
                                    <li><strong>Material:</strong> Descrição clara (ex: "Mesa de escritório em L")</li>
                                    <li><strong>Local de Retirada:</strong> Onde o item está (ex: "Setor de Registro, Sala 101")</li>
                                    <li><strong>Número BMP:</strong> Identificador do BMP (ex: "BMP-2024-001")</li>
                                    <li><strong>Dono da Carga:</strong> Responsável pelo material — é quem determina o destino final em caso de disputa</li>
                                    <li><strong>Tipo de Material:</strong> Categoria (ex: Eletrônico, Móvel)</li>
                                    <li><strong>Condição:</strong> Excelente, Bom, Regular ou Ruim</li>
                                    <li><strong>Quantidade Total:</strong> Número de unidades disponíveis</li>
                                    <li><strong>Setor:</strong> Setor responsável (pré-selecionado para admin de setor)</li>
                                    <li><strong>Fotos:</strong> Até 3 fotos (JPG, PNG, GIF - máx. 5MB cada)</li>
                                </ul>
                            </li>
                            <li>Clique em <strong>"Cadastrar Material"</strong>.</li>
                        </ol>
                        
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> <strong>Exemplo real:</strong> "Cadeira ergonômica em bom estado, localizada no almoxarifado do 1º Esquadrão. BMP-2024-042. Dono: Sgt Silva. Condição: Bom. Quantidade: 2."
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Solicitação de Resgate -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingResgate">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseResgate" id="solicitacao-resgate">
                        <i class="bi bi-hand-index me-2"></i> 3. Solicitação de Resgate (Solicitantes)
                    </button>
                </h2>
                <div id="collapseResgate" class="accordion-collapse collapse" data-bs-parent="#tutorialAccordion">
                    <div class="accordion-body">
                        <p>Qualquer pessoa que acessa a página inicial pode solicitar o resgate de um material disponível.</p>
                        
                        <h6>Passo a passo:</h6>
                        <ol>
                            <li>Acesse a página inicial do sistema.</li>
                            <li>Navegue pelos cards de materiais disponíveis (badge verde "Disponível").</li>
                            <li>Clique no botão <strong>"Resgatar"</strong> do material desejado.</li>
                            <li>Preencha o formulário de solicitação:
                                <ul>
                                    <li><strong>Quantidade a Resgatar:</strong> Quantas unidades você precisa</li>
                                    <li><strong>Posto/Graduação:</strong> Ex: Sgt, Cap, Maj</li>
                                    <li><strong>Nome de Guerra:</strong> Seu nome de guerra</li>
                                    <li><strong>Contato:</strong> Telefone ou ramal</li>
                                    <li><strong>Email:</strong> Para notificações</li>
                                    <li><strong>Esquadrão:</strong> Seu esquadrão</li>
                                    <li><strong>Setor:</strong> Seu setor</li>
                                    <li><strong>Justificativa:</strong> Breve explicação do uso (opcional, mas recomendado)</li>
                                </ul>
                            </li>
                            <li>Clique em <strong>"Confirmar Resgate"</strong>.</li>
                        </ol>
                        
                        <div class="alert alert-warning">
                            <i class="bi bi-clock"></i> <strong>Prazo:</strong> Você tem <strong><?= RESGATE_TIMEOUT_HOURS ?> horas</strong> para retirar o material no local indicado. Após esse prazo, o resgate expira e o material volta a ficar disponível.
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> <strong>Material em disputa:</strong> Se a quantidade solicitada por vários usuários exceder o disponível, o material entra em "Em Seleção". Seu pedido permanece na fila. Quem resolve a disputa é o cadastrador do item, em consonância com o Dono da Carga.
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Disputas -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingDisputas">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDisputas" id="disputas">
                        <i class="bi bi-exclamation-triangle me-2"></i> 4. O que são Disputas?
                    </button>
                </h2>
                <div id="collapseDisputas" class="accordion-collapse collapse" data-bs-parent="#tutorialAccordion">
                    <div class="accordion-body">
                        <p>Uma <strong>disputa</strong> ocorre quando a quantidade total solicitada por vários usuários excede a quantidade disponível do material.</p>
                        
                        <h6>Exemplo prático:</h6>
                        <ul>
                            <li>Material: 2 cadeiras disponíveis</li>
                            <li>Usuário A solicita 2 cadeiras</li>
                            <li>Usuário B solicita 1 cadeira (antes do A retirar)</li>
                            <li>Usuário C solicita 1 cadeira</li>
                            <li><strong>Resultado:</strong> Total solicitado = 4, disponível = 2 → <strong>Disputa!</strong></li>
                        </ul>
                        
                        <h6 class="mt-3">O que acontece na disputa?</h6>
                        <ol>
                            <li>O material recebe o status <strong>"Em Seleção"</strong> na página inicial.</li>
                            <li>É definido um prazo de <strong><?= DISPUTA_TIMEOUT_HOURS ?> horas</strong> para novos resgates.</li>
                            <li>Após o prazo, não é mais possível solicitar esse material.</li>
                            <li>Quem <strong>resolve a disputa</strong> é o <strong>cadastrador do item</strong> (quem incluiu o material no sistema).</li>
                            <li>O <strong>destino do material</strong> é sempre determinado pelo <strong>Dono da Carga</strong>.</li>
                        </ol>
                        
                        <div class="alert alert-secondary">
                            <i class="bi bi-person-check"></i> <strong>Importante:</strong> O Dono da Carga é quem define para quem o material vai. Se o Dono da Carga não for o cadastrador, ele deverá ser <strong>consultado e informado</strong> sobre o destino do material antes da decisão final.
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. Vencimento de Disputa (Cadastrador) -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingVencimento">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVencimento" id="vencimento-disputa">
                        <i class="bi bi-trophy me-2"></i> 5. Como Resolver uma Disputa (Cadastrador)
                    </button>
                </h2>
                <div id="collapseVencimento" class="accordion-collapse collapse" data-bs-parent="#tutorialAccordion">
                    <div class="accordion-body">
                        <p>Quem <strong>resolve a disputa</strong> é o <strong>cadastrador do item</strong> (quem incluiu o material no sistema). O destino do material é sempre determinado pelo <strong>Dono da Carga</strong>. Se o Dono da Carga não for o cadastrador, ele deve ser consultado e informado antes da decisão.</p>
                        
                        <h6>Passo a passo (para o cadastrador do item):</h6>
                        <ol>
                            <li><strong>Consulte o Dono da Carga</strong> (se não for você) para definir quem receberá o material.</li>
                            <li>Acesse <strong>Materiais</strong> no menu administrativo.</li>
                            <li>Materiais em disputa aparecem destacados em vermelho na tabela.</li>
                            <li>Clique no botão <strong><i class="bi bi-list-ul"></i></strong> (Ver Resgates) do material em disputa.</li>
                            <li>Um modal abrirá listando todos os solicitantes com:
                                <ul>
                                    <li>Ordem de solicitação</li>
                                    <li>Nome, posto, esquadrão, setor, contato</li>
                                    <li>Data/hora da solicitação</li>
                                    <li>Status (Em Disputa / Aguardando Retirada)</li>
                                    <li>Justificativa</li>
                                    <li>Quantidade solicitada e ajustada</li>
                                </ul>
                            </li>
                            <li><strong>Para definir o vencedor:</strong> Clique em <strong>"Marcar Retirado"</strong> na linha do solicitante que efetivamente retirou o material.</li>
                            <li>O sistema automaticamente:
                                <ul>
                                    <li>Marca o resgate como "Retirado"</li>
                                    <li>Cancela os demais resgates da disputa</li>
                                    <li>Atualiza a quantidade disponível do material</li>
                                </ul>
                            </li>
                        </ol>
                        
                        <h6 class="mt-3">Campo "Qtn. Ajustada":</h6>
                        <p>Se o solicitante retirou menos do que pediu (ex: pediu 2, retirou 1), ajuste o valor antes de marcar como retirado. Isso garante que a quantidade do material seja atualizada corretamente.</p>
                        
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> <strong>Exemplo:</strong> Material com 2 cadeiras, Dono da Carga: Sgt Silva. 3 pessoas solicitaram. Você (cadastrador) consulta o Sgt Silva, que indica o 1º solicitante. Marque "Retirado" nele. Os outros 2 são cancelados automaticamente.
                        </div>
                    </div>
                </div>
            </div>

            <!-- 6. Funções do Administrador -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAdmin">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdmin" id="administradores">
                        <i class="bi bi-person-gear me-2"></i> 6. Funções do Administrador
                    </button>
                </h2>
                <div id="collapseAdmin" class="accordion-collapse collapse" data-bs-parent="#tutorialAccordion">
                    <div class="accordion-body">
                        <h6>Administrador Geral (nivel: admin):</h6>
                        <ul>
                            <li><strong>Dashboard:</strong> Visão geral (total de materiais, disponíveis, resgates pendentes)</li>
                            <li><strong>Materiais:</strong> Cadastrar, editar, excluir e gerenciar todos os materiais</li>
                            <li><strong>Resgates:</strong> Ver todos os resgates, marcar retirados. Disputas são resolvidas pelo cadastrador do item</li>
                            <li><strong>Usuários:</strong> Gerenciar administradores e cadastradores</li>
                            <li><strong>Setores:</strong> Cadastrar e gerenciar setores</li>
                        </ul>
                        
                        <h6 class="mt-3">Administrador de Setor (nivel: setor):</h6>
                        <ul>
                            <li><strong>Dashboard:</strong> Visão dos materiais do seu setor</li>
                            <li><strong>Materiais:</strong> Cadastrar e gerenciar apenas materiais do seu setor</li>
                            <li><strong>Resgates:</strong> Ver e gerenciar resgates. Se você cadastrou o material, é quem resolve disputas (em consonância com o Dono da Carga)</li>
                            <li>Não tem acesso a Usuários e Setores</li>
                        </ul>
                        
                        <h6 class="mt-3">Fluxo resumido:</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th>Etapa</th>
                                        <th>Quem</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Cadastrador</td>
                                        <td>Cadastra material no painel</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Solicitante</td>
                                        <td>Resgata na página inicial</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Solicitante</td>
                                        <td>Retira no local em até <?= RESGATE_TIMEOUT_HOURS ?>h</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Cadastrador / Dono da Carga</td>
                                        <td>Cadastrador marca retirado no painel. Em disputa: Dono da Carga define destino; cadastrador executa (consultando o Dono se não for o mesmo)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Botão Voltar -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="index.php" class="btn btn-primary">
                    <i class="bi bi-house"></i> Voltar ao Início
                </a>
                <a href="index.php?route=admin" class="btn btn-outline-primary">
                    <i class="bi bi-gear"></i> Ir para Administração
                </a>
            </div>
        </div>
    </div>
</main>
