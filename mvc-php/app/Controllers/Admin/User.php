<?php

namespace App\Controllers\Admin;
use App\Utils\Views;
use App\Http\Request;
use \App\Model\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;
class User extends Page{



    /**
     * Método responsável por obter os itens de renderização dos  usuários
     * @param  Request  $request
     * @param Pagination $obPagination
     * @return string
     */
    private static function getUsersItems($request,&$obPagination){
        //USUÁRIOS
        $itens='';

        //QUANTIDADE TOTAL DE REGISTROS
        $quantidadetotal=EntityUser::getUsers(null,null,null,'COUNT(*) as qtd')->fetchObject()->qtd;
        //PAGINA ACTUAL
        $queryParams=$request->getQueryParams();
        $paginaActual=$queryParams['page']?? 1;
        //INSTANCIA DE PAGINAÇÃO 
        $obPagination= new Pagination($quantidadetotal,$paginaActual,3);
        //RESULTADOS DA PAGINA
        $results=EntityUser::getUsers(null,'id DESC',$obPagination->getLimit());

        //RENDERIZA O ITEM
        while($obUser=$results->fetchObject(EntityUser::class)){
            $itens.=  Views::render("admin/modules/users/item",[
                'id'=>(int)$obUser->id,
                'nome'=> $obUser->nome,
                'email'=> $obUser->email,
                ]);
        }
        //RETORNA OS DEPOIEMENTOS
        return $itens;
    }

    /**
     * Método responsável por renderizar a view da home de  listagem dos usuários.
     * @param Request $request
     * @return  string
     * 
     */
    public static  function getUsers($request){
       //CONTEÚDO DA HOME
       $content=Views::render('admin/modules/users/index',[
        'itens'=>self::getUsersItems($request,$obPagination),
        'pagination'=>parent::getPagination($request,$obPagination),
        'status'=>self::getStatus($request)
       ]);
       
       //RETORNA  A PÁGINA COMPLETA 

       return parent::getPanel('Usuários - PODCAST',$content,'users');
    }

    
    /**
     * Método responsável por retornar o formulario de cadastro de um novo usuário
     * @param Request $request
     * @return string
     */
    public static function getNewUsers($request){

          //CONTEÚDO DO FORMULÁRIO
       $content=Views::render('admin/modules/users/form',[
        'title' =>'Cadastrar usuário',
        'nome'  =>'',
        'email' =>'',
        'status'  =>self::getStatus($request)
       ]);
       
       //RETORNA  A PÁGINA COMPLETA 

       return parent::getPanel('Cadastrar  usuário',$content,'users');

    }


     
    /**
     * Método responsável por  cadastrar um novo usuário  no banco 
     * @param Request $request
     * @return string
     */
    public static function setNewUser($request){
        //POST VARS
        $postVars=$request->getPostVars();
        $nome=$postVars['nome'] ?? '';
        $email=$postVars['email'] ?? '';
        $senha=$postVars['senha'] ?? '';
        
        //VALIDA O EMAIL DO USUÁRIO
        $obUser=EntityUser::getUserByEmail($email);

        if($obUser instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users/new?status=duplicated');
        }
      
        //NOVA INSTANCIA DE DEPOIMENTO
        $obUser=new EntityUser;
        $obUser->nome=$nome;
        $obUser->email=$email;
        $obUser->senha=password_hash($senha,PASSWORD_DEFAULT);
        $obUser->cadastrar();

        $request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=created');

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
            return Alert::getSuccess('Usuário criado com sucesso!');
            break;
        case 'updated':
            return Alert::getSuccess('Usuário actualizado com sucesso!');
            break;
        
        case 'deleted':
            return Alert::getSuccess('Usuário excluído  com sucesso!');
            break;

        case 'duplicated':
            return Alert::getError('O email  inserido já está sendo utilizado por outro usuário');
            break;
        
     }


  }

   /**
     * Método responsável por retornar o formulario de edição de um usuário
     * @param Request $request
     * @param  integer $id
     * @return  string
     * 
     */
    public static  function getEditUser($request,$id){
        //OBTÉM O CONTEÚDO DO BANCO DE DADOS 
        $obUser=EntityUser::getUserById($id);

        //VALIDA A INSTANCIA 

        if (!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');

        }

        //CONTÉUDO DO FORMULÁRIO
        $content=Views::render('admin/modules/users/form',[
         'title'=>'Editar usuário',
         'nome'=>$obUser->nome,
         'email'=>$obUser->email,
         'status'=>self::getStatus($request)
        ]);

        
        //RETORNA  A PÁGINA COMPLETA 
 
        return parent::getPanel('Editar usuário',$content,'users');
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
         'itens'=>self::getUsersItems($request,$obPagination),
         'pagination'=>parent::getPagination($request,$obPagination)
        ]);
        
        //RETORNA  A PÁGINA COMPLETA 
 
        return parent::getPanel('DEPOIMENTOS - PODCAST',$content,'testimonies');
     }

     /** Método responsável por gravar a actualização de um usuário .
     * @param Request $request
     * @param  integer $id
     * @return  string
     * 
     */
     public static function setEditUser($request,$id){
    
           //OBTÉM O CONTEÚDO DO BANCO DE DADOS 
           $obUser=EntityUser::getUserById($id);
   
           //VALIDA A INSTANCIA 
   
           if (!$obUser instanceof EntityUser){
               $request->getRouter()->redirect('/admin/users');
   
           }

           //POST VARS
           $postvars=$request->getPostVars();
           //ACTUALIZA AS INSTANCIAS 
           $nome=$postvars['nome'] ?? '';
           $email=$postvars['email'] ?? '';
           $senha=$postvars['senha'] ?? '';

           //VALIDA O EMAIL DO USUÁRIO
           $obUserEmail=EntityUser::getUserByEmail($email);
           if($obUserEmail instanceof EntityUser && $obUserEmail->id!=$id){
            //REDIRECIONA O USUÁRIO
            $request->getRouter()->redirect('/admin/users/'.$id.'/edit?status=duplicated');
           }
   
           //ACTUALIZA AS INSTANCIAS
           $obUser->nome=$nome;
           $obUser->email=$email;
           $obUser->senha=password_hash($senha,PASSWORD_DEFAULT);
           $obUser->actualizar();


           //REDIRECIONA O USÚARIO
           $request->getRouter()->redirect('/admin/users/'.$obUser->id.'/edit?status=updated');
       }


       
   /**
     * Método responsável por retornar  o formulário de exclusão de um usuário
     * @param Request $request
     * @param  integer $id
     * @return  string
     * 
     */
    public static  function getDeleteUser($request,$id){
        //OBTÉM O CONTEÚDO DO BANCO DE DADOS 
        $obUser=Entityuser::getuserById($id);

        //VALIDA A INSTANCIA 

        if (!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');

        }

        //CONTÉUDO DO FORMULÁRIO
        $content=Views::render('admin/modules/users/delete',[
         'nome'=>$obUser->nome,
         'email'=>$obUser->email
        ]);

        
        //RETORNA  A PÁGINA COMPLETA 
 
        return parent::getPanel('Excluir usuário',$content,'users');
     }


       /** Método responsável por excluir um usuário
     * @param Request $request
     * @param  integer $id
     * @return  string
     * 
     */
    public static function setDeleteUser($request,$id){
    
        //OBTÉM O CONTEÚDO DO BANCO DE DADOS 
        $obUser=EntityUser::getUserById($id);

        //VALIDA A INSTANCIA 

        if (!$obUser instanceof EntityUser){
            $request->getRouter()->redirect('/admin/users');

        }

        //POST VARS
        $postvars=$request->getPostVars();
        //EXCLUI O DEPOIMENTO
        $obUser->excluir();
        //REDIRECIONA O USÚARIO
        $request->getRouter()->redirect('/admin/users?status=deleted');
    }


}
  

    