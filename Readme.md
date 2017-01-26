# File Upload wit GLPI API example

This reposity aims to provide a working example of an upload through the Rest API of [GLPI](https://github.com/glpi-project/glpi).
We are using [PHP Guzzle library](http://docs.guzzlephp.org/en/latest/) to send http queries.
See [upload.php](upload.php) code for details.

## Installation

* Clone this repository on your computer.
* Move the directory into your http serveur vhost
* Run ```composer install``` to pull dependencies
* Create a config.inc.php from config.inc.example file and fill parameters with your glpi instance data
* With your browser, got to the directory of this project and test the file upload and document creation.