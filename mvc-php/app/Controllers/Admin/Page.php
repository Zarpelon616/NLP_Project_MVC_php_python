<?php

namespace App\Controllers\Admin;
use App\Utils\Views;
use App\Http\Request;
use \WilliamCosta\DatabaseManager\Pagination;

class Page{


    /**
     * Módulos disponíveis no painel
     * @var array
     */
    private static $modules=[
         'home'=>[
            'label'=>'Home',
            'link'=>URL.'/admin'
         ],

         'testimonies'=>[
            'label'=>'Depoimentos',
            'link'=>URL.'/admin/testimonies'
         ],

         'users'=>[
            'label'=>'Usuários',
            'link'=>URL.'/admin/users'
         ]
    ];

    /**
     * Método responsável por retornar o conteúdo (view) estrutura generica de  página
     * @param string $title
     * @param string $content
     * @return  string
     */
    public static function getPage($title,$content){
         return Views::render('admin/page',[
            'title' =>$title,
            'Content'=>$content
         ]);
    }

    /**
     * Método responsável  por renderizar a view  do menu do painel
     * @param string $currentModule
     * @return string
     */
    private static  function getMenu($currentModule){
        //LINKS DO MENU
        $links='';

        //ITERA OS MODULOS
        foreach(self::$modules as $hash=>$module){
              $links.= Views::render('admin/menu/link',[
                'label'   =>$module['label'],
                'link'    =>$module['link'],
                'current' =>$hash == $currentModule ? 'text-danger':''
              ]);
        }
        //RETORNA A RENDERIZAÇÃO DO MENU
        return Views::render('admin/menu/box',[
            'links'=> $links

          ]);
    }

    /**
     * Método responsável por renderizar a view do painel com os conteúdos dinamicos 
     * @param string $title
     * @param string $content
     * @param string $currentModule
     * @return string
     */

    public static  function getPanel($title,$content,$currentModule){
        //RENDERIZA  A VIEW DO PAINEL
        $contentPanel=Views::render('admin/panel',[
            'menu'=>self::getMenu($currentModule),
            'content'=>$content
        ]);
        //RETORNA  A PÁGINA RENDERIZADA.

        return self::getPage($title,$contentPanel);
    }


      /**
     * Método responsável por renderizar o layout de paginação.
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    public static function getPagination($request, $obPagination) {
        $pages = $obPagination->getPages();

        // Verifica a quantidade de páginas
        if (count($pages) <= 1) return '';

        // Links
        $links = '';

        // URL atual (sem GETs)
        $url = $request->getRouter()->getCurrentUrl();

        // Query params
        $queryParams = $request->getQueryParams();

        // Renderizar os links
        foreach ($pages as $page) {
            // Altera a página
            $queryParams['page'] = $page['page'];

            // Link
            $link = $url . '?' . http_build_query($queryParams);

            // View
            $links .= Views::render("admin/pagination/link", [
                'page' => $page['page'],
                'link' => $link,
                'active'=>$page['current']  ? 'active':''
            ]);
        }

        // Renderiza a box de paginação
        return Views::render("admin/pagination/box", [
            'links' => $links
        ]);
    }
}