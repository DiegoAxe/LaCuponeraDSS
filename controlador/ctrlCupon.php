<?php
require '../modelo/Cupones/daoCupones.php';
date_default_timezone_set('America/El_Salvador');
$fecha_hoy = date("Y-m-d");
$daoCupones = new daoCupones();
$IdCupon = isset($_GET['idcupon']) ? $_GET['idcupon'] : "";
$accion = isset($_GET['accion']) ? $_GET['accion'] : "";

if($accion == "Canje" && $IdCupon != ""){
    $daoCupones->CanjearCupones($IdCupon, $fecha_hoy);
    header('location: ../vista/canjeCupon.php?canje=exitoso');
}


?>