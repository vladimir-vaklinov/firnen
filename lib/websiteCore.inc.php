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
		/**
		 * Here we trim all POST strings
		 */
		array_filter($_POST, 'trim_value');


		/**
		 * Here we escape the search string
		 */
		if(isset($_POST['search'])&&!empty($_POST['search']))
			$GLOBALS['searchstr']=filter_var($_POST['search'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

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
		if(isset($_GET['task_0'])&&(in_array($_GET['task_0'],array('gallery','photo'))) )
			$markAsActive[0]=$sel;
		if(isset($_GET['task_0'])&&(in_array($_GET['task_0'],array('albums','album'))) )
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
				<li id="nav-profile" '.$markAsActive[4].'><a href="/profile/">
					<img src="/assets/img/user_ico.png" alt="'.$_SESSION['user']['fname'].'" />'.$_SESSION['user']['fname'].'</a></li>
				<li id="nav-myalbums" '.$markAsActive[5].'><a href="/myalbums/">My Albums</a></li>
			';
		}else{
			$result.='
				<li id="nav-signup" '.$markAsActive[3].'><a href="/signup/">Register / Login</a></li>
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