<?php

session_start();

if(!isset($_SESSION['user_id'])) {
	header("Location: /login.php");
}

unset($_SESSION['event_id']);

require 'database.php';

$insertion_query = "INSERT INTO friends VALUES(:this_id, :friend_id)";

$records = $conn->prepare($insertion_query);
$records->bindParam(':this_id', $_SESSION['user_id']);
$records->bindParam(':friend_id', $_GET['friend_id']);

$message = "";

if($records->execute()) {
	$message = "Successfully friended..";
}
else {
	$message = "Issue in making friends..";
}

echo $message;
sleep(2);

echo "<script>window.close();</script>";


?>