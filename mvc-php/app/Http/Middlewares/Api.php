<?php

namespace App\Http\MiddleWares;
use \App\Http\Request;
use \App\Http\Response;
use Closure;
use Exception;
class Api{

    /**
     * Método responsável por executar o middle
     * @param  Request $request
     * @param Closure 
     * @return  Response
     */
    public function handle($request,$next){
      //ALTERA O CONTENT TYPE PARA JSON
      $request->getRouter()->setContentType('application/json');

        //EXECUTA O PRÓXIMO NÍVEL DO MIDDLEWARE
        return $next($request);
    }
}