<?php

/**
 * <h1> Sign Up </h1>
 *
 * @version 0.1
 * @since 2014-08-20
 */

class signUp{

	private $actionstate = 'init';
	private $errormsg = '';
	private $successmsg = '';

	public function actions(){
		global $db;

		if(isset($_POST['action']) && ($_POST['action'] == 'register')){

			if(
				!empty($_POST['fname']) &&
				!empty($_POST['lname']) &&
				!empty($_POST['email']) &&
				!empty($_POST['password'])
			){
				$fname = $db->real_escape_string($_POST['fname']);
				$lname = $db->real_escape_string($_POST['lname']);
				$email = $db->real_escape_string($_POST['email']);
				$password = $db->real_escape_string($_POST['password']);

				$q=$db->query("INSERT INTO `users` (`email`,`password`,`fname`,`lname`)
								VALUES ('".$email."','".md5($password)."','".$fname."','".$lname."') ");

				if($q != false){
					$id = $db->insert_id();
					$_SESSION['user']=array('id'=>$id,'fname'=>$fname,'lname'=>$lname,'email'=>$email);
					$this->actionstate = 'thankyou';
					$this->successmsg = 'Your registration completed successfully, click <a href="/newalbum/">here</a> to create your first album';
				}else{
					$this->errormsg = '<div class="errormsg">'.$db->error.'</div>';

				}
			}else{
				$this->errormsg = '<div class="errormsg">Please, fill all field in the form.</div>';
			}

		}

		if(isset($_POST['action']) && ($_POST['action'] == 'login')){

			if(
				!empty($_POST['email']) &&
				!empty($_POST['password'])
			){

				$email = $db->real_escape_string($_POST['email']);
				$password = $db->real_escape_string($_POST['password']);

				$q=$db->query("SELECT * FROM `users` WHERE `email`='".$email."' ");
				if($db->num_rows($q) > 0){
					while($a=$db->fetch_array($q)){
						if(md5($password) == $a['password']){
							$_SESSION['user']=array('id'=>$a['uid'],'fname'=>$a['fname'],'lname'=>$a['lname'],'email'=>$a['email']);
							header("Location:/");
							$this->actionstate = 'thankyou';
							$this->successmsg = 'Your are now logged in successfully';
						}else{
							$this->errormsg = '<div class="errormsg">This password does not match.</div>';
						}
					}
				}else{
					$this->errormsg = '<div class="errormsg">This email does not exist in our database.</div>';
				}
			}else{
				$this->errormsg = '<div class="errormsg">Email or password is empty ?</div>';
			}
		}

	}


	public function htmlForms(){

		$result='';

		if($this->actionstate == 'thankyou'){

			$result.='
			<div id="signup">
				<div class="backmsg">'.$this->successmsg.'</div>
			</div>';

		}elseif($this->actionstate == 'init'){

			$result.='
			<div id="signup">
				'.$this->errormsg.'

				<div id="register-form">
					<h1>Not a member? <strong>Register</strong></h1>
					<form method="post" action="/signup/">
						<p>
							<input type="text" name="fname" value="" placeholder="First name" required="required"/>
							<input type="text" name="lname" value="" placeholder="Last name" required="required"/>
						</p>
						<p>
							<input type="email" name="email" value="" placeholder="Email" required="required"/>
							<input type="password" name="password" value="" placeholder="Password" required="required"/>
						</p>
						<p><button type="submit" name="submit">Register</button></p>
						<input type="hidden" name="action" value="register"/>
					</form>
				</div>

				<div id="login-form">
					<h2>Login</h2>
					<form method="post" action="/signup/">
						<p><input type="email" name="email" value="" placeholder="Email" required="required"/></p>
						<p><input type="password" name="password" value="" placeholder="Password" required="required"/></p>
						<p><button type="submit" name="submit">Login</button></p>
						<input type="hidden" name="action" value="login"/>
					</form>
				</div>

			</div>
			';

		}


		return $result;
	}

}