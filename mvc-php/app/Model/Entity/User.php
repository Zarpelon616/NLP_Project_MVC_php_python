<?php

namespace App\Model\Entity;
use WilliamCosta\DatabaseManager\Database;
use PDOStatement;

class User{

    /**
     * Id do usuário 
     * @var  integer
     */
    public $id;


    /**
     * Nome do usuário
     * @var string
     */
    public $nome;


    /**
     *  Email do usuario
     * @var string
     */
    public $email;


    /**
     * Senha do usario
     * @var string
     */
    public $senha;


    /**
     * Método responsável por retornar o usuario com base em seu email.
     * @param string $email
     * @return User
     */
    public static function getUserByEmail($email){
       return self::getUsers('email ="'.$email.'"')->fetchObject(self::class);
        
    }


    /**
     * Método responsável por retornar os usuários .
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $field
     * @return PDOStatement
     */
    public static function getUsers($where=null,$order=null,$limit=null,$fields='*') {
        return (new Database('usuarios'))->select($where, $order, $limit, $fields);

    }

    /**
     * Método responsável por cadastrar um usuário
     * @return boolean
     */
    public function cadastrar(){
        //INSERE  A INSTANCIA NO BANCO
        $this->id=(new Database('usuarios'))->insert([
           'nome'=>$this->nome,
           'email'=>$this->email,
           'senha'=>$this->senha

        ]);

        //SUCESSO
        return true;

    }


    /**
     * Método responsável por actualizar os dados no banco de dados 
     * @return boolean
     */
    public function excluir(){
        return (new Database('usuarios'))->delete('id ='.$this->id);

    }


        /**
     * Método responsável por excluir usuários no banco de dados .
     * @return boolean
     */
    public function actualizar(){
        return (new Database('usuarios'))->update('id ='.$this->id,[
            'nome'=>$this->nome,
            'email'=>$this->email,
            'senha'=>$this->senha
        ]);

    }

    /**
     * Método responsável por retornar uma instancia com base no id
     * @param integer $id
     * @return User
     */
    public  static function getUserById($id){
           return self::getUsers('id = '.$id)->fetchObject(self::class);     
    }
}

