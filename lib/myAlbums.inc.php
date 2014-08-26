<?php

/**
 * <h1> My Albums </h1>
 * User's albums and albums administration
 *
 * @since 2014-08-20
 * @version 0.3
 */
class myAlbums
{

	private $errormsg=null;
	public $showform='add';

	public function __construct()
	{

		if(!isset($_SESSION['user']['id']))
			@header("Location:/");

	}

	public function actions()
	{
		global $db, $ws;

		if(isset($_GET['action'])&&($_GET['action']=='delphoto')){

			$aid=0;
			$filename='deleteme';
			$q=$db->query("SELECT * FROM `photos`
							WHERE `uid`='".$_SESSION['user']['id']."' AND `phid`='".intval($_GET['id'])."' ");

			if($db->num_rows($q)>0){
				while($a=$db->fetch_array($q)){
					$filename=$a['filename'];
					$aid=$a['aid'];
				}
			}

			$inpath='./photos/'.$_SESSION['user']['id'];
			@unlink($inpath.'/'.$filename);
			@unlink($inpath.'/t_'.$filename);

			$db->query("DELETE FROM `photos` WHERE `uid`='".$_SESSION['user']['id']."' AND `phid`='".intval($_GET['id'])."'");
			@header("Location:/myalbums/".$aid."/");
		}

		if(isset($_POST['action'])&&($_POST['action']=='editfile')){

			if(!empty($_POST['name'])&&isset($_GET['id'])&&is_numeric($_GET['id'])){
				$name=filter_var($_POST['name'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

				$q=$db->query("UPDATE `photos` SET `name`='".$name."'
				                WHERE `uid`='".$_SESSION['user']['id']."' AND `phid`='".intval($_GET['id'])."'");
				if($q==false){
					$this->errormsg='<div class="errormsg">'.$db->error.'</div>';
				}else{
					$this->errormsg='<div class="backmsg">Photo updated successfully.</div>';
				}
			}
		}

		if(isset($_GET['action'])&&($_GET['action']=='editphoto')){
			$this->showform='editphoto';
		}


		if(isset($_POST['action'])&&($_POST['action']=='addfile')){

			$inpath='./photos/'.$_SESSION['user']['id'];

			if(!is_dir($inpath)){
				@mkdir($inpath, 0777);
				@chmod($inpath, 0777);
			}

			$fileout=$ws->uniqstr().'.jpg';
			$ws->makeThumbnail($_FILES['file']['tmp_name'], $inpath.'/'.$fileout, 784, 588, 1);
			if($ws!=false){
				$aid=intval($_GET['task_1']);
				$name=filter_var($_POST['name'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
				$name=mb_substr($name, 0, 150, $GLOBALS['encoding']);

				$db->query("INSERT INTO `photos`
				(`uid`,`aid`,`name`,`filename`,`created`)
					VALUES
				('".$_SESSION['user']['id']."','".$aid."','".$name."','".$fileout."','".time()."') ");

				$ws->makeThumbnail($inpath.'/'.$fileout, $inpath.'/t_'.$fileout, 204, 153, 1);
			}

		}

		if(isset($_GET['action'])&&($_GET['action']=='edit')){
			$this->showform='edit';
		}

		if(isset($_GET['action'])&&($_GET['action']=='del')&&isset($_GET['id'])&&is_numeric($_GET['id'])){
			$db->query("DELETE FROM `albums` WHERE `uid`='".$_SESSION['user']['id']."' AND `aid`='".intval($_GET['id'])."'");
			@header("Location:/myalbums/");
		}

		if(isset($_POST['action'])&&($_POST['action']=='addalbum')){

			if(!empty($_POST['name'])){
				$name=filter_var($_POST['name'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

				$q=$db->query("INSERT INTO `albums` (`uid`,`name`,`created`)
					VALUES ('".$_SESSION['user']['id']."','".$name."','".time()."') ");
				if($q==false){
					$this->errormsg='<div class="errormsg">'.$db->error.'</div>';
				}else{
					$this->errormsg='<div class="backmsg">Album created successfully.</div>';
				}
			}
		}

		if(isset($_POST['action'])&&($_POST['action']=='editalbum')){

			if(!empty($_POST['name'])&&isset($_GET['id'])&&is_numeric($_GET['id'])){
				$name=filter_var($_POST['name'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

				$q=$db->query("UPDATE `albums` SET `name`='".$name."'
				                WHERE `uid`='".$_SESSION['user']['id']."' AND `aid`='".intval($_GET['id'])."'");
				if($q==false){
					$this->errormsg='<div class="errormsg">'.$db->error.'</div>';
				}else{
					$this->errormsg='<div class="backmsg">Album updated successfully.</div>';
				}
			}
		}


	}

	public function htmlForms()
	{
		global $db;

		$result='';

		if($this->showform=='add'){

			$result.='
			<div id="new-album">
			'.$this->errormsg.'
				<div id="new-album-form">
					<h1>Add new album?</h1>
					<form method="post" action="/myalbums/" class="frm">
						<p><input type="text" name="name" value="" placeholder="Album name" required="required"
								maxlength="150" /></p>
						<p><button type="submit" name="submit">Add</button></p>
						<input type="hidden" name="action" value="addalbum"/>
					</form>
				</div>
			</div>';

		}

		if($this->showform=='edit'){

			if(!isset($_GET['id']))
				return '';

			$nameal='';
			$q=$db->query("SELECT * FROM `albums`
							WHERE `uid`='".$_SESSION['user']['id']."' AND `aid`='".intval($_GET['id'])."'
								ORDER BY `aid` DESC");

			if($db->num_rows($q)>0){
				while($a=$db->fetch_array($q)){
					$nameal=$a['name'];
				}
			}


			$result.='
			<div id="edit-album">
			'.$this->errormsg.'
				<div id="edit-album-form">
					<h2>Edit Album: <em>'.$nameal.'</em></h2>
					<form method="post" class="frm">
						<p><input type="text" name="name" value="'.$nameal.'" placeholder="New album name"
								required="required" maxlength="150"/></p>
						<p><button type="submit" name="submit">Edit now</button></p>
						<input type="hidden" name="action" value="editalbum"/>
					</form>
				</div>
			</div>';

		}

		if($this->showform=='addphoto'){

			if(!isset($_GET['task_1'])||!is_numeric($_GET['task_1']))
				return '';
			$result.='
			<div id="add-photo">
			'.$this->errormsg.'
				<div id="add-photo-form">
					<h2>Upload new photo</h2>
					<form method="post" action="/myalbums/'.intval($_GET['task_1']).'/" class="frm" enctype="multipart/form-data">
						<p><input type="file" name="file" value="" required="required"/>
							<input type="text" name="name" value="" placeholder="Photo name"
								required="required" maxlength="150" /></p>
						<p><button type="submit" name="submit">Upload now</button></p>
						<input type="hidden" name="action" value="addfile"/>
					</form>
				</div>
			</div>';

		}

		if($this->showform=='editphoto'){

			if(!isset($_GET['task_1'])||!is_numeric($_GET['task_1']))
				return '';

			if(!isset($_GET['id']))
				return '';

			$nameph='';
			$q=$db->query("SELECT * FROM `photos`
							WHERE `uid`='".$_SESSION['user']['id']."' AND `phid`='".intval($_GET['id'])."' ");

			if($db->num_rows($q)>0){
				while($a=$db->fetch_array($q)){
					$nameph=$a['name'];
				}
			}

			$result.='
			<div id="add-photo">
			'.$this->errormsg.'
				<div id="add-photo-form">
					<h2>Edit photo</h2>
					<form method="post" class="frm">
						<p><input type="text" name="name" value="'.$nameph.'" placeholder="Photo name"
								required="required" maxlength="150" /></p>
						<p><button type="submit" name="submit">Edit now</button></p>
						<input type="hidden" name="action" value="editfile"/>
					</form>
				</div>
			</div>';

		}


		return $result;
	}

	public function listAlbums()
	{
		global $db;

		$result='';

		$q=$db->query("SELECT *,
		             IFNULL(
		                (SELECT `filename` FROM `photos` WHERE `photos`.`aid`=`albums`.`aid`
		                    ORDER BY `phid` DESC LIMIT 1), '') AS `photo`

						FROM `albums` WHERE `uid`='".$_SESSION['user']['id']."' ORDER BY `aid` DESC");

		if($db->num_rows($q)>0){

			$result.='
			<div id="albums-manage-blk">
				<h2>Your albums:</h2>
				<div id="albums-manage-items">';

			while($a=$db->fetch_array($q)){

				$image='/assets/img/no-image.jpg';
				if(!empty($a['photo'])){
					$image='/photos/'.$_SESSION['user']['id'].'/t_'.$a['photo'];
				}
				$result.='
				<div class="album-manage-block">
					<a href="/myalbums/'.$a['aid'].'/" class="aimgi">
						<img src="'.$image.'" alt="'.$a['name'].'"/>
					</a>
					<div class="album-manage-btns">
						<strong>'.$a['name'].'</strong>
						<a href="?action=edit&amp;id='.$a['aid'].'">edit</a>
						<a href="?action=del&amp;id='.$a['aid'].'">del</a>
					</div>
				</div>

				';

			}

			$result.='
				</div>
			</div>';


		}


		return $result;
	}

	public function browseAlbum()
	{
		global $db;

		$result='';
		$name='';
		$aid=0;

		if(!isset($_GET['task_1'])||!is_numeric($_GET['task_1']))
			return '';

		$q=$db->query("SELECT * FROM `albums`
						WHERE `uid`='".$_SESSION['user']['id']."' AND `aid`='".intval($_GET['task_1'])."'
							ORDER BY `aid` DESC");

		if($db->num_rows($q)>0){
			while($a=$db->fetch_array($q)){
				$aid=$a['aid'];
				$name=$a['name'];
			}
		}


		$qph=$db->query("SELECT * FROM `photos`
			WHERE `uid`='".$_SESSION['user']['id']."' AND `aid`='".$aid."' ORDER BY `phid` DESC");

		if($db->num_rows($qph)>0){

			$result.='
			<div id="photos-manage-blk">
				<h2>Your photos in <em>'.$name.'</em></h2>
				<div id="photos-manage-items">';

			while($a=$db->fetch_array($qph)){

				$image='/photos/'.$_SESSION['user']['id'].'/t_'.$a['filename'];
				$imagebig='/photos/'.$_SESSION['user']['id'].'/'.$a['filename'];

				$result.='
					<div class="photos-manage-block">
						<a href="'.$imagebig.'" class="aimgi" target="_blank">
							<img src="'.$image.'" alt="'.$a['name'].'"/>
						</a>
						<div class="photos-manage-btns">
							<strong>'.$a['name'].'</strong>
							<a href="?action=editphoto&amp;id='.$a['phid'].'">edit</a>
							<a href="?action=delphoto&amp;id='.$a['phid'].'">del</a>
						</div>
					</div>';

			}

			$result.='
				</div>
			</div>';
		}

		return $result;

	}
}