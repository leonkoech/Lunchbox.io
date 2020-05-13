<?php
    $connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
    include_once 'userclass.php';
    require_once 'mailerclass.php';
    $mailer= new Mailerclass();
    $user = new User();
    if(isset($_GET['qid']))
    {
        $query = "SELECT * FROM users WHERE uniqueid = :uniqueid";
        $statement = $connect->prepare($query);
        $statement->execute(
             array(
                 ':uniqueid'	=>	$_GET['qid']
            )
        );
        $no_of_row = $statement->rowCount();
        
        if($no_of_row == 1)
        {   //if new password is selected
            $result = $statement->fetchAll();
            foreach($result as $row)
            {
                        $em=trim($row['email']) ;
                        $na=trim($row['username']);
                
                        if (isset($_POST['submit'])) { 
                            extract($_POST);
                            //$usernamequery=   
                         
                                //this method resets the password
                                $user->resetpassword($newpassword,$na);
                                $subject='password reset';
                                $mailbody="
                                <p>Hi ".$na.",</p>
                                <p>Your password has been reset successfully</p>
                                <p>Open this link to login - http://localhost/phpprojects/lunchbox1/login.php</p>
                                <p>Best Regards,<br />Your Webmaster hahaha</p>
                                ";
                                $nab='';
                                $mailer ->sendemail($subject,$em,$mailbody,$nab);
                                if($mailer){
                                    $relocate='http://localhost/phpprojects/lunchbox1/login.php';
                                    echo '<html>
                                    <body>
                                    <div>
                                    <h1>Lunchbox.io</h1>
                                    <p>kinda sorta like medium</p>
                                    </div>
                                    <div>
                                    <h2>Successful</h2>
                                    <p>password successfully reset</p>
                                    <p>hold on while we redirect you</p>
                                    <span>If you are not automatically redirected <a href='.$relocate.'>click here<a></div>
                                    </body>
                                    </html>
                                    ';
                                    header("location:login.php") ;
                                    return true;
    
                                }
                                else{
                                    echo 'message could not be sent';
                                }
                            
                        }                  

                }
                
 
        }
            else{
                echo 'invalid link ';
                return false;
                
            }
    }
    else{
        echo 'that link is broken';
    }
?>