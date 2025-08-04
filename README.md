# Sistema de Resgate de Materiais - BANT

Sistema web completo em PHP 8 com MySQL para gerenciamento de doação e resgate de materiais da Base Aérea de Natal (BANT).

## 🚀 Características

- **PHP 8** com arquitetura MVC simples
- **MySQL** para persistência de dados
- **Bootstrap 5** para interface moderna e responsiva
- **DataTables** para tabelas administrativas
- **Upload de fotos** com suporte à câmera móvel
- **Sistema de timeout** automático para resgates
- **Notificações por email** para novos resgates
- **Interface responsiva** para desktop e mobile

## 📋 Requisitos

- PHP 8.0 ou superior
- MySQL 5.7 ou superior
- Servidor web (Apache/Nginx)
- Extensões PHP: PDO, PDO_MySQL, GD (para imagens)

## 🛠️ Instalação

### 1. Clone o repositório
```bash
git clone [url-do-repositorio]
cd olx_bant
```

### 2. Configure o banco de dados
- Crie um banco de dados MySQL chamado `olx_bant`
- Edite o arquivo `config/database.php` com suas credenciais:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'olx_bant');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');
```

### 3. Configure o servidor web
- Configure o DocumentRoot para a pasta do projeto
- Certifique-se de que o mod_rewrite está habilitado (Apache)

### 4. Permissões de pasta
```bash
chmod 755 uploads/
```

### 5. Acesse o sistema
- URL: `http://localhost/olx_bant`
- Credenciais padrão do administrador:
  - Usuário: `admin`
  - Senha: `admin123`

## 🏗️ Estrutura do Projeto

```
olx_bant/
├── config/
│   ├── config.php          # Configurações gerais
│   └── database.php        # Configuração do banco
├── controllers/
│   ├── AdminController.php  # Controle administrativo
│   ├── HomeController.php   # Página inicial
│   ├── MaterialController.php # Gerenciamento de materiais
│   └── ResgateController.php # Controle de resgates
├── views/
│   ├── layout/             # Layouts base
│   ├── admin/              # Views administrativas
│   ├── home/               # Views da página inicial
│   └── resgate/            # Views de resgate
├── uploads/                # Pasta para uploads de fotos
├── index.php               # Ponto de entrada
└── README.md               # Este arquivo
```

## 👥 Perfis de Usuário

### Administrador
- **Login obrigatório**
- Acesso ao painel completo
- Cadastro de materiais com até 3 fotos
- Upload via câmera móvel
- Visualização de todos os resgates
- Gerenciamento de cadastros e histórico

### Usuário (sem login)
- Acesso à página principal
- Visualização de materiais disponíveis
- Formulário de resgate com validação
- Prazo de 48h para retirada

## 📱 Funcionalidades

### Página Inicial
- Cards dos materiais em grade responsiva
- Informações completas: foto, descrição, local, BMP, dono, condição, tipo, quantidade
- Modal de resgate com formulário validado e controle de quantidade
- Design moderno em azul e branco

### Painel Administrativo
- Dashboard com estatísticas
- Listagem de materiais com DataTables e controle de quantidade
- Cadastro com upload de fotos e definição de quantidade
- Gerenciamento de resgates com quantidade resgatada
- Sistema de timeout automático

### Sistema de Resgate
- Formulário com validação
- Campos: Quantidade, Posto/Graduação, Nome de Guerra, Contato, Email, Esquadrão, Setor
- Controle de quantidade disponível
- Abatimento automático na quantidade
- Timeout de 48 horas
- Notificação por email
- Retorno automático após expiração

## 🎨 Design

- **Cores principais**: Tons de azul institucional e branco
- **Bootstrap 5** para layout responsivo
- **Bootstrap Icons** para ícones
- **DataTables** para tabelas administrativas
- **Interface limpa e formal**

## 🔧 Configurações

### Email
Edite `config/config.php` para configurar notificações:
```php
define('ADMIN_EMAIL', 'admin@bant.com');
define('SMTP_HOST', 'localhost');
define('SMTP_PORT', 587);
```

### Upload de Fotos
- Máximo 3 fotos por material
- Formatos: JPG, PNG, GIF
- Tamanho máximo: 5MB por foto
- Suporte à câmera móvel

### Timeout de Resgate
- Padrão: 48 horas
- Configurável em `config/config.php`
- Retorno automático após expiração

## 🚀 Uso

### Para Administradores
1. Acesse `http://localhost/olx_bant`
2. Clique em "Administração"
3. Faça login com as credenciais padrão
4. Use o painel para gerenciar materiais e resgates

### Para Usuários
1. Acesse `http://localhost/olx_bant`
2. Visualize os materiais disponíveis
3. Clique em "RESGATAR" no material desejado
4. Preencha o formulário
5. Aguarde confirmação

## 🔒 Segurança

- **Sanitização de dados** em todas as entradas
- **Prepared statements** para consultas SQL
- **Validação de arquivos** no upload
- **Controle de acesso** para área administrativa
- **Hash de senhas** com password_hash()

## 📞 Suporte

Para dúvidas ou problemas:
- Verifique os logs do servidor
- Confirme as configurações do banco de dados
- Teste as permissões da pasta uploads/

## 📄 Licença

Este projeto foi desenvolvido para a Base Aérea de Natal (BANT).

---

**Desenvolvido com ❤️ para a Base Aérea de Natal** 