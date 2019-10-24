<?php

session_start();

if(!isset($_SESSION['user_id'])) {
	header("Location: /login.php");
}

require 'database.php';

$message = '';

$user_fields = ['event-name', 'description', 'street', 'city', 'state', 'zip', 'capacity', 'event-date', 'event-time', 'deadline-date', 'deadline-time'];

if(isset($_POST['submit'])) {


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


	if(empty($message)) {

		$date_val = $_POST['event-date'] . ' ' . $_POST['event-time'];
		$deadline_val = $_POST['deadline-date'] . ' ' . $_POST['deadline-time'];
		
		$insert_query = "INSERT INTO events(name, description, user_id, street_address, city, state, zip, capacity, date, deadline) VALUES (:name, :description, :user_id, :street, :city, :state, :zip, :capacity, :date, :deadline)";
		$stmt = $conn->prepare($insert_query);
	
		$stmt->bindParam(':name', $_POST['event-name']);
		$stmt->bindParam(':description', $_POST['description']);
		$stmt->bindParam(':user_id', $_SESSION['user_id']);
		$stmt->bindParam(':street', $_POST['street']);
		$stmt->bindParam(':city', $_POST['city']);
		$stmt->bindParam(':state', $_POST['state']);
		$stmt->bindParam(':zip', $_POST['zip']);
		$stmt->bindParam(':capacity', $_POST['capacity']);	
		$stmt->bindParam(':date', $date_val);
		$stmt->bindParam(':deadline', $deadline_val);

	
		if( $stmt->execute() ):
			$message = 'Successfully created new event';
		else:
			$message = 'Sorry there must have been an issue creating your event';
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
	      <legend class="">Add or Edit events</legend>
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

	    		 ?>

	    	</p>
	    </div>

	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="event-name">Event Name</label>
	      <div class="controls">
	        <input type="text" id="event-name" name="event-name" placeholder="" class="input-xlarge" value="<?= $_POST['event-name'] ?>">
	      </div>
	    </div>


	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="description">Description</label>
	      <div class="controls">
	        <textarea id="description" name="description" placeholder="" class="input-xlarge"><?= $_POST['description'] ?></textarea>
	      </div>
	    </div>


	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="street">Street Address</label>
	      <div class="controls">
	        <input type="text" id="street" name="street" placeholder="" class="input-xlarge" value="<?= $_POST['street'] ?>">
	      </div>
	    </div>

	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="city">City</label>
	      <div class="controls">
	        <input type="text" id="city" name="city" placeholder="" class="input-xlarge" value="<?= $_POST['city'] ?>">
	      </div>
	    </div>

	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="state">State</label>
	      <div class="controls">
	        <input type="text" id="state" name="state" placeholder="" class="input-xlarge" value="<?= $_POST['state'] ?>">
	      </div>
	    </div>

	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="zip">Zip</label>
	      <div class="controls">
	        <input type="text" id="zip" name="zip" placeholder="" class="input-xlarge" value="<?= $_POST['zip'] ?>">
	      </div>
	    </div>

	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="capacity">Capacity</label>
	      <div class="controls">
	        <input type="text" id="capacity" name="capacity" placeholder="" class="input-xlarge" value="<?= $_POST['capacity'] ?>">
	      </div>
	    </div>


	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label">Event Date-Time</label>
	      <div class="controls">
	        <input type="text" id="event-date" name="event-date" placeholder="" class="input-xlarge" value="<?= $_POST['event-date'] ?>">
	        <p class="help-block">YYYY-MM-DD</p>
	        <input type="text" id="event-time" name="event-time" placeholder="" class="input-xlarge" value="<?= $_POST['event-time'] ?>">
	        <p class="help-block">HH:MM:SS</p>
	      </div>
	    </div>

	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label">Deadline Date-Time</label>
	      <div class="controls">
	        <input type="text" id="deadline-date" name="deadline-date" placeholder="" class="input-xlarge" value="<?= $_POST['deadline-date'] ?>">
	        <p class="help-block">YYYY-MM-DD</p>
	        <input type="text" id="deadline-time" name="deadline-time" placeholder="" class="input-xlarge" value="<?= $_POST['deadline-time'] ?>">
	        <p class="help-block">HH:MM:SS</p>
	      </div>
	    </div>

	 
	    <div class="control-group">
	      <!-- Button -->
	      <div class="controls">
	        <button class="btn btn-success" name="submit">Update</button>
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