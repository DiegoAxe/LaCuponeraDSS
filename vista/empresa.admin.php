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
        require 'menu.php';
    ?>
    
    
    <?php
        $id = isset($_GET['empresa'])?$_GET['empresa']:"EMP009";
        require '../modelo/Empresa/daoEmpresas.admin.php';
        require '../modelo/Promocion/daoPromocion.admin.php';
        require '../modelo/Cupones/daoCupones.admin.php';
        require '../modelo/Empresa/empresa.class.admin.php';
        $daoEmpresa = new daoEmpresa();

        $emp = $daoEmpresa->mostrarEmpresa($id);
        if($emp!=false){
            $emP = new Empresa($emp['NombreEmpresa'], $emp['PorcentajeComision'],
            $emp['NombreRubro'], $emp['Direccion'], $emp['Nombre'], $emp['Apellido'],
            $emp['Telefono'], $emp['Correo'], 3, "Si");
            $emP->setCodEmpresa($emp['CodEmpresa']);
            $emP->setCodUsuario($emp['CodUsuario']);
            $emP->setIMG($emp['Foto']);
            if(isset($_SESSION['CodRol']) && $_SESSION['CodRol'] == 1){
        ?>
        <ul class="nav justify-content-center  ">
            <li class="nav-item">
                <a class="nav-link active" href="vistaAdmin.php?gestion=Rubro" aria-current="page">Rubros</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="vistaAdmin.php?gestion=Empresas">Empresas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="vistaAdmin.php?gestion=Promociones">Promociones</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="vistaAdmin.php?gestion=Clientes">Clientes</a>
            </li>
        </ul>
    <div class="container">
        <section class="container mt-5">
        <article class="d-flex justify-content-center row" >
            <section class="col-md-15" >
                <br>
                <br>
                <article class="coupon p-3 bg-white">
                   <div class="row no-gutters justify-content-center align-items-center">
                    
                        <div class="col-md-4 border-right">
                            
                            <img class="img-fluid" src="img/<?=$emP->getImg()?>" width="300" alt="<?=$emP->getImg()?>">
                        </div>
                        <div  class="col-md-4 text-center m-2  border-right">
                            <p class="btn btn-primary col-md-12"><b><?=$emP->getNombreEmpresa()?></b></p>
                            <p>Rubro: <?=$emP->getRubro()?></p>
                            <p>Se ubica en: <?=$emP->getDireccion()?></p>
                            <p>Comisión: <?=$emP->getPorcentajeComision()?>%</p>
                        </div>
                        <div  class="col-md-3 m-2 text-center">
                            <h2>CEO</h2>
                            <p><?=$emP->getNombre()." ".$emP->getApellido()?></p>
                            <p>Telefono: <?=$emP->getTelefono()?></p>
                            <p>Correo: <?=$emP->getCorreo()?></p>
                        </div>
                   </div>
                </article>
                <br>
                <article >
                    <h1 class="text-center">Promociones</h1>
                    <section class="d-flex justify-content-center row">
                        <article>
                        <br>
                            <h2 class="header-primary">En espera de aprobación</h2>
                            <section class="col-md-15">
                    <?php
                        $daoPromocion = new daoPromocion();
                            $fechaActual = date('Y-m-d');
                            /*foreach($promos as $promo){
                                $diasRestantes = $daoPromocion->compararFechas($promo['FechaVencimiento'],$fechaActual);
                                if($diasRestantes < 0){
                                    $daoPromocion->actualizarEstado($promo['CodPromocion'], "Pasada");
                                }
                            }*/

                            //En espera de aprobación
                            $promos = $daoPromocion->mostrarPromocionesCat($emP->getCodEmpresa(), "En Espera de aprobación");
                            if(count($promos)>0){
                                foreach($promos as $promo){
                                    $diasRestantes = $daoPromocion->compararFechas($promo['FechaVencimiento'],$fechaActual);
                    ?>
                                <article class="coupon p-3 bg-white">
                                    <div class="row no-gutters justify-content-center align-items-center">
                                        <div class="col-md-3">
                                            <h2><?=$promo['Titulo']?></h2>
                                            <p><?=$promo['Descripcion']?><br><?=$promo['Detalle']?></p>
                                        </div>
                                        <div class="col-sm-3 m-2">
                                            <p><?=($promo['CantidadCupones']==null)?"No hay límite":"Cupones disponibles: ".$promo['CantidadCupones']?></p>
                                            <p>Inicia: <?=$promo['FechaInicio']?></p>
                                            <p>Termina: <?=$promo['FechaVencimiento']?></p>
                                            <p>Días Restantes: <?=($diasRestantes>=0)?$diasRestantes:"Ha llegado a su fecha límite"?></p>
                                            <p>Los cupones se pueden canjear hasta: <?=$promo['FechaLimiteCupon']?></p>
                                        </div>
                                        <div class="col-sm-2 text-center m-1">
                                            <p class="border border-warining px-3 ">Antes: $<?=$promo['PrecioRegular']?></p>
                                            <b><p class="border border-success px-3 ">Ahora: $<?=$promo['PrecioOferta_Cupon']?></p></b>
                                        </div>
                                        <div class="col-md-3 border-left">
                                            <img class="img-thumbnail" width="300" src="img/<?=$promo['FotoPromocion']?>" alt="<?=$promo['FotoPromocion']?>">
                                        </div>
                                        <div class="col-sm-12 justify-content-center align-items-center text-center">
                                        <?php
                                            switch($promo['Estado']){
                                                case 'Descartada':
                                                    echo "<h2 class=\"alert alert-secondary\">" . $promo['Estado'] . "</h2>";
                                                    break;
                                                case 'Rechazada':
                                                    echo "<h2 class=\"alert alert-warning\">" . $promo['Estado'] . "</h2>";
                                                    break;
                                                case 'En Espera de aprobación':
                                                    echo "<h2 class=\"alert alert-primary\">" . $promo['Estado'] . "</h2>";
                                                    echo "<a class=\"btn btn-success btn-lg \" href=\"../controlador/ctrlPromocion.admin.php?accion=Aprobar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Aprobar</h2></a>";
                                                    echo "<a class=\"btn btn-danger btn-lg\"href=\"../controlador/ctrlPromocion.admin.php?accion=Rechazar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Rechazar</h2></a>";
                                                    break;
                                                case 'Aprobada':
                                                    echo "<h2 class=\"alert alert-success \">" . $promo['Estado'] . "</h2>";
                                                    break;
                                                default:
                                                    echo "<h2 class=\"alert alert-dark\">" . $promo['Estado'] . "</h2>";
                                                    break;
                                            }
        
                                            $daoCupones= new daoCupones();
                                            $cantCupones= count($daoCupones->mostrarCupones($promo['CodPromocion']));
                                            $comision = $emP->getPorcentajeComision();
                                            $precioCupon = $promo['PrecioOferta_Cupon'];
                                            $ingresos = $cantCupones * $precioCupon;
                                            $cargos = $ingresos * ($comision/100);
                                        ?>
                                        </div>
                                    </div>
                                    </article>
                                </article>
                            </section>                         
                        <?php
                                }
                        ?>
                            
                        
                        <?php
                            }else{
                                echo "<article>";

                                echo "<h2 class=\"alert alert-dark\">Aún no hay promociones en espera de aprobación.</h2>";
                                echo "</article>";
                                echo "</section>";
                                echo "</article>";
                                echo "</section>";
                            }
                        ?>
                        
                    <section  class="d-flex justify-content-center row">
                        <article>
                        <br>
                            <h2 class="header-primary">Aprobadas</h2>
                            
                    <?php
                            //Aprobadas
                            $pasadas = array();
                            $activas = array();
                            $futuras = array();
                            $promos = $daoPromocion->mostrarPromocionesCat($emP->getCodEmpresa(), "Aprobada");
                        
                            if(count($promos)>0){
                                
                                foreach($promos as $promo){
                                    $limiteInferior = $daoPromocion->compararFechas($promo['FechaInicio'],$fechaActual); 
                                    $limiteSuperior = $daoPromocion->compararFechas($promo['FechaVencimiento'],$fechaActual);
                                    if($limiteInferior <= 0 && $limiteSuperior >= 0){
                                        array_push($activas, $promo);
                                    }elseif($limiteInferior > 0 && $limiteSuperior > 0){
                                        array_push($futuras, $promo);
                                    }else{
                                        array_push($pasadas, $promo);
                                    }
                                }

                                ?>
                            <section  class="d-flex justify-content-center row">
                                <article>
                                    <h1 class="header-primary text-center">Futuras</h1>
                        <?php
                                
                                if(count($futuras)>0){
                                    foreach($futuras as $cant=>$promo){
                                        $diasRestantes = $daoPromocion->compararFechas($promo['FechaVencimiento'],$fechaActual);
                        ?>
                                <article>
                                <div class="row no-gutters justify-content-center align-items-center">
                                    <div class="col-md-3">
                                        <h2><?=$promo['Titulo']?></h2>
                                        <p><?=$promo['Descripcion']?><br><?=$promo['Detalle']?></p>
                                    </div>
                                    <div class="col-sm-3 m-2">
                                        <p><?=($promo['CantidadCupones']==null)?"No hay límite":"Cupones disponibles: ". $promo['CantidadCupones']?></p>
                                        <p>Inicia: <?=$promo['FechaInicio']?></p>
                                        <p>Termina: <?=$promo['FechaVencimiento']?></p>
                                        <p>Días Restantes: <?=($diasRestantes>=0)?$diasRestantes:"Ha llegado a su fecha límite"?></p>
                                        <p>Los cupones se pueden canjear hasta: <?=$promo['FechaLimiteCupon']?></p>
                                    </div>
                                    <div class="col-sm-2 text-center m-1">
                                        <p class="border border-warining px-3 ">Antes: $<?=$promo['PrecioRegular']?></p>
                                        <b><p class="border border-success px-3 ">Ahora: $<?=$promo['PrecioOferta_Cupon']?></p></b>
                                    </div>
                                    <div class="col-md-3 border-left">
                                        <img class="img-thumbnail" width="300" src="img/<?=$promo['FotoPromocion']?>" alt="<?=$promo['FotoPromocion']?>">
                                    </div>
                                    <div class="col-sm-12 justify-content-center align-items-center text-center">
                                    <?php
                                        switch($promo['Estado']){
                                            case 'Descartada':
                                                echo "<h2 class=\"alert alert-secondary\">" . $promo['Estado'] . "</h2>";
                                                break;
                                            case 'Rechazada':
                                                echo "<h2 class=\"alert alert-warning\">" . $promo['Estado'] . "</h2>";
                                                break;
                                            case 'En Espera de aprobación':
                                                echo "<h2 class=\"alert alert-primary\">" . $promo['Estado'] . "</h2>";
                                                echo "<a class=\"btn btn-success btn-lg \" href=\"../controlador/ctrlPromocion.admin.php?accion=Aprobar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Aprobar</h2></a>";
                                                echo "<a class=\"btn btn-danger btn-lg\"href=\"../controlador/ctrlPromocion.admin.php?accion=Rechazar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Rechazar</h2></a>";
                                                break;
                                            case 'Aprobada':
                                                echo "<h2 class=\"alert alert-success \">" . $promo['Estado'] . "</h2>";
                                                break;
                                            default:
                                                echo "<h2 class=\"alert alert-dark\">" . $promo['Estado'] . "</h2>";
                                                break;
                                        }
    
                                        $daoCupones= new daoCupones();
                                        $cantCupones= count($daoCupones->mostrarCupones($promo['CodPromocion']));
                                        $comision = $emP->getPorcentajeComision();
                                        $precioCupon = $promo['PrecioOferta_Cupon'];
                                        $ingresos = $cantCupones * $precioCupon;
                                        $cargos = $ingresos * ($comision/100);
                                    ?>
                                    </div>
                                <div>
                                    <h1>Detalles de la oferta</h1>
                                    <div>
                                        <h2>Cupones vendidos: <?=$cantCupones?></h2>
                                        <h2>Cupones disponibles: <?=($promo['CantidadCupones'] ==null)?"No hay límite":$promo['CantidadCupones']?></h2>
                                        <h2>Ingresos totales: $<?=$ingresos?></h2>
                                        <h2>Cargos por servicio: $<?=$cargos?></h2>
                                    </div>
                                </div>
                                </article>                       
                            <?php
                                    }
                            ?>
                                
                                </article>
                            <?php

                                }else{
                                    echo "<article>";
                                    echo "<h1 class=\"alert alert-secondary\">Aún no hay promociones Aprobadas a Futuro.</h1>";
                                    echo "</article>";
                                    echo "</section>";
                                    echo "</article>";
                                    

                                }
                                 
                            ?>
                           
                                <article>
                                    <br>
                                    <h1 class="header-primary text-center">Activas</h1>
                    <?php
                        if(count($activas)>0){
                            foreach($activas as $cant=>$promo){
                                $diasRestantes = $daoPromocion->compararFechas($promo['FechaVencimiento'],$fechaActual);
                    ?>
                                <article>
                                <div class="row no-gutters justify-content-center align-items-center">
                                    
                                    <div class="col-md-3">
                                    <br>
                                <br>
                                        <h2><?=$promo['Titulo']?></h2>
                                        <p><?=$promo['Descripcion']?><br><?=$promo['Detalle']?></p>
                                    </div>
                                    <div class="col-sm-3 m-2">
                                        <p><?=($promo['CantidadCupones']==null)?"No hay límite":"Cupones disponibles: ".$promo['CantidadCupones']?></p>
                                        <p>Inicia: <?=$promo['FechaInicio']?></p>
                                        <p>Termina: <?=$promo['FechaVencimiento']?></p>
                                        <p>Días Restantes: <?=($diasRestantes>=0)?$diasRestantes:"Ha llegado a su fecha límite"?></p>
                                        <p>Los cupones se pueden canjear hasta: <?=$promo['FechaLimiteCupon']?></p>
                                    </div>
                                    <div class="col-sm-2 text-center m-1">
                                        <p class="border border-warining px-3 ">Antes: $<?=$promo['PrecioRegular']?></p>
                                        <b><p class="border border-success px-3 ">Ahora: $<?=$promo['PrecioOferta_Cupon']?></p></b>
                                    </div>
                                    <div class="col-md-3 border-left">
                                        <img class="img-thumbnail" width="300" src="img/<?=$promo['FotoPromocion']?>" alt="<?=$promo['FotoPromocion']?>">
                                    </div>
                                    <div class="col-sm-12 justify-content-center align-items-center text-center">
                                    <?php
                                        switch($promo['Estado']){
                                            case 'Descartada':
                                                echo "<h2 class=\"alert alert-secondary\">" . $promo['Estado'] . "</h2>";
                                                break;
                                            case 'Rechazada':
                                                echo "<h2 class=\"alert alert-warning\">" . $promo['Estado'] . "</h2>";
                                                break;
                                            case 'En Espera de aprobación':
                                                echo "<h2 class=\"alert alert-primary\">" . $promo['Estado'] . "</h2>";
                                                echo "<a class=\"btn btn-success btn-lg \" href=\"../controlador/ctrlPromocion.admin.php?accion=Aprobar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Aprobar</h2></a>";
                                                echo "<a class=\"btn btn-danger btn-lg\"href=\"../controlador/ctrlPromocion.admin.php?accion=Rechazar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Rechazar</h2></a>";
                                                break;
                                            case 'Aprobada':
                                                echo "<h2 class=\"alert alert-success \">" . $promo['Estado'] . "</h2>";
                                                break;
                                            default:
                                                echo "<h2 class=\"alert alert-dark\">" . $promo['Estado'] . "</h2>";
                                                break;
                                        }
    
                                        $daoCupones= new daoCupones();
                                        $cantCupones= count($daoCupones->mostrarCupones($promo['CodPromocion']));
                                        $comision = $emP->getPorcentajeComision();
                                        $precioCupon = $promo['PrecioOferta_Cupon'];
                                        $ingresos = $cantCupones * $precioCupon;
                                        $cargos = $ingresos * ($comision/100);
                                    ?>
                                    </div>
                                <div>
                                    <h1>Detalles de la oferta</h1>
                                    <div>
                                        <h2>Cupones vendidos: <?=$cantCupones?></h2>
                                        <h2>Cupones disponibles: <?=($promo['CantidadCupones'] ==null)?"No hay límite":$promo['CantidadCupones']?></h2>
                                        <h2>Ingresos totales: $<?=$ingresos?></h2>
                                        <h2>Cargos por servicio: $<?=$cargos?></h2>
                                    </div>
                                </div>
                                </article>                       
                            <?php
                                    }
                            ?>
                                </article>

                            <?php

                                }else{
                                    echo "<article>";
                                    echo "<h1 class=\"alert alert-secondary\">Aún no hay promociones aprobadas Activas.</h1>";
                                    echo "</article>";
                                    echo "</section>";
                                }
                                 
                            ?>
                            <article>
                                <br>
                                    <h1 class="header-primary text-center">Pasadas</h1>
                    <?php
                        if(count($pasadas)>0){
                            foreach($pasadas as $cant=>$promo){
                                $diasRestantes = $daoPromocion->compararFechas($promo['FechaVencimiento'],$fechaActual);
                    ?>
                                <article>
                                    
                                <div class="row no-gutters justify-content-center align-items-center">
                                <br>
             
                                    <div class="col-md-3">
                                        <h2><?=$promo['Titulo']?></h2>
                                        <p><?=$promo['Descripcion']?><br><?=$promo['Detalle']?></p>
                                    </div>
                                    <div class="col-sm-3 m-2">
                                        <p><?=($promo['CantidadCupones']==null)?"No hay límite":"Cupones disponibles: ".$promo['CantidadCupones']?></p>
                                        <p>Inicia: <?=$promo['FechaInicio']?></p>
                                        <p>Termina: <?=$promo['FechaVencimiento']?></p>
                                        <p>Días Restantes: <?=($diasRestantes>=0)?$diasRestantes:"Ha llegado a su fecha límite"?></p>
                                        <p>Los cupones se pueden canjear hasta: <?=$promo['FechaLimiteCupon']?></p>
                                    </div>
                                    <div class="col-sm-2 text-center m-1">
                                        <p class="border border-warining px-3 ">Antes: $<?=$promo['PrecioRegular']?></p>
                                        <b><p class="border border-success px-3 ">Ahora: $<?=$promo['PrecioOferta_Cupon']?></p></b>
                                    </div>
                                    <div class="col-md-3 border-left">
                                        <img class="img-thumbnail" width="300" src="img/<?=$promo['FotoPromocion']?>" alt="<?=$promo['FotoPromocion']?>">
                                    </div>
                                    <div class="col-sm-12 justify-content-center align-items-center text-center">
                                    <?php
                                        switch($promo['Estado']){
                                            case 'Descartada':
                                                echo "<h2 class=\"alert alert-secondary\">" . $promo['Estado'] . "</h2>";
                                                break;
                                            case 'Rechazada':
                                                echo "<h2 class=\"alert alert-warning\">" . $promo['Estado'] . "</h2>";
                                                break;
                                            case 'En Espera de aprobación':
                                                echo "<h2 class=\"alert alert-primary\">" . $promo['Estado'] . "</h2>";
                                                echo "<a class=\"btn btn-success btn-lg \" href=\"../controlador/ctrlPromocion.admin.php?accion=Aprobar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Aprobar</h2></a>";
                                                echo "<a class=\"btn btn-danger btn-lg\"href=\"../controlador/ctrlPromocion.admin.php?accion=Rechazar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Rechazar</h2></a>";
                                                break;
                                            case 'Aprobada':
                                                echo "<h2 class=\"alert alert-success \">" . $promo['Estado'] . "</h2>";
                                                break;
                                            default:
                                                echo "<h2 class=\"alert alert-dark\">" . $promo['Estado'] . "</h2>";
                                                break;
                                        }
    
                                        $daoCupones= new daoCupones();
                                        $cantCupones= count($daoCupones->mostrarCupones($promo['CodPromocion']));
                                        $comision = $emP->getPorcentajeComision();
                                        $precioCupon = $promo['PrecioOferta_Cupon'];
                                        $ingresos = $cantCupones * $precioCupon;
                                        $cargos = $ingresos * ($comision/100);
                                    ?>
                                    </div>
                                <div>
                                    <h1>Detalles de la oferta</h1>
                                    <div>
                                        <h2>Cupones vendidos: <?=$cantCupones?></h2>
                                        <h2>Cupones disponibles: <?=($promo['CantidadCupones'] ==null)?"No hay límite":$promo['CantidadCupones']?></h2>
                                        <h2>Ingresos totales: $<?=$ingresos?></h2>
                                        <h2>Cargos por servicio: $<?=$cargos?></h2>
                                    </div>
                                    <br>
                                <br>
                                <br>
                                <br>
                                </div> 
                                </article>                       
                            <?php
                                    }
                            ?>
                                
                                </section>
                        
                            <?php

                                }else{
                                    echo "<h1 class=\"alert alert-secondary\">Aún no hay promociones Pasadas.</h1>";
                                    echo "</article>";
                                    echo "</section>";
                                }
                            ?>
                            
            
                        <?php
                            }else{
                                echo "<section  class=\"col-md-15\">";
                                echo "<article>";
                                echo "<h2 class=\"alert alert-dark\">Aún no hay promociones Aprobadas.</h2>";
                                echo "</article>";
                                echo "</section>";
                                echo "</article>";

                                
                                echo "</section>";
                                
                                
                            }
                        ?>
                        <section  class="d-flex justify-content-center row">
                            <article>
                                <h2 class="header-primary">Rechazadas</h2>
                                <section class="col-md-15">
                        <?php
                            //Rechazada
                            $promos = $daoPromocion->mostrarPromocionesCat($emP->getCodEmpresa(), "Rechazada");
                            if(count($promos)>0){
                                foreach($promos as $promo){
                                    $diasRestantes = $daoPromocion->compararFechas($promo['FechaVencimiento'],$fechaActual);
                        ?>
                                <article class="coupon p-3 bg-white">
                                <br>
                                <br>
                                <div class="row no-gutters justify-content-center align-items-center">
                                    <div class="col-md-3">
                                        <h2><?=$promo['Titulo']?></h2>
                                        <p><?=$promo['Descripcion']?><br><?=$promo['Detalle']?></p>
                                    </div>
                                    <div class="col-sm-3 m-2">
                                        <p><?=($promo['CantidadCupones']==null)?"No hay límite":"Cupones disponibles: ".$promo['CantidadCupones']?></p>
                                        <p>Inicia: <?=$promo['FechaInicio']?></p>
                                        <p>Termina: <?=$promo['FechaVencimiento']?></p>
                                        <p>Días Restantes: <?=($diasRestantes>=0)?$diasRestantes:"Ha llegado a su fecha límite"?></p>
                                        <p>Los cupones se pueden canjear hasta: <?=$promo['FechaLimiteCupon']?></p>
                                    </div>
                                    <div class="col-sm-2 text-center m-1">
                                        <p class="border border-warining px-3 ">Antes: $<?=$promo['PrecioRegular']?></p>
                                        <b><p class="border border-success px-3 ">Ahora: $<?=$promo['PrecioOferta_Cupon']?></p></b>
                                    </div>
                                    <div class="col-md-3 border-left">
                                        <img class="img-thumbnail" width="300" src="img/<?=$promo['FotoPromocion']?>" alt="<?=$promo['FotoPromocion']?>">
                                    </div>
                                    <div class="col-sm-12 justify-content-center align-items-center text-center">
                                    <?php
                                        switch($promo['Estado']){
                                            case 'Descartada':
                                                echo "<h2 class=\"alert alert-secondary\">" . $promo['Estado'] . "</h2>";
                                                break;
                                            case 'Rechazada':
                                                echo "<h2 class=\"alert alert-warning\">" . $promo['Estado'] . "</h2>";
                                                break;
                                            case 'En Espera de aprobación':
                                                echo "<h2 class=\"alert alert-primary\">" . $promo['Estado'] . "</h2>";
                                                echo "<a class=\"btn btn-success btn-lg \" href=\"../controlador/ctrlPromocion.admin.php?accion=Aprobar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Aprobar</h2></a>";
                                                echo "<a class=\"btn btn-danger btn-lg\"href=\"../controlador/ctrlPromocion.admin.php?accion=Rechazar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Rechazar</h2></a>";
                                                break;
                                            case 'Aprobada':
                                                echo "<h2 class=\"alert alert-success \">" . $promo['Estado'] . "</h2>";
                                                break;
                                            default:
                                                echo "<h2 class=\"alert alert-dark\">" . $promo['Estado'] . "</h2>";
                                                break;
                                        }
    
                                        $daoCupones= new daoCupones();
                                        $cantCupones= count($daoCupones->mostrarCupones($promo['CodPromocion']));
                                        $comision = $emP->getPorcentajeComision();
                                        $precioCupon = $promo['PrecioOferta_Cupon'];
                                        $ingresos = $cantCupones * $precioCupon;
                                        $cargos = $ingresos * ($comision/100);
                                    ?>
                                    </div>
                                </article>                       
                            <?php
                                    }
                            ?>
                                </article>
                                </section>
                            <?php
                                }else{
                                    echo "<article>";
                                    echo "<h2 class=\"alert alert-dark\">Aún no hay promociones Rechazadas.</h2>";
                                    echo "</article>";
                                    echo "</section>";
                                    echo "</article>";
                                    echo "</section>";
                                }
                        ?>
                        <section class="d-flex justify-content-center row">
                        <article>
                            <h2 class="header-primary">Descartadas</h2>
                            <section class="col-md-15">
                    <?php
                            //Descartada
                            $promos = $daoPromocion->mostrarPromocionesCat($emP->getCodEmpresa(), "Descartada");
                            if(count($promos)>0){
                                foreach($promos as $promo){
                                    $diasRestantes = $daoPromocion->compararFechas($promo['FechaVencimiento'],$fechaActual);
                    ?>
                            <article class="coupon p-3 bg-white">
                            <br>
                                <br>
                                <div class="row no-gutters justify-content-center align-items-center">
                                    <div class="col-md-3">
                                        <br>
                                        <h2><?=$promo['Titulo']?></h2>
                                        <p><?=$promo['Descripcion']?><br><?=$promo['Detalle']?></p>
                                    </div>
                                    <div class="col-sm-3 m-2">
                                        <p><?=($promo['CantidadCupones']==null)?"No hay límite":"Cupones disponibles: ".$promo['CantidadCupones']?></p>
                                        <p>Inicia: <?=$promo['FechaInicio']?></p>
                                        <p>Termina: <?=$promo['FechaVencimiento']?></p>
                                        <p>Días Restantes: <?=($diasRestantes>=0)?$diasRestantes:"Ha llegado a su fecha límite"?></p>
                                        <p>Los cupones se pueden canjear hasta: <?=$promo['FechaLimiteCupon']?></p>
                                    </div>
                                    <div class="col-sm-2 text-center m-1">
                                        <p class="border border-warining px-3 ">Antes: $<?=$promo['PrecioRegular']?></p>
                                        <b><p class="border border-success px-3 ">Ahora: $<?=$promo['PrecioOferta_Cupon']?></p></b>
                                    </div>
                                    <div class="col-md-3 border-left">
                                        <img class="img-thumbnail" width="300" src="img/<?=$promo['FotoPromocion']?>" alt="<?=$promo['FotoPromocion']?>">
                                    </div>
                                    <div class="col-sm-12 justify-content-center align-items-center text-center">
                                    <?php
                                        switch($promo['Estado']){
                                            case 'Descartada':
                                                echo "<h2 class=\"alert alert-secondary\">" . $promo['Estado'] . "</h2>";
                                                break;
                                            case 'Rechazada':
                                                echo "<h2 class=\"alert alert-warning\">" . $promo['Estado'] . "</h2>";
                                                break;
                                            case 'En Espera de aprobación':
                                                echo "<h2 class=\"alert alert-primary\">" . $promo['Estado'] . "</h2>";
                                                echo "<a class=\"btn btn-success btn-lg \" href=\"../controlador/ctrlPromocion.admin.php?accion=Aprobar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Aprobar</h2></a>";
                                                echo "<a class=\"btn btn-danger btn-lg\"href=\"../controlador/ctrlPromocion.admin.php?accion=Rechazar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Rechazar</h2></a>";
                                                break;
                                            case 'Aprobada':
                                                echo "<h2 class=\"alert alert-success \">" . $promo['Estado'] . "</h2>";
                                                break;
                                            default:
                                                echo "<h2 class=\"alert alert-dark\">" . $promo['Estado'] . "</h2>";
                                                break;
                                        }
    
                                        $daoCupones= new daoCupones();
                                        $cantCupones= count($daoCupones->mostrarCupones($promo['CodPromocion']));
                                        $comision = $emP->getPorcentajeComision();
                                        $precioCupon = $promo['PrecioOferta_Cupon'];
                                        $ingresos = $cantCupones * $precioCupon;
                                        $cargos = $ingresos * ($comision/100);
                                    ?>
                                    </div>
                                <div>
                                    <h1>Detalles de la oferta</h1>
                                    <div>
                                        <h2>Cupones vendidos: <?=$cantCupones?></h2>
                                        <h2>Cupones disponibles: <?=($promo['CantidadCupones'] ==null)?"No hay límite":$promo['CantidadCupones']?></h2>
                                        <h2>Ingresos totales: $<?=$ingresos?></h2>
                                        <h2>Cargos por servicio: $<?=$cargos?></h2>
                                    </div>
                                </div>
                            </article>                       
                        <?php
                                }
                        ?>
                            </article>
                            </section>
                        <?php
                            }else{
                                echo "<article>";
                                echo "<h2 class=\"alert alert-dark\">Aún no hay promociones Descartadas.</h2>";
                                echo "</article>";
                                echo "</section>";
                                echo "</article>";
                                echo "</section>";
                            }
                    ?>
                    
                </article>
                </section>
                </article>
            </article>
            </section>
        <?php
            }else{
        ?>
            <article>
                <h1>No puedes acceder Aquí si no eres ADMINISTRADOR <a href="login.php">Inicia Sesión</a></h1>
            </article>
        <?php
            }
        }else{
            echo "<article class=\"text-center\">"; 
            echo "<h1 class=\"alert alert-warning\">Debe elejir una empresa válida</h1>";
            echo "</article>";
            echo "</section>";
        }
        ?>
    </div>
    <?php
        require 'footer.php';
    ?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>