<?php
    if (session_id() === "") { session_start(); }
    if(isset($_SESSION['CodUsuario'])){
        $log = $_SESSION['Rol'] . " - " .  $_SESSION['Nombre'];
        $link = "perfil.php";

    }else{
        $log = "Perfil";
        $link = "login.php";
    }
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Cuponera</title>
  </head>
  <body>
  <nav class="navbar sticky-top navbar navbar-expand-lg navbar navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="../vista/index.php"><img src="img/home.png" alt="Home" width="45"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="../vista/index.php">Home</a>
        </li>
        <li class="nav-item">
          <a href="<?=$link?>" class="nav-link active text-primary"><?=$log?></a>
        </li>
        <?php
        //Parte que varia de cada usuario
         if($log != "Perfil"){
          switch($_SESSION['Rol']){
            case 'Administrador':
              echo '
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="vistaAdmin.php?gestion=Promociones">Gestion Promociones</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="vistaAdmin.php?gestion=Rubro">Gestion Rubros</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="vistaAdmin.php?gestion=Clientes">Gestion Clientes</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="vistaAdmin.php?gestion=Empresas">Gestion Empresas</a>
              </li>
              ';
              break;
            case 'Empresa':
              echo '
              <li class="nav-item">
                <a class="nav-link disabled" aria-current="page" href="OfertasEmpresa.php">Gestion Ofertas</a>
              </li>
              <li class="nav-item">
                <a class="nav-link disabled" aria-current="page" href="GestorEmpleados.php">Gestion Empleados</a>
              </li>
              ';
              break;
            case 'Empleado':
                echo '
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="canjeCupon.php">Canjear Cupón</a>
                </li>
                ';
              break;
            default: 
              break;
           }
         }
        ?>
        <li class="nav-item">
        <a class="nav-link disabled" tabindex="-1" href="#" aria-disabled="true">Contactenos</a>
        </li>
      </ul>
      <span class="navbar-text">
      <?php
        if($log == "Perfil"){
          echo '<a href="login.php"><button class="btn btn-primary"> Iniciar Sesión </button></a>';
        }else{
          echo '<a href="logout.php"><button class="btn btn-danger"> Cerrar Sesión </button></a>';
        }
      ?>
      </span>
    </div>
    </div>
  </nav>
  <br><br><br>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>
