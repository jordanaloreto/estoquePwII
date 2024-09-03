<?php

require_once "models/Conexao.php";
require_once "models/Usuario.php";

class UsuarioController{
    public function login($login, $senha){
        try {
            $conexao = Conexao::getInstance();
            $stmt = $conexao->prepare("SELECT * FROM usuario WHERE login = :login");
            $stmt->bindParam(":login", $login);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($resultado) {
                $usuario = new Usuario(
                    $resultado["id"],
                    $resultado["nome"],
                    $resultado["login"],
                    $resultado["senha"]
                );

                // Verifica a senha usando password_verify
                if (password_verify($senha, $usuario->getSenha())) {
                    $_SESSION['id_usuario'] = $usuario->getId();
                    $_SESSION['nome_usuario'] = $usuario->getNome();
                    $_SESSION['login_usuario'] = $usuario->getLogin();
                    header("Location: ?pg=categorias");
                } else {
                    $_SESSION['mensagem'] = 'Senha incorreta';
                }
            } else {
                $_SESSION['mensagem'] = 'Usuário não encontrado';
            }
        } catch (PDOException $e) {
            echo "Erro ao buscar o usuário: " . $e->getMessage();
        }
    }

    public function save(Usuario $usuario) {
        try {
            $conexao = Conexao::getInstance();
            $nome = $usuario->getNome();
            $login = $usuario->getLogin();
            // Criptografa a senha antes de salvar
            $senha = password_hash($usuario->getSenha(), PASSWORD_BCRYPT);

            $stmt = $conexao->prepare("INSERT INTO usuario (nome, login, senha) VALUES (:nome, :login, :senha)");
            $stmt->bindParam(":nome", $nome);
            $stmt->bindParam(":login", $login);
            $stmt->bindParam(":senha", $senha);

            $stmt->execute();

            $lastInsertedId = $conexao->lastInsertId();
            return $this->findById($lastInsertedId);
        } catch (PDOException $e) {
            echo "Erro ao salvar o usuário: " . $e->getMessage();
            return null;
        }
    }

    public function findById($id) {
        try {
            $conexao = Conexao::getInstance();

            $stmt = $conexao->prepare("SELECT * FROM usuario WHERE id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                return new Usuario(
                    $resultado["id"],
                    $resultado["nome"],
                    $resultado["login"],
                    $resultado["senha"]
                );
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Erro ao buscar o usuário: " . $e->getMessage();
            return null;
        }
    }
}
?>
