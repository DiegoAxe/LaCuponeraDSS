<?php
if (session_id() === "") {
    session_start();
}
if (empty($_SESSION)) {
    echo "Inicie Sesion";
} else {
    if ($_SESSION["Rol"] == "Empresa") {
        require "../modelo/Usuario/daoUsuario.php";
        $daoUsuario = new daoUsuario();
        $listaEmpleados = $daoUsuario->listaEmpleados($_SESSION["CodEmpresa"]);


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

            <title>Gestión de Empleados</title>
        </head>

        <body style="background-color: #E9EEED ;">

            <div style="padding-bottom: 10px;">
                <h1 class="display-4 text-center">Gestión de los Empleados </h1>
            </div>
            <section>
                <div class="container">
                    <div class="col-lg-15">
                        <a href="CrearEmpleado.php"><button type='button' class='btn btn-success'>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z" />
                            </svg> Registrar Empleado</button></a>
                        <br>
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Nombre Completo</th>
                                    <th>Telefono</th>
                                    <th>Correo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                foreach ($listaEmpleados as $empleado) {
                                    $i++;
                                    echo "<tr>
                                    <th> $i </th>
                                    <td>" . $empleado['Apellido'] . ", " . $empleado['Nombre'] . " </td>
                                    <td> " . $empleado['Telefono'] . " </td>
                                    <td> " . $empleado['Correo'] . " </td>
                                    <td> <a href='CrearEmpleado.php?id=".$empleado['CodUsuario']."'><button type='button' class='btn btn-info'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='black' class='bi bi-pencil-fill' viewBox='0 0 16 16'>
  <path d='M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z'/>
                                    </svg>   Editar</button> </a>
                                    <button onclick='eliminar(".$empleado['CodUsuario'].")' type='button' class='btn btn-danger'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='white' class='bi bi-trash-fill' viewBox='0 0 16 16'>
  <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z'/>
                                    </svg>  Eliminar</button> 
                                    
                                    </td>
                                    </tr>";
                                }

                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </section>
            <br>

        </body>

        <script>
            function eliminar(idUsuario) {
                var mensaje;
                var opcion = confirm("Está seguro de eliminar este empleado:");
                if (opcion == true) {
                    location.href = "../controlador/ctrlEmpresaOfertante.php?operacion=eliminarEmpleado&id=" + idUsuario;
                }
            }
        </script>

        </html>
<?php
        require 'footer.php';
    } else {
        echo "Usted no tiene permiso a esta pagina";
    }
}
?>