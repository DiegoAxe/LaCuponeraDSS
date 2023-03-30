<?php
        if(isset($_SESSION['CodRol']) && $_SESSION['CodRol'] == 1){
    ?>
    <section>
        <article>
            <a href="../controlador/ctrlEmpresa.php?accion=Registrar">Nueva Empresa</a>
        <?php
            require '../modelo/Empresa/empresa.class.php';
            require '../modelo/Empresa/daoEmpresas.php';
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
            <table>
                <thead>
                    <tr>
                        <td>Codigo de Empresa</td>
                        <td>Nombre</td>
                        <td>Porcentaje de Comisión</td>
                        <td>Rubro</td>
                        <td>Direccion</td>
                        <td>Foto</td>
                        <td>Encargado</td>
                        <td>Telefono</td>
                        <td>Correo</td>
                        <td>Verificado</td>
                    </tr>
                </thead>
        <?php
                foreach($todasEmpresas as $emP){
                    echo "<tr>";
                        echo "<td>" . $emP->getCodEmpresa() ."</td>";
                        echo "<td>" . $emP->getNombreEmpresa() ."</td>";
                        echo "<td>" . $emP->getPorcentajeComision() ."%</td>";
                        echo "<td>" . $emP->getRubro() ."</td>";
                        echo "<td>" . $emP->getDireccion() ."</td>";
                        echo "<td><img src=\"img/".$emP->getImg()."\" alt=\"".$emP->getImg()."\"></td>";
                        echo "<td>" . $emP->getCodUsuario() . " - ". $emP->getNombre() . " " . $emP->getApellido() ."</td>";
                        echo "<td>" . $emP->getTelefono() ."</td>";
                        echo "<td>" . $emP->getCorreo() ."</td>";
                        echo "<td>" . $emP->getVerificado() ."</td>";
                        echo "<td>";
                    
                        echo "<a href=\"../controlador/ctrlEmpresa.php?accion=Modificar&id=".$emP->getCodEmpresa()."\">Modificar</a><br>";
                        echo "<a href=\"../controlador/ctrlEmpresa.php?accion=Eliminar&id=".$emP->getCodEmpresa()."\">Eliminar</a>";
                        echo "<a href=\"empresa.php?empresa=".$emP->getCodEmpresa()."\">Ver Empresa</a>";
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