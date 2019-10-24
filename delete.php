<?php

session_start();

if(!isset($_SESSION['user_id'])) {
	header("Location: /login.php");
}

unset($_SESSION['event_id']);

require 'database.php';

$deletion_query = "DELETE FROM events WHERE id = :event_id";

$records = $conn->prepare($deletion_query);
$records->bindParam(':event_id', $_GET['event_id']);

$message = "";

if($records->execute()) {
	$message = "Successful deletion..";
}
else {
	$message = "Issue in deletion..";
}

echo $message;
sleep(2);

echo "<script>window.close();</script>";

?>