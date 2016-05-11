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
	    $Sql='SELECT idasignacion FROM tb_asignaciones ';
	    $Sql.="WHERE datos LIKE '%#".$idal."#%' OR datos LIKE '%".$idal."#' OR datos LIKE '".$idal."#%' OR datos LIKE '%".$cursoalumno."%' ";
	    $Sql.='ORDER BY idasignacion';
	    $ii=0; // contador 
	    // NO PUEDO USARLO... $Sql = sprintf($Sql, mysqli_real_escape_string($link,"idasignacion")); // Seguridad que evita los ataques SQL Injection  	
        $result=mysqli_query($link,$Sql);// ejecuta la cadena sql y almacena el resultado el $result
	    $cadena = "";
	    while ($row=mysqli_fetch_array($result)) {
			$cadena.=$row["idasignacion"]."#";
		} // fin del while
		if (!is_null($cadena)) { // si no lo recupera, el valor por defecto)
		    return substr($cadena, 0, -1);; //envia el valor dado
		    } else {
		    return NULL;
	        }
	   mysqli_free_result($result); 
	   mysqli_close($link); 
	}
	
	// ********************************************************** 
	
	// 5b) Funci�n que, dada una lista de alumnos (id1#id2#id3...#idn) , retorna un listado de asignaciones a las que pertenecen dichos alumnos
	public function devuelveAsignacionesDeUnaTutoria($id, $profesor) {
		// A) Recibe la asignaci�n. Recupera sus alumnos.
		$alumnadoTutoria = 
		// B) Por cada uno de los alumnos, haz una cadena con las asignaciones
		
		// C) Split de esa cadena y meterlo en un array.
		
		// D) quitar repetidos
		
		// E) ordenar
	}
	// ********************************************************** 
}

?>
