<?php

session_start();

if(!isset($_SESSION['user_id'])) {
	header("Location: /login.php");
}

unset($_SESSION['event_id']);

require 'database.php';

$deletion_query = "DELETE FROM friends WHERE user_1 = :this_id AND user_2 = :friend_id";

$records = $conn->prepare($deletion_query);
$records->bindParam(':this_id', $_SESSION['user_id']);
$records->bindParam(':friend_id', $_GET['friend_id']);

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