
<?php

require 'database.php';

$message = '';

$user_fields = ['email', 'fullname', 'password', 'password_confirm', 'phone', 'street', 'city', 'state', 'zip'];

if(isset($_POST['submit'])) {


	foreach ($user_fields as $field) {
		if(empty($_POST[$field])) {
			$message = 'Enter all the fields';
			break;
		}
	}

	if(empty($message)) {

		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$message = "Invalid email";
		}
		else if(strlen($_POST['password']) < 4) {
			$message = "Password must be atleast 4 characters long";
		}
		else if(strcmp($_POST['password'], $_POST['password_confirm']) != 0) {
			$message = "Passwords do not match";
		}
		else if(!preg_match('/^[0-9]{10}$/', $_POST['phone'])) {
			$message = "Phone number not valid";
		}
		else if(!preg_match('/^[0-9]{5}$/', $_POST['zip'])) {
			$message = "Zipcode not valid";
		}

	}


	if(empty($message)) {

		$check_query = "SELECT * FROM users where email = :email";
		$records = $conn->prepare($check_query);
		$records->bindParam(':email', $_POST['email']);
		$records->execute();
		$results = $records->fetchAll(PDO::FETCH_ASSOC);
		
		if(count($results) == 0)
		{
			$insert_query = "INSERT INTO users (name, email, password, phone, street_address, city, state, zip) VALUES (:name, :email, :password, :phone, :street, :city, :state, :zip)";
			$stmt = $conn->prepare($insert_query);
		
			$stmt->bindParam(':name', $_POST['fullname']);
			$stmt->bindParam(':email', $_POST['email']);
			$stmt->bindParam(':password', password_hash($_POST['password'], PASSWORD_BCRYPT));
			$stmt->bindParam(':phone', $_POST['phone']);
			$stmt->bindParam(':street', $_POST['street']);
			$stmt->bindParam(':city', $_POST['city']);
			$stmt->bindParam(':state', $_POST['state']);
			$stmt->bindParam(':zip', $_POST['zip']);	
		
			if( $stmt->execute() ):
				$message = 'Successfully created new user';
			else:
				$message = 'Sorry there must have been an issue creating your account';
			endif;
		}
		else
		{
			$message = 'User already exists';
		}

	}


}


?>





<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<body background="">
	<form class="form-horizontal" action='register.php' method="POST">
	  <fieldset>
	    <div id="legend">
	      <legend class="">Register</legend>
	    </div>
	    
	    <div>
	    	<p style="color:blue">
	    		 <?= $message ?>
	    	</p>
	    </div>
	 
	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="email">E-mail</label>
	      <div class="controls">
	        <input type="text" id="email" name="email" placeholder="" class="input-xlarge" value="<?= $_POST['email'] ?>">
	        <p class="help-block">Please provide your E-mail</p>
	      </div>
	    </div>

	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="fullname">Name</label>
	      <div class="controls">
	        <input type="text" id="fullname" name="fullname" placeholder="" class="input-xlarge" value="<?= $_POST['fullname'] ?>">
	        <p class="help-block">Please provide your full name</p>
	      </div>
	    </div>
	 
	    <div class="control-group">
	      <!-- Password-->
	      <label class="control-label" for="password">Password</label>
	      <div class="controls">
	        <input type="password" id="password" name="password" placeholder="" class="input-xlarge" value="<?= $_POST['password'] ?>">
	        <p class="help-block">Password should be at least 4 characters</p>
	      </div>
	    </div>
	 
	    <div class="control-group">
	      <!-- Password -->
	      <label class="control-label"  for="password_confirm">Password (Confirm)</label>
	      <div class="controls">
	        <input type="password" id="password_confirm" name="password_confirm" placeholder="" class="input-xlarge" value="<?= $_POST['password_confirm'] ?>">
	        <p class="help-block">Please confirm password</p>
	      </div>
	    </div>

	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="phone">Phone</label>
	      <div class="controls">
	        <input type="text" id="phone" name="phone" placeholder="" class="input-xlarge" value="<?= $_POST['phone'] ?>">
	        <p class="help-block">Please provide your mobile number</p>
	      </div>
	    </div>

	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="street">Street Address</label>
	      <div class="controls">
	        <input type="text" id="street" name="street" placeholder="" class="input-xlarge" value="<?= $_POST['street'] ?>">
	        <p class="help-block">Please provide your street address</p>
	      </div>
	    </div>

	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="city">City</label>
	      <div class="controls">
	        <input type="text" id="city" name="city" placeholder="" class="input-xlarge" value="<?= $_POST['city'] ?>">
	        <p class="help-block">Please provide the name of your city</p>
	      </div>
	    </div>

	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="state">State</label>
	      <div class="controls">
	        <input type="text" id="state" name="state" placeholder="" class="input-xlarge" value="<?= $_POST['state'] ?>">
	        <p class="help-block">Please provide the name of your state</p>
	      </div>
	    </div>

	    <div class="control-group">
	      <!-- E-mail -->
	      <label class="control-label" for="zip">Zip</label>
	      <div class="controls">
	        <input type="text" id="zip" name="zip" placeholder="" class="input-xlarge" value="<?= $_POST['zip'] ?>">
	        <p class="help-block">Please provide your zipcode</p>
	      </div>
	    </div>
	 
	    <div class="control-group">
	      <!-- Button -->
	      <div class="controls">
	        <button class="btn btn-success" name="submit">Register</button>
	      </div>
	    </div>
	    
	    
	    <div class="control-group">
	    	<div class="controls">
	    		<a href="login.php">Go to login</a>
	    	</div>
	    </div>
	    
	  </fieldset>
	</form>	
</body>