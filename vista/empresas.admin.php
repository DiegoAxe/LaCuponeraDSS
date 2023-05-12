<?php
        if(isset($_SESSION['CodRol']) && $_SESSION['CodRol'] == 1){
    ?>
        <a class="btn btn-success"  href="../controlador/ctrlEmpresa.admin.php?accion=Registrar">Nueva Empresa</a>
    <?php
        require '../modelo/Empresa/empresa.class.admin.php';
        require '../modelo/Empresa/daoEmpresas.admin.php';
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
    ?>
        <div class="table-responsive">
            <table class="table table-light">
                <thead class="thead-light">
                    <tr>
                        <th>Codigo de Empresa</th>
                        <th>Nombre</th>
                        <th>Porcentaje de Comisión (%)</th>
                        <th>Rubro</th>
                        <th>Direccion</th>
                        <th>Foto</th>
                        <th>Encargado</th>
                        <th>Telefono</th>
                        <th>Correo</th>
                        <th>Verificado</th>
                    </tr>
                </thead>
        <?php
                foreach($todasEmpresas as $emP){
                    echo "<tr>";
                        echo "<td>" . $emP->getCodEmpresa() ."</td>";
                        echo "<td>" . $emP->getNombreEmpresa() ."</td>";
                        echo "<td>" . $emP->getPorcentajeComision() ."</td>";
                        echo "<td>" . $emP->getRubro() ."</td>";
                        echo "<td>" . $emP->getDireccion() ."</td>";
                        echo "<td class=\"rounded  img-fluid\"><img  src=\"img/".$emP->getImg()."\" alt=\"".$emP->getImg()."\" width=\"300\"></td>";
                        echo "<td>" . $emP->getCodUsuario() . " - ". $emP->getNombre() . " " . $emP->getApellido() ."</td>";
                        echo "<td>" . $emP->getTelefono() ."</td>";
                        echo "<td>" . $emP->getCorreo() ."</td>";
                        echo "<td>" . $emP->getVerificado() ."</td>";
                        echo "<td>";
                    
                        echo "<a class=\"btn btn-warning\" href=\"../controlador/ctrlEmpresa.admin.php?accion=Modificar&id=".$emP->getCodEmpresa()."\">Modificar</a><br>";
                        echo "<a class=\"btn btn-danger\" href=\"../controlador/ctrlEmpresa.admin.php?accion=Eliminar&id=".$emP->getCodEmpresa()."\">Eliminar</a>";
                        echo "<a class=\"btn btn-primary\"href=\"empresa.admin.php?empresa=".$emP->getCodEmpresa()."\">Ver Empresa</a>";
                        echo "</td>";
                    echo "</tr>";
                } 
            }else{
                ?>
                <h1>Aún no hay empresas, puede registrarlas</h1>    
                <?php
            }

        ?>
            </table>
        </div>

        <?php
            }else{
        ?>
        <article>
            <h1>No puedes acceder Aquí si no eres ADMINISTRADOR <a href="login.php">Inicia Sesión</a></h1>
        </article>
        <?php
            }
        ?>