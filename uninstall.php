<?php
/*
Rutina de desinstalaci�n 
*/

// Asegura que ralmente se desea desinstalar
if ( !defined('WP_UNINSTALL_PLUGIN') ) {
    exit();
}

// Borra configuracion
delete_option('itomx');

