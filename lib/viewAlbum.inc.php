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

	/**
	 * <h1> Contents for Albums</h1>
	 * Display an album photos
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

		$username='';
		$page=100;

		$q=$db->query("SELECT *,
						IFNULL( (SELECT `filename` FROM `photos` WHERE `photos`.`aid`=`albums`.`aid`
						ORDER BY `phid` DESC LIMIT 1), '') AS `photo`,
						IFNULL( (SELECT `fname` FROM `users` WHERE `users`.`uid`=`albums`.`uid`), '') AS `uname`

						FROM `albums` WHERE `aid`='".$aid."' ");

		if($db->num_rows($q)>0){
			while($a=$db->fetch_array($q)){
				$username=$a['uname'];
			}
		}

		$photos='';
		$q=$db->query("SELECT * FROM `photos` WHERE `aid`='".$aid."' ORDER BY `phid` DESC LIMIT ".$page);
		if($db->num_rows($q)>0){
			while($a=$db->fetch_array($q)){

				$photos.='
				<a href="/photo/'.$a['phid'].'/" class="aimgi">
					<img src="/photos/'.$a['uid'].'/t_'.$a['filename'].'" alt="'.$a['name'].'"/>
				</a>';

			}
		}

		include(__DIR__.'/../lib/comments.inc.php');
		$comm=new comments();
		$comm->aid=$aid;
		$comm->actions();
		$commentsForm=$comm->displayAddCommentForm();
		$commentsList=$comm->displayComments();

		include(__DIR__.'/../lib/ranking.inc.php');
		$vote=new votes();
		$vote->aid=$aid;
		$vote->actionSaveVote();
		$currentVote=$vote->displayVotes();

		$result='
		<div id="view-album">
			<h2 id="view-album-username"><img src="/assets/img/user_ico.png" alt="'.$username.'" />'.$username.'</h2>
			<div id="view-album-blk">
				'.$photos.'
			</div>
			<div id="show-votes">
				'.$currentVote.'
			</div>
			'.$commentsForm.'
			'.$commentsList.'
         </div>';


		return $result;

	}

}