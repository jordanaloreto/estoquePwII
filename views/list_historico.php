<?php

session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ?pg=login"); // Redireciona para a página de login se não estiver logado
    exit(); // Interrompe a execução do script
}
require_once "controllers/ProdutoController.php";
require_once "controllers/CategoriaController.php";
require_once "controllers/EstoqueController.php";
require_once "controllers/HistoricoController.php";
require_once "controllers/UsuarioController.php";
require_once "models/Categoria.php";
require_once "models/Produto.php";
require_once "models/Estoque.php";
require_once "models/Historico.php";
require_once "models/Usuario.php";

$historicoController = new HistoricoController();
$historicos = $historicoController->findAll();

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
    <title>Histórico de Movimentações</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Histórico de Movimentações</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Ação</th>
                    <th>Data</th>
                    <th>Usuário</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $historicoController = new HistoricoController();
                $historicos = $historicoController->findAll();

                if ($historicos) {
                    foreach ($historicos as $historico) {
                        echo "<tr>";
                        echo "<td>" . $historico->getId() . "</td>";
                        echo "<td>" . $historico->getEstoque()->getProduto()->getNome() . "</td>";
                        echo "<td>" . $historico->getQuantidade() . "</td>";
                        echo "<td>" . $historico->getAcao() . "</td>";
                        echo "<td>" . date('d/m/Y H:i:s', strtotime($historico->getData())) . "</td>";
                        echo "<td>" . $historico->getUsuario()->getNome() . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Nenhum registro encontrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

