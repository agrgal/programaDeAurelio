<?php
/*
 * misAsignaciones.php
 * 
 * Copyright 2015 root <root@aurelio-desktop>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

class misAsignaciones
{

    private $clasecurso;
    private $claseprofesor;
    private $clasealumno;
    private $clasemateria; // variables de clase que "paso" a variables privadas dentro de la clase asignacion
    var $estaAsignacion;
    var $listaDeAsignaciones;
    
    public function __construct($classcurso, $classprofesor, $classmateria, $classalumno)
    {
        $this->clasecurso = $classcurso;
        $this->claseprofesor = $classprofesor;
        $this->clasemateria = $classmateria;
        $this->clasealumno = $classalumno;
    }

		
	// *******************************************************
	// 1) Funcion que retorna la descripcion de una asignaci�n, dado id y profesor
	public function devuelveDescripcionAsignacion ($id, $profesor) {
		$this->listarAsignaciones($profesor); // llama al procedimiento
        $clave=array_search($id,$this->listaDeAsignaciones['idasignacion']);
        return $this->listaDeAsignaciones['descripcion'][$clave];
	}	
	// **********************************************************
	
	// *******************************************************
	// 2) Funcion que retorna las propiedades de una asignacion y las guarda en listaDeAsignaciones
	public function listarAsignaciones ($profesor) {
        $link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $Sql='SELECT * FROM tb_asignaciones WHERE profesor="%s" ORDER BY idasignacion';
	    $ii=0; // contador 
	    $Sql = sprintf($Sql, mysqli_real_escape_string($link,$profesor)); // Seguridad que evita los ataques SQL Injection  	
        $result=mysqli_query($link,$Sql);// ejecuta la cadena sql y almacena el resultado el $result
	    while ($row=mysqli_fetch_array($result)) {
			$this->listaDeAsignaciones['idasignacion'][$ii]=$row['idasignacion']; 
			$this->listaDeAsignaciones['profesor'][$ii]=$profesor;
			// llama a la clase claseprofesor para recuperar el nombre de empleado
			$this->claseprofesor->idprofesor=$profesor; // en esa clase, establezco la id del profesor
			$this->claseprofesor->nombreEmpleado(); // Llamo a la funci�n nombre de Empleado
			$this->listaDeAsignaciones['nombreProfesor'][$ii]=cambiarnombre($this->claseprofesor->Empleado);
			// llama a la clase clasemateria para recuperar el nombre y la abreviatura de la materia
			$this->listaDeAsignaciones['materia'][$ii]=$row['materia']; 	
			$this->clasemateria->devuelveMateria($row['materia']); // llama al procedimiento que establece la materia
			$this->listaDeAsignaciones['nombreMateria'][$ii]= strtoupper($this->clasemateria->estaMateria["materia"]);
			$this->listaDeAsignaciones['abreviaturaMateria'][$ii]= strtoupper($this->clasemateria->estaMateria["abr"]);
			// llama a la clase clasecurso para recuperar los datos sobre los cursos de la asignacion
			$this->listaDeAsignaciones['datos'][$ii]=$row['datos'];	
			$alumnado = $this->clasecurso->devuelveAsignacionLarga($row['datos']); // lamada a unaclase dentro de otra clase...
			$this->listaDeAsignaciones['alumnado'][$ii]= $alumnado;
			$this->listaDeAsignaciones['cursosAfectados'][$ii]=str_replace("#",", ",$this->clasecurso->devuelveCursosdeAsignacion($alumnado,0)); // lamada a una clase dentro de otra clase...			
			$this->listaDeAsignaciones['descripcion'][$ii]=$row['descripcion']; 	
			$this->listaDeAsignaciones['tutorada'][$ii]=$row['tutorada'];	
			// Ahora construyo un div. Su estilo en estiloCajas
			if ($row['tutorada']=="1") {
				$tutor='<div class="divasignaciontutor" title="Soy Tutor/a de este grupo" id="'.$row['tutorada'].'"><img src="./imagenes/iconos/tutor.png"></div>';
			} else { $tutor=""; }
			$div= 
			'<div class="divasignacion" id="'.$row['idasignacion'].'" descripcion="'.$row['descripcion'].'" corto="'.$row['datos'].'" alumnado="'.$alumnado.'">			        
			        <div class="divasignacionprofesor">
			           <p>'.$this->listaDeAsignaciones['nombreProfesor'][$ii].'</p>
			        </div>
			        <div class="divasignacionmateria" id="'.$row['materia'].'">
			           <p>'.$this->listaDeAsignaciones['nombreMateria'][$ii].'</p>
			        </div>
			        <div class="divasignacioncursos">
			           <p>'.$this->listaDeAsignaciones['cursosAfectados'][$ii].'</p>
			        </div>
			        <div class="divasignacionbotones">			        
			        <img class="editar" title="Pulsa para cambiar los alumnos de este grupo" id="'.$row['idasignacion'].'" src="./imagenes/iconos/edit.png">
			        <img class="borrar" title="Pulsa para borrar la asignaci�n" id="'.$row['idasignacion'].'" src="./imagenes/iconos/papelera.png"></br>
			        <button class="divasignacionbutton" id="'.$row['idasignacion'].'">Elegir</button>
			        </div>'.$tutor.'
			</div>';
			$this->listaDeAsignaciones['div'][$ii]=$div;
			// Fin del div				
			$ii++;		
		}
		if (!is_null($this->listaDeAsignaciones)) { // si no lo recupera, el valor por defecto)
		    return $this->listaDeAsignaciones; //envia el valor dado
		    } else {
		    return NULL;
	        }
	   mysqli_free_result($result); 
	   mysqli_close($link);
	}
	
	// *******************************************************
	// 3) Funcion que retorna si es tutor o no
	public function devuelveTutorAsignacion ($id, $profesor) {
		$this->listarAsignaciones($profesor); // llama al procedimiento
        $clave=array_search($id,$this->listaDeAsignaciones['idasignacion']);
        return $this->listaDeAsignaciones['tutorada'][$clave];
	}	
	// **********************************************************
	
	// 4) Funci�n que retorna el listado de alumnos de una asignaci�n
	public function devuelveListadoAlumnosdeEstaAsignacion ($id, $profesor) {
		$this->listarAsignaciones($profesor); // llama al procedimiento
		$clave=array_search($id,$this->listaDeAsignaciones['idasignacion']);
		return $this->listaDeAsignaciones['alumnado'][$clave];
	}
	// **********************************************************
	

	// 5a) Funci�n que, dada un alumno , retorna un listado de asignaciones a las que pertenece dicho alumno.
	// Tiene que retornar una retah�la id1#id2#id3#.... de �ndices de ASIGNACIONES.
	public function devuelveAsignacionesDondeEstaUnAlumno ($idal) {
		// 1) Curso donde est� el alumno. Nombre corto "1ESOE"
		$this->clasealumno->devuelveAlumno($idal); // llama a los datos de este alumno. Los recupera en la variable esteAlumno
		$cursoalumno = $this->clasecurso->devuelveCursoCortoPorUnidad($this->clasealumno->esteAlumno["unidad"]);
		   // Le paso la unidad donde est� el alumno y me devuelve el curso corto.
		// 2) Tengo que encontrar las asignaciones en las que en "datos" encuentre el curso "1ESOE" o la id
		$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
		// Reestructuro SQL el 17-Julio...
	    $Sql='SELECT idasignacion FROM tb_asignaciones ';
	    $Sql.="WHERE datos LIKE '".$idal; // Si datos es del tipo 14, un s�lo n�mero
	    $Sql.="' OR datos LIKE '%#".$idal; // Si encuentra la cadena #14
	    $Sql.="#%' OR datos LIKE '%#".$idal; // Si encuentra la cadena #14#
	    $Sql.="' OR datos LIKE '".$idal; // Si encuentra la cadena 14#
	    $Sql.="#%' OR datos LIKE '%".$cursoalumno."%' "; // Si encuentra el curso
	    $Sql.='ORDER BY idasignacion';
	    $ii=0; // contador 
	    // NO PUEDO USARLO... $Sql = sprintf($Sql, mysqli_real_escape_string($link,"idasignacion")); // Seguridad que evita los ataques SQL Injection  	
        $result=mysqli_query($link,$Sql);// ejecuta la cadena sql y almacena el resultado el $result
	    $cadena = "";
	    while ($row=mysqli_fetch_array($result)) {
			$cadena.=$row["idasignacion"]."#";
		} // fin del while
		if (!is_null($cadena)) { // si no lo recupera, el valor por defecto)
		    return substr($cadena, 0, -1); //envia el valor dado
		    // return $Sql;
		    } else {
		    return NULL;
	        }
	   mysqli_free_result($result); 
	   mysqli_close($link); 
	}
	
	// ********************************************************** 	
	// 5b) Funci�n que, dado un id y el valor del profesor, retorna las asignaciones de esa tutor�a
	public function devuelveAsignacionesDeUnaTutoria($id, $profesor) {
		// A) Recibe la asignaci�n. Recupera sus alumnos.
		$alumnadoTutoria = $this->devuelveListadoAlumnosdeEstaAsignacion ($id, $profesor);
		$alumnadoTutoriaArray = explode("#",$alumnadoTutoria);
		// B) Por cada uno de los alumnos, haz una cadena con las asignaciones
		$cadena="";
		foreach ($alumnadoTutoriaArray as $idal) {
			$cadena.=$this->devuelveAsignacionesDondeEstaUnAlumno($idal)."#";
		}
		$cadena = substr($cadena, 0, -1);
		// C) Split de esa cadena y meterlo en un array.
		$asignacionesDeEstaTutoriaArray=explode("#",$cadena);
		$asignacionesDeEstaTutoriaArray = array_filter($asignacionesDeEstaTutoriaArray, function($var){return !is_null($var);} ); // Quitar los nulos
		$asignacionesDeEstaTutoriaArray = array_filter($asignacionesDeEstaTutoriaArray, function($var){return !empty($var);} ); // Quitar los vacios
		// D) quitar repetidos y ordenar
		$asignacionesDeEstaTutoriaArray = array_unique($asignacionesDeEstaTutoriaArray);
		asort($asignacionesDeEstaTutoriaArray);
		// E) Mostrar (Normalmente comentado)
		/* $cadena2 = "";
		foreach ($asignacionesDeEstaTutoriaArray as $id) {
			$cadena2.=$id."-";
		} 
		return $cadena2; */
		// F) retorna el array
		return $asignacionesDeEstaTutoriaArray;
	}
	// ********************************************************** 
	
	// 6) Dada una asignaci�n, retorna su descripci�n
	public function asignacionDescripcion($idasignacion) {
		$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $Sql='SELECT descripcion FROM tb_asignaciones WHERE idasignacion="%s"';
	    $Sql = sprintf($Sql, mysqli_real_escape_string($link,$idasignacion)); // Seguridad que evita los ataques SQL Injection  	
        $result=mysqli_query($link,$Sql);// ejecuta la cadena sql y almacena el resultado el $result
	    while ($row=mysqli_fetch_array($result)) {
			$descripcion = $row["descripcion"];
		}
		if (!is_null($descripcion)) { // si no lo recupera, el valor por defecto)
		    return $descripcion; //envia el valor dado
		    } else {
		    return NULL;
	        }
	   mysqli_free_result($result); 
	   mysqli_close($link);
	}
	// ********************************************************** 
	
	// 7) Dada una asignaci�n, retorna el nombre de su profesor
	// Cual -->0 el idprofesor, Si Cual -> 1, el nombre
	public function asignacionProfesor($idasignacion, $cual) {
		$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $Sql='SELECT profesor FROM tb_asignaciones WHERE idasignacion="%s"';
	    $Sql = sprintf($Sql, mysqli_real_escape_string($link,$idasignacion)); // Seguridad que evita los ataques SQL Injection  	
        $result=mysqli_query($link,$Sql);// ejecuta la cadena sql y almacena el resultado el $result
	    while ($row=mysqli_fetch_array($result)) {
			$profesor = $row["profesor"];
		}
		mysqli_free_result($result); 
	    mysqli_close($link); // Cierro el enlace...	
	    
	    $this->claseprofesor->idprofesor=$profesor; // en esa clase, establezco la id del profesor
		$this->claseprofesor->nombreEmpleado(); // Llamo a la funci�n nombre de Empleado
		$nombreProfesor=$this->claseprofesor->Empleado;    

		if (!is_null($nombreProfesor)) { // si no lo recupera, el valor por defecto)
		    if ($cual>=1) { return cambiarnombre($nombreProfesor); }
		    if ($cual==0) { return $profesor; }
		     //envia el valor dado
		    } else {
		    return NULL;
	        } 
	    // return $Sql;
	}
	// ********************************************************** 
	
	// 8) Dada una asignaci�n, retorna el nombre de la materia
	// Cual -->0 el idmateria, Si Cual -> 1, el nombre
	public function asignacionMateria($idasignacion, $cual) {
		$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
	    $Sql='SELECT materia FROM tb_asignaciones WHERE idasignacion="%s"';
	    $Sql = sprintf($Sql, mysqli_real_escape_string($link,$idasignacion)); // Seguridad que evita los ataques SQL Injection  	
        $result=mysqli_query($link,$Sql);// ejecuta la cadena sql y almacena el resultado el $result
	    while ($row=mysqli_fetch_array($result)) {
			$materia = $row["materia"];
		}		
		// llama a la clase claseprofesor para recuperar el nombre de empleado
		$this->clasemateria->devuelveMateria($materia); // en esa clase, establezco la id de la materia
		$nombreMateria = $this->clasemateria->estaMateria["materia"];
		if (!is_null($nombreMateria)) { // si no lo recupera, el valor por defecto)
		    if ($cual>=1) { return $nombreMateria; }
		    if ($cual==0) { return $materia; }
		     //envia el valor dado
		    } else {
		    return NULL;
	        }
	   mysqli_free_result($result); 
	   mysqli_close($link);
	}
	// ********************************************************** 
	
	// 9) Dada una asignaci�n, devuelve un div con informaci�n de la asignacion para los email
	public function asignacionDIV($id) {
		$nombreProfesor = $this->asignacionProfesor($id,1);
		$claveProfesor = $this->asignacionProfesor($id,0);
		$this->claseprofesor->idprofesor=$claveProfesor;
		$email = $this->claseprofesor->profesorEmail();
		$cadena='<div id="'.$claveProfesor.'" class="divasignacionEmail" email="'.$email.'" title="'.$nombreProfesor.'">'
		.$nombreProfesor.'</div>';
		return $cadena;
		// return $email;
	} // ************************************************************************
	
	// 10) Dada una asignaci�n, devuelve un div con informaci�n del mismo
	public function asignacionDIV2($id) {
		$nombreProfesor = $this->asignacionProfesor($id,1);
		$claveProfesor = $this->asignacionProfesor($id,0);
		$nombreMateria = $this->asignacionMateria($id,1);		
		$claveMateria = $this->asignacionMateria($id,0);		
		// $clases = $this->clasecurso->devuelveCursosdeAsignacion($this->clasecurso->devuelveAsignacionLarga($id),0);
		$clases = $this->clasecurso->devuelveCursosdeAsignacion($this->clasecurso->devuelveAlumnosAsignacion($id),0);
		$cadena='<div id="'.$id.'" class="divasignacionCambio" materia="'.$claveMateria.'" profesor="'.$claveProfesor.'" title="'.$nombreProfesor.' ('.$nombreMateria.')">'.
		'<h3>'.$nombreProfesor.'</h3>'.
		'<h5>'.$nombreMateria.' ('.str_replace("#",",",$clases).') </h5>'.		
		'</div>';
		return $cadena;
	} // ************************************************************************
	
	// 11) Dada una asignaci�n, reconstruye y actualiza la descripci�n del mismo
	public function reconstruyeDescripcion($id) {
		$nombreProfesor = $this->asignacionProfesor($id,1);
		$claveProfesor = $this->asignacionProfesor($id,0);
		$nombreMateria = $this->asignacionMateria($id,1);		
		$claveMateria = $this->asignacionMateria($id,0);		
		// $clases = $this->clasecurso->devuelveCursosdeAsignacion($this->clasecurso->devuelveAsignacionLarga($id),0);
		$clases = $this->clasecurso->devuelveCursosdeAsignacion($this->clasecurso->devuelveAlumnosAsignacion($id),0);
		$cursosen = str_replace("#",", ",$clases);
		$descripcion = strtoupper("Asig: ".$nombreMateria.", Prof: ".$nombreProfesor.", Cursos:  ".$cursosen);  
		$Sql=""; // Como ejecuta varios Sql, al principio debe ser nulo.
		$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
		$Sql='UPDATE tb_asignaciones SET descripcion ="'.$descripcion.'"';
		$Sql.=' WHERE idasignacion = "'.$id.'"';
		mysqli_query($link, $Sql); // ejecuto el Sql
		mysqli_close($link); // Cierro el enlace...			
	} // ************************************************************************
	
	// ***********************************************************
	// 12) dada una asignacion, retira las opiniones de alumnado que no pertenece a dicha asignacion
	// Es posible si he estado cambiando alumnos de grupo
	public function retiraHuerfanos($id) {
		// ****************************************************************
		// 1�) Obtiene los alumnos de esa asignacion
		// ****************************************************************
		$profesor = $this->asignacionProfesor($id, 0);
		$alumnado = $this->devuelveListadoAlumnosdeEstaAsignacion($id,$profesor);
		$alumnadoArray = explode("#",$alumnado);
		// ****************************************************************
		// 2�) Obtiene el alumnado que presenta opiniones de esa asignaci�n
		// ****************************************************************
		$alumnadoConOpiniones=array();
		$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
		$asignacion = mysqli_real_escape_string($link,$asignacion);
	    $Sql ='SELECT DISTINCT alumno FROM tb_opiniones WHERE asignacion="'.$id.'" ORDER BY alumno ASC';
	    // $Sql = sprintf($Sql, mysqli_real_escape_string($link,$fecha)); // Seguridad que evita los ataques SQL Injection  	
	    $result=mysqli_query($link,$Sql); // ejecuta la cadena sql y almacena el resultado el $result
		 while ($row=mysqli_fetch_array($result)) {
			$alumnadoConOpiniones[]=$row["alumno"];
		 } // fin del while
	    mysqli_free_result($result); 
	    mysqli_close($link);
	    // $alcoristra=implode("*",$alumnadoConOpiniones);
	    // ***************************************************************************************
		// 3�) Obtiene el alumnado que presenta opiniones de esa asignaci�n Y NO PERTENECEN A ELLA
		// ***************************************************************************************
		$noestan = array_diff($alumnadoConOpiniones,$alumnadoArray); // Diferencia
	    // ***************************************************************************************
		// 4�) Borra opiniones de esesos alumnos
		// ***************************************************************************************
		foreach ($noestan as $valor) {		
			$link=Conectarse(); // y me conecto. //dependiendo del tipo recupero uno u otro.
			$Sql="DELETE FROM tb_opiniones WHERE alumno='%s'";
			$Sql = sprintf($Sql, mysqli_real_escape_string($link,$valor)); // Seguridad que evita los ataques SQL Injection  	
			mysqli_query($link, $Sql); // ejecuto el Sql
			mysqli_close($link); // cierro la conexi�n    
		}
		
		return NULL;
		
	} // *********************************************************

}

?>
