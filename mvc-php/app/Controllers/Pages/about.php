<?php

namespace App\Controllers\Pages;
use \App\Utils\Views;
use \App\Model\Entity\Organization;

class about extends page{
    /**
     * Summary of getHome
     * @return string
     */
    public  static function getAbout(){
        //ORGANIZAÇÃO
        $obOrganization=new Organization();
        //VIEW DA HOME 
        $content=  Views::render("Pages/about",['Name'=>$obOrganization->name,'Descriptions'=>$obOrganization->description,
                    'Site'=>$obOrganization->site]);
                    //RETORNA A VIEW DA PAGINA.
        return parent::getPage('LEX FRIEDMAN',$content);
    }  
}