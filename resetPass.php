<?php
ob_start();
session_start();
$errors = $passError = $emailError = "";
require_once('./PHP/database.php');
 if(!isset($_GET['email'] && $_GET['token'])){
         header("Location: forgotPass.php");
    }else{ 
        $email	= $_GET['email'];
        $token	= $_GET['token'];
        $sql = "SELECT * FROM users WHERE email='$email' AND token='$token'";
        $result = $conn->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
		$user_id = $row["user_id"];		
} 		

if (isset($_POST['reset-password'])) {

$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];

if(empty($password) && empty($confirmPassword)){
    $errors  = "Fill in all fields". "</br>";
}else if(empty($password)){
    $passError = "Password is a required field". "</br>";
}
else if(empty($confirmPassword)){
    $passError = "Confirm Password is a required field". "</br>";
}
elseif (strlen($_POST['password']) < 8 ) {
    $passError = "Password should be a minimum of eight (8) characters";     
}
else if ($password !== $confirmPassword){
    $passError = "Passwords did not match". "<br>";
} else if ($password == $confirmPassword){
    $password = $_POST['password'];
    $passHash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET password = '$passHash' WHERE user_id='$user_id' LIMIT 1";
    $result = $conn->query($sql);
	if ($result){
		$okmessage = "Password Reset was successful! You can now Sign In with your new password";
	}
    }
}

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="https://fonts.googleapis.com/css?family=Rubik&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="css/account.css">
        <script src="https://kit.fontawesome.com/833e0cadb7.js" crossorigin="anonymous"></script>
        <title>KymoBudget | Password Reset</title>
    </head>


    <body>

        <!-- Just an image -->
        <a href="index.php" id="top-logo"><img src="images/kymo.png" class="img-fluid" width="" height="30" alt="logo"></a>



        <img src="images/Ellipse.png" class="img-fluid top-ellipse" alt="">
        <section class="container reset-section ">
            <div class="text-center spacing">
                <!-- <a href="index.php"><img src="images/kymo.png" class="img-fluid" alt=""></a> -->
            </div>
            <h3 class="text-center welcome spacing">Password Reset</h3>
			<div class="okmessage"><?php if(isset($okmessage)) { echo $okmessage; } ?></div>
            <p class="welcome text-center">To reset your forgotten password, create a new password for your <strong>KymoBudget</strong> App below.</p>
            
			<form class="form-align" action='' method="POST">
                <div class="form-group col-md-4">
                    <input class="form-control" type="password" name="password" id="password" class="form-control" placeholder="Enter New Password" required><span class="error"><?php echo $passError; ?></span>
                </div>
                <div class="form-group col-md-4">
                    <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirm New Password" required><span class="error"><?php echo $passError; ?></span>
                </div>
                <button type="submit" name="reset-password" class="btn btn-primary login-btn">Submit</button>

                <p class="Already-acc">No Need To Reset?&nbsp;&nbsp; <a href="login.php"><span>Sign In</span></a></p>
            </form>
            <span class="error"><?php echo $errors; ?></span>
        </section>


        <img src="images/Ellipse.png" class="img-fluid bottom-ellipse d-none d-md-none d-lg-block" alt="">


        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>

    </html>