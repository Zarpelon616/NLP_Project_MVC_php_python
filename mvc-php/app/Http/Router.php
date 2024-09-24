<?php

namespace App\Http;

use App\Http\MiddleWares\Queue as MiddleWaresQueue;
use Closure;
use Exception;
use ReflectionFunction;


class Router{
    /**
     * URL completa do projeto (raiz)
     * @var string
     */
    private $url = '';

    /**
     * Prefixo de todas as rotas
     * @var string
     */
    private $prefix = '';

    /**
     * Índice de rotas
     * @var array
     */

     /**
      * Content type padrão dos response
      * @var string
      */
     private $contentType='text/html';
     private $routes = [];

    /**
     * Instância do request
     * @var Request
     */
    private $request;

    /**
     * Método responsável por iniciar a classe
     * @param string $url
     */
    public function __construct($url){
        $this->request = new Request($this);
        $this->url = $url;
        $this->setPrefix();
    }

    /**
     * Método responsável por alterar o valor   do content type
     * @param string $contentType
     */
    public  function  setContentType($contentType){
         $this->contentType = $contentType;
    }

    /**
     * Método responsável por definir o prefixo das rotas
     */
    private function setPrefix(){
        // INFORMAÇÕES DA URL
        $parseUrl = parse_url($this->url);

        // DEFINE O PREFIXO
        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
     * Método responsável por adicionar uma rota na classe
     * @param string $method
     * @param string $route
     * @param array $params
     */
    public function addRoute($method, $route, $params = []){
        // VALIDAÇÃO DOS PARÂMETROS
        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }
        
        //MIDDLEWARES DA ROTA
        $params['middlewares']=$params['middlewares'] ?? [];

        //VARIAVIES DA ROTA
        $params['variables']=[];

        //PADRÃO DE VALIDAÇÃO DAS VARIÁVEIS DAS rOTAS

        $patternVariable='/{(.*?)}/';
        if(preg_match_all($patternVariable, $route, $matches)){
            $route=preg_replace($patternVariable,'(.*?)',$route);
            $params['variables']=$matches[1];
        }

        // PADRÃO DE VALIDAÇÃO DA URL
        $patternRoute = '/^' . str_replace('/', '\/', $route) . '$/';

        // ADICIONA A ROTA DENTRO DA CLASSE
        $this->routes[$patternRoute][$method] = $params;
    }

    /**
     * Método responsável por definir uma rota de GET
     * @param string $route
     * @param array $params
     */
    public function get($route, $params = []){
        return $this->addRoute("GET", $route, $params);
    }

     /**
      * @param string $route
      *@param array $params
      */

    public function post($route, $params = []){
        return $this->addRoute("POST", $route, $params);
    }


      /**
      * @param string $route
      *@param array $params
      */

      public function put($route, $params = []){
        return $this->addRoute("PUT", $route, $params);
    }

    
      /**
      * @param string $route
      *@param array $params
      */

      public function delete($route, $params = []){
        return $this->addRoute("DELETE", $route, $params);
    }



    /**
     * Método responsável por retornar a URI desconsiderando o prefixo
     * @return string
     */
    private function getUri(){
        // URI DA REQUEST
        $uri = $this->request->getUri();

        // FATIA A URI COM O PREFIXO
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        // RETORNA A URI SEM PREFIXO
        return  end($xUri);
    }

    /**
     * Método responsável por retornar os dados da rota atual
     * @return array
     */
    private function getRoute(){
        // URI
        $uri = $this->getUri();

        // MÉTODO HTTP
        $httpMethod = $this->request->getHttpMethod();


        // VALIDAR AS ROTAS
        foreach ($this->routes as $patternRoute => $methods) {
            // VERIFICA SE A URI BATE COM O PADRÃO
            if (preg_match($patternRoute, $uri,$matches)) {
                // VERIFICA O MÉTODO
                if (isset($methods[$httpMethod])){
                    //REMOVE A PRIMEIRA POSIÇÃO
                    unset($matches[0]);

                    //VARIAVEIS PROCESSADAS
                    $keys=$methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables']=array_combine($keys,$matches);
                    $methods[$httpMethod]['variables']['request']=$this->request;
                    return $methods[$httpMethod];
                }

                throw new Exception("Método não permitido", 405);
            }
        }

        throw new Exception("URL não encontrada", 404);
    }

    /**
     * Método responsável por executar a rota atual
     * @return Response
     */
    public function run(){
        try {
            // OBTÉM A ROTA ATUAL
            $route = $this->getRoute();
            //VERIFICA  O CONTROLADOR            
            if(!isset($route['controller'])){
               throw new Exception('A URL não pode ser processada',500);
            }

            //ARGUMENTOS DA FUNÇÃO
            $args=[];

            //REFLETION
            $reflection = new ReflectionFunction($route['controller']);
            foreach($reflection->getParameters() as $parameter){
                $name=$parameter->getName();
                $args[$name]=$route['variables'][$name] ?? '';
            }

            // RETORNA A EXECUÇÃO DA FILA DE MIDDLEWARE
            return (new MiddleWaresQueue($route['middlewares'],$route['controller'],$args))->next($this->request);
    

        } catch (Exception $e) {
            return new Response($e->getCode(), $this->getErrorMessage($e->getMessage()),$this->contentType);
        }
    }

    /**
     * Método responsável por retornar a mensagem de erro de acordo com o contenttype
     * @param string $message
     * @return mixed
     */
     private function getErrorMessage($message){
       switch($this->contentType){
        case 'application/json':
            return [
               'error'=>$message
            ];
            break;
         
        default :
            return $message;
            break;
       }
    }

    /**
     * 
     * Método responsável por retornar a URL actual.
     * @return
     */
     

    /**
     * Método responsável por retornar a URL actual
     * @return string
     */
    public function getCurrentUrl(){
        return $this->url.$this->getUri();
}


/**
 * Summary of redirect
 * @param string $route
 * @return 
 */
public function redirect($route){
 //RULE 
  $url=$this->url.$route;
  //EXECUTA O REDIRECT
  header('location: '.$url);
  exit;
}
   
}
