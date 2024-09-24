<?php

namespace App\Controllers\Admin;
use App\Utils\Views;
use App\Http\Request;
use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;
class Testimony extends Page{



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
        $obPagination= new Pagination($quantidadetotal,$paginaActual,10);
        //RESULTADOS DA PAGINA
        $results=EntityTestimony::getTestimonies(null,'id DESC',$obPagination->getLimit());

        //RENDERIZA O ITEM
        while($obTestimony=$results->fetchObject(EntityTestimony::class)){
            $itens.=  Views::render("admin/modules/testimonies/item",[
                'id'=>$obTestimony->id,
                'nome'=> $obTestimony->nome,
                'mensagem'=> $obTestimony->mensagem,
                'data'=>date('d/m/Y H:i:s', strtotime($obTestimony->data)),

                ]);
        }
        //RETORNA OS DEPOIEMENTOS
        return $itens;
    }

    /**
     * Método responsável por renderizar a view da home de  listagem de depoimentos.
     * @param Request $request
     * @return  string
     * 
     */
    public static  function getTestimonies($request){
       //CONTEÚDO DA HOME
       $content=Views::render('admin/modules/testimonies/index',[
        'itens'=>self::getTestimonyItems($request,$obPagination),
        'pagination'=>parent::getPagination($request,$obPagination),
        'status'=>self::getStatus($request)
       ]);
       
       //RETORNA  A PÁGINA COMPLETA 

       return parent::getPanel('DEPOIMENTOS - PODCAST',$content,'testimonies');
    }

    
    /**
     * Método responsável por retornar o formulario de cadastro de um novo depoimento
     * @param Request $request
     * @return string
     */
    public static function getNewTestimony($request){

          //CONTEÚDO DO FORMULÁRIO
       $content=Views::render('admin/modules/testimonies/form',[
        'title' =>'Cadastrar depoimento',
        'nome'  =>'',
        'mensagem' =>'',
        'status'  =>''
       ]);
       
       //RETORNA  A PÁGINA COMPLETA 

       return parent::getPanel('Cadastrar  depoimentos',$content,'testimonies');

    }


     
    /**
     * Método responsável por  cadastrar um novo depoimento no banco 
     * @param Request $request
     * @return string
     */
    public static function setNewTestimony($request){
        //POST VARS
        $postVars=$request->getPostVars();

        //NOVA INSTANCIA DE DEPOIMENTO
        $obTestimony=new EntityTestimony;
        $obTestimony->nome=$postVars['nome'];
        $obTestimony->mensagem=$postVars['mensagem'];
        $obTestimony->cadastrar();

        $request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=created');

        //CONTEÚDO DO FORMULÁRIO
  }

   

  /**
   * Método responsável por retornar a mensagem de status
   * @param Request $request
   * @return string
   */
  private static function getStatus($request){
     //QUERY PARAMS
     $queryParams=$request->getQueryParams();

     //STATUS
     
     if(!isset($queryParams['status'])) return '';

     //MENSAGENS SE STATUS
     switch($queryParams['status']){
        case 'created':
            return Alert::getSuccess('Depoimento criado com sucesso!');
            break;
        case 'updated':
            return Alert::getSuccess('Depoimnento actualizado com sucesso!');
            break;
        
        case 'deleted':
            return Alert::getSuccess('Depoimnento excluído  com sucesso!');
            break;
        
        
     }


  }

   /**
     * Método responsável por retornar o formulario de edição de um depoimento
     * @param Request $request
     * @param  integer $id
     * @return  string
     * 
     */
    public static  function getEditTestimony($request,$id){
        //OBTÉM O CONTEÚDO DO BANCO DE DADOS 
        $obTestimony=EntityTestimony::getTestimonyById($id);

        //VALIDA A INSTANCIA 

        if (!$obTestimony instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testimonies');

        }

        //CONTÉUDO DO FORMULÁRIO
        $content=Views::render('admin/modules/testimonies/form',[
         'title'=>'Editar depoimento',
         'nome'=>$obTestimony->nome,
         'mensagem'=>$obTestimony->mensagem,
         'status'=>self::getStatus($request)
        ]);

        
        //RETORNA  A PÁGINA COMPLETA 
 
        return parent::getPanel('Editar depoimento',$content,'testimonies');
     }

    /**
     * Método responsável por gravar a actualização  view da home de  listagem de depoimentos.
     * @param Request $request
     * @return  string
     * 
     */
    public static  function getEdit($request){
        //CONTEÚDO DA HOME
        $content=Views::render('admin/modules/testimonies/index',[
         'itens'=>self::getTestimonyItems($request,$obPagination),
         'pagination'=>parent::getPagination($request,$obPagination)
        ]);
        
        //RETORNA  A PÁGINA COMPLETA 
 
        return parent::getPanel('DEPOIMENTOS - PODCAST',$content,'testimonies');
     }

     /** Método responsável por gravar a actualização de um depoimento .
     * @param Request $request
     * @param  integer $id
     * @return  string
     * 
     */
     public static function setEditTestimony($request,$id){
    
           //OBTÉM O CONTEÚDO DO BANCO DE DADOS 
           $obTestimony=EntityTestimony::getTestimonyById($id);
   
           //VALIDA A INSTANCIA 
   
           if (!$obTestimony instanceof EntityTestimony){
               $request->getRouter()->redirect('/admin/testimonies');
   
           }

           //POST VARS
           $postvars=$request->getPostVars();

           //ACTUALIZA AS INSTANCIAS 
           $obTestimony->nome=$postvars['nome'] ?? $obTestimony->nome;
           $obTestimony->mensagem=$postvars['mensagem'] ?? $obTestimony->mensagem;
           $obTestimony->actualizar();


           //REDIRECIONA O USÚARIO
           $request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=updated');
       }


       
   /**
     * Método responsável por retornar  o formulário de exclusão de um depoimento 
     * @param Request $request
     * @param  integer $id
     * @return  string
     * 
     */
    public static  function getDeleteTestimony($request,$id){
        //OBTÉM O CONTEÚDO DO BANCO DE DADOS 
        $obTestimony=EntityTestimony::getTestimonyById($id);

        //VALIDA A INSTANCIA 

        if (!$obTestimony instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testimonies');

        }

        //CONTÉUDO DO FORMULÁRIO
        $content=Views::render('admin/modules/testimonies/delete',[
         'nome'=>$obTestimony->nome,
         'mensagem'=>$obTestimony->mensagem
        ]);

        
        //RETORNA  A PÁGINA COMPLETA 
 
        return parent::getPanel('Excluir depoimento',$content,'testimonies');
     }


       /** Método responsável por excluir um depoimento
     * @param Request $request
     * @param  integer $id
     * @return  string
     * 
     */
    public static function setDeleteTestimony($request,$id){
    
        //OBTÉM O CONTEÚDO DO BANCO DE DADOS 
        $obTestimony=EntityTestimony::getTestimonyById($id);

        //VALIDA A INSTANCIA 

        if (!$obTestimony instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testimonies');

        }

        //POST VARS
        $postvars=$request->getPostVars();
        //EXCLUI O DEPOIMENTO
        $obTestimony->excluir();
        //REDIRECIONA O USÚARIO
        $request->getRouter()->redirect('/admin/testimonies?status=deleted');
    }


}
  

    