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
        $id = isset($_GET['promo'])?$_GET['promo']:"adsa1";
        require '../modelo/Empresa/empresa.class.admin.php';
        require '../modelo/Empresa/daoEmpresas.admin.php';
        require '../modelo/Promocion/daoPromocion.admin.php';
        require '../modelo/Cupones/daoCupones.admin.php';
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
                        <a class="btn btn-primary col-md-12" href="../vista/empresa.admin.php?empresa=<?=$emP->getCodEmpresa()?>"><b><?=$emP->getNombreEmpresa()?></b></a>
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
                                        echo "<h2 class=\"alert alert-secondary\">" . $promo['Estado'] . $estado . "</h2>";
                                        break;
                                    case 'Rechazada':
                                        echo "<h2 class=\"alert alert-warning\">" . $promo['Estado'] . $estado . "</h2>";
                                        break;
                                    case 'En Espera de aprobación':
                                        echo "<h2 class=\"alert alert-primary\">" . $promo['Estado'] . $estado . "</h2>";
                                        echo "<a class=\"btn btn-success btn-lg \" href=\"../controlador/ctrlPromocion.admin.php?accion=Aprobar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Aprobar</h2></a>";
                                        echo "<a class=\"btn btn-danger btn-lg\"href=\"../controlador/ctrlPromocion.admin.php?accion=Rechazar&id=" . $promo['CodPromocion'] ."\"><h2 class=\"aprobar\">Rechazar</h2></a>";
                                        break;
                                    case 'Aprobada':
                                        echo "<h2 class=\"alert alert-success \">" . $promo['Estado'] . $estado . "</h2>";
                                        break;
                                    default:
                                        echo "<h2 class=\"alert alert-dark\">" . $promo['Estado'] . $estado . "</h2>";
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
                        
            </article>
            </section>
                       
            </section>
        </article>
        </section>
        <?php
            }else{
                if($promo['Estado']=="Aprobada" && $diasRestantes>=0){
        ?>
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
                        <a class="btn btn-primary col-md-12" href="../vista/empresa.admin.php?empresa=<?=$emP->getCodEmpresa()?>"><b><?=$emP->getNombreEmpresa()?></b></a>
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
        <article>
            <?php
            $fechaActual = date('Y-m-d');
            
            $diasRestantes = $daoPromocion->compararFechas($promo['FechaVencimiento'],$fechaActual);
                        
            ?>
            <h1>Promocion</h1>
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

                            case 'Aprobada':
                                echo "<h2 class=\"alert alert-success \">" . $promo['Estado'] . "</h2>";
                                break;
                            default:
                                echo "<h2 class=\"alert alert-success \">" . $promo['Estado'] . "</h2>";
                                break;
                        }
                    ?>
                    </div>
                </div>
                </article>         
            <section>
                
        </article>
        </section>
        <?php
                }else{
                    echo "<article class=\"text-center\">"; 
                        echo "<h1 class=\"alert alert-warning\">Solo puede ver Promociones Aprobadas</h1>";
                    echo "</article>";
                }
        ?>
            
            
    
        <?php
            }
        }else{
            echo "<article class=\"text-center\">"; 
            echo "<h1 class=\"alert alert-warning\">Debe elejir una promoción registrada</h1>";
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