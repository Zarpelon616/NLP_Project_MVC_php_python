<?php
use \App\Controllers\Pages;
use \App\Http\Response;

$obRouter->get('/',[
    function(){
       return new Response(200,Pages\Home::getHome());
    }
]);

$obRouter->get('/about',[
    function(){
       return new Response(200,Pages\about::getAbout());
    }
]);

$obRouter->get('/depoimentos',[
    function($request){
       return new Response(200,Pages\Testimony::getTestimonies($request));
    }
]);



//ROTA DE DEPOIMENTO INSERT 
$obRouter->post('/depoimentos',[
    function($request)
    {
       return new Response(200,Pages\Testimony::insertTestimony($request));
    }
]);



