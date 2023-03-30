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
        //error_reporting(0);
    //Controlador de datos que ingresan
    $cod = isset($_POST['cod'])?$_POST['cod']:"";
    $nombre = isset($_POST['nombre'])?$_POST['nombre']:"";
    $desc = isset($_POST['desc'])?$_POST['desc']:"";
    
    $accion = isset($_REQUEST['accion'])?$_REQUEST['accion']:"";
    $id = isset($_GET['id'])?$_GET['id']:"";

               
    
    if($accion == ""){
        header("Location: ../modulosProyecto/index.php");
    }    

    if($accion == "Registrar"){
        require '../modelo/Rubro/rubro.class.php';
        require '../modelo/Rubro/daoRubro.php';
        $daoRubro = new daoRubro();
        $rubro = new Rubro($nombre, $desc);
        $validacion = $rubro->validarRubro();
        if($validacion == true){
           // var_dump($rubro);
            $daoRubro = new daoRubro();
            $daoRubro->insertar($rubro);
            header('location: ../vista/vistaAdmin.php'); 
        }else{
            $error = "";
            if($rubro->valNombre($nombre) == false){
                $error .= "<h3>El nombre sólo debe ser una palabrá con una inicial mayúsucula</h3>";
            }
            if($rubro->valDescripcion($desc) == false){
                $error .= "<h3>La descrición debe iniciar con una mayúsucula</h3>";
            }
            echo $error;
        }  
    }

    if($id != "" && $accion=="Eliminar"){
        require '../modelo/Rubro/daoRubro.php';
        $dao = new daoRubro();
        $dao->eliminar($id);
        //echo "<div style='margin-top: 10%; margin-left: 37%'><p style='font-size: 27px; font-weight: bold;'>Usuario eliminado exitosamente...</p></div>";
        //echo "<a style='margin-left: 47%; text-decoration: none; background-color: #0083B0; border-radius: 10px; padding: 7px; color: #fff; text-align: center; font-size: 17px; font-weight: bold;' href='../vista/CRUD.php'>Regresar</a>";
        header("location: ../vista/vistaAdmin.php");
    }

    if($id != "" && $accion=="Modificar"){
        require '../modelo/Rubro/daoRubro.php';
        $dao = new daoRubro();
        $rubro = $dao->mostrarRubro($id);
        $html = <<<'EOD'

            <form action="../controlador/ctrlRubro.php?accion=Modificar" method="post">
                <legend>Modificando Rubro</legend>
                <div>
                    <label for="nombre">Nombre</label>
        EOD;
        echo $html;
        echo "<input type=\"hidden\" name=\"cod\" id=\"cod\" required value=" . $rubro['CodRubro'] . ">";
        echo "<input type=\"text\" name=\"nombre\" id=\"nombre\" required value=" . $rubro['NombreRubro'] . ">";
        echo "</div>";
        echo "<div>";
        echo "<label for=\"desc\">Descripción</label>";
        echo "<textarea name=\"desc\" id=\"desc\" required>" . $rubro['Descripcion'] . "</textarea>";
        echo "</div>";
        echo "<input type=\"submit\" name=\"registro\" value=\"Registrar\">";
        echo "</form>";
        echo "</article>";
        echo "</section>";
    }

    if($id == "" && $accion == "Modificar"){
        require '../modelo/Rubro/rubro.class.php';
        require '../modelo/Rubro/daoRubro.php';
        $daoRubro = new daoRubro();
        $rubro = new Rubro($nombre, $desc);
        $rubro->setCodRubro($cod);
        $validacion = $rubro->validarRubro();
        
        if($validacion == true){
           // var_dump($rubro);
            $daoRubro = new daoRubro();
            $daoRubro->modificar($rubro);
            header('location: ../vista/vistaAdmin.php'); 
        }else{
            $error = "";
            if($rubro->valNombre($nombre) == false){
                $error .= "<h3>El nombre sólo debe ser una palabrá con una inicial mayúsucula</h3>";
            }
            if($rubro->valDescripcion($desc) == false){
                $error .= "<h3>La descrición debe iniciar con una mayúsucula</h3>";
            }
            echo $error;
        }  

        //echo "<div style='margin-top: 10%; margin-left: 35%'><p style='font-size: 27px; font-weight: bold;'>Registro modificado exitosamente...</p></div>";
        //echo "<a style='margin-left: 47%; text-decoration: none; background-color: #0083B0; border-radius: 10px; padding: 7px; color: #fff; text-align: center; font-size: 17px; font-weight: bold;' href='../vista/CRUD.php'>Regresar</a>";
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