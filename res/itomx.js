// Funciones de la pagina de publicacion
jQuery(document).ready(function($){

	// Switches para los DIVs
    $('.y_toggle').each(function(){
		$(this).change(function(){
			var source = $(this).attr('id');
			if ( $(this).attr('type') == 'checkbox' ) {
				if ($(this).attr('checked') == true) {
					$('.'+source).fadeIn(100);
				} else {
					$('.'+source).fadeOut(100).find(':checkbox').attr('checked',false);
				}
			} else {
				var target = $(this).val();
				$('.'+source).hide();
				$('#y_show_'+target).fadeIn(300);
			}
		});
	})	
	
	// Muestra los caracteres de la contraseña
	$('input:password').each(function(){
		var target = $(this).attr('id');
		$(this).after('&nbsp;<label><input type="checkbox" class="y_reveal" id="y_reveal_'+target+'> Mostrar caracteres</label>');
		$('#y_reveal_'+target).data('target', target)
	});
	
	// Muestra caracteres: comportamiento de las cajas de seleccion
	$('.y_reveal').change(function(){
		var target = $(this).data('target');
		password_toggle(target, $(this).attr('checked'));
		return;
	});
	
	// Muestra de Twitter
	$('.tw_msg_sample').click(function(){
		$('#tw_msg').val($(this).html());
	});
	
	// Intercambia el despliegue entre contrase#as y campos de texto
	function password_toggle(target, display) {
		if (display) {
			var pw = $('#'+target).val();
			$('#'+target).hide().after('<input type="text" name="'+target+'_text__" value="'+pw+'" id="'+target+'_text__"/>');
		} else {
			var pw = $('#'+target+'_text__').val();
			$('#'+target).show().val(pw);
			$('#'+target+'_text__').remove();
		}
	}
	
	// Resetea todos los campos de contraseña
	function password_hide_all() {
		$('input:password').each(function(){
			password_toggle( $(this).attr('id'), false );
		});
	}
	
	// Al enviar forma, resetea los campos de contraseñas
	$('.y_submit').click(function(){
		password_hide_all();
	});
	
	// Limpia los paths de Windows.
	$('#y_path').keyup(function(){
		$(this).val( $(this).val().replace(/\\/g, '/') );
	});
});

