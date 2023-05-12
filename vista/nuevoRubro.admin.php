<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Rubro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    
    <?php
        require 'menu.php';
    ?>
    <div>
        <div class="container">
        <?php
            if(isset($_SESSION['CodUsuario']) && $_SESSION['CodRol']==1){
        ?>
            <form action="../controlador/ctrlRubro.php?accion=Registrar" method="post">
                <legend>Modificando Rubro</legend>
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input class="form-control" type="text" name="nombre" id="nombre" required>
                </div>
                <div class="form-group">
                    <label for="nombre">Descripción</label>
                    <textarea class="form-control" name="desc" id="desc" required></textarea>
                </div>
                <input class="btn btn-success" type="submit" name="registro" value="Registrar">
                <a class="btn btn-danger" href="./vistaAdmin.php">Regresar</a>
            </form>
        </div>
    </div>
    <?php
        }else{
    ?>
        <div>
            <div class="container">
                <h1>Debes ser ADMINISTRADOR para ver este página: <a href="login.php">Inicia Sesión</a></h1>
            </div>
        </div>
    <?php
        }
    ?>
    
    <?php
        require 'footer.php';
    ?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>    
</body>
</html>