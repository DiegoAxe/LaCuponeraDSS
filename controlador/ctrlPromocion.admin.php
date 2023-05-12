<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Cuponera</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <div class="container">
    <section>
        <article>
<?php
    require '../vista/menu.php';
    if(isset($_SESSION['CodUsuario']) && $_SESSION['CodRol'] == 1){
        //Controlador de datos que ingresan
        
        $accion = isset($_REQUEST['accion'])?$_REQUEST['accion']:"";
        $id = isset($_GET['id'])?$_GET['id']:"";

               
    
        if($accion == ""){
            header("Location: ../modulosProyecto/index.php");
        }    

        if($accion == "Aprobar"){
            require '../modelo/Promocion/daoPromocion.admin.php';
            $daoPromo = new daoPromocion();
            $estado = "Aprobada";
            $estadoPromo = $daoPromo->verEstado($id);
            var_dump($estadoPromo);
            if(count($estadoPromo)>0){
                if($estadoPromo[0]['Estado']!="Aprobada"){
                    $daoPromo->actualizarEstado($id, $estado);
                    $_SESSION['mensaje'] = "Operación realizada con éxito.";
                    header('location: ../vista/vistaAdmin.php?gestion=Promociones');
                }else{
                    echo "<h1>Esta oferta ya ha sido aprobada</h1>";
                }
            }else{
                echo "<h1>Elija una oferta válida</h1>";
            }
            //
        }

        if($accion == "Rechazar"){
            require '../modelo/Promocion/daoPromocion.admin.php';
            $daoPromo = new daoPromocion();
            $estado = "Rechazada";
            $estadoPromo = $daoPromo->verEstado($id);
            var_dump($estadoPromo);
            if(count($estadoPromo)>0){
                if($estadoPromo[0]['Estado']!="Aprobada"){
                    $daoPromo->actualizarEstado($id, $estado);
                    $_SESSION['mensaje'] = "Operación realizada con éxito.";
                    header('location: ../vista/vistaAdmin.php?gestion=Promociones');
                }else{
                    echo "<h1>Esta oferta ya ha sido aprobada, no se puede rechazar</h1>";
                }
            }else{
                echo "<h1>Elija una oferta válida</h1>";
            }
            //
        }

    

    
    }else{
?>
    
        <h1>Debes ser ADMINISTRADOR para ver este página: <a href="../vista/login.php">Inicia Sesión</a></h1>
        </article>
    </section>
    </div>
<?php
    }
    require '../vista/footer.php';
?>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>