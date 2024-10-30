<?php

// Agrega <link> a <head> si aplica
function wp_itomx_add_head_link() {
	global $wp_itomx;
	if(
		( is_single() && $wp_itomx['link_on_post'] ) ||
		( is_page() && $wp_itomx['link_on_page'] )
	) {
		wp_itomx_head_linkrel();
	}
}

// Envio de Twit manual desde la interfaz de edicion
function wp_itomx_promote() {
	check_ajax_referer( 'itomx' );
	$account = $_POST['itomx_twitter_account'];
	$post_id = (int) $_POST['itomx_post_id'];

	if ( wp_itomx_send_tweet( stripslashes($_POST['itomx_tweet']) ) ) {
		$result = "Esta publicaci&oacute;n se promovi&oacute; exitosamente en <a href='http://twitter.com/$account'>@$account</a>!";
		update_post_meta($post_id, 'itomx_tweeted', 1);
	} else {
		$result = "Error: No se pudo promover esta publicaci&oacute;n en <a href='http://twitter.com/$account'>@$account</a>. Error de conexi&oacute;n con Twitter";
	}
	$x = new WP_AJAX_Response( array(
		'data' => $result
	) );
	$x->send();
	die('1');	
}

// Reseteo manual del URI corto en la interfaz de edicion
function wp_itomx_reset_url() {
	check_ajax_referer( 'itomx' );
	$post_id = (int) $_POST['itomx_post_id'];

	$old_shorturl = $_POST['itomx_shorturl'];
	delete_post_meta($post_id, 'itomx_shorturl');
	$shorturl = wp_itomx_geturl( $post_id );

	if ( $shorturl ) {
		$result = "Se acort&oacute; el URL a: <a href='../include/$shorturl'>$shorturl</a>";
		update_post_meta($post_id, 'itomx_shorturl', $shorturl);
	} else {
		$result = "Error: No se pudo acortar el URL. Error de conexi&oacute;n con ITO.mx, por favor intenta nuevamente m&aacute;s tarde.";
	}
	$x = new WP_AJAX_Response( array(
		'data' => $result,
		'supplemental' => array(
			'old_shorturl' => $old_shorturl,
			'shorturl' => $shorturl
		)
	) );
	$x->send();
	die('1');	
}

// Se llama a funcion con una nueva publicacion
function wp_itomx_newpost( $post ) {
	global $wp_itomx;
	
	$post_id = $post->ID;
	$url = get_permalink( $post_id );
	
	if ( $post->post_type != 'post' && $post->post_type != 'page' )
		return;
		
	// Genera URI corto
	if ( !wp_itomx_generate_on( $post->post_type ) )
		return;
	
	$title = get_the_title($post_id);
	$url = get_permalink ($post_id);
	$short = wp_itomx_get_new_short_url( $url );
	
	// Envio de URI a Twitter?
	if ( !wp_itomx_tweet_on( $post->post_type ) )
		return;

	if ( !get_post_custom_values( 'itomx_tweeted', $post_id ) ) {
		// No Twiteado aun
		$tweet = wp_itomx_maketweet( $short, $title );
		if ( wp_itomx_send_tweet( $tweet ) )
			update_post_meta($post_id, 'itomx_tweeted', 1);
	}
	
}

// Twitea el estatus - Retorna booleano por exito o error
function wp_itomx_send_tweet( $tweet ) {
	global $wp_itomx;
	require_once( dirname(__FILE__) . '/twitter.php' );
	return ( wp_itomx_tweet_it($wp_itomx['twitter_login'], $wp_itomx['twitter_password'], $tweet) );
}

// Conextor WP con ITO. Obtiene URI corto
function wp_itomx_get_new_short_url( $url, $post_id = 0 ) {
	global $wp_itomx;

	// Verifica configuracion del plugin
	$service = wp_itomx_service();
	if( !$service )
		return 'Plugin no configurado: No se han establecido las opciones a&uacute;n.';

	// Obtiene URI corto
	$shorturl = wp_itomx_api_call( $service, $url);

	// Almacena URI corto en un campo preliminar
	if ($post_id && $shorturl)
		update_post_meta($post_id, 'itomx_shorturl', $shorturl);

	return $shorturl;
}

// Llama al API. Obtiene el URI corto y un false en caso de error
function wp_itomx_api_call( $api, $url) {
	global $wp_itomx;

	$shorturl = '';

	switch( $api ) {

		case 'itomx-user':
			$api_url = sprintf( 'http://ito.mx?module=ShortURL&file=Add&mode=api&to_user=' . $wp_itomx['ito_login'] . '&to_pass=' . $wp_itomx['apipassword'] . '&url=%s', urlencode($url) );
			$shorturl = wp_itomx_remote_simple( $api_url );
			break;

		default:
			die('Error, este Plugin s&oacute;lo funciona con ITO');
	
	}
	
	return $shorturl;
}


// Invoca al API
function wp_itomx_remote_simple( $url ) {
	return wp_itomx_fetch_url( $url );
}



// Obtiene una pagina remota y obtiene el contenido
function wp_itomx_fetch_url( $url, $method='GET', $body=array(), $headers=array() ) {
	$request = new WP_Http;
	$result = $request->request( $url , array( 'method'=>$method, 'body'=>$body, 'headers'=>$headers, 'user-agent'=>'itomx http://ito.mx/' ) );

	// Exito?
	if ( !is_wp_error($result) && isset($result['body']) ) {
		return $result['body'];

	// Falla (problema de servidor)
	} else {
		// Retorna con error
		return false;
	}
}


// Parsea la plantilla de Tweet y genera cadena de 140 caracteres maximo
function wp_itomx_maketweet( $url, $title ) {
	global $wp_itomx;
	// Reemplaza %U con el URL corto
	$url = " " . $url . " ";
	$tweet = str_replace('%U', $url, $wp_itomx['twitter_message']);
	// Reemplaza %T - Mantiene el conteo de caracteres ABAJO de 140
	$maxlen = 140 - ( strlen( $tweet ) - 2); // 2 = "%T"
	if (strlen($title) > $maxlen) {
		$title = substr($title, 0, ($maxlen - 3)) . '...';
	}
	
	$tweet = str_replace('%T', $title, $tweet);
	return $tweet;
}

// Inicializa opciones del plugin
function wp_itomx_init(){
	global $wp_itomx;
	if (function_exists('register_setting')) 
		register_setting( 'wp_itomx_options', 'itomx', 'wp_itomx_sanitize' );
	$wp_itomx = get_option('itomx');
}


// Genera URI segun tipo de publicacion, articulo o pagina y regresa booleano 
function wp_itomx_generate_on( $type ) {
	global $wp_itomx;
	return ( $wp_itomx['generate_on_'.$type] == 1 );
}

// Envia el Twit segun tipo de publicacion, articulo o pagina y regresa booleano
function wp_itomx_tweet_on( $type ) {
	global $wp_itomx;
	return ( $wp_itomx['tweet_on_'.$type] == 1 );
}

// Se asegura de que el Plugin se ha configurado y asegura uso de ITO
function wp_itomx_service() {
	global $wp_itomx;
	if ( $wp_itomx['service'] == 'other')
		return $wp_itomx['other'];
}

// Icono de ITO.mx
function wp_itomx_customicon($in) {
	return WP_PLUGIN_URL.'/'.plugin_basename(dirname(dirname(__FILE__))).'/res/icon.gif';
}

// Agrega la liga de OPCIONES a la pagina del PLUGIN
function wp_itomx_plugin_actions($links) {
	$links[] = "<a href='./options-general.php?page=itomx'><b>Opciones</b></a>";
	return $links;
}



?>