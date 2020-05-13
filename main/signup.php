<?php 
require_once 'userclass.php';
$user = new User();
if (isset($_POST['submit'])){
        extract($_POST);
        $user->reg_user($username, $userpassword, $email);

        if ($user) {
            # code...

            if (isset($_POST['newsletter'])) {
                $user->setstatus($email);
            }
        }
        else {
            // Registration Failed
            echo '<script type="text/javascript">alert("Registration failed. Email or Username already exits please try again.")</script>';

            // $alert=new phpAlert();
			// $alert->phpAlert('');
            ///echo "<div style='text-align:center'></div>";
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
    <form action="" method="post" name="reg">
        <input type="text" value='username' name="username">
        <input type="email" value='email' name="email">
        <input type="password" value='password' name="userpassword">
        <input type="password" value='confirm password'>
        <span class="newsletter">
            <input type="checkbox" name="newsletter" value="signup"> sign up for our newsletter to get the good stuff early. 
        </span>
        <input type="submit" name="submit" class="button" value="Create account">

    </form>
    <p>already have an account?<a href="login.php">Log in</a><p>

</html>