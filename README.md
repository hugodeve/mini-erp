````markdown
# Mini-ERP Laravel 11

Este documento explica como instalar e usar o mini-ERP em Laravel 11, incluindo a configuração do envio de e-mails via Mailtrap e passos para rodar o projeto em outra máquina.

---

## 📋 Requisitos

- PHP >= 8.1
- Composer
- MySQL
- Node.js e npm (somente se for compilar assets)
- Extensões PHP: `OpenSSL`, `PDO`, `Mbstring`, `Tokenizer`, `XML`, `CURL`
- Acesso à Internet para chamadas ao ViaCEP

---

## ⚙️ Instalação

1. Clone o repositório:

   ```bash
   git clone https://github.com/seu-usuario/mini-erp.git
   cd mini-erp
````

2. Instale as dependências PHP:

   ```bash
   composer install
   ```

3. (Opcional) Instale dependências JS e compile assets:

   ```bash
   npm install
   npm run build
   ```

---

## 🔧 Configuração do Ambiente

1. Copie o arquivo de ambiente e ajuste as variáveis:

   ```bash
   cp .env.example .env
   ```

2. No `.env`, configure a conexão com MySQL:

   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=mini_erp
   DB_USERNAME=seu_usuario
   DB_PASSWORD=sua_senha
   ```

3. Gere a chave de aplicação:

   ```bash
   php artisan key:generate
   ```

---

## 🗄️ Banco de Dados

1. Crie o banco no MySQL:

   ```sql
   CREATE DATABASE mini_erp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. Execute as migrations:

   ```bash
   php artisan migrate
   ```

---

## 📧 Configuração de E-mail (Mailtrap)

Para testar o envio de e-mails localmente, usaremos o Mailtrap.

1. Crie uma conta em [mailtrap.io](https://mailtrap.io) e gere um projeto.

2. Copie as credenciais SMTP fornecidas pelo Mailtrap e ajuste no `.env`:

   ```dotenv
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=SEU_USERNAME_MAILTRAP
   MAIL_PASSWORD=SEU_PASSWORD_MAILTRAP
   MAIL_ENCRYPTION=null
   MAIL_FROM_ADDRESS=erp@seudominio.com
   MAIL_FROM_NAME="Mini-ERP"
   ```

3. Opcional: você pode visualizar as mensagens enviadas no dashboard do Mailtrap.

---

## 🚀 Executando o Projeto

1. Inicie o servidor local do Laravel:

   ```bash
   php artisan serve
   ```

2. Acesse no navegador: `http://localhost:8000`

---

## 🛒 Fluxo de Uso Básico

1. **Produtos**: cadastre produtos e estoques.
2. **Cupons**: crie cupons com validade e regras de desconto.
3. **Carrinho**: adicione produtos, aplique cupons e calcule frete.
4. **Checkout**: informe CEP (busca dados no ViaCEP), número, complemento e e-mail do cliente.
5. **Finalizar Pedido**: gera o pedido, reduz estoque e dispara e-mail de confirmação.
6. **Pedidos**: liste e visualize detalhes; cancele ou atualize status via webhook.

---

## 🔗 Webhook de Atualização de Pedido

* **Rota**: `POST /webhook/pedido-status`

* **Payload JSON**:

  ```json
  {
    "pedido_id": 123,
    "status": "cancelado"
  }
  ```

* Se `status` for `cancelado`, o pedido será removido; senão, apenas atualiza o campo `status`.

---

## 💡 Dicas e Boas Práticas

* Verifique sempre se as migrations rodaram corretamente.
* Use logs (`storage/logs/laravel.log`) para debugar falhas de API ou envio de e-mail.
* Para produção, configure um serviço SMTP real (SendGrid, Mailgun, etc.) e variáveis de ambiente adequadas.

---

## 📝 Licença

Este projeto está licenciado sob a MIT License. Consulte o arquivo `LICENSE` para mais detalhes.

---

**Feliz desenvolvimento!**

**Este projeto foi desenvolvido como teste para a empresa Montink.**

**Criado por Victor Ramirez – Backend Developer**

```
```
