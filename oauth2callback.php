<?php
require_once __DIR__ . '/kernel.php';

$client = getClient(true);

if ( isset( $_GET['code'] ) ) {
	$client->fetchAccessTokenWithAuthCode( $_GET['code'] );
	$_SESSION['access_token'] = $client->getAccessToken();
	redirectTo('http://' . $_SERVER['HTTP_HOST'] . '/');
} else {
	redirectTo($client->createAuthUrl());
}