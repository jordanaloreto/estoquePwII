<?php
require_once "models/Estoque.php";
require_once "models/Produto.php";
require_once "models/Conexao.php";
require_once "controllers/HistoricoController.php";
require_once "controllers/UsuarioController.php";  // Certifique-se de ter uma classe Conexao para lidar com a conexão com o banco de dados

class EstoqueController {

    public function findAll() {
        try {
            $conexao = Conexao::getInstance();
            $stmt = $conexao->prepare("SELECT * FROM estoque");
            $stmt->execute();
            $estoques = array();

            while($estoque = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $produto = $this->findProdutoById($estoque["produto"]);
                $estoques[] = new Estoque($estoque["id"], $produto, $estoque["quantidade"],$estoque["created_at"]);
            }

            return $estoques;
        } catch (PDOException $e) {
            echo "Erro ao buscar os estoques: " . $e->getMessage();
            return null;
        }
    }

    public function save(Estoque $estoque) {
        try {
            $conexao = Conexao::getInstance();
            $produto = $estoque->getProduto()->getId();
            $quantidade = $estoque->getQuantidade();
    
            $stmt = $conexao->prepare("INSERT INTO estoque (produto, quantidade) VALUES (:produto, :quantidade)");
            $stmt->bindParam(":produto", $produto);
            $stmt->bindParam(":quantidade", $quantidade);
    
            $stmt->execute();
    
            $lastInsertedId = $conexao->lastInsertId();
    
            $historicoController = new HistoricoController();
            $estoque = $this->findById($lastInsertedId); 
    
            $usuarioController = new UsuarioController();
            $usuario = $usuarioController->findById($_SESSION['id_usuario']);
    
            $historico = new Historico(null, $estoque, "Adicionar", $quantidade, null, $usuario);
            $historicoController->save($historico);
            
            return $this->findById($lastInsertedId);
        } catch (PDOException $e) {
            echo "Erro ao salvar o estoque: " . $e->getMessage();
            return null;
        }
    }
    

    public function findById($id) {
        try {
            $conexao = Conexao::getInstance();

            $stmt = $conexao->prepare("SELECT * FROM estoque WHERE id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                $produto = $this->findProdutoById($resultado["produto"]);
                return new Estoque($resultado["id"], $produto, $resultado["quantidade"],$resultado["created_at"]);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Erro ao buscar o estoque: " . $e->getMessage();
            return null;
        }
    }

    public function findProdutoById($id) {
        try {
            $conexao = Conexao::getInstance();

            $stmt = $conexao->prepare("SELECT p.*, c.nome as categoria_nome FROM produto p INNER JOIN categoria c ON p.categoria = c.id WHERE p.id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                $categoria = new Categoria($resultado["categoria"], $resultado["categoria_nome"]);
                return new Produto($resultado["id"], $resultado["nome"], $resultado["descricao"], $categoria, $resultado["preco"]);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Erro ao buscar o produto: " . $e->getMessage();
            return null;
        }
    }

    public function addQuantidade($id, $quantidade) {
        try {
            $conexao = Conexao::getInstance();
            $stmt = $conexao->prepare("UPDATE estoque SET quantidade = quantidade + :quantidade WHERE id = :id");
            $stmt->bindParam(":quantidade", $quantidade);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $historicoController = new HistoricoController();
            $estoque = $this->findById($id);

            $usuarioController = new UsuarioController();
            $usuario = $usuarioController->findById($_SESSION['id_usuario']);

            $historico = new Historico(null, $estoque, "Adicionar", $quantidade, null, $usuario);
            $historicoController->save($historico);

        } catch (PDOException $e) {
            echo "Erro ao adicionar quantidade: " . $e->getMessage();
        }
    }

    public function removeQuantidade($id, $quantidade) {
        try {
            $conexao = Conexao::getInstance();
            $stmt = $conexao->prepare("UPDATE estoque SET quantidade = quantidade - :quantidade WHERE id = :id AND quantidade >= :quantidade");
            $stmt->bindParam(":quantidade", $quantidade);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $historicoController = new HistoricoController();
            $estoque = $this->findById($id);

            $usuarioController = new UsuarioController();
            $usuario = $usuarioController->findById($_SESSION['id_usuario']);

            $historico = new Historico(null, $estoque, "Remover", $quantidade, null, $usuario);
            $historicoController->save($historico);

        } catch (PDOException $e) {
            echo "Erro ao remover quantidade: " . $e->getMessage();
        }
    }

}
?>
