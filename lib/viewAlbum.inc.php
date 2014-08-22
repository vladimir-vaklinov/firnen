<?php

/**
 * <h1> View Album </h1>
 * View album page with all item.
 *
 * @version 0.4
 * @since 2014-08-22
 */

class viewAlbum{

	public function contents(){
		global $db;

		$result='';

		$aid=0;
		if(isset($_GET['task_1'])&&(is_numeric($_GET['task_1']))){
			$aid=intval($_GET['task_1']);
		}else{
			@header("Location:/");
		}

		$uid=0;
		$albumname='';
		$username='';
		$image='';

		$q=$db->query("SELECT *,IFNULL( (SELECT `filename` FROM `photos` WHERE `photos`.`aid`=`albums`.`aid`
						ORDER BY `phid` DESC LIMIT 1), '') AS `photo` FROM `albums` WHERE `aid`='".$aid."' ");
		if($db->num_rows($q)>0){
			while($a=$db->fetch_array($q)){
				$uid=$a['uid'];
				$albumname=$a['name'];
				if(!empty($a['photo']))$image='/photos/'.$a['uid'].'/'.$a['photo'];
			}
		}

		$qu=$db->query("SELECT * FROM `users` WHERE `uid`='".$uid."' ");
		if($db->num_rows($qu)>0){
			while($a=$db->fetch_array($qu)){
				$username=$a['fname'];
			}
		}


		$result='
		<div id="view-album">
			<h2 id="view-album-username"><img src="/assets/img/user_ico.png" alt="'.$username.'" />'.$username.'</h2>

			<div id="view-album-blk">

				<div id="view-album-block">
					<img src="'.$image.'" alt="'.$albumname.'"/>
				</div>

			</div>

			<h2 id="view-album-name">'.$albumname.'</h2>

		</div>';






		return $result;

	}

}