<?php 
session_start();
require_once 'userclass.php';
$user = new User();
if (isset($_POST['submit'])) { 
		extract($_POST);   
	    $user->check_login($emailusername, $userpassword);
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
    <form action="" method="post" name="reg">
        <input type="text" value='username or email' name="emailusername">
        <input type="password" value='password' name="userpassword">

        <input type="submit" name="submit" value="login">

    </form>
    <p>Don't have an account?<a href="signup.php">Sign up</a><p>
	<p><a href="forgotpassword.php">forgot password?</a>

</html>