<?php

//I used  PDO because it was more convinient
$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
include_once 'userclass.php';
$user= new User();
//this 'ViD' is the argument I put inside the link to the verification
if(isset($_GET['LiD']))
{
    //then selecting all from the database where the uniqueid is similar to the unique id
    //that the user entered in the registration form. labelled :uniqueid
	$query = "SELECT * FROM users WHERE uniqueid = :uniqueid";
	$statement = $connect->prepare($query);
	$statement->execute(
		array(
			':uniqueid'	=>	$_GET['LiD']
		)
	);
	$no_of_row = $statement->rowCount();
	//if statement to check whether the unique id belongs to a user
	if($no_of_row > 0)
	{
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
            //fetch username and password. 
            $username=$row['username'];
            $password=$row['userpassword'];
            //Then call the login function from userclass and automatically log in user
            $user->check_firstlogin($username,$password);
            if(isset($user)){
				//the function automatically logs user in to the 'choose account type' page

            }
            else{
                //redirect to error pages
            }

		}
	}
	else
	{   //redirect to error page
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
<h2>Congratulations</h2>
<p>you are now a member of lunchbox.io</p>
<p>hold on while we redirect you</p>
<span>If you're not automatically redirected <a href=''>click here<a></div>
</body>
</html>
