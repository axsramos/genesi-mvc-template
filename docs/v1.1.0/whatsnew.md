# Novidades na Versão 1.1.0

Esta versão introduz um sistema completo de **autenticação e gerenciamento de sessão**, transformando o template em uma base robusta para aplicações que requerem áreas restritas. Além disso, a arquitetura foi aprimorada para oferecer mais segurança e flexibilidade em ambientes de produção.

## Principais Recursos e Melhorias:

- **Sistema de Autenticação Completo**:
    - **Cadastro de Usuário**: Fluxo simplificado requerindo apenas nome, e-mail e senha.
    - **Login Seguro**: Implementação de controle de sessão com `session_regenerate_id` para prevenir ataques de fixação de sessão.
    - **Recuperação de Senha**: Mecanismo seguro baseado em token enviado por e-mail.
    - **Logout Seguro**: O processo de logout invalida o cookie e destrói a sessão no servidor, garantindo o encerramento completo.

- **Gerenciamento de Sessão Robusto (`AuthSession.php`)**:
    - As sessões são configuradas com as melhores práticas de segurança, incluindo os flags `HttpOnly` (impede acesso via JavaScript) e `SameSite=Lax` (proteção contra CSRF).

- **Roteamento Avançado**:
    - O sistema de roteamento agora suporta **grupos** e **versionamento** via URL (ex: `/v1/auth/Login`), permitindo uma organização mais clara de APIs e áreas da aplicação.

- **Flexibilidade para Produção (Diretório `public`)**:
    - A aplicação foi refatorada para funcionar com o `index.php` tanto na raiz quanto em um diretório `public`. Isso permite que apenas o diretório `public` seja exposto no servidor web, uma prática de segurança essencial que protege arquivos sensíveis.

- **Integração com E-mail (`PHPMailer`)**:
    - Adicionada a dependência `phpmailer/phpmailer` para o envio de e-mails transacionais, como boas-vindas e recuperação de senha.

- **Novo Tema de Painel (SB Admin)**:
    - A área administrativa agora utiliza o tema SB Admin, oferecendo uma interface limpa e responsiva. A estrutura de views foi organizada em `Partials` para reutilização de componentes como `Head`, `Footer` e menus.

- **Menus Dinâmicos com Controle de Acesso**:
    - A exibição dos menus de navegação é controlada pelo estado da sessão. Usuários autenticados têm acesso a menus restritos e específicos de suas funções, enquanto visitantes anônimos visualizam apenas as opções públicas.
