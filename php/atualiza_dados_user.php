<?php
require "conexao.php";

// Cria a instância da classe Conexao e obtém a conexão PDO
$conn = new Conexao();
$pdo = $conn->conexao;

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém o ID do usuário e outros dados do formulário
    if (isset($_POST["id"]) && !empty($_POST["id"])) {
        $usuario_id = $_POST["id"];
        $nome = $_POST["nome"];
        $email = $_POST["email"];
        $telefone = $_POST["telefone"];
        $endereco = $_POST["endereco"];
        $data_nasc = $_POST["data_nasc"];
        
        // Inicializa a query SQL base
        $sql = "UPDATE usuario SET nome = ?, email = ?, telefone = ?, endereco = ?, data_nasc = ?";

        // Verifica se a senha foi fornecida
        if (!empty($_POST["senha"])) {
            // Adiciona a senha criptografada à query SQL se ela for fornecida
            $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
            $sql .= ", senha = ?";
        }

        // Finaliza a query SQL
        $sql .= " WHERE id = ?";

        try {
            // Prepara a query com base no que foi fornecido
            $stmt = $pdo->prepare($sql);
            
            if (!empty($_POST["senha"])) {
                // Executa a query incluindo a senha
                $stmt->execute([$nome, $email, $telefone, $endereco, $data_nasc, $senha, $usuario_id]);
            } else {
                // Executa a query sem alterar a senha
                $stmt->execute([$nome, $email, $telefone, $endereco, $data_nasc, $usuario_id]);
            }

            echo "Edição realizada com sucesso!<br>";
            header("location: ./edicao_user_sucesso.php");
        } catch (PDOException $e) {
            echo 'Erro: ' . $e->getMessage();
        }
    } else {
        echo "ID do usuário não fornecido.";
    }

    // Fecha a conexão
    $conn->fecharConexao();
} else {
    echo "Método de requisição inválido.";
}
?>
