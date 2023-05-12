<?php
require '../modelo/Promocion/daoPromocion.php';
require '../modelo/Cupones/daoCupones.php';
require '../modelo/Empresa/empresa.class.php';
require '../controlador/constantes.php';
include 'carrito.php';
include 'cabecera.php';
?>
<?php
$id = isset($_GET['promo'])?$_GET['promo']:"1";
$daoPromocion = new daoPromocion();
$promo = $daoPromocion->buscarPromo($id);
?>
<br>
<h3>Lista del carrito</h3>
<?php if(!empty($_SESSION['CARRITO'])){  ?>
<table class="table table-light table-bordered">
    <tbody>
    <tr>
        <th width="40%">Titulo</th>
        <th width="15%" class="text-center">Cantidad</th>
        <th width="20%" class="text-center">Precio</th>
        <th width="20%" class="text-center">Total</th>
        <th width="5%">--</th>
    </tr>
    <?php $total=0; ?>

    <?php foreach ($_SESSION['CARRITO'] as $indice=>$producto){ ?>
    <tr>
        <td width="40%"><?php echo $producto['NOMBRE'] ?></td>
        <td width="15%" class="text-center"><?php echo $producto['CANTIDAD'] ?></td>
        <td width="20%" class="text-center"><?php echo $producto['PRECIO'] ?></td>
        <td width="20%" class="text-center"><?php echo number_format($producto['PRECIO']*$producto['CANTIDAD'],2); ?></td>
        <td width="5%">

            <form action="" method="post">
                <input type="hidden" name="id" id="id" value="<?php echo openssl_encrypt($producto['ID'],COD,KEY); ?>">

                <button
                        class="btn btn-danger"
                        type="submit"
                        name="btnAccion"
                        value="Eliminar"
                >Eliminar</button>
            </form>
        </td>
    </tr>
        <?php $total=$total+($producto['PRECIO']*$producto['CANTIDAD']); ?>
    <?php } ?>
    <tr>
        <td colspan="3" align="right">
            <h3>Total</h3>
        </td>
        <td>
            <h3>$<?php echo number_format($total,2); ?></h3>
        </td>
        <td></td>
    </tr>

    <tr>
        <td colspan="5">
            <form action="pagar.php" method="post">
                <div class="alert alert-success">
                    <div class="form-group">
                        <label for="my-input">Correo de contacto</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?=$_SESSION['Correo']?>" readonly placeholder="<?=$_SESSION['Correo']?>">
                    </div>
                    <small id="emailHelp"
                    class="form-text text-muted">
                        Los productos serán enviados a este correo
                    </small>
                </div>
                <button class="btn btn-primary btn-lg btn-block" type="submit" value="proceder" name="btnAccion">
                    Proceder a pagar
                </button>
            </form>
        </td>
    </tr>



    </tbody>
</table>
<?php }else{  ?>
    <div class="alert alert-success">
        No hay productos en el carrito
    </div>
<?php }?>
<?php
include 'pie.php';
?>
