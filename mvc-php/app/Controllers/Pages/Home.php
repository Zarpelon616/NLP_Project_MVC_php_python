<?php

namespace App\Controllers\Pages;
use \App\Utils\Views;
use \App\Model\Entity\Organization;

class Home extends page{
    /**
     * Summary of getHome
     * @return string
     */
    public  static function getHome(){
        //ORGANIZAÇÃO
        $obOrganization=new Organization();
        //VIEW DA HOME 
        $content=  Views::render("Pages/Home",['Name'=>$obOrganization->name,
                    ]);
                    //RETORNA A VIEW DA PAGINA.
        return parent::getPage('LEX FRIEDMAN PODCAST',$content);
    }  
}