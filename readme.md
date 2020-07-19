<div align="center">
    <h1 align="center">Symfony Example</h1>
</div>

## Author

**Nome:** `Otávio Marques`

**E-mail:** `otaviomarques@gmail.com`

**Usuário:** @omrqs

## Começando

Clone este repositório, crie uma nova _branch_, como por exemplo `sf-challenge`.

Na sua máquina, você só precisa ter o [Docker](https://www.docker.com/get-started) e o [Docker Compose](https://docs.docker.com/compose/) instalados.

Para rodar a primeira vez o repositório, basta usar o comando make na raiz do projeto. Ele ira configurar a aplicação e já deixará em execução.
Garanta que a porta `80` de sua máquina não esteja sendo utilizada e rode o comando abaixo:

```bash
make
```

Para as demais ocasiões, você pode subir o projeto utilizando o `docker-compose`.

```bash
docker-compose up
```

Para popular o banco de dados com alguns registros, use o comando a seguir:

```bash
docker-compose exec web composer load-fixtures-db
```

A partir daqui, está tudo configurado :rocket:

Assim, será possível acessar [http://localhost/doc](http://localhost/doc) e ver a documentação da API.

## Testando

Para debugar a aplicação, utilize o comando para habilitar o server:dump na porta 9912:

```bash
docker-compose exec web composer server-dump
```
---

Para rodar os testes da aplicação, utilize o [phpunit](https://phpunit.de/), que já vem instalado:

```bash
docker-compose exec web vendor/bin/phpunit
```
---

Caso deseje rodar todas as checagens de qualidade de código, rode o comando abaixo:

```bash
docker-compose exec web vendor/bin/grumphp run
```

Para ativar essas checagens automaticamente a cada commit, utilize o `git:init` do _grumphp_:

```bash
docker-compose exec web vendor/bin/grumphp git:init
```

Para checar em detalhes a cobertura de código da aplicação, após rodar o _grumphp_,
abra o arquivo `build/coverage/index.html` em seu navegador.
