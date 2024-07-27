<?php
require_once "controllers/ProdutoController.php"; 
require_once "controllers/CategoriaController.php"; 

// Inicia a sessão
echo" kkkkk";
if (isset($_GET["id"])) {
    var_dump($produto);

    $produtoController = new ProdutoController();
    $produto = $produtoController->findById($_GET["id"]);
}

$categoriaController = new CategoriaController();
$categorias = $categoriaController->findAll();

if (isset($_POST["nome"])) {
    $produtoController = new ProdutoController();

    $produto = new Produto(
        null,
        $_POST["nome"],
        $_POST["descricao"],
        $categoriaController->findById($_POST["categoria_id"]),
        $_POST["preco"]
    );


    if (isset($_GET["id"])) {
        $produto->setId($_GET["id"]);
        $produtoController->update($produto);
        var_dump($produto);
    } else {
        var_dump($produto);

        $produtoController->save($produto);
    }

if(isset($_POST["Salvar"])){
    var_dump($produto);

        $produtoController->save($produto);
}
    header("Location: ?pg=produtos");

    exit();
}

?>

<div class="container mt-2">
    <h1 class="text-center mb-0">Cadastro de Produto</h1>
    <form method="POST">

        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo isset($produto) ? $produto->getNome() : ''; ?>">
        </div>

        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao"><?php echo isset($produto) ? $produto->getDescricao() : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label for="categoria">Categoria</label>

            <select class="form-control" id="categoria" name="categoria_id">

                <?php

                // Percorre o array de categorias
                foreach ($categorias as $categoria):

                    // Define a opção como selecionada se for a categoria do produto
                    $selected = (isset($produto) && $produto->getCategoria()->getId() == $categoria->getId()) ? "selected" : "";

                    // Exibe a opção do select
                    echo "<option value=\"{$categoria->getId()}\" {$selected}>{$categoria->getNome()}</option>";

                endforeach;

                ?>

            </select>
        </div>

        <div class="form-group">
            <label for="preco">Preço</label>
            <input type="text" class="form-control" id="preco" name="preco" value="<?php echo isset($produto) ? $produto->getPreco() : ''; ?>">
        </div>

        <input type="submit" class="btn btn-primary" id="salvar" name="salvar" value="Salvar">
    </form>
</div>
