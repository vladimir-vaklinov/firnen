<?php

class login
{

	public function loginForm()
	{

		$result='';
		$backerr='';
		$passval='';

		if(isset($_POST['logincode'])){

			$passval=filter_var($_POST['logincode'],FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_HIGH);
			if($passval==$GLOBALS['adminpass']){
				$_SESSION['firnen_admin_login']=1;
			}else{
				$backerr='<h3 class="statusline">Wrong password</h3>';
			}
		}

		$result.='
		<div class="admin-login-form">
			'.$backerr.'
			<h2>Administration Login</h2>
			<form name="code" method="post" action="?">
				<label>Enter Password:</label>
					<input type="password" name="logincode" value="'.$passval.'" class="inp"/>
						<button type="submit" class="btn">ENTER</button>
			</form>
		</div>';

		return $result;

	}

}