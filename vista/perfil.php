<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>La Cuponera</title>
</head>

<body>

	<?php
	require "menu.php";
	require "../modelo/Usuario/daoUsuario.php";
	//$rel = $_SESSION['relacion'];
	if (!empty($_SESSION)) {
		$daoUsuario = new daoUsuario();
		$InfoUsuario = $daoUsuario->UsuarioYEmpresa($_SESSION["CodUsuario"], $_SESSION["Rol"]);
		$InfoUsuario = $InfoUsuario[0];

	?>
		<div style="padding-bottom: 10px;">
			<h1 class="display-3 text-center">Información del Usuario: </h1>
		</div>
		<section>
			<div class="container ">
				<div class="col-lg-15">
					<div style="background-color: #eee;" class="card mb-4">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-3">
									<p class="mb-0">Nombre del Usuario: </p>
								</div>
								<div class="col-sm-9">
									<p class="text-muted mb-0"><?= $_SESSION['Apellido'] . ", " . $_SESSION['Nombre'] ?></p>
								</div>
							</div>
							<hr>

							<div class="row">
								<div class="col-sm-3">
									<p class="mb-0">Rol del Usuario: </p>
								</div>
								<div class="col-sm-9">
									<?php
									if ($_SESSION['Rol'] == "Empleado") {
										echo '<p class="text-muted mb-0">' . $_SESSION["Rol"] . ' de ' . $InfoUsuario["NombreEmpresa"] . '</p>';
									} else {
										echo '<p class="text-muted mb-0">' . $_SESSION["Rol"] . '</p>';
									}
									?>
								</div>
							</div>
							<hr>

							<div class="row">
								<div class="col-sm-3">
									<p class="mb-0">Telefono del Usuario: </p>
								</div>
								<div class="col-sm-9">
									<p class="text-muted mb-0"><?= $_SESSION['Telefono'] ?></p>
								</div>
							</div>
							<hr>

							<div class="row">
								<div class="col-sm-3">
									<p class="mb-0">DUI del Usuario: </p>
								</div>
								<div class="col-sm-9">
									<p class="text-muted mb-0"><?= $InfoUsuario['DUI'] ?></p>
								</div>
							</div>
							<hr>

							<div class="row">
								<div class="col-sm-3">
									<p class="mb-0">Dirección del Usuario: </p>
								</div>
								<div class="col-sm-9">
									<p class="text-muted mb-0"><?= $InfoUsuario['Direccion'] ?></p>
								</div>
							</div>
							<hr>

							<div class="row">
								<div class="col-sm-3">
									<p class="mb-0">Correo del Usuario: </p>
								</div>
								<div class="col-sm-9">
									<p class="text-muted mb-0"><?= $_SESSION['Correo'] ?></p>
								</div>
							</div>
							<hr>

							<div class="row">
								<div class="col-sm-3">
									<p class="mb-0">Contraseña del Usuario: </p>
								</div>
								<div class="col-sm-5">
									<p class="text-muted mb-0">********** </p>
								</div>
								<div class="col-sm-3">
									<a href="#"><button class="btn btn-warning mb-0"> Cambiar Contraseña </button></a>
								</div>
							</div>
							<hr>

							<div class="row">
								<div class="col-sm-3">
									<p class="mb-0">Usuario Verificado: </p>
								</div>
								<div class="col-sm-9">
									<p class="text-muted mb-0"><?= $_SESSION['Verificado'] ?></p>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
		//En caso de que el usuario sea tipo cliente
		if ($_SESSION["Rol"] == "Cliente") {
			require '../modelo/Cupones/daoCupones.php';
			$daoCupon = new daoCupones();
			$listaCuponesUser = $daoCupon->mostrarCuponesClientes($_SESSION['CodUsuario']);
		?>
			<div style="padding-bottom: 10px;">
				<h1 class="display-3 text-center">Tus cupones: </h1>
			</div>
			<section>
				<div class="container ">
					<div class="col-lg-15">
						<div style="background-color: #add8e6;" class="card mb-4">
							<div class="card-body">
								<?php
								foreach ($listaCuponesUser as $CuponesUser) {
									echo ' 
										<div class="row">
											
											<div class="col-sm-2">
												<p class="text-active mb-0"><b>' . $CuponesUser["CodCupon"] . '</b></p>
											</div>
											<div class="col-sm-2">
												<p class="mb-0 ">Cupón ' . $CuponesUser["EstadoCupon"] . ' </p>
											</div>
											<div class="col-sm-2">
												<p class="text-active mb-0">' . date("d/m/Y", strtotime($CuponesUser['FechaVencimiento'])) . '</p>
											</div>
											<div class="col-sm-4 overflow-hidden">
												<p class="text-active mb-0">' . $CuponesUser["Titulo"] . '</p>
											</div>
											<div class="col-sm-2">
											<button type="button" class="btn btn-primary" data-bs-toggle="modal" 
												data-bs-target="#Cupon' . $CuponesUser["CodCupon"] . '">		Más Detalles	</button>
											</div>
										</div>
										';
									//Modales de cada Cupon

									echo '
										<div class="modal  fade" id="Cupon' . $CuponesUser["CodCupon"] . '" tabindex="-1" aria-labelledby="Cupon' . $CuponesUser["CodCupon"] . '" aria-hidden="true">
											<div class="modal-dialog modal-lg">
												<div class="modal-content">
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLabel">Codigo: ' . $CuponesUser["CodCupon"] . '</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<div class="modal-body">
													<b>Oferta a la que pertenece:</b> <p> ' . $CuponesUser["Titulo"] . ' </p>
													<b>Descripción de la oferta:</b> <p> ' . $CuponesUser["Descripcion"] . ' </p>
													<b>Valor de la Oferta:</b> <p> Antes a $' . number_format($CuponesUser["PrecioRegular"], 2) . ' y ahora a 
													$' . number_format($CuponesUser["PrecioOferta_Cupon"], 2) . ' </p>
													<b>Fecha Limite de uso del Cupón:</b> <p> ' . date("d/m/Y", strtotime($CuponesUser['FechaVencimiento'])) . ' </p>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Okey</button>
													</div>
												</div>
											</div>
										</div>
										';

									if ($CuponesUser !== end($listaCuponesUser)) {
										echo "<hr>";
									}
								}
								?>

							</div>
						</div>
					</div>
				</div>
			</section>



		<?php
		}
	} else {
		?>


		<div class="opciones">
			<h2>Para acceder a esta página</h2>
			<h2>Debes Iniciar Sesión <a href="login.php">Iniciar Sesión</a></h2>
		</div>
	<?php
	}
	require 'footer.php';
	?>
</body>

</html>