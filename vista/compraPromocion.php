<?php
require '../modelo/Promocion/daoPromocion.php';
require '../modelo/Cupones/daoCupones.php';
require '../modelo/Empresa/empresa.class.php';
require '../controlador/constantes.php';
include 'carrito.php';
include 'cabecera.php';
?>
<br>
    <?php if($mensaje !="") {  ?>
    <div class="alert alert-success" >

        <a href="mostrarCarrito.php" class="link-success">Ver Carrito</a>
    </div>
<?php }?>

    <?php
        $id = isset($_GET['promo'])?$_GET['promo']:"1";
            $daoPromocion = new daoPromocion();
        $promo = $daoPromocion->buscarPromo($id);
    ?>
    <?php
    var_dump($promo[0]);
    ?>
    <div class="row">
        <div class="col-3">
            <div class="card">
                <img
                        title = "<?=$promo[0]['Titulo']?>"
                        src="img/<?=$promo[0]['FotoPromocion']?>"
                        alt="<?=$promo[0]['FotoPromocion']?>"
                        class="card-img-top"
                        data-toggle="popover"
                        data-trigger="hover"
                        data-content="<?=$promo[0]['Detalle']?>"

                >
                <div class="card-body">
                    <span></span>
                    <h5 class="card-title"><?=$promo[0]['Titulo']?></h5>
                    <p class="card-text"><?=$promo[0]['Descripcion']?></p>
                    <h5 class="card-title">$<?=$promo[0]['PrecioOferta_Cupon']?></h5>

                    <form action="" method="post">

                        <input type="hidden" name="id" id="id" value="<?php echo openssl_encrypt($promo[0]['CodPromocion'],COD,KEY); ?>">
                        <input type="hidden" name="nombre" id="nombre" value="<?php echo openssl_encrypt($promo[0]['Titulo'],COD,KEY);?>">
                        <input type="hidden" name="precio" id="precio" value="<?php echo openssl_encrypt($promo[0]['PrecioOferta_Cupon'],COD,KEY);?>">
                        <input type="hidden" name="cantidad" id="cantidad" value="<?php echo openssl_encrypt(1,COD,KEY); ?>">

                        <button class="btn btn-primary"
                                name="btnAccion"
                                value="AgregarCarrito"
                                type="submit">
                            Agregar al carrito
                        </button>


                    </form>

                </div>
            </div>
        </div>
    </div>

</div>
<script>
    $(function () {
        $('[data-toggle="popover"]').popover()
    })
</script>

<?php
include 'pie.php';
?>
