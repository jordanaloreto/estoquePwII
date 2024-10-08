<?php

session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ?pg=login"); // Redireciona para a página de login se não estiver logado
    exit(); // Interrompe a execução do script
}
require_once "controllers/ProdutoController.php";
require_once "controllers/CategoriaController.php";

// Inicia a sessão
if (isset($_GET["id"])) {
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
        $categoriaController->findById($_POST["categoria"]),
        $_POST["preco"]
    );

    if (isset($_GET["id"])) {
        $produto->setId($_GET["id"]);
        $produtoController->update($produto);
    } else {
        $produtoController->save($produto);
    }

    header("Location: ?pg=produtos");
    exit();
}
?>

<div class="container mt-2">
    <h1 class="text-center mb-0">Cadastro de Produto</h1>
    <form method="POST" enctype="multipart/form-data">

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
            <select class="form-control" id="categoria" name="categoria">
                <?php
                foreach ($categorias as $categoria):
                    $selected = (isset($produto) && $produto->getCategoria()->getId() == $categoria->getId()) ? "selected" : "";
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
