<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Cuponera</title>
</head>
<body>
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
            require '../modelo/Promocion/daoPromocion.php';
            $daoPromo = new daoPromocion();
            $estado = "Aprobada";
            $estadoPromo = $daoPromo->verEstado($id);
            var_dump($estadoPromo);
            if(count($estadoPromo)>0){
                if($estadoPromo[0]['Estado']!="Aprobada"){
                    $daoPromo->actualizarEstado($id, $estado);
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
            require '../modelo/Promocion/daoPromocion.php';
            $daoPromo = new daoPromocion();
            $estado = "Rechazada";
            $estadoPromo = $daoPromo->verEstado($id);
            var_dump($estadoPromo);
            if(count($estadoPromo)>0){
                if($estadoPromo[0]['Estado']!="Aprobada"){
                    $daoPromo->actualizarEstado($id, $estado);
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
<?php
    }
    require '../vista/footer.php';
?>
    
</body>
</html>