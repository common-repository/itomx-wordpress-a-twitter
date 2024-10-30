=== ito.mx: WordPress a Twitter ===
Contributors: Miguel Ayala
Donate link: http://ito.mx/donacion
Tags: acortador, twitter, links, urls, mexicano, plugin, API
Requires at least: 2.8
Tested up to: 2.9.1
Stable tag: trunk

Crea un URL corto para cada publicacion con el formato ito.mx/123 y mandalo directamente a tu cuenta de Twitter.

== Description ==

ito.mx es un servicio de acortador de URLs gratuITO el cual puedes aprovechar para usar en tu Blog, para postear links en tu cuenta de Twitter, Facebook, o tambien configurarlo para usarlo de forma automatica con Tweetie 2 con tu iPhone.

Este plugin es un puente entre ito.mx y Twitter y tu blog. Cuando tu creas un nuevo post o pagina y publicas el contenido, tu blog para pedirle a ito.mx que genere una URL corta de ese post o pagina y si asi lo deseas el plugin podra twitearlo por ti de forma automatica.

Este plugin cuenta con una API para que los desarrolladores puedan divertirse con ella y la puedan incluir en sus proyectos web de forma sencilla.
	
== Installation ==

La instalacion es standard:

1. Crea un folder en tu directorio de /wp-content/plugins/ llamado 'ito' y vacia el contenido del plugin en ese folder.
1. Activa el plugin a traves del menu de 'Plugins' en WordPress.
1. Configura las opciones del plugin a traves del link llamado 'Opciones' y denrto de ahi un submenu nuevo llamado 'itomx'.

== Frequently Asked Questions ==

= Como se instala el plugin? =
Debes de crear una carpeta dentro de /wp-content/plugins/ llamada ito y ahi vaciar el contenido del plugin.

= Que debo hacer si no tengo una cuenta en ito? =
Tienes dos opciones: Darte de alta con tu usuario y contraseña de twitter o bien registrarte y crear una cuenta en ito.

= Puedo borrar mis URL creadas? =
Si eres usuario registrado si. Si creaste una url y no lo hiciste como usuario registrado no podras borrarla.

= Puedo crear una URL temporal? =
Si, ito.mx permite crear URLs cortas temporales.

= Puedo crear una URL corta con contraseña? =
Si, el sistema de ito permite crear URLs cortas con contraseña.

= Que beneficio tiene que este registrado? =
Que podras llevar un control y administrar tus URLs creadas, y tambien llevar una estadistica de cada una.

= ito.mx hace 301 redirect hacia la URL que se enalce? =
Si, ito se preocupo desde un principio de esto y todas las URLs cortas redireccionan con un 301 permanent redirect.

== Upgrade Notice ==
Para actualizar el plugin solo es necesario desactivar el que tengas instalado, borrarlo y subir la nueva version y activarla.

== Screenshots ==
 
1. Panel de configuracion del Plugin de ito.mx para Wordpress
2. Esta es la funcion que te ayudara a contar los caracteres de tu titulo
3. Desde el panel donde escribes tus posts, podras regenerar un URL cortITO

== Changelog ==
 
= 1.02 =
* Nueva funcion: Se le agrego el soporte para obtener autorizacion mediante una contraseña a traves de la API de ito, el parametro: to_pass.
 
= 1.01 =
* Correccion de BUG: Un error que prevenia que los tags para los templates funcionar fue ya arreglado.

= 1.0 =
* Primer release publico.