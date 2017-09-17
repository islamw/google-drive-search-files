<?php
//require_once __DIR__.'/vendor/autoload.php';
//
//const APPLICATION_NAME = 'Drive API PHP Quickstart';
//
//const CREDENTIALS_PATH = '~/.credentials/drive-php-quickstart.json';
//
//const CLIENT_SECRET_PATH = __DIR__.'/client_secret.json';
//
//const EXTENSIONS = ['pdf', 'jpg', 'jpeg'];
//
//// If modifying these scopes, delete your previously saved credentials
//// at ~/.credentials/drive-php-quickstart.json
//
//define(
//	'SCOPES',
//	implode(
//		' ',
//		[
//			Google_Service_Drive::DRIVE
//		]
//	)
//);
//
//if (php_sapi_name() != 'cli') {
//	throw new Exception('This application must be run on the command line.');
//}
//
///**
// * Returns an authorized API client.
// *
// * @return \Google_Client the authorized client object
// * @throws \Google_Exception
// * @throws \InvalidArgumentException
// * @throws \LogicException
// */
//function getClient()
//{
//	$client = new Google_Client();
//	$client->setApplicationName(APPLICATION_NAME);
//	$client->setScopes(SCOPES);
//	$client->setAuthConfig(CLIENT_SECRET_PATH);
//	$client->setAccessType('offline');
//
//	// Load previously authorized credentials from a file.
//	$credentialsPath = expandHomeDirectory(CREDENTIALS_PATH);
//	if (file_exists($credentialsPath)) {
//		$accessToken = json_decode(file_get_contents($credentialsPath), true);
//	} else {
//		// Request authorization from the user.
//		$authUrl = $client->createAuthUrl();
//		printf("Open the following link in your browser:\n%s\n", $authUrl);
//		print 'Enter verification code: ';
//		$authCode = trim(fgets(STDIN));
//
//		// Exchange authorization code for an access token.
//		$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
//
//		// Store the credentials to disk.
//		if (!file_exists(dirname($credentialsPath))) {
//			mkdir(dirname($credentialsPath), 0700, true);
//		}
//		file_put_contents($credentialsPath, json_encode($accessToken));
//		printf("Credentials saved to %s\n", $credentialsPath);
//	}
//	$client->setAccessToken($accessToken);
//
//	// Refresh the token if it's expired.
//	if ($client->isAccessTokenExpired()) {
//		$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
//		file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
//	}
//
//	return $client;
//}
//
///**
// * Expands the home directory alias '~' to the full path.
// *
// * @param string $path the path to expand.
// *
// * @return string the expanded path.
// */
//function expandHomeDirectory($path)
//{
//	$homeDirectory = getenv('HOME');
//	if (empty($homeDirectory)) {
//		$homeDirectory = getenv('HOMEDRIVE').getenv('HOMEPATH');
//	}
//
//	return str_replace('~', realpath($homeDirectory), $path);
//}
//
///**
// * @param \Google_Service_Drive $service
// * @param $argv
// *
// * @return bool|string
// */
//function getFileLink(Google_Service_Drive $service, $argv)
//{
//	if (count($argv) < 2) {
//		return "Enter name of file!";
//	}
//
//	$extensionsKeys   = array_keys(EXTENSIONS);
//	$lastExtensionKey = end($extensionsKeys);
//
//	foreach (EXTENSIONS as $key => $EXTENSION) {
//		$name   = "name='".$argv[1].".".$EXTENSION."'";
//
//		$result = $service->files->listFiles(['q' => $name, 'fields' => 'files(id, webViewLink, permissions)']);
//		$files  = $result->getFiles();
//		if (empty($files)) {
//			if ($lastExtensionKey == $key) {
//				return 'This file doesn\'t exists';
//			}
//			continue;
//		} else {
//			/** @var Google_Service_Drive_DriveFile $file */
//			$file = $files[0];
//
//			/** @var \Google_Service_Drive_Permission $permission */
//			$permission = new Google_Service_Drive_Permission();
//			$permission->setRole('reader');
//			$permission->setType('anyone');
//
//			$service->permissions->create($file->id, $permission);
//
//			return $file->webViewLink;
//		}
//	}
//
//	return false;
//}
//
//// Get the API client and construct the service object.
//$client  = getClient();
//$service = new Google_Service_Drive($client);
//
//$result = getFileLink($service, $argv);
//
//print $result;