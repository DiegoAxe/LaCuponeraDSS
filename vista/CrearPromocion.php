<?php
if (session_id() === "") {
    session_start();
}
if (empty($_SESSION)) {
    echo "Inicie Sesion";
} else {
    if ($_SESSION["Rol"] == "Empresa") {
        require "../modelo/Empresa/daoEmpresaOfertante.php";
        $CodPromocion = isset($_GET['id']) ? $_GET['id'] : "";
        $operacion = "crearOferta";
        $idPromocion = 0;
        $Titulo = "";
        $Descripcion = "";
        $Detalle = "";
        $Cantidad = 0;
        $FInicio = "";
        $FVencimiento = "";
        $FLimite = "";
        $PRegular = 0;
        $POferta = 0;

        if ($CodPromocion != "") {
            $daoEmpresaOfertante = new daoEmpresaOfertante();
            $datosOferta = $daoEmpresaOfertante->porIdPromocion($CodPromocion);
            $operacion = "editarOferta";
            $idPromocion = $datosOferta["CodPromocion"];
            $Titulo = $datosOferta["Titulo"];
            $Descripcion = $datosOferta["Descripcion"];
            $Detalle = $datosOferta["Detalle"];
            $Cantidad = $datosOferta["CantidadCupones"];
            $FInicio = $datosOferta["FechaInicio"];
            $FVencimiento = $datosOferta["FechaVencimiento"];
            $FLimite = $datosOferta["FechaLimiteCupon"];
            $PRegular = $datosOferta["PrecioRegular"];
            $POferta = $datosOferta["PrecioOferta_Cupon"];
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
                if ($CodPromocion == "") {
                    echo "Crear Promoción";
                } else {
                    echo "Editar Promoción";
                }
                ?>
            </title>
        </head>

        <body style="background-color: #E9EEED ;">

            <div style="padding-bottom: 10px;">
                <h1 class="display-4 text-center"><?php
                                                    if ($CodPromocion == "") {
                                                        echo "Crear Promoción";
                                                    } else {
                                                        echo "Editar Promoción: " . $Titulo;
                                                    }
                                                    ?></h1>
            </div>

            <br>
            <div class="container">
                <div style="padding: 0;">
                    <form role="form" action="../controlador/ctrlEmpresaOfertante.php" method="POST">
                        <div class="col-md-12" id="conten">
                            <input type="hidden" name="idPromocion" id="idPromocion" value="<?= $idPromocion ?>">
                            <input type="hidden" value="<?= $operacion ?>" name="operacion" id="operacion">
                            <input type="hidden" value="<?= $_SESSION["CodEmpresa"] ?>" name="CodEmpresa" id="CodEmpresa">

                            <div class="form-group">
                                <label for="Titulo">Ingrese el Titulo de la Promoción: </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="Titulo" id="Titulo" value="<?= $Titulo ?>" placeholder="Ingrese el Titulo" required>
                                </div>
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="Descripcion">Ingrese la Descripcion de la Promoción: </label>
                                <div class="input-group">
                                    <textarea class="form-control" id="Descripcion" name="Descripcion" placeholder="Información Detallada de la Promoción" required><?= $Descripcion ?></textarea>
                                </div>
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="Detalle">Ingrese el Detalle de la Promoción: </label>
                                <div class="input-group">
                                    <textarea class="form-control" id="Detalle" name="Detalle" placeholder="Información Resumida de la Promoción" required><?= $Detalle ?></textarea>
                                </div>
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="Cantidad">Ingrese la Cantidad de Cupones de la Promoción: </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="Cantidad" id="Cantidad" value="<?= $Cantidad ?>" placeholder="Ingrese la Cantidad de Cupones" required>
                                </div>
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="FInicio">Ingrese la Fecha de Inicio de la Promoción: </label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="FInicio" id="FInicio" value="<?= $FInicio ?>" placeholder="Ingrese la fecha inicial" required>
                                </div>
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="FVencimiento">Ingrese la Fecha de Vencimiento de la Promoción: </label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="FVencimiento" id="FVencimiento" value="<?= $FVencimiento ?>" placeholder="Ingrese la fecha final" required>
                                </div>
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="FLimite">Ingrese la ultima Fecha de Canje de la Promoción: </label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="FLimite" id="FLimite" value="<?= $FLimite ?>" placeholder="Ingrese la ultima fecha de canje" required>
                                </div>
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="PRegular">Ingrese el Precio Regular de la Promoción: </label>
                                <div class="input-group">
                                    <input type="number" min="0" step="0.01" class="form-control" name="PRegular" id="PRegular" value="<?= $PRegular ?>" placeholder="Ingrese el Precio Regular" required>
                                </div>
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="POferta">Ingrese el Precio en Oferta de la Promoción: </label>
                                <div class="input-group">
                                    <input type="number" min="0" step="0.01" class="form-control" name="POferta" id="POferta" value="<?= $POferta ?>" placeholder="Ingrese el Precio en Oferta" required>
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