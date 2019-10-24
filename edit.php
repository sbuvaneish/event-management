<?php

session_start();

if(!isset($_SESSION['user_id'])) {
	header("Location: /login.php");
}

require 'database.php';

$message = '';

$user_fields = ['event-name', 'description', 'street', 'city', 'state', 'zip', 'capacity', 'event-date', 'event-time', 'deadline-date', 'deadline-time'];


function getFieldValue($field, $record) {

	$value = $_POST[$field];

	if(isset($_GET['event_id'])) {

		if($field === 'event-name') {
			$value = $record['name'];
		}
		else if($field === 'street') {
			$value = $record['street_address'];
		}
		else if($field === 'event-date') {
			$value = explode(" ", $record['date'])[0];
		} 
		else if($field === 'event-time') {
			$value = explode(" ", $record['date'])[1];
		}
		else if($field === 'deadline-date') {
			$value = explode(" ", $record['deadline'])[0];
		}
		else if($field === 'deadline-time') {
			$value = explode(" ", $record['deadline'])[1];
		}
		else {
			$value = $record[$field];
		}

	}
	
	return $value;

}


function validate() {

	global $message, $user_fields;

	foreach ($user_fields as $field) {
		if(empty($_POST[$field])) {
			$message = 'Enter all the fields';
			break;
		}
	}

	if(empty($message)) {

		if(!preg_match('/^[0-9]{5}$/', $_POST['zip'])) {
			$message = "Zipcode not valid";
		}
		else if(!preg_match('/^[0-9]+$/', $_POST['capacity'])) {
			$message = "Enter valid capacity";
		}
		else if(!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $_POST['event-date'])) {
			$message = "Enter valid event date";
		}
		else if(!preg_match('/^(?:[01]\d|2[0123]):(?:[012345]\d):(?:[012345]\d)$/', $_POST['event-time'])) {
			$message = "Enter valid event time";
		}
		else if(!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $_POST['deadline-date'])) {
			$message = "Enter valid deadline date";
		}
		else if(!preg_match('/^(?:[01]\d|2[0123]):(?:[012345]\d):(?:[012345]\d)$/', $_POST['deadline-time'])) {
			$message = "Enter valid deadline time";
		}

	}

	if(isset($_POST['count'])) {
		if(intval($_POST['capacity']) < intval($_POST['count'])) {
			$message = "Capacity cannot be less than current count";
		}
	}

}


if(isset($_GET['event_id'])) {

	$_SESSION['event_id'] = $_GET['event_id'];

	$event_query = "SELECT * FROM events WHERE id = :id";
	$stmt = $conn->prepare($event_query);
	$stmt->bindParam(':id', $_GET['event_id']);

	if($stmt->execute()) {
		$record = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
	}
	else {
		$message = "Error in receiving event information";
	}

}



if(isset($_POST['submit'])) {

	validate();


	if(empty($message)) {

		$date_val = $_POST['event-date'] . ' ' . $_POST['event-time'];
		$deadline_val = $_POST['deadline-date'] . ' ' . $_POST['deadline-time'];
		
		$insert_query = "INSERT INTO events(name, description, user_id, street_address, city, state, zip, capacity, date, deadline) VALUES (:name, :description, :user_id, :street, :city, :state, :zip, :capacity, :date, :deadline)";

		$update_query = "UPDATE events SET name = :name, description = :description, street_address = :street, city = :city, state = :state, zip = :zip, capacity = :capacity, date = :date, deadline = :deadline WHERE id = :id";

		if(isset($_SESSION['event_id'])) {
			$stmt = $conn->prepare($update_query);
			$stmt->bindParam(':id', $_SESSION['event_id']);
		}
		else {
			$stmt = $conn->prepare($insert_query);
			$stmt->bindParam(':user_id', $_SESSION['user_id']);
		}
	
		$stmt->bindParam(':name', $_POST['event-name']);
		$stmt->bindParam(':description', $_POST['description']);
		$stmt->bindParam(':street', $_POST['street']);
		$stmt->bindParam(':city', $_POST['city']);
		$stmt->bindParam(':state', $_POST['state']);
		$stmt->bindParam(':zip', $_POST['zip']);
		$stmt->bindParam(':capacity', $_POST['capacity']);	
		$stmt->bindParam(':date', $date_val);
		$stmt->bindParam(':deadline', $deadline_val);

	
		if( $stmt->execute() ):
			if(isset($_SESSION['event_id'])) {
				$message = 'Successfully updated your event';
			}
			else {
				$message = 'Successfully created new event';
			}
		else:
			$message = 'Sorry there must have been an issue';
		endif;

	}


}

?>



<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<body background="">
	<form class="form-horizontal" action='edit.php' method="POST">
	  <fieldset>
	    <div id="legend">
	      <legend class="">Add/View/Edit events</legend>
	    </div>

	    <div align="right" class="container">
			User: <?=$_SESSION['email']?>
		</div>
	    
	    <div>
	    	<p style="color:blue">
	    		 <?= $message ?>
	    		 <?php 

	    		 if($message === "Successfully created new event") {
	    		 	echo "<br>" . "Redirecting...";
					echo "<script>setTimeout(\"location.href = 'http://localhost:8080/main.php';\",1500);</script>";
	    		 }
	    		 else if($message === "Successfully updated your event") {
	    		 	unset($_SESSION['event_id']);
	    		 	echo "<br>" . "Redirecting...";
					echo "<script>setTimeout(\"location.href = 'http://localhost:8080/main.php';\",1500);</script>";
	    		 }

	    		 ?>

	    	</p>
	    </div>

	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="event-name">Event Name</label>
	      <div class="controls">
	        <input type="text" id="event-name" name="event-name" placeholder="" class="input-xlarge" value="<?= getFieldValue('event-name', $record) ?>">
	      </div>
	    </div>


	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="description">Description</label>
	      <div class="controls">
	        <textarea id="description" name="description" placeholder="" class="input-xlarge"><?= getFieldValue('description', $record) ?></textarea>
	      </div>
	    </div>


	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="street">Street Address</label>
	      <div class="controls">
	        <input type="text" id="street" name="street" placeholder="" class="input-xlarge" value="<?= getFieldValue('street', $record) ?>">
	      </div>
	    </div>

	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="city">City</label>
	      <div class="controls">
	        <input type="text" id="city" name="city" placeholder="" class="input-xlarge" value="<?= getFieldValue('city', $record) ?>">
	      </div>
	    </div>

	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="state">State</label>
	      <div class="controls">
	        <input type="text" id="state" name="state" placeholder="" class="input-xlarge" value="<?= getFieldValue('state', $record) ?>">
	      </div>
	    </div>

	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="zip">Zip</label>
	      <div class="controls">
	        <input type="text" id="zip" name="zip" placeholder="" class="input-xlarge" value="<?= getFieldValue('zip', $record) ?>">
	      </div>
	    </div>

	    
    	<div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="count">Count</label>
	      <div class="controls">
	        <input type="text" id="count" name="count" placeholder="" class="input-xlarge" value="<?= $record['count'] ?>" disabled>
	      </div>
	    </div>

	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="capacity">Capacity</label>
	      <div class="controls">
	        <input type="text" id="capacity" name="capacity" placeholder="" class="input-xlarge" value="<?= getFieldValue('capacity', $record) ?>">
	      </div>
	    </div>


	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label">Event Date-Time</label>
	      <div class="controls">
	        <input type="text" id="event-date" name="event-date" placeholder="" class="input-xlarge" value="<?= getFieldValue('event-date', $record) ?>">
	        <p class="help-block">YYYY-MM-DD</p>
	        <input type="text" id="event-time" name="event-time" placeholder="" class="input-xlarge" value="<?= getFieldValue('event-time', $record) ?>">
	        <p class="help-block">HH:MM:SS</p>
	      </div>
	    </div>

	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label">Deadline Date-Time</label>
	      <div class="controls">
	        <input type="text" id="deadline-date" name="deadline-date" placeholder="" class="input-xlarge" value="<?= getFieldValue('deadline-date', $record) ?>">
	        <p class="help-block">YYYY-MM-DD</p>
	        <input type="text" id="deadline-time" name="deadline-time" placeholder="" class="input-xlarge" value="<?= getFieldValue('deadline-time', $record) ?>">
	        <p class="help-block">HH:MM:SS</p>
	      </div>
	    </div>

	 
	    <div class="control-group">
	      <!-- Button -->
	      <div class="controls">
	        <button class="btn btn-success" name="submit" <?php if(isset($_GET['view_only'])) {echo "disabled";} ?>>Update</button>
	      </div>
	    </div>
	    
	    
	    <div class="control-group">
	    	<div class="controls">
	    		<a href="main.php">Back to Main Page</a>
	    	</div>
	    </div>
	    
	  </fieldset>
	</form>	
</body>