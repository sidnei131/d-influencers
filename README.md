
# D-Influencers API

D-Influencers é uma API para gerenciamento de campanhas e influenciadores. Este projeto foi desenvolvido utilizando Laravel e segue o padrão RESTful. Além disso, utiliza autenticação via JWT para proteger os endpoints.

A escolha do laravel foi por conta do seu ecossistema robusto, familiaridade e pelo desenvolvimento rápido e elegante que ele proporciona.


#### Documentação das APIs

Auth API: https://documenter.getpostman.com/view/3019903/2sAYHzGNh5

Campaigns API: https://documenter.getpostman.com/view/3019903/2sAYHzGNmW

Influencers: https://documenter.getpostman.com/view/3019903/2sAYHzGP5E

---

## 📋 Requisitos

- Docker
- Docker Compose
- Postman (opcional, para testar os endpoints)

---

## 🚀 Configuração e Execução

### 1. Clone o Repositório

```bash
git clone https://github.com/sidnei131/d-influencers.git
cd d-influencers
```

### 2. Executando o projeto com Docker

```bash
# suba os containers
docker compose up --build

# execute as migrations
docker exec -it d-influencers-api php artisan migrate --seed

# execute os testes automatizados
docker exec -it d-influencers-api php artisan test
```

🛠️ Tecnologias Utilizadas
Backend: Laravel 11
Frontend: Vue.js 3 (Pinia, Bootstrap, Axios e Cypress)
Servidor HTTP: Nginx com proxy reverso
Banco de Dados: MySQL 8.1
Autenticação: JWT
Ambiente de Desenvolvimento: Docker, Docker Compose

