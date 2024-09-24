<?php

namespace App\Http;

class Response{
    /**
     * Codigos do status HTTP
     * @var integer
     */
    private $httpCode=200;

    /**
     * Cabeçallho do response
     * @var array
     */

     private $headers=[];
     /**
      * 
      * Tipo de  contéudo  que está sendo retornado
      * @var string
      */

     private $contentType='text/html';
     /**
      * 
      * Conteúdo do response;

      *@var mixed
      */
      private $content;

      /**
       * Método reponsável por iniciar a classe e definir os valores.
       * @param integer $httpCode
       * @param mixed $content
       * @param string $contentType
       */

      public function __construct($httpCode,$content,$contentType='text/html'){
           $this->httpCode=$httpCode;
           $this->content=$content;
           $this->contentType=$contentType; 
           $this->setContentType($contentType);
      }
       /**
        *Método responsável por alterar o content type do response 
        *@param string 
        */

      public function setContentType($contentType){
             $this->contentType=$contentType;
             $this->addHeader('Content-Type',$contentType);
      }

      /**
       * Método responsável por adicionar  um  registro no cabeçalho de response
       * @param string $key 
       * @param  string $value
       * 
       */

      public function addHeader ($key,$value){
        $this->headers[$key]=$value;

      }

      /**
       * Métod responsável por enviar os headers para o navegador
       */
      private function sendHeaders(){
        //STATUS
        http_response_code($this->httpCode);
        //ENVIAR HEADERS

        foreach ($this->headers as $key=>$value){
            header($key.':'.$value);
        }
      }

      /**
       * Método responsávek por enviar a resposta ao usuário.
       * 
       */

      public function sendResponse(){
        //ENVIA OS HEADERS
        $this->sendHeaders();
        //IMPRIME O CONTEÚDO
        switch($this->contentType){
            case 'text/html':
                echo $this->content;
                exit;
            case 'application/json':
              echo json_encode($this->content,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
               exit;
        }
      }
      
}