<?php

/**
 * <h1> View Photo </h1>
 * This class display's the selected photo to any user of the website,
 * on separate page with rating and more.
 *
 * @version 0.3
 * @since 2014-08-21
 *
 */
class viewPhoto
{


	public function content()
	{

		global $db;

		$phid=0;
		if(isset($_GET['task_1'])&&(is_numeric($_GET['task_1']))){
			$phid=intval($_GET['task_1']);
		}else{
			@header("Location:/");
		}

		$aid=0;
		$uid=0;
		$albumname='';
		$username='';
		$photoname='';
		$image='';
		$created='';

		$q=$db->query("SELECT * FROM `photos` WHERE `phid`='".$phid."' ");
		if($db->num_rows($q)>0){
			while($a=$db->fetch_array($q)){
				$aid=$a['aid'];
				$uid=$a['uid'];
				$photoname=$a['name'];
				$image='/photos/'.$a['uid'].'/'.$a['filename'];
				$created = $a['created'];
			}
		}

		$qa=$db->query("SELECT * FROM `albums` WHERE `aid`='".$aid."' ");
		if($db->num_rows($qa)>0){
			while($a=$db->fetch_array($qa)){
				$albumname=$a['name'];
			}
		}
		$qu=$db->query("SELECT * FROM `users` WHERE `uid`='".$uid."' ");
		if($db->num_rows($qu)>0){
			while($a=$db->fetch_array($qu)){
				$username=$a['fname'];
			}
		}



		$result='
		<div id="viewphoto">
			<h1 class="viewphoto-album-name"><a href="/album/'.$aid.'/">'.$albumname.'</a></h1>
			<h2 class="viewphoto-username">'.$username.'</h2>

			<div id="view-photo-blk">

				<div class="p-photos-block">
					<img src="'.$image.'" alt="'.$photoname.'"/>
					<div class="p-photos-inf">
						<strong class="p-photos-name">'.$photoname.'</strong>
						<em class="p-photos-date">'.date('Y/m/d', $created).'</em>
					</div>
				</div>
				<h2>'.$photoname.'</h2>

			</div>
		</div>';


		return $result;

	}


}