<?php

include_once __DIR__."/index.php";
echo "<hr>";
echo "<h2>Return:</h2>";

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

// construct file keys
$inputname = array_keys($_FILES)[0];
$filename  = $_FILES[$inputname]['name'][0];
$filepath  = $_FILES[$inputname]['tmp_name'][0];

// let's proceed a document addition
$response = $api_client->post('Document/', [
   'headers' => [
      'Session-Token' => $session_token
   ],
   'multipart' => [
      [
         'name'     => 'uploadManifest',
         'contents' => json_encode([
            'input' => [
               'name'       => $_POST['document_name'],
               '_filename'  => [$filename],
            ]
         ])
      ],[
         'name'     => $inputname.'[]',
         'contents' => file_get_contents($filepath),
         'filename' => $filename
      ]
]]);
$document_return = json_decode( (string) $response->getBody(), true);
echo "status code: ".$response->getStatusCode()."<br>";
var_dump($document_return);

