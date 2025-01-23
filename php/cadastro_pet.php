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
  <link rel="stylesheet" href="../css/area_usuario.css">
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
      <!--<a class="navbar-brand" href="#" id="home">Home</a>-->
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
    <div class=container2>
      <h2>Seja bem-vindo(a)!</h2>
      <p>Escolha uma das opções abaixo para começar:</p>
      <div>
        <a href="./cadastro_pet_encontrado.php" class="opcoes">Cadastrar Pet Encontrado</a>
        <a href="./cadastro_pet_perdido.php" class="opcoes">Cadastrar Pet Perdido</a>
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