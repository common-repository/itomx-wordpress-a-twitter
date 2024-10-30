// Funciones de la pagina de publicacion
jQuery(document).ready(function(){
	
	// Agrega el contador de caracteres
	jQuery('#titlewrap #title').after('<div id="itomx_count" title="Conteo para Twitter Max 140 char">000</div>').keyup(function(e){
		itomx_update_count();
	});
	itomx_update_count();
	
	
});

function itomx_update_count() {
	var len = 140 - jQuery('#titlewrap #title').val().length;
	jQuery('#itomx_count').html(len);
	jQuery('#itomx_count').removeClass();
	if (len < 60) {jQuery('#itomx_count').removeClass().addClass('len60');}
	if (len < 30) {jQuery('#itomx_count').removeClass().addClass('len30');}
	if (len < 15) {jQuery('#itomx_count').removeClass().addClass('len15');}
	if (len < 0) {jQuery('#itomx_count').removeClass().addClass('len0');}
}


