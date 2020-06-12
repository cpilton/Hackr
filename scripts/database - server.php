<?php


error_reporting(0);

$host = "localhost"; // MySQL host name eg. localhost
$user = "piltonca"; // MySQL user. eg. root ( if your on localserver)
$password = "google31"; // MySQL user password  (if password is not set for your root user then keep it empty )
$database = "piltonca_hackerrevolution"; // MySQL Database name

$DBH = new PDO("mysql:host=$host;dbname=$database", $user, $password);
 
// Connect to MySQL Database
$con = new mysqli($host, $user, $password, $database);
// Callum Pilton