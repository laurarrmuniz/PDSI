<?php
require_once "conexao.php";

try {
    $conn = new Conexao();
} catch (PDOException $e) {
    http_response_code(500);
    echo "Erro na conexão com o banco de dados: " . $e->getMessage();
    exit;
}

// Verificar se a requisição é do tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar o ID enviado no corpo da requisição
    if (isset($_POST["id"])) {
        $id_usuario = $_POST["id"];

        try {
            // Deletar o usuário pelo ID
            $sql = "DELETE FROM usuario WHERE id = ?";
            $stmt = $conn->conexao->prepare($sql);
            $stmt->bindParam(1, $id_usuario, PDO::PARAM_INT);

            if ($stmt->execute()) {
                http_response_code(200);
                // Redirecionar para a página desejada (por exemplo, página de login)
                header("Location: ../php/busca.php"); // Ajuste o caminho conforme necessário
                exit();
            } else {
                http_response_code(500);
                echo "Erro ao excluir o usuário. Verifique se o ID é válido: " . $id_usuario; 
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo "Erro na execução da consulta SQL: " . $e->getMessage();
        } catch (Exception $e) {
            http_response_code(500);
            echo "Falha inesperada: " . $e->getMessage();
        }
    } else {
        http_response_code(400);
        echo "O ID do usuário não foi fornecido.";
    }
} else {
    http_response_code(405);
    echo "Método não permitido.";
}