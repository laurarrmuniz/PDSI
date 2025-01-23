<?php

require "conexao.php";
$conn = new Conexao();

// Verifica se o formulário foi submetido
$searchQuery = '';
if (isset($_GET['search'])) {
  $searchQuery = $_GET['search'];
  // Normaliza o texto da busca removendo acentos e caracteres especiais
  $searchQuery = preg_replace('/[áàãâä]/ui', 'a', $searchQuery);
  $searchQuery = preg_replace('/[éèêë]/ui', 'e', $searchQuery);
  $searchQuery = preg_replace('/[íìîï]/ui', 'i', $searchQuery);
  $searchQuery = preg_replace('/[óòõôö]/ui', 'o', $searchQuery);
  $searchQuery = preg_replace('/[úùûü]/ui', 'u', $searchQuery);
  $searchQuery = preg_replace('/[ç]/ui', 'c', $searchQuery);
  $searchQuery = preg_replace('/[^a-z0-9]/i', ' ', $searchQuery);
}

$tipo_filtro = isset($_GET['tipo_filtro']) ? $_GET['tipo_filtro'] : '';
$porte_filtro = isset($_GET['porte_filtro']) ? $_GET['porte_filtro'] : '';
$sexo_filtro = isset($_GET['sexo_filtro']) ? $_GET['sexo_filtro'] : '';
$dia_filtro = isset($_GET['dia_filtro']) ? $_GET['dia_filtro'] : '';
$status_filtro = isset($_GET['status_filtro']) ? $_GET['status_filtro'] : '';
$local_filtro = isset($_GET['local_filtro']) ? $_GET['local_filtro'] : '';

// Verificar se os parâmetros estão sendo passados corretamente
//var_dump($tipo_filtro, $porte_filtro, $sexo_filtro, $dia_filtro, $status_filtro, $local_filtro);


// Inicia a query base sem UNION duplicado
$sql = "SELECT titulo, local, horario, data, info, tipo, porte, sexo, nomeArqFoto, nome, telefone, status
        FROM (
            SELECT titulo, local, horario, data, info, tipo, porte, sexo, nomeArqFoto, nome, telefone, status
            FROM pet_encontrado 
            INNER JOIN foto_pets_encontrado ON pet_encontrado.id = foto_pets_encontrado.id_pet_encontrado 
            INNER JOIN usuario ON usuario.id = pet_encontrado.id_usuario
            
            UNION ALL 
            
            SELECT titulo, local, horario, data, info, tipo, porte, sexo, nomeArqFoto, nome, telefone, status
            FROM pet_perdido 
            INNER JOIN foto_pets_perdido ON pet_perdido.id = foto_pets_perdido.id_pet_perdido 
            INNER JOIN usuario ON usuario.id = pet_perdido.id_usuario
        ) AS pets
        WHERE 1=1";


// Se o usuário buscar pela palavra (opção 1)
if (!empty($searchQuery)) {
  $sql .= " AND (titulo LIKE :searchQuery
    OR local LIKE :searchQuery
    OR horario LIKE :searchQuery
    OR data LIKE :searchQuery
    OR info LIKE :searchQuery
    OR tipo LIKE :searchQuery
    OR porte LIKE :searchQuery
    OR sexo LIKE :searchQuery
    OR nome LIKE :searchQuery
    OR telefone LIKE :searchQuery
    OR status LIKE :searchQuery)";
}

// Se o usuário aplicar filtros adicionais (opção 2)
if (!empty($tipo_filtro)) {
  $sql .= " AND tipo = :tipo_filtro";
}
if (!empty($porte_filtro)) {
  $sql .= " AND porte = :porte_filtro";
}
if (!empty($sexo_filtro)) {
  $sql .= " AND sexo = :sexo_filtro";
}
if (!empty($dia_filtro)) {
  $sql .= " AND data >= :dia_filtro";
}
if (!empty($status_filtro)) {
  $sql .= " AND status = :status_filtro";
}
if (!empty($local_filtro)) {
  $sql .= " AND local LIKE :local_filtro";
}

// Preparação da query
$stmt = $conn->conexao->prepare($sql);

// Bind dos parâmetros
if (!empty($searchQuery)) {
  $searchParam = "%$searchQuery%";
  $stmt->bindParam(':searchQuery', $searchParam, PDO::PARAM_STR);
}
if (!empty($tipo_filtro)) {
  $stmt->bindParam(':tipo_filtro', $tipo_filtro, PDO::PARAM_STR);
}
if (!empty($porte_filtro)) {
  $stmt->bindParam(':porte_filtro', $porte_filtro, PDO::PARAM_STR);
}
if (!empty($sexo_filtro)) {
  $stmt->bindParam(':sexo_filtro', $sexo_filtro, PDO::PARAM_STR);
}
if (!empty($dia_filtro)) {
  $stmt->bindParam(':dia_filtro', $dia_filtro, PDO::PARAM_STR);
}
if (!empty($status_filtro)) {
  $stmt->bindParam(':status_filtro', $status_filtro, PDO::PARAM_STR);
}
if (!empty($local_filtro)) {
  $localParam = "%$local_filtro%";
  $stmt->bindParam(':local_filtro', $localParam, PDO::PARAM_STR);
}

// Executa a query
$stmt->execute();

// Armazena os resultados
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css'>
  <!-- CSS -->
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/itens_busca.css">
  <link rel="stylesheet" href="../css/validacao.css">
  <!-- Script -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="../js/bootstrap.bundle.min.js"></script>
  <script src="../js/bootstrap-4.3.1.bundle.min.js.js"></script>
  <!--Link para fonte-->
  <link href="https://fonts.cdnfonts.com/css/berlin-sans-fb-demi" rel="stylesheet">

  <title>EncontraPets</title>
</head>

<body>
  <header id="menu" class="p-3 text-bg-dark">
    <nav class="navbar justify-content-center">
      <a class="navbar-brand" href="busca.php">
        <img src="../images/logo_sem_fundo.png" class="d-inline-block align-top"
          alt="">
      </a>
    </nav>
    <nav class="navbar navbar-expand-lg navbar-light ">
      <!--<a class="navbar-brand" href="busca.php" id="home">Home</a>-->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
        aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <a class="nav-item nav-link active" href="../cadastroUsuario.html">Cadastrar<span class="sr-only">(current)</span></a>
          <a class="nav-item nav-link active" href="busca.php">Buscar Pets<span class="sr-only">(current)</span></a>
          <form class="form-inline my-2 my-lg-0 login-botao">
            <a href="../login.html" class="btn btn-outline-success my-2 my-sm-0">Login</a>
          </form>
        </div>
      </div>
    </nav>
  </header>

  <main>

    <!-- Modal Bootstrap para Busca Avançada -->
    <div class="modal fade" id="modalFiltro" tabindex="-1" aria-labelledby="modalFiltroLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalFiltroLabel">Buscar por:</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="formFiltro" action="busca.php" method="GET">
              <!-- Campo de tipo -->
              <div class="mb-3">
                <label for="tipo_filtro" class="form-label">Tipo</label>
                <select class="form-select" id="tipo_filtro" name="tipo_filtro">
                  <option value="">Selecione o tipo</option>
                  <option value="cao">Cão</option>
                  <option value="gato">Gato</option>
                  <option value="outro">Outro</option>
                </select>
              </div>
              <!-- Campo de porte -->
              <div class="mb-3">
                <label for="porte_filtro" class="form-label">Porte</label>
                <select class="form-select" id="porte_filtro" name="porte_filtro">
                  <option value="">Selecione o porte</option>
                  <option value="pequeno">Pequeno</option>
                  <option value="medio">Médio</option>
                  <option value="grande">Grande</option>
                </select>
              </div>
              <!-- Campo de sexo -->
              <div class="mb-3">
                <label for="sexo_filtro" class="form-label">Sexo</label>
                <select class="form-select" id="sexo_filtro" name="sexo_filtro">
                  <option value="">Selecione o sexo</option>
                  <option value="Macho">Macho</option>
                  <option value="Fêmea">Fêmea</option>
                  <option value="Não tenho certeza">Não tenho certeza</option>
                </select>
              </div>
              <!-- Campo de local -->
              <div class="mb-3">
                <label for="local_filtro" class="form-label">Local</label>
                <input type="text" id="local_filtro" name="local_filtro" autocomplete="off" class="form-control" maxlength="100">
              </div>

              <!-- Campo de data -->
              <div class="mb-3">
                <label for="dia_filtro" class="form-label">Data</label>
                <input type="date" id="dia_filtro" name="dia_filtro" autocomplete="off" class="form-control" maxlength="10">
                <small class="form-text text-muted">A busca será feita a partir da data informada.</small>
                <div id="errorDia" class="error-message d-none">A data não pode ser no futuro.</div> <!-- Mensagem de erro -->
              </div>

              <!-- Campo de status -->
              <div class="mb-3">
                <label for="status_filtro" class="form-label">Status</label>
                <select class="form-select" id="status_filtro" name="status_filtro">
                  <option value="">Selecione o status</option>
                  <option value="Encontrado">Pet Encontrado</option>
                  <option value="Perdido">Pet Perdido</option>
                </select>
              </div>

              <!-- Botão de filtro -->
              <button type="submit" class="btn btn-primary" id="btn-outline-success">Buscar</button>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-outline-close">Fechar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <!-- Busca -->
      <form class="d-flex" id="form" method="GET" action="">
        <img class="search-img" src="../images/icons/search.svg" alt="">
        <input class="form-control me-2" type="text" name="search" id="searchInput" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Pesquisar" aria-label="Pesquisar">
        <button class="btn btn-outline-success" id="button" type="submit">Buscar</button>
        <a class="filtro" id="filtro" href="#" data-bs-toggle="modal" data-bs-target="#modalFiltro"> <img class="filter-img" src="../images/icons/filter.svg" alt=""></a>
      </form>

      <div id="container" class="row">
        <?php if (empty($resultados)) {
          echo '<div class="alert alert-warning" role="alert" id="alerta_busca">
            Nenhum pet encontrado com o título: <strong>' . htmlspecialchars($searchQuery) . '</strong>.
          </div>';
        } else ?>
        <?php foreach ($resultados as $resultado) : ?>
          <?php
          $titulo = htmlspecialchars($resultado['titulo']);
          $local = htmlspecialchars($resultado['local']);
          $horario = htmlspecialchars($resultado['horario']);
          $data = htmlspecialchars($resultado['data']);
          $info = htmlspecialchars($resultado['info']);
          $tipo = htmlspecialchars($resultado['tipo']);
          $porte = htmlspecialchars($resultado['porte']);
          $sexo = htmlspecialchars($resultado['sexo']);
          $nomeArqFoto = htmlspecialchars($resultado['nomeArqFoto']);
          $caminho_imagem = "../images/$nomeArqFoto";
          $nome = htmlspecialchars($resultado['nome']);
          $telefone = htmlspecialchars($resultado['telefone']);
          $status = htmlspecialchars($resultado['status']);
          ?>

          <div class="coluna col-lg-6">
            <div class="item">

              <div class="contato">
                <div class="img-container">
                  <img src="<?= $caminho_imagem ?>" alt="<?= $titulo ?>" class="img">
                </div>
                <h5>Contato com: </h5>
                <h5><?= $nome ?></h5>
                <h5><?= $telefone ?></h5>
              </div>

              <div class="alinha_item">
                <h4><?= $titulo ?></h4>
                <p>Tipo: <?= $tipo ?></p>
                <p>Porte: <?= $porte ?></p>
                <p>Sexo: <?= $sexo ?></p>
                <p>Local: <?= $local ?></p>
                <p>Data:
                  <?php
                  // Converte a data do formato yyyy/mm/dd para dd/mm/yyyy
                  $data_formatada = DateTime::createFromFormat('Y-m-d', $data)->format('d/m/Y');
                  echo $data_formatada;
                  ?>
                </p>
                <p>Hora: <?= $horario ?></p>
                <p>Status: <?= $status ?></p>
                <p class="info">Informações adicionais: <?= $info ?></p>
              </div>
            </div>
          </div>

        <?php endforeach; ?>

      </div>
    </div>
    </div>
  </main>

  <footer class="footer">
    <div class="container">
      <div class="py-3 my-4">
        <ul class="nav justify-content-center border-bottom pb-3 mb-3">
          <li class="nav-item"><a href="../cadastroUsuario.html" class="nav-link px-2 text-body-secondary">Cadastrar</a></li>
          <li class="nav-item"><a href="busca.php" class="nav-link px-2 text-body-secondary">Buscar Pets</a></li>
          <li class="nav-item"><a href="../login.html" class="nav-link px-2 text-body-secondary">Login</a></li>
        </ul>
        <p class="text-center text-body-secondary">© 2024 Company, Inc</p>
      </div>
    </div>
  </footer>

</body>

<!--Referência ao scrip js para validação da data-->
<script src="../js/validacao_data_modal.js"></script>

</html>