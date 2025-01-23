<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header("location: ../login.html");
    exit();
}

// Fazer logout
if (isset($_GET['sair'])) {
    unset($_SESSION['email']);
    header("location: ../cadastroUsuario.html");
    exit();
}

// Verifica se o ID do usuário foi fornecido
if (isset($_GET['id'])) {
    // Armazena o ID do usuário para edição
    $_SESSION['edit_id'] = $_GET['id'];
    $usuario_id = $_GET['id'];
} else {
    echo "ID não fornecido.";
    exit();
}

// Inclui a classe de conexão
require "../php/conexao.php";

// Cria uma nova conexão
$conn = new Conexao();
$pdo = $conn->conexao;

// Busca os dados do usuário
try {
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo "Usuário não encontrado.";
        exit();
    }

} catch (PDOException $e) {
    echo 'Erro: ' . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-4.3.1.min.css">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css'>
 
    <link rel="stylesheet" href="../css/edita_dados_user.css">
    <link rel="stylesheet" href="../css/style.css">
    <!-- Script -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/bootstrap-4.3.1.bundle.min.js.js"></script>

     <!--Link para fonte-->
    <link href="https://fonts.cdnfonts.com/css/berlin-sans-fb-demi" rel="stylesheet">

    <title>Editar Dados - EncontraPets</title>
</head>

<body>
    <header id="menu" class="p-3 text-bg-dark">
        <nav class="navbar navbar-light justify-content-center">
            <a class="navbar-brand" href="busca_cadastrado.php">
                <img src="../images/logo_sem_fundo.png" class="d-inline-block align-top" alt="">
            </a>
        </nav>
        <nav class="navbar navbar-expand-lg navbar-light" id="login_nav">
            <!--<a class="navbar-brand" href="#">Home</a>-->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-item nav-link active" href="./perfil_usuario.php">Meus Dados<span class="sr-only">(current)</span></a>
                    <a class="nav-item nav-link active" href="./meus_pets.php">Meus Pets<span class="sr-only">(current)</span></a>
                    <a class="nav-item nav-link active" href="./cadastro_pet.php">Cadastrar Pets<span class="sr-only">(current)</span></a>
                    <a class="nav-item nav-link active" href="busca_cadastrado.php">Buscar Pets<span class="sr-only">(current)</span></a>
                    <form class="form-inline my-2 my-lg-0 login-botao" method="GET">
                        <a href="busca.php?sair=true" class="btn btn-outline-danger my-2 my-sm-0">Sair</a>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <main>

                <!-- Modal de aviso de campos obrigatórios -->
                <div class="modal fade" id="modalCamposObrigatorios" tabindex="-1" aria-labelledby="modalCamposObrigatoriosLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCamposObrigatoriosLabel">Campos Obrigatórios</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Por favor, preencha todos os campos obrigatórios antes de enviar o formulário.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <form name="formEditaDadosUser" id="editaDadosUser" enctype="multipart/form-data" action="atualiza_dados_user.php" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($usuario['id']); ?>">

                <div class="container container-flex">
                    <div class="item">
                        <h3>Editar Dados do Usuário</h3>
                        <h8>(*) Campos Obrigatórios</h8>
                    </div>
                </div>

                <div class="container container-flex">
                    <!-- Nome -->
                    <div class="item col-md-3">
                        <label for="nome" class="form-label required">Nome <span class="asterisco">*</span></label>
                        <input type="text" id="nome" name="nome" class="form-control required" maxlength="50" value="<?php echo htmlspecialchars($usuario['nome']); ?>">
                        <div class="alert alert-danger d-none" id="alert-nome"></div>
                    </div>

                    <!-- Email -->
                    <div class="item col-md-3">
                        <label for="email" class="form-label required">Email <span class="asterisco">*</span></label>
                        <input type="email" id="email" name="email" class="form-control required" maxlength="50" value="<?php echo htmlspecialchars($usuario['email']); ?>">
                        <div class="alert alert-danger d-none" id="alert-email"></div>
                    </div>

                    <!-- Telefone -->
                    <div class="item col-md-3">
                        <label for="telefone" class="form-label required">Telefone <span class="asterisco">*</span></label>
                        <input type="text" id="telefone" name="telefone" class="form-control required" maxlength="20" value="<?php echo htmlspecialchars($usuario['telefone']); ?>">
                        <div class="alert alert-danger d-none" id="alert-telefone"></div>
                    </div>

                    <!-- Endereço -->
                    <div class="item col-md-3">
                        <label for="endereco" class="form-label required">Endereço <span class="asterisco">*</span></label>
                        <input type="text" id="endereco" name="endereco" class="form-control required" maxlength="350" value="<?php echo htmlspecialchars($usuario['endereco']); ?>">
                        <div class="alert alert-danger d-none" id="alert-endereco"></div>
                    </div>

                    <!-- Data de Nascimento -->
                    <div class="item col-md-3">
                        <label for="data_nasc" class="form-label required">Data de Nascimento <span class="asterisco">*</span></label>
                        <input type="date" id="data_nasc" name="data_nasc" class="form-control required" value="<?php echo htmlspecialchars($usuario['data_nasc']); ?>">
                        <div class="alert alert-danger d-none" id="alert-data_nasc"></div>
                    </div>

                    <!-- senha -->
                    <div class="item col-md-3">
                        <label for="senha" class="form-label">Nova Senha</label>
                        <input type="password" id="senha" name="senha" class="form-control" value="">
                    </div>
                    
                    <!-- Botões -->
                    <div class="item col-md-4 mt-5">
                        <button type="submit" class="btn botao">Salvar Alterações</button>
                        <a href="perfil_usuario.php" class="cancelar-link">Cancelar</a>
                    </div>
                    
 
                </div>
            </form>

 

        </div>


    <footer class="footer">
        <div class="container">
            <div class="py-3 my-4">
                <ul class="nav justify-content-center border-bottom pb-3 mb-3">
                    
                    <li class="nav-item"><a href="./perfil_usuario.php" class="nav-link px-2 text-body-secondary">Meus Dados</a></li>
                    <li class="nav-item"><a href="./meus_pets.php" class="nav-link px-2 text-body-secondary">Meus Pets</a></li>
                    <li class="nav-item"><a href="cadastro_pet.php" class="nav-link px-2 text-body-secondary">Cadastrar Pets</a></li>
                    <li class="nav-item"><a href="busca_cadastrado.php" class="nav-link px-2 text-body-secondary">Buscar Pets</a></li>
                </ul>
                <p class="text-center text-body-secondary">© 2024 Company, Inc</p>
            </div>
        </div>
    </footer>

</body>

<!-- Scripts adicionais -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>


<script src="../js/validacao_campos_obrigatorios_dados_user.js"></script>

</html>
