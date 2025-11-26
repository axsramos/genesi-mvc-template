# Feature
Aqui estão listadas as funcionalidades desejadas para este projeto.

## Funcionalidades Desejadas
- **Autenticação Avançada (Em Breve):** Estamos implementando mecanismos de autenticação mais robustos, como a Autenticação de Dois Fatores (2FA).
- Wizard com instruções para o setup inicial.
- Sincronização de contas offline com a base de dados.
- Melhorias no tratamento de erros e logging.
- API Manager.


## Versão 1.0.0
1. **Estrutura MVC Completa**: Implementação clara e funcional do padrão Model-View-Controller para uma separação de responsabilidades eficiente.

2. **Autoloading PSR-4 com Composer**: Gerenciamento de dependências e carregamento automático de classes para um código organizado e moderno.

3. **Roteamento por Convenção**: Sistema de roteamento simples e intuitivo que mapeia URLs diretamente para Controllers, agilizando o desenvolvimento.

4. **Tratamento de Erros 404**: Redirecionamento automático para uma página de erro 404 quando um Controller não é encontrado.

5. **Configuração Inicial para MySQL**: Base pronta para integração com bancos de dados MySQL.

6. **Framework para Frontend**: Tema SBSimple como página inicial.

## Versão 1.1.0
7. **Sistema de Autenticação Completo**:
    - **Cadastro de Usuário**: Fluxo simplificado requerindo apenas nome, e-mail e senha.
    - **Login Seguro**: Implementação de controle de sessão com `session_regenerate_id` para prevenir ataques de fixação de sessão.
    - **Recuperação de Senha**: Mecanismo seguro baseado em token enviado por e-mail.
    - **Logout Seguro**: O processo de logout invalida o cookie e destrói a sessão no servidor, garantindo o encerramento completo.

8. **Gerenciamento de Sessão Robusto (`AuthSession.php`)**:
    - As sessões são configuradas com as melhores práticas de segurança, incluindo os flags `HttpOnly` (impede acesso via JavaScript) e `SameSite=Lax` (proteção contra CSRF).

9. **Roteamento Avançado**:
    - O sistema de roteamento agora suporta **grupos** e **versionamento** via URL (ex: `/v1/auth/Login`), permitindo uma organização mais clara de APIs e áreas da aplicação.

10. **Flexibilidade para Produção (Diretório `public`)**:
    - A aplicação foi refatorada para funcionar com o `index.php` tanto na raiz quanto em um diretório `public`. Isso permite que apenas o diretório `public` seja exposto no servidor web, uma prática de segurança essencial que protege arquivos sensíveis.

11. **Integração com E-mail (`PHPMailer`)**:
    - Adicionada a dependência `phpmailer/phpmailer` para o envio de e-mails transacionais, como boas-vindas e recuperação de senha.

12. **Novo Tema de Painel (SB Admin)**:
    - A área administrativa agora utiliza o tema SB Admin, oferecendo uma interface limpa e responsiva. A estrutura de views foi organizada em `Partials` para reutilização de componentes como `Head`, `Footer` e menus.

13. **Menus Dinâmicos com Controle de Acesso**:
    - A exibição dos menus de navegação é controlada pelo estado da sessão. Usuários autenticados têm acesso a menus restritos e específicos de suas funções, enquanto visitantes anônimos visualizam apenas as opções públicas.


## Versão 2.0.0
14. **Gerenciamento Multi-Tenant:** Administre diversos inquilinos de forma centralizada, com isolamento de dados garantido para cada cliente.

15. **Gestão do Ciclo de Vida do Usuário:** Crie, edite, suspenda e desative contas de usuário de forma simples e intuitiva.

16. **Controle de Acesso:** Atribua papéis e permissões granulares para controlar exatamente quem tem acesso a quais recursos.

17. **Auditoria e Monitoramento:** Registre e acompanhe todas as atividades dos usuários para auditorias e detecção de comportamentos suspeitos.

18. **Modo de Manutenção:** O sistema pode operar em um estado offline limitado, permitindo acesso a conteúdo estático (como downloads) durante manutenções planejadas do banco de dados.

## Versão 2.1.0 (Biuld)
19. **Login OAuth Social (Google, Facebook, etc)**: Autenticação com conta da Google.
