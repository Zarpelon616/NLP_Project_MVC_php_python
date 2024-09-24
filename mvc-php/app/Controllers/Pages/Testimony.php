<?php

namespace App\Controllers\Pages;
use \App\Utils\Views;
use \App\Http\Request;
use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

class Testimony extends page{
    /**
     * Método responsável por obter os itens de renderização dos depoimentos.
     * @param  Request  $request
     * @param Pagination $obPagination
     * @return string
     */
    private static function getTestimonyItems($request,&$obPagination){
        //DEPOIMENTOS
        $itens='';

        //QUANTIDADE TOTAL DE REGISTROS
        $quantidadetotal=EntityTestimony::getTestimonies(null,null,null,'COUNT(*) as qtd')->fetchObject()->qtd;
        //PAGINA ACTUAL
        $queryParams=$request->getQueryParams();
        $paginaActual=$queryParams['page']?? 1;
        //INSTANCIA DE PAGINAÇÃO 
        $obPagination= new Pagination($quantidadetotal,$paginaActual,3);
        //RESULTADOS DA PAGINA
        $results=EntityTestimony::getTestimonies(null,'id DESC',$obPagination->getLimit());

        //RENDERIZA O ITEM
        while($obTestimony=$results->fetchObject(EntityTestimony::class)){
            $itens.=  Views::render("Pages/testimony/item",[
                'nome'=> $obTestimony->nome,
                'mensagem'=> $obTestimony->mensagem,
                'data'=>date('d/m/Y H:i:s', strtotime($obTestimony->data)),

                ]);
        }
        //RETORNA OS DEPOIEMENTOS
        return $itens;
    }

    /**
     * Summary of getHome
     * @return string
     */

     //MÉTODO RESPONSÁVEL POR EXIBIR A VIEW DE DEPOIMENTOS.
    /**
     * Summary of getTestimonies
     * @param Request $request
     * @return string
     */
    public  static function getTestimonies($request){
        //ORGANIZAÇÃO
        //VIEW DA HOME 
        $content=  Views::render("Pages/Testimonies",[
                    'itens'=>self::getTestimonyItems($request,$obPagination),
                'pagination'=>parent ::getPagination($request,$obPagination)]);
                    //RETORNA A VIEW DA PAGINA.
        return parent::getPage('ROTAS DA AMIZADE ',$content);
    }
    
    
    /**
     * Método responsável por cadastar um depoimento
     * @param Request $request
     * @return string 
     */
    public static function insertTestimony($request){
        //DADOS DO POST
        $postVars=$request->getPostVars();
        //NOVA INSTANCIA DE DEPOIMENTO
        $obTestimony=new EntityTestimony();
        $obTestimony->nome=$postVars['nome'];
        $obTestimony->mensagem=$postVars['mensagem'];
        $obTestimony->cadastrar();
        
        //RETORNA AS LISTAGENS DE DEPOIMENTOS
        return self::getTestimonies($request);
    }
}