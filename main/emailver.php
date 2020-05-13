<?php

//I used  PDO because it was more convinient
$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
//session_start();
//importing the mailer class for sending emails
include_once 'mailerclass.php';
//importing user class to fetch user details(for the email to be sent to the company)
//for notification of user confirmation of registration
$mailer = new Mailerclass();
//this 'ViD' is the argument I put inside the link to the verification
if(isset($_GET['ViD']))
{
    //then selecting all from the database where the uniqueid is similar to the unique id
    //that the user entered in the registration form. labelled :uniqueid
	$query = "SELECT * FROM users WHERE uniqueid = :uniqueid";
	$statement = $connect->prepare($query);
	$statement->execute(
		array(
			':uniqueid'	=>	$_GET['ViD']
		)
	);
	$no_of_row = $statement->rowCount();
	
	if($no_of_row > 0)
	{
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			if($row['active'] == 0)
			{	//change active to 1
				$update_query = "UPDATE users SET active = 1 WHERE id = '".$row['id']."'";
				$statement = $connect->prepare($update_query);
				$statement->execute();
				$sub_result = $statement->fetchAll();
				//if updated
				if(isset($sub_result))
				{	        //navigate to congratulations page to let user know they are now members
							header("Location: http://localhost/phpprojects/lunchbox1/congratulations.php?LiD=".$row['uniqueid']."");
							
							//send email to admin to let them know user has created an account
							$address='leon@getnada.com';
							$subject='User Registration';
							$mailbody= '
							<p>Another user has confirmed registration.</p>
							<p>username </p>'.$row['username'].'
							<p>and</p>
							<p>email</P'.$row['email'].'
							<p>Open this link to log in as admin - http://localhost/phpprojects/lunchbox1/admin/login.php</P>
							<p>Best Regards,<br/>Leon Koech<br/>System Administrator</p>
							';
							$name='';
							$mailer->sendemail($subject,$address,$mailbody,$name);			

				}
			}
			else
			{   
				//redirect to error page
				$errortype='link already verified';
				$errorenc=md5($errortype);
				header("Location: http://localhost/phpprojects/lunchbox1/error/linkerror.php?error=".$errorenc."");
			}
		}
	}
	else
	{   
		//redirect to error page
		$errortype='link is invalid';
		$errorenc=md5($errortype);
		header("Location: http://localhost/phpprojects/lunchbox1/error/linkerror.php?error=".$errorenc."");
	
	}
}

