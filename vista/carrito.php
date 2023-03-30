<?php
session_start();
$mensaje="";

if(isset($_POST['btnAccion'])){
    switch(($_POST['btnAccion'])){
        case 'AgregarCarrito':
            if(is_numeric(openssl_decrypt($_POST['id'],COD,KEY))){
                $ID = openssl_decrypt($_POST['id'],COD,KEY);
                $mensaje .= "OK id correcto " . $ID ."<br>";
            }else{
                $mensaje .= "UP id incorrecto". $ID ."<br>";
            }
            if(is_string(openssl_decrypt($_POST['nombre'],COD,KEY))){
                $NOMBRE = openssl_decrypt($_POST['nombre'],COD,KEY);
                $mensaje .= "OK nombre correcto " . $NOMBRE ."<br>";
            }else{ $mensaje .= "UP nombre incorrecto". $NOMBRE ."<br>"; break;}

            if(is_numeric(openssl_decrypt($_POST['cantidad'],COD,KEY))){
                $CANTIDAD = openssl_decrypt($_POST['cantidad'],COD,KEY);
                $mensaje .= "OK cantidad correcto " . $CANTIDAD ."<br>";
            }else{ $mensaje .= "UP cantidad incorrecto". $CANTIDAD ."<br>"; break;}

            if(is_numeric(openssl_decrypt($_POST['precio'],COD,KEY))){
                $PRECIO = openssl_decrypt($_POST['precio'],COD,KEY);
                $mensaje .= "OK precio correcto " . $PRECIO ."<br>";
            }else{ $mensaje .= "UP precio incorrecto". $PRECIO ."<br>"; break;}
            if(!isset($_SESSION['CARRITO'])){
                $producto = array(
                    'ID'=>$ID,
                    'NOMBRE'=>$NOMBRE,
                    'CANTIDAD'=>$CANTIDAD,
                    'PRECIO'=>$PRECIO
                );
                $_SESSION['CARRITO'][0]=$producto;
                $mensaje= "Producto agregado al carrito";

            }else{

                $idProductos = array_column($_SESSION['CARRITO'],"ID");
                if (in_array($ID,$idProductos)){
                        echo "<script>alert('El producto ya fue seleccionado');</script>";
                        $mensaje="";
                }else{

                $numeroProductos=count($_SESSION['CARRITO']);
                $producto = array(
                    'ID'=>$ID,
                    'NOMBRE'=>$NOMBRE,
                    'CANTIDAD'=>$CANTIDAD,
                    'PRECIO'=>$PRECIO
                );

                $_SESSION['CARRITO'][$numeroProductos]=$producto;
                $mensaje= "Producto agregado al carrito";
                }
            }

        break;
        case "Eliminar":

            if(is_numeric(openssl_decrypt($_POST['id'],COD,KEY))){
                $ID = openssl_decrypt($_POST['id'],COD,KEY);
               foreach ($_SESSION['CARRITO'] as $indice=>$producto){
                    if($producto['ID']==$ID){
                        unset($_SESSION['CARRITO'][$indice]);
                        echo "<script>alert('Elemento borrado');</script>";
                    }


               }
            }else{
                $mensaje .= "UP id incorrecto". $ID ."<br>";
            }

            break;
    }
}


?>
