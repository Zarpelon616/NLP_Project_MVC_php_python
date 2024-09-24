<?php

namespace App\Http\MiddleWares;
use \App\Http\Request;
use \App\Http\Response;
use Closure;
use Exception;
class Maintenance{

    /**
     * Método responsável por executar o middle
     * @param  Request $request
     * @param Closure 
     * @return  Response
     */
    public function handle($request,$next){
      //VERIFICA  O ESTADO DE  MANUTENÇÃO DA PÁGINA
    if(getenv("MAINTENANCE")=='true'){
        throw new Exception('Página em manutenção,tente novamente mais tarde !',200);
    }
        //EXECUTA O PRÓXIMO NÍVEL DO MIDDLEWARE
        return $next($request);
    }
}