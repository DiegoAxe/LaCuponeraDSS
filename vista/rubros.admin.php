    <?php
        if(isset($_SESSION['CodRol']) && $_SESSION['CodRol'] == 1){

        
    ?>
    <a class="btn btn-success" href="nuevoRubro.admin.php">Nuevo Rubro</a>

    <?php
        require '../modelo/Rubro/rubro.class.admin.php';
        require '../modelo/Rubro/daoRubro.admin.php';
        $dao = new daoRubro();
        $rubros = $dao->listaRubro();
        $rubrosTodos = array();
        
        if(count($rubros) > 1){
            foreach($rubros as $rubro){
                $rub = new Rubro($rubro['NombreRubro'], $rubro['Descripcion']);
                $rub->setCodRubro($rubro['CodRubro']);
                array_push($rubrosTodos, $rub);
            }
    ?>

        <div class="table-responsive">
            <table class="table table-light">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Descripcion</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
    <?php
        foreach($rubrosTodos as $rubroP){
            echo "<tr>";
                echo "<td>" . $rubroP->getCodRubro() ."</td>";
                echo "<td>" . $rubroP->getNombre() ."</td>";
                echo "<td>" . $rubroP->getDescripcion() ."</td>";
                echo "<td>";
            
                echo "<a class=\"btn btn-warning d-inline\" href=\"../controlador/ctrlRubro.php?accion=Modificar&id=".$rubroP->getCodRubro()."\">Modificar</a>";
                echo "<a class=\"btn btn-danger d-inline\"href=\"../controlador/ctrlRubro.php?accion=Eliminar&id=".$rubroP->getCodRubro()."\">Eliminar</a>";
                echo "</td>";
            echo "</tr>";
        }   
        }
    ?>
                </tbody>
            </table>
        </div>

        <?php
            }else{
        ?>
            <h1>No puedes acceder Aquí si no eres ADMINISTRADOR <a href="login.php">Inicia Sesión</a></h1>
        <?php
            }
        ?>