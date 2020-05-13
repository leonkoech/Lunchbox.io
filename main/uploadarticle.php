<?php 
$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
include_once 'userclass.php';
$user = new User();
if (isset($_GET['CbHua'])) {
  # code...
  $query="SELECT * FROM users WHERE uniqueid=:uniqueid";
  $statement = $connect->prepare($query);
  $statement->execute(
		array(
			':uniqueid'	=>	$_GET['CbHua']
		)
	);
	$no_of_row = $statement->rowCount();
	//if statement to check whether the unique id belongs to a user
	if($no_of_row > 0)
	{
		$result = $statement->fetchAll();
		foreach($result as $row)
        {
      $uid=$row['uniqueid'];
      if(isset($_POST['upload'])){
          //uploading the image if any
          if(isset($_FILES['images'])){
                  # code...
            $selected=$_POST['accessibility'];
            $selected=trim($selected);
            if ($selected=='private') {
                # code...
                $accessibility=1;

            }
            elseif ($selected=='public') {
                # code...
                $accessibility=0;
            }
            $artvalidation=$user->article_validation($uid);
            if(isset($artvalidation)){
                $addinfo = $user->addarticle($_FILES['images']['tmp_name'],$_FILES['images']['name'],$_POST['content'],$accessibility,$_POST['title'],$uid);
                if(isset($addinfo)){
                    if(!empty($_POST['checkboxbtn'])) {
                        // Counting number of checked checkboxes.
                            $checked_count = count($_POST['checkboxbtn']);
                            // Loop to store and display values of individual checked checkbox.
                            foreach($_POST['checkboxbtn'] as $selected) {
                                $articletops=$user->articletopics($_POST['title'],$selected,$uid);
                                if(isset($articletops)){
                                    header("Refresh:0");

                                }
                            }
                        }
                        else{
                            echo "<b>Please Select Atleast One category.</b>";
                        }
                }
                elseif (!isset($addinfo)) {
                    echo'<p>add info did not run</p>';
                }
            }

            }
            else{
                echo'<p>image has a problem</p>';
            }
            // Get image name
            
            
      }
    }
    }

}
?>
<!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8">
    <title>upload article</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  </head>

  <body>
  <div>
  <h1>lunchbox.io</h1>
  <h2>kinda sorta like medium</h2>
  <?php $user->display_my_articles($uid) ?>
  <form method="post" enctype="multipart/form-data" autocomplete="off">
            <input type="file" name="images"><br>
            <textarea id="text" cols="40" rows="1" name="title" placeholder="enter article title"></textarea>
            <div>
                <textarea id="text" cols="90" rows="10" name="content" placeholder="your article text goes here"></textarea>
            </div>
            <select id="" name="accessibility">
                <option value="public">select accessibility</option>
                <option value="public">public - anyone can read it</option>
                <option value="private">private - only lunchbox.io members can read it</option>
            </select> 
            <p>select category</p> 
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
            <input type="submit" value="upload story" class="button" name="upload">  </form>
    </div>
  </body>
  </html>