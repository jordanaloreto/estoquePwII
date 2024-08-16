<?php

require_once "Estoque.php";
require_once "Usuario.php";

class Historico {
    private $id;
    private $estoque;
    private $acao;
    private $quantidade;
    private $data;
    private $usuario;

    function __construct($id, Estoque $estoque, $acao, $quantidade, $data, Usuario $usuario) {
        $this->id = $id;
        $this->estoque = $estoque;
        $this->acao = $acao;
        $this->quantidade = $quantidade;
        $this->data = $data;
        $this->usuario = $usuario;
    }

    function getId() {
        return $this->id;
    }
    function setId($id) {
        $this->id = $id;
    }
    function getEstoque() {
        return $this->estoque;
    }
    function setEstoque(Estoque $estoque) {
        $this->estoque = $estoque;
    }
    function getAcao() {
        return $this->acao;
    }
    function setAcao($acao) {
        $this->acao = $acao;
    }
    function getQuantidade() {
        return $this->quantidade;
    }
    function setQuantidade($quantidade) {
        $this->quantidade = $quantidade;
    }
    function getData() {
        return $this->data;
    }
    function setData($data) {
        $this->data = $data;
    }
    function getUsuario() {
        return $this->usuario;
    }
    function setUsuario(Usuario $usuario) {
        $this->usuario = $usuario;
    }
}
?>
