<?php
if (session_id() === "") {
    session_start();
}
if (empty($_SESSION)) {
    echo "Inicie Sesion";
} else {
    if ($_SESSION["Rol"] == "Empresa") {
        require "../modelo/Usuario/daoUsuario.php";
        $CodUsuario = isset($_GET['id']) ? $_GET['id'] : "";
        $operacion = "crearEmpleado";
        $idUser = 0;
        $mensaje = "La contraseña del empleado se generará aleatoriamente.";
        $Correo = "";
        $Nombres = "";
        $Apellidos = "";
        $Telefono = "";
        if ($CodUsuario != "") {
            $daoUsuario = new daoUsuario();
            $datosUsuario = $daoUsuario->buscarPorId($CodUsuario);
            $operacion = "editarEmpleado";
            $idUser = $datosUsuario["CodUsuario"];
            $mensaje = "La contraseña del empleado se mantiene a la anterior";
            $Correo = $datosUsuario["Correo"];
            $Nombres = $datosUsuario["Nombre"];
            $Apellidos = $datosUsuario["Apellido"];
            $Telefono = $datosUsuario["Telefono"];
        }

        require 'menu.php';
?>
        <!doctype html>
        <html lang="en">

        <head>
            <!-- Required meta tags -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

            <title>
                <?php
                if ($CodUsuario == "") {
                    echo "Crear Empleado";
                } else {
                    echo "Editar Empleado";
                }
                ?>
            </title>
        </head>

        <body style="background-color: #E9EEED ;">

            <div style="padding-bottom: 10px;">
                <h1 class="display-4 text-center"><?php
                    if ($CodUsuario == "") {
                        echo "Crear Empleado";
                    } else {
                        echo "Editar Empleado: " . $datosUsuario["Apellido"] . ", " . $datosUsuario["Nombre"];
                    }
                    ?></h1>
            </div>

            <br>
            <div class="container">
                <div style="padding: 0;">
                    <form role="form" action="../controlador/ctrlEmpresaOfertante.php" method="POST">
                        <div class="col-md-12" id="conten">
                            <input type="hidden" name="idUsuario" id="idUsuario" value="<?= $idUser ?>">
                            <input type="hidden" value="<?= $operacion ?>" name="operacion" id="operacion">
                            <input type="hidden" value="<?= $_SESSION["CodEmpresa"]?>" name="CodEmpresa" id="CodEmpresa">

                            <div class="form-group">
                                <label for="Nombres">Ingrese los Nombres del Empleado: </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="Nombres" id="Nombres" value="<?=$Nombres?>" placeholder="Ingrese los Nombres" required>
                                </div>
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="Apellidos">Ingrese los Apellidos del Empleado:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="Apellidos" id="Apellidos" value="<?=$Apellidos?>" placeholder="Ingresa los Apellidos" required>
                                </div>
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="Telefono">Ingrese el Telefono del Empleado:</label>
                                <div class="input-group">
                                    <input type="tel" class="form-control" name="Telefono" id="Telefono" value="<?=$Telefono?>" placeholder="Ingresa el Telefono (XXXX-XXXX)" title="Ingrese 4 digitos, un guion y otros 4 digitos" pattern="[0-9]{4}[-][0-9]{4}" required>
                                </div>
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="Correo">Ingrese el Correo del Empleado:</label>
                                <div class="input-group">
                                    <input type="email" class="form-control" name="Correo" id="Correo" value="<?=$Correo?>" placeholder="Ingresa el Correo" required>
                                </div>
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="">La Contraseña del Empleado:</label>
                                <div class="input-group">
                                <h4 class="text-center"><?=$mensaje?></h4>
                                </div>
                            </div>
                            <br>

                            <div style="margin-left: 30%;">
                                <input type="submit" class="btn btn-success col-md-6 " value="Guardar">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <br>
            <br>

        </body>


        </html>
<?php
        require 'footer.php';
    } else {
        echo "Usted no tiene permiso a esta pagina";
    }
}
?>