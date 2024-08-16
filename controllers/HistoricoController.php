<?php
require_once "models/Historico.php";
require_once "models/Estoque.php";
require_once "models/Usuario.php";
require_once "models/Conexao.php";

class HistoricoController {

    public function findAll() {
        try {
            $conexao = Conexao::getInstance();
            $stmt = $conexao->prepare("SELECT * FROM historico");
            $stmt->execute();
            $historicos = array();

            while($historico = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $estoque = $this->findEstoqueById($historico["estoque"]);
                $usuario = $this->findUsuarioById($historico["usuario"]);
                $historicos[] = new Historico(
                    $historico["id"], 
                    $estoque, 
                    $historico["acao"], 
                    $historico["quantidade"], 
                    $historico["data"], 
                    $usuario
                );
            }

            return $historicos;
        } catch (PDOException $e) {
            echo "Erro ao buscar o histórico: " . $e->getMessage();
            return null;
        }
    }

    public function save(Historico $historico) {
        try {
            $conexao = Conexao::getInstance();
            $estoque = $historico->getEstoque()->getId();
            $usuario = $historico->getUsuario()->getId();
            $acao = $historico->getAcao();
            $quantidade = $historico->getQuantidade();
            $data = $historico->getData();

            $stmt = $conexao->prepare("INSERT INTO historico (estoque, usuario, acao, quantidade, data) VALUES (:estoque, :usuario, :acao, :quantidade, :data)");
            $stmt->bindParam(":estoque", $estoque);
            $stmt->bindParam(":usuario", $usuario);
            $stmt->bindParam(":acao", $acao);
            $stmt->bindParam(":quantidade", $quantidade);
            $stmt->bindParam(":data", $data);

            $stmt->execute();

            $lastInsertedId = $conexao->lastInsertId();
            return $this->findById($lastInsertedId);
        } catch (PDOException $e) {
            echo "Erro ao salvar o histórico: " . $e->getMessage();
            return null;
        }
    }

    public function findById($id) {
        try {
            $conexao = Conexao::getInstance();

            $stmt = $conexao->prepare("SELECT * FROM historico WHERE id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                $estoque = $this->findEstoqueById($resultado["estoque"]);
                $usuario = $this->findUsuarioById($resultado["usuario"]);
                return new Historico(
                    $resultado["id"], 
                    $estoque, 
                    $resultado["acao"], 
                    $resultado["quantidade"], 
                    $resultado["data"], 
                    $usuario
                );
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Erro ao buscar o histórico: " . $e->getMessage();
            return null;
        }
    }

    private function findEstoqueById($id) {
        try {
            $conexao = Conexao::getInstance();
    
            $stmt = $conexao->prepare(
                "SELECT e.*, h.id AS historico_id
                 FROM estoque e 
                 INNER JOIN historico h ON e.id = h.estoque
                 WHERE e.id = :id"
            );
            $stmt->bindParam(":id", $id);
            $stmt->execute();
    
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($resultado) {
                // Usa o findProdutoById para buscar o produto relacionado ao estoque
                $produto = $this->findProdutoById($resultado["produto"]);
                return new Estoque($resultado["id"], $produto, $resultado["quantidade"], $resultado["created_at"]);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Erro ao buscar o estoque: " . $e->getMessage();
            return null;
        }
    }
    
    private function findUsuarioById($id) {
        try {
            $conexao = Conexao::getInstance();
    
            $stmt = $conexao->prepare(
                "SELECT u.*, h.id AS historico_id
                 FROM usuario u
                 INNER JOIN historico h ON u.id = h.usuario
                 WHERE u.id = :id"
            );
            $stmt->bindParam(":id", $id);
            $stmt->execute();
    
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($resultado) {
                return new Usuario($resultado["id"], $resultado["nome"], $resultado["login"], $resultado["senha"]);
            } else {
                return null;
            }
        } catch (PDOException $e) {
            echo "Erro ao buscar o usuário: " . $e->getMessage();
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

}
?>
