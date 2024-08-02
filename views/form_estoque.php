<?php
require_once "controllers/ProdutoController.php";
require_once "controllers/EstoqueController.php";

$produtoController = new ProdutoController();
$produtos = $produtoController->findAll();

if (isset($_POST["produto"]) && isset($_POST["quantidade"])) {
    $estoqueController = new EstoqueController();

    $estoque = new Estoque(
        null,
        $produtoController->findById($_POST["produto"]),
        $_POST["quantidade"]
    );

    $estoqueController->save($estoque);

    header("Location: ?pg=estoque");
    exit();
}
?>

<div class="container mt-2">
    <h1 class="text-center mb-0">Cadastro de Estoque</h1>
    <form method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label for="produto">Produto</label>
            <select class="form-control" id="produto" name="produto">
                <?php
                foreach ($produtos as $produto):
                    echo "<option value=\"{$produto->getId()}\">{$produto->getNome()}</option>";
                endforeach;
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="quantidade">Quantidade</label>
            <input type="number" class="form-control" id="quantidade" name="quantidade" value="0">
        </div>

        <input type="submit" class="btn btn-primary" id="salvar" name="salvar" value="Salvar">
    </form>
</div>
