# File Upload with GLPI API

This reposity aims to provide a working example of an upload through the Rest API of [GLPI](https://github.com/glpi-project/glpi).
We are using [PHP Guzzle library](http://docs.guzzlephp.org/en/latest/) to send http queries.
See [upload.php](upload.php) code for details.

## Prerequisites

* GLPI 9.2 (with api enabled)
* PHP >= 5.6

## Installation

* Clone this repository on your computer.
* Move the directory into your http serveur vhost
* Run ```composer install``` to pull dependencies
* Create a config.inc.php from config.inc.example file and fill parameters with your glpi instance data
* With your browser, got to the directory of this project and test the file upload and document creation.

## Additional Curl example

```
$ curl -X POST \
-H 'Content-Type: multipart/form-data' \
-H "Session-Token: 83af7e620c83a50a18d3eac2f6ed05a3ca0bea62" \
-H "App-Token: f7g3csp8mgatg5ebc5elnazakw20i9fyev1qopya7" \
-F 'uploadManifest={"input": {"name": "Uploaded document", "_filename" : ["file.txt"]}};type=application/json' \
-F 'filename[0]=@file.txt' \
'http://path/to/glpi/apirest.php/Document/'

< 201 OK
< Location: http://path/to/glpi/api/Document/1
< {"id": 1, "message": "Document move succeeded.", "upload_result": {...}}
```
