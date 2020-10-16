<?php
	include_once '../includes/head.php';
	include_once 'functions.php';
	include_once '../includes/conexion.php';

	session_start();
	if (empty($_SESSION['active'])) {

		header("location: ../paginas");

	}
?>
<div id="header">

	<div class="container">

		<div class="row">

			<div class="col-md-6">
				<h1>Bienvenido al sistema</h1>
			</div>
		
			<div class="col-md-4">
				<div class="optionsBar">
					<p><?php echo fechaC(); ?></p>
					<!--<span>|</span>-->
				</div>
			</div>

			<div class="col-md-2">
				<div class="optionsBar">
					<!--<img class="photouser" src="img/user.png" alt="Usuario">--><i class="fa fa-user"></i>
					<span class="user"> <?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido']; ?> </span>
					<a href="salir.php"><i class="fa fa-power-off"></i></a>
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
	/*session_start();
	if($_SESSION['rol'] != 1)
	{
		header("location: ./");
	}
	include "../conexion.php";
*/
	if(!empty($_POST))
	{
		
		$id_producto = $_POST['idproducto'];

		$query_delete = mysqli_query($conexion,"DELETE FROM productos WHERE id_producto = $id_producto ");
		//$query_delete = mysqli_query($conexion,"UPDATE usuario SET estatus = 0 WHERE idusuario = $idusuario ");
		mysqli_close($conexion);
		if($query_delete){
			header("location: lista_productos.php");
		}else{
			echo "Error al eliminar";
		}
	}
	


	if(empty($_REQUEST['id']))
	{
		header("location: lista_productos.php");
		mysqli_close($conexion);
	}else{

		$id_producto = $_REQUEST['id'];

		$query = mysqli_query($conexion,"SELECT producto
												FROM productos
												WHERE id_producto = $id_producto ");
		mysqli_close($conexion);
		$result = mysqli_num_rows($query);

		if($result > 0){
			while ($data = mysqli_fetch_array($query)) {
				# code...
				$producto = $data['producto'];
			}
		}else{
			header("location: lista_productos.php");
		}
	}


 ?>


<!-- NAVIGATION -->
		<nav id="navigation">
			<!-- container -->
			<div class="container">
				<!-- responsive-nav -->
				<div id="responsive-nav">
					<!-- NAV -->
					<ul class="main-nav nav navbar-nav list-inline">
						<li class="list-inline-item"><a href="index.php">Inicio</a></li>
						<li class="list-inline-item"><a href="lista_productos">
							<i class="fa fa-cubes"></i> Lista de Productos</a></li>
						<li class="list-inline-item"><a href="registro_productos" class="btn_new">
							<i class="fa fa-plus"></i> Registrar Producto</a></li>
						<li class="list-inline-item"> 
							<form action="buscar_producto.php" method="get" class="form_search">
								<input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
								<input type="submit" value="Buscar" class="btn_search">
							</form></li>
					</ul>
					<!-- /NAV -->
				</div>
				<!-- /responsive-nav -->
			</div>
			<!-- /container -->
		</nav>
		<!-- /NAVIGATION -->
<div class="section">
	<div class="container">
		<div class="row">
			<!-- section title -->
			<div class="col-md-12">
				<div class="section-title">
					<h1><i class="fa fa-cubes"></i>Eliminar Productos</h1>
				</div>
			</div>
			<!-- /section title -->
			
			<div class="col-md-12">
				<div class="row">
					<div class="data_delete">
						<h2>¿Está seguro de eliminar el siguiente registro?</h2>
						<p>Nombre: <span><?php echo $producto; ?></span></p>

						<form method="post" action="">
							<input type="hidden" name="idproducto" value="<?php echo $id_producto; ?>">
							<a href="lista_productos.php" class="btn_cancel">Cancelar</a>
							<input type="submit" value="Aceptar" class="btn_ok">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>