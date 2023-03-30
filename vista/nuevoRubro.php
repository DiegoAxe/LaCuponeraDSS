<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Rubro</title>
</head>
<body>
    <?php
        require 'menu.php';
    ?>
    <section>
        <article>
            <?php
                if(isset($_SESSION['CodUsuario']) && $_SESSION['CodRol']==1){
            ?>
                <form action="controlador/ctrlRubro.php?accion=Registrar" method="post">
                    <legend>Nuevo Rubro</legend>
                    <div>
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" required>
                    </div>
                    <div>
                        <label for="desc">Descripción</label>
                        <textarea name="desc" id="desc" required></textarea>
                    </div>
                    <input type="submit" name="registro" value="Registrar">
                </form>
            <?php
                }else{
            ?>
                 <h1>Debes ser ADMINISTRADOR para ver este página: <a href="login.php">Inicia Sesión</a></h1>
            <?php
                }
            ?>
        </article>     
    </section>
    <?php
        require 'footer.php';
    ?>
</body>
</html>