<?php
session_start();

if (!isset($_SESSION['email']))
    header("location: ../login.html");

//Fazer logout
if (isset($_GET['sair'])) {
    unset($_SESSION['email']);
    header("location: ../login.html");
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
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cadastrar_pets.css">
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
                <img src="../images/logo_sem_fundo.png" class="d-inline-block align-top"
                    alt="">
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
                    <form class="form-inline my-2 my-lg-0 login-botao" method="GET">
                        <a href="busca.php?sair=true" class="btn btn-outline-danger my-2 my-sm-0">Sair</a>
                    </form>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <!-- Modal Bootstrap para falta de imagem-->
        <div class="modal fade" id="modalSemImagem" tabindex="-1" aria-labelledby="modalSemImagemLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalSemImagemLabel">Nenhuma Imagem Selecionada</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Por favor, selecione uma imagem antes de enviar o formulário.
                        Formatos permitidos: png, jpeg, jpg
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

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


        <form name="formCadastroPetEncontrado" id="formCadastroPetEncontrado" action="./insert_pet_encontrado.php" method="POST" enctype="multipart/form-data">
            <!--Primeiro container com foto e h3-->
            <div class="container container-flex">
                <!--Primeiro item-->
                <div class="item">

                    <img src="../images/cachorro.png" id="image" width="120" height="120" alt="Clique para trocar a imagem">
                    <label for="fileInput" id="fileLabel" class="form-label">Escolha uma foto <span class="required"></span></label>
                    <!-- Input de arquivo escondido -->
                    <input type="file" id="fileInput" name="foto[]" accept=".png, .jpg, .jpeg">
                </div>
                <!--Segundo item-->
                <div class="item ">
                    <h3>Cadastro Pet Encontrado</h3>
                </div>
            </div>

            <!--Segundo container com forms-->
            <div class="container container-flex">
                <!--Primeiro item-->
                <div class="item col-md-3">
                    <label for="titulo" class="form-label">Título da Postagem *</label>
                    <input type="text" id="titulo" name="titulo" autocomplete="off" class="form-control" maxlength="100">
                    <div class="alert alert-danger d-none" id="alert-nome"></div>
                </div>
                <!--Segundo item-->
                <div class="item col-md-3">
                    <label for="local" class="form-label">Local onde foi Encontrado *</label>
                    <input type="text" id="local" name="local" autocomplete="off" class="form-control" maxlength="100">
                    <div class="alert alert-danger d-none" id="alert-local"></div>
                </div>
                <!--Terceiro item-->
                <div class="item col-md-3">
                    <label for="dia" class="form-label">Dia que foi Encontrado *</label>
                    <input type="date" id="dia" name="dia" autocomplete="off" class="form-control" maxlength="10">
                    <div id="errorDia" class="error-message d-none">A data não pode ser no futuro.</div> <!-- Mensagem de erro -->
                    <div class="alert alert-danger d-none" id="alert-dia"></div>
                </div>
                <!--Quarto item-->
                <div class="item col-md-3">
                    <label for="horario" class="form-label">Horário que foi Encontrado</label>
                    <input type="text" id="horario" name="horario" autocomplete="off" class="form-control" maxlength="20" placeholder="16:00, de manhã, fim da tarde...">
                    <div class="alert alert-danger d-none" id="alert-horario"></div>
                </div>
                <!--Quinto item-->
                <div class="item col-md-3">
                    <label for="info" class="form-label">Mais informações relevantes</label>
                    <input type="text" id="info" name="info" autocomplete="off" class="form-control" maxlength="125" placeholder="nome, cor, raça, coleira...">
                    <h8>Máximo de 125 caracteres</h8>
                    <div class="alert alert-danger d-none" id="alert-info"></div>
                </div>
                <!--Sexto item-->
                <div class="item col-md-3">
                    <fieldset>
                        <legend class="campo_radio">Tipo <span class="required">*</span></legend>
                        <div>
                            <input type="radio" id="cao" name="tipo" value="Cão">
                            <label for="cao">Cão</label>
                        </div>
                        <div>
                            <input type="radio" id="gato" name="tipo" value="Gato">
                            <label for="gato">Gato</label>
                        </div>
                        <div>
                            <input type="radio" id="outro" name="tipo" value="Outro">
                            <label for="outro">Outro</label>
                        </div>
                    </fieldset>
                </div>
                <!--Setimo item-->
                <div class="item col-md-3">
                    <fieldset>
                        <legend class="campo_radio">Porte <span class="required">*</span></legend>
                        <div>
                            <input type="radio" id="pequeno" name="porte" value="Pequeno">
                            <label for="pequeno">Pequeno</label>
                        </div>
                        <div>
                            <input type="radio" id="medio" name="porte" value="Médio">
                            <label for="medio">Médio</label>
                        </div>
                        <div>
                            <input type="radio" id="grande" name="porte" value="Grande">
                            <label for="grande">Grande</label>
                        </div>
                    </fieldset>
                </div>
                <!--Oitavo item-->
                <div class="item col-md-3">
                    <fieldset>
                        <legend class="campo_radio">Sexo <span class="required">*</span></legend>
                        <div>
                            <input type="radio" id="macho" name="sexo" value="Macho">
                            <label for="macho">Macho</label>
                        </div>
                        <div>
                            <input type="radio" id="femea" name="sexo" value="Fêmea">
                            <label for="femea">Fêmea</label>
                        </div>
                        <div>
                            <input type="radio" id="ntc" name="sexo" value="Não tenho certeza">
                            <label for="ntc">Não tenho certeza</label>
                        </div>
                    </fieldset>
                </div>

                <!-- Botão Cadastrar-->
                <div class="item col-md-4">
                    <button type="submit" class="btn botao">Cadastrar</button>
                    <a href="cadastro_pet.php" class="cancelar-link">Cancelar</a>
                </div>
            </div>
        </form>

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

<!-- Bootstrap JS e Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Referência ao seu script JavaScript  / modal de aviso para escolha de imagem-->
<script src="../js/modal_imagem_encontrado.js"></script>

<!-- Referência ao seu script JavaScript / escolha de imagem pet/ icone de foto-->
<script src="../js/cadastro_imagem.js"></script>

<!-- Referência ao seu script JavaScript / modal obrigatoriedade dos campos do form-->
<script src="../js/modal_form_encontrado.js"></script>

<script src="../js/validacao_data.js"></script>

</html>