<h1 align="center">
    Backend Dictionary API
</h1>

>  This is a challenge by [Coodesh](https://coodesh.com/)

## Índice

- [Sobre o projeto](#boat-sobre-o-projeto)
- [Tecnologias](#hammer-tecnologias)
- [Como contribuir para o projeto](#raising_hand-como-contribuir-para-o-projeto)
- [Como rodar esse projeto](#rocket-como-rodar-esse-projeto)
- [Processo de Desenvolvimento](#processo-de-desenvolvimento)
- [Estrutura da API](#estrutura-da-api)
- [Escolha da Tecnologia](#escolha-da-tecnologia)
- [Modelagem de Dados](#modelagem-de-dados)
- [Implementação da API](#implementação-da-api)
- [Implementação de Cache](#implementação-de-cache)
- [Documentação da API](#documentação-da-api)
- [Testes Automatizados](#testes-automatizados)
- <a href="#pencil-licença">Licença</a>
 
## :boat: Sobre o projeto

Este projeto foi desenvolvido como parte de um desafio de Back-end, com o objetivo de listar palavras em inglês e gerenciar as palavras visualizadas utilizando a Free Dictionary API. A aplicação foi construída seguindo as melhores práticas de desenvolvimento backend, com foco em organização, performance e segurança.

A API proxy será responsável por intermediar o acesso entre o front-end e a Free Dictionary API, além de gerenciar o histórico de palavras visualizadas e permitir a marcação de palavras como favoritas.

O projeto visa demonstrar as habilidades de criação de uma API RESTful robusta, com gerenciamento de usuários, integração com uma API externa, e tratamento de dados com banco de dados.

## :hammer: Tecnologias:
- **[PHP 8.3](https://www.php.net)**
- **[Laravel](https://laravel.com/)**
- **[Sail](https://github.com/laravel/sail)**
- **[MySQL](https://www.mysql.com/)**
- **[Redis](https://redis.io/)**
- **[Horizon](https://github.com/laravel/horizon)**
- **[L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger)**
- **[PHPUnit](https://phpunit.de)**
- **[jwt-auth](https://github.com/PHP-Open-Source-Saver/jwt-auth)** 

## :rocket: Como rodar esse projeto

Se você estiver usando Windows, vai precisar do WSL para rodar esse projeto de forma prática. Para isso, você pode instalá-lo seguindo o seguinte [tutorial](https://learn.microsoft.com/pt-br/windows/wsl/install). Também será necessário uma distribuição linux para utilizar o WSL. Recomendo o Ubuntu que pode ser baixando na própria Microsoft Store no [link](https://apps.microsoft.com/store/detail/ubuntu/9PDXGNCFSCZV).
Depois, vai precisar do Docker, o qual a versão de Windows pode ser encontrada [aqui](https://docs.docker.com/desktop/install/windows-install/).
Então, clone o projeto dentro do WSL, vá para pasta dele e execute o comando:

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```
Este comando usa um pequeno contêiner Docker contendo PHP e Composer para instalar as dependências do aplicativo.

Para iniciar todos os contêineres Docker definidos no docker-compose.ymlarquivo do seu aplicativo:

```
./vendor/bin/sail up
```
Para iniciar todos os contêineres do Docker em segundo plano, você pode iniciar o Sail no modo "desanexado":
```
./vendor/bin/sail up -d
```
Agora precisamos configurar as variáveis ambientes. Crie o arquivo .env:

```
cp .env.example .env
```

Agora precisamos configurar as variáveis ambientes para realização de testes. Crie o arquivo .env.testing:

```
cp .env.example .env.testing
```

Altere o valor de "APP_ENV" para "testing" no arquivo ".env.testing".

Crie as chaves de segurança da aplicação:

    `./vendor/bin/sail artisan key:generate`

Execute as migrações:

    `./vendor/bin/sail artisan migrate`

Agora será necessário carregar as palavras do dicionário no sistema:

```
./vendor/bin/sail artisan load:words
```

Depois que os contêineres do aplicativo forem iniciados, você poderá acessar o projeto no seu cliente REST em: http://localhost .

Para parar todos os contêineres, você pode simplesmente pressionar Control + C para parar a execução do contêiner. Ou, se os contêineres estiverem rodando em segundo plano, você pode usar o stopcomando:
```
./vendor/bin/sail stop
```

Para iniciar os processos de worker configurados para o ambiente:
```
php artisan horizon
```
Depois que os processos de worker forem iniciados, você poderá acessar o dashboard do Horizon em: http://localhost/horizon/dashboard .

## Processo de Desenvolvimento

### 1. Definição dos Requisitos

Iniciei o projeto analisando os requisitos funcionais e não funcionais apresentados no desafio. 

#### Requisitos Funcionais

1. Autenticação de usuário:
    O usuário deve ser capaz de se cadastrar e fazer login com email e senha.
2. Listagem de palavras:
    - O usuário deve ser capaz de visualizar uma lista de palavras do dicionário em inglês.
    - A listagem deve oferecer suporte à busca de palavras e paginação.
3. Histórico de palavras visualizadas:
    - O sistema deve armazenar o histórico de palavras que o usuário já visualizou.
    - O usuário deve ser capaz de visualizar esse histórico.
4. Favoritar palavras:
    - O usuário deve ser capaz de marcar palavras como favoritas.
    - O usuário deve ser capaz de remover palavras de sua lista de favoritas.
    - O usuário deve ser capaz de visualizar suas palavras favoritas.
5. Proxy da API externa:
    - A API interna deve agir como um proxy para a API externa de palavras, de modo que o front-end interaja apenas com a API interna.
6. Carregamento de dados externos:
    - O sistema deve fornecer um script para baixar e importar uma lista de palavras do repositório externo para o banco de dados local.
8. Cache de requisições:
    - O sistema deve armazenar em cache os resultados das requisições à API para melhorar a performance em buscas repetidas.
#### Requisitos Não Funcionais
1. Desempenho:
    - O tempo de resposta das requisições deve ser otimizado com o uso de cache.
Cabeçalhos devem ser utilizados para informar se o cache foi utilizado e o tempo de resposta das requisições.
2. Escalabilidade:
    - O sistema deve ser capaz de suportar múltiplos usuários acessando simultaneamente.
Persistência de dados:

3. Utilização de um banco de dados relacional ou não-relacional para armazenamento de usuários, histórico, palavras favoritas e outras informações relevantes.
Manutenibilidade:

4. O código deve seguir padrões de clean code para facilitar a manutenção.
Validação de chamadas assíncronas:

5. Deve haver tratamento adequado para evitar travamentos devido a chamadas assíncronas mal gerenciadas.
Segurança:

6. Autenticação deve ser feita via JWT (JSON Web Tokens) para garantir a segurança no acesso aos dados.
Documentação:

7. Testes:
    - Implementação de testes unitários para garantir a funcionalidade dos endpoints e a integridade do sistema.
8. Deploy:
    - Configurar o ambiente de produção, preferencialmente utilizando Docker para facilitar o processo de deployment.
9. Suporte a Open API 3.0:
    - Implementação de documentação da API utilizando o padrão Open API 3.0.

### 2. Estrutura da API

Com base nos casos de uso, elaborei um esboço das rotas que seriam necessárias para atender aos requisitos.
<br>
<h4 align="center">
    <img alt="Representação visual de arquitetura hexagonal" title="diagrama-de-casos-de-uso" src=".github/readme/diagrama-de-casos-de-uso.png" width="1864px" />
</h4>
<br>

As rotas principais incluem:

Aqui está a formatação das rotas seguindo o modelo que você forneceu:

- **Raiz**: `/`
- **Autenticação**: 
  - `/api/auth/logout`
  - `/api/auth/refresh`
  - `/api/auth/signin`
  - `/api/auth/signup`
- **Documentação da API**: `/api/documentation`
- **Gerenciamento de Palavras**: 
  - `/api/entries/en`
  - `/api/entries/en/{word}`
  - `/api/entries/en/{word}/favorite`
  - `/api/entries/en/{word}/unfavorite`
- **OAuth2**: `/api/oauth2-callback`
- **Usuário**: 
  - `/api/user/me`
  - `/api/user/me/favorites`
  - `/api/user/me/history`


### 3. Escolha da Tecnologia

Optei por usar o Laravel por sua robustez e facilidade de uso na criação de APIs RESTful. O Laravel também possui suporte embutido para autenticação e gerenciamento de banco de dados, o que facilita o desenvolvimento. Para cache foi utilizado o Redis e para monitorar a fila foi utilizado o Laravel Horizon.

### 4. Modelagem de Dados

Baseado na documentação da API, modele os dados das palavras, incluindo campos como `word`, `definition`, `synonyms`, `etymology`, etc. 
<br>
<h4 align="center">
    <img alt="Representação visual de arquitetura hexagonal" title="deiagrama-de-entidade-word" src=".github/readme/estrutura-de-retorno-de-uma-palavra-na-free-dictionary-api.png" width="1864px" />
</h4>
<br>
Criei modelos Eloquent para facilitar a interação com o banco de dados.
<br>
<h4 align="center">
    <img alt="Representação visual de arquitetura hexagonal" title="deiagrama-de-entidade-word" src=".github/readme/models.png" width="1864px" />
</h4>
<br>

### 5. Implementação da API

- **Autenticação**: Implementei a autenticação utilizando JWT, permitindo que os usuários façam login e recebam um token para acessar as rotas protegidas.

```
{
	"id": "9d47b4c6-d5c9-4839-a949-f1c1318ba90a",
	"name": "User 1",
	"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0L2FwaS9hdXRoL3NpZ25pbiIsImlhdCI6MTcyOTMxNDA5NCwiZXhwIjoxNzI5MzE3Njk0LCJuYmYiOjE3MjkzMTQwOTQsImp0aSI6Im9DUUEwT3NOUlBoYkJWS0YiLCJzdWIiOiI5ZDQ3YjRjNi1kNWM5LTQ4MzktYTk0OS1mMWMxMzE4YmE5MGEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.FPFoC9Ea_aUD72H_Uw2ZzXUBG2CX-Dle2SuwZwBHkjg"
}
```

- **Gerenciamento de Palavras**: Criei endpoints para listar palavras, visualizar detalhes e registrar o histórico de visualizações.
    - `/api/entries/en` 
    <img alt="Representação visual de arquitetura hexagonal" title="deiagrama-de-entidade-word" src=".github/readme/listar-palavras.png" width="1864px" />
    - `/api/entries/en/{word}`
    <img alt="Representação visual de arquitetura hexagonal" title="deiagrama-de-entidade-word" src=".github/readme/word.png" width="1864px" />
    - Histrioco de Visualizações:
    ```
        function show(string $word)
    {
        ...
        $user = $data->viewer()->where('user_id', '=', auth()->user()->id)->first();
        if (!$user) {
            $data->viewer()->attach(auth()->user()->id);
        }
        return ...
    }
    ```
    <img alt="Representação visual de arquitetura hexagonal" title="Histórico de visualizações" src=".github/readme/historico-views.png" width="1024px" />
- **Favoritos**: Desenvolvi a funcionalidade para marcar e desmarcar palavras como favoritas.
    - Funcionalidade para marcar palavras como favoritas:
    <img alt="Representação visual de arquitetura hexagonal" title="Palavra sendo marcada como favorita." src=".github/readme/favorite.png" width="1024px" />
    - Listar palavras favoritas:
    <img alt="Representação visual de arquitetura hexagonal" title="Listando favoritos" src=".github/readme/list-favorites.png" width="1024px" />
    - Funcionalidade para desmarcar palavras como favoritas:
    <img alt="Representação visual de arquitetura hexagonal" title="Removendo palavra da lista de favoritos." src=".github/readme/unfavorite.png" width="1024px" />
    <img alt="Representação visual de arquitetura hexagonal" title="Lista de palavras favoritas atualizada." src=".github/readme/void-list-favorites.png" width="1024px" />



### 6. Implementação de Cache

Para melhorar a performance, implementei um sistema de cache usando Redis para armazenar os resultados das requisições à API de dicionário. Isso ajuda a reduzir o tempo de resposta para buscas frequentes.

<br>
<h4 align="center">
    <img alt="Representação visual de arquitetura hexagonal" title="Deiagrama de sequência de palavras sendo enviadas para a cache." src=".github/readme/word-cache.png" width="1864px" />
</h4>
<br>

*Observação*: Devido a abordagem utilizada ao importar a lista de palavras no sistema, que é ralizada a consulta das palavras na api Free Dicionary API durante a execução do script. o requisito `Proxy da API externa` não foi atendido, por já ter os dados de detalhes das palavras armazenadas no banco de dados da aplicação.

### 7. Documentação da API

Utilizei o Open API 3.0 para documentar todos os endpoints da API, facilitando a compreensão e a integração para outros desenvolvedores. para implementar este recurso utilizei o pacote L5-Swagger. Para visualizar a documentação da api acesse a rota:

*Local*: `http://localhost/api/documentation`;

ou 

*Produção*: `https://<seu dominio>/api/documentation`

<br>
<h4 align="center">
    <img alt="Representação visual de arquitetura hexagonal" title="Visão da documentação da API." src=".github/readme/swagger.png" width="1864px" />
</h4>
<br>


### 8. Testes Automatizados

Escrevi testes unitários para garantir a funcionalidade dos endpoints. Os testes verificam a autenticação, gerenciamento de palavras e histórico.

Para execução dos testes escreva na linha de comando dentro da pasta raiz do projeto `sail artisan test`.
<br>
<h4 align="center">
    <img alt="Representação visual de arquitetura hexagonal" title="Testes sendo executados." src=".github/readme/testes.png" width="1864px" />
</h4>
<br>

## :raising_hand: Como contribuir para o projeto

- Faça um **fork** do projeto;
- Crie uma nova branch com as suas alterações: `git checkout -b my-feature`
- Salve as alterações e crie uma mensagem de commit contando o que você fez:`git commit -m "feature: My new feature"`
- Envie as suas alterações: `git push origin my-feature`

> Caso tenha alguma dúvida confira este [guia de como contribuir no GitHub](https://github.com/firstcontributions/first-contributions)

## :pencil: Licença

Este projeto esta sobe a licença MIT. Veja a [LICENÇA](https://opensource.org/licenses/MIT) para saber mais.

Feito com ❤️ por Edeson Ribeiro Silva 👋🏽 [Entre em contato!](https://www.linkedin.com/in/edribeirodeveloper/)


[Voltar ao topo]
(#índice)
