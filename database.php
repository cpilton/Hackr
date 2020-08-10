<?php

$host = $_ENV["SQL_HOST"]; // MySQL host name eg. shareddb-p.hosting.stackcp.net
$user = $_ENV["SQL_USER"]; // MySQL user. eg. root ( if your on localserver)
$password = $_ENV["SQL_PASSWORD"]; // MySQL user password  (if password is not set for your root user then keep it
// empty )
$database = $_ENV["SQL_DATABASE"]; // MySQL Database name

$DBH = new PDO("mysql:host=$host;dbname=$database", $user, $password);
 
// Connect to MySQL Database
$con = new mysqli($host, $user, $password, $database);
// Callum Pilton