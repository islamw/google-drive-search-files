<?php
require_once __DIR__ . '/vendor/autoload.php';

session_start();

define( 'REDIRECT_URI', 'http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php' );

const EXTENSIONS = [ 'pdf', 'jpg', 'jpeg' ];

/**
 * @param bool $oauthCallback
 *
 * @return \Google_Client
 * @throws \Google_Exception
 */
function getClient( $oauthCallback = false ) {
	$client = new Google_Client();
	$client->setAuthConfig( 'client_secrets_web.json' );
	$client->addScope( Google_Service_Drive::DRIVE );
	
	$oauthCallback ? $client->setRedirectUri( REDIRECT_URI ) : $client->setAccessType( "offline" );
	
	return $client;
}

/**
 * @param $url
 */
function redirectTo( $url ) {
	header( 'Location: ' . filter_var( $url, FILTER_SANITIZE_URL ) );
}

/**
 * @return bool
 */
function isExistsToken() {
	return isset( $_SESSION['access_token'] ) && $_SESSION['access_token'];
}

/**
 * @param \Google_Service_Drive $service
 * @param $filename
 *
 * @return bool|string
 */
function getFileLink( Google_Service_Drive $service, $filename ) {
	if ( ! $filename ) {
		return "Filename can't be empty!";
	}
	
	$extensionsKeys   = array_keys( EXTENSIONS );
	$lastExtensionKey = end( $extensionsKeys );
	
	foreach ( EXTENSIONS as $key => $EXTENSION ) {
		$name = "name='" . $filename . "." . $EXTENSION . "'";
		
		$result = $service->files->listFiles( [ 'q' => $name, 'fields' => 'files(id, webViewLink, permissions)' ] );
		$files  = $result->getFiles();
		
		if ( empty( $files ) ) {
			if ( $lastExtensionKey == $key ) {
				return 'This file doesn\'t exists';
			}
			continue;
		} else {
			
			/** @var Google_Service_Drive_DriveFile $file */
			$file = $files[0];
			
			/** @var \Google_Service_Drive_Permission $permission */
			$permission = new Google_Service_Drive_Permission();
			$permission->setRole( 'reader' );
			$permission->setType( 'anyone' );
			
			$service->permissions->create( $file->id, $permission );
			
			return $file->webViewLink;
		}
	}
	
	return false;
}