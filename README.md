# 📍 API para Gerenciamento de Lugares

API desenvolvida com Laravel 12, utilizando Laravel Sail (Docker). A API é responsável pelo gerenciamento de "places" (lugares), permitindo operações de CRUD completas.

---

## ⚙️ Como instalar

> É necessário possuir Docker e Docker Compose instalados em seu ambiente.

 ##### 1. Clone o repositório:
   ```bash
    git clone https://github.com/juanfranca/PlaceApi.git 
    cd PlaceApi
   ```
 ##### 2. Copie o arquivo de variáveis de ambiente:
   ```bash
    cp .env.example .env
   ```
##### 3. Suba os containers do Docker com o sail:
   ```bash
    ./vendor/bin/sail up -d
   ```
##### 4. Realize a instalação do projeto com o composer:
   ```bash
    ./vendor/bin/sail composer install
   ```
##### 5. Gere a chave da aplicação:
   ```bash
    ./vendor/bin/sail artisan key:generate
   ```
##### 6. Rode as migrações:
   ```bash 
    ./vendor/bin/sail artisan migrate
   ```

   Caso deseje rodar as migrações com o seeder adicione --seed:
   ```bash 
    ./vendor/bin/sail artisan migrate --seed
   ```
##### 7. Para realizar os testes: 
   ```bash 
    ./vendor/bin/sail artisan test
   ```

## 📡 Endpoints

| Método   | Rota                       | Descrição   |
| -------- | -----                      | ----------- |
| GET      | /places                    | Lista todos os lugares       |
| GET      | /places?name=NomeExemplo   | Filtra os lugares por nome             |
| GET      | /places/{place}            |   Resgata um lugar específico          |
| POST     | /places                    |     Cria um lugar        |
| PUT/PATCH| /places/{place}            |      Atualiza um lugar       |


## 📘 Modelo de Paylod para criação de um Lugar:
 ```json
    {
        "name": "Praia de Antunes",
        "city": "Maragogi",
        "state": "Alagoas"
    }
```

🛠️ Tecnologias

   *  Laravel 12

   *  Laravel Sail (Docker)

   * PHP 8.2

   * PHPUnit (Testes)