<?php
$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');

include_once 'userclass.php';
$user= new User();
if(isset($_GET['uTK12']))
{       $query = "SELECT * FROM users WHERE userpassword = :up";
	$statement = $connect->prepare($query);
	$statement->execute(
		array(
			':up'	=>	$_GET['uTK12']
		)
	);
	$no_of_row = $statement->rowCount();
	//if statement to check whether the unique id belongs to a user
	if($no_of_row > 0)
	{
        if (isset($_POST['submit'])) {
        foreach ($statement as $row) {
            # code...
                // validate card
                extract($_POST);
                $user->validatecard($type,$cnumber);
                    # redirect to select topics page
                    $userid=$row['uniqueid'];
							
                    $unimportantinfo=md5($userid);
                    header("Location: http://localhost/phpprojects/lunchbox1/choosetopics.php?TidD=".$userid."&uiMP=".$unimportantinfo."");
                
            
            }
        }
    }
    else{
       // echo '<script type="text/javascript">alert("it doesnot work")</script>';

    }
		

    }
    else{
        //echo '<script type="text/javascript">alert("uTK12 couldnot be summoned")</script>';

    }

?>
<!DOCTYPE html>
<html>
<div>
    <h1>lunchbox.io</h1>
    <p>kinda sorta like github</p>
</div>
<div>
    <h2>enter credit card details a <br>to seal the deal</h2>
    <form action="" method="post" >
        <div>
            <p>card type</p>
            <select id="cardtype" name="cardtype">
                <option value="visa">visa</option>
                <option value="mastercard">mastercard</option>
                <option value="amex">amex</option>
                <option value="discover">discover</option>
            </select>
        </div>
        <input type="text" value='cardholders name'>
        <input type="text" value='card number' name="cnumber">
        <div>
            <p>expiration date</p>
            <select id="month">
                <option value="01">Jan</option>
                <option value="02">Feb</option>
                <option value="03">Mar</option>
                <option value="04">Apr</option>
                <option value="05">May</option>
                <option value="06">Jun</option>
                <option value="07">Jul</option>
                <option value="08">Aug</option>
                <option value="09">Sep</option>
                <option value="10">Oct</option>
                <option value="11">Nov</option>
                <option value="12">Dec</option>
            </select>
            <select id="year">
                <option value="14">14</option>
                <option value="15">15</option>
                <option value="16">16</option>
                <option value="17">17</option>
                <option value="18">18</option>
                <option value="19">19</option>
                <option value="20">20</option>
                <option value="21">21</option>
                <option value="22">22</option>
                <option value="23">23</option>
            </select>
        </div>
        <span>Billing address</span>
        <input type="text" value='address'>
        <input type="text" value='country'>

        <input type="submit" name="submit" class="button" value="Confirm">

    </form>


</div>

</html>