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

$base_uri = trim(GLPI_URL, '/');

// define api client
$api_client = new GuzzleHttp\Client(['base_uri' => "$base_uri/apirest.php/"]);

// connect to api
$response = $api_client->get("initSession/", [
   'headers' => [
      'App-Token' => API_APP_TOKEN,
   ],
   'auth' => [API_USER, API_PASSWORD],
]);
if ($response->getStatusCode() != 200
    || !$session_token = json_decode( (string) $response->getBody(), true)['session_token']) {
   throw new Exception("Cannot connect to api, check your config.inc.php file");
}

// check input
if (!isset($_POST['document_name'])) {
   die("No document name provided");
}
if (count($_FILES) == 0) {
   die("No file provided");
}
// we may need more file control here, but for the example purpose, i skip

// construct file keys
$docname   = $_POST['document_name'];
$inputname = array_keys($_FILES)[0];
$filename  = $_FILES[$inputname]['name'][0];
$filepath  = $_FILES[$inputname]['tmp_name'][0];

// let's proceed a document addition
$response = $api_client->post('Document/', [
   'headers' => [
      'App-Token'     => API_APP_TOKEN,
      'Session-Token' => $session_token,
   ],
   'multipart' => [
      // the document part
      [
         'name'     => 'uploadManifest',
         'contents' => json_encode([
            'input' => [
               'name'       => $docname,
               '_filename'  => [$filename],
            ]
         ])
      ],
      // the FILE part
      [
         'name'     => $inputname.'[]',
         'contents' => file_get_contents($filepath),
         'filename' => $filename
      ]
]]);
$document_return = json_decode( (string) $response->getBody(), true);

// display return
if ($response->getStatusCode() != 201) {
   throw new Exception("Error when sending file/document to api");
}

Echo "Document created:
      <a target='_blank'
         href='$base_uri/front/document.form.php?id=".$document_return['id']."'>
         $docname
      </a>";
echo "<pre>";
var_dump(json_decode( (string) $response->getBody()));
echo "</pre>";
