<?php
session_start();

$email = $_SESSION['email'];

require "conexao.php";
$conn = new Conexao();

if (!isset($_SESSION['email']))
  header("location: ../login.html");

//Fazer logout
if (isset($_GET['sair'])) {
  unset($_SESSION['email']);
  header("location: ../login.html");
}

try {

  // Consulta para pets encontrados
  $sqlEncontrado = "SELECT pet_encontrado.id AS id_pet, pet_encontrado.titulo, pet_encontrado.info, foto_pets_encontrado.nomeArqFoto
                  FROM pet_encontrado
                  LEFT JOIN foto_pets_encontrado ON pet_encontrado.id = foto_pets_encontrado.id_pet_encontrado
                  WHERE pet_encontrado.id_usuario = (SELECT id FROM usuario WHERE email = '$email')";

  $stmtEncontrado = $conn->conexao->query($sqlEncontrado);

  // Consulta para pets perdidos
  $sqlPerdido = "SELECT pet_perdido.id AS id_pet, pet_perdido.titulo, pet_perdido.info, foto_pets_perdido.nomeArqFoto
               FROM pet_perdido
               LEFT JOIN foto_pets_perdido ON pet_perdido.id = foto_pets_perdido.id_pet_perdido
               WHERE pet_perdido.id_usuario = (SELECT id FROM usuario WHERE email = '$email')";

  $stmtPerdido = $conn->conexao->query($sqlPerdido);
} catch (Exception $e) {
  exit('Ocorreu uma falha: ' . $e->getMessage());
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
  <link rel="stylesheet" href="../css/area_usuario.css">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css'>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/meus_pets.css">
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
          <form class="form-inline my-2 my-lg-0 login-botao" method="GET" action="">
            <a href="busca.php?sair=true" class="btn btn-outline-danger my-2 my-sm-0">Sair</a>
          </form>
        </div>
      </div>
    </nav>
  </header>

  <main>
    <div class="container">
      <h3>Meus Pets Encontrados</h3>
      <div class="table-responsive">
      <table class="table table-hover">
        <tr>
          <th></th>
          <th>Foto</th>
          <th>Título</th>
          <th>Mais informações relevantes</th>
          <th></th>
        </tr>

        <?php
        // Pets encontrados
        while ($rowEncontrado = $stmtEncontrado->fetch()) {
          $id = $rowEncontrado['id_pet'];
          $titulo = htmlspecialchars($rowEncontrado['titulo']);
          $info = htmlspecialchars($rowEncontrado['info']);
          $nomeArqFoto = htmlspecialchars($rowEncontrado['nomeArqFoto']);

          // Verificar se há uma foto associada, se não exibir uma imagem padrão
          $foto = $nomeArqFoto ? "../images/{$nomeArqFoto}" : "../images/cachorro.png";

          echo <<<HTML
              <tr>
                <td>
                  <a href="exclui_pet_encontrado.php?id=$id">
                    <img src="../images/icons/delete.svg" width="20" height="20" alt="Excluir">
                  </a>
                </td>
                <td><img src="$foto" class="foto-pet" alt="Foto do Pet"></td>
                <td>$titulo</td>
                <td>$info</td> 
                <td>
                  <a href="edita_pet_encontrado.php?id=$id">
                    <img src="../images/icons/editar.svg" width="20" height="20" alt="Editar">
                  </a>
                </td>   
              </tr>    
            HTML;
        }
        ?>
      </table>
      </div>

      <h3>Meus Pets Perdidos</h3>
      <div class="table-responsive">
      <table class="table table-hover">
        <tr>
          <th></th>
          <th>Foto</th>
          <th>Título</th>
          <th>Mais informações relevantes</th>
          <th></th>
        </tr>

        <?php
        // Pets perdidos
        while ($rowPerdido = $stmtPerdido->fetch()) {
          $id = $rowPerdido['id_pet'];
          $titulo = htmlspecialchars($rowPerdido['titulo']);
          $info = htmlspecialchars($rowPerdido['info']);
          $nomeArqFoto = htmlspecialchars($rowPerdido['nomeArqFoto']);

          // Verificar se há uma foto associada, se não exibir uma imagem padrão
          $foto = $nomeArqFoto ? "../images/{$nomeArqFoto}" : "../images/cachorro.png";

          echo <<<HTML
              <tr>
                <td>
                  <a href="exclui_pet_perdido.php?id=$id">
                    <img src="../images/icons/delete.svg" width="20" height="20" alt="Excluir">
                  </a>
                </td> 
                <td><img src="$foto" class="foto-pet" alt="Foto do Pet"></td>
                <td>$titulo</td>
                <td>$info</td> 
                <td>
                  <a href="edita_pet_perdido.php?id=$id">
                    <img src="../images/icons/editar.svg" width="20" height="20" alt="Editar">
                  </a>
                </td>   
              </tr>    
            HTML;
        }
        ?>
      </table>
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