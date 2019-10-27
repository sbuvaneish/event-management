<?php

require 'database.php';

// $file = fopen("users.csv","r");
// $data = fgetcsv($file);


// while (!feof($file)) {
// 	$data = fgetcsv($file);
	
// 	$insert_query = "INSERT INTO users (id, name, email, password, phone, street_address, city, state, zip) VALUES (:id, :name, :email, :password, :phone, :street, :city, :state, :zip)";
	
// 	$stmt = $conn->prepare($insert_query);

// 	$stmt->bindParam(':id', $data[0]);
// 	$stmt->bindParam(':name', $data[1]);
// 	$stmt->bindParam(':email', $data[2]);
// 	$stmt->bindParam(':password', password_hash($data[3], PASSWORD_BCRYPT));
// 	$stmt->bindParam(':phone', $data[4]);
// 	$stmt->bindParam(':street', $data[5]);
// 	$stmt->bindParam(':city', $data[6]);
// 	$stmt->bindParam(':state', $data[7]);
// 	$stmt->bindParam(':zip', $data[8]);

// 	$stmt->execute();


// }


// $file = fopen("events.csv", "r");
// $data = fgetcsv($file);

// while(!feof($file)) {

// 	$data = fgetcsv($file);

// 	$insert_query = "INSERT INTO events(id, name, description, user_id, street_address, city, state, zip, capacity, date, deadline) VALUES (:id, :name, :description, :user_id, :street, :city, :state, :zip, :capacity, :date, :deadline)";

// 	$stmt = $conn->prepare($insert_query);

// 	$stmt->bindParam(':id', $data[0]);
// 	$stmt->bindParam(':name', $data[1]);
// 	$stmt->bindParam(':description', $data[2]);
// 	$stmt->bindParam(':user_id', $data[3]);
// 	$stmt->bindParam(':street', $data[4]);
// 	$stmt->bindParam(':city', $data[5]);
// 	$stmt->bindParam(':state', $data[6]);
// 	$stmt->bindParam(':zip', $data[7]);
// 	$stmt->bindParam(':capacity', $data[8]);
// 	$stmt->bindParam(':date', $data[9]);
// 	$stmt->bindParam(':deadline', $data[10]);


// 	$stmt->execute();


// }

// $file = fopen("registrations.csv","r");
// $data = fgetcsv($file);


// while(!feof($file)) {

// 	$data = fgetcsv($file);

// 	$insert_query = "INSERT INTO registrations(user_id, event_id) VALUES(:user_id, :event_id)";

// 	$stmt = $conn->prepare($insert_query);

// 	$stmt->bindParam(':user_id', $data[0]);
// 	$stmt->bindParam(':event_id', $data[1]);

// 	$stmt->execute();

// }



$file = fopen("friends.csv","r");
$data = fgetcsv($file);


while(!feof($file)) {

	$data = fgetcsv($file);

	$insert_query = "INSERT INTO friends(user_1, user_2) VALUES(:user_1, :user_2)";

	$stmt = $conn->prepare($insert_query);

	$stmt->bindParam(':user_1', $data[0]);
	$stmt->bindParam(':user_2', $data[1]);

	$stmt->execute();

}


fclose($file);

