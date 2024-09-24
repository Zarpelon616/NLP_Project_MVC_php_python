<?php

namespace App\Controllers\Api;
use App\Http\Request;
use \App\Utils\Views;
use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;
use \App\Controllers\Pages\page;

class Testimony extends Api {

        /**
     * Método responsável por obter os itens de renderização dos depoimentos.
     * @param  Request  $request
     * @param Pagination $obPagination
     * @return array
     */
    private static function getTestimonyItems($request,&$obPagination){
        //DEPOIMENTOS
        $itens=[];

        //QUANTIDADE TOTAL DE REGISTROS
        $quantidadetotal=EntityTestimony::getTestimonies(null,null,null,'COUNT(*) as qtd')->fetchObject()->qtd;
        //PAGINA ACTUAL
        $queryParams=$request->getQueryParams();
        $paginaActual=$queryParams['page']?? 1;
        //INSTANCIA DE PAGINAÇÃO 
        $obPagination= new Pagination($quantidadetotal,$paginaActual,10);
        //RESULTADOS DA PAGINA
        $results=EntityTestimony::getTestimonies(null,'id DESC',$obPagination->getLimit());

        //RENDERIZA O ITEM
        while($obTestimony=$results->fetchObject(EntityTestimony::class)){
            $itens[]=[
                'id'=>(int)$obTestimony->id,
                'nome'=> $obTestimony->nome,
                'mensagem'=> $obTestimony->mensagem,
                'data'=>$obTestimony->data

            ];
        }
        //RETORNA OS DEPOIEMENTOS
        return $itens;
    }

    /**
     * Método responsável por retornar os depoimentos cadastrados.
     * @param Request $request
     * @return array
     */
    public static function getTestimonies($request){
        return [
            'depoimentos'=> self::getTestimonyItems($request,$obPagination),
            'paginacao'=> parent::getPagination($request,$obPagination)

        ];
    }

    /**
     * Método responsável por retornar os detalhes de um depoimento
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public static function getTestimony($request,$id){

        //VALIDA  O ID DO DEPOIMENTO

        if(!is_numeric($id)){
            throw new \Exception("O  id $id não   é  valido",404);
        }
       //BUSCA DEPOIMENTO
       $obTestimony=EntityTestimony::getTestimonyById($id);

       //VALIDA SE O DEPOIMENTO EXISTE

       if(!$obTestimony instanceof EntityTestimony){
          throw new \Exception("O depoimento $id não  foi encontrado",404);
       }

       //RETORNA OS DETALHES DEPOIMENTOS

       return [
        'id'=>(int)$obTestimony->id,
        'nome'=> $obTestimony->nome,
        'mensagem'=> $obTestimony->mensagem,
        'data'=>$obTestimony->data

    ];
    }

}




