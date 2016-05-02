<?php
$con=mysqli_connect("","root","7171","bdtutoria");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  } else {
  echo "Conectado: ";  
  }
 
$sql="SELECT Empleado, DNI FROM tb_profesores";
echo $sql;
$result=mysqli_query($con,$sql);


// Numeric array /*
/*
while($row=mysqli_fetch_array($result,MYSQLI_NUM)) {
	printf ("%s (%s)\n",$row[0],$row[1]);
	echo '</br>';
	}; */

while($row=mysqli_fetch_array($result)) {
	printf ("%s  -- %s -- \n",$row["Empleado"],$row["DNI"]);
	echo '</br>';
	};


// Free result set
mysqli_free_result($result);

mysqli_close($con);
?> 
