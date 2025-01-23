<?php
session_start();
if (isset($_POST["nome"]) && isset($_POST["telefone"]) && isset($_POST["email"]) && isset($_POST["endereco"]) 
    && isset($_POST["datanasc"]) && isset($_POST["senha"])) {
    require_once "conexao.php";
    require_once "usuarioEntidade.php";
    
    $senha = $_POST["senha"];
    $nome = $_POST["nome"];
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];
    $endereco = $_POST["endereco"];
    $datanasc = $_POST["datanasc"];

    $conn = new Conexao();

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Verificar se o email já está cadastrado
    $sql = "SELECT COUNT(*) FROM usuario WHERE email = ?";
    $stmt = $conn->conexao->prepare($sql);
    $stmt->execute([$email]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        
        header("location: cadastro_email_existente.php");
        exit();
    }

    // Inserir o novo usuário
    $sql = "INSERT INTO usuario (nome, telefone, email, senha, endereco, data_nasc) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->conexao->prepare($sql);
    $stmt->bindParam(1, $nome);
    $stmt->bindParam(2, $telefone);
    $stmt->bindParam(3, $email);
    $stmt->bindParam(4, $senhaHash);
    $stmt->bindParam(5, $endereco);
    $stmt->bindParam(6, $datanasc);

    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso!";
        // Para testes, comentamos o redirecionamento:
         header("location: ../login.html");
         exit();
    } else {
        echo "Erro ao tentar efetivar cadastro.";
    }
}
?>
