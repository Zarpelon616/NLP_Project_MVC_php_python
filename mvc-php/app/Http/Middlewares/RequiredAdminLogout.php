<?php

namespace App\Http\MiddleWares;
use Closure;
use App\Http\Response;
use App\Http\Request;
use \App\Session\Admin\Login as SessionAdminLogin;

class RequiredAdminLogout{

    /**
     * Método responsável por executar o middle
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request,$next){
      //VERIFICA SE O USUARIO ESTÁ LOGADO
      if(SessionAdminLogin::isLogged()){
        $request-> getRouter()->redirect('/admin');
      }

      //CONTINUA A EXECUÇÃO
      return $next($request);
    }
}