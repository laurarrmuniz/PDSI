<?php
session_start();
$response = ["success" => false, "detail" => ""];

if (isset($_POST["email"]) && isset($_POST["senha"])) {
    require_once "conexao.php";
    require_once "usuarioEntidade.php";

    try {
        $conn = new Conexao();
    } catch (PDOException $e) {
        $response["detail"] = "Erro na conexão com o banco de dados: " . $e->getMessage();
        echo json_encode($response);
        exit;
    }

    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $sql = "SELECT senha FROM usuario WHERE email = ?";
    $stmt = $conn->conexao->prepare($sql);
    $stmt->bindParam(1, $email);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $senhaHashBD = $row['senha'];

        if (password_verify($senha, $senhaHashBD)) {
            $_SESSION["login"] = "1";
            $_SESSION["email"] = $email;
            
            $response["success"] = true;
            $response["detail"] = "./php/cadastro_pet.php";
        } else {
            $response["detail"] = "Senha incorreta";
        }
    } else {
        $response["detail"] = "E-mail não encontrado";
    }
} else {
    $response["detail"] = "Dados incompletos";
}

header('Content-Type: application/json');
echo json_encode($response);
?>