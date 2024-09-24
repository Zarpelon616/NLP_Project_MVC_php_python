<?php
use \App\Controllers\Admin;
use App\Http\Response;

//ROTA  DE LOGIN 

$obRouter->get('/admin/login',[
    'middlewares'=>[
        'required-admin-logout'
    ],
    function($request){
       return new Response(200,Admin\Login::getLogin($request));
    }
]);


//ROTA DE LOGIN (POST)

$obRouter->post('/admin/login',[
    'middlewares'=>[
        'required-admin-logout'
    ],
    function($request){
        return new Response(200,Admin\Login::setLogin($request));
    }
]);


//ROTA DE LOGOUT 

$obRouter->get('/admin/logout',[
    'middlewares'=>[
        'required-admin-login'
    ],
    function($request){
       return new Response(200,Admin\Login::setLogout($request));
    }
]);
