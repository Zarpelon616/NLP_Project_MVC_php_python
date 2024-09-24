<?php

namespace App\Controllers\Admin;
use App\Utils\Views;
use App\Http\Request;
class Home extends Page{

    /**
     * Método responsável por renderizar a view da home do painel
     * @param Request $request
     * @return  string
     */
    public static  function getHome($request){
       //CONTEÚDO DA HOME
       $content=Views::render('admin/modules/home/index',[]);
       
       //RETORNA  A PÁGINA COMPLETA 

       return parent::getPanel('HOME - PODCAST',$content,'home');
    }
}