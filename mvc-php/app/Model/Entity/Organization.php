<?php

namespace App\Model\Entity;

class Organization
{
    /**
     * ID da organização.
     * @var integer
     */
    public $id = 1;
    
    /**
     * Nome da organização.
     * @var string
     */
    public $name = 'Videira SC';
    
    /**
     * Site da organização.
     * @var string
     */
    public $site = 'https://videira.com/';
    
    /**
     * Descrição da organização/pessoa.
     * @var string
     */
    public $description ='Venha conhecer paisagens incriveís ';
}
