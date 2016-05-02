
// Funciones para los tooltips...

$(document).tooltip({ // añade etiquetas TIP personalizadas al atributo TITLE
	show: { effect: "slideDown", delay: 300 }, // con efecto de ir mostrando
	// show: { effect: "blind", duration: 800 },
	hide: { effect: "blind", duration: 300 },
	position: { my: "center top+5", at: "center bottom+5" },
	open: function (event, ui) {
		setTimeout(function () {
			$(ui.tooltip).hide({ effect: "blind", duration: 300 });
		}, 1500);
    } // Con esto se cierran solas al segundo y medio.
}); 


