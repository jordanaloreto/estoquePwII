<?php

session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ?pg=login"); // Redireciona para a página de login se não estiver logado
    exit(); // Interrompe a execução do script
}
require_once "controllers/ProdutoController.php";
require_once "controllers/EstoqueController.php";

$produtoController = new ProdutoController();
$estoqueController = new EstoqueController();

$produtos = $produtoController->findAll();

$id = isset($_GET['id']) ? $_GET['id'] : null;
$action = isset($_GET['action']) ? $_GET['action'] : null;

$quantidadeAtual = 0;
$produtoSelecionado = '';

if ($id) {
    $estoque = $estoqueController->findById($id);
    $produtoSelecionado = $estoque->getProduto()->getId();
    $quantidadeAtual = $estoque->getQuantidade();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["quantidade"])) {
    $quantidade = $_POST["quantidade"];

    if ($action == 'add') {
        $estoqueController->addQuantidade($id, $quantidade);
        $_SESSION['mensagem'] = "Quantidade adicionada com sucesso!";
    } elseif ($action == 'remove') {
        $estoqueController->removeQuantidade($id, $quantidade);
        $_SESSION['mensagem'] = "Quantidade removida com sucesso!";
    } else {
        $estoque = new Estoque(
            null,
            $produtoController->findById($_POST["produto"]),
            $quantidade
        );
        $estoqueController->save($estoque);
        $_SESSION['mensagem'] = "Estoque cadastrado com sucesso!";
    }

    header("Location: ?pg=estoque");
    exit();
}
?>

<div class="container mt-2">
    <h1 class="text-center mb-0"><?php echo $action == 'add' ? 'Adicionar' : ($action == 'remove' ? 'Remover' : 'Cadastrar'); ?> Quantidade</h1>
    <form method="POST" enctype="multipart/form-data">
        <?php if (!$action): ?>
            <div class="form-group">
                <label for="produto">Produto</label>
                <select class="form-control" id="produto" name="produto">
                    <?php
                    foreach ($produtos as $produto):
                        $selected = $produtoSelecionado == $produto->getId() ? 'selected' : '';
                        echo "<option value=\"{$produto->getId()}\" $selected>{$produto->getNome()}</option>";
                    endforeach;
                    ?>
                </select>
            </div>
        <?php else: ?>
            <input type="hidden" name="produto" value="<?php echo htmlspecialchars($produtoSelecionado); ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="quantidade">Quantidade</label>
            <input type="number" class="form-control" id="quantidade" name="quantidade" value="<?php echo htmlspecialchars($quantidadeAtual); ?>" required>
        </div>

        <input type="submit" class="btn btn-primary" id="salvar" name="salvar" value="Salvar">
    </form>
</div>
