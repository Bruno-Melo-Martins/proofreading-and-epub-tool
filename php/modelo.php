<?php
class Tarefa{

    private $titulo;
    private $nome;
    private $autor;
    private $tipo;
    private $fonte;
    private $caminho;

    public function __get($atributo){
        return $this-> $atributo;
    }

    public function __set($atributo, $valor){
      $this-> $atributo=$valor;
    }
}
?>