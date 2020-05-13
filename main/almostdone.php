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
if(isset($_GET['aDiD']))
{
    //then selecting all from the database where the uniqueid is similar to the unique id
    //that the user entered in the registration form. labelled :uniqueid
	$query = "SELECT * FROM users WHERE uniqueid = :uniqueid";
	$statement = $connect->prepare($query);
	$statement->execute(
		array(
			':uniqueid'	=>	$_GET['aDiD']
		)
	);
	$no_of_row = $statement->rowCount();
	
	if($no_of_row > 0)
	{
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			if($row['active'] == 1)
			{
                //navigate to congratulationa header
                header("Location: http://localhost/phpprojects/lunchbox1/congratulations.php?LiD=':uniqueid'");	
            }
            else{
                //do nothing
            }
            }
        }
    }
?>
<html>
<body>
<div>
<h1>Lunchbox.io</h1>
<p>kinda sorta like medium</p>
</div>
<div>
<h2>almost done</h2>
<p>we sent a link to your email account. Kindly click on it to verify your account</p>
<span>Didn't get a link?<a href=''>click here to resend<a></div>
</body>
</html>