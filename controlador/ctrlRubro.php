<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Cuponera</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
    
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
        require '../modelo/Rubro/rubro.class.admin.php';
        require '../modelo/Rubro/daoRubro.admin.php';
        $daoRubro = new daoRubro();
        $rubro = new Rubro($nombre, $desc);
        $validacion = $rubro->validarRubro();
        if($validacion == true){
           // var_dump($rubro);
            $daoRubro = new daoRubro();
            $daoRubro->insertar($rubro);
            $_SESSION['mensaje'] = "Operación realizada con éxito.";
            header('location: ../vista/vistaAdmin.php'); 
        }else{
            $error = "";
            if($rubro->valNombre($nombre) == false){
                $error .= "<h3>El nombre sólo debe ser una palabra con una inicial mayúsucula</h3>";
            }
            if($rubro->valDescripcion($desc) == false){
                $error .= "<h3>La descrición debe iniciar con una mayúsucula</h3>";
            }
            echo $error;
        }  
    }

    if($id != "" && $accion=="Eliminar"){
        require '../modelo/Rubro/daoRubro.admin.php';
        $dao = new daoRubro();
        try{
            $dao->eliminar($id);
            $_SESSION['mensaje'] = "Operación realizada con éxito";
            header("location: ../vista/vistaAdmin.php");
        }catch(Exception $e){
            $_SESSION['error'] = "No se puede borrar, ya está relacionado con alguna empresa.";
            header("location: ../vista/vistaAdmin.php");
        }
    }

    if($id != "" && $accion=="Modificar"){
        require '../modelo/Rubro/daoRubro.admin.php';
        $dao = new daoRubro();
        $rubro = $dao->mostrarRubro($id);
        $html = <<<'EOD'
        <div>
        <div class="container">
            <form action="../controlador/ctrlRubro.php?accion=Modificar" method="post">
                <legend>Modificando Rubro</legend>
                <div class="form-group">
                    <label for="nombre">Nombre</label>
        EOD;
        echo $html;
        echo "<input type=\"hidden\" name=\"cod\" id=\"cod\" required value=" . $rubro['CodRubro'] . ">";
        echo "<input class=\"form-control\" type=\"text\" name=\"nombre\" id=\"nombre\" required value=" . $rubro['NombreRubro'] . ">";
        echo "</div>";
        echo "<div class=\"form-group\">";
        echo "<label for=\"desc\">Descripción</label>";
        echo "<textarea class=\"form-control\" name=\"desc\" id=\"desc\" required>" . $rubro['Descripcion'] . "</textarea>";
        echo "</div>";
        echo "<input class=\"btn btn-success\" type=\"submit\" name=\"registro\" value=\"Guardar datos\">";
        echo "<a class=\"btn btn-danger\" href=\"../vista/vistaAdmin.php\">Regresar</a>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
    }

    if($id == "" && $accion == "Modificar"){
        require '../modelo/Rubro/rubro.class.admin.php';
        require '../modelo/Rubro/daoRubro.admin.php';
        $daoRubro = new daoRubro();
        $rubro = new Rubro($nombre, $desc);
        $rubro->setCodRubro($cod);
        $validacion = $rubro->validarRubro();
        
        if($validacion == true){
           // var_dump($rubro);
            $daoRubro = new daoRubro();
            $daoRubro->modificar($rubro);
            $_SESSION['mensaje'] = "Operación realizada con éxito.";
            header('location: ../vista/vistaAdmin.php'); 
            
        }else{
            echo "<div class=\"container\">";
            $error = "";
            if($rubro->valNombre($nombre) == false){
                $error .= "<h3 class=\"alert alert-danger\">El nombre sólo debe ser una palabrá con una inicial mayúsucula</h3><br/>";
            }
            if($rubro->valDescripcion($desc) == false){
                $error .= "<h3 class=\"alert alert-danger\">>La descrición debe iniciar con una mayúsucula</h3>";
            }
            echo $error;
            echo "<a class=\"btn btn-danger\" href=\"". $_SERVER['PHP_SELF'] . "?accion=Modificar&id=" . $rubro->getCodRubro() . "\">Regresar</a>";
            echo "</div>";
        }  

        //echo "<div style='margin-top: 10%; margin-left: 35%'><p style='font-size: 27px; font-weight: bold;'>Registro modificado exitosamente...</p></div>";
        //echo "<a style='margin-left: 47%; text-decoration: none; background-color: #0083B0; border-radius: 10px; padding: 7px; color: #fff; text-align: center; font-size: 17px; font-weight: bold;' href='../vista/CRUD.php'>Regresar</a>";
    }
    }else{
?> 
    <div class="container">
        <h1>Debes ser ADMINISTRADOR para ver este página: <a href="../vista/login.php">Inicia Sesión</a></h1>
    </div>
<?php
    }
    require '../vista/footer.php';
?>    
</body>
</html>