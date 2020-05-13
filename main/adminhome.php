<?php
include_once "mailerclass.php";
require_once 'userclass.php';
$connect=new PDO('mysql:host=localhost;dbname=lunchboxone','root','');
$user = new User();
$body= '
    <div class="theform">
        <div class="header"> <h1>Admin login</h1></div>
             <form action="" method="post" name="login">
                    <input  name="newsletters" type="submit" value="send newsletters">
                    <input name="user accounts" type="submit" value="user accounts">
                <div class="formgroup">
            </div>
        </form>
    </div>
';
//enable admin to send email/newsletters
//to check amount of money gained from users. ie. gets 1$ for 5$ accounts and 2$ for 10$ accounts
//check number of free/5$/10$ accounts
//check number of private and public  articles in lunchbox.io
if (isset($_POST['newsletters'])) {
    # code...
    $body = '
    <div class="theform">
        <div class="header"> <h1>Admin login</h1></div>
             <form action="" method="post" name="login">
             <input  name="title" type="text" value="subject">
             <input  name="message" type="text" value="message">
                    <input  name="sendnewsletter" type="submit" value="send">
                <div class="formgroup">
            </div>
        </form>
    </div>
    ';
    if (isset($_POST['sendnewsletter'])) {
        # code...
        if(isset($_GET['uqYiDHHH']))
        {
        $query = "SELECT * FROM admins WHERE id = :id";
        $statement = $connect->prepare($query);
        $statement->execute(
            array(
                ':id'	=>	$_GET['uqYiDHHH']
            )
        );
        $no_of_row = $statement->rowCount();
        //if statement to check whether the id belongs to an admin
            if($no_of_row > 0)
            {
            foreach ($statement as $row) {
                $adminemail=$row['email'];
                //select subscribed from accounts
                $query= "SELECT * FROM users WHERE subscription=1";
                $stmnt= $connect->prepare($query);
                $stmnt->execute();
                foreach ($stmnt as $rows) {
                    # code...
                    $useremail=$rows['email'];
                    $username=$rows['username'];

                }
                $body = $_POST['message'];
                $mailbody="
                        <div  style='
						background:black;
						display:inline-block;
						color:#ffffff;
						font-family:Arial;
						font-size:18px;
						padding:13px;' > 
                        <p>Hi ".$username.",</p>
                        ".$body."</div>
						
                ";
                $companyname='Lunchbox.io';
                $subject=$_POST['title'];
                $mailer= new Mailerclass();
                $mailer->sendemail($subject,$useremail,$mailbody,'');
            }
        

}
        }
}
}
?>
<html>
    <title>
    </title>
        <link rel="stylesheet" href="style.css">
        <script src="main.js"></script>
<head>
</head>
    <body>
    <?php echo $body; ?>
    </body>
</html>