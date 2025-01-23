<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../login.html");
    exit();
}
//Recuperando o título do cadastro do pet
$titulo = $_GET['titulo'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/cadastroSucesso.css">
    <title>Encontrapets</title>
</head>

<body>

    <main>
        <div class="logo">
            <a>
                <img src="../images/logo_sem_fundo.png" alt="logo">
            </a>
        </div>

        <h2>Cadastro realizado com sucesso!</h2>

        <p>O pet "<?= $titulo ?>" foi cadastrado com sucesso.</p>

        <div id="button">
            <a href="./meus_pets.php"><button>Meus Pets</button></a>
        </div>

    </main>

</body>

</html>