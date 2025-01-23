<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../login.html");
    exit();
}

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

        <h2>Edição realizada com sucesso!</h2>
        <div class="centralizado">
            <p>Seus dados foram alterados com sucesso.</p>
        </div>

        <div id="button">
            <a href="./perfil_usuario.php"><button>Meus Dados</button></a>
        </div>

    </main>

</body>

</html>