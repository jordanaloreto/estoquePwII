<?php
require_once "controllers/ProdutoController.php";

if (isset($_GET["id"])) {
    $produtoController = new ProdutoController();
    $produtoController->delete($_GET["id"]);

    // Voltando para a tela anterior
    header("Location: ?pg=produtos");
    exit(); // Encerrando a execução do script PHP após redirecionamento
}
?>
