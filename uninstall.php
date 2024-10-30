<?php
/*
Rutina de desinstalación 
*/

// Asegura que ralmente se desea desinstalar
if ( !defined('WP_UNINSTALL_PLUGIN') ) {
    exit();
}

// Borra configuracion
delete_option('itomx');

