# Docker com Alpine + PHP 8.3 + SQLSrv

Este repositório contém a configuração de uma imagem Docker baseada no Alpine Linux com PHP 8.3 e suporte à extensão SQLSrv.

## Requisitos
- Docker instalado ([Instruções](https://docs.docker.com/get-docker/))
- Docker Compose instalado ([Instruções](https://docs.docker.com/compose/install/))

## Como utilizar

1. **Clone o repositório para dentro do seu projeto:**
   ```sh
   git clone https://github.com/seu-repositorio.git docker-alpine-php
   cd docker-alpine-php
   ```

2. **Configure o `docker-compose.yaml` conforme suas necessidades.**

3. **Inicie o container:**
   ```sh
   docker-compose up -d
   ```

4. **Executar comandos dentro do container:**
   ```sh
   docker exec -it container_name command
   ```
   Substitua `container_name` pelo nome do container e `command` pelo comando desejado, por exemplo:
   ```sh
   docker exec -it meu-container php -v
   ```

## Personalização
- Para modificar versões ou adicionar extensões ao PHP, edite o `Dockerfile`.
- Caso precise de outras configurações de banco de dados, ajuste o `docker-compose.yaml`.

