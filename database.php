<?php

$server = 'us-cdbr-iron-east-05.cleardb.net';
$username = 'b607abb5e11746';
$password = 'fe67bffa';
$database = 'heroku_02f84be589b00af';

try{
	$conn = new PDO("mysql:host=$server;dbname=$database;", $username, $password);
} catch(PDOException $e){
	die( "Connection failed: " . $e->getMessage());
}