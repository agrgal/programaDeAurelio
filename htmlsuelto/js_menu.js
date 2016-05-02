
// carga dinámicamente el contenido del menú profesores
$("#menuProfesor")
	.click(function(event) {
		var posting = $.post("./htmlsuelto/menuIZQUIERDO.php", { 
					  lee: 1,
			});
				  posting.done(function(data,status) { 
					  // alert(data);
					  $("#menu").html(data); 
				  });
});

// También al pasar el ratón sobre él.
$("#menuProfesor").mouseover(function() {
   // this.click();
});


// carga dinámicamente el contenido del menu administracion		
$("#menuAdmin")
	.click(function(event) {
		var posting = $.post("./htmlsuelto/menuIZQUIERDO.php", { 
					  lee: 3,
			});
				  posting.done(function(data,status) { 
					  // alert(data);
					  $("#menu").html(data); 
				  });
});
// También al pasar el ratón sobre él.
$("#menuAdmin").mouseover(function() {
   // this.click();
});

// carga dinámicamente el contenido del menú profesores
$("#menuTutor")
	.click(function(event) {
		var posting = $.post("./htmlsuelto/menuIZQUIERDO.php", { 
					  lee: 2,
			});
				  posting.done(function(data,status) { 
					  // alert(data);
					  $("#menu").html(data); 
				  });
});

// También al pasar el ratón sobre él.
$("#menuTutor").mouseover(function() {
   //this.click();
});


