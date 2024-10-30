<?php

// Manda un mensaje a Twitter, retorna booleano en caso de exito o error
function wp_itomx_tweet_it($username, $password, $message){

    $api_url = 'http://twitter.com/statuses/update.xml';
	
	$body =    array( 'status'=>$message );
	$headers = array( 'Authorization' => 'Basic '.base64_encode("$username:$password") );
	
	$result = wp_itomx_fetch_url( $api_url, 'POST', $body, $headers );
	
	// Verificacion basica de exito o error si el campo contiene tags de <error>
	return ( preg_match_all('!<error>[^<]+</error>!', $result, $matches) !== 1 );
}
?>