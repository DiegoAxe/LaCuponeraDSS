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
        if(isset($_SESSION['CodUsuario']) && $_SESSION['CodRol'] == 1){
    ?>
        <section>
            <article>
                <h1><a href="<?=$_SERVER['PHP_SELF']?>?gestion=Rubro">Rubros</a></h1>
                <h1><a href="<?=$_SERVER['PHP_SELF']?>?gestion=Empresas">Empresas</a></h1>
                <h1><a href="<?=$_SERVER['PHP_SELF']?>?gestion=Promociones">Promociones</a></h1>
                <h1><a href="<?=$_SERVER['PHP_SELF']?>?gestion=Clientes">Clientes</a></h1>
            </article>
        </section>
    <?php
        $gestion = isset($_GET['gestion'])?$_GET['gestion']:"";
        switch($gestion){
            case 'Rubro':
                require 'rubros.php';
                break;
            case 'Empresas':
                require 'empresas.php';
                break;
            case 'Promociones':
                require 'promociones.php';
                break;
            case 'Clientes':
                require 'clientes.php';
                break;
            
            
            default:
                require 'rubros.php';
            break;
        }
        }else{
    ?>
        <section>
            <article>
                <h1>Debes ser ADMINISTRADOR para ver este página: <a href="login.php">Inicia Sesión</a></h1>
            </article>
        </section>

    <?php
        }
        require 'footer.php';
    ?>    
</body>
</html>