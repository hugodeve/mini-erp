````markdown
# Mini-ERP em Laravel 11

Este repositório contém um sistema mínimo de ERP desenvolvido em **Laravel 11** para gerenciamento de **pedidos**, **produtos**, **cupons** e **estoque**. Apresenta funcionalidades essenciais de e‑commerce, incluindo carrinho de compras, cálculo de frete, aplicação de cupons, integração com ViaCEP, envio de e‑mail via Mailtrap e webhook para atualização de status de pedidos.

---

## Índice

1. [Requisitos](#requisitos)
2. [Instalação](#instalação)
3. [Configuração do Ambiente](#configuração-do-ambiente)
4. [Banco de Dados](#banco-de-dados)
5. [Configuração de E-mail (Mailtrap)](#configuração-de-e-mail-mailtrap)
6. [Execução da Aplicação](#execução-da-aplicação)
7. [Fluxo de Operações](#fluxo-de-operações)
8. [Webhook de Atualização de Pedido](#webhook-de-atualização-de-pedido)
9. [Boas Práticas](#boas-práticas)
10. [Licença](#licença)
11. [Créditos](#créditos)

---

## Requisitos

- **PHP** >= 8.1
- **Composer**
- **MySQL**
- **Node.js** e **npm** (opcional, para compilação de assets)
- Extensões PHP: `OpenSSL`, `PDO`, `Mbstring`, `Tokenizer`, `XML`, `CURL`
- Conexão com a Internet (para chamadas ao ViaCEP)

---

## Instalação

1. Clone o repositório:
   ```bash
   git clone https://github.com/seu-usuario/mini-erp.git
   cd mini-erp
````

2. Instale dependências PHP:

   ```bash
   composer install
   ```
3. (Opcional) Instale dependências JS e compile assets:

   ```bash
   npm install
   npm run build
   ```

---

## Configuração do Ambiente

1. Duplique o arquivo de ambiente:

   ```bash
   cp .env.example .env
   ```
2. Ajuste as variáveis no `.env`:

   ```dotenv
   APP_NAME="Mini-ERP"
   APP_ENV=local
   APP_KEY=           # será gerada no próximo passo
   APP_DEBUG=true
   APP_URL=http://localhost

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

## Banco de Dados

1. Crie o banco no MySQL:

   ```sql
   CREATE DATABASE mini_erp
     CHARACTER SET utf8mb4
     COLLATE utf8mb4_unicode_ci;
   ```
2. Execute as migrations:

   ```bash
   php artisan migrate
   ```

---

## Configuração de E-mail (Mailtrap)

1. Crie uma conta em [Mailtrap](https://mailtrap.io) e obtenha as credenciais SMTP.
2. Insira no `.env`:

   ```dotenv
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=SEU_USERNAME
   MAIL_PASSWORD=SEU_PASSWORD
   MAIL_ENCRYPTION=null
   MAIL_FROM_ADDRESS=erp@seudominio.com
   MAIL_FROM_NAME="Mini-ERP"
   ```

---

## Execução da Aplicação

Inicie o servidor local:

```bash
php artisan serve
```

Acesse em: `http://localhost:8000`

---

## Fluxo de Operações

1. **Produtos**: cadastre nome, preço, variações e estoque.
2. **Cupons**: defina códigos, tipos, valores, limites mínimos e validade.
3. **Carrinho**: adicione itens, aplique cupons e calcule frete.
4. **Checkout**: preencha CEP (ViaCEP), número, complemento e e‑mail.
5. **Finalização**: salva pedido, reduz estoque e envia e-mail de confirmação.
6. **Pedidos**: liste e visualize detalhes; cancele ou atualize status via webhook.

---

## Webhook de Atualização de Pedido

**Endpoint:** `POST /webhook/pedido-status`

**Payload:**

```json
{
  "pedido_id": 123,
  "status": "cancelado"
}
```

* **cancelado**: exclui o registro
* **outros**: atualiza o campo `status`

---

## Boas Práticas

* Verifique as migrations após alterações de esquema.
* Utilize logs (`storage/logs/laravel.log`) para depuração de falhas.
* Em produção, configure um serviço de e-mail real (Mailgun, SendGrid, etc.).

---

## Licença

Este projeto está licenciado sob a [MIT License](LICENSE).

---

## Créditos

Este projeto foi desenvolvido como teste para a empresa **Montink**.

**Autor:** Victor Ramirez – *Backend Developer*

```
```
