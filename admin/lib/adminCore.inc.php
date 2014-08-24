<?php

/**
 * <h1> Admin core functions </h1>
 *
 */
class adminCore
{

	/**
	 * <h1> Admin base HTML </h1>
	 *
	 * @param $contents String HTML content do display
	 * @param string $pageTitle String Text to use for H1 title
	 * @return string
	 */
	public function contents($contents,$pageTitle='Firnen Admin panel')
	{

		$sel='class="active"';
		$markAsActive=array('', '', '', '', '', '');

		if(!isset($_GET['task_0'])&&(isset($_GET['task_0'])&&$_GET['task_0']=='')){
			$markAsActive[0]=$sel;
		}
		if(isset($_GET['task_0'])&&(in_array($_GET['task_0'], array('users')))){
			$markAsActive[1]=$sel;
		}
		if(isset($_GET['task_0'])&&(in_array($_GET['task_0'], array('settings')))){
			$markAsActive[2]=$sel;
		}


		$result='
		<div id="admin-wrapper">
			<aside id="menu">
				<a href="/admin/" '.$markAsActive[0].'>Home</a>
				<a href="/admin/users/" '.$markAsActive[1].'>Users</a>
				<a href="/admin/settings/" '.$markAsActive[2].'>Settings</a><br/>
				<a href="/admin/logout/">Logout</a>
			</aside>
			<div id="admin-content">
				<h1 id="admin-content-title">'.$pageTitle.'</h1>
				<div id="admin-content-in">
					'.$contents.'
				</div>
			</div>
		</div>';

		return $result;
	}


}
