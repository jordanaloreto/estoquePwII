<?php
session_start();
require_once "controllers/ProdutoController.php";
require_once "controllers/CategoriaController.php";
require_once "controllers/EstoqueController.php";
require_once "models/Categoria.php";
require_once "models/Produto.php";

$estoqueController = new EstoqueController();
$estoques = $estoqueController->findAll();

// Verificar se existe uma mensagem definida na sessão
if (isset($_SESSION['mensagem'])) {
    echo "<script>alert('" . $_SESSION['mensagem'] . "')</script>";
    unset($_SESSION['mensagem']); // Limpar a variável de sessão após exibir o alerta
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Estoque</title>
    <!-- Inclua seus arquivos de estilo e scripts aqui -->
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col">
            <div class="d-flex justify-content-between mb-3">
                <h1 class="text-center mb-0">Lista de Estoque</h1>
                <a href="?pg=form_estoque" class="btn btn-success" role="button">Cadastrar</a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($estoques as $e) : ?>
                        <tr id="row-<?php echo htmlspecialchars($e->getId()); ?>">
                            <td><?php echo htmlspecialchars($e->getId()); ?></td>
                            <td><?php echo htmlspecialchars($e->getProduto()->getNome()); ?></td>
                            <td class="quantidade"><?php echo htmlspecialchars($e->getQuantidade()); ?></td>
                            <td>
                                <a class="btn btn-primary" href="?pg=form_estoque&id=<?php echo $e->getId(); ?>&action=add">
                                    <i class="fas fa-plus"></i></a>
                                <a class="btn btn-danger" href="?pg=form_estoque&id=<?php echo $e->getId(); ?>&action=remove">
                                    <i class="fas fa-minus"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>
</body>
</html>
