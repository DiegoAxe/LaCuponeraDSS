<?php
$busqueda = isset($_REQUEST['busqueda']) ? $_REQUEST['busqueda'] : "";
require '../modelo/Cupones/daoCupones.php';
$daoCupon = new daoCupones();
if(isset($_SESSION['CodUsuario'])){
    $listaCuponesUser = $daoCupon->mostrarCuponesClientes($_SESSION['CodUsuario']);
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
    <link href="css/stylesHome.css" rel="stylesheet" type="text/css">

    <title></title>
  </head>
  <body> 

    <div class="fixed-right rounded shadow">
        <div class="card">
            <div class="card-body">
            <?php
        if($busqueda != ""){
    ?>
        <div style=" margin-left:35px;">
            <a href="index.php"><button class="btn btn-danger"> Deshacer busqueda </button></a><br>
        </div>
    <?php
        }
    ?>  
    <article >    
        <h4 class="display-6 text-center" > Buscador: </h4>
        <br>
        <form id="BuscadorPromos" method="POST" action="<?=$_SERVER['PHP_SELF']?>"> 
            <div class="div-hijo">
                <input required type="text" name="busqueda" value="<?=$busqueda ?>">
            </div>
            <div class="div-hijo">
                <input class="btn btn-primary centrar-btn" type="submit" value="Buscar">
            </div>
            
        </form> 
    </article>
    <br><hr style="width:100%;"><br>

    <article>
        <h4 class=" display-6 text-center"> Tus cupones: </h4>
        <div class="aside-Cupon ">
        <?php
        if(!isset($_SESSION['CodRol']) ){
            echo "<h5 class='col-sm-9'> No esta logeado, no puede ver sus cupones </h5>
            <a href='login.php'><button> Loguearse </button></a>";
        }else if($_SESSION['CodRol'] != 2){
            echo "<h5 class='col-sm-11 text-center'> Usted no es un cliente, no puede comprar Cupones </h5>";
        }else{
            echo '<div class="list-group">';
            foreach($listaCuponesUser as $CuponesUser){
                if($CuponesUser["EstadoCupon"] == "Disponible"){
                    echo ' <a href="perfil.php" class="text-center list-group-item list-group-item-action list-group-item-primary">
                        <b>'.$CuponesUser["CodCupon"].'<br> Vence:</b> '.
                        date("d/m/Y", strtotime($CuponesUser['FechaVencimiento'])).'</a>
                    ';
                }
            }
            echo '</div>';
        }
        ?>
        </div>
    </article>
            </div>
        </div>

    </div>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>