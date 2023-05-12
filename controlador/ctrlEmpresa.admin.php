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
<?php
    require '../vista/menu.php';
    if(isset($_SESSION['CodUsuario']) && $_SESSION['CodRol'] == 1){
    //Controlador de datos que ingresan
    //Datos de la Empresa
    $cod = isset($_POST['cod'])?$_POST['cod']:"";
    $nomEmpre = isset($_POST['nombre'])?$_POST['nombre']:"";
    $porcentaje = isset($_POST['cargo'])?$_POST['cargo']:"";
    $rubroEmpre = isset($_POST['rubro'])?$_POST['rubro']:"";
    $dir = isset($_POST['direc'])?$_POST['direc']:"";
    $puedeImagen = isset($_POST['chec'])?$_POST['chec']:"";
    
    $image = isset($_FILES['image']['name'])?$_FILES['image']['name']:"";

    $codUsu = isset($_POST['codUsu'])?$_POST['codUsu']:"";
    $nomUsu = isset($_POST['nombreEncargado'])?$_POST['nombreEncargado']:"";
    $apeUsu = isset($_POST['apellidoEncargado'])?$_POST['apellidoEncargado']:"";
    
    $telefono = isset($_POST['telefono'])?$_POST['telefono']:"";
    $correo = isset($_POST['correo'])?$_POST['correo']:"";
    $contra = isset($_POST['contra'])?$_POST['contra']:"";
    $rol = isset($_POST['codRol'])?$_POST['codRol']:"";
    $verify = isset($_POST['verificado'])?$_POST['verificado']:"";
    
    $accion = isset($_REQUEST['accion'])?$_REQUEST['accion']:"";
    $id = isset($_GET['id'])?$_GET['id']:"";

               
    
    if($accion == ""){
        header("Location: ../../modulosProyecto/index.php");
    }    

    if($accion == "Registrar"){
        require '../modelo/Rubro/rubro.class.admin.php';
        require '../modelo/Rubro/daoRubro.admin.php';
        if(isset($_POST['accion'])){
            require '../modelo/Empresa/empresa.class.admin.php';
            //$nomEmpre, $porcentaje, $Rubro, $dir, $nom, $ape, $tel, $email, $role, $verify
            if($nomEmpre != "" && $porcentaje != "" && $rubroEmpre != "" && $dir != "" && 
                $nomUsu != "" && $apeUsu != "" && $telefono != "" && $correo != ""){
                    $rubroEmpre = intval($rubroEmpre);
                    $empresa = new Empresa($nomEmpre, $porcentaje, $rubroEmpre, $dir, $nomUsu, $apeUsu,
                    $telefono, $correo, 3, "Si");
                    $cod = $empresa->generarCodEmpresa();
                    $contra = $empresa->generarContraseña();
                    $empresa->setCodEmpresa($cod);
                    $empresa->setCodUsuario($cod);
                    $empresa->setIMG($image);
                    $empresa->setContraseña($contra);
                    $erroresEmpresa = $empresa->valEmpresa();
                    $erroresUsuario = $empresa->valUsuario($empresa->getCodEmpresa());

                    if(isset($empresa)){
                        require '../modelo/Empresa/daoEmpresas.admin.php';
                        if($empresa->seguroInsertarUsuario($empresa->getCodEmpresa()) == true && $empresa->seguroInsertarEmpresa()){
                            //var_dump($empresa);
                            $daoEmpresa= new daoEmpresa();
                            $valCorreo = $daoEmpresa->valEmail($empresa->getCorreo());
                            $valCorreo = intval($valCorreo['COUNT(*)']);
                            $valTel = $daoEmpresa->valTelefono($empresa->getTelefono());
                            $valTel = intval($valTel['COUNT(*)']);
                            $valNomEmpre = $daoEmpresa->valNombreEmpresa($empresa->getNombreEmpresa());
                            $valNomEmpre = intval($valNomEmpre['COUNT(*)']);
                            $errando = "";
                            if($valTel == 0){
                                if($valCorreo == 0){
                                    if($valNomEmpre == 0){
                                        if($daoEmpresa->insertarIMG($empresa->getCodEmpresa())){
                                            $daoEmpresa->insertarEmpresa($empresa);
                                            $daoEmpresa->insertarJefe($empresa);
                                            
                                            $titulo = "Empresa registrada";
                                            $mensaje .= "<h1>Credenciales</h1>";
                                            $mensaje .= "<h2>E-mail</h2><br>";
                                            $mensaje .= "<br>";
                                            $mensaje .= $empresa->getCorreo();
                                            $mensaje .= "<h2>Contraseña</h2><br>";
                                            $mensaje .= "<br>";
                                            $mensaje .= $empresa->getContraseña();
                                            $mensaje .= "<br><h1>Información de la empresa</h1>";
                                            $mensaje .= "Código de la Empresa: " . $empresa->getCodEmpresa() . "<br>";
                                            $mensaje .= "Nombre de la Empresa: " . $empresa->getNombreEmpresa() . "<br>";
                                            $mensaje .= "Porcentaje de comisión para la Empresa: " . $empresa->getPorcentajeComision() . "<br>";
                                            $mensaje .= "Dirección de la Empresa: " . $empresa->getDireccion() . "<br>";
                                            $mensaje .= "Administrador de la Empresa: " . $empresa->getApellido() . ", ". $empresa->getNombre() . "<br>";
                                            $mensaje .= "Telefono de referencia: " . $empresa->getTelefono() . "<br>";
                                            require '../modelo/correo/correo.class.php';
                                            try{
                                                $mail->send();
                                                //$daoEmpresa->notificar();
                                                $_SESSION['mensaje'] = "Operación realizada con éxito.";
                                                header('location: ../vista/vistaAdmin.php?gestion=Empresas');
                                            } catch (Exception $e) {
                                                echo "No se pudo debido a: {$mail->ErrorInfo}";
                                            }
                                            
                                        }
                                    }else{
                                        $errando .= "<p>Por favor, ingrese un nombre de empresa único, ese ya ha sido tomado</p>";
                                    }
                                }else{
                                    $errando .= "<p>Por favor, ingrese un correo único, ese ya está tomado</p>";
                                }
                            }else{
                                $errando .= "<p>Ingrese un número telefónico único, el ingresado ya esta usado</p>";
                            }
                            
                        }
                    }
                }else{
                    $errando = "<p>No deje campos en blanco.</p>";
                }
            
                //var_dump($empresa);
                //if()
        }
        $iUsu = 0;
        $iEmp = 0;
    ?>
    <div class="container">
    <form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
        <legend>Registrando nueva Empresa</legend>
        <section>
            <article>
                <h1>Detalles Empresa</h1>
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input class="form-control" type="text" name="nombre" id="nombre" value="<?=$nomEmpre?>">
                    <?php
                        if(isset($erroresEmpresa) && $erroresEmpresa[$iEmp] != ""){
                            echo "<div class=\"alert alert-danger\">" . $erroresEmpresa[$iEmp] . "</div>";          
                        }
                        $iEmp++;
                    ?>
                </div>
                <div>
                    <label for="file">Imágen</label>
                    <input class="form-control" type="file" name="image"/>
                    <?php
                        if(isset($erroresUsuario) && $erroresUsuario[$iUsu] != ""){
                            echo "<div class=\"alert alert-danger\">" . $erroresUsuario[$iUsu] . "</div>";                    
                        }
                        $iUsu++;
                    ?>
                </div>
                <div>
                    <label for="direc">Dirección</label>
                    <input class="form-control" type="text" name="direc" value="<?=$dir?>">
                    <?php
                        if(isset($erroresEmpresa) && $erroresEmpresa[$iEmp] != ""){
                            echo "<div class=\"alert alert-danger\">" . $erroresEmpresa[$iEmp] . "</div>";                    
                        }
                        $iEmp++;
                    ?>
                </div>
                <div>
                    <label for="rubro">Rubro</label>
                    <select class="form-control" name="rubro" id="rubro">
                        <?php
                            $dao = new daoRubro();
                            $rubros = $dao->listaRubro();
                            foreach($rubros as $rubro){
                                if($rubroEmpre == $rubro['CodRubro']){
                                    echo "<option value=\"". $rubro['CodRubro'] . "\" selected>" . $rubro['NombreRubro'] . "</option>";
                                }else{
                                    echo "<option value=\"". $rubro['CodRubro'] . "\">" . $rubro['NombreRubro'] . "</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="cargo">Porcentaje de comisión:</label>
                    <input class="form-control" type="number" name="cargo" id="" value="<?=$porcentaje?>">
                </div>
            </article>
            <article>
                <h1>Detalles Encargado</h1>
                <div>
                    <label for="nombreEncargado">Nombres</label>
                    <input class="form-control" type="text" name="nombreEncargado" id="nombre" value="<?=$nomUsu?>">
                    <?php
                        if(isset($erroresUsuario) && $erroresUsuario[$iUsu] != ""){
                            echo "<div class=\"alert alert-danger\">" . $erroresUsuario[$iUsu] . "</div>";           
                        }
                        $iUsu++;
                    ?>
                </div>
                <div>
                    <label for="apellidoEncargado">Apellidos</label>
                    <input class="form-control" type="text" name="apellidoEncargado" id="nombre" value="<?=$apeUsu?>">
                    <?php
                        if(isset($erroresUsuario) && $erroresUsuario[$iUsu] != ""){
                            echo "<div class=\"alert alert-danger\">" . $erroresUsuario[$iUsu] . "</div>";            
                        }
                        $iUsu++;
                    ?>
                </div>
                <div>
                    <label for="telefono">Telefono</label>
                    <input class="form-control" type="text" name="telefono" id="nombre" value="<?=$telefono?>">
                    <?php
                        if(isset($erroresUsuario) && $erroresUsuario[$iUsu] != ""){
                            echo "<div class=\"alert alert-danger\">" . $erroresUsuario[$iUsu] . "</div>";            
                        }
                        $iUsu++;
                    ?>
                </div>
                <div>
                    <label for="correo">Correo</label>
                    <input class="form-control" type="email" name="correo" id="correo" value="<?=$correo?>">
                    <?php
                        if(isset($erroresUsuario) && $erroresUsuario[$iUsu] != ""){
                            echo "<div class=\"alert alert-danger\">" . $erroresUsuario[$iUsu] . "</div>";           
                        }
                        $iUsu++;
                    ?>
                </div>
                
            </article>
        </section>
        <br>
        <input class="btn btn-success col-md-4 m-2" type="submit" value="Registrar" name= "accion">
        <a class="btn btn-danger col-md-4 m-2" href="../vista/vistaAdmin.php?gestion=Empresas">Regresar</a>
    </form>
    <?php
        if(isset($errando) && $errando != ""){
    ?>  
        <br>
        <div class="alert alert-danger alert-dismissible" role="alert">
                <?=$errando?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
        </div>
    <?php  
        }
    ?>
    </div>
    <?php
    }

    if($id != "" && $accion=="Eliminar"){
        require '../modelo/Empresa/daoEmpresas.admin.php';
        $dao = new daoEmpresa();
        $empre = $dao->mostrarEmpresa($id);
        $dao->eliminarJefe($empre['CodUsuario']);
        $dao->eliminar($empre['CodEmpresa']);
        $_SESSION['mensaje'] = "Operación realizada con éxito.";
        header('location: ../vista/vistaAdmin.php?gestion=Empresas');
    }

    if($id != "" && $accion=="Modificar"){
        require '../modelo/Rubro/rubro.class.admin.php';
        require '../modelo/Rubro/daoRubro.admin.php';
        require '../modelo/Empresa/daoEmpresas.admin.php';
        $daoEmpresa= new daoEmpresa();
        $empre = $daoEmpresa->mostrarEmpresa($id);
        if($empre != false){
            if(isset($_POST['accion'])){
            var_dump($puedeImagen);
            require '../modelo/Empresa/empresa.class.admin.php';
            //$nomEmpre, $porcentaje, $Rubro, $dir, $nom, $ape, $tel, $email, $role, $verify
            if($nomEmpre != "" && $porcentaje != "" && $rubroEmpre != "" && $dir != "" && 
                $nomUsu != "" && $apeUsu != "" && $telefono != "" && $correo != ""){
                    $rubroEmpre = intval($rubroEmpre);
                    $empresa = new Empresa($nomEmpre, $porcentaje, $rubroEmpre, $dir, $nomUsu, $apeUsu,
                    $telefono, $correo, 3, "Si");
                    //$cod = $empresa->generarCodEmpresa();
                    //$contra = $empresa->generarContraseña();
                    $empresa->setCodEmpresa($cod);
                    $empresa->setCodUsuario($codUsu);
                    if($puedeImagen != ""){
                        $empresa->setIMG($image);
                        $erroresUsuario = $empresa->valUsuario($empresa->getCodEmpresa());
                        $valUsuario = $empresa->seguroInsertarUsuario($empresa->getCodEmpresa());
                    }else{
                        $erroresUsuario = $empresa->valUsuarioAlter();
                        $valUsuario = $empresa->seguroInsertarUsuarioAlter();
                    }
                    
                    //$empresa->setContraseña($contra);
                    $erroresEmpresa = $empresa->valEmpresa();
                    

                    if(isset($empresa)){
                        
                        if($valUsuario == true && $empresa->seguroInsertarEmpresa()){
                            //var_dump($empresa);
                            
                            $valCorreo = $daoEmpresa->valEmail($empresa->getCorreo());
                            $valCorreo = intval($valCorreo['COUNT(*)']);
                            $valTel = $daoEmpresa->valTelefono($empresa->getTelefono());
                            $valTel = intval($valTel['COUNT(*)']);
                            $valNomEmpre = $daoEmpresa->valNombreEmpresa($empresa->getNombreEmpresa());
                            $valNomEmpre = intval($valNomEmpre['COUNT(*)']);
                            $errando = "";
                            if($valTel <= 1){
                                if($valCorreo <= 1){
                                    if($valNomEmpre <= 1){

                                        if($puedeImagen != ""){
                                            if($daoEmpresa->insertarIMG($empresa->getCodEmpresa())){
                                                $daoEmpresa->modificarEmpresa($empresa);
                                                $daoEmpresa->modificarJefeFoto($empresa);
                                                
                                                $titulo = "Empresa modificada";
                                                $mensaje .= "<h1>Credenciales</h1>";
                                                $mensaje .= "<h2>E-mail</h2><br>";
                                                $mensaje .= "<br>";
                                                $mensaje .= $empresa->getCorreo();
                                                $mensaje .= "<h2>Contraseña</h2><br>";
                                                $mensaje .= "<br>";
                                                $mensaje .= "La misma con la que se registro<br>";
                                                $mensaje .= "<br><h1>Información de la empresa</h1>";
                                                $mensaje .= "<br>Se modificó la imágen<br>";
                                                $mensaje .= "Código de la Empresa: " . $empresa->getCodEmpresa() . "<br>";
                                                $mensaje .= "Nombre de la Empresa: " . $empresa->getNombreEmpresa() . "<br>";
                                                $mensaje .= "Porcentaje de comisión para la Empresa: " . $empresa->getPorcentajeComision() . "<br>";
                                                $mensaje .= "Dirección de la Empresa: " . $empresa->getDireccion() . "<br>";
                                                $mensaje .= "Administrador de la Empresa: " . $empresa->getApellido() . ", ". $empresa->getNombre() . "<br>";
                                                $mensaje .= "Telefono de referencia: " . $empresa->getTelefono() . "<br>";
                                                require '../modelo/correo/correo.class.php';
                                                try{
                                                    $mail->send();
                                                    //$daoEmpresa->notificar();
                                                    $_SESSION['mensaje'] = "Operación realizada con éxito.";
                                                    header('location: ../vista/vistaAdmin.php?gestion=Empresas');
                                                } catch (Exception $e) {
                                                    echo "No se pudo debido a: {$mail->ErrorInfo}";
                                                }
                                            }
                                        }else{
                                            $daoEmpresa->modificarEmpresa($empresa);
                                            $daoEmpresa->modificarJefe($empresa);
                                            
                                            $titulo = "Empresa modificada";
                                                $mensaje .= "<h1>Credenciales</h1>";
                                                $mensaje .= "<h2>E-mail</h2><br>";
                                                $mensaje .= "<br>";
                                                $mensaje .= $empresa->getCorreo();
                                                $mensaje .= "<h2>Contraseña</h2><br>";
                                                $mensaje .= "<br>";
                                                $mensaje .= "La misma con la que se registro<br>";
                                                $mensaje .= "<br><h1>Información de la empresa</h1>";
                                                $mensaje .= "Código de la Empresa: " . $empresa->getCodEmpresa() . "<br>";
                                                $mensaje .= "Nombre de la Empresa: " . $empresa->getNombreEmpresa() . "<br>";
                                                $mensaje .= "Porcentaje de comisión para la Empresa: " . $empresa->getPorcentajeComision() . "<br>";
                                                $mensaje .= "Dirección de la Empresa: " . $empresa->getDireccion() . "<br>";
                                                $mensaje .= "Administrador de la Empresa: " . $empresa->getApellido() . ", ". $empresa->getNombre() . "<br>";
                                                $mensaje .= "Telefono de referencia: " . $empresa->getTelefono() . "<br>";
                                                require '../modelo/correo/correo.class.php';
                                                try{
                                                    $mail->send();
                                                    //$daoEmpresa->notificar();
                                                    $_SESSION['mensaje'] = "Operación realizada con éxito.";
                                                    header('location: ../vista/vistaAdmin.php?gestion=Empresas');
                                                } catch (Exception $e) {
                                                    echo "No se pudo debido a: {$mail->ErrorInfo}";
                                                }
                                        }
                                    }else{
                                        $errando .= "<p>Por favor, ingrese un nombre de empresa único, ese ya ha sido tomado</p>";
                                    }
                                }else{
                                    $errando .= "<p>Por favor, ingrese un correo único, ese ya está tomado</p>";
                                }
                            }else{
                                $errando .= "<p>Ingrese un número telefónico único, el ingresado ya esta usado</p>";
                            }
                            
                        }
                    }
                }else{
                    $errando = "<p>No deje campos en blanco.</p>";
                }
            
                //var_dump($empresa);
                //if()
        }
        $iUsu = 0;
        $iEmp = 0;
    ?>
    <div class="container">
    <form action="<?=$_SERVER['PHP_SELF']."?id=".$id?>" method="post" enctype="multipart/form-data">
        <legend>Registrando nueva Empresa</legend>
                <h1>Detalles Empresa</h1>
                <div class="form-group">
                    <input type="hidden" name="cod" value="<?=$empre['CodEmpresa']?>">
                    <input type="hidden" name="codUsu" value="<?=$empre['CodUsuario']?>">
                    <label for="nombre">Nombre</label>
                    <input class="form-control" type="text" name="nombre" id="nombre" value="<?=$empre['NombreEmpresa']?>">
                    <?php
                        if(isset($erroresEmpresa) && $erroresEmpresa[$iEmp] != ""){
                            echo "<div class=\"alert alert-danger\">" . $erroresEmpresa[$iEmp] . "</div>";          
                        }
                        $iEmp++;
                    ?>
                </div>
                <div class="form-group">
                    <div class="rounded  img-fluid"><img src="../vista/img/<?=$empre['Foto']?>" alt="<?=$empre['Foto']?>" width="300">
                    </div>
                    <label for="file">Imágen</label>
                        <input name="chec" type="checkbox" id="chec" value="probar" onChange="comprobar(this);"/>
                        <label for="chec">Cambiar Logo/Imágen</label>
                        <div class="form-group" id="boton" readonly style="display:none">
                            <input class="form-control"type="file" name="image"/>
                        </div>
                    
                    <?php
                        if(isset($erroresUsuario) && $erroresUsuario[$iUsu] != ""){
                            echo "<div class=\"alert alert-danger\">" . $erroresUsuario[$iUsu] . "</div>";            
                        }
                        $iUsu++;
                    ?>
                </div>
                <div class="form-group">
                    <label for="direc">Dirección</label>
                    <input class="form-control" type="text" name="direc" value="<?=$empre['Direccion']?>">
                    <?php
                        if(isset($erroresEmpresa) && $erroresEmpresa[$iEmp] != ""){
                            echo "<div class=\"alert alert-danger\">" . $erroresEmpresa[$iEmp] . "</div>";          
                        }
                        $iEmp++;
                    ?>
                </div>
                <div class="form-group">
                    <label for="rubro">Rubro</label>
                    <select class="form-control" name="rubro" id="rubro">
                        <?php
                            $dao = new daoRubro();
                            $rubros = $dao->listaRubro();
                            foreach($rubros as $rubro){
                                if($empre['NombreRubro']== $rubro['NombreRubro']){
                                    echo "<option value=\"". $rubro['CodRubro'] . "\" selected>" . $rubro['NombreRubro'] . "</option>";
                                }else{
                                    echo "<option value=\"". $rubro['CodRubro'] . "\">" . $rubro['NombreRubro'] . "</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cargo">Porcentaje de comisión:</label>
                    <input class="form-control" type="number" name="cargo" id="" value="<?=$empre['PorcentajeComision']?>">
                </div>
                <h1>Detalles Encargado</h1>
                <div class="form-group">
                    <label for="nombreEncargado">Nombres</label>
                    <input class="form-control" type="text" name="nombreEncargado" id="nombre" value="<?=$empre['Nombre']?>">
                    <?php
                        if(isset($erroresUsuario) && $erroresUsuario[$iUsu] != ""){
                            echo "<div class=\"alert alert-danger\">" . $erroresUsuario[$iUsu] . "</div>";            
                        }
                        $iUsu++;
                    ?>
                </div>
                <div class="form-group">
                    <label for="apellidoEncargado">Apellidos</label>
                    <input class="form-control" type="text" name="apellidoEncargado" id="nombre" value="<?=$empre['Apellido']?>">
                    <?php
                        if(isset($erroresUsuario) && $erroresUsuario[$iUsu] != ""){
                            echo "<div class=\"alert alert-danger\">" . $erroresUsuario[$iUsu] . "</div>";            
                        }
                        $iUsu++;
                    ?>
                </div>
                <div class="form-group">
                    <label for="telefono">Telefono</label>
                    <input class="form-control" type="text" name="telefono" id="nombre" value="<?=$empre['Telefono']?>">
                    <?php
                        if(isset($erroresUsuario) && $erroresUsuario[$iUsu] != ""){
                            echo "<div class=\"alert alert-danger\">" . $erroresUsuario[$iUsu] . "</div>";            
                        }
                        $iUsu++;
                    ?>
                </div>
                <div class="form-group">
                    <label for="correo">Correo</label>
                    <input class="form-control" type="email" name="correo" id="correo" value="<?=$empre['Correo']?>">
                    <?php
                        if(isset($erroresUsuario) && $erroresUsuario[$iUsu] != ""){
                            echo "<div class=\"alert alert-danger\">" . $erroresUsuario[$iUsu] . "</div>";            
                        }
                        $iUsu++;
                    ?>
                </div>
                <br>
        <input class="btn btn-success" type="submit" value="Modificar" name= "accion">
        <a class="btn btn-danger" href="../vista/vistaAdmin.php?gestion=Empresas">Regresar</a>
    </form>
    <?php
        if(isset($errando) && $errando != ""){
    ?>  
        <br>
        <div class="alert alert-danger alert-dismissible" role="alert">
                <?=$errando?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
        </div>
    <?php  
        }

        }else{
            echo "<div class=\"container\">
            <div class=\"alert alert-danger alert-dismissible\">
            <h1>Seleccione una empresa registrada</h1></div></div>";
        }
        
        
    }
    ?>

    </div>
<?php
}else{
?>
    <div class="container">
        <h1>Debes ser ADMINISTRADOR para ver este página: <a href="../vista/login.php">Inicia Sesión</a></h1>
    </div>
<?php
    }
    require '../vista/footer.php';
?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
<script src="../vista/js/boton.js"></script>
</html>