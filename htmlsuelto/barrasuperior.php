<div id="mainnav">
		<ul>
			<?php 
			
			// Incluyo además las clases que se van a usar
			include_once("./clases/class.profesores.php"); //clase que recupera datos de profesores
			include_once("./clases/class.cursos.php"); //clase que recupera datos de cursos
			include_once("./clases/class.materias.php"); //clase que recupera datos de materias
			include_once("./clases/class.asignaciones.php"); //clase que recupera datos de materias
			
			$profesorado2 = New profesores(); //variable de la clase profesores
			$curso2 = New misCursos(); // variable de la clase curso
			$materia2 = New misMaterias(); // variable de la clase materia
			$asignacion2 = New misAsignaciones($curso2, $profesorado2, $materia2); // Uso el constructor para pasarle la clase curso, profesorado y materias a Asignaciones
			
			if ($_SESSION["profesor"]>0 && isset($_SESSION["idasignacion"])) {
			      $tutorAsignacion=$asignacion2->devuelveTutorAsignacion($_SESSION["idasignacion"],$_SESSION["profesor"]);
			} else { $tutorAsignacion=0; }
			$_SESSION["tutor"]=$tutorAsignacion;
			
			// Caso en que el permiso sea cero
			if ($_SESSION["permisos"]>=0) { // Si es cero sólo se puede volver a la página de índice
				echo '<li><a href="./index.php">';
				echo '<img src="./imagenes/iconos/home_by_egopin.png" style="width:22px; height: auto; margin-right: 5px;">';
				echo 'Inicio</a></li>';
			}
			// Caso en que el permiso sea uno o dos
			if ($_SESSION["permisos"]>=1) { // Si soy profesor, simplemente
			    echo '<li id="limenuProfesor"><a id="menuProfesor" href="#">';
			    echo '<img src="./imagenes/iconos/teacher_by_leandrosciola.png" style="width:22px; height: auto; margin-right: 5px;">';
			    echo 'Profesor/a</a></li>'; 
			}
			
			
			// Caso en que el permiso sea uno o dos y sea tutor
			if (($_SESSION["permisos"]>=1) && ($_SESSION["tutor"]>=1)) { // Si soy profesor, simplemente
			    echo '<li id="limenuProfesor"><a id="menuTutor" href="#">';
			    echo '<img src="./imagenes/iconos/tutor.png" style="width:22px; height: auto; margin-right: 5px;">';
			    echo 'Tutor/a</a></li>'; 
			}
			
			// Caso en que el permiso sea dos
			if ($_SESSION["permisos"]==2) { // Si soy administrador, simplemente
			    echo '<li id="limenuAdmin"><a id="menuAdmin" href="#">';
			    echo '<img src="./imagenes/iconos/nicubunu-Tools.png" style="width:22px; height: auto; margin-right: 5px;">';
				echo 'Administrador/a</a></li>'; 		
			}
			// Falta en el caso de que se sea tutor...							
			?>
		</ul>
		
		
		<?php // Contenido de la descripcion...
		if ($_SESSION["profesor"]>0 && isset($_SESSION["idasignacion"])) {
		   echo '<div id="descripcion" title="'.$asignacion2->devuelveDescripcionAsignacion($_SESSION["idasignacion"],$_SESSION["profesor"]).'">';
		   echo "Asignación elegida: ".$asignacion2->devuelveDescripcionAsignacion($_SESSION["idasignacion"],$_SESSION["profesor"]);	
		   echo '</div>';
		} else {
		   echo '<div id="descripcion">';
		   echo "Aún no has elegido asignación";
		   echo '</div>';
		}
		?>

</div>


