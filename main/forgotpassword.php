<?php 
require_once 'userclass.php';
$user = new User();
if (isset($_POST['submit'])) { 
		extract($_POST);   
	    $sendresetlink=$user->resetpasswordlink($emailusername);
        if($sendresetlink){
            header("Location: http://localhost/phpprojects/lunchbox1/resetlink.php");

        }
        else{
            $errorl='link could not be sent';
            $errormd=md5($errorl);
            header("Location: http://localhost/phpprojects/lunchbox1/error/linkerror.php?LiD='".$errormd."'");
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
    <h2>Welcome back</h2>
    <form>
        <input type="text" value='username or email' name="emailusername">

        <input type="submit" name="submit" value="reset password">

    </form>
    <p>or<a href="login.php">log in</a><p>
	<p>Don't have an account?<a href="signup.php">signup</a></p>

</html>