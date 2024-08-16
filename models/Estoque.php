<?php
require_once "Produto.php";

class Estoque{
    private $id;
    private $produto;
    private $quantidade;
    private $created_at;


    function __construct(
        $id,
        Produto $produto,
        $quantidade,
        $created_at = null // Valor padrão é null
    ){
        $this->id = $id;
        $this->produto = $produto;
        $this->quantidade = $quantidade;
        $this->created_at = $created_at ?? date('Y-m-d H:i:s'); // Se não for passado, define como a data e hora atual
    }
    
    function getId(){
        return $this->id;
    }
    function setId($id){
        $this->id = $id;
    }
    function getProduto(){
        return $this->produto;
    }
    function setProduto(Produto $produto){
        $this->produto = $produto;
    }
    function getQuantidade(){
        return $this->quantidade;
    }
    function setQuantidade($quantidade){
        $this->quantidade = $quantidade;
    }
    function getCreatedAt(){
        return $this->created_at;
    }
    function setCreatedAt($created_at){
        $this->created_at = $created_at;
    }

}