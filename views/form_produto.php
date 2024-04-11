<?php
require_once "../contollers/ProdutoController.php"; 
require_once "../controllers/CategoriaController.php"; 

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
        $categoriaController->findById($_POST["categoria_id"]),
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
                <?php foreach ($categorias as $categoria) { ?>
                    <option value="<?php echo $categoria->getId(); ?>" <?php echo (isset($produto) && $produto->getCategoria()->getId() == $categoria->getId()) ? 'selected' : ''; ?>><?php echo $categoria->getNome(); ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label for="preco">Preço</label>
            <input type="text" class="form-control" id="preco" name="preco" value="<?php echo isset($produto) ? $produto->getPreco() : ''; ?>">
        </div>

        <input type="submit" class="btn btn-primary" id="salvar" name="salvar" value="Salvar">
    </form>
</div>
