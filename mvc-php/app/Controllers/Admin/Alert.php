<?php

namespace App\Controllers\Admin;
use App\Utils\Views;

class Alert {
    /**
     * Método responsável por retornar uma mensagem de sucesso !
     * @param string $message
     * @return string
     */
    public static  function getSuccess($message){
       return Views::render('admin/alerts/status',[
        'tipo'=>'success',
        'mensagem'=> $message
       ]);
    }


    /**
     * Método responsável por retornar uma mensagem de erro !
     * @param string $message
     * @return string
     */
    public static  function getError($message){
        return Views::render('admin/alerts/status',[
         'tipo'=>'danger',
         'mensagem'=> $message
        ]);
     }

}