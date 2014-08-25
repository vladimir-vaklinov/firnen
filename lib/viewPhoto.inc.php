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

		$q=$db->query("SELECT * FROM `photos` WHERE `phid`='".$phid."' ");
		if($db->num_rows($q)>0){
			while($a=$db->fetch_array($q)){
				$aid=$a['aid'];
				$uid=$a['uid'];
				$photoname=$a['name'];
				$image='/photos/'.$a['uid'].'/'.$a['filename'];
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


		$nextphoto='#';
		$prevphotos='#';
		$q=$db->query("SELECT * FROM `photos` WHERE `phid` > '".$phid."' ORDER BY `phid` ASC LIMIT 1 ");
		if($db->num_rows($q)>0){
			while($a=$db->fetch_array($q)){
				$nextphoto='/photo/'.$a['phid'].'/';
			}
		}
		$q=$db->query("SELECT * FROM `photos` WHERE `phid` < '".$phid."' ORDER BY `phid` DESC LIMIT 1 ");
		if($db->num_rows($q)>0){
			while($a=$db->fetch_array($q)){
				$prevphotos='/photo/'.$a['phid'].'/';
			}
		}


		$result='
		<div id="viewphoto">
			<h1 class="viewphoto-album-name"><a href="/album/'.$aid.'/">'.$albumname.'</a></h1>
			<h2 class="viewphoto-username"><img src="/assets/img/user_ico.png" alt="'.$username.'" />'.$username.'</h2>

			<div id="view-photo-blk">

				<div id="p-photos-block">
					<a href="'.$nextphoto.'#view-photo-blk" id="p-photos-block-arr-left"></a>
					<img src="'.$image.'" alt="'.$photoname.'"/>
					<a href="'.$prevphotos.'#view-photo-blk" id="p-photos-block-arr-right"></a>
				</div>

			</div>

			<h2 id="p-photo-name">'.$photoname.'</h2>

		</div>';


		return $result;

	}


}