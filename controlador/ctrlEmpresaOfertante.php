<?php
session_start();
if (!isset($_SESSION['Rol'])) {
    echo "<h1> Inicie sesión primero </h1>";
} else {
    if ($_SESSION['Rol'] != "Empresa") {
        echo "<h1> Ústed no tiene permiso para acceder a esta página </h1>";
    } else {
        require '../modelo/Empresa/daoEmpresaOfertante.php';
        $daoEmpresa = new daoEmpresaOfertante();

        if (!empty($_REQUEST)) {
            $operacion = isset($_REQUEST['operacion']) ? $_REQUEST['operacion'] : "";
            switch ($operacion) {
                case 'crearEmpleado':
                    $Nombres = isset($_REQUEST['Nombres']) ? $_REQUEST['Nombres'] : "";
                    $Apellidos = isset($_REQUEST['Apellidos']) ? $_REQUEST['Apellidos'] : "";
                    $Telefono = isset($_REQUEST['Telefono']) ? $_REQUEST['Telefono'] : "";
                    $Correo = isset($_REQUEST['Correo']) ? $_REQUEST['Correo'] : "";
                    $CodEmpresa = isset($_REQUEST['CodEmpresa']) ? $_REQUEST['CodEmpresa'] : "";
                    $Contra = $_SESSION["CodEmpresa"] . "-";
                    for ($i = 1; $i <= 5; $i++) {
                        $Contra .= rand(0, 9);
                    }
                    $Contra = hash('sha512', $Contra);

                    $daoEmpresa->insertarEmpleado($Nombres, $Apellidos, $Correo, $Contra, $Telefono, $CodEmpresa);
                    header("Location: ../vista/GestorEmpleados.php");
                    break;
                case 'editarEmpleado':
                    $CodUsuario = isset($_REQUEST['idUsuario']) ? $_REQUEST['idUsuario'] : "";
                    $Nombres = isset($_REQUEST['Nombres']) ? $_REQUEST['Nombres'] : "";
                    $Apellidos = isset($_REQUEST['Apellidos']) ? $_REQUEST['Apellidos'] : "";
                    $Telefono = isset($_REQUEST['Telefono']) ? $_REQUEST['Telefono'] : "";
                    $Correo = isset($_REQUEST['Correo']) ? $_REQUEST['Correo'] : "";

                    $daoEmpresa->modificarEmpleado($CodUsuario, $Nombres, $Apellidos, $Correo, $Telefono);
                    header("Location: ../vista/GestorEmpleados.php");
                    break;
                case 'eliminarEmpleado':
                    $CodUsuario = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
                    $daoEmpresa->eliminarEmpleado($CodUsuario);
                    header("Location: ../vista/GestorEmpleados.php");
                    break;

                case 'descartarOferta':
                    $CodOferta = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
                    $daoEmpresa->descartarOferta($CodOferta);
                    header("Location: ../vista/OfertasEmpresa.php");
                    break;
                case 'crearOferta':
                    $Titulo = isset($_REQUEST['Titulo']) ? $_REQUEST['Titulo'] : "";
                    $Descripcion = isset($_REQUEST['Descripcion']) ? $_REQUEST['Descripcion'] : "";
                    $Detalle = isset($_REQUEST['Detalle']) ? $_REQUEST['Detalle'] : "";
                    $Cantidad = isset($_REQUEST['Cantidad']) ? $_REQUEST['Cantidad'] : "";
                    $FInicio = isset($_REQUEST['FInicio']) ? $_REQUEST['FInicio'] : "";
                    $FVencimiento = isset($_REQUEST['FVencimiento']) ? $_REQUEST['FVencimiento'] : "";
                    $FLimite = isset($_REQUEST['FLimite']) ? $_REQUEST['FLimite'] : "";
                    $PRegular = isset($_REQUEST['PRegular']) ? $_REQUEST['PRegular'] : "";
                    $POferta = isset($_REQUEST['POferta']) ? $_REQUEST['POferta'] : "";
                    $CodEmpresa = isset($_REQUEST['CodEmpresa']) ? $_REQUEST['CodEmpresa'] : "";

                    $daoEmpresa->insertarPromocion($Titulo, $Descripcion, $Detalle, $Cantidad, $FInicio, $FVencimiento, $FLimite, $PRegular, $POferta, $CodEmpresa);
                    header("Location: ../vista/OfertasEmpresa.php");
                    break;
                case 'editarOferta':
                    $CodPromo = isset($_REQUEST['idPromocion']) ? $_REQUEST['idPromocion'] : "";
                    $Titulo = isset($_REQUEST['Titulo']) ? $_REQUEST['Titulo'] : "";
                    $Descripcion = isset($_REQUEST['Descripcion']) ? $_REQUEST['Descripcion'] : "";
                    $Detalle = isset($_REQUEST['Detalle']) ? $_REQUEST['Detalle'] : "";
                    $Cantidad = isset($_REQUEST['Cantidad']) ? $_REQUEST['Cantidad'] : "";
                    $FInicio = isset($_REQUEST['FInicio']) ? $_REQUEST['FInicio'] : "";
                    $FVencimiento = isset($_REQUEST['FVencimiento']) ? $_REQUEST['FVencimiento'] : "";
                    $FLimite = isset($_REQUEST['FLimite']) ? $_REQUEST['FLimite'] : "";
                    $PRegular = isset($_REQUEST['PRegular']) ? $_REQUEST['PRegular'] : "";
                    $POferta = isset($_REQUEST['POferta']) ? $_REQUEST['POferta'] : "";
                   
                    $daoEmpresa->modificarPromocion($Titulo, $Descripcion, $Detalle, $Cantidad, $FInicio, $FVencimiento, $FLimite, $PRegular, $POferta, $CodPromo);
                    header("Location: ../vista/OfertasEmpresa.php");
                    break;
            }
        }
    }
}
