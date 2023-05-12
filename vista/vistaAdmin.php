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
        if(isset($_SESSION['CodUsuario']) && $_SESSION['CodRol'] == 1){
    ?>
    <ul class="nav justify-content-center  ">
        <li class="nav-item">
            <a class="nav-link active" href="<?=$_SERVER['PHP_SELF']?>?gestion=Rubro" aria-current="page">Rubros</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?=$_SERVER['PHP_SELF']?>?gestion=Empresas">Empresas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?=$_SERVER['PHP_SELF']?>?gestion=Promociones">Promociones</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?=$_SERVER['PHP_SELF']?>?gestion=Clientes">Clientes</a>
        </li>
    </ul>
    <div class="container">
    <?php
        if($_SESSION['mensaje'] != ""){
    ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <?=$_SESSION['mensaje']?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php
            $_SESSION['mensaje'] = "";
        }else{
            $_SESSION['mensaje'] = "";
        }

        if($_SESSION['error'] != ""){
    ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <?=$_SESSION['error']?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
    
    <?php
            $_SESSION['error'] = "";
        }else{
            $_SESSION['error'] = "";
        }
        $gestion = isset($_GET['gestion'])?$_GET['gestion']:"";
        
        switch($gestion){
            case 'Rubro':
                require 'rubros.admin.php';
                break;
            case 'Empresas':
                require 'empresas.admin.php';
                break;
            case 'Promociones':
                require 'promociones.admin.php';
                break;
            case 'Clientes':
                require 'clientes.admin.php';
                break;
            
            
            default:
                require 'rubros.admin.php';
            break;
        }
        ?>
        </div> 
    <?php
        }else{
    ?>
        <div class="container">
                <h1>Debes ser ADMINISTRADOR para ver este página: <a href="login.php">Inicia Sesión</a></h1>
        </div> 
    <?php
        }
        require 'footer.php';
    ?>    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>