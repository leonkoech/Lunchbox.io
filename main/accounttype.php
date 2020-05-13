<?php
$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
include_once 'userclass.php';
$user= new User();
//this 'ViD' is the argument I put inside the link to the verification
if(isset($_GET['AiD']))
{
    //then selecting all from the database where the uniqueid is similar to the unique id
    //that the user entered in the registration form. labelled :uniqueid
	$query = "SELECT * FROM users WHERE uniqueid = :uniqueid";
	$statement = $connect->prepare($query);
	$statement->execute(
		array(
			':uniqueid'	=>	$_GET['AiD']
		)
	);
	$no_of_row = $statement->rowCount();
	//if statement to check whether the unique id belongs to a user
	if($no_of_row > 0)
	{
        if (isset($_POST['free'])) {
            //if free is clicked
            $typef='free';
            foreach ($statement as $row) {
                # code...
            $insert=$user->setaccount($row['uniqueid'],$typef,0);
            if (!isset($insert)) {
                # code...
                $user->update($row['uniqueid'],$typef,0);
            }
            header("Location: http://localhost/phpprojects/lunchbox1/choosetopics.php?TidD=".$row['uniqueid']."");
            }
            //insert into database
            
              } 
        if (isset($_POST['5$'])) {
            //if 5$ is clicked
            $type5='5$';
            //insert into database
            foreach ($statement as $row) {
                # code...
                
                $insert=$user->setaccount($row['uniqueid'],$type5,5);
                if (!isset($insert)) {
                    # code...
                    $user->update($row['uniqueid'],$type5,5);
                }
                $password=$row['userpassword'];
                header("Location: http://localhost/phpprojects/lunchbox1/carddetails.php?uTK12=".$password."");

            }

        }
        if (isset($_POST['10$'])) {
            //if free is clicked
            $type10='10$';
            //insert into database
            foreach ($statement as $row) {
                # code...
                $insert=$user->setaccount($row['uniqueid'],$type10,10);
                if (!isset($insert)) {
                    # code...
                    $user->update($row['uniqueid'],$type10,10);
                }
                $password=$row['userpassword'];
                header("Location: http://localhost/phpprojects/lunchbox1/carddetails.php?uTK12=".$password."");

            }

        }       	
    }
    else{
        #take user to the error of links
        echo'something is wrong with that link';
    }
}
    


?>
<html>
<body>
<h1>select account type</h1>
<div>
<div>
<h2>free</h2>
<p>the features<p>
<form action="" method="post">
<input type="submit" name="free" value="free account">
</form>
</div>
<div>
<h2>5$ per month</h2>
<p>the features<p>
<form action="" method="post">
<input type="submit" name="5$" value="5$ account">
</form>
</div>
<div>
<h2>10$ per month</h2>
<p>the features<p>
<form action="" method="post">
<input type="submit" name="10$" value="10$ account">
</form>
</div>

<div>
</body>
</html>