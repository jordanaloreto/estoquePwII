<?php
require_once "models/Produto.php";
class ProdutoController {

    public function findAll() {
        try {
            $conexao = Conexao::getInstance();

            $stmt = $conexao->prepare("SELECT * FROM produto");
            $stmt->execute();
            $produtos = array();

            while($produto = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $categoria = new Categoria($produto["categoria_id"], $produto["categoria_nome"]);
                $produtos[] = new Produto($produto["id"], $produto["nome"], $produto["descricao"], $categoria, $produto["preco"]);
            }
            
            return $produtos;
        } catch (PDOException $e) {
            echo "Erro ao buscar os produtos: " . $e->getMessage();
            return null;
        }
    }

    public function save(Produto $produto) {
        try {
            $conexao = Conexao::getInstance();
            $nome = $produto->getNome();
            $descricao = $produto->getDescricao();
            $categoria = $produto->getCategoria()->getId();
            $preco = $produto->getPreco();
            
            $stmt = $conexao->prepare("INSERT INTO produto (nome, descricao, categoria_id, preco) VALUES (:nome, :descricao, :categoria_id, :preco)");
            $stmt->bindParam(":nome", $nome);
            $stmt->bindParam(":descricao", $descricao);
            $stmt->bindParam(":categoria_id", $categoria);
            $stmt->bindParam(":preco", $preco);

            $stmt->execute();

            $lastInsertedId = $conexao->lastInsertId();
            $produto = $this->findById($lastInsertedId);

            return $produto;
        } catch (PDOException $e) {
            echo "Erro ao salvar o produto: " . $e->getMessage();
            return null;
        }
    }

    public function update(Produto $produto) {
        try {
            $conexao = Conexao::getInstance();
            $id = $produto->getId();
            $nome = $produto->getNome();
            $descricao = $produto->getDescricao();
            $categoria = $produto->getCategoria()->getId();
            $preco = $produto->getPreco();
            
            $stmt = $conexao->prepare("UPDATE produto SET nome = :nome, descricao = :descricao, categoria_id = :categoria_id, preco = :preco WHERE id = :id");
            $stmt->bindParam(":nome", $nome);
            $stmt->bindParam(":descricao", $descricao);
            $stmt->bindParam(":categoria_id", $categoria);
            $stmt->bindParam(":preco", $preco);
            $stmt->bindParam(":id", $id);

            $stmt->execute();

            $produto = $this->findById($id);

            return $produto;
        } catch (PDOException $e) {
            echo "Erro ao atualizar o produto: " . $e->getMessage();
            return null;
        }
    }

    public function delete($id) {
        try {
            $conexao = Conexao::getInstance();

            $stmt = $conexao->prepare("DELETE FROM produto WHERE id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Erro ao excluir o produto: " . $e->getMessage();
            return false;
        }
    }

    public function findById($id) {
        try {
            $conexao = Conexao::getInstance();

            $stmt = $conexao->prepare("SELECT * FROM produto WHERE id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                $categoria = new Categoria($resultado["categoria_id"], $resultado["categoria_nome"]);
                return new Produto($resultado["id"], $resultado["nome"], $resultado["descricao"], $categoria, $resultado["preco"]);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Erro ao buscar o produto: " . $e->getMessage();
            return null;
        }
    }
}

?>
