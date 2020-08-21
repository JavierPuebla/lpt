<!-- <script type="text/javascript"> console.log(<?php //echo json_encode($acts); ?>)</script> -->

<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="cambiar menu">
	<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse " id="navbarNavAltMarkup">
		<div class="navbar-nav ">
			<a class="nav-item nav-link active"><span class="sr-only">(current)</span></a>
			<?php 
			foreach ($acts as $v) {
				$acc = $this->app_model->get_visual_elements($v);
				echo "<a class='nav-item nav-link' href='{$acc['controller']}'>{$acc['nombre']}</a>";
			};
			?>
		</div>
		<!-- <a class="nav-item"> <span class="glyphicon-class"><?php //echo $username ?></span></a> -->
	</div>
	<a class="navbar-brand mr-4" href='#' id="navbar_msg"></a>
	
	<!-- <form class="form-inline">
		<input class="form-control mr-sm-2" type="search" placeholder="Buscar" aria-label="Buscar">
		<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
	</form> -->
</nav>
