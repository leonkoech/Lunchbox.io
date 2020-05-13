<?php 
$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
include_once 'userclass.php';
$user = new User();
if (isset($_GET['HiDu2'])) {
  # code...
  $query="SELECT * FROM users WHERE uniqueid=:uniqueid";
  $statement = $connect->prepare($query);
  $statement->execute(
		array(
			':uniqueid'	=>	$_GET['HiDu2']
		)
	);
	$no_of_row = $statement->rowCount();
	//if statement to check whether the unique id belongs to a user
	if($no_of_row > 0)
	{
		$result = $statement->fetchAll();
		foreach($result as $row)
    {
      $userid=$row['uniqueid'];
      if(isset($_POST['search'])){
        //when search is clicked
      }
      if(isset($_POST['uploadarticle'])){
        header("Location: http://localhost/phpprojects/lunchbox1/uploadarticle.php?CbHua=".$userid."");

      }
    }
    }

}
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8">
    <title>Home</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  </head>

  <body>
  <div>
  <h1>lunchbox.io</h1>
  <h2>kinda sorta like github</h2>
  <form method="post" name="">
            <input type="submit" value="search" class="button" name="search">
            <input type="submit" value="upload story" class="button" name="uploadarticle">
  </form>
  <button>profile</button>
    </div>
    <div id="container" class="container">
      <div id="header">
      </div>
      <div id="main-body">
      <?php $user->get_articles($userid) ?>
      </div>
      <div id="footer"></div>
    </div>
  </body>

  </html>