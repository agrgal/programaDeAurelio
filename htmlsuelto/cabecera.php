	<div id="header" title="sitename">
		<div id="logo">
			<img src="./imagenes/logoA.png" title="logo 'programa de Aurelio', realizado por Miguel Parra" alt="logo 'programa de Aurelio', realizado por Miguel Parra" width="80%" height="auto">
		</div>
		<div id="iesseritium">
			<a href="./index.php">
			<img src="./imagenes/iesseritium.png" title="logo 'IES Seritium', realizado por Miguel Parra" alt="logo 'programa de Aurelio', realizado por Miguel Parra" width="80%" height="auto">
			</a>
		</div>
		<div id="calendario">
			<h2>
			<?php
		    // echo 'Fecha: '.$calendario->fechaformateadalarga("2015/01/17");
	        echo 'Son las '.$calendario->horactual().'<br/>del '.$calendario->fechaformateadalarga($calendario->fechadehoy());
		    ?>
			</h2>
		</div>
	</div>
