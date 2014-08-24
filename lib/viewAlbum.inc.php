<?php

/**
 * <h1> View Album </h1>
 * View album page with all item.
 *
 * @version 0.4
 * @since 2014-08-22
 */
class viewAlbum
{

	private $backmsg='';

	/**
	 * <h1> Action on the page </h1>
	 *
	 */

	public function actions()
	{
		global $db, $ws;


		if(isset($_POST['action'])&&($_POST['action']=='addcomment')&&($_POST['captcha']==$_SESSION['captcha123']['code'])){

			$aid=intval($_GET['task_1']);
			$name=filter_var($_POST['name'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			$name=mb_substr($name, 0, 150, $GLOBALS['encoding']);
			$comment=filter_var($_POST['comment'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

			$db->query("INSERT INTO `comments` (`aid`,`uname`,`comment`,`cdate`)
				   VALUES ('".$aid."','".$name."','".$comment."','".time()."') ");

			$ws->updateCapcha();
		}

	}

	/**
	 * <h1> Contents for Albums</h1>
	 *
	 * @return string
	 */
	public function contents()
	{
		global $db;

		$aid=0;
		if(isset($_GET['task_1'])&&(is_numeric($_GET['task_1']))){
			$aid=intval($_GET['task_1']);
		}else{
			@header("Location:/");
		}

		$albumname='';
		$username='';
		$page=100;

		$q=$db->query("SELECT *,IFNULL( (SELECT `filename` FROM `photos` WHERE `photos`.`aid`=`albums`.`aid`
						ORDER BY `phid` DESC LIMIT 1), '') AS `photo` FROM `albums` WHERE `aid`='".$aid."' ");

		if($db->num_rows($q)>0){
			while($a=$db->fetch_array($q)){
				$uid=$a['uid'];
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
		<div id="view-album">

			<h2 id="view-album-username"><img src="/assets/img/user_ico.png" alt="'.$username.'" />'.$username.'</h2>';

		$q=$db->query("SELECT * FROM `photos` WHERE `aid`='".$aid."' ORDER BY `phid` DESC LIMIT ".$page);
		if($db->num_rows($q)>0){

			$result.='
				<div id="view-album-blk">';
			while($a=$db->fetch_array($q)){

				$image='/photos/'.$a['uid'].'/t_'.$a['filename'];

				$result.='
							<a href="/photo/'.$a['phid'].'/" class="aimgi">
									<img src="'.$image.'" alt="'.$a['name'].'"/>
								</a>';

			}
			$result.='
				</div>';
		}

		$result.='
			<div id="comment-block">
				<h2 id="view-album-name">'.$albumname.'</h2>
				<form method="post">
					<p><input type="text" name="name" id="comment-uname" value="" placeholder="Your Name..." required="required"/></p>
					<p><textarea name="comment" placeholder="Write a comment..." id="comment-text" required="required"></textarea></p>
					<p class="captcha">
						<label for="captcha">Enter the numbers from the image:</label>
						<img src="/assets/img/captcha/'.$_SESSION['captcha123']['img'].'" alt="Captcha"/>
						<input type="number" name="captcha" id="captcha" value="" required="required"/>
					</p>
					<p><button type="submit" id="comment-button">Comment</button></p>
					<input type="hidden" name="action" value="addcomment"/>
				</form>
			</div>
         </div>

		<div id="comment-list">';

		$q=$db->query("SELECT * FROM `comments` WHERE `aid`='".$aid."' ORDER BY `cdate` DESC");
		if($db->num_rows($q)>0){
			while($a=$db->fetch_array($q)){
				$name = $a['uname'];
				if(empty($a['uname']))$name = ' --- noname --- ';
				$result.='
					<div class="full-comment">
						<strong class="full-comment-name">'.$name.':</strong>
						<em class="full-comment-date">'.date('Y/m/d.', $a['cdate']).'</em>
						<p class="full-comment-comment">'.$a['comment'].'</p>
					</div>';
			}
		}

		$result.='
		</div>';


		return $result;

	}

}