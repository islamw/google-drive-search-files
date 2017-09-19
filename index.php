<?php
require_once __DIR__. '/kernel.php';

$refs = ["CV", "CV", "CV"];

$client = getClient();

if ( isExistsToken() ) {
	$client->setAccessToken( $_SESSION['access_token'] );
	$service = new Google_Service_Drive($client);
	foreach($refs as $ref) {
		$result = getFileLink($service, "$ref");
		$fp = fopen("PDFOF$ref.json", 'w');
		fwrite($fp, json_encode($result));
		fclose($fp);
	}
	
	return true;
}

redirectTo(REDIRECT_URI);
