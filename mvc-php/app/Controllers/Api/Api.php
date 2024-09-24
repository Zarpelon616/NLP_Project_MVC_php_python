<?php

namespace App\Controllers\Api;
use App\Http\Request;
use \WilliamCosta\DatabaseManager\Pagination;


class Api {

    /**
     * Método responsável por retornar os detalhes da API
     * @param Request $request
     * @return array
     */
    public static function getDetails($request){
        return [
            'nome' => 'API-Analíse de Contéudo',
            'versao' => 'v1.0.0',
            'autor' => 'Aguinaldo Mulonde & Murilo',
            'email' => 'guinathetrap@gmail.com'
        ];


    }

    /**
     * Método  responsável por retornar os detalhes da paginação.
     * @param Request $request
     * @param Pagination $obPagination 
     * @return  array
     */
    protected static function getPagination($request, $obPagination){
      $queryParams=$request->getQueryParams();
      //PÁGINA
      $pages=$obPagination->getPages();
      //RETORNO
      return [
        'paginaActual'=>isset($queryParams['page']) ? (int)$queryParams['page']:1,
        'quantidadePaginas'=>!empty($pages) ? count($pages):1
      ];

    }
}
