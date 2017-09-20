# programaDeAurelio
Programa para la recogida de datos en un centro educativo

Este programa servirá para recoger datos de tutoría que ayuden a los tutores a recoger información sobre su alumnado, y manejarla. El profesorado, tras crearse asignaciones, puede opinar sobre sus alumnos y estas opiniones pueden después ser recogidas por el tutor. Usar siempre de forma interna, no presentar la información directamente a los padres. Este progrma sirve para facilitar el trabajo a los tutores y a la jefatura de estudios.

Instrucciones para administradores

A) Base de datos.

La base de datos bdtutotia.sql se encuentra en el directorio otros. Contiene algunos datos de ejemplo, que no se corresponden con la realidad. Recomiendo el uso de phpmyadmin. Antes de empezar a manejar la aplicación, hay que crear esta base de datos en el servidor y realizar los siguientes pasos:
  
  1) En la tabla tb_alumno, hacer una carga de todos los datos del alumnado, del tipo [Apellidos],[Nombre] y [Curso]
  2) Borrar el contenido de la tabla tb_asignaciones
  3) Borrar el contenido de la tabla tb_opiniones
  4) Borrar el contenido de la tabla tb_opiniongeneral
  5) Se puede prescindir de la tabla 'tb_ord_incidencias'; no sirve con esta aplicación.
  6) En la tabla tb_profesores, incluir la lista de los mismos. 
    a) Usar en 'Empleado' el formato de [Apellidos],[Nombre]
    b) El DNI sirve como contraseña provisional. Puede cambiarse después por cada usuario.
    c) El campo IDEA puede obviarse (se puede dejar datos en blanco pero no modificar el campo en sí). Modificando el programa puede servir de contraseña.
    d) En 'tutorde' puede incluirse el curso del que se es tutor. Sin embargo, en la mecánica del programa, la asignación de tutoras se realiza de otra forma. Es el usuario el que indica si es o no tutor de un curso.
    e) email
    f) campo administrador --> 1, usuario normal --> 0
    
El resto de datos no borrar. Se pueden modificar desde la consola de administración del programa.

B) Fichero config.php

Este fichero se encuentra en el directorio configuracion. 
Contiene las variables de acceso a la base de datos mysql (servidor, contraseña, login) y a una dirección de correo electrónico SMTP

C) Posibles BUGs
  1) Si el tutor hace una selección de datos muy amplia en "Datos en mi tutora" puede tener problemas al generar el PDF. Debe reducir el intervalo de datos. Necesita una revisión de la programación, en la forma de generar el pdf.
  2) Codificación en la fecha de los PDFs
  3) Puede haber problemas con el CSS. 
  4) Leer las instrucciones de la página de inicio para una correcta visualización (a la izquierda).
  
D) Instrucciones para el usuario

En cada página hay instrucciones en video de cómo se usan, o bien un símbolo de ayuda, excepto en "Histórico de opiniones" en la que la ayuda se encuentra en la página anterior "Opiniones alumno por alumno"
  
  
