<?php
require_once('./PHP/database.php');
// require "database.php";
$emailAddress 	= "";
$emailError 	= "";

//if (isset($_POST['forgot-password'])) {
if($_SERVER["REQUEST_METHOD"] == "POST"){

$email = $_POST['emailAddress'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $emailError = "Email is not a valid format". "<br>";

} else{
        
    $sql = "SELECT * FROM users WHERE email='$email'";
    
    $result = $conn->query($sql);
    
    $checkEmail = $result->fetch(PDO::FETCH_ASSOC);
    
    if($checkEmail) {

                $token = md5(rand(0,1000) );
                $sql = "UPDATE users SET token = '$token' WHERE email='$email' LIMIT 1";
                $result = $conn->query($sql);

                /*
                //the following php mail function works fine on 000webhost.com //
                $to = $email;
				$subject = "KymoBudget Password Reset";
				$body = "Please click on the link below or paste it into your browser to start the process of changing your password:" . "\n" .
						"https://kymobudget.herokuapp.com/resetPass.php?email=$email&token=$token";
				$headers = 'From: "KymoBudget App" <ooolalere2015@gmail.com>';						
	
				mail ($to, $subject, $body, $headers);
				
                $okmessage = "Please check your email for the link to reset your password (You may need to check your spam/junk folder). ";
                */

                require 'phpmailer/PHPMailerAutoload.php';
                $mail = new PHPMailer;
                $mail->isSMTP();
                $mail->Host='smtp.gmail.com';
                //$mail->Host = "ssl://smtp.gmail.com";
                $mail->port=587;
                $mail->SMTPAuth=true;
                $mail->SMTPSecure='tls';
                $mail->Username='oolalere2019@gmail.com';
                $mail->Password='#Itis2019';
                $mail->setFrom('oolalere2019@gmail.com', 'Kymobudget');
                $mail->addAddress($email);
                $mail->addReplyTo('noreply@gmail.com');
                $mail->isHTML(true);
                $mail->Subject='KymoBudget Reset Password';
                $mail->Body = "Please click on the link below or paste it into your browser to start the process of changing your password:" . "\n" .
                                "https://kymobudget.herokuapp.com/resetPass.php?email=$email&token=$token";
                if (!$mail->send()) {
                echo "Message could not be sent!";
                echo 'Mailer Error: ' . $mail->ErrorInfo;
                }else{
                    $okmessage = "Please check your email for the link to reset your password.";
                }
                
        
			}else if (!empty($email)){
			
				$okmessage = "No active user account exists for the email address you entered. Please enter an existing user email address.";
				
			
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
    <title>KymoBudget | Forgot Password</title>
</head>

<body>

    <!-- Just an image -->

    <a href="index.php" id="top-logo"><img src="images/kymo.png" class="img-fluid" width="auto" height="30" alt="logo"></a>

    <img src="images/Ellipse.png" class="img-fluid top-ellipse" alt="">

    <section class="container reset-section ">
        <h3 class=" welcome text-center spacing">Forgot Password?</h3>
		<div class="okmessage"><?php if(isset($okmessage)) { echo $okmessage; } ?></div>
        <p class="welcome text-center">To reset your forgotten password, enter the email address you used to sign up for <strong>KymoBudget</strong> App.</p>
        <form class="" action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method="POST">
            <div class="form-group col-md-4 ">
				<input type="email" class="form-control" aria-describedby="emailHelp" placeholder="Your email address" id="emailAddress" name="emailAddress" value="<?php echo $emailAddress; ?>" required><span class="error"><?php echo $emailError; ?></span>
            </div>
            <br>
            <button type="submit" name="forgot-password" class="btn btn-primary login-btn">Submit</button>

            <p class="Already-acc">New to Kymo Budget?&nbsp;&nbsp;<span><a href="signup.php"> Sign Up</span></a></p>

        </form>

    </section>


    <img src="images/Ellipse.png" class="img-fluid bottom-ellipse d-none d-md-none d-lg-block" alt="">


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>
