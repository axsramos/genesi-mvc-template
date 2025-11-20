# SBAdmin - Project Manager
### Modificar chave do produto

A Chave de criptografia da aplicação deve ser uma string aleatória de 36 caracteres. Isso garante que a cópia deste projeto em execução é única.  

Abaixo um exemplo do valor padrão:  

| Variável | Descrição |
|---|---|
|APP_KEY|UFJPSkVDVCBTQkFETUlOIExPQ0FM|  
|APP_TOKEN|51b39438-552e-11f0-afef-fc4596f8a36d|  

Modifique os valores no arquivo de configuração do ambiente em [`.env`](./.env)  

Sobrescreva os valores das chaves nos arquivos:  
- [Settings_LOCAL-Manager_V1.0.0.json](App\Static\Rules\Manager\Settings_LOCAL-Manager_V1.0.0.json).
  Localize o elemento "ProductKey" e aplique o novo valor atribuído em APP_KEY.  

- [02_ConfigureManager.sql](Doc\DBVersion\db_development\02_ConfigureManager.sql).
  Localize a variável "@CasAppCod" e aplique o novo valor atribuído em APP_KEY.  


- [02_ConfigureManager.sql](Doc\DBVersion\db_development\02_ConfigureManager.sql).
  Localize a variável "@CasAppKey" e aplique o novo valor atribuído em APP_TOKEN.  

### Modificar contas de usuário
Cada usuário no sistema é único e recebe um identificador composto por uma chave md5 de seu e-mail. Ao modificar o e-mail do usuário Admin o identificador do repositório deve ser modificado.  

#### Conta de usuário: Admin

Este procedimento deve ser realizado utilizando o formulário de cadastro acessando o sistema em [localhost:8080/Auth/Register](http://localhost:8080/Auth/Register).  

Siga as orientações abaixo:  

- Verifique as configurações do ambiente localizado no arquivo `.env` a chave STATIC_AUTHETICATION e sobrescreva seu conteúdo com os dados abaixo:  
```
STATIC_AUTHETICATION=true
```

- Verifique as configurações do ambiente localizado no arquivo [`.env`](./.env) a chave MAIL_SERVICE e sobrescreva seu conteúdo com os dados abaixo:  
```
MAIL_SERVICE=false
```

- Localize o arquivo [UserAccounts.json](App\Static\Auth\UserAccounts.json) e sobrescreva seu conteúdo com os dados abaixo:  
```
{
  "AdministratorProfile": [],
  "OtherProfiles": []
}
```
- Acesse o formulário para cadastrar-se em [localhost:8080/Auth/Register](http://localhost:8080/Auth/Register). Utilize os dados de sua preferência para cadastrar o usuário Admin do sistema.  

- Localize o arquivo [UserAccounts.json](App\Static\Auth\UserAccounts.json) e modifique seu conteúdo movendo os dados gerados em "OtherProfiles" para "AdministratorProfile" e modifique o valor de "PROFILE" de "USER" para "ADMINISTRATOR PORTAL obtendo um resultado semelhante ao abaixo:   
```
{
  "AdministratorProfile": [
    {
      "FirstName": "Admin",
      "LastName": "Account",
      "Account": "manager-admin@uorak.com",
      "Password": "a9aa976dbb0d38c94731431dc48a1aee",
      "Token": "808573",
      "Repository": "60dc1348618d255fe9b9d81ea4219e48",
      "USR_ID": "60dc1348618d255fe9b9d81ea4219e48",
      "USR_LOGGED": "Admin Account",
      "PROFILE": "ADMINISTRATOR PORTAL",
      "LANGUAGE": "pt",
      "SSW_ID": "60dc1348618d255fe9b9d81ea4219e48",
      "USR_AUTH": "60dc1348618d255fe9b9d81ea4219e48"
    }
  ],
  "OtherProfiles": []
}
```

#### Conta de usuário: Support
**IMPORTANTE**: Siga as orientações do passo anterior para configurar o usuário Admin.  

Após configurado o usuário Admin, siga as instruções abaixo para configurar o usuário Support.  

- Acesse o formulário para cadastrar-se em [localhost:8080/Auth/Register](http://localhost:8080/Auth/Register). Utilize os dados de sua preferência para cadastrar o usuário Support do sistema.  

- Localize o arquivo [UserAccounts.json](App\Static\Auth\UserAccounts.json) e modifique o valor de "PROFILE" de "USER" para "SUPPORT ACCOUNT obtendo um resultado semelhante ao abaixo:   
```
{
  "AdministratorProfile": [
    {
      "FirstName": "Admin",
      "LastName": "Account",
      "Account": "manager-admin@uorak.com",
      "Password": "a9aa976dbb0d38c94731431dc48a1aee",
      "Token": "808573",
      "Repository": "60dc1348618d255fe9b9d81ea4219e48",
      "USR_ID": "60dc1348618d255fe9b9d81ea4219e48",
      "USR_LOGGED": "Admin Account",
      "PROFILE": "ADMINISTRATOR PORTAL",
      "LANGUAGE": "pt",
      "SSW_ID": "60dc1348618d255fe9b9d81ea4219e48",
      "USR_AUTH": "60dc1348618d255fe9b9d81ea4219e48"
    }
  ],
  "OtherProfiles": [
    {
      "FirstName": "Support",
      "LastName": "Account",
      "Account": "manager-support@uorak.com",
      "Password": "7a8489429cff6d2bc3ac91ca8ac09ad0",
      "Token": "840552",
      "Repository": "f496066a1371151df6ba00068c197a3f",
      "USR_ID": "f496066a1371151df6ba00068c197a3f",
      "USR_LOGGED": "Support Account",
      "PROFILE": "SUPPORT ACCOUNT",
      "LANGUAGE": "pt",
      "SSW_ID": "f496066a1371151df6ba00068c197a3f",
      "USR_AUTH": "f496066a1371151df6ba00068c197a3f"
    }
  ]
}
```

- Localize o arquivo [SupportUser.json](App\Static\Rules\Manager\SupportUser.json) no elemento "ProductKey" e aplique o novo valor atribuído em USR_ID obterndo do arquivo [UserAccounts.json](App\Static\Auth\UserAccounts.json) .  

- Modifique os valores dos elementos "CasUsrNme, CasUsrSnm, CasUsrDsc, CasUsrDmn, CasUsrLgn e CasUsrPwd" que correspondam ao usuário Support. Os elementos equivalentes estão na tabela abaixo:  

| UserAccounts.json | SupportUser.json |
|---|---|
| FirstName | CasUsrNme |  
| LastName | CasUsrSnm |  
| Account | CasUsrDsc |  
| Account* | CasUsrDmn |  
| Account** | CasUsrLgn |  
| Password | CasUsrPwd |  

Verifique o formado abaixo:  
> Account*: @domain.com  

> Account**: username

### Modificar Scripts de inicialização
A configuração da base de dados deve ser realizada após a criação / configuração dos usuários: Admin e Support.

Certifique-se de criar a base de dados com o mesmo nome atribuido no arquivo de configuração do ambiente em [`.env`](./.env), localize no arquivo a chave: **DB_DATABASE** e **DB_SAAS_DATABASE**. Os valores atribuídos devem ser iguais e devem ser apliados nos scripts:
- 01_CreateDatabase.sql
- 02_ConfigureManager.sql

Onde aplicar:  
localize no primeiro [script](01_CreateDatabase.sql) o nome do banco de dados "sbadmin_local", modifique pelo nome correto de sua preferência.

```
create database sbadmin_local;

use sbadmin_local;
```

localize no segundo [script](02_ConfigureManager.sql) o nome do banco de dados "sbadmin_local", modifique pelo nome correto de sua preferência.

```
use sbadmin_local;
```

Neste mesmo script, verifique a chave do APP, igual a chave do produto.
A chave do produto: [Settings_LOCAL-Manager_V1.0.0.json](App\Static\Rules\Manager\Settings_LOCAL-Manager_V1.0.0.json)  . Localize o elemento "ProductKey".

**ProductKey deve ser igual a @CasAppCod**.  

```
SET @CasAppCod = 'UFJPSkVDVCBTQkFETUlOIExPQ0FM';
SET @CasAppKey = '28bfa817-55b6-11f0-8e0d-fc4596f8a36d';
```

Execute ambos os scripts seguindo a ordem nomeada dos scripts.

### Pacote de Instalação
Após aplicar as alterações anteriores desta documentação, será necesário configura o ambiente com a instalação do Manager para o usuário Admin e Support.

**Porque isso é necessário?**  
Como os usuários de administração do Portal possuem configurações direfenciadas dos demais usuários, alguns procedimentos devem ser seguidos:

- Modifique as configurações do ambiente localizado no arquivo `.env` a chave STATIC_AUTHETICATION e sobrescreva seu conteúdo com os dados abaixo:  
```
STATIC_AUTHETICATION=false
```

Faça login com a conta de administrador do portal **manager-admin@uorak.com**.
- Acesse a url: localhost:8080/Support/Package  
- Adicione a chave do pacote de instalação.  
  A chave do produto: [Settings_LOCAL-Manager_V1.0.0.json](App\Static\Rules\Manager\Settings_LOCAL-Manager_V1.0.0.json).  
  Localize o elemento "ProductKey".  
  Clique em "Instalar"

Faça login com a conta de support **manager-support@uorak.com**.
- Acesse a url: localhost:8080/Support/Package  
- Adicione a chave do pacote de instalação.  
  A chave do produto: [Settings_LOCAL-Manager_V1.0.0.json](App\Static\Rules\Manager\Settings_LOCAL-Manager_V1.0.0.json).  
  Localize o elemento "ProductKey".  
  Clique em "Instalar"

Com as configurações aplicadas, os usuários já estarão com os acessos aos programas do menu Portal e CONTROLE DE ACESSO.  
Esta configuração não é necessária para os demais usuários. No ato de cadastro as configurações serão aplicadas automaticamente.  

### Papel do usuário administrador do portal
O usuário default: manager-admin@uorak.com, possui um tipo de acesso especial: ADMINISTRATOR PORTAL.  
Não é um perfil de acesso, mas, um tipo de usuário.  
Com este tipo de usuário a conta poderá visualizar o menu PORTAL e os programas:
- Aplicativo;
- Repositório;
- Token;
- Tipo de Usuário;
- Usuário;

A visão dos usuários não está restrita somente ao seu repositório. Permitindo visualizar todos os usuários do sistema.

### Papel do usuário de suporte
O usuário default: manager-support@uorak.com, possui um tipo de acesso especial: SUPPORT ACCOUNT.  
Não é um perfil de acesso, mas, um tipo de usuário.  
Este usuário será responsável por realizar o suporte as contas de outros usuários. Mas para ter acesso a outros repositório é necessário fazer parte da mesma.

O menu de administração(do próprio repositório) para os demais usuáios devem ser liberados pelo usuário de suporte.  
