<?php

$server = 'localhost:3306';
$username = 'root';
$password = 'password';
$database = 'event_management';

try{
	$conn = new PDO("mysql:host=$server;dbname=$database;", $username, $password);
} catch(PDOException $e){
	die( "Connection failed: " . $e->getMessage());
}