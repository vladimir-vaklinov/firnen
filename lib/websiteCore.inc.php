<?php

/**
 * <h1>Website Core </h1>
 * In this class we collect all simple
 * basic website functions.
 *
 * @version 0.2
 * @since 2014-08-20
 */
class websiteCore
{


	/**
	 * <h1> Website Initiation </h1>
	 * This method is called when the class is created.
	 *
	 * @version 0.2
	 * @since 2014-08-20
	 */

	public function __construct()
	{
		global $db;

		/**
		 * Here we trim all POST strings
		 */
		array_filter($_POST, 'trim_value');


		/**
		 * Here we escape the search string
		 */
		if(isset($_POST['search'])&&!empty($_POST['search']))
			$GLOBALS['searchstr']=filter_var($_POST['search'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);


		/**
		 * Create captcha code if not set
		 */
		if(!isset($_SESSION['captcha123'])||!isset($_SESSION['captcha123']['img'])){
			$_SESSION['captcha123']=array();
			$rnd=(integer)rand(1, 50)+rand(1, 50);
			$q=$db->query("SELECT * FROM `captcha` LIMIT ".$rnd.",1");
			if($db->num_rows($q)>0){
				while($a=$db->fetch_array($q)){
					$_SESSION['captcha123']['img']=$a['filename'];
					$_SESSION['captcha123']['code']=$a['code'];
				}
			}
		}

		/**
		 * Settings for the website
		 */
		$q=$db->query("SELECT * FROM `settings` ");
		if($db->num_rows($q)>0){
			while($a=$db->fetch_array($q)){
				$GLOBALS['pageTitle']=trim($a['site_title']);
				$GLOBALS['pageKeywords']=trim($a['site_keywords']);
				$GLOBALS['pageDescription']=trim($a['site_description']);
			}
		}

	}


	/**
	 * <h1>Main Navigation</h1>
	 * This method manages the main menu.
	 *
	 * @version 0.2
	 * @since 2014-08-20
	 * @return string
	 */

	public function mainNavigation()
	{

		$result='';
		$sel='class="active"';
		$markAsActive=array('', '', '', '', '', '');

		/**
		 * Checking if some of the menus is selected and it is - we
		 * mark it as selected
		 */
		if(isset($_GET['task_0'])&&(in_array($_GET['task_0'], array('gallery', 'photo'))))
			$markAsActive[0]=$sel;
		if(isset($_GET['task_0'])&&(in_array($_GET['task_0'], array('albums', 'album'))))
			$markAsActive[1]=$sel;
		if(isset($_GET['task_0'])&&($_GET['task_0']=='authors'))
			$markAsActive[2]=$sel;
		if(isset($_GET['task_0'])&&($_GET['task_0']=='signup'))
			$markAsActive[3]=$sel;
		if(isset($_GET['task_0'])&&($_GET['task_0']=='profile'))
			$markAsActive[4]=$sel;
		if(isset($_GET['task_0'])&&($_GET['task_0']=='myalbums'))
			$markAsActive[5]=$sel;

		$result.='
				<li id="nav-photos" '.$markAsActive[0].'><a href="/gallery/">Photos</a></li>
				<li id="nav-albums" '.$markAsActive[1].'><a href="/albums/">Albums</a></li>
				<li id="nav-authors" '.$markAsActive[2].'><a href="/authors/">By Author</a></li>';
		if(isset($_SESSION['user']['fname'])){
			$result.='
			<li id="profile-links">
			<ul>
				<li id="nav-profile" '.$markAsActive[4].'><a href="/profile/">
					<img src="/assets/img/user_ico.png" alt="'.$_SESSION['user']['fname'].'" />'.$_SESSION['user']['fname'].'</a></li>
				<li id="nav-myalbums" '.$markAsActive[5].'><a href="/myalbums/">My Albums</a></li>
			</ul>
			</li>
			';
		}else{
			$result.='
				<li id="nav-signup" '.$markAsActive[3].'><a href="/signup/">Sign Up</a></li>
			';
		}
		return $result;
	}


	/**
	 * <h1> Make Thumbnail </h1>
	 * This method is used to create thumbnails from
	 * bigger images.
	 *
	 *
	 * @param $file         String, InputFile, the input file
	 * @param $outfile      String, OutPutFile, the output file
	 * @param $w_in         Integer, OutPutWidth, final thumbnail width
	 * @param $h_in         Integer, OutPutHeight, final thumbnail height
	 * @param int $crop_inside Integer, if - 1 crops the image inside, 0 - crops outside
	 * @param int $bgR Integer, Red part of RGB background color
	 * @param int $bgG Integer, Green part of RGB background color
	 * @param int $bgB Integer, Blue part of RGB background color
	 * @return bool             Integer, Returns true, of false if some error occures
	 */

	public function makeThumbnail($file, $outfile, $w_in, $h_in, $crop_inside=0, $bgR=255, $bgG=255, $bgB=255)
	{

		list($in_width, $in_height, $type)=getimagesize($file);

		switch($type){
			case 1:
				$in_jpg=imagecreatefromgif($file);
				break;
			case 2:
				$in_jpg=imagecreatefromjpeg($file);
				break;
			case 3:
				$in_jpg=imagecreatefrompng($file);
				break;
			default:
				return false;
		}

		$image_new=imagecreatetruecolor($w_in, $h_in) or die("Проблеми с 'GD2'");
		if($type==2){
			for($y=0; $y<$h_in; $y++){
				for($x=0; $x<$w_in; $x++){
					imagesetpixel($image_new, $x, $y, imagecolorallocate($image_new, $bgR, $bgG, $bgB));
				}
			}
		}else{
			imagecolortransparent($image_new, '');
		}

		if($crop_inside==0){

			if(($in_width/$in_height)>($w_in/$h_in)){
				$h_in=$in_height/($in_width/$w_in);
			}else{
				$w_in=$in_width/($in_height/$h_in);
			}

			if(($in_width/$in_height)>=1.34){
				$pos_total=($w_in/2)-(($in_width/($in_height/$h_in))/2);
			}elseif((($in_width/$in_height)<1.333) and (($in_width/$in_height)>1) and ($h_in>100)){
				$pos_total=($w_in/2)-(($in_width/($in_height/$h_in))/2);
			}elseif((($in_width/$in_height)<1.333) and (($in_width/$in_height)>1) and ($h_in<100)){
				$pos_total=(($w_in/2)-(($in_width/($in_height/$h_in))/2))+1;
			}elseif(($in_width/$in_height)<1){
				$pos_total=($w_in/2)-(($in_width/($in_height/$h_in))/2);
			}elseif(($in_width/$in_height)==1){
				$pos_total=($w_in/2)-(($in_width/($in_height/$h_in))/2);
			}else{
				$pos_total=0;
			}

			imagecopyresampled($image_new, $in_jpg, $pos_total, 0, 0, 0, $w_in, $h_in, $in_width, $in_height);

		}else{
			if(($in_width/$in_height)>($w_in/$h_in)){
				$in_width=$in_height*($w_in/$h_in);
			}else{
				$in_height=$in_width/($w_in/$h_in);
			}

			imagecopyresampled($image_new, $in_jpg, 0, 0, 0, 0, $w_in, $h_in, $in_width, $in_height);
		}

		imagejpeg($image_new, $outfile, 100);
		imagedestroy($image_new);

		return true;
	}


	/**
	 * <h1> Unique string</h1>
	 * Creates unique string with specific length
	 *
	 * @param int $len Integer, the length of the string
	 * @return mixed|string    returns string
	 */

	public function uniqstr($len=15)
	{
		$ukey=crypt((microtime()+mt_rand(0, 100000)));
		$ukey=str_replace('$1$', '', $ukey);
		$ukey=strtolower(substr(preg_replace('/[^A-Za-z1-9]/', '', $ukey), 0, $len));
		return $ukey;
	}


	/**
	 * <h1> Update Captcha </h1>
	 * Generate new captcha code , and new image
	 *
	 * @return bool
	 */
	public function updateCapcha()
	{
		global $db;

		$_SESSION['captcha123']=array();
		$rnd=(integer)rand(1, 50)+rand(1, 50);
		$q=$db->query("SELECT * FROM `captcha` LIMIT ".$rnd.",1");
		if($db->num_rows($q)>0){
			while($a=$db->fetch_array($q)){
				$_SESSION['captcha123']['img']=$a['filename'];
				$_SESSION['captcha123']['code']=$a['code'];
			}
		}
		return true;
	}


	/**
	 * <h1> Album Preview </h1>
	 * Use this method to display the small album block / preview.
	 *
	 *
	 * @param int $searchID Integer ID of the album, if > 0 the rest of the parameters are not required
	 * @param int $aid Integer  ID of the album.
	 * @param int $uid Integer  ID of the User
	 * @param string $photo String If not empty - the first photo in the album will be shown.
	 * @param string $name String Name of the album
	 * @param int $created Integer Date - created
	 * @param int $likes Number of Likes
	 * @param int $dislikes Number of DisLikes
	 * @param int $comments Number of Comments
	 * @return string
	 */
	public function albumPreview($searchID=0, $aid=0, $uid=0, $photo='', $name='', $created=0,$likes=0,$dislikes=0,$comments=0)
	{


		if($searchID>0){

			global $db;

			$q=$db->query("SELECT *,
		             IFNULL(
		                (SELECT `filename` FROM `photos` WHERE `photos`.`aid`=`albums`.`aid`
		                    ORDER BY `phid` DESC LIMIT 1), '') AS `photo`
						FROM `albums` WHERE `aid`='".intval($searchID)."' ");

			if($db->num_rows($q)>0){
				while($a=$db->fetch_array($q)){
					$aid=$a['aid'];
					$uid=$a['uid'];
					$photo=$a['photo'];
					$name=$a['name'];
					$created=$a['created'];
				}
			}
		}

		$image='/assets/img/no-image.jpg';
		if(!empty($photo))
			$image='/photos/'.$uid.'/t_'.$photo;

		$result='
			<div class="album-block">
				<a href="/album/'.$aid.'/" class="aimgi">
					<img src="'.$image.'" alt="'.$name.' / '.$created.'"/>
				</a>
				<div class="albums-inf">
					<strong>'.$name.'</strong>
				</div>
				<div class="albums-likes">
					<strong class="likesnum">Likes: '.$likes.'</strong>
					<strong class="dislikesnum">Dislikes: '.$dislikes.'</strong>
					<strong class="commentsnum">Comments: '.$comments.'</strong>
				</div>
			</div>
			';

		return $result;

	}


	/**
	 * <h1> Simple Photo preview </h1>
	 * Use this method to display small photo block ( photo preview ) wherever it's needed.
	 * That's part of the real OOP.
	 *
	 * @param $searchID Integer If > 0 , the rest of the parameters will be ignored, and MySQL search will be performed
	 * @param $phid Integer ID of the photo
	 * @param $uid Integer User ID
	 * @param $filename String Filename
	 * @param $name String Name of the photo
	 * @param $created Integer Data - created, unix timestamp
	 * @return string
	 */
	public function photoPreview($searchID=0, $phid=0, $uid=0, $filename='', $name='', $created=0)
	{


		if($searchID>0){
			global $db;
			$q=$db->query("SELECT * FROM `photos` WHERE `phid`='".intval($searchID)."' ");
			if($db->num_rows($q)>0){
				while($a=$db->fetch_array($q)){
					$phid=$a['phid'];
					$uid=$a['uid'];
					$filename=$a['filename'];
					$name=$a['name'];
					$created=$a['created'];
				}
			}
		}

		$result='
			<div class="p-photos-block">
				<a href="/photo/'.$phid.'/" class="aimgi">
					<img src="/photos/'.$uid.'/t_'.$filename.'" alt="'.$name.'"/>
				</a>
				<div class="p-photos-inf">
					<strong class="p-photos-name">'.$name.'</strong>
					<strong class="p-photos-author">by Author (link)</strong>
					<em class="p-photos-date">'.date('Y/m/d', $created).'</em>
				</div>
			</div>';

		return $result;

	}

}

/**
 * This small function is used in condjuction with 'array_filter'
 *
 * @param $value String
 */
function trim_value(&$value)
{
	$value=trim($value);
}