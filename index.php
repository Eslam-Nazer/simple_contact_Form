<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Check If User Coming From A Post Request
if ($_SERVER['REQUEST_METHOD'] == 'POST'){


    // Assign Variable

    $userAllowedChar = "/[^a-zA-Z0-9 \-_]+/"; // allowed '-' , '_'
    $msgAllowedChar = "/[^a-zA-Z0-9 !?\-_]+/"; // allowed '-' , '_', 'space', '!','?'
    
    $user = preg_replace($userAllowedChar,'',strip_tags($_POST['username']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phoneNumber = filter_var($_POST['phoneNumber'], FILTER_SANITIZE_NUMBER_INT);
    $msg = preg_replace($msgAllowedChar,'',strip_tags($_POST['message']));

    $formErrors = array();
    if(strlen($user) <= 3){
        $formErrors[] = "- Username Must Be Larger Than 3 Characters";
    }
    if(empty($email)) {
        $formErrors[] = "- Email can not be empty";
    }
    if(strlen($msg) <= 10){
        $formErrors[] = "- Sorry Message Can't Be Less Than 10 Characters";
    }

    // if not Errors send $mail in phpmailer Or mail using [mail(To, Subject, message, headers, paramerters)]
    $subject = "contact form";
    if (empty($formErrors)){
        $mail = new PHPMailer(true);
        $mail ->isSMTP();
        $mail ->Host = 'smtp.gmail.com';
        $mail ->SMTPAuth = true;
        $mail ->Username = 'test@gmail.com'; //  gmail
        $mail ->Password = 'Secret'; // gmail code
        $mail ->SMTPSecure = 'ssl';
        $mail ->Port = 465;
        $mail ->setFrom($email); // my gmail
        $mail ->FromName = $user;
        $mail ->addAddress($email);
        $mail ->isHTML(true);
        $mail ->Subject = $subject;
        $mail ->Body = $msg;
        $mail ->send();
        
        $success = '<div class="alert alert-success">message send successfully</div>';
    }
}

?>

<!DOCTYPE html>
<html lang="en-ar">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>
        Contact first project
    </title>
    <link rel="stylesheet" href="CSS/bootstrap.min.css" />
    <link rel="stylesheet" href="CSS/font-awesome.min.css" />
    <link rel="stylesheet" href="CSS/contact.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,600;0,800;1,600;1,800&display=swap" />
</head>
<body>
    <!-- Start From -->
    <dev class="container">
        <h1 class="text-center">Contact Me</h1>

        <form class="contact-form" method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">

        <?php if(! empty($formErrors)) { ?>
        <div class="alert alert-danger alert-dismissible fade show alert-box" role="alert">
            <i class="fa fa-exclamation-triangle fa-2x alert-icon "></i>
            <div class="text-error-message"><strong class="message-error"><?php 
            foreach($formErrors as $error){
                echo $error . '<br />';
            }
            ?> </strong></div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </div>
        <?php } ?>
        <?php
        if(isset($success)){ 
            echo $success ; 
            header("Refresh:7;");
        }
        ?>

        <div class="form-group">
            <input class="username form-control" type="text" name="username" placeholder="Type Your Username" value="<?php if(!empty($formErrors)) { echo $user; } ?>" />

            <i class="fa fa-user fa-fw icon"></i>
            <span class="asterisx">*</span>
            <div class="alert alert-danger custom-alert">
                <i class="fa fa-exclamation-triangle"></i>
                <strong>The Username field must contain at least 4 characters</strong>
            </div>
        </div>

        <div class="form-group">
            <input class="email form-control" type="email" name="email" placeholder="Type Your Valid Email" value="<?php if(!empty($formErrors)){ echo $email; } ?>" />

            <i class="fa fa-envelope fa-fw icon"></i>
            <span class="asterisx">*</span>
            <div class=" alert alert-danger custom-alert">
                <i class="fa fa-exclamation-triangle "></i>
                <strong>Email can not be empty</strong>
            </div>
        </div>

        <input class="form-control" type="text" name="phoneNumber" placeholder="Type Your Phone Number" value="<?php if(!empty($formErrors)){ echo $phoneNumber; } ?>" />
        <i class="fa fa-phone fa-fw icon" ></i>

        <div class="form-group">
        <textarea class="message form-control" name="message" placeholder="Your Message!"><?php if(!empty($formErrors)){ echo $msg; } ?></textarea>
        <span class="asterisx">*</span>
            <div class="alert alert-danger custom-alert">
                <i class="fa fa-exclamation-triangle"></i>
                <strong>The Message content must consist of 10 characters or more</strong>
            </div>
        </div>

        <input class="btn btn-success send-button" type="submit" value="Send Message" />
        <i class="fa fa-send fa-fw send-icon icon"></i>
        </form>
    </div>
    <!-- End From -->
    <script src="JS/jquery-3.7.1.min.js" ></script>
    <script src="JS/bootstrap.min.js"></script>
    <script src="JS/custom.js"></script>
</body>
</html>