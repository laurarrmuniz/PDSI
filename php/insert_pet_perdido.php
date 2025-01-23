<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../login.html");
    exit();
}

$email = $_SESSION['email'];
//echo "Script iniciou<br>";

if (
    isset($_POST["titulo"]) && isset($_POST["local"]) && isset($_FILES["foto"]) && isset($_POST["dia"]) && isset($_POST["horario"])
    && isset($_POST["info"]) && isset($_POST["tipo"]) && isset($_POST["porte"]) && isset($_POST["sexo"])
) {
    require_once "conexao.php";
    require_once "usuarioEntidade.php";

    $titulo = htmlspecialchars($_POST["titulo"]);
    $localizacao = htmlspecialchars($_POST["local"]);
    $data = htmlspecialchars($_POST["dia"]);
    $horario = htmlspecialchars($_POST["horario"]);
    $info = htmlspecialchars($_POST["info"]);
    $tipo = htmlspecialchars($_POST["tipo"]);
    $porte = htmlspecialchars($_POST["porte"]);
    $sexo = htmlspecialchars($_POST["sexo"]);

    // Inicializa o array $fotos como um array vazio
    $fotos = array();

    if (isset($_FILES['foto']) && !empty($_FILES['foto']['name'][0])) {
        for ($i = 0; $i < count($_FILES['foto']['name']); $i++) {
            if ($_FILES['foto']['error'][$i] === UPLOAD_ERR_OK) {
                $nomeArqFoto = strtolower($_FILES['foto']['name'][$i]);
                $caminhoArqFoto = '../images/' . $nomeArqFoto;

                // Verifica se o arquivo foi movido com sucesso
                if (move_uploaded_file($_FILES['foto']['tmp_name'][$i], $caminhoArqFoto)) {
                    // Adiciona o nome do arquivo ao array $fotos
                    $fotos[] = $nomeArqFoto;
                } else {
                    echo "Erro ao mover o arquivo: $nomeArqFoto<br>";
                }
            } else {
                echo "Erro no upload do arquivo: " . $_FILES['foto']['error'][$i] . "<br>";
            }
        }
    } else {
        echo "O campo de arquivo está vazio ou não foi enviado corretamente.";
    }

    try {
        $conn = new Conexao();

        $sql = "INSERT INTO pet_perdido (local, titulo, data, horario, info, tipo, porte, sexo, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $sql2 = "INSERT INTO foto_pets_perdido (id_pet_perdido, nomeArqFoto) VALUES (?, ?)";
        $sql3 = "SELECT id FROM usuario WHERE email = ?";

        $stmt3 = $conn->conexao->prepare($sql3);
        $stmt3->bindParam(1, $email);
        $stmt3->execute();
        $row = $stmt3->fetch();

        if (!$row) {
            die("Erro: Usuário não encontrado.");
        }

        $id_usuario = $row['id'];
        //echo "Email: $email<br>";
        //echo "ID do Usuário: $id_usuario<br>";

        $stmt1 = $conn->conexao->prepare($sql);

        $stmt1->bindParam(1, $localizacao);
        $stmt1->bindParam(2, $titulo);
        $stmt1->bindParam(3, $data);
        $stmt1->bindParam(4, $horario);
        $stmt1->bindParam(5, $info);
        $stmt1->bindParam(6, $tipo);
        $stmt1->bindParam(7, $porte);
        $stmt1->bindParam(8, $sexo);
        $stmt1->bindParam(9, $id_usuario);

        if ($stmt1->execute()) {
            $id_pet_perdido = $conn->conexao->lastInsertId(); //id do último pet_encontrado cadastrado

            foreach ($fotos as $nomeArqFoto) {
                $stmt2 = $conn->conexao->prepare($sql2);
                $stmt2->bindParam(1, $id_pet_perdido);
                $stmt2->bindParam(2, $nomeArqFoto);

                if ($stmt2->execute()) {
                    echo "Imagem associada ao pet com sucesso: $nomeArqFoto<br>";
                } else {
                    echo "Falha na inserção da imagem: $nomeArqFoto<br>";
                }
            }

            echo "Cadastro realizado com sucesso!<br>";
            header("location: ./cadastro_sucesso.php?titulo=".urlencode($titulo));
            exit();
        } else {
            echo "Falha na primeira inserção<br>";
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
} else {
    echo "Dados incompletos.<br>";
}
