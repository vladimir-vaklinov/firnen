<?php

/**
 * <h1> Admin settings </h1>
 * Website SEO settings
 */

class settingsAdmin{

	public function __construct(){
		global $db;

		if(isset($_POST['action']) && ($_POST['action'] == 'savesettings')){

			$site_title=filter_var($_POST['site_title'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$site_keywords=filter_var($_POST['site_keywords'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$site_description=filter_var($_POST['site_description'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$q=$db->query("SELECT * FROM `settings` ");
			if($db->num_rows($q)>0){
				$db->query("UPDATE `settings` SET
					`site_title`='".$site_title."',
					`site_keywords`='".$site_keywords."',
					`site_description`='".$site_description."'
					");
			}else{
				$db->query("INSERT INTO `settings` (`site_title`,`site_keywords`,`site_description`)
					VALUES ('".$site_title."','".$site_keywords."','".$site_description."') ");
			}
			print $db->error;


		}

	}

	/**
	 * <h1> contents </h1>
	 * Forms for website settings changes.
	 *
	 * @return string
	 */
	public function contents(){
		global $db;

		$site_title="";$site_keywords="";$site_description="";

		$q=$db->query("SELECT * FROM `settings` ");
		if($db->num_rows($q)>0){
			while($a=$db->fetch_array($q)){
				$site_title=$a['site_title'];
				$site_keywords=$a['site_keywords'];
				$site_description=$a['site_description'];
			}
		}

		$result='
		<div id="admin-settings" class="formblk">
			<form method="post">
				<p><label>Website title:</label>
					<input type="text" name="site_title" value="'.$site_title.'" required="required" class="inp"/></p>
				<p><label>Website keywords:</label>
					<input type="text" name="site_keywords" value="'.$site_keywords.'" required="required" class="inp"/></p>
				<p><label>Website description:</label>
					<textarea name="site_description" rows="3" cols="20" required="required" class="inp">'.$site_description.'</textarea></p>
				<p><button type="submit" class="btn">Save</button></p>
				<input type="hidden" name="action" value="savesettings" />
			</form>
		</div>';


		return $result;

	}


}