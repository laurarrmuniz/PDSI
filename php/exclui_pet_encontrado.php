<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header("Location: ../login.html");
    exit();
}

// Inclui a classe de conexão
require_once "../php/conexao.php";

// Cria uma nova conexão
try {
    $conn = new Conexao();
} catch (PDOException $e) {
    echo "Erro na conexão com o banco de dados: " . $e->getMessage();
    exit();
}

// Verifica se o ID do pet foi fornecido
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    try {
        // Começa a transação para garantir integridade dos dados
        $conn->conexao->beginTransaction();
        
        // Exclui as fotos associadas ao pet
        $sqlFotos = "DELETE FROM foto_pets_encontrado WHERE id_pet_encontrado = ?";
        $stmtFotos = $conn->conexao->prepare($sqlFotos);
        $stmtFotos->bindParam(1, $id);
        $stmtFotos->execute();
        
        // Exclui o pet
        $sqlPet = "DELETE FROM pet_encontrado WHERE id = ?";
        $stmtPet = $conn->conexao->prepare($sqlPet);
        $stmtPet->bindParam(1, $id);

        if ($stmtPet->execute()) {
            // Comita a transação
            $conn->conexao->commit();
            // Redireciona para a página de lista após a exclusão
            header("Location: meus_pets.php");
            exit();
        } else {
            // Desfaz a transação em caso de erro
            $conn->conexao->rollBack();
            echo "Erro ao excluir o pet.";
        }
    } catch (PDOException $e) {
        // Desfaz a transação e exibe o erro
        $conn->conexao->rollBack();
        echo "Erro na execução da consulta SQL: " . $e->getMessage();
    } catch (Exception $e) {
        // Desfaz a transação e exibe o erro
        $conn->conexao->rollBack();
        echo "Falha inesperada: " . $e->getMessage();
    }
} else {
    echo "O parâmetro ID não foi fornecido.";
}
?>
