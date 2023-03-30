<?php
include 'asideHome.php';
require '../modelo/Promocion/daoPromocion.php';
require '../modelo/Rubro/daoRubro.php';
date_default_timezone_set('America/El_Salvador');
$fecha_hoy = date("Y-m-d");
$rubro = isset($_REQUEST['rubro']) ? $_REQUEST['rubro'] : 0;

$daoRubro = new daoRubro();
$listRubros = $daoRubro->listaRubro();

$daoPromo = new daoPromocion();
$daoPromo->ActivarPromociones($fecha_hoy);
$daoPromo->VencerPromociones($fecha_hoy);
$listPromociones = $daoPromo->listarPromociones($rubro);
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
  <body  style="background-color: white ;">
        <section class="container-left">
            <article class="container">
                
                <?php
                if($busqueda != ""){
                    $listPromociones = $daoPromo->buscarPromociones($busqueda);
                    echo "<h1 class='display-2 text-center'>Resultados de la Búsqueda</h1>";
                }else{
                    echo "<h1 class='display-5 text-center'>Vea las Ultimas Promociones Solo Para Ústed</h1> <br><br>";
                }
                ?>
                
                <div>
                    <form id="FiltrarRubro" method="GET" action="<?=$_SERVER['PHP_SELF']?>">
                        <select name="rubro">
                            <option value="0"> Sin Rubro </option>
                        <?php
                            foreach($listRubros as $rubros){
                        ?>
                            <option <?= $rubro == $rubros["CodRubro"] ? "selected" : "" ?>  value="<?= $rubros["CodRubro"] ?>">
                            <?= $rubros["NombreRubro"] ?> </option>
                        <?php
                            }
                        ?>                    
                        </select>
                        <input  class=" btn btn-primary"type="submit" value="Filtrar">
                    </form>
                </div>
                <hr class="text-center" style="width: 100%;"><br>

                <?php
                    if(sizeof($listPromociones) == 0){
                        if($busqueda != ""){
                            echo "<br><div>
                                    <h1  class='alert alert-danger' role='alert'> No hay ningun resultado de su busqueda. </h1>
                                </div><br><br><br> ";
                        }else{
                            echo " <br><div class='alert alert-danger' role='alert' style='padding:35px; margin:auto;'>
                                    <h1> No hay ninguna oferta activa de ese rubro. </h1>
                                </div><br><br><br> ";
                        }
            
                    }else{
                        foreach($listPromociones as $promo){
                ?>
                    
                <div class="row text-center" style="background-color: #F6FCFB ; border-radius:8px; margin-left: 8px;">
                    <div class="col-4" style="border-radius:8px;">
                        <img  style="margin-top:35px;"src="img/<?= $promo['FotoPromocion']?>" width="200px" height="200px">
                    </div>
                    <div class="col-7">
                        <h3 style="padding: 10px;" class="h4"> <?=$promo["Titulo"]?> </h3>
                        <p class="lead align-items: center; y justify-content: center;" style="text-aling:justify;" > <?=$promo["Detalle"]?> </p>
                        <h4><i class="blockquote-footer"> Vence el: <?= date("d/m/Y", strtotime($promo['FechaVencimiento']))   ?> </i></h4><br>
                        <div class="row text-center">
                            <div class="col-7">
                                <h4><b class="h4">$ <?= $promo["PrecioOferta_Cupon"]?> </b> </h4>
                            </div>
                            <div class="col-4" >
                                <a href="promocion.php?promo=<?=$promo['CodPromocion']?>"><button class="btn btn-success"> Detalles </button></a>
                            </div>
                        </div>
                    </div>
                </div>
                <br><hr>
                <?php
                        }
                    }
                ?>

            </article>
        </section>
</body>