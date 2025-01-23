<?php
require "conexao.php";

// Cria a instância da classe Conexao e obtém a conexão PDO
$conn = new Conexao();
$pdo = $conn->conexao;

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém o ID do pet e outros dados do formulário
    if (isset($_POST["id"]) && !empty($_POST["id"])) {
        $pet_id = $_POST["id"];
        $titulo = $_POST["titulo"];
        $local = $_POST["local"];
        $dia = $_POST["dia"];
        $horario = $_POST["horario"];
        $info = $_POST["info"];
        $tipo = $_POST["tipo"];
        $porte = $_POST["porte"];
        $sexo = $_POST["sexo"];

        try {
            // Atualiza os detalhes do pet
            $stmt = $pdo->prepare("UPDATE pet_perdido SET titulo = ?, local = ?, data = ?, horario = ?, info = ?, tipo = ?, porte = ?, sexo = ? WHERE id = ?");
            $stmt->execute([$titulo, $local, $dia, $horario, $info, $tipo, $porte, $sexo, $pet_id]);

            // Se houver fotos a serem atualizadas
            if (!empty($_FILES['foto']['name'][0])) {
                foreach ($_FILES['foto']['tmp_name'] as $key => $tmp_name) {
                    $foto_name = $_FILES['foto']['name'][$key];
                    $foto_tmp = $_FILES['foto']['tmp_name'][$key];
                    $foto_path = "../images/" . basename($foto_name);

                    // Move o arquivo para o diretório de imagens
                    move_uploaded_file($foto_tmp, $foto_path);

                    // Insere a nova foto no banco de dados
                    $stmt = $pdo->prepare("INSERT INTO foto_pets_perdido (id_pet_perdido, nomeArqFoto) VALUES (?, ?)");
                    $stmt->execute([$pet_id, $foto_name]);
                }
            }

            echo "Edição realizada com sucesso!<br>";
            header("location: ./edicao_sucesso.php?titulo=".urlencode($titulo));
        } catch (PDOException $e) {
            echo 'Erro: ' . $e->getMessage();
        }
    } else {
        echo "ID do pet não fornecido.";
    }

    // Fecha a conexão
    $conn->fecharConexao();
} else {
    echo "Método de requisição inválido.";
}
?>
