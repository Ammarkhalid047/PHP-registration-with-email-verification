<?php require('includes/config.php');

//if logged in redirect to members page
if( $user->is_logged_in() ){ header('Location: #'); exit(); }
//if form has been submitted process it
if(isset($_POST['submit'])){

    if (!isset($_POST['username'])) $error[] = "Please fill out all fields";
    if (!isset($_POST['email'])) $error[] = "Please fill out all fields";
    if (!isset($_POST['password'])) $error[] = "Please fill out all fields";

	$username = $_POST['username'];

	//very basic validation
	if(!$user->isValidUsername($username)){
		$error[] = 'Usernames must be at least 3 Alphanumeric characters';
	} else {
		$stmt = $db->prepare('SELECT username FROM members WHERE username = :username');
		$stmt->execute(array(':username' => $username));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['username'])){
			$error[] = 'Username provided is already in use.';
		}

	}

	if(strlen($_POST['password']) < 3){
		$error[] = 'Password is too short.';
	}

	if(strlen($_POST['passwordConfirm']) < 3){
		$error[] = 'Confirm password is too short.';
	}

	if($_POST['password'] != $_POST['passwordConfirm']){
		$error[] = 'Passwords do not match.';
	}

	//email validation
	$email = htmlspecialchars_decode($_POST['email'], ENT_QUOTES);
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
	    $error[] = 'Please enter a valid email address';
	} else {
		$stmt = $db->prepare('SELECT email FROM members WHERE email = :email');
		$stmt->execute(array(':email' => $email));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!empty($row['email'])){
			$error[] = 'Email provided is already in use.';
		}

	}


	//if no errors have been created carry on
	if(!isset($error)){

		//hash the password
		$hashedpassword = md5($_POST['password']);
        echo $hashedpassword;
		//create the activasion code
		$activasion = md5(uniqid(rand(),true));

		try {

			//insert into database with a prepared statement
			$stmt = $db->prepare('INSERT INTO members (username,password,email,active) VALUES (:username, :password, :email, :active)');
			$stmt->execute(array(
				':username' => $username,
				':password' => $hashedpassword,
				':email' => $email,
				':active' => $activasion
			));
			$id = $db->lastInsertId('memberID');
            $to = $_POST['email'];
            
			//send email
			
		$headers = 'From:info@prime-websol.com' . "\r\n";
		$headers .= "Content-type: text/html\r\n";
            mail("$to","Registration Confirmation","<p>Thank you for registering at demo site.</p>
			<p>To activate your account, please click on this link: <a href='".DIR."activate.php?x=$id&y=$activasion'>".DIR."activate.php?x=$id&y=$activasion</a></p>
			<p>Regards Site Admin</p>",$headers);
            
            
            $fname = $_POST['fname'];
            $industry = $_POST['industry'];
            $research = $_POST['research'];
            $free = $_POST['free'];
            $school =$_POST['school'];
            $firstuni = $_POST['firstuni'];
            $ethnicity = $_POST['ethnicity'];
            $sex = $_POST['sex'];
            $achievement = $_POST['achievement'];
            $email = $_POST['email'];
            
            
            
            
            mail("sylvester.lewis@icloud.com", "A new user has registered to your site", "Name: $fname ,<br> Which industry are you most interested in? $industry ,<br> Have you already conducted extensive desktop research into your chosen industry and do you have several substantive questions to ask a prospective mentor? $research ,<br> During your education were you at anytime eligible for free school meals? $free ,<br> Did you attend a state or private school between the ages 11 - 18? $school ,<br> Are you the first generation in your family to attend university? $firstuni ,<br> What is your ethnicity? $ethnicity ,<br> What is your sex? $sex ,<br> What achievement are you most proud of? $achievement ,<br> Email: $email .", $headers);
            
			//redirect to index page
			header('Location: signup.php?action=joined');
			exit;

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}

	}

}

//define page title
$title = 'Demo';

//include header template
require('layout/header.php');
?>


<div class="container" style="margin-top:100px;">

	<div class="row">

	    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
			<form role="form" method="post" action="" autocomplete="off">
				<h2>Please Sign Up</h2>
				<p>Already a member? <a href='login.php'>Login</a></p>
				<hr>

				<?php
				//check for any errors
				if(isset($error)){
					foreach($error as $error){
						echo '<p class="bg-danger">'.$error.'</p>';
					}
				}

				//if action is joined show sucess
				if(isset($_GET['action']) && $_GET['action'] == 'joined'){
					echo "<h2 class='bg-success'>Registration successful, please check your email to activate your account.</h2>";
				}
				?>
                
                
                <!========ROW1=====!>
                
                
                <div class="row">
					<div class="col-xs-12 col-sm-12 col-md-6">
						<div class="form-group">
							<input type="text" name="fname" id="fname" class="form-control input-lg" placeholder="First Name" tabindex="3" required>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6">
						<div class="form-group">
							<input type="text" name="lname" id="lname" class="form-control input-lg" placeholder="Last Name" tabindex="4" required>
						</div>
					</div>
				</div>
                
                <!========ROW2=====!>
                
                
                <div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="form-group">
						<label style="color:gray"> Which industry are you most interested in?</label>    
                        <select class="form-select" aria-label="Default select example" style="color:gray" name="industry" required>
                          <option selected>Select Industry</option>
                          <option value="Investment Banking">Investment Banking</option>
                          <option value="Law">Law</option>
                          <option value="Stratergy Consulting">Stratergy Consulting</option>
                          <option value="Technology">Technology</option>
                          <option value="Other">Other</option>
                        </select>
						</div>
					</div>
				</div>
                
                                <!========ROW2=====!>
                
                
                <div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="form-group">
						<label style="color:gray"> Have you already conducted extensive desktop research into your chosen industry and do you have several substantive questions to ask a prospective mentor?</label>    
                            <select style="color:gray" class="form-select" aria-label="Default select example" name="research" required>
                              <option selected>Select Option</option>
                              <option value="Yes">Yes</option>
                              <option value="No">No</option>
                            </select>
						</div>
					</div>
				</div>
                
                <!========ROW2=====!>
                
                
                               <div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="form-group">
            <label style="color:gray"> During your education were you at anytime eligible for free school meals? </label>    
            <select style="color:gray" class="form-select" aria-label="Default select example" name="free" required>
              <option selected>Select Option</option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
                        </div>
                   </div>
            </div>
                
                 <!========ROW2=====!>
                
                
                               <div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="form-group">
            <label style="color:gray"> Did you attend a state or private school between the ages 11 - 18? </label>    
            <select style="color:gray" class="form-select" aria-label="Default select example" name="school" required>
              <option selected>Select Option</option>
              <option value="state">State</option>
              <option value="private">Private</option>
              <option value="both">Both</option>
            </select>
                        </div>
                   </div>
            </div>
                
                
                
                
                </div>
            
            
            
            
            
            <div class="col-md-6">
                
                                 <!========ROW2=====!>
                
                
                               <div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="form-group">
            <label style="color:gray"> Are you the first generation in your family to attend university? </label>    
            <select style="color:gray" class="form-select" aria-label="Default select example" name="firstuni" required>
              <option selected>Select Option</option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
                        </div>
                   </div>
            </div>
                
                
                 <!========ROW2=====!>
                
                
                               <div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="form-group">
            <label style="color:gray"> What is your ethnicity? </label>    
            <select style="color:gray" class="form-select" aria-label="Default select example" name="ethnicity" required>
              <option selected>Select Option</option>
              <option value="Black / Black British African">Black / Black British African</option>
              <option value="Black / Black British Caribbean">Black / Black British Caribbean</option>
              <option value="Any other Black / African / Caribbean background">Any other Black / African / Caribbean background</option>
              <option value="White English / Welsh / Scottish / Northern Irish / British">White English / Welsh / Scottish / Northern Irish / British</option>
              <option value="White Irish">White Irish</option>
              <option value="White English / Welsh / Scottish / Northern Irish / British">White English / Welsh / Scottish / Northern Irish / British</option>
              <option value="White Gypsy or Irish Traveller">White Gypsy or Irish Traveller</option>
              <option value="Other White Background">Other White Background</option>
              <option value="Mixed White and Black Caribbean">Mixed White and Black Caribbean</option>
              <option value="Mixed White and Black African">Mixed White and Black African</option>
              <option value="Mixed White and Asian">Mixed White and Asian</option>
              <option value="Any other Mixed / Multiple ethnic background">Any other Mixed / Multiple ethnic background</option>
              <option value="Asian / Asian British Indian">Asian / Asian British Indian</option>
              <option value="Asian / Asian British Pakistani">Asian / Asian British Pakistani</option>
              <option value="Mixed White and Black Bangladeshi">Mixed White and Black Bangladeshi</option>
              <option value="Mixed White and Black Chinese">Mixed White and Black Chinese</option>
              <option value="Any other Asian background">Any other Asian background</option>
              <option value="Arab / Arab British">Arab / Arab British</option>
              <option value="Other">Other</option>
            </select>
                        </div>
                   </div>
            </div>
                
                
                <!========ROW2=====!>
                
                
                               <div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="form-group">
            <label style="color:gray"> What is your sex? </label>    
            <select style="color:gray" class="form-select" aria-label="Default select example" name="sex" required>
              <option selected>Select Option</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Non-binary">Non-Binary</option>
            </select>
                        </div>
                   </div>
            </div>
                
                
                <!========ROW2=====!>
                
                
                               <div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="form-group">
            <label style="color:gray"> What achievement are you most proud of? </label>    
            <textarea name="achievement" class="form-control" id="exampleFormControlTextarea1" rows="3" required></textarea>
                        </div>
                   </div>
            </div>
                
                <!========ROW2=====!>
                
                
                <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6">
				<div class="form-group">
					<input type="text" name="username" id="username" class="form-control input-lg" placeholder="User Name" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['username'], ENT_QUOTES); } ?>" tabindex="1">
				</div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6">
				<div class="form-group">
					<input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email Address" value="<?php if(isset($error)){ echo htmlspecialchars($_POST['email'], ENT_QUOTES); } ?>" tabindex="2">
				</div>
                </div>
                </div>
                
                
                <!========ROW3=====!>
                
                
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-6">
						<div class="form-group">
							<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="3">
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6">
						<div class="form-group">
							<input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder="Confirm Password" tabindex="4">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Register" class="btn btn-primary btn-block btn-lg" tabindex="5"></div>
				</div>
			</form>
		</div>
	</div>

</div>

<?php
//include header template
require('layout/footer.php');
?>
