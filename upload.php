<?php

if (!file_exists(__DIR__.'/vendor/autoload.php')) {
   throw new Exception("No vendor found, please run composer install --no-dev");
}
require_once __DIR__.'/vendor/autoload.php';
if (!file_exists(__DIR__.'/config.inc.php')) {
   throw new Exception("config.inc.php file not found, please create if and copy config.inc.example content.");
}
require_once __DIR__.'/config.inc.php';


// define api client
$api_client = new GuzzleHttp\Client(['base_uri' => trim(GLPI_URL, '/').'/apirest.php/']);


// connect to api
$response = $api_client->get("initSession/", ['auth' => [API_USER, API_PASSWORD]]);
$code = $response->getStatusCode();
$session_token = json_decode( (string) $response->getBody(), true)['session_token'];


// retrieve glpi version
$response = $api_client->get("Config/", ['headers' => [
                                          'Session-Token' => $session_token]]);
$code = $response->getStatusCode();
$config = json_decode( (string) $response->getBody(), true);
$glpi_version = $config[0]['value'];


// define fileupload client
$fileupload_client = new GuzzleHttp\Client([
   'base_uri' => trim(GLPI_URL, '/').
      (version_compare($glpi_version, "9.2") == -1 // if glpi 9.1.x, fileupload is in front folder
         ? '/front/'
         : '/ajax/')
      .'fileupload.php']
);


// let's proceed a document addition

// 1st, send file to glpi ajax/fileupload (copy to glpi _tmp folder)
$response = $fileupload_client->post('', [
   'multipart' => [
      [
         'name'     => 'name',
         'contents' => 'filename'
      ],[
         'name'     => 'filename[]',
         'contents' => file_get_contents($_FILES['filename']['tmp_name'][0]),
         'filename' => $_FILES['filename']['name'][0]
      ]
]]);
$body_fileupload    = json_decode( (string) $response->getBody(), true);
$message_fileupload = array_shift($body_fileupload);

if (isset($message_fileupload[0]['error'])) {
   throw new Exception($message_fileupload[0]['error']);
}

// 2nd add document object
$response = $api_client->post('Document/', [
   'headers' => [
      'Session-Token' => $session_token
   ],
   'json'   => [
      'input' => [
         'name'      => $_POST['document_name'],
         '_filename' => [$message_fileupload[0]['name']]
      ]
   ]
]);
