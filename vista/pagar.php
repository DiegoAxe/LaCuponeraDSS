<?php
require '../modelo/Promocion/daoPromocion.php';
require '../modelo/Cupones/daoCupones.php';
require '../modelo/Empresa/empresa.class.php';
require '../controlador/constantes.php';
include 'carrito.php';
include 'cabecera.php';

?>

<?php

if ($_POST){
    $total = 0;
    $SID=session_id();
    $correo = $_POST['email'];
foreach ($_SESSION['CARRITO'] as $indice=>$producto){

        $total=$total+($producto['PRECIO']*$producto['CANTIDAD']);

    }
     $pdo = new PDO('mysql:host=localhost;dbname=proyecto_cuponera','root','');
    $sentencia=$pdo->prepare("INSERT INTO `tblventas` (`ID`, `ClaveTransaccion`, `PaypalDatos`, `Fecha`, `Correo`, `Total`, `status`) VALUES (NULL,:ClaveTransaccion,'',NOW(),:Correo,:Total, 'pendiente')");

    $sentencia->bindParam(":ClaveTransaccion",$SID);
    $sentencia->bindParam(":Correo",$correo);
    $sentencia->bindParam(":Total",$total);
    $idVenta = $pdo->lastInsertId();


    foreach ($_SESSION['CARRITO'] as $indice=>$producto){

        //INSERT INTO `cupon` (`CodCupon`, `CodPromocion`, `CodUsuario`, `EstadoCupon`, `FechaCanje`)
        //VALUES (
//            CONCAT(
//                LEFT((SELECT `CodCupon` FROM `cupon` ORDER BY `CodCupon` DESC LIMIT 1), 10),
//                RIGHT(CONCAT('000', CAST(RAND()*1000 AS UNSIGNED)), 3)
//            ),
        //    '8', '5', 'Disponible', NULL
        //);

        //INSERT INTO `tbldetalleventa` (`ID`, `IDVENTA`, `IDPRODUCTO`, `PRECIOUNITARIO`, `CANTIDAD`, `DESCARGADO`) VALUES (NULL,:IDVENTA,:IDPRODUCTO,:PRECIOUNITARIO,:CANTIDAD, 0)
        $sentencia=$pdo->prepare("SET @last_cod_cupon = (SELECT MAX(`CodCupon`) FROM `cupon`);
INSERT INTO `cupon` (`CodCupon`, `CodPromocion`, `CodUsuario`, `EstadoCupon`, `FechaCanje`)
VALUES (CONCAT('EMP', LPAD(SUBSTRING(@last_cod_cupon, 4) + 1, 7, '0')), :IDPRODUCTO, :CODUSUARIO, 'Disponible', NULL)
ON DUPLICATE KEY UPDATE `CodCupon` = CONCAT('EMP', LPAD(SUBSTRING(@last_cod_cupon, 4) + 2, 7, '0'));");

        //$sentencia->bindParam(":IDVENTA",$idVenta);
        $sentencia->bindParam(":IDPRODUCTO",$producto['ID']);
        $sentencia->bindParam(":CODUSUARIO",$_SESSION['CodUsuario']);
        //$sentencia->bindParam(":CANTIDAD",$producto['CANTIDAD']);
        $sentencia->execute();




    }

//    echo "<h3>" . $total . "</h3>";
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<style>
    @media screen and (max-width: 400px){
        #paypal-button-container {
            width: 100%;
        }
    }

    @media screen and (min-width: 400px){
        #paypal-button-container{
            width: 250px;
            display: inline-block;
        }
    }
</style>

<div class="mt-4 p-5 bg-primary text-white rounded text-center">
    <h1 class="display-4">¡Paso final!</h1>
    <hr class="my-4">
    <p class="lead">Estas a punto de pagar con paypal la cantidad de:
        <h4>$<?php echo number_format($total,2);?></h4>
        <div id="paypal-button-container"></div>
    </p>
    <p>Podrás ver tus productos una vez hallas finalizado el proceso de pago</p>
</div>

<script>
    paypal.Button.render({
        env: 'sandbox',
        style:{
            label: 'checkout',
            size: 'responsive',
            shape: 'pill',
            color: 'gold'
        },

        client:{
            sandbox: 'AX_J_c2eR0Y3uP0SlXwuvod2-IY1UxbF5KLELOx6TXKfSxE-Gv3zHj_7HVMDc0_02K7Nw7_k1o_z8BzS'
        },

        payment: function (data, actions) {
            return actions.payment.create({
                payment: {
                    transactions: [
                        {
                            amount: { total: '<?php echo $total;?>', currency: 'USD'},
                            description: "Compra de productos a la cuponera:$<?php echo number_format($total,2);?>",
                            custom: "<?php echo $SID;?>#<?php echo openssl_encrypt($idVenta, COD, KEY);?>"
                        }
                    ]
                }
            });
        },

        onAuthorize: function (data, actions){
            return actions.payment.execute().then(function (){
                console.log(data);
                Swal.fire(
                    'El pago se realizó de manera exitosa!',
                    'Será redirigido a su perfil',
                    'success'
                ).then(function (){
                    window.location = "perfil.php";
                });
            });
        },
    }, '#paypal-button-container');
</script>




<?php
include 'pie.php';
?>
