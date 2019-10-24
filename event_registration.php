<?php

session_start();

if(!isset($_SESSION['user_id'])) {
	header("Location: /login.php");
}

unset($_SESSION['event_id']);

require 'database.php';


function validateRecord($record) {

	$now = new DateTime();
	$time = $now->format('Y-m-d H:i:s');

	return ((($time <= $record['deadline']) AND ($record['count'] < $record['capacity'])) ? 1 : 0);

} 


$check_query = "SELECT deadline, count, capacity FROM events WHERE id = :event_id";
$records = $conn->prepare($check_query);
$records->bindParam(':event_id', $_GET['event_id']);
$records->execute();
$result = $records->fetchAll(PDO::FETCH_ASSOC)[0];



if(validateRecord($result) === 1) {

	$registration_query = "INSERT INTO registrations(user_id, event_id) VALUES (:user_id, :event_id)";
	$records = $conn->prepare($registration_query);
	$records->bindParam(':event_id', $_GET['event_id']);
	$records->bindParam(':user_id', $_SESSION['user_id']);

	$message = "";

	if($records->execute()) {
		
		$updation_query = "UPDATE events SET count = count + 1 WHERE id = :event_id";
		$records = $conn->prepare($updation_query);
		$records->bindParam(':event_id', $_GET['event_id']);

		if($records->execute()) {
			$message = "Successful registration..";
		}
		else {
			$message = "Issue in registration..";
		}

	}
	else {
		$message = "Issue in registration..";
	}

}
else {
	$message = "Issue in registration..";
}




echo $message;
sleep(2);

echo "<script>window.close();</script>";


?>