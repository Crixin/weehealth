<?php

namespace App\Classes;

use App\Classes\RESTServices;

/**
 * Class QueryGEDServices
 * @package App\Classes
 */
class QueryGEDServices
{
    private $REST;
    private $idArea;
    private $parametros;

    public function __construct()
    {
        $this->REST = new RESTServices();
    }

    /* GET */
    public function getIdArea()
    {
        return $this->idArea;
    }

    public function getParametros()
    {
        return $this->parametros;
    }



    /* SET */
    public function setIdArea($idArea)
    {
        $this->idArea = $idArea;
    }

    public function setParametros($parametros)
    {
        $this->parametros = $parametros;
    }


    /*Metodos*/



}