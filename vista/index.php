<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head> 
<body>
    <?php
        require 'menu.php';
        $Apellidos = isset($_SESSION['Apellido']) ?$_SESSION['Apellido'] : "";
        $Nombre = isset($_SESSION['Nombre']) ?$_SESSION['Nombre'] : "";
        $cod = 4;
        if(isset($_SESSION['CodRol'])){
            $cod = $_SESSION['CodRol'];
        }

        switch($cod){
            case 1:
                require 'home.php';
                break;
            default:
                require 'home.php';
                break;
        }

        require 'footer.php';

    ?>
</body>
</html>