<?php
require '../modelo/Cupones/daoCupones.php';

date_default_timezone_set('America/El_Salvador');
$fecha_hoy = date("Y-m-d");

$daoCupon = new daoCupones();
$daoCupon->VencerCupones($fecha_hoy);
$Resultados = isset($_REQUEST['canje']) ? $_REQUEST['canje'] : "";
$Dui = isset($_REQUEST['Dui']) ? $_REQUEST['Dui'] : "";
$IdCupon = isset($_REQUEST['CuponId']) ? $_REQUEST['CuponId'] : "";
if ($Dui != "" && $IdCupon != ""){
    $CuponObtenido = $daoCupon-> solicitud_Cupones($Dui,$IdCupon);
}
?>
 
 <!doctype html>
  <html lang="en">
    <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
  
      <!-- Bootstrap CSS -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  
      <title>Solicitudes de Canje</title>
    </head>
    <body style="background-color: #E9EEED ;">
    <?php
    require 'menu.php';
    
    if($_SESSION["Rol"] == "Empleado"){
    ?>
<!-- Modal -->
<?php
if (isset($CuponObtenido)){
  if(sizeof($CuponObtenido) != 0 && $CuponObtenido[0]["EstadoCupon"] == "Disponible"){
    $Cupon = $CuponObtenido[0];
?>
  <div class="modal fade" tabindex="-1" id="ModalConfirm" data-bs-backdrop="static" data-bs-keyboard="false"> <!-- ESTA LINEA NADIE LA TOQUE -->
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Oferta - <?=$Cupon["Titulo"] ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p> <b> Descripcion de la Oferta: </b> <?=$Cupon["Descripcion"] ?> </p>
          <p> <b> Información del Cliente: </b> <?=$Cupon["Apellido"].", ".$Cupon["Nombre"]?></p>  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <form method="POST" action="../controlador/ctrlCupon.php?accion=Canje&idcupon=<?=$Cupon["CodCupon"]?>">
            <button type="submit" class="btn btn-primary">Canjear Cupón</button>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php
    
  }
}

if($Resultados == "exitoso"){
 echo '
  <div class="alert alert-success" role="alert">
    El cupon ha sido canjeado exitosamente.
  </div>';
}

?>
  <div class="card" style="width: 50rem; padding: auto; margin: auto; border-radius:8px" >
    <div class="card-header text-center" style="background-color:#434646;">
    <h5 class="card-title text-center" style="color:white;">Proceso de Canje de Cupon</h5>
    </div>
    <div class="card-body">
      <p class="card-text text-center">Aqui podras ver todas las solicitudes de canje para los cupones comprados por los clientes. Valida que toda la informacion se encuentre correcta</p>
      <div class="card" style="width: 45rem; padding: 2rem; margin: auto;">
      <?php
      if (isset($CuponObtenido)){
        if(sizeof($CuponObtenido) == 0){
            echo '
            <div class="alert alert-danger" role="alert">
              Error! Ingreso un dato incorrecto.
            </div>';
            $Dui="";
            $IdCupon="";
        }else {
          if($CuponObtenido[0]["EstadoCupon"] == "Canjeado"){
            echo '
            <div class="alert alert-danger" role="alert">
              Este cupon ya ha sido canjeado.
            </div>';
            $Dui="";
            $IdCupon="";
          }else if($CuponObtenido[0]["EstadoCupon"] == "Vencido"){
            echo '
            <div class="alert alert-danger" role="alert">
              Este cupon ya ha vencido.
            </div>';
            $Dui="";
            $IdCupon="";
          }else{
            echo ' 
          <img id="activaONLOAD" width=0 src="img/foto.jpg">
          ';
          }
           
        }
    }
    ?>
    <div class="card-body" >
      <h5 class="card-title text-center">Canjea los cupones</h5><br>
      <form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
        <input required class="form-control" type="text" value="<?=$Dui?>" placeholder="Dui" aria-label="Disabled input example" name="Dui">
        <input required class="form-control" type="text" value="<?=$IdCupon?>" placeholder="Codigo Cupon" aria-label="Disabled input example" name="CuponId">
        <br><div class="d-grid gap-2 d-md-flex justify-content-md-end" style="margin: auto;">
        <input class="btn btn-success" data-bs-toggle="modal" data-bs-target="Modal_Datos" type="submit" value="Verificando" name="Canjeando"></div>
      </form>   
    </div>
  </div>
    </div>
  </div>
  <br><br>
  <?php
    }
    else{
      echo "<h1 style='text-align:center'>Solo un empleado puede acceder aqui:</h1> <br>

        <div style='margin: 0 auto 20px auto; width: 10%;'> 
        <a href=' index.php'><button class=' fs-4 fw-bolder btn btn-primary'> Regresar </button></a>
        </div>

      ";
           
    }
  ?>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
      
      <script>
        document.getElementById("activaONLOAD").onload = function() {cargaModal()};

        function cargaModal() {
          var ModalConfirm = new bootstrap.Modal(document.getElementById('ModalConfirm'));
          ModalConfirm.toggle();
        }
        </script>

    </body>
  </html>
  <?php
   require 'footer.php';
   ?>
  