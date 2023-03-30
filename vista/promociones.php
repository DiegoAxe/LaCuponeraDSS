<?php
        if(isset($_SESSION['CodRol']) && $_SESSION['CodRol'] == 1){
    ?>

<section>
        <?php
            require '../modelo/Empresa/empresa.class.php';
            require '../modelo/Empresa/daoEmpresas.php';
            require '../modelo/Promocion/daoPromocion.php';
            require '../modelo/Cupones/daoCupones.php';
            $dao = new daoEmpresa();
            $empresas = $dao->listaEmpresa();
            $todasEmpresas = array();
            
            if(count($empresas) > 0){
                //$nomEmpre, $porcentaje, $Rubro, $dir, $imag,$nom, $ape, $tel, $email, $role, $verify
                foreach($empresas as $empre){
                    $empresa = new Empresa($empre['NombreEmpresa'], $empre['PorcentajeComision'],
                        $empre['NombreRubro'], $empre['Direccion'], $empre['Nombre'], 
                        $empre['Apellido'],$empre['Telefono'], $empre['Correo'], 
                        $empre['CodRol'], $empre['Verificado']);
                    $empresa->setCodEmpresa($empre['CodEmpresa']);
                    $empresa->setCodUsuario($empre['CodUsuario']);
                    $empresa->setIMG($empre['Foto']);
                    array_push($todasEmpresas, $empresa);
                }

            foreach($todasEmpresas as $emP){
        ?>
        <article>
            <section>
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
                    <h1>Promociones</h1>
                    <section>
                        <article>
                            <h1>En espera de aprobación</h1>
                            <section>
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
                                        <h2>Días Restantes: <?=($diasRestantes>=0)?$diasRestantes:"Ha llegado a su fecha límite"?></h2>
                                        <h2>Los cupones se pueden canjear hasta: <?=$promo['FechaLimiteCupon']?></h2>
                                    </div>
                                    <div>
                                        <h2>Antes: $<?=$promo['PrecioRegular']?></h2>
                                        <h2>Ahora: $<?=$promo['PrecioOferta_Cupon']?></h2>
                                    </div>
                                    <?php
                                        switch($promo['Estado']){
                                            case 'Descartada':
                                                echo "<h2 class=\"descartada\">Estado: " . $promo['Estado'] . "</h2>";
                                                break;
                                            case 'Rechazada':
                                                echo "<h2 class=\"rechazada\">Estado: " . $promo['Estado'] . "</h2>";
                                                break;
                                            case 'En Espera de aprobación':
                                                echo "<h2 class=\"espera\">Estado: " . $promo['Estado'] . "</h2>";
                                                echo "<a href=\"../controlador/ctrlPromocion.php?accion=Aprobar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Aprobar</h2></a>";
                                                echo "<a href=\"../controlador/ctrlPromocion.php?accion=Rechazar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Rechazar</h2></a>";
                                                break;
                                            case 'Aprobada':
                                                echo "<h2 class=\"aprobada\">Estado: " . $promo['Estado'] . "</h2>";
                                                break;
                                            default:
                                                echo "<h2 class=\"vencido\">Estado: " . $promo['Estado'] . "</h2>";
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
                                }
                        ?>
                            </article>
                            </section>
                        <?php
                            }else{
                                echo "<article>";
                                echo "<h1 class=\"existencia\">Aún no hay promociones en espera de aprobación.</h1>";
                                echo "</article>";
                                echo "</section>";
                                echo "</article>";
                                echo "</section>";
                            }
                    ?>
                    <section>
                        <article>
                            <h1>Aprobadas</h1>
                    <?php
                            //Aprobadas
                            $pasadas = array();
                            $activas = array();
                            $futuras = array();
                            $promos = $daoPromocion->mostrarPromocionesCat($emP->getCodEmpresa(), "Aprobada");
                        
                            if(count($promos)>0){
                                ?>
                        <section>
                                <article>
                                    <h1>Futuras</h1>
                        <?php
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
                                if(count($futuras)>0){
                                    foreach($futuras as $cant=>$promo){
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
                                            <h2>Días Restantes: <?=($diasRestantes>=0)?$diasRestantes:"Ha llegado a su fecha límite"?></h2>
                                            <h2>Los cupones se pueden canjear hasta: <?=$promo['FechaLimiteCupon']?></h2>
                                        </div>
                                        <div>
                                            <h2>Antes: $<?=$promo['PrecioRegular']?></h2>
                                            <h2>Ahora: $<?=$promo['PrecioOferta_Cupon']?></h2>
                                        </div>
                                        <?php
                                            switch($promo['Estado']){
                                                case 'Descartada':
                                                    echo "<h2 class=\"descartada\">Estado: " . $promo['Estado'] . "</h2>";
                                                    break;
                                                case 'Rechazada':
                                                    echo "<h2 class=\"rechazada\">Estado: " . $promo['Estado'] . "</h2>";
                                                    break;
                                                case 'En Espera de aprobación':
                                                    echo "<h2 class=\"espera\">Estado: " . $promo['Estado'] . "</h2>";
                                                    echo "<a href=\"../controlador/ctrlPromocion.php?accion=Aprobar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Aprobar</h2></a>";
                                                    echo "<a href=\"../controlador/ctrlPromocion.php?accion=Rechazar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Rechazar</h2></a>";
                                                    break;
                                                case 'Aprobada':
                                                    echo "<h2 class=\"aprobada\">Estado: " . $promo['Estado'] . "</h2>";
                                                    break;
                                                default:
                                                    echo "<h2 class=\"vencido\">Estado: " . $promo['Estado'] . "</h2>";
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
                                    }
                            ?>
                                
                                </article>
                            <?php

                                }else{
                                    echo "<article>";
                                    echo "<h1 class=\"existencia\">Aún no hay promociones Aprobadas a Futuro.</h1>";
                                    echo "</article>";
                                    echo "</section>";
                                    echo "</article>";
                                    echo "</section>";
                                }
                                 
                            ?>
                                <article>
                                    <h1>Activas</h1>
                    <?php
                        if(count($activas)>0){
                            foreach($activas as $cant=>$promo){
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
                                            <h2>Días Restantes: <?=($diasRestantes>=0)?$diasRestantes:"Ha llegado a su fecha límite"?></h2>
                                            <h2>Los cupones se pueden canjear hasta: <?=$promo['FechaLimiteCupon']?></h2>
                                        </div>
                                        <div>
                                            <h2>Antes: $<?=$promo['PrecioRegular']?></h2>
                                            <h2>Ahora: $<?=$promo['PrecioOferta_Cupon']?></h2>
                                        </div>
                                        <?php
                                            switch($promo['Estado']){
                                                case 'Descartada':
                                                    echo "<h2 class=\"descartada\">Estado: " . $promo['Estado'] . "</h2>";
                                                    break;
                                                case 'Rechazada':
                                                    echo "<h2 class=\"rechazada\">Estado: " . $promo['Estado'] . "</h2>";
                                                    break;
                                                case 'En Espera de aprobación':
                                                    echo "<h2 class=\"espera\">Estado: " . $promo['Estado'] . "</h2>";
                                                    echo "<a href=\"../controlador/ctrlPromocion.php?accion=Aprobar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Aprobar</h2></a>";
                                                    echo "<a href=\"../controlador/ctrlPromocion.php?accion=Rechazar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Rechazar</h2></a>";
                                                    break;
                                                case 'Aprobada':
                                                    echo "<h2 class=\"aprobada\">Estado: " . $promo['Estado'] . "</h2>";
                                                    break;
                                                default:
                                                    echo "<h2 class=\"vencido\">Estado: " . $promo['Estado'] . "</h2>";
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
                                    }
                            ?>
                                </article>

                            <?php

                                }else{
                                    echo "<article>";
                                    echo "<h1 class=\"existencia\">Aún no hay promociones aprobadas Activas.</h1>";
                                    echo "</article>";
                                    echo "</section>";
                                }
                                 
                            ?>
                            <article>
                                    <h1>Pasadas</h1>
                    <?php
                        if(count($pasadas)>0){
                            foreach($pasadas as $cant=>$promo){
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
                                            <h2>Días Restantes: <?=($diasRestantes>=0)?$diasRestantes:"Ha llegado a su fecha límite"?></h2>
                                            <h2>Los cupones se pueden canjear hasta: <?=$promo['FechaLimiteCupon']?></h2>
                                        </div>
                                        <div>
                                            <h2>Antes: $<?=$promo['PrecioRegular']?></h2>
                                            <h2>Ahora: $<?=$promo['PrecioOferta_Cupon']?></h2>
                                        </div>
                                        <?php
                                            switch($promo['Estado']){
                                                case 'Descartada':
                                                    echo "<h2 class=\"descartada\">Estado: " . $promo['Estado'] . "</h2>";
                                                    break;
                                                case 'Rechazada':
                                                    echo "<h2 class=\"rechazada\">Estado: " . $promo['Estado'] . "</h2>";
                                                    break;
                                                case 'En Espera de aprobación':
                                                    echo "<h2 class=\"espera\">Estado: " . $promo['Estado'] . "</h2>";
                                                    echo "<a href=\"../controlador/ctrlPromocion.php?accion=Aprobar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Aprobar</h2></a>";
                                                    echo "<a href=\"../controlador/ctrlPromocion.php?accion=Rechazar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Rechazar</h2></a>";
                                                    break;
                                                case 'Aprobada':
                                                    echo "<h2 class=\"aprobada\">Estado: " . $promo['Estado'] . "</h2>";
                                                    break;
                                                default:
                                                    echo "<h2 class=\"vencido\">Estado: " . $promo['Estado'] . "</h2>";
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
                                    }
                            ?>
                                </article>
                                </section>
                                </article>
                                </section>
                            <?php

                                }else{
                                    echo "<h1 class=\"existencia\">Aún no hay promociones Pasadas.</h1>";
                                    echo "</article>";
                                    echo "</section>";
                                }
                                 
                            ?>
                        <?php
                            }else{
                                echo "<h1 class=\"existencia\">Aún no hay promociones Aprobadas.</h1>";
                                echo "</article>";
                                echo "</section>";
                            }
                    ?>
                    <section>
                        <article>
                            <h1>Rechazadas</h1>
                            <section>
                    <?php
                        //Rechazada
                        $promos = $daoPromocion->mostrarPromocionesCat($emP->getCodEmpresa(), "Rechazada");
                        if(count($promos)>0){
                            foreach($promos as $promo){
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
                                        <h2>Días Restantes: <?=($diasRestantes>=0)?$diasRestantes:"Ha llegado a su fecha límite"?></h2>
                                        <h2>Los cupones se pueden canjear hasta: <?=$promo['FechaLimiteCupon']?></h2>
                                    </div>
                                    <div>
                                        <h2>Antes: $<?=$promo['PrecioRegular']?></h2>
                                        <h2>Ahora: $<?=$promo['PrecioOferta_Cupon']?></h2>
                                    </div>
                                    <?php
                                        switch($promo['Estado']){
                                            case 'Descartada':
                                                echo "<h2 class=\"descartada\">Estado: " . $promo['Estado'] . "</h2>";
                                                break;
                                            case 'Rechazada':
                                                echo "<h2 class=\"rechazada\">Estado: " . $promo['Estado'] . "</h2>";
                                                break;
                                            case 'En Espera de aprobación':
                                                echo "<h2 class=\"espera\">Estado: " . $promo['Estado'] . "</h2>";
                                                echo "<a href=\"../controlador/ctrlPromocion.php?accion=Aprobar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Aprobar</h2></a>";
                                                echo "<a href=\"../controlador/ctrlPromocion.php?accion=Rechazar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Rechazar</h2></a>";
                                                break;
                                            case 'Aprobada':
                                                echo "<h2 class=\"aprobada\">Estado: " . $promo['Estado'] . "</h2>";
                                                break;
                                            default:
                                                echo "<h2 class=\"vencido\">Estado: " . $promo['Estado'] . "</h2>";
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
                                }
                        ?>
                            </article>
                            </section>
                        <?php
                            }else{
                                echo "<article>";
                                echo "<h1 class=\"existencia\">Aún no hay promociones Rechazadas.</h1>";
                                echo "</article>";
                                echo "</section>";
                                echo "</article>";
                                echo "</section>";
                            }
                    ?>
                    <section>
                        <article>
                            <h1>Descartada</h1>
                            <section>
                    <?php
                            //Descartada
                            $promos = $daoPromocion->mostrarPromocionesCat($emP->getCodEmpresa(), "Descartada");
                            if(count($promos)>0){
                                foreach($promos as $promo){
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
                                        <h2>Días Restantes: <?=($diasRestantes>=0)?$diasRestantes:"Ha llegado a su fecha límite"?></h2>
                                        <h2>Los cupones se pueden canjear hasta: <?=$promo['FechaLimiteCupon']?></h2>
                                    </div>
                                    <div>
                                        <h2>Antes: $<?=$promo['PrecioRegular']?></h2>
                                        <h2>Ahora: $<?=$promo['PrecioOferta_Cupon']?></h2>
                                    </div>
                                    <?php
                                        switch($promo['Estado']){
                                            case 'Descartada':
                                                echo "<h2 class=\"descartada\">Estado: " . $promo['Estado'] . "</h2>";
                                                break;
                                            case 'Rechazada':
                                                echo "<h2 class=\"rechazada\">Estado: " . $promo['Estado'] . "</h2>";
                                                break;
                                            case 'En Espera de aprobación':
                                                echo "<h2 class=\"espera\">Estado: " . $promo['Estado'] . "</h2>";
                                                echo "<a href=\"../controlador/ctrlPromocion.php?accion=Aprobar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Aprobar</h2></a>";
                                                echo "<a href=\"../controlador/ctrlPromocion.php?accion=Rechazar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Rechazar</h2></a>";
                                                break;
                                            case 'Aprobada':
                                                echo "<h2 class=\"aprobada\">Estado: " . $promo['Estado'] . "</h2>";
                                                break;
                                            default:
                                                echo "<h2 class=\"vencido\">Estado: " . $promo['Estado'] . "</h2>";
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
                                }
                        ?>
                            </article>
                            </section>
                        <?php
                            }else{
                                echo "<article>";
                                echo "<h1 class=\"existencia\">Aún no hay promociones Descartadas.</h1>";
                                echo "</article>";
                                echo "</section>";
                                echo "</article>";
                                echo "</section>";
                            }
                    ?>
                
            </article>
            </article>
        <?php
                   
            }
        ?>
            </article>
            </section>
        <?php
            }else{
                ?>
                <h1>Aún no hay empresas, puede registrarlas</h1>    
                <?php
            }

        ?>
        </article>
        <?php
            }else{
        ?>
        <article>
            <h1>No puedes acceder Aquí si no eres ADMINISTRADOR <a href="login.php">Inicia Sesión</a></h1>
        </article>
        <?php
            }
        ?>
</section>