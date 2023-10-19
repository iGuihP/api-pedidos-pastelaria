# API Pedidos de Pastelaria

Este readme contém informações sobre como configurar o ambiente para o projeto. Siga as etapas abaixo para preparar o ambiente e ajustar as variáveis de ambiente.

## Configuração do Ambiente

Para configurar o ambiente deste projeto, siga os passos abaixo:

1. Certifique-se de que o Docker e o Docker Compose estejam instalados em sua máquina. Caso não estejam, siga as instruções de instalação nos seguintes links:
   - [Docker](https://docs.docker.com/get-docker/)
   - [Docker Compose](https://docs.docker.com/compose/install/)

2. Valide se o arquivo .docker/entrypoint.sh esteja com a configuração "LF" e não "CRLF".

3. Após a instalação do Docker e do Docker Compose, abra um terminal de comando.

4. Navegue até o diretório raiz do projeto, onde está localizado o arquivo `docker-compose.yml`.

5. Execute o seguinte comando para iniciar os serviços do projeto em segundo plano:

   ```bash
   docker-compose up -d
Após isso, o projeto será iniciado na porta 5000. Podendo ser acessada através do URL: http://host.docker.internal:5000/

## Variáveis de Ambiente

Certifique-se de alterar todas as variáveis de ambiente no arquivo `.env`.

<b>Inclusive as variáveis de e-mail SMTP!
Sem elas, você não conseguirá criar um novo pedido.</b>

## Banco de Dados

Após todas as variáveis devidamente configuradas, utilize a migração do Laravel para o banco de dados utilizando o comando `php artisan migrate`.

Se quiser que seeds sejam criadas, utilize o comando `php artisan migrate --seed`