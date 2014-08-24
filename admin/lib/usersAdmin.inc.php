<?php

/**
 * <h1> Users admin </h1>
 *
 * Administration of the registered users on
 * the website.
 */

class usersAdmin{


	public function __construct(){
		global $db;

		if(isset($_POST['action']) && ($_POST['action'] == 'setstatus')){
			$status=0;
			if(isset($_POST['status']))$status=1;
			$db->query("UPDATE `users` SET `state`='".$status."' WHERE `uid`='".intval($_POST['uid'])."' ");
		}

		if(isset($_POST['action']) && ($_POST['action'] == 'deluser')){
			$uid=intval($_POST['uid']);
			$q=$db->query("SELECT * FROM `photos` WHERE `uid`='".$uid."' ");
			if($db->num_rows($q)){
				while($a=$db->fetch_array($q)){
					@unlink('../photos/'.$uid.'/'.$a['filename']);
					@unlink('../photos/'.$uid.'/t_'.$a['filename']);
				}
				rmdir('../photos/'.$uid);
			}
			$db->query("DELETE FROM `users` WHERE `uid`='".$uid."' ");
			$db->query("DELETE FROM `albums` WHERE `uid`='".$uid."' ");
		}


	}

	public function contents(){
		global $db;

		$result='
		<table id="userslist" border="1">
			<tr>
				<th width="20">ID</th>
				<th>First name</th>
				<th>Last name</th>
				<th width="200">EMail</th>
				<th width="50">Blocked</th>
				<th width="65">Delete</th>
			</tr>';

		$q=$db->query("SELECT * FROM `users` ORDER BY `uid` DESC");
		if($db->num_rows($q)){
			while($a=$db->fetch_array($q)){

				$status='';
				if($a['state']==1)$status=' checked="checked"';
				$result.='
				<tr>
					<td>'.$a['uid'].'</td>
					<td>'.$a['fname'].'</td>
					<td>'.$a['lname'].'</td>
					<td>'.$a['email'].'</td>
					<td>
						<form method="post">
							<input type="checkbox" name="status" value="1" onclick="this.form.submit()" class="inp" '.$status.'/>
							<input type="hidden" name="action" value="setstatus" />
							<input type="hidden" name="uid" value="'.$a['uid'].'" />
						</form>
					</td>
					<td>
						<form method="post">
							<button type="submit" name="submitform" class="btn"
								onclick="return confirm(\'Are u sure ?\')">Delete</button>
							<input type="hidden" name="action" value="deluser" />
							<input type="hidden" name="uid" value="'.$a['uid'].'" />
						</form>
					</td>
				</tr>';

			}
		}

		$result.='
		</table>';



		return $result;
	}

}