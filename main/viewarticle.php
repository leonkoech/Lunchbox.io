<?php 
$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
include_once 'userclass.php';
$user = new User();
$articleid=$_GET['UnPID'];
$uid=$_GET['UviAD'];
if (isset($articleid)) {
    
    if(isset($uid)){
        $user->addarticlesread($uid,$articleid);
        //when article id has been gotten add this article to clapped articles
        //leaving the clapped part as null
        $user->add_clapped($articleid,$uid);
        $checkclapped=$user->check_claps($articleid,$uid);
        if(isset($checkclapped)){
          $clap='clapped';
        }
        else{
          $clap='clap';
        }
        if(isset($_POST['clap'])){
          //if it was already clapped for then clicking the button resets the clap
          if ($clap=='clapped') {
            $user->Remove_clap_status($articleid,$uid);
          }
          //if it was not clapped then clicking the button claps
          elseif ($clap=='clap') {
            $user->reset_claps($uid);
              $add=$user->add_claps($articleid,$uid);
              if(isset($add)){
                $user->Update_clap_status($articleid,$uid);
                $user->reset_money($uid);
                $user->money_paid($articleid,$uid);
              
              } 
          }       
        }
        if(isset($_POST['search'])){
        //when search is clicked
        }
        if(isset($_POST['uploadarticle'])){
        header("Location: http://localhost/phpprojects/lunchbox1/uploadarticle.php?CbHua=".$uid."");
        }
    }
    else{
      echo'uid broken';
    }
}
else{
    echo 'broken link';
}

    
    



?>
<!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8">
    <title><?php $user->get_title($articleid) ?></title>
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
      <?php $user->get_image($articleid) ?>
      <h2><?php $user->get_title($articleid) ?></h2>
      <form method='post'>
      <input type="submit" name="clap" value="<?php echo $clap ?>" >
      </form>
      <p><?php $user->get_article($articleid)?></p>
      </div>
      <div id="footer"></div>
    </div>
  </body>

  </html>