<?php
require_once "../controllers/UsuarioController.php";

session_start();

if (isset($_POST["login"]) && isset($_POST["senha"])) {
    $usuarioController = new UsuarioController();
    $usuarioController->login($_POST["login"], $_POST["senha"]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form method="POST">
        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">User</label>
        </div>
        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Email address</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
            <label for="floatingPassword">Password</label>
        </div>
    </form>
    <?php

    if(isset($_SESSION["mensagem"])){
    ?>
        <div class="alert alert-warning" role="allert">
            <strong>ERRO:</strong>
            <?php
            echo $_SESSION["mensagem"];
            unset ($_SESSION["mensagem"]);
            ?>
        </div>
    <?php } ?>
    <button type="submit" class="btn btn-primary btn-block">Login</button>


</body>

</html>