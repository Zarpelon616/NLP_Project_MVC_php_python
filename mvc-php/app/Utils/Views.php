<?php

namespace App\Utils;

class Views{
    /**
     * Variaveis padrões da view
     * @var array
     */
    private static $vars = [];

    /**
     * Método responsável por definir os dados inicias da classe
     * @param array $vars
     * 
     */
    public static function init($vars=[]){
        self::$vars=$vars;
    }

    /**
     * Método responsável por retornar o conteúdo de uma view.
     *
     * @param string $view
     * @return string
     */
    private static function getContentView($view){
        $file = __DIR__.'/../../resources/View/'.$view.'.html';
        return file_exists($file) ? file_get_contents($file) : '';
    }

    /**
     * Método responsável por retornar o conteúdo renderizado de uma view.
     *
     * @param string $view
     * @param array $vars  (string/numeric)
     * @return string
     */
    public static function render($view, $vars = []){
        // Conteúdo da View
        $contentView = self::getContentView($view);
        //MERGE DE  VARIAVEIS DA  VIEW 
        $vars=array_merge(self::$vars,$vars);
        // Chaves do array de variaveis.
        $keys=array_keys($vars);
        $keys=array_map(function($item){
            return '{{'.$item.'}}';
        },$keys);

        $views=str_replace($keys,array_values($vars),$contentView);
        return  $views;
        // Retorna o conteúdo renderizado
    }

}
