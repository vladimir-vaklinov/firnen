<?php
/**
 * <h1>Website Core </h1>
 * In this class we collect all simple
 * basic website functions.
 *
 * @version 0.2
 * @since 2014-08-20
 */

class websiteCore{


	/**
	 * <h1> Website Initiation </h1>
	 * This method is called when the class is created.
	 *
	 * @version 0.2
	 * @since 2014-08-20
	 */

	public function __construct(){
		global $db;

		/**
		 * Here we escape the search string
		 */
		if(isset($_POST['search']) && !empty($_POST['search']))$GLOBALS['searchstr']=$db->real_escape_string($_POST['search']);

	}


	/**
	 * <h1>Main Navigation</h1>
	 * This method manages the main menu.
	 *
	 * @version 0.2
	 * @since 2014-08-20
	 * @return string
	 */

	public function mainNavigation(){

		$result='';$sel='class="active"';$markAsActive = array('','','','','');

		/**
		 * Checking if some of the menus is selected and it is - we
		 * mark it as selected
		 */
		if(isset($_GET['task_0']) && ($_GET['task_0']=='photos'))$markAsActive[0]=$sel;
		if(isset($_GET['task_0']) && ($_GET['task_0']=='albums'))$markAsActive[1]=$sel;
		if(isset($_GET['task_0']) && ($_GET['task_0']=='authors'))$markAsActive[2]=$sel;
		if(isset($_GET['task_0']) && ($_GET['task_0']=='signup'))$markAsActive[3]=$sel;
		if(isset($_GET['task_0']) && ($_GET['task_0']=='profile'))$markAsActive[4]=$sel;

		$result.='
				<li id="nav-photos" '.$markAsActive[0].'><a href="/photos/">Photos</a></li>
				<li id="nav-albums" '.$markAsActive[1].'><a href="/albums/">Albums</a></li>
				<li id="nav-authors" '.$markAsActive[2].'><a href="/authors/">Authors</a></li>';
		if(isset($_SESSION['user']['fname'])){
			$result.='
				<li id="nav-profile" '.$markAsActive[4].'><a href="/profile/">
					<img src="/assets/img/username_ico.jpg" alt="'.$_SESSION['user']['fname'].'" />'.$_SESSION['user']['fname'].'</a></li>
			';
		}else{
			$result.='
				<li id="nav-signup" '.$markAsActive[3].'><a href="/signup/">Register / Login</a></li>
			';
		}
		return $result;
	}

}