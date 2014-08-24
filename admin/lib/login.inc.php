<?php

/**
 * <h1>Class login
 *
 * Login procedure for the admin panel.
 */
class login
{

	private $backerr='';


	/**
	 * Login procedure
	 */
	public function __construct()
	{

		if(isset($_POST['logincode'])){

			$passval=filter_var($_POST['logincode'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			if($passval==$GLOBALS['adminpass']){
				$_SESSION['firnen_admin_login']=1;
			}else{
				$this->backerr='<h3 class="statusline">Wrong password</h3>';
			}
		}

	}


	/**
	 * <h1> Admin Login form </h1>
	 * Login form for the admin panel.
	 *
	 * @return string
	 */
	public function loginForm()
	{

		$result='
		<div class="admin-login-form">
			'.$this->backerr.'
			<h2>Administration Login</h2>
			<form name="code" method="post" action="?">
				<label>Enter Password:</label>
					<input type="password" name="logincode" value="" class="inp"/>
						<button type="submit" class="btn">ENTER</button>
			</form>
		</div>';

		return $result;

	}

}