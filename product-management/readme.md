### Estocador - Catálogo de Produtos

O **Estocador** é um sistema web simplificado para gerenciamento e cadastro de produtos (CRUD). Desenvolvido em PHP nativo com persistência em banco de dados MySQL via PDO, o sistema oferece uma interface responsiva para monitorar métricas básicas, buscar itens e gerenciar um catálogo de produtos em tempo real.

## Funcionalidades

* **Painel de Controle (Dashboard):** Exibição de métricas gerais do inventário, incluindo o total de produtos cadastrados, preço médio dos itens e o maior valor registrado.
* **Gerenciamento Completo (CRUD):** Criação, leitura, atualização e exclusão de produtos.
* **Filtro de Busca:** Localização rápida de itens por correspondência de nome ou descrição.
* **Segurança e Consistência:** Utilização de *Prepared Statements* contra SQL Injection e formulários baseados em requisições POST para operações críticas (como exclusões).
* **Localização:** Interface totalmente em português com formatação monetária padrão **BRL (R$)**.

## Tecnologias Utilizadas

* **Backend:** PHP 8.x (Nativo)
* **Banco de Dados:** MySQL
* **Abstração de Banco:** PDO (PHP Data Objects)
* **Frontend:** HTML5, CSS3 (Interface customizada via `style.css`)

## Estrutura do Projeto

```text
├── db.php          # Arquivo de conexão com o banco de dados (PDO)
├── index.php       # Painel principal, listagem de produtos e métricas
├── create.php      # Formulário e lógica de cadastro de novos produtos
├── edit.php        # Formulário e lógica de edição de produtos existentes
├── delete.php      # Script de processamento para exclusão segura de produtos
├── style.css       # Estilização global do catálogo
├── database.sql    # Arquivo de importação do banco de dados MySQL
└── README.md       # Documentação do projeto
```

## Configuração e Instalação

# Pré-requisitos

Servidor local instalado (ex: XAMPP, WampServer ou Laragon) com suporte a PHP 8.0+ e MySQL.

1. Importação do Banco de Dados
O projeto já acompanha a estrutura do banco de dados pronta para uso. Para configurá-lo:

Acesse o seu gerenciador de banco de dados local (como o phpMyAdmin).

Crie um banco de dados chamado product_db.

Selecione o banco criado, clique na aba Importar, selecione o arquivo database.sql incluído na pasta do projeto e confirme a execução.

2. Configuração da Conexão
Se você estiver utilizando as credenciais padrões do XAMPP (host: 127.0.0.1, usuário: root e sem senha), o arquivo db.php já está pronto para uso. Caso contrário, abra o arquivo e edite os parâmetros conforme seu ambiente:

```
$host = '127.0.0.1';
$db   = 'product_db';
$user = 'sua_id';
$pass = 'sua_senha';
```

3. Executando o Projeto
Mova ou clone a pasta do projeto para o diretório de arquivos públicos do seu servidor local (ex: C:\xampp\htdocs\estocador).

Certifique-se de que os serviços do Apache e MySQL estejam ativos no painel do seu servidor.

Abra o navegador e acesse: http://localhost/estocador.

Notas de Desenvolvimento
O sistema conta com validações no backend para impedir a inserção de preços negativos ou nomes vazios.

A paginação e formatação monetária utilizam as funções nativas do PHP, garantindo leveza e facilidade de manutenção no código fonte.