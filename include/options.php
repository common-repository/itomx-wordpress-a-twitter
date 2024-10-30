<?php

// Agregar p&aacute;gina al men&uacute;
function wp_itomx_add_page() {
	// Cargando CSS y JS s&oacute;lo cuando sea necesario.
	$page = add_options_page('itomx: WordPress to Twitter', 'itomx', 'manage_options', 'itomx', 'wp_itomx_do_page');
	add_action("load-$page", 'wp_itomx_add_css_js_plugin');
	// Agrega el CSS y JS para contar caracteres
	add_action('load-post.php', 'wp_itomx_add_css_js_post');
	add_action('load-post-new.php', 'wp_itomx_add_css_js_post');
	add_action('load-page.php', 'wp_itomx_add_css_js_post');
	add_action('load-page-new.php', 'wp_itomx_add_css_js_post');
}

// Agrega estilos y JS a la pagina del plugin.
function wp_itomx_add_css_js_plugin() {
	$plugin_url = WP_PLUGIN_URL.'/'.plugin_basename( dirname(dirname(__FILE__)) );
	wp_enqueue_script('itomx_js', $plugin_url.'/res/itomx.js');
	wp_enqueue_style('itomx_css', $plugin_url.'/res/itomx.css');
}

// Agrega estilos y JS en la p&aacute;gina de Publicar o modificar p&aacute;gina
function wp_itomx_add_css_js_post() {
	global $pagenow;
	$current = str_replace( array('-new.php', '.php'), '', $pagenow);
	if ( wp_itomx_generate_on($current) ) {
		$plugin_url = WP_PLUGIN_URL.'/'.plugin_basename( dirname(dirname(__FILE__)) );
		wp_enqueue_script('itomx_js', $plugin_url.'/res/post.js');
		wp_enqueue_style('itomx_css', $plugin_url.'/res/post.css');
	}
}

// Limpia y valida opciones enviadas
function wp_itomx_sanitize($in) {
	// Limpia campos y strings
	$in = array_map( 'esc_attr', $in);
	foreach( array('generate_on_post', 'tweet_on_post', 'generate_on_page', 'tweet_on_page') as $item ) {
		$in[$item] = ( $in[$item] == 1 ? 1 : 0 );
	}
	return $in;
}

// Genera pagina de opciones
function wp_itomx_do_page() {
	$plugin_url = WP_PLUGIN_URL.'/'.plugin_basename( dirname(dirname(__FILE__)) );
	?>
	<div class="wrap">
	
	<?php /** ?>
	<pre><?php print_r(get_option('itomx')); ?></pre>
	<?php /**/ ?>

	<div class="icon32" id="icon-plugins"><br/></div>
	<h2>ITO.MX - WordPress a Twitter</h2>
	
	<div id="y_logo">
		<div class="y_logo">
			<a href="http://ito.mx/"><img src="<?php echo $plugin_url; ?>/res/itomx-logo.png"></a>
		</div>
		<div class="y_text">
			<p><a href="http://ito.mx/">ITO</a> es es un servicio gratuito que sirve para acortar URLs largos en URLs chiquITOs.<br />Este plugin es un puete entre ito, Twitter y tu blog; cuando publiques un nuevo art&iacute;culo o p&aacute;gina, tu blog llamar&aacute; a ito para que &eacute;ste genere un URL m&aacute;s cortITO para este art&iacute;culo o p&aacute;gina y despu&eacute;s lo enviar&aacute; a tu cuenta de Twitter.</p>

            <form name="_xclick" action="https://www.paypal.com/mx/cgi-bin/webscr" method="post" target="_blank">
            <table border="1"><tr><td><a href="http://ito.mx/donacion" target="_blank"><img src="<?php echo $plugin_url; ?>/res/ito-pledgie.jpg" width="100" height="50" alt="Donar con Pledgie" border="0" /></a></td>
              <td>
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="business" value="donar@ito.mx">
				<input type="hidden" name="item_name" value="Donar al desarrollo de ITO">
				<input type="hidden" name="currency_code" value="MXN">
				<input type="hidden" name="amount" value="25.00">
				<input type="image" src="<?php echo $plugin_url; ?>/res/ito-paypal.jpg" border="0" name="submit" alt="Haz los pagos con Paypal - es rápido, fácil y seguro!">
              </td><td><a href="http://ito.mx/follow" target="_blank"><img src="<?php echo $plugin_url; ?>/res/ito-twitter.jpg" width="100" height="50" alt="S&iacute;guenos en Twitter" border="0" /></a></td></tr></table>
           </form>
            

		</div>
	</div>
	
	<form method="post" action="options.php">
	<?php settings_fields('wp_itomx_options'); ?>
	<?php $itomx = get_option('itomx'); ?>

	<h3>Configuraci&oacute;n de acortador en ITO<br />(Opcional)</h3>

	<table class="form-table">

	<tr valign="top">
	<th scope="row">Nombre de usuario en ITO</th>

	<td>
	<input name="itomx[service]" type="hidden" value="other" />
	<input name="itomx[other]" type="hidden" value="itomx-user" />
    <input type="text" id="y_api_ito_login" name="itomx[ito_login]" value="<?php echo $itomx['ito_login']; ?>"/>
    </td>
    
        <td><strong>Si no tienes a&uacute;n una cuenta, puedes <a href="http://ito.mx/Registration/">registrarte aqu&iacute;.</a></strong><br />(Este es necesario para que puedas llevar las estad&iacute;sticas de tus URLs acortados en ITO)<br /></td>
	</tr>


	<tr valign="top">
	<th scope="row">Contrase&ntilde;a</th>
	<td colspan="2">
    <input id="api_passwd" name="itomx[apipassword]" type="password" value="<?php echo $itomx['apipassword']; ?>" />
    </td>

    
    </tr>

	</table>

	<h3>Informaci&oacute;n de acceso a Twitter</h3> 

	<table class="form-table">

	<tr valign="top">
	<th scope="row">Usuario de Twitter <br />(Login)</th>
	<td valign="bottom"><input id="tw_login" name="itomx[twitter_login]" type="text" value="<?php echo $itomx['twitter_login']; ?>"/></td>
	</tr>

	<tr valign="top">
	<th scope="row">Contrase&ntilde;a de Twitter <br />(Password)</th>
	<td valign="bottom"><input id="tw_passwd" name="itomx[twitter_password]" type="password" value="<?php echo $itomx['twitter_password']; ?>"/></td>
	</tr>
	
	</table>
	
	<h3>Generar un URL ChiquITO</h3> 

	<table class="form-table">

	<tr valign="top">
	<th scope="row">Con cada nueva <strong>publicaci&oacute;n</strong> de tu Blog</th>
	<td>
	<input class="y_toggle" id="generate_on_post" name="itomx[generate_on_post]" type="checkbox" value="1" <?php checked( '1', $itomx['generate_on_post'] ); ?> /><label for="generate_on_post"> Generar URL ChiquITO</label><br/>
	<?php $hidden = ( $itomx['generate_on_post'] == '1' ? '' : 'y_hidden' ) ; ?>
	<div id="y_show_generate_on_post" class="<?php echo $hidden; ?> generate_on_post">
		<input id="tweet_on_post" name="itomx[tweet_on_post]" type="checkbox" value="1" <?php checked( '1', $itomx['tweet_on_post'] ); ?> /><label for="tweet_on_post"> Enviar un Twit con el nuevo URL ChiquITO</label>
	</div>
	</td>
	</tr>

	<tr valign="top">
	<th scope="row">Con una nueva <strong>p&aacute;gina</strong> publicada</th>
	<td>
	<input class="y_toggle" id="generate_on_page" name="itomx[generate_on_page]" type="checkbox" value="1" <?php checked( '1', $itomx['generate_on_page'] ); ?> /><label for="generate_on_page"> Generar URL ChiquITO</label><br/>
	<?php $hidden = ( $itomx['generate_on_page'] == '1' ? '' : 'y_hidden' ) ; ?>
	<div id="y_show_generate_on_page" class="<?php echo $hidden; ?> generate_on_page">
		<input id="tweet_on_page" name="itomx[tweet_on_page]" type="checkbox" value="1" <?php checked( '1', $itomx['tweet_on_page'] ); ?> /><label for="tweet_on_page"> Enviar URL ChiquITO a Twitter</label>
	</div>
	</td>
	</tr>

	</table>

	<h3>Qu&eacute; Twitear</h3> 

	<table class="form-table">

	<tr valign="top">
	<th scope="row">Plantilla para Twit</th>
	<td><input id="tw_msg" name="itomx[twitter_message]" type="text" size="50" value="<?php echo $itomx['twitter_message']; ?>"/><br/>
	Esta es la plantilla para enviar tus mensajes a Twitter. Este plugin reemplazar&aacute; <tt>%T</tt> con el t&iacute;tulo del post y <tt>%U</tt> con el URL acortado por ITO de tu post. Recuerda que s&oacute;lo se pueden enviar un m&aacute;ximo de 140 caracteres a Twitter.<br/>
	Ejemplos: <br/>
	<ul id="tw_msg_sample">
		<li><code class="tw_msg_sample">Lo m&aacute;s nuevo en <?php bloginfo();?>: %T - %U</code></li>
		<li><code class="tw_msg_sample">Noticias al d&iacute;a: %T - (%U)</code></li>
		<li><code class="tw_msg_sample">%T - %U</code></li>
	</ul>
	<em>Tip: Mant&eacute;n el mensaje de tu plantilla para Twitter corto.</em>
	</td>
	</td>
	</tr>

	</table>
	
	<h3>Auto descubrir URL corto</h3>
	
	<p>Agrega un c&oacute;digo <code>&lt;link></code> en el <code>&lt;head></code> de tus p&aacute;ginas (Altamente recomendado)<br /></p>
	
	<table class="form-table">

	<tr valign="top">
	<th scope="row">En art&iacute;culos (sugerido)</th>
	<td valign="bottom">
	<input id="link_on_post" name="itomx[link_on_post]" type="checkbox" value="1" <?php checked( '1', $itomx['link_on_post'] ); ?> /><label for="link_on_post"> </label><br/>
	</td>
	</tr>

	<tr valign="top">
	<th scope="row">En p&aacute;ginas (sugerido) </th>
	<td valign="bottom">
	<input id="link_on_page" name="itomx[link_on_page]" type="checkbox" value="1" <?php checked( '1', $itomx['link_on_page'] ); ?> /><label for="link_on_page"> </label><br/>
	</td>
	</tr>

	</table>
	


	<p class="submit">
	<input type="submit" class="button-primary y_submit" value="<?php _e('Guardar cambios') ?>" />
	</p>

	</form>

	</div> <!-- wrap -->

	
	<?php	
}

// Agrega meta caja a las pagina de publicacion y edicion
function wp_itomx_addbox() {
	if ( wp_itomx_generate_on('post') )
		add_meta_box('itomxdiv', 'Acortar URL &amp; Twitearlo', 'wp_itomx_drawbox', 'post', 'side', 'default');
	if ( wp_itomx_generate_on('page') )
		add_meta_box('itomxdiv', 'Acortar URL &amp; Twitearlo', 'wp_itomx_drawbox', 'page', 'side', 'default');
}

// Genera meta cajas
function wp_itomx_drawbox($post) {
	$type = $post->post_type;
	$status = $post->post_status;
	$id = $post->ID;
	$title = $post->post_title;

	if ($type != 'post' && $type !='page')
		return; 
	
	if ($status != 'publish') {
		echo '<p>Cuando publiques esta entrada, vas a poder promoverla aqu&iacute; con ITO.mx en Twitter.</p>
		<p>Dependiendo de tu <a href="options-general.php?page=itomx">configuraci&oacute;n</a>, esto podr&aacute; hacerse autom&aacute;ticamente.</p>';
		return;
	}
	
	$shorturl = wp_itomx_geturl( $id );
	// Bummer, could not generate a short URL
	if (!shorturl) {
		echo '<p>Error: ITO.mx no est&aacute; disponible en este momento, por favor intenta m&aacute;s tarde.</p>';
		return;
	}
	

	
	global $wp_itomx;
	$action = 'Twitear esto';
	$promote = "Promover $type";
	$tweeted = get_post_meta( $id, 'itomx_tweeted', true );
	$account = $wp_itomx['twitter_login'];

	wp_nonce_field( 'itomx', '_ajax_itomx', false );
	echo '
	<input type="hidden" id="itomx_post_id" value="'.$id.'" />
	<input type="hidden" id="itomx_shorturl" value="'.$shorturl.'" />
	<input type="hidden" id="itomx_twitter_account" value="'.$account.'" />';
	
	echo '<p><strong>URL corto</strong></p>';
	echo '<div id="itomx-shorturl">';
	
	echo "<p>El URL corto de tu $type es: <strong><a href='$shorturl'>$shorturl</a></strong></p>
	<p>Si este URL aparece con error, puedes regenerarlo haciendo clic en:</p>";
	echo '<p style="text-align:right"><input class="button" id="itomx_reset" type="submit" value="Regenerar URL corto" /></p>';
	echo '</div>';
	
	echo '<p><strong>'.$promote.' en <a href="http://twitter.com/'.$account.'">@'.$account.'</a>: </strong></p>
	<div id="itomx-promote">';
	if ($tweeted) {
		$action = 'Twitear nuevamente';
		$promote = "Promueve tu $type nuevamente";
		echo '<p><em>Nota:</em> esta publicaci&oacute;n ya fue twiteada.</p>';
	}
	echo '<p><textarea id="itomx_tweet" rows="1" style="width:100%">'.wp_itomx_maketweet( $shorturl, $title ).'</textarea></p>
	<p style="text-align:right"><input class="button" id="itomx_promote" type="submit" value="'.$action.'" /></p>
	</div>';
	
	?>
	<script type="text/javascript">
	/* <![CDATA[ */
	(function($){
		var itomx = {
			// Enviar un Twit
			send: function() {
			
				var post = {};
				post['itomx_tweet'] = $('#itomx_tweet').val();
				post['itomx_post_id'] = $('#itomx_post_id').val();
				post['itomx_twitter_account'] = $('#itomx_twitter_account').val();
				post['action'] = 'itomx-promote';
				post['_ajax_nonce'] = $('#_ajax_itomx').val();

				$('#itomx-promote').html('<p>Un momento, por favor...</p>');

				$.ajax({
					type : 'POST',
					url : '<?php echo admin_url('admin-ajax.php'); ?>',
					data : post,
					success : function(x) { itomx.success(x, 'itomx-promote'); },
					error : function(r) { itomx.error(r, 'itomx-promote'); }
				});
			},
			
			// Resetea URI corto
			reset: function() {
			
				var post = {};
				post['itomx_post_id'] = $('#itomx_post_id').val();
				post['itomx_shorturl'] = $('#itomx_shorturl').val();
				post['action'] = 'itomx-reset';
				post['_ajax_nonce'] = $('#_ajax_itomx').val();

				$('#itomx-shorturl').html('<p>Un momento, por favor...</p>');

				$.ajax({
					type : 'POST',
					url : '<?php echo admin_url('admin-ajax.php'); ?>',
					data : post,
					success : function(x) { itomx.success(x, 'itomx-shorturl'); itomx.update(x); },
					error : function(r) { itomx.error(r, 'itomx-shorturl'); }
				});
			},
			
			// Actualiza URI corto en el area de texto del Twit
			update: function(x) {
				var r = wpAjax.parseAjaxResponse(x);
				r = r.responses[0];
				var oldurl = r.supplemental.old_shorturl;
				var newurl = r.supplemental.shorturl;
				var bg = jQuery('#itomx_tweet').css('backgroundColor');
				if (bg == 'transparent') {bg = '#fff';}

				$('#itomx_tweet')
					.val( $('#itomx_tweet').val().replace(oldurl, newurl) )
					.animate({'backgroundColor':'#ff8'}, 500, function(){
						jQuery('#itomx_tweet').animate({'backgroundColor':bg}, 500)
					});
			},
			
			// Ajax exitoso
			success : function(x, div) {
				if ( typeof(x) == 'string' ) {
					this.error({'responseText': x}, div);
					return;
				}

				var r = wpAjax.parseAjaxResponse(x);
				if ( r.errors )
					this.error({'responseText': wpAjax.broken}, div);

				r = r.responses[0];
				$('#'+div).html('<p>'+r.data+'</p>');
			},

			// Ajax: error
			error : function(r, div) {
				var er = r.statusText;
				if ( r.responseText )
					er = r.responseText.replace( /<.[^<>]*?>/g, '' );
				if ( er )
					$('#'+div).html('<p>Error de petici&on a AJAX: '+er+'</p>');
			}
		};
		
		$(document).ready(function(){
			$('#itomx_promote').click(function(e) {
				itomx.send();
				e.preventDefault();
			});
			$('#itomx_reset').click(function(e) {
				itomx.reset();
				e.preventDefault();
			});
			
			$('#edit-slug-box').append('<span id="itomx-shorturl-button"><a onclick="prompt(\'URL Corto:\', \'<?php echo $shorturl; ?>\'); return false;" class="button" href="#">Acortar URL</a></span>');
			$('#itomx-shorturl-button a').css('border-color','#bbf');
		})

	})(jQuery);
	/* ]]> */
	</script>

	<?php
}

?>