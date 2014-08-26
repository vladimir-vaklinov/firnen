<?php

/**
 * <h1> My Profile </h1>
 * This class manages the user's profile
 *
 * @version 0.3
 * @since 2014-08-21
 */
class myProfile
{

	private $backmsg='';

	public function __construct()
	{

		if(!isset($_SESSION['user']['id']))
			@header("Location:/");

	}

	public function actions()
	{
		global $db;

		if(isset($_POST['action'])&&($_POST['action']=='update')){

			$willUpdate=array();

			if(isset($_POST['fname'])&&!empty($_POST['fname'])){
				$fname=filter_var($_POST['fname'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$willUpdate[]="`fname`='".$fname."' ";
				$_SESSION['user']['fname']=$fname;
			}
			if(isset($_POST['lname'])&&!empty($_POST['lname'])){
				$lname=filter_var($_POST['lname'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$willUpdate[]="`lname`='".$lname."' ";
				$_SESSION['user']['lname']=$lname;
			}
			if(isset($_POST['password'])&&!empty($_POST['password'])){
				$password=filter_var($_POST['password'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$willUpdate[]="`password`='".$password."' ";
			}

			$updStr="";
			if(count($willUpdate)>0)
				$updStr=implode(",", $willUpdate);

			$q=$db->query("UPDATE `users` SET
							".$updStr."
							WHERE `uid`='".$_SESSION['user']['id']."'
							");

			if($q!=false){
				$this->backmsg='<div class="backmsg">Your profile updated successfully.</div>';
			}else{
				$this->backmsg='<div class="errormsg">'.$db->error.'</div>';

			}

		}


	}


	public function htmlForms()
	{
		global $db;

		$result='';
		$fname='';
		$lname='';

		$q=$db->query("SELECT * FROM `users` WHERE `uid`='".$_SESSION['user']['id']."' ");
		if($db->num_rows($q)>0){
			while($a=$db->fetch_array($q)){
				$fname=$a['fname'];
				$lname=$a['lname'];
			}
		}


		$result.='
		<div id="profile">
			'.$this->backmsg.'

			<div id="profile-form">
				<h1>Update your profile</h1>
				<form method="post" action="/profile/" class="frm">
					<p>
						<input type="text" name="fname" value="'.$fname.'" placeholder="First name" required="required"/>
						<input type="text" name="lname" value="'.$lname.'" placeholder="Last name" required="required"/>
					</p>
					<p>
						<input type="password" name="password" value="" placeholder="Password"/>
						<button type="submit" name="submit">Update</button>
					</p>
					<input type="hidden" name="action" value="update"/>
				</form>
			</div>
			<div id="profile-form-logout">
				<a href="/logout/">Logout</a>
			</div>
		</div>
		';

		return $result;
	}

}