<?php
use \App\Controllers\Admin;
use App\Http\Response;

//ROTA ADMIN
$obRouter->get('/admin',[
    'middlewares'=>[
        'required-admin-login'
    ],
    function($request){
       return new Response(200,Admin\Home::getHome($request));
    }
]);


