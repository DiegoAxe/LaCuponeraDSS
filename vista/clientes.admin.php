<?php
        if(isset($_SESSION['CodRol']) && $_SESSION['CodRol'] == 1){
    ?>
<section class="container mt-5">
    <article class="d-flex justify-content-center row" >
        <section class="col-md-15" >
            <article class="coupon p-3 bg-white">
                <h1 class="text-center h1">Clientes</h1>
        <?php
            require '../modelo/Cliente/daoCliente.admin.php';
            require '../modelo/Cupones/daoCupones.admin.php';
            require '../modelo/Promocion/daoPromocion.admin.php';
            require '../modelo/Empresa/daoEmpresas.admin.php';
            $dao = new daoCliente();
            $clientes = $dao->listaClientes();
            $clientesTodos = array();
            $daoCupon = new daoCupones();
            $daoPromocion = new daoPromocion();
            $daoEmpresa = new daoEmpresa();

            $fechaActual = date('Y-m-d');
            if(count($clientes) > 1){
                foreach($clientes as $cliente){
                ?>
                <div class="row no-gutters justify-content-center align-items-center border-top">
                    <div class="col-md-5 text-center">
                        <img class="img-fluid" width="300" src="img/<?=$cliente['Foto']?>" alt="<?=$cliente['Foto']?>">
                    </div>
                    <div class="col-md-6 text-justify m-3">
                        <a class="h4" href="../vista/cliente.admin.php?id=<?=$cliente['CodUsuario']?>"><p><?=$cliente['Apellido'] . ", " . $cliente['Nombre']?></p></a>
                        <p>DUI: <?=$cliente['DUI']?></p>
                        <p>Telefono: <?=$cliente['Telefono']?></p>
                        <p>Correo: <?=$cliente['Correo']?></p>
                        <p>Direccion: <br> <?=$cliente['Direccion']?></p>
                        <p>Verificado: <?=$cliente['Verificado']?></p>
                    </div>
                </div>
                <div class="coupon p-3 bg-white">
                    <?php
                        if(isset($cliente['Verificado'])){
                    ?>    
                    <h1 class="text-center">Sus cupones</h1>
                    <section class="row no-gutters justify-content-center align-items-center">
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
                                echo "<br>";
                                echo "<h1 class=\"text-center\">" . $titulos[$i] . "</h1>";
                                echo "<br>";
                                $i++;
                                if(count($cat)>0){
                                    foreach($cat as $cupon){
                                        $diasCupones = $daoPromocion->compararFechas($cupon['FechaLimiteCupon'], $fechaActual);
    
                            ?>
                            <article class="row no-gutters justify-content-center align-items-center border">
                                <div class="col-md-4 text-center  justify-content-center">
                                
                                    <h3>Código: <?=$cupon['CodCupon']?></h3>
                                    <h2 class="alert alert-success col-md-8"><?=$cupon['EstadoCupon']?></h2>
                                </div>
                                <div class="col-md-6">
                                    <div class="row no-gutters justify-content-center align-items-center">
                                        <div class="col-md-8">
                                            <a class="btn btn-primary col-md-12" href="../vista/empresa.admin.php?empresa=<?=$cupon['CodEmpresa']?>"><?=$cupon['NombreEmpresa']?></a>
                                            <p><?=$cupon['Titulo']?></p>
                                            <p>Ahora: $<?=$cupon['PrecioOferta_Cupon']?></p>
                                            <p>Se puede canjear hasta: <?=$cupon['FechaLimiteCupon']?></p>
                                            <p>Días restantes: <?=($diasCupones>=0)?$diasCupones:"Ya ha llegado a su fecha límite."?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <img class="img img-fluid" src="img/<?=$cupon['FotoPromocion']?>" alt="<?=$cupon['FotoPromocion']?>">
                                        </div>
                                        <div class="col-md-12 text-center">
                                        <?="<h2 class=\"alert alert-success\">" . $cupon['Estado'] . "</h2>"?>
                                        <h2 class="alert alert-secondary"><a  href="../vista/promocion.admin.php?promo=<?=$cupon['CodPromocion']?>">Detalles de la oferta</a></h1>
                                    </div> 
                                    <br>
                                    <br>
                            </article>
                            <br><br>
                            <?php
                                    }
                                }else{
                                    echo "<p class=\"alert alert-secondary\">Aún no tiene cupones con este Estado</p>";
                                }
                                
                                echo "</div>";
                            }
                            }else{
                                echo "<article>"; 
                                echo "<h1 class=\"alert alert-secondary\">Aún no ha comprado ofertas</h1>";
                                echo "</article>";
                            }
                        ?>
                        
                    </section>
                    <?php
                        }
                    ?>
                </div>
                <?php

                }
            }
        ?>
        <?php
            }else{
        ?>
        <article>
            <h1>No puedes acceder Aquí si no eres ADMINISTRADOR <a href="login.php">Inicia Sesión</a></h1>
        </article>
        <?php
            }
        ?>
            </article>
        </section>
    </article>
</section>