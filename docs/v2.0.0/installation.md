# SBAdmin - Project Manager

### Informções Importantes
Este produto está em desenvolvimento (versão 2.0.0). É necessária a configuração inicial e revisão de arquivos manualmente.

Certifique-se de compreender a documentação e seguir as instruções.

Novos pacotes para automatização do setup serão implementados em breve.

### Instalação Inicial

Para configuração em produção, requer alterar a chave do produto, veja mais em [MODIFY-PRODUCT-KEY.](./modify-product-key.md)  

### Configuração do Ambiente
- Crie uma cópia do arquivo `.env.example` nomeando para `.env`  

Modifique as informações do arquivo [`.env`](./.env) seguindo as instruções:  


| Variável | Descrição |
|---|---|
| `APP_NAME` | Modifique o nome do aplicativo nesta variável. Utilize um nome curto com até 20 caracteres. |
| `APP_ENV` | Ambiente da aplicação (local, development, staging, production). |
| `APP_KEY` | Chave de criptografia da aplicação. Deve ser uma string aleatória de 36 caracteres. |
| `APP_TOKEN` | Deve ser uma string aleatória de 36 caracteres. |  
| `APP_URL` | modifique para a URL da aplicação. |  
| `STATIC_AUTHETICATION` | (true ou false). Quando utilizar em modo de manutenção e ou operação offline, defina o valor "true" nesta variável, isto fará a aplicação funcionar sem o uso do banco de dados. O valor padrão é "false", permitindo o uso do banco de dados. |  
| `MAIL_PASSWORD` | atribua a senha da conta de e-mail de serviço. Utilize um e-mail de autenticação de aplicativo. |  
| `MAIL_SERVICE` | (true ou false). Habilita ou desabilita o envio de e-mail. |  

**IMPORTANTE**: Ao modificar os valores de (APP_KEY, APP_TOKEN) ou contas de e-mail do adminstrador ou do suporte, devem ser modificados no script de configuração da base de dados "02_ConfigureManager.sql", "UserAccounts" e "SupportUser.json".  

### Arquivo de configuração: UserAccounts.json
O arquivo está localizado em: App\Static\Auth\UserAccounts.json  

São armazenados as credenciais de acesso dos usuários do sistema quando em modo de operação offiline.  

Existem inicialmente três tipos de perfil de acesso sendo: (ADMINISTRATOR PORTAL | SUPPORT ACCOUNT | USER). As contas ADMINISTRATOR PORTAL e SUPPORT ACCOUNT, são exclusivas para administração do sistema. As demais contas são criadas com o perfil: USER.  

Por padrão: 
```
Conta de adminstrador:  
user: manager-admin@uorak.com
pass: manager@123
```

```
Conta de suporte:  
user: manager-support@uorak.com
pass: support@123
```
**IMPORTANTE**: O domínio "@uorak.com" é um site para gerar e receber e-mail para testes, sendo este um serviço de terceiro. Podendo ser acessado no endereço: [invertexto.com](https://invertexto.com/gerador-email-temporario).  É recomendado modificar os e-mails para uma conta de sua propriedade.  


### Arquivo de configuração: Settings_LOCAL-Manager_V1.0.0.json
O arquivo está localizado em:  App\Static\Rules\Manager\Settings_LOCAL-Manager_V1.0.0.json  

São armazenadas as configurações do sistema para carregar dados iniciais. Este arquivo em específico, indica ser da versão 1.0.0 do sistema. É importante verificar no conteúdo deste arquivo as informações como: ProductKey e Version. No caso de alteração dos valores contidos no arquivo ".env" localizado no dieretório raiz da aplicação, a chave do produto "ProductKey" deve ser alterada, portanto, ambas devem ser iguais.  

### Arquivo de configuração: SupportUser.json
O arquivo está localizado em:  
App\Static\Rules\Manager\SupportUser.json  

São armazenadas as contas de suporte do sistema que deverão ser configuradas automaticamente a cada novo repositório criado no sistema, desta forma, os usuários contidos nesta lista serão inseridos na criação de novos repositórios.  

### Diretórios

**Repositories**: Cada usuário no sistema é único e recebe um identificador composto por uma chave md5 de seu e-mail. Um diretório é criado no momento do cadastro de um novo usuário. Todos os dados que estão diretamente relacionados ao usuário é armazenado em seu respectivo diretório.  

**Temp**: Contém arquivos temporários para:  
- Logs: DB, Mail, Package, Outros;  
- Cache: Para otimizar acesso do usuário ou outros processamentos;  

**SBAdmin**: Contém classes, imagens e scripts para carregamento das páginas.  
