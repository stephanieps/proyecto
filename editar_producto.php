<?php
	include_once '../includes/head.php';
	include_once 'functions.php';
	include_once '../includes/conexion.php';
	
	session_start();
	if (empty($_SESSION['active'])) {

		header("location: ../paginas/login.php");

	}

	//Mostrar Datos VALIDAR PRODUCTO
	if(empty($_REQUEST['id'])) {

		header('Location: lista_productos.php');

	}else{
		
		$id_producto = $_REQUEST['id'];

		if (!is_numeric($id_producto)) {

			header('Location: lista_productos.php');

		}
		
		$query_producto = mysqli_query($conexion, "SELECT p.id_producto, p.producto, p.precio, p.modelo, p.descripcion,
															p.imagen, m.id_marca, m.marca, c.id_categoria, c.categoria, 
															s.id_subcategoria, s.subcategoria FROM productos p
												LEFT JOIN marcas m ON p.id_marca = m.id_marca
												LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
												LEFT JOIN subcategoria s ON p.id_subcategoria = s.id_subcategoria 
												WHERE p.id_producto = $id_producto");

		$result_producto = mysqli_num_rows($query_producto);

			if ($result_producto > 0) {

				$data_producto = mysqli_fetch_assoc($query_producto);

				//print_r($data_producto);
				# code...
			}else{

				header('Location: lista_productos.php');
			}
	}

	if (!empty($_POST['btn_save_updates'])) {

		$producto = $_POST['producto'];
		$precio = $_POST['precio'];
		$modelo = $_POST['modelo'];
		$descripcion = $_POST['descripcion'];
		$marca = $_POST['id_marca'];
		$categoria = $_POST['id_categoria'];
		$subcategoria = $_POST['id_subcategoria'];

		$foto = $_FILES['imagen'];
		$nombre_foto = $foto['name'];
		$type = $foto['type'];
		$url_temp = $foto['tmp_name'];

			if ($nombre_foto) {

				$destino = '../img/';
				$img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));
				$imgProducto = $img_nombre.'.jpg';
				$src = $destino.$imgProducto;

			}else{

				$imgProducto = $data_producto['imagen'];

			}

			$query_update = mysqli_query($conexion, "UPDATE productos
													SET producto = '$producto',
													precio = $precio,
													modelo = '$modelo', 
													descripcion = '$descripcion', 
													imagen = '$imgProducto',
													id_categoria = $categoria, 
													id_subcategoria = $subcategoria, 
													id_marca = $marca
													WHERE id_producto = $id_producto");
				if ($query_update)
				{

					if ($nombre_foto) {
						# code...
						unlink('../img/'.$data_producto['imagen']);
						move_uploaded_file ($url_temp, $src);
						
					}
					?>
                	<script>
                			alert('Producto actualizado correctamente.');
                			window.location.href='lista_productos.php';
                	</script>
                	<?php
					//$alert = '<p class="msg_save">Producto actualizado correctamente.</p>';

				}else{

					$alert = '<p class="msg_error">Error al actualizar producto.</p>';

				}
	}

?>

		<!-- BREADCRUMB -->
		<div id="header" class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<div class="col-md-6">
						<h1>Bienvenido al sistema</h1>
					</div>
		
					<div class="col-md-3">
						<div class="optionsBar">
							<p><?php echo fechaC(); ?></p>
							<!--<span>|</span>-->
						</div>
					</div>

					<div class="col-md-3">
						<div class="optionsBar">
							<!--<img class="photouser" src="img/user.png" alt="Usuario">--><i class="fa fa-user"></i>
							<span class="user"> <?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido']; ?> </span>
							<a href="salir.php"><i class="fa fa-power-off"></i></a>
						</div>
					</div>
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /BREADCRUMB -->

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

		<!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					
		
					<div class="form_register">
			

						<div class="col-12 order-details">
							<div class="section-title">
								<h3 class="title"><i class="fa fa-cube"></i>Editar Producto</h3>
							</div><hr>
							<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
							<!--<input type="hidden" name="id" value="<?php echo $data_producto['id_producto']; ?>">
							<input type="hidden" id="imagen_actual" name="imagen_actual" value="<?php echo $data_producto['imagen']; ?>">
							<input type="hidden" id="imagen_remove" name="imagen_remove" value="<?php echo $data_producto['imagen']; ?>">-->
							<form action="" method="post" enctype="multipart/form-data">

								<div class="form-group">
									<label for="producto">Nombre del Producto</label>
									<input class="input" type="text" name="producto" id="producto" placeholder="Nombre del Producto" value="<?php echo $data_producto['producto']; ?>">
								</div>
								<div class="form-group">
									<label for="precio">Precio</label>
									<input class="input" type="text" name="precio" id="precio" placeholder="Precio" value="<?php echo $data_producto['precio']; ?>">
								</div>
								<div class="form-group">
									<label for="modelo">Modelo</label>
									<input class="input" type="text" name="modelo" id="modelo" placeholder="Modelo" value="<?php echo $data_producto['modelo']; ?>">
								</div>
								<div class="form-group">
										<label for="descripcion">Descripcion</label><br>
										<textarea class="input" name="descripcion" id="descripcion" placeholder="Descripcion" value="<?php echo $data_producto['descripcion']; ?>"
											style="margin: 0px; width: 580px; height: 162px;"></textarea>
								</div>
								<div class="form-group">
									<!--<div class="photo">
										<label for="imagen">Foto</label>
									        <div class="prevPhoto">
									        	<span class="delPhoto notBlock">X</span>
									        	<label for="imagen"></label>
									        </div>
									        <div class="upimg">
									        	<input type="file" name="imagen" id="imagen">
									        </div>
									        <div id="form_alert"></div>
									</div>-->
										
										<label for="imagen" style="margin-right: 113px;">Foto</label>
				        				<input type="file" name="imagen" id="imagen" style="width:73%" accept="image/png, .jpeg, .jpg, image/gif" >
				        				<!--<div id="form_alert"><?php echo $foto; ?></div>
										<input class="input" type="text" name="foto" id="foto" placeholder="foto">-->
								</div>
								<div class="form-group">
									<label for="marca" style="margin-right: 100px;">Marca</label>
										<?php 
											$query_marca = mysqli_query($conexion,"SELECT * FROM marcas");
											
											$result_marca = mysqli_num_rows($query_marca);
								 		?>
								 	<select class="input-select notItemOne"  name="id_marca" id="id_marca">
								 		<option value="<?php echo $data_producto['id_marca']; ?>" selected><?php echo $data_producto['marca']; ?></option>
										<?php 
											if($result_marca > 0)
											{
												while ($marca = mysqli_fetch_array($query_marca)) {
										echo '<option value="'.$marca["id_marca"].'">'.$marca["marca"].'</option>';
													# code...
												}
												
											}
										 ?>
									</select>
								</div>
								
								<div class="form-group">
									<label for="categoria" style="margin-right: 72px;">Categoria</label>
										<?php 
											$query_categoria = mysqli_query($conexion,"SELECT * FROM categoria");
											
											$result_categoria = mysqli_num_rows($query_categoria);
								 		?>
								 	<select class="input-select notItemOne" id="id_categoria" name="id_categoria">
								 		<option value="<?php echo $data_producto['id_categoria']; ?>" selected><?php echo $data_producto['categoria']; ?></option>
										<?php 
											if($result_categoria > 0)
											{
												while ($categoria = mysqli_fetch_array($query_categoria)) {
										echo '<option value="'.$categoria["id_categoria"].'">'.$categoria["categoria"].'</option>';
													# code...
												}
												
											}
										 ?>
									</select>
								</div>
								<div class="form-group">
									<label for="subcategoria" style="margin-right: 42px;">Subcategoria</label>
										<?php 
											$query_subcategoria = mysqli_query($conexion,"SELECT id_subcategoria, subcategoria FROM subcategoria");
											mysqli_close($conexion);
											$result_subcategoria = mysqli_num_rows($query_subcategoria);
								 		?>
								 	<select class="input-select notItemOne" id="id_subcategoria" name="id_subcategoria">
								 		<option value="<?php echo $data_producto["id_subcategoria"]; ?>"><?php echo $data_producto["subcategoria"]; ?></option>
										<?php 
											if($result_subcategoria > 0)
											{
												while ($subcategoria = mysqli_fetch_array($query_subcategoria)) {
										echo '<option value="'.$subcategoria["id_subcategoria"].'">'.$subcategoria["subcategoria"].'</option>';
													# code...
												}
												
											}
										 ?>
									</select>
								</div>
								<!--<button type="submit" class="btn_save" name="btn_save_updates"><i class="fa fa-save"></i> Actualizar</button>-->
								<!--<input type="submit" value="Crear usuario" class="btn_save">-->
								<input type="submit" value="Actualizar" class="btn_save" name="btn_save_updates">
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
