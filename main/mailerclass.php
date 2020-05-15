<?php
	//Import the PHPMailer class into the global namespace
	//require PHPMailer.php;
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	// Load Composer's autoloader
    require 'vendor/autoload.php';
    require 'vendor/phpmailer/phpmailer/src/Exception.php';
    require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require 'vendor/phpmailer/phpmailer/src/SMTP.php';
    class Mailerclass{
        public function sendemail($subject,$emailadress,$mailbody,$userthing){

            $mail = new PHPMailer;
            /*With gmail smtp you get
            100 e-mails per 24-hour time period restriction
            Research link:https://www.presslabs.com/how-to/google-smtp-server/
            */
            $mail->IsSMTP();                           // telling the class to use SMTP
            $mail->SMTPAuth   = true;                  // enable SMTP authentication
            $mail->Host       = "tls://smtp.gmail.com"; // set the SMTP server
            //you can avoid 'ssl' in your host by doing the following;
            //$mail->Host = 'smtp.gmail.com';
            //$mail->SMTPSecure = 'tls';                  
            // the above line is to Enable TLS encryption, `ssl` also accepted
            $mail->Port       = 587;                    // set the SMTP port
            /*you can either use 465 for ssl or 587 for tls*/
            $mail->Username   = "your email"; // SMTP account username
            $mail->Password   = "your password";        // SMTP account password
            //Set who the message is to be sent from
            $mail->setFrom('notreal@notmail.com', 'your name');
            //Set an alternative reply-to address
            //$mail->addReplyTo('replyto@example.com', 'First Last');
            //Set who the message is to be sent to
            $mail->addOrEnqueueAnAddress('to',$emailadress,$userthing);
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            //$mail->msgHTML(file_get_contents('confirmreg.html'), __DIR__);
            //Replace the plain text body with one created manually
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $mailbody;
            $mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');
            //send the message, check for errors
            if ($mail->send()) {
                //echo "Message sent";
                //TO:DO
                //to check whether user has clicked on confirm
                //on message sent redirect user to login
                //after they login make sure they get a notification to tell them 
                //whether their account is activated or not
            } 
            else {
                echo "Mailer Error: " . $mail->ErrorInfo;		
                
        
        }
        }
    }
?>
