# Novidades na Versão 2.1.0

### **Visão Geral**

A versão 2.1.0 do **SBAdmin - Project Manager** traz novidades para o login da aplicação.

Sistema de login OAuth Social com a conta do Google.

### **Principais Funcionalidades**

* **Oauth Google:** Autenticação com google oauth.
  Para habilitar o recurso login com Google, gere uma chave de API em sua conta do Google.
  Obtenhas informações de como configurar sua chave de api em https://console.cloud.google.com/
  API e serviços e Tela de permissão Oauth.
  Habilite e configure as parametrizações no arquivo `.env`

  ```
  # Social Login with Google
  AUTH_SIGN_GOOGLE=true
  GOOGLE_CLIENT_ID=
  GOOGLE_CLIENT_SECRET=
  GOOGLE_REDIRECT_URI=
  ```  

## Próximos Passos:

Estamos sempre buscando melhorar e expandir as funcionalidades do GENESI-MVC-TEMPLATE. As próximas versões podem incluir:

- Wizard com instruções para o setup inicial.
- Sincronização de contas offline com a base de dados.
- Sistema de login OAuth Social (Google, Facebook, etc).
- Melhorias no tratamento de erros e logging.
- API Manager.

Agradecemos o seu interesse e feedback!
