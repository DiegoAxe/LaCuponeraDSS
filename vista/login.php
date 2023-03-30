<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuponera</title>
</head>
<body>
    <?php
        require 'menu.php';
    ?>
    <section>
        <article>
            <?php
                if(!isset($_SESSION['CodUsuario'])){
            ?>
                    <section class="vh-100">
                        <div class="container-fluid h-custom">
                            <div class="row d-flex justify-content-center align-items-center h-100">
                                <div class="col-md-9 col-lg-6 col-xl-5">
                                    <img src="img/draw2.png"
                                         class="img-fluid" alt="Sample image">
                                </div>
                                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                                    <form action="../controlador/ctrlUsuario.php" method="POST">

                                        <!-- Email input -->
                                        <div class="form-outline mb-4">
                                            <input type="email" id="form3Example3" class="form-control form-control-lg"
                                                   placeholder="Ingresa una direccion de correo valida" name="correo" id="correo" required/>
                                            <label class="form-label" for="form3Example3">Correo</label>
                                        </div>

                                        <!-- Password input -->
                                        <div class="form-outline mb-3">
                                            <input type="password" id="form3Example4" class="form-control form-control-lg"
                                                   placeholder="Ingresa aquí tu contraseña" name="contra" id="contra" required/>
                                            <label class="form-label" for="form3Example4">Password</label>
                                        </div>



                                        <div class="text-center text-lg-start mt-4 pt-2">
                                            <input type="submit" class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;" name="accion" value="Iniciar">
                                            <a href="index.php"><button type="button" class="btn btn-warning btn-lg">Regresar</button></a>
                                            <p class="small fw-bold mt-2 pt-1 mb-0">No tienes una cuenta? <a href="registro.php" class="link-danger">Registrate aquí</a></p>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                    </section>
            <?php
                }else{
            ?>
                <h1>Ya has iniciado Sesión</h1>
            <?php
                }
            ?>
            
        </article>
    </section>  
</body>
</html>