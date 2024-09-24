<?php

namespace App\Controllers\Pages;

use \App\Utils\Views;
use App\Http\Request;
use \WilliamCosta\DatabaseManager\Pagination;

class page {

    /**
     * Método responsável por renderizar o topo da página.
     * @return string
     */
    private static function getHeader() {
        return Views::render('Pages/header');
    }

    /**
     * Método responsável por renderizar o rodapé da página.
     * @return string
     */
    private static function getFooter() {
        return Views::render('pages/footer');
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
            $links .= Views::render("Pages/pagination/link", [
                'page' => $page['page'],
                'link' => $link,
                'active'=>$page['current']  ? 'active':''
            ]);
        }

        // Renderiza a box de paginação
        return Views::render("Pages/pagination/box", [
            'links' => $links
        ]);
    }

    /**
     * Método responsável por renderizar a página completa com header, footer e conteúdo.
     * @param string $title
     * @param string $content
     * @return string
     */
    public static function getPage($title, $content) {
        return Views::render("Pages/pages", [
            'Title'   => $title,
            'Content' => $content,
            'header'  => self::getHeader(),
            'footer'  => self::getFooter()
        ]);
    }
}
