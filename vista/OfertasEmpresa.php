<?php
if (session_id() === "") {
    session_start();
}
if (empty($_SESSION)) {
    echo "Inicie Sesion";
} else {
    if ($_SESSION["Rol"] == "Empresa") {
        require '../modelo/Empresa/daoEmpresaOfertante.php';
        $daoEmpresa = new daoEmpresaOfertante();
        $ofertasActivas = $daoEmpresa->listarPromoSegunEstado($_SESSION["CodEmpresa"], "Activa");
        $ofertasEspera = $daoEmpresa->listarPromoSegunEstado($_SESSION["CodEmpresa"], "En Espera de aprobación");
        $ofertasFuturas = $daoEmpresa->listarPromoSegunEstado($_SESSION["CodEmpresa"], "Futura");
        $ofertasPasadas = $daoEmpresa->listarPromoSegunEstado($_SESSION["CodEmpresa"], "Pasada");
        $ofertasRechazadas = $daoEmpresa->listarPromoSegunEstado($_SESSION["CodEmpresa"], "Rechazada");
        $ofertasDescartadas = $daoEmpresa->listarPromoSegunEstado($_SESSION["CodEmpresa"], "Descartada");
        $Comision = $daoEmpresa->obtenerComision($_SESSION["CodEmpresa"]);
        $Comision = $Comision["PorcentajeComision"];

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
                <h1 class="display-4 text-center">Gestión de las Ofertas </h1>
                <br>
                <div class="position-relative">
                    <a href="CrearPromocion.php"><button type='button' class='position-absolute top-50 start-50 translate-middle  btn btn-success'>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z" />
                            </svg> Registrar Promoción</button></a>

                </div>
            </div>
            <br>
            <section>

                <div style="margin: 10px 5vw" class="col-lg-11">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="border border-primary nav-item rounded-top">
                            <a class="nav-link" id="espera-tab" data-toggle="tab" href="#espera" role="tab" aria-controls="espera" aria-selected="true">Espera de Aprobacion</a>
                        </li>
                        <li class="border border-primary nav-item rounded-top">
                            <a class="nav-link" id="futuras-tab" data-toggle="tab" href="#futuras" role="tab" aria-controls="futuras" aria-selected="false">Futuras</a>
                        </li>
                        <li class="border border-primary nav-item rounded-top">
                            <a class="nav-link active" id="activas-tab" data-toggle="tab" href="#activas" role="tab" aria-controls="activas" aria-selected="false">Activas</a>
                        </li>
                        <li class="border border-primary nav-item rounded-top">
                            <a class="nav-link" id="pasadas-tab" data-toggle="tab" href="#pasadas" role="tab" aria-controls="pasadas" aria-selected="false">Pasadas</a>
                        </li>
                        <li class="border border-primary nav-item rounded-top">
                            <a class="nav-link" id="rechazadas-tab" data-toggle="tab" href="#rechazadas" role="tab" aria-controls="rechazadas" aria-selected="false">Rechazadas</a>
                        </li>
                        <li class="border border-primary nav-item rounded-top">
                            <a class="nav-link" id="descartadas-tab" data-toggle="tab" href="#descartadas" role="tab" aria-controls="descartadas" aria-selected="false">Descartadas</a>
                        </li>
                    </ul>
                    <div style="background-color:white" class="tab-content" id="myTabContent">
                        <!-- DIV DE LAS OFERTAS ACTIVAS -->
                        <div style="margin-top:-2px" class="tab-pane border border-primary fade show active" id="activas" role="tabpanel" aria-labelledby="activas-tab">
                            <br>
                            <div style="padding-bottom: 10px;">
                                <h3 class="display-6 text-center">Ofertas Activas </h3>
                            </div>
                            <hr>
                            <?php

                            if (sizeof($ofertasActivas) == 0) {
                                echo ' <h3 class="display-7 text-center">No hay ofertas en esta categoria. </h3>
                                        <br>';
                            } else {
                                $i = 0;
                            ?>

                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Titulo</th>
                                            <th>Descripcion</th>
                                            <th>Precio <br> Regular / Oferta</th>
                                            <th>Cupones <br> Vendidos/Disponibles</th>
                                            <th>Ingresos Totales</th>
                                            <th>Cargo por Servicio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($ofertasActivas as $oferta) {
                                            $CuponesVendidos = $daoEmpresa->contarCuponesVendidos($oferta["CodPromocion"]);
                                            $CuponesVendidos = $CuponesVendidos[0];
                                            $i++;
                                            echo "<tr>
                                    <th> $i </th>
                                    <td> " . $oferta["Titulo"] . " </td>
                                    <td> " . $oferta["Descripcion"] . " </td>
                                    <td> $" . $oferta["PrecioRegular"] . " / $" . $oferta["PrecioOferta_Cupon"] . " </td>
                                    <td> " . $CuponesVendidos . " /" . $oferta["CantidadCupones"] . " </td>
                                    <td> $" . $CuponesVendidos * $oferta["PrecioOferta_Cupon"] . " </td>
                                    <td> $" . ($CuponesVendidos * $oferta["PrecioOferta_Cupon"]) * ($Comision * 0.01) . " </td>
                                    </td>
                                    </tr>";
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            <?php
                            }
                            ?>

                        </div>

                        <!-- DIV DE LAS OFERTAS EN ESPERA DE APROBACION -->
                        <div style="margin-top:-2px" class="tab-pane border border-primary fade" id="espera" role="tabpanel" aria-labelledby="espera-tab">
                            <br>
                            <div style="padding-bottom: 10px;">
                                <h3 class="display-6 text-center">Ofertas en Espera de Aprobación </h3>

                            </div>
                            <hr>
                            <?php

                            if (sizeof($ofertasEspera) == 0) {
                                echo ' <h3 class="display-7 text-center">No hay ofertas en esta categoria. </h3>
                                        <br>';
                            } else {
                                $i = 0;
                            ?>

                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Titulo</th>
                                            <th>Descripcion</th>
                                            <th>Precio <br> Regular / Oferta</th>
                                            <th>Cupones Disponibles</th>
                                            <th>Fecha de Inicio</th>
                                            <th>Fecha de Vencimiento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($ofertasEspera as $oferta) {
                                            $i++;
                                            echo "<tr>
                                    <th> $i </th>
                                    <td> " . $oferta["Titulo"] . " </td>
                                    <td> " . $oferta["Descripcion"] . " </td>
                                    <td> $" . $oferta["PrecioRegular"] . " / $" . $oferta["PrecioOferta_Cupon"] . " </td>
                                    <td> " . $oferta["CantidadCupones"] . " </td>
                                    <td> " . $oferta["FechaInicio"] . " </td>
                                    <td> " . $oferta["FechaVencimiento"] . " </td>
                                    </tr>";
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            <?php
                            }
                            ?>


                        </div>

                        <!-- DIV DE LAS OFERTAS FUTURAS -->
                        <div style="margin-top:-2px" class="tab-pane border border-primary fade" id="futuras" role="tabpanel" aria-labelledby="futuras-tab">

                            <br>
                            <div style="padding-bottom: 10px;">
                                <h3 class="display-6 text-center">Ofertas Futuras </h3>
                            </div>
                            <hr>
                            <?php

                            if (sizeof($ofertasFuturas) == 0) {
                                echo ' <h3 class="display-7 text-center">No hay ofertas en esta categoria. </h3>
                                        <br>';
                            } else {
                                $i = 0;
                            ?>

                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Titulo</th>
                                            <th>Descripcion</th>
                                            <th>Precio <br> Regular / Oferta</th>
                                            <th>Cupones Disponibles</th>
                                            <th>Fecha de Inicio</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($ofertasFuturas as $oferta) {
                                            $i++;
                                            echo "<tr>
                                    <th> $i </th>
                                    <td> " . $oferta["Titulo"] . " </td>
                                    <td> " . $oferta["Descripcion"] . " </td>
                                    <td> $" . $oferta["PrecioRegular"] . " / $" . $oferta["PrecioOferta_Cupon"] . " </td>
                                    <td> " . $oferta["CantidadCupones"] . " </td>
                                    <td> " . $oferta["FechaInicio"] . " </td>
                                    <td> <button onclick='descartar(" . $oferta['CodPromocion'] . ")' type='button' class='btn btn-danger'>Descartar</button>
                                    </td>
                                    </tr>";
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            <?php
                            }
                            ?>

                        </div>

                        <!-- DIV DE LAS OFERTAS PASADAS -->
                        <div style="margin-top:-2px" class="tab-pane border border-primary fade" id="pasadas" role="tabpanel" aria-labelledby="pasadas-tab">

                            <br>
                            <div style="padding-bottom: 10px;">
                                <h3 class="display-6 text-center">Ofertas Pasadas </h3>
                            </div>
                            <hr>
                            <?php

                            if (sizeof($ofertasPasadas) == 0) {
                                echo ' <h3 class="display-7 text-center">No hay ofertas en esta categoria. </h3>
                                        <br>';
                            } else {
                                $i = 0;
                            ?>

                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Titulo</th>
                                            <th>Ultima Fecha de Canje</th>
                                            <th>Precio <br> Regular / Oferta</th>
                                            <th>Cupones <br> Vendidos/Disponibles</th>
                                            <th>Ingresos Totales</th>
                                            <th>Cargo por Servicio</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($ofertasPasadas as $oferta) {
                                            $CuponesVendidos = $daoEmpresa->contarCuponesVendidos($oferta["CodPromocion"]);
                                            $CuponesVendidos = $CuponesVendidos[0];
                                            $i++;
                                            echo "<tr>
                                    <th> $i </th>
                                    <td> " . $oferta["Titulo"] . " </td>
                                    <td> " . $oferta["FechaLimiteCupon"] . " </td>
                                    <td> $" . $oferta["PrecioRegular"] . " / $" . $oferta["PrecioOferta_Cupon"] . " </td>
                                    <td> " . $CuponesVendidos . " /" . $oferta["CantidadCupones"] . " </td>
                                    <td> $" . $CuponesVendidos * $oferta["PrecioOferta_Cupon"] . " </td>
                                    <td> $" . ($CuponesVendidos * $oferta["PrecioOferta_Cupon"]) * ($Comision * 0.01) . " </td>
                                    </td>
                                    </tr>";
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            <?php
                            }
                            ?>

                        </div>

                        <!-- DIV DE LAS OFERTAS RECHAZADAS -->
                        <div style="margin-top:-2px" class="tab-pane border border-primary fade" id="rechazadas" role="tabpanel" aria-labelledby="rechazadas-tab">

                            <br>
                            <div style="padding-bottom: 10px;">
                                <h3 class="display-6 text-center">Ofertas Rechazadas </h3>
                            </div>
                            <hr>
                            <?php

                            if (sizeof($ofertasRechazadas) == 0) {
                                echo ' <h3 class="display-7 text-center">No hay ofertas en esta categoria. </h3>
                                        <br>';
                            } else {
                                $i = 0;
                            ?>

                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Titulo</th>
                                            <th>Descripcion</th>
                                            <th>Precio <br> Regular / Oferta</th>
                                            <th>Cupones Disponibles</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($ofertasRechazadas as $oferta) {
                                            $i++;
                                            echo "<tr>
                                    <th> $i </th>
                                    <td> " . $oferta["Titulo"] . " </td>
                                    <td> " . $oferta["Descripcion"] . " </td>
                                    <td> $" . $oferta["PrecioRegular"] . " / $" . $oferta["PrecioOferta_Cupon"] . " </td>
                                    <td> " . $oferta["CantidadCupones"] . " </td>
                                    <td> <a href='CrearPromocion.php?id=".$oferta['CodPromocion']."'> <button type='button' class='btn btn-success'>Editar</button> </a>
                                    <button onclick='descartar(" . $oferta['CodPromocion'] . ")' type='button' class='btn btn-danger'>Descartar</button>
                                    </td>
                                    </tr>";
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            <?php
                            }
                            ?>

                        </div>

                        <!-- DIV DE LAS OFERTAS DESCARTADAS -->
                        <div style="margin-top:-2px" class="tab-pane border border-primary fade" id="descartadas" role="tabpanel" aria-labelledby="descartadas-tab">

                            <br>
                            <div style="padding-bottom: 10px;">
                                <h3 class="display-6 text-center">Ofertas Descartadas </h3>
                            </div>
                            <hr>
                            <?php

                            if (sizeof($ofertasDescartadas) == 0) {
                                echo ' <h3 class="display-7 text-center">No hay ofertas en esta categoria. </h3>
                                        <br>';
                            } else {
                                $i = 0;
                            ?>

                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Titulo</th>
                                            <th>Descripcion</th>
                                            <th>Precio <br> Regular / Oferta</th>
                                            <th>Cupones Disponibles</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($ofertasDescartadas as $oferta) {
                                            $i++;
                                            echo "<tr>
                                    <th> $i </th>
                                    <td> " . $oferta["Titulo"] . " </td>
                                    <td> " . $oferta["Descripcion"] . " </td>
                                    <td> $" . $oferta["PrecioRegular"] . " / $" . $oferta["PrecioOferta_Cupon"] . " </td>
                                    <td> " . $oferta["CantidadCupones"] . " </td>
                                    </tr>";
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            <?php
                            }
                            ?>

                        </div>

                    </div>
                    <br>
                    <br>

                </div>

            </section>
            <br>

        </body>
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

        <script>
            function descartar(idPromo) {
                var mensaje;
                var opcion = confirm("Está seguro de descartar esta oferta:");
                if (opcion == true) {
                    location.href = "../controlador/ctrlEmpresaOfertante.php?operacion=descartarOferta&id=" + idPromo;
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