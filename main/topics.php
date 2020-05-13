<?php
$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
include_once 'userclass.php';
$user= new User();
if(isset($_GET['uTK12']))
{       $query = "SELECT * FROM users WHERE uniqueid = :uniqueid";
	$statement = $connect->prepare($query);
	$statement->execute(
		array(
			':uniqueid'	=>	$_GET['uTK12']
		)
	);
	$no_of_row = $statement->rowCount();
	//if statement to check whether the unique id belongs to a user
	if($no_of_row > 0)
	{
        foreach ($statement as $row) {
            # code...
            $uid=$row['uniqueid'];
            if(isset($_POST['submit'])){
                if(!empty($_POST['checkboxbtn'])) {
                // Counting number of checked checkboxes.
                    $checked_count = count($_POST['checkboxbtn']);
                    // Loop to store and display values of individual checked checkbox.
                    foreach($_POST['checkboxbtn'] as $selected) {
                        $user->addtopics($uid,$selected);
                        if(isset($user)){
                            //if topics have been added go to next page
                            header("Location:http://localhost/phpprojects/lunchbox1/home.php?HiDu2=".$uid."");
                        }
                    }
                }
                else{
                    echo "<b>Please Select Atleast One Option.</b>";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<div>
    <h1>lunchbox.io</h1>
    <p>kinda sorta like github</p>
</div>
<div>
    <h2>Create an account</h2>
    <form action="" method="post">
        <input type="checkbox" value='Home feed' name="checkboxbtn[]">Home feed
        <input type="checkbox" value='popular' name="checkboxbtn[]">Popular
        <input type="checkbox" value='videos' name="checkboxbtn[]">Videos
        <input type="checkbox" value='shop' name="checkboxbtn[]">Shop
        <input type="checkbox" value='animals and pets' name="checkboxbtn[]">Animals and Pets<br>
        <input type="checkbox" value='architecture' name="checkboxbtn[]">Architecture
        <input type="checkbox" value='art' name="checkboxbtn[]">Art
        <input type="checkbox" value='cars and motorcycles' name="checkboxbtn[]">Cars and motorcycles
        <input type="checkbox" value='celebrities' name="checkboxbtn[]">Celebrities
        <input type="checkbox" value='DIY and crafts' name="checkboxbtn[]">DIY and crafts
        <input type="checkbox" value='Design' name="checkboxbtn[]">Design
        <input type="checkbox" value='education' name="checkboxbtn[]">Education
        <input type="checkbox" value='Film music and books' name="checkboxbtn[]">Film, Music and Books
        <input type="checkbox" value='food and drink' name="checkboxbtn[]">Food and Drink
        <input type="checkbox" value='gardening' name="checkboxbtn[]">Gardening<br>
        <input type="checkbox" value='geek' name="checkboxbtn[]">Geek
        <input type="checkbox" value='hair and fitness' name="checkboxbtn[]">Hair and Fitness
        <input type="checkbox" value='health and beauty' name="checkboxbtn[]">Health and Beauty
        <input type="checkbox" value='history' name="checkboxbtn[]">History
        <input type="checkbox" value='holidays and events' name="checkboxbtn[]">Holiday and Events<br>
        <input type="checkbox" value='homedecor' name="checkboxbtn[]">Homedecor
        <input type="checkbox" value='humor' name="checkboxbtn[]">Humor
        <input type="checkbox" value='illustrations and posters' name="checkboxbtn[]">Illustration and Posters
        <input type="checkbox" value='kids and parenting' name="checkboxbtn[]">Kids and Parenting
        <input type="checkbox" value='mens fashion' name="checkboxbtn[]">Men's fashion
        <input type="checkbox" value='outdoors' name="checkboxbtn[]">Outdoors
        <input type="checkbox" value='photography' name="checkboxbtn[]">Photography
        <input type="checkbox" value='products' name="checkboxbtn[]">Products
        <input type="checkbox" value='quotes' name="checkboxbtn[]">Quotes
        <input type="checkbox" value='science and nature' name="checkboxbtn[]">Science and Nature<br>
        <input type="checkbox" value='sports' name="checkboxbtn[]">Sports
        <input type="checkbox" value='tattoos' name="checkboxbtn[]">Tattoos
        <input type="checkbox" value='technology' name="checkboxbtn[]">Technology
        <input type="checkbox" value='travel' name="checkboxbtn[]">Travel
        <input type="checkbox" value='weddings' name="checkboxbtn[]">Weddings<br>
        <input type="checkbox" value='womens fashion' name="checkboxbtn[]">Women's fashion
        <input type="submit" name="submit" value="next"/>

    </form>
</html>