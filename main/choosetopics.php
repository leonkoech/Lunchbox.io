<?php
$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
include_once 'userclass.php';
$user= new User();
if(isset($_GET['TidD']))
{
        $query = "SELECT * FROM users WHERE uniqueid = :uniqueid";
        $statement = $connect->prepare($query);
        $statement->execute(
            array(
                ':uniqueid'	=>	$_GET['TidD']
            )
        );
        $no_of_row = $statement->rowCount();
        //if statement to check whether the unique id belongs to a user
        if($no_of_row > 0)
        {
            foreach ($statement as $row) {
                $userid=$row['uniqueid'];
                if (isset($_POST['select'])) {
                    # code...
                    header("Location: http://localhost/phpprojects/lunchbox1/topics.php?uTK12=".$userid."");
        
                }
                elseif(isset($_POST['random'])){
                    $user->randomtopics($userid);
                    header("Location: http://localhost/phpprojects/lunchbox1/topics.php?uTK12=".$userid."");
                }
            }
        }

}
?>
<!DOCTYPE html>
<html>
    <title>

    </title>
    <body>
        <h1>lunchbox.io</h1>
        <div>
            <h2>select what you'd like to see</h2>
            <form  action="" method="post" >
            <input type="submit" value="select topics" class="button" name="select">
            <input type="submit" value="random topics" class="button" name="random">
       
            </form>
           </div>
    </body>
</html>