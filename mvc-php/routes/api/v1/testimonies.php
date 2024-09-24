<?php


use \App\Http\Response;
use App\Controllers\Api;


//ROTA DE LISTAGEM DE DEPOIMENTOS
$obRouter->get('/api/v1/testimonies',[
    'middlewares'=>[
       'api'
    ],
    function($request){
        return new Response(200,Api\Testimony::getTestimonies($request),'application/json');
    }
]);


//ROTA DE CONSULTA INVIDUAL DE DEPOIMENTO
$obRouter->get('/api/v1/testimonies/{id}',[
    'middlewares'=>[
        'api'
     ],
    function($request,$id){
        return new Response(200,Api\Testimony::getTestimony($request,$id),'application/json');
    }
]);