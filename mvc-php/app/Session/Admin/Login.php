<?php

namespace App\Session\Admin;
use  App\Model\Entity\User;

class Login{


    /**
     * Método responsável por iniciar a sessão 
     * @return void
     */
    private static function init(){
       //VERIFICA SE A SESSÃO NÃO ESTÁ ATIVA
       if(session_status()!=PHP_SESSION_ACTIVE){
           session_start();
       }
    }

    /**
     * Método responsável por criar  o login do usuario
     * @param User $obuser
     * @return boolean
     */
    public static function login($obuser){
        //INCIA A SESSÃO
        self::init();
        //DEFINE A SESSÃO DO USÚARIO
        $_SESSION['admin']['usuario']=[
            'id'  =>$obuser->id,
            'nome' =>$obuser->nome,
            'email' =>$obuser->email
        ]; 
        //SUCESSO
        return true;
    }

    /**
     * Método responsável por verificar se  o usúario está logado
     * @return boolean
     */
    public static function isLogged(){
      // INICIA A SESSÃO
      self::init();
      
      //RETORNA A VERIFICAÇÃO 
      return isset($_SESSION['admin']['usuario']['id']);
    }

    /**
     * Método responsável por executar o logout do usúario
     * @return boolean
     */
    public static  function logout(){
      //INICIA  A SESSÃO
       self::init();  

       //DESLIGA O USUARIO
       unset($_SESSION['admin']['usuario']);
       //SUCESSO
       return true;
    }
}