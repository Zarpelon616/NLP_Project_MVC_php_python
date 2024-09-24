# Documentação do Projeto

## Requisitos do Sistema

Para executar esta aplicação, você precisará dos seguintes softwares instalados:

### 1. PHP
- **Versão:** 8.3.10 ou superior
- **Descrição:** PHP é uma linguagem de script amplamente utilizada para desenvolvimento web. Baixe e instale a versão 8.3.10 ou superior a partir do [site oficial do PHP](https://www.php.net/).

### 2. MySQL
- **Descrição:** MySQL é um banco de dados relacional necessário para armazenar dados da aplicação. Caso utilize o [XAMPP](https://www.apachefriends.org/index.html), o MySQL já estará incluído no pacote.

### 3. Composer
- **Versão:** 6.3
- **Descrição:** Composer é o gerenciador de dependências para PHP. Baixe e instale a versão 6.3 a partir do [site oficial do Composer](https://getcomposer.org/).

### 4. XAMPP
- **Descrição:** XAMPP é uma distribuição que facilita a configuração de um ambiente local com Apache e MySQL. Inclui o Apache Server, PHP e MySQL. Baixe o XAMPP através do [site oficial](https://www.apachefriends.org/index.html).

### 5. phpMyAdmin
- **Versão:** 5.2.1
- **Descrição:** phpMyAdmin é uma ferramenta para gerenciar o MySQL através de uma interface web. Instale a versão 5.2.1 ou superior a partir do [site oficial do phpMyAdmin](https://www.phpmyadmin.net/).

## 6. Pacotes para Instalar
- *Descrição:Insira no terminal os seguintes comandos * 1-composer require williamcosta/database-manager
 2-composer require william-costa/dot-env

---

## Configuração do Banco de Dados

Defina as variáveis de ambiente para configuração do banco de dados. Exemplo:

```bash
URL=http://localhost:8000/mvc
DB_HOST=localhost
DB_NAME=database_name
DB_USER=root
DB_PASS=1234
DB_PORT=3306
MAINTENANCE=false


---

## Como Usar o Projeto

Algumas rotas não foram configuradas de forma dinâmica, portanto, especificarei como acessar as telas. 

### Exemplo:
Para acessar o painel de administração de depoimentos, use a seguinte URL:
- **URL:** `http://localhost:8000/mvc/admin/login`

#### Credenciais utilizadas para acessar:
1 Criar bancos de dados
2 Criar as tabelas
2.1 usuarios
2.2 depoimentos
3 Para acessar a rota de admin para o painel de 
admnistração cadastrar alguns dados na tabela de 
usuarios.
- **Email:** aguinaldo@gmail.com
- **Senha:** 123

---

## Integração com Ambiente Python

O objetivo do sistema é analisar sentimentos dos textos inseridos pelos usuários. Portanto, o sistema depende de outras ferramentas. Aqui estão outros requisitos para executar o projeto de forma eficiente.

### Requisitos do Sistema - Python

Para executar esta aplicação, você precisará dos seguintes softwares instalados:

### 1. Python
- **Versão:** 3.10 ou superior

### 2. TensorFlow
- **Descrição:** TensorFlow é uma framework utilizada para computação numérica e deep learning. [TensorFlow](https://www.tensorflow.org/).

### 3. NumPy
- **Descrição:** NumPy é uma biblioteca utilizada para computação numérica. [NumPy](https://numpy.org/).

### 4. Matplotlib
- **Descrição:** Matplotlib é uma biblioteca utilizada para geração de gráficos visuais. [Matplotlib](https://matplotlib.org/).

### 5. Pandas
- **Descrição:** Pandas é uma ferramenta utilizada para trabalhar com estruturas tabulares. [Pandas](https://pandas.pydata.org/).

### 6. Seaborn
- **Descrição:** Seaborn é uma biblioteca utilizada para geração de gráficos visuais. [Seaborn](https://seaborn.pydata.org/).

---

## Ambiente Virtual

### Descrição:
É recomendado instalar um ambiente virtual antes de instalar todas as bibliotecas.

### Como Instalar:
1. Navegue até o diretório do seu projeto:
   ```bash
   cd caminho/para/seu/projeto
python -m venv venv
venv\Scripts\activate


