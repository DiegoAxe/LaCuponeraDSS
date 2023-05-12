<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--    <link rel="stylesheet" href="css/estiloRegistro.css">-->
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
    //error_reporting(0);
    //Controlador de datos que ingresan

    //login
    $nombre = isset($_POST['nom'])?$_POST['nom']:"";
    $apellido = isset($_POST['ape'])?$_POST['ape']:"";
    $idUsuario = isset($_POST['idUsuario'])?$_POST['idUsuario']:"";
    $eMail = isset($_POST['correo'])?$_POST['correo']:"";
    $contras = isset($_POST['contra'])?$_POST['contra']:"";
    $contras = hash('sha512', $contras);
    $accion = isset($_REQUEST['accion'])?$_REQUEST['accion']:"";
    $id = isset($_GET['id'])?$_GET['id']:"";

    //registro
    $cod = isset($_POST['cod'])?$_POST['cod']:"";
    $nombreRegistro = isset($_POST['nombre'])?$_POST['nombre']:"";
    $apellidoRegistro = isset($_POST['apellido'])?$_POST['apellido']:"";
    $tel = isset($_POST['telefono'])?$_POST['telefono']:"";
    $emailRegistro = isset($_POST['email'])?$_POST['email']:"";
    $direccion = isset($_POST['direccion'])?$_POST['direccion']:"";
    $dui = isset($_POST['dui'])?$_POST['dui']:"";
    $pass = isset($_POST['pass'])?$_POST['pass']:"";
    $pass = hash('sha512', $pass);

/*if($accion == ""){
        header("Location: ../modulosProyecto/index.php");
    }  */

    if($accion == "Iniciar"){
        if(!empty($eMail) || !empty($contras)){

            if(preg_match('/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/',$eMail)!= 0){
                //require_once '../modelo/claseUsuario.php';
                require_once '../modelo/Usuario/daoUsuario.php';
                $dao = new DaoUsuario();
                $val = $dao->validar($eMail);       
                if($val == 1){
                    $usuario = $dao->iniciar($eMail);
                    if(isset($usuario["Contrasena"]) && $usuario['Contrasena'] == $contras){
                        switch($usuario['CodRol']){
                            case 1:
                                session_start();
                                $_SESSION['CodUsuario'] = $usuario['CodUsuario'];
                                $_SESSION['Nombre'] = $usuario['Nombre'];
                                $_SESSION['Foto'] = $usuario['Foto'];
                                $_SESSION['Apellido'] = $usuario['Apellido'];
                                $_SESSION['Telefono'] = $usuario['Telefono'];
                                $_SESSION['Correo'] = $usuario['Correo'];
                                $_SESSION['CodRol'] = $usuario['CodRol'];
                                $_SESSION['Rol'] = 'Administrador';
                                $_SESSION['Verificado'] = $usuario['Verificado'];
                                header('location: ../vista/index.php');
                                break;
                            case 2:
                                session_start();
                                $_SESSION['CodUsuario'] = $usuario['CodUsuario'];
                                $_SESSION['Nombre'] = $usuario['Nombre'];
                                $_SESSION['Foto'] = $usuario['Foto'];
                                $_SESSION['Apellido'] = $usuario['Apellido'];
                                $_SESSION['Telefono'] = $usuario['Telefono'];
                                $_SESSION['Correo'] = $usuario['Correo'];
                                $_SESSION['CodRol'] = $usuario['CodRol'];
                                $_SESSION['Rol'] = 'Cliente';
                                $_SESSION['Verificado'] = $usuario['Verificado'];
                                header('location: ../vista/index.php');
                                break;
                            case 3:
                                session_start();
                                $_SESSION['CodUsuario'] = $usuario['CodUsuario'];
                                $_SESSION['Nombre'] = $usuario['Nombre'];
                                $_SESSION['Foto'] = $usuario['Foto'];
                                $_SESSION['Apellido'] = $usuario['Apellido'];
                                $_SESSION['Telefono'] = $usuario['Telefono'];
                                $_SESSION['Correo'] = $usuario['Correo'];
                                $_SESSION['CodRol'] = $usuario['CodRol'];
                                $_SESSION['Rol'] = 'Empresa';
                                $_SESSION['CodEmpresa'] = $usuario['CodEmpresa'];
                                $_SESSION['Verificado'] = $usuario['Verificado'];
                                header('location: ../vista/index.php');
                                break;
                            case 4:
                                session_start();
                                $_SESSION['CodUsuario'] = $usuario['CodUsuario'];
                                $_SESSION['Nombre'] = $usuario['Nombre'];
                                $_SESSION['Foto'] = $usuario['Foto'];
                                $_SESSION['Apellido'] = $usuario['Apellido'];
                                $_SESSION['Telefono'] = $usuario['Telefono'];
                                $_SESSION['Correo'] = $usuario['Correo'];
                                $_SESSION['CodRol'] = $usuario['CodRol'];
                                $_SESSION['Rol'] = 'Empleado';
                                $_SESSION['Verificado'] = $usuario['Verificado'];
                                header('location: ../vista/index.php');
                                break;
                            default:
                                //No se como llegaria aqui, pero ERROR
                                session_destroy();
                        }
                    
                    }else{
                        echo "<h1 style='margin-top: 15px; margin-left: 35%'>No se ha podido Iniciar Sesión:<h1>";
                        echo "<h1 style='margin-left:34%;'>La Contraseña no coincide</h1>";
                        echo "</h1><br/><a style='margin-left:46%; padding:7px; background-color: #0074d9; text-decoration: none; color: white; border-radius: 10px;' href='../vista/login.php'>Volver a Intentar</a>";
                        exit();
                    }
                }else{
                    echo "<h1 style='margin-top: 15px; margin-left: 35%'>No se ha podido Iniciar Sesión:<h1>";
                    echo "<h1 style='margin-left:34%;'>Usuario No Registrado</h1>";
                    echo "</h1><br/><a style='margin-left:46%; padding:7px; background-color: #0074d9; text-decoration: none; color: white; border-radius: 10px;' href='../vista/login.php'>Volver a Intentar</a>";
                    exit();
                }
            }else{
                echo "<h1 style='margin-top: 15px; margin-left: 35%'>No se ha podido Iniciar Sesión:<h1>";
                echo "<h1 style='margin-left:34%;'>Digite un correo válido</h1>";
                echo "</h1><br/><a style='margin-left:46%; padding:7px; background-color: #0074d9; text-decoration: none; color: white; border-radius: 10px;' href='../vista/login.php'>Volver a Intentar</a>";
                exit();
            }
        }
    } elseif ($accion == "Registrar"){
            require '../modelo/Usuario/usuario.class.php';
            require  '../modelo/Usuario/daoUsuario.php';
            if(isset($_POST['accion'])){
                if($nombreRegistro != "" && $apellidoRegistro != "" && $tel != "" && $emailRegistro != "" && $direccion != "" && $dui != "" && $pass != ""){
                    $cliente = new Usuario($nombreRegistro, $apellidoRegistro, $tel, $emailRegistro, $direccion, $dui, 2 );
                    $cliente->setCodUsuario($cod);
                    $cliente->setContraseña($pass);
                    $erroresCliente = $cliente->ValUsuario();

//                    $erroresUsuario = $cliente->valUsuario($cliente->getCodUsuario());

                    if(isset($cliente)){
                        require_once '../modelo/Usuario/daoUsuario.php';
                        if($cliente->seguroInsertarUsuario() == true){
                            $daoUsuario = new daoUsuario();
//                            $valCorreo = $daoUsuario->validar($cliente->getCorreo());
//                            $valCorreo = intval($valCorreo['COUNT(*)']);
//                            $valTel = $daoUsuario->valTelefono($cliente->getTelefono());
//                            $valTel = intval($valTel['COUNT(*)']);
//                            $valNombre = $daoUsuario->valNombreUsuario($cliente->getNombre());
//                            $valNombre = intval($valNombre['COUNT(*)']);
//                            $errando = "";*/


                            //verificar correo
                            $registro = new daoUsuario();
                            $duiUnico = $registro->verifyDui($dui);
                            $correoUnico = $registro->validar2($emailRegistro);

                            if(!$correoUnico || !$duiUnico){
                                $daoUsuario->insertar($cliente);
                                echo "<h1>EL CLIENTE HA SIDO REGISTRADO EXITOSAMENTE</h1>";
                                header('location: ../vista/index.php');
                            }else{
                                echo '
                                     <script>
                                        alert("Este correo o DUI ya estan registrados, por favor verifique y use otros. Gracias");
                                        window.location= "../vista/registro.php";
                                     </script>
                                ';
                                exit();
                            }

                            //verificar DUI



                        }
                         }
                     }
            }

    }
$iUsu = 0;
$iEmp = 0;

?>
<!--Registro-->
<section class="vh-100" style="background-color: #eee;">
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-12 col-xl-11">
                <div class="card text-black" style="border-radius: 25px;">
                    <div class="card-body p-md-5">
                        <div class="row justify-content-center">
                            <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Registro</p>

                                <form class="mx-1 mx-md-4" action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">


                                    <div class="d-flex flex-row align-items-center mb-4" >
                                        <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input type="text" id="form3Example1c" name="nombre" placeholder="Nombre" class="form-control" value="<?=$nombreRegistro?>"/>
                                            <?php
                                            if(isset($erroresCliente)){
                                                echo $erroresCliente[$iEmp];
                                            }
                                            $iEmp++;
                                            ?>
                                            <label class="form-label" for="form3Example1c">Ingresa tu nombre</label>
                                        </div>
                                    </div>



                                    <div class="d-flex flex-row align-items-center mb-4" >
                                        <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input type="text" id="form3Example1c" type="text" name="apellido" placeholder="Apellido" class="form-control" value="<?=$apellidoRegistro?>"/>
                                            <?php
                                            if(isset($erroresCliente)){
                                                echo $erroresCliente[$iEmp];
                                            }
                                            $iEmp++;
                                            ?>
                                            <label class="form-label" for="form3Example1c">Ingresa tu apellido</label>
                                        </div>
                                    </div>



                                    <div class="d-flex flex-row align-items-center mb-4" >
                                        <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input type="tel" id="form3Example1c" name="telefono" placeholder="Telefono" class="form-control" value="<?=$tel?>"/>
                                            <?php
                                            if(isset($erroresCliente)){
                                                echo $erroresCliente[$iEmp];
                                            }
                                            $iEmp++;
                                            ?>
                                            <label class="form-label" for="form3Example1c">Ingresa tu telefono</label>
                                        </div>
                                    </div>



                                    <div class="d-flex flex-row align-items-center mb-4" >
                                        <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input type="email" id="form3Example1c" name="email" placeholder="Email" class="form-control" value="<?=$emailRegistro?>" />
                                            <?php
                                            if(isset($erroresCliente)){
                                                echo $erroresCliente[$iEmp];
                                            }
                                            $iEmp++;
                                            ?>
                                            <label class="form-label" for="form3Example1c">Ingresa tu correo</label>
                                        </div>
                                    </div>



                                    <div class="d-flex flex-row align-items-center mb-4" >
                                        <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input type="text" id="form3Example1c" name="direccion" placeholder="Direccion" class="form-control" value="<?=$direccion?>" />
                                            <?php
                                            if(isset($erroresCliente)){
                                                echo $erroresCliente[$iEmp];
                                            }
                                            $iEmp++;
                                            ?>
                                            <label class="form-label" for="form3Example1c">Ingresa tu direccion</label>
                                        </div>
                                    </div>



                                    <div class="d-flex flex-row align-items-center mb-4" >
                                        <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input type="text" id="form3Example1c" name="dui" placeholder="DUI" class="form-control" value="<?=$dui?>" />
                                            <?php
                                            if(isset($erroresCliente)){
                                                echo $erroresCliente[$iEmp];
                                            }
                                            $iEmp++;
                                            ?>
                                            <label class="form-label" for="form3Example1c">Ingresa tu DUI</label>
                                        </div>
                                    </div>



                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input type="password" id="form3Example4c" name="pass" placeholder="Contraseña" class="form-control" required/>
                                            <label class="form-label" for="form3Example4c">Ingresa tu contraseña</label>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                        <input type="submit" class="btn btn-success btn-lg" name="accion" value="Registrar"></input>
                                        <a href="http://localhost:8081/Proyecto%20Cuponera%202da/modulosProyecto/vista/index.php"><button type="button" class="btn btn-warning btn-lg">Regresar</button></a>
                                    </div>
                                    <div class="text-center text-lg-start mt-4 pt-2">

                                        <p class="small fw-bold mt-2 pt-1 mb-0">Ya tienes una cuenta? <a href="../vista/login.php" class="link-danger">Ingresa aquí</a></p>
                                    </div>

                                </form>

                            </div>
                            <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

                                <img src="../vista/img/draw1.png"
                                     class="img-fluid" alt="Sample image">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
/*require '../vista/footer.php';
*/?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</body>
</html>

