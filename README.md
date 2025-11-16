
# GENESI-MVC-TEMPLATE
> Template MVC Base em PHP  

<img src="https://img.shields.io/badge/license-MIT-green"><img/>
<img src="https://img.shields.io/badge/version-1.1.0-blue"><img/>
<img src="https://img.shields.io/badge/biuld-2511161859-orange"><img/>

Ver mais em [Changelog](./docs/v1.1.0/whatsnew.md)

## Sobre o Projeto
Este repositório serve como um Template Base (Boilerplate) para o desenvolvimento rápido de aplicações web com PHP Puro e o Padrão de Projeto MVC (Model-View-Controller).

Foi desenhado para ser um ponto de partida limpo e performático, ideal para projetos freelance ou internos, permitindo que a lógica de negócio seja o foco principal. O projeto adere a boas práticas de código e estrutura moderna.

### Características Principais
**Padrão MVC**: Separação clara das responsabilidades entre dados (Models), apresentação (Views) e fluxo (Controllers).

**Autoloading PSR-4**: Uso do Composer para carregamento automático de classes, garantindo um código organizado e moderno.

**Rotas por Nome de Classe (Convenção)**: O roteamento é realizado por convenção, mapeando diretamente a URL solicitada ao nome exato do arquivo Controller correspondente (Ex: url/Login busca por Login.php).

**Boas Práticas**: Nomenclatura de diretórios em CamelCase e Plural (Controllers, Models) para alinhamento com namespaces PHP.

### Arquitetura de Roteamento
**Roteamento por Convenção**: Este template utiliza uma abordagem de roteamento minimalista baseada em Convenção sobre Configuração, inspirada no projeto base.

Princípio: A URI (Uniform Resource Identifier) é utilizada para carregar dinamicamente o Controller com o nome correspondente.

**Limitação**: Esta simplicidade significa que não há suporte nativo para criação de aliases de rota (URL amigáveis diferentes do nome da classe) ou para parâmetros complexos de rota.

**Vantagem**: Facilita a compreensão imediata do fluxo e a criação rápida de novos endpoints sem necessidade de um arquivo de configuração de rotas dedicado.

**Tratamento de Erros**: Qualquer Controller não encontrado resulta em um redirecionamento para a página de erro 404 - Page Not Found.

### Inspiração e Créditos
A inspiração e a base conceitual para a arquitetura deste template vieram da leitura e estudo do artigo:

"Construindo um simples framework MVC com PHP" por Jardel Gonçalves

Link de Referência: https://medium.com/@jardelgoncalves/construindo-um-simples-framework-mvc-com-php-349e9cacbeb1

### Tecnologias Utilizadas
Linguagem: PHP (Versão 8.x recomendada)

Gerenciador de Dependências: Composer

Banco de Dados: MySQL 8.x (Configuração inicial)

Padrão: MVC

### Como Utilizar este Template
1. Pré-requisitos  
Certifique-se de ter instalado em seu ambiente Windows:  

- PHP 8.x ou superior
- Banco de dados MySQL 5.7.x ou superior
- Composer 2.x
- Servidor Web (Apache ou Nginx)

2. Clonagem e Setup  
Você pode criar um novo projeto a partir deste template:  

- Crie um novo repositório a partir deste template (botão "Use this template").
- Clone o novo projeto:

```Bash
git clone https://github.com/axsramos/genesi-mvc-template.git
```
Instale as dependências:

```Bash
composer install
```

### Contribuição e Licença
Este projeto é público para fins de exposição de conhecimento e portfólio.

Sinta-se à vontade para utilizar o código em seus próprios projetos.

Licenciado sob a Licença MIT.

### Desenvolvedor
Este projeto é mantido por:

Alex Ramos

Portfólio: https://cvfacil.com.br/resume/id/90f9d65ca835079288ac016b003ed1a8

GitHub: https://github.com/axsramos
