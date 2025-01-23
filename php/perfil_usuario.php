<?php
session_start();

require "conexao.php";
$conn = new Conexao();

if (!isset($_SESSION['email'])) {
    header("location: ../login.html");
    exit;
}

$email = $_SESSION['email'];

// Fazer logout
if (isset($_GET['sair'])) {
    unset($_SESSION['email']);
    header("location: ../login.html"); // Inserir depois o caminho para a página inicial
    exit;
}

try {
    // Consulta para obter os dados do usuário
    $sql = "SELECT id, nome, email, senha, endereco, telefone, data_nasc
    FROM usuario
    WHERE email = :email";

    $stmt = $conn->conexao->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        // Atribui os valores retornados pelo banco de dados
        $id = $resultado['id'];
        $nome = $resultado['nome'];
        $email = $resultado['email'];
        $senha = $resultado['senha'];
        $endereco = $resultado['endereco'];
        $telefone = $resultado['telefone'];
        $data_nasc = $resultado['data_nasc'];
    } else {
        // Trate o caso de nenhum resultado encontrado
        echo "Nenhum usuário encontrado com o email fornecido.";
        exit;
    }
} catch (Exception $e) {
    exit('Ocorreu uma falha: ' . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-4.3.1.min.css">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css'>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/perfil_usuario.css">
    <!-- Script -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/bootstrap-4.3.1.bundle.min.js.js"></script>

    <title>EncontraPets</title>
    <!--Link para fonte-->
    <link href="https://fonts.cdnfonts.com/css/berlin-sans-fb-demi" rel="stylesheet">
</head>

<body>
    <header id="menu" class="p-3 text-bg-dark">
        <nav class="navbar navbar-light  justify-content-center">
            <a class="navbar-brand" href="busca_cadastrado.php">
                <img src="../images/logo_sem_fundo.png" class="d-inline-block align-top" alt="">
            </a>
        </nav>
        <nav class="navbar navbar-expand-lg navbar-light" id="login_nav">
            <!--<a class="navbar-brand" href="./cadastro_pet.php" id="home">Home</a>-->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-item nav-link active" href="./perfil_usuario.php">Meus Dados<span class="sr-only">(current)</span></a>
                    <a class="nav-item nav-link active" href="./meus_pets.php">Meus Pets<span class="sr-only">(current)</span></a>
                    <a class="nav-item nav-link active" href="./cadastro_pet.php">Cadastrar Pets<span class="sr-only">(current)</span></a>
                    <a class="nav-item nav-link active" href="./busca_cadastrado.php">Buscar Pets<span class="sr-only">(current)</span></a>
                    <form class="form-inline my-2 my-lg-0 login-botao" method="GET" action="">
                        <a href="busca.php?sair=true" class="btn btn-outline-danger my-2 my-sm-0">Sair</a>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <div id="container">

            <div class="content-container">
                <h3><?php echo $nome ?></h3>

                <hr>

                <section>
                <p><strong>Data de Nascimento:</strong> 
                    <?php 
                    $data_formatada = DateTime::createFromFormat('Y-m-d', $data_nasc)->format('d/m/Y'); 
                    echo $data_formatada;
                    ?>
                </p>
                    <p><strong>Email:</strong> <?php echo $email; ?></p>
                    <p><strong>Telefone:</strong> <?php echo $telefone; ?></p>
                    <p><strong>Endereço:</strong> <?php echo $endereco; ?></p>
                </section>

                <div class="item col-md-4 mx-auto d-flex flex-column align-items-center">
                    <a href="edita_dados_user.php?id=<?php echo $resultado['id']; ?>" class="btn" id="editar">Editar</a>
                </div>

                </form>

                <div class="header-container">
                    <div class="deletaConta btnOpenModal">
                        <a href="#" id="deletaConta" data-bs-toggle="modal"
                            data-bs-target="#modalDeletarConta">Deletar Conta</a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal de confirmação de deleção de conta -->
        <div class="modal fade" id="modalDeletarConta" tabindex="-1" aria-labelledby="modalDeletarContaLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDeletarContaLabel">Confirmar Exclusão de Conta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Tem certeza de que deseja deletar sua conta? Esta ação é irreversível.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form id="formDeletarConta" method="POST" action="deleta_conta.php">
                            <input type="hidden" name="id" value="<?php echo $resultado['id']; ?>">
                            <button type="submit" class="btn btn-danger" id="botaoDeletar">Deletar Conta</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <footer class="footer">
        <div class="container">
            <div class="py-3 my-4">
                <ul class="nav justify-content-center border-bottom pb-3 mb-3">
                    
                    <li class="nav-item"><a href="./perfil_usuario.php" class="nav-link px-2 text-body-secondary">Meus Dados</a></li>
                    <li class="nav-item"><a href="./meus_pets.php" class="nav-link px-2 text-body-secondary">Meus Pets</a></li>
                    <li class="nav-item"><a href="cadastro_pet.php" class="nav-link px-2 text-body-secondary">Cadastrar Pets</a></li>
                    <li class="nav-item"><a href="./busca_cadastrado.php" class="nav-link px-2 text-body-secondary">Buscar Pets</a></li>
                </ul>
                <p class="text-center text-body-secondary">© 2024 Company, Inc</p>
            </div>
        </div>
    </footer>

</body>

</html>