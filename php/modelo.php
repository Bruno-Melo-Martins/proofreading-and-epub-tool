<?php
class Tarefa{

    private $titulo;

    public function __get($atributo){
        return $this-> $atributo;
    }

    public function __set($atributo, $valor){
      $this-> $atributo=$valor;
    }
}
?>