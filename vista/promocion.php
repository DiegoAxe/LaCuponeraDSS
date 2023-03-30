<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Cuponera</title>
</head>
<body>
    <?php
        require 'menu.php';
    ?>
    <section>
    <?php
        $id = isset($_GET['promo'])?$_GET['promo']:"1";
        require '../modelo/Empresa/daoEmpresas.php';
        require '../modelo/Promocion/daoPromocion.php';
        require '../modelo/Cupones/daoCupones.php';
        require '../modelo/Empresa/empresa.class.php';
        $daoEmpresa = new daoEmpresa();
        $daoPromocion = new daoPromocion();
        $fechaActual = date('Y-m-d');
        $promo = $daoPromocion->buscarPromo($id);
        if($promo!=false){
            $promo = $promo[0];
            $diasRestantes = $daoPromocion->compararFechas($promo['FechaVencimiento'],$fechaActual);
            $limiteInferior = $daoPromocion->compararFechas($promo['FechaInicio'],$fechaActual); 
            $limiteSuperior = $daoPromocion->compararFechas($promo['FechaVencimiento'],$fechaActual);
            if($limiteInferior <= 0 && $limiteSuperior >= 0){
                $estado = " (Activa)";
            }elseif($limiteInferior > 0 && $limiteSuperior > 0){
                $estado = " (Futura)";
            }else{
                $estado = " (Pasada)";
            }
        
            $codEmpresa = $promo['CodEmpresa'];
            $emp = $daoEmpresa->mostrarEmpresa($codEmpresa);
            $emP = new Empresa($emp['NombreEmpresa'], $emp['PorcentajeComision'],
            $emp['NombreRubro'], $emp['Direccion'], $emp['Nombre'], $emp['Apellido'],
            $emp['Telefono'], $emp['Correo'], 3, "Si");
            $emP->setCodEmpresa($emp['CodEmpresa']);
            $emP->setCodUsuario($emp['CodUsuario']);
            $emP->setIMG($emp['Foto']);
            if(isset($_SESSION['CodRol']) && $_SESSION['CodRol'] == 1){
        ?>
        <article>
            <div>
                <img src="img/<?=$emP->getImg()?>" alt="">
            </div>
            <div>
                <div>
                    <h1><?=$emP->getNombreEmpresa()?></h1>
                    <h1>Rubro: <?=$emP->getRubro()?></h1>
                    <h1>Se ubica en: <?=$emP->getDireccion()?></h1>
                    <h1>Comisión: <?=$emP->getPorcentajeComision()?>%</h1>
                </div>
                <div>
                    <h1><?=$emP->getNombre()." ".$emP->getApellido()?></h1>
                    <h1>Telefono: <?=$emP->getTelefono()?></h1>
                    <h1>Correo: <?=$emP->getCorreo()?></h1>
                </div>
            </div>
        </article>
        <article>
                    <div>
                        <h2><?=$promo['Titulo']?></h2>
                        <h2><?=$promo['Descripcion']?><br><?=$promo['Detalle']?></h2>
                    </div>
                    <div>
                        <h2><?=($promo['CantidadCupones']==null)?"No hay límite":$promo['CantidadCupones']?></h2>
                        <h2>Inicia: <?=$promo['FechaInicio']?></h2>
                        <h2>Termina: <?=$promo['FechaVencimiento']?></h2>
                        <h2>Días Restantes: <?=($diasRestantes>0)?$diasRestantes:"Ha llegado a su fecha límite"?></h2>
                        <h2>Los cupones se pueden canjear hasta: <?=$promo['FechaLimiteCupon']?></h2>
                    </div>
                    <div>
                        <h2>Antes: $<?=$promo['PrecioRegular']?></h2>
                        <h2>Ahora: $<?=$promo['PrecioOferta_Cupon']?></h2>
                    </div>
                    <?php
                    switch($promo['Estado']){
                        case 'Descartada':
                            echo "<h2 class=\"descartada\">Estado: " . $promo['Estado'] . $estado . "</h2>";
                            break;
                        case 'Rechazada':
                            echo "<h2 class=\"rechazada\">Estado: " . $promo['Estado'] . $estado . "</h2>";
                            break;
                        case 'En Espera de aprobación':
                            echo "<h2 class=\"espera\">Estado: " . $promo['Estado'] . "</h2>";
                            echo "<a href=\"../controlador/ctrlPromocion.php?accion=Aprobar&id=" . $promo['CodPromocion'] . $estado ."\"><h2 class=\"aprobar\">Aprobar</h2></a>";
                            echo "<a href=\"../controlador/ctrlPromocion.php?accion=Rechazar&id=" . $promo['CodPromocion'] . $estado ."\"><h2 class=\"aprobar\">Rechazar</h2></a>";
                            break;
                        case 'Aprobada':
                        case 'Activa':
                            echo "<h2 class=\"aprobada\">Estado: " . $promo['Estado'] . $estado . "</h2>";
                            break;
                        default:
                            echo "<h2 class=\"vencido\">Estado: " . $promo['Estado'] . $estado . "</h2>";
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
                <div>
                    <img src="img/<?=$promo['FotoPromocion']?>" alt="<?=$promo['FotoPromocion']?>">
                </div>
            </article>                       
        <?php
        ?>
            </article>
            </section>
                       
            </section>
        </article>
        </section>
        <?php
        }else{
            //Lo que ve cualquier otro que no sea el administrador
            if(($promo['Estado']=="Aprobada" || $promo['Estado']=="Activa") && $diasRestantes>=0){
        ?>
            <!--<article>
            <div>
                <img src="img/<?php /*=$emP->getImg()*/?>" alt="">
            </div>
            <div>
                <div>
                    <h1><?php /*=$emP->getNombreEmpresa()*/?></h1>
                    <h1>Rubro: <?php /*=$emP->getRubro()*/?></h1>
                    <h1>Se ubica en: <?php /*=$emP->getDireccion()*/?></h1>
                </div>
                <div>
                    <h1><?php /*=$emP->getNombre()." ".$emP->getApellido()*/?></h1>
                    <h1>Telefono: <?php /*=$emP->getTelefono()*/?></h1>
                    <h1>Correo: <?php /*=$emP->getCorreo()*/?></h1>
                </div>
            </div>
        </article>-->
        <article>
            <h1>Promociones</h1>
            <section>
                <?php
                    $daoPromocion = new daoPromocion();
                    $pasadas = array();
                    $activas = array();
                    $futuras = array();
                    $promos = $daoPromocion->mostrarPromocionesCat($emP->getCodEmpresa(), "Aprobada");
                    $fechaActual = date('Y-m-d');
                                $diasRestantes = $daoPromocion->compararFechas($promo['FechaVencimiento'],$fechaActual);
                                
                    ?>
                        <article>
                            <div>
                                 <div>
                                    <h2><?=$promo['Titulo']?></h2>
                                    <p><?=$promo['Descripcion']?><br><?=$promo['Detalle']?></p>
                                </div>
                                <div>
                                    <h2><?=($promo['CantidadCupones']==null)?"No hay límite":$promo['CantidadCupones']?></h2>
                                    <h2>Inicia: <?=$promo['FechaInicio']?></h2>
                                    <h2>Termina: <?=$promo['FechaVencimiento']?></h2>
                                    <h2>Días Restantes: <?=($diasRestantes>0)?$diasRestantes:"Ha llegado a su fecha límite"?></h2>
                                    <h2>Los cupones se pueden canjear hasta: <?=$promo['FechaLimiteCupon']?></h2>
                                </div>
                                <div>
                                    <h2>Antes: $<?=$promo['PrecioRegular']?></h2>
                                    <h2>Ahora: $<?=$promo['PrecioOferta_Cupon']?></h2>
                                </div>
                                <?php
                                    switch($promo['Estado']){
                                        case 'Aprobada':
                                            echo "<h2 class=\"aprobada\">Estado: " . $promo['Estado'] . "</h2>";
                                            break;
                                    }
                                ?>

                                <a href="compraPromocion.php?promo=<?=$promo['CodPromocion']?>">Comprar</a>
                            </div>
                            <div>
                                <img src="img/<?=$promo['FotoPromocion']?>" alt="<?=$promo['FotoPromocion']?>">
                            </div>
                        </article>                       
            </section>
        </article>
        </section>
        <?php
                }else{
                    echo "<h1>promociones activas</h1>";
                }
        ?>
            
            
    
        <?php
            }
        }else{
            echo "<article>"; 
            echo "<h1>Debe elejir una promoción registrada</h1>";
            echo "</article>";
            echo "</section>";
        }
       
        require 'footer.php';
    ?>
</body>
</html>