<?php
require_once __DIR__. '/kernel.php';

$client = getClient();

if ( isExistsToken() ) {
	$client->setAccessToken( $_SESSION['access_token'] );
	echo getFileLink( new Google_Service_Drive( $client ), "CV" );
	
	return true;
}

redirectTo(REDIRECT_URI);
