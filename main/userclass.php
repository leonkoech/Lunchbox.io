<?php 
	//require '../vendor/autoload.php';
	//include the server connector
	include "serverconnect.php";
	include_once "mailerclass.php";

	class User{
		protected $db;
		public function __construct(){
			$this->db = new DB_con();
			$this->db = $this->db->ret_obj();
		}
		//registration of new users
		public function reg_user($uname, $upassword, $uemail){	

			//this is to prevent mysql injection with trim
			 $uname=trim($uname);
			 $uemail=trim($uemail);
			 $upassword=trim($upassword);
			
			//encrypting the password		
			$hashed_password = password_hash($upassword, PASSWORD_DEFAULT);

			//creating a unique id with username email and the month.year registration
			$uniqueid = $uname . $uemail . date('mY');
			//making the unguessable
			$unique_key = md5($uniqueid);
			
			//checking if the username or email is available in db
			$query = "SELECT * FROM users WHERE username='$uname' OR email='$uemail'";
			
			$result = $this->db->query($query) or die($this->db->error);
			
			$count_row = $result->num_rows;
			
			//if the username is not in the database, then insert to the table
			
			if($count_row == 0){
				$query = "INSERT INTO users SET uniqueid='$unique_key', username='$uname', userpassword='$hashed_password', email='$uemail',active=0";
				//set active as 0 because the account is not yet verified
				$result=$this->db->query($query) or die ($this->db->error);
				if(isset($result))
				{		//change header
						/*send the email*/
						//Create a new PHPMailer instance
						//change this baseurl value as per your file path
						header("Location: http://localhost/phpprojects/lunchbox1/almostdone.php?aDiD=".$unique_key."");
						$mailbody = "
						<p>Hi ".$uname.",</p>
						<p>Thanks for Registering. Your account will be activated only after you verify your email.</p>
						<p>Please click this button to verify your email address <br> <a  style='box-shadow: 0px 10px 14px -7px #276873;
						background:linear-gradient(to bottom, #599bb3 5%, #408c99 100%);
						background-color:#599bb3;
						border-radius:8px;
						display:inline-block;
						cursor:pointer;
						color:#ffffff;
						font-family:Arial;
						font-size:20px;
						font-weight:bold;
						padding:13px 32px;
						text-decoration:none;
						text-shadow:0px 1px 0px #3d768a;'href='http://localhost/phpprojects/lunchbox1/emailver.php?ViD=".$unique_key."'>CLICK HERE</a><br>
						<p>Best Regards,<br />Your Webmaster hahaha</p>
						";
						$companyname='Lunchbox.io';
						$subject='Verify your '.$companyname.' account';
						$mailer= new Mailerclass();
						$mailer->sendemail($subject,$uemail,$mailbody,'');
						

				}
				else{
					echo'error in database!!!!';
					$this->db->error;
				}
				return true;
			}
			else{
				//navigate to error page
				$usererror='user with the same credentials exists';
				$usererrorenc=md5($usererror);
				header("Location: http://localhost/phpprojects/lunchbox1/error/accerror.php?error=".$usererrorenc."");
				return false;
			}
			
			
    }
			
	//login in users for the first time
	public function check_firstlogin($username, $upassword){

		$username=trim($username);
		$lupassword=trim($upassword);
		//check if user exists
		$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
		$query = "SELECT * from users WHERE username='$username' and userpassword='$lupassword'";
		$result= $connect->prepare($query);
		$result->execute();
		$rowcount = $result->rowCount();//count number of users with the same credentials
        if($rowcount == 1)
        {
		$stmnt = $result->fetchAll();	//fetch all rows		
				foreach ($stmnt as $count_row) {
					
					if($count_row['active']==1){
							// If the password inputs matched the hashed password in the form
							// log them in.
							//echo '<script type="text/javascript">alert("logging you in")</script>';
							header("Location:http://localhost/phpprojects/lunchbox1/accounttype.php?AiD=".$count_row['uniqueid']."");

					}
					//if user is not verified
					else{
						//now check if user is verified
						//because the user already exists I'm just going to check username or email
						//and checkins status of activity if it equals 1 which means it is verified
						$getmail = "SELECT * from users WHERE username='$username' and active=0";
						$resultmail= $connect->prepare($getmail);
						$resultmail->execute();
						$mresult = $resultmail->fetchAll();
						foreach ($mresult as $column) {
							
							header("Location: http://localhost/phpprojects/lunchbox1/error/notactive.php?LiD='".$column['uniqueid']."'");
						}
	
	
					}
				}               
	    }
			else{
				$query = "SELECT * from users WHERE username='$username'";
				$result= $connect->prepare($query);
				$result->execute();
				$rowcount = $result->rowCount();//count number of users with the same credentials
				if($rowcount == 0)
				{
					echo '<script type="text/javascript">alert("Username does not exist")</script>';

				}
				else{
					
					echo '<script type="text/javascript">alert("Wrong password")</script>';

				}
			}			
		
		
	}
	 //loging in users
		public function check_login($emailusername, $upassword){

		$emailusername=trim($emailusername);
		$upassword=trim($upassword);
        $hashed_password = password_hash($upassword, PASSWORD_DEFAULT);
		//check if user exists
		$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
		$query = "SELECT * from users WHERE email='$emailusername' or username='$emailusername' and userpassword='$hashed_password'";
		$result= $connect->prepare($query);
		$result->execute();
		$rowcount = $result->rowCount();//count number of users with the same credentials
        if($rowcount == 1)
        {
		$stmnt = $result->fetchAll();	//fetch all rows		
				foreach ($stmnt as $count_row) {
					
					if($count_row['active']==1){
						if(password_verify($upassword, $hashed_password)) {
							// If the password inputs matched the hashed password in the form
							// log them in.
							$_SESSION['login'] = true; // this login var will use for the session 
							$_SESSION['id'] = $count_row['id'];
							$uid=$count_row['uniqueid'];
							header("location:home.php?HiDu2=".$uid."");
							return true;
						} 
						else{
							echo '<script type="text/javascript">alert("this doesnt normally happen but your password 
							does not match with the one in our database please refresh and enter again")</script>';
							return false;
						}
					}
					//if user is not verified
					elseif($count_row['active']==0){
						//now check if user is verified
						//because the user already exists I'm just going to check username or email
						//and checkins status of activity if it equals 1 which means it is verified
						$getmail = "SELECT username from users WHERE email='$emailusername' or username='$emailusername' and active=0";
						$resultmail= $connect->prepare($getmail);
						$resultmail->execute();
						$mresult = $resultmail->fetchAll();
						foreach ($mresult as $column) {
							header("Location: http://localhost/phpprojects/lunchbox1/error/notactive.php?LiD='".$column['uniqueid']."'");
						}
	
	
					}
				}               
	    }
			
		else{
			$query = "SELECT * from users WHERE email='$emailusername'";
			$result= $connect->prepare($query);
			$result->execute();
			$rowcount = $result->rowCount();//count number of users with the same credentials
			if($rowcount == 0)
			{
				echo '<script type="text/javascript">alert("Email does not exist")</script>';

			}
			else{
				$query = "SELECT * from users WHERE username='$emailusername'";
				$result= $connect->prepare($query);
				$result->execute();
				$rowcount = $result->rowCount();//count number of users with the same credentials
				if($rowcount == 0)
				{
					echo '<script type="text/javascript">alert("Username does not exist")</script>';

				}
				else{
					
					echo '<script type="text/javascript">alert("Wrong password")</script>';

				}
			}			
		}
		
	}

	//creating a function to fetch username
	public function get_username($uid){
		$query = "SELECT username FROM users WHERE id = '$uid'";
		
		$result = $this->db->query($query) or die($this->db->error);
		
		$user_data = $result->fetch_array(MYSQLI_ASSOC);
		echo $user_data['username'];
		
	}
	//creating a function to fetch email address
	public function get_email($uid){
		$query = "SELECT email FROM users WHERE id = '$uid'";
		
		$result = $this->db->query($query) or die($this->db->error);
		
		$user_data = $result->fetch_array(MYSQLI_ASSOC);
		echo $user_data['email'];
		
	}
	//function to set account type
	public function setaccount($uniqueuserid,$type,$bal){
		$date2=date('mY');
		$createaccount = "INSERT INTO account SET uniqueid='$uniqueuserid', acctype='$type',accountbalance='$bal', claps=0, privatepostsread=0,postscreated=0,moneygained=0,moneydonated=0 ,resetdate='$date'";
		$result=$this->db->query($createaccount) or die($this->db->error);
		if(isset($result)){
			//do nothing
			//echo '<script type="text/javascript">alert("inserted")</script>';
			return true;
		}
		else{
			//update account details
			return false;
		}
	}
	//function to update account deatils
	public function update($uniqueuserid,$type,$bal){
		//first check if user exists in database just to be sure=
		$checkuser = "SELECT * FROM account WHERE uniqueid='$uniqueuserid'";
		$checkuserresult=$this->db->$checkuser or die($this->db->error);
		if(isset($checkuser)){
			$createaccount = "UPDATE account SET acctype='$type',accountbalance='$bal' WHERE uniqueid='$uniqueuserid'";
			$result=$this->db->query($createaccount) or die($this->db->error);
		}
	}
	//function to add topics bc I could not find all information I needed on adding arrays to database
	public function addtopics($uniqueuserid,$topic)
	{
		$addtopic="INSERT INTO usertopics SET uniqueid='$uniqueuserid', nameoftopic='$topic'";
		$result=$this->db->query($addtopic) or die($this->db->error);
		if(isset($result)){
			//navigate into next page
		}
	}
	//function to add articles to table with their topics
	public function articletopics($article_title,$topic,$uid)
	{	$utitleid= $article_title.$uid.date('mY');
		$uniquetitleid=md5($utitleid);
		$addtopic="INSERT INTO articletopics SET articleuid='$uniquetitleid', topic='$topic'";
		$result=$this->db->query($addtopic) or die($this->db->error);
		if(isset($result)){
			//navigate into next page
		}

	}
	//add article details to the databse
	public function addarticle($imgtmp,$image,$article_content,$article_accessibility,$article_title,$uid)
	{		$utitleid= $article_title . $uid . date('mY');
			$uniquetitleid=md5($utitleid);
			$dateposted=date('mY');
            // image file directory
			$target = "articleimages/".basename($image);
			//first check the type of account the user has
			//if it's free then they can post 8 articles and all articles should be public
			$addarticle="INSERT INTO articles SET uniqueid='$uid', aimage='$image', article='$article_content',title='$article_title',accessibility='$article_accessibility',uniquetitleid='$uniquetitleid',dateposted='$dateposted'";
			$result=$this->db->query($addarticle) or die($this->db->error);
			if(isset($result)){
				//navigate to the next page
				if (move_uploaded_file($imgtmp, $target)) {
					echo "Image uploaded successfully";
					$dateposted=date('mY');
					$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
					$checkaccount="SELECT * FROM account WHERE uniqueid='$uid'";
					$statement = $connect->prepare($checkaccount);
					$statement->execute();
					$no_of_row = $statement->rowCount();
					//if statement to check whether the unique id belongs to a user
					if($no_of_row > 0)
					{
						foreach ($statement as $accountrow) {
					//add to number of posts created in accounts database
						$noofpost=(int)$accountrow['postscreated'];
						$noofposts=$noofpost+1;
						$addposts="UPDATE account SET postscreated=$noofposts WHERE uniqueid='$uid' ";
						$resulttt=$this->db->query($addposts) or die($this->db->error);
							if (isset($resulttt)) {
								# code...
								echo "result ran";
								return true;
							}
							else{
								echo "here";
							}
						}
					}

					else{
								echo "here";
							}
				}else{
					echo "Failed to upload image";
				}	
			}
			else{
				return false;
			}
	}
	//function to validate posting of articles based on account type
	public function article_validation($uid)
	{
		# code...
		//start by fetching the month and year that we are in
		//this is for making sure the month and year are adhered to
		$dateposted=date('mY');
		$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
		$checkaccount="SELECT * FROM account WHERE uniqueid='$uid'";
		$statement = $connect->prepare($checkaccount);
		$no_of_row = $statement->rowCount();
		//if statement to check whether the unique id belongs to a user
		if($no_of_row > 0)
		{
			foreach ($statement as $accountrow) {
				$accountype=trim($accountrow['acctype']);
					//now check the number of articles they have posted
					$checkarticles="SELECT * FROM articles WHERE uniqueid='$uid' AND dateposted='$dateposted'";
					$stmnt = $connect->prepare($checkarticles);
					$rowno = $stmnt->rowCount();
					//if statement to check whether the unique id has articles

					//for free account
					if ($accountype=='free') {
					if($rowno >= 8)
					{
						echo '<script type="text/javascript">alert("you have reached the maximum limit for this month<br> for the free account plan")</script>';
					}
					else{
						//now check if user selected private article
						if ($article_accessibility < 1) {
							# code...
							//insert into database
						return true;
						}
						else{
							return false;
							echo '<script type="text/javascript">alert("your account does not allow private articles")</script>';
						}


					}

					}
					//for 5$ accounts
					elseif ($accountype=='5$') {
						if($rowno >= 16)
						{
							echo '<script type="text/javascript">alert("you have reached the article post limit for this month<br> for the 5$ account plan")</script>';
						}
						else{
							//check if article is public or private
							if ($article_accessibility>0) {
								//now check the number of private posts made
								//remember they can make 3 private posts only per month
								$checkprivate="SELECT * FROM articles WHERE uniqueid='$uid' AND dateposted='$dateposted' AND accessibility=1 ";
								$stmnt = $connect->prepare($checkprivate);
								$no_of_private = $stmnt->rowCount();
								if($no_of_private >= 3)
								{	return false; 
									echo '<script type="text/javascript">alert("you have reached the maximum limit for posting private articles<br> for the 5$ account plan<br>update to post more.")</script>';
								}
								else{
									return true;

								}

							}
							//if they are posting public articles they may post them
							else{
								return true;
							}
						}

					}
					//for 10$ accounts
					elseif ($accountype=='10$') {
						if($rowno >= 25)
						{
							echo '<script type="text/javascript">alert("you have reached the article post limit for this month<br> for the 10$ account plan")</script>';
						}
						else{
							//check if article is public or private
							if ($article_accessibility>0) {
								//now check the number of private posts made
								//remember they can make 3 private posts only per month
								$checkprivate="SELECT * FROM articles WHERE uniqueid='$uid' AND dateposted='$dateposted' AND accessibility=1 ";
								$stmnt = $connect->prepare($checkprivate);
								$no_of_private = $stmnt->rowCount();
								if($no_of_private >= 9)
								{	return false;
									echo '<script type="text/javascript">alert("you have reached the maximum limit for posting private articles<br> for the 10$ account plan<br>update to post more.")</script>';
								}
								else{
									//if they have not reached the limit they may continue
									return false;

								}

							}
							//if they are posting public articles they may post them
							else{
									return true;
								}
							}
						}

					}
			}
			else{
				return false;
			}
		}
		//function to display articles based on your topics
		public function get_articles($userid)
		{
			# code...
			$query="SELECT nameoftopic FROM usertopics WHERE uniqueid='$userid'";
			$result = $this->db->query($query) or die($this->db->error);
			$user_data = $result->fetch_array(MYSQLI_ASSOC);
			$count_row = $result->num_rows;
			//checking if user has topics
			if ($count_row > 0) {
				//now find articles based on topics selected
				$usertopics=$user_data['nameoftopic'];
				$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
				$topics="SELECT articleuid FROM articletopics WHERE topic='$usertopics'";
				$statement = $connect->prepare($topics);
				$statement->execute();
				$row_count = $statement->rowCount();
				//checking if topics have posted articles present
				if($row_count>0){
					$topic_data = $statement->fetchAll();
					//select articles with the articleuid from articles table
					foreach ($topic_data as $topicsdata) {
						# code...
						$articleid = $topicsdata['articleuid'];
						$query="SELECT * FROM articles WHERE uniquetitleid='$articleid'";
						$statement = $connect->prepare($query);
						$statement->execute();
						//$row_count = $statement->rowCount();
						//$result=$this->db->query($query) or die ($this->db->error);
						$roww = $statement->fetchAll();
						foreach ($roww as $rowtwo){
							if ($rowtwo['accessibility']<1) {
								//means it's a public article
								$article_accessibility='public';
							}
							elseif($rowtwo['accessibility']>0){
								$article_accessibility='private';
							}
							//only display articles that are not written by the logged in user
							$query="SELECT * FROM users WHERE uniqueid='".$rowtwo['uniqueid']."'";
							$statement = $connect->prepare($query);
							$statement->execute();
							$rows = $statement->fetchAll();
							foreach ($rows as $row){
								if ($userid!=$row['uniqueid']) {
									# code...
									$author=$row['username'];
									echo"<a href='http://localhost/phpprojects/lunchbox1/viewarticle.php?UviAD=".urlencode($userid)."&UnPID=".urlencode($articleid)."'/>";
									echo '<div style="background:#F1F1F1; border:0 solid black; border-radius:3px; 
									box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; padding:0; 
									display:inline-block; margin:20px; hover">';
									echo "<img style='height:200px; width:auto;' src='articleimages/".$rowtwo['aimage']."' />";
									echo'<br>';
									echo '
									<span class="article_title" style="padding-left:4px;color:black; font-size:46px;">'.$rowtwo['title'].'</span>
									<p style="padding-left:4px;color:black; font-size:26px;">'.$article_accessibility.'</p>
									';
									echo '<p style="padding-left:4px;color:black; font-size:26px;">author:'.$author.'</p>';
									echo '</div>';
								}
						
							}
							
	
						}
					}

				}


			}

		}
	//display users articles on 'upload article' page
	public function display_my_articles($uid)
	{
		# code...
		$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
		$query="SELECT * FROM articles WHERE uniqueid= '$uid'";
		$statement=$connect->prepare($query);
		$statement->execute();
		$row_count = $statement->rowCount();
		if($row_count>0){
			//if user has posts get them then display them
			$rowselect=$statement->fetchAll();
			foreach($rowselect as $row) {
				# code...
				$image=$row['aimage'];
				$title=$row['title'];
				$article=$row['article'];
				if (is_null($row['clapsrecieved'])) {
					# code...
					$claps=0;
				}
				else{
					$claps=$row['clapsrecieved'];

				}
				if(is_null($row['moneyearned'])){
					$money_earned=0;
				}
				else{
					$money_earned=$row['moneyearned'];

				}		
				if ($row['accessibility']<1) {
					//0 means it's a public article
					$accessibility='public';
				}
				else{
					$accessibility='private';
				}
				echo'<div>';
				echo "<img style='height:200px; width:auto;' src='articleimages/".$image."' />";
				echo'<br>';
				echo'<p>Title: '.$title.'</p>';
				echo'<p>accessibility: '.$accessibility.'</p>';
				echo'<p>Claps received: '.$claps.'</p>';
				echo'<p>Money earned: '.$money_earned.'</p>';
				echo'</div>';
			}

		}
		else{
			echo'<p>problem here</p>';
		}



	}
	//add number of viewed articles for user
	public function addarticlesread($uid,$articleid)
	{
		# code...
		//first add to the total number of posts readby the user
		$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
		$articlesread="SELECT * FROM account WHERE uniqueid='$uid'";
		$statement = $connect->prepare($articlesread);
		$statement->execute();
		$no_of_row = $statement->rowCount();
		//if statement to check whether the unique id belongs to a user
		if($no_of_row > 0)
		{
			foreach ($statement as $accountrow) {
				//check account type
				$accountype="SELECT * FROM account WHERE uniqueid='$uid'";
				$stmnt=$connect->prepare($accountype);
				$stmnt->execute();
				$row_count=$statement->rowCount();
				foreach ($stmnt as $row) {
					# code...
						$acctype=(int)$row['acctype'];
						//no of private posts should be two per month
						//select all from articles where uid is users the month is the present month and accessibility is 1
						$dateposted=date('mY');
						$acctype2="SELECT * FROM articles WHERE  uniqueid='$uid' AND dateposted='$dateposted' AND accessibility=1";
						$stmnt2=$connect->prepare($acctype2);
						$stmnt2->execute();
						$rows=$stmnt2->fetchAll();
						$noofrows=$stmnt2->rowCount();
						if($noofrows>0){
													//check if it's free
					if($acctype=='free'){
						if($rows<3){
							//add to number of posts read in accounts database 

						}
						else{
							echo"<p>you have reached your limit for this month</p>";
						}
					}
					elseif($acctype=='5$'){
						if($rows<7){
							//add to number of posts read in accounts database 

						}
						else{
							echo"<p>you have reached your limit for this month</p>";
						}
					}
					elseif($acctype=='10$'){
						if($rows<13){
							//add to number of posts read in accounts database
						}
						else{
							echo"<p>you have reached your limit for this month</p>";
						}
					}
					$noofpost=(int)$accountrow['privatepostsread'];
					$noofposts=$noofpost+1;
					$addposts="UPDATE account SET privatepostsread=$noofposts WHERE uniqueid='$uid' ";
					$resulttt=$this->db->query($addposts) or die($this->db->error);
						}

						
				}


			}
		}
		//check number of articles read and if user has exceeded maximum private articles or public 
		//articles display a popup or redirect*

	}
	//get title of article
	public function get_title($titleid)
	{
		$query="SELECT title FROM articles WHERE uniquetitleid='$titleid'";		
		$result = $this->db->query($query) or die($this->db->error);
		$user_data = $result->fetch_array(MYSQLI_ASSOC);
		echo $user_data['title'];

	}
	//display article
	public function get_article($articleid)
	{
		$query="SELECT article FROM articles WHERE uniquetitleid='$articleid'";		
		$result = $this->db->query($query) or die($this->db->error);
		$user_data = $result->fetch_array(MYSQLI_ASSOC);
		echo $user_data['article'];
		
	}
	//display image
	public function get_image($articleid)
	{
		$query="SELECT aimage FROM articles WHERE uniquetitleid='$articleid'";		
		$result = $this->db->query($query) or die($this->db->error);
		$user_data = $result->fetch_array(MYSQLI_ASSOC);
		$image=$user_data['aimage'];
		echo "<img style='height:400px; width:auto;' src='articleimages/".$image."' />";

		
	}
	//function to insert into clapped table
	public function add_clapped($article,$uid){
		$query="INSERT INTO clapped SET articleid='$article', uniqueid='$uid' ";
		//leave clapped status as null until the user clicks the clap button
		$result=$this->db->query($query) or die($this->db->error);
	}
	//function to update clap status
	public function Update_clap_status($articleid,$uid)
	{
	//if clap has been added to articles account add it to clapped articles for the user
	$addposts="UPDATE clapped SET clapstatus=1 WHERE articleid='$articleid' AND uniqueid='$uid'";
	$added=$this->db->query($addposts) or die($this->db->error);
	}
	//function to remove clap status
	public function Remove_clap_status($articleid,$uid)
	{
		//if clap has been added to articles account add it to clapped articles for the user
		$addposts="UPDATE clapped SET clapstatus=0 WHERE articleid='$articleid' AND uniqueid='$uid'";
		$added=$this->db->query($addposts) or die($this->db->error);
	}
	//function to add claps
	public function add_claps($articleid,$uid)
	{	
		$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
		$addclaps="SELECT * FROM account WHERE uniqueid='$uid'";
		$statement = $connect->prepare($addclaps);
		$statement->execute();
		$no_of_row = $statement->rowCount();
		if($no_of_row > 0)
		{
			foreach ($statement as $accountrow) {
				if($accountrow['acctype']=='free'){
					if ($accountrow['claps']<7) {
						# if account is free we only allow 6 claps
					}
					else{
						return false;
						echo '<script type="text/javascript">alert("you have exceeded your clap limit for this month")</script>';
					}
				}
				elseif($accountrow['acctype']=='5$'){
					if ($accountrow['claps']<13) {
						# if account is 5$ we only allow 12 claps
					}
					else{
						echo '<script type="text/javascript">alert("you have exceeded your clap limit for this month")</script>';
						return false;
					}
				}
				elseif($accountrow['acctype']=='10$'){
					if ($accountrow['claps']<21) {
						# if account is 10$ we only allow 20 claps
					}
					else{
						echo '<script type="text/javascript">alert("you have exceeded your clap limit for this month")</script>';
						return false;
					}
				}
					$clap=(int)$accountrow['claps'];
					$claps=$clap+1;
					$addposts="UPDATE account SET claps='$claps' WHERE uniqueid='$uid' ";
					$resulttt=$this->db->query($addposts) or die($this->db->error);
					if(isset($resulttt)){
					//if clap has been added to user account add it to article count
					$articlesread="SELECT * FROM articles WHERE uniquetitleid='$articleid'";
					$statement = $connect->prepare($articlesread);
					$statement->execute();
					$no_of_row = $statement->rowCount();
					//if statement to check whether the unique id belongs to a user
					if($no_of_row > 0)
					{
						foreach ($statement as $articlerow) {
							if(is_null($articlerow['clapsrecieved'])){
								$clap=0;
							}
							else{
								$clap=(int)$articlerow['clapsrecieved'];
							}
								$claps=$clap+1;
								$addposts="UPDATE articles SET clapsrecieved='$claps' WHERE uniquetitleid='$articleid' ";
								$resulttt=$this->db->query($addposts) or die($this->db->error);
								
						}
					}
					}

			}
		}

	}
	//function to check clap status
	public function check_claps($articleid,$uid)
	{
		$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
		$query="SELECT * FROM clapped WHERE  uniqueid='$uid' AND articleid='$articleid' AND clapstatus=1";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result=$statement->rowCount();
		if($result>0){
			return true;
		}
		else{
			return false;
		}

	} 
	//function to reset claps monthly
	public function reset_claps($uid)
	{
		$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
		$date_today="SELECT * FROM account WHERE uniqueid='$uid'";
		$statement = $connect->prepare($date_today);
		$statement->execute();
		foreach ($statement as $daterow) {
			//first create a variable to find the date now
			$datenow=date('mY');
			//compare that variable to the date in the database
			if ($daterow['resetdate']==$datenow) {
				//do nothing
			}
			else{
				//if they don't match then reset the claps to 0 and the date to the date now
				$addposts="UPDATE account SET claps=0, resetdate='$datenow' WHERE uniqueid='$uid' ";
				$result=$this->db->query($addposts) or die($this->db->error);
			}
		}
	}
	//function to calculate the money paid
	public function money_paid($articleid,$uid)
	{
		//select accountbalance from acocunt table
		//select claps from account
		//divide the two
		//update this value to moneydonated
		//update accountbalance with the new value*(by subtracting the value from the account balance)
		//update article money earned with this value
		$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
		$query="SELECT * FROM account WHERE  uniqueid='$uid'";
		$statement = $connect->prepare($query);
		$statement->execute();
		foreach ($statement as $accbal) {
		//$result=$this->db->query($query) or die ($this->db->error);
		//while ($accbal = $statement){
				$accountbalance=(double)$accbal['accountbalance'];
				$claps=(int)$accbal['claps'];
				$oldmoney=(double)$accbal['moneygained'];
				if ($claps=1) {
					# code...
					$moneydonated=$accountbalance;
				}
				elseif($claps=0){
					$moneydonated=0;
				}
				elseif($claps>1){
					$moneydonated = $accountbalance/$claps;

				}
					$newbalance=$accountbalance-$moneydonated;
					$update_moneydonated="UPDATE account SET moneydonated='$moneydonated', accountbalance='$newbalance'  WHERE uniqueid='$uid'";
					$result=$this->db->query($update_moneydonated) or die($this->db->error);
					if(isset($result)){
						$article="SELECT * FROM articles WHERE  uniquetitleid='$articleid'";
						$stmnt = $connect->prepare($article);
						$stmnt->execute();
						foreach ($stmnt as $articlerow) {
							$userid=$articlerow['uniqueid'];
							$oldbalance=(double)$articlerow['moneyearned'];
							$moneygained=$oldbalance+$moneydonated;
							$money_earned="UPDATE articles SET moneyearned='$moneygained' WHERE uniquetitleid='$articleid' ";
							$reslt=$this->db->query($money_earned) or die($this->db->error);
							if(isset($reslt)){
								//update money to user account
								//fetch userid from titleid
								$newmoney=$oldmoney+$moneydonated;
								$update_moneyearned="UPDATE account SET moneygained='$newmoney' WHERE uniqueid='$userid'";
								$result=$this->db->query($update_moneyearned) or die($this->db->error);
							}
							else{
								echo 'problem here';
							}

						}
				}
				else{
					echo 'error couldnt update';
				}

			
		}
	}
	//function to reset money every month
	Public function reset_money($uid)
	{
		//selct user from database
		$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
		$balance="SELECT * FROM account WHERE uniqueid='$uid'";
		$statement = $connect->prepare($balance);
		$statement->execute();
		foreach ($statement as $daterow) {
			//first create a variable to find the date now
			$datenow=date('mY');
			//compare that variable to the date in the database
			if ($daterow['resetdate']==$datenow) {
				//do nothing
			}
			else{
				//if they don't match then reset the money to 0 and the date to the date now
				if($daterow['acctype']=='free'){
					$bal=0;
				}
				elseif($daterow['acctype']=='5$'){
					$bal=5;
				}
				elseif($daterow['acctype']=='10$'){
					$bal=10;
				}
				$changedate="UPDATE account SET accountbalance='$bal', resetdate='$datenow' WHERE uniqueid='$uid' ";
				$result=$this->db->query($changedate) or die($this->db->error);
			}
		}
	}
	//function to validate a credit card
	Public function validatecard($card,$number)
	{

		$cardtype = array(
			"visa"       => "/^4[0-9]{12}(?:[0-9]{3})?$/",
			"mastercard" => "/^5[1-5][0-9]{14}$/",
			"amex"       => "/^3[47][0-9]{13}$/",
			"discover"   => "/^6(?:011|5[0-9]{2})[0-9]{12}$/",
		);

		if (preg_match($cardtype['visa'],$number))
		{
		$type= "visa";
			if(strcasecmp($card, $type) == 0)
			{
				// card is valid
				echo'something is right here';
				return true;
			} else {
				echo ' card is invalid';
			}
		}
		else if (preg_match($cardtype['mastercard'],$number))
		{
		$type= "mastercard";
			if(strcasecmp($card, $type) == 0)
			{
				// card is valid
				return true;

			} else {
				echo ' card is invalid';
			}
		}
		else if (preg_match($cardtype['amex'],$number))
		{
		$type= "amex";
			if(strcasecmp($card, $type) == 0)
			{
				// card is valid
				return true;

			} else {
				echo ' card is invalid';
			}
		}
		else if (preg_match($cardtype['discover'],$number))
		{
		$type= "discover";
			if(strcasecmp($card, $type) == 0)
			{
				// card is valid
				return true;

			} else {
				echo ' card is invalid';
			}
		}
		else
		{
			return false;
		} 
 	}
	//function to start the session
	public function get_session(){
	    return $_SESSION['login'];
		}
	//function to logout the user
	public function user_logout() {
	    $_SESSION['login'] = FALSE;
		unset($_SESSION);
	    session_destroy();
	}
	//function for resetting password by sending an email first
	public function resetpasswordlink($emailusername){
		$query = "SELECT * from users WHERE email='$emailusername' or username='$emailusername'";
		$result = $this->db->query($query) or die($this->db->error);
		$connect = new PDO('mysql:host=localhost;dbname=lunchboxone', 'root', '');
		//checking if user exists
		$countrow= $result ->num_rows;
		if ($countrow==1) {
			//send email for password verification
			$getmail = "SELECT email,username from users WHERE email='$emailusername' or username='$emailusername'";
			$resultmail= $connect->prepare($getmail);
			$resultmail->execute();

				$mresult = $resultmail->fetchAll();
				foreach ($mresult as $column) {
					$em=trim($column['email']) ;
					$na=$column['username'];
						$uniquekey = $column['uniqueid'];
						$subject='Password Reset Request';
						$mailbody = "
						<p>Hi ".$na.",</p>
						<p>We have received a request to reset your password</p>
						<p>Please Open this link to reset password - http://localhost/phpprojects/lunchbox1/resetpassword.php?qid=".$uniquekey."
						<p>and if it wasnt you please ignore this email.
						<p>Best Regards,<br/> Leon the Webmaster</p>
						";
						$mailer= new Mailerclass();
						$mailer->sendemail($subject,$em,$mailbody,'');  
						if($mailer){
							echo '<script type="text/javascript">alert("Check your email for the reset link")</script>';
							return true;
						}
						else{
							echo '<script type="text/javascript">alert("something went wrong on our end. Please try again")</script>';
							return false;
						}
				}			     
	        }
			
		else{
			$query = "SELECT * from users WHERE email='$emailusername'";
			$result= $connect->prepare($query);
			$result->execute();
			$rowcount = $result->rowCount();//count number of users with the same credentials
			if($rowcount == 0)
			{
				echo '<script type="text/javascript">alert("Email does not exist")</script>';

			}
			else{
				$query = "SELECT * from users WHERE username='$emailusername'";
				$result= $connect->prepare($query);
				$result->execute();
				$rowcount = $result->rowCount();//count number of users with the same credentials
				if($rowcount == 0)
				{
					echo '<script type="text/javascript">alert("Username does not exist")</script>';

				}
				else{
					
					echo '<script type="text/javascript">alert("Wrong password")</script>';

				}
			}
		}
	
		}
	   //this function is for setting subscription to '1'
	   public function setstatus($email){
        //check if email exists on database
        $uemail=trim($email);
			$query = "SELECT * FROM users WHERE  email='$uemail'";
			$result = $this->db->query($query) or die($this->db->error);
            $count_row = $result->num_rows;	
			//if the user is not in the database, then there is an error
			if($count_row == 1){
                $query = "UPDATE users SET  subscription=1 WHERE email='$uemail'";
                $resultt = $this->db->query($query) or die ($this->db->error);
                if(isset($resultt)){
                    //send a mail for change subscription status
                    //first find the username
					$uname= "SELECT username FROM users WHERE email='$uemail'";
					$result=$this->db->query($uname) or die($this->db->error);
                    	/*send the email*/
						//Create a new PHPMailer instance
						$mailbody = "
						<p>Hello there ".$uname.",</p>
                        <p>Thanks for Subscribing to our newsletter.
                        <p>Best Regards,<br />Your WebMASTER hahaha</p>
						";
						$companyname='lunchbox.io';
						$subject='Subscription to '.$companyname.' newsletter';
						$mailer= new Mailerclass();
						$mailer->sendemail($subject,$uemail,$mailbody,'');
                }
            }
	}
	//admin functions
		//send newsletter to subscribed users

		//send email to users based on topics they've subscibed to
		//create admin
		public function Create_admin($username, $upassword,$email)
		{
			$hashed_password = password_hash($upassword, PASSWORD_DEFAULT);
			$query="INSERT INTO admins SET username='$username', adminpassword='$hashed_password', email='$email' ";
			$result= $this->db->query($query) or die($this->db->error);
		}
		 //loging in Admin
		 public function check_adminlogin($username, $upassword){
			$connect = new PDO('mysql:host=localhost;dbname=lunchboxone','root','');
			$hashed_password = password_hash($upassword, PASSWORD_DEFAULT);
			$query = "SELECT * FROM admins WHERE  username='$username' AND adminpassword='$upassword'";
			$statement=$connect->prepare($query);
			$statement->execute();			
			$count_row = $statement->rowCount();
			//checking if user exists
			if ($count_row == 1) {
				//log them in
					foreach ($statement as $adminrow) {
						# code...
						$id=$adminrow['id'];
						$unique_key=md5($hashed_password,$username,date('mY'));
						echo 'logged';
					header("Location: http://localhost/phpprojects/lunchbox1/adminhome.php?ue&ScN67678dcssdsd=".$unique_key." &&uqYiDHHH=".$id."");
					}
					
			}
			else{
				//echo error
				echo 'error';
				return false;
			}
		
		}
			//get inactive users
		public function activeusers(){
			$query="SELECT username FROM users WHERE active = 1";
			$result=$this->db->query($query) or die ($this->db->error);
			echo '<table>';
			while ($rowtwo = $result->fetch_array(MYSQLI_ASSOC)){
				echo '<tr>
					
					<td><font size="5" face="Lucida Sans Unicode" color=black>'.$rowtwo['username'].'</td>
					</tr>';
			}
			echo '</table>';
		}
		//get inactive users
		public function inactiveusers(){
			$query="SELECT username FROM users WHERE active = 0";
			$result=$this->db->query($query) or die ($this->db->error);
			echo '<table>';
			while ($rowtwo = $result->fetch_array(MYSQLI_ASSOC)){
				echo '<tr>
					
					<td><font size="5" face="Lucida Sans Unicode" color=black>'.$rowtwo['username'].'</td>
					</tr>';
			}
			echo '</table>';
		}
		//creating a function to fetch admin username
		public function get_adminusername($userid){
			$query = "SELECT username FROM admins WHERE id = '$userid'";
			
			$result = $this->db->query($query) or die($this->db->error);
			
			$user_data = $result->fetch_array(MYSQLI_ASSOC);
			echo $user_data['username'];
			
		}
	
}