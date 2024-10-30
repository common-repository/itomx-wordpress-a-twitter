<?php
/*
Plugin Name: ito.mx WordPress a Twitter
Plugin URI: http://blog.ito.mx/28
Description: Crea un URL corto para cada publicaci&oacute;n con el formato ito.mx/ito y m&aacute;ndalos a tu cuenta de Twitter.
Author: ITO.mx
Author URI: http://ito.mx/
Version: 1.02
*/

/* Release History :
1.02 / 2009/11/12 New feature: Added support for password auth through ITO API, Parameter: to_pass
1.01 / 2009/09/12 BUG Fix: An error that prevented template tags to work has been fixed.
1.0 / 2009/07/12 First public release.

Plugin owner Miguel Ayala, info@miguelayala.net
Base programming by Carlos A. Bazan-Canabal, carlos@bazan.mx
ito.mx Wordpress a Twitter is based on Yourls WP2Twitter Plugin and is published under the GNU / GPLv3 License.

/********************* DO NOT EDIT / NO MODIFICAR *********************/

global $wp_itomx;

require_once(dirname(__FILE__).'/include/engine.php');

// Funcion de plantilla: Despliega el URI corto 
function wp_itomx_url() {
	global $id;
	$short = wp_itomx_geturl($id);
	if ($short) echo("<a href=\"$short\" rel=\"nofollow alternate shorter\" title=\"URL ChiqITO\">$short</a>");
}


// Funcion de plantilla: Inserta el tag <link> en la publicacion actual

function wp_itomx_head_linkrel() {
	global $id;
	$short = wp_itomx_geturl( $id );
	if ($short)
		echo "<link rel=\"alternate short shorter shorturl shortlink\" href=\"$short\" />\n";
}
	

// Funcion de plantilla: Despliega el URI corto sin formato
function wp_itomx_raw_url( $echo = false ) {
	global $id;
	$short = wp_itomx_geturl( $id );
	if ($short) {
		if ($echo)
			echo $short;
		return $short;
	}
}



// Obtiene o genera el URI corto para una publicacion. Se ingresa valor numerico, se retorna el string del URI
function wp_itomx_geturl( $id ) {
	$short = get_post_meta( $id, 'itomx_shorturl', true );
	if ( !$short && !is_preview() ) {
		// Si no existe el URI corto, se genera ahora
		require_once(dirname(__FILE__).'/include/engine.php');
		$short = wp_itomx_get_new_short_url( get_permalink($id), $id );
	}
	
	return $short;
}

if (is_admin()) {
	require_once(dirname(__FILE__).'/include/options.php');
	// Agrega pagina de Menu, opciones de configuracion y caja de opcion para la interfaz de publicacion y edicion.
	add_action('admin_menu', 'wp_itomx_add_page');
	add_action('admin_init', 'wp_itomx_addbox', 10);
	// Administra la solicitud de Ajax
	add_action('wp_ajax_itomx-promote', 'wp_itomx_promote' );
	add_action('wp_ajax_itomx-reset', 'wp_itomx_reset_url' );
	// Administra el icono del plugin
	add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'wp_itomx_plugin_actions', -10);
	add_filter( 'ozh_adminmenu_icon_itomx', 'wp_itomx_customicon' );
	// Inicializa plugin
	add_action('admin_init', 'wp_itomx_init', 1 );
} else {
	// Agrega <link> En el <head>
	add_action('wp_head', 'wp_itomx_add_head_link');
	// Inicializa plugin
	add_action('init', 'wp_itomx_init', 1 );
}

// Administra las nuevas publicaciones
add_action('new_to_publish', 'wp_itomx_newpost', 10, 1);
add_action('draft_to_publish', 'wp_itomx_newpost', 10, 1);
add_action('pending_to_publish', 'wp_itomx_newpost', 10, 1);
add_action('future_to_publish', 'wp_itomx_newpost', 10, 1);

?>