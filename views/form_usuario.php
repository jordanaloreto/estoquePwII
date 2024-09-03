<?php
require_once "controllers/UsuarioController.php";
session_start();

if (isset($_POST["login"]) && isset($_POST["senha"]) && isset($_POST["nome"])) {
    $usuarioController = new UsuarioController();
    $usuario = new Usuario(null, $_POST["nome"], $_POST["login"], $_POST["senha"]);
    $usuarioController->save($usuario);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
</head>
<body>
    <div class="container mt-5">
        <form method="POST">
            <div class="mb-3 row">
                <label for="exampleFormControlInput1" class="col-sm-2 col-form-label">Email address</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="exampleFormControlInput1" name="login" placeholder="name@example.com" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="username" class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="addon-wrapping">@</span>
                        </div>
                        <input type="text" class="form-control" id="username" name="nome" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping" required>
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputPassword" class="col-sm-2 col-form-label">Password (senha)</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="inputPassword" name="senha" placeholder="Password" required>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </div>
            </div>
        </form>
    </div>

    <?php
    if(isset($_SESSION["mensagem"])){
    ?>
        <div class="alert alert-warning" role="alert">
            <strong>ERRO:</strong>
            <?php
            echo $_SESSION["mensagem"];
            unset ($_SESSION["mensagem"]);
            ?>
        </div>
    <?php } ?>
</body>
</html>
