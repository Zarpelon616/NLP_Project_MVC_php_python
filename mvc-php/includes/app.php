<?php
require __DIR__ . '/../vendor/autoload.php'; 

use \App\Controllers\Pages\Home;
use App\Utils\Views; 
use WilliamCosta\DatabaseManager\Database;
use \WilliamCosta\DotEnv\Environment;
use \App\Http\Middlewares\Queue as MiddleWaresQueue;


//CARREGAR AS VARIAVEIS DE AMBIENTE
Environment::load(__DIR__.'/../'); 
Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT'),
);
//DEFINE AS CONFIGURAÇÕES DO BANCO DE DADOS 
define('URL',getenv('URL'));
//DEFINE O VALOR PADRÃO DAS VARIAVEIS.

Views::init([
   'URL'=>URL
]);

//DEFINE O MAPEAMENTO DE MIDDLEWARES  
MiddleWaresQueue::setMap([
    'maintenance'=>\App\Http\MiddleWares\Maintenance::class,
    'required-admin-logout'=>\App\Http\MiddleWares\RequiredAdminLogout::class,
    'required-admin-login'=>\App\Http\MiddleWares\RequiredAdminLogin::class,
    'api'=>\App\Http\MiddleWares\Api::class
]);


//DEFINE O MAPEAMENTO DE MIDDLEWARES PADRÕES (EXECUTADOS EM TODAS AS ROTAS)
MiddleWaresQueue::setDefault([
    'maintenance'
]);