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
        $id = isset($_GET['id'])?$_GET['id']:"1";
        require '../modelo/Cliente/daoCliente.php';
        require '../modelo/Cupones/daoCupones.php';
        require '../modelo/Promocion/daoPromocion.php';
        $daoCliente = new daoCliente();
        $daoCupon = new daoCupones();
        $daoPromocion = new daoPromocion();
        $fechaActual = date('Y-m-d');
        if(isset($_SESSION['CodRol']) && $_SESSION['CodRol'] == 1){
            $cliente = $daoCliente->mostrarCliente($id);
            if($cliente!=false){
        ?>
            <article>
                    <div>
                        <img src="img/<?=$cliente['Foto']?>" alt="">
                    </div>
                    <div>
                        <a href="../vista/cliente.php?id=<?=$cliente['CodUsuario']?>"><h1><?=$cliente['CodUsuario'] . " - " . $cliente['Nombre'] . " " . $cliente['Apellido']?></h1></a>
                        <h1>DUI: <?=$cliente['DUI']?></h1>
                        <h1>Telefono: <?=$cliente['Telefono']?></h1>
                        <h1>Correo: <?=$cliente['Correo']?></h1>
                        <p>Direccion: <?=$cliente['Direccion']?></p>
                        <h1>Verificado: <?=$cliente['Verificado']?></h1>
                    </div>
                    <?php
                        if($cliente['Verificado'] == "Si"){
                    ?>
                        <section>
                            <h1>Cupones</h1>
                        <?php
                            $disponibles=array();
                            $canjeados=array();
                            $vencidos=array();
                            $todoCupon=array();
                            $titulos = array("Disponibles", "Canjeados", "Vencidos");
                            $cupones = $daoCupon->mostrarCuponesCompleto($cliente['CodUsuario']);
                            if(count($cupones)> 0){
                                foreach($cupones as $cupon){
                                    $diasCupones = $daoPromocion->compararFechas($cupon['FechaLimiteCupon'], $fechaActual);
                                    if($diasCupones<0 && $cupon['EstadoCupon'] != "Canjeado"){
                                        $daoCupon->actualizarEstadoCupon("Vencido", $cupon['CodCupon']);
                                    }
                                }
                                foreach($cupones as $cupon){
                                    $diasCupones = $daoPromocion->compararFechas($cupon['FechaLimiteCupon'], $fechaActual);
                                    if($diasCupones<0 && $cupon['EstadoCupon'] != "Canjeado"){
                                        array_push($vencidos, $cupon);
                                    }elseif($cupon['EstadoCupon']== "Canjeado"){
                                        array_push($canjeados, $cupon);
                                    }else{
                                        array_push($disponibles, $cupon);
                                    }
                                }
                                array_push($todoCupon, $disponibles);
                                array_push($todoCupon, $canjeados);
                                array_push($todoCupon, $vencidos);
                                $i=0;
                            foreach($todoCupon as $cat){
                                echo "<div>";
                                echo "<h1>" . $titulos[$i] . "</h1>";
                                $i++;
                                if(count($cat)>0){
                                    foreach($cat as $cupon){
                                        $diasCupones = $daoPromocion->compararFechas($cupon['FechaLimiteCupon'], $fechaActual);
    
                            ?>
                            <article>
                                <div>
                                
                                <h1>Código: <?=$cupon['CodCupon']?></h1>
                                <h1>Estado: <?=$cupon['EstadoCupon']?></h1>
                                </div>
                                <div>
                        <?php
    
                                $emp = $cupon['CodEmpresa'];
                        ?>
                                <article>
                                    <div>
                                        <div>
                                            <a href="../vista/empresa.php?empresa=<?=$cupon['CodEmpresa']?>">Empresa: <?=$cupon['NombreEmpresa']?></a>
                                            <h2><?=$cupon['Titulo']?></h2>
                                            <h2>Ahora: $<?=$cupon['PrecioOferta_Cupon']?></h2>
                                            <h2>Se puede canjear hasta: <?=$cupon['FechaLimiteCupon']?></h2>
                                            <h2>Días restantes: <?=($diasCupones>=0)?$diasCupones:"Ya ha llegado a su fecha límite."?></h2>
                                        </div>
                                        <?php
                                            switch($cupon['Estado']){
                                                case 'Descartada':
                                                    echo "<h2 class=\"descartada\">Estado: " . $cupon['Estado'] . "</h2>";
                                                    break;
                                                case 'Rechazada':
                                                    echo "<h2 class=\"rechazada\">Estado: " . $cupon['Estado'] . "</h2>";
                                                    break;
                                                case 'En Espera de aprobación':
                                                    echo "<h2 class=\"espera\">Estado: " . $cupon['Estado'] . "</h2>";
                                                    echo "<a href=\"../controlador/ctrlPromocion.php?accion=Aprobar&id=" . $cupon['CodPromocion'] ."\"><h2 class=\"aprobar\">Aprobar</h2></a>";
                                                    echo "<a href=\"../controlador/ctrlPromocion.php?accion=Rechazar&id=" . $cupon['CodPromocion'] ."\"><h2 class=\"aprobar\">Rechazar</h2></a>";
                                                    break;
                                                case 'Aprobada':
                                                    echo "<h2 class=\"aprobada\">Estado: " . $cupon['Estado'] . "</h2>";
                                                    break;
                                                default:
                                                    echo "<h2 class=\"vencido\">Estado: " . $cupon['Estado'] . "</h2>";
                                                    break;
                                            }
                                        ?>
                                    </div>
                                    <div>
                                        <a href="../vista/promocion.php?promo=<?=$cupon['CodPromocion']?>"><h1>Detalles de la oferta</h1></a>
                                    </div>
                                        </article>
                                    <article>
                                        <img src="img/<?=$cupon['FotoPromocion']?>" alt="<?=$cupon['FotoPromocion']?>">
                                </article>                       
                                </div>
                            </article>
                            <?php
                                    }
                                }else{
                                    echo "<p>Aún no tiene cupones con este Estado</p>";
                                }
                                
                                echo "</div>";
                            }
                            }else{
                                echo "<article>"; 
                                echo "<h1>Aún no ha comprado ofertas</h1>";
                                echo "</article>";
                            }
                        ?>
                        
                    </section>
                    <?php
                        }
                    ?>
                </article>
                <?php

            }else{
                echo "<article>"; 
                echo "<h1>Debe elejir un Cliente registrado</h1>";
                echo "</article>";
                echo "</section>";
            }
        }else{
            echo "<article>"; 
            echo "<h1>Debes ser ADMINISTRADOR para ver este página: <a href=\"login.php\">Inicia Sesión</a></h1>";
            echo "</article>";
            echo "</section>";  
        }
       
        require 'footer.php';
    ?>
</body>
</html>