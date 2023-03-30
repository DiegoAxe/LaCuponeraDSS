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
        require '../modelo/Rubro/rubro.class.php';
        require '../modelo/Rubro/daoRubro.php';
        if(isset($_POST['accion'])){
            require '../modelo/Empresa/empresa.class.php';
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
                        require '../modelo/Empresa/daoEmpresas.php';
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
                                            //$daoEmpresa->notificar();
                                            echo "SUUU";
                                            header('location: ../vista/vistaAdmin.php?gestion=Empresas');
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
    <form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
        <legend>Registrando nueva Empresa</legend>
        <section>
            <article>
                <h1>Detalles Empresa</h1>
                <div>
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" value="<?=$nomEmpre?>">
                    <?php
                        if(isset($erroresEmpresa)){
                            echo $erroresEmpresa[$iEmp];          
                        }
                        $iEmp++;
                    ?>
                </div>
                <div>
                    <label for="file">Imágen</label>
                    <input type="file" name="image"/>
                    <?php
                        if(isset($erroresUsuario)){
                            echo $erroresUsuario[$iUsu];          
                        }
                        $iUsu++;
                    ?>
                </div>
                <div>
                    <label for="direc">Dirección</label>
                    <input type="text" name="direc" value="<?=$dir?>">
                    <?php
                        if(isset($erroresEmpresa)){
                            echo $erroresEmpresa[$iEmp];          
                        }
                        $iEmp++;
                    ?>
                </div>
                <div>
                    <label for="rubro">Rubro</label>
                    <select name="rubro" id="rubro">
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
                    <input type="number" name="cargo" id="" value="<?=$porcentaje?>">
                </div>
            </article>
            <article>
                <h1>Detalles Encargado</h1>
                <div>
                    <label for="nombreEncargado">Nombres</label>
                    <input type="text" name="nombreEncargado" id="nombre" value="<?=$nomUsu?>">
                    <?php
                        if(isset($erroresUsuario)){
                            echo $erroresUsuario[$iUsu];          
                        }
                        $iUsu++;
                    ?>
                </div>
                <div>
                    <label for="apellidoEncargado">Apellidos</label>
                    <input type="text" name="apellidoEncargado" id="nombre" value="<?=$apeUsu?>">
                    <?php
                        if(isset($erroresUsuario)){
                            echo $erroresUsuario[$iUsu];          
                        }
                        $iUsu++;
                    ?>
                </div>
                <div>
                    <label for="telefono">Telefono</label>
                    <input type="text" name="telefono" id="nombre" value="<?=$telefono?>">
                    <?php
                        if(isset($erroresUsuario)){
                            echo $erroresUsuario[$iUsu];          
                        }
                        $iUsu++;
                    ?>
                </div>
                <div>
                    <label for="correo">Correo</label>
                    <input type="email" name="correo" id="correo" value="<?=$correo?>">
                    <?php
                        if(isset($erroresUsuario)){
                            echo $erroresUsuario[$iUsu];          
                        }
                        $iUsu++;
                    ?>
                </div>
                
            </article>
        </section>
        <input type="submit" value="Registrar" name= "accion">
    </form>
    <?php
        if(isset($errando)) echo $errando;
    }

    if($id != "" && $accion=="Eliminar"){
        require '../modelo/Empresa/daoEmpresas.php';
        $dao = new daoEmpresa();
        $empre = $dao->mostrarEmpresa($id);
        $dao->eliminarJefe($empre['CodUsuario']);
        $dao->eliminar($empre['CodEmpresa']);
        header('location: ../vista/vistaAdmin.php?gestion=Empresas');
    }

    if($id != "" && $accion=="Modificar"){
        require '../modelo/Rubro/rubro.class.php';
        require '../modelo/Rubro/daoRubro.php';
        require '../modelo/Empresa/daoEmpresas.php';
        $daoEmpresa= new daoEmpresa();
        $empre = $daoEmpresa->mostrarEmpresa($id);
        if($empre != false){
            if(isset($_POST['accion'])){
            var_dump($puedeImagen);
            require '../modelo/Empresa/empresa.class.php';
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
                                                header('location: ../vista/vistaAdmin.php?gestion=Empresas');
                                            }
                                        }else{
                                            $daoEmpresa->modificarEmpresa($empresa);
                                            $daoEmpresa->modificarJefe($empresa);
                                            header('location: ../vista/vistaAdmin.php?gestion=Empresas');
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
    <form action="<?=$_SERVER['PHP_SELF']."?id=".$id?>" method="post" enctype="multipart/form-data">
        <legend>Registrando nueva Empresa</legend>
        <section>
            <article>
                <h1>Detalles Empresa</h1>
                <div>
                    <input type="hidden" name="cod" value="<?=$empre['CodEmpresa']?>">
                    <input type="hidden" name="codUsu" value="<?=$empre['CodUsuario']?>">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" value="<?=$empre['NombreEmpresa']?>">
                    <?php
                        if(isset($erroresEmpresa)){
                            echo $erroresEmpresa[$iEmp];          
                        }
                        $iEmp++;
                    ?>
                </div>
                <div>
                    <label for="file">Imágen</label>
                        <input name="chec" type="checkbox" id="chec" value="probar" onChange="comprobar(this);"/>
                        <label for="chec">Cambiar Logo/Imágen</label>
                        <div id="boton" readonly style="display:none">
                            <input type="file" name="image"/>
                        </div>
                    
                    <?php
                        if(isset($erroresUsuario)){
                            echo $erroresUsuario[$iUsu];          
                        }
                        $iUsu++;
                    ?>
                </div>
                <div>
                    <label for="direc">Dirección</label>
                    <input type="text" name="direc" value="<?=$empre['Direccion']?>">
                    <?php
                        if(isset($erroresEmpresa)){
                            echo $erroresEmpresa[$iEmp];          
                        }
                        $iEmp++;
                    ?>
                </div>
                <div>
                    <label for="rubro">Rubro</label>
                    <select name="rubro" id="rubro">
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
                <div>
                    <label for="cargo">Porcentaje de comisión:</label>
                    <input type="number" name="cargo" id="" value="<?=$empre['PorcentajeComision']?>">
                </div>
            </article>
            <article>
                <h1>Detalles Encargado</h1>
                <div>
                    <label for="nombreEncargado">Nombres</label>
                    <input type="text" name="nombreEncargado" id="nombre" value="<?=$empre['Nombre']?>">
                    <?php
                        if(isset($erroresUsuario)){
                            echo $erroresUsuario[$iUsu];          
                        }
                        $iUsu++;
                    ?>
                </div>
                <div>
                    <label for="apellidoEncargado">Apellidos</label>
                    <input type="text" name="apellidoEncargado" id="nombre" value="<?=$empre['Apellido']?>">
                    <?php
                        if(isset($erroresUsuario)){
                            echo $erroresUsuario[$iUsu];          
                        }
                        $iUsu++;
                    ?>
                </div>
                <div>
                    <label for="telefono">Telefono</label>
                    <input type="text" name="telefono" id="nombre" value="<?=$empre['Telefono']?>">
                    <?php
                        if(isset($erroresUsuario)){
                            echo $erroresUsuario[$iUsu];          
                        }
                        $iUsu++;
                    ?>
                </div>
                <div>
                    <label for="correo">Correo</label>
                    <input type="email" name="correo" id="correo" value="<?=$empre['Correo']?>">
                    <?php
                        if(isset($erroresUsuario)){
                            echo $erroresUsuario[$iUsu];          
                        }
                        $iUsu++;
                    ?>
                </div>
                
            </article>
        </section>
        <input type="submit" value="Modificar" name= "accion">
    </form>
    <?php
        if(isset($errando)) echo $errando;

        }else{
            echo "<h1>Seleccione una empresa registrada</h1>";
        }
        
        
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
<script src="../vista/js/boton.js"></script>
</html>