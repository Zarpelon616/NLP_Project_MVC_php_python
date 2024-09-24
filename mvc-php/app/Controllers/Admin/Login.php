<?php

namespace App\Controllers\Admin;
use App\Controllers\Admin\Page;
use App\Http\Request;
use App\Utils\Views;
use App\Model\Entity\User;
use App\Session\Admin\Login as SessionAdminLogin;

class Login extends Page{

    /**
     * Método responsável por retornar a renderização da pagina de login
     * @param Request $request
     * @param string $errorMessage
     * @return string
     */
    public static function getLogin($request,$errorMessage=null){
        //STATUS
        //CONTEÚDO DA PÁGINA DE LOGIN
       $status=!is_null($errorMessage) ? Alert::getError($errorMessage):'';

       $content=Views::render('admin/login',[
        'status'=> $status

       ]);

       //RETORNA A  PAGINA COMPLETA
       return parent::getPage('Login RT',$content);
    }

    /**
     * Método responsável por definir  o login do usuario
     * @param Request $request
     * 
     */
    public static function setLogin($request){
        //POST VARs
        $postVars=$request->getPostVars();
        $email=$postVars['email'] ?? '';
        $senha=$postVars['senha'] ?? '';
        //BUSCA O USUARIO PELO E-MAIL
        $obuser=User::getUserByEmail($email);
        if(!$obuser instanceof User){
            return self::getLogin($request,'E-mail ou senha invalidos');
        }

        //VERIFICA A SENHA DO  USUARIO
        if(($senha!=$obuser->senha)){
            return self::getLogin($request,'E-mail ou senha invalidos');

        }

        //CRIA A SESSÃO DE LOGIN
        SessionAdminLogin::login($obuser);
        //REDIRICIONA O USUARIO PARA HOME DO ADMIN
        $request->getRouter()->redirect('/admin');

    }

    /**
     * Método responsável por deslogar o usúario
     * @param Request $request
     * 
     */
    public static function setLogout($request){
       //DESTROI A SESSÃO DE LOGIN
       SessionAdminLogin::logout();
       //REDIRICIONA O USUARIO PARA A TELA DE LOGIN
       $request->getRouter()->redirect('/admin/login');

    }
}